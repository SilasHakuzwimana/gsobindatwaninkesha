<?php

namespace Controllers;

use Models\Pathway;
use Services\UUIDService;
use Middleware\AuthMiddleware;

class PathwayController extends BaseController
{
  // -------------------------
  // Get all pathways
  // -------------------------
  public function index(): void
  {
    $pathways = Pathway::getAll();
    $data = [];

    foreach ($pathways as $p) {
      $data[] = [
        'pathway_id' => $p['pathway_id'] !== null ? UUIDService::fromBinary($p['pathway_id']) : null,
        'pathway_name' => $p['pathway_name'] ?? null,
        'description'  => $p['description'] ?? null,
        'created_at' => $p['created_at'] ?? null,
        'created_by' => $p['created_by'] !== null ? UUIDService::fromBinary($p['created_by']) : null
      ];
    }

    $this->json(['status' => 'success', 'data' => $data], 200);
  }

  // -------------------------
  // Create new pathway
  // -------------------------
  public function store(): void
  {
    // Step 1: Get logged-in user
    $currentUser = AuthMiddleware::requireAuth();
    $userId = $currentUser['user_id'] ?? $currentUser['sub'] ?? null;

    if (!$userId) {
      $this->json(['status' => 'error', 'message' => 'Logged in user not found'], 401);
      return;
    }

    // Step 2: Normalize user ID to binary
    try {
      if (strlen($userId) === 36) {
        $userIdBinary = UUIDService::toBinary($userId); // UUID string
      } elseif (strlen($userId) === 32 && ctype_xdigit($userId)) {
        $userIdBinary = hex2bin($userId); // hex string
      } elseif (strlen($userId) === 16) {
        $userIdBinary = $userId; // already binary
      } else {
        $this->json(['status' => 'error', 'message' => 'Invalid user_id format'], 400);
        return;
      }
    } catch (\Exception $e) {
      $this->json(['status' => 'error', 'message' => 'Invalid user_id: ' . $e->getMessage()], 400);
      return;
    }

    // Step 3: Ensure user exists in DB to satisfy FK
    if (!\Models\User::findById($userIdBinary)) {
      $this->json(['status' => 'error', 'message' => 'Logged in user does not exist in database'], 401);
      return;
    }

    // Step 4: Get request data
    $data = $this->getRequestData();
    if (empty($data['pathway_name'])) {
      $this->json(['status' => 'error', 'message' => 'Pathway name is required'], 400);
      return;
    }

    // Step 5: Assign FK created_by
    $data['created_by'] = $userIdBinary;

    // Step 6: Check for duplicate pathway
    $existingPathways = Pathway::getAll();
    foreach ($existingPathways as $existingPathway) {
      if ($existingPathway['pathway_name'] === $data['pathway_name']) {
        $this->json(['status' => 'error', 'message' => 'Pathway already exists'], 409);
        return;
      }
    }

    // Step 7: Generate pathway UUID if not provided
    if (empty($data['pathway_id'])) {
      $data['pathway_id'] = UUIDService::generateBinary();
    }

    // Step 8: Create pathway
    $pathway = new Pathway($data);
    $saved = $pathway->save();

    if ($saved) {
      $createdPathway = Pathway::getById($pathway->pathway_id);
      $source = $createdPathway ?? $pathway;

      $this->json([
        'status' => 'success',
        'message' => 'Pathway created successfully',
        'data' => [
          'pathway_id'   => isset($source->pathway_id) && strlen($source->pathway_id) === 16
            ? UUIDService::fromBinary($source->pathway_id)
            : null,
          'pathway_name' => $source->pathway_name ?? null,
          'description'  => $source->description ?? null,
          'created_at'   => $source->created_at ?? null,
          'created_by'   => isset($source->created_by) && strlen($source->created_by) === 16
            ? UUIDService::fromBinary($source->created_by)
            : null,
        ]
      ], 201);
      return;
    }

    $this->json(['status' => 'error', 'message' => 'Failed to create pathway'], 500);
  }

  public function show(string $id): void
  {
    try {
      $uuid = trim($id);  // remove spaces
      if (empty($uuid)) {
        $this->json(['status' => 'error', 'message' => 'Pathway ID is required'], 400);
        return;
      }

      try {
        $binaryId = UUIDService::toBinary($uuid);
      } catch (\Exception $e) {
        $this->json(['status' => 'error', 'message' => 'Invalid UUID format: ' . $e->getMessage()], 400);
        return;
      }

      $pathway = Pathway::getById($binaryId);
      if (!$pathway) {
        $this->json(['status' => 'error', 'message' => 'Pathway not found'], 404);
        return;
      }

      // Safely convert binary UUIDs to string only if they exist
      $responseData = [
        'pathway_id'   => isset($pathway['pathway_id']) ? UUIDService::fromBinary($pathway['pathway_id']) : null,
        'pathway_name' => $pathway['pathway_name'] ?? '',
        'description'  => $pathway['description'] ?? null,
        'created_by'   => isset($pathway['created_by']) ? UUIDService::fromBinary($pathway['created_by']) : null,
        'updated_by'   => isset($pathway['updated_by']) ? UUIDService::fromBinary($pathway['updated_by']) : null,
      ];

      $this->json([
        'status' => 'success',
        'data'   => $responseData
      ], 200);
    } catch (\Exception $e) {
      $this->json([
        'status'  => 'error',
        'message' => 'Error fetching pathway: ' . $e->getMessage()
      ], 500);
    }
  }

  // -------------------------
  // Update pathway
  // -------------------------
  public function update(string $id): void
  {
    // Step 1: Authenticate user
    $currentUser = AuthMiddleware::requireAuth();
    $userId = $currentUser['user_id'] ?? $currentUser['sub'] ?? null;

    if (!$userId) {
      $this->json(['status' => 'error', 'message' => 'Logged in user ID not found'], 401);
      return;
    }

    // Step 2: Validate and convert IDs
    $binaryId = UUIDService::toBinary($id ?? '');
    if ($binaryId === false) {
      $this->json(['status' => 'error', 'message' => 'Invalid pathway id'], 400);
      return;
    }

    // Step 3: Get request data
    $data = $this->getRequestData();

    // Step 4: Fetch existing pathway
    $existingPathway = Pathway::findById($binaryId);
    if (!$existingPathway) {
      $this->json(['status' => 'error', 'message' => 'Pathway not found'], 404);
      return;
    }

    // Step 5: Merge data (only update provided fields)
    $mergedData = [
      'pathway_name' => $data['pathway_name'] ?? $existingPathway['pathway_name'],
      'description'  => $data['description'] ?? $existingPathway['description'],
      'updated_by'   => UUIDService::toBinary($userId),
      // created_by remains unchanged
    ];

    // Step 6: Perform update
    $updated = Pathway::updatePathway($binaryId, $mergedData);

    // Step 7: Send response
    if ($updated) {
      $updatedPathway = Pathway::getById($binaryId);
      $this->json([
        'status' => 'success',
        'message' => 'Pathway updated successfully',
        'data' => [
          'pathway_id'   => $binaryId !== null ? UUIDService::fromBinary($binaryId) : null,
          'pathway_name' => $updatedPathway['pathway_name'],
          'description'  => $updatedPathway['description'],
          'created_at' => $updatedPathway['created_at'],
        ]
      ], 200);
    } else {
      $this->json(['status' => 'error', 'message' => 'Failed to update pathway'], 500);
    }
  }

  // -------------------------
  // Delete pathway
  // -------------------------
  public function destroy(string $id): void
  {
    $binaryId = UUIDService::toBinary($id ?? '');

    if ($binaryId === false) {
      $this->json(['status' => 'error', 'message' => 'Invalid pathway id'], 400);
      return;
    }

    $deleted = Pathway::deletePathway($binaryId);

    if ($deleted) {
      $this->json([
        'status' => 'success',
        'message' => 'Pathway deleted successfully',
        'data' => [
          'pathway_id' => $id
        ]
      ], 200);
    } else {
      $this->json(['status' => 'error', 'message' => 'Failed to delete pathway'], 500);
    }
  }

  // -------------------------
  // Helper: Get request data
  // -------------------------
  private function getRequestData(): array
  {
    $input = json_decode(file_get_contents('php://input'), true);
    return is_array($input) ? $input : ($_POST ?? []);
  }
}
