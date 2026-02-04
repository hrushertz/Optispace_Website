<?php
require_once 'db_config.php';
$conn = getDBConnection();

$pageName = 'blogs';
$eyebrow = 'Knowledge Hub';
$heading = 'Insights & <span>Best Practices</span>';
$subheading = 'Expert perspectives on lean manufacturing, factory optimization, and operational excellence. Learn from real-world implementations and industry expertise.';
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
        echo "Blogs page banner seeded successfully.";
    } else {
        echo "Error seeding banner: " . $conn->error;
    }
} else {
    echo "Blogs page banner already exists.";
}

$conn->close();
?>