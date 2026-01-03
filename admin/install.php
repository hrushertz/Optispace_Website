<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Installation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .install-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 600px;
            padding: 3rem;
        }
        
        h1 {
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 1rem;
        }
        
        .status {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin: 1rem 0;
        }
        
        .success {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
        }
        
        .error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        
        .info {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            color: #1e40af;
        }
        
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #e99431 0%, #f5a854 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            margin-top: 1rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(233, 148, 49, 0.3);
        }
        
        .credentials {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 1.5rem 0;
            border: 1px solid #e2e8f0;
        }
        
        .credentials h3 {
            color: #1e293b;
            margin-bottom: 1rem;
        }
        
        .credentials p {
            color: #64748b;
            margin: 0.5rem 0;
        }
        
        .credentials strong {
            color: #1e293b;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <h1>🚀 Admin Panel Installation</h1>
        
        <?php
        // Check if .env file exists
        $env_file = dirname(__DIR__) . '/.env';
        if (!file_exists($env_file)) {
            echo '<div class="status error">✗ .env file not found!</div>';
            echo '<p style="color: #64748b; margin: 1rem 0;">Please copy .env.example to .env first:</p>';
            echo '<pre style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">cp .env.example .env</pre>';
            echo '</div></body></html>';
            exit;
        }
        
        require_once '../database/db_config.php';
        
        try {
            // Initialize database
            initializeDatabase();
            
            echo '<div class="status success">✓ Database created successfully!</div>';
            echo '<div class="status success">✓ Tables created successfully!</div>';
            echo '<div class="status success">✓ Default admin user created!</div>';
            
            echo '<div class="credentials">';
            echo '<h3>Default Login Credentials</h3>';
            echo '<p><strong>Username:</strong> admin</p>';
            echo '<p><strong>Password:</strong> admin123</p>';
            echo '<p style="margin-top: 1rem; color: #ef4444; font-size: 0.9rem;">⚠️ Please change the password after first login!</p>';
            echo '</div>';
            
            echo '<div class="status info">';
            echo '<strong>Next Steps:</strong><br>';
            echo '1. Delete or rename this install.php file for security<br>';
            echo '2. Go to the login page and sign in<br>';
            echo '3. Start adding content to your website';
            echo '</div>';
            
            echo '<a href="login.php" class="btn">Go to Login Page →</a>';
            
        } catch (Exception $e) {
            echo '<div class="status error">✗ Installation failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '<p style="margin-top: 1rem; color: #64748b;">Please check your database configuration in /database/db_config.php</p>';
        }
        ?>
    </div>
</body>
</html>
