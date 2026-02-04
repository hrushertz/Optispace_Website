<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/plain');

echo "=== Testing downloads.php for errors ===\n\n";

// Test 1: Check syntax
echo "Test 1: PHP Syntax Check\n";
$output = [];
$return = 0;
exec('php -l downloads.php 2>&1', $output, $return);
echo implode("\n", $output) . "\n";
if ($return !== 0) {
    echo "SYNTAX ERROR FOUND!\n";
    exit;
}
echo "✓ Syntax OK\n\n";

// Test 2: Try to execute and catch errors
echo "Test 2: Execution Test\n";
try {
    ob_start();
    include 'downloads.php';
    $content = ob_get_clean();
    echo "✓ File executed successfully\n";
    echo "Output length: " . strlen($content) . " bytes\n\n";
} catch (Throwable $e) {
    ob_end_clean();
    echo "✗ ERROR:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

// Test 3: Check for undefined variables
echo "\nTest 3: Check recent error log\n";
if (function_exists('error_get_last')) {
    $error = error_get_last();
    if ($error) {
        echo "Last error:\n";
        print_r($error);
    } else {
        echo "No errors\n";
    }
}
?>
