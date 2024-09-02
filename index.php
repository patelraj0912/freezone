<?php
session_start();
if(!isset($_SESSION['username']))
{
    header("Location: login.php"); 
}

include 'db_connect.php'; // Include your database connection file

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="sticky-top"><?php include 'navbar.php'; ?></div>
    <div class="container-fluid mb-2">
        <input id="searchInput" class="form-control" type="search" placeholder="Search..." aria-label="Search">
    </div>
    <div class="container-fluid">
        <div id="cardContainer" class="mt-0">
            <!-- Cards will be dynamically updated here -->
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function fetchResults(query = '') {
                $.ajax({
                    url: 'fetch_innovations.php', // PHP file to process the search
                    method: 'GET',
                    data: {query: query},
                    success: function(data) {
                        $('#cardContainer').html(data);
                    }
                });
            }

            // Fetch all results initially
            fetchResults();

            // Trigger search on input change
            $('#searchInput').on('input', function() {
                let query = $(this).val();
                fetchResults(query);
            });
        });
    </script>
</body>
</html>
