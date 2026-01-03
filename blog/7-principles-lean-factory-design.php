<?php
$pageTitle = "7 Principles of Lean Factory Design That Transform Operations | Solutions OptiSpace";
$pageDescription = "Discover the fundamental principles that guide successful lean factory implementations. From value stream mapping to continuous flow, learn how these concepts translate into tangible efficiency gains.";
$currentPage = "blogs";
include '../includes/header.php';
?>

<style>
/* ========================================
   BLOG ARTICLE PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --article-orange: #E99431;
    --article-orange-light: rgba(233, 148, 49, 0.08);
    --article-blue: #3B82F6;
    --article-blue-light: rgba(59, 130, 246, 0.08);
    --article-green: #10B981;
    --article-green-light: rgba(16, 185, 129, 0.08);
    --article-dark: #1E293B;
    --article-text: #475569;
    --article-text-light: #64748B;
    --article-border: #E2E8F0;
}

/* Article Hero */
.article-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 4rem;
    position: relative;
    overflow: hidden;
}

.article-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
}

.article-hero-inner {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem;
    position: relative;
    z-index: 2;
}

.article-meta-top {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.article-category-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(233, 148, 49, 0.15);
    color: var(--article-orange);
    padding: 0.5rem 1rem;
    border-radius: 100px;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.article-category-badge:hover {
    background: rgba(233, 148, 49, 0.25);
}

.article-category-badge svg {
    width: 16px;
    height: 16px;
}

.article-date-badge {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.9rem;
}

.article-hero h1 {
    font-size: 2.75rem;
    font-weight: 700;
    color: white;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    max-width: 800px;
}

.article-excerpt {
    font-size: 1.2rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2rem;
    max-width: 700px;
}

.article-author-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    max-width: fit-content;
}

.author-avatar-large {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--article-orange) 0%, #f5a854 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
}

.author-info-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.author-info-name {
    font-size: 1rem;
    font-weight: 600;
    color: white;
}

.author-info-role {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
}

.author-info-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.5);
}

.author-info-meta span {
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.author-info-meta svg {
    width: 14px;
    height: 14px;
}

/* Breadcrumb */
.article-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--article-border);
}

.article-breadcrumb ul {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
    flex-wrap: wrap;
}

.article-breadcrumb a {
    color: var(--article-text-light);
    text-decoration: none;
}

.article-breadcrumb a:hover {
    color: var(--article-orange);
}

.article-breadcrumb li:last-child {
    color: var(--article-dark);
    font-weight: 500;
}

.article-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--article-border);
}

/* Article Content Layout */
.article-layout {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 4rem;
    align-items: start;
}

.article-main {
    padding: 3rem 0;
}

/* Article Content Styles */
.article-content {
    font-size: 1.1rem;
    line-height: 1.85;
    color: var(--article-text);
}

.article-content h2 {
    font-size: 1.75rem;
    color: var(--article-dark);
    margin: 2.5rem 0 1rem;
    font-weight: 700;
    line-height: 1.3;
}

.article-content h3 {
    font-size: 1.35rem;
    color: var(--article-dark);
    margin: 2rem 0 0.75rem;
    font-weight: 600;
}

.article-content p {
    margin-bottom: 1.5rem;
}

.article-content ul,
.article-content ol {
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
}

.article-content li {
    margin-bottom: 0.75rem;
}

.article-content strong {
    color: var(--article-dark);
    font-weight: 600;
}

.article-content a {
    color: var(--article-orange);
    text-decoration: underline;
    text-underline-offset: 2px;
}

.article-content a:hover {
    text-decoration: none;
}

/* Principle Cards */
.principle-card {
    background: #F8FAFC;
    border: 1px solid var(--article-border);
    border-left: 4px solid var(--article-orange);
    border-radius: 0 12px 12px 0;
    padding: 1.75rem;
    margin: 2rem 0;
}

.principle-card.blue {
    border-left-color: var(--article-blue);
}

.principle-card.green {
    border-left-color: var(--article-green);
}

.principle-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.principle-number {
    width: 40px;
    height: 40px;
    background: var(--article-orange);
    color: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
}

.principle-card.blue .principle-number {
    background: var(--article-blue);
}

.principle-card.green .principle-number {
    background: var(--article-green);
}

.principle-title {
    font-size: 1.25rem;
    color: var(--article-dark);
    font-weight: 600;
    margin: 0;
}

.principle-content {
    font-size: 1rem;
    color: var(--article-text);
    line-height: 1.7;
}

.principle-content p:last-child {
    margin-bottom: 0;
}

/* Blockquote */
.article-content blockquote {
    background: var(--article-orange-light);
    border-left: 4px solid var(--article-orange);
    border-radius: 0 12px 12px 0;
    padding: 1.5rem 2rem;
    margin: 2rem 0;
    font-style: italic;
    font-size: 1.15rem;
    color: var(--article-dark);
}

.article-content blockquote p:last-child {
    margin-bottom: 0;
}

/* Key Takeaways Box */
.takeaways-box {
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
    border-radius: 16px;
    padding: 2rem;
    margin: 2.5rem 0;
}

.takeaways-box h3 {
    color: white !important;
    margin-top: 0 !important;
    margin-bottom: 1.25rem !important;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.takeaways-box h3 svg {
    width: 24px;
    height: 24px;
    color: var(--article-orange);
}

.takeaways-list {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

.takeaways-list li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.9);
    font-size: 1rem;
}

.takeaways-list li:last-child {
    border-bottom: none;
}

.takeaways-list li svg {
    width: 20px;
    height: 20px;
    color: var(--article-orange);
    flex-shrink: 0;
    margin-top: 2px;
}

/* Article Image */
.article-image {
    margin: 2.5rem 0;
    border-radius: 12px;
    overflow: hidden;
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.article-image svg {
    width: 80px;
    height: 80px;
    color: rgba(255, 255, 255, 0.2);
}

.article-image-caption {
    text-align: center;
    font-size: 0.9rem;
    color: var(--article-text-light);
    margin-top: 0.75rem;
    font-style: italic;
}

/* Article Tags */
.article-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid var(--article-border);
}

.article-tag {
    padding: 0.4rem 0.9rem;
    background: #F8FAFC;
    border: 1px solid var(--article-border);
    border-radius: 100px;
    font-size: 0.85rem;
    color: var(--article-text);
    text-decoration: none;
    transition: all 0.2s ease;
}

.article-tag:hover {
    background: var(--article-orange-light);
    border-color: var(--article-orange);
    color: var(--article-orange);
}

/* Share Section */
.share-section {
    margin-top: 2rem;
    padding: 1.5rem;
    background: #F8FAFC;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.share-label {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--article-dark);
}

.share-buttons {
    display: flex;
    gap: 0.5rem;
}

.share-btn {
    width: 40px;
    height: 40px;
    border: 1px solid var(--article-border);
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--article-text);
    text-decoration: none;
    transition: all 0.2s ease;
}

.share-btn:hover {
    border-color: var(--article-orange);
    color: var(--article-orange);
}

.share-btn svg {
    width: 18px;
    height: 18px;
}

/* Sidebar */
.article-sidebar {
    padding: 3rem 0;
    position: sticky;
    top: 2rem;
}

.sidebar-widget {
    background: #F8FAFC;
    border: 1px solid var(--article-border);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.sidebar-widget h4 {
    font-size: 1rem;
    color: var(--article-dark);
    margin-bottom: 1rem;
    font-weight: 600;
}

/* Table of Contents */
.toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.toc-list li {
    margin-bottom: 0.5rem;
}

.toc-list a {
    font-size: 0.9rem;
    color: var(--article-text);
    text-decoration: none;
    display: block;
    padding: 0.4rem 0;
    padding-left: 1rem;
    border-left: 2px solid transparent;
    transition: all 0.2s ease;
}

.toc-list a:hover,
.toc-list a.active {
    color: var(--article-orange);
    border-left-color: var(--article-orange);
}

/* Related Articles */
.related-article {
    display: flex;
    gap: 1rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--article-border);
    text-decoration: none;
    transition: all 0.2s ease;
}

.related-article:last-child {
    border-bottom: none;
}

.related-article:hover .related-title {
    color: var(--article-orange);
}

.related-thumb {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
    border-radius: 8px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.related-thumb svg {
    width: 24px;
    height: 24px;
    color: rgba(255, 255, 255, 0.3);
}

.related-content {
    flex: 1;
}

.related-title {
    font-size: 0.9rem;
    color: var(--article-dark);
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 0.25rem;
    transition: color 0.2s ease;
}

.related-meta {
    font-size: 0.8rem;
    color: var(--article-text-light);
}

/* CTA Widget */
.cta-widget {
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%) !important;
    border: none !important;
}

.cta-widget h4 {
    color: white !important;
}

.cta-widget p {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.75);
    margin-bottom: 1rem;
    line-height: 1.6;
}

.cta-widget .btn-cta {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    width: 100%;
    justify-content: center;
    padding: 0.75rem 1.25rem;
    background: var(--article-orange);
    color: white;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.cta-widget .btn-cta:hover {
    background: #d4851c;
    transform: translateY(-1px);
}

.cta-widget .btn-cta svg {
    width: 16px;
    height: 16px;
}

/* Author Bio Section */
.author-bio-section {
    background: #F8FAFC;
    padding: 4rem 0;
}

.author-bio-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem;
}

.author-bio-card {
    background: white;
    border: 1px solid var(--article-border);
    border-radius: 16px;
    padding: 2rem;
    display: flex;
    gap: 2rem;
    align-items: center;
}

.author-bio-avatar {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--article-orange) 0%, #f5a854 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    font-weight: 600;
    flex-shrink: 0;
}

.author-bio-content h3 {
    font-size: 1.35rem;
    color: var(--article-dark);
    margin-bottom: 0.25rem;
}

.author-bio-role {
    font-size: 0.95rem;
    color: var(--article-orange);
    margin-bottom: 0.75rem;
}

.author-bio-text {
    font-size: 0.95rem;
    color: var(--article-text);
    line-height: 1.7;
    margin-bottom: 1rem;
}

.author-social {
    display: flex;
    gap: 0.5rem;
}

.author-social a {
    width: 36px;
    height: 36px;
    border: 1px solid var(--article-border);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--article-text);
    transition: all 0.2s ease;
}

.author-social a:hover {
    border-color: var(--article-orange);
    color: var(--article-orange);
}

.author-social svg {
    width: 18px;
    height: 18px;
}

/* Related Articles Section */
.related-section {
    padding: 5rem 0;
    background: white;
}

.related-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.related-header {
    text-align: center;
    margin-bottom: 3rem;
}

.related-header h2 {
    font-size: 2rem;
    color: var(--article-dark);
    margin-bottom: 0.5rem;
}

.related-header p {
    color: var(--article-text);
    font-size: 1.05rem;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

.related-card {
    background: #FAFBFC;
    border: 1px solid var(--article-border);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.related-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    transform: translateY(-4px);
}

.related-card-image {
    height: 160px;
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.related-card-image svg {
    width: 48px;
    height: 48px;
    color: rgba(255, 255, 255, 0.2);
}

.related-card-content {
    padding: 1.5rem;
}

.related-card-category {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--article-orange);
    margin-bottom: 0.5rem;
}

.related-card-title {
    font-size: 1.05rem;
    color: var(--article-dark);
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 0.75rem;
}

.related-card-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s ease;
}

.related-card-title a:hover {
    color: var(--article-orange);
}

.related-card-meta {
    font-size: 0.85rem;
    color: var(--article-text-light);
}

/* Responsive */
@media (max-width: 1024px) {
    .article-layout {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .article-sidebar {
        position: static;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        padding: 0 0 3rem;
    }
    
    .related-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .article-hero {
        padding: 6rem 0 3rem;
    }
    
    .article-hero h1 {
        font-size: 2rem;
    }
    
    .article-excerpt {
        font-size: 1.05rem;
    }
    
    .article-sidebar {
        grid-template-columns: 1fr;
    }
    
    .author-bio-card {
        flex-direction: column;
        text-align: center;
    }
    
    .author-social {
        justify-content: center;
    }
    
    .related-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Article Hero -->
<section class="article-hero">
    <div class="article-hero-inner">
        <div class="article-meta-top">
            <a href="<?php echo url('blogs.php'); ?>#lean-factory" class="article-category-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5"/>
                    <path d="M2 12l10 5 10-5"/>
                </svg>
                Lean Factory
            </a>
            <span class="article-date-badge">December 15, 2025</span>
        </div>
        
        <h1>7 Principles of Lean Factory Design That Transform Operations</h1>
        
        <p class="article-excerpt">Discover the fundamental principles that guide successful lean factory implementations. From value stream mapping to continuous flow, learn how these concepts translate into tangible efficiency gains.</p>
        
        <div class="article-author-card">
            <div class="author-avatar-large">RS</div>
            <div class="author-info-details">
                <span class="author-info-name">Rajesh Sharma</span>
                <span class="author-info-role">Lead Consultant, Solutions OptiSpace</span>
                <div class="author-info-meta">
                    <span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        8 min read
                    </span>
                    <span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        2,450 views
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="article-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li><a href="<?php echo url('blogs.php'); ?>">Blog</a></li>
        <li>7 Principles of Lean Factory Design</li>
    </ul>
</nav>

<!-- Article Layout -->
<div class="article-layout">
    <!-- Main Content -->
    <main class="article-main">
        <article class="article-content">
            <p>In today's competitive manufacturing landscape, the difference between thriving and merely surviving often comes down to operational efficiency. <strong>Lean factory design</strong> isn't just about cutting costs—it's about creating a production environment that maximizes value while minimizing waste at every turn.</p>
            
            <p>After two decades of implementing lean principles across manufacturing facilities in India and beyond, we've distilled our experience into seven fundamental principles that consistently deliver transformative results.</p>
            
            <div class="article-image">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5"/>
                    <path d="M2 12l10 5 10-5"/>
                </svg>
            </div>
            <p class="article-image-caption">Lean factory design creates optimized workflows and minimal waste</p>
            
            <h2>Understanding Lean at Its Core</h2>
            
            <p>Before diving into the principles, it's essential to understand what lean truly means. Lean manufacturing is a systematic approach to identifying and eliminating waste through continuous improvement. In the context of factory design, this translates to creating physical layouts that naturally support efficient operations.</p>
            
            <blockquote>
                "The goal isn't to work harder—it's to design systems where working smarter becomes the default behavior."
            </blockquote>
            
            <h2>The 7 Principles of Lean Factory Design</h2>
            
            <div class="principle-card">
                <div class="principle-header">
                    <span class="principle-number">1</span>
                    <h3 class="principle-title">Value Stream Optimization</h3>
                </div>
                <div class="principle-content">
                    <p>Everything begins with understanding your value stream—the complete sequence of activities required to deliver your product. A lean factory layout must align physical space with the natural flow of value creation.</p>
                    <p><strong>Key implementation steps:</strong></p>
                    <ul>
                        <li>Map your current state value stream</li>
                        <li>Identify non-value-adding activities</li>
                        <li>Design future state with optimized flow</li>
                        <li>Align physical layout to support the future state</li>
                    </ul>
                </div>
            </div>
            
            <div class="principle-card blue">
                <div class="principle-header">
                    <span class="principle-number">2</span>
                    <h3 class="principle-title">Continuous Flow Design</h3>
                </div>
                <div class="principle-content">
                    <p>Products should move continuously through the manufacturing process with minimal interruption. This means designing workstations, material handling systems, and buffer points to support uninterrupted production.</p>
                    <p>In practice, this often means moving away from batch processing toward one-piece flow or small-batch production. The physical layout must support this transition with:</p>
                    <ul>
                        <li>Adjacent workstations that minimize transport time</li>
                        <li>Right-sized equipment that matches takt time requirements</li>
                        <li>Visual management systems for flow monitoring</li>
                    </ul>
                </div>
            </div>
            
            <div class="principle-card green">
                <div class="principle-header">
                    <span class="principle-number">3</span>
                    <h3 class="principle-title">Pull System Integration</h3>
                </div>
                <div class="principle-content">
                    <p>Rather than pushing product through based on forecasts, lean factories respond to actual customer demand. The physical layout must support visual signals and kanban systems that enable pull-based production.</p>
                    <p>This includes designing supermarkets, kanban boards, and signal points directly into the factory layout—not as afterthoughts, but as integral components of the production system.</p>
                </div>
            </div>
            
            <div class="principle-card">
                <div class="principle-header">
                    <span class="principle-number">4</span>
                    <h3 class="principle-title">Waste Elimination by Design</h3>
                </div>
                <div class="principle-content">
                    <p>The seven wastes (muda) should be addressed through thoughtful layout design:</p>
                    <ul>
                        <li><strong>Transportation:</strong> Minimize distances between processes</li>
                        <li><strong>Inventory:</strong> Design small buffer zones, not large warehouses</li>
                        <li><strong>Motion:</strong> Optimize workstation ergonomics</li>
                        <li><strong>Waiting:</strong> Balance line to eliminate bottlenecks</li>
                        <li><strong>Over-processing:</strong> Right-size equipment and work areas</li>
                        <li><strong>Overproduction:</strong> Enable visual production control</li>
                        <li><strong>Defects:</strong> Build in quality at source</li>
                    </ul>
                </div>
            </div>
            
            <div class="principle-card blue">
                <div class="principle-header">
                    <span class="principle-number">5</span>
                    <h3 class="principle-title">Flexibility and Scalability</h3>
                </div>
                <div class="principle-content">
                    <p>Markets change. Product mixes evolve. A lean factory must be designed with flexibility in mind—not just for today's production requirements, but for tomorrow's uncertainties.</p>
                    <p>This means incorporating modular layouts, reconfigurable workstations, and growth corridors that allow expansion without disruption.</p>
                </div>
            </div>
            
            <div class="principle-card green">
                <div class="principle-header">
                    <span class="principle-number">6</span>
                    <h3 class="principle-title">Visual Management Integration</h3>
                </div>
                <div class="principle-content">
                    <p>A well-designed lean factory communicates status instantly. Anyone walking the floor should be able to understand what's happening—whether production is on schedule, if there are quality issues, or where problems exist.</p>
                    <p>Design considerations include:</p>
                    <ul>
                        <li>Clear sightlines across production areas</li>
                        <li>Dedicated spaces for visual displays</li>
                        <li>Color-coded zones and pathways</li>
                        <li>Central display areas for key metrics</li>
                    </ul>
                </div>
            </div>
            
            <div class="principle-card">
                <div class="principle-header">
                    <span class="principle-number">7</span>
                    <h3 class="principle-title">People-Centric Design</h3>
                </div>
                <div class="principle-content">
                    <p>Ultimately, lean is about enabling people to do their best work. Factory design must consider ergonomics, safety, communication, and teamwork.</p>
                    <p>This includes creating team zones that foster collaboration, ensuring adequate lighting and ventilation, and designing workstations that minimize physical strain while maximizing productivity.</p>
                </div>
            </div>
            
            <h2>Putting Principles into Practice</h2>
            
            <p>These principles don't exist in isolation—they reinforce each other. A factory designed for continuous flow naturally supports pull systems. Visual management makes waste visible. People-centric design improves quality at source.</p>
            
            <p>The key is to approach factory design holistically, considering how each decision impacts the overall system. This is where our <a href="<?php echo url('process.php'); ?>">LFB Master Journey</a> methodology proves invaluable—providing a structured framework for translating these principles into physical reality.</p>
            
            <div class="takeaways-box">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    Key Takeaways
                </h3>
                <ul class="takeaways-list">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Start with value stream mapping before making any physical layout decisions
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Design for continuous flow—minimize batching and transportation
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Build flexibility into your layout to accommodate future changes
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Make visual management an integral part of the physical design
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Always consider the human element—ergonomics, safety, and communication
                    </li>
                </ul>
            </div>
            
            <h2>Getting Started</h2>
            
            <p>If you're considering a factory redesign or planning a new facility, these principles provide a solid foundation. However, every manufacturing operation is unique, and the application of these principles must be tailored to your specific context.</p>
            
            <p>That's why we recommend starting with a <a href="<?php echo url('pulse-check.php'); ?>">Pulse Check</a>—a complimentary assessment that helps identify opportunities for improvement and provides a roadmap for your lean factory journey.</p>
            
            <!-- Article Tags -->
            <div class="article-tags">
                <a href="#" class="article-tag">Lean Manufacturing</a>
                <a href="#" class="article-tag">Factory Design</a>
                <a href="#" class="article-tag">Value Stream</a>
                <a href="#" class="article-tag">Continuous Flow</a>
                <a href="#" class="article-tag">Waste Elimination</a>
                <a href="#" class="article-tag">Visual Management</a>
            </div>
            
            <!-- Share Section -->
            <div class="share-section">
                <span class="share-label">Share this article:</span>
                <div class="share-buttons">
                    <a href="#" class="share-btn" title="Share on LinkedIn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                            <rect x="2" y="9" width="4" height="12"/>
                            <circle cx="4" cy="4" r="2"/>
                        </svg>
                    </a>
                    <a href="#" class="share-btn" title="Share on Twitter">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
                        </svg>
                    </a>
                    <a href="#" class="share-btn" title="Share via Email">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </a>
                    <a href="#" class="share-btn" title="Copy Link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                        </svg>
                    </a>
                </div>
            </div>
        </article>
    </main>
    
    <!-- Sidebar -->
    <aside class="article-sidebar">
        <!-- Table of Contents -->
        <div class="sidebar-widget">
            <h4>In This Article</h4>
            <ul class="toc-list">
                <li><a href="#understanding">Understanding Lean at Its Core</a></li>
                <li><a href="#principles" class="active">The 7 Principles</a></li>
                <li><a href="#practice">Putting Principles into Practice</a></li>
                <li><a href="#started">Getting Started</a></li>
            </ul>
        </div>
        
        <!-- Related Articles -->
        <div class="sidebar-widget">
            <h4>Related Articles</h4>
            <a href="<?php echo url('blog/value-stream-mapping-guide.php'); ?>" class="related-article">
                <div class="related-thumb">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    </svg>
                </div>
                <div class="related-content">
                    <div class="related-title">Value Stream Mapping Guide</div>
                    <div class="related-meta">7 min read</div>
                </div>
            </a>
            <a href="<?php echo url('blog/brownfield-vs-greenfield.php'); ?>" class="related-article">
                <div class="related-thumb">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                    </svg>
                </div>
                <div class="related-content">
                    <div class="related-title">Brownfield vs Greenfield</div>
                    <div class="related-meta">6 min read</div>
                </div>
            </a>
            <a href="<?php echo url('blog/5s-implementation-manufacturing.php'); ?>" class="related-article">
                <div class="related-thumb">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </div>
                <div class="related-content">
                    <div class="related-title">5S Implementation Beyond Basics</div>
                    <div class="related-meta">6 min read</div>
                </div>
            </a>
        </div>
        
        <!-- CTA Widget -->
        <div class="sidebar-widget cta-widget">
            <h4>Ready to Transform Your Factory?</h4>
            <p>Start with a complimentary Pulse Check to identify optimization opportunities.</p>
            <a href="<?php echo url('pulse-check.php'); ?>" class="btn-cta">
                Request Pulse Check
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14"/>
                    <path d="M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </aside>
</div>

<!-- Author Bio Section -->
<section class="author-bio-section">
    <div class="author-bio-container">
        <div class="author-bio-card">
            <div class="author-bio-avatar">RS</div>
            <div class="author-bio-content">
                <h3>Rajesh Sharma</h3>
                <div class="author-bio-role">Lead Consultant, Solutions OptiSpace</div>
                <p class="author-bio-text">With over 20 years of experience in lean manufacturing and factory design, Rajesh has led transformative projects across automotive, pharmaceutical, and consumer goods industries. He specializes in value stream optimization and brownfield factory redesigns.</p>
                <div class="author-social">
                    <a href="#" title="LinkedIn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                            <rect x="2" y="9" width="4" height="12"/>
                            <circle cx="4" cy="4" r="2"/>
                        </svg>
                    </a>
                    <a href="#" title="Email">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Articles Section -->
<section class="related-section">
    <div class="related-container">
        <div class="related-header">
            <h2>Continue Reading</h2>
            <p>More articles you might find interesting</p>
        </div>
        
        <div class="related-grid">
            <article class="related-card">
                <div class="related-card-image">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path d="M3 9h18"/>
                        <path d="M9 21V9"/>
                    </svg>
                </div>
                <div class="related-card-content">
                    <div class="related-card-category">Layout Design</div>
                    <h3 class="related-card-title"><a href="<?php echo url('blog/brownfield-vs-greenfield.php'); ?>">Brownfield vs Greenfield: Making the Right Choice</a></h3>
                    <div class="related-card-meta">Nov 28, 2025 • 6 min read</div>
                </div>
            </article>
            
            <article class="related-card">
                <div class="related-card-image">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <div class="related-card-content">
                    <div class="related-card-category">Case Study</div>
                    <h3 class="related-card-title"><a href="<?php echo url('blog/automotive-plant-transformation.php'); ?>">40% Efficiency Gain: An Automotive Success Story</a></h3>
                    <div class="related-card-meta">Nov 12, 2025 • 10 min read</div>
                </div>
            </article>
            
            <article class="related-card">
                <div class="related-card-image">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div class="related-card-content">
                    <div class="related-card-category">Lean Factory</div>
                    <h3 class="related-card-title"><a href="<?php echo url('blog/value-stream-mapping-guide.php'); ?>">Complete Guide to Value Stream Mapping</a></h3>
                    <div class="related-card-meta">Dec 5, 2025 • 7 min read</div>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- Newsletter CTA -->
<section class="newsletter-cta">
    <div class="newsletter-cta-container">
        <div class="newsletter-cta-content">
            <h2>Get More Insights Delivered</h2>
            <p>Subscribe to receive our monthly newsletter with the latest articles on lean manufacturing and factory optimization.</p>
        </div>
        <form class="newsletter-cta-form" action="#" method="POST">
            <input type="email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
        </form>
    </div>
</section>

<style>
/* Newsletter CTA */
.newsletter-cta {
    padding: 4rem 0;
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
}

.newsletter-cta-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 3rem;
    flex-wrap: wrap;
}

.newsletter-cta-content h2 {
    font-size: 1.75rem;
    color: white;
    margin-bottom: 0.5rem;
}

.newsletter-cta-content p {
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
    font-size: 1rem;
}

.newsletter-cta-form {
    display: flex;
    gap: 0.75rem;
}

.newsletter-cta-form input {
    padding: 0.875rem 1.25rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    font-size: 0.95rem;
    color: white;
    min-width: 280px;
}

.newsletter-cta-form input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.newsletter-cta-form input:focus {
    outline: none;
    border-color: var(--article-orange);
}

.newsletter-cta-form button {
    padding: 0.875rem 1.5rem;
    background: var(--article-orange);
    border: none;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: all 0.2s ease;
}

.newsletter-cta-form button:hover {
    background: #d4851c;
}

@media (max-width: 768px) {
    .newsletter-cta-container {
        flex-direction: column;
        text-align: center;
    }
    
    .newsletter-cta-form {
        flex-direction: column;
        width: 100%;
    }
    
    .newsletter-cta-form input {
        min-width: auto;
        width: 100%;
    }
}
</style>

<?php include '../includes/footer.php'; ?>
