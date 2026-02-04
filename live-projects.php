<?php
$currentPage = 'live-projects';
$pageTitle = 'Live Projects | Solutions OptiSpace';
$pageDescription = 'Explore our ongoing factory transformation projects. See how OptiSpace is currently helping businesses optimize their manufacturing facilities.';
$pageKeywords = 'live factory projects, ongoing lean projects, current transformations, factory design projects, manufacturing optimization, OptiSpace projects, brownfield projects, greenfield projects';
include 'includes/header.php';

// Get database connection
require_once 'database/db_config.php';
$conn = getDBConnection();

// Fetch active banners for Live Projects Page
$bannerQuery = "SELECT * FROM banner_settings WHERE page_name = 'live-projects' AND is_active = 1 ORDER BY sort_order ASC, created_at DESC";
$bannerResult = $conn->query($bannerQuery);
$activeBanners = [];
if ($bannerResult && $bannerResult->num_rows > 0) {
    while ($row = $bannerResult->fetch_assoc()) {
        $activeBanners[] = $row;
    }
}

// Fallback for Live Projects Banner
if (empty($activeBanners)) {
    $activeBanners[] = [
        'image_path' => '',
        'eyebrow_text' => 'Currently In Progress',
        'heading_html' => 'Our <span>Live Projects</span>',
        'subheading' => 'Watch our ongoing factory transformations in action. Each project showcases our commitment to delivering lean manufacturing excellence.'
    ];
}

// Fetch live projects from database
$projectsQuery = "SELECT * FROM live_projects 
                  WHERE is_active = 1 
                  ORDER BY is_featured DESC, sort_order ASC, created_at DESC";
$projectsResult = $conn->query($projectsQuery);
$projects = [];
if ($projectsResult) {
    while ($row = $projectsResult->fetch_assoc()) {
        $projects[] = $row;
    }
}
$conn->close();
?>

<style>
/* ========================================
   LIVE PROJECTS PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --live-orange: #E99431;
    --live-orange-light: rgba(233, 148, 49, 0.08);
    --live-blue: #3B82F6;
    --live-blue-light: rgba(59, 130, 246, 0.08);
    --live-green: #10B981;
    --live-green-light: rgba(16, 185, 129, 0.08);
    --live-dark: #1E293B;
    --live-text: #475569;
    --live-border: #E2E8F0;
}

/* Hero Section */
.live-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 6rem;
    position: relative;
    overflow: hidden;
    min-height: 550px;
    display: flex;
    align-items: center;
}

/* Slider Container */
.hero-slider-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1.5s ease-in-out;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.hero-slide.active {
    opacity: 1;
    z-index: 2;
}

    .hero-slide::before {
        content: none;
    }

.live-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
    position: relative;
    z-index: 3;
    width: 100%;
}

.hero-content-wrapper {
    display: grid;
    grid-template-areas: "hero-content";
    width: 100%;
    justify-content: center;
}

/* Slide Content Structure */
.hero-slide-content {
    grid-area: hero-content;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.4s ease;
    max-width: 800px;
}

.hero-slide-content.active {
    opacity: 1;
    pointer-events: auto;
    z-index: 2;
}

/* Staggered Animations */
.hero-slide-content .hero-eyebrow,
.hero-slide-content h1,
.hero-slide-content .live-hero-text {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.hero-slide-content:not(.active) .hero-eyebrow,
.hero-slide-content:not(.active) h1,
.hero-slide-content:not(.active) .live-hero-text {
    transform: translateY(-10px);
    opacity: 0;
    transition: all 0.4s ease;
}

.hero-slide-content.active .hero-eyebrow {
    opacity: 1;
    transform: translateY(0);
    transition-delay: 0.1s;
}

.hero-slide-content.active h1 {
    opacity: 1;
    transform: translateY(0);
    transition-delay: 0.2s;
}

.hero-slide-content.active .live-hero-text {
    opacity: 1;
    transform: translateY(0);
    transition-delay: 0.3s;
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

.live-hero h1 {
    font-size: 3.25rem;
    font-weight: 700;
    color: white;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

.live-hero h1 span {
    color: #E99431;
}

.live-hero-text {
    font-size: 1.2rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.75);
    margin-bottom: 0;
    max-width: 650px;
    margin-left: auto;
    margin-right: auto;
}

/* Slider Dots CSS */
.slider-dots {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 12px;
    z-index: 4;
}

.slider-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
}

.slider-dot.active {
    background: #E99431;
    width: 30px;
    border-radius: 6px;
}

.slider-dot:hover {
    background: rgba(255, 255, 255, 0.6);
}

@media (max-width: 768px) {
    .live-hero {
        padding: 7rem 0 4rem;
    }
    .live-hero h1 {
        font-size: 2.25rem;
    }
    .hero-stats-row {
        gap: 2rem;
    }
    .hero-stat-value {
        font-size: 2.25rem;
    }
}

/* Breadcrumb */
.live-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--live-border);
}

.live-breadcrumb ul {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.live-breadcrumb a {
    color: var(--live-text);
    text-decoration: none;
}

.live-breadcrumb a:hover {
    color: var(--live-orange);
}

.live-breadcrumb li:last-child {
    color: var(--live-dark);
    font-weight: 500;
}

.live-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--live-border);
}

/* Projects Section */
.live-projects-section {
    padding: 5rem 0;
    background: white;
}

.live-projects-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.section-intro {
    text-align: center;
    margin-bottom: 4rem;
}

.section-intro h2 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--live-dark);
    margin-bottom: 1rem;
}

.section-intro p {
    font-size: 1.1rem;
    color: var(--live-text);
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.7;
}

/* Project Cards */
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
}

@media (max-width: 480px) {
    .projects-grid {
        grid-template-columns: 1fr;
    }
}

.project-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--live-border);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    max-width: 320px;
    margin: 0 auto;
    width: 100%;
    text-decoration: none;
    color: inherit;
}

.project-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border-color: var(--live-orange);
}

.project-image {
    position: relative;
    width: 100%;
    height: auto;
    aspect-ratio: 1/1;
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    overflow: hidden;
}

.project-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.project-card:hover .project-image img {
    transform: scale(1.05);
}

.project-type-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: rgba(255, 255, 255, 0.95);
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--live-dark);
    backdrop-filter: blur(8px);
}

.project-type-badge.greenfield {
    background: rgba(16, 185, 129, 0.9);
    color: white;
}

.project-type-badge.brownfield {
    background: rgba(59, 130, 246, 0.9);
    color: white;
}

.project-progress-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
}

.project-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--live-orange) 0%, #f5a854 100%);
    transition: width 0.5s ease;
}

.project-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.project-header {
    margin-bottom: 1rem;
}

.project-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--live-dark);
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.project-client {
    font-size: 0.9rem;
    color: var(--live-text);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.project-client svg {
    width: 14px;
    height: 14px;
    color: var(--live-orange);
}

.project-description {
    font-size: 0.95rem;
    color: var(--live-text);
    line-height: 1.6;
    margin-bottom: 1.25rem;
    flex: 1;
}

.project-meta {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--live-border);
    margin-bottom: 1.25rem;
}

.project-meta-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.meta-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #94a3b8;
    font-weight: 600;
}

.meta-value {
    font-size: 0.9rem;
    color: var(--live-dark);
    font-weight: 500;
}

.project-status {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 1rem;
    border-top: 1px solid var(--live-border);
}

.status-progress {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.progress-circle {
    width: 44px;
    height: 44px;
    position: relative;
}

.progress-circle svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.progress-circle .bg {
    fill: none;
    stroke: #e5e7eb;
    stroke-width: 4;
}

.progress-circle .progress {
    fill: none;
    stroke: var(--live-orange);
    stroke-width: 4;
    stroke-linecap: round;
    transition: stroke-dashoffset 0.5s ease;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--live-dark);
}

.status-info {
    text-align: left;
}

.status-phase {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--live-dark);
}

.status-label {
    font-size: 0.75rem;
    color: var(--live-text);
}

.project-highlights {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.highlight-tag {
    background: var(--live-orange-light);
    color: var(--live-orange);
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.highlight-tag svg {
    width: 12px;
    height: 12px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: #f8fafc;
    border-radius: 16px;
    border: 2px dashed var(--live-border);
}

.empty-state svg {
    width: 64px;
    height: 64px;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    color: var(--live-dark);
    margin-bottom: 0.75rem;
}

.empty-state p {
    color: var(--live-text);
    font-size: 1rem;
}

.live-cta-section {
    padding: 5rem 0;
    background: #2f3030;
    position: relative;
    overflow: hidden;
}

.live-cta-section::before {
    content: '';
    position: absolute;
    top: -30%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(233, 148, 49, 0.2) 0%, transparent 70%);
    border-radius: 50%;
}

.live-cta-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
    position: relative;
    z-index: 2;
}

.live-cta-container h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1rem;
}

.live-cta-container h2 span {
    color: var(--live-orange);
}

.live-cta-container p {
    font-size: 1.15rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2rem;
    line-height: 1.7;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, var(--live-orange) 0%, #f5a854 100%);
    color: white;
    padding: 1rem 2rem;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(233, 148, 49, 0.35);
}

.cta-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(233, 148, 49, 0.45);
    color: white;
}

.cta-btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: transparent;
    color: white;
    padding: 1rem 2rem;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.cta-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
}

@media (max-width: 768px) {
    .live-cta-container h2 {
        font-size: 1.75rem;
    }
}
</style>

<!-- Hero Section -->
<section class="live-hero">
    <!-- Slider Slides (Images) -->
    <div class="hero-slider-container">
        <?php foreach ($activeBanners as $index => $banner): ?>
            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                style="background-image: url('<?php echo !empty($banner['image_path']) ? url(htmlspecialchars($banner['image_path'])) : ''; ?>'); <?php echo empty($banner['image_path']) ? 'background: linear-gradient(165deg, #1E293B 0%, #334155 100%);' : ''; ?>"
                data-index="<?php echo $index; ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <div class="live-hero-inner">
        <div class="hero-content-wrapper">
            <?php foreach ($activeBanners as $index => $banner): ?>
            <div class="hero-slide-content <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                <div class="hero-eyebrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                    <?php echo htmlspecialchars($banner['eyebrow_text']); ?>
                </div>
                <h1><?php echo $banner['heading_html']; ?></h1>
                <p class="live-hero-text"><?php echo htmlspecialchars($banner['subheading']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Slider Controls (Dots) -->
    <?php if (count($activeBanners) > 1): ?>
        <div class="slider-dots">
            <?php foreach ($activeBanners as $index => $banner): ?>
                <div class="slider-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>"></div>
            <?php endforeach; ?>
        </div>
    
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const slides = document.querySelectorAll('.hero-slide');
                const contents = document.querySelectorAll('.hero-slide-content');
                const dots = document.querySelectorAll('.slider-dot');
                let currentSlide = 0;
                const totalSlides = slides.length;
                let sliderInterval;
    
                function goToSlide(index) {
                    slides[currentSlide].classList.remove('active');
                    contents[currentSlide].classList.remove('active');
                    if (dots.length) dots[currentSlide].classList.remove('active');
    
                    currentSlide = (index + totalSlides) % totalSlides;
    
                    slides[currentSlide].classList.add('active');
                    contents[currentSlide].classList.add('active');
                    if (dots.length) dots[currentSlide].classList.add('active');
                }
    
                function nextSlide() {
                    goToSlide(currentSlide + 1);
                }
    
                sliderInterval = setInterval(nextSlide, 5000);
    
                dots.forEach(dot => {
                    dot.addEventListener('click', function() {
                        clearInterval(sliderInterval);
                        const index = parseInt(this.getAttribute('data-index'));
                        goToSlide(index);
                        sliderInterval = setInterval(nextSlide, 5000);
                    });
                });
            });
        </script>
    <?php endif; ?>
</section>

<!-- Breadcrumb -->
<nav class="live-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li>Live Projects</li>
    </ul>
</nav>

<!-- Projects Section -->
<section class="live-projects-section">
    <div class="live-projects-container">
        <div class="section-intro">
            <h2>Transformations in Progress</h2>
            <p>Every project represents our dedication to creating efficient, lean manufacturing facilities. Track our current engagements and see the OptiSpace methodology in action.</p>
        </div>
        
        <?php if (!empty($projects)): ?>
        <div class="projects-grid">
            <?php foreach ($projects as $project): 
                $detailUrl = !empty($project['slug']) ? url('live-project-details.php?slug=' . $project['slug']) : url('live-project-details.php?id=' . $project['id']);
            ?>
            <a href="<?php echo $detailUrl; ?>" class="project-card">
                <div class="project-image">
                    <?php if ($project['image_path']): ?>
                    <img src="<?php echo htmlspecialchars($project['image_path']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                    <?php else: ?>
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="1.5">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($project['project_type']): ?>
                    <span class="project-type-badge <?php echo strtolower(str_replace(' ', '-', $project['project_type'])); ?>">
                        <?php echo htmlspecialchars($project['project_type']); ?>
                    </span>
                    <?php endif; ?>
                    
                    <div class="project-progress-bar">
                        <div class="project-progress-fill" style="width: <?php echo (int)$project['progress_percentage']; ?>%;"></div>
                    </div>
                </div>
                
                <div class="project-content">
                    <div class="project-header">
                        <h3 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h3>
                        <?php if ($project['client_name']): ?>
                        <div class="project-client">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 21h18M5 21V7l8-4v18M13 21V11l6-4v14"/>
                            </svg>
                            <?php echo htmlspecialchars($project['client_name']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($project['description']): 
                        $plainDescription = strip_tags($project['description']);
                    ?>
                    <p class="project-description"><?php echo htmlspecialchars(substr($plainDescription, 0, 150)) . (strlen($plainDescription) > 150 ? '...' : ''); ?></p>
                    <?php endif; ?>
                    
                    <div class="project-meta">
                        <?php if ($project['industry']): ?>
                        <div class="project-meta-item">
                            <span class="meta-label">Industry</span>
                            <span class="meta-value"><?php echo htmlspecialchars($project['industry']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($project['location']): ?>
                        <div class="project-meta-item">
                            <span class="meta-label">Location</span>
                            <span class="meta-value"><?php echo htmlspecialchars($project['location']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($project['start_date']): ?>
                        <div class="project-meta-item">
                            <span class="meta-label">Started</span>
                            <span class="meta-value"><?php echo date('M Y', strtotime($project['start_date'])); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($project['expected_completion']): ?>
                        <div class="project-meta-item">
                            <span class="meta-label">Target</span>
                            <span class="meta-value"><?php echo date('M Y', strtotime($project['expected_completion'])); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="project-status">
                        <div class="status-progress">
                            <div class="progress-circle">
                                <?php 
                                $progress = (int)$project['progress_percentage'];
                                $circumference = 2 * 3.14159 * 18;
                                $offset = $circumference - ($progress / 100) * $circumference;
                                ?>
                                <svg viewBox="0 0 44 44">
                                    <circle class="bg" cx="22" cy="22" r="18"/>
                                    <circle class="progress" cx="22" cy="22" r="18" 
                                            stroke-dasharray="<?php echo $circumference; ?>" 
                                            stroke-dashoffset="<?php echo $offset; ?>"/>
                                </svg>
                                <span class="progress-text"><?php echo $progress; ?>%</span>
                            </div>
                            <div class="status-info">
                                <div class="status-phase"><?php echo htmlspecialchars($project['current_phase'] ?? 'In Progress'); ?></div>
                                <div class="status-label">Current Phase</div>
                            </div>
                        </div>
                        
                        <?php if ($project['highlight_1'] || $project['highlight_2'] || $project['highlight_3']): ?>
                        <div class="project-highlights">
                            <?php if ($project['highlight_1']): ?>
                            <span class="highlight-tag">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <?php echo htmlspecialchars(substr($project['highlight_1'], 0, 25)); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
            </svg>
            <h3>No Live Projects Currently</h3>
            <p>Check back soon to see our ongoing factory transformation projects.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<?php $hideFooterCTA = true; ?>
<section class="live-cta-section">
    <div class="live-cta-container">
        <h2>Want Your Project <span>Featured Here?</span></h2>
        <p>Start with a complimentary Pulse Check. We'll visit your facility and show you exactly how we can transform your operations.</p>
        <div class="cta-buttons">
            <a href="<?php echo url('pulse-check.php'); ?>" class="cta-btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                </svg>
                Request Pulse Check
            </a>
            <a href="<?php echo url('portfolio.php'); ?>" class="cta-btn-secondary">
                View Completed Projects
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<?php 
$hideFooterCTA = true;
include 'includes/footer.php'; 
?>
