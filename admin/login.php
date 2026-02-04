<?php
/**
 * Admin Login Page
 */

require_once __DIR__ . '/includes/auth.php';

// Redirect if already logged in
if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $user = authenticateAdmin($username, $password);

        if ($user) {
            // Check if user is an editor - they should use blogger panel
            if ($user['role'] === 'editor') {
                // Don't set admin session, show error with redirect link
                $error = 'Editors should use the <a href="../blogger/login.php" style="color: #E99431; font-weight: 600;">Blogger Panel</a> to log in.';
            } else {
                // Only super_admin and admin can access admin panel
                setAdminSession($user);
                header('Location: dashboard.php');
                exit;
            }
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - OptiSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        .blogger-note {
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(233, 148, 49, 0.08);
            border-radius: 8px;
            font-size: 0.85rem;
            color: #64748b;
            text-align: center;
        }

        .blogger-note a {
            color: #E99431;
            text-decoration: none;
            font-weight: 600;
        }

        .blogger-note a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="admin-body login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo-wrapped">
                    <a href="../index.php">
                        <img src="../assets/img/optispace.png" alt="OptiSpace Logo" class="login-company-logo">
                    </a>
                </div>
                <h1>Welcome Back</h1>
                <p>Sign in to OptiSpace Admin Panel</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form" data-validate>
                <div class="form-group">
                    <label for="username" class="form-label">Username or Email</label>
                    <input type="text" id="username" name="username" class="form-control"
                        placeholder="Enter your username" required
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg>
                    Sign In
                </button>
            </form>

            <div class="blogger-note">
                <strong>Are you a blogger/editor?</strong><br>
                Please use the <a href="../blogger/login.php">Blogger Panel</a> to log in.
            </div>

            <div class="login-footer">
                <p>&copy; <?php echo date('Y'); ?> Solutions OptiSpace. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>