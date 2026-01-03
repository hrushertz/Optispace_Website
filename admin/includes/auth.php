<?php
/**
 * Admin Authentication Functions
 * Handles login, logout, session management for admin panel
 */

require_once __DIR__ . '/../../database/db_config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Authenticate admin user
 */
function authenticateAdmin($username, $password) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("SELECT id, username, email, password, full_name, role, is_active FROM admin_users WHERE (username = ? OR email = ?) AND is_active = 1");
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
            logAdminActivity($user['id'], 'login', 'admin_users', $user['id'], 'User logged in');
            
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
 * Set admin session
 */
function setAdminSession($user) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_email'] = $user['email'];
    $_SESSION['admin_name'] = $user['full_name'];
    $_SESSION['admin_role'] = $user['role'];
    $_SESSION['admin_login_time'] = time();
    
    // Regenerate session ID for security
    session_regenerate_id(true);
}

/**
 * Check if admin is logged in
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Get current admin user data
 */
function getCurrentAdmin() {
    if (!isAdminLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['admin_id'],
        'username' => $_SESSION['admin_username'],
        'email' => $_SESSION['admin_email'],
        'full_name' => $_SESSION['admin_name'],
        'role' => $_SESSION['admin_role']
    ];
}

/**
 * Check if admin has specific role
 */
function hasAdminRole($requiredRole) {
    if (!isAdminLoggedIn()) {
        return false;
    }
    
    $roleHierarchy = [
        'super_admin' => 3,
        'admin' => 2,
        'editor' => 1
    ];
    
    $userRole = $_SESSION['admin_role'] ?? 'editor';
    $userLevel = $roleHierarchy[$userRole] ?? 0;
    $requiredLevel = $roleHierarchy[$requiredRole] ?? 0;
    
    return $userLevel >= $requiredLevel;
}

/**
 * Require admin login - redirects to login if not authenticated
 */
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Alias for requireAdminLogin - commonly used shorthand
 */
function requireLogin() {
    requireAdminLogin();
}

/**
 * Require specific admin role
 */
function requireAdminRole($role) {
    requireAdminLogin();
    
    if (!hasAdminRole($role)) {
        header('Location: dashboard.php?error=unauthorized');
        exit;
    }
}

/**
 * Logout admin user
 */
function logoutAdmin() {
    if (isset($_SESSION['admin_id'])) {
        logAdminActivity($_SESSION['admin_id'], 'logout', 'admin_users', $_SESSION['admin_id'], 'User logged out');
    }
    
    // Clear all session data
    $_SESSION = [];
    
    // Destroy session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy session
    session_destroy();
}

/**
 * Log admin activity
 */
function logAdminActivity($userId, $action, $entityType = null, $entityId = null, $details = null) {
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
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get CSRF input field
 */
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCSRFToken()) . '">';
}
?>
