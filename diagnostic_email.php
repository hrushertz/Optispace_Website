<!DOCTYPE html>
<html>
<head>
    <title>Contact Form Email Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .test-box {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .info { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        h2 { margin-top: 0; }
        button {
            background: #E99431;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px 10px 0;
        }
        button:hover { background: #D97706; }
        pre { background: white; padding: 10px; border-radius: 3px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔍 Contact Form Email Diagnostic</h1>
    
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    require_once 'env_loader.php';
    require_once 'includes/mailer.php';
    
    // Check if test was requested
    if (isset($_POST['test_contact'])) {
        echo '<div class="test-box info"><h2>Running Contact Form Email Test...</h2>';
        
        // Test data
        $emailData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+91 98765 43210',
            'subject' => 'Test Contact Submission',
            'message' => 'This is a test message from the diagnostic tool.'
        ];
        
        echo '<p><strong>Test Data:</strong></p><pre>';
        print_r($emailData);
        echo '</pre>';
        
        $mailEnabled = env('MAIL_ENABLED', false);
        echo '<p><strong>MAIL_ENABLED:</strong> ' . var_export($mailEnabled, true) . '</p>';
        echo '<p><strong>Condition Check:</strong> ';
        if ($mailEnabled === true || $mailEnabled === 'true') {
            echo '✅ Email sending is ENABLED</p>';
            
            echo '<p>Attempting to send email...</p>';
            $result = sendInquiryNotification($emailData);
            
            if ($result['success']) {
                echo '<div class="success"><strong>✅ SUCCESS!</strong><br>' . htmlspecialchars($result['message']) . '</div>';
            } else {
                echo '<div class="error"><strong>❌ FAILED!</strong><br>' . htmlspecialchars($result['message']) . '</div>';
            }
        } else {
            echo '❌ Email sending is DISABLED</p>';
            echo '<div class="error">MAIL_ENABLED is not set to true. Current value: ' . var_export($mailEnabled, true) . '</div>';
        }
        
        echo '</div>';
    }
    ?>
    
    <div class="test-box">
        <h2>Environment Configuration</h2>
        <table style="width: 100%;">
            <tr>
                <td style="width: 250px;"><strong>MAIL_ENABLED:</strong></td>
                <td><?php echo var_export(env('MAIL_ENABLED', 'NOT SET'), true); ?></td>
            </tr>
            <tr>
                <td><strong>SMTP_HOST:</strong></td>
                <td><?php echo htmlspecialchars(env('SMTP_HOST', 'NOT SET')); ?></td>
            </tr>
            <tr>
                <td><strong>SMTP_PORT:</strong></td>
                <td><?php echo htmlspecialchars(env('SMTP_PORT', 'NOT SET')); ?></td>
            </tr>
            <tr>
                <td><strong>SMTP_USERNAME:</strong></td>
                <td><?php echo htmlspecialchars(env('SMTP_USERNAME', 'NOT SET')); ?></td>
            </tr>
            <tr>
                <td><strong>SMTP_PASSWORD:</strong></td>
                <td><?php echo (env('SMTP_PASSWORD') ? '***SET***' : 'NOT SET'); ?></td>
            </tr>
            <tr>
                <td><strong>ENQUIRY_FROM_EMAIL:</strong></td>
                <td><?php echo htmlspecialchars(env('ENQUIRY_FROM_EMAIL', 'NOT SET')); ?></td>
            </tr>
            <tr>
                <td><strong>ENQUIRY_TO_EMAIL:</strong></td>
                <td><?php echo htmlspecialchars(env('ENQUIRY_TO_EMAIL', 'NOT SET')); ?></td>
            </tr>
        </table>
    </div>
    
    <div class="test-box">
        <h2>Run Tests</h2>
        <form method="POST">
            <button type="submit" name="test_contact" value="1">Test Contact Form Email</button>
        </form>
        <p style="color: #666; font-size: 14px;">This will simulate sending a contact form notification email.</p>
    </div>
    
    <div class="test-box info">
        <h2>📋 Instructions</h2>
        <ol>
            <li>First, verify the environment configuration above is correct</li>
            <li>Click the "Test Contact Form Email" button</li>
            <li>Check if the email sends successfully</li>
            <li>If it works here but not on the actual form, there may be an issue with form submission</li>
        </ol>
    </div>
</body>
</html>
