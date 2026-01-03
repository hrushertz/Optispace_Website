<?php
/**
 * Admin Panel Entry Point
 * Redirects to login page or dashboard based on authentication status
 */

// Start session
session_start();

// Include authentication functions
require_once __DIR__ . '/includes/auth.php';

// Check if user is already logged in
if (isAdminLoggedIn()) {
    // Redirect to dashboard
    header('Location: dashboard.php');
} else {
    // Redirect to login
    header('Location: login.php');
}
exit;
