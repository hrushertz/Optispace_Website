<?php
require_once 'db_config.php';
$conn = getDBConnection();

$pageName = 'contact';
$eyebrow = 'Get In Touch';
$heading = "Let's Start a <span>Conversation</span>";
$subheading = "Ready to transform your factory? We'd love to hear about your project and explore how we can help.";
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
        echo "Contact page banner seeded successfully.";
    } else {
        echo "Error seeding banner: " . $conn->error;
    }
} else {
    echo "Contact page banner already exists.";
}

$conn->close();
?>