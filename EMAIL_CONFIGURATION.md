# PHPMailer Configuration Guide - OptiSpace Website

## Overview
The OptiSpace website has been configured with PHPMailer to send emails from two different forms:
- **Enquiry Form** (`inquiry.php`)
- **Pulse Check Form** (`pulse-check.php`)

## Email Configuration

### Enquiry Form
- **From:** enquiry@solutionoptispace.com
- **To:** hrushi@karnanisoft.com
- **Purpose:** General contact inquiries

### Pulse Check Form
- **From:** pulsecheck@solutionoptispace.com
- **To:** hrushi@karnanisoft.com
- **Purpose:** Factory assessment requests

## Environment Variables (.env file)

The following environment variables have been added to your `.env` file:

```env
# Enable/Disable Email
MAIL_ENABLED=true

# SMTP Configuration (Common for all emails)
SMTP_HOST=mail.solutionsoptispace.com
SMTP_PORT=587
SMTP_ENCRYPTION=tls
SMTP_USERNAME=no-reply@solutionsoptispace.com
SMTP_PASSWORD=your-email-password

# Enquiry Form Configuration
ENQUIRY_FROM_EMAIL=enquiry@solutionoptispace.com
ENQUIRY_FROM_NAME=OptiSpace Enquiry
ENQUIRY_TO_EMAIL=hrushi@karnanisoft.com

# Pulse Check Form Configuration
PULSECHECK_FROM_EMAIL=pulsecheck@solutionoptispace.com
PULSECHECK_FROM_NAME=OptiSpace Pulse Check
PULSECHECK_TO_EMAIL=hrushi@karnanisoft.com
```

## Setup Steps

### 1. Create Email Accounts
You need to create the following email accounts in your hosting cPanel/webmail:
- `enquiry@solutionoptispace.com`
- `pulsecheck@solutionoptispace.com`
- `no-reply@solutionsoptispace.com` (for SMTP authentication)

### 2. Configure SMTP Password
Update the `.env` file with the actual SMTP password:
```env
SMTP_PASSWORD=your-actual-password
```

### 3. Verify SMTP Settings
Check with your hosting provider for correct SMTP settings:
- SMTP Host (usually `mail.yourdomain.com`)
- SMTP Port (587 for TLS or 465 for SSL)
- Encryption type (TLS or SSL)

### 4. Enable Email Sending
Make sure emails are enabled:
```env
MAIL_ENABLED=true
```

## Testing

### Option 1: Use Test Script
1. Navigate to: `http://localhost/Optispace_Website/test_email.php`
2. Review the configuration
3. Click "Test Inquiry Email" or "Test Pulse Check Email"
4. Check for success/error messages

### Option 2: Submit Actual Forms
1. **Enquiry Form:** http://localhost/Optispace_Website/inquiry.php
2. **Pulse Check Form:** http://localhost/Optispace_Website/pulse-check.php

## Files Modified

### 1. `.env` - Environment Configuration
Added email configuration variables for both forms.

### 2. `.env.example` - Example Configuration
Updated with new email configuration variables as a template.

### 3. `includes/mailer.php` - Email Functions
- Added `sendInquiryNotification()` function
- Added `buildInquiryEmailBody()` function
- Added `buildInquiryPlainTextBody()` function
- Updated `sendPulseCheckNotification()` to use PULSECHECK_ variables

### 4. `inquiry.php` - Inquiry Form Handler
- Added `require_once 'includes/mailer.php'`
- Added email sending logic after successful form submission

### 5. `test_email.php` - Test Script (NEW)
Created a test page to verify email configuration and test sending.

## Email Flow

### Enquiry Form Flow:
1. User fills out the inquiry form at `inquiry.php`
2. Data is validated and saved to the database
3. If `MAIL_ENABLED=true`, an email is sent:
   - **From:** enquiry@solutionoptispace.com
   - **To:** hrushi@karnanisoft.com
   - **Reply-To:** User's email address
   - **Subject:** "New Inquiry: [subject]"

### Pulse Check Form Flow:
1. User fills out the pulse check form at `pulse-check.php`
2. Data is validated and saved to the database
3. If `MAIL_ENABLED=true`, an email is sent:
   - **From:** pulsecheck@solutionoptispace.com
   - **To:** hrushi@karnanisoft.com
   - **Reply-To:** User's email address
   - **Subject:** "New Pulse Check Request - [Company Name]"

## Troubleshooting

### Emails Not Sending
1. Check if `MAIL_ENABLED=true` in `.env`
2. Verify SMTP credentials are correct
3. Check error logs: `error_log()` messages in PHP error log
4. Use test script to get detailed error messages

### SMTP Authentication Failed
1. Verify the email account exists
2. Check username and password are correct
3. Confirm SMTP host and port are correct
4. Try alternate ports (587 or 465)

### Emails Going to Spam
1. Configure SPF records for your domain
2. Set up DKIM authentication
3. Configure DMARC policy
4. Contact your hosting provider for email deliverability setup

## Security Notes

1. **Never commit .env file to git** - It contains sensitive passwords
2. **Delete test_email.php** before deploying to production
3. **Use strong passwords** for email accounts
4. **Enable 2FA** on email accounts if available
5. **Regularly update** PHPMailer library via Composer

## Production Deployment Checklist

- [ ] Create all email accounts in production hosting
- [ ] Update `.env` with production SMTP credentials
- [ ] Set `MAIL_ENABLED=true`
- [ ] Delete `test_email.php`
- [ ] Test both forms in production
- [ ] Monitor error logs for any issues
- [ ] Configure DNS records (SPF, DKIM, DMARC)

## Support

For issues or questions:
- Check PHPMailer documentation: https://github.com/PHPMailer/PHPMailer
- Review error logs in your hosting cPanel
- Contact hosting provider for SMTP configuration help

---
Last Updated: January 14, 2026
