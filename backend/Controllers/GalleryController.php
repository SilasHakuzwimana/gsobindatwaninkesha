<?php

namespace Controllers;

use Models\Gallery;
use Middleware\AuthMiddleware;
use Services\CloudinaryService;

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
      $this->jsonResponse(false, 'Invalid gallery type', 400);
    }
  }

  public function __construct()
  {
    $this->cloudinary = new CloudinaryService();
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
      'uploaded_at' => date('Y-m-d H:i:s'),
      'updated_by' => null,
      'updated_at' => null
    ];

    $gallery = new Gallery($data, $table);
    $saved = $gallery->save();

    return $this->jsonResponse(true, 'Saved successfully', 200, $saved);
  }

  /**
   * Update a gallery item
   */
  public function update(string $table, string $id)
  {
    $user = $this->checkAdmin();
    $user['is_admin'] = ($user['role'] ?? '') === 'admin';
    if (!$user['is_admin']) return $this->jsonResponse(false, 'Unauthorized', 401);

    new Gallery([], $table);
    $binaryId = hex2bin($id);
    $galleryItem = Gallery::findById($binaryId);
    if (!$galleryItem) return $this->jsonResponse(false, 'Item not found', 404);

    $input = $_POST;
    $input['updated_by'] = isset($user['id']) ? hex2bin($user['id']) : null;
    $input['updated_at'] = date('Y-m-d H:i:s');

    $filePath = $galleryItem['file_path'] ?? null;

    // Handle file replacement
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
      $file = $_FILES['file'];
      $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, $this->imageExtensions)) {
        return $this->jsonResponse(false, "Invalid file type: .$ext", 400);
      }
      if ($file['size'] > $this->maxFileSize) {
        return $this->jsonResponse(false, "File too large", 400);
      }

      // Delete old file
      if ($filePath) {
        $publicId = $this->extractPublicId($filePath);
        if ($publicId) $this->cloudinary->delete($publicId);
      }

      $folder = $table . '/' . date('Y/m/d');
      $fileName = pathinfo($file['name'], PATHINFO_FILENAME);
      $result = $this->cloudinary->upload($file['tmp_name'], $folder, $fileName);
      $filePath = $result['secure_url'] ?? $filePath;
    }

    $gallery = new Gallery(array_merge($galleryItem, $input, ['file_path' => $filePath]), $table);
    $updated = $gallery->save();

    return $this->jsonResponse(true, 'Updated successfully', 200, $updated);
  }

  /**
   * Delete a gallery item
   */
  public function delete(string $table, string $id)
  {
    $user = $this->checkAdmin();
    $user['is_admin'] = ($user['role'] ?? '') === 'admin';
    if (!$user['is_admin']) return $this->jsonResponse(false, 'Unauthorized', 401);

    new Gallery([], $table);
    $binaryId = hex2bin($id);
    $item = Gallery::findById($binaryId);
    if (!$item) return $this->jsonResponse(false, 'Item not found', 404);

    if ($item['file_path']) {
      $publicId = $this->extractPublicId($item['file_path']);
      if ($publicId) $this->cloudinary->delete($publicId);
    }

    if (Gallery::delete($binaryId)) {
      return $this->jsonResponse(true, 'Deleted successfully');
    }

    return $this->jsonResponse(false, 'Failed to delete', 500);
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
    if (ob_get_length()) ob_clean();
    echo json_encode([
      'status' => $status,
      'message' => $message,
      'data' => $data ?? []
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
  }
}
