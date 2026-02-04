<?php
/**
 * Test Contact Form Email Submission
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'env_loader.php';
require_once 'includes/mailer.php';

echo "<h1>Testing Contact Form Email</h1>";

// Load environment
loadEnv();

// Test data matching contact.php form
$emailData = [
    'name' => 'Test User Name',
    'email' => 'testuser@example.com',
    'phone' => '+91 98765 43210',
    'subject' => 'Test Contact Form Submission',
    'message' => 'This is a test message from the contact form.'
];

echo "<h2>Email Configuration:</h2>";
echo "<pre>";
echo "MAIL_ENABLED: " . var_export(env('MAIL_ENABLED'), true) . "\n";
echo "SMTP_HOST: " . env('SMTP_HOST') . "\n";
echo "SMTP_PORT: " . env('SMTP_PORT') . "\n";
echo "SMTP_USERNAME: " . env('SMTP_USERNAME') . "\n";
echo "SMTP_PASSWORD: " . (env('SMTP_PASSWORD') ? '***SET***' : 'NOT SET') . "\n";
echo "ENQUIRY_FROM_EMAIL: " . env('ENQUIRY_FROM_EMAIL') . "\n";
echo "ENQUIRY_TO_EMAIL: " . env('ENQUIRY_TO_EMAIL') . "\n";
echo "</pre>";

echo "<h2>Test Data:</h2>";
echo "<pre>";
print_r($emailData);
echo "</pre>";

echo "<h2>Attempting to send email...</h2>";

try {
    $result = sendInquiryNotification($emailData);
    
    echo "<pre>";
    if ($result['success']) {
        echo "✅ SUCCESS: " . $result['message'] . "\n";
    } else {
        echo "❌ FAILED: " . $result['message'] . "\n";
    }
    echo "</pre>";
} catch (Exception $e) {
    echo "<pre>";
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    echo "</pre>";
}
