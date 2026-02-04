<?php
/**
 * Apache Access Test
 * Tests if PHP files can be accessed directly
 */
header('Content-Type: text/plain');
echo "SUCCESS: PHP is executing on this server\n";
echo "Current file: " . __FILE__ . "\n";
echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
echo "Script Name: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "\n";
echo "\nIf you can see this, Apache can execute PHP files.\n";
echo "The 403 error is likely from .htaccess or mod_security rules.\n";
?>
