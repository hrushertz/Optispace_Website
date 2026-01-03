<?php
require_once __DIR__ . '/../database/db_config.php';

echo "Running schema application...\n";

// Try to initialize database and run schema
try {
    initializeDatabase();
    echo "initializeDatabase() completed. Check for errors above if any.\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

echo "Done.\n";
