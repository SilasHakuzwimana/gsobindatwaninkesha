<?php

namespace Controllers;

use Models\Gallery;
use Middleware\AuthMiddleware;
use Services\CloudinaryService;
use Config\Database;
use PDO;

class GalleryController
{
  private CloudinaryService $cloudinary;
  private int $maxFileSize = 10 * 1024 * 1024; // 10MB
  private array $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];


  private function checkAdmin(): array
  {
    $user = AuthMiddleware::check(false);
    if (($user['role'] ?? '') !== 'admin') {
      $this->jsonResponse(false, 'Unauthorized', 401);
    }
    return $user;
  }

  private function validateTable(string $table): void
  {
    $allowed = [
      'school_gallery',
      'school_alumni_gallery',
      'extra_curricular_activities_gallery',
      'school_updates_gallery'
    ];

    if (!in_array($table, $allowed)) {
      throw new \InvalidArgumentException("Invalid gallery type: $table");
    }
  }

  public function __construct()
  {
    $this->cloudinary = new CloudinaryService();
  }

  public function list(string $table)
  {
    $stmt = Database::getConnection()->prepare("SELECT * FROM `$table`");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode([
      'status' => true,
      'message' => 'Gallery fetched',
      'data' => $data
    ]);
    exit; // ⚠️ must exit after echo to prevent 200 + null
  }



  /**
   * Fetch all galleries grouped by table/section
   */
  public function all()
  {
    try {
      $data = Gallery::allSections();
      return $this->jsonResponse(true, 'Fetched', 200, $data);
    } catch (\Throwable $e) {
      return $this->jsonResponse(false, 'Server error: ' . $e->getMessage(), 500);
    }
  }

  /**
   * Find a single gallery item
   */
  public function find(string $table, string $id)
  {
    new Gallery([], $table);
    $item = Gallery::findById(hex2bin($id));
    if (!$item) return $this->jsonResponse(false, 'Item not found', 404);
    return $this->jsonResponse(true, 'Fetched', 200, $item);
  }

  /**
   * Create new gallery item
   */
  public function create(string $table)
  {
    $this->validateTable($table);
    $user = $this->checkAdmin();

    $title = trim($_POST['title'] ?? '');
    $category = $_POST['category'] ?? null;

    if (!$title) return $this->jsonResponse(false, 'Title is required', 400);
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
      return $this->jsonResponse(false, 'File upload required', 400);
    }

    $file = $_FILES['file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $this->imageExtensions)) {
      return $this->jsonResponse(false, "Invalid file type: .$ext", 400);
    }

    $folder = $table . '/' . date('Y/m/d');
    $fileName = pathinfo($file['name'], PATHINFO_FILENAME);
    $result = $this->cloudinary->upload($file['tmp_name'], $folder, $fileName);
    $filePath = $result['secure_url'] ?? null;

    $data = [
      'title' => $title,
      'file_path' => $filePath,
      'category' => $category,
      'uploaded_by' => isset($user['id']) ? hex2bin($user['id']) : null,
      'updated_by' => null,
      'updated_at' => date('Y-m-d H:i:s'),
    ];

    // Handle timestamps correctly per table
    if ($table === 'school_updates_gallery') {
      $data['created_at'] = date('Y-m-d H:i:s');
    } else {
      $data['uploaded_at'] = date('Y-m-d H:i:s');
    }

    $gallery = new Gallery($data, $table);
    $saved = $gallery->save();

    return $this->jsonResponse(true, 'Saved successfully', 200, $saved);
  }

  /**
   * Update a gallery item
   */
  public function update(string $table, string $id)
  {
    $this->validateTable($table);
    $user = $this->checkAdmin();

    new Gallery([], $table);
    $binaryId = hex2bin($id);

    $galleryItem = Gallery::findById($binaryId);
    if (!$galleryItem) {
      return $this->jsonResponse(false, 'Item not found', 404);
    }

    // READ JSON BODY
    $raw = file_get_contents("php://input");
    $input = json_decode($raw, true) ?? [];

    $input['updated_by'] = isset($user['id']) ? hex2bin($user['id']) : null;
    $input['updated_at'] = date('Y-m-d H:i:s');

    $gallery = new Gallery(array_merge($galleryItem, $input), $table);
    $updated = $gallery->save();

    return $this->jsonResponse(true, 'Updated successfully', 200, $updated);
  }


  /**
   * Delete a gallery item
   */
  public function delete(string $table, string $id)
  {
    $this->validateTable($table);
    $user = $this->checkAdmin();

    new Gallery([], $table);
    $binaryId = hex2bin($id);

    $item = Gallery::findById($binaryId);
    if (!$item) {
      return $this->jsonResponse(false, 'Item not found', 404);
    }

    if ($item['file_path']) {
      $publicId = $this->extractPublicId($item['file_path']);
      if ($publicId) $this->cloudinary->delete($publicId);
    }

    Gallery::delete($binaryId);
    return $this->jsonResponse(true, 'Deleted successfully');
  }

  /**
   * Extract Cloudinary public ID from URL
   */
  private function extractPublicId(string $url): ?string
  {
    $parsed = parse_url($url);
    $path = ltrim($parsed['path'] ?? '', '/');
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    return $ext ? substr($path, 0, -strlen($ext) - 1) : null;
  }

  /**
   * Standard JSON response
   */
  private function jsonResponse(bool $status, string $message, int $code = 200, ?array $data = null)
  {
    http_response_code($code);
    header('Content-Type: application/json');
    while (ob_get_level()) {
      ob_end_clean();
    }
    echo json_encode([
      'status' => $status,
      'message' => $message,
      'data' => $data ?? []
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
  }
}
