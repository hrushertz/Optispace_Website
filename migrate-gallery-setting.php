<?php
/**
 * Migration: Ensure gallery_enabled setting exists
 * Run this once on production to ensure the gallery_enabled setting exists
 */

require_once __DIR__ . '/database/db_config.php';

$conn = getDBConnection();

echo "<h2>Gallery Setting Migration</h2>";

// Check if the setting exists
$result = $conn->query("SELECT * FROM site_settings WHERE setting_key = 'gallery_enabled'");

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<p style='color: green;'>✓ gallery_enabled setting already exists</p>";
    echo "<pre>";
    print_r($row);
    echo "</pre>";
} else {
    echo "<p style='color: orange;'>⚠ gallery_enabled setting does NOT exist. Creating it now...</p>";
    
    $stmt = $conn->prepare("INSERT INTO site_settings (setting_key, setting_value, setting_type, description) VALUES (?, ?, ?, ?)");
    $key = 'gallery_enabled';
    $value = '1';
    $type = 'boolean';
    $desc = 'Enable or disable the gallery section';
    $stmt->bind_param("ssss", $key, $value, $type, $desc);
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>✓ Successfully created gallery_enabled setting</p>";
        
        // Verify
        $result = $conn->query("SELECT * FROM site_settings WHERE setting_key = 'gallery_enabled'");
        if ($result && $row = $result->fetch_assoc()) {
            echo "<pre>";
            print_r($row);
            echo "</pre>";
        }
    } else {
        echo "<p style='color: red;'>✗ Failed to create setting: " . $conn->error . "</p>";
    }
    $stmt->close();
}

$conn->close();

echo "<hr>";
echo "<p><a href='index.php'>← Back to Home</a></p>";
?>
