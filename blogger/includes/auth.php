<?php
/**
 * Blogger Authentication Functions
 * Handles login, logout, session management for blogger panel (editors only)
 */

require_once __DIR__ . '/../../database/db_config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Authenticate blogger (editor role only)
 */
function authenticateBlogger($username, $password) {
    $conn = getDBConnection();
    
    // Only allow editor role to login to blogger panel
    $stmt = $conn->prepare("SELECT id, username, email, password, full_name, role, is_active FROM admin_users WHERE (username = ? OR email = ?) AND role = 'editor' AND is_active = 1");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Update last login
            $updateStmt = $conn->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
            $updateStmt->bind_param("i", $user['id']);
            $updateStmt->execute();
            $updateStmt->close();
            
            // Log activity
            logBloggerActivity($user['id'], 'login', 'admin_users', $user['id'], 'Blogger logged in');
            
            $stmt->close();
            $conn->close();
            
            // Remove password from user data
            unset($user['password']);
            return $user;
        }
    }
    
    $stmt->close();
    $conn->close();
    return false;
}

/**
 * Set blogger session
 */
function setBloggerSession($user) {
    $_SESSION['blogger_logged_in'] = true;
    $_SESSION['blogger_id'] = $user['id'];
    $_SESSION['blogger_username'] = $user['username'];
    $_SESSION['blogger_email'] = $user['email'];
    $_SESSION['blogger_name'] = $user['full_name'];
    $_SESSION['blogger_role'] = $user['role'];
    $_SESSION['blogger_login_time'] = time();
    
    // Regenerate session ID for security
    session_regenerate_id(true);
}

/**
 * Check if blogger is logged in
 */
function isBloggerLoggedIn() {
    return isset($_SESSION['blogger_logged_in']) && $_SESSION['blogger_logged_in'] === true;
}

/**
 * Get current blogger user data
 */
function getCurrentBlogger() {
    if (!isBloggerLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['blogger_id'],
        'username' => $_SESSION['blogger_username'],
        'email' => $_SESSION['blogger_email'],
        'full_name' => $_SESSION['blogger_name'],
        'role' => $_SESSION['blogger_role']
    ];
}

/**
 * Require blogger login - redirects to login if not authenticated
 */
function requireBloggerLogin() {
    if (!isBloggerLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Logout blogger
 */
function logoutBlogger() {
    if (isset($_SESSION['blogger_id'])) {
        logBloggerActivity($_SESSION['blogger_id'], 'logout', 'admin_users', $_SESSION['blogger_id'], 'Blogger logged out');
    }
    
    // Clear blogger session data
    unset($_SESSION['blogger_logged_in']);
    unset($_SESSION['blogger_id']);
    unset($_SESSION['blogger_username']);
    unset($_SESSION['blogger_email']);
    unset($_SESSION['blogger_name']);
    unset($_SESSION['blogger_role']);
    unset($_SESSION['blogger_login_time']);
    
    // If no admin session exists, destroy completely
    if (!isset($_SESSION['admin_logged_in'])) {
        session_destroy();
    }
}

/**
 * Log blogger activity
 */
function logBloggerActivity($userId, $action, $entityType = null, $entityId = null, $details = null) {
    $conn = getDBConnection();
    
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    
    $stmt = $conn->prepare("INSERT INTO admin_activity_log (user_id, action, entity_type, entity_id, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississs", $userId, $action, $entityType, $entityId, $details, $ipAddress, $userAgent);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

/**
 * Generate CSRF token
 */
function generateBloggerCSRFToken() {
    if (!isset($_SESSION['blogger_csrf_token'])) {
        $_SESSION['blogger_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['blogger_csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyBloggerCSRFToken($token) {
    return isset($_SESSION['blogger_csrf_token']) && hash_equals($_SESSION['blogger_csrf_token'], $token);
}

/**
 * Get CSRF input field
 */
function bloggerCsrfField() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateBloggerCSRFToken()) . '">';
}
?>
