<?php
/**
 * Blogger Login Page
 */

require_once __DIR__ . '/includes/auth.php';

// Redirect if already logged in
if (isBloggerLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$info = '';

// Check for messages
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'editor':
            $info = 'Editors should use this panel to log in and manage blogs.';
            break;
        case 'admin_redirect':
            $info = 'This is the Editor/Blogger panel. Admins should use the <a href="../admin/login.php" style="color: #3B82F6; font-weight: 600;">Admin Panel</a>.';
            break;
    }
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $user = authenticateBlogger($username, $password);

        if ($user) {
            setBloggerSession($user);
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid credentials or you do not have editor access. <br>Admins should use the <a href="../admin/login.php" style="color: #3B82F6; font-weight: 600;">Admin Panel</a>.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogger Login - OptiSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admin/assets/css/admin.css">
    <style>
        .login-card {
            max-width: 420px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3B82F6 0%, #2563eb 100%);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .blogger-note {
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(59, 130, 246, 0.08);
            border-radius: 8px;
            font-size: 0.85rem;
            color: #64748b;
            text-align: center;
        }

        .blogger-note a {
            color: #3B82F6;
            text-decoration: none;
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
                <h1>Blogger Panel</h1>
                <p>Sign in to write and manage your blogs</p>
            </div>

            <?php if ($info): ?>
                <div class="alert alert-info"
                    style="background: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.3); color: #1E40AF;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="16" x2="12" y2="12" />
                        <line x1="12" y1="8" x2="12.01" y2="8" />
                    </svg>
                    <span><?php echo $info; ?></span>
                </div>
            <?php endif; ?>

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

            <form method="POST" class="login-form">
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
                <strong>Are you an administrator?</strong><br>
                Please use the <a href="../admin/login.php">Admin Panel</a> to log in.
            </div>

            <div class="login-footer">
                <p>&copy; <?php echo date('Y'); ?> Solutions OptiSpace. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>