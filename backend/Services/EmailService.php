<?php

namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use DateTime;

class EmailService
{
  private static ?\PHPMailer\PHPMailer\PHPMailer $mailer = null;
  private static array $config = [];

  /**
   * Initialize PHPMailer
   */
  public static function init(): void
  {
    if (empty(self::$config)) {
      self::$config = require __DIR__ . '/../config/env.php';
    }
    if (!self::$mailer) {
      self::$mailer = new \PHPMailer\PHPMailer\PHPMailer(true);
      self::$mailer->isSMTP();
      self::$mailer->Host = self::$config['EMAIL_HOST'];
      self::$mailer->SMTPAuth = true;
      self::$mailer->Username = self::$config['EMAIL_USERNAME'];
      self::$mailer->Password = self::$config['EMAIL_PASSWORD'];
      self::$mailer->SMTPSecure = 'tls';
      self::$mailer->Port = self::$config['EMAIL_PORT'];
      self::$mailer->setFrom(self::$config['EMAIL_FROM_ADDRESS'], self::$config['EMAIL_FROM_NAME']);
    }
  }

  /**
   * Get base email template matching email.service.js styling
   */
  private static function getBaseTemplate(string $content, string $preheader = ''): string
  {
    $currentYear = date('Y');
    $baseUrl = getenv('APP_URL') ?: 'http://localhost:8000/';

    return "<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title>GSOB Communication</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body { 
      margin: 0; 
      padding: 0; 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
      background-color: #f8f9fa;
    }
    
    .email-container { 
      max-width: 600px; 
      margin: 0 auto; 
      background-color: #ffffff; 
      border-radius: 8px; 
      overflow: hidden; 
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
    }
    
    .header { 
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
      padding: 30px 40px; 
      text-align: center; 
    }
    
    .header h1 { 
      margin: 0; 
      color: #ffffff; 
      font-size: 28px; 
      font-weight: 600; 
      letter-spacing: -0.5px; 
    }
    
    .header p { 
      margin: 8px 0 0 0; 
      color: #e8eaed; 
      font-size: 14px; 
      opacity: 0.9; 
    }
    
    .content { 
      padding: 40px; 
    }
    
    .content p { 
      margin: 0 0 24px 0; 
      color: #2c3e50; 
      font-size: 16px; 
      line-height: 1.6; 
    }
    
    .otp-box {
      background-color: #f8f9fa;
      border: 2px solid #e9ecef;
      border-radius: 8px;
      padding: 24px;
      text-align: center;
      margin: 32px 0;
    }
    
    .otp-label {
      margin: 0 0 8px 0;
      color: #6c757d;
      font-size: 14px;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .otp-code {
      font-family: 'Courier New', monospace;
      font-size: 32px;
      font-weight: 700;
      color: #495057;
      letter-spacing: 4px;
      background-color: #ffffff;
      border: 1px solid #dee2e6;
      border-radius: 6px;
      padding: 16px;
      display: inline-block;
      margin: 8px 0;
    }
    
    .otp-expiry {
      margin: 8px 0 0 0;
      color: #dc3545;
      font-size: 14px;
      font-weight: 500;
    }
    
    .alert-box {
      background-color: #fff3cd;
      border-left: 4px solid #ffc107;
      padding: 16px;
      margin: 24px 0;
      border-radius: 4px;
    }
    
    .alert-box h4 {
      margin: 0 0 8px 0;
      color: #856404;
      font-size: 14px;
      font-weight: 600;
    }
    
    .alert-box p {
      margin: 0;
      color: #856404;
      font-size: 13px;
      line-height: 1.4;
    }
    
    .info-box {
      background-color: #d1ecf1;
      border-left: 4px solid #17a2b8;
      padding: 16px;
      margin: 24px 0;
      border-radius: 4px;
    }
    
    .info-box h4 {
      margin: 0 0 8px 0;
      color: #0c5460;
      font-size: 14px;
      font-weight: 600;
    }
    
    .info-box p, .info-box ul {
      margin: 0;
      color: #0c5460;
      font-size: 13px;
      line-height: 1.4;
    }
    
    .info-box ul {
      padding-left: 16px;
    }
    
    .info-box li {
      margin-bottom: 4px;
    }
    
    .success-box {
      background-color: #d4edda;
      border-left: 4px solid #28a745;
      padding: 16px;
      margin: 24px 0;
      border-radius: 4px;
    }
    
    .success-box h4 {
      margin: 0 0 8px 0;
      color: #155724;
      font-size: 14px;
      font-weight: 600;
    }
    
    .success-box ol {
      margin: 0;
      padding-left: 16px;
      color: #155724;
      font-size: 13px;
      line-height: 1.5;
    }
    
    .success-box li {
      margin-bottom: 4px;
    }
    
    .btn-container {
      text-align: center;
      margin: 32px 0;
    }
    
    .btn {
      display: inline-block;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #ffffff;
      text-decoration: none;
      padding: 16px 32px;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 600;
      text-align: center;
      transition: all 0.3s ease;
    }
    
    .btn-success {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    
    .btn-danger {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }
    
    .link-box {
      margin: 24px 0;
      color: #6c757d;
      font-size: 14px;
      line-height: 1.5;
      text-align: center;
    }
    
    .link-box span {
      word-break: break-all;
      color: #495057;
      font-family: monospace;
      background-color: #f8f9fa;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
    }
    
    .details-box {
      background-color: #f8f9fa;
      border: 1px solid #e9ecef;
      border-radius: 6px;
      padding: 20px;
      margin: 24px 0;
    }
    
    .details-box h4 {
      margin: 0 0 12px 0;
      color: #495057;
      font-size: 16px;
      font-weight: 600;
    }
    
    .details-table {
      width: 100%;
      border-collapse: collapse;
    }
    
    .details-table td {
      padding: 8px 0;
      font-size: 14px;
    }
    
    .details-table td:first-child {
      color: #6c757d;
      font-weight: 500;
      width: 30%;
    }
    
    .details-table td:last-child {
      color: #495057;
      font-family: monospace;
    }
    
    .divider {
      border: none;
      border-top: 1px solid #e9ecef;
      margin: 24px 0;
    }
    
    .footer {
      background-color: #f8f9fa;
      padding: 24px 40px;
      border-top: 1px solid #e9ecef;
    }
    
    .footer p {
      margin: 0;
      color: #6c757d;
      font-size: 12px;
      text-align: center;
      line-height: 1.4;
    }
    
    .footer .copyright {
      margin: 12px 0 0 0;
      color: #adb5bd;
      font-size: 11px;
    }
    
    @media only screen and (max-width: 600px) {
      .content { padding: 30px 20px; }
      .header { padding: 25px 20px; }
      .footer { padding: 20px; }
      .otp-code { font-size: 28px; letter-spacing: 2px; }
    }
  </style>
</head>
<body style='margin: 0; padding: 0; font-family: \"Segoe UI\", Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa;'>
  <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
    
    <!-- Header -->
    <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 40px; text-align: center;'>
      <h1 style='margin: 0; color: #ffffff; font-size: 28px; font-weight: 600; letter-spacing: -0.5px;'>
        GSOB
      </h1>
      <p style='margin: 8px 0 0 0; color: #e8eaed; font-size: 14px; opacity: 0.9;'>
        Indatwa n'inkesha
      </p>
    </div>
    
    <!-- Content -->
    <div style='padding: 40px;'>
      {$content}
    </div>
    
    <!-- Footer -->
    <div style='background-color: #f8f9fa; padding: 24px 40px; border-top: 1px solid #e9ecef;'>
      <p style='margin: 0; color: #6c757d; font-size: 12px; text-align: center; line-height: 1.4;'>
        This is an automated security message from GSOB.<br>
        Please do not reply to this email. For assistance, contact our support team.
      </p>
      <p style='margin: 12px 0 0 0; color: #adb5bd; font-size: 11px; text-align: center;'>
        © {$currentYear} GSOB Indatwa n'inkesha. All rights reserved.
      </p>
    </div>
  </div>
</body>
</html>";
  }

  /**
   * Send generic email
   */
  public static function sendEmail(string $to, string $subject, string $body): bool
  {
    self::init();
    try {
      self::$mailer->clearAllRecipients();
      self::$mailer->addAddress($to);
      self::$mailer->Subject = $subject;
      self::$mailer->isHTML(true);
      self::$mailer->Body    = $body;
      self::$mailer->AltBody = strip_tags($body);
      self::$mailer->CharSet = 'UTF-8';

      return self::$mailer->send();
    } catch (Exception $e) {
      error_log("Email sending failed: {$e->getMessage()}");
      return false;
    }
  }

  /**
   * Send OTP Email
   */
  public static function sendOtpEmail(string $to, string $full_name, string $otp, int $expiryMinutes = 5): bool
  {
    $subject = "GSOB Account Verification - OTP Required";
    $greeting = $full_name ? "Dear {$full_name}" : 'Dear User';

    $content = "
      <p style='margin: 0 0 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.5;'>{$greeting},</p>
      
      <p style='margin: 0 0 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.6;'>
        We received a request to verify your GSOB account. Please use the following One-Time Password (OTP) to complete your authentication:
      </p>
      
      <div style='background-color: #f8f9fa; border: 2px solid #e9ecef; border-radius: 8px; padding: 24px; text-align: center; margin: 32px 0;'>
        <p style='margin: 0 0 8px 0; color: #6c757d; font-size: 14px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;'>
          Your Verification Code
        </p>
        <div style='font-family: \"Courier New\", monospace; font-size: 32px; font-weight: 700; color: #495057; letter-spacing: 4px; background-color: #ffffff; border: 1px solid #dee2e6; border-radius: 6px; padding: 16px; display: inline-block; margin: 8px 0;'>
          {$otp}
        </div>
        <p style='margin: 8px 0 0 0; color: #dc3545; font-size: 14px; font-weight: 500;'>
          ⏰ Expires in {$expiryMinutes} minutes
        </p>
      </div>
      
      <div style='background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 16px; margin: 24px 0; border-radius: 4px;'>
        <h4 style='margin: 0 0 8px 0; color: #856404; font-size: 14px; font-weight: 600;'>
          🔒 Security Notice
        </h4>
        <p style='margin: 0; color: #856404; font-size: 13px; line-height: 1.4;'>
          If you did not request this verification, please ignore this email or contact our support team immediately.
        </p>
      </div>
      
      <p style='margin: 24px 0 0 0; color: #6c757d; font-size: 14px; line-height: 1.5;'>
        Best regards,<br><strong>The GSOB Security Team</strong>
      </p>
    ";

    $body = self::getBaseTemplate($content);
    return self::sendEmail($to, $subject, $body);
  }

  /**
   * Send Password Reset Email
   */
  public static function sendPasswordResetEmail(string $to, string $full_name, int $expiryHours, string $resetLink): bool
  {
    $subject = "GSOB Password Reset Request";
    $greeting = $full_name ? "Dear {$full_name}" : 'Dear User';

    $content = "
      <p style='margin: 0 0 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.5;'>{$greeting},</p>
      
      <p style='margin: 0 0 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.6;'>
        We received a request to reset the password for your GSOB account. If you made this request, please click the button below to create a new password:
      </p>
      
      <div style='text-align: center; margin: 32px 0;'>
        <a href='{$resetLink}' 
           style='display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 6px; font-size: 16px; font-weight: 600; text-align: center; transition: all 0.3s ease;'>
          Reset My Password
        </a>
      </div>
      
      <p style='margin: 24px 0; color: #6c757d; font-size: 14px; line-height: 1.5; text-align: center;'>
        Or copy and paste this link into your browser:<br>
        <span style='word-break: break-all; color: #495057; font-family: monospace; background-color: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 12px;'>
          {$resetLink}
        </span>
      </p>
      
      <div style='background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 16px; margin: 24px 0; border-radius: 4px;'>
        <h4 style='margin: 0 0 8px 0; color: #0c5460; font-size: 14px; font-weight: 600;'>
          🔒 Security Information
        </h4>
        <ul style='margin: 0; padding-left: 16px; color: #0c5460; font-size: 13px; line-height: 1.4;'>
          <li>This password reset link will expire in {$expiryHours} hour for your security</li>
          <li>If you didn't request this reset, please ignore this email</li>
          <li>Your current password remains unchanged until you complete the reset process</li>
        </ul>
      </div>
      
      <p style='margin: 24px 0 0 0; color: #6c757d; font-size: 14px; line-height: 1.5;'>
        For your security, if you continue to receive these emails without requesting them, please contact our support team immediately.
      </p>
      
      <hr style='border: none; border-top: 1px solid #e9ecef; margin: 24px 0;'>
      
      <p style='margin: 0; color: #6c757d; font-size: 14px; line-height: 1.5;'>
        Best regards,<br><strong>The GSOB Security Team</strong>
      </p>
    ";

    $body = self::getBaseTemplate($content);
    return self::sendEmail($to, $subject, $body);
  }

  /**
   * Send Welcome Email
   */
  public static function sendWelcomeEmail(string $to, string $userName, string $name, string $username, string $roleName, string $password): bool
  {

    $role = ucfirst($roleName);
    $subject = "Welcome to GSOB - Account Created Successfully";

    $content = "
      <p style='margin: 0 0 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.5;'>
        Dear {$userName},
      </p>
      
      <p style='margin: 0 0 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.6;'>
        Congratulations! <br/>Your GSOB account has been successfully created. We're excited to have you join our community.
      </p>
      
      <div style='background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; padding: 20px; margin: 24px 0;'>
        <h4 style='margin: 0 0 12px 0; color: #495057; font-size: 16px; font-weight: 600;'>
          Account Details
        </h4>
        <table style='width: 100%; border-collapse: collapse;'>
          <tr>
            <td style='padding: 8px 0; color: #6c757d; font-size: 14px; font-weight: 500; width: 30%;'>Name:</td>
            <td style='padding: 8px 0; color: #495057; font-size: 14px;'>{$name}</td>
          </tr>
          <tr>
            <td style='padding: 8px 0; color: #6c757d; font-size: 14px; font-weight: 500;'>Username(Half of your name):</td>
            <td style='padding: 8px 0; color: #495057; font-size: 14px; font-family: monospace;'>{$userName}</td>
          </tr>
          <tr>
            <td style='padding: 8px 0; color: #6c757d; font-size: 14px; font-weight: 500;'>Email:</td>
            <td style='padding: 8px 0; color: #495057; font-size: 14px;'>{$to}</td>
          </tr>
          <tr>
            <td style='padding: 8px 0; color: #6c757d; font-size: 14px; font-weight: 500;'>Password:</td>
            <td style='padding: 8px 0; color: #495057; font-size: 14px;'>{$password}</td>
          </tr>
          <tr>
            <td style='padding: 8px 0; color: #6c757d; font-size: 14px; font-weight: 500;'>Role:</td>
            <td style='padding: 8px 0; color: #495057; font-size: 14px;'>{$role}</td>
          </tr>
        </table>
      </div>
      
      <div style='background-color: #e7f3ff; border-left: 4px solid #007bff; padding: 16px; margin: 24px 0; border-radius: 4px;'>
        <h4 style='margin: 0 0 12px 0; color: #004085; font-size: 14px; font-weight: 600;'>
          📋 Next Steps
        </h4>
        <ol style='margin: 0; padding-left: 16px; color: #004085; font-size: 13px; line-height: 1.5;'>
          <li>Log in to your account using your email and password</li>
          <li>Complete your profile setup</li>
          <li>Explore the platform features</li>
          <li>Contact support if you need any assistance</li>
        </ol>
      </div>
      
      <p style='margin: 24px 0 0 0; color: #6c757d; font-size: 14px; line-height: 1.5;'>
        Thank you for choosing GSOB. We look forward to providing you with an exceptional experience.
      </p>
      
      <hr style='border: none; border-top: 1px solid #e9ecef; margin: 24px 0;'>
      
      <p style='margin: 0; color: #6c757d; font-size: 14px; line-height: 1.5;'>
        Best regards,<br><strong>The GSOB Team</strong>
      </p>
    ";

    $body = self::getBaseTemplate($content);
    return self::sendEmail($to, $subject, $body);
  }

  /**
   * Send Account Unlock Email
   */
  public static function sendUnlockEmail(string $to, string $unlockUrl, string $userName = ''): bool
  {
    $subject = "GSOB Account Security Alert - Account Locked";
    $greeting = $userName ? "Dear {$userName}" : 'Dear User';

    $content = "
      <p style='margin: 0 0 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.5;'>{$greeting},</p>
      
      <p style='margin: 0 0 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.6;'>
        Your GSOB account has been temporarily locked due to multiple failed login attempts. This is a security measure to protect your account.
      </p>
      
      <div style='background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 6px; padding: 20px; margin: 24px 0; text-align: center;'>
        <h4 style='margin: 0 0 12px 0; color: #721c24; font-size: 16px; font-weight: 600;'>
          🚨 Account Temporarily Locked
        </h4>
        <p style='margin: 0; color: #721c24; font-size: 14px; line-height: 1.4;'>
          Multiple failed login attempts detected
        </p>
      </div>
      
      <p style='margin: 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.6;'>
        To unlock your account and regain access, please click the button below:
      </p>
      
      <div style='text-align: center; margin: 32px 0;'>
        <a href='{$unlockUrl}' 
           style='display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 6px; font-size: 16px; font-weight: 600; text-align: center;'>
          Unlock My Account
        </a>
      </div>
      
      <p style='margin: 24px 0; color: #6c757d; font-size: 14px; line-height: 1.5; text-align: center;'>
        Or copy and paste this link into your browser:<br>
        <span style='word-break: break-all; color: #495057; font-family: monospace; background-color: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 12px;'>
          {$unlockUrl}
        </span>
      </p>
      
      <div style='background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 16px; margin: 24px 0; border-radius: 4px;'>
        <h4 style='margin: 0 0 12px 0; color: #856404; font-size: 14px; font-weight: 600;'>
          🛡️ Security Recommendations
        </h4>
        <ul style='margin: 0; padding-left: 16px; color: #856404; font-size: 13px; line-height: 1.4;'>
          <li>Use a strong, unique password for your GSOB account</li>
          <li>Enable two-factor authentication if available</li>
          <li>Never share your login credentials with others</li>
          <li>Contact support immediately if you suspect unauthorized access</li>
        </ul>
      </div>
      
      <p style='margin: 24px 0 0 0; color: #6c757d; font-size: 14px; line-height: 1.5;'>
        If you did not attempt to log in to your account, please contact our security team immediately as your account may be compromised.
      </p>
      
      <hr style='border: none; border-top: 1px solid #e9ecef; margin: 24px 0;'>
      
      <p style='margin: 0; color: #6c757d; font-size: 14px; line-height: 1.5;'>
        Best regards,<br><strong>The GSOB Security Team</strong>
      </p>
    ";

    $body = self::getBaseTemplate($content);
    return self::sendEmail($to, $subject, $body);
  }

  /**
   * Send Failed Login Attempt Notification
   */
  public static function sendFailedLoginEmail(
    string $to,
    string $userName,
    int $failedAttempts,
    $lockUntil = null,
    string $unlockUrl = ''
  ): bool {
    $greeting = $userName ? "Dear {$userName}" : 'Dear User';
    $isLocked = $lockUntil && $lockUntil > new DateTime();
    $subject = "GSOB Security Alert - Failed Login Attempt" . ($isLocked ? ' (Account Locked)' : '');

    $lockUntilFormatted = $isLocked && $lockUntil ? $lockUntil->format('Y-m-d H:i:s') : '';
    $timestamp = (new DateTime())->format('Y-m-d H:i:s');

    $content = "
      <p style='margin: 0 0 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.5;'>{$greeting},</p>
      
      <p style='margin: 0 0 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.6;'>
        We detected a failed login attempt on your GSOB account. This email is sent as a security precaution to keep you informed of account activity.
      </p>
      
      <div style='background-color: " . ($isLocked ? '#f8d7da' : '#fff3cd') . "; border: 1px solid " . ($isLocked ? '#f5c6cb' : '#ffeaa7') . "; border-radius: 6px; padding: 20px; margin: 24px 0;'>
        <h4 style='margin: 0 0 12px 0; color: " . ($isLocked ? '#721c24' : '#856404') . "; font-size: 16px; font-weight: 600;'>
          " . ($isLocked ? '🔒 Account Locked' : '⚠️ Security Alert') . "
        </h4>
        <table style='width: 100%; border-collapse: collapse;'>
          <tr>
            <td style='padding: 4px 0; color: " . ($isLocked ? '#721c24' : '#856404') . "; font-size: 13px; font-weight: 500; width: 40%;'>Failed Attempts:</td>
            <td style='padding: 4px 0; color: " . ($isLocked ? '#721c24' : '#856404') . "; font-size: 13px; font-weight: 600;'>{$failedAttempts} of 5</td>
          </tr>
          <tr>
            <td style='padding: 4px 0; color: " . ($isLocked ? '#721c24' : '#856404') . "; font-size: 13px; font-weight: 500;'>Timestamp:</td>
            <td style='padding: 4px 0; color: " . ($isLocked ? '#721c24' : '#856404') . "; font-size: 13px;'>{$timestamp}</td>
          </tr>
          " . ($isLocked ? "
          <tr>
            <td style='padding: 4px 0; color: #721c24; font-size: 13px; font-weight: 500;'>Locked Until:</td>
            <td style='padding: 4px 0; color: #721c24; font-size: 13px; font-weight: 600;'>{$lockUntilFormatted}</td>
          </tr>
          " : '') . "
        </table>
      </div>
      
      " . ($isLocked ? "
      <p style='margin: 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.6;'>
        Your account has been temporarily locked for security reasons. You can unlock it immediately by clicking the button below:
      </p>
      
      <div style='text-align: center; margin: 32px 0;'>
        <a href='{$unlockUrl}' 
           style='display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 6px; font-size: 16px; font-weight: 600;'>
          Unlock My Account
        </a>
      </div>
      " : "
      <p style='margin: 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.6;'>
        If this was you, please try logging in again with the correct password. If you've forgotten your password, you can reset it using the password recovery option.
      </p>
      ") . "
      
      <div style='background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 16px; margin: 24px 0; border-radius: 4px;'>
        <h4 style='margin: 0 0 8px 0; color: #0c5460; font-size: 14px; font-weight: 600;'>
          🔒 If This Wasn't You
        </h4>
        <p style='margin: 0; color: #0c5460; font-size: 13px; line-height: 1.4;'>
          If you did not attempt to log in, your account may be under attack. Please contact our security team immediately and consider changing your password.
        </p>
      </div>
      
      <hr style='border: none; border-top: 1px solid #e9ecef; margin: 24px 0;'>
      
      <p style='margin: 0; color: #6c757d; font-size: 14px; line-height: 1.5;'>
        Best regards,<br><strong>The GSOB Security Team</strong>
      </p>
    ";

    $body = self::getBaseTemplate($content);
    return self::sendEmail($to, $subject, $body);
  }

  /**
   * Send Account Verification Email
   */
  public static function sendAccountVerificationEmail(string $to, string $verificationLink, string $fullName): bool
  {
    $subject = "Please Verify Your GSOB Account";
    $greeting = $fullName ? "Dear {$fullName}" : 'Dear User';

    $content = "
      <p style='margin: 0 0 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.5;'>{$greeting},</p>
      
      <p style='margin: 0 0 24px 0; color: #2c3e50; font-size: 16px; line-height: 1.6;'>
        Thank you for registering with GSOB. To complete your registration and activate your account, please verify your email address by clicking the button below:
      </p>
      
      <div style='text-align: center; margin: 32px 0;'>
        <a href='{$verificationLink}' 
           style='display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 6px; font-size: 16px; font-weight: 600; text-align: center;'>
          Verify My Account
        </a>
      </div>
      
      <p style='margin: 24px 0; color: #6c757d; font-size: 14px; line-height: 1.5; text-align: center;'>
        Or copy and paste this link into your browser:<br>
        <span style='word-break: break-all; color: #495057; font-family: monospace; background-color: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 12px;'>
          {$verificationLink}
        </span>
      </p>
      
      <div style='background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 16px; margin: 24px 0; border-radius: 4px;'>
        <h4 style='margin: 0 0 8px 0; color: #0c5460; font-size: 14px; font-weight: 600;'>
          ℹ️ Important Information
        </h4>
        <p style='margin: 0; color: #0c5460; font-size: 13px; line-height: 1.4;'>
          This verification link will expire in 48 hours. If you didn't create an account with GSOB, you can safely ignore this email.
        </p>
      </div>
      
      <hr style='border: none; border-top: 1px solid #e9ecef; margin: 24px 0;'>
      
      <p style='margin: 0; color: #6c757d; font-size: 14px; line-height: 1.5;'>
        Best regards,<br><strong>The GSOB Team</strong>
      </p>
    ";

    $body = self::getBaseTemplate($content);
    return self::sendEmail($to, $subject, $body);
  }

  /**
   * Send Custom Email with Template
   */
  public static function sendCustomEmail(string $to, string $subject, string $htmlContent, string $preheader = ''): bool
  {
    $body = self::getBaseTemplate($htmlContent, $preheader);
    return self::sendEmail($to, $subject, $body);
  }

  /**
   * Send Notification Email
   */
  public static function sendNotificationEmail(
    string $to,
    string $title,
    string $message,
    string $actionUrl = '',
    string $actionText = 'View Details',
    string $badgeColor = 'primary'
  ): bool {
    $subject = $title;

    $gradientColors = [
      'primary' => 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);',
      'success' => 'background: linear-gradient(135deg, #28a745 0%, #20c997 100%);',
      'danger' => 'background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);',
      'warning' => 'background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);',
      'info' => 'background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);'
    ];

    $buttonStyle = $gradientColors[$badgeColor] ?? $gradientColors['primary'];

    $content = "
      <h2 style='color: #2c3e50; font-weight: 700; font-size: 28px; margin-bottom: 25px; text-align: center;'>
        {$title}
      </h2>
      
      <div style='color: #4a5568; font-size: 16px; line-height: 1.8; margin-bottom: 30px;'>
        {$message}
      </div>
    ";

    if (!empty($actionUrl)) {
      $content .= "
      <div style='text-align: center; margin: 32px 0;'>
        <a href='{$actionUrl}' 
           style='display: inline-block; {$buttonStyle} color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 6px; font-size: 16px; font-weight: 600;'>
          {$actionText}
        </a>
      </div>
      ";
    }

    $content .= "
      <hr style='border: none; border-top: 1px solid #e9ecef; margin: 24px 0;'>
      
      <p style='margin: 0; color: #6c757d; font-size: 14px; line-height: 1.5;'>
        Best regards,<br><strong>The GSOB Team</strong>
      </p>
    ";

    $body = self::getBaseTemplate($content);
    return self::sendEmail($to, $subject, $body);
  }

  /**
   * Send Success Notification
   */
  public static function sendSuccessEmail(
    string $to,
    string $title,
    string $message,
    string $actionUrl = '',
    string $actionText = 'Continue'
  ): bool {
    return self::sendNotificationEmail($to, $title, $message, $actionUrl, $actionText, 'success');
  }

  /**
   * Send Alert/Warning Email
   */
  public static function sendAlertEmail(
    string $to,
    string $title,
    string $message,
    string $actionUrl = '',
    string $actionText = 'Review Now'
  ): bool {
    return self::sendNotificationEmail($to, $title, $message, $actionUrl, $actionText, 'warning');
  }

  /**
   * Send Professional Newsletter
   */
  public static function sendProfessionalNewsletter(
    string $to,
    string $fullName,
    string $subject,
    array $sections
  ): bool {
    $greeting = $fullName ? "Hello {$fullName}" : 'Hello';

    $content = "
      <h2 style='color: #2c3e50; font-weight: 700; font-size: 28px; margin-bottom: 15px; text-align: center;'>
        Latest Updates from GSOB
      </h2>
      
      <p style='color: #4a5568; font-size: 17px; text-align: center; margin-bottom: 35px;'>
        {$greeting},<br>
        Here's what's new and exciting at GSOB.
      </p>
    ";

    $iconColors = ['#667eea', '#28a745', '#17a2b8', '#ffc107', '#dc3545'];

    foreach ($sections as $index => $section) {
      $iconColor = $iconColors[$index % count($iconColors)];

      $content .= "
      <div style='background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; padding: 20px; margin: 20px 0;'>
        <h4 style='color: #2c3e50; font-weight: 700; font-size: 18px; margin-bottom: 12px;'>
          <span style='color: {$iconColor};'>▸</span> {$section['title']}
        </h4>
        <p style='color: #4a5568; font-size: 15px; line-height: 1.7; margin: 0;'>
          {$section['content']}
        </p>
      </div>
      ";
    }

    $content .= "
      <hr style='border: none; border-top: 1px solid #e9ecef; margin: 30px 0;'>
      
      <div style='text-align: center; padding: 20px 0;'>
        <p style='color: #6c757d; font-size: 15px; margin-bottom: 10px;'>
          Want to stay updated with more news and announcements?
        </p>
        <p style='margin: 0;'>
          <a href='#' style='color: #667eea; text-decoration: none; font-weight: 600; font-size: 15px;'>
            Visit Our Blog →
          </a>
        </p>
      </div>
      
      <hr style='border: none; border-top: 1px solid #e9ecef; margin: 24px 0;'>
      
      <p style='margin: 0; color: #6c757d; font-size: 14px; line-height: 1.5;'>
        Best regards,<br><strong>The GSOB Team</strong>
      </p>
    ";

    $body = self::getBaseTemplate($content);
    return self::sendEmail($to, $subject, $body);
  }
}