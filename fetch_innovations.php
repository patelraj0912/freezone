<?php
include 'db_connect.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$sql = "SELECT * FROM tbl_innovation WHERE status = 1";

if (!empty($query)) {
    $sql .= " AND (title LIKE '%$query%' OR description LIKE '%$query%')";
}

$sql .= " ORDER BY timestamp DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '
        <div class="card mb-3">
            <div class="card-header fs-3">
                '.$row["title"] . '
            </div>
            <div class="card-body">
                <p class="card-text">'.$row["description"] . '</p>';
        echo '<button class="btn bg-body-tertiary text-dark bg-opacity-100 border border-dark-subtle w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm'.$row["innovation_id"].'" >
            View Code 
        </button>';
        echo '<div class="collapse" id="collapseForm'.$row["innovation_id"].'"><pre><code>' . ($row["code"]) . '</code></pre></div>';
        echo '
                <p class="card-text"><small class="text-muted">Submitted on ' .($row["timestamp"]) . '</small></p>
            </div>
        </div>';
    }
} else {
    echo '<p>No Innovation found.</p>';
}

$conn->close();
?>
