<?php
$currentPage = 'about';
$pageTitle = 'Who We Are | Solutions OptiSpace';
$pageDescription = 'Lean manufacturing consultants who design buildings around your process. A division of Solutions KMS with 20 years of operational excellence experience.';
$pageKeywords = 'Solutions OptiSpace, Solutions KMS, lean manufacturing consultants, factory design experts, operational excellence, lean consultants India, manufacturing consultants, industrial architects, LFB experts, Minish Umrani, factory optimization consultants, lean six sigma, process optimization';
include 'includes/header.php';

// Get database connection
require_once 'database/db_config.php';
$conn = getDBConnection();

// Fetch Active Banners
$bannerQuery = "SELECT * FROM banner_settings WHERE page_name = 'about' AND is_active = 1 ORDER BY sort_order ASC, created_at DESC";
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
        'eyebrow_text' => 'Since 2004',
        'heading_html' => 'Lean Experts Who <span>Design Factories</span>',
        'subheading' => "We're not traditional architects. We're lean manufacturing consultants who understand that the building should serve the process — not the other way around."
    ];
}
?>

<style>
    /* ========================================
   ABOUT PAGE - MODERN CLEAN DESIGN
   ======================================== */

    :root {
        --about-orange: #E99431;
        --about-orange-light: rgba(233, 148, 49, 0.08);
        --about-blue: #3B82F6;
        --about-blue-light: rgba(59, 130, 246, 0.08);
        --about-green: #10B981;
        --about-green-light: rgba(16, 185, 129, 0.08);
        --about-dark: #1E293B;
        --about-text: #475569;
        --about-border: #E2E8F0;
    }

    /* Hero Section */
    .about-hero {
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

    .about-hero-inner {
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

    .about-hero-content {
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
    .hero-slide-content .about-hero-text {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .hero-slide-content:not(.active) .hero-eyebrow,
    .hero-slide-content:not(.active) h1,
    .hero-slide-content:not(.active) .about-hero-text {
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

    .hero-slide-content.active .about-hero-text {
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

    .about-hero h1 {
        font-size: 3.25rem;
        font-weight: 700;
        color: white;
        line-height: 1.15;
        margin-bottom: 1.5rem;
    }

    .about-hero h1 span {
        color: #E99431;
    }

    .about-hero-text {
        font-size: 1.2rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.75);
        margin-bottom: 0;
        max-width: 500px;
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

    /* Breadcrumb */
    .about-breadcrumb {
        background: #F8FAFC;
        padding: 1rem 0;
        border-bottom: 1px solid var(--about-border);
    }

    .about-breadcrumb ul {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        list-style: none;
        display: flex;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .about-breadcrumb a {
        color: var(--about-text);
        text-decoration: none;
    }

    .about-breadcrumb a:hover {
        color: var(--about-orange);
    }

    .about-breadcrumb li:last-child {
        color: var(--about-dark);
        font-weight: 500;
    }

    .about-breadcrumb li:not(:last-child)::after {
        content: '/';
        margin-left: 0.5rem;
        color: var(--about-border);
    }

    /* Section Styles */
    .about-section {
        padding: 6rem 0;
    }

    .about-section.alt-bg {
        background: #FAFBFC;
    }

    .about-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .section-header-about {
        text-align: center;
        margin-bottom: 4rem;
    }

    .section-header-about h2 {
        font-size: 2.5rem;
        color: var(--about-dark);
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .section-header-about p {
        font-size: 1.15rem;
        color: var(--about-text);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.7;
    }

    /* Heritage Grid */
    .heritage-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 3rem;
        align-items: start;
    }

    .heritage-content h3 {
        font-size: 1.75rem;
        color: var(--about-dark);
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .heritage-content p {
        font-size: 1.05rem;
        color: var(--about-text);
        line-height: 1.8;
        margin-bottom: 1.5rem;
    }

    .heritage-content p strong {
        color: var(--about-dark);
    }

    .heritage-highlight {
        background: var(--about-orange-light);
        border-left: 4px solid var(--about-orange);
        padding: 1.5rem;
        border-radius: 0 12px 12px 0;
        margin-top: 2rem;
    }

    .heritage-highlight p {
        margin: 0;
        font-size: 1rem;
        color: var(--about-text);
    }

    .heritage-highlight strong {
        color: var(--about-dark);
    }

    /* Stats Card */
    .stats-card-modern {
        background: white;
        border: 1px solid var(--about-border);
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    .stats-card-modern h3 {
        font-size: 1.5rem;
        color: var(--about-dark);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--about-border);
    }

    .stats-grid-modern {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: #F8FAFC;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background: var(--about-orange-light);
    }

    .stat-value {
        font-size: 2.75rem;
        font-weight: 700;
        color: var(--about-orange);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--about-text);
        font-weight: 500;
    }

    /* Value Props */
    .value-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .value-card {
        background: white;
        border: 1px solid var(--about-border);
        border-radius: 16px;
        padding: 2.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .value-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--about-orange);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .value-card:hover {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        transform: translateY(-4px);
    }

    .value-card:hover::before {
        transform: scaleX(1);
    }

    .value-icon {
        width: 56px;
        height: 56px;
        background: var(--about-orange-light);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .value-card:nth-child(2) .value-icon {
        background: var(--about-blue-light);
    }

    .value-card:nth-child(3) .value-icon {
        background: var(--about-green-light);
    }

    .value-icon svg {
        width: 28px;
        height: 28px;
        color: var(--about-orange);
    }

    .value-card:nth-child(2) .value-icon svg {
        color: var(--about-blue);
    }

    .value-card:nth-child(3) .value-icon svg {
        color: var(--about-green);
    }

    .value-card h3 {
        font-size: 1.25rem;
        color: var(--about-dark);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .value-card p {
        font-size: 0.95rem;
        color: var(--about-text);
        line-height: 1.7;
        margin: 0;
    }

    /* Approach Grid */
    .approach-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .approach-card {
        background: white;
        border: 1px solid var(--about-border);
        border-radius: 12px;
        padding: 2rem;
        display: flex;
        gap: 1.25rem;
        transition: all 0.3s ease;
    }

    .approach-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        border-color: transparent;
    }

    .approach-num {
        width: 44px;
        height: 44px;
        background: var(--about-orange-light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--about-orange);
        flex-shrink: 0;
    }

    .approach-content h3 {
        font-size: 1.15rem;
        color: var(--about-dark);
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .approach-content p {
        font-size: 0.95rem;
        color: var(--about-text);
        line-height: 1.7;
        margin: 0;
    }

    /* Integration Banner */
    .integration-banner {
        background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
        border: 1px solid var(--about-border);
        border-radius: 16px;
        padding: 3rem;
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-top: 3rem;
    }

    .integration-icon {
        width: 72px;
        height: 72px;
        background: var(--about-orange-light);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .integration-icon svg {
        width: 36px;
        height: 36px;
        color: var(--about-orange);
    }

    .integration-content h3 {
        font-size: 1.5rem;
        color: var(--about-dark);
        margin-bottom: 0.5rem;
    }

    .integration-content p {
        font-size: 1rem;
        color: var(--about-text);
        line-height: 1.7;
        margin: 0;
    }

    /* CTA Section */
    .about-cta {
        padding: 6rem 0;
        background: #2f3030;
        position: relative;
        overflow: hidden;
    }

    .about-cta::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.05;
        background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
    }

    .about-cta-inner {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 2rem;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .about-cta h2 {
        font-size: 2.75rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .about-cta p {
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.7;
        margin-bottom: 2.5rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
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
        background: var(--about-orange);
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
    @media (max-width: 1024px) {
        .about-hero-inner {
            grid-template-columns: 1fr;
            gap: 3rem;
            text-align: center;
        }

        .about-hero-text {
            margin: 0 auto 2rem;
        }

        .hero-stats {
            justify-content: center;
        }

        .heritage-grid {
            grid-template-columns: 1fr;
        }

        .value-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .about-hero {
            padding: 5rem 0 4rem;
        }

        .about-hero h1 {
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

        .hero-cards-preview {
            grid-template-columns: 1fr;
            max-width: 280px;
        }

        .value-grid,
        .approach-grid {
            grid-template-columns: 1fr;
        }

        .integration-banner {
            flex-direction: column;
            text-align: center;
            padding: 2rem;
        }

        .stats-grid-modern {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero Section -->
<section class="about-hero">
    <!-- Slider Slides (Images) -->
    <div class="hero-slider-container">
        <?php foreach ($activeBanners as $index => $banner): ?>
            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                style="background-image: url('<?php echo !empty($banner['image_path']) ? htmlspecialchars($banner['image_path']) : ''; ?>'); <?php echo empty($banner['image_path']) ? 'background: linear-gradient(165deg, #1E293B 0%, #334155 100%);' : ''; ?>"
                data-index="<?php echo $index; ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <div class="about-hero-inner">
        <div class="about-hero-content">
            <?php foreach ($activeBanners as $index => $banner): ?>
                <div class="hero-slide-content <?php echo $index === 0 ? 'active' : ''; ?>"
                    data-index="<?php echo $index; ?>">
                    <div class="hero-eyebrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        <?php echo htmlspecialchars($banner['eyebrow_text']); ?>
                    </div>
                    <h1><?php echo $banner['heading_html']; ?></h1>
                    <p class="about-hero-text"><?php echo htmlspecialchars($banner['subheading']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="hero-visual">
            <!-- Redundant Visual Cards Removed -->
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
<nav class="about-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li>About Us</li>
    </ul>
</nav>

<!-- Heritage Section -->
<section class="about-section">
    <div class="about-container">
        <div class="heritage-grid">
            <div class="heritage-content">
                <h3>Heritage & Identity</h3>
                <p><strong>OptiSpace is not a startup;</strong> it is a specialized division of Solutions Kaizen
                    Management Systems (Solutions KMS). With over 20 years of experience in Lean, Six Sigma, TOC, and
                    TPM, we bring deep operational expertise to the world of architecture.</p>
                <p>Solutions KMS has been enhancing the top and bottom lines of businesses through proven methodologies.
                    Solutions OptiSpace brings this operational expertise into the architectural realm, creating the
                    unique "inside-out" design approach that sets us apart.</p>

                <div class="heritage-highlight">
                    <p><strong>What makes us different:</strong> Traditional architects design buildings first, then you
                        fit your operations inside. We flip that — designing your optimized process first, then building
                        around it.</p>
                </div>
            </div>
            <div class="stats-card-modern">
                <h3>Our Track Record</h3>
                <div class="stats-grid-modern">
                    <div class="stat-item">
                        <div class="stat-value">20+</div>
                        <div class="stat-label">Years of Excellence</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">250+</div>
                        <div class="stat-label">Businesses Served</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">75+</div>
                        <div class="stat-label">Industrial Segments</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">100%</div>
                        <div class="stat-label">Satisfaction Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Value Proposition Section -->
<section class="about-section alt-bg">
    <div class="about-container">
        <div class="section-header-about">
            <h2>Our Unique Value Proposition</h2>
            <p>What makes us different from traditional architects and consultants</p>
        </div>

        <div class="value-grid">
            <div class="value-card">
                <div class="value-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M12 1v6m0 6v10" />
                        <path d="M21 12h-6m-6 0H1" />
                        <path d="M18.36 5.64l-4.24 4.24" />
                        <path d="M9.88 14.12l-4.24 4.24" />
                        <path d="M5.64 5.64l4.24 4.24" />
                        <path d="M14.12 14.12l4.24 4.24" />
                    </svg>
                </div>
                <h3>Operations-First Mindset</h3>
                <p>We don't just design buildings — we optimize operations and then design the building to support them.
                    Our team understands manufacturing processes deeply, not just architectural aesthetics.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                </div>
                <h3>Proven Lean Methodologies</h3>
                <p>We bring 20 years of lean manufacturing expertise. Every layout decision is backed by time-tested
                    principles: value stream mapping, takt time, flow, pull, and continuous improvement.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 20V10" />
                        <path d="M18 20V4" />
                        <path d="M6 20v-4" />
                    </svg>
                </div>
                <h3>Measurable Results</h3>
                <p>We focus on outcomes: reduced lead times, lower inventory, improved productivity, better quality. Our
                    success is measured by your operational performance, not just beautiful buildings.</p>
            </div>
        </div>
    </div>
</section>

<!-- Approach Section -->
<section class="about-section">
    <div class="about-container">
        <div class="section-header-about">
            <h2>Our Approach to Consulting</h2>
            <p>How we work with clients to deliver lasting value</p>
        </div>

        <div class="approach-grid">
            <div class="approach-card">
                <div class="approach-num">01</div>
                <div class="approach-content">
                    <h3>Partnership, Not Just Service</h3>
                    <p>We view our engagement as a partnership. Your success is our success. We invest time to deeply
                        understand your business, challenges, and aspirations before proposing solutions.</p>
                </div>
            </div>
            <div class="approach-card">
                <div class="approach-num">02</div>
                <div class="approach-content">
                    <h3>Knowledge Transfer</h3>
                    <p>We don't just do the work and leave. We build your internal capability through knowledge transfer
                        and coaching, empowering you to sustain improvements.</p>
                </div>
            </div>
            <div class="approach-card">
                <div class="approach-num">03</div>
                <div class="approach-content">
                    <h3>Practical, Not Theoretical</h3>
                    <p>We bring real-world manufacturing experience, not just textbook knowledge. Our solutions are
                        practical, implementable, and deliver results.</p>
                </div>
            </div>
            <div class="approach-card">
                <div class="approach-num">04</div>
                <div class="approach-content">
                    <h3>Continuous Improvement Mindset</h3>
                    <p>We believe no solution is perfect on day one. We build in feedback loops and refinement cycles to
                        continuously improve.</p>
                </div>
            </div>
        </div>

        <div class="integration-banner">
            <div class="integration-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
            </div>
            <div class="integration-content">
                <h3>Single Partner, Complete Responsibility</h3>
                <p>Our integrated approach means you have a single partner responsible for the entire outcome. We handle
                    coordination across all disciplines — architecture, engineering, lean consulting, compliance — to
                    avoid gaps and finger-pointing.</p>
            </div>
        </div>
    </div>
</section>

<?php $hideFooterCTA = true; ?>
<!-- CTA Section -->
<section class="about-cta">
    <div class="about-cta-inner">
        <h2>Ready to Work With Us?</h2>
        <p>Experience the OptiSpace difference in your next factory project. Our integrated approach handles
            architecture, engineering, and lean consulting as one cohesive solution.</p>
        <div class="cta-buttons">
            <a href="<?php echo url('pulse-check.php'); ?>" class="btn-cta-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                Request a Pulse Check
            </a>
            <a href="<?php echo url('portfolio.php'); ?>" class="btn-cta-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" />
                    <circle cx="8.5" cy="8.5" r="1.5" />
                    <path d="M21 15l-5-5L5 21" />
                </svg>
                View Our Portfolio
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>