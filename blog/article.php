<?php
/**
 * Dynamic Blog Article Page
 * Loads blog content from database based on slug
 */

require_once __DIR__ . '/../database/db_config.php';

// Get the slug from URL
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: ' . url('blogs.php'));
    exit;
}

$conn = getDBConnection();

// Fetch the blog article with author and category info
$stmt = $conn->prepare("
    SELECT b.*, 
           bc.name as category_name, 
           bc.slug as category_slug,
           bc.color as category_color,
           au.full_name as author_name,
           au.email as author_email
    FROM blogs b
    LEFT JOIN blog_categories bc ON b.category_id = bc.id
    LEFT JOIN admin_users au ON b.author_id = au.id
    WHERE b.slug = ? AND b.is_published = 1
");
$stmt->bind_param("s", $slug);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$article) {
    header('HTTP/1.0 404 Not Found');
    $pageTitle = "Article Not Found | Solutions OptiSpace";
    $pageDescription = "The requested article could not be found.";
    $currentPage = "blogs";
    include '../includes/header.php';
    echo '<div style="text-align: center; padding: 8rem 2rem;"><h1>Article Not Found</h1><p>The article you are looking for does not exist or has been removed.</p><a href="' . url('blogs.php') . '" style="color: #E99431;">Back to Blog</a></div>';
    include '../includes/footer.php';
    $conn->close();
    exit;
}

// Increment view count
$updateStmt = $conn->prepare("UPDATE blogs SET view_count = view_count + 1 WHERE id = ?");
$updateStmt->bind_param("i", $article['id']);
$updateStmt->execute();
$updateStmt->close();

// Fetch related articles (same category, exclude current)
$relatedStmt = $conn->prepare("
    SELECT b.id, b.title, b.slug, b.excerpt, b.read_time, b.published_at,
           bc.name as category_name, bc.slug as category_slug
    FROM blogs b
    LEFT JOIN blog_categories bc ON b.category_id = bc.id
    WHERE b.category_id = ? AND b.id != ? AND b.is_published = 1
    ORDER BY b.published_at DESC
    LIMIT 3
");
$relatedStmt->bind_param("ii", $article['category_id'], $article['id']);
$relatedStmt->execute();
$relatedArticles = $relatedStmt->get_result();
$relatedStmt->close();

$conn->close();

// Set page meta
$pageTitle = htmlspecialchars($article['meta_title'] ?: $article['title']) . " | Solutions OptiSpace";
$pageDescription = htmlspecialchars($article['meta_description'] ?: $article['excerpt']);
$pageKeywords = htmlspecialchars('lean manufacturing, factory design, OptiSpace blog, LFB insights, manufacturing optimization, industrial design, lean principles, factory planning, manufacturing excellence');
$currentPage = "blogs";

// Get author initials
$authorInitials = '';
$nameToInit = $article['author_name'] ?? 'System Admin';
$nameParts = explode(' ', $nameToInit);
foreach ($nameParts as $part) {
    if (!empty($part)) {
        $authorInitials .= strtoupper(substr($part, 0, 1));
    }
}
if (empty($authorInitials))
    $authorInitials = 'AU';
$authorInitials = substr($authorInitials, 0, 2);

include '../includes/header.php';
?>

<style>
    /* ========================================
   BLOG ARTICLE PAGE - MODERN CLEAN DESIGN
   ======================================== */

    :root {
        --article-orange: #E99431;
        --article-orange-light: rgba(233, 148, 49, 0.08);
        --article-blue: #3B82F6;
        --article-blue-light: rgba(59, 130, 246, 0.08);
        --article-green: #10B981;
        --article-green-light: rgba(16, 185, 129, 0.08);
        --article-dark: #1E293B;
        --article-text: #475569;
        --article-text-light: #64748B;
        --article-border: #E2E8F0;
    }

    /* Article Hero */
    .article-hero {
        background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
        padding: 8rem 0 4rem;
        position: relative;
        overflow: hidden;
    }

    .article-hero::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
    }

    .article-hero-inner {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 2rem;
        position: relative;
        z-index: 2;
    }

    .article-meta-top {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .article-category-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(233, 148, 49, 0.15);
        color: var(--article-orange);
        padding: 0.5rem 1rem;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .article-category-badge:hover {
        background: rgba(233, 148, 49, 0.25);
    }

    .article-category-badge svg {
        width: 16px;
        height: 16px;
    }

    .article-date-badge {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
    }

    .article-hero h1 {
        font-size: 2.75rem;
        font-weight: 700;
        color: white;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        max-width: 800px;
    }

    .article-excerpt {
        font-size: 1.2rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 2rem;
        max-width: 700px;
    }

    .article-author-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        max-width: fit-content;
    }

    .author-avatar-large {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--article-orange) 0%, #f5a854 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .author-info-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .author-info-name {
        font-size: 1rem;
        font-weight: 600;
        color: white;
    }

    .author-info-role {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
    }

    .author-info-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.5);
    }

    .author-info-meta span {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .author-info-meta svg {
        width: 14px;
        height: 14px;
    }

    /* Breadcrumb */
    .article-breadcrumb {
        background: #F8FAFC;
        padding: 1rem 0;
        border-bottom: 1px solid var(--article-border);
    }

    .article-breadcrumb ul {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 2rem;
        list-style: none;
        display: flex;
        gap: 0.5rem;
        font-size: 0.9rem;
        flex-wrap: wrap;
    }

    .article-breadcrumb a {
        color: var(--article-text-light);
        text-decoration: none;
    }

    .article-breadcrumb a:hover {
        color: var(--article-orange);
    }

    .article-breadcrumb li:last-child {
        color: var(--article-dark);
        font-weight: 500;
    }

    .article-breadcrumb li:not(:last-child)::after {
        content: '/';
        margin-left: 0.5rem;
        color: var(--article-border);
    }

    /* Article Content Layout */
    .article-layout {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 4rem;
        align-items: start;
    }

    .article-main {
        padding: 3rem 0;
    }

    /* Article Content Styles */
    .article-content {
        font-size: 1.1rem;
        line-height: 1.85;
        color: var(--article-text);
    }

    .article-content h2 {
        font-size: 1.75rem;
        color: var(--article-dark);
        margin: 2.5rem 0 1rem;
        font-weight: 700;
        line-height: 1.3;
    }

    .article-content h3 {
        font-size: 1.35rem;
        color: var(--article-dark);
        margin: 2rem 0 0.75rem;
        font-weight: 600;
    }

    .article-content p {
        margin-bottom: 1.5rem;
    }

    .article-content ul,
    .article-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }

    .article-content li {
        margin-bottom: 0.75rem;
    }

    .article-content strong {
        color: var(--article-dark);
        font-weight: 600;
    }

    .article-content a {
        color: var(--article-orange);
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    .article-content a:hover {
        text-decoration: none;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 2rem 0;
    }

    /* Blockquote */
    .article-content blockquote {
        background: var(--article-orange-light);
        border-left: 4px solid var(--article-orange);
        border-radius: 0 12px 12px 0;
        padding: 1.5rem 2rem;
        margin: 2rem 0;
        font-style: italic;
        font-size: 1.15rem;
        color: var(--article-dark);
    }

    .article-content blockquote p:last-child {
        margin-bottom: 0;
    }

    /* Share Section */
    .share-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem 0;
        border-top: 1px solid var(--article-border);
        margin-top: 2rem;
    }

    .share-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--article-dark);
    }

    .share-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .share-btn {
        width: 40px;
        height: 40px;
        border: 1px solid var(--article-border);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--article-text);
        transition: all 0.2s ease;
    }

    .share-btn:hover {
        border-color: var(--article-orange);
        color: var(--article-orange);
    }

    .share-btn svg {
        width: 18px;
        height: 18px;
    }

    /* Sidebar Styles */
    .article-sidebar {
        position: sticky;
        top: 2rem;
        padding: 3rem 0;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .sidebar-widget {
        background: #F8FAFC;
        border: 1px solid var(--article-border);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .sidebar-widget h4 {
        font-size: 1rem;
        color: var(--article-dark);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    /* CTA Widget */
    .cta-widget {
        background: linear-gradient(135deg, #1E293B 0%, #334155 100%) !important;
        border: none !important;
    }

    .cta-widget h4 {
        color: white !important;
    }

    .cta-widget p {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.75);
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .cta-widget .btn-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        justify-content: center;
        padding: 0.625rem 1rem;
        background: var(--article-orange);
        color: white;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .cta-widget .btn-cta:hover {
        background: #d4851c;
        transform: translateY(-1px);
    }

    .cta-widget .btn-cta svg {
        width: 16px;
        height: 16px;
    }

    /* Related Articles Section */
    .related-section {
        padding: 5rem 0;
        background: #F8FAFC;
    }

    .related-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .related-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .related-header h2 {
        font-size: 2rem;
        color: var(--article-dark);
        margin-bottom: 0.5rem;
    }

    .related-header p {
        color: var(--article-text);
        font-size: 1.05rem;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .related-card {
        background: white;
        border: 1px solid var(--article-border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .related-card:hover {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        transform: translateY(-4px);
    }

    .related-card-image {
        height: 160px;
        background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .related-card-image svg {
        width: 48px;
        height: 48px;
        color: rgba(255, 255, 255, 0.2);
    }

    .related-card-content {
        padding: 1.5rem;
    }

    .related-card-category {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--article-orange);
        margin-bottom: 0.5rem;
    }

    .related-card-title {
        font-size: 1.05rem;
        color: var(--article-dark);
        line-height: 1.4;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .related-card-meta {
        font-size: 0.85rem;
        color: var(--article-text-light);
    }

    /* Newsletter CTA */
    .newsletter-cta {
        padding: 4rem 0;
        background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
    }

    .newsletter-cta-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 3rem;
        flex-wrap: wrap;
    }

    .newsletter-cta-content h2 {
        font-size: 1.75rem;
        color: white;
        margin-bottom: 0.5rem;
    }

    .newsletter-cta-content p {
        color: rgba(255, 255, 255, 0.7);
        margin: 0;
        font-size: 1rem;
    }

    .newsletter-cta-form {
        display: flex;
        gap: 0.75rem;
    }

    .newsletter-cta-form input {
        padding: 0.875rem 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        font-size: 0.95rem;
        color: white;
        min-width: 280px;
    }

    .newsletter-cta-form input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .newsletter-cta-form input:focus {
        outline: none;
        border-color: var(--article-orange);
    }

    .newsletter-cta-form button {
        padding: 0.875rem 1.5rem;
        background: var(--article-orange);
        border: none;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .newsletter-cta-form button:hover {
        background: #d4851c;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .article-layout {
            grid-template-columns: 1fr;
        }

        .article-sidebar {
            position: static;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .article-hero {
            padding: 6rem 0 3rem;
        }

        .article-hero h1 {
            font-size: 2rem;
        }

        .article-excerpt {
            font-size: 1.05rem;
        }

        .article-sidebar {
            grid-template-columns: 1fr;
        }

        .related-grid {
            grid-template-columns: 1fr;
        }

        .newsletter-cta-container {
            flex-direction: column;
            text-align: center;
        }

        .newsletter-cta-form {
            flex-direction: column;
            width: 100%;
        }

        .newsletter-cta-form input {
            min-width: auto;
            width: 100%;
        }
    }
</style>

<!-- Article Hero -->
<section class="article-hero">
    <div class="article-hero-inner">
        <div class="article-meta-top">
            <a href="<?php echo url('blogs.php'); ?>#<?php echo htmlspecialchars($article['category_slug']); ?>"
                class="article-category-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5z" />
                    <path d="M2 17l10 5 10-5" />
                    <path d="M2 12l10 5 10-5" />
                </svg>
                <?php echo htmlspecialchars($article['category_name']); ?>
            </a>
            <span class="article-date-badge"><?php echo date('F j, Y', strtotime($article['published_at'])); ?></span>
        </div>

        <h1><?php echo htmlspecialchars($article['title']); ?></h1>

        <p class="article-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>

        <div class="article-author-card">
            <div class="author-avatar-large"><?php echo $authorInitials; ?></div>
            <div class="author-info-details">
                <span
                    class="author-info-name"><?php echo htmlspecialchars($article['author_name'] ?? 'System Admin'); ?></span>
                <span class="author-info-role">Solutions OptiSpace</span>
                <div class="author-info-meta">
                    <span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <?php echo $article['read_time']; ?> min read
                    </span>
                    <span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        <?php echo number_format($article['view_count']); ?> views
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="article-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li><a href="<?php echo url('blogs.php'); ?>">Blog</a></li>
        <li><?php echo htmlspecialchars($article['title']); ?></li>
    </ul>
</nav>

<!-- Article Layout -->
<div class="article-layout">
    <!-- Main Content -->
    <main class="article-main">
        <article class="article-content">
            <?php echo $article['content']; ?>

            <!-- Share Section -->
            <div class="share-section">
                <span class="share-label">Share this article:</span>
                <div class="share-buttons">
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(url('blog/article.php?slug=' . $article['slug'])); ?>&title=<?php echo urlencode($article['title']); ?>"
                        target="_blank" class="share-btn" title="Share on LinkedIn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z" />
                            <rect x="2" y="9" width="4" height="12" />
                            <circle cx="4" cy="4" r="2" />
                        </svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(url('blog/article.php?slug=' . $article['slug'])); ?>&text=<?php echo urlencode($article['title']); ?>"
                        target="_blank" class="share-btn" title="Share on Twitter">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z" />
                        </svg>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode($article['title']); ?>&body=<?php echo urlencode('Check out this article: ' . url('blog/article.php?slug=' . $article['slug'])); ?>"
                        class="share-btn" title="Share via Email">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                    </a>
                </div>
            </div>
        </article>
    </main>

    <!-- Sidebar -->
    <aside class="article-sidebar">
        <!-- CTA Widget -->
        <div class="sidebar-widget cta-widget">
            <h4>Ready to Transform Your Factory?</h4>
            <p>Start with a complimentary Pulse Check to identify optimization opportunities.</p>
            <a href="<?php echo url('pulse-check.php'); ?>" class="btn-cta">
                Request Pulse Check
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14" />
                    <path d="M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </aside>
</div>

<?php if ($relatedArticles->num_rows > 0): ?>
    <!-- Related Articles Section -->
    <section class="related-section">
        <div class="related-container">
            <div class="related-header">
                <h2>Continue Reading</h2>
                <p>More articles you might find interesting</p>
            </div>

            <div class="related-grid">
                <?php while ($related = $relatedArticles->fetch_assoc()): ?>
                    <a href="<?php echo url('blog/article.php?slug=' . $related['slug']); ?>" class="related-card">
                        <div class="related-card-image">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 2L2 7l10 5 10-5-10-5z" />
                                <path d="M2 17l10 5 10-5" />
                                <path d="M2 12l10 5 10-5" />
                            </svg>
                        </div>
                        <div class="related-card-content">
                            <div class="related-card-category"><?php echo htmlspecialchars($related['category_name']); ?></div>
                            <h3 class="related-card-title"><?php echo htmlspecialchars($related['title']); ?></h3>
                            <div class="related-card-meta"><?php echo date('M j, Y', strtotime($related['published_at'])); ?> •
                                <?php echo $related['read_time']; ?> min read
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Newsletter CTA -->
<section class="newsletter-cta">
    <div class="newsletter-cta-container">
        <div class="newsletter-cta-content">
            <h2>Get More Insights Delivered</h2>
            <p>Subscribe to receive our monthly newsletter with the latest articles on lean manufacturing and factory
                optimization.</p>
        </div>
        <form class="newsletter-cta-form" action="#" method="POST">
            <input type="email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
        </form>
    </div>
</section>

<?php include '../includes/footer.php'; ?>