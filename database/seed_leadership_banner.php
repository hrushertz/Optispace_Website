<?php
require_once 'db_config.php';
$conn = getDBConnection();

$pageName = 'leadership';
$eyebrow = 'Meet Our Founder';
$heading = 'Leadership <span>& Vision</span>';
$subheading = "Practitioners who combine Lean, Six Sigma, and factory architecture experience to deliver extraordinary results.";
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
        echo "Leadership page banner seeded successfully.";
    } else {
        echo "Error seeding banner: " . $conn->error;
    }
} else {
    echo "Leadership page banner already exists.";
}

$conn->close();
?>