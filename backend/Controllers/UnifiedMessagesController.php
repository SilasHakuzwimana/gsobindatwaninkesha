<?php

namespace Controllers;

use Models\Messages;
use Models\ContactModel;
use Services\UUIDService;

class UnifiedMessagesController extends BaseController
{
  public function findAll(): void
  {
    // ---- Fetch all messages table entries ----
    $messages = Messages::getAll();
    foreach ($messages as &$msg) {
      $msg['id'] = UUIDService::fromBinary($msg['message_id']);
      $msg['source'] = 'messages';
      $msg['attachment_url'] = $msg['attachment'];
    }

    // ---- Fetch all contact table entries ----
    $contact = ContactModel::getAll();
    foreach ($contact as &$c) {
      $c['id'] = UUIDService::fromBinary($c['contact_id']);
      $c['source'] = 'contact';
    }

    // ---- Merge two datasets ----
    $merged = array_merge($messages, $contact);

    // ---- Sort by newest date ----
    usort($merged, function ($a, $b) {
      return strtotime($b['created_at']) <=> strtotime($a['created_at']);
    });

    // ---- JSON response ----
    return $this->json([
      'status' => 'success',
      'data' => $merged
    ], 200);
  }
}
