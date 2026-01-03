<?php
$currentPage = 'about';
$pageTitle = 'Leadership | Solutions OptiSpace';
$pageDescription = 'Solutions OptiSpace is led by practitioners who combine Lean, Six Sigma and factory architecture experience.';
include 'includes/header.php';
?>

<style>
/* ========================================
   LEADERSHIP PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --leadership-orange: #E99431;
    --leadership-orange-light: rgba(233, 148, 49, 0.08);
    --leadership-blue: #3B82F6;
    --leadership-blue-light: rgba(59, 130, 246, 0.08);
    --leadership-green: #10B981;
    --leadership-green-light: rgba(16, 185, 129, 0.08);
    --leadership-dark: #1E293B;
    --leadership-text: #475569;
    --leadership-border: #E2E8F0;
}

/* Hero Section */
.leadership-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 6rem;
    position: relative;
    overflow: hidden;
}

.leadership-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
}

.leadership-hero-inner {
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

.leadership-hero h1 {
    font-size: 3.25rem;
    font-weight: 700;
    color: white;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

.leadership-hero h1 span {
    color: #E99431;
}

.leadership-hero-text {
    font-size: 1.2rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.75);
    margin-bottom: 0;
    max-width: 650px;
    margin-left: auto;
    margin-right: auto;
}

/* Breadcrumb */
.leadership-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--leadership-border);
}

.leadership-breadcrumb ul {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.leadership-breadcrumb a {
    color: var(--leadership-text);
    text-decoration: none;
}

.leadership-breadcrumb a:hover {
    color: var(--leadership-orange);
}

.leadership-breadcrumb li:last-child {
    color: var(--leadership-dark);
    font-weight: 500;
}

.leadership-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--leadership-border);
}

/* Section Styles */
.leadership-section {
    padding: 6rem 0;
}

.leadership-section.alt-bg {
    background: #FAFBFC;
}

.leadership-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.section-header-leadership {
    text-align: center;
    margin-bottom: 4rem;
}

.section-header-leadership h2 {
    font-size: 2.5rem;
    color: var(--leadership-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.section-header-leadership p {
    font-size: 1.15rem;
    color: var(--leadership-text);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.7;
}

/* Leader Profile Card */
.leader-profile {
    background: white;
    border: 1px solid var(--leadership-border);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}

.leader-profile-grid {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 0;
}

.leader-photo-section {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 3rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.leader-photo {
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border: 3px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
}

.leader-photo svg {
    width: 80px;
    height: 80px;
    color: rgba(255, 255, 255, 0.5);
}

.leader-name {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
}

.leader-title {
    font-size: 1rem;
    color: var(--leadership-orange);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.leader-subtitle {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 1.5rem;
}

.leader-location {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    padding: 0.75rem 1.25rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 100px;
}

.leader-location svg {
    width: 16px;
    height: 16px;
}

.leader-content {
    padding: 3rem;
}

.leader-quote {
    background: var(--leadership-orange-light);
    border-left: 4px solid var(--leadership-orange);
    padding: 1.5rem;
    border-radius: 0 12px 12px 0;
    margin-bottom: 2.5rem;
}

.leader-quote p {
    font-size: 1.25rem;
    font-style: italic;
    color: var(--leadership-dark);
    margin: 0;
    line-height: 1.6;
}

.leader-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-bottom: 2.5rem;
}

.leader-detail-card {
    background: #F8FAFC;
    border-radius: 12px;
    padding: 1.5rem;
}

.leader-detail-card h4 {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--leadership-orange);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.leader-detail-card h4 svg {
    width: 18px;
    height: 18px;
}

.detail-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.detail-list li {
    font-size: 0.9rem;
    color: var(--leadership-text);
    padding: 0.5rem 0;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
}

.detail-list li svg {
    width: 16px;
    height: 16px;
    color: var(--leadership-green);
    flex-shrink: 0;
    margin-top: 2px;
}

/* Skills Tags */
.skills-section {
    margin-bottom: 2.5rem;
}

.skills-section h4 {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--leadership-orange);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}

.skills-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.skill-tag {
    padding: 0.5rem 1rem;
    background: #F8FAFC;
    border: 1px solid var(--leadership-border);
    border-radius: 100px;
    font-size: 0.85rem;
    color: var(--leadership-text);
    font-weight: 500;
    transition: all 0.2s ease;
}

.skill-tag:hover {
    background: var(--leadership-orange-light);
    border-color: var(--leadership-orange);
    color: var(--leadership-orange);
}

/* Recognition Section */
.recognition-section {
    background: #F8FAFC;
    border-radius: 12px;
    padding: 2rem;
}

.recognition-section h4 {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--leadership-orange);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.recognition-section h4 svg {
    width: 18px;
    height: 18px;
}

.recognition-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.recognition-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.9rem;
    color: var(--leadership-text);
}

.recognition-item svg {
    width: 18px;
    height: 18px;
    color: var(--leadership-blue);
    flex-shrink: 0;
}

/* Philosophy Section at Bottom of Profile */
.philosophy-section {
    margin-top: 2.5rem;
    padding-top: 2.5rem;
    border-top: 1px solid var(--leadership-border);
}

.philosophy-section h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--leadership-dark);
    margin-bottom: 1rem;
}

.philosophy-section p {
    font-size: 0.95rem;
    color: var(--leadership-text);
    line-height: 1.8;
    margin-bottom: 1rem;
}

.philosophy-section p:last-child {
    margin-bottom: 0;
}

.philosophy-section strong {
    color: var(--leadership-dark);
}

/* What Drives Us Section */
.drives-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

.drive-card {
    background: white;
    border: 1px solid var(--leadership-border);
    border-radius: 16px;
    padding: 2.5rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.drive-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--leadership-orange);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.drive-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    transform: translateY(-4px);
}

.drive-card:hover::before {
    transform: scaleX(1);
}

.drive-icon {
    width: 56px;
    height: 56px;
    background: var(--leadership-orange-light);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.drive-card:nth-child(2) .drive-icon {
    background: var(--leadership-blue-light);
}

.drive-card:nth-child(3) .drive-icon {
    background: var(--leadership-green-light);
}

.drive-icon svg {
    width: 28px;
    height: 28px;
    color: var(--leadership-orange);
}

.drive-card:nth-child(2) .drive-icon svg {
    color: var(--leadership-blue);
}

.drive-card:nth-child(3) .drive-icon svg {
    color: var(--leadership-green);
}

.drive-card h3 {
    font-size: 1.25rem;
    color: var(--leadership-dark);
    margin-bottom: 1rem;
    font-weight: 600;
}

.drive-card p {
    font-size: 0.95rem;
    color: var(--leadership-text);
    line-height: 1.7;
    margin: 0;
}

/* CTA Section */
.leadership-cta {
    padding: 6rem 0;
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    position: relative;
    overflow: hidden;
}

.leadership-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.05;
    background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
}

.leadership-cta-inner {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
    position: relative;
    z-index: 1;
}

.leadership-cta h2 {
    font-size: 2.75rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.leadership-cta p {
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
    background: var(--leadership-orange);
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
    .leader-profile-grid {
        grid-template-columns: 1fr;
    }
    
    .leader-photo-section {
        padding: 2.5rem;
    }
    
    .leader-details-grid {
        grid-template-columns: 1fr;
    }
    
    .drives-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .leadership-hero {
        padding: 5rem 0 4rem;
    }
    
    .leadership-hero h1 {
        font-size: 2.25rem;
    }
    
    .drives-grid {
        grid-template-columns: 1fr;
    }
    
    .recognition-grid {
        grid-template-columns: 1fr;
    }
    
    .leader-content {
        padding: 2rem;
    }
}
</style>

<!-- Hero Section -->
<section class="leadership-hero">
    <div class="leadership-hero-inner">
        <div class="hero-eyebrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            Meet Our Founder
        </div>
        <h1>Leadership <span>& Vision</span></h1>
        <p class="leadership-hero-text">Practitioners who combine Lean, Six Sigma, and factory architecture experience to deliver extraordinary results.</p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="leadership-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li><a href="<?php echo url('about.php'); ?>">About</a></li>
        <li>Leadership</li>
    </ul>
</nav>

<!-- Leader Profile Section -->
<section class="leadership-section">
    <div class="leadership-container">
        <div class="section-header-leadership">
            <h2>Meet Our Leadership</h2>
            <p>Led by proven lean manufacturing and factory design experts</p>
        </div>
        
        <div class="leader-profile">
            <div class="leader-profile-grid">
                <div class="leader-photo-section">
                    <div class="leader-photo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <div class="leader-name">Minish Umrani</div>
                    <div class="leader-title">Founder & CEO, Solutions KMS</div>
                    <div class="leader-subtitle">Director, Solutions OptiSpace</div>
                    <div class="leader-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Pune, Maharashtra, India
                    </div>
                </div>
                <div class="leader-content">
                    <div class="leader-quote">
                        <p>"The extraordinary begins where the ordinary ends."</p>
                    </div>
                    
                    <div class="leader-details-grid">
                        <div class="leader-detail-card">
                            <h4>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                </svg>
                                Education
                            </h4>
                            <ul class="detail-list">
                                <li>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    BA, DBM, Lean & Six Sigma Black Belt
                                </li>
                                <li>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Anant English School, Satara (1975-1980)
                                </li>
                            </ul>
                        </div>
                        <div class="leader-detail-card">
                            <h4>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                </svg>
                                Experience
                            </h4>
                            <ul class="detail-list">
                                <li>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Founder & CEO, Solutions Kaizen Management Systems
                                </li>
                                <li>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Director, Solutions OptiSpace
                                </li>
                                <li>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Crane Process Flow Technologies Ltd
                                </li>
                                <li>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Acumen and Grin Solutions Pvt. Ltd.
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="skills-section">
                        <h4>Skills & Expertise</h4>
                        <div class="skills-tags">
                            <span class="skill-tag">Process Flow</span>
                            <span class="skill-tag">Six Sigma</span>
                            <span class="skill-tag">Lean Manufacturing</span>
                            <span class="skill-tag">Value Stream Mapping</span>
                            <span class="skill-tag">Factory Design</span>
                            <span class="skill-tag">Theory of Constraints</span>
                            <span class="skill-tag">TPM</span>
                            <span class="skill-tag">Logistics</span>
                        </div>
                    </div>
                    
                    <div class="recognition-section">
                        <h4>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="8" r="7"/>
                                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                            </svg>
                            Recognition & Affiliations
                        </h4>
                        <div class="recognition-grid">
                            <div class="recognition-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                250+ businesses transformed
                            </div>
                            <div class="recognition-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                75+ industrial segments served
                            </div>
                            <div class="recognition-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                20+ years of excellence
                            </div>
                            <div class="recognition-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                Pioneer of Inside-Out Design
                            </div>
                        </div>
                    </div>
                    
                    <div class="philosophy-section">
                        <h4>Philosophy & Approach</h4>
                        <p>Minish brings a unique combination of lean manufacturing expertise and architectural thinking to every project. <strong>His belief that "the extraordinary begins where the ordinary ends" drives him to push beyond conventional factory architecture</strong> into the realm of Lean Factory Building (LFB).</p>
                        <p>With deep knowledge of Lean, Six Sigma, and Theory of Constraints, combined with practical experience across 75+ industrial segments, Minish ensures that every factory design is not just functional, but truly optimized for operational excellence.</p>
                        <p>He is known for his hands-on approach, attention to detail, and unwavering commitment to delivering measurable results for clients. His leadership has established OptiSpace as a thought leader in the inside-out design philosophy.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What Drives Us Section -->
<section class="leadership-section alt-bg">
    <div class="leadership-container">
        <div class="section-header-leadership">
            <h2>What Drives Us</h2>
            <p>The principles behind our leadership approach</p>
        </div>
        
        <div class="drives-grid">
            <div class="drive-card">
                <div class="drive-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
                <h3>Practitioner, Not Theorist</h3>
                <p>Minish is a practitioner who has worked hands-on with over 250 businesses across 75+ industrial segments. He brings real-world experience, not just academic knowledge.</p>
            </div>
            <div class="drive-card">
                <div class="drive-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                        <line x1="9" y1="9" x2="9.01" y2="9"/>
                        <line x1="15" y1="9" x2="15.01" y2="9"/>
                    </svg>
                </div>
                <h3>Innovation & Excellence</h3>
                <p>Under Minish's leadership, OptiSpace has pioneered the Inside-Out Design approach — designing the process first, then building the factory around it.</p>
            </div>
            <div class="drive-card">
                <div class="drive-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3>Client-Centric Focus</h3>
                <p>Every project is approached with a deep understanding of the client's business challenges and aspirations. Success is measured by client results, not just project completion.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="leadership-cta">
    <div class="leadership-cta-inner">
        <h2>Ready to Work With Our Team?</h2>
        <p>Connect with our experienced leadership team. Start with a complimentary Pulse Check and discover the OptiSpace difference.</p>
        <div class="cta-buttons">
            <a href="<?php echo url('pulse-check.php'); ?>" class="btn-cta-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                Request Your Pulse Check
            </a>
            <a href="<?php echo url('team.php'); ?>" class="btn-cta-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Meet Our Team
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
