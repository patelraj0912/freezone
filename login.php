<?php
    session_start();
    if(isset($_SESSION['username']))
    {
        header("Location: index.php"); 
    }
    include 'db_connect.php'; // Include your database connection file

    $error = "";
    $error_username = "";
    $error_password= "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get POST data
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $err_status = 1;

        if($username == ""){
            $error_username = "Username can't be Empty.";
            $err_status = 0;
        }
        if($password == ""){
            $error_password = "Password can't be Empty.";
            $err_status = 0;
        }

        // Query to check if the username exists and is active
        if($err_status==1){
        $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE username = ? AND status = 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $username;
                header("Location: index.php"); // Redirect to profile page
                exit();
            } else {
                $error = "Invalid Cardential.";
            }
        } else {
            $error = "Invalid Cardential.";
        }

        $stmt->close();
        $conn->close();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link href="css/styles.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <h2 class="text-center">Login</h2>
                        <form id="loginForm" class="px-3 py-4" method="POST" action="login.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php if(isset($username)){echo $username;} ?>">
                                <?php if ($error_username): ?>
                                        <div class="text-danger"><?php echo $error_username; ?></div>
                                    <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <?php if ($error_password): ?>
                                        <div class="text-danger"><?php echo $error_password; ?></div>
                                    <?php endif; ?>
                            </div>
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <div id="error-msg" class="text-danger mb-3"></div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <div class="text-center mt-3">
                            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
