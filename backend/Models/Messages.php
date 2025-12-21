<?php

namespace Models;

use PDO;
use Services\UUIDService;
use Config\Database;

class Messages extends BaseModel
{
  protected static string $table = 'messages';
  protected static string $primaryKey = 'message_id';

  public string $message_id;
  public string $full_name;
  public string $email;
  public ?string $subject;
  public string $message;
  public ?string $attachment_url;
  public int $consent;
  public string $created_at;
  public ?string $updated_at;

  public function __construct(array $data)
  {
    $this->message_id = UUIDService::generateBinary();
    $this->full_name = $data['full_name'] ?? '';
    $this->email = $data['email'] ?? '';
    $this->subject = $data['subject'] ?? null;
    $this->message = $data['message'] ?? '';
    $this->attachment_url = $data['attachment_url'] ?? null;
    $this->consent = isset($data['consent']) ? (int)$data['consent'] : 0;
    $this->created_at = date('Y-m-d H:i:s');
    $this->updated_at = null;
  }

  // Fetch all messages
  public static function getAll(): array
  {
    $db = Database::getConnection();

    $sql = "SELECT 
                message_id,
                full_name,
                email,
                subject,
                message,
                attachment,
                consent,
                created_at,
                updated_at
            FROM messages
            ORDER BY created_at DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  // Fetch single message
  public static function getById(string $idBinary): ?self
  {
    $stmt = self::query(
      "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ? LIMIT 1",
      [$idBinary]
    );
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) return null;

    $message = new self([]);
    foreach ($result as $key => $value) {
      if (property_exists($message, $key)) {
        $message->$key = $value;
      }
    }
    return $message;
  }

  // Save new message
  public function save(): bool
  {
    $sql = "INSERT INTO " . static::$table . " 
            (message_id, full_name, email, subject, message, attachment, consent, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
      $this->message_id,
      $this->full_name,
      $this->email,
      $this->subject,
      $this->message,
      $this->attachment_url,
      $this->consent,
      $this->created_at
    ];

    return self::query($sql, $params) ? true : false;
  }

  // Delete message
  public static function deleteMessage(string $idBinary): string
  {
    $sql = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";
    $params = [
      $idBinary
    ];;
    return self::query($sql, $params) ? 'Message deleted successfully' : 'An error occurred';
  }
}