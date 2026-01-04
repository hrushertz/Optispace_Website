<?php
/**
 * PHPMailer Configuration and Helper Functions
 * 
 * This file provides email sending functionality using PHPMailer
 * Configure SMTP settings in your .env file
 */

// Load Composer autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Load environment variables
require_once dirname(__DIR__) . '/env_loader.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Create and configure a PHPMailer instance
 * 
 * @return PHPMailer Configured PHPMailer instance
 */
function getMailer() {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = env('SMTP_HOST', 'smtp.gmail.com');
        $mail->SMTPAuth   = true;
        $mail->Username   = env('SMTP_USERNAME', '');
        $mail->Password   = env('SMTP_PASSWORD', '');
        $mail->Port       = (int)env('SMTP_PORT', 587);
        
        // Set encryption
        $encryption = env('SMTP_ENCRYPTION', 'tls');
        if ($encryption === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }
        
        // Set sender
        $mail->setFrom(
            env('SMTP_FROM_EMAIL', env('SMTP_USERNAME', '')),
            env('SMTP_FROM_NAME', 'Solutions OptiSpace')
        );
        
        // Set character encoding
        $mail->CharSet = 'UTF-8';
        
        // Disable debug output in production
        if (env('APP_ENV', 'production') !== 'development') {
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
        } else {
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Set to SMTP::DEBUG_SERVER for debugging
        }
        
    } catch (Exception $e) {
        error_log("Mailer configuration error: " . $e->getMessage());
        throw $e;
    }
    
    return $mail;
}

/**
 * Send a Pulse Check notification email
 * 
 * @param array $data Form submission data
 * @return array ['success' => bool, 'message' => string]
 */
function sendPulseCheckNotification($data) {
    try {
        $mail = getMailer();
        
        // Recipients
        $notifyEmail = env('NOTIFY_EMAIL', 'info@solutionskms.com');
        $notifyCC = env('NOTIFY_CC', '');
        
        // Add primary recipient(s)
        $recipients = array_map('trim', explode(',', $notifyEmail));
        foreach ($recipients as $recipient) {
            if (!empty($recipient)) {
                $mail->addAddress($recipient);
            }
        }
        
        // Add CC recipients if specified
        if (!empty($notifyCC)) {
            $ccRecipients = array_map('trim', explode(',', $notifyCC));
            foreach ($ccRecipients as $cc) {
                if (!empty($cc)) {
                    $mail->addCC($cc);
                }
            }
        }
        
        // Reply-to: the person who submitted the form
        if (!empty($data['email'])) {
            $replyToName = trim(($data['firstName'] ?? '') . ' ' . ($data['lastName'] ?? ''));
            $mail->addReplyTo($data['email'], $replyToName);
        }
        
        // Email subject
        $companyName = $data['companyName'] ?? 'Unknown Company';
        $mail->Subject = "New Pulse Check Request from {$companyName}";
        
        // Build email body
        $mail->isHTML(true);
        $mail->Body = buildPulseCheckEmailBody($data);
        $mail->AltBody = buildPulseCheckPlainTextBody($data);
        
        $mail->send();
        
        return ['success' => true, 'message' => 'Email sent successfully'];
        
    } catch (Exception $e) {
        error_log("Pulse Check email failed: " . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

/**
 * Build HTML email body for Pulse Check notification
 * 
 * @param array $data Form submission data
 * @return string HTML email body
 */
function buildPulseCheckEmailBody($data) {
    $firstName = htmlspecialchars($data['firstName'] ?? '');
    $lastName = htmlspecialchars($data['lastName'] ?? '');
    $designation = htmlspecialchars($data['designation'] ?? 'N/A');
    $email = htmlspecialchars($data['email'] ?? '');
    $phone = htmlspecialchars($data['phone'] ?? '');
    $altPhone = htmlspecialchars($data['altPhone'] ?? 'N/A');
    
    $companyName = htmlspecialchars($data['companyName'] ?? '');
    $website = htmlspecialchars($data['website'] ?? 'N/A');
    $industry = htmlspecialchars($data['industry'] ?? '');
    $facilityAddress = htmlspecialchars($data['facilityAddress'] ?? '');
    $facilityCity = htmlspecialchars($data['facilityCity'] ?? '');
    $facilityState = htmlspecialchars($data['facilityState'] ?? '');
    $facilityCountry = htmlspecialchars($data['facilityCountry'] ?? 'India');
    $facilitySize = htmlspecialchars($data['facilitySize'] ?? 'N/A');
    $employees = htmlspecialchars($data['employees'] ?? 'N/A');
    $annualRevenue = htmlspecialchars($data['annualRevenue'] ?? 'N/A');
    
    $projectType = htmlspecialchars($data['projectType'] ?? '');
    $interests = htmlspecialchars($data['interests'] ?? 'N/A');
    $currentChallenges = nl2br(htmlspecialchars($data['currentChallenges'] ?? 'N/A'));
    $projectGoals = nl2br(htmlspecialchars($data['projectGoals'] ?? 'N/A'));
    $timeline = htmlspecialchars($data['timeline'] ?? 'N/A');
    $referral = htmlspecialchars($data['referral'] ?? 'N/A');
    $preferredContact = htmlspecialchars($data['preferredContact'] ?? 'N/A');
    
    $submittedAt = date('F j, Y \a\t g:i A');
    
    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 700px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #1E293B 0%, #334155 100%); padding: 30px 40px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;">
                    <span style="color: #E99431;">🏭</span> New Pulse Check Request
                </h1>
                <p style="color: rgba(255,255,255,0.8); margin: 10px 0 0; font-size: 14px;">
                    Submitted on {$submittedAt}
                </p>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td style="padding: 30px 40px;">
                <!-- Contact Information -->
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                    <tr>
                        <td style="padding-bottom: 15px;">
                            <h2 style="color: #1E293B; font-size: 18px; margin: 0 0 15px; padding-bottom: 10px; border-bottom: 2px solid #E99431;">
                                👤 Contact Information
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" cellpadding="8" cellspacing="0" style="background-color: #f8fafc; border-radius: 6px;">
                                <tr>
                                    <td width="35%" style="color: #64748B; font-weight: 500;">Name</td>
                                    <td style="color: #1E293B; font-weight: 600;">{$firstName} {$lastName}</td>
                                </tr>
                                <tr style="background-color: #ffffff;">
                                    <td style="color: #64748B; font-weight: 500;">Designation</td>
                                    <td style="color: #1E293B;">{$designation}</td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 500;">Email</td>
                                    <td><a href="mailto:{$email}" style="color: #3B82F6; text-decoration: none;">{$email}</a></td>
                                </tr>
                                <tr style="background-color: #ffffff;">
                                    <td style="color: #64748B; font-weight: 500;">Phone</td>
                                    <td style="color: #1E293B;">{$phone}</td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 500;">Alt. Phone</td>
                                    <td style="color: #1E293B;">{$altPhone}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                
                <!-- Company & Facility -->
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                    <tr>
                        <td style="padding-bottom: 15px;">
                            <h2 style="color: #1E293B; font-size: 18px; margin: 0 0 15px; padding-bottom: 10px; border-bottom: 2px solid #E99431;">
                                🏢 Company & Facility Details
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" cellpadding="8" cellspacing="0" style="background-color: #f8fafc; border-radius: 6px;">
                                <tr>
                                    <td width="35%" style="color: #64748B; font-weight: 500;">Company Name</td>
                                    <td style="color: #1E293B; font-weight: 600;">{$companyName}</td>
                                </tr>
                                <tr style="background-color: #ffffff;">
                                    <td style="color: #64748B; font-weight: 500;">Website</td>
                                    <td style="color: #1E293B;">{$website}</td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 500;">Industry</td>
                                    <td style="color: #1E293B;">{$industry}</td>
                                </tr>
                                <tr style="background-color: #ffffff;">
                                    <td style="color: #64748B; font-weight: 500;">Facility Address</td>
                                    <td style="color: #1E293B;">{$facilityAddress}</td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 500;">City / State / Country</td>
                                    <td style="color: #1E293B;">{$facilityCity}, {$facilityState}, {$facilityCountry}</td>
                                </tr>
                                <tr style="background-color: #ffffff;">
                                    <td style="color: #64748B; font-weight: 500;">Facility Size</td>
                                    <td style="color: #1E293B;">{$facilitySize}</td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 500;">Number of Employees</td>
                                    <td style="color: #1E293B;">{$employees}</td>
                                </tr>
                                <tr style="background-color: #ffffff;">
                                    <td style="color: #64748B; font-weight: 500;">Annual Revenue</td>
                                    <td style="color: #1E293B;">{$annualRevenue}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                
                <!-- Project Details -->
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                    <tr>
                        <td style="padding-bottom: 15px;">
                            <h2 style="color: #1E293B; font-size: 18px; margin: 0 0 15px; padding-bottom: 10px; border-bottom: 2px solid #E99431;">
                                📋 Project Details
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" cellpadding="8" cellspacing="0" style="background-color: #f8fafc; border-radius: 6px;">
                                <tr>
                                    <td width="35%" style="color: #64748B; font-weight: 500;">Project Type</td>
                                    <td style="color: #1E293B; font-weight: 600;">{$projectType}</td>
                                </tr>
                                <tr style="background-color: #ffffff;">
                                    <td style="color: #64748B; font-weight: 500;">Areas of Interest</td>
                                    <td style="color: #1E293B;">{$interests}</td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 500;">Timeline</td>
                                    <td style="color: #1E293B;">{$timeline}</td>
                                </tr>
                                <tr style="background-color: #ffffff;">
                                    <td style="color: #64748B; font-weight: 500;">How Did You Hear About Us?</td>
                                    <td style="color: #1E293B;">{$referral}</td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 500;">Preferred Contact Method</td>
                                    <td style="color: #1E293B;">{$preferredContact}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                
                <!-- Current Challenges -->
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                    <tr>
                        <td style="padding-bottom: 15px;">
                            <h2 style="color: #1E293B; font-size: 18px; margin: 0 0 15px; padding-bottom: 10px; border-bottom: 2px solid #E99431;">
                                ⚠️ Current Challenges
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f8fafc; padding: 15px; border-radius: 6px; color: #1E293B; line-height: 1.6;">
                            {$currentChallenges}
                        </td>
                    </tr>
                </table>
                
                <!-- Project Goals -->
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                    <tr>
                        <td style="padding-bottom: 15px;">
                            <h2 style="color: #1E293B; font-size: 18px; margin: 0 0 15px; padding-bottom: 10px; border-bottom: 2px solid #10B981;">
                                🎯 Project Goals
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f0fdf4; padding: 15px; border-radius: 6px; color: #1E293B; line-height: 1.6;">
                            {$projectGoals}
                        </td>
                    </tr>
                </table>
                
                <!-- Action Button -->
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="text-align: center; padding-top: 20px;">
                            <a href="mailto:{$email}" style="display: inline-block; background-color: #E99431; color: #ffffff; padding: 14px 30px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px;">
                                📧 Reply to {$firstName}
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <!-- Footer -->
        <tr>
            <td style="background-color: #f1f5f9; padding: 20px 40px; text-align: center; border-top: 1px solid #e2e8f0;">
                <p style="color: #64748B; font-size: 13px; margin: 0;">
                    This is an automated notification from the Solutions OptiSpace website.
                </p>
                <p style="color: #94a3b8; font-size: 12px; margin: 10px 0 0;">
                    © Solutions OptiSpace | Design the Process, Then the Building
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
}

/**
 * Build plain text email body for Pulse Check notification
 * 
 * @param array $data Form submission data
 * @return string Plain text email body
 */
function buildPulseCheckPlainTextBody($data) {
    $firstName = $data['firstName'] ?? '';
    $lastName = $data['lastName'] ?? '';
    $designation = $data['designation'] ?? 'N/A';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $altPhone = $data['altPhone'] ?? 'N/A';
    
    $companyName = $data['companyName'] ?? '';
    $website = $data['website'] ?? 'N/A';
    $industry = $data['industry'] ?? '';
    $facilityAddress = $data['facilityAddress'] ?? '';
    $facilityCity = $data['facilityCity'] ?? '';
    $facilityState = $data['facilityState'] ?? '';
    $facilityCountry = $data['facilityCountry'] ?? 'India';
    $facilitySize = $data['facilitySize'] ?? 'N/A';
    $employees = $data['employees'] ?? 'N/A';
    $annualRevenue = $data['annualRevenue'] ?? 'N/A';
    
    $projectType = $data['projectType'] ?? '';
    $interests = $data['interests'] ?? 'N/A';
    $currentChallenges = $data['currentChallenges'] ?? 'N/A';
    $projectGoals = $data['projectGoals'] ?? 'N/A';
    $timeline = $data['timeline'] ?? 'N/A';
    $referral = $data['referral'] ?? 'N/A';
    $preferredContact = $data['preferredContact'] ?? 'N/A';
    
    $submittedAt = date('F j, Y \a\t g:i A');
    
    return <<<TEXT
NEW PULSE CHECK REQUEST
=======================
Submitted on: {$submittedAt}

CONTACT INFORMATION
-------------------
Name: {$firstName} {$lastName}
Designation: {$designation}
Email: {$email}
Phone: {$phone}
Alt. Phone: {$altPhone}

COMPANY & FACILITY DETAILS
--------------------------
Company Name: {$companyName}
Website: {$website}
Industry: {$industry}
Facility Address: {$facilityAddress}
City / State / Country: {$facilityCity}, {$facilityState}, {$facilityCountry}
Facility Size: {$facilitySize}
Number of Employees: {$employees}
Annual Revenue: {$annualRevenue}

PROJECT DETAILS
---------------
Project Type: {$projectType}
Areas of Interest: {$interests}
Timeline: {$timeline}
How Did You Hear About Us: {$referral}
Preferred Contact Method: {$preferredContact}

CURRENT CHALLENGES
------------------
{$currentChallenges}

PROJECT GOALS
-------------
{$projectGoals}

---
This is an automated notification from the Solutions OptiSpace website.
TEXT;
}

/**
 * Send a confirmation email to the person who submitted the form
 * 
 * @param array $data Form submission data
 * @return array ['success' => bool, 'message' => string]
 */
function sendPulseCheckConfirmation($data) {
    try {
        $mail = getMailer();
        
        // Send to the submitter
        $firstName = $data['firstName'] ?? '';
        $lastName = $data['lastName'] ?? '';
        $email = $data['email'] ?? '';
        
        if (empty($email)) {
            return ['success' => false, 'message' => 'No email address provided'];
        }
        
        $mail->addAddress($email, "{$firstName} {$lastName}");
        
        // Email subject
        $mail->Subject = "We've Received Your Pulse Check Request - Solutions OptiSpace";
        
        // Build confirmation email body
        $mail->isHTML(true);
        $mail->Body = buildPulseCheckConfirmationBody($data);
        $mail->AltBody = buildPulseCheckConfirmationPlainText($data);
        
        $mail->send();
        
        return ['success' => true, 'message' => 'Confirmation email sent successfully'];
        
    } catch (Exception $e) {
        error_log("Pulse Check confirmation email failed: " . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

/**
 * Build HTML confirmation email body
 */
function buildPulseCheckConfirmationBody($data) {
    $firstName = htmlspecialchars($data['firstName'] ?? '');
    $companyName = htmlspecialchars($data['companyName'] ?? '');
    
    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #1E293B 0%, #334155 100%); padding: 40px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 600;">
                    <span style="color: #E99431;">Solutions</span> OptiSpace
                </h1>
                <p style="color: rgba(255,255,255,0.7); margin: 10px 0 0; font-size: 14px; letter-spacing: 1px;">
                    Design the Process, Then the Building
                </p>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td style="padding: 40px;">
                <h2 style="color: #1E293B; font-size: 22px; margin: 0 0 20px;">
                    Thank You, {$firstName}! 🎉
                </h2>
                
                <p style="color: #475569; font-size: 16px; line-height: 1.7; margin: 0 0 20px;">
                    We have received your Pulse Check request for <strong>{$companyName}</strong>. Our team is excited to learn more about your facility optimization needs.
                </p>
                
                <div style="background-color: #f8fafc; border-left: 4px solid #E99431; padding: 20px; margin: 25px 0; border-radius: 0 6px 6px 0;">
                    <h3 style="color: #1E293B; font-size: 16px; margin: 0 0 10px;">What happens next?</h3>
                    <ol style="color: #475569; margin: 0; padding-left: 20px; line-height: 1.8;">
                        <li>Our team will review your submission within 24-48 hours</li>
                        <li>A consultant will reach out to discuss your needs</li>
                        <li>We'll schedule your complimentary Pulse Check visit</li>
                    </ol>
                </div>
                
                <p style="color: #475569; font-size: 16px; line-height: 1.7; margin: 0 0 20px;">
                    If you have any immediate questions, feel free to reply to this email or contact us at <a href="mailto:info@solutionskms.com" style="color: #3B82F6; text-decoration: none;">info@solutionskms.com</a>.
                </p>
                
                <p style="color: #475569; font-size: 16px; line-height: 1.7; margin: 25px 0 0;">
                    Best regards,<br>
                    <strong style="color: #1E293B;">The Solutions OptiSpace Team</strong>
                </p>
            </td>
        </tr>
        
        <!-- Footer -->
        <tr>
            <td style="background-color: #f1f5f9; padding: 25px 40px; text-align: center; border-top: 1px solid #e2e8f0;">
                <p style="color: #64748B; font-size: 13px; margin: 0 0 10px;">
                    Solutions OptiSpace | Factory Planning & Optimization Experts
                </p>
                <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                    This is an automated confirmation. Please do not reply directly to this email.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
}

/**
 * Build plain text confirmation email body
 */
function buildPulseCheckConfirmationPlainText($data) {
    $firstName = $data['firstName'] ?? '';
    $companyName = $data['companyName'] ?? '';
    
    return <<<TEXT
THANK YOU, {$firstName}!
========================

We have received your Pulse Check request for {$companyName}. Our team is excited to learn more about your facility optimization needs.

WHAT HAPPENS NEXT?
------------------
1. Our team will review your submission within 24-48 hours
2. A consultant will reach out to discuss your needs
3. We'll schedule your complimentary Pulse Check visit

If you have any immediate questions, feel free to contact us at info@solutionskms.com.

Best regards,
The Solutions OptiSpace Team

---
Solutions OptiSpace | Factory Planning & Optimization Experts
Design the Process, Then the Building
TEXT;
}
