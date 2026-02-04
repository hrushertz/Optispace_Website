<?php
$currentPage = 'about';
$pageTitle = 'Leadership | Solutions OptiSpace';
$pageDescription = 'Solutions OptiSpace is led by practitioners who combine Lean, Six Sigma and factory architecture experience.';
$pageKeywords = 'Minish Umrani, OptiSpace leadership, lean six sigma experts, factory design leaders, LFB pioneers, manufacturing consultants leaders, industrial architecture experts, lean manufacturing expertise, OptiSpace founder, inside-out design pioneer';
include 'includes/header.php';

// Get database connection
require_once 'database/db_config.php';
$conn = getDBConnection();

// Fetch Active Banners
$bannerQuery = "SELECT * FROM banner_settings WHERE page_name = 'leadership' AND is_active = 1 ORDER BY sort_order ASC, created_at DESC";
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
        'eyebrow_text' => 'Meet Our Founder',
        'heading_html' => 'Leadership <span>& Vision</span>',
        'subheading' => "Practitioners who combine Lean, Six Sigma, and factory architecture experience to deliver extraordinary results."
    ];
}
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
        padding: 5rem 0 4rem;
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

    .leadership-hero-inner {
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
    .hero-slide-content .leadership-hero-text {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .hero-slide-content:not(.active) .hero-eyebrow,
    .hero-slide-content:not(.active) h1,
    .hero-slide-content:not(.active) .leadership-hero-text {
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

    .hero-slide-content.active .leadership-hero-text {
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
        padding: 4rem 0;
        background: #F8FAFC;
    }

    .leadership-container {
        max-width: 1100px;
        /* Optimal width for single reading card */
        margin: 0 auto;
        padding: 0 2rem;
    }

    .section-header-leadership {
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-header-leadership h2 {
        font-size: 2.5rem;
        color: var(--leadership-dark);
        margin-bottom: 1rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .section-header-leadership p {
        font-size: 1.15rem;
        color: var(--leadership-text);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Single Featured Profile Layout */
    .single-leader-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(226, 232, 240, 0.8);
        overflow: hidden;
        display: grid;
        grid-template-columns: 350px 1fr;
        /* Split Layout */
        margin-bottom: 4rem;
    }

    /* Left Column: Visual Identity */
    .leader-visual-col {
        background: #2f3030;
        padding: 3rem 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        color: white;
    }

    .leader-visual-col::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('assets/images/pattern-dark.svg');
        background-size: cover;
        opacity: 0.1;
    }

    .leader-profile-img-container {
        width: 220px;
        height: 220px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.15);
        padding: 4px;
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
        background: rgba(255, 255, 255, 0.05);
    }

    .leader-profile-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        background: #334155;
    }

    .leader-name-large {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        line-height: 1.2;
        letter-spacing: -0.5px;
        position: relative;
        z-index: 1;
        color: #ffffff !important;
    }

    .leader-title-large {
        font-size: 1.25rem;
        /* Larger title */
        color: var(--leadership-orange);
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        z-index: 1;
    }

    .leader-subtitle {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.95rem;
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
    }

    .leader-contact-info {
        margin-top: auto;
        width: 100%;
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .contact-item {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
    }

    /* Right Column: Content */
    .leader-content-col {
        padding: 3.5rem 3rem;
        display: flex;
        flex-direction: column;
    }

    .leader-intro-quote {
        font-size: 1.25rem;
        line-height: 1.6;
        color: var(--leadership-dark);
        font-style: italic;
        margin-bottom: 2.5rem;
        position: relative;
        padding-left: 1.5rem;
        border-left: 4px solid var(--leadership-orange);
    }

    .details-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }

    .detail-section h4 {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--leadership-text);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px solid var(--leadership-border);
        padding-bottom: 0.75rem;
    }

    .detail-section h4 svg {
        color: var(--leadership-orange);
    }

    .clean-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .clean-list li {
        margin-bottom: 1rem;
        font-size: 0.95rem;
        color: var(--leadership-dark);
        line-height: 1.5;
        position: relative;
        padding-left: 1.25rem;
    }

    .clean-list li::before {
        content: '•';
        position: absolute;
        left: 0;
        top: 0;
        color: var(--leadership-orange);
        font-weight: bold;
    }

    .skills-row {
        margin-top: auto;
    }

    .skill-pill {
        display: inline-block;
        padding: 0.4rem 1rem;
        background: #F1F5F9;
        color: var(--leadership-dark);
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .philosophy-text p {
        color: var(--leadership-text);
        line-height: 1.7;
        margin-bottom: 1rem;
    }

    /* What Drives Us Section */
    .leadership-section.alt-bg {
        background: #FAFBFC;
    }

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

    .drive-icon svg {
        width: 28px;
        height: 28px;
        color: var(--leadership-orange);
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
        padding: 4rem 0;
        background: #2f3030;
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
    @media (max-width: 900px) {
        .single-leader-card {
            grid-template-columns: 1fr;
            /* Stack */
        }

        .leader-visual-col {
            padding: 3rem 1.5rem;
        }

        .leader-content-col {
            padding: 2rem 1.5rem;
        }

        .details-row {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .drives-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .leadership-hero {
            padding: 5rem 0 4rem;
        }

        .leader-name-large {
            font-size: 1.75rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="leadership-hero">
    <!-- Slider Slides (Images) -->
    <div class="hero-slider-container">
        <?php foreach ($activeBanners as $index => $banner): ?>
            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                style="background-image: url('<?php echo !empty($banner['image_path']) ? url(htmlspecialchars($banner['image_path'])) : ''; ?>'); <?php echo empty($banner['image_path']) ? 'background: linear-gradient(165deg, #1E293B 0%, #334155 100%);' : ''; ?>"
                data-index="<?php echo $index; ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <div class="leadership-hero-inner">
        <div class="hero-content-wrapper">
            <?php foreach ($activeBanners as $index => $banner): ?>
                <div class="hero-slide-content <?php echo $index === 0 ? 'active' : ''; ?>"
                    data-index="<?php echo $index; ?>">
                    <div class="hero-eyebrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        <?php echo htmlspecialchars($banner['eyebrow_text']); ?>
                    </div>
                    <h1><?php echo $banner['heading_html']; ?></h1>
                    <p class="leadership-hero-text"><?php echo htmlspecialchars($banner['subheading']); ?></p>
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
<nav class="leadership-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li><a href="<?php echo url('about.php'); ?>">About</a></li>
        <li>Leadership</li>
    </ul>
</nav>

<!-- Single Featured Leader Section -->
<section class="leadership-section">
    <div class="leadership-container">

        <?php
        // Fetch Leaders - We optimize for the FIRST one
        $leadershipQuery = "SELECT * FROM leadership WHERE is_active = 1 ORDER BY sort_order ASC, id ASC LIMIT 1";
        $leadershipResult = $conn->query($leadershipQuery);
        $leader = null;

        if ($leadershipResult && $leadershipResult->num_rows > 0) {
            $leader = $leadershipResult->fetch_assoc();
        }

        if ($leader):
            // Parse JSON fields
            $education = !empty($leader['education_items']) ? json_decode($leader['education_items'], true) : [];
            $experience = !empty($leader['experience_items']) ? json_decode($leader['experience_items'], true) : [];
            $recognition = !empty($leader['recognition_items']) ? json_decode($leader['recognition_items'], true) : [];
            $skills = !empty($leader['skills']) ? array_map('trim', explode(',', $leader['skills'])) : [];
            ?>

            <div class="single-leader-card">
                <!-- Left: Visual Identity -->
                <div class="leader-visual-col">
                    <div class="leader-profile-img-container">
                        <?php if (!empty($leader['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($leader['image_path']); ?>"
                                alt="<?php echo htmlspecialchars($leader['name']); ?>" class="leader-profile-img">
                        <?php else: ?>
                            <div class="leader-profile-img" style="display:flex; align-items:center; justify-content:center;">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.2)"
                                    stroke-width="1">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h2 class="leader-name-large"><?php echo htmlspecialchars($leader['name']); ?></h2>
                    <div class="leader-title-large"><?php echo htmlspecialchars($leader['designation']); ?></div>
                    <?php if (!empty($leader['sub_designation'])): ?>
                        <div class="leader-subtitle"><?php echo htmlspecialchars($leader['sub_designation']); ?></div>
                    <?php endif; ?>

                    <div class="leader-contact-info">
                        <?php if (!empty($leader['location'])): ?>
                            <div class="contact-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                <span><?php echo htmlspecialchars($leader['location']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Right: Rich Content -->
                <div class="leader-content-col">
                    <!-- Quote/Intro -->
                    <?php if (!empty($leader['quote'])): ?>
                        <blockquote class="leader-intro-quote">
                            "<?php echo htmlspecialchars($leader['quote']); ?>"
                        </blockquote>
                    <?php endif; ?>

                    <!-- Two Column Details -->
                    <div class="details-row">
                        <!-- Experience -->
                        <?php if (!empty($experience)): ?>
                            <div class="detail-section">
                                <h4>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                    </svg>
                                    Experience
                                </h4>
                                <ul class="clean-list">
                                    <?php foreach ($experience as $item): ?>
                                        <li><?php echo htmlspecialchars($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Education -->
                        <?php if (!empty($education)): ?>
                            <div class="detail-section">
                                <h4>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                                    </svg>
                                    Education
                                </h4>
                                <ul class="clean-list">
                                    <?php foreach ($education as $item): ?>
                                        <li><?php echo htmlspecialchars($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Philosophy/Bio -->
                    <?php if (!empty($leader['philosophy_content'])): ?>
                        <div class="detail-section" style="margin-bottom:2rem;">
                            <h4>Philosophy & Approach</h4>
                            <div class="philosophy-text">
                                <?php echo $leader['philosophy_content']; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Skills -->
                    <?php if (!empty($skills)): ?>
                        <div class="skills-row">
                            <?php foreach ($skills as $skill):
                                if (trim($skill)): ?>
                                    <span class="skill-pill"><?php echo htmlspecialchars(trim($skill)); ?></span>
                                <?php endif; endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <div class="text-center">
                <p>Leadership profile is being updated.</p>
            </div>
        <?php endif; ?>

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
                        <path
                            d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                    </svg>
                </div>
                <h3>Practitioner, Not Theorist</h3>
                <p>Minish is a practitioner who has worked hands-on with over 250 businesses across 75+ industrial
                    segments. He brings real-world experience, not just academic knowledge.</p>
            </div>
            <div class="drive-card">
                <div class="drive-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                        <line x1="9" y1="9" x2="9.01" y2="9" />
                        <line x1="15" y1="9" x2="15.01" y2="9" />
                    </svg>
                </div>
                <h3>Innovation & Excellence</h3>
                <p>Under Minish's leadership, OptiSpace has pioneered the Inside-Out Design approach — designing the
                    process first, then building the factory around it.</p>
            </div>
            <div class="drive-card">
                <div class="drive-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <h3>Client-Centric Focus</h3>
                <p>Every project is approached with a deep understanding of the client's business challenges and
                    aspirations. Success is measured by client results, not just project completion.</p>
            </div>
        </div>
    </div>
</section>

<?php $hideFooterCTA = true; ?>
<!-- CTA Section -->
<section class="leadership-cta">
    <div class="leadership-cta-inner">
        <h2>Ready to Work With Our Team?</h2>
        <p>Connect with our experienced leadership team. Start with a complimentary Pulse Check and discover the
            OptiSpace difference.</p>
        <div class="cta-buttons">
            <a href="<?php echo url('pulse-check.php'); ?>" class="btn-cta-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                Request Your Pulse Check
            </a>
            <a href="<?php echo url('team.php'); ?>" class="btn-cta-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                Meet Our Team
            </a>
        </div>
    </div>
</section>

<?php
$hideFooterCTA = true;
include 'includes/footer.php';
?>