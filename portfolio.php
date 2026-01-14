<?php
$currentPage = 'portfolio';
$pageTitle = 'Portfolio & Success Stories | Solutions OptiSpace';
$pageDescription = '250+ businesses transformed across 75+ industrial segments. View our sample designs, innovations, and client success stories.';
$pageKeywords = 'factory design portfolio, manufacturing projects, LFB case studies, OptiSpace projects, factory transformations, industrial design portfolio, before after factory, manufacturing innovations, factory design examples, lean manufacturing results, industry solutions, success stories, 75 industries, 250 businesses';
include 'includes/header.php';
?>

<style>
/* ========================================
   PORTFOLIO PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --portfolio-orange: #E99431;
    --portfolio-orange-light: rgba(233, 148, 49, 0.08);
    --portfolio-blue: #3B82F6;
    --portfolio-blue-light: rgba(59, 130, 246, 0.08);
    --portfolio-green: #10B981;
    --portfolio-green-light: rgba(16, 185, 129, 0.08);
    --portfolio-purple: #8B5CF6;
    --portfolio-purple-light: rgba(139, 92, 246, 0.08);
    --portfolio-dark: #1E293B;
    --portfolio-text: #475569;
    --portfolio-border: #E2E8F0;
}

/* Hero Section */
.portfolio-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 6rem;
    position: relative;
    overflow: hidden;
}

.portfolio-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
}

.portfolio-hero-inner {
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

.portfolio-hero h1 {
    font-size: 3.25rem;
    font-weight: 700;
    color: white;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

.portfolio-hero h1 span {
    color: #E99431;
}

.portfolio-hero-text {
    font-size: 1.2rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.75);
    margin-bottom: 3rem;
    max-width: 650px;
    margin-left: auto;
    margin-right: auto;
}

.hero-stats-row {
    display: flex;
    justify-content: center;
    gap: 4rem;
    flex-wrap: wrap;
}

.hero-stat {
    text-align: center;
}

.hero-stat-value {
    font-size: 3rem;
    font-weight: 700;
    color: white;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.hero-stat-label {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.6);
}

/* Breadcrumb */
.portfolio-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--portfolio-border);
}

.portfolio-breadcrumb ul {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.portfolio-breadcrumb a {
    color: var(--portfolio-text);
    text-decoration: none;
}

.portfolio-breadcrumb a:hover {
    color: var(--portfolio-orange);
}

.portfolio-breadcrumb li:last-child {
    color: var(--portfolio-dark);
    font-weight: 500;
}

.portfolio-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--portfolio-border);
}

/* Section Styles */
.portfolio-section {
    padding: 6rem 0;
}

.portfolio-section.alt-bg {
    background: #FAFBFC;
}

.portfolio-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.section-header-portfolio {
    text-align: center;
    margin-bottom: 4rem;
}

.section-header-portfolio h2 {
    font-size: 2.5rem;
    color: var(--portfolio-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.section-header-portfolio p {
    font-size: 1.15rem;
    color: var(--portfolio-text);
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.7;
}

/* Industries Grid */
.industries-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.industry-card {
    background: white;
    border: 1px solid var(--portfolio-border);
    border-radius: 12px;
    padding: 1.75rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.industry-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--portfolio-orange);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.industry-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    transform: translateY(-3px);
}

.industry-card:hover::before {
    transform: scaleX(1);
}

.industry-icon {
    width: 48px;
    height: 48px;
    background: var(--portfolio-orange-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}

.industry-card:nth-child(2) .industry-icon { background: var(--portfolio-blue-light); }
.industry-card:nth-child(3) .industry-icon { background: var(--portfolio-green-light); }
.industry-card:nth-child(4) .industry-icon { background: var(--portfolio-purple-light); }
.industry-card:nth-child(5) .industry-icon { background: var(--portfolio-blue-light); }
.industry-card:nth-child(6) .industry-icon { background: var(--portfolio-green-light); }
.industry-card:nth-child(7) .industry-icon { background: var(--portfolio-orange-light); }
.industry-card:nth-child(8) .industry-icon { background: var(--portfolio-purple-light); }

.industry-icon svg {
    width: 24px;
    height: 24px;
    color: var(--portfolio-orange);
}

.industry-card:nth-child(2) .industry-icon svg { color: var(--portfolio-blue); }
.industry-card:nth-child(3) .industry-icon svg { color: var(--portfolio-green); }
.industry-card:nth-child(4) .industry-icon svg { color: var(--portfolio-purple); }
.industry-card:nth-child(5) .industry-icon svg { color: var(--portfolio-blue); }
.industry-card:nth-child(6) .industry-icon svg { color: var(--portfolio-green); }
.industry-card:nth-child(7) .industry-icon svg { color: var(--portfolio-orange); }
.industry-card:nth-child(8) .industry-icon svg { color: var(--portfolio-purple); }

.industry-card h4 {
    font-size: 1rem;
    color: var(--portfolio-dark);
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.industry-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.industry-list li {
    font-size: 0.875rem;
    color: var(--portfolio-text);
    padding: 0.35rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.industry-list li::before {
    content: '';
    width: 4px;
    height: 4px;
    background: var(--portfolio-orange);
    border-radius: 50%;
    flex-shrink: 0;
}

/* Sample Designs Grid */
.designs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

.design-card {
    background: white;
    border: 1px solid var(--portfolio-border);
    border-radius: 16px;
    padding: 2rem;
    transition: all 0.3s ease;
}

.design-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    transform: translateY(-4px);
}

.design-preview {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    border-radius: 12px;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}

.design-preview svg {
    width: 64px;
    height: 64px;
    color: var(--portfolio-orange);
    opacity: 0.8;
}

.design-card h3 {
    font-size: 1.25rem;
    color: var(--portfolio-dark);
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.design-card p {
    font-size: 0.95rem;
    color: var(--portfolio-text);
    line-height: 1.7;
    margin-bottom: 1rem;
}

.design-meta {
    font-size: 0.875rem;
    color: var(--portfolio-text);
    font-weight: 500;
    padding-top: 1rem;
    border-top: 1px solid var(--portfolio-border);
}

/* Request Banner */
.request-banner {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    border: 1px solid var(--portfolio-border);
    border-radius: 16px;
    padding: 3rem;
    text-align: center;
    margin-top: 3rem;
}

.request-banner h3 {
    font-size: 1.5rem;
    color: var(--portfolio-dark);
    margin-bottom: 1rem;
}

.request-banner p {
    font-size: 1rem;
    color: var(--portfolio-text);
    margin-bottom: 1.5rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.btn-primary-portfolio {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--portfolio-orange);
    color: white;
    padding: 0.875rem 1.75rem;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary-portfolio:hover {
    background: #d4851c;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(233, 148, 49, 0.3);
}

.btn-primary-portfolio svg {
    width: 18px;
    height: 18px;
}

/* Innovations Grid */
.innovations-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

.innovation-card {
    background: white;
    border: 1px solid var(--portfolio-border);
    border-radius: 16px;
    padding: 2rem;
    display: flex;
    gap: 1.5rem;
    transition: all 0.3s ease;
}

.innovation-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
}

.innovation-icon {
    width: 56px;
    height: 56px;
    background: var(--portfolio-orange-light);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.innovation-card:nth-child(2) .innovation-icon { background: var(--portfolio-blue-light); }
.innovation-card:nth-child(3) .innovation-icon { background: var(--portfolio-green-light); }
.innovation-card:nth-child(4) .innovation-icon { background: var(--portfolio-purple-light); }

.innovation-icon svg {
    width: 28px;
    height: 28px;
    color: var(--portfolio-orange);
}

.innovation-card:nth-child(2) .innovation-icon svg { color: var(--portfolio-blue); }
.innovation-card:nth-child(3) .innovation-icon svg { color: var(--portfolio-green); }
.innovation-card:nth-child(4) .innovation-icon svg { color: var(--portfolio-purple); }

.innovation-content h3 {
    font-size: 1.15rem;
    color: var(--portfolio-dark);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.innovation-content p {
    font-size: 0.9rem;
    color: var(--portfolio-text);
    margin-bottom: 1rem;
}

.innovation-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
}

.innovation-list li {
    font-size: 0.85rem;
    color: var(--portfolio-text);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.innovation-list li svg {
    width: 14px;
    height: 14px;
    color: var(--portfolio-green);
    flex-shrink: 0;
}

/* Success Stories Grid */
.success-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

.success-card {
    background: white;
    border: 1px solid var(--portfolio-border);
    border-radius: 16px;
    padding: 2rem;
    transition: all 0.3s ease;
}

.success-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
}

.success-tags {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.tag {
    padding: 0.35rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tag-type {
    background: var(--portfolio-orange-light);
    color: var(--portfolio-orange);
}

.tag-industry {
    background: var(--portfolio-blue-light);
    color: var(--portfolio-blue);
}

.success-card h3 {
    font-size: 1.25rem;
    color: var(--portfolio-dark);
    margin-bottom: 1rem;
    font-weight: 600;
}

.success-detail {
    margin-bottom: 0.75rem;
}

.success-detail strong {
    color: var(--portfolio-dark);
    font-size: 0.9rem;
}

.success-detail p {
    font-size: 0.9rem;
    color: var(--portfolio-text);
    margin: 0.25rem 0 0;
    line-height: 1.6;
}

.success-results {
    margin-top: 1.25rem;
    padding-top: 1.25rem;
    border-top: 1px solid var(--portfolio-border);
}

.success-results h4 {
    font-size: 0.85rem;
    color: var(--portfolio-dark);
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.results-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
}

.results-list li {
    font-size: 0.85rem;
    color: var(--portfolio-text);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.results-list li svg {
    width: 16px;
    height: 16px;
    color: var(--portfolio-green);
    flex-shrink: 0;
}

/* Before/After Section */
.comparison-card {
    background: white;
    border: 1px solid var(--portfolio-border);
    border-radius: 16px;
    padding: 3rem;
}

.comparison-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
}

.comparison-side h3 {
    font-size: 1.25rem;
    color: var(--portfolio-dark);
    margin-bottom: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.comparison-side h3 svg {
    width: 24px;
    height: 24px;
}

.before-side h3 svg { color: #EF4444; }
.after-side h3 svg { color: var(--portfolio-green); }

.comparison-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.comparison-list li {
    font-size: 0.95rem;
    color: var(--portfolio-text);
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--portfolio-border);
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.comparison-list li:last-child {
    border-bottom: none;
}

.comparison-list li svg {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
    margin-top: 2px;
}

.before-side .comparison-list li svg { color: #EF4444; }
.after-side .comparison-list li svg { color: var(--portfolio-green); }

/* CTA Section */
.portfolio-cta {
    padding: 4.5rem 0;
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    position: relative;
    overflow: hidden;
}

.portfolio-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.05;
    background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
}

.portfolio-cta-inner {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
    position: relative;
    z-index: 1;
}

.portfolio-cta h2 {
    font-size: 2.25rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1.25rem;
    line-height: 1.2;
}

.portfolio-cta p {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.85);
    line-height: 1.7;
    margin-bottom: 2rem;
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
    gap: 0.75rem;
    background: var(--portfolio-orange);
    color: white;
    padding: 1.125rem 2.5rem;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(233, 148, 49, 0.35);
}

.btn-cta-primary:hover {
    background: #d4851c;
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(233, 148, 49, 0.45);
}

.btn-cta-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: transparent;
    color: white;
    padding: 1.125rem 2.5rem;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.btn-cta-secondary:hover {
    border-color: white;
    background: rgba(255, 255, 255, 0.1);
}

/* Responsive */
@media (max-width: 1024px) {
    .industries-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .designs-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .portfolio-hero {
        padding: 5rem 0 4rem;
    }
    
    .portfolio-hero h1 {
        font-size: 2.25rem;
    }
    
    .hero-stats-row {
        gap: 2rem;
    }
    
    .industries-grid,
    .innovations-grid,
    .success-grid,
    .designs-grid {
        grid-template-columns: 1fr;
    }
    
    .comparison-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .innovation-card {
        flex-direction: column;
    }
    
    .innovation-list {
        grid-template-columns: 1fr;
    }
    
    .results-list {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Hero Section -->
<section class="portfolio-hero">
    <div class="portfolio-hero-inner">
        <div class="hero-eyebrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            Proven Results
        </div>
        <h1>Our Portfolio & <span>Success Stories</span></h1>
        <p class="portfolio-hero-text">Theory is good, but results are better. Explore how Lean Factory Building has transformed manufacturing facilities across diverse sectors.</p>
        <div class="hero-stats-row">
            <div class="hero-stat">
                <div class="hero-stat-value">250+</div>
                <div class="hero-stat-label">Businesses Transformed</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">75+</div>
                <div class="hero-stat-label">Industrial Segments</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">20+</div>
                <div class="hero-stat-label">Years Experience</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">100%</div>
                <div class="hero-stat-label">Client Satisfaction</div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="portfolio-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li>Portfolio</li>
    </ul>
</nav>

<!-- Industries Section -->
<section class="portfolio-section alt-bg">
    <div class="portfolio-container">
        <div class="section-header-portfolio">
            <h2>Industries We Serve</h2>
            <p>LFB principles applied with respect to each industry's unique regulatory and operational realities</p>
        </div>
        
        <div class="industries-grid">
            <div class="industry-card">
                <div class="industry-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v4M12 18v4M2 12h4M18 12h4"/>
                    </svg>
                </div>
                <h4>Automotive & Components</h4>
                <ul class="industry-list">
                    <li>Tier 1 & Tier 2 suppliers</li>
                    <li>Component manufacturing</li>
                    <li>Assembly operations</li>
                </ul>
            </div>
            <div class="industry-card">
                <div class="industry-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
                <h4>Engineering & Fabrication</h4>
                <ul class="industry-list">
                    <li>Machine shops</li>
                    <li>Sheet metal fabrication</li>
                    <li>Heavy engineering</li>
                </ul>
            </div>
            <div class="industry-card">
                <div class="industry-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="4" y="4" width="16" height="16" rx="2"/>
                        <rect x="9" y="9" width="6" height="6"/>
                        <path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/>
                    </svg>
                </div>
                <h4>Electronics & Electrical</h4>
                <ul class="industry-list">
                    <li>PCB assembly</li>
                    <li>Cable harness</li>
                    <li>Switchgear manufacturing</li>
                </ul>
            </div>
            <div class="industry-card">
                <div class="industry-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <h4>FMCG & Consumer Goods</h4>
                <ul class="industry-list">
                    <li>Food processing</li>
                    <li>Packaging operations</li>
                    <li>Consumer products</li>
                </ul>
            </div>
            <div class="industry-card">
                <div class="industry-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                </div>
                <h4>Pharmaceuticals</h4>
                <ul class="industry-list">
                    <li>Drug manufacturing</li>
                    <li>Packaging lines</li>
                    <li>Quality control labs</li>
                </ul>
            </div>
            <div class="industry-card">
                <div class="industry-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <h4>Plastics & Polymers</h4>
                <ul class="industry-list">
                    <li>Injection molding</li>
                    <li>Blow molding</li>
                    <li>Extrusion operations</li>
                </ul>
            </div>
            <div class="industry-card">
                <div class="industry-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                    </svg>
                </div>
                <h4>Textiles & Garments</h4>
                <ul class="industry-list">
                    <li>Garment manufacturing</li>
                    <li>Fabric processing</li>
                    <li>Home textiles</li>
                </ul>
            </div>
            <div class="industry-card">
                <div class="industry-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <h4>Others</h4>
                <ul class="industry-list">
                    <li>Furniture manufacturing</li>
                    <li>Chemical processing</li>
                    <li>And many more...</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Sample Designs Section -->
<section class="portfolio-section">
    <div class="portfolio-container">
        <div class="section-header-portfolio">
            <h2>Sample Designs & Drawings</h2>
            <p>Examples of our architectural and layout work demonstrating the LFB approach</p>
        </div>
        
        <div class="designs-grid">
            <div class="design-card">
                <div class="design-preview">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                </div>
                <h3>Architectural Drawings</h3>
                <p>Complete sets of architectural plans, elevations, sections, and details for new factory buildings.</p>
                <div class="design-meta">Available formats: PDF, DWG</div>
            </div>
            <div class="design-card">
                <div class="design-preview">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                </div>
                <h3>Layout Drawings</h3>
                <p>2D CAD layouts showing optimized equipment placement, material flow, and area utilization.</p>
                <div class="design-meta">Before & After comparisons available</div>
            </div>
            <div class="design-card">
                <div class="design-preview">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                </div>
                <h3>3D Visualizations</h3>
                <p>Photorealistic renderings and virtual walkthroughs of proposed facilities.</p>
                <div class="design-meta">Helps stakeholders visualize the end result</div>
            </div>
        </div>
    </div>
</section>

<!-- Innovations Section -->
<section class="portfolio-section alt-bg">
    <div class="portfolio-container">
        <div class="section-header-portfolio">
            <h2>Ideas & Innovations</h2>
            <p>Custom solutions created within LFB projects to solve specific factory challenges</p>
        </div>
        
        <div class="innovations-grid">
            <div class="innovation-card">
                <div class="innovation-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M5 12h14"/>
                        <path d="M12 5l7 7-7 7"/>
                    </svg>
                </div>
                <div class="innovation-content">
                    <h3>Material Handling Innovations</h3>
                    <p>Custom-designed material handling solutions:</p>
                    <ul class="innovation-list">
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Gravity-fed presentation systems</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Custom assembly trolleys</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> FIFO flow racks</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Modular conveyor systems</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Auto door close</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Safe lift</li>
                    </ul>
                </div>
            </div>
            <div class="innovation-card">
                <div class="innovation-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                </div>
                <div class="innovation-content">
                    <h3>Workstation & Assembly Innovations</h3>
                    <p>Specialized workstation designs:</p>
                    <ul class="innovation-list">
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Height-adjustable stations</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Integrated tool presentation</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Work instruction displays</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Ergonomic solutions</li>
                    </ul>
                </div>
            </div>
            <div class="innovation-card">
                <div class="innovation-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                </div>
                <div class="innovation-content">
                    <h3>Storage & Inventory Solutions</h3>
                    <p>Innovative inventory management:</p>
                    <ul class="innovation-list">
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Visual count-free storage</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Color-coded locations</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Kanban card systems</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Supermarket layouts</li>
                    </ul>
                </div>
            </div>
            <div class="innovation-card">
                <div class="innovation-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div class="innovation-content">
                    <h3>Visual Management Tools</h3>
                    <p>Visual factory elements:</p>
                    <ul class="innovation-list">
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Production tracking boards</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Andon systems</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Floor marking systems</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Shadow boards</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories Section -->
<section class="portfolio-section">
    <div class="portfolio-container">
        <div class="section-header-portfolio">
            <h2>Client Success Stories</h2>
            <p>Anonymized examples illustrating typical results achieved with LFB-driven projects</p>
        </div>
        
        <div class="success-grid">
            <?php
            // Fetch success stories from database
            require_once 'database/db_config.php';
            
            $conn = getDBConnection();
            $result = $conn->query("
                SELECT id, title, project_type, industry, challenge, solution, results, sort_order
                FROM success_stories 
                WHERE is_active = 1 
                ORDER BY sort_order ASC, id ASC
            ");
            
            $stories = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $stories[] = $row;
                }
            }
            
            if (!empty($stories)):
                foreach ($stories as $story):
                    $results = json_decode($story['results'], true);
                    if (!is_array($results)) {
                        $results = [];
                    }
            ?>
            <div class="success-card">
                <div class="success-tags">
                    <?php if (!empty($story['project_type'])): ?>
                    <span class="tag tag-type"><?php echo htmlspecialchars($story['project_type']); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($story['industry'])): ?>
                    <span class="tag tag-industry"><?php echo htmlspecialchars($story['industry']); ?></span>
                    <?php endif; ?>
                </div>
                <h3><?php echo htmlspecialchars($story['title']); ?></h3>
                <div class="success-detail">
                    <strong>Challenge:</strong>
                    <p><?php echo htmlspecialchars($story['challenge']); ?></p>
                </div>
                <div class="success-detail">
                    <strong>Solution:</strong>
                    <p><?php echo htmlspecialchars($story['solution']); ?></p>
                </div>
                <div class="success-results">
                    <h4>Results Achieved:</h4>
                    <ul class="results-list">
                        <?php foreach ($results as $result): ?>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <?php echo htmlspecialchars($result); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php 
                endforeach;
                $conn->close();
            else:
                // Fallback if no stories in database
                echo '<p style="text-align: center; color: #64748b;">No success stories available at this time.</p>';
            endif;
            ?>
        </div>
    </div>
</section>

<!-- Before/After Section -->
<section class="portfolio-section alt-bg">
    <div class="portfolio-container">
        <div class="section-header-portfolio">
            <h2>Before vs. After Transformations</h2>
            <p>Typical patterns when LFB is applied to existing or new factories</p>
        </div>
        
        <div class="comparison-card">
            <div class="comparison-grid">
                <div class="comparison-side before-side">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M15 9l-6 6"/>
                            <path d="M9 9l6 6"/>
                        </svg>
                        Typical "Before" State
                    </h3>
                    <ul class="comparison-list">
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6"/><path d="M9 9l6 6"/></svg> Spaghetti flow with excessive material travel</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6"/><path d="M9 9l6 6"/></svg> Equipment placed without layout planning</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6"/><path d="M9 9l6 6"/></svg> Large WIP inventory between operations</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6"/><path d="M9 9l6 6"/></svg> Operators walking excessive distances</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6"/><path d="M9 9l6 6"/></svg> Congestion and bottlenecks</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6"/><path d="M9 9l6 6"/></svg> Difficult to manage and visualize</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6"/><path d="M9 9l6 6"/></svg> Poor space utilization</li>
                    </ul>
                </div>
                <div class="comparison-side after-side">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        After OptiSpace LFB
                    </h3>
                    <ul class="comparison-list">
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Streamlined flow with minimal travel</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Equipment positioned for optimal sequence</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Minimal WIP with balanced flow</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Operators work within compact cells</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Clear aisles and organized zones</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Visual management throughout</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Efficient space with room for growth</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="request-banner" style="margin-top: 3rem;">
            <h3>See Specific Examples</h3>
            <p>Before/after layouts and flows can be shared in a one-to-one discussion, subject to client confidentiality.</p>
            <a href="<?php echo url('contact.php'); ?>" class="btn-primary-portfolio">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                Request Industry Examples
            </a>
        </div>
    </div>
</section>

<?php $hideFooterCTA = true; ?>
<!-- CTA Section -->

<?php include 'includes/footer.php'; ?>
