<?php
/**
 * Maintenance Mode Page
 * Displayed when site is under maintenance
 */
require_once __DIR__ . '/includes/config.php';

// If not in maintenance mode, redirect to home
if (!isMaintenanceMode()) {
    header('Location: ' . url('index.php'));
    exit;
}

$maintenanceMessage = getMaintenanceMessage();
$maintenanceEndTime = getMaintenanceEndTime();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Under Maintenance | Solutions OptiSpace</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #fff;
        }
        
        .maintenance-container {
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        
        .maintenance-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: rgba(233, 148, 49, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        
        .maintenance-icon svg {
            width: 60px;
            height: 60px;
            color: #E99431;
        }
        
        .logo {
            margin-bottom: 2rem;
        }
        
        .logo img {
            max-width: 200px;
            height: auto;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #fff;
        }
        
        .message {
            font-size: 1.125rem;
            color: #94A3B8;
            margin-bottom: 2rem;
            line-height: 1.7;
        }
        
        .end-time {
            background: rgba(233, 148, 49, 0.1);
            border: 1px solid rgba(233, 148, 49, 0.3);
            padding: 1rem 2rem;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 2rem;
        }
        
        .end-time-label {
            font-size: 0.875rem;
            color: #94A3B8;
            margin-bottom: 0.25rem;
        }
        
        .end-time-value {
            font-size: 1.25rem;
            color: #E99431;
            font-weight: 600;
        }
        
        .links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .link-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9375rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .link-btn:hover {
            background: rgba(233, 148, 49, 0.2);
            border-color: rgba(233, 148, 49, 0.3);
        }
        
        .link-btn svg {
            width: 18px;
            height: 18px;
        }
        
        .footer-text {
            margin-top: 3rem;
            font-size: 0.875rem;
            color: #64748B;
        }
        
        @media (max-width: 640px) {
            h1 {
                font-size: 1.75rem;
            }
            
            .message {
                font-size: 1rem;
            }
            
            .maintenance-icon {
                width: 100px;
                height: 100px;
            }
            
            .maintenance-icon svg {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="logo">
            <img src="<?php echo asset('img/optispace.png'); ?>" alt="OptiSpace Logo">
        </div>
        
        <div class="maintenance-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
        </div>
        
        <h1>Under Maintenance</h1>
        
        <p class="message"><?php echo htmlspecialchars($maintenanceMessage); ?></p>
        
        <?php if ($maintenanceEndTime): ?>
        <div class="end-time">
            <div class="end-time-label">Estimated completion</div>
            <div class="end-time-value"><?php echo htmlspecialchars($maintenanceEndTime); ?></div>
        </div>
        <?php endif; ?>
        
        <div class="links">
            <a href="<?php echo url('blogs.php'); ?>" class="link-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                Read Our Blog
            </a>
            <a href="mailto:info@optispace.com" class="link-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                Contact Us
            </a>
        </div>
        
        <p class="footer-text">We apologize for any inconvenience and appreciate your patience.</p>
    </div>
</body>
</html>
