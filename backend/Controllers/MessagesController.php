<?php

namespace Controllers;

use Models\Messages;
use Services\UUIDService;
use Models\ContactModel;

class MessagesController extends BaseController
{

  // -------------------------
  // List all messages
  // -------------------------
  public function findAll()
  {
    $messages = Messages::getAll();
    foreach ($messages as &$msg) {
      $msg['id'] = UUIDService::fromBinary($msg['message_id']);
      $msg['source'] = 'messages';
      $msg['attachment_url'] = $msg['attachment'];
    }

    $contact = ContactModel::getAll();
    foreach ($contact as &$c) {
      $c['id'] = UUIDService::fromBinary($c['contact_id']);
      $c['source'] = 'contact';
      $c['attachment_url'] = $c['attachment_url'] ?? null;
    }

    $merged = array_merge($messages, $contact);

    usort($merged, function ($a, $b) {
      return strtotime($b['created_at']) <=> strtotime($a['created_at']);
    });

    // If $this->json() already outputs the response, no "return" needed
    $this->json([
      'status' => 'success',
      'data' => $merged
    ], 200);
  }

  public function index(): void
  {
    $messages = Messages::getAll();
    foreach ($messages as &$msg) {
      $msg['message_id'] = UUIDService::fromBinary($msg['message_id']);
    }

    $this->json(['status' => 'success', 'data' => $messages], 200);
  }

  // -------------------------
  // Create a new message
  // -------------------------
  public function store(): void
  {
    $data = $this->getRequestData();

    if (empty($data['full_name']) || empty($data['email']) || empty($data['message'])) {
      $this->json(['status' => 'error', 'message' => 'Full name, email and message are required'], 400);
      return;
    }

    $message = new Messages($data);
    if ($message->save()) {
      $this->json(['status' => 'success', 'message' => 'Message submitted successfully'], 201);
    } else {
      $this->json(['status' => 'error', 'message' => 'Failed to save message'], 500);
    }
  }

  // -------------------------
  // Show single message
  // -------------------------
  public function show(string $id): void
  {
    try {
      $binaryId = UUIDService::toBinary($id ?? '');
    } catch (\Exception $e) {
      $this->json(['status' => 'error', 'message' => 'Invalid UUID format'], 400);
      return;
    }

    $message = MessagesController::getById($binaryId);
    if (!$message) {
      $this->json(['status' => 'error', 'message' => 'Message not found'], 404);
      return;
    }

    $response = [
      'message_id' => UUIDService::fromBinary($message->message_id),
      'full_name'  => $message->full_name,
      'email'      => $message->email,
      'subject'    => $message->subject,
      'message'    => $message->message,
      'attachment' => $message->attachment_url,
      'consent'    => $message->consent,
      'created_at' => $message->created_at,
      'updated_at' => $message->updated_at
    ];

    $this->json(['status' => 'success', 'data' => $response], 200);
  }

  // -------------------------
  // Helper: Get request data
  // -------------------------
  private function getRequestData(): array
  {
    $input = json_decode(file_get_contents('php://input'), true);
    return is_array($input) ? $input : ($_POST ?? []);
  }

  public static function getById(string $idBinary): ?Messages
  {
    return Messages::getById($idBinary);
  }

  public function deleteMessage(string $idBinary): void
  {
    $messagedeleted = Messages::deleteMessage($idBinary);
    if ($messagedeleted) {
      $this->json(['status' => 'success', 'message' => $messagedeleted], 200);
    } else {
      $this->json(['status' => 'error', 'message' => 'Failed to delete message'], 500);
    }
  }
}
