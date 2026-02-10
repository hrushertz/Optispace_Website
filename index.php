<?php
$currentPage = 'home';
$pageTitle = 'Lean Factory Building (LFB) Architecture for Future-Ready Plants | Solutions OptiSpace';
$pageDescription = 'Cut internal travel, energy costs, and WIP before construction begins. Design the process first, then the building—with OptiSpace LFB Architecture.';
$pageKeywords = 'lean factory building, LFB architecture, factory design, lean manufacturing, manufacturing plant design, process-first design, factory optimization, industrial architecture, greenfield factory, brownfield factory, OptiSpace, lean consultants India, manufacturing excellence, factory layout, value stream mapping, inside-out design, future-ready plants';
include 'includes/header.php';

// Fetch active client testimonial videos
require_once 'database/db_config.php';
$conn = getDBConnection();
$clientVideosQuery = "SELECT id, title, description, youtube_video_url 
                      FROM client_videos 
                      WHERE is_active = 1
                      ORDER BY sort_order ASC";
$clientVideosResult = $conn->query($clientVideosQuery);
$clientVideos = [];
if ($clientVideosResult) {
    while ($row = $clientVideosResult->fetch_assoc()) {
        $clientVideos[] = $row;
    }
}


// Fetch all active banners
$activeBanners = [];
$bannerQuery = "SELECT * FROM banner_settings WHERE page_name = 'home' AND is_active = 1 ORDER BY sort_order ASC, created_at DESC";
$bannerResult = $conn->query($bannerQuery);

if ($bannerResult && $bannerResult->num_rows > 0) {
    while ($row = $bannerResult->fetch_assoc()) {
        $activeBanners[] = $row;
    }
} else {
    // Fallback if DB is empty
    $activeBanners[] = [
        'image_path' => '', // Will fallback to default in CSS if empty
        'eyebrow_text' => 'Factory Design Experts',
        'heading_html' => 'Lean Factory Building for <span>Future‑Ready</span> Plants',
        'subheading' => 'Design the process first, then the building—cut internal travel, energy costs, and WIP before construction begins. Transform your manufacturing operations with our inside-out approach.'
    ];
}

// Fetch live projects for marquee (Vertical in Hero)
$marqueeQuery = "SELECT * FROM live_projects
                 WHERE is_active = 1 AND is_featured = 1
                 ORDER BY sort_order ASC, created_at DESC
                 LIMIT 6";
$marqueeResult = $conn->query($marqueeQuery);
$marqueeProjects = [];
if ($marqueeResult) {
    while ($row = $marqueeResult->fetch_assoc()) {
        $marqueeProjects[] = $row;
    }
}

// Helper function to extract YouTube video ID from URL or return as-is if already an ID
function getYouTubeVideoId($input)
{
    if (empty($input))
        return '';

    // If it's already a video ID (11 characters, alphanumeric with - and _)
    if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
        return $input;
    }

    // Extract from various YouTube URL formats
    $patterns = [
        '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
        '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
        '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
        '/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/'
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $input, $matches)) {
            return $matches[1];
        }
    }

    return '';
}
?>

<style>
    /* ========================================
   HOME PAGE - MODERN CLEAN DESIGN
   ======================================== */

    :root {
        --home-orange: #e99738;
        --home-orange-light: rgba(233, 151, 56, 0.08);
        /* Adjusted RGB for #e99738 */
        --home-blue: #0284c7;
        --home-blue-light: rgba(2, 132, 199, 0.08);
        --home-green: #059669;
        --home-gray: #64748B;
        --home-gray-light: rgba(100, 116, 139, 0.08);
        --home-dark: #1E293B;
        --home-text: #475569;
        --home-border: #E2E8F0;
    }

    /* Hero Section */
    .home-hero {
        padding: 12rem 0 12rem;
        position: relative;
        overflow: hidden;
        background: #1E293B;
        /* Fallback color */
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

    /* Dark Overlay for better text readability */
    .hero-slide::before {
        content: none;
        /* Overlay removed as requested */
    }

    /* Additional Graphic Elements over Slides */
    .home-hero::before {
        content: none;
        /* Gradient removed */
    }

    .home-hero::before {
        content: none;
        /* Gradient removed */
    }

    .home-hero::after {
        content: none;
        /* Gradient removed */
    }

    .home-hero-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        position: relative;
        z-index: 3;
        /* Above slides */
    }

    .home-hero-content {
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
        transition: opacity 1.0s ease-in-out;
        /* Smoother exit */
    }

    .hero-slide-content.active {
        opacity: 1;
        pointer-events: auto;
        z-index: 2;
    }

    /* Staggered Child Animations */
    .hero-slide-content .hero-eyebrow,
    .hero-slide-content h1,
    .hero-slide-content .home-hero-text,
    .hero-slide-content .hero-cta-group {
        opacity: 0;
        transform: translateY(20px);
        transition: all 1.5s cubic-bezier(0.2, 1, 0.3, 1);
        /* Slower, smoother entry */
    }

    /* Reset state (outgoing) */
    .hero-slide-content:not(.active) .hero-eyebrow,
    .hero-slide-content:not(.active) h1,
    .hero-slide-content:not(.active) .home-hero-text,
    .hero-slide-content:not(.active) .hero-cta-group {
        transform: translateY(-10px);
        opacity: 0;
        transition: all 0.8s ease-in-out;
        /* Slower, smoother exit */
    }

    /* Active State Delays */
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

    .hero-slide-content.active .home-hero-text {
        opacity: 1;
        transform: translateY(0);
        transition-delay: 0.3s;
    }

    .hero-slide-content.active .hero-cta-group {
        opacity: 1;
        transform: translateY(0);
        transition-delay: 0.4s;
    }

    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(233, 151, 56, 0.15);
        /* Updated for #e99738 */
        color: #e99738;
        /* Updated */
        padding: 0.5rem 1rem;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 1.5rem;
        /* Animation handled by slide active state */
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .home-hero h1 {
        font-size: 3.25rem;
        font-weight: 700;
        color: white;
        line-height: 1.15;
        margin-bottom: 1.5rem;
        /* Animation handled by slide active state */
    }

    .home-hero h1 span {
        color: #e99738;
        /* Updated */
    }

    .home-hero-text {
        font-size: 1.2rem;
        line-height: 1.7;
        color: #ffffff;
        margin-bottom: 2.5rem;
        max-width: 500px;
        /* Animation handled by slide active state */
    }

    .hero-cta-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 3rem;
        /* Animation handled by slide active state */
    }

    .hero-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #e99738 0%, #f5a854 100%);
        /* Updated start color */
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        /* Shadow removed */
    }

    .hero-btn-primary:hover {
        transform: translateY(-2px);
        /* Shadow removed */
        color: white;
        justify-content: center;
    }

    .hero-btn-secondary {
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

    .hero-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
    }

    .hero-stats {
        display: flex;
        gap: 3rem;
        animation: fadeInUp 0.6s ease 0.4s both;
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
        animation: fadeInRight 0.8s ease 0.3s both;
    }

    .hero-feature-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        max-width: 420px;
    }

    .hero-feature-card {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 1.5rem;
        cursor: default;
    }

    .feature-card-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #e99738 0%, #f5a854 100%);
        /* Updated */
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .feature-card-icon svg {
        width: 22px;
        height: 22px;
        color: white;
    }

    .hero-feature-card:nth-child(2) .feature-card-icon {
        background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%);
    }

    .hero-feature-card:nth-child(3) .feature-card-icon {
        background: linear-gradient(135deg, #10B981 0%, #34d399 100%);
    }

    .hero-feature-card:nth-child(4) .feature-card-icon {
        background: linear-gradient(135deg, #64748B 0%, #94a3b8 100%);
    }

    .feature-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: white;
        margin-bottom: 0.25rem;
    }

    .feature-card-text {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        line-height: 1.4;
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
        /* Updated */
        width: 30px;
        border-radius: 6px;
    }

    .slider-dot:hover {
        background: rgba(255, 255, 255, 0.6);
    }

    @media (max-width: 1024px) {
        .home-hero-inner {
            grid-template-columns: 1fr;
            gap: 3rem;
            text-align: center;
        }

        .home-hero-text {
            margin: 0 auto 2.5rem;
        }

        .hero-cta-group {
            justify-content: center;
        }

        .hero-stats {
            justify-content: center;
        }

        .hero-visual {
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .home-hero {
            padding: 7rem 0 4rem;
        }

        .home-hero h1 {
            font-size: 2.25rem;
        }

        .hero-stats {
            flex-direction: row;
            flex-wrap: wrap;
            gap: 1.5rem;
            justify-content: center;
        }

        .hero-stat {
            text-align: center;
            flex: 0 0 auto;
        }

        .hero-feature-grid {
            display: none;
        }

        /* Vertical Marquee on Mobile */
        .vertical-marquee-container {
            display: block;
            /* Show on mobile */
            max-width: 450px;
            margin: 2rem auto 0 auto;
            /* Center with top margin */
            width: 90%;
            margin-right: auto;
            margin-bottom: 0;
            margin-left: auto;
        }

        .hero-visual {
            display: flex;
            justify-content: center;
            width: 100%;
        }
    }

    /* Vertical Marquee Styles */
    .vertical-marquee-container {
        /* Footer grey #2f3030, 70% opacity */
        background: rgba(47, 48, 48, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 1rem;
        /* Reduced padding */
        width: 100%;
        max-width: 450px;
        /* Increased from 400px */
        backdrop-filter: blur(16px);
        overflow: hidden;
        position: relative;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        margin-left: auto;
        /* Adjusted margins */
        margin-right: -2rem;
        margin-bottom: -3rem;
    }

    .vertical-marquee-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .vertical-marquee-title {
        color: white;
        font-weight: 700;
        font-size: 1.05rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .pulsing-dot {
        width: 8px;
        height: 8px;
        background-color: #e99738;
        border-radius: 50%;
        position: relative;
    }

    .pulsing-dot::after {
        content: '';
        position: absolute;
        top: -4px;
        left: -4px;
        right: -4px;
        bottom: -4px;
        border-radius: 50%;
        border: 1px solid #e99738;
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0% {
            transform: scale(0.8);
            opacity: 1;
        }

        100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    .vertical-marquee-viewport {
        height: 450px;
        /* Increased from 350px/380px */
        overflow: hidden;
        position: relative;
        mask-image: linear-gradient(to bottom, transparent, black 5%, black 95%, transparent);
    }

    .vertical-marquee-content {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        animation: scroll-vertical 25s linear infinite;
    }

    .vertical-marquee-viewport:hover .vertical-marquee-content {
        animation-play-state: paused;
    }

    .vm-item {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.05);
        padding: 0.85rem;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
    }

    .vm-item:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateX(5px);
        border-color: rgba(233, 151, 56, 0.4);
    }

    .vm-item-image {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        object-fit: cover;
        background-color: rgba(255, 255, 255, 0.05);
        flex-shrink: 0;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .vm-item-content {
        flex: 1;
        min-width: 0;
    }

    .vm-item-title {
        color: white;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.4rem;
        display: block;
        line-height: 1.3;
    }

    .vm-item-meta {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .progress-bar-mini {
        width: 60px;
        height: 4px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        overflow: hidden;
    }

    .progress-fill-mini {
        height: 100%;
        background: #e99738;
        border-radius: 2px;
    }

    @keyframes scroll-vertical {
        0% {
            transform: translateY(0);
        }

        100% {
            transform: translateY(-50%);
        }
    }

    /* Mobile Responsive Overrides for Vertical Marquee 
       Placed here to ensure they override base styles */
    @media (max-width: 768px) {
        .vertical-marquee-container {
            display: block !important;
            margin: 2rem auto !important;
            width: 90% !important;
            max-width: 450px !important;
            /* Reset any specific positioning margins */
            margin-right: auto !important;
            margin-bottom: 2rem !important;
            margin-left: auto !important;
        }

        .hero-visual {
            display: flex;
            justify-content: center;
            width: 100%;
            padding-bottom: 2rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="home-hero">
    <!-- Slider Slides (Images) -->
    <div class="hero-slider-container">
        <?php foreach ($activeBanners as $index => $banner): ?>
            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                style="background-image: url('<?php echo !empty($banner['image_path']) ? htmlspecialchars($banner['image_path']) : 'assets/images/banners/home-hero.jpg'; ?>');"
                data-index="<?php echo $index; ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Content (Text, changes with slides if we want, or stays static? Requirement implied text also changes) 
         If text changes, we need separate content blocks that fade.
    -->
    <div class="home-hero-inner">
        <div class="home-hero-content">
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
                    <h1><?php echo $banner['heading_html']; // Allow HTML for span tags ?></h1>
                    <p class="home-hero-text"><?php echo htmlspecialchars($banner['subheading']); ?></p>

                    <div class="hero-cta-group">
                        <a href="<?php echo url('pulse-check.php'); ?>" class="hero-btn-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                            </svg>
                            Request Pulse Check
                        </a>
                        <a href="#how-lfb-works" class="hero-btn-secondary">
                            Learn More
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-value">250+</div>
                    <div class="hero-stat-label">Factories Transformed</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">20+</div>
                    <div class="hero-stat-label">Years Experience</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">75+</div>
                    <div class="hero-stat-label">Industrial Segments</div>
                </div>
            </div>
        </div>


        <div class="hero-visual">
            <?php if (!empty($marqueeProjects)): ?>
                <div class="vertical-marquee-container">
                    <div class="vertical-marquee-header">
                        <div class="vertical-marquee-title">
                            <span class="pulsing-dot"></span>
                            Live Projects
                        </div>
                        <a href="<?php echo url('live-projects.php'); ?>"
                            style="color: rgba(255,255,255,0.6); font-size: 0.8rem; text-decoration: none;">View All</a>
                    </div>

                    <div class="vertical-marquee-viewport">
                        <div class="vertical-marquee-content">
                            <?php
                            // Duplicate for infinite scroll
                            $displayProjects = array_merge($marqueeProjects, $marqueeProjects);
                            // Ensure enough items for scroll if few
                            if (count($displayProjects) < 6) {
                                $displayProjects = array_merge($displayProjects, $marqueeProjects);
                            }

                            foreach ($displayProjects as $project):
                                $projectUrl = !empty($project['slug']) ? url('live-project-details.php?slug=' . $project['slug']) : url('live-project-details.php?id=' . $project['id']);
                                ?>
                                <a href="<?php echo $projectUrl; ?>" class="vm-item">
                                    <?php if (!empty($project['image_path'])): ?>
                                        <img src="<?php echo htmlspecialchars($project['image_path']); ?>"
                                            alt="<?php echo htmlspecialchars($project['title']); ?>" class="vm-item-image">
                                    <?php else: ?>
                                        <!-- Fallback generic icon if no image -->
                                        <div class="vm-item-image"
                                            style="display: flex; align-items: center; justify-content: center; background: rgba(233, 151, 56, 0.1);">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e99738"
                                                stroke-width="2">
                                                <path d="M3 21h18M5 21V7l8-4 8 4v14M17 21v-8.5a2.5 2.5 0 0 0-5 0V21" />
                                            </svg>
                                        </div>
                                    <?php endif; ?>

                                    <div class="vm-item-content">
                                        <span class="vm-item-title"><?php echo htmlspecialchars($project['title']); ?></span>
                                        <div class="vm-item-meta">
                                            <span><?php echo htmlspecialchars($project['location'] ?? 'India'); ?></span>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <div class="progress-bar-mini">
                                                    <div class="progress-fill-mini"
                                                        style="width: <?php echo (int) $project['progress_percentage']; ?>%;">
                                                    </div>
                                                </div>
                                                <span><?php echo (int) $project['progress_percentage']; ?>%</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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
                    // Remove active class from current
                    slides[currentSlide].classList.remove('active');
                    contents[currentSlide].classList.remove('active');
                    if (dots.length) dots[currentSlide].classList.remove('active');

                    // Update index
                    currentSlide = (index + totalSlides) % totalSlides;

                    // Add active class to new
                    slides[currentSlide].classList.add('active');
                    contents[currentSlide].classList.add('active');
                    if (dots.length) dots[currentSlide].classList.add('active');
                }

                function nextSlide() {
                    goToSlide(currentSlide + 1);
                }

                // Set interval (7000ms)
                sliderInterval = setInterval(nextSlide, 7000);

                // Dot Click Events
                dots.forEach(dot => {
                    dot.addEventListener('click', function () {
                        clearInterval(sliderInterval);
                        const index = parseInt(this.getAttribute('data-index'));
                        goToSlide(index);
                        sliderInterval = setInterval(nextSlide, 7000);
                    });
                });
            });
        </script>
    <?php endif; ?>




    <!-- Stories Marquee -->
    <?php
    // Fetch 'Stories' category ID
    $storiesCategoryQuery = "SELECT id FROM blog_categories WHERE name = 'Stories' LIMIT 1";
    $storiesCategoryResult = $conn->query($storiesCategoryQuery);
    $storiesCategory = $storiesCategoryResult ? $storiesCategoryResult->fetch_assoc() : null;

    $storiesBlogs = [];
    if ($storiesCategory) {
        $storiesQuery = "SELECT title, slug FROM blogs
                     WHERE category_id = " . $storiesCategory['id'] . "
                     AND is_published = 1
                     ORDER BY published_at DESC
                     LIMIT 5";
        $storiesResult = $conn->query($storiesQuery);
        if ($storiesResult) {
            while ($row = $storiesResult->fetch_assoc()) {
                $storiesBlogs[] = $row;
            }
        }
    }
    ?>

    <div class="marquees-wrapper"
        style="position: absolute; bottom: 0; left: 0; width: 100%; z-index: 20; display: flex; flex-direction: column;">


        <?php if (!empty($storiesBlogs)): ?>
            <div class="stories-marquee-wrapper"
                style="background: #2f3030; padding: 0.5rem 0; display: flex; align-items: center;">

                <!-- Label -->
                <div class="lp-tag"
                    style="background: rgba(255,255,255,0.1); color: white; padding: 0.3rem 0.8rem; margin-left: 1rem; border-radius: 50px; font-weight: 700; white-space: nowrap; font-size: 0.8rem; display: flex; align-items: center; gap: 0.5rem; z-index: 5;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    Stories
                </div>

                <div
                    style="overflow: hidden; flex: 1; mask-image: linear-gradient(to right, transparent, black 2%, black 98%, transparent);">
                    <div class="lp-marquee stories-marquee"
                        style="display: flex; gap: 3rem; animation: scroll-left-reverse 40s linear infinite; white-space: nowrap; padding-left: 2rem;">
                        <?php
                        // Repeat stories to ensure they span across the screen for the marquee effect
                        $displayStories = $storiesBlogs;
                        while (count($displayStories) < 12) {
                            $displayStories = array_merge($displayStories, $storiesBlogs);
                        }

                        foreach ($displayStories as $story):
                            ?>
                            <a href="<?php echo url('blog/article.php?slug=' . urlencode($story['slug'])); ?>"
                                class="story-marquee-item"
                                style="display: inline-flex; align-items: center; gap: 0.5rem; color: rgba(255, 255, 255, 0.9); font-weight: 500; font-size: 0.95rem; cursor: pointer; text-decoration: none; transition: all 0.3s ease;">
                                <span><?php echo htmlspecialchars($story['title']); ?></span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" style="opacity: 0.5;">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <style>
            @keyframes scroll-left {
                0% {
                    transform: translateX(0);
                }

                100% {
                    transform: translateX(-50%);
                }
            }

            @keyframes scroll-left-reverse {
                0% {
                    transform: translateX(-50%);
                }

                100% {
                    transform: translateX(0);
                }
            }

            .lp-marquee {
                animation-play-state: running;
            }

            .lp-marquee:hover,
            .stories-marquee:hover {
                animation-play-state: paused !important;
            }

            .lp-marquee-wrapper a:hover .lp-marquee-item {
                opacity: 0.8;
            }

            .story-marquee-item:hover {
                color: #E99431 !important;
            }

            .story-marquee-item:hover svg {
                opacity: 1 !important;
                transform: translateX(3px);
                transition: transform 0.3s ease;
            }
        </style>
    </div>
</section>

<!-- Key Benefits Section -->
<section class="benefits-section" style="padding: 5rem 0 3rem; background: #f8f9fa;">
    <style>
        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            max-width: 1100px;
            margin: 0 auto;
        }

        .benefit-card {
            text-align: center;
            padding: 2.5rem 2rem;
            background: white;
            border-radius: 12px;
            border: 1px solid var(--home-border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .benefit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--home-orange) 0%, #f5a854 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .benefit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        .benefit-card:hover::before {
            transform: scaleX(1);
        }

        .benefit-icon {
            width: 64px;
            height: 64px;
            background: var(--home-orange-light);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            transition: all 0.3s ease;
        }

        .benefit-card:hover .benefit-icon {
            background: var(--home-orange);
            transform: scale(1.05);
        }

        .benefit-icon svg {
            width: 28px;
            height: 28px;
            color: var(--home-orange);
            transition: color 0.3s ease;
        }

        .benefit-card:hover .benefit-icon svg {
            color: white;
        }

        .benefit-card:nth-child(2) .benefit-icon {
            background: var(--home-orange-light);
        }

        .benefit-card:nth-child(2) .benefit-icon svg {
            color: var(--home-orange);
        }

        .benefit-card:nth-child(2):hover .benefit-icon {
            background: var(--home-orange);
        }

        .benefit-card:nth-child(2):hover .benefit-icon svg {
            color: white;
        }

        .benefit-card:nth-child(3) .benefit-icon {
            background: var(--home-orange-light);
        }

        .benefit-card:nth-child(3) .benefit-icon svg {
            color: var(--home-orange);
        }

        .benefit-card:nth-child(3):hover .benefit-icon {
            background: var(--home-orange);
        }

        .benefit-card:nth-child(3):hover .benefit-icon svg {
            color: white;
        }

        .benefit-label {
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--home-orange);
            margin-bottom: 0.75rem;
        }

        .benefit-card:nth-child(2) .benefit-label {
            color: var(--home-orange);
        }

        .benefit-card:nth-child(3) .benefit-label {
            color: var(--home-orange);
        }

        .benefit-text {
            font-size: 1rem;
            color: var(--home-text);
            line-height: 1.6;
            margin: 0;
        }

        @media (max-width: 768px) {
            .benefits-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <div class="container">
        <div class="benefits-grid ">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                        <line x1="9" y1="9" x2="9.01" y2="9" />
                        <line x1="15" y1="9" x2="15.01" y2="9" />
                    </svg>
                </div>
                <div class="benefit-label">Efficiency</div>
                <p class="benefit-text">Reduce internal movement and material handling costs through optimized flow
                    design</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" />
                        <path d="M2 17l10 5 10-5" />
                        <path d="M2 12l10 5 10-5" />
                    </svg>
                </div>
                <div class="benefit-label">Sustainability</div>
                <p class="benefit-text">Lower lifetime energy through passive design alignment and smart orientation</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                        <path d="M12 8v8" />
                        <path d="M8 12h8" />
                    </svg>
                </div>
                <div class="benefit-label">Flexibility</div>
                <p class="benefit-text">Build layouts ready for automation, new product lines, and rapid growth</p>
            </div>
        </div>
    </div>
</section>

<!-- Problem & Stakes Section -->
<section class="problem-section" style="padding: 5rem 0; background: white;">
    <style>
        .problem-card-new {
            padding: 2rem;
            background: #f8fafc;
            border: 1px solid var(--home-border);
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .problem-card-new::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: #e99738;
            /* Orange */
            transform: scaleY(0);
            transition: transform 0.3s ease;
            transform-origin: bottom;
        }

        .problem-card-new:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            background: white;
        }

        .problem-card-new:hover::before {
            transform: scaleY(1);
        }

        .problem-icon {
            width: 48px;
            height: 48px;
            background: rgba(233, 151, 56, 0.08);
            /* Orange tint */
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            transition: all 0.3s ease;
        }

        .problem-card-new:hover .problem-icon {
            background: var(--home-orange);
        }

        .problem-icon svg {
            width: 24px;
            height: 24px;
            color: #e99738;
            /* Orange */
            transition: color 0.3s ease;
        }

        .problem-card-new:hover .problem-icon svg {
            color: white;
        }

        .problem-card-new h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--home-dark);
            margin-bottom: 0.75rem;
            transition: color 0.3s ease;
        }

        .problem-card-new:hover h3 {
            color: var(--home-orange);
        }

        .problem-card-new p {
            font-size: 0.95rem;
            line-height: 1.7;
            color: var(--home-text);
            margin: 0;
        }
    </style>
    <div class="container">
        <div class="section-header" style="max-width: 850px; margin: 0 auto 4rem; text-align: center;">
            <span
                style="display: inline-block; background: rgba(233, 151, 56, 0.1); color: #e99738; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.5rem 1rem; border-radius: 100px; margin-bottom: 1.5rem;">The
                Challenge</span>
            <h2
                style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--home-dark); line-height: 1.2;">
                Why conventional factory design fails operations</h2>
            <p style="font-size: 1.15rem; line-height: 1.8; color: var(--home-text); max-width: 750px; margin: 0 auto;">
                Most manufacturers struggle with hidden inefficiencies that SOPs and management cannot fix. These
                problems are structural—when a building is treated as a mere shell, it creates permanent bottlenecks.
            </p>
        </div>

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem; max-width: 1200px; margin: 0 auto;">
            <div class="problem-card-new">
                <div class="problem-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <path d="M3 9h18" />
                        <path d="M9 21V9" />
                    </svg>
                </div>
                <h3>Aesthetic Priority Over Function</h3>
                <p>Buildings planned as real‑estate boxes, prioritizing facades over flow-optimized factory operations
                </p>
            </div>
            <div class="problem-card-new">
                <div class="problem-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M16 8l-4 4-4-4" />
                        <path d="M8 16l4-4 4 4" />
                    </svg>
                </div>
                <h3>Excessive Material Movement</h3>
                <p>High forklift and operator travel distances leading to hidden, recurring OPEX leakages</p>
            </div>
            <div class="problem-card-new">
                <div class="problem-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="10" rx="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </div>
                <h3>Rigid Infrastructure Lock-in</h3>
                <p>Painful expansions and automation retrofits blocked by immovable structural grids</p>
            </div>
            <div class="problem-card-new">
                <div class="problem-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3" />
                        <path
                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                    </svg>
                </div>
                <h3>Poor Sightlines & Visibility</h3>
                <p>Layouts with dead corners that don't support Lean principles, 5S, and continuous flow</p>
            </div>
            <div class="problem-card-new">
                <div class="problem-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                    </svg>
                </div>
                <h3>MEP & Utility Conflicts</h3>
                <p>HVAC and utility systems designed without process requirements creating daily constraints</p>
            </div>
            <div class="problem-card-new">
                <div class="problem-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                        <line x1="12" y1="22.08" x2="12" y2="12" />
                    </svg>
                </div>
                <h3>Zero Growth Flexibility</h3>
                <p>No flexibility built in for future product mix changes or volume scaling needs</p>
            </div>
        </div>
    </div>
</section>

<!-- What is LFB Section -->
<section class="lfb-section" id="how-lfb-works" style="padding: 3rem 0; background: #f8f9fa;">
    <style>
        .lfb-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .lfb-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: center;
        }

        .lfb-content {
            position: relative;
        }

        .lfb-eyebrow {
            display: inline-block;
            background: var(--home-orange-light);
            color: var(--home-orange);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0.5rem 1rem;
            border-radius: 100px;
            margin-bottom: 1rem;
        }

        .lfb-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--home-dark);
            line-height: 1.1;
        }

        .lfb-description {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--home-text);
            margin-bottom: 1.5rem;
        }

        .lfb-features {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .lfb-feature {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            border: 1px solid var(--home-border);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .lfb-feature:hover {
            transform: translateX(8px);
            border-color: var(--home-orange);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .lfb-feature-num {
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--home-orange) 0%, #f5a854 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .lfb-feature:nth-child(2) .lfb-feature-num {
            background: linear-gradient(135deg, var(--home-orange) 0%, #f5a854 100%);
        }

        .lfb-feature:nth-child(3) .lfb-feature-num {
            background: linear-gradient(135deg, var(--home-orange) 0%, #f5a854 100%);
        }

        .lfb-feature-content h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
            color: var(--home-dark);
        }

        .lfb-feature-content p {
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--home-gray);
            margin: 0;
        }

        .lfb-visual {
            background: linear-gradient(135deg, var(--home-dark) 0%, #334155 100%);
            border-radius: 16px;
            padding: 3rem;
            position: relative;
            overflow: hidden;
            min-height: 450px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .lfb-visual::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(233, 148, 49, 0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .lfb-visual-content {
            position: relative;
            z-index: 2;
        }

        .lfb-diagram {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .lfb-diagram-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .lfb-diagram-item:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(233, 148, 49, 0.3);
        }

        .lfb-diagram-icon {
            width: 40px;
            height: 40px;
            background: var(--home-orange);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .lfb-diagram-icon svg {
            width: 20px;
            height: 20px;
            color: white;
        }

        .lfb-diagram-item:nth-child(2) .lfb-diagram-icon {
            background: var(--home-orange);
        }

        .lfb-diagram-item:nth-child(3) .lfb-diagram-icon {
            background: var(--home-orange);
        }

        .lfb-diagram-item:nth-child(4) .lfb-diagram-icon {
            background: var(--home-orange);
        }

        .lfb-diagram-text {
            color: white;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .lfb-diagram-arrow {
            text-align: center;
            padding: 0.5rem 0;
        }

        .lfb-diagram-arrow svg {
            width: 24px;
            height: 24px;
            color: rgba(255, 255, 255, 0.4);
        }

        @media (max-width: 992px) {
            .lfb-grid {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
        }

        .process-flow-img {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 450px;
            /* Further restricted height */
            display: block;
            margin: 0 auto;
            /* Center the image */
            border: none;
            object-fit: contain;
        }

        .lfb-visual {
            /* Remove fixed min-height if generic, or keep it. */
            /* min-height: 450px; <-- Remove or adjust if image is tall */
            min-height: auto;
            padding: 0;
            /* Remove padding for image to fit fully or keep standard? */
            background: transparent;
            /* Remove background if image covers it */
        }

        .lfb-visual::before {
            display: none;
            /* Hide the background circle effect if it conflicts */
        }
    </style>
    <div class="container">
        <div class="lfb-container">
            <div class="lfb-grid">
                <div class="lfb-content">
                    <span class="lfb-eyebrow">Our Approach</span>
                    <h2 class="lfb-title">What is Lean Factory Building?</h2>
                    <p class="lfb-description">
                        Lean Factory Building (LFB) is a specialized architectural approach that reverses the
                        conventional sequence. Instead of forcing your manufacturing process into a pre-conceived
                        structure, we start from your value stream and material flow, wrapping the building around that
                        optimal process.
                    </p>
                    <div class="lfb-features">
                        <div class="lfb-feature">
                            <div class="lfb-feature-num">1</div>
                            <div class="lfb-feature-content">
                                <h3>Inside-Out Design</h3>
                                <p>From process flow to building envelope—not the other way around</p>
                            </div>
                        </div>
                        <div class="lfb-feature">
                            <div class="lfb-feature-num">2</div>
                            <div class="lfb-feature-content">
                                <h3>Full Integration</h3>
                                <p>Lean principles, industrial layout, and civil architecture unified</p>
                            </div>
                        </div>
                        <div class="lfb-feature">
                            <div class="lfb-feature-num">3</div>
                            <div class="lfb-feature-content">
                                <h3>Strategic Alignment</h3>
                                <p>Project timelines, operations requirements, and finance goals</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lfb-visual">
                    <div class="lfb-visual-content">
                        <?php
                        // Fetch Process Flow Image
                        $processImageSettings = null;
                        $procQuery = "SELECT * FROM banner_settings WHERE page_name = 'home_process' AND is_active = 1 LIMIT 1";
                        $procResult = $conn->query($procQuery);
                        if ($procResult && $procResult->num_rows > 0) {
                            $processImageSettings = $procResult->fetch_assoc();
                        }

                        if ($processImageSettings && !empty($processImageSettings['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($processImageSettings['image_path']); ?>"
                                alt="<?php echo htmlspecialchars($processImageSettings['heading_html']); ?>"
                                class="process-flow-img">
                        <?php else: ?>
                            <!-- Fallback to original diagram if no image set -->
                            <div class="lfb-diagram">
                                <div class="lfb-diagram-item">
                                    <div class="lfb-diagram-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                        </svg>
                                    </div>
                                    <span class="lfb-diagram-text">1. Analyze Value Stream & Flow</span>
                                </div>
                                <div class="lfb-diagram-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 5v14M19 12l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div class="lfb-diagram-item">
                                    <div class="lfb-diagram-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <path d="M3 9h18M9 21V9" />
                                        </svg>
                                    </div>
                                    <span class="lfb-diagram-text">2. Design Optimal Layout</span>
                                </div>
                                <div class="lfb-diagram-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 5v14M19 12l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div class="lfb-diagram-item">
                                    <div class="lfb-diagram-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                            <polyline points="9 22 9 12 15 12 15 22" />
                                        </svg>
                                    </div>
                                    <span class="lfb-diagram-text">3. Define Structure & MEP</span>
                                </div>
                                <div class="lfb-diagram-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 5v14M19 12l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div class="lfb-diagram-item">
                                    <div class="lfb-diagram-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                            <polyline points="22 4 12 14.01 9 11.01" />
                                        </svg>
                                    </div>
                                    <span class="lfb-diagram-text">4. Build for Performance</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Inside-Out vs Conventional Comparison -->
<section class="comparison-section" style="padding: 5rem 0; background: white;">
    <style>
        .comparison-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .comparison-table-new {
            width: 100%;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        .comparison-table-header {
            display: flex;
            background: #2f3030;
        }

        .comparison-table-header .col-label {
            width: 160px;
            flex-shrink: 0;
            padding: 1.5rem;
            background: rgba(0, 0, 0, 0.2);
        }

        .comparison-table-header .col-conventional {
            flex: 1;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .comparison-table-header .col-lfb {
            flex: 1;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: rgba(233, 148, 49, 0.15);
            color: var(--home-orange);
            font-weight: 600;
        }

        .comparison-table-row {
            display: flex;
            border-bottom: 1px solid var(--home-border);
        }

        .comparison-table-row:last-child {
            border-bottom: none;
        }

        .comparison-table-row:hover {
            background: #fafbfc;
        }

        .comparison-table-row .col-label {
            width: 160px;
            flex-shrink: 0;
            padding: 1.5rem;
            background: #f8fafc;
            font-weight: 600;
            color: var(--home-dark);
            font-size: 0.95rem;
            display: flex;
            align-items: center;
        }

        .comparison-table-row .col-conventional {
            flex: 1;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            color: var(--home-gray);
            font-size: 0.9rem;
            line-height: 1.6;
            border-right: 1px solid var(--home-border);
        }

        .comparison-table-row .col-lfb {
            flex: 1;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            color: var(--home-dark);
            font-size: 0.9rem;
            line-height: 1.6;
            background: rgba(233, 148, 49, 0.03);
        }

        .comp-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.65rem;
            font-weight: 700;
            margin-top: 2px;
        }

        .comp-icon.bad {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        .comp-icon.good {
            background: rgba(16, 185, 129, 0.15);
            color: var(--home-green);
        }

        @media (max-width: 768px) {

            /* Hide Main Header */
            .comparison-table-header {
                display: none;
            }

            /* Card Style for Rows */
            .comparison-table-row {
                flex-direction: column;
                background: white;
                border: 1px solid var(--home-border);
                border-radius: 12px;
                margin-bottom: 1.5rem;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }

            .comparison-table-row:last-child {
                border-bottom: 1px solid var(--home-border);
            }

            /* Label as Header */
            .comparison-table-row .col-label {
                width: 100%;
                background: #2f3030;
                color: white;
                padding: 1rem 1.5rem;
                font-size: 1.1rem;
            }

            /* Content Styling */
            .comparison-table-row .col-conventional,
            .comparison-table-row .col-lfb {
                width: 100%;
                padding: 1.25rem 1.5rem;
                border-right: none;
                border-bottom: 1px solid var(--home-border);
                position: relative;
                padding-top: 2.5rem;
                /* Space for label */
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
            }

            /* Inline Labels via Pseudo-elements */
            .comparison-table-row .col-conventional::before {
                content: 'Conventional';
                position: absolute;
                top: 0.75rem;
                left: 1.5rem;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                color: #EF4444;
                /* Red */
                letter-spacing: 0.5px;
            }

            .comparison-table-row .col-lfb::before {
                content: 'LFB Approach';
                position: absolute;
                top: 0.75rem;
                left: 1.5rem;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                color: #10B981;
                /* Green matches icon */
                letter-spacing: 0.5px;
            }

            .comparison-table-row .col-lfb {
                border-bottom: none;
                background: rgba(16, 185, 129, 0.05);
                /* Light Green bg */
            }
        }
    </style>
    <div class="container">
        <div class="section-header" style="max-width: 800px; margin: 0 auto 3rem; text-align: center;">
            <span
                style="display: inline-block; background: rgba(233, 148, 49, 0.1); color: var(--home-orange); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.5rem 1rem; border-radius: 100px; margin-bottom: 1.5rem;">The
                Difference</span>
            <h2 style="font-size: 2.25rem; font-weight: 700; margin-bottom: 1rem; color: var(--home-dark);">Conventional
                vs LFB Approach</h2>
        </div>

        <div class="comparison-container">
            <div class="comparison-table-new">
                <!-- Header -->
                <div class="comparison-table-header">
                    <div class="col-label"></div>
                    <div class="col-conventional">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="21" width="18" height="2" />
                            <rect x="5" y="3" width="14" height="18" rx="2" />
                            <path d="M9 7h6" />
                            <path d="M9 11h6" />
                            <path d="M9 15h6" />
                        </svg>
                        Conventional
                    </div>
                    <div class="col-lfb">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        LFB Approach
                    </div>
                </div>

                <!-- Row 1: Starting Point -->
                <div class="comparison-table-row">
                    <div class="col-label">Starting Point</div>
                    <div class="col-conventional">
                        <span class="comp-icon bad">✕</span>
                        <span>Design begins with building shell, facades, and land limits</span>
                    </div>
                    <div class="col-lfb">
                        <span class="comp-icon good">✓</span>
                        <span>Design begins with process flow, value stream, and logistics data</span>
                    </div>
                </div>

                <!-- Row 2: Layout Impact -->
                <div class="comparison-table-row">
                    <div class="col-label">Layout Impact</div>
                    <div class="col-conventional">
                        <span class="comp-icon bad">✕</span>
                        <span>Manufacturing squeezed into fixed structural grid</span>
                    </div>
                    <div class="col-lfb">
                        <span class="comp-icon good">✓</span>
                        <span>Structural grid adapts to support optimal flow</span>
                    </div>
                </div>

                <!-- Row 3: Lifetime Cost -->
                <div class="comparison-table-row">
                    <div class="col-label">Lifetime Cost</div>
                    <div class="col-conventional">
                        <span class="comp-icon bad">✕</span>
                        <span>Locks in high recurring energy and handling costs</span>
                    </div>
                    <div class="col-lfb">
                        <span class="comp-icon good">✓</span>
                        <span>Lower lifetime OPEX, passive efficiencies, easier expansions</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services / Use-Cases Section -->
<section class="services-section" style="padding: 5rem 0; background: #f8f9fa;">
    <style>
        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .service-card-new {
            padding: 2.5rem;
            background: white;
            border-radius: 16px;
            border: 1px solid var(--home-border);
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .service-card-new::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--home-orange) 0%, #f5a854 100%);
        }

        .service-card-new:nth-child(2)::before {
            background: linear-gradient(90deg, var(--home-orange) 0%, #f5a854 100%);
        }

        .service-card-new:nth-child(3)::before {
            background: linear-gradient(90deg, var(--home-orange) 0%, #f5a854 100%);
        }

        .service-card-new:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .service-icon {
            width: 64px;
            height: 64px;
            background: var(--home-orange-light);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .service-card-new:hover .service-icon {
            transform: scale(1.1);
        }

        .service-icon svg {
            width: 28px;
            height: 28px;
            color: var(--home-orange);
        }

        .service-card-new:nth-child(2) .service-icon {
            background: var(--home-orange-light);
        }

        .service-card-new:nth-child(2) .service-icon svg {
            color: var(--home-orange);
        }

        .service-card-new:nth-child(3) .service-icon {
            background: var(--home-orange-light);
        }

        .service-card-new:nth-child(3) .service-icon svg {
            color: var(--home-orange);
        }

        .service-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--home-orange);
            margin-bottom: 0.75rem;
        }

        .service-card-new:nth-child(2) .service-label {
            color: var(--home-orange);
        }

        .service-card-new:nth-child(3) .service-label {
            color: var(--home-orange);
        }

        .service-card-new h3 {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--home-dark);
        }

        .service-card-new p {
            font-size: 1rem;
            line-height: 1.7;
            color: var(--home-text);
            flex-grow: 1;
            margin-bottom: 2rem;
        }

        .service-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--home-orange);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: auto;
        }

        .service-card-new:nth-child(2) .service-link {
            color: var(--home-orange);
        }

        .service-card-new:nth-child(3) .service-link {
            color: var(--home-orange);
        }

        .service-link:hover {
            gap: 0.75rem;
        }

        .service-link svg {
            width: 18px;
            height: 18px;
            transition: transform 0.3s ease;
        }

        .service-link:hover svg {
            transform: translateX(4px);
        }

        @media (max-width: 992px) {
            .services-grid {
                grid-template-columns: 1fr;
                max-width: 500px;
            }
        }
    </style>
    <div class="container">
        <div class="section-header" style="max-width: 800px; margin: 0 auto 4rem; text-align: center;">
            <span
                style="display: inline-block; background: var(--home-orange-light); color: var(--home-orange); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.5rem 1rem; border-radius: 100px; margin-bottom: 1.5rem;">Our
                Services</span>
            <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1.25rem; color: var(--home-dark);">Where LFB
                Helps You</h2>
            <p style="font-size: 1.15rem; line-height: 1.7; color: var(--home-text);">Whether you are breaking ground on
                a new site, optimizing an existing facility, or improving running operations, LFB principles apply.</p>
        </div>

        <div class="services-grid">
            <div class="service-card-new">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                </div>
                <div class="service-label">Greenfield Plants</div>
                <h3>New Factory Design</h3>
                <p>Define process, flow, and expansion options before land and structure are frozen. Avoid costly layout
                    compromises from day one.</p>
                <a href="<?php echo url('services/greenfield.php'); ?>" class="service-link">
                    Plan My New Plant
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="service-card-new">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                    </svg>
                </div>
                <div class="service-label">Expansions & Brownfield</div>
                <h3>Existing Plant Optimization</h3>
                <p>Re‑engineer existing layouts to reduce internal travel, integrate new lines, and unlock hidden
                    capacity in your current facility.</p>
                <a href="<?php echo url('services/brownfield.php'); ?>" class="service-link">
                    Optimize My Plant
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="service-card-new">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                </div>
                <div class="service-label">Post‑Commissioning</div>
                <h3>Running Operations</h3>
                <p>Eliminate wastes in material handling, storage, and movement using LFB principles to stabilize and
                    optimize operations.</p>
                <a href="<?php echo url('services/post-commissioning.php'); ?>" class="service-link">
                    Request Plant Study
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Outcomes & Proof Section -->
<?php
// Fetch Outcomes Section Image
$outcomesImageSettings = null;
$outcomesQuery = "SELECT * FROM banner_settings WHERE page_name = 'home_outcomes' AND is_active = 1 LIMIT 1";
$outcomesResult = $conn->query($outcomesQuery);
if ($outcomesResult && $outcomesResult->num_rows > 0) {
    $outcomesImageSettings = $outcomesResult->fetch_assoc();
}
$outcomesBgImage = ($outcomesImageSettings && !empty($outcomesImageSettings['image_path']))
    ? htmlspecialchars($outcomesImageSettings['image_path'])
    : 'assets/images/home/lfb-stats-bg.png';
?>
<section class="outcomes-section"
    style="padding: 8rem 0; background-image: url('<?php echo $outcomesBgImage; ?>'); background-attachment: fixed; background-position: center; background-repeat: no-repeat; background-size: cover; position: relative; overflow: hidden;">
    <!-- Dark overlay to ensure text readability -->
    <div
        style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(34, 23, 11, 0.95) 0%, rgba(51, 35, 17, 0.9) 100%); z-index: 1;">
    </div>

    <style>
        .outcomes-container {
            position: relative;
            z-index: 2;
        }

        .outcomes-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            max-width: 1100px;
            margin: 0 auto 3rem;
        }

        .outcome-card {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .outcome-card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(233, 148, 49, 0.5);
            transform: translateY(-5px);
        }

        .outcome-value {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--home-orange) 0%, #f5a854 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .outcome-label {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.5;
            font-weight: 500;
        }

        .outcomes-text {
            max-width: 850px;
            margin: 0 auto;
            text-align: center;
        }

        .outcomes-text p {
            font-size: 1.2rem;
            line-height: 1.9;
            color: rgba(255, 255, 255, 0.95);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 992px) {
            .outcomes-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .outcomes-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="container">
        <div class="outcomes-container">
            <div class="section-header" style="max-width: 800px; margin: 0 auto 3rem; text-align: center;">
                <span
                    style="display: inline-block; background: rgba(233, 148, 49, 0.2); color: var(--home-orange); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.5rem 1rem; border-radius: 100px; margin-bottom: 1.5rem; backdrop-filter: blur(5px);">Proven
                    Results</span>
                <h2
                    style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                    what manufacturers achieve with LFB</h2>
            </div>

            <div class="outcomes-grid">
                <div class="outcome-card">
                    <div class="outcome-value">250+</div>
                    <div class="outcome-label">Factories transformed with flow‑first layouts</div>
                </div>
                <div class="outcome-card">
                    <div class="outcome-value">20+</div>
                    <div class="outcome-label">Years of expertise in factory design</div>
                </div>
                <div class="outcome-card">
                    <div class="outcome-value">75+</div>
                    <div class="outcome-label">Industrial segments served pan India</div>
                </div>
                <div class="outcome-card">
                    <div class="outcome-value">30%-60%</div>
                    <div class="outcome-label">Average reduction in material travel</div>
                </div>
            </div>

            <div class="outcomes-text">
                <p>Beyond the numbers, the operational impact is felt on the shop floor every day. Clients report
                    significant reduction in internal material travel and handling effort, improved manufacturing
                    throughput and space utilization, and better structural readiness for future automation.</p>
            </div>
        </div>
    </div>
</section>

<!-- SOLUTIONS Legacy Section -->
<section class="legacy-section" style="padding: 6rem 0; background: #2f3030; position: relative; overflow: hidden;">
    <style>
        .legacy-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 15% 30%, rgba(233, 148, 49, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 85% 70%, rgba(59, 130, 246, 0.06) 0%, transparent 40%);
            pointer-events: none;
        }

        .legacy-content-wrapper {
            position: relative;
            z-index: 2;
        }

        /* Journey Timeline */
        .legacy-journey {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            max-width: 1100px;
            margin: 0 auto 4rem;
            align-items: center;
        }

        .journey-story {
            position: relative;
        }

        .journey-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(233, 148, 49, 0.15);
            border: 1px solid rgba(233, 148, 49, 0.3);
            border-radius: 100px;
            margin-bottom: 1.5rem;
        }

        .journey-badge svg {
            width: 14px;
            height: 14px;
            color: var(--home-orange);
        }

        .journey-badge span {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--home-orange);
        }

        .journey-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .journey-title .highlight {
            color: var(--home-orange);
        }

        .journey-text {
            font-size: 1.1rem;
            line-height: 1.9;
            color: #cbd5e1;
            margin-bottom: 2rem;
        }

        .journey-text strong {
            color: #ffffff;
        }

        .journey-founder {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
        }

        .founder-avatar {
            width: 56px;
            height: 56px;
            background: #ffffff;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .founder-info h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0 0 0.25rem 0;
        }

        .founder-info p {
            font-size: 0.85rem;
            color: #94a3b8;
            margin: 0;
        }

        /* Timeline Visual */
        .journey-timeline {
            position: relative;
            padding-left: 2rem;
        }

        .journey-timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, var(--home-orange) 0%, rgba(233, 148, 49, 0.2) 100%);
        }

        .timeline-item {
            position: relative;
            padding: 1.25rem 0 1.25rem 2rem;
            transition: all 0.3s ease;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 1.75rem;
            width: 12px;
            height: 12px;
            background: var(--home-orange);
            border-radius: 50%;
            border: 3px solid #2f3030;
            transition: all 0.3s ease;
        }

        .timeline-item:hover::before,
        .timeline-item.active::before {
            transform: scale(1.3);
            box-shadow: 0 0 0 4px rgba(233, 148, 49, 0.2);
            background: var(--home-orange);
            border-color: #2f3030;
        }

        .timeline-item.active .timeline-year {
            color: var(--home-orange);
            text-shadow: 0 0 10px rgba(233, 148, 49, 0.4);
        }

        .timeline-item.active .timeline-title {
            color: #ffffff;
        }

        .timeline-item {
            opacity: 0.5;
            transform: translateX(-10px);
            transition: all 0.6s ease;
        }

        .timeline-item.active {
            opacity: 1;
            transform: translateX(0);
        }

        .timeline-year {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--home-orange);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .timeline-title {
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.35rem;
        }

        .timeline-desc {
            font-size: 0.875rem;
            color: #94a3b8;
            line-height: 1.6;
        }

        /* Expertise Pills */
        .expertise-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 3rem;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
            justify-content: center;
        }

        .expertise-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 100px;
            transition: all 0.3s ease;
        }

        .expertise-pill:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(233, 148, 49, 0.3);
            transform: translateY(-2px);
        }

        .expertise-pill svg {
            width: 16px;
            height: 16px;
            color: var(--home-orange);
        }

        .expertise-pill span {
            font-size: 0.85rem;
            font-weight: 500;
            color: #e2e8f0;
        }

        @media (max-width: 992px) {
            .legacy-journey {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .journey-title {
                font-size: 2rem;
            }
        }
    </style>

    <div class="legacy-bg-pattern"></div>

    <div class="container">
        <div class="legacy-content-wrapper">

            <!-- Journey Section -->
            <div class="legacy-journey">
                <div class="journey-story">
                    <div class="journey-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                        </svg>
                        <span>Our Foundation</span>
                    </div>
                    <h2 class="journey-title">
                        The <span class="highlight">"SOLUTIONS"</span> Legacy
                    </h2>
                    <p class="journey-text">
                        For over two decades, <strong>Solutions Kaizen Management Systems</strong> has been at the
                        forefront of manufacturing transformation in India. What started as a vision to bring
                        world-class operational excellence to Indian factories has grown into a legacy of <strong>250+
                            successful transformations</strong> across <strong>75+ industry segments</strong>.
                    </p>
                    <p class="journey-text" style="margin-bottom: 2rem;">
                        Our journey has been defined by a relentless pursuit of perfection—applying <strong>Lean
                            Manufacturing, Six Sigma, and Theory of Constraints</strong> to unlock hidden potential in
                        every facility we touch.
                    </p>
                    <div class="journey-founder">
                        <div class="founder-avatar">
                            <img src="assets/logo/solutions_logo.jfif" alt="Solutions Kaizen Management Systems Logo"
                                style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <div class="founder-info">
                            <h4>Solutions Kaizen Management Systems</h4>
                            <p>Established 2006 • Pune, India</p>
                        </div>
                    </div>
                </div>

                <div class="journey-timeline">
                    <div class="timeline-item">
                        <div class="timeline-year">2006 – The Beginning</div>
                        <div class="timeline-title">Founded with a Vision</div>
                        <div class="timeline-desc">Started with a mission to bring Japanese manufacturing excellence to
                            Indian industries</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2013 – Expansion</div>
                        <div class="timeline-title">100+ Projects Milestone</div>
                        <div class="timeline-desc">Expanded expertise to include complete factory layout design and
                            process optimization</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2015 – Innovation</div>
                        <div class="timeline-title">LFB Methodology Developed</div>
                        <div class="timeline-desc">Created the Lean Factory Building approach—designing factories from
                            inside-out</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2020 – Evolution</div>
                        <div class="timeline-title">OptiSpace Architecture Born</div>
                        <div class="timeline-desc">Launched dedicated architectural practice combining LFB with modern
                            design</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">Today</div>
                        <div class="timeline-title">250+ Transformations</div>
                        <div class="timeline-desc">Continuing to shape India's manufacturing landscape with proven
                            methodologies</div>
                    </div>
                </div>
            </div>

            <!-- Expertise Pills -->
            <div class="expertise-pills">
                <div class="expertise-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                    <span>Lean Manufacturing</span>
                </div>
                <div class="expertise-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 20V10M12 20V4M6 20v-6" />
                    </svg>
                    <span>Six Sigma</span>
                </div>
                <div class="expertise-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    </svg>
                    <span>Factory Layouts</span>
                </div>
                <div class="expertise-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                    </svg>
                    <span>Inventory Systems</span>
                </div>
                <div class="expertise-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                    </svg>
                    <span>Visual Workplace</span>
                </div>
                <div class="expertise-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    <span>Process Optimization</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Client Success Stories Section -->
<section class="success-stories-section" style="padding: 5rem 0; background: #f8fafc;">
    <style>
        .stories-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .stories-header {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .stories-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(233, 148, 49, 0.1);
            /* Orange tint */
            border-radius: 100px;
            margin-bottom: 1rem;
        }

        .stories-badge svg {
            width: 16px;
            height: 16px;
            color: var(--home-orange);
        }

        .stories-badge span {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--home-orange);
        }

        .stories-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--home-dark);
            margin-bottom: 0.75rem;
        }

        .stories-subtitle {
            font-size: 1.1rem;
            color: var(--home-gray);
            max-width: 650px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Hero Video Section */
        .hero-video-section {
            margin-bottom: 3rem;
        }

        .hero-video-card {
            position: relative;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hero-video-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            border-color: var(--home-orange);
        }

        .hero-thumbnail-wrapper {
            position: relative;
            width: 100%;
            padding-top: 50%;
            /* Wider aspect ratio for hero */
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            overflow: hidden;
        }

        .hero-thumbnail-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .hero-video-card:hover .hero-thumbnail-wrapper img {
            transform: scale(1.05);
        }

        .hero-play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
            z-index: 2;
        }

        .hero-video-card:hover .hero-play-button {
            transform: translate(-50%, -50%) scale(1.15);
            background: white;
            box-shadow: 0 16px 50px rgba(233, 148, 49, 0.4);
        }

        .hero-play-button svg {
            width: 32px;
            height: 32px;
            color: var(--home-orange);
            margin-left: 4px;
        }

        .hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 3rem;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.4) 50%, transparent 100%);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(233, 148, 49, 0.95);
            backdrop-filter: blur(8px);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
        }

        .hero-badge svg {
            width: 14px;
            height: 14px;
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .hero-description {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            max-width: 800px;
        }

        /* Modern Video Grid Layout */
        .video-grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 1rem;
        }

        .video-card {
            position: relative;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            border: 1px solid #e5e7eb;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .video-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
            border-color: var(--home-orange);
        }

        .video-thumbnail-wrapper {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            /* 16:9 aspect ratio */
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            overflow: hidden;
        }

        .video-thumbnail-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .video-card:hover .video-thumbnail-wrapper img {
            transform: scale(1.08);
        }

        /* Play Button */
        .video-play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            z-index: 2;
        }

        .video-card:hover .video-play-button {
            transform: translate(-50%, -50%) scale(1.15);
            background: white;
            box-shadow: 0 12px 32px rgba(233, 148, 49, 0.3);
        }

        .video-play-button svg {
            width: 24px;
            height: 24px;
            color: var(--home-orange);
            margin-left: 3px;
        }

        /* Gradient Overlay */
        .video-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .video-card:hover .video-overlay {
            opacity: 1;
        }

        /* Video Info Section */
        .video-info {
            padding: 1.25rem 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .video-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--home-dark);
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 0.25rem;
            transition: color 0.3s ease;
        }

        .video-card:hover .video-title {
            color: var(--home-orange);
        }

        .video-description {
            font-size: 0.875rem;
            color: var(--home-gray);
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Video Badge */
        .video-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(233, 148, 49, 0.95);
            backdrop-filter: blur(8px);
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 2;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .video-card:hover .video-badge {
            opacity: 1;
            transform: translateY(0);
        }

        /* Watch Icon in Info */
        .video-watch-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--home-orange);
            margin-top: auto;
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s ease;
        }

        .video-card:hover .video-watch-indicator {
            opacity: 1;
            transform: translateX(0);
        }

        .video-watch-indicator svg {
            width: 14px;
            height: 14px;
        }

        /* Show More Button */
        .show-more-container {
            text-align: center;
            margin-top: 3rem;
        }

        .show-more-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: white;
            color: var(--home-dark);
            padding: 1rem 2.5rem;
            border: 2px solid var(--home-orange);
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(233, 148, 49, 0.15);
        }

        .show-more-btn:hover {
            background: var(--home-orange);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233, 148, 49, 0.3);
        }

        .show-more-btn svg {
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }

        .show-more-btn:hover svg {
            transform: translateY(3px);
        }

        .show-more-btn.expanded svg {
            transform: rotate(180deg);
        }

        .show-more-btn.expanded:hover svg {
            transform: rotate(180deg) translateY(-3px);
        }

        .video-card.hidden {
            display: none;
        }

        .video-card.hidden-mobile {
            display: flex;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .stories-container {
                padding: 0 1rem;
            }

            .video-grid-container {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 1.5rem;
            }

            .stories-title {
                font-size: 2rem;
            }

            .hero-thumbnail-wrapper {
                padding-top: 56.25%;
            }

            .hero-overlay {
                padding: 2rem;
            }

            .hero-title {
                font-size: 1.5rem;
            }

            .hero-description {
                font-size: 1rem;
            }
        }

        @media (max-width: 768px) {
            .video-grid-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .stories-title {
                font-size: 1.75rem;
            }

            .stories-subtitle {
                font-size: 1rem;
            }

            .video-info {
                padding: 1rem;
            }

            .video-play-button {
                width: 56px;
                height: 56px;
            }

            .video-play-button svg {
                width: 20px;
                height: 20px;
            }

            .video-title {
                font-size: 0.9rem;
            }

            .video-description {
                font-size: 0.8rem;
                -webkit-line-clamp: 1;
            }

            .hero-video-section {
                margin-bottom: 2rem;
            }

            .hero-overlay {
                padding: 1.5rem;
            }

            .hero-title {
                font-size: 1.25rem;
            }

            .hero-description {
                font-size: 0.9rem;
            }

            .hero-play-button {
                width: 70px;
                height: 70px;
            }

            .hero-play-button svg {
                width: 26px;
                height: 26px;
            }

            /* Hide videos 5-6 on mobile, show only 4 initially */
            .video-card.hidden-mobile {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .stories-header {
                margin-bottom: 2rem;
            }

            .stories-title {
                font-size: 1.5rem;
            }

            .stories-subtitle {
                font-size: 0.9rem;
            }

            .video-grid-container {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .video-card {
                max-width: 100%;
            }

            .video-info {
                padding: 1rem 1.25rem;
            }

            .hero-thumbnail-wrapper {
                padding-top: 60%;
            }

            .hero-overlay {
                padding: 1.25rem;
            }

            .hero-title {
                font-size: 1.1rem;
                margin-bottom: 0.5rem;
            }

            .hero-description {
                font-size: 0.85rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .hero-play-button {
                width: 60px;
                height: 60px;
            }

            .hero-play-button svg {
                width: 22px;
                height: 22px;
            }
        }

        @media (max-width: 400px) {
            .video-grid-container {
                gap: 1rem;
            }

            .video-info {
                padding: 0.875rem 1rem;
            }

            .video-title {
                font-size: 0.85rem;
            }

            .hero-badge {
                font-size: 0.65rem;
                padding: 0.35rem 0.75rem;
            }
        }
    </style>

    <div class="container">
        <div class="stories-container">
            <div class="stories-header">
                <div class="stories-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="23 7 16 12 23 17 23 7" />
                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2" />
                    </svg>
                    <span>Testimonials</span>
                </div>
                <h2 class="stories-title">Client Success Stories</h2>
                <p class="stories-subtitle">Hear directly from our clients about their transformation journey and the
                    impact on their operations</p>
            </div>

            <?php if (!empty($clientVideos)): ?>

                <!-- Hero Video -->
                <?php
                $heroVideo = $clientVideos[0];
                $heroVideoId = getYouTubeVideoId($heroVideo['youtube_video_url']);
                if ($heroVideoId):
                    ?>
                    <div class="hero-video-section">
                        <div class="hero-video-card" onclick="openVideoModal('<?php echo htmlspecialchars($heroVideoId); ?>')">
                            <div class="hero-thumbnail-wrapper">
                                <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($heroVideoId); ?>/maxresdefault.jpg"
                                    alt="<?php echo htmlspecialchars($heroVideo['title']); ?>"
                                    onerror="this.src='https://img.youtube.com/vi/<?php echo htmlspecialchars($heroVideoId); ?>/hqdefault.jpg'">

                                <div class="hero-play-button">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="5 3 19 12 5 21 5 3" />
                                    </svg>
                                </div>

                                <div class="hero-overlay">
                                    <div class="hero-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                        </svg>
                                        Featured Story
                                    </div>
                                    <h3 class="hero-title"><?php echo htmlspecialchars($heroVideo['title']); ?></h3>
                                    <p class="hero-description">
                                        <?php echo htmlspecialchars($heroVideo['description'] ?? 'Discover how we helped transform their operations and achieve remarkable results.'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Remaining Videos Grid -->
                <?php if (count($clientVideos) > 1): ?>
                    <div class="video-grid-container" id="videoGridContainer">
                        <?php
                        $initialShow = 6; // Show first 6 videos initially on desktop
                        $mobileShow = 4; // Show first 4 videos on mobile
                        for ($i = 1; $i < count($clientVideos); $i++):
                            $video = $clientVideos[$i];
                            $videoId = getYouTubeVideoId($video['youtube_video_url']);
                            if (!$videoId)
                                continue;

                            // Determine classes
                            $classes = '';
                            if ($i > $initialShow) {
                                $classes = 'hidden';
                            } elseif ($i > $mobileShow) {
                                $classes = 'hidden-mobile';
                            }
                            ?>
                            <div class="video-card <?php echo $classes; ?>"
                                onclick="openVideoModal('<?php echo htmlspecialchars($videoId); ?>')">
                                <div class="video-thumbnail-wrapper">
                                    <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($videoId); ?>/maxresdefault.jpg"
                                        alt="<?php echo htmlspecialchars($video['title']); ?>"
                                        onerror="this.src='https://img.youtube.com/vi/<?php echo htmlspecialchars($videoId); ?>/hqdefault.jpg'">

                                    <div class="video-play-button">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <polygon points="5 3 19 12 5 21 5 3" />
                                        </svg>
                                    </div>

                                    <div class="video-overlay"></div>
                                </div>

                                <div class="video-info">
                                    <h3 class="video-title"><?php echo htmlspecialchars($video['title']); ?></h3>
                                    <p class="video-description">
                                        <?php echo htmlspecialchars($video['description'] ?? 'Watch this testimonial'); ?>
                                    </p>
                                    <div class="video-watch-indicator">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polygon points="5 3 19 12 5 21 5 3" />
                                        </svg>
                                        <span>Watch Now</span>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <!-- Show More Button -->
                    <?php if (count($clientVideos) > ($initialShow + 1)): ?>
                        <div class="show-more-container">
                            <button class="show-more-btn" id="showMoreBtn" onclick="toggleVideos()">
                                <span id="btnText" data-desktop="<?php echo count($clientVideos) - $initialShow - 1; ?>"
                                    data-mobile="<?php echo count($clientVideos) - $mobileShow - 1; ?>">Show <span
                                        class="video-count"><?php echo count($clientVideos) - $initialShow - 1; ?></span> More
                                    Videos</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            <?php else: ?>
                <div style="text-align: center; padding: 3rem; color: #64748b;">
                    <p>No client testimonial videos available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Video Modal -->
<div id="videoModal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 9999; align-items: center; justify-content: center;">
    <div style="position: relative; width: 90%; max-width: 1000px;">
        <button onclick="closeVideoModal()"
            style="position: absolute; top: -50px; right: 0; background: none; border: none; color: white; font-size: 2.5rem; cursor: pointer; padding: 0.5rem; line-height: 1;">&times;</button>
        <div
            style="position: relative; padding-top: 56.25%; background: #000; border-radius: 16px; overflow: hidden; box-shadow: 0 30px 100px rgba(0,0,0,0.5);">
            <iframe id="videoIframe"
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" src=""
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe>
        </div>
    </div>
</div>

<script>
    function openVideoModal(videoId) {
        const modal = document.getElementById('videoModal');
        const iframe = document.getElementById('videoIframe');
        modal.style.display = 'flex';
        iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
        document.body.style.overflow = 'hidden';
    }

    function closeVideoModal() {
        const modal = document.getElementById('videoModal');
        const iframe = document.getElementById('videoIframe');
        modal.style.display = 'none';
        iframe.src = '';
        document.body.style.overflow = '';
    }

    document.getElementById('videoModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeVideoModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeVideoModal();
        }
    });

    // Toggle Videos Show More/Less
    function toggleVideos() {
        const allHiddenCards = document.querySelectorAll('.video-card.hidden, .video-card.hidden-mobile');
        const btn = document.getElementById('showMoreBtn');
        const btnText = document.getElementById('btnText');
        const videoCount = document.querySelector('.video-count');
        const isExpanded = btn.classList.contains('expanded');
        const isMobile = window.innerWidth <= 768;

        if (isExpanded) {
            // Collapse - hide videos based on screen size
            allHiddenCards.forEach(card => {
                if (card.classList.contains('hidden')) {
                    card.style.display = 'none';
                } else if (card.classList.contains('hidden-mobile') && isMobile) {
                    card.style.display = 'none';
                }
            });
            btn.classList.remove('expanded');

            // Update count based on screen size
            const count = isMobile ? btnText.getAttribute('data-mobile') : btnText.getAttribute('data-desktop');
            if (videoCount) {
                videoCount.textContent = count;
                btnText.innerHTML = 'Show <span class="video-count">' + count + '</span> More Videos';
            }

            // Scroll to grid top
            setTimeout(() => {
                document.getElementById('videoGridContainer').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100);
        } else {
            // Expand - show all videos
            allHiddenCards.forEach(card => {
                card.style.display = 'flex';
            });
            btn.classList.add('expanded');
            btnText.textContent = 'Show Less';
        }
    }

    // Update button text on window resize
    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            const btn = document.getElementById('showMoreBtn');
            if (btn && !btn.classList.contains('expanded')) {
                const btnText = document.getElementById('btnText');
                const videoCount = document.querySelector('.video-count');
                const isMobile = window.innerWidth <= 768;
                const count = isMobile ? btnText.getAttribute('data-mobile') : btnText.getAttribute('data-desktop');

                if (videoCount) {
                    videoCount.textContent = count;
                    btnText.innerHTML = 'Show <span class="video-count">' + count + '</span> More Videos';
                }
            }
        }, 250);
    });
</script>

<!-- Live Projects Section -->
<?php include 'includes/live-projects-section.php'; ?>

<!-- Methodology Snapshot Section -->
<section class="methodology-section" style="padding: 5rem 0; background: white;">
    <style>
        .methodology-container-new {
            max-width: 1200px;
            margin: 0 auto;
        }

        .methodology-steps-new {
            display: flex;
            align-items: stretch;
            gap: 1.5rem;
            margin-top: 3rem;
        }

        .methodology-card-new {
            flex: 1;
            background: #f8f9fa;
            border: 1px solid var(--home-border);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .methodology-card-new::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 16px 16px 0 0;
            background: linear-gradient(90deg, #e99738 0%, #f5a854 100%);
            /* Updated */
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .methodology-card-new:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }

        .methodology-card-new:hover::before {
            opacity: 1;
        }

        .methodology-icon-new {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #e99738 0%, #f5a854 100%);
            /* Updated */
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .methodology-card-new:hover .methodology-icon-new {
            transform: scale(1.1);
        }

        .methodology-icon-new svg {
            width: 28px;
            height: 28px;
            color: white;
        }

        .methodology-card-new:nth-child(3) .methodology-icon-new {
            background: linear-gradient(135deg, #e99738 0%, #f5a854 100%);
        }

        .methodology-card-new:nth-child(5) .methodology-icon-new {
            background: linear-gradient(135deg, #e99738 0%, #f5a854 100%);
        }

        .methodology-step-label-new {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--home-orange);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.75rem;
        }

        .methodology-card-new:nth-child(3) .methodology-step-label-new {
            color: var(--home-orange);
        }

        .methodology-card-new:nth-child(5) .methodology-step-label-new {
            color: var(--home-orange);
        }

        .methodology-card-new h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--home-dark);
        }

        .methodology-card-new p {
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--home-gray);
            margin: 0;
        }

        .methodology-arrow-new {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 40px;
        }

        .methodology-arrow-new svg {
            width: 28px;
            height: 28px;
            color: var(--home-orange);
        }

        @media (max-width: 992px) {
            .methodology-steps-new {
                flex-direction: column;
                gap: 2rem;
            }

            .methodology-arrow-new {
                width: 100%;
                height: 40px;
            }

            .methodology-arrow-new svg {
                transform: rotate(90deg);
            }
        }
    </style>
    <div class="container">
        <div class="methodology-container-new">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <span
                    style="display: inline-block; background: var(--home-orange-light); color: var(--home-orange); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.5rem 1rem; border-radius: 100px;">Our
                    Process</span>
            </div>

            <div style="text-align: center; max-width: 800px; margin: 0 auto 3rem;">
                <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1.25rem; color: var(--home-dark);">How
                    OptiSpace delivers LFB</h2>
                <p style="font-size: 1.1rem; line-height: 1.7; color: var(--home-text);">We don't guess; we calculate.
                    Our methodology is a rigorous translation of your production needs into architectural reality.</p>
            </div>

            <div class="methodology-steps-new">
                <div class="methodology-card-new">
                    <div class="methodology-icon-new">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                        </svg>
                    </div>
                    <div class="methodology-step-label-new">Step 1</div>
                    <h3>Understand Value Stream</h3>
                    <p>Map products, volumes, and material flows to establish baseline metrics and identify optimization
                        opportunities</p>
                </div>

                <div class="methodology-arrow-new">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </div>

                <div class="methodology-card-new">
                    <div class="methodology-icon-new">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <path d="M3 9h18M9 21V9" />
                        </svg>
                    </div>
                    <div class="methodology-step-label-new">Step 2</div>
                    <h3>Design Flow-First Layouts</h3>
                    <p>Translate flows into layout and structural options that minimize waste and maximize throughput
                    </p>
                </div>

                <div class="methodology-arrow-new">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </div>

                <div class="methodology-card-new">
                    <div class="methodology-icon-new">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                    </div>
                    <div class="methodology-step-label-new">Step 3</div>
                    <h3>Detail Architecture & MEP</h3>
                    <p>Freeze building concepts for Lean operations and ensure structural readiness for future growth
                    </p>
                </div>
            </div>

            <div style="text-align: center; margin-top: 3rem;">
                <a href="<?php echo url('process.php'); ?>"
                    style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--home-orange); font-weight: 600; text-decoration: none; font-size: 1.05rem; transition: gap 0.3s ease;">
                    Explore our detailed process
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Lean Wastes Section -->
<section class="wastes-section" style="padding: 5rem 0; background: #f8f9fa;">
    <style>
        .wastes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .waste-card-new {
            padding: 2rem;
            background: white;
            border-radius: 12px;
            border: 1px solid var(--home-border);
            transition: all 0.3s ease;
            display: flex;
            gap: 1.25rem;
            align-items: flex-start;
        }

        .waste-card-new:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            border-color: var(--home-orange);
        }

        .waste-icon {
            width: 48px;
            height: 48px;
            background: rgba(233, 151, 56, 0.1);
            /* Orange tint */
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .waste-card-new:hover .waste-icon {
            background: var(--home-orange);
        }

        .waste-icon svg {
            width: 24px;
            height: 24px;
            color: var(--home-orange);
            fill: none;
            transition: color 0.3s ease;
        }

        .waste-card-new:hover .waste-icon svg {
            color: white;
        }

        .waste-content h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--home-dark);
            transition: color 0.3s ease;
        }

        .waste-card-new:hover .waste-content h3 {
            color: var(--home-orange);
        }

        .waste-content p {
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--home-gray);
            margin: 0;
        }
    </style>
    <div class="container">
        <div class="section-header" style="max-width: 850px; margin: 0 auto 4rem; text-align: center;">
            <span
                style="display: inline-block; background: rgba(233, 148, 49, 0.1); color: var(--home-orange); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.5rem 1rem; border-radius: 100px; margin-bottom: 1.5rem;">Waste
                Elimination</span>
            <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1.25rem; color: var(--home-dark);">Designed
                to eliminate Lean wastes</h2>
            <p style="font-size: 1.15rem; line-height: 1.8; color: var(--home-text);">LFB is fundamentally aligned with
                Lean principles. While traditional Lean focuses on process and people, we focus on the environment they
                work in—structurally eliminating key wastes through design decisions.</p>
        </div>

        <div class="wastes-grid">
            <div class="waste-card-new">
                <div class="waste-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="3" width="15" height="13" />
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                        <circle cx="5.5" cy="18.5" r="2.5" />
                        <circle cx="18.5" cy="18.5" r="2.5" />
                    </svg>
                </div>
                <div class="waste-content">
                    <h3>Transportation</h3>
                    <p>Moving material unnecessarily due to poor dock location or equipment placement</p>
                </div>
            </div>
            <div class="waste-card-new">
                <div class="waste-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M16 8l-4 4-4-4" />
                        <path d="M8 16l4-4 4 4" />
                    </svg>
                </div>
                <div class="waste-content">
                    <h3>Motion</h3>
                    <p>Operators walking excessive distances between workstations and storage areas</p>
                </div>
            </div>
            <div class="waste-card-new">
                <div class="waste-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <div class="waste-content">
                    <h3>Waiting</h3>
                    <p>Materials sitting idle due to unbalanced line layouts and process bottlenecks</p>
                </div>
            </div>
            <div class="waste-card-new">
                <div class="waste-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                        <line x1="12" y1="22.08" x2="12" y2="12" />
                    </svg>
                </div>
                <div class="waste-content">
                    <h3>Inventory</h3>
                    <p>Excess WIP accumulating between operations from poor flow design</p>
                </div>
            </div>
            <div class="waste-card-new">
                <div class="waste-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                </div>
                <div class="waste-content">
                    <h3>Over-Processing</h3>
                    <p>Redundant handling or rework caused by inefficient workspace organization</p>
                </div>
            </div>
            <div class="waste-card-new">
                <div class="waste-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18.36 6.64a9 9 0 1 1-12.73 0" />
                        <line x1="12" y1="2" x2="12" y2="12" />
                    </svg>
                </div>
                <div class="waste-content">
                    <h3>Energy Waste</h3>
                    <p>Inefficient HVAC, lighting, and utilities from suboptimal building orientation</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $hideFooterCTA = true; ?>
<!-- CTA Section -->
<section class="cta-section" style="padding: 6rem 0; background: #2f3030; position: relative; overflow: hidden;">
    <style>
        .cta-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.05;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .cta-bg-glow-1 {
            position: absolute;
            top: -30%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(233, 151, 56, 0.2) 0%, transparent 70%);
            /* Updated rgb */
            border-radius: 50%;
        }

        .cta-bg-glow-2 {
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(233, 151, 56, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .cta-container {
            position: relative;
            z-index: 2;
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
        }

        .cta-icon {
            width: 80px;
            height: 80px;
            background: rgba(233, 151, 56, 0.15);
            /* Updated rgb */
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
        }

        .cta-icon svg {
            width: 40px;
            height: 40px;
            color: var(--home-orange);
        }

        .cta-title {
            font-size: 3rem;
            font-weight: 800;
            color: white;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .cta-title span {
            color: var(--home-orange);
        }

        .cta-description {
            font-size: 1.25rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 2.5rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 1.25rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 4rem;
        }

        .cta-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, #e99738 0%, #f5a854 100%);
            /* Updated */
            color: white;
            padding: 1.125rem 2.5rem;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            /* Shadow removed */
        }

        .cta-btn-primary:hover {
            transform: translateY(-3px);
            /* Shadow removed */
            color: white;
        }

        .cta-btn-primary svg {
            width: 20px;
            height: 20px;
        }

        .cta-btn-secondary {
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

        .cta-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
        }

        .cta-btn-secondary svg {
            width: 18px;
            height: 18px;
            transition: transform 0.3s ease;
        }

        .cta-btn-secondary:hover svg {
            transform: translateX(4px);
        }

        .cta-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            padding-top: 3rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .cta-stat {
            text-align: center;
        }

        .cta-stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #e99738 0%, #f5a854 100%);
            /* Updated */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.35rem;
        }

        .cta-stat-label {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.7);
        }

        @media (max-width: 768px) {
            .cta-title {
                font-size: 2.25rem;
            }

            .cta-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
    <div class="cta-bg-pattern"></div>
    <div class="cta-bg-glow-1"></div>
    <div class="cta-bg-glow-2"></div>
    <div class="container">
        <div class="cta-container">
            <div class="cta-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                </svg>
            </div>
            <h2 class="cta-title">Ready to Design Your Factory <span>the Right Way?</span></h2>
            <p class="cta-description">
                Start with a complimentary Pulse Check. We'll visit your facility, understand your challenges, and show
                you exactly how LFB Architecture can transform your operations.
            </p>

            <div class="cta-buttons">
                <a href="<?php echo url('pulse-check.php'); ?>" class="cta-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                    Request Your Pulse Check
                </a>
                <a href="<?php echo url('process.php'); ?>" class="cta-btn-secondary">
                    Learn About Our Process
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="cta-stats">
                <div class="cta-stat">
                    <div class="cta-stat-value">2-4 Hrs</div>
                    <div class="cta-stat-label">Pulse Check Duration</div>
                </div>
                <div class="cta-stat">
                    <div class="cta-stat-value">100%</div>
                    <div class="cta-stat-label">Complimentary</div>
                </div>
                <div class="cta-stat">
                    <div class="cta-stat-value">24 Hrs</div>
                    <div class="cta-stat-label">Response Time</div>
                </div>
                <div class="cta-stat">
                    <div class="cta-stat-value">Zero</div>
                    <div class="cta-stat-label">Obligation</div>
                </div>
            </div>
        </div>
    </div>
</section>



<?php include 'includes/footer.php'; ?>