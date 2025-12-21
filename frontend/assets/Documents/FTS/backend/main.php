<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php

session_start();
include 'manager.php';
$_SESSION['username']="";
$_SESSION['id']=0;
$conn=new mysqli('localhost','u573466658_root','Ctc@123flex','u573466658_hackthon');

if($conn->connect_error){
    http_response_code(400); // Internal Server Error
    echo "Failed connect to the Database.";
}
else{
    $User=new manager($conn);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['confirm_password'])) {
        $username = $_POST['username'];
        $class=$_POST['class'];
        $password = $_POST['password'];
        $confirm_password=$_POST['confirm_password'];
        // Basic validation (you should add more robust validation)
        if (empty($username) || empty($password) || empty($confirm_password)) {
            http_response_code(400); // Bad Request
            echo "All fields are required.";
        }
        if ( strlen($password)) {
            http_response_code(400); // Bad Request
            echo "Passwords do not match.";
            
        }
        if ($password !== $confirm_password) {
            http_response_code(400); // Bad Request
            echo "Passwords do not match.";
            
        }
        $success =$User->register($username,$password,$class);
        if ($success) {
            http_response_code(200); // OK
        } else {
            http_response_code(500); // Internal Server Error
            echo " register Failed to create account. Please try again later.";
        }
    } 
    else if ($_SERVER['REQUEST_METHOD'] === 'POST' &&  empty($_POST['confirm_password']) ) {
        $username = $_POST['username'];
        $passkey = $_POST['password'];
        $results= $User->login($username,$passkey);
        if ($results != false) {
            http_response_code(200);
            $return=trim($results) == 'Admin' ? 'backend/admin/admin.html':'navbar/home.html'; 
            echo $return;
        } else {
            http_response_code(500); // Internal Server Error
            echo $results;
        }
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        // Return the session variable as JSON
        $results = $User->users();
        http_response_code(200);
        $users_data=array('session_value' => $_SESSION['username'],'session_id' => $_SESSION['id']);
        $result=$results+$users_data;
        header('Content-Type: application/json');
        echo json_encode($result);
        exit; // Stop script execution
    }
    // else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['compare_files'])) {
    //     // Handle file comparison
    //     /* $userId = $_POST['user_id'];
    //     $file = $_FILES['uploaded_file'];
    //     $result = $User->comparefiles($userId, $file);
    //     http_response_code(200);
    //     echo json_encode($result);
    //     exit; */
    // }
     else {
        http_response_code(405); // Method Not Allowed
        echo "Submission failed. Please try again. ";
    }
}

?>