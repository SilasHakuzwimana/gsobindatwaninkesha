<?php

namespace Controllers;

use Config\Database;
use Models\ContactModel;
use Services\CloudinaryService;

class ContactController
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

        // Save to database using Contact model
        $db = Database::getConnection();
        $contact = new ContactModel($db, [
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $messageText,
            'attachment' => $attachmentUrl
        ]);

        $errors = $contact->validate();
        if (!empty($errors)) {
            echo json_encode(['message' => 'Validation failed', 'errors' => $errors]);
            return;
        }

        if (!$contact->save()) {
            echo json_encode(['message' => 'Failed to save contact to database.']);
            return;
        }

        // Send emails
        $contact->send();
        $contact->sendAcknowledgment();

        echo json_encode(['message' => 'Your message has been sent successfully!']);
    }
}
