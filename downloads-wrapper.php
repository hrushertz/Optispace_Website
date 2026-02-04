<?php
// Simple redirect test - if downloads.php works via include but not directly
// This wrapper will serve it properly

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set proper headers
header('Content-Type: text/html; charset=utf-8');
header('X-Powered-By: PHP/' . phpversion());

// Output buffering
ob_start();

try {
    // Include and execute downloads.php
    require 'downloads.php';
    
    // Get the output
    $output = ob_get_clean();
    
    // Send it to browser
    echo $output;
    
} catch (Throwable $e) {
    ob_end_clean();
    http_response_code(500);
    echo "<!DOCTYPE html><html><head><title>Error</title></head><body>";
    echo "<h1>Page Error</h1>";
    echo "<p>An error occurred while loading this page.</p>";
    echo "<p style='color: #999; font-size: 0.9em;'>Error logged. Please contact support.</p>";
    echo "</body></html>";
    
    error_log("Downloads wrapper error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
}
?>
