<?php
$currentPage = 'brownfield';
$pageTitle = 'Existing Factory Optimization (Brownfield) | Solutions OptiSpace';
$pageDescription = 'Transform your existing factory with lean diagnosis, layout optimization, and material handling improvements.';
include '../includes/header.php';
?>

<style>
/* ========================================
   BROWNFIELD PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --brownfield-orange: #E99431;
    --brownfield-orange-light: rgba(233, 148, 49, 0.08);
    --brownfield-blue: #3B82F6;
    --brownfield-blue-light: rgba(59, 130, 246, 0.08);
    --brownfield-green: #10B981;
    --brownfield-gray: #64748B;
    --brownfield-dark: #1E293B;
    --brownfield-text: #475569;
    --brownfield-border: #E2E8F0;
}

/* Hero Section */
.brownfield-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 6rem;
    position: relative;
    overflow: hidden;
}

.brownfield-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(59, 130, 246, 0.12) 0%, transparent 60%);
}

.brownfield-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.brownfield-hero-content {
    position: relative;
    z-index: 2;
}

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(59, 130, 246, 0.15);
    color: #3B82F6;
    padding: 0.5rem 1rem;
    border-radius: 100px;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 1.5rem;
}

.brownfield-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    color: white;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

.brownfield-hero h1 span {
    color: #3B82F6;
}

.brownfield-hero-text {
    font-size: 1.2rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.75);
    margin-bottom: 2rem;
    max-width: 500px;
}

.hero-stats {
    display: flex;
    gap: 2.5rem;
}

.hero-stat {
    text-align: left;
}

.hero-stat-value {
    font-size: 2.25rem;
    font-weight: 700;
    color: white;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.hero-stat-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
}

.hero-visual {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.hero-benefits-preview {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    max-width: 400px;
}

.preview-benefit {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.25rem;
    transition: all 0.3s ease;
}

.preview-benefit:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(59, 130, 246, 0.3);
    transform: translateY(-2px);
}

.preview-benefit-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #3B82F6 0%, #60a5fa 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
}

.preview-benefit:nth-child(2) .preview-benefit-icon {
    background: linear-gradient(135deg, #E99431 0%, #f5a854 100%);
}

.preview-benefit:nth-child(3) .preview-benefit-icon {
    background: linear-gradient(135deg, #10B981 0%, #34d399 100%);
}

.preview-benefit:nth-child(4) .preview-benefit-icon {
    background: linear-gradient(135deg, #64748B 0%, #94a3b8 100%);
}

.preview-benefit-icon svg {
    width: 18px;
    height: 18px;
    color: white;
}

.preview-benefit-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
}

.preview-benefit-value {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 0.25rem;
}

/* Breadcrumb */
.brownfield-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--brownfield-border);
}

.brownfield-breadcrumb ul {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.brownfield-breadcrumb a {
    color: var(--brownfield-gray);
    text-decoration: none;
}

.brownfield-breadcrumb a:hover {
    color: var(--brownfield-blue);
}

.brownfield-breadcrumb li:last-child {
    color: var(--brownfield-dark);
    font-weight: 500;
}

.brownfield-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--brownfield-border);
}

/* Section Styling */
.brownfield-section {
    padding: 6rem 0;
    background: white;
}

.brownfield-section.section-light {
    background: #FAFBFC;
}

.brownfield-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.brownfield-section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.brownfield-section-header h2 {
    font-size: 2.5rem;
    color: var(--brownfield-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.brownfield-section-header p {
    font-size: 1.15rem;
    color: var(--brownfield-text);
    max-width: 800px;
    margin: 0 auto;
    line-height: 1.7;
}

/* Cards */
.content-card {
    background: white;
    border: 1px solid var(--brownfield-border);
    border-radius: 12px;
    padding: 2rem;
    height: 100%;
    transition: all 0.3s ease;
}

.content-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    border-color: var(--brownfield-blue);
}

.content-card h3 {
    font-size: 1.35rem;
    color: var(--brownfield-dark);
    margin: 0 0 1.25rem 0;
    font-weight: 600;
}

.content-card p {
    font-size: 1rem;
    line-height: 1.7;
    color: var(--brownfield-text);
    margin-bottom: 1rem;
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
    color: var(--brownfield-text);
    font-size: 0.95rem;
    line-height: 1.6;
}

.content-card ul li::before {
    content: '✓';
    color: var(--brownfield-blue);
    font-weight: 700;
    flex-shrink: 0;
}

.content-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

/* Process Steps */
.process-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.process-step-card {
    background: white;
    border: 1px solid var(--brownfield-border);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
}

.process-step-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    transform: translateY(-4px);
    border-color: var(--brownfield-blue);
}

.step-number {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, var(--brownfield-blue) 0%, #60a5fa 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0 auto 1.5rem;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.process-step-card h3 {
    font-size: 1.15rem;
    color: var(--brownfield-dark);
    margin: 0 0 1rem 0;
    font-weight: 600;
}

.process-step-card p {
    margin: 0;
    line-height: 1.7;
    color: var(--brownfield-text);
    font-size: 0.95rem;
}

/* Services Grid */
.services-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.service-card {
    background: white;
    border: 1px solid var(--brownfield-border);
    border-radius: 12px;
    padding: 2rem;
    height: 100%;
    transition: all 0.3s ease;
}

.service-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    border-color: var(--brownfield-blue);
}

.service-card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.service-icon {
    width: 48px;
    height: 48px;
    background: var(--brownfield-blue-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.service-icon svg {
    width: 24px;
    height: 24px;
    color: var(--brownfield-blue);
}

.service-card h3 {
    font-size: 1.15rem;
    color: var(--brownfield-dark);
    margin: 0;
    font-weight: 600;
}

.service-card > p {
    font-size: 0.95rem;
    color: var(--brownfield-text);
    margin-bottom: 1rem;
    font-weight: 600;
}

.service-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.service-card ul li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.4rem 0;
    color: var(--brownfield-text);
    font-size: 0.9rem;
    line-height: 1.6;
}

.service-card ul li::before {
    content: '→';
    color: var(--brownfield-blue);
    font-weight: 700;
    flex-shrink: 0;
}

/* Highlight Card */
.highlight-card {
    background: var(--brownfield-blue-light);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 12px;
    padding: 2.5rem;
    display: flex;
    gap: 2rem;
    margin-top: 2rem;
}

.highlight-icon {
    width: 56px;
    height: 56px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.highlight-icon svg {
    width: 28px;
    height: 28px;
    color: var(--brownfield-blue);
}

.highlight-content h3 {
    font-size: 1.25rem;
    color: var(--brownfield-dark);
    margin-bottom: 0.5rem;
}

.highlight-content p {
    color: var(--brownfield-text);
    margin: 0;
    line-height: 1.6;
}

/* Spaghetti Section */
.spaghetti-card {
    background: white;
    border: 1px solid var(--brownfield-border);
    border-radius: 16px;
    overflow: hidden;
}

.spaghetti-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
}

.spaghetti-content {
    padding: 2.5rem;
}

.spaghetti-content h3 {
    font-size: 1.35rem;
    color: var(--brownfield-dark);
    margin: 0 0 1rem 0;
    font-weight: 600;
}

.spaghetti-content p {
    font-size: 1rem;
    line-height: 1.7;
    color: var(--brownfield-text);
    margin-bottom: 1rem;
}

.spaghetti-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.spaghetti-content ul li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.4rem 0;
    color: var(--brownfield-text);
    font-size: 0.95rem;
}

.spaghetti-content ul li::before {
    content: '✓';
    color: var(--brownfield-blue);
    font-weight: 700;
}

.spaghetti-stats {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    padding: 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.stat-highlight {
    text-align: center;
    padding: 1.5rem;
    background: white;
    border-radius: 12px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.stat-value {
    font-size: 3rem;
    font-weight: 700;
    color: var(--brownfield-blue);
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.95rem;
    color: var(--brownfield-text);
}

.stat-detail {
    font-size: 0.9rem;
    color: var(--brownfield-text);
    text-align: center;
    line-height: 1.6;
}

/* Implementation Card */
.implementation-card {
    background: white;
    border: 1px solid var(--brownfield-border);
    border-radius: 16px;
    padding: 2.5rem;
}

.implementation-card h3 {
    font-size: 1.35rem;
    color: var(--brownfield-dark);
    margin: 0 0 0.75rem 0;
    font-weight: 600;
}

.implementation-card > p {
    font-size: 1rem;
    color: var(--brownfield-text);
    margin-bottom: 1.5rem;
}

.implementation-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.implementation-item h4 {
    font-size: 1rem;
    color: var(--brownfield-dark);
    margin: 0 0 0.75rem 0;
    font-weight: 600;
}

.implementation-item ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.implementation-item ul li {
    padding: 0.3rem 0;
    color: var(--brownfield-text);
    font-size: 0.9rem;
}

.implementation-item ul li::before {
    content: '•';
    color: var(--brownfield-blue);
    font-weight: 700;
    margin-right: 0.5rem;
}

/* CTA Section */
.brownfield-cta {
    padding: 6rem 0;
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
    position: relative;
    overflow: hidden;
}

.brownfield-cta::before {
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
    background: var(--brownfield-orange);
    color: white;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 2px solid var(--brownfield-orange);
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
@media (max-width: 1024px) {
    .brownfield-hero-inner {
        grid-template-columns: 1fr;
        gap: 3rem;
        text-align: center;
    }
    
    .brownfield-hero-text {
        margin: 0 auto 2rem;
        max-width: 700px;
    }
    
    .hero-stats {
        justify-content: center;
    }
    
    .process-grid, .services-grid, .implementation-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .spaghetti-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .brownfield-hero {
        padding: 5rem 0 4rem;
    }
    
    .brownfield-hero h1 {
        font-size: 2.25rem;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: 1.5rem;
        align-items: center;
    }
    
    .hero-stat {
        text-align: center;
    }
    
    .hero-benefits-preview {
        grid-template-columns: 1fr;
        max-width: 280px;
    }
    
    .brownfield-section {
        padding: 4rem 0;
    }
    
    .brownfield-section-header h2 {
        font-size: 2rem;
    }
    
    .content-grid, .process-grid, .services-grid, .implementation-grid {
        grid-template-columns: 1fr;
    }
    
    .highlight-card {
        flex-direction: column;
        text-align: center;
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
<section class="brownfield-hero">
    <div class="brownfield-hero-inner">
        <div class="brownfield-hero-content">
            <div class="hero-eyebrow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                Brownfield Optimization
            </div>
            <h1>Transform Your <span>Existing Factory</span></h1>
            <p class="brownfield-hero-text">Redesign layouts, flows, and work areas inside your current walls to unlock capacity, reduce movement, and improve safety.</p>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-value">30-50%</div>
                    <div class="hero-stat-label">Less Movement</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">20-40%</div>
                    <div class="hero-stat-label">Less WIP</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">6</div>
                    <div class="hero-stat-label">Step Process</div>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-benefits-preview">
                <div class="preview-benefit">
                    <div class="preview-benefit-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <div class="preview-benefit-title">Flow-First Design</div>
                    <div class="preview-benefit-value">Inside-Out</div>
                </div>
                <div class="preview-benefit">
                    <div class="preview-benefit-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                    </div>
                    <div class="preview-benefit-title">Reduced Motion</div>
                    <div class="preview-benefit-value">Less Waste</div>
                </div>
                <div class="preview-benefit">
                    <div class="preview-benefit-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                        </svg>
                    </div>
                    <div class="preview-benefit-title">Phased Approach</div>
                    <div class="preview-benefit-value">Low Disruption</div>
                </div>
                <div class="preview-benefit">
                    <div class="preview-benefit-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="preview-benefit-title">On-Floor Support</div>
                    <div class="preview-benefit-value">Real Results</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="brownfield-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li><a href="#">Services</a></li>
        <li>Brownfield Projects</li>
    </ul>
</nav>

<!-- Challenge Section -->
<section class="brownfield-section">
    <div class="brownfield-container">
        <div class="brownfield-section-header">
            <h2>The Brownfield Challenge</h2>
            <p>Most manufacturing facilities weren't designed with lean principles. Over time, they accumulate inefficiencies that drain productivity every day.</p>
        </div>

        <div class="content-grid">
            <div class="content-card">
                <h3>Common Issues We Solve</h3>
                <p>Accumulated inefficiencies that constrain your operations:</p>
                <ul>
                    <li><strong>Unplanned additions:</strong> Equipment added without layout planning, creating bottlenecks</li>
                    <li><strong>Excessive travel:</strong> Materials and operators traveling far due to poor adjacency</li>
                    <li><strong>Space constraints:</strong> Storage and WIP growing uncontrolled, limiting capacity</li>
                    <li><strong>Safety concerns:</strong> Congested aisles and unclear traffic patterns</li>
                </ul>
            </div>
            <div class="content-card">
                <h3>What You Can Achieve</h3>
                <p><strong>Typical impact ranges from brownfield projects:</strong></p>
                <ul>
                    <li><strong>30-50% reduction</strong> in material movement distance</li>
                    <li><strong>20-40% reduction</strong> in work-in-process inventory</li>
                    <li><strong>15-30% improvement</strong> in floor space utilization</li>
                    <li><strong>20-35% reduction</strong> in operator walking</li>
                    <li><strong>Improved quality</strong> through better visibility and flow</li>
                    <li><strong>Enhanced safety</strong> with organized workspaces</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="brownfield-section section-light" id="process">
    <div class="brownfield-container">
        <div class="brownfield-section-header">
            <h2>Our Brownfield Optimization Process</h2>
            <p>Brownfield projects require surgical precision. We work within your existing constraints to find new efficiencies, often revealing capacity you didn't know you had.</p>
        </div>

        <div class="process-grid">
            <div class="process-step-card">
                <div class="step-number">1</div>
                <h3>Current State Assessment</h3>
                <p>Capture real flows and physical constraints to feed LFB‑based layout options.</p>
            </div>
            <div class="process-step-card">
                <div class="step-number">2</div>
                <h3>Lean Diagnosis</h3>
                <p>Identify waste types, quantify movement distances, and analyze flow inefficiencies.</p>
            </div>
            <div class="process-step-card">
                <div class="step-number">3</div>
                <h3>Spaghetti Diagram Analysis</h3>
                <p>Visualize material and operator paths to expose hidden transportation waste.</p>
            </div>
            <div class="process-step-card">
                <div class="step-number">4</div>
                <h3>Future State Layout</h3>
                <p>Create an inside‑out layout where flow leads and structure follows.</p>
            </div>
            <div class="process-step-card">
                <div class="step-number">5</div>
                <h3>Phased Implementation</h3>
                <p>Develop a step-by-step roadmap to minimize production disruption.</p>
            </div>
            <div class="process-step-card">
                <div class="step-number">6</div>
                <h3>Implementation Support</h3>
                <p>Ensure the new layout is realized physically on the floor, not just on CAD.</p>
            </div>
        </div>
    </div>
</section>

<!-- Shop-Floor Optimization Services -->
<section class="brownfield-section">
    <div class="brownfield-container">
        <div class="brownfield-section-header">
            <h2>Shop-Floor Optimization Services</h2>
            <p>LFB drives not just plant‑level layout, but also detailed workstation, material handling, and storage design</p>
        </div>

        <div class="services-grid">
            <div class="service-card">
                <div class="service-card-header">
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/>
                        </svg>
                    </div>
                    <h3>Workstation Design</h3>
                </div>
                <p><strong>Reduced fatigue and motion per cycle.</strong></p>
                <ul>
                    <li>Ergonomic worktable design</li>
                    <li>Assembly station layouts</li>
                    <li>Tool and material positioning</li>
                    <li>Lighting and utility access</li>
                    <li>Standard work documentation</li>
                </ul>
            </div>
            <div class="service-card">
                <div class="service-card-header">
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <h3>Material Handling</h3>
                </div>
                <p><strong>Right‑sized systems for takt time.</strong></p>
                <ul>
                    <li>Custom trolley and cart design</li>
                    <li>Conveyor system specification</li>
                    <li>Crane and hoist requirements</li>
                    <li>Gravity-feed solutions</li>
                    <li>Presentation and replenishment</li>
                </ul>
            </div>
            <div class="service-card">
                <div class="service-card-header">
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
                        </svg>
                    </div>
                    <h3>Storage Design</h3>
                </div>
                <p><strong>Controlled inventory with visual limits.</strong></p>
                <ul>
                    <li>Raw material storage calculations</li>
                    <li>Work-in-process (WIP) sizing</li>
                    <li>Finished goods warehousing</li>
                    <li>Visual count-free stores</li>
                    <li>FIFO flow racks</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Spaghetti Diagram Section -->
<section class="brownfield-section section-light">
    <div class="brownfield-container">
        <div class="brownfield-section-header">
            <h2>Spaghetti Diagram Analysis</h2>
            <p>Spaghetti diagrams bring the LFB philosophy to life by exposing the hidden, tangled paths inside your building</p>
        </div>

        <div class="spaghetti-card">
            <div class="spaghetti-grid">
                <div class="spaghetti-content">
                    <h3>What is a Spaghetti Diagram?</h3>
                    <p>A visual tool that traces the actual path materials and operators take through your facility. When multiple product paths are overlaid, the result looks like tangled spaghetti, revealing:</p>
                    <ul>
                        <li>Excessive travel distances</li>
                        <li>Backtracking and crisscrossing</li>
                        <li>Congestion points</li>
                        <li>Opportunities for adjacency</li>
                    </ul>
                    <h3 style="margin-top: 1.5rem;">The Impact</h3>
                    <p>One client reduced their main product's travel distance from <strong>847 meters to 94 meters</strong> through layout optimization guided by spaghetti diagram analysis.</p>
                </div>
                <div class="spaghetti-stats">
                    <div class="stat-highlight">
                        <div class="stat-value">89%</div>
                        <div class="stat-label">Reduction in Transportation</div>
                    </div>
                    <p class="stat-detail">Faster throughput with same resources. Lower handling costs and damage. Better visibility and control.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Equipment & Materials Section -->
<section class="brownfield-section">
    <div class="brownfield-container">
        <div class="brownfield-section-header">
            <h2>Equipment & Material Selection Support</h2>
            <p>When layout changes demand new equipment or handling systems, OptiSpace supports selection aligned with the new LFB‑based design</p>
        </div>

        <div class="content-grid">
            <div class="content-card">
                <h3>Equipment Procurement Support</h3>
                <p>Expert guidance during equipment selection:</p>
                <ul>
                    <li>Specification development</li>
                    <li>Vendor identification and evaluation</li>
                    <li>Technical requirement documentation</li>
                    <li>Quotation comparison support</li>
                    <li>Trial and acceptance testing</li>
                </ul>
            </div>
            <div class="content-card">
                <h3>Materials Guidance</h3>
                <p>Selection of appropriate materials and components:</p>
                <ul>
                    <li>Fabrication material recommendations</li>
                    <li>Finish and coating selections</li>
                    <li>Supplier network access</li>
                    <li>Cost-effective alternatives</li>
                    <li>Quality and durability considerations</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- On-Floor Implementation -->
<section class="brownfield-section section-light">
    <div class="brownfield-container">
        <div class="brownfield-section-header">
            <h2>On-Floor Implementation Support</h2>
            <p>This ensures your team adopts the new ways of working and the gains are sustained, not lost after the project ends</p>
        </div>

        <div class="implementation-card">
            <h3>Hands-On Guidance</h3>
            <p>Our consultants work alongside your team during implementation:</p>
            <div class="implementation-grid">
                <div class="implementation-item">
                    <h4>Training</h4>
                    <ul>
                        <li>Demonstration of improved methods</li>
                        <li>Procedure development</li>
                        <li>Operator training</li>
                    </ul>
                </div>
                <div class="implementation-item">
                    <h4>Support</h4>
                    <ul>
                        <li>Trial runs and adjustments</li>
                        <li>Problem-solving support</li>
                        <li>Best practice transfer</li>
                    </ul>
                </div>
                <div class="implementation-item">
                    <h4>Sustainability</h4>
                    <ul>
                        <li>Visual management setup</li>
                        <li>Standard work creation</li>
                        <li>Sustainability planning</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="brownfield-cta">
    <div class="brownfield-container">
        <div class="cta-inner">
            <h2>Transform Your Existing Factory</h2>
            <p>Unlock hidden improvement potential in your current facility. Get a structured LFB Pulse Check to identify layout optimization and flow improvement opportunities.</p>
            <div class="cta-buttons">
                <a href="<?php echo url('pulse-check.php'); ?>" class="btn-cta-primary">
                    Request Brownfield Assessment
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="<?php echo url('portfolio.php'); ?>" class="btn-cta-secondary">
                    View Success Stories
                </a>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
