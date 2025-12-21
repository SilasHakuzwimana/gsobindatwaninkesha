<?php

namespace Services;

use Config\Database;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Services\UUIDService;
use PDO;
use DateTime;
use DateTimeZone;

class AuthService
{
  private static array $config = [];

  public static function init(): void
  {
    if (!self::$config) {
      self::$config = require __DIR__ . '/../Config/env.php';
    }
  }

  /**
   * Step 1: Verify credentials and send OTP
   * Returns temporary user ID for OTP verification
   */
  public static function getJwtExpirationTime()
  {
    return self::$config['JWT_EXPIRATION_TIME'];
  }

  public static function login(string $email, string $password): ?string
  {
    self::init();
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) return null;

    $otpCode = random_int(100000, 999999);
    $now = new DateTime('now', new DateTimeZone('Africa/Kigali'));
    $createdAt = $now->format('Y-m-d H:i:s');
    $expiresAt = (clone $now)->modify('+5 minutes')->format('Y-m-d H:i:s'); // expiration time

    $otpId = UUIDService::generateBinary();
    $stmt = $pdo->prepare("INSERT INTO otps (otp_id, user_id, otp_code, expires_at, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$otpId, $user['user_id'], $otpCode, $expiresAt, $createdAt]);

    EmailService::sendOtpEmail($user['email'], $user['fullName'], $otpCode, 5);

    return bin2hex($user['user_id']);
  }

  /**
   * Step 2: Verify OTP and generate JWT
   */
  public static function verifyOTP(string $userIdHex, string $otpCode): ?string
  {
    self::init();
    $pdo = Database::getConnection();
    $userId = hex2bin($userIdHex);

    $now = new DateTime('now', new DateTimeZone('Africa/Kigali'));
    $stmt = $pdo->prepare("SELECT * FROM otps WHERE user_id = ? AND otp_code = ? AND verified = 0 AND expires_at >= ?");
    $stmt->execute([$userId, $otpCode, $now->format('Y-m-d H:i:s')]);
    $otp = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$otp) {
      return null;
    }

    // Mark OTP as verified
    $stmt = $pdo->prepare("UPDATE otps SET verified = 1 WHERE otp_id = ?");
    $stmt->execute([$otp['otp_id']]);

    // Generate JWT
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $issuedAt = time();
    $expire = $issuedAt + self::$config['JWT_EXPIRATION_TIME'];

    $payload = [
      'iss' => self::$config['JWT_ISSUER'],
      'aud' => self::$config['JWT_AUDIENCE'],
      'iat' => $issuedAt,
      'exp' => $expire,
      'sub' => bin2hex($user['user_id']),
      'email' => $user['email'],
      'role' => $user['role']
    ];

    return JWT::encode($payload, self::$config['JWT_SECRET_KEY'], 'HS256');
  }

  /**
   * Verify JWT token
   */
  public static function verify(string $token): ?object
  {
    self::init();
    try {
      return JWT::decode($token, new Key(self::$config['JWT_SECRET_KEY'], 'HS256'));
    } catch (\Exception $e) {
      return null;
    }
  }

  public static function generateToken(array $payload): string
  {
    self::init();
    $issuedAt = time();
    $expire = $issuedAt + self::$config['JWT_EXPIRATION_TIME'];

    $fullPayload = array_merge($payload, [
      'iss' => self::$config['JWT_ISSUER'],
      'aud' => self::$config['JWT_AUDIENCE'],
      'iat' => $issuedAt,
      'exp' => $expire
    ]);

    return JWT::encode($fullPayload, self::$config['JWT_SECRET_KEY'], 'HS256');
  }
  /**
   * Logout user (optional JWT blacklist)
   */
  public static function logout(string $token): bool
  {
    // Optional JWT blacklist implementation
    return true;
  }

  /**
   * Send password reset email
   */
  public static function sendPasswordReset(string $email): bool
  {
    self::init();
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
      return false;
    }

    $token = bin2hex(random_bytes(36));
    $now = new DateTime('now', new DateTimeZone('Africa/Kigali'));
    $createdAt = $now->format('Y-m-d H:i:s');
    $expiresAt = (clone $now)->modify('+1 hour')->format('Y-m-d H:i:s');

    $restId = UUIDService::generateBinary();
    $stmt = $pdo->prepare("INSERT INTO password_resets (reset_id, user_id, token, created_at, expires_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$restId, $user['user_id'], $token, $createdAt, $expiresAt]);

    $resetLink = "http://localhost:8000/reset-password?token=$token";

    EmailService::sendPasswordResetEmail($user['email'], $user['fullName'], 1, $resetLink);

    return true;
  }

  /**
   * Reset password using token
   */
  public static function resetPassword(string $token, string $newPassword): bool
  {
    self::init();
    $pdo = Database::getConnection();

    $now = (new DateTime('now', new DateTimeZone('Africa/Kigali')))->format('Y-m-d H:i:s');
    $usedAt = (new DateTime('now', new DateTimeZone('Africa/Kigali')))->format('Y-m-d H:i:s');

    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at >= ? AND used = 0");
    $stmt->execute([$token, $now]);
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reset) {
      return false; // token expired or already used
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $stmt->execute([$hashedPassword, $reset['user_id']]);

    $stmt = $pdo->prepare("UPDATE password_resets SET used =1, used_at = ? WHERE token = ?");
    $stmt->execute([$usedAt, $token]);

    return true;
  }
}