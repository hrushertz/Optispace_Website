<?php
/**
 * Site Configuration
 * 
 * This file contains configuration settings that can be easily changed
 * when switching between development and production environments.
 */

// ===========================================
// ENVIRONMENT CONFIGURATION
// ===========================================

/**
 * Set this to 'development' or 'production'
 * This controls various settings like error display, caching, etc.
 */
define('ENVIRONMENT', 'development');

// ===========================================
// BASE URL CONFIGURATION
// ===========================================

/**
 * BASE_URL - The root URL of your website
 * 
 * Development examples:
 *   - '' (empty string for root installations like localhost/Optispace_Website/)
 *   - '/Optispace_Website' (if installed in a subdirectory)
 *   - 'http://localhost/Optispace_Website' (full URL)
 * 
 * Production examples:
 *   - '' (empty string if installed at domain root)
 *   - 'https://www.optispace.com' (full production URL)
 *   - '/subfolder' (if installed in a subdirectory)
 */

// Uncomment the appropriate line based on your environment:

// For XAMPP local development (installed at htdocs/Optispace_Website)
// For XAMPP local development (installed at htdocs/Optispace_Website)
define('BASE_URL', '/Optispace_Website');

// For production (installed at domain root)
// define('BASE_URL', '');

// For production with full URL
// define('BASE_URL', 'https://www.optispace.com');

// ===========================================
// ASSET PATHS
// ===========================================

define('ASSETS_URL', BASE_URL . '/assets');
define('CSS_URL', ASSETS_URL . '/css');
define('JS_URL', ASSETS_URL . '/js');
define('IMG_URL', ASSETS_URL . '/img');

// ===========================================
// SITE INFORMATION
// ===========================================

define('SITE_NAME', 'Solutions OptiSpace');
define('SITE_TAGLINE', 'Design the Process, Then the Building');

// ===========================================
// HELPER FUNCTIONS
// ===========================================

/**
 * Generate a URL with the base path
 * 
 * @param string $path The path relative to the site root (e.g., '/contact.php')
 * @return string The full URL with base path
 */
function url($path = '') {
    // Remove leading slash if present to avoid double slashes
    $path = ltrim($path, '/');
    
    // If BASE_URL is empty and path is empty, return '/'
    if (empty(BASE_URL) && empty($path)) {
        return '/';
    }
    
    // If BASE_URL is empty, just return the path with leading slash
    if (empty(BASE_URL)) {
        return '/' . $path;
    }
    
    // Return BASE_URL + path
    return BASE_URL . '/' . $path;
}

/**
 * Generate an asset URL
 * 
 * @param string $path The path relative to the assets folder (e.g., 'css/style.css')
 * @return string The full asset URL
 */
function asset($path = '') {
    $path = ltrim($path, '/');
    return ASSETS_URL . '/' . $path;
}

/**
 * Generate an image URL
 * 
 * @param string $filename The image filename
 * @return string The full image URL
 */
function img($filename) {
    return IMG_URL . '/' . ltrim($filename, '/');
}

// ===========================================
// ERROR HANDLING BASED ON ENVIRONMENT
// ===========================================

if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ===========================================
// MAINTENANCE MODE FUNCTIONS
// ===========================================

/**
 * Get a site setting from database
 * 
 * @param string $key The setting key
 * @param mixed $default Default value if setting not found
 * @return mixed The setting value
 */
function getSiteSetting($key, $default = null) {
    static $settings = null;
    
    // Cache settings to avoid multiple DB calls
    if ($settings === null) {
        $settings = [];
        try {
            require_once __DIR__ . '/../database/db_config.php';
            $conn = getDBConnection();
            $result = $conn->query("SELECT setting_key, setting_value, setting_type FROM site_settings");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $value = $row['setting_value'];
                    // Convert based on type
                    switch ($row['setting_type']) {
                        case 'boolean':
                            $value = (bool) intval($value);
                            break;
                        case 'integer':
                            $value = intval($value);
                            break;
                        case 'json':
                            $value = json_decode($value, true);
                            break;
                    }
                    $settings[$row['setting_key']] = $value;
                }
            }
            $conn->close();
        } catch (Exception $e) {
            // If database not available, return default
            return $default;
        }
    }
    
    return isset($settings[$key]) ? $settings[$key] : $default;
}

/**
 * Update a site setting in database
 * 
 * @param string $key The setting key
 * @param mixed $value The setting value
 * @param int|null $userId The admin user making the change
 * @return bool Success status
 */
function updateSiteSetting($key, $value, $userId = null) {
    try {
        require_once __DIR__ . '/../database/db_config.php';
        $conn = getDBConnection();
        
        // Convert value to string for storage
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        } elseif (is_array($value)) {
            $value = json_encode($value);
        }
        
        $stmt = $conn->prepare("UPDATE site_settings SET setting_value = ?, updated_by = ? WHERE setting_key = ?");
        $stmt->bind_param("sis", $value, $userId, $key);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        
        return $result;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Check if site is in maintenance mode
 * 
 * @return bool True if in maintenance mode
 */
function isMaintenanceMode() {
    return getSiteSetting('maintenance_mode', false);
}

/**
 * Get maintenance mode message
 * 
 * @return string The maintenance message
 */
function getMaintenanceMessage() {
    return getSiteSetting('maintenance_message', 'We are currently performing scheduled maintenance. Please check back soon.');
}

/**
 * Get maintenance end time
 * 
 * @return string|null The estimated end time
 */
function getMaintenanceEndTime() {
    return getSiteSetting('maintenance_end_time', null);
}

/**
 * Check maintenance mode and redirect if needed
 * Allows access to: admin panel, blogger panel, blog pages
 * 
 * @param bool $allowBlogPages Whether to allow blog pages during maintenance
 */
function checkMaintenanceMode($allowBlogPages = false) {
    if (!isMaintenanceMode()) {
        return; // Not in maintenance mode
    }
    
    // Get current script path
    $currentPath = $_SERVER['SCRIPT_NAME'] ?? '';
    
    // Always allow admin and blogger panels
    if (strpos($currentPath, '/admin/') !== false || strpos($currentPath, '/blogger/') !== false) {
        return;
    }
    
    // Allow blog pages if specified
    if ($allowBlogPages) {
        if (strpos($currentPath, '/blog/') !== false || basename($currentPath) === 'blogs.php') {
            return;
        }
    }
    
    // Allow the maintenance page itself
    if (basename($currentPath) === 'maintenance.php') {
        return;
    }
    
    // Redirect to maintenance page
    header('HTTP/1.1 503 Service Unavailable');
    header('Retry-After: 3600');
    header('Location: ' . url('maintenance.php'));
    exit;
}
