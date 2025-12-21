<?php

namespace Controllers;

use Models\User;
use Models\UserActivity;
use Services\AuthService;
use Services\EmailService;
use PDO;
use Config\Database;
use Controllers\BaseController;

class AuthController extends BaseController
{
  /**
   * Register a new user
   */
  public function register()
  {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['email'], $data['password'], $data['fullName'])) {
      return $this->json([
        'success' => false,
        'error' => 'Missing required fields'
      ], 400);
    }

    // Check if email already exists
    if (User::findByEmail($data['email'])) {
      return $this->json([
        'success' => false,
        'error' => 'Email already registered'
      ], 409);
    }

    // Validate phone if provided
    if (isset($data['phone'])) {
      if (!preg_match('/^\+\d{7,15}$/', $data['phone'])) {
        return $this->json([
          'success' => false,
          'error' => 'Invalid phone number'
        ], 400);
      }
    }

    try {
      // Create user (password hashed inside User::create)
      $userData = User::create($data);

      // Log registration
      $activity = new UserActivity([
        'user_id' => $userData['uuid'], // assuming User::create returns 'uuid'
        'activity_type' => 'register',
        'activity_description' => 'New user registered with email ' . $userData['email'],
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
      ]);
      $activity->save();


      // Send welcome email
      EmailService::sendWelcomeEmail(
        $userData['email'],
        explode(' ', $userData['fullName'])[0],
        $userData['fullName'],
        $userData['username'] ?? '',
        $userData['roleName'] ?? 'Student',
        $data['password']
      );

      return $this->json([
        'success' => true,
        'message' => 'User registered successfully',
        'user' => $userData,
        'redirect_url' => 'http://localhost:8000/login'
      ], 201);
    } catch (\Exception $e) {
      return $this->json([
        'success' => false,
        'error' => 'Registration failed',
        'details' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Step 1: Login using email & password → send OTP
   */
  public function login()
  {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($email) || empty($password)) {
      return $this->json(['error' => 'Email and password are required'], 400);
    }

    $userIdHex = AuthService::login($email, $password);

    if (!$userIdHex) {
      return $this->json(['error' => 'Invalid email or password'], 401);
    }


    // Log login attempt
    $activity = new UserActivity([
      'user_id' => $userIdHex,
      'activity_type' => 'login_attempt',
      'activity_description' => 'User attempted login with email ' . $email,
      'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
      'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
    ]);
    $activity->save();


    return $this->json([
      'success' => true,
      'message' => 'Credentials verified. OTP sent to your email.',
      'user_id' => $userIdHex,
      'redirect_url' => 'http://localhost:8000/verify-otp'
    ]);
  }

  /**
   * Step 2: Verify OTP → Generate JWT token
   */
  public function verifyOtp()
  {
    $data = json_decode(file_get_contents("php://input"), true);
    $userId = $data['user_id'] ?? '';
    $otpCode = $data['otp_code'] ?? '';

    if (empty($userId) || empty($otpCode)) {
      return $this->json(['error' => 'User ID and OTP code are required'], 400);
    }

    $jwtToken = AuthService::verifyOTP($userId, $otpCode);

    if (!$jwtToken) {

      // Log failed OTP
      $activity = new UserActivity([
        'user_id' => $userId,
        'activity_type' => 'otp_failed',
        'activity_description' => 'User failed OTP verification',
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
      ]);
      $activity->save();


      return $this->json(['error' => 'Invalid or expired OTP'], 401);
    }


    // Log successful OTP
    $activity = new UserActivity([
      'user_id' => $userId,
      'activity_type' => 'login',
      'activity_description' => 'User successfully logged in via OTP',
      'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
      'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
    ]);
    $activity->save();


    // Set secure, HTTP-only cookie
    setcookie('auth_token', $jwtToken, [
      'expires' => time() + AuthService::getJwtExpirationTime(),
      'path' => '/',
      'domain' => '',
      'secure' => true,
      'httponly' => true,
      'samesite' => 'Strict'
    ]);

    return $this->json([
      'success' => true,
      'message' => 'Login successful',
      'redirect_url' => 'http://localhost:8000/admin/dashboard'
    ]);
  }

  /**
   * Logout user → clear cookie
   */
  public function logout()
  {
    $userId = $this->currentUserId(); // get user ID first

    // Log the logout activity ONLY if user ID exists
    if ($userId) {
      try {
        $activity = new UserActivity([
          'user_id' => $userId,
          'activity_type' => 'logout',
          'activity_description' => 'User logged out',
          'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
          'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);
        $activity->save();
      } catch (\Exception $e) {
        // Don't block logout if logging fails
        error_log("Failed to log logout activity: " . $e->getMessage());
      }
    }

    // Clear the cookie
    setcookie('auth_token', '', [
      'expires' => time() - 3600,
      'path' => '/',
      'domain' => '',
      'secure' => true,
      'httponly' => true,
      'samesite' => 'Strict'
    ]);

    // Return JSON response
    return $this->json([
      'success' => true,
      'message' => 'Logout successful'
    ]);
  }

  /**
   * Send password reset link/email
   */
  public function forgotPassword()
  {
    $pdo = Database::getConnection();
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? '';

    if (empty($email)) {
      return $this->json([
        'success' => false,
        'error' => 'Email is required'
      ], 400);
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      if (AuthService::sendPasswordReset($email)) {
        return $this->json([
          'success' => true,
          'message' => 'Password reset email sent'
        ], 201);
      } else {
        return $this->json([
          'success' => false,
          'error' => 'Failed to send password reset email'
        ], 500);
      }
    }

    return $this->json([
      'success' => false,
      'error' => 'User with that email is not found'
    ], 404);
  }

  /**
   * Reset password using token
   */
  public function resetPassword()
  {
    $data = json_decode(file_get_contents("php://input"), true);
    $token = $data['token'] ?? '';
    $newPassword = $data['new_password'] ?? '';

    if (empty($token) || empty($newPassword)) {
      return $this->json([
        'success' => false,
        'error' => 'Token and password are required'
      ], 400);
    }

    if (AuthService::resetPassword($token, $newPassword)) {
      return $this->json([
        'success' => true,
        'message' => 'Password has been reset successfully'
      ], 201);
    }

    return $this->json([
      'success' => false,
      'error' => 'Invalid or expired token'
    ], 400);
  }

  public function logoutUser()
  {
    $userId = $this->currentUserId(); // get user ID first

    // Log the logout activity ONLY if user ID exists
    if ($userId) {
      try {
        $activity = new UserActivity([
          'user_id' => $userId,
          'activity_type' => 'logout',
          'activity_description' => 'User logged out',
          'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
          'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);
        $activity->save();
      } catch (\Exception $e) {
        // Don't block logout if logging fails
        error_log("Failed to log logout activity: " . $e->getMessage());
      }
    }

    // Clear auth cookie
    setcookie('auth_token', '', [
      'expires' => time() - 3600,
      'path' => '/',
      'domain' => '',
      'secure' => true,
      'httponly' => true,
      'samesite' => 'Strict'
    ]);

    // Clear session safely
    if (session_status() === PHP_SESSION_NONE) {
      @session_start();
    }
    if (session_status() === PHP_SESSION_ACTIVE) {
      session_unset();
      session_destroy();
    }

    // Return JSON response
    $this->json([
      'success'  => true,
      'message'  => 'Logout successful',
      'redirect' => 'http://localhost:8000/login'
    ]);
  }
}