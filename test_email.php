<?php
/**
 * Email Configuration Test Script
 * This script tests the PHPMailer configuration without sending actual emails
 */

require_once 'env_loader.php';
require_once 'includes/mailer.php';

// Load environment variables
loadEnv();

// Test data for inquiry form
$testInquiryData = [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '+91 98765 43210',
    'subject' => 'Test Inquiry',
    'message' => 'This is a test inquiry message to verify the email configuration.'
];

// Test data for pulse check form
$testPulseCheckData = [
    'firstName' => 'John',
    'lastName' => 'Doe',
    'designation' => 'Operations Manager',
    'email' => 'john.doe@example.com',
    'phone' => '+91 98765 43210',
    'altPhone' => '+91 98765 43211',
    'companyName' => 'Test Company Ltd.',
    'website' => 'www.testcompany.com',
    'industry' => 'Manufacturing',
    'facilityAddress' => '123 Test Street',
    'facilityCity' => 'Mumbai',
    'facilityState' => 'Maharashtra',
    'facilityCountry' => 'India',
    'facilitySize' => '50000 sq ft',
    'employees' => '100-200',
    'annualRevenue' => '$5M - $10M',
    'projectType' => 'Greenfield',
    'interests' => 'Layout Design, Material Handling',
    'currentChallenges' => 'Need to optimize production flow',
    'projectGoals' => 'Increase efficiency by 30%',
    'timeline' => '3-6 months',
    'referral' => 'Google Search',
    'preferredContact' => 'Email'
];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Configuration Test - OptiSpace</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .header p {
            opacity: 0.9;
        }
        
        .content {
            padding: 2rem;
        }
        
        .config-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .config-section h2 {
            color: #1E293B;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #E99431;
        }
        
        .config-item {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .config-item:last-child {
            border-bottom: none;
        }
        
        .config-label {
            flex: 0 0 200px;
            font-weight: 600;
            color: #64748B;
        }
        
        .config-value {
            flex: 1;
            color: #1E293B;
            word-break: break-all;
        }
        
        .status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .status-enabled {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .status-disabled {
            background: #FEE2E2;
            color: #991B1B;
        }
        
        .test-section {
            margin-top: 2rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #E99431;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn:hover {
            background: #D97706;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(233, 148, 49, 0.4);
        }
        
        .btn-secondary {
            background: #3B82F6;
        }
        
        .btn-secondary:hover {
            background: #2563EB;
        }
        
        .warning {
            background: #FEF3C7;
            border: 1px solid #FCD34D;
            border-left: 4px solid #F59E0B;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        
        .warning-title {
            font-weight: 600;
            color: #92400E;
            margin-bottom: 0.5rem;
        }
        
        .warning-text {
            color: #78350F;
            font-size: 0.875rem;
        }
        
        .footer {
            text-align: center;
            padding: 1.5rem;
            background: #f8fafc;
            color: #64748B;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Email Configuration Test</h1>
            <p>OptiSpace Website PHPMailer Configuration Status</p>
        </div>
        
        <div class="content">
            <div class="warning">
                <div class="warning-title">⚠️ Important</div>
                <div class="warning-text">
                    This is a test page. Delete this file (test_email.php) before deploying to production.
                    Make sure to configure the email passwords in your .env file before testing.
                </div>
            </div>
            
            <!-- SMTP Configuration -->
            <div class="config-section">
                <h2>SMTP Configuration</h2>
                <div class="config-item">
                    <div class="config-label">Mail Enabled</div>
                    <div class="config-value">
                        <?php 
                        $mailEnabled = env('MAIL_ENABLED', false);
                        $isEnabled = ($mailEnabled === true || $mailEnabled === 'true');
                        ?>
                        <span class="status <?php echo $isEnabled ? 'status-enabled' : 'status-disabled'; ?>">
                            <?php echo $isEnabled ? 'ENABLED' : 'DISABLED'; ?>
                        </span>
                    </div>
                </div>
                <div class="config-item">
                    <div class="config-label">SMTP Host</div>
                    <div class="config-value"><?php echo htmlspecialchars(env('SMTP_HOST', 'Not Set')); ?></div>
                </div>
                <div class="config-item">
                    <div class="config-label">SMTP Port</div>
                    <div class="config-value"><?php echo htmlspecialchars(env('SMTP_PORT', 'Not Set')); ?></div>
                </div>
                <div class="config-item">
                    <div class="config-label">SMTP Encryption</div>
                    <div class="config-value"><?php echo htmlspecialchars(env('SMTP_ENCRYPTION', 'Not Set')); ?></div>
                </div>
                <div class="config-item">
                    <div class="config-label">SMTP Username</div>
                    <div class="config-value"><?php echo htmlspecialchars(env('SMTP_USERNAME', 'Not Set')); ?></div>
                </div>
                <div class="config-item">
                    <div class="config-label">SMTP Password</div>
                    <div class="config-value"><?php echo env('SMTP_PASSWORD', 'Not Set') !== 'your-email-password' && !empty(env('SMTP_PASSWORD')) ? '••••••••' : 'Not Set'; ?></div>
                </div>
            </div>
            
            <!-- Enquiry Form Configuration -->
            <div class="config-section">
                <h2>📧 Enquiry Form Configuration</h2>
                <div class="config-item">
                    <div class="config-label">From Email</div>
                    <div class="config-value"><?php echo htmlspecialchars(env('ENQUIRY_FROM_EMAIL', 'Not Set')); ?></div>
                </div>
                <div class="config-item">
                    <div class="config-label">From Name</div>
                    <div class="config-value"><?php echo htmlspecialchars(env('ENQUIRY_FROM_NAME', 'Not Set')); ?></div>
                </div>
                <div class="config-item">
                    <div class="config-label">To Email</div>
                    <div class="config-value"><?php echo htmlspecialchars(env('ENQUIRY_TO_EMAIL', 'Not Set')); ?></div>
                </div>
            </div>
            
            <!-- Pulse Check Form Configuration -->
            <div class="config-section">
                <h2>🏭 Pulse Check Form Configuration</h2>
                <div class="config-item">
                    <div class="config-label">From Email</div>
                    <div class="config-value"><?php echo htmlspecialchars(env('PULSECHECK_FROM_EMAIL', 'Not Set')); ?></div>
                </div>
                <div class="config-item">
                    <div class="config-label">From Name</div>
                    <div class="config-value"><?php echo htmlspecialchars(env('PULSECHECK_FROM_NAME', 'Not Set')); ?></div>
                </div>
                <div class="config-item">
                    <div class="config-label">To Email</div>
                    <div class="config-value"><?php echo htmlspecialchars(env('PULSECHECK_TO_EMAIL', 'Not Set')); ?></div>
                </div>
            </div>
            
            <!-- Test Section -->
            <div class="test-section">
                <div class="config-section">
                    <h2>🧪 Test Email Functions</h2>
                    <p style="color: #64748B; margin-bottom: 1rem;">
                        Click the buttons below to test sending emails. Make sure MAIL_ENABLED is set to true in your .env file.
                    </p>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <form method="POST" action="" style="display: inline;">
                            <input type="hidden" name="test_inquiry" value="1">
                            <button type="submit" class="btn">Test Inquiry Email</button>
                        </form>
                        <form method="POST" action="" style="display: inline;">
                            <input type="hidden" name="test_pulsecheck" value="1">
                            <button type="submit" class="btn btn-secondary">Test Pulse Check Email</button>
                        </form>
                    </div>
                    
                    <?php
                    // Handle test email sending
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['test_inquiry'])) {
                            echo '<div style="margin-top: 1rem; padding: 1rem; background: #F0F9FF; border: 1px solid #BAE6FD; border-radius: 6px;">';
                            echo '<strong style="color: #0369A1;">Testing Inquiry Email...</strong><br>';
                            $result = sendInquiryNotification($testInquiryData);
                            if ($result['success']) {
                                echo '<span style="color: #065F46;">✓ Success: ' . htmlspecialchars($result['message']) . '</span>';
                            } else {
                                echo '<span style="color: #991B1B;">✗ Error: ' . htmlspecialchars($result['message']) . '</span>';
                            }
                            echo '</div>';
                        } elseif (isset($_POST['test_pulsecheck'])) {
                            echo '<div style="margin-top: 1rem; padding: 1rem; background: #F0F9FF; border: 1px solid #BAE6FD; border-radius: 6px;">';
                            echo '<strong style="color: #0369A1;">Testing Pulse Check Email...</strong><br>';
                            $result = sendPulseCheckNotification($testPulseCheckData);
                            if ($result['success']) {
                                echo '<span style="color: #065F46;">✓ Success: ' . htmlspecialchars($result['message']) . '</span>';
                            } else {
                                echo '<span style="color: #991B1B;">✗ Error: ' . htmlspecialchars($result['message']) . '</span>';
                            }
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="footer">
            OptiSpace Email Configuration Test | Delete this file before production deployment
        </div>
    </div>
</body>
</html>
