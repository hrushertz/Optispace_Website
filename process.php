<?php
$currentPage = 'process';
$pageTitle = 'Our Step-Wise Approach | Solutions OptiSpace';
$pageDescription = 'Understanding how we work: From initial Pulse Check to project completion and beyond.';
$pageKeywords = 'LFB process, factory design process, lean factory methodology, OptiSpace process, factory planning phases, manufacturing design methodology, value stream analysis, factory layout design process, implementation support, post-commissioning, factory project phases, pulse check, lean assessment';
include 'includes/header.php';

// Get database connection
require_once 'database/db_config.php';
$conn = getDBConnection();

// Fetch Active Banners
$bannerQuery = "SELECT * FROM banner_settings WHERE page_name = 'process' AND is_active = 1 ORDER BY sort_order ASC, created_at DESC";
$bannerResult = $conn->query($bannerQuery);
$activeBanners = [];
if ($bannerResult && $bannerResult->num_rows > 0) {
    while ($row = $bannerResult->fetch_assoc()) {
        $activeBanners[] = $row;
    }
}

// Fallback
if (empty($activeBanners)) {
    $activeBanners[] = [
        'image_path' => '',
        'eyebrow_text' => 'Proven Methodology',
        'heading_html' => 'The LFB <span>Master Journey</span>',
        'subheading' => 'A systematic four-phase approach that transforms your vision into an optimized, lean factory — built for performance and ready for the future.'
    ];
}
?>

<style>
    /* ========================================
   PROCESS PAGE - MODERN CLEAN DESIGN
   ======================================== */

    :root {
        --process-orange: #e99738;
        --process-orange-light: rgba(233, 151, 56, 0.08);
        --process-blue: #e99738;
        --process-blue-light: rgba(233, 151, 56, 0.08);
        --process-gray: #64748B;
        --process-gray-light: rgba(100, 116, 139, 0.08);
        --process-dark: #1E293B;
        --process-text: #475569;
        --process-border: #E2E8F0;
    }

    /* Hero Section */
    .process-hero {
        background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
        padding: 8rem 0 6rem;
        position: relative;
        overflow: hidden;
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

    /* Overlay */
    .hero-slide::before {
        content: none;
    }

    .process-hero-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        position: relative;
        z-index: 3;
    }

    .process-hero-content {
        position: relative;
        z-index: 3;
        display: grid;
        grid-template-areas: "hero-content";
    }

    /* Slide Content Structure */
    .hero-slide-content {
        grid-area: hero-content;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.4s ease;
    }

    .hero-slide-content.active {
        opacity: 1;
        pointer-events: auto;
        z-index: 2;
    }

    /* Staggered Animations */
    .hero-slide-content .hero-eyebrow,
    .hero-slide-content h1,
    .hero-slide-content .process-hero-text,
    .hero-slide-content .hero-stats {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .hero-slide-content:not(.active) .hero-eyebrow,
    .hero-slide-content:not(.active) h1,
    .hero-slide-content:not(.active) .process-hero-text,
    .hero-slide-content:not(.active) .hero-stats {
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

    .hero-slide-content.active .process-hero-text {
        opacity: 1;
        transform: translateY(0);
        transition-delay: 0.3s;
    }

    .hero-slide-content.active .hero-stats {
        opacity: 1;
        transform: translateY(0);
        transition-delay: 0.4s;
    }

    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(233, 151, 56, 0.15);
        color: #e99738;
        padding: 0.5rem 1rem;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 1.5rem;
    }

    .process-hero h1 {
        font-size: 3.25rem;
        font-weight: 700;
        color: white;
        line-height: 1.15;
        margin-bottom: 1.5rem;
    }

    .process-hero h1 span {
        color: #e99738;
    }

    .process-hero-text {
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
        background: #e99738;
        width: 30px;
        border-radius: 6px;
    }

    .slider-dot:hover {
        background: rgba(255, 255, 255, 0.6);
    }

    /* Breadcrumb */
    .process-breadcrumb {
        background: #F8FAFC;
        padding: 1rem 0;
        border-bottom: 1px solid var(--process-border);
    }

    .process-breadcrumb ul {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        list-style: none;
        display: flex;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .process-breadcrumb a {
        color: var(--process-gray);
        text-decoration: none;
    }

    .process-breadcrumb a:hover {
        color: var(--process-orange);
    }

    .process-breadcrumb li:last-child {
        color: var(--process-dark);
        font-weight: 500;
    }

    .process-breadcrumb li:not(:last-child)::after {
        content: '/';
        margin-left: 0.5rem;
        color: var(--process-border);
    }

    /* Journey Timeline Section */
    .journey-section {
        padding: 6rem 0;
        background: white;
    }

    .journey-container {
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
        color: var(--process-dark);
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .section-header p {
        font-size: 1.15rem;
        color: var(--process-text);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.7;
    }

    /* Timeline Flow */
    .timeline-flow {
        position: relative;
        display: flex;
        justify-content: space-between;
        margin-bottom: 4rem;
    }

    .timeline-flow::before {
        content: '';
        position: absolute;
        top: 40px;
        left: 60px;
        right: 60px;
        height: 2px;
        background: var(--process-orange);
        z-index: 0;
    }

    .timeline-step {
        position: relative;
        z-index: 1;
        flex: 1;
        max-width: 260px;
        text-align: center;
    }

    .timeline-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        background: white;
        border: 2px solid var(--process-border);
        transition: all 0.3s ease;
    }

    .timeline-step:hover .timeline-icon {
        transform: scale(1.1);
    }

    .timeline-step:nth-child(1) .timeline-icon {
        border-color: var(--process-orange);
        box-shadow: none;
    }

    .timeline-step:nth-child(2) .timeline-icon {
        border-color: var(--process-orange);
        box-shadow: none;
    }

    .timeline-step:nth-child(3) .timeline-icon {
        border-color: var(--process-orange);
        box-shadow: none;
    }

    .timeline-step:nth-child(4) .timeline-icon {
        border-color: var(--process-orange);
        box-shadow: none;
    }

    .timeline-icon svg {
        width: 32px;
        height: 32px;
    }

    .timeline-step:nth-child(1) .timeline-icon svg {
        color: var(--process-orange);
    }

    .timeline-step:nth-child(2) .timeline-icon svg {
        color: var(--process-orange);
    }

    .timeline-step:nth-child(3) .timeline-icon svg {
        color: var(--process-orange);
    }

    .timeline-step:nth-child(4) .timeline-icon svg {
        color: var(--process-orange);
    }

    .timeline-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--process-gray);
        margin-bottom: 0.5rem;
    }

    .timeline-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--process-dark);
        margin-bottom: 0.5rem;
    }

    .timeline-duration {
        font-size: 0.85rem;
        color: var(--process-text);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .timeline-duration svg {
        width: 14px;
        height: 14px;
    }

    /* Commitment Card */
    .commitment-card {
        background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
        border: 1px solid var(--process-border);
        border-radius: 16px;
        padding: 3rem;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .commitment-icon {
        width: 64px;
        height: 64px;
        background: var(--process-orange-light);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .commitment-icon svg {
        width: 28px;
        height: 28px;
        color: var(--process-orange);
    }

    .commitment-content h3 {
        font-size: 1.35rem;
        color: var(--process-dark);
        margin-bottom: 0.5rem;
    }

    .commitment-content p {
        font-size: 1rem;
        color: var(--process-text);
        line-height: 1.7;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .process-hero-inner {
            grid-template-columns: 1fr;
            gap: 3rem;
            text-align: center;
        }

        .process-hero-text {
            margin: 0 auto 2rem;
        }

        .hero-stats {
            justify-content: center;
        }

        .timeline-flow {
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }

        .timeline-flow::before {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .process-hero {
            padding: 5rem 0 4rem;
        }

        .process-hero h1 {
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

        .hero-process-preview {
            grid-template-columns: 1fr;
            max-width: 280px;
        }

        .commitment-card {
            flex-direction: column;
            text-align: center;
            padding: 2rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="process-hero">
    <!-- Slider Slides (Images) -->
    <div class="hero-slider-container">
        <?php foreach ($activeBanners as $index => $banner): ?>
            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                style="background-image: url('<?php echo !empty($banner['image_path']) ? htmlspecialchars($banner['image_path']) : ''; ?>'); <?php echo empty($banner['image_path']) ? 'background: linear-gradient(165deg, #1E293B 0%, #334155 100%);' : ''; ?>"
                data-index="<?php echo $index; ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <div class="process-hero-inner">
        <div class="process-hero-content">
            <?php foreach ($activeBanners as $index => $banner): ?>
                <div class="hero-slide-content <?php echo $index === 0 ? 'active' : ''; ?>"
                    data-index="<?php echo $index; ?>">
                    <div class="hero-eyebrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <?php echo htmlspecialchars($banner['eyebrow_text']); ?>
                    </div>
                    <h1><?php echo $banner['heading_html']; ?></h1>
                    <p class="process-hero-text"><?php echo htmlspecialchars($banner['subheading']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="hero-visual">
            <!-- Process Step Cards Removed -->
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
            document.addEventListener('DOMContentLoaded', function () {
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
                    dot.addEventListener('click', function () {
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
<nav class="process-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li>Our Process</li>
    </ul>
</nav>

<!-- Journey Overview -->
<section class="journey-section">
    <div class="journey-container">
        <div class="section-header">
            <h2>Your Journey to Excellence</h2>
            <p>Each phase builds on the previous, ensuring a systematic transformation from concept to commissioning</p>
        </div>

        <div class="timeline-flow">
            <div class="timeline-step">
                <div class="timeline-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div class="timeline-label">Phase 1</div>
                <div class="timeline-title">Engagement & Pulse Check</div>
                <div class="timeline-duration">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>

                </div>
            </div>

            <div class="timeline-step">
                <div class="timeline-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div class="timeline-label">Phase 2</div>
                <div class="timeline-title">Lean Diagnosis</div>
                <div class="timeline-duration">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>

                </div>
            </div>

            <div class="timeline-step">
                <div class="timeline-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                </div>
                <div class="timeline-label">Phase 3</div>
                <div class="timeline-title">Layout Development</div>
                <div class="timeline-duration">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>

                </div>
            </div>

            <div class="timeline-step">
                <div class="timeline-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="timeline-label">Phase 4</div>
                <div class="timeline-title">Project Execution</div>
                <div class="timeline-duration">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>

                </div>
            </div>
        </div>

        <div class="commitment-card">
            <div class="commitment-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="commitment-content">
                <h3>Our Commitment: No Surprises</h3>
                <p>Complete transparency at every step. Each phase has clear deliverables, timelines, and sign-offs.
                    You'll always know what to expect, what we're working on, and what comes next.</p>
            </div>
        </div>
    </div>
</section>

<!-- Phase 1 Detail Section -->
<style>
    /* Phase Detail Sections - Clean Modern Design */
    .phase-section {
        padding: 6rem 0;
    }

    .phase-section:nth-child(odd) {
        background: #FAFBFC;
    }

    .phase-section:nth-child(even) {
        background: white;
    }

    .phase-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .phase-header {
        display: flex;
        align-items: flex-start;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .phase-number-badge {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        flex-shrink: 0;
    }

    .phase-1-section .phase-number-badge,
    .phase-2-section .phase-number-badge,
    .phase-3-section .phase-number-badge,
    .phase-4-section .phase-number-badge {
        background: linear-gradient(135deg, var(--process-orange) 0%, #f5a854 100%);
    }

    .phase-header-content h2 {
        font-size: 2rem;
        color: var(--process-dark);
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .phase-header-content p {
        font-size: 1.1rem;
        color: var(--process-text);
        margin: 0;
        line-height: 1.6;
    }

    .phase-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }

    .phase-card-modern {
        background: white;
        border: 1px solid var(--process-border);
        border-radius: 12px;
        padding: 2rem;
        transition: all 0.3s ease;
    }

    .phase-card-modern:hover {
        /* box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06); */
        box-shadow: none;
        border-color: var(--process-orange);
    }

    .phase-card-modern h3 {
        font-size: 1.25rem;
        color: var(--process-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .phase-card-modern h3 svg {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
    }

    .phase-1-section .phase-card-modern h3 svg,
    .phase-2-section .phase-card-modern h3 svg,
    .phase-3-section .phase-card-modern h3 svg,
    .phase-4-section .phase-card-modern h3 svg {
        color: var(--process-orange);
    }

    .phase-card-modern p {
        color: var(--process-text);
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }

    .check-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .check-list li {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.5rem 0;
        color: var(--process-text);
        font-size: 0.95rem;
    }

    .check-list li svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .phase-1-section .check-list li svg,
    .phase-2-section .check-list li svg,
    .phase-3-section .check-list li svg,
    .phase-4-section .check-list li svg {
        color: var(--process-orange);
    }

    .phase-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--process-border);
        font-size: 0.9rem;
        color: var(--process-text);
        font-weight: 500;
    }

    .phase-meta svg {
        width: 16px;
        height: 16px;
    }

    .phase-1-section .phase-meta {
        color: var(--process-orange);
    }

    .phase-cta {
        margin-top: 3rem;
        background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
        border: 1px solid var(--process-border);
        border-radius: 12px;
        padding: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
    }

    .phase-cta-content h3 {
        font-size: 1.5rem;
        color: var(--process-dark);
        margin-bottom: 0.5rem;
    }

    .phase-cta-content p {
        color: var(--process-text);
        margin: 0;
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
        white-space: nowrap;
    }

    .btn-primary-modern {
        background: var(--process-orange);
        color: white;
    }

    .btn-primary-modern:hover {
        background: #d4851c;
        transform: translateY(-1px);
        /* box-shadow: 0 4px 12px rgba(233, 148, 49, 0.3); */
        box-shadow: none;
    }

    .btn-secondary-modern {
        background: white;
        color: var(--process-dark);
        border: 1px solid var(--process-border);
    }

    .btn-secondary-modern:hover {
        border-color: var(--process-orange);
        color: var(--process-orange);
    }

    .btn-modern svg {
        width: 18px;
        height: 18px;
    }

    /* Steps Grid */
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-top: 3rem;
    }

    .step-card {
        background: white;
        border: 1px solid var(--process-border);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .step-card:hover {
        /* box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06); */
        box-shadow: none;
        transform: translateY(-2px);
        border-color: var(--process-orange);
    }

    .step-num {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 700;
        margin: 0 auto 1rem;
        color: white;
    }

    .phase-2-section .step-num {
        background: var(--process-orange);
    }

    .step-card h4 {
        font-size: 1rem;
        color: var(--process-dark);
        margin-bottom: 0.5rem;
    }

    .step-card p {
        font-size: 0.9rem;
        color: var(--process-text);
        line-height: 1.6;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .phase-header {
            flex-direction: column;
            gap: 1rem;
        }

        .phase-grid {
            grid-template-columns: 1fr;
        }

        .steps-grid {
            grid-template-columns: 1fr;
        }

        .phase-cta {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<!-- Phase 1: Engagement & Pulse Check -->
<section class="phase-section phase-1-section">
    <div class="phase-container">
        <div class="phase-header">
            <div class="phase-number-badge">01</div>
            <div class="phase-header-content">
                <h2>Engagement & The "Pulse Check"</h2>
                <p>Understanding your needs and confirming mutual fit through our complimentary consultation</p>
            </div>
        </div>

        <div class="phase-grid">
            <div class="phase-card-modern">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    What is a Pulse Check?
                </h3>
                <p>A complimentary consultation visit where we explore your facility and understand your unique
                    challenges.</p>
                <ul class="check-list">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Tour your existing or proposed facility
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Understand your products and processes
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Learn about your challenges and goals
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Identify preliminary opportunities
                    </li>
                </ul>
                <div class="phase-meta">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                    Duration: 2-4 hours • Complimentary
                </div>
            </div>

            <div class="phase-card-modern">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Techno-Commercial Proposal
                </h3>
                <p>Following the Pulse Check, we submit a detailed proposal tailored to your specific needs.</p>
                <ul class="check-list">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Scope of work and deliverables
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Phased approach and timeline
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Investment and payment terms
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Success criteria and metrics
                    </li>
                </ul>
            </div>
        </div>

        <div class="phase-cta">
            <div class="phase-cta-content">
                <h3>Ready to Get Started?</h3>
                <p>The Pulse Check is complimentary and risk-free — explore how we can help.</p>
            </div>
            <a href="<?php echo url('pulse-check.php'); ?>" class="btn-modern btn-primary-modern">
                Request Pulse Check
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Phase 2: Lean Diagnosis -->
<section class="phase-section phase-2-section">
    <div class="phase-container">
        <div class="phase-header">
            <div class="phase-number-badge">02</div>
            <div class="phase-header-content">
                <h2>Lean Diagnosis & Opportunity Identification</h2>
                <p>Building the factual foundation for all LFB layout and building decisions</p>
            </div>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-num">1</div>
                <h4>Data Collection</h4>
                <p>Product portfolio, process flows, equipment specs, volumes, and constraints.</p>
            </div>
            <div class="step-card">
                <div class="step-num">2</div>
                <h4>Process Mapping</h4>
                <p>Material and information flows across all product families and stages.</p>
            </div>
            <div class="step-card">
                <div class="step-num">3</div>
                <h4>Spaghetti Analysis</h4>
                <p>Visual waste identification through mapping flow paths and operator movement.</p>
            </div>
            <div class="step-card">
                <div class="step-num">4</div>
                <h4>Shop-Floor Study</h4>
                <p>Time studies, bottleneck identification, and ergonomics evaluation.</p>
            </div>
            <div class="step-card">
                <div class="step-num">5</div>
                <h4>Waste Quantification</h4>
                <p>Measuring and categorizing the seven types of waste.</p>
            </div>
            <div class="step-card">
                <div class="step-num">6</div>
                <h4>Documentation</h4>
                <p>As-is layout, value stream mapping, and improvement potential.</p>
            </div>
        </div>

        <div class="deliverable-card">
            <div class="deliverable-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="deliverable-content">
                <h3>Deliverable: Comprehensive Diagnostic Report</h3>
                <p>A detailed report documenting current state, identifying opportunities, and quantifying improvement
                    potential — the foundation for Phase 3 design work.</p>
            </div>
        </div>
    </div>
</section>

<style>
    .deliverable-card {
        margin-top: 3rem;
        background: var(--process-orange-light);
        border: 1px solid rgba(233, 151, 56, 0.2);
        border-radius: 12px;
        padding: 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .deliverable-icon {
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

    .deliverable-icon svg {
        width: 28px;
        height: 28px;
        color: var(--process-orange);
    }

    .deliverable-content h3 {
        font-size: 1.25rem;
        color: var(--process-dark);
        margin-bottom: 0.5rem;
    }

    .deliverable-content p {
        color: var(--process-text);
        margin: 0;
        line-height: 1.6;
    }
</style>

<!-- Phase 3: Layout Development -->
<section class="phase-section phase-3-section">
    <div class="phase-container">
        <div class="phase-header">
            <div class="phase-number-badge">03</div>
            <div class="phase-header-content">
                <h2>Lean Layout Development (2D CAD)</h2>
                <p>Designing your optimized future state where flow leads and the building follows</p>
            </div>
        </div>

        <div class="phase-grid">
            <div class="phase-card-modern">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                    The Design Process
                </h3>
                <p>Iterative refinement using LFB principles so that flow leads and the building follows.</p>
                <ul class="check-list">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Multiple layout alternatives based on lean principles
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Assessment against flow, space, flexibility, and cost
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Detailed development and stakeholder validation
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Final 2D CAD layout with dimensions
                    </li>
                </ul>
            </div>

            <div class="phase-card-modern">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Key Design Principles
                </h3>
                <p>Every layout incorporates proven lean manufacturing principles.</p>
                <ul class="check-list">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Continuous flow with minimal transportation
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Visual management and ergonomic design
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Flexible configuration for volume variation
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Future expansion capability built-in
                    </li>
                </ul>
            </div>
        </div>

        <div class="layout-types">
            <div class="layout-type greenfield">
                <div class="layout-type-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h4>For Greenfield</h4>
                <p>Production footprint, support sizing, building dimensions, column grid, loading docks</p>
            </div>
            <div class="layout-type brownfield">
                <div class="layout-type-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>
                <h4>For Brownfield</h4>
                <p>Equipment relocations, new positions, aisle widths, storage locations, utility requirements</p>
            </div>
            <div class="layout-type documentation">
                <div class="layout-type-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h4>Deliverables</h4>
                <p>2D CAD layouts, equipment list, area calculations, flow diagrams, implementation notes</p>
            </div>
        </div>
    </div>
</section>

<style>
    .layout-types {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-top: 3rem;
    }

    .layout-type {
        background: white;
        border: 1px solid var(--process-border);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .layout-type:hover {
        /* box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06); */
        box-shadow: none;
        transform: translateY(-2px);
        border-color: var(--process-orange);
    }

    .layout-type-icon {
        width: 48px;
        height: 48px;
        background: var(--process-orange-light);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .layout-type-icon svg {
        width: 24px;
        height: 24px;
        color: var(--process-orange);
    }

    .layout-type h4 {
        font-size: 1.1rem;
        color: var(--process-dark);
        margin-bottom: 0.5rem;
    }

    .layout-type p {
        font-size: 0.9rem;
        color: var(--process-text);
        line-height: 1.6;
        margin: 0;
    }

    @media (max-width: 768px) {
        .layout-types {
            grid-template-columns: 1fr;
        }

        .deliverable-card {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<!-- Phase 4: Project Execution -->
<section class="phase-section phase-4-section">
    <div class="phase-container">
        <div class="phase-header">
            <div class="phase-number-badge">04</div>
            <div class="phase-header-content">
                <h2>Project Execution</h2>
                <p>Bringing the design to life with complete execution support</p>
            </div>
        </div>

        <div class="execution-grid">
            <div class="execution-card greenfield-card">
                <div class="execution-header">
                    <div class="execution-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3>Greenfield Execution</h3>
                </div>
                <p>For new factory construction:</p>
                <ul class="check-list">
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>Complete architectural design</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>Structural & MEP engineering</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>3D visualization & walkthroughs</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>Statutory approvals & compliance</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>Construction supervision</li>
                </ul>
                <a href="<?php echo url('services/greenfield.php'); ?>" class="btn-modern btn-primary-modern">
                    Learn More
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <div class="execution-card brownfield-card">
                <div class="execution-header">
                    <div class="execution-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </div>
                    <h3>Brownfield Execution</h3>
                </div>
                <p>For existing factory optimization:</p>
                <ul class="check-list">
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>Workstation & work cell design</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>Material handling systems</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>Implementation planning</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>Installation supervision</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>Training & standardization</li>
                </ul>
                <a href="<?php echo url('services/brownfield.php'); ?>" class="btn-modern btn-secondary-modern">
                    Learn More
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .execution-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }

    .execution-card {
        background: white;
        border: 1px solid var(--process-border);
        border-radius: 16px;
        padding: 2rem;
        transition: all 0.3s ease;
    }

    .execution-card:hover {
        /* box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08); */
        box-shadow: none;
        border-color: var(--process-orange);
    }

    .greenfield-card {
        border-top: 4px solid var(--process-orange);
    }

    .brownfield-card {
        border-top: 4px solid var(--process-orange);
    }

    .execution-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .execution-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .greenfield-card .execution-icon {
        background: var(--process-orange-light);
    }

    .brownfield-card .execution-icon {
        background: var(--process-orange-light);
    }

    .execution-icon svg {
        width: 24px;
        height: 24px;
    }

    .greenfield-card .execution-icon svg {
        color: var(--process-orange);
    }

    .brownfield-card .execution-icon svg {
        color: var(--process-orange);
    }

    .execution-card h3 {
        font-size: 1.35rem;
        color: var(--process-dark);
        margin: 0;
    }

    .execution-card>p {
        color: var(--process-text);
        margin-bottom: 1rem;
    }

    .execution-card .check-list {
        margin-bottom: 1.5rem;
    }

    .greenfield-card .check-list li svg {
        color: var(--process-orange);
    }

    .brownfield-card .check-list li svg {
        color: var(--process-orange);
    }

    @media (max-width: 768px) {
        .execution-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Post-Commissioning Support -->
<section class="support-section">
    <div class="phase-container">
        <div class="section-header" style="margin-bottom: 3rem;">
            <h2>Post-Commissioning Support</h2>
            <p>Ensuring your LFB-designed factory performs as intended in daily operations</p>
        </div>

        <div class="support-grid">
            <div class="support-item">
                <div class="support-item-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <div class="support-item-content">
                    <h4>Factory Shifting</h4>
                    <p>Assistance during the critical transition from old to new facility or layout.</p>
                </div>
            </div>
            <div class="support-item">
                <div class="support-item-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div class="support-item-content">
                    <h4>Visual Factory</h4>
                    <p>Deployment of visual management systems for self-explaining operations.</p>
                </div>
            </div>
            <div class="support-item">
                <div class="support-item-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div class="support-item-content">
                    <h4>Production Planning</h4>
                    <p>Guidance on establishing effective production planning and control systems.</p>
                </div>
            </div>
            <div class="support-item">
                <div class="support-item-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <div class="support-item-content">
                    <h4>Supply Chain</h4>
                    <p>Optimization of supply chain to align with your new operational flow.</p>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 2.5rem;">
            <a href="<?php echo url('services/post-commissioning.php'); ?>" class="btn-modern btn-secondary-modern">
                Learn About Post-Commissioning
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</section>

<style>
    .support-section {
        padding: 6rem 0;
        background: #F8FAFC;
    }

    .support-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .support-item {
        background: white;
        border: 1px solid var(--process-border);
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .support-item:hover {
        /* box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06); */
        box-shadow: none;
        border-color: var(--process-orange);
    }

    .support-item-icon {
        width: 48px;
        height: 48px;
        background: var(--process-orange-light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .support-item-icon svg {
        width: 24px;
        height: 24px;
        color: var(--process-orange);
    }

    .support-item-content h4 {
        font-size: 1.1rem;
        color: var(--process-dark);
        margin-bottom: 0.35rem;
    }

    .support-item-content p {
        font-size: 0.9rem;
        color: var(--process-text);
        line-height: 1.6;
        margin: 0;
    }

    @media (max-width: 768px) {
        .support-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Final CTA -->
<section class="final-cta">
    <div class="phase-container">
        <div class="cta-content">
            <h2>Start Your Journey to an Optimized Factory</h2>
            <p>Begin with a complimentary LFB Pulse Check. We'll assess your facility and show you the step-by-step
                process to transform your operations.</p>
            <div class="cta-buttons">
                <a href="<?php echo url('pulse-check.php'); ?>" class="btn-modern btn-primary-modern btn-large">
                    Request Your Pulse Check
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
                <a href="<?php echo url('services/greenfield.php'); ?>" class="btn-modern btn-outline-light">
                    Explore Our Services
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .final-cta {
        padding: 6rem 0;
        background: #2f3030;
    }

    .cta-content {
        text-align: center;
        max-width: 700px;
        margin: 0 auto;
    }

    .cta-content h2 {
        font-size: 2.5rem;
        color: white;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .cta-content p {
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

    .btn-large {
        padding: 1rem 2rem !important;
        font-size: 1rem !important;
    }

    .btn-outline-light {
        background: transparent;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 1rem 2rem;
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
    }

    @media (max-width: 768px) {
        .cta-content h2 {
            font-size: 2rem;
        }

        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

<?php
$hideFooterCTA = true;
include 'includes/footer.php';
?>