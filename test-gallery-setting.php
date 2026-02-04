<?php
/**
 * Quick test to check gallery_enabled setting
 */

require_once __DIR__ . '/database/db_config.php';
require_once __DIR__ . '/includes/config.php';

$conn = getDBConnection();

echo "<h2>Gallery Setting Test</h2>";

// Check raw database value
$result = $conn->query("SELECT * FROM site_settings WHERE setting_key = 'gallery_enabled'");
if ($result && $row = $result->fetch_assoc()) {
    echo "<h3>Database Value:</h3>";
    echo "<pre>";
    print_r($row);
    echo "</pre>";
}

// Check getSiteSetting function
echo "<h3>getSiteSetting() Result:</h3>";
$settingValue = getSiteSetting('gallery_enabled', true);
echo "Value: ";
var_dump($settingValue);
echo "<br>Type: " . gettype($settingValue);

// Check isGalleryEnabled function
echo "<h3>isGalleryEnabled() Result:</h3>";
$enabled = isGalleryEnabled();
var_dump($enabled);

$conn->close();
?>
