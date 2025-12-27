<?php

namespace Middleware;

use Services\AuthService;

class AuthMiddleware
{
    /* =====================================================
     | PUBLIC ENTRY POINTS
     ===================================================== */

  /**
   * Require any authenticated user
   */
  public static function requireAuth(): array
  {
    return self::check();
  }

  /**
   * Require admin role
   */
  public static function requireAdmin(): array
  {
    return self::check(true);
  }

  /**
   * Require a specific role
   */
  public static function requireRole(string $role): array
  {
    return self::check(false, [$role]);
  }

  /**
   * Require any role from list
   */
  public static function requireAnyRole(array $roles): array
  {
    return self::check(false, $roles);
  }

  /* =====================================================
     | CORE AUTH CHECK
     ===================================================== */

  public static function check(bool $requireAdmin = false, array $allowedRoles = []): array
  {
    $token = self::extractToken();

    if (!$token) {
      self::fail('Authentication required.', 401);
    }

    $decoded = AuthService::verify($token);

    if (!$decoded) {
      self::clearAuthCookie();
      self::fail('Invalid or expired token.', 401);
    }

    $user = (array) $decoded;

    /* ---------- Role Validation ---------- */

    if (!empty($allowedRoles)) {
      if (!in_array($user['role'] ?? '', $allowedRoles, true)) {
        self::fail('Access denied.', 403);
      }
    } elseif ($requireAdmin && (($user['role'] ?? '') !== 'admin')) {
      self::fail('Admin access required.', 403);
    }

    /* ---------- Token Rotation ---------- */
    self::rotateToken($user);

    return $user;
  }

    /* =====================================================
     | TOKEN HANDLING
     ===================================================== */

  /**
   * Extract token from cookie or Authorization header
   */
  private static function extractToken(): ?string
  {
    if (!empty($_COOKIE['auth_token'])) {
      return $_COOKIE['auth_token'];
    }

    $headers = self::getRequestHeaders();

    if (
      !empty($headers['Authorization']) &&
      preg_match('/Bearer\s+(\S+)/', $headers['Authorization'], $matches)
    ) {
      return $matches[1];
    }

    return null;
  }

  /**
   * Rotate JWT (sliding expiration)
   */
  private static function rotateToken(array $user): void
  {
    $newToken = AuthService::generateToken([
      'sub'      => $user['sub'] ?? $user['user_id'] ?? null,
      'role'     => $user['role'] ?? 'user',
      'email'    => $user['email'] ?? null,
      'fullName' => $user['fullName'] ?? null,
    ]);

    setcookie(
      'auth_token',
      $newToken,
      self::cookieOptions(time() + AuthService::getJwtExpirationTime())
    );
  }

  /**
   * Clear authentication cookie
   */
  public static function clearAuthCookie(): void
  {
    setcookie(
      'auth_token',
      '',
      self::cookieOptions(time() - 3600)
    );

    unset($_COOKIE['auth_token']);
  }

  /* =====================================================
     | COOKIE CONFIG (SINGLE SOURCE OF TRUTH)
     ===================================================== */

  private static function cookieOptions(int $expires): array
  {
    return [
      'expires'  => $expires,
      'path'     => '/',
      'secure'   => !empty($_SERVER['HTTPS']), // auto-prod safe
      'httponly' => true,
      'samesite' => 'Lax',
    ];
  }

  /* =====================================================
     | ERROR HANDLING
     ===================================================== */

  private static function fail(string $message, int $status): void
  {
    http_response_code($status);

    if (self::isApiRequest()) {
      header('Content-Type: application/json');
      echo json_encode([
        'status'  => 'error',
        'code'    => $status,
        'message' => $message,
      ]);
    } else {
      switch ($status) {
        case 401:
          header('Location: /login');
          break;
        case 403:
          header('Location: /403');
          break;
        default:
          header('Location: /500');
      }
    }

    exit;
  }

    /* =====================================================
     | REQUEST HELPERS
     ===================================================== */

  /**
   * Detect API request
   */
  private static function isApiRequest(): bool
  {
    if (str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/api/')) {
      return true;
    }

    $headers = self::getRequestHeaders();

    return (
      str_contains($headers['Accept'] ?? '', 'application/json') ||
      str_contains($headers['Content-Type'] ?? '', 'application/json')
    );
  }

  /**
   * Cross-server request headers
   */
  private static function getRequestHeaders(): array
  {
    if (function_exists('apache_request_headers')) {
      return apache_request_headers();
    }

    $headers = [];

    foreach ($_SERVER as $key => $value) {
      if (str_starts_with($key, 'HTTP_')) {
        $header = str_replace(
          ' ',
          '-',
          ucwords(strtolower(str_replace('_', ' ', substr($key, 5))))
        );
        $headers[$header] = $value;
      }
    }

    return $headers;
  }
}
