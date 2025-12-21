<?php
// Connect to your database

$conn=new mysqli('localhost','u573466658_root','Ctc@123flex','u573466658_hackthon');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve the end date from the database
$sql = "SELECT `end_date` FROM `file_table` WHERE `id` = 1"; // Adjust the WHERE clause as needed
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['end_date' => $row['end_date']]);
} else {
    echo json_encode(['error' => 'No end date found']);
}

$conn->close();
?>
