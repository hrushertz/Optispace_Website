<?php
$currentPage = 'brownfield';
$pageTitle = 'Existing Factory Optimization (Brownfield) | Solutions OptiSpace';
$pageDescription = 'Transform your existing factory with lean diagnosis, layout optimization, and material handling improvements.';
$pageKeywords = 'brownfield factory optimization, existing factory redesign, factory renovation, plant layout optimization, brownfield project, existing facility improvement, factory retrofitting, manufacturing plant optimization, brownfield LFB, factory transformation, existing plant redesign, facility optimization';
include '../includes/header.php';

// Get database connection
require_once '../database/db_config.php';
$conn = getDBConnection();

// Fetch Active Banners for Brownfield Page
$bannerQuery = "SELECT * FROM banner_settings WHERE page_name = 'brownfield' AND is_active = 1 ORDER BY sort_order ASC, created_at DESC";
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
        'eyebrow_text' => 'Brownfield Optimization',
        'heading_html' => 'Existing Factory Optimization <span>with LFB</span>',
        'subheading' => 'Transform your existing factory with lean diagnosis, layout optimization, and material handling improvements.'
    ];
}
?>

<style>
    /* ========================================
   BROWNFIELD PAGE - MODERN CLEAN DESIGN
   ======================================== */

    :root {
        --brownfield-orange: #e99738;
        --brownfield-orange-light: rgba(233, 151, 56, 0.08);
        --brownfield-blue: #e99738;
        --brownfield-blue-light: rgba(233, 151, 56, 0.08);
        --brownfield-green: #e99738;
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

    .brownfield-hero-inner {
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

    .brownfield-hero-content {
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
    .hero-slide-content .brownfield-hero-text,
    .hero-slide-content .hero-stats {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Reset state (outgoing) */
    .hero-slide-content:not(.active) .hero-eyebrow,
    .hero-slide-content:not(.active) h1,
    .hero-slide-content:not(.active) .brownfield-hero-text,
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

    .hero-slide-content.active .brownfield-hero-text {
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

    .brownfield-hero h1 {
        font-size: 3rem;
        font-weight: 700;
        color: white;
        line-height: 1.15;
        margin-bottom: 1.5rem;
    }

    .brownfield-hero h1 span {
        color: #e99738;
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

    @media (max-width: 1024px) {
        .brownfield-hero-inner {
            grid-template-columns: 1fr;
            gap: 3rem;
        }

        .brownfield-hero-content {
            text-align: center;
        }

        .brownfield-hero-text {
            margin: 0 auto 2rem;
            max-width: 700px;
        }

        .hero-stats {
            justify-content: center;
        }
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

    /* Issues list specific styling */
    .content-card .issues-list li {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        padding: 0.75rem 0 0.75rem 1.5rem;
        position: relative;
    }

    .content-card .issues-list li::before {
        content: '✓';
        color: var(--brownfield-blue);
        font-weight: 700;
        position: absolute;
        left: 0;
        top: 0.75rem;
    }

    .content-card .issues-list {
        padding-left: 0;
    }

    .content-card .issue-label {
        font-weight: 700;
        color: var(--brownfield-dark);
        display: block;
        line-height: 1.5;
    }

    .content-card .issue-desc {
        color: var(--brownfield-text);
        display: block;
        line-height: 1.6;
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
        background: var(--brownfield-orange);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 auto 1.5rem;
        box-shadow: none;
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

    .service-card>p {
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
        background: var(--brownfield-orange-light);
        border: 1px solid rgba(233, 151, 56, 0.2);
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
        color: var(--brownfield-orange);
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

    .implementation-card>p {
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
        background: #2f3030;
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
        /* box-shadow: 0 4px 12px rgba(233, 148, 49, 0.3); */
        box-shadow: none;
    }

    .btn-cta-primary:hover {
        background: #d4851c;
        transform: translateY(-1px);
        /* box-shadow: 0 6px 20px rgba(233, 148, 49, 0.4); */
        box-shadow: none;
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

        .process-grid,
        .services-grid,
        .implementation-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .spaghetti-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .brownfield-hero {
            padding: 7rem 0 4rem;
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
            display: none;
        }

        .brownfield-section {
            padding: 4rem 0;
        }

        .brownfield-section-header h2 {
            font-size: 2rem;
        }

        .content-grid,
        .process-grid,
        .services-grid,
        .implementation-grid {
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
    <!-- Slider Slides (Images) -->
    <div class="hero-slider-container">
        <?php foreach ($activeBanners as $index => $banner): ?>
            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                style="background-image: url('<?php echo !empty($banner['image_path']) ? htmlspecialchars('../' . $banner['image_path']) : ''; ?>'); <?php echo empty($banner['image_path']) ? 'background: linear-gradient(165deg, #1E293B 0%, #334155 100%);' : ''; ?>"
                data-index="<?php echo $index; ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <div class="brownfield-hero-inner">
        <div class="brownfield-hero-content">
            <?php foreach ($activeBanners as $index => $banner): ?>
                <div class="hero-slide-content <?php echo $index === 0 ? 'active' : ''; ?>"
                    data-index="<?php echo $index; ?>">
                    <div class="hero-eyebrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z">
                            </path>
                        </svg>
                        <?php echo htmlspecialchars($banner['eyebrow_text']); ?>
                    </div>
                    <h1><?php echo $banner['heading_html']; ?></h1>
                    <p class="brownfield-hero-text"><?php echo htmlspecialchars($banner['subheading']); ?></p>
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
            <p>Most manufacturing facilities weren't designed with lean principles. Over time, they accumulate
                inefficiencies that drain productivity every day.</p>
        </div>

        <div class="content-grid">
            <div class="content-card">
                <h3>Common Issues We Solve</h3>
                <p>Accumulated inefficiencies that constrain your operations:</p>
                <ul class="issues-list">
                    <li><span class="issue-label">Unplanned additions:</span> <span class="issue-desc">Equipment added
                            without layout planning, creating bottlenecks</span></li>
                    <li><span class="issue-label">Excessive travel:</span> <span class="issue-desc">Materials and
                            operators traveling far due to poor adjacency</span></li>
                    <li><span class="issue-label">Space constraints:</span> <span class="issue-desc">Storage and WIP
                            growing uncontrolled, limiting capacity</span></li>
                    <li><span class="issue-label">Safety concerns:</span> <span class="issue-desc">Congested aisles and
                            unclear traffic patterns</span></li>
                </ul>
            </div>
            <div class="content-card">
                <h3>What You Can Achieve</h3>
                <p><strong>Typical impact ranges from brownfield projects:</strong></p>
                <ul>
                    <li><span><strong>30-50% reduction</strong> in material movement distance</span></li>
                    <li><span><strong>20-40% reduction</strong> in work-in-process inventory</span></li>
                    <li><span><strong>15-30% improvement</strong> in floor space utilization</span></li>
                    <li><span><strong>20-35% reduction</strong> in operator walking</span></li>
                    <li><span><strong>Improved quality</strong> through better visibility and flow</span></li>
                    <li><span><strong>Enhanced safety</strong> with organized workspaces</span></li>
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
            <p>Brownfield projects require surgical precision. We work within your existing constraints to find new
                efficiencies, often revealing capacity you didn't know you had.</p>
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
            <p>LFB drives not just plant‑level layout, but also detailed workstation, material handling, and storage
                design</p>
        </div>

        <div class="services-grid">
            <div class="service-card">
                <div class="service-card-header">
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path
                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z" />
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
                            <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h3>Material Handling</h3>
                </div>
                <p><strong>Right‑sized systems for handling.</strong></p>
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
                            <path
                                d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z" />
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
            <p>Spaghetti diagrams bring the LFB philosophy to life by exposing the hidden, tangled paths inside your
                building</p>
        </div>

        <div class="spaghetti-card">
            <div class="spaghetti-grid">
                <div class="spaghetti-content">
                    <h3>What is a Spaghetti Diagram?</h3>
                    <p>A visual tool that traces the actual path materials and operators take through your facility.
                        When multiple product paths are overlaid, the result looks like tangled spaghetti, revealing:
                    </p>
                    <ul>
                        <li>Excessive travel distances</li>
                        <li>Backtracking and crisscrossing</li>
                        <li>Congestion points</li>
                        <li>Opportunities for adjacency</li>
                    </ul>
                    <h3 style="margin-top: 1.5rem;">The Impact</h3>
                    <p>One client reduced their main product's travel distance by <strong>50%</strong> through layout
                        optimization guided by spaghetti diagram analysis.</p>
                </div>
                <div class="spaghetti-stats">
                    <div class="stat-highlight">
                        <div class="stat-value">89%</div>
                        <div class="stat-label">Reduction in Transportation</div>
                    </div>
                    <p class="stat-detail">Faster throughput with same resources. Lower handling costs and damage.
                        Better visibility and control.</p>
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
            <p>When layout changes demand new equipment or handling systems, OptiSpace supports selection aligned with
                the new LFB‑based design</p>
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
            <p>This ensures your team adopts the new ways of working and the gains are sustained, not lost after the
                project ends</p>
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

<?php $hideFooterCTA = true; ?>
<!-- CTA Section -->
<section class="brownfield-cta">
    <div class="brownfield-container">
        <div class="cta-inner">
            <h2>Transform Your Existing Factory</h2>
            <p>Unlock hidden improvement potential in your current facility. Get a structured LFB Pulse Check to
                identify layout optimization and flow improvement opportunities.</p>
            <div class="cta-buttons">
                <a href="<?php echo url('pulse-check.php'); ?>" class="btn-cta-primary">
                    Request Brownfield Assessment
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
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