<?php
/**
 * Script to populate missing slugs for live projects
 * Run this once to update existing records
 */

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

echo "Checking for projects with missing slugs...\n";

$query = "SELECT id, title FROM live_projects WHERE slug IS NULL OR slug = ''";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "Found " . $result->num_rows . " projects to update.\n";

    $updateStmt = $conn->prepare("UPDATE live_projects SET slug = ? WHERE id = ?");

    while ($row = $result->fetch_assoc()) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $row['title'])));
        // Remove duplicate hyphens
        $slug = preg_replace('/-+/', '-', $slug);
        // Remove trailing hyphens
        $slug = trim($slug, '-');

        $updateStmt->bind_param("si", $slug, $row['id']);

        if ($updateStmt->execute()) {
            echo "Updated Project ID " . $row['id'] . ": " . $row['title'] . " -> " . $slug . "\n";
        } else {
            echo "Failed to update Project ID " . $row['id'] . ": " . $conn->error . "\n";
        }
    }
    $updateStmt->close();
    echo "Update complete.\n";
} else {
    echo "No projects need updating.\n";
}

$conn->close();
?>