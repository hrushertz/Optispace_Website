<?php
/**
 * Blogger Index - Redirects to login or dashboard
 */

require_once __DIR__ . '/includes/auth.php';

if (isBloggerLoggedIn()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
