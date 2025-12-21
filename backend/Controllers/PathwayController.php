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

    //Get logged in user
    $currentUser = AuthMiddleware::requireAuth();

    $currentUser = AuthMiddleware::requireAuth();
    $userId = $currentUser['user_id'] ?? $currentUser['sub'] ?? null;

    if (!$userId) {
      $this->json(['status' => 'error', 'message' => 'Logged in user ID not found'], 500);
      return;
    }

    $data = $this->getRequestData();

    if (empty($data['pathway_name'])) {
      $this->json(['status' => 'error', 'message' => 'Pathway name is required'], 400);
      return;
    }

    // Assign logged-in user's binary UUID
    $data['created_by'] = UUIDService::toBinary($currentUser['user_id']);

    //check if pathway already exists
    $existingPathways = Pathway::getAll();
    foreach ($existingPathways as $existingPathway) {
      if ($existingPathway['pathway_name'] === $data['pathway_name']) {
        $this->json(['status' => 'error', 'message' => 'Pathway already exists'], 409);
        return;
      }
    }

    $pathway = new Pathway($data);
    $saved = $pathway->save();



    if ($saved) {
      $this->json(['status' => 'success', 'message' => 'Pathway created successfully'], 201);
    } else {
      $this->json(['status' => 'error', 'message' => 'Failed to create pathway'], 500);
    }
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
      $this->json([
        'status' => 'success',
        'message' => 'Pathway updated successfully',
        'data' => [
          'pathway_id'   => $binaryId !== null ? UUIDService::fromBinary($binaryId) : null,
          'pathway_name' => $mergedData['pathway_name'],
          'description'  => $mergedData['description']
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
      $this->json(['status' => 'success', 'message' => 'Pathway deleted successfully'], 200);
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