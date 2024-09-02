<?php
    session_start();
    if(!isset($_SESSION['username']))
        {
            header("Location: login.php"); 
        }
    include 'db_connect.php';
    $innovation_id = $_GET['id'];
    echo $innovation_id;

    $stmt = $conn->prepare("UPDATE tbl_innovation SET status = 0 WHERE innovation_id = $innovation_id");
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: my_innovations.php");
    exit();
?>