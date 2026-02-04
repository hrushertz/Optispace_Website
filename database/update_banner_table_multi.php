<?php
/**
 * Update Banner Table for Multiple Banners
 */
require_once 'db_config.php';

$conn = getDBConnection();

// 1. Drop Unique Key 'unique_page'
echo "Attempting to drop unique key 'unique_page'...<br>";
// Check if key exists (pseudo-check by just trying to drop, catching error is easier but let's query first)
$keyCheck = $conn->query("SHOW INDEX FROM banner_settings WHERE Key_name = 'unique_page'");
if ($keyCheck->num_rows > 0) {
    if ($conn->query("ALTER TABLE banner_settings DROP INDEX unique_page")) {
        echo "Dropped unique key 'unique_page'.<br>";
    } else {
        echo "Error dropping key: " . $conn->error . "<br>";
    }
} else {
    echo "Key 'unique_page' not found (maybe already dropped).<br>";
}

// 2. Add 'sort_order' column
if (!$conn->query("SHOW COLUMNS FROM banner_settings LIKE 'sort_order'")->num_rows) {
    if ($conn->query("ALTER TABLE banner_settings ADD COLUMN sort_order INT DEFAULT 0 AFTER subheading")) {
        echo "Added 'sort_order' column.<br>";
    } else {
        echo "Error adding 'sort_order': " . $conn->error . "<br>";
    }
} else {
    echo "'sort_order' already exists.<br>";
}

// 3. Add 'is_active' column
if (!$conn->query("SHOW COLUMNS FROM banner_settings LIKE 'is_active'")->num_rows) {
    if ($conn->query("ALTER TABLE banner_settings ADD COLUMN is_active TINYINT DEFAULT 1 AFTER sort_order")) {
        echo "Added 'is_active' column.<br>";
    } else {
        echo "Error adding 'is_active': " . $conn->error . "<br>";
    }
} else {
    echo "'is_active' already exists.<br>";
}

$conn->close();
?>