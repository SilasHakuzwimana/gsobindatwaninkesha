<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your manager.php which should contain the comparefiles function
include 'manager.php';

// Create a database connection
$conn = new mysqli('localhost', 'u573466658_root', 'Ctc@123flex', 'u573466658_hackthon');  // Replace with your actual database credentials

if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'error' => 'Failed to connect to the Database: ' . $conn->connect_error]);
    exit();
}

// Instantiate your manager class
$User = new manager($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['compare_files']) && isset($_FILES['uploaded_file'])) {
    $userId = $_POST['user_id'];
    $file = $_FILES['uploaded_file'];

    // Read the file content
    $uploadedData = file_get_contents($file['tmp_name']);

    if ($uploadedData === false) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'error' => 'Failed to read the uploaded file.']);
        exit();
    }

    // Call the comparefiles function from your manager class
    $results = $User->comparefiles($userId, $uploadedData);
    if ($results === false) {
        $result = $results;
    } else {
        $result = ['status' => 'error', 'message' => 'where are you']; // Access message directly.
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}
else if($_SERVER['REQUEST_METHOD'] === 'POST'&& !isset($_POST['compare_files']) && !isset($_FILES['uploaded_file'])){
    $result=$User->logout();
    json_encode($result);
}
 else {
    // Handle cases where the request is not a POST with the expected parameters
    http_response_code(400); // Bad Request
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'error' => 'Invalid request.  Expected POST with compare_files and uploaded_file.']);
    exit();
}
?>
