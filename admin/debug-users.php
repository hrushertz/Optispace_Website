<?php
/**
 * Debug version of users.php to show actual errors
 */

// Enable error display
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "Starting debug...<br><br>";

echo "1. Loading auth.php...<br>";
require_once __DIR__ . '/includes/auth.php';
echo "✓ auth.php loaded<br><br>";

echo "2. Checking admin login...<br>";
requireAdminLogin();
echo "✓ Admin logged in<br><br>";

echo "3. Checking admin role...<br>";
requireAdminRole('admin');
echo "✓ Admin role verified<br><br>";

echo "4. Getting DB connection...<br>";
$conn = getDBConnection();
echo "✓ DB connected<br><br>";

echo "5. Getting current admin...<br>";
$currentAdmin = getCurrentAdmin();
echo "✓ Current admin: " . print_r($currentAdmin, true) . "<br><br>";

echo "6. Loading header...<br>";
ob_start();
include __DIR__ . '/includes/header.php';
$header = ob_get_clean();
echo "✓ Header loaded<br><br>";

echo "<strong>All checks passed! The issue might be elsewhere.</strong><br>";
echo "<pre>Header content length: " . strlen($header) . " bytes</pre>";

$conn->close();
?>
