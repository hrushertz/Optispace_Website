<?php
require_once 'db_config.php';
$conn = getDBConnection();

$pageName = 'about';
$eyebrow = 'Since 2004';
$heading = 'Lean Experts Who <span>Design Factories</span>';
$subheading = "We're not traditional architects. We're lean manufacturing consultants who understand that the building should serve the process — not the other way around.";
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
        echo "About page banner seeded successfully.";
    } else {
        echo "Error seeding banner: " . $conn->error;
    }
} else {
    echo "About page banner already exists.";
}

$conn->close();
?>