<?php
/**
 * Downloads Page Diagnostic Script
 * Upload this to production to diagnose the 403 error
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Downloads Debug</title></head><body>";
echo "<h1>Downloads Page Diagnostics</h1>";
echo "<pre>";

echo "\n=== STEP 1: Check PHP Version ===\n";
echo "PHP Version: " . phpversion() . "\n";

echo "\n=== STEP 2: Check File Existence ===\n";
$files = [
    'downloads.php',
    'database/db_config.php',
    'env_loader.php',
    '.env',
    'includes/header.php',
    'includes/footer.php'
];

foreach ($files as $file) {
    $exists = file_exists($file);
    $readable = is_readable($file);
    echo sprintf("%-30s Exists: %s | Readable: %s\n", 
        $file, 
        $exists ? 'YES' : 'NO', 
        $readable ? 'YES' : 'NO'
    );
}

echo "\n=== STEP 3: Check env() Function ===\n";
try {
    require_once 'env_loader.php';
    echo "env_loader.php loaded: YES\n";
    echo "env() function exists: " . (function_exists('env') ? 'YES' : 'NO') . "\n";
    
    if (function_exists('env')) {
        echo "Testing env('DB_HOST', 'default'): " . env('DB_HOST', 'default') . "\n";
    }
} catch (Exception $e) {
    echo "ERROR loading env_loader.php: " . $e->getMessage() . "\n";
}

echo "\n=== STEP 4: Check Database Config ===\n";
try {
    require_once 'database/db_config.php';
    echo "db_config.php loaded: YES\n";
    echo "DB_HOST defined: " . (defined('DB_HOST') ? DB_HOST : 'NO') . "\n";
    echo "DB_USER defined: " . (defined('DB_USER') ? DB_USER : 'NO') . "\n";
    echo "DB_NAME defined: " . (defined('DB_NAME') ? DB_NAME : 'NO') . "\n";
    echo "getDBConnection() exists: " . (function_exists('getDBConnection') ? 'YES' : 'NO') . "\n";
} catch (Exception $e) {
    echo "ERROR loading db_config.php: " . $e->getMessage() . "\n";
}

echo "\n=== STEP 5: Test Database Connection ===\n";
try {
    if (function_exists('getDBConnection')) {
        $conn = getDBConnection();
        echo "Database connection: SUCCESS\n";
        echo "Database selected: " . DB_NAME . "\n";
        
        // Test query
        $result = $conn->query("SELECT COUNT(*) as count FROM download_categories WHERE is_active = 1");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "Active categories count: " . $row['count'] . "\n";
        }
        
        $conn->close();
    } else {
        echo "ERROR: getDBConnection() function not available\n";
    }
} catch (Exception $e) {
    echo "ERROR connecting to database: " . $e->getMessage() . "\n";
}

echo "\n=== STEP 6: Check Apache/Server Variables ===\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Not set') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'Not set') . "\n";
echo "SERVER_SOFTWARE: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Not set') . "\n";

echo "\n=== STEP 7: Check File Permissions ===\n";
foreach ($files as $file) {
    if (file_exists($file)) {
        $perms = fileperms($file);
        $perms_oct = substr(sprintf('%o', $perms), -4);
        echo sprintf("%-30s Permissions: %s\n", $file, $perms_oct);
    }
}

echo "\n=== STEP 8: Test Basic Include ===\n";
try {
    ob_start();
    include 'includes/header.php';
    $header_output = ob_get_clean();
    echo "header.php included: SUCCESS (output length: " . strlen($header_output) . " bytes)\n";
} catch (Exception $e) {
    echo "ERROR including header.php: " . $e->getMessage() . "\n";
}

echo "\n=== DIAGNOSIS COMPLETE ===\n";
echo "If you see this message, PHP is executing correctly.\n";
echo "If downloads.php shows 403, check:\n";
echo "1. .htaccess restrictions\n";
echo "2. Apache error logs\n";
echo "3. SELinux/AppArmor on production\n";
echo "4. File ownership and permissions\n";

echo "</pre>";
echo "<hr>";
echo "<p><a href='downloads.php'>Try accessing downloads.php</a></p>";
echo "</body></html>";
?>
