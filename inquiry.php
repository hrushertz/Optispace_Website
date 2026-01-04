<?php
$currentPage = 'contact';
$pageTitle = 'General Inquiry | Contact Us | Solutions OptiSpace';
$pageDescription = 'Get in touch with Solutions OptiSpace for general inquiries, questions, or partnership opportunities.';

// Handle form submission
require_once 'database/db_config.php';

$formSuccess = false;
$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = getDBConnection();
        
        // Sanitize and collect form data
        $name = trim($_POST['contactName'] ?? '');
        $email = trim($_POST['contactEmail'] ?? '');
        $phone = trim($_POST['contactPhone'] ?? '');
        $subject = trim($_POST['contactSubject'] ?? '');
        $message = trim($_POST['contactMessage'] ?? '');
        
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // Basic validation
        if (empty($name) || empty($email) || empty($message)) {
            $formError = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $formError = 'Please enter a valid email address.';
        } else {
            // Insert into database
            $stmt = $conn->prepare("
                INSERT INTO inquiry_submissions (
                    name, email, phone, subject, message, ip_address, user_agent
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            if (!$stmt) {
                $formError = 'Database error: ' . $conn->error;
            } else {
                $stmt->bind_param(
                    "sssssss",
                    $name, $email, $phone, $subject, $message, $ipAddress, $userAgent
                );
                
                if ($stmt->execute()) {
                    $formSuccess = true;
                } else {
                    $formError = 'Database error: ' . $stmt->error;
                }
                
                $stmt->close();
            }
        }
        
        $conn->close();
    } catch (Exception $e) {
        $formError = 'Error: ' . $e->getMessage();
    }
}

include 'includes/header.php';
?>

<section class="page-hero" style="position: relative; min-height: 450px; display: flex; align-items: center;">
    <img src="<?php echo img('banner_1920x500.jpg'); ?>" alt="Banner" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;">
    <div class="container" style="position: relative; z-index: 2; text-align: center; color: #fff; padding: 5rem 2rem 3rem 2rem; max-width: 800px; margin: 0 auto;">
        <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 1rem; letter-spacing: -0.02em;">Contact Us</h1>
        <p style="font-size: 1.25rem; font-weight: 400; opacity: 0.95;">Let's Start a Conversation About Your Factory</p>
    </div>
</section>

<section class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
            <li>Contact</li>
        </ul>
    </div>
</section>

<section class="section" style="padding: 5rem 0;">
    <div class="container" style="max-width: 800px;">
        <div class="section-header" style="margin-bottom: 3rem; text-align: center;">
            <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">General Inquiries</h2>
            <p style="font-size: 1.125rem; color: var(--text-medium); line-height: 1.7;">Have questions not related to a factory project? Use this form to reach out to us</p>
        </div>

        <?php if ($formSuccess): ?>
        <div style="background: white; padding: 4rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; text-align: center;">
            <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <h3 style="font-size: 2rem; color: #1E293B; margin-bottom: 1rem;">Thank You!</h3>
            <p style="font-size: 1.1rem; color: #64748B; max-width: 500px; margin: 0 auto 2rem; line-height: 1.7;">Your message has been sent successfully. Our team will review your inquiry and get back to you shortly.</p>
            <a href="<?php echo url('index.php'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #E99431; color: white; padding: 1rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Back to Home
            </a>
        </div>
        <?php else: ?>
        <div class="contact-form">
            <?php if ($formError): ?>
            <div style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; color: #991B1B;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <?php echo htmlspecialchars($formError); ?>
            </div>
            <?php endif; ?>
            <form id="contactForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="background: white; padding: 3rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;" onsubmit="console.log('Form submitting...'); return true;">
                <div class="grid grid-2" style="gap: 1.5rem;">
                    <div class="form-group">
                        <label for="contactName" style="font-weight: 600; margin-bottom: 0.625rem; display: block; color: #334155;">Your Name *</label>
                        <input type="text" id="contactName" name="contactName" required style="padding: 0.875rem; font-size: 1rem; border: 2px solid #e2e8f0; border-radius: 6px; transition: all 0.2s;" placeholder="Enter your name">
                    </div>
                    <div class="form-group">
                        <label for="contactEmail" style="font-weight: 600; margin-bottom: 0.625rem; display: block; color: #334155;">Email Address *</label>
                        <input type="email" id="contactEmail" name="contactEmail" required style="padding: 0.875rem; font-size: 1rem; border: 2px solid #e2e8f0; border-radius: 6px; transition: all 0.2s;" placeholder="your.email@example.com">
                    </div>
                </div>
                
                <div class="grid grid-2" style="gap: 1.5rem; margin-top: 1.5rem;">
                    <div class="form-group">
                        <label for="contactPhone" style="font-weight: 600; margin-bottom: 0.625rem; display: block; color: #334155;">Phone Number</label>
                        <input type="tel" id="contactPhone" name="contactPhone" style="padding: 0.875rem; font-size: 1rem; border: 2px solid #e2e8f0; border-radius: 6px; transition: all 0.2s;" placeholder="+91 98765 43210">
                    </div>
                    <div class="form-group">
                        <label for="contactSubject" style="font-weight: 600; margin-bottom: 0.625rem; display: block; color: #334155;">Subject</label>
                        <input type="text" id="contactSubject" name="contactSubject" style="padding: 0.875rem; font-size: 1rem; border: 2px solid #e2e8f0; border-radius: 6px; transition: all 0.2s;" placeholder="Brief subject">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="contactMessage" style="font-weight: 600; margin-bottom: 0.625rem; display: block; color: #334155;">Your Message *</label>
                    <textarea id="contactMessage" name="contactMessage" required style="padding: 1rem; min-height: 180px; font-size: 1rem; border: 2px solid #e2e8f0; border-radius: 6px; transition: all 0.2s; line-height: 1.6;" placeholder="Please enter your message here..."></textarea>
                </div>

                <div style="text-align: center; margin-top: 2.5rem;">
                    <button type="submit" class="btn btn-primary btn-large" style="padding: 1rem 3rem; font-size: 1.0625rem; font-weight: 600;">Send Message</button>
                </div>
            </form>
            
            <script>
            // Ensure the contact form submits properly - override any other handlers
            (function() {
                'use strict';
                const contactForm = document.getElementById('contactForm');
                if (contactForm) {
                    console.log('Contact form handler initialized');
                    
                    // Remove all existing submit event listeners
                    const newForm = contactForm.cloneNode(true);
                    contactForm.parentNode.replaceChild(newForm, contactForm);
                    
                    // Add our own handler that explicitly allows submission
                    newForm.addEventListener('submit', function(e) {
                        console.log('Contact form is submitting - allowing natural form submission');
                        // Don't call e.preventDefault() - let it submit naturally
                        return true;
                    });
                }
            })();
            </script>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="section section-light" style="padding: 5rem 0;">
    <div class="container">
        <div class="section-header" style="margin-bottom: 3rem; text-align: center;">
            <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">Our Office</h2>
            <p style="font-size: 1.125rem; color: var(--text-medium);">Reach out to us directly</p>
        </div>

        <div class="card" style="max-width: 600px; margin: 0 auto; padding: 2.5rem;">
            <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color);">Office Location</h3>
            <div style="margin-bottom: 2rem;">
                <p style="font-weight: 600; margin-bottom: 0.75rem; color: var(--text-dark);">Address:</p>
                <p style="color: var(--text-medium); line-height: 1.7;">
                [Complete Address Line 1]<br>
                [Address Line 2]<br>
                [City, State - PIN Code]
                </p>
            </div>
            <div>
                <p style="font-weight: 600; margin-bottom: 0.75rem; color: var(--text-dark);">Contact:</p>
                <p style="color: var(--text-medium); line-height: 1.7;">
                Email: <a href="mailto:info@optispace.com" style="color: var(--primary-color); text-decoration: none;">info@optispace.com</a><br>
                Mobile: <a href="tel:+919999999999" style="color: var(--primary-color); text-decoration: none;">+91 99999 99999</a>
                </p>
            </div>
        </div>
    </div>
</section>

<section style="padding: 5rem 0; background: linear-gradient(135deg, #434242 0%, #5a5a5a 100%); position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.08; background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="container" style="position: relative; z-index: 1;">
        <div style="max-width: 900px; margin: 0 auto; text-align: center;">
            <h2 style="font-size: 2.75rem; font-weight: 700; margin-bottom: 1.5rem; color: white; line-height: 1.2;">Ready for a Factory Project?</h2>
            <p style="font-size: 1.25rem; margin-bottom: 3rem; color: rgba(255, 255, 255, 0.95); line-height: 1.7; max-width: 750px; margin-left: auto; margin-right: auto;">
                Move from general inquiry to action. Request a complimentary Pulse Check to explore how LFB Architecture can transform your facility.
            </p>
            <div style="display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo url('pulse-check.php'); ?>" class="btn btn-large" style="background-color: #E99431; color: white; padding: 1.125rem 3rem; font-size: 1.125rem; font-weight: 600; text-decoration: none; border: 2px solid #E99431; transition: all 0.3s; box-shadow: 0 4px 12px rgba(233, 148, 49, 0.3);">
                    Request Your Pulse Check
                </a>
                <a href="<?php echo url('services/greenfield.php'); ?>" class="btn btn-large" style="background-color: transparent; color: white; padding: 1.125rem 3rem; font-size: 1.125rem; font-weight: 600; text-decoration: none; border: 2px solid white; transition: all 0.3s;">
                    View Our Services
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
