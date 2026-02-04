<?php
// Check what outputs before session
ob_start();

$pageTitle = "Users";
require_once __DIR__ . '/includes/auth.php';

$output = ob_get_clean();

if ($output !== '') {
    echo "Output found before session (" . strlen($output) . " bytes):\n";
    echo "Hex: " . bin2hex($output) . "\n";
    echo "Content: " . var_export($output, true) . "\n";
} else {
    echo "No output before session - checking auth.php itself\n";
    
    // Check auth.php for issues
    $authFile = __DIR__ . '/includes/auth.php';
    $content = file_get_contents($authFile);
    
    if (substr($content, 0, 5) !== '<?php') {
        echo "❌ auth.php has content before <?php\n";
        echo "First 50 bytes (hex): " . bin2hex(substr($content, 0, 50)) . "\n";
    } else {
        echo "✓ auth.php starts correctly\n";
    }
    
    // Check what auth.php includes
    $dbConfigFile = dirname(__DIR__) . '/database/db_config.php';
    $dbContent = file_get_contents($dbConfigFile);
    
    if (substr($dbContent, 0, 5) !== '<?php') {
        echo "❌ db_config.php has content before <?php\n";
        echo "First 50 bytes (hex): " . bin2hex(substr($dbContent, 0, 50)) . "\n";
    } else {
        echo "✓ db_config.php starts correctly\n";
    }
}
