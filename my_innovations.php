<?php
    session_start();
    if(!isset($_SESSION['username']))
    {
        header("Location: login.php"); 
    }

    include 'db_connect.php'; // Include your database connection file
    $user_id = $_SESSION['user_id'];
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
            $stmt = $conn->prepare("INSERT INTO tbl_innovation (user_id, title, description, code) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $title, $description, $code);
            $stmt->execute();
            $stmt->close();

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
        <title>My Innovation</title>
        <!-- Bootstrap 5.3 CSS -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">
        <!-- Bootstrap 5.3 JS -->
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div class="sticky-top"><?php include 'navbar.php';?></div>
        <div class="container my-5">
            <h2>My Innovation</h2>

            <!-- Collapsible Form Button -->
            <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                Add New 
            </button>

            <!-- Collapsible Form -->
            <div class="collapse" id="collapseForm">
                <div class="card card-body">
                    <form action="" method="post">
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php if(isset($title)){echo $title;} ?>">
                            <?php if ($titleErr): ?>
                                <div class="text-danger"><?php echo $titleErr; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?php if(isset($description)){echo $description;} ?></textarea>
                            <?php if ($descriptionErr): ?>
                                <div class="text-danger"><?php echo $descriptionErr; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Code (Optional) -->
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <textarea class="form-control" id="code" name="code" rows="4"><?php if(isset($code)){echo $code;} ?></textarea>
                            <?php if ($codeErr): ?>
                                <div class="text-danger"><?php echo $codeErr; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>

            <!-- List of innovation -->
            <div class="mt-5">
                <h3>Your Innovation</h3>
                <?php
                $sql = "SELECT * FROM tbl_innovation WHERE user_id = $user_id AND status = 1 ORDER BY timestamp DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '
                        <div class="card mb-3">
                            <div class="card-header fs-3">
                                ' . $row["title"] . '
                            </div>
                            <div class="card-body">
                                <p class="card-text">' . $row["description"]. '</p>';
                                echo '<button class="btn bg-body-tertiary text-dark bg-opacity-100 border border-dark-subtle w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#code" aria-expanded="false" aria-controls="collapseForm">
                                    View Code 
                                </button>';
                                echo '<div class="collapse" id="code"><pre><code>' . ($row["code"]) . '</code></pre></div>';    
                                echo '
                                <p class="card-text"><small class="text-muted">Submitted on ' . $row["timestamp"] . '</small></p>
                                <!-- Edit and Delete Buttons -->
                                <a href="edit_innovation.php?id=' . $row["innovation_id"] . '" class="btn btn-warning me-2">Edit</a>
                                <a href="delete_innovation.php?id=' . $row["innovation_id"] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this problem?\');">Delete</a>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p>No Innovation found.</p>';
                }

                $conn->close();
                ?>
            </div>
        </div>
    </body>
</html>
