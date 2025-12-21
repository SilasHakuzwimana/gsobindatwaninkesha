<?php
namespace Services;

use Services\EmailService;
use Services\UUIDService;
use config\Database;
use PDO;
use Exception;

class OtpService
{
    private const OTP_LENGTH = 6;
    private const OTP_EXPIRY_MINUTES = 10; // Default expiry

    /**
     * Generate a random numeric OTP
     */
    public static function generateOtp(int $length = self::OTP_LENGTH): string
    {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= random_int(0, 9);
        }
        return $otp;
    }

    /**
     * Create OTP for a user and store in DB
     */
    public static function createOtp(string $userId, string $type = 'login'): string
    {
        $pdo = Database::getConnection();

        $otp = self::generateOtp();
        $expiresAt = date('Y-m-d H:i:s', time() + self::OTP_EXPIRY_MINUTES * 60);
        $otpId = UUIDService::generateBinary(); // Binary(16)

        $stmt = $pdo->prepare("INSERT INTO otps (otp_id, user_id, otp_code, type, expires_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$otpId, $userId, $otp, $type, $expiresAt]);

        return $otp;
    }

    /**
     * Send OTP to user's email
     */
    public static function sendOtpEmail(string $email, string $userId, string $type = 'login'): bool
    {
        $otp = self::createOtp($userId, $type);
        return EmailService::sendOtpEmail($email, $otp, self::OTP_EXPIRY_MINUTES);
    }

    /**
     * Validate OTP
     */
    public static function validateOtp(string $userId, string $otpCode, string $type = 'login'): bool
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
            SELECT * FROM otps 
            WHERE user_id = ? AND otp_code = ? AND type = ? AND expires_at >= NOW()
            ORDER BY created_at DESC
            LIMIT 1
        ");
        $stmt->execute([$userId, $otpCode, $type]);
        $otp = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($otp) {
            // Optionally, delete OTP after successful validation
            $deleteStmt = $pdo->prepare("DELETE FROM otps WHERE otp_id = ?");
            $deleteStmt->execute([$otp['otp_id']]);
            return true;
        }

        return false;
    }

    /**
     * Clean up expired OTPs (optional cron job)
     */
    public static function deleteExpiredOtps(): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM otps WHERE expires_at < NOW()");
        $stmt->execute();
    }
}
