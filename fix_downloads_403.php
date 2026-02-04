<?php
/**
 * Fix Downloads 403 Error
 * Moves files from downloads/ directory and removes it
 */

header('Content-Type: text/html');
echo "<!DOCTYPE html><html><head><title>Fix Downloads 403</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;}.success{color:green;}.error{color:red;}.warning{color:orange;}pre{background:white;padding:15px;border-radius:5px;}</style>";
echo "</head><body><h1>Fix Downloads 403 Error</h1>";

$steps = [];

// Step 1: Check if downloads directory exists
echo "<h2>Step 1: Check downloads/ directory</h2>";
if (is_dir('downloads')) {
    echo "<p class='warning'>⚠ downloads/ directory exists</p>";
    $files = array_diff(scandir('downloads'), ['.', '..']);
    echo "<p>Files found: " . count($files) . "</p>";
    echo "<ul>";
    foreach ($files as $file) {
        echo "<li>" . htmlspecialchars($file) . "</li>";
    }
    echo "</ul>";
    $steps[] = "downloads_dir_exists";
} else {
    echo "<p class='success'>✓ No downloads/ directory (already fixed)</p>";
    echo "<p><a href='/downloads'>Test downloads page now</a></p>";
    exit;
}

// Step 2: Create proper storage directory
echo "<h2>Step 2: Create assets/downloads/ directory</h2>";
if (!is_dir('assets/downloads')) {
    if (mkdir('assets/downloads', 0755, true)) {
        echo "<p class='success'>✓ Created assets/downloads/</p>";
        $steps[] = "created_assets_dir";
    } else {
        echo "<p class='error'>✗ Failed to create assets/downloads/</p>";
    }
} else {
    echo "<p class='success'>✓ assets/downloads/ already exists</p>";
}

// Step 3: Move files
echo "<h2>Step 3: Move files from downloads/ to assets/downloads/</h2>";
$moved = 0;
$failed = 0;
foreach ($files as $file) {
    $source = "downloads/$file";
    $dest = "assets/downloads/$file";
    if (rename($source, $dest)) {
        echo "<p class='success'>✓ Moved: $file</p>";
        $moved++;
    } else {
        echo "<p class='error'>✗ Failed to move: $file</p>";
        $failed++;
    }
}
echo "<p>Moved: $moved file(s), Failed: $failed</p>";

// Step 4: Remove empty downloads directory
echo "<h2>Step 4: Remove downloads/ directory</h2>";
$remaining = array_diff(scandir('downloads'), ['.', '..']);
if (empty($remaining)) {
    if (rmdir('downloads')) {
        echo "<p class='success'>✓ Removed downloads/ directory</p>";
        $steps[] = "removed_dir";
    } else {
        echo "<p class='error'>✗ Failed to remove downloads/ directory</p>";
        echo "<p>You may need to do this manually via FTP/cPanel</p>";
    }
} else {
    echo "<p class='warning'>⚠ Directory not empty, cannot remove</p>";
    echo "<p>Remaining files:</p><ul>";
    foreach ($remaining as $file) {
        echo "<li>$file</li>";
    }
    echo "</ul>";
}

// Step 5: Test
echo "<h2>Step 5: Test</h2>";
if (in_array('removed_dir', $steps)) {
    echo "<p class='success'>✓ Fix complete! The downloads/ directory has been removed.</p>";
    echo "<p><strong>Test now:</strong> <a href='/downloads' target='_blank'>Visit /downloads page</a></p>";
    echo "<p>The page should now work correctly.</p>";
} else {
    echo "<p class='error'>Could not complete automatic fix.</p>";
    echo "<p><strong>Manual steps:</strong></p>";
    echo "<ol>";
    echo "<li>Via cPanel File Manager or FTP, delete the 'downloads' directory</li>";
    echo "<li>Files have been moved to assets/downloads/</li>";
    echo "<li>Then test /downloads page</li>";
    echo "</ol>";
}

// Summary
echo "<h2>Summary</h2>";
echo "<pre>";
echo "Files moved: $moved\n";
echo "Files failed: $failed\n";
echo "Directory removed: " . (in_array('removed_dir', $steps) ? 'YES' : 'NO') . "\n";
echo "\nDownload files are now in: assets/downloads/\n";
echo "</pre>";

echo "</body></html>";
?>
