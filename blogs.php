<?php
$pageTitle = "Blog | Solutions OptiSpace";
$pageDescription = "Insights, articles, and thought leadership from Solutions OptiSpace on lean manufacturing and factory design.";
$pageKeywords = "lean manufacturing blog, factory design articles, LFB insights, manufacturing insights, OptiSpace blog, lean manufacturing articles, factory optimization blog, industrial design insights, manufacturing thought leadership, lean principles";
$currentPage = "blogs";

// Load blogs from database
require_once __DIR__ . '/database/db_config.php';
$conn = getDBConnection();

// Get category filter if present
$categorySlug = $_GET['category'] ?? '';
$categoryFilter = '';
$categoryInfo = null;

if (!empty($categorySlug)) {
    $stmt = $conn->prepare("SELECT * FROM blog_categories WHERE slug = ? AND is_active = 1");
    $stmt->bind_param("s", $categorySlug);
    $stmt->execute();
    $categoryInfo = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($categoryInfo) {
        $categoryFilter = " AND b.category_id = " . $categoryInfo['id'];
        $pageTitle = $categoryInfo['name'] . " - Blog | Solutions OptiSpace";
    }
}



// Fetch all published blogs (excluding featured for main list)
$allBlogs = $conn->query("
    SELECT b.*, c.name as category_name, c.slug as category_slug, c.color as category_color,
           a.full_name as author_name, a.role as author_role
    FROM blogs b
    LEFT JOIN blog_categories c ON b.category_id = c.id
    LEFT JOIN admin_users a ON b.author_id = a.id
    WHERE b.is_published = 1 AND b.published_at <= NOW() $categoryFilter
    ORDER BY b.published_at DESC
    LIMIT 20
");

// Fetch categories with counts
$categories = $conn->query("
    SELECT c.*, COUNT(b.id) as blog_count
    FROM blog_categories c
    LEFT JOIN blogs b ON c.id = b.category_id AND b.is_published = 1 AND b.published_at <= NOW()
    WHERE c.is_active = 1
    GROUP BY c.id
    ORDER BY c.sort_order, c.name
");

// Get total blog count and other stats
$totalBlogsResult = $conn->query("SELECT COUNT(*) as cnt FROM blogs WHERE is_published = 1 AND published_at <= NOW()");
$totalBlogs = $totalBlogsResult ? $totalBlogsResult->fetch_assoc()['cnt'] : 0;

$totalCategoriesResult = $conn->query("SELECT COUNT(*) as cnt FROM blog_categories WHERE is_active = 1");
$totalCategories = $totalCategoriesResult ? $totalCategoriesResult->fetch_assoc()['cnt'] : 0;

// Fetch Active Banners for Blogs Page
$bannerQuery = "SELECT * FROM banner_settings WHERE page_name = 'blogs' AND is_active = 1 ORDER BY sort_order ASC, created_at DESC";
$bannerResult = $conn->query($bannerQuery);
$activeBanners = [];
if ($bannerResult && $bannerResult->num_rows > 0) {
    while ($row = $bannerResult->fetch_assoc()) {
        $activeBanners[] = $row;
    }
}

// Fallback for Blogs Banner
if (empty($activeBanners)) {
    $activeBanners[] = [
        'image_path' => '',
        'eyebrow_text' => 'Knowledge Hub',
        'heading_html' => 'Insights & <span>Best Practices</span>',
        'subheading' => 'Expert perspectives on lean manufacturing, factory optimization, and operational excellence. Learn from real-world implementations and industry expertise.'
    ];
}

$conn->close();

// Helper function to get author initials
function getAuthorInitials($name)
{
    if (empty($name))
        return 'AU';
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    return substr($initials, 0, 2);
}

include 'includes/header.php';
?>

<style>
    /* ========================================
   BLOG PAGE - MODERN CLEAN DESIGN
   ======================================== */

    :root {
        --blog-orange: #E99431;
        --blog-orange-light: rgba(233, 148, 49, 0.08);
        --blog-blue: #3B82F6;
        --blog-blue-light: rgba(59, 130, 246, 0.08);
        --blog-green: #10B981;
        --blog-green-light: rgba(16, 185, 129, 0.08);
        --blog-purple: #8B5CF6;
        --blog-purple-light: rgba(139, 92, 246, 0.08);
        --blog-gray: #64748B;
        --blog-gray-light: rgba(100, 116, 139, 0.08);
        --blog-dark: #1E293B;
        --blog-text: #475569;
        --blog-border: #E2E8F0;
    }

    /* Hero Section */
    .blog-hero {
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

    .blog-hero-inner {
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
    .hero-slide-content .blog-hero-content {
        opacity: 0;
        transform: translateY(30px);
        transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .hero-slide-content:not(.active) .blog-hero-content {
        transform: translateY(-20px);
        opacity: 0;
        transition: all 0.5s ease;
    }

    .hero-slide-content.active .blog-hero-content {
        opacity: 1;
        transform: translateY(0);
        transition-delay: 0.1s;
    }

    .blog-hero-content {
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

    .blog-hero h1 {
        font-size: 3.25rem;
        font-weight: 700;
        color: white;
        line-height: 1.15;
        margin-bottom: 1.5rem;
    }

    .blog-hero h1 span {
        color: #E99431;
    }

    .blog-hero-text {
        font-size: 1.2rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.75);
        margin-bottom: 2rem;
        max-width: 650px;
        margin-left: auto;
        margin-right: auto;
    }

    .hero-stats {
        display: flex;
        gap: 3rem;
        justify-content: center;
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
    .blog-breadcrumb {
        background: #F8FAFC;
        padding: 1rem 0;
        border-bottom: 1px solid var(--blog-border);
    }

    .blog-breadcrumb ul {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        list-style: none;
        display: flex;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .blog-breadcrumb a {
        color: var(--blog-gray);
        text-decoration: none;
    }

    .blog-breadcrumb a:hover {
        color: var(--blog-orange);
    }

    .blog-breadcrumb li:last-child {
        color: var(--blog-dark);
        font-weight: 500;
    }

    .blog-breadcrumb li:not(:last-child)::after {
        content: '/';
        margin-left: 0.5rem;
        color: var(--blog-border);
    }

    /* Responsive Hero */
    @media (max-width: 1024px) {
        .blog-hero-inner {
            grid-template-columns: 1fr;
            gap: 3rem;
            text-align: center;
        }

        .blog-hero-text {
            margin: 0 auto 2rem;
        }

        .hero-stats {
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .blog-hero {
            padding: 5rem 0 4rem;
        }

        .blog-hero h1 {
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
    }
</style>

<!-- Hero Section -->
<section class="blog-hero">
    <!-- Slider Slides (Images) -->
    <div class="hero-slider-container">
        <?php foreach ($activeBanners as $index => $banner): ?>
            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                style="background-image: url('<?php echo !empty($banner['image_path']) ? url(htmlspecialchars($banner['image_path'])) : ''; ?>'); <?php echo empty($banner['image_path']) ? 'background: linear-gradient(165deg, #1E293B 0%, #334155 100%);' : ''; ?>"
                data-index="<?php echo $index; ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <div class="blog-hero-inner">
        <div class="hero-content-wrapper">
            <?php foreach ($activeBanners as $index => $banner): ?>
                <div class="hero-slide-content <?php echo $index === 0 ? 'active' : ''; ?>"
                    data-index="<?php echo $index; ?>">
                    <div class="blog-hero-content">
                        <div class="hero-eyebrow">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                            <?php echo htmlspecialchars($banner['eyebrow_text']); ?>
                        </div>
                        <h1><?php echo $banner['heading_html']; ?></h1>
                        <p class="blog-hero-text"><?php echo htmlspecialchars($banner['subheading']); ?></p>
                        <div class="hero-stats">
                            <div class="hero-stat">
                                <div class="hero-stat-value"><?php echo $totalBlogs; ?>+</div>
                                <div class="hero-stat-label">Articles</div>
                            </div>
                            <div class="hero-stat">
                                <div class="hero-stat-value"><?php echo $totalCategories; ?></div>
                                <div class="hero-stat-label">Categories</div>
                            </div>
                            <div class="hero-stat">
                                <div class="hero-stat-value">10K+</div>
                                <div class="hero-stat-label">Readers</div>
                            </div>
                        </div>
                    </div>
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
<nav class="blog-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li>Blog</li>
    </ul>
</nav>



<style>
    /* Categories Section */
    .blog-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }
</style>





<!-- All Articles Section -->
<section class="articles-section">
    <div class="blog-container">
        <div class="articles-header">
            <div class="section-header-left">
                <span
                    class="section-label"><?php echo $categoryInfo ? htmlspecialchars($categoryInfo['name']) : 'Latest'; ?></span>
                <h2><?php echo $categoryInfo ? 'Articles' : 'All Articles'; ?></h2>
            </div>
            <div class="filter-tabs">
                <a href="blogs.php" class="filter-tab <?php echo empty($categorySlug) ? 'active' : ''; ?>">All</a>
                <?php
                $categories->data_seek(0);
                while ($cat = $categories->fetch_assoc()): ?>
                    <a href="?category=<?php echo urlencode($cat['slug']); ?>"
                        class="filter-tab <?php echo $categorySlug === $cat['slug'] ? 'active' : ''; ?>"><?php echo htmlspecialchars($cat['name']); ?></a>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="articles-grid">
            <?php
            $colorClasses = ['orange', 'blue', 'green', 'purple', 'gray'];

            if ($allBlogs->num_rows > 0):
                while ($blog = $allBlogs->fetch_assoc()):
                    // Get color class based on category
                    $catColor = 'orange';
                    switch ($blog['category_slug']) {
                        case 'layout-design':
                            $catColor = 'blue';
                            break;
                        case 'case-studies':
                            $catColor = 'green';
                            break;
                        case 'industry-trends':
                            $catColor = 'purple';
                            break;
                        case 'operations':
                            $catColor = 'gray';
                            break;
                    }
                    ?>
                    <article class="article-card">
                        <div class="article-card-image">
                            <?php if ($blog['featured_image']): ?>
                                <img src="<?php echo htmlspecialchars($blog['featured_image']); ?>" alt="">
                            <?php else: ?>
                                <div class="image-placeholder">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M12 2L2 7l10 5 10-5-10-5z" />
                                        <path d="M2 17l10 5 10-5" />
                                        <path d="M2 12l10 5 10-5" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="article-card-content">
                            <div class="article-meta">
                                <span
                                    class="article-category <?php echo $catColor; ?>"><?php echo htmlspecialchars($blog['category_name'] ?? 'Article'); ?></span>
                                <span
                                    class="article-date"><?php echo date('M j, Y', strtotime($blog['published_at'])); ?></span>
                            </div>
                            <h3><a
                                    href="<?php echo url('blog/article.php?slug=' . urlencode($blog['slug'])); ?>"><?php echo htmlspecialchars($blog['title']); ?></a>
                            </h3>
                            <p><?php echo htmlspecialchars($blog['excerpt'] ?: substr(strip_tags($blog['content']), 0, 150) . '...'); ?>
                            </p>
                            <div class="article-card-footer">
                                <span class="read-time">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <?php echo $blog['read_time']; ?> min read
                                </span>
                                <a href="<?php echo url('blog/article.php?slug=' . urlencode($blog['slug'])); ?>"
                                    class="read-more">
                                    Read More
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M5 12h14" />
                                        <path d="M12 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-articles" style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5"
                        style="margin: 0 auto 1rem;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                    <h3 style="color: #1E293B; margin-bottom: 0.5rem;">No Articles Yet</h3>
                    <p style="color: #64748B;">Check back soon for new content!</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="load-more-container">
            <button class="btn-load-more">
                Load More Articles
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14" />
                    <path d="M19 12l-7 7-7-7" />
                </svg>
            </button>
        </div>
    </div>
</section>

<style>
    /* Articles Section */
    .articles-section {
        padding: 5rem 0;
        background: white;
    }

    .articles-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 0.5rem 1rem;
        border: 1px solid var(--blog-border);
        background: white;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--blog-text);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-tab:hover {
        border-color: var(--blog-orange);
        color: var(--blog-orange);
    }

    .filter-tab.active {
        background: var(--blog-orange);
        border-color: var(--blog-orange);
        color: white;
    }

    .articles-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .article-card {
        background: #FAFBFC;
        border: 1px solid var(--blog-border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .article-card:hover {
        background: white;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        border-color: transparent;
        transform: translateY(-4px);
    }

    .article-card-image {
        height: 180px;
        background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
    }

    .article-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-placeholder svg {
        width: 64px;
        height: 64px;
        color: rgba(255, 255, 255, 0.2);
    }

    .article-card-content {
        padding: 1.5rem;
    }

    .article-card-content h3 {
        font-size: 1.1rem;
        color: var(--blog-dark);
        margin-bottom: 0.75rem;
        line-height: 1.4;
        font-weight: 600;
    }

    .article-card-content h3 a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .article-card-content h3 a:hover {
        color: var(--blog-orange);
    }

    .article-card-content>p {
        font-size: 0.9rem;
        color: var(--blog-text);
        line-height: 1.6;
        margin-bottom: 1.25rem;
    }

    .article-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--blog-border);
    }

    .article-card-footer .read-time {
        font-size: 0.8rem;
    }

    .read-more {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--blog-orange);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .read-more:hover {
        gap: 0.6rem;
    }

    .read-more svg {
        transition: transform 0.2s ease;
    }

    .read-more:hover svg {
        transform: translateX(2px);
    }

    .load-more-container {
        text-align: center;
        margin-top: 3rem;
    }

    .btn-load-more {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        background: white;
        border: 2px solid var(--blog-border);
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--blog-dark);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-load-more:hover {
        border-color: var(--blog-orange);
        color: var(--blog-orange);
    }

    @media (max-width: 1024px) {
        .articles-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .articles-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .filter-tabs {
            width: 100%;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .articles-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="blog-container">
        <div class="newsletter-card">
            <div class="newsletter-content">
                <div class="newsletter-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                </div>
                <div class="newsletter-text">
                    <h2>Stay Updated with Industry Insights</h2>
                    <p>Get the latest articles on lean manufacturing, factory optimization, and operational excellence
                        delivered to your inbox monthly.</p>
                </div>
                <form class="newsletter-form" action="#" method="POST">
                    <div class="form-group">
                        <input type="email" placeholder="Enter your email address" required>
                        <button type="submit" class="btn-subscribe">
                            Subscribe
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M5 12h14" />
                                <path d="M12 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                    <p class="form-note">No spam. Unsubscribe anytime. Read our <a
                            href="<?php echo url('privacy-policy.php'); ?>">Privacy Policy</a>.</p>
                </form>
            </div>
            <div class="newsletter-features">
                <div class="newsletter-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    <span>Monthly digest of top articles</span>
                </div>
                <div class="newsletter-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    <span>Exclusive case study previews</span>
                </div>
                <div class="newsletter-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    <span>Early access to new resources</span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Newsletter Section */
    .newsletter-section {
        padding: 5rem 0;
        background: #F8FAFC;
    }

    .newsletter-card {
        background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
        border-radius: 20px;
        padding: 3.5rem;
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 3rem;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .newsletter-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background: radial-gradient(ellipse at 80% 50%, rgba(233, 148, 49, 0.15) 0%, transparent 60%);
    }

    .newsletter-content {
        position: relative;
        z-index: 1;
    }

    .newsletter-icon {
        width: 64px;
        height: 64px;
        background: rgba(233, 148, 49, 0.15);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .newsletter-icon svg {
        width: 32px;
        height: 32px;
        color: var(--blog-orange);
    }

    .newsletter-text h2 {
        font-size: 2rem;
        color: white;
        margin-bottom: 0.75rem;
        font-weight: 700;
    }

    .newsletter-text p {
        font-size: 1.05rem;
        color: rgba(255, 255, 255, 0.75);
        line-height: 1.7;
        margin-bottom: 2rem;
        max-width: 500px;
    }

    .newsletter-form .form-group {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .newsletter-form input[type="email"] {
        flex: 1;
        padding: 0.875rem 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        font-size: 0.95rem;
        color: white;
        transition: all 0.2s ease;
    }

    .newsletter-form input[type="email"]::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .newsletter-form input[type="email"]:focus {
        outline: none;
        border-color: var(--blog-orange);
        background: rgba(255, 255, 255, 0.15);
    }

    .btn-subscribe {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        background: var(--blog-orange);
        border: none;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-subscribe:hover {
        background: #d4851c;
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(233, 148, 49, 0.4);
    }

    .form-note {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.5);
        margin: 0;
    }

    .form-note a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: underline;
    }

    .form-note a:hover {
        color: var(--blog-orange);
    }

    .newsletter-features {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .newsletter-feature {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .newsletter-feature svg {
        width: 20px;
        height: 20px;
        color: var(--blog-orange);
        flex-shrink: 0;
    }

    .newsletter-feature span {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.85);
    }

    @media (max-width: 1024px) {
        .newsletter-card {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .newsletter-features {
            flex-direction: row;
            flex-wrap: wrap;
        }

        .newsletter-feature {
            flex: 1;
            min-width: 200px;
        }
    }

    @media (max-width: 640px) {
        .newsletter-card {
            padding: 2rem;
        }

        .newsletter-form .form-group {
            flex-direction: column;
        }

        .newsletter-features {
            flex-direction: column;
        }

        .newsletter-feature {
            min-width: auto;
        }
    }
</style>

<!-- Final CTA -->
<section class="blog-cta">
    <div class="blog-container">
        <div class="cta-content">
            <h2>Ready to Optimize Your Factory?</h2>
            <p>Let's discuss how lean factory principles can transform your manufacturing operations. Start with a
                complimentary Pulse Check consultation.</p>
            <div class="cta-buttons">
                <a href="<?php echo url('pulse-check.php'); ?>" class="btn-modern btn-primary-modern btn-large">
                    Request Pulse Check
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14" />
                        <path d="M12 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="<?php echo url('contact.php'); ?>" class="btn-modern btn-outline-light btn-large">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    /* Blog CTA */
    .blog-cta {
        padding: 6rem 0;
        background: #2f3030;
    }

    .blog-cta .cta-content {
        text-align: center;
        max-width: 700px;
        margin: 0 auto;
    }

    .blog-cta .cta-content h2 {
        font-size: 2.5rem;
        color: white;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .blog-cta .cta-content p {
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
        background: var(--blog-orange);
        color: white;
    }

    .btn-primary-modern:hover {
        background: #d4851c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(233, 148, 49, 0.3);
    }

    .btn-outline-light {
        background: transparent;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
    }

    .btn-large {
        padding: 1rem 2rem !important;
        font-size: 1rem !important;
    }

    @media (max-width: 768px) {
        .blog-cta .cta-content h2 {
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