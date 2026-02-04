<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering to catch any errors
ob_start();

// Try to include users.php and catch any errors
try {
    include __DIR__ . '/users.php';
} catch (Throwable $e) {
    ob_end_clean();
    echo "<h1>Error Found:</h1>";
    echo "<pre>";
    echo "Type: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n" . $e->getTraceAsString();
    echo "</pre>";
}

// If no exception, check for errors in output
$output = ob_get_clean();
if ($output) {
    echo $output;
} else {
    echo "No output generated";
}
?>
