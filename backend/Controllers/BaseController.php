<?php

namespace Controllers;

use Config\Database;
use Models\BaseModel;
use Services\AuthService;

class BaseController
{
  public function __construct()
  {
    $pdo = Database::getConnection();
    BaseModel::setConnection($pdo);
  }

  protected function json($data, int $status = 200)
  {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
  }

  /**
   * Get the currently authenticated user's ID from the JWT cookie.
   * Returns the user UUID in HEX format or null if not logged in.
   */
  protected function currentUserId(): ?string
  {
    if (!isset($_COOKIE['auth_token'])) {
      return null;
    }

    $token = $_COOKIE['auth_token'];

    $payload = AuthService::verify($token); // returns stdClass
    if (!$payload || !isset($payload->user_id)) {
      return null;
    }

    return $payload->user_id; // ✅ object property
  }
}