<?php
$currentPage = 'contact';
$pageTitle = 'Contact Us | Request a Pulse Check | Solutions OptiSpace';
$pageDescription = 'Get in touch with Solutions OptiSpace. Request a complimentary Pulse Check visit to discuss your factory design or optimization needs.';
include 'includes/header.php';
?>

<style>
/* ========================================
   CONTACT PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --contact-orange: #E99431;
    --contact-orange-light: rgba(233, 148, 49, 0.08);
    --contact-blue: #3B82F6;
    --contact-blue-light: rgba(59, 130, 246, 0.08);
    --contact-green: #10B981;
    --contact-green-light: rgba(16, 185, 129, 0.08);
    --contact-dark: #1E293B;
    --contact-text: #475569;
    --contact-border: #E2E8F0;
}

/* Hero Section */
.contact-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 6rem;
    position: relative;
    overflow: hidden;
}

.contact-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
}

.contact-hero-inner {
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

.contact-hero h1 {
    font-size: 3.25rem;
    font-weight: 700;
    color: white;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

.contact-hero h1 span {
    color: #E99431;
}

.contact-hero-text {
    font-size: 1.2rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.75);
    margin-bottom: 0;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Breadcrumb */
.contact-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--contact-border);
}

.contact-breadcrumb ul {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.contact-breadcrumb a {
    color: var(--contact-text);
    text-decoration: none;
}

.contact-breadcrumb a:hover {
    color: var(--contact-orange);
}

.contact-breadcrumb li:last-child {
    color: var(--contact-dark);
    font-weight: 500;
}

.contact-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--contact-border);
}

/* Section Styles */
.contact-section {
    padding: 6rem 0;
}

.contact-section.alt-bg {
    background: #FAFBFC;
}

.contact-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.section-header-contact {
    text-align: center;
    margin-bottom: 4rem;
}

.section-header-contact h2 {
    font-size: 2.5rem;
    color: var(--contact-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.section-header-contact p {
    font-size: 1.15rem;
    color: var(--contact-text);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.7;
}

/* Contact Grid */
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 3rem;
    align-items: start;
}

/* Contact Info Cards */
.contact-info-stack {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.contact-info-card {
    background: white;
    border: 1px solid var(--contact-border);
    border-radius: 16px;
    padding: 2rem;
    transition: all 0.3s ease;
}

.contact-info-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
}

.info-card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--contact-border);
}

.info-icon {
    width: 48px;
    height: 48px;
    background: var(--contact-orange-light);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-icon svg {
    width: 24px;
    height: 24px;
    color: var(--contact-orange);
}

.info-card-header h3 {
    font-size: 1.25rem;
    color: var(--contact-dark);
    font-weight: 600;
    margin: 0;
}

.info-item {
    margin-bottom: 1.25rem;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    font-size: 0.85rem;
    color: var(--contact-text);
    font-weight: 500;
    margin-bottom: 0.35rem;
}

.info-value {
    font-size: 1rem;
    color: var(--contact-dark);
    line-height: 1.6;
}

.info-value a {
    color: var(--contact-orange);
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    transition: all 0.2s ease;
}

.info-value a:hover {
    color: #d4851c;
}

.info-value a svg {
    width: 16px;
    height: 16px;
}

/* Quick Connect Card */
.quick-connect-card {
    background: linear-gradient(135deg, var(--contact-orange) 0%, #f5a854 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
}

.quick-connect-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.quick-connect-card p {
    font-size: 0.95rem;
    opacity: 0.9;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.btn-pulse-check {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    color: var(--contact-orange);
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-pulse-check:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-pulse-check svg {
    width: 18px;
    height: 18px;
}

/* Contact Form */
.contact-form-card {
    background: white;
    border: 1px solid var(--contact-border);
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}

.form-header {
    margin-bottom: 2.5rem;
    text-align: center;
}

.form-header h3 {
    font-size: 1.75rem;
    color: var(--contact-dark);
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.form-header p {
    font-size: 1rem;
    color: var(--contact-text);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-group {
    margin-bottom: 0;
}

.form-group.full-width {
    grid-column: span 2;
}

.form-group label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--contact-dark);
    margin-bottom: 0.5rem;
}

.form-group label .required {
    color: #EF4444;
}

.form-input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 1px solid var(--contact-border);
    border-radius: 8px;
    font-size: 0.95rem;
    color: var(--contact-dark);
    background: #F8FAFC;
    transition: all 0.2s ease;
}

.form-input:focus {
    outline: none;
    border-color: var(--contact-orange);
    background: white;
    box-shadow: 0 0 0 3px var(--contact-orange-light);
}

.form-input::placeholder {
    color: #94A3B8;
}

textarea.form-input {
    min-height: 150px;
    resize: vertical;
}

.form-submit {
    margin-top: 2rem;
    text-align: center;
}

.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--contact-orange);
    color: white;
    padding: 1rem 2.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-submit:hover {
    background: #d4851c;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(233, 148, 49, 0.3);
}

.btn-submit svg {
    width: 20px;
    height: 20px;
}

/* FAQ Grid */
.faq-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.faq-card {
    background: white;
    border: 1px solid var(--contact-border);
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
    color: var(--contact-dark);
    margin-bottom: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.faq-card h4 svg {
    width: 20px;
    height: 20px;
    color: var(--contact-orange);
    flex-shrink: 0;
    margin-top: 2px;
}

.faq-card p {
    font-size: 0.95rem;
    color: var(--contact-text);
    line-height: 1.7;
    margin: 0;
    padding-left: 1.85rem;
}

/* CTA Section */
.contact-cta {
    padding: 6rem 0;
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    position: relative;
    overflow: hidden;
}

.contact-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.05;
    background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
}

.contact-cta-inner {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
    position: relative;
    z-index: 1;
}

.contact-cta h2 {
    font-size: 2.75rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.contact-cta p {
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
    background: var(--contact-orange);
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
    .contact-grid {
        grid-template-columns: 1fr;
    }
    
    .contact-info-stack {
        order: 2;
    }
    
    .contact-form-card {
        order: 1;
    }
}

@media (max-width: 768px) {
    .contact-hero {
        padding: 5rem 0 4rem;
    }
    
    .contact-hero h1 {
        font-size: 2.25rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-group.full-width {
        grid-column: span 1;
    }
    
    .faq-grid {
        grid-template-columns: 1fr;
    }
    
    .contact-form-card {
        padding: 2rem;
    }
}
</style>

<!-- Hero Section -->
<section class="contact-hero">
    <div class="contact-hero-inner">
        <div class="hero-eyebrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            Get In Touch
        </div>
        <h1>Let's Start a <span>Conversation</span></h1>
        <p class="contact-hero-text">Ready to transform your factory? We'd love to hear about your project and explore how we can help.</p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="contact-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li>Contact</li>
    </ul>
</nav>

<!-- Main Contact Section -->
<section class="contact-section">
    <div class="contact-container">
        <div class="contact-grid">
            <!-- Contact Info -->
            <div class="contact-info-stack">
                <div class="contact-info-card">
                    <div class="info-card-header">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <h3>Office Location</h3>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Address</div>
                        <div class="info-value">
                            [Complete Address Line 1]<br>
                            [Address Line 2]<br>
                            [City, State - PIN Code]
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <a href="mailto:info@optispace.com">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                info@optispace.com
                            </a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone</div>
                        <div class="info-value">
                            <a href="tel:+919999999999">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                +91 99999 99999
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="quick-connect-card">
                    <h3>Looking for a Factory Assessment?</h3>
                    <p>Request a complimentary Pulse Check — our on-site visit to understand your facility and identify optimization opportunities.</p>
                    <a href="<?php echo url('pulse-check.php'); ?>" class="btn-pulse-check">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        Request a Pulse Check
                    </a>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form-card">
                <div class="form-header">
                    <h3>Send Us a Message</h3>
                    <p>Questions not related to a factory project? Use this form.</p>
                </div>
                
                <form id="contactForm" method="post" action="#">
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
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="you@company.com" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-input" placeholder="+91 99999 99999">
                        </div>
                        <div class="form-group full-width">
                            <label for="subject">Subject <span class="required">*</span></label>
                            <input type="text" id="subject" name="subject" class="form-input" placeholder="How can we help?" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="message">Your Message <span class="required">*</span></label>
                            <textarea id="message" name="message" class="form-input" placeholder="Tell us about your inquiry..." required></textarea>
                        </div>
                    </div>
                    
                    <div class="form-submit">
                        <button type="submit" class="btn-submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13"/>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                            </svg>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="contact-section alt-bg">
    <div class="contact-container">
        <div class="section-header-contact">
            <h2>Frequently Asked Questions</h2>
            <p>Quick answers to common questions about working with us</p>
        </div>
        
        <div class="faq-grid">
            <div class="faq-card">
                <h4>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    What is Lean Factory Building (LFB)?
                </h4>
                <p>LFB is our inside-out approach: we design your manufacturing process first using Lean principles, then design the building around that optimized process — not the other way around.</p>
            </div>
            <div class="faq-card">
                <h4>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Is the Pulse Check really free?
                </h4>
                <p>Yes, absolutely. The Pulse Check is complimentary with no obligation. We invest this time to understand your needs and demonstrate our value.</p>
            </div>
            <div class="faq-card">
                <h4>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Do you work outside of India?
                </h4>
                <p>Yes. While most of our work is in India, we have experience with international projects. We can travel or coordinate remotely depending on project needs.</p>
            </div>
            <div class="faq-card">
                <h4>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    What if I'm not ready to proceed right away?
                </h4>
                <p>That's perfectly fine. Many clients engage us months or even years after the initial Pulse Check. We're here when you're ready.</p>
            </div>
            <div class="faq-card">
                <h4>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Can you provide references?
                </h4>
                <p>Yes, we can connect you with existing clients in your industry who can share their experience working with us.</p>
            </div>
            <div class="faq-card">
                <h4>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    How long does a typical project take?
                </h4>
                <p>It varies by scope. Layout optimization projects typically take 2-3 months. Full greenfield architectural projects can take 6-12 months.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="contact-cta">
    <div class="contact-cta-inner">
        <h2>Ready to Transform Your Factory?</h2>
        <p>Take the first step with a complimentary Pulse Check. We'll assess your facility and show you the potential for improvement.</p>
        <div class="cta-buttons">
            <a href="<?php echo url('pulse-check.php'); ?>" class="btn-cta-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                Request Your Pulse Check
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
