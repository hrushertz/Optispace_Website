<?php
require_once 'db_config.php';
$conn = getDBConnection();

$pageName = 'gallery';
$eyebrow = 'Visual Portfolio';
$heading = 'Project <span>Gallery</span>';
$subheading = 'Explore our collection of lean factory transformations, facility designs, and successful implementations across diverse industries.';
$imagePath = ''; // Gradient fallback
$isActive = 1;
$sortOrder = 0;

// Check if exists
$check = $conn->prepare("SELECT id FROM banner_settings WHERE page_name = ?");
$check->bind_param("s", $pageName);
$check->execute();
$res = $check->get_result();

if ($res->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO banner_settings (page_name, eyebrow_text, heading_html, subheading, image_path, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssii", $pageName, $eyebrow, $heading, $subheading, $imagePath, $sortOrder, $isActive);
    if ($stmt->execute()) {
        echo "Gallery page banner seeded successfully.";
    } else {
        echo "Error seeding banner: " . $conn->error;
    }
} else {
    echo "Gallery page banner already exists.";
}

$conn->close();
?>