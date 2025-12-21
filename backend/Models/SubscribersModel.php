<?php

namespace Models;

use Services\UUIDService;
use PDO;

class SubscribersModel
{
  private string $table = "subscribers";
  private PDO $conn;

  public string $subscriber_id;
  public string $full_name;
  public string $email;
  public string $subject;
  public string $message;
  public ?string $attachment;

  public function __construct(PDO $conn, array $data = [])
  {
    $this->conn = $conn;
    $this->subscriber_id = UUIDService::generateBinary();
    $this->full_name = trim($data['full_name'] ?? '');
    $this->email = trim($data['email'] ?? '');
    $this->subject = trim($data['subject'] ?? '');
    $this->message = trim($data['message'] ?? '');
    $this->attachment = $data['attachment'] ?? null;
  }

  public function getAllSubscribers(): array
  {
    $stmt = $this->conn->query(
      "SELECT HEX(subscriber_id) AS subscriber_id, email, full_name FROM {$this->table}"
    );
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
