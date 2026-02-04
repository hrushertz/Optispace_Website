<?php
/**
 * File Verification Script
 * Check if all files have the correct include guards
 */

header('Content-Type: text/plain');

echo "=== FILE VERIFICATION REPORT ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

$files = [
    'auth.php' => __DIR__ . '/includes/auth.php',
    'db_config.php' => dirname(__DIR__) . '/database/db_config.php',
    'config.php' => dirname(__DIR__) . '/includes/config.php',
    'mailer.php' => dirname(__DIR__) . '/includes/mailer.php',
    'env_loader.php' => dirname(__DIR__) . '/env_loader.php',
];

foreach ($files as $name => $path) {
    echo "Checking: $name\n";
    echo "Path: $path\n";
    
    if (!file_exists($path)) {
        echo "❌ FILE NOT FOUND!\n\n";
        continue;
    }
    
    $content = file_get_contents($path);
    
    // Check for include guard
    $guards = [
        'auth.php' => 'ADMIN_AUTH_LOADED',
        'db_config.php' => 'DB_CONFIG_LOADED',
        'config.php' => 'SITE_CONFIG_LOADED',
        'mailer.php' => 'MAILER_LOADED',
        'env_loader.php' => 'ENV_LOADER_LOADED',
    ];
    
    $guardName = $guards[$name];
    
    if (strpos($content, $guardName) !== false) {
        echo "✓ Include guard found: $guardName\n";
    } else {
        echo "❌ MISSING INCLUDE GUARD: $guardName\n";
    }
    
    // Check for protected constants (if applicable)
    if ($name === 'db_config.php') {
        if (strpos($content, "if (!defined('DB_HOST'))") !== false) {
            echo "✓ DB constants protected\n";
        } else {
            echo "❌ DB CONSTANTS NOT PROTECTED!\n";
        }
    }
    
    if ($name === 'config.php') {
        if (strpos($content, "if (!defined('BASE_URL'))") !== false) {
            echo "✓ Config constants protected\n";
        } else {
            echo "❌ CONFIG CONSTANTS NOT PROTECTED!\n";
        }
    }
    
    echo "File size: " . strlen($content) . " bytes\n";
    echo "Last modified: " . date('Y-m-d H:i:s', filemtime($path)) . "\n\n";
}

echo "=== PHP INFO ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Opcache Enabled: " . (function_exists('opcache_get_status') && opcache_get_status() ? 'Yes' : 'No') . "\n";

if (function_exists('opcache_get_status')) {
    $status = opcache_get_status(false);
    if ($status) {
        echo "Opcache Stats: " . $status['opcache_statistics']['num_cached_scripts'] . " cached scripts\n";
    }
}

echo "\n=== END REPORT ===\n";
?>
