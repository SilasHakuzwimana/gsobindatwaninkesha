<?php

namespace Controllers;

use Config\Database;
use Models\SupportModel;
use Services\CloudinaryService;

class SupportController
{
    public function submit()
    {
        header("Content-Type: application/json");

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $messageText = trim($_POST['message'] ?? '');
        $checkbox = isset($_POST['checkbox']);
        $attachmentUrl = null;

        // Validation
        if (!$name || !$email || !$subject || !$messageText || !$checkbox) {
            echo json_encode(['message' => 'All fields are required.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['message' => 'Invalid email address.']);
            return;
        }

        // Upload attachment to Cloudinary
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            try {
                $cloudinary = new CloudinaryService();
                $uploadResult = $cloudinary->upload($_FILES['attachment']['tmp_name'], 'gsob_documents');
                $attachmentUrl = $uploadResult['secure_url'] ?? null;
            } catch (\Exception $e) {
                echo json_encode(['message' => 'File upload failed: ' . $e->getMessage()]);
                return;
            }
        }

        // Save to database using Support model
        $db = Database::getConnection();
        $support = new SupportModel($db, [
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $messageText,
            'attachment' => $attachmentUrl
        ]);

        $errors = $support->validate();
        if (!empty($errors)) {
            echo json_encode(['message' => 'Validation failed', 'errors' => $errors]);
            return;
        }

        if (!$support->save()) {
            echo json_encode(['message' => 'Failed to save support request.']);
            return;
        }

        // Send emails
        $support->send();
        $support->sendAcknowledgment();

        echo json_encode(['message' => 'Support request submitted successfully!']);
    }
}
