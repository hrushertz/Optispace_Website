<?php
date_default_timezone_set('Asia/Kolkata');
// Database Configuration for OptiSpace Admin Panel

// Prevent multiple inclusions
if (defined('DB_CONFIG_LOADED')) {
    return;
}
define('DB_CONFIG_LOADED', true);

// Load environment variables
$env_loader_path = dirname(__DIR__) . '/env_loader.php';
if (file_exists($env_loader_path)) {
    require_once $env_loader_path;
}

// Fallback env() function if not loaded
if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = getenv($key);
        return ($value !== false) ? $value : $default;
    }
}

// Define constants only if not already defined
if (!defined('DB_HOST')) {
    define('DB_HOST', env('DB_HOST', '127.0.0.1'));
}
if (!defined('DB_USER')) {
    define('DB_USER', env('DB_USER', 'root'));
}
if (!defined('DB_PASS')) {
    define('DB_PASS', env('DB_PASS', ''));
}
if (!defined('DB_NAME')) {
    define('DB_NAME', env('DB_NAME', 'optispace_db'));
}

// Create connection
function getDBConnection()
{
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }

        $conn->set_charset("utf8mb4");
        return $conn;
    } catch (Exception $e) {
        error_log("Database connection error: " . $e->getMessage());
        throw $e;
    }
}

// Create database if it doesn't exist
function createDatabase()
{
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
function initializeDatabase()
{
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