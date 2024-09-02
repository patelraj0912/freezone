<?php 
    session_start();
    if(!isset($_SESSION['username']))
    {
        header("Location: login.php"); 
    }
    
    include 'db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get POST data
        // $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $user_id= $_SESSION['user_id']; 

        $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0){

        $stmt = $conn->prepare("UPDATE tbl_users SET email = ? ,phone = ? where user_id=?");
        $stmt->bind_param("ssi", $email, $phone,$user_id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        // Redirect to avoid form resubmission
        header("Location: profile.php");
        exit();}
        else{
            echo "USER ALREADY EXIST";
        }
    }
?>