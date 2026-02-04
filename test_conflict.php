<?php
// Quick check for conflicts
header('Content-Type: text/plain');

echo "=== DOWNLOADS CONFLICT CHECK ===\n\n";

// Check if there's a downloads directory
if (is_dir('downloads')) {
    echo "⚠ WARNING: 'downloads' DIRECTORY EXISTS!\n";
    echo "This causes Apache to prioritize the directory over downloads.php\n";
    echo "Directory permissions: " . substr(sprintf('%o', fileperms('downloads')), -4) . "\n";
    echo "\nContents of downloads/ directory:\n";
    $files = scandir('downloads');
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "  - $file\n";
        }
    }
    echo "\n🔧 FIX: Remove the downloads/ directory or rename downloads.php to resources.php\n";
} else {
    echo "✓ No 'downloads' directory found - good!\n";
}

echo "\n";

// Check file
if (file_exists('downloads.php')) {
    echo "✓ downloads.php file exists\n";
    echo "  Size: " . filesize('downloads.php') . " bytes\n";
    echo "  Permissions: " . substr(sprintf('%o', fileperms('downloads.php')), -4) . "\n";
} else {
    echo "✗ downloads.php file NOT found\n";
}

echo "\n=== .HTACCESS CHECK ===\n";
if (file_exists('.htaccess')) {
    echo "✓ .htaccess exists\n";
    echo "\nRelevant rules:\n";
    $htaccess = file_get_contents('.htaccess');
    $lines = explode("\n", $htaccess);
    foreach ($lines as $line) {
        if (stripos($line, 'rewrite') !== false || stripos($line, 'redirect') !== false) {
            echo "  " . trim($line) . "\n";
        }
    }
}

echo "\n=== RECOMMENDATION ===\n";
if (is_dir('downloads')) {
    echo "The 403 error is likely because:\n";
    echo "1. Apache finds a 'downloads/' directory\n";
    echo "2. Directory has no index file\n";
    echo "3. Options -Indexes prevents directory listing\n";
    echo "4. Result: 403 Forbidden\n\n";
    echo "SOLUTION: Rename or remove the downloads/ directory\n";
} else {
    echo "Check mod_security logs or Apache error logs for blocks on 'downloads' keyword\n";
}
?>
