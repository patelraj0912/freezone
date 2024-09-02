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
        $password = trim($_POST['password']);
        $cnfpassword = trim($_POST['cnfpassword']);
        $user_id= $_SESSION['user_id']; 

        if ($password === $cnfpassword){
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE tbl_users SET  password = ? where user_id=?");
            $stmt->bind_param("si", $hashedPassword,$user_id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            // Redirect to avoid form resubmission
            header("Location: profile.php");
            exit();
        }
        else{
            echo "PASSWORD AND CONFIRM PASSWORD MUST BE SAME";
        }
    }
?>