<?php
$currentPage = 'philosophy';
$pageTitle = 'Why Design with LFB? | The OptiSpace Difference';
$pageDescription = 'Learn about the Lean Factory Building philosophy and why the OptiSpace inside-out approach creates superior manufacturing facilities.';
include 'includes/header.php';
?>

<style>
/* ========================================
   PHILOSOPHY PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --philosophy-orange: #E99431;
    --philosophy-orange-light: rgba(233, 148, 49, 0.08);
    --philosophy-blue: #3B82F6;
    --philosophy-blue-light: rgba(59, 130, 246, 0.08);
    --philosophy-gray: #64748B;
    --philosophy-gray-light: rgba(100, 116, 139, 0.08);
    --philosophy-dark: #1E293B;
    --philosophy-text: #475569;
    --philosophy-border: #E2E8F0;
}

/* Hero Section */
.philosophy-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 6rem;
    position: relative;
    overflow: hidden;
}

.philosophy-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
}

.philosophy-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.philosophy-hero-content {
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

.philosophy-hero h1 {
    font-size: 3.25rem;
    font-weight: 700;
    color: white;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

.philosophy-hero h1 span {
    color: #E99431;
}

.philosophy-hero-text {
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

.hero-principles-preview {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    max-width: 400px;
}

.preview-principle {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.25rem;
    transition: all 0.3s ease;
}

.preview-principle:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(233, 148, 49, 0.3);
    transform: translateY(-2px);
}

.preview-principle-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #E99431 0%, #f5a854 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
}

.preview-principle:nth-child(2) .preview-principle-icon {
    background: linear-gradient(135deg, #3B82F6 0%, #60a5fa 100%);
}

.preview-principle:nth-child(3) .preview-principle-icon {
    background: linear-gradient(135deg, #10B981 0%, #34d399 100%);
}

.preview-principle:nth-child(4) .preview-principle-icon {
    background: linear-gradient(135deg, #64748B 0%, #94a3b8 100%);
}

.preview-principle-icon svg {
    width: 18px;
    height: 18px;
    color: white;
}

.preview-principle-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
}

/* Breadcrumb */
.philosophy-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--philosophy-border);
}

.philosophy-breadcrumb ul {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.philosophy-breadcrumb a {
    color: var(--philosophy-gray);
    text-decoration: none;
}

.philosophy-breadcrumb a:hover {
    color: var(--philosophy-orange);
}

.philosophy-breadcrumb li:last-child {
    color: var(--philosophy-dark);
    font-weight: 500;
}

.philosophy-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--philosophy-border);
}

@media (max-width: 1024px) {
    .philosophy-hero-inner {
        grid-template-columns: 1fr;
        gap: 3rem;
        text-align: center;
    }
    
    .philosophy-hero-text {
        margin: 0 auto 2rem;
        max-width: 700px;
    }
    
    .hero-stats {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .philosophy-hero {
        padding: 5rem 0 4rem;
    }
    
    .philosophy-hero h1 {
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
    
    .hero-principles-preview {
        grid-template-columns: 1fr;
        max-width: 280px;
    }
}
</style>

<!-- Hero Section -->
<section class="philosophy-hero">
    <div class="philosophy-hero-inner">
        <div class="philosophy-hero-content">
            <div class="hero-eyebrow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                    <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
                Core Methodology
            </div>
            <h1>The <span>LFB Philosophy</span></h1>
            <p class="philosophy-hero-text">Understanding the deep strategic logic behind OptiSpace and why inside-out factory design creates superior manufacturing facilities.</p>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-value">Inside</div>
                    <div class="hero-stat-label">Out Design</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">20+</div>
                    <div class="hero-stat-label">Years Locked In</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">4</div>
                    <div class="hero-stat-label">Wastes Eliminated</div>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-principles-preview">
                <div class="preview-principle">
                    <div class="preview-principle-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                        </svg>
                    </div>
                    <div class="preview-principle-title">Flow First</div>
                </div>
                <div class="preview-principle">
                    <div class="preview-principle-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4"/>
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                    </div>
                    <div class="preview-principle-title">Waste Elimination</div>
                </div>
                <div class="preview-principle">
                    <div class="preview-principle-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                            <path d="M9 7h1m-1 4h1m4-4h1m-1 4h1"/>
                        </svg>
                    </div>
                    <div class="preview-principle-title">Building Follows</div>
                </div>
                <div class="preview-principle">
                    <div class="preview-principle-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="preview-principle-title">Future Ready</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="philosophy-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li>Philosophy</li>
    </ul>
</nav>

<style>
/* Section Styling */
.philosophy-section {
    padding: 6rem 0;
    background: white;
}

.philosophy-section.section-light {
    background: #FAFBFC;
}

.philosophy-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.philosophy-section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.philosophy-section-header h2 {
    font-size: 2.5rem;
    color: var(--philosophy-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.philosophy-section-header p {
    font-size: 1.15rem;
    color: var(--philosophy-text);
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.7;
}

.belief-card {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    border-left: 4px solid var(--philosophy-orange);
    border-radius: 12px;
    padding: 3rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}

.belief-card p {
    font-size: 1.25rem;
    line-height: 1.8;
    margin: 0;
    color: var(--philosophy-dark);
}

@media (max-width: 768px) {
    .philosophy-section {
        padding: 4rem 0;
    }
    
    .philosophy-section-header h2 {
        font-size: 2rem;
    }
    
    .belief-card {
        padding: 2rem;
    }
}
</style>

<section class="philosophy-section">
    <div class="philosophy-container">
        <div class="philosophy-section-header">
            <h2>Our Core Belief</h2>
            <p>The foundation of everything we do at OptiSpace</p>
        </div>

        <div class="belief-card">
            <p>OptiSpace believes that factory buildings must be designed as seamless extensions of the production system, not as generic industrial boxes. Too often, the building constrains the process. We flip this dynamic. The LFB philosophy translates Lean thinking directly into architecture and layout, ensuring the physical asset works for the process, not against it.</p>
        </div>
        
        <!-- Philosophy Flow Visual -->
        <div class="philosophy-flow">
            <div class="flow-step">
                <div class="flow-icon flow-icon-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="flow-label">Understand</div>
                <div class="flow-title">Process & Flow</div>
            </div>
            <div class="flow-connector"></div>
            <div class="flow-step">
                <div class="flow-icon flow-icon-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/>
                    </svg>
                </div>
                <div class="flow-label">Optimize</div>
                <div class="flow-title">Layout Design</div>
            </div>
            <div class="flow-connector"></div>
            <div class="flow-step">
                <div class="flow-icon flow-icon-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="flow-label">Design</div>
                <div class="flow-title">Building Follows</div>
            </div>
            <div class="flow-connector"></div>
            <div class="flow-step">
                <div class="flow-icon flow-icon-4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="flow-label">Achieve</div>
                <div class="flow-title">Lasting Success</div>
            </div>
        </div>
    </div>
</section>

<style>
.philosophy-flow {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 4rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.flow-step {
    text-align: center;
    flex: 0 0 auto;
}

.flow-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    background: white;
    border: 2px solid var(--philosophy-border);
    transition: all 0.3s ease;
}

.flow-step:hover .flow-icon {
    transform: scale(1.1);
}

.flow-icon svg {
    width: 32px;
    height: 32px;
}

.flow-icon-1 { border-color: var(--philosophy-orange); box-shadow: 0 4px 20px rgba(233, 148, 49, 0.15); }
.flow-icon-1 svg { color: var(--philosophy-orange); }

.flow-icon-2 { border-color: var(--philosophy-blue); box-shadow: 0 4px 20px rgba(59, 130, 246, 0.15); }
.flow-icon-2 svg { color: var(--philosophy-blue); }

.flow-icon-3 { border-color: #10B981; box-shadow: 0 4px 20px rgba(16, 185, 129, 0.15); }
.flow-icon-3 svg { color: #10B981; }

.flow-icon-4 { border-color: var(--philosophy-gray); box-shadow: 0 4px 20px rgba(100, 116, 139, 0.15); }
.flow-icon-4 svg { color: var(--philosophy-gray); }

.flow-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--philosophy-gray);
    margin-bottom: 0.25rem;
}

.flow-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--philosophy-dark);
}

.flow-connector {
    width: 60px;
    height: 2px;
    background: linear-gradient(90deg, var(--philosophy-orange), var(--philosophy-blue));
    margin: 0 0.5rem;
    margin-bottom: 2.5rem;
}

@media (max-width: 768px) {
    .philosophy-flow {
        flex-direction: column;
    }
    
    .flow-connector {
        width: 2px;
        height: 40px;
        background: linear-gradient(180deg, var(--philosophy-orange), var(--philosophy-blue));
        margin: 0.5rem 0;
    }
}
</style>

<style>
.principle-card {
    background: white;
    border: 1px solid var(--philosophy-border);
    border-radius: 12px;
    padding: 2rem;
    height: 100%;
    transition: all 0.3s ease;
}

.principle-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    border-color: var(--philosophy-orange);
}

.principle-card h3 {
    font-size: 1.35rem;
    color: var(--philosophy-dark);
    margin: 0 0 1.25rem 0;
    font-weight: 600;
}

.principle-card p {
    font-size: 1.05rem;
    line-height: 1.7;
    color: var(--philosophy-text);
    margin: 0;
}

.principle-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.principle-card ul li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.5rem 0;
    color: var(--philosophy-text);
    font-size: 1rem;
}

.principle-card ul li::before {
    content: '→';
    color: var(--philosophy-orange);
    font-weight: 700;
    flex-shrink: 0;
}

.principle-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

@media (max-width: 768px) {
    .principle-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<section class="philosophy-section section-light">
    <div class="philosophy-container">
        <div class="philosophy-section-header">
            <h2>The Core Principle: Inside‑Out Factory Design</h2>
            <p>LFB is our signature 'inside‑out' design philosophy. It necessitates starting with the value stream, flow, and ergonomics, and only then defining the building's grid, structure, and utility networks.</p>
        </div>

        <div class="principle-grid">
            <div class="principle-card">
                <h3>The Engine Before the Chassis</h3>
                <p>Imagine building a car by designing the chassis before you know what engine goes inside. That is how most factories are built—shell first, process second. LFB demands we understand the 'engine' of your factory—your process—before we ever draw the chassis.</p>
            </div>
            <div class="principle-card">
                <h3>The LFB Sequence</h3>
                <ul>
                    <li>Map product families, process sequences, and value streams first</li>
                    <li>Balance workloads and takt time across operations to define space needs</li>
                    <li>Optimize material movement, storage, and operator ergonomics</li>
                    <li>Finally, translate this ideal flow into building grids, floor levels, and utility routing</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="philosophy-section section-light">
    <div class="philosophy-container">
        <div class="philosophy-section-header">
            <h2>Eliminating 'Mudas' (Waste) Structurally</h2>
            <p>LFB is built on the foundation of Lean thinking. Our philosophy focuses on structurally eliminating key factory wastes through permanent layout and architectural decisions.</p>
        </div>

        <div class="waste-grid">
            <div class="waste-card">
                <div class="waste-header">
                    <div class="waste-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                    </div>
                    <h3>Transportation Waste</h3>
                </div>
                <div class="waste-content">
                    <div class="waste-example">
                        <span class="waste-label">Problem:</span>
                        Forklifts traveling 300 meters between machining and assembly every cycle
                    </div>
                    <div class="waste-solution">
                        <span class="waste-label">LFB Solution:</span>
                        Minimal or near-zero transportation through optimized adjacency and flow design
                    </div>
                    <div class="waste-impact">Reduced handling costs, faster throughput, lower damage</div>
                </div>
            </div>
            <div class="waste-card">
                <div class="waste-header">
                    <div class="waste-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                        </svg>
                    </div>
                    <h3>Motion Waste</h3>
                </div>
                <div class="waste-content">
                    <div class="waste-example">
                        <span class="waste-label">Problem:</span>
                        Operators walking 15 meters to retrieve parts, adding fatigue and cycle time
                    </div>
                    <div class="waste-solution">
                        <span class="waste-label">LFB Solution:</span>
                        Ergonomic workstation design with materials positioned within easy reach
                    </div>
                    <div class="waste-impact">Increased productivity, reduced fatigue, improved quality</div>
                </div>
            </div>
            <div class="waste-card">
                <div class="waste-header">
                    <div class="waste-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <h3>Waiting Waste</h3>
                </div>
                <div class="waste-content">
                    <div class="waste-example">
                        <span class="waste-label">Problem:</span>
                        Sub-assemblies piling up while the next operation sits idle due to imbalanced flow
                    </div>
                    <div class="waste-solution">
                        <span class="waste-label">LFB Solution:</span>
                        Balanced production flow with synchronized takt time across operations
                    </div>
                    <div class="waste-impact">Smoother flow, predictable lead times, better utilization</div>
                </div>
            </div>
            <div class="waste-card">
                <div class="waste-header">
                    <div class="waste-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <h3>Excess Inventory Waste</h3>
                </div>
                <div class="waste-content">
                    <div class="waste-example">
                        <span class="waste-label">Problem:</span>
                        Excessive buffer stocks consuming floor space and working capital
                    </div>
                    <div class="waste-solution">
                        <span class="waste-label">LFB Solution:</span>
                        Continuous flow with minimal WIP through balanced layout and visual management
                    </div>
                    <div class="waste-impact">Reduced capital, faster quality feedback, less obsolescence</div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.waste-card {
    background: white;
    border: 1px solid var(--philosophy-border);
    border-radius: 12px;
    padding: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}

.waste-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    transform: translateY(-4px);
    border-color: var(--philosophy-orange);
}

.waste-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    border-bottom: 1px solid var(--philosophy-border);
}

.waste-icon {
    width: 48px;
    height: 48px;
    background: var(--philosophy-orange-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.waste-icon svg {
    width: 24px;
    height: 24px;
    color: var(--philosophy-orange);
}

.waste-card h3 {
    font-size: 1.15rem;
    color: var(--philosophy-dark);
    margin: 0;
    font-weight: 600;
}

.waste-content {
    padding: 1.5rem;
}

.waste-example, .waste-solution {
    font-size: 0.95rem;
    line-height: 1.6;
    color: var(--philosophy-text);
    margin-bottom: 0.75rem;
}

.waste-label {
    font-weight: 600;
    color: var(--philosophy-dark);
    display: inline;
}

.waste-impact {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--philosophy-orange);
    padding-top: 0.75rem;
    border-top: 1px dashed var(--philosophy-border);
    margin-top: 0.75rem;
}

.waste-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

@media (max-width: 768px) {
    .waste-grid {
        grid-template-columns: 1fr;
    }
}
</style>
    </div>
</section>

<style>
.comparison-section {
    padding: 6rem 0;
    background: white;
}

.comparison-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

.comparison-column {
    background: white;
    border: 2px solid var(--philosophy-border);
    border-radius: 16px;
    padding: 2.5rem;
    transition: all 0.3s ease;
}

.comparison-column:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
}

.comparison-column.conventional {
    border-top: 4px solid #EF4444;
}

.comparison-column.optispace {
    border-top: 4px solid var(--philosophy-orange);
    background: linear-gradient(135deg, #FFFBF5 0%, #FFF8EE 100%);
}

.comparison-column h3 {
    font-size: 1.5rem;
    margin: 0 0 1.5rem 0;
    color: var(--philosophy-dark);
    font-weight: 700;
}

.comparison-column.conventional h3 {
    color: #EF4444;
}

.comparison-column.optispace h3 {
    color: var(--philosophy-orange);
}

.comparison-column ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.comparison-column ul li {
    padding: 1rem 0;
    border-bottom: 1px solid var(--philosophy-border);
    color: var(--philosophy-text);
    line-height: 1.7;
}

.comparison-column ul li:last-child {
    border-bottom: none;
}

.comparison-column ul li strong {
    color: var(--philosophy-dark);
    font-weight: 600;
    display: block;
    margin-bottom: 0.25rem;
}

@media (max-width: 968px) {
    .comparison-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<section class="comparison-section">
    <div class="philosophy-container">
        <div class="philosophy-section-header">
            <h2>How LFB Philosophy Differs from Conventional Architecture</h2>
            <p>Understanding the fundamental difference in approach</p>
        </div>

        <div class="comparison-grid">
            <div class="comparison-column conventional">
                <h3>❌ Conventional Approach</h3>
                <ul>
                    <li><strong>Starting Point:</strong> Building design and aesthetics</li>
                    <li><strong>Structure:</strong> Sees the factory as a building first, system second... Optimizes for form, facades, and standard spans</li>
                    <li><strong>Utilities:</strong> Planned around building layout and structural convenience</li>
                    <li><strong>Flexibility:</strong> Limited adaptability constrained by pre-defined structural decisions</li>
                    <li><strong>Material Flow:</strong> Treats material flow as an operational problem to be 'managed inside' the finished box</li>
                    <li><strong>Column Grid:</strong> Based on standard spans and structural convenience</li>
                    <li><strong>Result:</strong> Building constraints often fight against operational efficiency</li>
                </ul>
            </div>
            <div class="comparison-column optispace">
                <h3>✓ OptiSpace LFB Approach</h3>
                <ul>
                    <li><strong>Starting Point:</strong> Production process, value streams, and product flow</li>
                    <li><strong>Structure:</strong> Sees the factory as a flow system first, building second... Optimizes for takt time, adjacency, and line‑of‑flow before facades</li>
                    <li><strong>Utilities:</strong> Uses structure, levels, and utilities as strategic levers to remove waste</li>
                    <li><strong>Flexibility:</strong> Built-in adaptability for automation, volume variation, and product mix changes</li>
                    <li><strong>Material Flow:</strong> Material flow and value streams drive all building design decisions</li>
                    <li><strong>Column Grid:</strong> Designed to support optimal equipment layout and future expansion</li>
                    <li><strong>Result:</strong> Building actively supports and enables operational excellence</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<style>
.roi-card {
    background: white;
    border: 1px solid var(--philosophy-border);
    border-radius: 12px;
    padding: 2rem;
    transition: all 0.3s ease;
}

.roi-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    border-color: var(--philosophy-orange);
}

.roi-card h3 {
    font-size: 1.25rem;
    color: var(--philosophy-dark);
    margin: 0 0 1rem 0;
    font-weight: 600;
}

.roi-card p {
    font-size: 0.95rem;
    color: var(--philosophy-text);
    line-height: 1.7;
    margin-bottom: 1rem;
}

.roi-card p strong {
    color: var(--philosophy-dark);
    font-weight: 600;
    display: block;
    margin-bottom: 0.5rem;
}

.roi-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.roi-card ul li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.35rem 0;
    color: var(--philosophy-text);
    font-size: 0.95rem;
    line-height: 1.6;
}

.roi-card ul li::before {
    content: '✓';
    color: var(--philosophy-orange);
    font-weight: 700;
    flex-shrink: 0;
}

.roi-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.lock-in-card {
    margin-top: 2.5rem;
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    border-left: 4px solid var(--philosophy-orange);
    border-radius: 12px;
    padding: 2.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}

.lock-in-card h3 {
    font-size: 1.5rem;
    color: var(--philosophy-dark);
    margin: 0 0 1rem 0;
    font-weight: 700;
}

.lock-in-card p {
    font-size: 1.1rem;
    line-height: 1.7;
    margin: 0;
    color: var(--philosophy-dark);
}

@media (max-width: 968px) {
    .roi-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<section class="philosophy-section section-light">
    <div class="philosophy-container">
        <div class="philosophy-section-header">
            <h2>The ROI: Preventing Hidden Costs</h2>
            <p>The cost of a building is paid once, but the cost of operating inside it is paid every day for decades. LFB decisions are made to minimize that daily cost, preventing locked-in inefficiencies that drain cash flow over the life of the asset.</p>
        </div>

        <div class="roi-grid">
            <div class="roi-card">
                <h3>Natural Light & Climate Control</h3>
                <p><strong>LFB Prevention:</strong></p>
                <ul>
                    <li>Orient and proportion spaces for natural light and efficient air conditioning loads</li>
                    <li>Right-sized HVAC zones based on actual production heat loads</li>
                    <li>Strategic building orientation to minimize solar heat gain</li>
                    <li>Reduced lifetime energy costs</li>
                </ul>
            </div>
            <div class="roi-card">
                <h3>Structural Alignment</h3>
                <p><strong>LFB Prevention:</strong></p>
                <ul>
                    <li>Align column grids with equipment and flow requirements, not just structural convenience</li>
                    <li>Floor load capacities designed for actual equipment weights</li>
                    <li>Clear spans where material flow demands them</li>
                    <li>Prevents costly structural workarounds later</li>
                </ul>
            </div>
            <div class="roi-card">
                <h3>Future Readiness</h3>
                <p><strong>LFB Prevention:</strong></p>
                <ul>
                    <li>Reserve corridors and vertical volumes specifically for future automation paths</li>
                    <li>Utility capacity and routing planned for expansion</li>
                    <li>Modular zones that accommodate product mix changes</li>
                    <li>Decades of structural feasibility preserved</li>
                </ul>
            </div>
        </div>

        <div class="lock-in-card">
            <h3>The 20-Year Lock-In Effect</h3>
            <p>Once a factory is built, decisions about column spacing, floor levels, loading bays, and utility routing are nearly impossible to change without major renovation costs. LFB ensures these critical decisions are made correctly from the start, preventing hidden costs that drain profitability every single day for the life of the asset.</p>
        </div>
    </div>
</section>

<style>
.benefit-card {
    background: white;
    border: 1px solid var(--philosophy-border);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
}

.benefit-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    transform: translateY(-4px);
    border-color: var(--philosophy-orange);
}

.benefit-card h3 {
    font-size: 1.25rem;
    color: var(--philosophy-dark);
    margin: 0 0 1rem 0;
    font-weight: 600;
}

.benefit-card p {
    font-size: 1rem;
    line-height: 1.7;
    color: var(--philosophy-text);
    margin: 0;
}

.benefit-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

@media (max-width: 968px) {
    .benefit-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<section class="philosophy-section">
    <div class="philosophy-container">
        <div class="philosophy-section-header">
            <h2>What the LFB Philosophy Means for You</h2>
        </div>

        <div class="benefit-grid">
            <div class="benefit-card">
                <h3>Process Never Constrained</h3>
                <p>Your building will never constrain your process improvements. The physical infrastructure supports continuous improvement rather than limiting it.</p>
            </div>
            <div class="benefit-card">
                <h3>Lean Culture in Concrete</h3>
                <p>Operational efficiency and Lean culture become embedded in the concrete, not just in SOPs. Good behaviors become structurally easier than bad ones.</p>
            </div>
            <div class="benefit-card">
                <h3>Decades of Flexibility</h3>
                <p>Expansion, automation, and product mix changes remain structurally feasible for decades without costly demolition or major renovation.</p>
            </div>
        </div>
    </div>
</section>

<style>
.philosophy-cta {
    padding: 6rem 0;
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
    position: relative;
    overflow: hidden;
}

.philosophy-cta::before {
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
    background: var(--philosophy-orange);
    color: white;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 2px solid var(--philosophy-orange);
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

@media (max-width: 768px) {
    .philosophy-cta {
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

<section class="philosophy-cta">
    <div class="philosophy-container">
        <div class="cta-inner">
            <h2>See the LFB Philosophy in Action</h2>
            <p>Start with a complimentary LFB Pulse Check to discover how our process-first philosophy can transform your specific manufacturing situation.</p>
            <div class="cta-buttons">
                <a href="<?php echo url('pulse-check.php'); ?>" class="btn-cta-primary">
                    Request Your Pulse Check
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="<?php echo url('services/greenfield.php'); ?>" class="btn-cta-secondary">
                    Explore Services
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
