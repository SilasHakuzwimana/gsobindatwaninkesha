<?php

namespace Controllers;

use Models\SchoolDocument;
use Models\BaseModel;
use Services\CloudinaryService;
use Services\UUIDService;
use Middleware\AuthMiddleware;


error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', '0');


class SchoolDocumentController
{
  private CloudinaryService $cloudinary;
  private int $maxFileSize = 10 * 1024 * 1024; // 10MB

  private array $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
  private array $documentExtensions = ['pdf', 'doc', 'docx', 'xlsx', 'txt'];

  public function __construct()
  {
    $this->cloudinary = new CloudinaryService();
  }

  /**
   * Create / Upload document
   * Handles file upload + database insertion
   */
  public function create()
  {
    try {
      $user = AuthMiddleware::requireAdmin(); // ensures user exists
      $input = json_decode(file_get_contents('php://input'), true);

      $title = trim($input['title'] ?? '');
      $filePath = $input['file_path'] ?? null;
      $category = $input['category'] ?? 'other';
      $description = $input['description'] ?? null;

      if (!$title) return $this->jsonResponse(false, 'Title is required', 400);
      if (!$filePath) return $this->jsonResponse(false, 'File path is required', 400);

      $uploadedBy = isset($user['user_id']) ? hex2bin($user['user_id']) : null;

      $doc = new SchoolDocument([
        'title'       => $title,
        'file_path'   => $filePath,
        'category'    => $category,
        'uploaded_by' => $uploadedBy,
        'description' => $description
      ]);

      $savedDoc = $doc->save(); // save() returns the saved record

      if (!$savedDoc) {
        return $this->jsonResponse(false, 'Failed to save document', 500, []);
      }

      return $this->jsonResponse(true, 'Document saved successfully', 200, $savedDoc ?? []);
    } catch (\Exception $e) {
      return $this->jsonResponse(false, 'create() error: ' . $e->getMessage(), 500);
    }
  }

  /**
   * Read all documents
   */
  public function all()
  {
    try {
      $docs = SchoolDocument::all() ?: [];
      foreach ($docs as &$doc) {
        // Convert binary UUIDs to hex
        $doc['document_id'] = bin2hex($doc['document_id']);
        $doc['uploaded_by'] = $doc['uploaded_by'] ? bin2hex($doc['uploaded_by']) : null;

        // Get uploader name
        $doc['uploaded_by_name'] = $this->getUserName($doc['uploaded_by']);
      }

      $this->jsonResponse(true, 'Documents fetched', 200, $docs);
    } catch (\Exception $e) {
      $this->jsonResponse(false, $e->getMessage(), 500);
    }
  }

  /**
   * Read single document
   */
  public function find(string $id)
  {
    $binaryId = UUIDService::toBinary($id);
    $doc = SchoolDocument::findById($binaryId);
    if (!$doc) {
      return $this->jsonResponse(false, 'Document not found', 404);
    }

    $doc['document_id'] = bin2hex($doc['document_id']);
    $doc['uploaded_by'] = $doc['uploaded_by'] ? bin2hex($doc['uploaded_by']) : null;
    $doc['uploaded_by_name'] = $this->getUserName($doc['uploaded_by']);

    $this->jsonResponse(true, 'Document fetched', 200, $doc);
  }

  /**
   * Update document metadata or replace file
   * Accepts optional file upload for replacing file
   */
  public function update(string $id)
  {
    $user = AuthMiddleware::check(false);
    $user['is_admin'] = ($user['role'] ?? '') === 'admin';
    if (!$user['is_admin']) {
      return $this->jsonResponse(false, 'Unauthorized', 401);
    }

    $binaryId = UUIDService::toBinary($id);
    $doc = SchoolDocument::findById($binaryId);
    if (!$doc) {
      return $this->jsonResponse(false, 'Document not found', 404);
    }

    $data = $_POST; // metadata like title, description, category

    // Handle file replacement
    if (isset($_FILES['file'])) {
      $file = $_FILES['file'];
      $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

      $type = in_array($ext, $this->imageExtensions) ? 'image' : (in_array($ext, $this->documentExtensions) ? 'document' : null);

      if (!$type) {
        return $this->jsonResponse(false, "Invalid file type: .$ext", 400);
      }

      if ($file['size'] > $this->maxFileSize) {
        return $this->jsonResponse(false, 'File too large', 400);
      }

      $folder = $type === 'image' ? 'gsob_images' : 'gsob_documents';
      $folder .= '/' . date('Y/m/d');

      $fileName = pathinfo($file['name'], PATHINFO_FILENAME);

      // Delete old file from Cloudinary
      $publicId = $this->extractPublicId($doc['file_path']);
      if ($publicId) {
        $this->cloudinary->delete($publicId);
      }

      $result = $this->cloudinary->upload($file['tmp_name'], $folder, $fileName);
      $data['file_path'] = $result['secure_url'];
      $data['category'] = $type;
    }

    $data['updated_at'] = date('Y-m-d H:i:s');

    $allowedFields = ['title', 'file_path', 'category', 'description', 'uploaded_by', 'updated_at'];
    $updateData = array_intersect_key($data, array_flip($allowedFields));

    $success = SchoolDocument::update($binaryId, $updateData);

    if ($success) {
      $this->jsonResponse(true, 'Document updated', 200, SchoolDocument::findById($binaryId));
    } else {
      $this->jsonResponse(false, 'No changes made', 400);
    }
  }

  /**
   * Delete document
   */
  public function delete(string $id)
  {
    $user = AuthMiddleware::check(false);
    $user['is_admin'] = ($user['role'] ?? '') === 'admin';
    if (!$user['is_admin']) {
      return $this->jsonResponse(false, 'Unauthorized', 401);
    }

    $binaryId = UUIDService::toBinary($id);
    $doc = SchoolDocument::findById($binaryId);
    if (!$doc) {
      return $this->jsonResponse(false, 'Document not found', 404);
    }

    try {
      $publicId = $this->extractPublicId($doc['file_path']);
      if ($publicId) {
        $this->cloudinary->delete($publicId);
      }

      SchoolDocument::delete($binaryId);
      $this->jsonResponse(true, 'Document deleted');
    } catch (\Exception $e) {
      $this->jsonResponse(false, $e->getMessage(), 500);
    }
  }

  /**
   * Helper: Extract Cloudinary public_id from URL
   */
  private function extractPublicId(string $url): ?string
  {
    $parsed = parse_url($url);
    $path = $parsed['path'] ?? '';
    $path = ltrim($path, '/');

    $ext = pathinfo($path, PATHINFO_EXTENSION);
    return $ext ? substr($path, 0, -strlen($ext) - 1) : null;
  }

  /**
   * Optional: get uploader's name
   */
  private function getUserName(?string $hexUserId)
  {
    if (!$hexUserId) return null;

    $userIdBin = hex2bin($hexUserId);

    try {
      $stmt = BaseModel::db()->prepare("SELECT fullName FROM users WHERE user_id = ?");
      $stmt->execute([$userIdBin]);
      $row = $stmt->fetch(\PDO::FETCH_ASSOC);
      return $row['fullName'] ?? null;
    } catch (\Exception $e) {
      return null;
    }
  }

  /**
   * Standard JSON response
   */
  private function jsonResponse(bool $status, string $message, int $code = 200, ?array $data = null)
  {
    http_response_code($code);
    header('Content-Type: application/json');

    ob_clean(); // clear any previous output
    echo json_encode([
      'status' => $status,
      'message' => $message,
      'data' => $data ?? []
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
  }
}