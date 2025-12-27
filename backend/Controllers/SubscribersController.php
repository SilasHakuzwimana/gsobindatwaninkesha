<?php

namespace Controllers;

use Models\SubscribersModel;
use Services\EmailService;
use PDO;

class SubscribersController
{
  private PDO $conn;
  private SubscribersModel $subscriberModel;
  private EmailService $emailService;

  public function __construct(PDO $conn)
  {
    $this->conn = $conn;
    $this->subscriberModel = new SubscribersModel();
    $this->emailService = new EmailService();
  }

  /**
   * Get all subscribers and return JSON.
   */
  public function getSubscribers(): void
  {
    $subscribers = $this->subscriberModel->getAllSubscribers();

    header('Content-Type: application/json');
    echo json_encode([
      'status' => 'success',
      'count' => count($subscribers),
      'data' => $subscribers
    ]);
  }

  /**
   * Send customized emails to all subscribers and return JSON results.
   */
  public function sendToSubscribers(): void
  {
    $data = json_decode(file_get_contents('php://input'), true);

    $subject = trim($data['subject'] ?? '');
    $messageTemplate = trim($data['message'] ?? '');

    if (empty($subject) || empty($messageTemplate)) {
      http_response_code(400);
      echo json_encode([
        'status' => 'error',
        'message' => 'Subject and message are required.'
      ]);
      return;
    }

    $subscribers = $this->subscriberModel->all();
    $results = [];

    foreach ($subscribers as $subscriber) {
      $personalizedMessage = str_replace('{{name}}', $subscriber['full_name'], $messageTemplate);

      $status = $this->emailService->sendEmail($subscriber['email'], $subject, $personalizedMessage);

      $results[] = [
        'subscriber_id' => $subscriber['subscriber_id'],
        'email' => $subscriber['email'],
        'status' => $status === true ? 'sent' : $status
      ];
    }

    header('Content-Type: application/json');
    echo json_encode([
      'status' => 'success',
      'sent_count' => count($results),
      'results' => $results
    ]);
  }
}
