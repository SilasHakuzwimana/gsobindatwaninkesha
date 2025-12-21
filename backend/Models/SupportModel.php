<?php
namespace Models;

use Services\UUIDService;
use Services\EmailService;
use PDO;

class SupportModel
{
    private string $table = "support";
    private PDO $conn;

    public string $id;
    public string $name;
    public string $email;
    public string $subject;
    public string $message;
    public ?string $attachment;

    public function __construct(PDO $conn, array $data = [])
    {
        $this->conn = $conn;
        $this->id = UUIDService::generateBinary();
        $this->name = trim($data['name'] ?? '');
        $this->email = trim($data['email'] ?? '');
        $this->subject = trim($data['subject'] ?? '');
        $this->message = trim($data['message'] ?? '');
        $this->attachment = $data['attachment'] ?? null;
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
            (support_id, name, email, subject, message, attachment_url) 
            VALUES (:support_id, :name, :email, :subject, :message, :attachment_url)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':support_id' => $this->id,
            ':name' => $this->name,
            ':email' => $this->email,
            ':subject' => $this->subject,
            ':message' => $this->message,
            ':attachment_url' => $this->attachment
        ]);
    }

    public function send(): bool
    {
        $supportEmail = getenv('SUPPORT_EMAIL') ?: 'hakuzwisilas@gmail.com';
        $subject = "[Support] {$this->subject}";

        // Convert binary UUID to hex string
        $hexId = UUIDService::fromBinary($this->id);
        $body = "
            <p><strong>Name:</strong> {$this->name}</p>
            <p><strong>Email:</strong> {$this->email}</p>
            <p><strong>Message:</strong><br>{$this->message}</p>
            <p><strong>ID:</strong> {$hexId}</p>
            <p><strong>Attachment:</strong> {$this->attachment}</p>
        ";
        return EmailService::sendCustomEmail($supportEmail, $subject, $body, "New support request from {$this->name}", $this->attachment);
    }

    public function sendAcknowledgment(): bool
    {
        $subject = "Support request received!";
        // Convert binary UUID to hex string
        $hexId = UUIDService::fromBinary($this->id);
        $body = "
            <p>Hi {$this->name},</p>
            <p>We have received your support request. Our team will respond soon.</p>
            <p>Reference ID: {$hexId}</p>
            <p>Best regards,<br>GSOB Support Team</p>
        ";
        return EmailService::sendCustomEmail($this->email, $subject, $body, "Acknowledgment for your support request");
    }
}
