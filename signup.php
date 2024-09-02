<?php
    session_start();
    
    if(isset($_SESSION['username']))
    {
        header("Location: profile.php"); 
    }
    include 'db_connect.php'; // Connect to your database

    $error = "";
    $error_username = "";
    $error_email = "";
    $error_phone = "";
    $error_password = "";
    $error_confirmpwd="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get POST data
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirmPassword']);

        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);

        $err_staus = 1;

        if($username == "")
        {
            $error_username = "Username can't be Empty.";
            $err_staus = 0;
        }
        if($email == "")
        {
            $err_staus = 0;
            $error_email = "Email can't be Empty.";
        }
        if($phone == "")
        {
            $err_staus = 0;
            $error_phone = "Phone Number can't be Empty.";
        }
        elseif(strlen($phone) != 10)
        {
            $err_staus = 0;
            $error_phone = "Phone Number must be 10 digits.";
        }
        if($password == "")
        {
            $err_staus = 0;
            $error_password = "Password can't be Empty.";
        }
        elseif(!$uppercase || !$lowercase || !$number || strlen($password) < 8){
            $err_staus = 0;
            $error_password = "Password must be at least 8 characters and must contain at least one Lower and Upper Latter,OneDigit";
        }
        if ($password !== $confirmPassword) {
            $err_staus = 0;
            $error_confirmpwd = "Passwords and ConfirmPassword must be same.";
        } 
        
        if($err_staus == 1) {
            // Check if username or email already exists
            $error="";
            $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE username = ? AND status=1 ");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Username already exists.";
            } else {
                // If validation passes, hash the password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Insert user data into the database
                $stmt = $conn->prepare("INSERT INTO tbl_users (username, email, phone, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $phone, $hashedPassword);

                if ($stmt->execute()) {
                    $_SESSION['user_id'] = $conn->insert_id;
                    $_SESSION['username'] = $username;
                    // setcookie('username', $username, time() + (86400 * 30), "/"); // 1 day = 86400 seconds
                    header("Location: index.php"); // Redirect to profile page
                    exit();
                } else {
                    $error = "Error: " . $stmt->error;
                }

                $stmt->close();
            }
        }
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Problem Discussion</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">Create your account</h2>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form id="signupForm" method="POST" action="signup.php" >
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php if(isset($username)){echo $username;} ?>">
                                <?php if ($error_username): ?>
                                    <div class="text-danger"><?php echo $error_username; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php if(isset($email)){echo $email;} ?>">
                                <?php if ($error_email): ?>
                                    <div class="text-danger"><?php echo $error_email; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php if(isset($phone)){echo $phone;} ?>">
                                <?php if ($error_phone): ?>
                                    <div class="text-danger"><?php echo $error_phone; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="<?php if(isset($password)){echo $password;} ?>">
                                <?php if ($error_password): ?>
                                    <div class="text-danger"><?php echo $error_password; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                                <?php if ($error_confirmpwd): ?>
                                    <div class="text-danger"><?php echo $error_confirmpwd; ?></div>
                                <?php endif; ?>
                            </div>
                            <div id="error-msg" class="text-danger mb-3"></div>
                            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                        </form>
                    </div>
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="login.php">Log in</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
