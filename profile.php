<?php 
    session_start();
    if(!isset($_SESSION['username']))
    {
        header("Location: login.php"); 
    }
    $user_id= $_SESSION['user_id']; 
    include 'db_connect.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <!-- Bootstrap 5.3 JS -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="sticky-top"><?php include 'navbar.php';?></div>

    <div class="container-fluid">
        <div class="card my-3">
            <ul class="list-group list-group-flush">
                <li class="list-group-item" type="button" data-bs-toggle="collapse" data-bs-target="#userinfo">User Info</li>
                    <div class="collapse" id="userinfo">
                        <?php
                        $sql = "SELECT * FROM tbl_users WHERE user_id = $user_id AND status = 1";
                        $result = $conn->query($sql);

                        if ($result->num_rows == 1) {
                            while($row = $result->fetch_assoc()) {
                        ?>
                            <form action="update_user.php" class="px-3 py-4" method="POST" >
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username']; ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?> "require>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" min="10" class="form-control" id="phone" name="phone" value="<?php echo $row['phone']; ?>" require>
                                </div>
                                <div id="error-msg" class="text-danger mb-3"></div>
                                <button type="submit" class="btn btn-primary w-100">Update</button>
                            </form>
                        <?php }
                        }?>
                    </div>
                
                <li class="list-group-item" type="button" data-bs-toggle="collapse" data-bs-target="#changepassword">Change Password</li>
                    <div class="collapse" id="changepassword">
                        <form action="change_password.php" class="px-3 py-4" method="POST">
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" require>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" min="10" class="form-control" id="cnfpassword" name="cnfpassword" require>
                            </div>
                            <div id="error-msg" class="text-danger mb-3"></div>
                            <button type="submit" class="btn btn-primary w-100">Change Password</button>
                        </form>
                    </div>
            </ul>
        </div>
        
        <!-- logout -->
        <div class="card my-2">
            <form action="logout.php" method="post">
                <button type="submit" class="btn btn-outline-warning w-100">Logout</button>
            </form>
        </div>

        <!-- delete account -->
        <div class="card mt-5">
            <form action="delete_account.php" method="post">
                <button type="submit" class="btn btn-outline-danger w-100">Delete Account</button>
            </form>
        </div>
    </div>
    
</body>
</html>