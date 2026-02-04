<?php
/**
 * Setup Banner Settings Table
 */
require_once 'db_config.php';

$conn = getDBConnection();

// Create banner_settings table
$sql = "CREATE TABLE IF NOT EXISTS banner_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_name VARCHAR(50) NOT NULL,
    image_path VARCHAR(255),
    eyebrow_text VARCHAR(100),
    heading_html TEXT,
    subheading TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_page (page_name)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'banner_settings' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert default row for 'home' if it doesn't exist
$check = $conn->query("SELECT id FROM banner_settings WHERE page_name = 'home'");
if ($check->num_rows == 0) {
    // We'll leave it empty for now, or we could seed it with current values.
    // Let's seed it with current values to make the transition smoother.
    $defaultImage = 'assets/images/banners/home-hero.jpg'; // Assuming there might be one, or we leave it null to use hardcoded fallback initially
    $defaultEyebrow = 'Factory Design Experts';
    $defaultHeading = 'Lean Factory Building for <span>Future‑Ready</span> Plants';
    $defaultSubheading = 'Design the process first, then the building—cut internal travel, energy costs, and WIP before construction begins. Transform your manufacturing operations with our inside-out approach.';

    $stmt = $conn->prepare("INSERT INTO banner_settings (page_name, eyebrow_text, heading_html, subheading) VALUES (?, ?, ?, ?)");
    $page = 'home';
    $stmt->bind_param("ssss", $page, $defaultEyebrow, $defaultHeading, $defaultSubheading);

    if ($stmt->execute()) {
        echo "Default home banner settings inserted.<br>";
    } else {
        echo "Error inserting default settings: " . $stmt->error . "<br>";
    }
} else {
    echo "Home banner settings already exist.<br>";
}

$conn->close();
?>