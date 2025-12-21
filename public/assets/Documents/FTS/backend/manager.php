<?php 
class manager {
    private $conn=null;
    public function __construct($conn)
    {
        $this->conn=$conn;
    }
    public function login($username,$passkey){
        $login_query="SELECT * FROM `users` WHERE `username`='$username' ";
        $result=$this->conn->query($login_query);
        if ($result) {
            if(mysqli_num_rows($result) == 1){
                while($row=$result->fetch_assoc()) {
                    $_SESSION['username']=$row['username'];
                    $_SESSION['id']=$row['user_id'];
                    if($row['passkey'] === $passkey){
                        if($row['user_level']==='Admin'){
                            return "Admin"; 
                        }else{
                            return "Guest"; 
                        }
                    }
                    else{
                        echo"Incorrect Password";
                        return false;
                    }
                }
            }
            else{
                echo'User not found 2'.$username." ".$passkey;
                return false;
            }

        } else {
            echo'User not found 1';
            return false;

        }
    }
    public function register($username,$passkey,$class){
        $check_query="SELECT username FROM `users` WHERE username ='$username' ";
        $results=$this->conn->query($check_query);
        if($results->num_rows <1){
            $register_query="INSERT INTO `users` (username,class,passkey,user_level) VALUES('$username','$class','$passkey','Guest')";
            $result=$this->conn->query($register_query);
            if ($result) {
                return true;
            }else{
                return false;
            }
        }
        else{
            echo"Username is unavailable";
            return false;
        }
    }
    public function users() {
        $query = "SELECT * FROM `users` WHERE user_level = 'Guest'";
        $result = $this->conn->query($query);
        $users = array(); // Initialize an empty array
        if ($result && $result->num_rows > 0) { //check if the $result is valid
            while ($row = $result->fetch_assoc()) {
                $users[] = $row; // Append each row to the $users array
            }
            return ["status" => "success", "users" => $users]; // Return an associative array
        } else if ($result) {
            return ["status" => "success", "users" => []]; // Return empty array if no users found
        } 
        else {
            return ["status" => "error", "message" => "Error fetching users: " . $this->conn->error]; //error message
        }
    }
    
    public function comparefiles($user_id,$uploadedData){
        
        $user_id=(int) $user_id;
        $query="SELECT * FROM users WHERE `user_id` = $user_id ";
        $query_2="UPDATE `users` SET `submit_status` = 0";
        $query_3="UPDATE `users` SET `status` = 1 WHERE `users`.`user_id` = $user_id ";
        $results=$this->conn->query($query);
        if($results == true){
            if(mysqli_num_rows($results) == 1){
                while($row=$results->fetch_assoc()) {
                    if($row['submit_status'] === boolval(true)){
                        $result=$this->comparefile_util($uploadedData);
                        if($result==true){
                            if ($this->conn->query($query_2) === TRUE) {
                                // Perform the second query
                                if ($this->conn->query($query_3) === TRUE) {
                                    return ['status' => 'success', 'message' => 'Files match. Challenge Completed !'];
                                } else {
                                    return ['status' => 'error', 'message' => 'System Error'];
                                }
                                
                            } else {
                                return ['status' => 'error', 'message' => 'System Error'];
                            } 
                        }else{
                             return ['status' => 'error', 'message' => 'Files do not match.'];
                        }
                    }else{
                        return ['status' => 'error', 'message' => 'The Challenge has ended'];  
                    }
                }
            }
         }else{
            return ['status' => 'error', 'message' => 'System Error'];
        }
    }
    
    private function comparefile_util($uploadedData){
        $file_id=1;
        // Fetch the stored file's binary data
        $storedData='';
        $query = "SELECT data FROM file_table WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("<div class='result'>Error preparing statement: " . $this->conn->error . "</div>");
        }

        $stmt->bind_param('i', $file_id);
        $stmt->execute();
        $stmt->bind_result($storedData);

        if ($stmt->fetch()) {
            // Compare hashes of the data
            $uploadedHash = hash('sha256', $uploadedData);
            $storedHash = hash('sha256', $storedData);

            if ($uploadedHash === $storedHash) {
                return true;
            } else {
               return false;
            }
        } else {
            return ['status' => 'error', 'message' => 'No file found in the database with the provided ID.'];
        }

        $stmt->close();
    }
    public function logout(){
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session
        return ['status' => 'success']; 
    }

  
}

?>