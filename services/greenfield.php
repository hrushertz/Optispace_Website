<?php
$currentPage = 'greenfield';
$pageTitle = 'New Factory Design & Architecture (Greenfield) | Solutions OptiSpace';
$pageDescription = 'Complete architectural and engineering services for new factory construction, including lean design, compliance, and 3D visualization.';
$pageKeywords = 'greenfield factory design, new factory construction, new plant design, factory architecture, greenfield project, new manufacturing facility, factory construction planning, lean greenfield design, new factory layout, industrial building design, greenfield LFB, new plant architecture';
include '../includes/header.php';

// Get database connection
require_once '../database/db_config.php';
$conn = getDBConnection();

// Fetch Active Banners for Greenfield Page
$bannerQuery = "SELECT * FROM banner_settings WHERE page_name = 'greenfield' AND is_active = 1 ORDER BY sort_order ASC, created_at DESC";
$bannerResult = $conn->query($bannerQuery);
$activeBanners = [];
if ($bannerResult && $bannerResult->num_rows > 0) {
    while ($row = $bannerResult->fetch_assoc()) {
        $activeBanners[] = $row;
    }
}

// Fallback if no active banners
if (empty($activeBanners)) {
    $activeBanners[] = [
        'image_path' => '', // Will fallback to gradient
        'eyebrow_text' => 'Greenfield Projects',
        'heading_html' => 'Greenfield Factory Design <span>with LFB</span>',
        'subheading' => 'Design your process and flow first, then create a building that supports peak efficiency from day one'
    ];
}
?>

<style>
    /* ========================================
   GREENFIELD PAGE - MODERN CLEAN DESIGN
   ======================================== */

    :root {
        --greenfield-orange: #E99431;
        --greenfield-orange-light: rgba(233, 148, 49, 0.08);
        --greenfield-blue: #3B82F6;
        --greenfield-green: #10B981;
        --greenfield-gray: #64748B;
        --greenfield-dark: #1E293B;
        --greenfield-text: #475569;
        --greenfield-border: #E2E8F0;
    }

    /* Hero Section */
    .greenfield-hero {
        /* Fallback background if image fails to load or empty */
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

    .greenfield-hero-inner {
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

    .greenfield-hero-content {
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
        /* Quick exit */
    }

    .hero-slide-content.active {
        opacity: 1;
        pointer-events: auto;
        z-index: 2;
    }

    /* Staggered Child Animations */
    .hero-slide-content .hero-eyebrow,
    .hero-slide-content h1,
    .hero-slide-content .greenfield-hero-text,
    .hero-slide-content .hero-stats {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Reset state (outgoing) */
    .hero-slide-content:not(.active) .hero-eyebrow,
    .hero-slide-content:not(.active) h1,
    .hero-slide-content:not(.active) .greenfield-hero-text,
    .hero-slide-content:not(.active) .hero-stats {
        transform: translateY(-10px);
        opacity: 0;
        transition: all 0.4s ease;
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

    .hero-slide-content.active .greenfield-hero-text {
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
        background: rgba(233, 148, 49, 0.15);
        color: #E99431;
        padding: 0.5rem 1rem;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 1.5rem;
    }

    .greenfield-hero h1 {
        font-size: 3.25rem;
        font-weight: 700;
        color: white;
        line-height: 1.15;
        margin-bottom: 1.5rem;
    }

    .greenfield-hero h1 span {
        color: #E99431;
    }

    .greenfield-hero-text {
        font-size: 1.2rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.75);
        max-width: 500px;
        margin-bottom: 2rem;
    }

    .hero-stats {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
    }

    .hero-stat {
        text-align: left;
    }

    .hero-stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--greenfield-orange);
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

    @media (max-width: 968px) {
        .greenfield-hero-inner {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .greenfield-hero-content {
            text-align: center;
        }

        .greenfield-hero-text {
            max-width: 100%;
            margin: 0 auto 2rem;
        }

        .hero-stats {
            justify-content: center;
        }
    }

    /* Breadcrumb */
    .greenfield-breadcrumb {
        background: #F8FAFC;
        padding: 1rem 0;
        border-bottom: 1px solid var(--greenfield-border);
    }

    .greenfield-breadcrumb ul {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        list-style: none;
        display: flex;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .greenfield-breadcrumb a {
        color: var(--greenfield-gray);
        text-decoration: none;
    }

    .greenfield-breadcrumb a:hover {
        color: var(--greenfield-orange);
    }

    .greenfield-breadcrumb li:last-child {
        color: var(--greenfield-dark);
        font-weight: 500;
    }

    .greenfield-breadcrumb li:not(:last-child)::after {
        content: '/';
        margin-left: 0.5rem;
        color: var(--greenfield-border);
    }
</style>

<!-- Hero Section -->
<section class="greenfield-hero">
    <!-- Slider Slides (Images) -->
    <div class="hero-slider-container">
        <?php foreach ($activeBanners as $index => $banner): ?>
            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                style="background-image: url('<?php echo !empty($banner['image_path']) ? htmlspecialchars('../' . $banner['image_path']) : ''; ?>'); <?php echo empty($banner['image_path']) ? 'background: linear-gradient(165deg, #1E293B 0%, #334155 100%);' : ''; ?>"
                data-index="<?php echo $index; ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <div class="greenfield-hero-inner">
        <div class="greenfield-hero-content">
            <?php foreach ($activeBanners as $index => $banner): ?>
                <div class="hero-slide-content <?php echo $index === 0 ? 'active' : ''; ?>"
                    data-index="<?php echo $index; ?>">
                    <div class="hero-eyebrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <?php echo htmlspecialchars($banner['eyebrow_text']); ?>
                    </div>
                    <h1><?php echo $banner['heading_html']; ?></h1>
                    <p class="greenfield-hero-text"><?php echo htmlspecialchars($banner['subheading']); ?></p>

                </div>
            <?php endforeach; ?>
        </div>
        <div class="hero-visual">
            <!-- Feature Grid removed as requested -->
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

                // Set interval (5000ms)
                sliderInterval = setInterval(nextSlide, 5000);

                // Dot Click Events
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
<nav class="greenfield-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li><a href="#">Services</a></li>
        <li>Greenfield Projects</li>
    </ul>
</nav>

<style>
    /* Section Styling */
    .greenfield-section {
        padding: 6rem 0;
        background: white;
    }

    .greenfield-section.section-light {
        background: #FAFBFC;
    }

    .greenfield-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .greenfield-section-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .greenfield-section-header h2 {
        font-size: 2.5rem;
        color: var(--greenfield-dark);
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .greenfield-section-header p {
        font-size: 1.15rem;
        color: var(--greenfield-text);
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.7;
    }

    .intro-card {
        background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
        border-left: 4px solid var(--greenfield-orange);
        border-radius: 16px;
        padding: 3rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    .intro-card h2 {
        font-size: 2rem;
        color: var(--greenfield-dark);
        margin: 0 0 2.5rem 0;
        text-align: center;
        font-weight: 700;
    }

    .intro-features {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-bottom: 2.5rem;
    }

    .intro-feature {
        text-align: center;
    }

    .intro-feature-icon {
        width: 56px;
        height: 56px;
        background: var(--greenfield-orange-light);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .intro-feature-icon svg {
        width: 28px;
        height: 28px;
        color: var(--greenfield-orange);
    }

    .intro-feature p {
        margin: 0;
        line-height: 1.6;
        color: var(--greenfield-text);
        font-size: 1rem;
    }

    .btn-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-primary-modern {
        background: var(--greenfield-orange);
        color: white;
    }

    .btn-primary-modern:hover {
        background: #d4851c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(233, 148, 49, 0.3);
    }

    @media (max-width: 968px) {
        .intro-features {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .greenfield-section {
            padding: 4rem 0;
        }

        .greenfield-section-header h2 {
            font-size: 2rem;
        }

        .intro-card {
            padding: 2rem;
        }
    }
</style>

<section class="greenfield-section">
    <div class="greenfield-container">
        <div class="intro-card">
            <h2>Design Your Ideal Factory from Day One</h2>
            <div class="intro-features">
                <div class="intro-feature">
                    <div class="intro-feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                        </svg>
                    </div>
                    <p>Flow‑first layouts aligned to value streams</p>
                </div>
                <div class="intro-feature">
                    <div class="intro-feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <p>Integrated architecture, structure, and MEP designed around Lean principles</p>
                </div>
                <div class="intro-feature">
                    <div class="intro-feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <p>Future‑ready designs prepared for expansion and automation</p>
                </div>
            </div>
            <div style="text-align: center;">
                <a href="<?php echo url('pulse-check.php'); ?>" class="btn-modern btn-primary-modern">Discuss My
                    Greenfield Project</a>
            </div>
        </div>
    </div>
</section>

<style>
    .content-card {
        background: white;
        border: 1px solid var(--greenfield-border);
        border-radius: 12px;
        padding: 2rem;
        height: 100%;
        transition: all 0.3s ease;
    }

    .content-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        border-color: var(--greenfield-orange);
    }

    .content-card h3 {
        font-size: 1.35rem;
        color: var(--greenfield-dark);
        margin: 0 0 1.25rem 0;
        font-weight: 600;
    }

    .content-card p {
        font-size: 1rem;
        line-height: 1.7;
        color: var(--greenfield-text);
        margin-bottom: 1.25rem;
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
        color: var(--greenfield-text);
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .content-card ul li::before {
        content: '✓';
        color: var(--greenfield-orange);
        font-weight: 700;
        flex-shrink: 0;
    }

    .content-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }

    .highlight-text {
        margin: 0;
        font-weight: 600;
        color: var(--greenfield-orange);
        font-size: 1rem;
    }

    @media (max-width: 968px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="greenfield-section section-light">
    <div class="greenfield-container">
        <div class="greenfield-section-header">
            <h2>Why Greenfield Projects Need LFB</h2>
            <p>A new factory is a once‑in‑decades decision with irreversible consequences. It is the single best
                opportunity to build efficiency into your DNA. LFB ensures your plot usage, building orientation, grids,
                and utilities are driven by flow and Lean logic, not by standard architectural templates.</p>
        </div>

        <div class="content-grid">
            <div class="content-card">
                <h3>The Greenfield Advantage</h3>
                <p>A new factory is a blank canvas and your once-in-a-lifetime opportunity. With no existing
                    constraints, we can design the perfect production flow first, then wrap the ideal building around
                    it.</p>
                <ul>
                    <li>Avoid layouts that lock in high material handling and energy costs for the life of the plant
                    </li>
                    <li>Keep future lines and expansions structurally feasible without demolition</li>
                    <li>Align land use, docking, and circulation with actual product and logistics flows</li>
                    <li>Embed 5S and safety standards into the physical design from day one</li>
                </ul>
            </div>
            <div class="content-card">
                <h3>The Stakes Are High</h3>
                <p>Every decision you make now will impact operations for decades. Poor early decisions become permanent
                    constraints that:</p>
                <ul>
                    <li>Force inefficient material flows that waste time and money daily</li>
                    <li>Require expensive material handling systems to compensate for layout problems</li>
                    <li>Block future automation and expansion opportunities</li>
                    <li>Create safety and quality issues that are structurally embedded</li>
                </ul>
                <p class="highlight-text">LFB gets it right from the beginning, preventing these locked-in
                    inefficiencies.</p>
            </div>
        </div>
    </div>
</section>

<style>
    .process-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .process-step-card {
        background: white;
        border: 1px solid var(--greenfield-border);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .process-step-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        transform: translateY(-4px);
        border-color: var(--greenfield-orange);
    }

    .step-number {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--greenfield-orange) 0%, #f5a854 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 auto 1.5rem;
        box-shadow: 0 4px 12px rgba(233, 148, 49, 0.3);
    }

    .process-step-card h3 {
        font-size: 1.15rem;
        color: var(--greenfield-dark);
        margin: 0 0 1rem 0;
        font-weight: 600;
    }

    .process-step-card p {
        margin: 0;
        line-height: 1.7;
        color: var(--greenfield-text);
        font-size: 0.95rem;
    }

    @media (max-width: 968px) {
        .process-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .process-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="greenfield-section">
    <div class="greenfield-container">
        <div class="greenfield-section-header">
            <h2>Our Greenfield Process</h2>
            <p>A systematic approach that places flow before form</p>
        </div>

        <div class="process-grid">
            <div class="process-step-card">
                <div class="step-number">1</div>
                <h3>Process & Flow Analysis</h3>
                <p>Understand product families, volumes, and value streams to define the ideal flow before drawing
                    lines.</p>
            </div>

            <div class="process-step-card">
                <div class="step-number">2</div>
                <h3>Lean Layout Options</h3>
                <p>Develop multiple layout options that minimize movement, waiting, and congestion.</p>
            </div>

            <div class="process-step-card">
                <div class="step-number">3</div>
                <h3>Architecture Around Flow</h3>
                <p>Define building envelope, grids, heights, and entries specifically around the chosen Lean layout.</p>
            </div>

            <div class="process-step-card">
                <div class="step-number">4</div>
                <h3>Integrated Engineering</h3>
                <p>Structure and MEP systems designed to support flow, safety, and energy efficiency.</p>
            </div>

            <div class="process-step-card">
                <div class="step-number">5</div>
                <h3>Compliance & Approvals</h3>
                <p>Ensure plans meet all statutory and industrial regulations seamlessly.</p>
            </div>

            <div class="process-step-card">
                <div class="step-number">6</div>
                <h3>Visualization & Support</h3>
                <p>Use 3D walkthroughs to align stakeholders and provide on-site checks to ensure the built factory
                    matches the Lean intent.</p>
            </div>
        </div>
    </div>
</section>

<style>
    .services-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .service-card {
        background: white;
        border: 1px solid var(--greenfield-border);
        border-radius: 12px;
        padding: 2rem;
        height: 100%;
        transition: all 0.3s ease;
    }

    .service-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        border-color: var(--greenfield-orange);
    }

    .service-card h3 {
        font-size: 1.25rem;
        color: var(--greenfield-dark);
        margin: 0 0 1.25rem 0;
        font-weight: 600;
    }

    .service-card>p {
        font-size: 0.95rem;
        color: var(--greenfield-text);
        margin-bottom: 1rem;
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
        color: var(--greenfield-text);
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .service-card ul li::before {
        content: '→';
        color: var(--greenfield-orange);
        font-weight: 700;
        flex-shrink: 0;
    }

    @media (max-width: 968px) {
        .services-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Supervision Card */
    .supervision-card {
        background: white;
        border: 1px solid var(--greenfield-border);
        border-radius: 16px;
        padding: 3rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    .supervision-content h3 {
        font-size: 1.25rem;
        color: var(--greenfield-dark);
        margin: 0 0 1.25rem 0;
        font-weight: 600;
    }

    .supervision-content ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .supervision-content ul li {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.5rem 0;
        color: var(--greenfield-text);
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .supervision-content ul li::before {
        content: '✓';
        color: var(--greenfield-orange);
        font-weight: 700;
        flex-shrink: 0;
    }

    .supervision-note {
        margin-top: 2rem;
        margin-bottom: 0;
        font-style: italic;
        color: var(--greenfield-gray);
        line-height: 1.7;
        padding-top: 1.5rem;
        border-top: 1px solid var(--greenfield-border);
    }
</style>

<section class="greenfield-section section-light">
    <div class="greenfield-container">
        <div class="greenfield-section-header">
            <h2>Core Architectural & Engineering Services</h2>
            <p>You get a single partner for concept, design and supervision so responsibilities are clear</p>
        </div>

        <div class="services-grid">
            <div class="service-card">
                <h3>Architectural Drawings</h3>
                <p>Complete design documentation with:</p>
                <ul>
                    <li>Column grids, spans, and levels coordinated with equipment and flow</li>
                    <li>Site plans and plot layouts</li>
                    <li>Floor plans (all levels)</li>
                    <li>Elevations and sections</li>
                    <li>Door, window, and finish schedules</li>
                </ul>
            </div>
            <div class="service-card">
                <h3>Structural Design</h3>
                <p>Engineering for strength and flexibility:</p>
                <ul>
                    <li>Structure designed to support cranes, mezzanines, and future lines without rework</li>
                    <li>Foundation design for equipment loads</li>
                    <li>Column and beam optimization</li>
                    <li>Roof structure and load calculations</li>
                    <li>Expansion provisions</li>
                </ul>
            </div>
            <div class="service-card">
                <h3>MEP Engineering</h3>
                <p>Utilities that enable operations:</p>
                <ul>
                    <li>Utilities routed to support flow and flexibility, not just shortest pipe run</li>
                    <li>Electrical distribution and lighting</li>
                    <li>Plumbing and drainage systems</li>
                    <li>HVAC design and zoning</li>
                    <li>Fire protection systems</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="greenfield-section">
    <div class="greenfield-container">
        <div class="greenfield-section-header">
            <h2>Specialized Compliance & Planning</h2>
            <p>Navigating regulatory requirements and approvals</p>
        </div>

        <div class="content-grid" style="margin-bottom: 2rem;">
            <div class="content-card">
                <h3>Statutory Approvals</h3>
                <p>We prepare and deliver complete drawing packages for all required approvals:</p>
                <ul>
                    <li>Municipal building permissions</li>
                    <li>Industrial area approvals</li>
                    <li>Environmental clearances</li>
                    <li>Fire NOC documentation</li>
                    <li>Occupancy certificates</li>
                </ul>
            </div>
            <div class="content-card">
                <h3>Factory Act Compliance</h3>
                <p>Ensuring your facility meets all industrial regulations:</p>
                <ul>
                    <li>Factory Act requirements</li>
                    <li>Safety and welfare provisions</li>
                    <li>Sanitation standards</li>
                    <li>Worker amenity spaces</li>
                    <li>Emergency egress planning</li>
                </ul>
            </div>
        </div>
        <div class="content-grid">
            <div class="content-card">
                <h3>Vastu Shastra Integration</h3>
                <p>For clients who value Vastu principles:</p>
                <ul>
                    <li>Building orientation per Vastu</li>
                    <li>Zone allocation (North, East, etc.)</li>
                    <li>Entry and exit placement</li>
                    <li>Balance with functional requirements</li>
                </ul>
            </div>
            <div class="content-card">
                <h3>BCP Development</h3>
                <p>Building Construction Plan for civil contractors:</p>
                <ul>
                    <li>Construction methodology</li>
                    <li>Material specifications</li>
                    <li>Quality control measures</li>
                    <li>Architect inspection levels</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="greenfield-section section-light">
    <div class="greenfield-container">
        <div class="greenfield-section-header">
            <h2>Visualization & Interior Design</h2>
            <p>Bringing your factory to life before construction begins</p>
        </div>

        <div class="content-grid">
            <div class="content-card">
                <h3>3D Visualization</h3>
                <p>High-quality 3D renderings and walkthroughs that help you:</p>
                <ul>
                    <li>Visualize the completed facility</li>
                    <li>Communicate vision to stakeholders</li>
                    <li>Identify design issues early</li>
                    <li>Make confident decisions</li>
                    <li>Present to investors and partners</li>
                </ul>
            </div>
            <div class="content-card">
                <h3>Interior Design</h3>
                <p>Professional design for non-production areas:</p>
                <ul>
                    <li>Office and administrative spaces</li>
                    <li>Conference and meeting rooms</li>
                    <li>Cafeteria and break areas</li>
                    <li>Reception and visitor areas</li>
                    <li>Landscaping and external aesthetics</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="greenfield-section">
    <div class="greenfield-container">
        <div class="greenfield-section-header">
            <h2>5S Concepts Embedded in Design</h2>
            <p>Building lean principles into the physical structure</p>
        </div>

        <div class="services-grid">
            <div class="service-card">
                <h3>Sort & Set in Order</h3>
                <p>Line‑side stocking, supermarkets, and staging areas defined clearly in the layout. Every item has a
                    designated home built into the design.</p>
            </div>
            <div class="service-card">
                <h3>Shine & Standardize</h3>
                <p>Clear access for cleaning, concealed but maintainable services, and uniform light levels.
                    Easy-to-clean surfaces and standardized design throughout.</p>
            </div>
            <div class="service-card">
                <h3>Sustain & Safety</h3>
                <p>Visual lanes, markings, and ergonomics planned in drawings—not added later with paint. Safety
                    features and good practices become structurally easier to maintain.</p>
            </div>
        </div>
    </div>
</section>

<section class="greenfield-section section-light">
    <div class="greenfield-container">
        <div class="greenfield-section-header">
            <h2>Construction Supervision</h2>
            <p>Supervision ensures that the final built factory preserves the clearances, adjacencies, and lines of flow
                agreed in the LFB design</p>
        </div>

        <div class="supervision-card">
            <div class="content-grid">
                <div class="supervision-content">
                    <h3>Design Intent Protection</h3>
                    <ul>
                        <li>Regular site visits and inspections</li>
                        <li>Verification of critical dimensions and clearances</li>
                        <li>Flow path preservation during construction</li>
                        <li>Coordination with contractors</li>
                        <li>RFI responses and technical clarifications</li>
                    </ul>
                </div>
                <div class="supervision-content">
                    <h3>Quality Assurance</h3>
                    <ul>
                        <li>Construction quality verification</li>
                        <li>Compliance with LFB design intent</li>
                        <li>Material and workmanship checks</li>
                        <li>Punch list management</li>
                        <li>Handover support and documentation</li>
                    </ul>
                </div>
            </div>
            <p class="supervision-note">Our supervision ensures the built factory performs exactly as designed, with no
                compromises to the flow logic that drives operational efficiency.</p>
        </div>
    </div>
</section>

<style>
    .greenfield-cta {
        padding: 6rem 0;
        background: #2f3030;
        position: relative;
        overflow: hidden;
    }

    .greenfield-cta::before {
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
        background: var(--greenfield-orange);
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 2px solid var(--greenfield-orange);
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
        .greenfield-cta {
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

<?php $hideFooterCTA = true; ?>
<section class="greenfield-cta">
    <div class="greenfield-container">
        <div class="cta-inner">
            <h2>Ready to Build Your Ideal Factory?</h2>
            <p>Start with a complimentary LFB Pulse Check for your greenfield project. Design the process first, then
                the building—optimize flow, layout, and expansion before locking your design.</p>
            <div class="cta-buttons">
                <a href="<?php echo url('pulse-check.php'); ?>" class="btn-cta-primary">
                    Request Greenfield Assessment
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="<?php echo url('process.php'); ?>" class="btn-cta-secondary">
                    See Our Process
                </a>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>