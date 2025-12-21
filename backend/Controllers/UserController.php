<?php

namespace Controllers;

use Models\User;
use Services\AuthService;

class UserController extends BaseController
{
  // -------------------------
  // Get all users (Admin only)
  // -------------------------
  public function index()
  {
    $user = $this->authenticateAdmin();
    if (!$user) return;

    $users = User::all();
    $totalUsers = count($users);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
      'status' => 'success',
      'total' => $totalUsers,
      'data' => $users
    ]);
  }


  // -------------------------
  // Get a single user by ID
  // -------------------------
  public function getUserById(string $id)
  {
    $user = $this->authenticateAdmin();
    if (!$user) return;

    $foundUser = User::getById($id);
    if (!$foundUser) {
      return $this->json(['status' => 'error', 'message' => 'User not found'], 404);
    }

    return $this->json(['status' => 'success', 'data' => $foundUser]);
  }

  // -------------------------
  // Create a new user
  // -------------------------
  public function createUser()
  {
    $user = $this->authenticateAdmin();
    if (!$user) return;

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['full_name'], $data['email'], $data['password'])) {
      return $this->json(['status' => 'error', 'message' => 'Missing required fields'], 400);
    }

    $newUser = new User($data);

    try {
      $createdUser = $newUser->create($data);
      return $this->json([
        'status' => 'User registered successfully!',
        'data' => $createdUser
      ], 201);
    } catch (\Exception $e) {
      return $this->json([
        'status' => 'error',
        'message' => $e->getMessage()
      ], 400);
    }
  }

  // -------------------------
  // Update existing user
  // -------------------------
  public function updateUser(string $id)
  {
    $user = $this->authenticateAdmin();
    if (!$user) return;

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
      return $this->json(['status' => 'error', 'message' => 'No data provided'], 400);
    }

    $existingUser = User::getById($id);
    if (!$existingUser) {
      return $this->json(['status' => 'error', 'message' => 'User not found'], 404);
    }

    $userModel = new User($existingUser);
    foreach ($data as $key => $value) {
      if (property_exists($userModel, $key)) {
        $userModel->$key = $key === 'password' ? password_hash($value, PASSWORD_BCRYPT) : $value;
      }
    }
    $userModel->updated_at = (new \DateTime('now', new \DateTimeZone('Africa/Kigali')))->format('Y-m-d H:i:s');

    if ($userModel->patch($id, $data)) {
      $updatedUser = User::getById($id);
      return $this->json(['status' => 'User updated successfully!', 'data' => $updatedUser]);
    }

    return $this->json(['status' => 'error', 'message' => 'Failed to update user'], 500);
  }

  // -------------------------
  // Delete a user
  // -------------------------
  public function deleteUser(string $id)
  {
    $user = $this->authenticateAdmin();
    if (!$user) return;

    $existingUser = User::getById($id);
    if (!$existingUser) {
      return $this->json(['status' => 'error', 'message' => 'User not found'], 404);
    }

    if (User::deleteById($id)) {
      return $this->json(['status' => 'User deleted successfully!', 'data' => $existingUser]);
    }

    return $this->json(['status' => 'error', 'message' => 'Failed to delete user'], 500);
  }

  // -------------------------
  // Helper: Authenticate Admin
  // -------------------------
  private function authenticateAdmin(): ?array
  {
    //Get token from cookie or header
    $token = $_COOKIE['auth_token'] ?? null;

    if (!$token) {
      $authHeader = $_SERVER['HTTP_AUTHORIZATION']
        ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
        ?? '';
      if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
      }
    }

    //No token found
    if (!$token) {
      $this->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
      return null;
    }

    //Verify token
    $decoded = AuthService::verify($token);

    if (!$decoded || $decoded->role !== 'admin') {
      $this->json(['status' => 'error', 'message' => 'Forbidden'], 403);
      return null;
    }

    return (array)$decoded;
  }
}