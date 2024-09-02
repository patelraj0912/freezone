<?php
    session_start();
    if(!isset($_SESSION['username']))
    {
        header("Location: login.php"); 
    }
    $innovation_id = $_GET['id'];
    include 'db_connect.php'; // Include your database connection file

    $titleErr = $descriptionErr = $codeErr= "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get POST data
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $code = trim($_POST['code']);
        $err_status = 1;
        // Validate title

        if($title == ""){
            $titleErr = "Title can't be Empty.";
            $err_status = 0;
        }
        if($description == ""){
            $descriptionErr = "description can't be Empty.";
            $err_status = 0;
        }
        if($code == ""){
            $codeErr = "Code can't be Empty.";
            $err_status = 0;
        }

        if($err_status==1)
        {
            
            
            $stmt = $conn->prepare("UPDATE tbl_innovation SET title = ? , description = ? ,code = ? where innovation_id=?");
            $stmt->bind_param("sssi", $title, $description, $code,$innovation_id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            // Redirect to avoid form resubmission
            header("Location: my_innovations.php");
            exit();
        }
        
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Innovation</title>
        <!-- Bootstrap 5.3 CSS -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">
        <!-- Bootstrap 5.3 JS -->
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <style>
            .profile-image {
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #4a7766;
                color: white;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                font-weight: bold;
                font-size: 3rem;
                margin: 0px auto;
            }
        </style>
    </head>
    <body>
        <div class="sticky-top"><?php include 'navbar.php';?></div>
        <div class="container my-5">
            <h2>Edit Innovation</h2>
            <?php
                $sql = "SELECT * FROM tbl_innovation WHERE innovation_id = $innovation_id AND status = 1";
                $result = $conn->query($sql);

                if ($result->num_rows == 1) {
                    while($row = $result->fetch_assoc()) {
                ?>
                    <div class="card card-body">
                        <form action="" method="post">
                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo $row['title']; ?>">
                                <?php if ($titleErr): ?>
                                    <div class="text-danger"><?php echo $titleErr; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4"><?php echo $row['description']; ?></textarea>
                                <?php if ($descriptionErr): ?>
                                    <div class="text-danger"><?php echo $descriptionErr; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Code (Optional) -->
                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <textarea class="form-control" id="code" name="code" rows="20"><?php echo $row['code']; ?></textarea>
                                <?php if ($codeErr): ?>
                                    <div class="text-danger"><?php echo $codeErr; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                <?php
                    } 
                } 
                ?>
        </div>
    </body>
</html>
