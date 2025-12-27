<?php

namespace Models;

use PDO;
use Utils\HasUUID;

class SubscribersModel extends BaseModel
{
  use HasUUID;

  protected static string $table = 'subscribers';

  public string $subscriber_id;
  public string $full_name;
  public string $email;
  public string $subject;
  public string $message;
  public ?string $attachment;

  public function __construct(array $data = [])
  {
    if (!empty($data)) {
      $this->generateId('subscriber_id');
      $this->full_name = trim($data['full_name'] ?? '');
      $this->email = trim($data['email'] ?? '');
      $this->subject = trim($data['subject'] ?? '');
      $this->message = trim($data['message'] ?? '');
      $this->attachment = $data['attachment'] ?? null;
    }
  }

  /** 🔹 Save new subscriber */
  public function save(): bool
  {
    $sql = "INSERT INTO subscribers 
                (subscriber_id, full_name, email, subject, message, attachment)
                VALUES (UNHEX(?), ?, ?, ?, ?, ?)";

    return self::query($sql, [
      $this->getHexId('subscriber_id'),
      $this->full_name,
      $this->email,
      $this->subject,
      $this->message,
      $this->attachment
    ]) !== false;
  }

  /** 🔹 Dashboard: total subscribers */
  public static function countAll(): int
  {
    return (int) self::query(
      "SELECT COUNT(*) FROM subscribers"
    )->fetchColumn();
  }

  /** 🔹 Admin list */
  public static function getAllSubscribers(): array
  {
    $sql = "SELECT 
                    HEX(subscriber_id) AS subscriber_id,
                    full_name,
                    email,
                    created_at
                FROM subscribers
                ORDER BY created_at DESC";

    return self::query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }
}
