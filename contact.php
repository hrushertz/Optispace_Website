<style>
    body.contact-no-padding {
        padding-top: 0 !important;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('contact-no-padding');
    });
</script>
<?php
$currentPage = 'contact';
$pageTitle = 'Contact Us | Request a Pulse Check | Solutions OptiSpace';
$pageDescription = 'Get in touch with Solutions OptiSpace. Request a complimentary Pulse Check visit to discuss your factory design or optimization needs.';
include 'includes/header.php';
?>

<section class="page-hero" style="position: relative; min-height: 450px; display: flex; align-items: center;">
    <img src="assets/img/banner_1920x500.jpg" alt="Banner" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;">
    <div class="container" style="position: relative; z-index: 2; text-align: center; color: #fff; padding: 5rem 2rem 3rem 2rem; max-width: 800px; margin: 0 auto;">
        <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 1rem; letter-spacing: -0.02em;">Contact Us</h1>
        <p style="font-size: 1.25rem; font-weight: 400; opacity: 0.95;">Let's Start a Conversation About Your Factory</p>
    </div>
</section>

<section class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="/index.php">Home</a></li>
            <li>Contact</li>
        </ul>
    </div>
</section>

<section class="section" id="pulse-check" style="padding: 5rem 0;">
    <div class="container">
        <div class="section-header" style="margin-bottom: 3.5rem;">
            <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">Request a Complimentary Pulse Check</h2>
            <p style="font-size: 1.125rem; color: var(--text-medium); max-width: 700px; margin: 0 auto;">The first step toward optimizing your factory - with no obligation</p>
        </div>

        <div class="grid grid-2" style="margin-bottom: 4rem; gap: 2rem;">
            <div>
                <div class="card" style="height: 100%; padding: 2.5rem;">
                    <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; color: var(--text-dark);">What is a Pulse Check?</h3>
                    <p style="margin-bottom: 1.5rem; color: var(--text-medium);">A complimentary consultation visit where we:</p>
                    <ul class="feature-list" style="margin-bottom: 2rem;">
                        <li>Tour your facility (existing or proposed site)</li>
                        <li>Understand your manufacturing process</li>
                        <li>Learn about your challenges and goals</li>
                        <li>Identify preliminary opportunities</li>
                        <li>Explain how we can help</li>
                        <li>Answer all your questions</li>
                    </ul>
                    <p style="padding-top: 1.5rem; border-top: 2px solid var(--border-color); font-weight: 600; color: var(--text-dark);">Duration: 2-4 hours | Investment: Complimentary</p>
                </div>
            </div>
            <div>
                <div class="card" style="height: 100%; padding: 2.5rem; background: var(--bg-light); border-left: 4px solid var(--primary-color);">
                    <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; color: var(--text-dark);">What Happens Next?</h3>
                    <ol style="padding-left: 1.5rem; line-height: 2; margin-bottom: 2rem; color: var(--text-medium);">
                        <li>You submit the form below</li>
                        <li>We contact you within 24 hours to schedule</li>
                        <li>We visit your facility for the Pulse Check</li>
                        <li>We submit a detailed proposal (if there's a fit)</li>
                        <li>You decide whether to proceed - no pressure</li>
                    </ol>
                    <p style="padding-top: 1.5rem; border-top: 2px solid var(--border-color); font-style: italic; color: var(--text-medium);">
                        There's no cost and no obligation. It's simply an opportunity to explore whether our Lean Factory Building approach can help you.
                    </p>
                </div>
            </div>
        </div>

        <div class="contact-form" style="max-width: 900px; margin: 0 auto;">
            <form id="pulseCheckForm" method="post" action="#" style="background: white; padding: 3rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <h3 style="text-align: center; margin-bottom: 2.5rem; font-size: 1.75rem; font-weight: 600;">Request Your Pulse Check</h3>

                <div class="grid grid-2" style="gap: 1.5rem;">
                    <div class="form-group">
                        <label for="name" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Your Name *</label>
                        <input type="text" id="name" name="name" required style="padding: 0.875rem;">
                    </div>

                    <div class="form-group">
                        <label for="designation" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Designation *</label>
                        <input type="text" id="designation" name="designation" required style="padding: 0.875rem;">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="company" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Company Name *</label>
                    <input type="text" id="company" name="company" required style="padding: 0.875rem;">
                </div>

                <div class="grid grid-2" style="gap: 1.5rem; margin-top: 1.5rem;">
                    <div class="form-group">
                        <label for="email" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Email Address *</label>
                        <input type="email" id="email" name="email" required style="padding: 0.875rem;">
                    </div>

                    <div class="form-group">
                        <label for="phone" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required style="padding: 0.875rem;">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="industry" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Industry / Sector *</label>
                    <select id="industry" name="industry" required style="padding: 0.875rem;">
                        <option value="">Select Your Industry</option>
                        <option value="automotive">Automotive & Components</option>
                        <option value="electronics">Electronics & Electrical</option>
                        <option value="pharmaceuticals">Pharmaceuticals</option>
                        <option value="fmcg">FMCG & Food Processing</option>
                        <option value="plastics">Plastics & Polymers</option>
                        <option value="engineering">Engineering & Fabrication</option>
                        <option value="textiles">Textiles & Garments</option>
                        <option value="packaging">Packaging</option>
                        <option value="chemical">Chemical Processing</option>
                        <option value="other">Other (Please specify in message)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="projectType" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Project Type *</label>
                    <select id="projectType" name="projectType" required style="padding: 0.875rem;">
                        <option value="">Select Project Type</option>
                        <option value="greenfield">Greenfield - New Factory Design</option>
                        <option value="brownfield">Brownfield - Existing Factory Optimization</option>
                        <option value="expansion">Factory Expansion</option>
                        <option value="relocation">Factory Relocation</option>
                        <option value="consultation">General Consultation</option>
                        <option value="other">Other (Please specify in message)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="timeline" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Project Timeline *</label>
                    <select id="timeline" name="timeline" required style="padding: 0.875rem;">
                        <option value="">Select Timeline</option>
                        <option value="immediate">Immediate (Within 1 month)</option>
                        <option value="short">Short-term (1-3 months)</option>
                        <option value="medium">Medium-term (3-6 months)</option>
                        <option value="long">Long-term (6+ months)</option>
                        <option value="exploring">Just Exploring Options</option>
                    </select>
                    <small style="display: block; margin-top: 0.625rem; color: var(--text-light); font-size: 0.875rem;">Helps us understand urgency and plan our engagement</small>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="location" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Facility Location (City/State)</label>
                    <input type="text" id="location" name="location" placeholder="e.g., Pune, Maharashtra" style="padding: 0.875rem;">
                    <small style="display: block; margin-top: 0.625rem; color: var(--text-light); font-size: 0.875rem;">Helps us plan the Pulse Check visit</small>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="message" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Tell Us About Your Project *</label>
                    <textarea id="message" name="message" required placeholder="Please describe your current situation, challenges, or what you hope to achieve..." style="padding: 0.875rem; min-height: 140px;"></textarea>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label style="display: flex; align-items: start; gap: 0.625rem; cursor: pointer;">
                        <input type="checkbox" name="consent" required style="width: auto; margin-top: 0.25rem;">
                        <span style="font-size: 0.9375rem;">I consent to Solutions OptiSpace contacting me via email or phone to discuss this project *</span>
                    </label>
                </div>

                <div style="text-align: center; margin-top: 2.5rem;">
                    <button type="submit" class="btn btn-primary btn-large" style="padding: 1rem 3rem; font-size: 1.0625rem;">Submit Request</button>
                </div>

                <p style="text-align: center; color: var(--text-light); margin-top: 1.5rem; font-size: 0.875rem; line-height: 1.6;">
                    * Required fields. We respect your privacy and will never share your information.<br>
                    <strong style="color: var(--text-medium);">Business Hours:</strong> Mon–Sat, 10:00–18:00 IST<br>
                    <em>Prefer email or call? Reach us at <a href="mailto:info@optispace.com" style="color: var(--primary-color); text-decoration: none;">info@optispace.com</a> or <a href="tel:+919999999999" style="color: var(--primary-color); text-decoration: none;">+91 99999 99999</a></em>
                </p>
            </form>
        </div>
    </div>
</section>

<section class="section section-light" style="padding: 5rem 0;">
    <div class="container">
        <div class="section-header" style="margin-bottom: 3rem;">
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

<section class="section" style="padding: 5rem 0;">
    <div class="container" style="max-width: 650px;">
        <div class="section-header" style="margin-bottom: 2.5rem;">
            <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.75rem;">General Inquiries</h2>
            <p style="font-size: 1.0625rem; color: var(--text-medium);">Questions not related to a factory project? Use this form</p>
        </div>
        <div class="contact-form">
            <form id="contactForm" method="post" action="#" style="background: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div class="form-group">
                    <label for="contactName" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Your Name *</label>
                    <input type="text" id="contactName" name="contactName" required style="padding: 0.875rem;">
                </div>
                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="contactEmail" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Email Address *</label>
                    <input type="email" id="contactEmail" name="contactEmail" required style="padding: 0.875rem;">
                </div>
                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="contactMessage" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Your Message *</label>
                    <textarea id="contactMessage" name="contactMessage" required style="padding: 0.875rem; min-height: 140px;"></textarea>
                </div>
                <div style="text-align: center; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary btn-large" style="padding: 1rem 2.5rem;">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="section section-light" style="padding: 5rem 0;">
    <div class="container">
        <div class="section-header" style="margin-bottom: 3.5rem;">
            <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">Frequently Asked Questions</h2>
            <p style="font-size: 1.125rem; color: var(--text-medium);">Quick answers to common questions</p>
        </div>

        <div class="grid grid-2" style="gap: 2rem;">
            <div class="card" style="padding: 2rem; display: flex; flex-direction: column; height: 100%;">
                <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-dark);">What is Lean Factory Building (LFB)?</h4>
                <p style="color: var(--text-medium); line-height: 1.7; flex-grow: 1;">LFB is our inside-out approach: we design your manufacturing process first using Lean principles, then design the building around that optimized process - not the other way around.</p>
            </div>
            <div class="card" style="padding: 2rem; display: flex; flex-direction: column; height: 100%;">
                <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-dark);">Is the Pulse Check really free?</h4>
                <p style="color: var(--text-medium); line-height: 1.7; flex-grow: 1;">Yes, absolutely. The Pulse Check is complimentary with no obligation. We invest this time to understand your needs and demonstrate our value.</p>
            </div>
            <div class="card" style="padding: 2rem; display: flex; flex-direction: column; height: 100%;">
                <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-dark);">Do you work outside of India?</h4>
                <p style="color: var(--text-medium); line-height: 1.7; flex-grow: 1;">Yes. While most of our work is in India, we have experience with international projects. We can travel or coordinate remotely depending on project needs.</p>
            </div>
            <div class="card" style="padding: 2rem; display: flex; flex-direction: column; height: 100%;">
                <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-dark);">What if I'm not ready to proceed right away?</h4>
                <p style="color: var(--text-medium); line-height: 1.7; flex-grow: 1;">That's perfectly fine. Many clients engage us months or even years after the initial Pulse Check. We're here when you're ready.</p>
            </div>
            <div class="card" style="padding: 2rem; display: flex; flex-direction: column; height: 100%;">
                <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-dark);">Can you provide references?</h4>
                <p style="color: var(--text-medium); line-height: 1.7; flex-grow: 1;">Yes, we can connect you with existing clients in your industry who can share their experience working with us.</p>
            </div>
            <div class="card" style="padding: 2rem; display: flex; flex-direction: column; height: 100%;">
                <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-dark);">How long does a typical project take?</h4>
                <p style="color: var(--text-medium); line-height: 1.7; flex-grow: 1;">It varies by scope. Layout optimization projects typically take 2-3 months. Full greenfield architectural projects can take 6-12 months.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section" style="padding: 5rem 0;">
    <div class="container" style="text-align: center;">
        <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; color: white;">Ready to Transform Your Factory?</h2>
        <p style="font-size: 1.25rem; margin-bottom: 2.5rem; opacity: 0.95;">Take the first step with a complimentary Pulse Check</p>
        <a href="#pulse-check" class="btn btn-large" style="background-color: white; color: var(--primary-color); padding: 1rem 3rem; font-size: 1.0625rem; font-weight: 600; text-decoration: none;">Request Your Pulse Check</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
