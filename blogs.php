<?php
$pageTitle = "Blog | Solutions OptiSpace";
$pageDescription = "Insights, articles, and thought leadership from Solutions OptiSpace on lean manufacturing and factory design.";
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

// Fetch featured blogs
$featuredBlogs = $conn->query("
    SELECT b.*, c.name as category_name, c.slug as category_slug, c.color as category_color,
           a.full_name as author_name, a.role as author_role
    FROM blogs b
    LEFT JOIN blog_categories c ON b.category_id = c.id
    LEFT JOIN admin_users a ON b.author_id = a.id
    WHERE b.is_published = 1 AND b.is_featured = 1 $categoryFilter
    ORDER BY b.published_at DESC
    LIMIT 4
");

// Fetch all published blogs (excluding featured for main list)
$allBlogs = $conn->query("
    SELECT b.*, c.name as category_name, c.slug as category_slug, c.color as category_color,
           a.full_name as author_name, a.role as author_role
    FROM blogs b
    LEFT JOIN blog_categories c ON b.category_id = c.id
    LEFT JOIN admin_users a ON b.author_id = a.id
    WHERE b.is_published = 1 $categoryFilter
    ORDER BY b.published_at DESC
    LIMIT 20
");

// Fetch categories with counts
$categories = $conn->query("
    SELECT c.*, COUNT(b.id) as blog_count
    FROM blog_categories c
    LEFT JOIN blogs b ON c.id = b.category_id AND b.is_published = 1
    WHERE c.is_active = 1
    GROUP BY c.id
    ORDER BY c.sort_order, c.name
");

// Get total blog count and other stats
$totalBlogs = $conn->query("SELECT COUNT(*) as cnt FROM blogs WHERE is_published = 1")->fetch_assoc()['cnt'];
$totalCategories = $conn->query("SELECT COUNT(*) as cnt FROM blog_categories WHERE is_active = 1")->fetch_assoc()['cnt'];

$conn->close();

// Helper function to get author initials
function getAuthorInitials($name) {
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
}

.blog-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
}

.blog-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
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

.hero-featured-preview {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    max-width: 420px;
    width: 100%;
}

.preview-article {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.25rem;
    transition: all 0.3s ease;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.preview-article:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(233, 148, 49, 0.3);
    transform: translateX(4px);
}

.preview-article-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #E99431 0%, #f5a854 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.preview-article:nth-child(2) .preview-article-icon {
    background: linear-gradient(135deg, #3B82F6 0%, #60a5fa 100%);
}

.preview-article:nth-child(3) .preview-article-icon {
    background: linear-gradient(135deg, #10B981 0%, #34d399 100%);
}

.preview-article-icon svg {
    width: 24px;
    height: 24px;
    color: white;
}

.preview-article-content {
    flex: 1;
}

.preview-article-category {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #E99431;
    margin-bottom: 0.25rem;
}

.preview-article:nth-child(2) .preview-article-category {
    color: #60a5fa;
}

.preview-article:nth-child(3) .preview-article-category {
    color: #34d399;
}

.preview-article-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.preview-article-meta {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.5);
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
    
    .hero-featured-preview {
        max-width: 100%;
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
    <div class="blog-hero-inner">
        <div class="blog-hero-content">
            <div class="hero-eyebrow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                Knowledge Hub
            </div>
            <h1>Insights & <span>Best Practices</span></h1>
            <p class="blog-hero-text">Expert perspectives on lean manufacturing, factory optimization, and operational excellence. Learn from real-world implementations and industry expertise.</p>
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
        <div class="hero-visual">
            <div class="hero-featured-preview">
                <div class="preview-article">
                    <div class="preview-article-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5"/>
                            <path d="M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <div class="preview-article-content">
                        <div class="preview-article-category">Lean Factory</div>
                        <div class="preview-article-title">7 Principles of Lean Factory Design</div>
                        <div class="preview-article-meta">8 min read</div>
                    </div>
                </div>
                <div class="preview-article">
                    <div class="preview-article-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <path d="M3 9h18"/>
                            <path d="M9 21V9"/>
                        </svg>
                    </div>
                    <div class="preview-article-content">
                        <div class="preview-article-category">Layout Design</div>
                        <div class="preview-article-title">Brownfield vs Greenfield: Choosing Right</div>
                        <div class="preview-article-meta">6 min read</div>
                    </div>
                </div>
                <div class="preview-article">
                    <div class="preview-article-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <div class="preview-article-content">
                        <div class="preview-article-category">Case Study</div>
                        <div class="preview-article-title">40% Efficiency Gain: A Success Story</div>
                        <div class="preview-article-meta">10 min read</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="blog-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li>Blog</li>
    </ul>
</nav>

<!-- Categories Section -->
<section class="categories-section">
    <div class="blog-container">
        <div class="categories-header">
            <h2>Explore Topics</h2>
            <p>Browse articles by category to find exactly what you're looking for</p>
        </div>
        
        <div class="categories-grid">
            <?php 
            $categoryIcons = [
                'lean-factory' => '<path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>',
                'layout-design' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/>',
                'case-studies' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
                'industry-trends' => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
                'operations' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>'
            ];
            $colorClasses = ['orange', 'blue', 'green', 'purple', 'gray'];
            $colorIndex = 0;
            
            // Reset categories result pointer
            $categories->data_seek(0);
            
            while ($cat = $categories->fetch_assoc()): 
                $iconSvg = $categoryIcons[$cat['slug']] ?? '<path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>';
                $colorClass = $colorClasses[$colorIndex % count($colorClasses)];
                $colorIndex++;
            ?>
            <a href="?category=<?php echo urlencode($cat['slug']); ?>" class="category-card">
                <div class="category-icon <?php echo $colorClass; ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <?php echo $iconSvg; ?>
                    </svg>
                </div>
                <div class="category-content">
                    <h3><?php echo htmlspecialchars($cat['name']); ?></h3>
                    <p><?php echo htmlspecialchars($cat['description'] ?: 'Articles about ' . $cat['name']); ?></p>
                    <span class="category-count"><?php echo $cat['blog_count']; ?> Articles</span>
                </div>
            </a>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<style>
/* Categories Section */
.blog-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.categories-section {
    padding: 5rem 0;
    background: white;
}

.categories-header {
    text-align: center;
    margin-bottom: 3rem;
}

.categories-header h2 {
    font-size: 2.25rem;
    color: var(--blog-dark);
    margin-bottom: 0.75rem;
    font-weight: 700;
}

.categories-header p {
    font-size: 1.1rem;
    color: var(--blog-text);
    max-width: 500px;
    margin: 0 auto;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.categories-grid .category-card:nth-child(4),
.categories-grid .category-card:nth-child(5) {
    grid-column: span 1;
}

.category-card {
    background: #FAFBFC;
    border: 1px solid var(--blog-border);
    border-radius: 12px;
    padding: 1.75rem;
    display: flex;
    gap: 1.25rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.category-card:hover {
    background: white;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    border-color: transparent;
    transform: translateY(-2px);
}

.category-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.category-icon svg {
    width: 28px;
    height: 28px;
}

.category-icon.orange {
    background: var(--blog-orange-light);
}
.category-icon.orange svg { color: var(--blog-orange); }

.category-icon.blue {
    background: var(--blog-blue-light);
}
.category-icon.blue svg { color: var(--blog-blue); }

.category-icon.green {
    background: var(--blog-green-light);
}
.category-icon.green svg { color: var(--blog-green); }

.category-icon.purple {
    background: var(--blog-purple-light);
}
.category-icon.purple svg { color: var(--blog-purple); }

.category-icon.gray {
    background: var(--blog-gray-light);
}
.category-icon.gray svg { color: var(--blog-gray); }

.category-content h3 {
    font-size: 1.15rem;
    color: var(--blog-dark);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.category-content p {
    font-size: 0.9rem;
    color: var(--blog-text);
    line-height: 1.6;
    margin-bottom: 0.75rem;
}

.category-count {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--blog-orange);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@media (max-width: 1024px) {
    .categories-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .categories-grid {
        grid-template-columns: 1fr;
    }
    
    .category-card {
        flex-direction: column;
        text-align: center;
    }
    
    .category-icon {
        margin: 0 auto;
    }
}
</style>

<!-- Featured Articles Section -->
<section class="featured-section">
    <div class="blog-container">
        <div class="section-header-flex">
            <div class="section-header-left">
                <span class="section-label">Featured</span>
                <h2>Editor's Picks</h2>
            </div>
            <p class="section-desc">Hand-picked articles our readers find most valuable</p>
        </div>
        
        <?php if ($featuredBlogs->num_rows > 0): 
            $featuredBlogs->data_seek(0);
            $mainFeatured = $featuredBlogs->fetch_assoc();
        ?>
        <div class="featured-grid">
            <article class="featured-main">
                <div class="featured-image">
                    <?php if ($mainFeatured['featured_image']): ?>
                        <img src="<?php echo htmlspecialchars($mainFeatured['featured_image']); ?>" alt="<?php echo htmlspecialchars($mainFeatured['title']); ?>">
                    <?php else: ?>
                        <div class="image-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                <path d="M2 17l10 5 10-5"/>
                                <path d="M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <span class="featured-badge">Featured</span>
                </div>
                <div class="featured-content">
                    <div class="article-meta">
                        <span class="article-category" style="background: <?php echo $mainFeatured['category_color'] ?? '#E99431'; ?>20; color: <?php echo $mainFeatured['category_color'] ?? '#E99431'; ?>;"><?php echo htmlspecialchars($mainFeatured['category_name'] ?? 'Uncategorized'); ?></span>
                        <span class="article-date"><?php echo date('M j, Y', strtotime($mainFeatured['published_at'])); ?></span>
                    </div>
                    <h3><a href="<?php echo url('blog/article.php?slug=' . urlencode($mainFeatured['slug'])); ?>"><?php echo htmlspecialchars($mainFeatured['title']); ?></a></h3>
                    <p><?php echo htmlspecialchars($mainFeatured['excerpt'] ?: substr(strip_tags($mainFeatured['content']), 0, 200) . '...'); ?></p>
                    <div class="article-footer">
                        <div class="author-info">
                            <div class="author-avatar"><?php echo getAuthorInitials($mainFeatured['author_name']); ?></div>
                            <div class="author-details">
                                <span class="author-name"><?php echo htmlspecialchars($mainFeatured['author_name']); ?></span>
                                <span class="author-role"><?php echo ucfirst(str_replace('_', ' ', $mainFeatured['author_role'])); ?></span>
                            </div>
                        </div>
                        <span class="read-time">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <?php echo $mainFeatured['read_time']; ?> min read
                        </span>
                    </div>
                </div>
            </article>
            
            <div class="featured-sidebar">
                <?php 
                $colorClasses = ['blue', 'green', 'purple', 'orange'];
                $colorIndex = 0;
                while ($featured = $featuredBlogs->fetch_assoc()): 
                ?>
                <article class="featured-small">
                    <div class="featured-small-image">
                        <?php if ($featured['featured_image']): ?>
                            <img src="<?php echo htmlspecialchars($featured['featured_image']); ?>" alt="">
                        <?php else: ?>
                            <div class="image-placeholder small">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                    <path d="M2 17l10 5 10-5"/>
                                    <path d="M2 12l10 5 10-5"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="featured-small-content">
                        <span class="article-category <?php echo $colorClasses[$colorIndex % 4]; ?>"><?php echo htmlspecialchars($featured['category_name'] ?? 'Article'); ?></span>
                        <h4><a href="<?php echo url('blog/article.php?slug=' . urlencode($featured['slug'])); ?>"><?php echo htmlspecialchars($featured['title']); ?></a></h4>
                        <div class="article-meta-small">
                            <span><?php echo date('M j, Y', strtotime($featured['published_at'])); ?></span>
                            <span>•</span>
                            <span><?php echo $featured['read_time']; ?> min read</span>
                        </div>
                    </div>
                </article>
                <?php 
                $colorIndex++;
                endwhile; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="empty-featured">
            <p>No featured articles yet. Check back soon for editor's picks!</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Featured Section */
.featured-section {
    padding: 5rem 0;
    background: #F8FAFC;
}

.section-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 2.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.section-header-left {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.section-label {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--blog-orange);
}

.section-header-flex h2 {
    font-size: 2rem;
    color: var(--blog-dark);
    font-weight: 700;
    margin: 0;
}

.section-desc {
    font-size: 1rem;
    color: var(--blog-text);
    margin: 0;
}

.featured-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 2rem;
}

.featured-main {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
}

.featured-main:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    transform: translateY(-4px);
}

.featured-image {
    position: relative;
    height: 280px;
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
}

.image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-placeholder svg {
    width: 80px;
    height: 80px;
    color: rgba(255, 255, 255, 0.2);
}

.image-placeholder.small svg {
    width: 36px;
    height: 36px;
}

.featured-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: var(--blog-orange);
    color: white;
    padding: 0.35rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.featured-content {
    padding: 1.75rem;
}

.article-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.article-category {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.25rem 0.6rem;
    border-radius: 4px;
}

.article-category.orange {
    background: var(--blog-orange-light);
    color: var(--blog-orange);
}

.article-category.blue {
    background: var(--blog-blue-light);
    color: var(--blog-blue);
}

.article-category.green {
    background: var(--blog-green-light);
    color: var(--blog-green);
}

.article-category.purple {
    background: var(--blog-purple-light);
    color: var(--blog-purple);
}

.article-date {
    font-size: 0.85rem;
    color: var(--blog-gray);
}

.featured-content h3 {
    font-size: 1.5rem;
    color: var(--blog-dark);
    margin-bottom: 0.75rem;
    line-height: 1.35;
}

.featured-content h3 a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s ease;
}

.featured-content h3 a:hover {
    color: var(--blog-orange);
}

.featured-content > p {
    font-size: 0.95rem;
    color: var(--blog-text);
    line-height: 1.7;
    margin-bottom: 1.5rem;
}

.article-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1.25rem;
    border-top: 1px solid var(--blog-border);
}

.author-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.author-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--blog-orange) 0%, #f5a854 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.85rem;
    font-weight: 600;
}

.author-details {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--blog-dark);
}

.author-role {
    font-size: 0.8rem;
    color: var(--blog-gray);
}

.read-time {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.85rem;
    color: var(--blog-gray);
}

.read-time svg {
    color: var(--blog-gray);
}

/* Featured Sidebar */
.featured-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.featured-small {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    gap: 1rem;
    padding: 1rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

.featured-small:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transform: translateX(4px);
}

.featured-small-image {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
    border-radius: 8px;
    flex-shrink: 0;
    overflow: hidden;
}

.featured-small-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.featured-small-content .article-category {
    width: fit-content;
    margin-bottom: 0.5rem;
}

.featured-small-content h4 {
    font-size: 0.95rem;
    color: var(--blog-dark);
    line-height: 1.4;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.featured-small-content h4 a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s ease;
}

.featured-small-content h4 a:hover {
    color: var(--blog-orange);
}

.article-meta-small {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: var(--blog-gray);
}

@media (max-width: 1024px) {
    .featured-grid {
        grid-template-columns: 1fr;
    }
    
    .featured-sidebar {
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .featured-small {
        flex: 1;
        min-width: 280px;
    }
}

@media (max-width: 640px) {
    .section-header-flex {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .featured-sidebar {
        flex-direction: column;
    }
    
    .featured-small {
        min-width: auto;
    }
}
</style>

<!-- All Articles Section -->
<section class="articles-section">
    <div class="blog-container">
        <div class="articles-header">
            <div class="section-header-left">
                <span class="section-label"><?php echo $categoryInfo ? htmlspecialchars($categoryInfo['name']) : 'Latest'; ?></span>
                <h2><?php echo $categoryInfo ? 'Articles' : 'All Articles'; ?></h2>
            </div>
            <div class="filter-tabs">
                <a href="blogs.php" class="filter-tab <?php echo empty($categorySlug) ? 'active' : ''; ?>">All</a>
                <?php 
                $categories->data_seek(0);
                while ($cat = $categories->fetch_assoc()): ?>
                    <a href="?category=<?php echo urlencode($cat['slug']); ?>" class="filter-tab <?php echo $categorySlug === $cat['slug'] ? 'active' : ''; ?>"><?php echo htmlspecialchars($cat['name']); ?></a>
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
                        case 'layout-design': $catColor = 'blue'; break;
                        case 'case-studies': $catColor = 'green'; break;
                        case 'industry-trends': $catColor = 'purple'; break;
                        case 'operations': $catColor = 'gray'; break;
                    }
            ?>
            <article class="article-card">
                <div class="article-card-image">
                    <?php if ($blog['featured_image']): ?>
                        <img src="<?php echo htmlspecialchars($blog['featured_image']); ?>" alt="">
                    <?php else: ?>
                        <div class="image-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                <path d="M2 17l10 5 10-5"/>
                                <path d="M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="article-card-content">
                    <div class="article-meta">
                        <span class="article-category <?php echo $catColor; ?>"><?php echo htmlspecialchars($blog['category_name'] ?? 'Article'); ?></span>
                        <span class="article-date"><?php echo date('M j, Y', strtotime($blog['published_at'])); ?></span>
                    </div>
                    <h3><a href="<?php echo url('blog/article.php?slug=' . urlencode($blog['slug'])); ?>"><?php echo htmlspecialchars($blog['title']); ?></a></h3>
                    <p><?php echo htmlspecialchars($blog['excerpt'] ?: substr(strip_tags($blog['content']), 0, 150) . '...'); ?></p>
                    <div class="article-card-footer">
                        <span class="read-time">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <?php echo $blog['read_time']; ?> min read
                        </span>
                        <a href="<?php echo url('blog/article.php?slug=' . urlencode($blog['slug'])); ?>" class="read-more">
                            Read More
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14"/>
                                <path d="M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>
            <?php endwhile; ?>
            <?php else: ?>
            <div class="no-articles" style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5" style="margin: 0 auto 1rem;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
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
                    <path d="M12 5v14"/>
                    <path d="M19 12l-7 7-7-7"/>
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

.article-card-content > p {
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
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
                <div class="newsletter-text">
                    <h2>Stay Updated with Industry Insights</h2>
                    <p>Get the latest articles on lean manufacturing, factory optimization, and operational excellence delivered to your inbox monthly.</p>
                </div>
                <form class="newsletter-form" action="#" method="POST">
                    <div class="form-group">
                        <input type="email" placeholder="Enter your email address" required>
                        <button type="submit" class="btn-subscribe">
                            Subscribe
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14"/>
                                <path d="M12 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    <p class="form-note">No spam. Unsubscribe anytime. Read our <a href="<?php echo url('privacy-policy.php'); ?>">Privacy Policy</a>.</p>
                </form>
            </div>
            <div class="newsletter-features">
                <div class="newsletter-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <span>Monthly digest of top articles</span>
                </div>
                <div class="newsletter-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <span>Exclusive case study previews</span>
                </div>
                <div class="newsletter-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
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
            <p>Let's discuss how lean factory principles can transform your manufacturing operations. Start with a complimentary Pulse Check consultation.</p>
            <div class="cta-buttons">
                <a href="<?php echo url('pulse-check.php'); ?>" class="btn-modern btn-primary-modern btn-large">
                    Request Pulse Check
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14"/>
                        <path d="M12 5l7 7-7 7"/>
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
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
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

<?php include 'includes/footer.php'; ?>
