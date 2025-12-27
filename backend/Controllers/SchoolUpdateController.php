<?php

namespace Controllers;

use Models\SchoolUpdate;
use Controllers\BaseController;
use Services\CloudinaryService;
use Middleware\AuthMiddleware;

class SchoolUpdateController extends BaseController
{
  private SchoolUpdate $schoolUpdateModel;

  private CloudinaryService $cloudinary;
  private int $maxFileSize = 10 * 1024 * 1024; // 10MB

  private array $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
  private array $documentExtensions = ['pdf', 'doc', 'docx', 'xlsx', 'txt'];

  public function __construct()
  {
    // No need for $conn, BaseModel uses static DB connection
    $this->schoolUpdateModel = new SchoolUpdate([]);
    $this->cloudinary = new CloudinaryService();
  }

  /** 🔹 Get all school updates */
  public function getAll(): void
  {
    $updates = SchoolUpdate::getAllUpdates();

    header('Content-Type: application/json');
    echo json_encode([
      'status' => 'success',
      'count' => count($updates),
      'data' => $updates
    ]);
  }

  /** 🔹 Get a single school update by ID */
  public function getById(string $id): void
  {
    $update = SchoolUpdate::findById(hex2bin($id));

    header('Content-Type: application/json');
    if ($update) {
      echo json_encode([
        'status' => 'success',
        'data' => $update
      ]);
    } else {
      http_response_code(404);
      echo json_encode([
        'status' => 'error',
        'message' => 'School update not found.'
      ]);
    }
  }

  /** 🔹 Create a new school update */
  public function create(): void
  {
    $user = AuthMiddleware::requireAdmin();
    $data = json_decode(file_get_contents('php://input'), true);

    $uploadedBy = isset($user['user_id']) ? hex2bin($user['user_id']) : null;

    if (empty($data['title']) || empty($data['description'])) {
      http_response_code(400);
      echo json_encode([
        'status' => 'error',
        'message' => 'Title and description are required.'
      ]);
      return;
    }

    // -------------------------------
    // Cloudinary Upload (optional)
    // -------------------------------
    $imagePath = null;

    if (!empty($_FILES['image']['tmp_name'])) {
      try {
        $upload = $this->cloudinary->upload(
          $_FILES['image']['tmp_name'],
          'gsob_images'
        );

        $imagePath = $upload['secure_url'] ?? null;
      } catch (\Exception $e) {
        http_response_code(400);
        echo json_encode([
          'status' => 'error',
          'message' => $e->getMessage()
        ]);
        return;
      }
    }


    date_default_timezone_set('Africa/Kigali');
    $timestamp = date('Y-m-d H:i:s');

    $update = new SchoolUpdate([
      'title' => $data['title'],
      'description' => $data['description'],
      'image_path' => $imagePath ?? null,
      'posted_by' => $uploadedBy,
    ]);

    $saved = $update->save();

    header('Content-Type: application/json');
    if ($saved) {
      echo json_encode([
        'status' => 'success',
        'message' => 'School update created successfully.',
        'data' => [
          'update_id' => $update->update_id,
          'title' => $update->title,
          'description' => $update->description,
          'image_path' => $update->image_path,
          'uploaded_by' => $uploadedBy,
          'updated_by' => $uploadedBy,
          'created_at' => $timestamp,
          'updated_at' => $timestamp
        ]
      ]);
    } else {
      http_response_code(500);
      echo json_encode([
        'status' => 'error',
        'message' => 'Failed to create school update.'
      ]);
    }
  }

  /** 🔹 Update an existing school update */
  public function update(string $id): void
  {
    $user = AuthMiddleware::requireAdmin();
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_POST['title'])) {
      $data['title'] = $_POST['title'];
    }

    if (isset($_POST['description'])) {
      $data['description'] = $_POST['description'];
    }

    if (empty($data)) {
      http_response_code(400);
      echo json_encode([
        'status' => 'error',
        'message' => 'No data provided.'
      ]);
      return;
    }

    // -------------------------------
    // Optional image replacement
    // -------------------------------
    if (!empty($_FILES['image']['tmp_name'])) {
      try {
        $upload = $this->cloudinary->upload(
          $_FILES['image']['tmp_name'],
          'gsob_images'
        );

        $data['image_path'] = $upload['secure_url'];
      } catch (\Exception $e) {
        http_response_code(400);
        echo json_encode([
          'status' => 'error',
          'message' => $e->getMessage()
        ]);
        return;
      }
    }

    $userId = isset($user['user_id']) ? hex2bin($user['user_id']) : null;
    $data['updated_by'] = $userId;

    date_default_timezone_set('Africa/Kigali');
    $data['updated_at'] = date('Y-m-d H:i:s');

    try {
      $updated = SchoolUpdate::update(hex2bin($id), $data);
      header('Content-Type: application/json');

      if ($updated) {
        echo json_encode([
          'status' => 'success',
          'message' => 'School update updated successfully.'
        ]);
      } else {
        http_response_code(404);
        echo json_encode([
          'status' => 'error',
          'message' => 'School update not found or no changes made.'
        ]);
      }
    } catch (\PDOException $e) {
      http_response_code(500);
      echo json_encode([
        'status' => 'error',
        'message' => 'Update failed: ' . $e->getMessage()
      ]);
    }
  }

  /** 🔹 Delete a school update */
  public function delete(string $id): void
  {
    try {
      $deleted = SchoolUpdate::delete(hex2bin($id));
      header('Content-Type: application/json');

      if ($deleted) {
        echo json_encode([
          'status' => 'success',
          'message' => 'School update deleted successfully.'
        ]);
      } else {
        http_response_code(404);
        echo json_encode([
          'status' => 'error',
          'message' => 'School update not found.'
        ]);
      }
    } catch (\PDOException $e) {
      http_response_code(500);
      echo json_encode([
        'status' => 'error',
        'message' => 'Delete failed: ' . $e->getMessage()
      ]);
    }
  }

  public function handlePost(): void
  {
    // Check if it's an update
    $updateId = $_POST['update_id'] ?? null;

    if ($updateId) {
      $this->update($updateId);
    } else {
      $this->create();
    }
  }
}
