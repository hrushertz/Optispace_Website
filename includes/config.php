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
// define('BASE_URL', '/Optispace_Website');

// For production (installed at domain root)
define('BASE_URL', '');

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
