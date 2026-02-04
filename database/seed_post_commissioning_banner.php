<?php
require_once 'db_config.php';
$conn = getDBConnection();

$pageName = 'post-commissioning';
$eyebrow = 'Post-Commissioning Support'; // Matching existing eyebrow text
$heading = 'LFB Post-Commissioning <span>Support</span>'; // Matching existing styling
$subheading = 'Completion is not the finish line it is the starting point of performance. We ensure the transition from construction to operation is seamless, guaranteeing that the LFB design delivers on its promise the moment production begins.';
$imagePath = ''; // Fallback
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
        echo "Post-Commissioning banner seeded successfully.";
    } else {
        echo "Error seeding banner: " . $conn->error;
    }
} else {
    echo "Post-Commissioning banner already exists.";
}

$conn->close();
?>