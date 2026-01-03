<?php
// Database Configuration for OptiSpace Admin Panel
// Load environment variables
$env_loader_path = dirname(__DIR__) . '/env_loader.php';
if (file_exists($env_loader_path)) {
    require_once $env_loader_path;
}

define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_NAME', env('DB_NAME', 'optispace_db'));

// Create connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Create database if it doesn't exist
function createDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    
    if ($conn->query($sql) === TRUE) {
        $conn->close();
        return true;
    } else {
        $conn->close();
        return false;
    }
}

// Initialize database tables
function initializeDatabase() {
    createDatabase();
    $conn = getDBConnection();
    
    // Read and execute schema.sql
    $schema = file_get_contents(__DIR__ . '/schema.sql');
    
    if ($conn->multi_query($schema)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->next_result());
    }
    
    $conn->close();
}
?>
