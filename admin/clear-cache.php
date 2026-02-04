<?php
/**
 * Clear PHP Opcache
 */

if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "✓ Opcache cleared successfully!<br>";
    } else {
        echo "❌ Failed to clear opcache<br>";
    }
} else {
    echo "ℹ️ Opcache is not enabled or not available<br>";
}

// Also clear any file stat cache
clearstatcache(true);
echo "✓ File stat cache cleared<br><br>";

echo "<strong>Now try accessing:</strong><br>";
echo '<a href="users.php">admin/users.php</a>';
?>
