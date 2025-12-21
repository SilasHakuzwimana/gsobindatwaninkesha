<?php
require_once __DIR__ . '/../Models/ContactModel.php';
require_once __DIR__ . '/../Models/SupportModel.php';

use Models\ContactModel;
use Models\SupportModel;

header('Content-Type: application/json');

$uri = $_SERVER['REQUEST_URI'];
$data = $_POST;

// Handle file uploads
if (!empty($_FILES['attachment']['tmp_name'])) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $filePath = $uploadDir . basename($_FILES['attachment']['name']);
    move_uploaded_file($_FILES['attachment']['tmp_name'], $filePath);
    $data['attachment'] = $filePath;
}

try {
    if (str_contains($uri, '/api/contact')) {
        $request = new ContactRequest($data);
    } elseif (str_contains($uri, '/api/support-request')) {
        $request = new SupportRequest($data);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Endpoint not found']);
        exit;
    }

    $errors = $request->validate();
    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['message' => 'Validation failed', 'errors' => $errors]);
        exit;
    }

    $sent = $request->send();
    $request->sendAcknowledgment();

    if ($sent) {
        echo json_encode(['message' => 'Your request has been successfully submitted!']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to send request.']);
    }

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Server error', 'error' => $e->getMessage()]);
}
