<?php
/**
 * Fix download file paths in database
 * Changes downloads/* to assets/downloads/*
 */

require_once 'database/db_config.php';

header('Content-Type: text/html');
echo "<h1>Fix Download Paths</h1>";
echo "<pre>";

try {
    $conn = getDBConnection();
    
    // Get all downloads
    $result = $conn->query("SELECT id, title, file_path FROM downloads");
    
    echo "Found " . $result->num_rows . " downloads\n\n";
    
    $updated = 0;
    $skipped = 0;
    
    while ($row = $result->fetch_assoc()) {
        $oldPath = $row['file_path'];
        
        // Check if path starts with 'downloads/'
        if (strpos($oldPath, 'downloads/') === 0) {
            // Replace with 'assets/downloads/'
            $newPath = str_replace('downloads/', 'assets/downloads/', $oldPath);
            
            // Update database
            $stmt = $conn->prepare("UPDATE downloads SET file_path = ? WHERE id = ?");
            $stmt->bind_param('si', $newPath, $row['id']);
            
            if ($stmt->execute()) {
                echo "✓ Updated: {$row['title']}\n";
                echo "  Old: $oldPath\n";
                echo "  New: $newPath\n\n";
                $updated++;
            } else {
                echo "✗ Failed: {$row['title']}\n\n";
            }
            
            $stmt->close();
        } else {
            echo "- Skipped: {$row['title']} (already correct: $oldPath)\n\n";
            $skipped++;
        }
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "Updated: $updated\n";
    echo "Skipped: $skipped\n";
    echo "Total: " . ($updated + $skipped) . "\n";
    
    $conn->close();
    
    echo "\n✓ Done! Downloads should now work correctly.\n";
    echo "<a href='/downloads'>Test downloads page</a>\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
