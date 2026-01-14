<?php
$currentPage = 'post-commissioning';
$pageTitle = 'LFB Post-Commissioning Support | Solutions OptiSpace';
$pageDescription = 'Post‑commissioning support from OptiSpace ensures your Lean Factory Building (LFB) design translates into stable, high‑performing daily operations.';
$pageKeywords = 'post-commissioning support, factory startup support, manufacturing transition, plant stabilization, production ramp-up, operations optimization, factory implementation, lean implementation support, post-construction support, factory handover, operational excellence support';
include '../includes/header.php';
?>

<style>
/* ========================================
   POST-COMMISSIONING PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --pcs-orange: #E99431;
    --pcs-orange-light: rgba(233, 148, 49, 0.08);
    --pcs-blue: #3B82F6;
    --pcs-green: #10B981;
    --pcs-gray: #64748B;
    --pcs-dark: #1E293B;
    --pcs-text: #475569;
    --pcs-border: #E2E8F0;
}

/* Hero Section */
.pcs-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 6rem;
    position: relative;
    overflow: hidden;
}

.pcs-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
}

.pcs-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    position: relative;
    z-index: 2;
}

.hero-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.hero-content {
    text-align: left;
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

.pcs-hero h1 {
    font-size: 3.25rem;
    font-weight: 700;
    color: white;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

.pcs-hero h1 span {
    color: #E99431;
}

.pcs-hero-text {
    font-size: 1.2rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.75);
    max-width: 500px;
    margin-bottom: 2rem;
}

.hero-stats {
    display: flex;
    gap: 2rem;
    margin-top: 2rem;
}

.hero-stat {
    text-align: left;
}

.hero-stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--pcs-orange);
    margin-bottom: 0.25rem;
}

.hero-stat-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
}

.hero-visual {
    display: flex;
    justify-content: center;
}

.preview-card-stack {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.preview-benefit-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    min-width: 280px;
    cursor: default;
}

.preview-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--pcs-orange) 0%, #f5a854 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.preview-icon svg {
    width: 24px;
    height: 24px;
    color: white;
}

.preview-text {
    display: flex;
    flex-direction: column;
}

.preview-title {
    font-weight: 600;
    color: white;
    font-size: 1rem;
}

.preview-desc {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
}

/* Breadcrumb */
.pcs-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--pcs-border);
}

.pcs-breadcrumb ul {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.pcs-breadcrumb a {
    color: var(--pcs-gray);
    text-decoration: none;
}

.pcs-breadcrumb a:hover {
    color: var(--pcs-orange);
}

.pcs-breadcrumb li:last-child {
    color: var(--pcs-dark);
    font-weight: 500;
}

.pcs-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--pcs-border);
}

/* Section Styling */
.pcs-section {
    padding: 6rem 0;
    background: white;
}

.pcs-section.section-light {
    background: #FAFBFC;
}

.pcs-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.pcs-section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.pcs-section-header h2 {
    font-size: 2.5rem;
    color: var(--pcs-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.pcs-section-header p {
    font-size: 1.15rem;
    color: var(--pcs-text);
    max-width: 800px;
    margin: 0 auto;
    line-height: 1.7;
}

/* Content Cards */
.content-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

.content-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.content-card {
    background: white;
    border: 1px solid var(--pcs-border);
    border-radius: 12px;
    padding: 2rem;
    height: 100%;
    transition: all 0.3s ease;
}

.content-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    border-color: var(--pcs-orange);
}

.content-card h3 {
    font-size: 1.35rem;
    color: var(--pcs-dark);
    margin: 0 0 1.25rem 0;
    font-weight: 600;
}

.content-card h4 {
    font-size: 1.15rem;
    color: var(--pcs-dark);
    margin: 0 0 1rem 0;
    font-weight: 600;
}

.content-card p {
    font-size: 1rem;
    line-height: 1.7;
    color: var(--pcs-text);
    margin-bottom: 1.25rem;
}

.content-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.content-card ul li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.5rem 0;
    color: var(--pcs-text);
    font-size: 0.95rem;
    line-height: 1.6;
}

.content-card ul li::before {
    content: '✓';
    color: var(--pcs-orange);
    font-weight: 700;
    flex-shrink: 0;
}

.content-card .note {
    margin: 0;
    font-style: italic;
    color: var(--pcs-gray);
}

.content-card .outcome {
    margin: 1rem 0 0 0;
    font-weight: 600;
    color: var(--pcs-orange);
    font-size: 0.9rem;
}

/* Feature Card */
.feature-card {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    border-left: 4px solid var(--pcs-orange);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}

.feature-card h3 {
    font-size: 1.35rem;
    color: var(--pcs-dark);
    margin: 0 0 1.25rem 0;
    font-weight: 600;
}

.feature-card p {
    font-size: 1rem;
    line-height: 1.7;
    color: var(--pcs-text);
    margin-bottom: 1.25rem;
}

.feature-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.feature-card ul li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.5rem 0;
    color: var(--pcs-text);
    font-size: 0.95rem;
    line-height: 1.6;
}

.feature-card ul li::before {
    content: '→';
    color: var(--pcs-orange);
    font-weight: 700;
    flex-shrink: 0;
}

/* Transition Card */
.transition-card {
    background: white;
    border: 1px solid var(--pcs-border);
    border-radius: 16px;
    padding: 3rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    margin-top: 2rem;
}

.transition-card h3 {
    font-size: 1.35rem;
    color: var(--pcs-dark);
    margin: 0 0 1.5rem 0;
    font-weight: 600;
}

.transition-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

.transition-option h4 {
    font-size: 1.15rem;
    color: var(--pcs-dark);
    margin: 0 0 1rem 0;
    font-weight: 600;
}

.transition-option p {
    font-size: 0.95rem;
    line-height: 1.7;
    color: var(--pcs-text);
    margin-bottom: 1rem;
}

.transition-option ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.transition-option ul li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.4rem 0;
    color: var(--pcs-text);
    font-size: 0.9rem;
    line-height: 1.6;
}

.transition-option ul li::before {
    content: '•';
    color: var(--pcs-orange);
    font-weight: 700;
    flex-shrink: 0;
}

/* Training Card */
.training-card {
    background: white;
    border: 1px solid var(--pcs-border);
    border-radius: 16px;
    padding: 3rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}

.training-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

.training-item h3 {
    font-size: 1.15rem;
    color: var(--pcs-dark);
    margin: 0 0 1.25rem 0;
    font-weight: 600;
}

.training-item ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.training-item ul li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.4rem 0;
    color: var(--pcs-text);
    font-size: 0.9rem;
    line-height: 1.6;
}

.training-item ul li::before {
    content: '→';
    color: var(--pcs-orange);
    font-weight: 700;
    flex-shrink: 0;
}

/* CTA Section */
.pcs-cta {
    padding: 6rem 0;
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
    position: relative;
    overflow: hidden;
}

.pcs-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.08;
    background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
}

.cta-inner {
    position: relative;
    z-index: 1;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.cta-inner h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.cta-inner p {
    font-size: 1.15rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2.5rem;
    line-height: 1.7;
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
    background: var(--pcs-orange);
    color: white;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 2px solid var(--pcs-orange);
    box-shadow: 0 4px 12px rgba(233, 148, 49, 0.3);
}

.btn-cta-primary:hover {
    background: #d4851c;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(233, 148, 49, 0.4);
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
    transition: all 0.2s ease;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-cta-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
}

/* Responsive */
@media (max-width: 968px) {
    .hero-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .hero-content {
        text-align: center;
    }
    
    .pcs-hero-text {
        max-width: 100%;
        margin: 0 auto 2rem;
    }
    
    .hero-stats {
        justify-content: center;
    }
    
    .hero-visual {
        display: none;
    }
    
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .content-grid-3 {
        grid-template-columns: 1fr;
    }
    
    .transition-grid {
        grid-template-columns: 1fr;
    }
    
    .training-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .pcs-hero {
        padding: 7rem 0 4rem;
    }
    
    .pcs-hero h1 {
        font-size: 2.25rem;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }
    
    .hero-stat {
        text-align: center;
    }
    
    .pcs-section {
        padding: 4rem 0;
    }
    
    .pcs-section-header h2 {
        font-size: 2rem;
    }
    
    .pcs-cta {
        padding: 4rem 0;
    }
    
    .cta-inner h2 {
        font-size: 2rem;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<!-- Hero Section -->
<section class="pcs-hero">
    <div class="pcs-hero-inner">
        <div class="hero-grid">
            <div class="hero-content">
                <div class="hero-eyebrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    Post-Commissioning Support
                </div>
                <h1>LFB Post-Commissioning <span>Support</span></h1>
                <p class="pcs-hero-text">Completion is not the finish line it is the starting point of performance. We ensure the transition from construction to operation is seamless, guaranteeing that the LFB design delivers on its promise the moment production begins.</p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-value">Faster Ramp-Up</div>
                        <div class="hero-stat-label">Achieve full production capacity in record time.</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">Higher Adoption</div>
                        <div class="hero-stat-label">Ensure rapid workforce integration of new processes.</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">Sustained</div>
                        <div class="hero-stat-label">Embed a culture of sustained Lean improvement.</div>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="preview-card-stack">
                    <div class="preview-benefit-card">
                        <div class="preview-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="preview-text">
                            <span class="preview-title">Factory Shifting</span>
                            <span class="preview-desc">Seamless transition support</span>
                        </div>
                    </div>
                    <div class="preview-benefit-card">
                        <div class="preview-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div class="preview-text">
                            <span class="preview-title">Visual Factory</span>
                            <span class="preview-desc">Management by sight</span>
                        </div>
                    </div>
                    <div class="preview-benefit-card">
                        <div class="preview-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="preview-text">
                            <span class="preview-title">Training & Transfer</span>
                            <span class="preview-desc">Build internal capability</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="pcs-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li><a href="#">Services</a></li>
        <li>Post-Commissioning Support</li>
    </ul>
</nav>

<!-- Beyond Design Section -->
<section class="pcs-section">
    <div class="pcs-container">
        <div class="pcs-section-header">
            <h2>Beyond Design & Construction</h2>
            <p>Post‑commissioning support from OptiSpace ensures your Lean Factory Building (LFB) design translates into stable, high‑performing daily operations</p>
        </div>

        <div class="content-grid">
            <div class="content-card">
                <h3>The Critical Transition Phase</h3>
                <p>A well-designed facility is just the foundation. The real challenge lies in:</p>
                <ul>
                    <li>Transitioning from old to new facility without disrupting operations</li>
                    <li>Training teams on new layouts and processes</li>
                    <li>Implementing visual management systems</li>
                    <li>Establishing production planning rhythms</li>
                    <li>Optimizing supply chain for the new flow</li>
                    <li>Fine-tuning operations based on real-world feedback</li>
                </ul>
                <p><strong>Our team bridges the critical gap between architectural drawings and day‑to‑day operations.</strong></p>
            </div>
            <div class="feature-card">
                <h3>Why Continued Support Matters</h3>
                <p><strong>Facilities designed with LFB principles achieve faster and more stable performance when supported by experts through this transition phase.</strong></p>
                <ul>
                    <li><strong>Faster ramp-up</strong> to full production capacity</li>
                    <li><strong>Higher adoption</strong> of new processes and layouts</li>
                    <li><strong>Better sustainability</strong> of lean improvements</li>
                    <li><strong>Quicker resolution</strong> of operational issues</li>
                    <li><strong>Continuous improvement</strong> culture development</li>
                </ul>
                <p class="note">We stay with you through the critical months after commissioning.</p>
            </div>
        </div>
    </div>
</section>

<!-- Factory Shifting Section -->
<section class="pcs-section section-light">
    <div class="pcs-container">
        <div class="pcs-section-header">
            <h2>Factory Shifting Assistance</h2>
            <p>Moving a factory is a high-risk operation. When moving into an LFB‑designed facility, we help ensure equipment placement, material flows, and line-side stocking match the intended design exactly.</p>
        </div>

        <div class="content-grid-3">
            <div class="content-card">
                <h3>Pre-Move Planning</h3>
                <ul>
                    <li>Detailed relocation schedule</li>
                    <li>Equipment moving sequence</li>
                    <li>Inventory transition planning</li>
                    <li>Critical path identification</li>
                    <li>Risk mitigation strategies</li>
                    <li>Contingency planning</li>
                </ul>
            </div>
            <div class="content-card">
                <h3>During the Move</h3>
                <ul>
                    <li>On-site coordination support</li>
                    <li>Equipment placement verification</li>
                    <li>Layout conformance checks</li>
                    <li>Issue resolution</li>
                    <li>Progress tracking</li>
                    <li>Communication facilitation</li>
                </ul>
            </div>
            <div class="content-card">
                <h3>Post-Move Optimization</h3>
                <ul>
                    <li>Fine-tuning of layouts</li>
                    <li>Ergonomic adjustments</li>
                    <li>Material flow validation</li>
                    <li>Bottleneck resolution</li>
                    <li>Performance verification</li>
                    <li>Lessons learned documentation</li>
                </ul>
                <p class="outcome">Outcome: This reduces ramp‑up time and avoids the early chaos often seen in new facilities.</p>
            </div>
        </div>

        <div class="transition-card">
            <h3>Phased Transition Options</h3>
            <p>We help you choose the right moving strategy:</p>
            <div class="transition-grid">
                <div class="transition-option">
                    <h4>Big Bang Move</h4>
                    <p>Complete transition over a short shutdown period. Best for:</p>
                    <ul>
                        <li>Smaller facilities</li>
                        <li>Scheduled production breaks</li>
                        <li>When old facility must close immediately</li>
                    </ul>
                </div>
                <div class="transition-option">
                    <h4>Phased Migration</h4>
                    <p>Gradual transition over weeks or months. Best for:</p>
                    <ul>
                        <li>Large complex facilities</li>
                        <li>Continuous production requirements</li>
                        <li>When you can run both facilities temporarily</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visual Factory Section -->
<section class="pcs-section">
    <div class="pcs-container">
        <div class="pcs-section-header">
            <h2>Visual Factory Deployment</h2>
            <p>A well-designed layout should speak for itself. Visual factory deployment makes the LFB layout self‑explaining, allowing operators and supervisors to manage by sight, reducing training time and errors.</p>
        </div>

        <div class="content-grid">
            <div class="content-card">
                <h3>What is a Visual Factory?</h3>
                <p>A workplace designed so that anyone can understand the status of operations at a glance, without asking questions or checking computer systems.</p>
                <p>Visual management transforms your factory floor into a self-explaining, self-regulating system where problems surface immediately and solutions are obvious.</p>
            </div>
            <div class="content-card">
                <h3>Core Visual Elements</h3>
                <ul>
                    <li>Floor markings and traffic flow indicators</li>
                    <li>Location labels and address systems</li>
                    <li>Standard work instructions at workstations</li>
                    <li>Inventory level indicators</li>
                    <li>Quality alert systems</li>
                    <li>Safety and housekeeping standards</li>
                </ul>
            </div>
        </div>

        <div class="content-grid-3" style="margin-top: 2rem;">
            <div class="content-card">
                <h3>Visual Work Instructions</h3>
                <p>Clear, picture-based instructions at each workstation showing:</p>
                <ul>
                    <li>Operation sequence</li>
                    <li>Quality checkpoints</li>
                    <li>Tools and materials needed</li>
                    <li>Cycle time targets</li>
                </ul>
            </div>
            <div class="content-card">
                <h3>Visual Scheduling & Boards</h3>
                <p>Integrated displays that show:</p>
                <ul>
                    <li>Daily production targets</li>
                    <li>Actual vs. planned progress</li>
                    <li>Material availability status</li>
                    <li>Problem alerts and resolution</li>
                    <li>Production sequence and priorities</li>
                </ul>
            </div>
            <div class="content-card">
                <h3>Visual Inventory</h3>
                <p>Count-free systems using:</p>
                <ul>
                    <li>Min-max markers</li>
                    <li>Kanban cards and signals</li>
                    <li>Color-coded zones</li>
                    <li>FIFO lanes</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Production Planning Section -->
<section class="pcs-section section-light">
    <div class="pcs-container">
        <div class="pcs-section-header">
            <h2>Production Planning & Control</h2>
            <p>LFB defines the physical flow; production planning and control align schedules and capacities to that physical reality.</p>
        </div>

        <div class="content-grid">
            <div class="content-card">
                <h3>Production Planning Guidance</h3>
                <p>We help you establish robust planning processes:</p>
                <ul>
                    <li>Master production scheduling</li>
                    <li>Capacity planning and takt time calculation</li>
                    <li>Line balancing and workload leveling</li>
                    <li>Batch size optimization</li>
                    <li>Changeover reduction strategies</li>
                    <li>Planning rhythm establishment (daily, weekly, monthly)</li>
                </ul>
            </div>
            <div class="content-card">
                <h3>Performance Measurement</h3>
                <p>Setting up the right metrics and tracking systems:</p>
                <ul>
                    <li>OEE (Overall Equipment Effectiveness)</li>
                    <li>Cycle time and throughput monitoring</li>
                    <li>Quality metrics (FTY, FPY, PPM)</li>
                    <li>Inventory turns and WIP tracking</li>
                    <li>On-time delivery performance</li>
                    <li>Visual metric displays and dashboards</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Supply Chain Section -->
<section class="pcs-section">
    <div class="pcs-container">
        <div class="pcs-section-header">
            <h2>Supply Chain Optimization</h2>
            <p>Your supply chain must follow the new takt and material routes defined by LFB; we help you realign it.</p>
        </div>

        <div class="content-grid-3">
            <div class="content-card">
                <h3>Supplier Integration</h3>
                <p>Connecting suppliers to your new production rhythm:</p>
                <ul>
                    <li>Delivery frequency optimization</li>
                    <li>Packaging and presentation standards</li>
                    <li>Pull signal implementation (Kanban, VMI)</li>
                    <li>Supplier performance metrics</li>
                </ul>
            </div>

            <div class="content-card">
                <h3>Inventory Strategy</h3>
                <p>Right-sizing inventory for your optimized flow:</p>
                <ul>
                    <li>Safety stock calculations</li>
                    <li>Reorder point determination</li>
                    <li>Economic order quantity (EOQ) analysis</li>
                    <li>Consignment and vendor-managed inventory options</li>
                </ul>
            </div>

            <div class="content-card">
                <h3>Logistics Optimization</h3>
                <p>Streamlining inbound and outbound logistics:</p>
                <ul>
                    <li>Receiving and shipping procedures</li>
                    <li>Milk-run and route optimization</li>
                    <li>Cross-docking opportunities</li>
                    <li>Transportation mode selection</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Data & Analysis Section -->
<section class="pcs-section section-light">
    <div class="pcs-container">
        <div class="pcs-section-header">
            <h2>Data, Analysis, and Training</h2>
            <p>Post‑commissioning support is designed to build your internal capability to sustain and improve on the LFB baseline</p>
        </div>

        <div class="content-grid">
            <div class="content-card">
                <h3>Data Collection Systems</h3>
                <p>Setting up simple, effective data capture:</p>
                <ul>
                    <li>Production count tracking</li>
                    <li>Downtime and delay recording</li>
                    <li>Quality defect logging</li>
                    <li>Material consumption tracking</li>
                    <li>Manual vs. automated collection strategy</li>
                </ul>
            </div>
            <div class="content-card">
                <h3>Analysis & Problem-Solving</h3>
                <p>Turning data into actionable improvements:</p>
                <ul>
                    <li>Pareto analysis of losses</li>
                    <li>Root cause analysis methods</li>
                    <li>Trend identification</li>
                    <li>Corrective action tracking</li>
                    <li>Continuous improvement project prioritization</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Training Section -->
<section class="pcs-section">
    <div class="pcs-container">
        <div class="pcs-section-header">
            <h2>Training & Knowledge Transfer</h2>
            <p>Building internal capability for sustained success</p>
        </div>

        <div class="training-card">
            <div class="training-grid">
                <div class="training-item">
                    <h3>Operator Training</h3>
                    <ul>
                        <li>New layout orientation</li>
                        <li>Standard work instruction</li>
                        <li>Quality standards</li>
                        <li>Visual management use</li>
                    </ul>
                </div>
                <div class="training-item">
                    <h3>Supervisor Development</h3>
                    <ul>
                        <li>Production management</li>
                        <li>Problem-solving tools</li>
                        <li>Team leadership</li>
                        <li>Continuous improvement</li>
                    </ul>
                </div>
                <div class="training-item">
                    <h3>Management Coaching</h3>
                    <ul>
                        <li>Performance review systems</li>
                        <li>Strategic planning</li>
                        <li>Change management</li>
                        <li>Culture building</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $hideFooterCTA = true; ?>
<!-- CTA Section -->
<section class="pcs-cta">
    <div class="pcs-container">
        <div class="cta-inner">
            <h2>Stabilize, Ramp Up, and Continuously Improve</h2>
            <p>Our post-commissioning support helps your team achieve operational excellence in your LFB-designed facility. From stabilization to continuous improvement.</p>
            <div class="cta-buttons">
                <a href="<?php echo url('pulse-check.php'); ?>" class="btn-cta-primary">
                    Request Support
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="<?php echo url('process.php'); ?>" class="btn-cta-secondary">
                    View Our Process
                </a>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
