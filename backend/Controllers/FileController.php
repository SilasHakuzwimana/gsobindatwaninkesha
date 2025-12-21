<?php

namespace Controllers;

use Services\CloudinaryService;
use Middleware\AuthMiddleware;

class FileController
{
  private CloudinaryService $cloudinary;

  private int $maxSize = 10 * 1024 * 1024; // 10 MB

  public function __construct()
  {
    $this->cloudinary = new CloudinaryService();
  }

  /**
   * Upload a file
   * Expected POST fields:
   * - file: uploaded file
   * - type: "image" or "document"
   */
  public function upload()
  {
    // Only authenticated users can upload (admin)
    $user = AuthMiddleware::check(false);
    $user['is_admin'] = ($user['role'] === 'admin');
    if (!$user['is_admin']) {
      return $this->jsonResponse(false, 'Unauthorized', 401);
    }

    try {
      if (!isset($_FILES['file'])) {
        return $this->jsonResponse(false, 'No file uploaded', 400);
      }

      $file = $_FILES['file'];

      // Get extension and MIME type
      $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mimeType = finfo_file($finfo, $file['tmp_name']);
      finfo_close($finfo);

      // Define allowed extensions and MIME types
      $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
      $imageMimes = ['image/jpeg', 'image/png', 'image/gif'];

      $documentExtensions = ['pdf', 'doc', 'docx', 'xlsx', 'txt'];
      $documentMimes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain'
      ];

      // Determine file type
      if (in_array($ext, $imageExtensions) && in_array($mimeType, $imageMimes)) {
        $type = 'image';
      } elseif (in_array($ext, $documentExtensions) && in_array($mimeType, $documentMimes)) {
        $type = 'document';
      } else {
        return $this->jsonResponse(false, "Invalid file type: .$ext or MIME: $mimeType", 400);
      }

      if ($file['size'] > $this->maxSize) {
        return $this->jsonResponse(false, "File too large. Max size is " . ($this->maxSize / (1024 * 1024)) . "MB", 400);
      }

      // Automatic foldering: gsob_images/YYYY/MM/DD
      $folder = $type === 'image' ? 'gsob_images' : 'gsob_documents';
      $datePath = date('Y/m/d');
      $folder .= "/$datePath";

      $filePath = $file['tmp_name'];
      $fileName = pathinfo($file['name'], PATHINFO_FILENAME);

      $result = $this->cloudinary->upload($filePath, $folder, $fileName);

      return $this->jsonResponse(true, 'File uploaded successfully', 200, [
        'public_id' => $result['public_id'],
        'url' => $result['secure_url']
      ]);
    } catch (\Exception $e) {
      return $this->jsonResponse(false, $e->getMessage(), 500);
    }
  }

  public function uploadFileDirect(array $file): array
  {
    if (!isset($file['tmp_name'], $file['name'])) {
      throw new \Exception('Invalid file');
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $documentExtensions = ['pdf', 'doc', 'docx', 'xlsx', 'txt'];

    if (in_array($ext, $imageExtensions)) {
      $type = 'image';
    } elseif (in_array($ext, $documentExtensions)) {
      $type = 'document';
    } else {
      throw new \Exception("Invalid file type: .$ext");
    }

    $folder = $type === 'image' ? 'gsob_images' : 'gsob_documents';
    $folder .= '/' . date('Y/m/d');

    $fileName = pathinfo($file['name'], PATHINFO_FILENAME);

    return $this->cloudinary->upload($file['tmp_name'], $folder, $fileName);
  }


  /**
   * Delete a file
   * Expected POST field: public_id
   */
  public function delete()
  {
    //Only authenticated users can upload (admin)
    $user = AuthMiddleware::check(true);
    if (!$user || !$user['is_admin']) {
      return $this->jsonResponse(false, 'Unauthorized', 401);
    }

    try {
      $publicId = $_POST['public_id'] ?? null;
      if (!$publicId) {
        return $this->jsonResponse(false, 'public_id is required', 400);
      }

      $result = $this->cloudinary->delete($publicId);
      return $this->jsonResponse(true, 'File deleted successfully', 200, $result);
    } catch (\Exception $e) {
      return $this->jsonResponse(false, $e->getMessage(), 500);
    }
  }

  /**
   * Generate a Cloudinary URL for a given public ID
   * Expected GET parameter: public_id
   */
  public function getUrl()
  {
    //Only authenticated users can upload (admin)
    $user = AuthMiddleware::check(true);
    if (!$user || !$user['is_admin']) {
      return $this->jsonResponse(false, 'Unauthorized', 401);
    }

    try {
      $publicId = $_GET['public_id'] ?? null;

      if (!$publicId) {
        return $this->jsonResponse(false, 'public_id is required', 400);
      }

      $url = $this->cloudinary->getUrl($publicId);

      return $this->jsonResponse(true, 'URL generated successfully', 200, [
        'url' => $url
      ]);
    } catch (\Exception $e) {
      return $this->jsonResponse(false, $e->getMessage(), 500);
    }
  }

  /**
   * Standard JSON response
   */
  private function jsonResponse(bool $status, string $message, int $code = 200, array $data = [])
  {
    http_response_code($code);
    echo json_encode([
      'status' => $status,
      'message' => $message,
      'data' => $data
    ]);
    exit;
  }
}