<?php
/**
 * Verify download files exist
 */

header('Content-Type: text/html');
echo "<h1>Verify Download Files</h1>";
echo "<pre>";

// Check local filesystem
$downloadsDir = 'assets/downloads';
echo "Checking: /$downloadsDir/\n\n";

if (is_dir($downloadsDir)) {
    echo "✓ Directory exists: /$downloadsDir/\n";
    echo "Permissions: " . substr(sprintf('%o', fileperms($downloadsDir)), -4) . "\n\n";
    
    $files = array_diff(scandir($downloadsDir), ['.', '..']);
    
    if (empty($files)) {
        echo "⚠ No files found in directory!\n";
    } else {
        echo "Files found:\n";
        foreach ($files as $file) {
            $path = $downloadsDir . '/' . $file;
            $size = filesize($path);
            $readable = is_readable($path) ? 'YES' : 'NO';
            echo "  ✓ $file\n";
            echo "    Size: " . ($size / 1024 / 1024) . " MB\n";
            echo "    Readable: $readable\n";
            echo "    URL: /$path\n";
            echo "    Full path: " . realpath($path) . "\n\n";
        }
    }
} else {
    echo "✗ Directory NOT found!\n";
    echo "Create it with: mkdir -p assets/downloads\n";
}

// Check database
echo "\n=== DATABASE PATHS ===\n";
try {
    require_once 'database/db_config.php';
    $conn = getDBConnection();
    
    $result = $conn->query("SELECT id, title, file_path FROM downloads WHERE is_active = 1");
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "\nDownload: {$row['title']}\n";
            echo "Database path: {$row['file_path']}\n";
            
            // Check if file exists
            if (file_exists($row['file_path'])) {
                echo "✓ File EXISTS\n";
                echo "  Full URL: /{$row['file_path']}\n";
            } else {
                echo "✗ File NOT FOUND at: {$row['file_path']}\n";
                echo "  Checked location: " . realpath('.') . "/{$row['file_path']}\n";
            }
        }
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

echo "\n</pre>";
?>
