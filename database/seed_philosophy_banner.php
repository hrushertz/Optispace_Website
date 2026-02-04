<?php
require_once 'db_config.php';
$conn = getDBConnection();

$pageName = 'philosophy';
$eyebrow = 'Core Methodology';
$heading = 'The <span>LFB Philosophy</span>';
$subheading = 'The LFB Philosophy Process Before Structure: Leveraging OptiSpace to design high-performance facilities from the inside out.';
// No image path, will rely on gradient or placeholder for now
$imagePath = '';
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
        echo "Philosophy banner seeded successfully.";
    } else {
        echo "Error seeding banner: " . $conn->error;
    }
} else {
    echo "Philosophy banner already exists.";
}

$conn->close();
?>