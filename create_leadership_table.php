<?php
require_once 'database/db_config.php';
$conn = getDBConnection();

$sql = "CREATE TABLE IF NOT EXISTS leadership (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    designation VARCHAR(255) NOT NULL,
    sub_designation VARCHAR(255),
    location VARCHAR(255),
    image_path VARCHAR(255),
    quote TEXT,
    education_items JSON,
    experience_items JSON,
    recognition_items JSON,
    skills TEXT,
    philosophy_content TEXT,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'leadership' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$conn->close();
?>