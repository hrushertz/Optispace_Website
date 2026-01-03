<?php
$pageTitle = "Downloads | Solutions OptiSpace";
$pageDescription = "Download brochures, case studies, and resources from Solutions OptiSpace.";
$currentPage = "downloads";
include 'includes/header.php';
?>

<style>
/* ========================================
   DOWNLOADS PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --download-orange: #E99431;
    --download-orange-light: rgba(233, 148, 49, 0.08);
    --download-blue: #3B82F6;
    --download-blue-light: rgba(59, 130, 246, 0.08);
    --download-green: #10B981;
    --download-green-light: rgba(16, 185, 129, 0.08);
    --download-purple: #8B5CF6;
    --download-purple-light: rgba(139, 92, 246, 0.08);
    --download-gray: #64748B;
    --download-gray-light: rgba(100, 116, 139, 0.08);
    --download-dark: #1E293B;
    --download-text: #475569;
    --download-border: #E2E8F0;
}

/* Hero Section */
.downloads-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 6rem;
    position: relative;
    overflow: hidden;
}

.downloads-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
}

.downloads-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.downloads-hero-content {
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

.downloads-hero h1 {
    font-size: 3.25rem;
    font-weight: 700;
    color: white;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

.downloads-hero h1 span {
    color: #E99431;
}

.downloads-hero-text {
    font-size: 1.2rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.75);
    margin-bottom: 2rem;
    max-width: 500px;
}

.hero-stats {
    display: flex;
    gap: 3rem;
}

.hero-stat {
    text-align: left;
}

.hero-stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.hero-stat-label {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.6);
}

.hero-visual {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.hero-resource-preview {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    max-width: 400px;
}

.preview-resource {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.25rem;
    transition: all 0.3s ease;
}

.preview-resource:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(233, 148, 49, 0.3);
    transform: translateY(-2px);
}

.preview-resource-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #E99431 0%, #f5a854 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
}

.preview-resource-icon svg {
    width: 18px;
    height: 18px;
    color: white;
}

.preview-resource:nth-child(2) .preview-resource-icon {
    background: linear-gradient(135deg, #3B82F6 0%, #60a5fa 100%);
}

.preview-resource:nth-child(3) .preview-resource-icon {
    background: linear-gradient(135deg, #10B981 0%, #34d399 100%);
}

.preview-resource:nth-child(4) .preview-resource-icon {
    background: linear-gradient(135deg, #8B5CF6 0%, #a78bfa 100%);
}

.preview-resource-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
}

.preview-resource-count {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.5);
}

/* Breadcrumb */
.downloads-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--download-border);
}

.downloads-breadcrumb ul {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.downloads-breadcrumb a {
    color: var(--download-gray);
    text-decoration: none;
}

.downloads-breadcrumb a:hover {
    color: var(--download-orange);
}

.downloads-breadcrumb li:last-child {
    color: var(--download-dark);
    font-weight: 500;
}

.downloads-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--download-border);
}

/* Resource Categories Section */
.resources-section {
    padding: 6rem 0;
    background: white;
}

.resources-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.section-header {
    text-align: center;
    margin-bottom: 4rem;
}

.section-header h2 {
    font-size: 2.5rem;
    color: var(--download-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.section-header p {
    font-size: 1.15rem;
    color: var(--download-text);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.7;
}

/* Category Cards */
.category-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 4rem;
}

.category-card {
    background: white;
    border: 1px solid var(--download-border);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.category-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    border-color: transparent;
    transform: translateY(-4px);
}

.category-card.active {
    border-color: var(--download-orange);
    box-shadow: 0 8px 30px rgba(233, 148, 49, 0.15);
}

.category-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.category-card:nth-child(1) .category-icon {
    background: var(--download-orange-light);
}

.category-card:nth-child(2) .category-icon {
    background: var(--download-blue-light);
}

.category-card:nth-child(3) .category-icon {
    background: var(--download-green-light);
}

.category-card:nth-child(4) .category-icon {
    background: var(--download-purple-light);
}

.category-icon svg {
    width: 28px;
    height: 28px;
}

.category-card:nth-child(1) .category-icon svg { color: var(--download-orange); }
.category-card:nth-child(2) .category-icon svg { color: var(--download-blue); }
.category-card:nth-child(3) .category-icon svg { color: var(--download-green); }
.category-card:nth-child(4) .category-icon svg { color: var(--download-purple); }

.category-card h3 {
    font-size: 1.1rem;
    color: var(--download-dark);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.category-card p {
    font-size: 0.9rem;
    color: var(--download-text);
    margin-bottom: 1rem;
    line-height: 1.5;
}

.category-count {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #F8FAFC;
    padding: 0.35rem 0.75rem;
    border-radius: 100px;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--download-gray);
}

/* Downloads Grid */
.downloads-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.download-card {
    background: white;
    border: 1px solid var(--download-border);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    gap: 1.25rem;
    transition: all 0.3s ease;
}

.download-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    border-color: transparent;
}

.download-thumbnail {
    width: 80px;
    height: 100px;
    background: linear-gradient(135deg, #F8FAFC 0%, #E2E8F0 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}

.download-thumbnail svg {
    width: 32px;
    height: 32px;
    color: var(--download-gray);
}

.file-type-badge {
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--download-orange);
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
}

.download-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.download-category {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--download-orange);
    margin-bottom: 0.5rem;
}

.download-card:nth-child(even) .download-category {
    color: var(--download-blue);
}

.download-content h4 {
    font-size: 1.1rem;
    color: var(--download-dark);
    margin-bottom: 0.5rem;
    font-weight: 600;
    line-height: 1.4;
}

.download-content p {
    font-size: 0.9rem;
    color: var(--download-text);
    line-height: 1.5;
    margin-bottom: auto;
}

.download-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--download-border);
}

.download-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.85rem;
    color: var(--download-gray);
}

.download-info span {
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.download-info svg {
    width: 14px;
    height: 14px;
}

.btn-download {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1rem;
    background: var(--download-orange);
    color: white;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-download:hover {
    background: #d4851c;
    transform: translateY(-1px);
}

.btn-download svg {
    width: 16px;
    height: 16px;
}

/* CTA Section */
.download-cta-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
}

.download-cta-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
}

.download-cta-icon {
    width: 80px;
    height: 80px;
    background: var(--download-orange-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
}

.download-cta-icon svg {
    width: 36px;
    height: 36px;
    color: var(--download-orange);
}

.download-cta-container h2 {
    font-size: 2rem;
    color: var(--download-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.download-cta-container > p {
    font-size: 1.1rem;
    color: var(--download-text);
    margin-bottom: 2rem;
    line-height: 1.7;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.75rem;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-primary-modern {
    background: var(--download-orange);
    color: white;
}

.btn-primary-modern:hover {
    background: #d4851c;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(233, 148, 49, 0.3);
}

.btn-secondary-modern {
    background: white;
    color: var(--download-dark);
    border: 1px solid var(--download-border);
}

.btn-secondary-modern:hover {
    border-color: var(--download-orange);
    color: var(--download-orange);
}

.btn-modern svg {
    width: 18px;
    height: 18px;
}

/* Responsive */
@media (max-width: 1024px) {
    .downloads-hero-inner {
        grid-template-columns: 1fr;
        gap: 3rem;
        text-align: center;
    }
    
    .downloads-hero-text {
        margin: 0 auto 2rem;
    }
    
    .hero-stats {
        justify-content: center;
    }
    
    .category-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .downloads-hero {
        padding: 5rem 0 4rem;
    }
    
    .downloads-hero h1 {
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
    
    .hero-resource-preview {
        grid-template-columns: 1fr;
        max-width: 280px;
    }
    
    .category-grid {
        grid-template-columns: 1fr;
    }
    
    .downloads-grid {
        grid-template-columns: 1fr;
    }
    
    .download-card {
        flex-direction: column;
        text-align: center;
    }
    
    .download-thumbnail {
        margin: 0 auto;
    }
    
    .download-meta {
        flex-direction: column;
        gap: 1rem;
    }
    
    .cta-buttons {
        flex-direction: column;
    }
}
</style>

<!-- Hero Section -->
<section class="downloads-hero">
    <div class="downloads-hero-inner">
        <div class="downloads-hero-content">
            <div class="hero-eyebrow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Resource Library
            </div>
            <h1>Knowledge <span>Downloads</span></h1>
            <p class="downloads-hero-text">Access our comprehensive library of brochures, case studies, technical resources, and white papers to help you make informed decisions about your facility optimization.</p>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-value">15+</div>
                    <div class="hero-stat-label">Resources</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">4</div>
                    <div class="hero-stat-label">Categories</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">Free</div>
                    <div class="hero-stat-label">Access</div>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-resource-preview">
                <div class="preview-resource">
                    <div class="preview-resource-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                        </svg>
                    </div>
                    <div class="preview-resource-title">Brochures</div>
                    <div class="preview-resource-count">4 files</div>
                </div>
                <div class="preview-resource">
                    <div class="preview-resource-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                    </div>
                    <div class="preview-resource-title">Case Studies</div>
                    <div class="preview-resource-count">5 files</div>
                </div>
                <div class="preview-resource">
                    <div class="preview-resource-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20h9"/>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                        </svg>
                    </div>
                    <div class="preview-resource-title">Technical Docs</div>
                    <div class="preview-resource-count">4 files</div>
                </div>
                <div class="preview-resource">
                    <div class="preview-resource-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </div>
                    <div class="preview-resource-title">White Papers</div>
                    <div class="preview-resource-count">2 files</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="downloads-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li>Downloads</li>
    </ul>
</nav>

<!-- Resources Section -->
<section class="resources-section">
    <div class="resources-container">
        <div class="section-header">
            <h2>Explore Our Resources</h2>
            <p>Download free resources to learn more about lean factory building, space optimization, and our proven methodologies</p>
        </div>

        <!-- Category Filter Cards -->
        <div class="category-grid">
            <div class="category-card active">
                <div class="category-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                </div>
                <h3>Brochures</h3>
                <p>Company and service overviews</p>
                <span class="category-count">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    </svg>
                    4 files
                </span>
            </div>

            <div class="category-card">
                <div class="category-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </div>
                <h3>Case Studies</h3>
                <p>Real project success stories</p>
                <span class="category-count">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    </svg>
                    5 files
                </span>
            </div>

            <div class="category-card">
                <div class="category-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                    </svg>
                </div>
                <h3>Technical Resources</h3>
                <p>Guidelines and specifications</p>
                <span class="category-count">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    </svg>
                    4 files
                </span>
            </div>

            <div class="category-card">
                <div class="category-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                </div>
                <h3>White Papers</h3>
                <p>In-depth research and insights</p>
                <span class="category-count">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    </svg>
                    2 files
                </span>
            </div>
        </div>

        <!-- Downloads Grid -->
        <div class="downloads-grid">
            <!-- Brochure 1 -->
            <div class="download-card">
                <div class="download-thumbnail">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span class="file-type-badge">PDF</span>
                </div>
                <div class="download-content">
                    <span class="download-category">Brochure</span>
                    <h4>Solutions OptiSpace Company Overview</h4>
                    <p>Learn about our mission, expertise, and comprehensive approach to lean factory building and space optimization.</p>
                    <div class="download-meta">
                        <div class="download-info">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                2.4 MB
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                Dec 2025
                            </span>
                        </div>
                        <a href="#" class="btn-download">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>

            <!-- Brochure 2 -->
            <div class="download-card">
                <div class="download-thumbnail">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span class="file-type-badge">PDF</span>
                </div>
                <div class="download-content">
                    <span class="download-category">Brochure</span>
                    <h4>Lean Factory Building (LFB) Services</h4>
                    <p>Detailed overview of our LFB methodology and how it transforms manufacturing facilities.</p>
                    <div class="download-meta">
                        <div class="download-info">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                3.1 MB
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                Nov 2025
                            </span>
                        </div>
                        <a href="#" class="btn-download">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>

            <!-- Case Study 1 -->
            <div class="download-card">
                <div class="download-thumbnail">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span class="file-type-badge">PDF</span>
                </div>
                <div class="download-content">
                    <span class="download-category">Case Study</span>
                    <h4>Automotive Manufacturing: 40% Space Optimization</h4>
                    <p>How we helped a leading automotive supplier achieve 40% space reduction while increasing throughput by 25%.</p>
                    <div class="download-meta">
                        <div class="download-info">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                1.8 MB
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                Oct 2025
                            </span>
                        </div>
                        <a href="#" class="btn-download">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>

            <!-- Case Study 2 -->
            <div class="download-card">
                <div class="download-thumbnail">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span class="file-type-badge">PDF</span>
                </div>
                <div class="download-content">
                    <span class="download-category">Case Study</span>
                    <h4>Electronics Greenfield: Zero to Production in 6 Months</h4>
                    <p>Complete greenfield project from site selection to production launch for an electronics manufacturer.</p>
                    <div class="download-meta">
                        <div class="download-info">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                2.2 MB
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                Sep 2025
                            </span>
                        </div>
                        <a href="#" class="btn-download">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>

            <!-- Technical Resource 1 -->
            <div class="download-card">
                <div class="download-thumbnail">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span class="file-type-badge">PDF</span>
                </div>
                <div class="download-content">
                    <span class="download-category">Technical Resource</span>
                    <h4>Layout Design Principles & Guidelines</h4>
                    <p>Essential principles for designing efficient manufacturing layouts with lean methodology integration.</p>
                    <div class="download-meta">
                        <div class="download-info">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                4.5 MB
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                Nov 2025
                            </span>
                        </div>
                        <a href="#" class="btn-download">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>

            <!-- White Paper 1 -->
            <div class="download-card">
                <div class="download-thumbnail">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span class="file-type-badge">PDF</span>
                </div>
                <div class="download-content">
                    <span class="download-category">White Paper</span>
                    <h4>The Future of Lean Manufacturing in Industry 4.0</h4>
                    <p>Research insights on integrating lean principles with digital transformation and smart factory concepts.</p>
                    <div class="download-meta">
                        <div class="download-info">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                3.8 MB
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                Oct 2025
                            </span>
                        </div>
                        <a href="#" class="btn-download">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>

            <!-- Brochure 3 -->
            <div class="download-card">
                <div class="download-thumbnail">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span class="file-type-badge">PDF</span>
                </div>
                <div class="download-content">
                    <span class="download-category">Brochure</span>
                    <h4>Brownfield Optimization Services</h4>
                    <p>Transform your existing facility with our brownfield optimization expertise and proven methodology.</p>
                    <div class="download-meta">
                        <div class="download-info">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                2.8 MB
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                Nov 2025
                            </span>
                        </div>
                        <a href="#" class="btn-download">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>

            <!-- Case Study 3 -->
            <div class="download-card">
                <div class="download-thumbnail">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span class="file-type-badge">PDF</span>
                </div>
                <div class="download-content">
                    <span class="download-category">Case Study</span>
                    <h4>Pharmaceutical Facility: Compliance & Efficiency</h4>
                    <p>Balancing regulatory compliance with lean principles in a pharmaceutical manufacturing environment.</p>
                    <div class="download-meta">
                        <div class="download-info">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                2.0 MB
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                Aug 2025
                            </span>
                        </div>
                        <a href="#" class="btn-download">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="download-cta-section">
    <div class="download-cta-container">
        <div class="download-cta-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <h2>Need Custom Resources?</h2>
        <p>Can't find what you're looking for? Our team can prepare customized materials tailored to your specific industry or project requirements.</p>
        <div class="cta-buttons">
            <a href="<?php echo url('contact.php'); ?>" class="btn-modern btn-primary-modern">
                Contact Our Team
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
            <a href="<?php echo url('pulse-check.php'); ?>" class="btn-modern btn-secondary-modern">
                Request Pulse Check
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
