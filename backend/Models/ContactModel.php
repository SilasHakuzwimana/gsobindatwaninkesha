<?php

namespace Models;

use Services\UUIDService;
use Config\Database;
use Services\EmailService;
use PDO;

class ContactModel
{
  private string $table = "contact";
  private PDO $conn;

  public string $id;
  public string $name;
  public string $email;
  public string $subject;
  public string $message;
  public ?string $attachment_url = null;

  public function __construct(PDO $conn, array $data = [])
  {
    $this->conn = $conn;
    $this->id = UUIDService::generateBinary();
    $this->name = trim($data['name'] ?? '');
    $this->email = trim($data['email'] ?? '');
    $this->subject = trim($data['subject'] ?? '');
    $this->message = trim($data['message'] ?? '');
    $this->attachment = $data['attachment_url'] ?? null;
  }

  public function validate(): array
  {
    $errors = [];
    if (!$this->name) $errors['name'] = 'Name is required';
    if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required';
    if (!$this->subject) $errors['subject'] = 'Subject is required';
    if (!$this->message) $errors['message'] = 'Message cannot be empty';
    return $errors;
  }

  public function save(): bool
  {
    $query = "INSERT INTO {$this->table} 
            (contact_id, name, email, subject, message, attachment_url) 
            VALUES (:contact_id, :name, :email, :subject, :message, :attachment_url)";

    $stmt = $this->conn->prepare($query);
    return $stmt->execute([
      ':contact_id' => $this->id,
      ':name' => $this->name,
      ':email' => $this->email,
      ':subject' => $this->subject,
      ':message' => $this->message,
      ':attachment_url' => $this->attachment_url
    ]);
  }

  public function send(): bool
  {
    $supportEmail = getenv('SUPPORT_EMAIL') ?: 'hakuzwisilas@gmail.com';
    $subject = "[Contact] {$this->subject}";

    // Convert binary UUID to hex string
    $hexId = UUIDService::fromBinary($this->id);

    $body = "
            <p><strong>Name:</strong> {$this->name}</p>
            <p><strong>Email:</strong> {$this->email}</p>
            <p><strong>Message:</strong><br>{$this->message}</p>
            <p><strong>ID:</strong> {$hexId}</p>
            <p><strong>Attachment:</strong> {$this->attachment_url}</p>
        ";
    return EmailService::sendCustomEmail($supportEmail, $subject, $body, "New contact form submission");
  }

  public static function getAll(): array
  {
    $db = Database::getConnection();

    $sql = "SELECT
                contact_id,
                name,
                email,
                subject,
                message,
                attachment_url,
                created_at
            FROM contact
            ORDER BY created_at DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function sendAcknowledgment(): bool
  {
    $subject = "Thank you for contacting GSOB!";
    // Convert binary UUID to hex string
    $hexId = UUIDService::fromBinary($this->id);
    $body = "
            <p>Hi {$this->name},</p>
            <p>Thank you for reaching out. We will respond to your inquiry shortly.</p>
            <p>Reference ID: {$hexId}</p>
            <p>Best regards,<br>GSOB Support Team</p>
        ";
    return EmailService::sendCustomEmail($this->email, $subject, $body, "Acknowledgment");
  }
}
