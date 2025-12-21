<?php

namespace Services;

use DateTime;
use DateTimeZone;
use Services\UUIDService;
use Config\Database;
use PDO;

class NewsletterService
{
  /**
   * Subscribe a user to the newsletter
   */
  public static function subscribe(string $email, string $fullName): bool
  {
    $pdo = Database::getConnection();
    $now = (new DateTime('now', new DateTimeZone('Africa/Kigali')))->format('Y-m-d H:i:s');
    $subscriberId = UUIDService::generateBinary();

    try {
      $stmt = $pdo->prepare("INSERT INTO subscribers (subscriber_id, email, full_name, verified, created_at) VALUES (?, ?, ?, 1, ?)");
      $stmt->execute([$subscriberId, $email, $fullName, $now]);

      // Optional: send a welcome newsletter immediately
      EmailService::sendProfessionalNewsletter(
        $email,
        $fullName ?? 'Subscriber',
        'Welcome to GSOB Newsletter!',
        [
          ['title' => 'Thank You for Subscribing', 'content' => 'You will now receive weekly updates and news from GSOB. Stay tuned!']
        ]
      );

      return true;
    } catch (\PDOException $e) {
      // Email already exists
      if ($e->getCode() == 23000) return false;
      throw $e;
    }
  }

  /**
   * Get all subscribers (for sending mass newsletters)
   */
  public static function getAllSubscribers(): array
  {
    $pdo = Database::getConnection();
    $stmt = $pdo->query("SELECT email, full_name FROM subscribers WHERE verified = 1");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}