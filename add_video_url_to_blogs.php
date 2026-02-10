<?php
require_once __DIR__ . '/database/db_config.php';

$conn = getDBConnection();

// Check if column exists
$check = $conn->query("SHOW COLUMNS FROM blogs LIKE 'video_url'");

if ($check->num_rows == 0) {
    // Column doesn't exist, so add it
    $sql = "ALTER TABLE blogs ADD COLUMN video_url VARCHAR(255) DEFAULT NULL AFTER meta_description";

    if ($conn->query($sql) === TRUE) {
        echo "Successfully added 'video_url' column to 'blogs' table.";
    } else {
        echo "Error updating table: " . $conn->error;
    }
} else {
    echo "Column 'video_url' already exists in 'blogs' table.";
}

$conn->close();
?>