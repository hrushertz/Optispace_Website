<?php
/**
 * Simple .env file loader
 * Loads environment variables from .env file
 */

// Prevent multiple inclusions
if (defined('ENV_LOADER_LOADED')) {
    return;
}
define('ENV_LOADER_LOADED', true);

function loadEnv($path = null) {
    // If no path provided, look in the root directory
    if ($path === null) {
        $path = dirname(__FILE__) . '/.env';
    }
    
    if (!file_exists($path)) {
        // Log error but don't throw exception - allow app to use defaults
        error_log('.env file not found at: ' . $path . '. Using default configuration.');
        return false;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments and empty lines
        if (strpos(trim($line), '#') === 0 || empty(trim($line))) {
            continue;
        }

        // Parse the line
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            $value = trim($value, '"\'');
            
            // Set as environment variable and $_ENV
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

/**
 * Get environment variable with optional default value
 */
function env($key, $default = null) {
    $value = getenv($key);
    
    if ($value === false) {
        $value = $_ENV[$key] ?? $default;
    }
    
    // Convert string representations to proper types
    if (is_string($value)) {
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'null':
            case '(null)':
                return null;
            case 'empty':
            case '(empty)':
                return '';
        }
    }
    
    return $value;
}

// Load the .env file - fail gracefully
try {
    loadEnv();
} catch (Exception $e) {
    // Log error but continue - allow app to use defaults
    error_log('Environment loading error: ' . $e->getMessage());
}
?>
