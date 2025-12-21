<?php

namespace Controllers;

use Services\NewsletterService;

class NewsletterController
{
  public function subscribe()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'] ?? '';
    $fullName = $data['full_name'] ?? '';

    if (!$email) {
      http_response_code(400);
      echo json_encode(['status' => false, 'message' => 'Email is required']);
      return;
    }

    $success = NewsletterService::subscribe($email, $fullName);

    if ($success) {
      echo json_encode(['status' => true, 'message' => 'Subscribed successfully!']);
    } else {
      http_response_code(409);
      echo json_encode(['status' => false, 'message' => 'Email is already subscribed']);
    }
  }
}