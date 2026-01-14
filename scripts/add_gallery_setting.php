<?php
/**
 * Add Gallery Setting to Database
 * Run this script once to add the gallery_enabled setting to the site_settings table
 */

require_once __DIR__ . '/../database/db_config.php';

echo "Adding gallery_enabled setting to database...\n\n";

try {
    $conn = getDBConnection();
    
    // Check if the setting already exists
    $checkStmt = $conn->prepare("SELECT id FROM site_settings WHERE setting_key = 'gallery_enabled'");
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "✓ Gallery setting already exists in the database.\n";
        $checkStmt->close();
    } else {
        $checkStmt->close();
        
        // Insert the gallery_enabled setting
        $stmt = $conn->prepare("INSERT INTO site_settings (setting_key, setting_value, setting_type, description) VALUES ('gallery_enabled', '1', 'boolean', 'Enable or disable the gallery section')");
        
        if ($stmt->execute()) {
            echo "✓ Successfully added gallery_enabled setting to the database.\n";
            echo "✓ Gallery is enabled by default.\n\n";
            echo "You can now toggle the gallery from the Admin Settings page:\n";
            echo "Admin Panel → Settings → Gallery Section\n";
        } else {
            echo "✗ Error adding gallery_enabled setting: " . $stmt->error . "\n";
        }
        
        $stmt->close();
    }
    
    $conn->close();
    
    echo "\n✓ Script completed successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
