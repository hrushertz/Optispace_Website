<?php
$currentPage = 'pulse-check';
$pageTitle = 'Request a Pulse Check | Free Factory Assessment | Solutions OptiSpace';
$pageDescription = 'Request your complimentary Pulse Check from Solutions OptiSpace. Our on-site visit helps identify opportunities for factory optimization and improvement.';
$pageKeywords = 'pulse check, free factory assessment, factory evaluation, manufacturing assessment, lean assessment, facility evaluation, factory diagnosis, free consultation, manufacturing audit, factory optimization assessment, OptiSpace pulse check';

// Handle form submission
require_once 'database/db_config.php';
require_once 'includes/mailer.php';

$formSuccess = false;
$formError = '';
$emailError = '';

// Get database connection for the entire page
$conn = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        // Sanitize and collect form data
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName = trim($_POST['lastName'] ?? '');
        $designation = trim($_POST['designation'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $altPhone = trim($_POST['altPhone'] ?? '');
        
        $companyName = trim($_POST['companyName'] ?? '');
        $website = trim($_POST['website'] ?? '');
        $industry = trim($_POST['industry'] ?? '');
        $facilityAddress = trim($_POST['facilityAddress'] ?? '');
        $facilityCity = trim($_POST['facilityCity'] ?? '');
        $facilityState = trim($_POST['facilityState'] ?? '');
        $facilityCountry = trim($_POST['facilityCountry'] ?? 'India');
        $facilitySize = trim($_POST['facilitySize'] ?? '');
        $employees = trim($_POST['employees'] ?? '');
        $annualRevenue = trim($_POST['annualRevenue'] ?? '');
        
        $projectType = trim($_POST['projectType'] ?? '');
        $interests = isset($_POST['interests']) ? implode(', ', $_POST['interests']) : '';
        $currentChallenges = trim($_POST['currentChallenges'] ?? '');
        $projectGoals = trim($_POST['projectGoals'] ?? '');
        $timeline = trim($_POST['timeline'] ?? '');
        $referral = trim($_POST['referral'] ?? '');
        $preferredContact = trim($_POST['preferredContact'] ?? '');
        
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // Basic validation
        if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($companyName) || empty($industry) || empty($facilityAddress) || empty($facilityCity) || empty($facilityState) || empty($projectType)) {
            $formError = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $formError = 'Please enter a valid email address.';
        } else {
            // Insert into database
            $stmt = $conn->prepare("
                INSERT INTO pulse_check_submissions (
                    first_name, last_name, designation, email, phone, alt_phone,
                    company_name, website, industry, facility_address, facility_city, 
                    facility_state, facility_country, facility_size, employees, annual_revenue,
                    project_type, interests, current_challenges, project_goals, 
                    timeline, referral, preferred_contact, ip_address, user_agent
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            if (!$stmt) {
                $formError = 'Database error: ' . $conn->error;
            } else {
                $stmt->bind_param(
                    "sssssssssssssssssssssssss",
                    $firstName, $lastName, $designation, $email, $phone, $altPhone,
                    $companyName, $website, $industry, $facilityAddress, $facilityCity,
                    $facilityState, $facilityCountry, $facilitySize, $employees, $annualRevenue,
                    $projectType, $interests, $currentChallenges, $projectGoals,
                    $timeline, $referral, $preferredContact, $ipAddress, $userAgent
                );
                
                if ($stmt->execute()) {
                    $formSuccess = true;
                    
                    // Send notification email to internal team (if enabled)
                    if (env('MAIL_ENABLED', false) === true || env('MAIL_ENABLED', false) === 'true') {
                        // Prepare data for email
                        $emailData = [
                            'firstName' => $firstName,
                            'lastName' => $lastName,
                            'designation' => $designation,
                            'email' => $email,
                            'phone' => $phone,
                            'altPhone' => $altPhone,
                            'companyName' => $companyName,
                            'website' => $website,
                            'industry' => $industry,
                            'facilityAddress' => $facilityAddress,
                            'facilityCity' => $facilityCity,
                            'facilityState' => $facilityState,
                            'facilityCountry' => $facilityCountry,
                            'facilitySize' => $facilitySize,
                            'employees' => $employees,
                            'annualRevenue' => $annualRevenue,
                            'projectType' => $projectType,
                            'interests' => $interests,
                            'currentChallenges' => $currentChallenges,
                            'projectGoals' => $projectGoals,
                            'timeline' => $timeline,
                            'referral' => $referral,
                            'preferredContact' => $preferredContact
                        ];
                        
                        $notificationResult = sendPulseCheckNotification($emailData);
                        if (!$notificationResult['success']) {
                            // Log the error but don't show to user - form was still submitted successfully
                            error_log("Pulse Check notification email failed: " . $notificationResult['message']);
                        }
                    }
                    
                } else {
                    $formError = 'Database error: ' . $stmt->error;
                }
                
                $stmt->close();
            }
        }
        
    } catch (Exception $e) {
        $formError = 'Error: ' . $e->getMessage();
    }
}

include 'includes/header.php';
?>

<style>
/* ========================================
   PULSE CHECK PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --pulse-orange: #E99431;
    --pulse-orange-light: rgba(233, 148, 49, 0.08);
    --pulse-blue: #3B82F6;
    --pulse-blue-light: rgba(59, 130, 246, 0.08);
    --pulse-green: #10B981;
    --pulse-green-light: rgba(16, 185, 129, 0.08);
    --pulse-dark: #1E293B;
    --pulse-text: #475569;
    --pulse-border: #E2E8F0;
}

/* Hero Section */
.pulse-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 6rem;
    position: relative;
    overflow: hidden;
}

.pulse-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
}

.pulse-hero::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40%;
    height: 60%;
    background: radial-gradient(ellipse at 20% 100%, rgba(16, 185, 129, 0.08) 0%, transparent 50%);
}

.pulse-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
    position: relative;
    z-index: 2;
}

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(233, 148, 49, 0.15);
    color: #E99431;
    padding: 0.5rem 1rem;
    border-radius: 100px;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 1.5rem;
}

.pulse-hero h1 {
    font-size: 3.25rem;
    font-weight: 700;
    color: white;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

.pulse-hero h1 span {
    color: #E99431;
}

.pulse-hero-text {
    font-size: 1.2rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.75);
    margin-bottom: 0;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

/* Breadcrumb */
.pulse-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--pulse-border);
}

.pulse-breadcrumb ul {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.pulse-breadcrumb a {
    color: var(--pulse-text);
    text-decoration: none;
}

.pulse-breadcrumb a:hover {
    color: var(--pulse-orange);
}

.pulse-breadcrumb li:last-child {
    color: var(--pulse-dark);
    font-weight: 500;
}

.pulse-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--pulse-border);
}

/* Section Styles */
.pulse-section {
    padding: 6rem 0;
}

.pulse-section.alt-bg {
    background: #FAFBFC;
}

.pulse-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.section-header-pulse {
    text-align: center;
    margin-bottom: 4rem;
}

.section-header-pulse h2 {
    font-size: 2.5rem;
    color: var(--pulse-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.section-header-pulse p {
    font-size: 1.15rem;
    color: var(--pulse-text);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.7;
}

/* What Is Pulse Check Section */
.what-is-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
}

.what-is-content h2 {
    font-size: 2.5rem;
    color: var(--pulse-dark);
    margin-bottom: 1.5rem;
    font-weight: 700;
}

.what-is-content h2 span {
    color: var(--pulse-orange);
}

.what-is-content > p {
    font-size: 1.1rem;
    color: var(--pulse-text);
    line-height: 1.8;
    margin-bottom: 2rem;
}

.pulse-highlights {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.highlight-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
    background: white;
    border: 1px solid var(--pulse-border);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.highlight-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
    border-color: transparent;
}

.highlight-icon {
    width: 44px;
    height: 44px;
    background: var(--pulse-orange-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.highlight-icon svg {
    width: 22px;
    height: 22px;
    color: var(--pulse-orange);
}

.highlight-icon.blue { background: var(--pulse-blue-light); }
.highlight-icon.blue svg { color: var(--pulse-blue); }
.highlight-icon.green { background: var(--pulse-green-light); }
.highlight-icon.green svg { color: var(--pulse-green); }

.highlight-content h4 {
    font-size: 1.05rem;
    color: var(--pulse-dark);
    margin-bottom: 0.35rem;
    font-weight: 600;
}

.highlight-content p {
    font-size: 0.95rem;
    color: var(--pulse-text);
    margin: 0;
    line-height: 1.6;
}

.what-is-visual {
    background: white;
    border: 1px solid var(--pulse-border);
    border-radius: 20px;
    padding: 3rem;
    position: relative;
    overflow: hidden;
}

.what-is-visual::before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(233, 148, 49, 0.08) 0%, transparent 70%);
    border-radius: 50%;
}

.pulse-visual-icon {
    width: 80px;
    height: 80px;
    background: var(--pulse-orange);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
}

.pulse-visual-icon svg {
    width: 40px;
    height: 40px;
    color: white;
}

.visual-features {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.visual-feature {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.feature-check {
    width: 24px;
    height: 24px;
    background: var(--pulse-green-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.feature-check svg {
    width: 14px;
    height: 14px;
    color: var(--pulse-green);
}

.visual-feature span {
    font-size: 1rem;
    color: var(--pulse-dark);
    font-weight: 500;
}

/* Form Section */
.pulse-form-section {
    padding: 6rem 0;
}

.pulse-form-wrapper {
    max-width: 1100px;
    margin: 0 auto;
}

.pulse-form-card {
    background: white;
    border: 1px solid var(--pulse-border);
    border-radius: 24px;
    padding: 3.5rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
}

.form-header {
    margin-bottom: 3rem;
    text-align: center;
}

.form-header h3 {
    font-size: 2rem;
    color: var(--pulse-dark);
    margin-bottom: 0.75rem;
    font-weight: 700;
}

.form-header p {
    font-size: 1.05rem;
    color: var(--pulse-text);
    line-height: 1.6;
}

.form-section-title {
    font-size: 1.15rem;
    color: var(--pulse-dark);
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--pulse-border);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.form-section-title svg {
    width: 20px;
    height: 20px;
    color: var(--pulse-orange);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.form-group.span-2 {
    grid-column: span 2;
}

.form-group.span-3 {
    grid-column: span 3;
}

.form-group {
    margin-bottom: 0;
}

.form-group.full-width {
    grid-column: span 3;
}

.form-group label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--pulse-dark);
    margin-bottom: 0.5rem;
}

.form-group label .required {
    color: #EF4444;
}

.form-input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 1px solid var(--pulse-border);
    border-radius: 8px;
    font-size: 0.95rem;
    color: var(--pulse-dark);
    background: #F8FAFC;
    transition: all 0.2s ease;
}

.form-input:focus {
    outline: none;
    border-color: var(--pulse-orange);
    background: white;
    box-shadow: 0 0 0 3px var(--pulse-orange-light);
}

.form-input::placeholder {
    color: #94A3B8;
}

select.form-input {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    padding-right: 2.5rem;
}

textarea.form-input {
    min-height: 120px;
    resize: vertical;
}

/* Checkbox Group */
.checkbox-group-label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--pulse-dark);
    margin-bottom: 1rem;
}

.checkbox-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem 1.5rem;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.checkbox-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--pulse-orange);
    cursor: pointer;
}

.checkbox-item span {
    font-size: 0.95rem;
    color: var(--pulse-text);
}

.form-submit {
    margin-top: 2.5rem;
    text-align: center;
}

.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: var(--pulse-orange);
    color: white;
    padding: 1.125rem 3rem;
    border: none;
    border-radius: 8px;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(233, 148, 49, 0.25);
}

.btn-submit:hover {
    background: #d4851c;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(233, 148, 49, 0.35);
}

.btn-submit svg {
    width: 22px;
    height: 22px;
}

.form-note {
    margin-top: 1.5rem;
    font-size: 0.9rem;
    color: var(--pulse-text);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.form-note svg {
    width: 16px;
    height: 16px;
    color: var(--pulse-green);
}

/* Process Steps - Card Design */
.process-steps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.process-step {
    background: white;
    border: 1px solid var(--pulse-border);
    border-radius: 16px;
    padding: 2.5rem 2rem;
    text-align: center;
    position: relative;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 200px;
}

.process-step:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    border-color: var(--pulse-orange);
}

.process-step::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--pulse-orange);
    border-radius: 16px 16px 0 0;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.process-step:hover::before {
    opacity: 1;
}

.step-number {
    width: 64px;
    height: 64px;
    background: var(--pulse-orange-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--pulse-orange);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.process-step:hover .step-number {
    background: var(--pulse-orange);
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(233, 148, 49, 0.25);
}

.process-step h4 {
    font-size: 1.15rem;
    color: var(--pulse-dark);
    margin-bottom: 0.75rem;
    font-weight: 700;
    line-height: 1.3;
}

.process-step p {
    font-size: 0.9rem;
    color: var(--pulse-text);
    line-height: 1.7;
    margin: 0;
}

/* FAQ Grid */
.faq-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.faq-card {
    background: white;
    border: 1px solid var(--pulse-border);
    border-radius: 12px;
    padding: 2rem;
    transition: all 0.3s ease;
}

.faq-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    border-color: transparent;
}

.faq-card h4 {
    font-size: 1.1rem;
    color: var(--pulse-dark);
    margin-bottom: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.faq-card h4 svg {
    width: 20px;
    height: 20px;
    color: var(--pulse-orange);
    flex-shrink: 0;
    margin-top: 2px;
}

.faq-card p {
    font-size: 0.95rem;
    color: var(--pulse-text);
    line-height: 1.7;
    margin: 0;
    padding-left: 1.85rem;
}

/* CTA Section */
.pulse-cta {
    padding: 6rem 0;
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    position: relative;
    overflow: hidden;
}

.pulse-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.05;
    background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
}

.pulse-cta-inner {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
    position: relative;
    z-index: 1;
}

.pulse-cta h2 {
    font-size: 2.75rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.pulse-cta p {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.85);
    line-height: 1.7;
    margin-bottom: 2.5rem;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-cta-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--pulse-orange);
    color: white;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(233, 148, 49, 0.3);
}

.btn-cta-primary:hover {
    background: #d4851c;
    transform: translateY(-2px);
}

.btn-cta-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: transparent;
    color: white;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.btn-cta-secondary:hover {
    border-color: white;
    background: rgba(255, 255, 255, 0.1);
}

.btn-cta-primary svg,
.btn-cta-secondary svg {
    width: 20px;
    height: 20px;
}

/* Responsive */
@media (max-width: 1024px) {
    .what-is-grid {
        grid-template-columns: 1fr;
        gap: 2.5rem;
    }
    
    .process-steps {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-group.full-width,
    .form-group.span-3 {
        grid-column: span 2;
    }
    
    .checkbox-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .pulse-section {
        padding: 4rem 0;
    }
    
    .section-header-pulse h2 {
        font-size: 2.25rem;
    }
}

@media (max-width: 768px) {
    .pulse-hero {
        padding: 4rem 0 3rem;
    }
    
    .pulse-hero h1 {
        font-size: 2rem;
    }
    
    .pulse-hero-text {
        font-size: 1.05rem;
    }
    
    .pulse-section {
        padding: 3rem 0;
    }
    
    .section-header-pulse {
        margin-bottom: 2.5rem;
    }
    
    .section-header-pulse h2 {
        font-size: 1.85rem;
    }
    
    .section-header-pulse p {
        font-size: 1rem;
    }
    
    .what-is-content h2 {
        font-size: 1.85rem;
    }
    
    .what-is-content > p {
        font-size: 1rem;
    }
    
    .what-is-visual {
        padding: 2rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 1.25rem;
        margin-bottom: 2.5rem;
    }
    
    .form-group.full-width,
    .form-group.span-2,
    .form-group.span-3 {
        grid-column: span 1;
    }
    
    .checkbox-grid {
        grid-template-columns: 1fr;
    }
    
    .faq-grid {
        grid-template-columns: 1fr;
    }
    
    .process-steps {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .process-step {
        padding: 2rem 1.5rem;
    }
    
    .pulse-form-card {
        padding: 1.75rem;
    }
    
    .form-header h3 {
        font-size: 1.5rem;
    }
    
    .form-header p {
        font-size: 0.95rem;
    }
    
    .form-section-title {
        font-size: 1rem;
    }
    
    .btn-submit {
        width: 100%;
        justify-content: center;
        padding: 1rem 2rem;
        font-size: 1rem;
    }
    
    .pulse-cta {
        padding: 4rem 0;
    }
    
    .pulse-cta h2 {
        font-size: 2rem;
    }
    
    .pulse-cta p {
        font-size: 1.05rem;
        margin-bottom: 2rem;
    }
    
    .cta-buttons {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .btn-cta-primary,
    .btn-cta-secondary {
        width: 100%;
        justify-content: center;
        padding: 0.875rem 1.5rem;
        font-size: 0.95rem;
    }
    
    .pulse-container {
        padding: 0 1.25rem;
    }
    
    .pulse-hero-inner,
    .pulse-breadcrumb ul {
        padding: 0 1.25rem;
    }
}

@media (max-width: 480px) {
    .pulse-hero {
        padding: 3rem 0 2.5rem;
    }
    
    .pulse-hero h1 {
        font-size: 1.75rem;
    }
    
    .hero-eyebrow {
        font-size: 0.8rem;
        padding: 0.4rem 0.875rem;
    }
    
    .pulse-hero-text {
        font-size: 0.95rem;
    }
    
    .section-header-pulse h2 {
        font-size: 1.6rem;
    }
    
    .what-is-content h2 {
        font-size: 1.6rem;
    }
    
    .pulse-form-card {
        padding: 1.5rem;
        border-radius: 16px;
    }
    
    .form-header {
        margin-bottom: 2rem;
    }
    
    .form-header h3 {
        font-size: 1.35rem;
    }
    
    .form-input {
        padding: 0.75rem 0.875rem;
        font-size: 0.9rem;
    }
    
    .highlight-item {
        padding: 1rem;
    }
    
    .faq-card {
        padding: 1.5rem;
    }
    
    .faq-card h4 {
        font-size: 1rem;
    }
    
    .faq-card p {
        font-size: 0.9rem;
    }
    
    .pulse-cta h2 {
        font-size: 1.65rem;
    }
    
    .pulse-cta p {
        font-size: 0.95rem;
    }
}
</style>

<!-- Hero Section -->
<section class="pulse-hero">
    <div class="pulse-hero-inner">
        <div class="hero-eyebrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            Complimentary Assessment
        </div>
        <h1>Request Your <span>Pulse Check</span></h1>
        <p class="pulse-hero-text">Take the first step towards factory transformation. Our no-obligation Pulse Check helps identify opportunities for optimization and improvement in your facility.</p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="pulse-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li>Pulse Check</li>
    </ul>
</nav>

<!-- What Is Pulse Check Section -->
<section class="pulse-section">
    <div class="pulse-container">
        <div class="what-is-grid">
            <div class="what-is-content">
                <h2>What Is a <span>Pulse Check?</span></h2>
                <p>A Pulse Check is our complimentary on-site visit where we assess your current facility, understand your challenges, and identify areas for potential improvement. Think of it as a health check for your factory.</p>
                
                <div class="pulse-highlights">
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                        </div>
                        <div class="highlight-content">
                            <h4>No Obligation, No Cost</h4>
                            <p>The Pulse Check is complimentary. We invest our time to understand your needs and demonstrate our value.</p>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </div>
                        <div class="highlight-content">
                            <h4>Fresh Eyes on Your Facility</h4>
                            <p>Sometimes it takes an outside perspective to spot inefficiencies that have become invisible over time.</p>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                        </div>
                        <div class="highlight-content">
                            <h4>Actionable Observations</h4>
                            <p>We provide immediate feedback on potential quick wins and longer-term improvement opportunities.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="what-is-visual">
                <div class="pulse-visual-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                </div>
                <div class="visual-features">
                    <div class="visual-feature">
                        <div class="feature-check">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <span>Walkthrough of your production facility</span>
                    </div>
                    <div class="visual-feature">
                        <div class="feature-check">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <span>Understanding of your current processes</span>
                    </div>
                    <div class="visual-feature">
                        <div class="feature-check">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <span>Discussion on your business goals</span>
                    </div>
                    <div class="visual-feature">
                        <div class="feature-check">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <span>Identification of improvement opportunities</span>
                    </div>
                    <div class="visual-feature">
                        <div class="feature-check">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <span>Honest assessment & recommendations</span>
                    </div>
                    <div class="visual-feature">
                        <div class="feature-check">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <span>Understand your pain areas</span>
                    </div>
                    <div class="visual-feature">
                        <div class="feature-check">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <span>Discuss ides and automations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="pulse-section alt-bg">
    <div class="pulse-container">
        <div class="section-header-pulse">
            <h2>How It Works</h2>
            <p>A simple four-step process to get your Pulse Check scheduled</p>
        </div>
        
        <div class="process-steps">
            <div class="process-step">
                <div class="step-number">1</div>
                <h4>Submit Request</h4>
                <p>Fill out the form below with your details and project information</p>
            </div>
            <div class="process-step">
                <div class="step-number">2</div>
                <h4>Initial Call</h4>
                <p>We'll schedule a brief call to understand your needs better</p>
            </div>
            <div class="process-step">
                <div class="step-number">3</div>
                <h4>Site Visit</h4>
                <p>We visit your facility for the actual Pulse Check walkthrough</p>
            </div>
            <div class="process-step">
                <div class="step-number">4</div>
                <h4>Feedback</h4>
                <p>Receive honest observations and recommendations</p>
            </div>
        </div>
    </div>
</section>

<!-- Form Section -->
<section class="pulse-form-section" id="request-form">
    <div class="pulse-container">
        <div class="pulse-form-wrapper">
            <?php if ($formSuccess): ?>
            <div class="pulse-form-card" style="text-align: center; padding: 4rem;">
                <div style="width: 80px; height: 80px; background: var(--pulse-green-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--pulse-green)" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                <h3 style="font-size: 2rem; color: var(--pulse-dark); margin-bottom: 1rem;">Thank You!</h3>
                <p style="font-size: 1.1rem; color: var(--pulse-text); max-width: 500px; margin: 0 auto 2rem; line-height: 1.7;">Your Pulse Check request has been submitted successfully. Our team will review your information and contact you shortly to schedule your complimentary assessment.</p>
                <a href="<?php echo url('index.php'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--pulse-orange); color: white; padding: 1rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Back to Home
                </a>
            </div>
            <?php else: ?>
            <div class="pulse-form-card">
                <div class="form-header">
                    <h3>Request Your Pulse Check</h3>
                    <p>Please fill out the form below and we'll get in touch to schedule your visit.</p>
                </div>
                
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
                
                <form id="pulseCheckForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#request-form">
                    <!-- Contact Information -->
                    <div class="form-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Contact Information
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="firstName">First Name <span class="required">*</span></label>
                            <input type="text" id="firstName" name="firstName" class="form-input" placeholder="Your first name" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input type="text" id="lastName" name="lastName" class="form-input" placeholder="Your last name" required>
                        </div>
                        <div class="form-group">
                            <label for="designation">Designation <span class="required">*</span></label>
                            <input type="text" id="designation" name="designation" class="form-input" placeholder="e.g., Plant Manager, VP Operations" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Work Email <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="you@company.com" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" class="form-input" placeholder="+91 99999 99999" required>
                        </div>
                        <div class="form-group">
                            <label for="altPhone">Alternate Phone</label>
                            <input type="tel" id="altPhone" name="altPhone" class="form-input" placeholder="+91 99999 99999">
                        </div>
                    </div>
                    
                    <!-- Company Information -->
                    <div class="form-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 21h18"/>
                            <path d="M9 8h6"/>
                            <path d="M9 12h6"/>
                            <path d="M9 16h6"/>
                            <path d="M5 21V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v14"/>
                        </svg>
                        Company Information
                    </div>
                    <div class="form-grid">
                        <div class="form-group span-2">
                            <label for="companyName">Company Name <span class="required">*</span></label>
                            <input type="text" id="companyName" name="companyName" class="form-input" placeholder="Your company name" required>
                        </div>
                        <div class="form-group">
                            <label for="website">Company Website</label>
                            <input type="url" id="website" name="website" class="form-input" placeholder="https://www.company.com">
                        </div>
                        <div class="form-group">
                            <label for="industry">Industry <span class="required">*</span></label>
                            <select id="industry" name="industry" class="form-input" required>
                                <option value="">Select your industry</option>
                                <option value="automotive">Automotive & Auto Components</option>
                                <option value="aerospace">Aerospace & Defense</option>
                                <option value="pharma">Pharmaceutical & Healthcare</option>
                                <option value="fmcg">FMCG & Consumer Goods</option>
                                <option value="electronics">Electronics & Electrical</option>
                                <option value="machinery">Industrial Machinery & Equipment</option>
                                <option value="chemical">Chemicals & Plastics</option>
                                <option value="textile">Textile & Apparel</option>
                                <option value="food">Food & Beverage</option>
                                <option value="metal">Metal Fabrication & Foundry</option>
                                <option value="packaging">Packaging</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group span-2">
                            <label for="facilityAddress">Facility Address <span class="required">*</span></label>
                            <input type="text" id="facilityAddress" name="facilityAddress" class="form-input" placeholder="Street address or area" required>
                        </div>
                        <div class="form-group">
                            <label for="facilityCity">City <span class="required">*</span></label>
                            <input type="text" id="facilityCity" name="facilityCity" class="form-input" placeholder="City" required>
                        </div>
                        <div class="form-group">
                            <label for="facilityState">State <span class="required">*</span></label>
                            <input type="text" id="facilityState" name="facilityState" class="form-input" placeholder="State" required>
                        </div>
                        <div class="form-group">
                            <label for="facilityCountry">Country <span class="required">*</span></label>
                            <input type="text" id="facilityCountry" name="facilityCountry" class="form-input" placeholder="Country" value="India" required>
                        </div>
                        <div class="form-group">
                            <label for="facilitySize">Facility Size (sq. ft.)</label>
                            <input type="text" id="facilitySize" name="facilitySize" class="form-input" placeholder="e.g., 50,000">
                        </div>
                        <div class="form-group">
                            <label for="employees">Number of Employees</label>
                            <select id="employees" name="employees" class="form-input">
                                <option value="">Select range</option>
                                <option value="1-50">1 - 50</option>
                                <option value="51-200">51 - 200</option>
                                <option value="201-500">201 - 500</option>
                                <option value="501-1000">501 - 1000</option>
                                <option value="1000+">1000+</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="annualRevenue">Annual Revenue (approx.)</label>
                            <select id="annualRevenue" name="annualRevenue" class="form-input">
                                <option value="">Select range</option>
                                <option value="under-10cr">Under ₹10 Cr</option>
                                <option value="10-50cr">₹10 - 50 Cr</option>
                                <option value="50-100cr">₹50 - 100 Cr</option>
                                <option value="100-500cr">₹100 - 500 Cr</option>
                                <option value="500cr+">₹500 Cr+</option>
                                <option value="prefer-not">Prefer not to say</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Project Information -->
                    <div class="form-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                            <polyline points="2 17 12 22 22 17"/>
                            <polyline points="2 12 12 17 22 12"/>
                        </svg>
                        Project Information
                    </div>
                    <div class="form-grid">
                        <div class="form-group span-3">
                            <label for="projectType">Project Type <span class="required">*</span></label>
                            <select id="projectType" name="projectType" class="form-input" required>
                                <option value="">Select project type</option>
                                <option value="greenfield">Greenfield (New Factory Building Architecture)</option>
                                <option value="lightgreenfield">Light Greenfield (Only Workplace Layout Design for New Building)</option>
                                <option value="brownfield">Brownfield (Existing Factory Optimization)</option>
                                <option value="not-sure">Not Sure / Exploring Options</option>
                            </select>
                        </div>
                        
                        <div class="form-group span-3">
                            <span class="checkbox-group-label">Areas of Interest (select all that apply)</span>
                            <div class="checkbox-grid">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="interests[]" value="layout">
                                    <span>Factory Layout Design</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="interests[]" value="flow">
                                    <span>Material Flow Optimization</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="interests[]" value="lean">
                                    <span>Lean Implementation</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="interests[]" value="capacity">
                                    <span>Capacity Enhancement</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="interests[]" value="architecture">
                                    <span>Industrial Architecture</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="interests[]" value="safety">
                                    <span>Safety & Ergonomics</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="interests[]" value="automation">
                                    <span>Automation Integration</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="interests[]" value="warehouse">
                                    <span>Warehouse & Storage</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="interests[]" value="utilities">
                                    <span>Utility Planning</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group span-3">
                            <label for="currentChallenges">Current Challenges</label>
                            <textarea id="currentChallenges" name="currentChallenges" class="form-input" placeholder="What are the main challenges you're facing? (e.g., space constraints, inefficient material flow, high WIP inventory, poor utilization...)"></textarea>
                        </div>
                        
                        <div class="form-group span-3">
                            <label for="projectGoals">Project Goals & Expectations</label>
                            <textarea id="projectGoals" name="projectGoals" class="form-input" placeholder="What do you hope to achieve? (e.g., increase capacity by 30%, reduce walking distance, improve safety compliance...)"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="timeline">Expected Timeline</label>
                            <select id="timeline" name="timeline" class="form-input">
                                <option value="">When would you like to start?</option>
                                <option value="immediate">Immediately</option>
                                <option value="1-3months">1-3 Months</option>
                                <option value="3-6months">3-6 Months</option>
                                <option value="6-12months">6-12 Months</option>
                                <option value="exploring">Just Exploring</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="referral">How Did You Hear About Us?</label>
                            <select id="referral" name="referral" class="form-input">
                                <option value="">Select an option</option>
                                <option value="search">Internet Search</option>
                                <option value="linkedin">LinkedIn</option>
                                <option value="facebook">Facebook</option>
                                <option value="twitter">Twitter</option>
                                <option value="instagram">Instagram</option>
                                <option value="referral">Referral / Recommendation</option>
                                <option value="conference">Conference / Event</option>
                                <option value="publication">Article / Publication</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="preferredContact">Preferred Contact Method</label>
                            <select id="preferredContact" name="preferredContact" class="form-input">
                                <option value="">Select preference</option>
                                <option value="phone">Phone Call</option>
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="any">Preferred any of the above</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-submit">
                        <button type="submit" class="btn-submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            Submit Request
                        </button>
                        <p class="form-note">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            Your information is secure and will not be shared
                        </p>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="pulse-section alt-bg">
    <div class="pulse-container">
        <div class="section-header-pulse">
            <h2>Frequently Asked Questions</h2>
            <p>Common questions about the Pulse Check process</p>
        </div>
        
        <?php
        // Fetch active FAQs from database
        $faqResult = $conn->query("SELECT * FROM pulse_check_faqs WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
        $faqs = [];
        if ($faqResult) {
            while ($row = $faqResult->fetch_assoc()) {
                $faqs[] = $row;
            }
        }
        ?>
        
        <?php if (!empty($faqs)): ?>
        <div class="faq-grid">
            <?php foreach ($faqs as $faq): ?>
            <div class="faq-card">
                <h4>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    <?php echo htmlspecialchars($faq['question']); ?>
                </h4>
                <p><?php echo $faq['answer']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 3rem; color: #64748B;">
            <p>No FAQs available at the moment. Please check back later.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php $hideFooterCTA = true; ?>
<!-- CTA Section -->
<section class="pulse-cta">
    <div class="pulse-cta-inner">
        <h2>Have Questions? Let's Talk</h2>
        <p>Not ready for a Pulse Check yet? No problem. Reach out to us with any questions about our approach or how we might help.</p>
        <div class="cta-buttons">
            <a href="<?php echo url('contact.php'); ?>" class="btn-cta-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                Contact Us
            </a>
            <a href="<?php echo url('about.php'); ?>" class="btn-cta-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                Learn About Us
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
