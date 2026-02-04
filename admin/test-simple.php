<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

// Clear previous log
file_put_contents(__DIR__ . '/php_errors.log', "=== Error Log Started ===\n");

try {
    require_once __DIR__ . '/includes/auth.php';
    requireAdminLogin();
    requireAdminRole('admin');
    require_once __DIR__ . '/../database/db_config.php';
    $conn = getDBConnection();
    echo "Success - No errors found";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    error_log("Exception: " . $e->getMessage());
}
