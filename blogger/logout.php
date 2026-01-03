<?php
/**
 * Blogger Logout
 */

require_once __DIR__ . '/includes/auth.php';

logoutBlogger();
header('Location: login.php');
exit;
