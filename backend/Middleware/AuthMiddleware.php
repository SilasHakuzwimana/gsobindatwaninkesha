<?php

namespace Middleware;

use Services\AuthService;

class AuthMiddleware
{
  /**
   * Main check for authentication and roles
   */
  public static function check(bool $requireAdmin = false, array $allowedRoles = []): array
  {
    $token = self::extractToken();

    if (!$token) {
      self::fail('Authentication token is required.', 401);
    }

    $decoded = AuthService::verify($token);
    if (!$decoded) {
      self::clearAuthCookie();
      self::fail('Invalid or expired token.', 401);
    }

    $userData = (array)$decoded;

    // Role validation
    if (!empty($allowedRoles)) {
      if (!in_array($userData['role'] ?? '', $allowedRoles, true)) {
        self::fail('Access denied. Required role: ' . implode(', ', $allowedRoles), 403);
      }
    } elseif ($requireAdmin && (($userData['role'] ?? '') !== 'admin')) {
      self::fail('Admin access required.', 403);
    }

    // Refresh token
    self::rotateToken($userData);

    return $userData;
  }

  public static function requireAuth(): array
  {
    return self::check(false);
  }
  public static function requireAdmin(): array
  {
    return self::check(true);
  }
  public static function requireRole(string $role): array
  {
    return self::check(false, [$role]);
  }
  public static function requireAnyRole(array $roles): array
  {
    return self::check(false, $roles);
  }

  /**
   * Extract Bearer token from cookie or header
   */
  private static function extractToken(): ?string
  {
    $token = $_COOKIE['auth_token'] ?? null;

    if (!$token) {
      $headers = self::getRequestHeaders();
      if (!empty($headers['Authorization']) && preg_match('/Bearer\s+(\S+)/', $headers['Authorization'], $matches)) {
        $token = $matches[1];
      }
    }

    return $token;
  }

  /**
   * Centralized error handler
   */
  private static function fail(string $message, int $code): void
  {
    http_response_code($code);

    if (self::isApiRequest()) {
      header('Content-Type: application/json');
      echo json_encode([
        'status' => 'error',
        'code' => $code,
        'message' => $message,
      ]);
    } else {
      // Redirect web users to correct page
      switch ($code) {
        case 401:
          header('Location: /401');
          break;
        case 403:
          header('Location: /403');
          break;
        case 404:
          header('Location: /404');
          break;
        default:
          header('Location: /500');
          break;
      }
    }
    exit;
  }

  /**
   * Rotate/refresh token
   */
  private static function rotateToken(array $userData): void
  {
    $newToken = AuthService::generateToken([
      'user_id' => $userData['user_id'] ?? $userData['sub'] ?? null,
      'role'    => $userData['role'] ?? 'user',
      'email'   => $userData['email'] ?? null,
      'fullName'    => $userData['fullName'] ?? null,
    ]);

    setcookie('auth_token', $newToken, [
      'expires'  => time() + AuthService::getJwtExpirationTime(),
      'path'     => '/',
      'secure'   => true,
      'httponly' => true,
      'samesite' => 'Strict',
    ]);
  }

  public static function clearAuthCookie(): void
  {
    setcookie('auth_token', '', [
      'expires'  => time() - 3600,
      'path'     => '/',
      'secure'   => true,
      'httponly' => true,
      'samesite' => 'Strict',
    ]);
    unset($_COOKIE['auth_token']);
  }

  private static function getRequestHeaders(): array
  {
    if (function_exists('apache_request_headers')) {
      return apache_request_headers();
    }
    $headers = [];
    foreach ($_SERVER as $key => $value) {
      if (strpos($key, 'HTTP_') === 0) {
        $header = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
        $headers[$header] = $value;
      }
    }
    return $headers;
  }

  private static function isApiRequest(): bool
  {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($uri, '/api/') === 0) return true;

    $headers = self::getRequestHeaders();
    $accept = $headers['Accept'] ?? '';
    $content = $headers['Content-Type'] ?? ($_SERVER['CONTENT_TYPE'] ?? '');

    return (strpos($accept, 'application/json') !== false ||
      strpos($content, 'application/json') !== false);
  }
}