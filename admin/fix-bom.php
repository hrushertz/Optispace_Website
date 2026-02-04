<?php
// BOM Remover Script - Run this on the server to fix BOM issues
$files = [
    __DIR__ . '/includes/auth.php',
    dirname(__DIR__) . '/database/db_config.php',
    dirname(__DIR__) . '/includes/config.php',
    dirname(__DIR__) . '/includes/mailer.php',
    dirname(__DIR__) . '/env_loader.php',
    __DIR__ . '/users.php',
];

echo "Removing BOM from files...\n\n";

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "SKIP: " . basename($file) . " - Not found\n";
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Check for BOM
    if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
        // Remove BOM
        $content = substr($content, 3);
        file_put_contents($file, $content);
        echo "FIXED: " . basename($file) . " - BOM removed\n";
    } else {
        echo "OK: " . basename($file) . " - No BOM\n";
    }
}

echo "\nDone! Now try accessing users.php\n";
