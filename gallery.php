<?php
$pageTitle = "Project Gallery | Solutions OptiSpace";
$pageDescription = "Explore our portfolio of lean factory designs, facility transformations, and successful project implementations across industries.";
$currentPage = "gallery";

// Database connection
require_once __DIR__ . '/database/db_config.php';
$conn = getDBConnection();

// Fetch gallery categories
$categoriesResult = $conn->query("SELECT * FROM gallery_categories WHERE is_active = 1 ORDER BY sort_order ASC");
$categories = [];
while ($row = $categoriesResult->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch gallery items with category info
$itemsResult = $conn->query("
    SELECT gi.*, gc.name as category_name, gc.slug as category_slug, gc.bg_class, gc.color
    FROM gallery_items gi
    JOIN gallery_categories gc ON gi.category_id = gc.id
    WHERE gi.is_active = 1
    ORDER BY gi.sort_order ASC, gi.created_at DESC
");
$galleryItems = [];
while ($row = $itemsResult->fetch_assoc()) {
    $galleryItems[] = $row;
}

// Fetch featured projects with category info
$featuredResult = $conn->query("
    SELECT fp.*, gi.id as item_id, gc.slug as category_slug, gc.bg_class, gc.name as category_name
    FROM featured_projects fp
    LEFT JOIN gallery_items gi ON fp.gallery_item_id = gi.id
    LEFT JOIN gallery_categories gc ON gi.category_id = gc.id
    WHERE fp.is_active = 1
    ORDER BY fp.is_primary DESC, fp.sort_order ASC
");
$featuredProjects = [];
while ($row = $featuredResult->fetch_assoc()) {
    $featuredProjects[] = $row;
}

// Fetch industries
$industriesResult = $conn->query("SELECT * FROM gallery_industries WHERE is_active = 1 ORDER BY sort_order ASC");
$industries = [];
while ($row = $industriesResult->fetch_assoc()) {
    $industries[] = $row;
}

// Calculate stats
$totalProjects = count($galleryItems);
$totalIndustries = count($industries);
$totalSqFt = 0;
foreach ($galleryItems as $item) {
    if (preg_match('/(\d+)[,\s]*(\d*)\s*(sq\s*ft|square\s*feet)/i', $item['project_size'] ?? '', $matches)) {
        $sqft = (int)str_replace(',', '', $matches[1] . $matches[2]);
        $totalSqFt += $sqft;
    }
}
$sqFtDisplay = $totalSqFt >= 1000000 ? round($totalSqFt / 1000000, 1) . 'M+' : round($totalSqFt / 1000) . 'K+';

$conn->close();

include 'includes/header.php';
?>

<style>
/* ========================================
   GALLERY PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --gallery-orange: #E99431;
    --gallery-orange-light: rgba(233, 148, 49, 0.08);
    --gallery-blue: #3B82F6;
    --gallery-blue-light: rgba(59, 130, 246, 0.08);
    --gallery-green: #10B981;
    --gallery-green-light: rgba(16, 185, 129, 0.08);
    --gallery-gray: #64748B;
    --gallery-gray-light: rgba(100, 116, 139, 0.08);
    --gallery-dark: #1E293B;
    --gallery-text: #475569;
    --gallery-border: #E2E8F0;
}

/* Hero Section */
.gallery-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 6rem;
    position: relative;
    overflow: hidden;
}

.gallery-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
}

.gallery-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.gallery-hero-content {
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

.gallery-hero h1 {
    font-size: 3.25rem;
    font-weight: 700;
    color: white;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

.gallery-hero h1 span {
    color: #E99431;
}

.gallery-hero-text {
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

.hero-gallery-preview {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    max-width: 400px;
}

.preview-image {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.3s ease;
    aspect-ratio: 4/3;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-image:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(233, 148, 49, 0.3);
    transform: translateY(-2px);
}

.preview-image svg {
    width: 40px;
    height: 40px;
    color: rgba(255, 255, 255, 0.4);
}

.preview-image:nth-child(1) { grid-row: span 2; aspect-ratio: 3/4; }
.preview-image:nth-child(1) svg { color: var(--gallery-orange); opacity: 0.8; }
.preview-image:nth-child(2) svg { color: var(--gallery-blue); opacity: 0.8; }
.preview-image:nth-child(3) svg { color: var(--gallery-green); opacity: 0.8; }

/* Breadcrumb */
.gallery-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--gallery-border);
}

.gallery-breadcrumb ul {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.gallery-breadcrumb a {
    color: var(--gallery-gray);
    text-decoration: none;
}

.gallery-breadcrumb a:hover {
    color: var(--gallery-orange);
}

.gallery-breadcrumb li:last-child {
    color: var(--gallery-dark);
    font-weight: 500;
}

.gallery-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--gallery-border);
}

/* Responsive Hero */
@media (max-width: 1024px) {
    .gallery-hero-inner {
        grid-template-columns: 1fr;
        gap: 3rem;
        text-align: center;
    }
    
    .gallery-hero-text {
        margin: 0 auto 2rem;
    }
    
    .hero-stats {
        justify-content: center;
    }
    
    .hero-gallery-preview {
        max-width: 320px;
        margin: 0 auto;
    }
}

@media (max-width: 768px) {
    .gallery-hero {
        padding: 5rem 0 4rem;
    }
    
    .gallery-hero h1 {
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
<section class="gallery-hero">
    <div class="gallery-hero-inner">
        <div class="gallery-hero-content">
            <div class="hero-eyebrow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
                Visual Portfolio
            </div>
            <h1>Project <span>Gallery</span></h1>
            <p class="gallery-hero-text">Explore our collection of lean factory transformations, facility designs, and successful implementations across diverse industries.</p>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-value"><?php echo $totalProjects; ?>+</div>
                    <div class="hero-stat-label">Projects Completed</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value"><?php echo $totalIndustries; ?></div>
                    <div class="hero-stat-label">Industries Served</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value"><?php echo $sqFtDisplay; ?></div>
                    <div class="hero-stat-label">Sq Ft Optimized</div>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-gallery-preview">
                <div class="preview-image">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <div class="preview-image">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                        <path d="M16 7V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v3"/>
                    </svg>
                </div>
                <div class="preview-image">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="gallery-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li>Gallery</li>
    </ul>
</nav>

<!-- Gallery Filter Section -->
<section class="gallery-filter-section">
    <div class="gallery-container">
        <div class="section-header">
            <h2>Explore Our Work</h2>
            <p>Browse through our completed projects across different categories and industries</p>
        </div>
        
        <div class="filter-controls">
            <button class="filter-btn active" data-filter="all">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"/>
                    <rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/>
                </svg>
                All Projects
            </button>
            <?php foreach ($categories as $cat): ?>
            <button class="filter-btn" data-filter="<?php echo htmlspecialchars($cat['slug']); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <?php
                    // Icon based on category slug
                    switch ($cat['slug']) {
                        case 'greenfield':
                            echo '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>';
                            break;
                        case 'brownfield':
                            echo '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>';
                            break;
                        case 'layout':
                            echo '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>';
                            break;
                        case 'process':
                            echo '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4"/>';
                            break;
                        default:
                            echo '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>';
                    }
                    ?>
                </svg>
                <?php echo htmlspecialchars($cat['name']); ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
/* Gallery Filter Section */
.gallery-filter-section {
    padding: 4rem 0 2rem;
    background: white;
}

.gallery-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.section-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.section-header h2 {
    font-size: 2.5rem;
    color: var(--gallery-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.section-header p {
    font-size: 1.15rem;
    color: var(--gallery-text);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.7;
}

.filter-controls {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.75rem;
}

.filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: white;
    border: 1px solid var(--gallery-border);
    border-radius: 100px;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--gallery-text);
    cursor: pointer;
    transition: all 0.25s ease;
}

.filter-btn:hover {
    border-color: var(--gallery-orange);
    color: var(--gallery-orange);
}

.filter-btn.active {
    background: var(--gallery-orange);
    border-color: var(--gallery-orange);
    color: white;
}

.filter-btn svg {
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .filter-controls {
        gap: 0.5rem;
    }
    
    .filter-btn {
        padding: 0.625rem 1rem;
        font-size: 0.85rem;
    }
}
</style>

<!-- Gallery Grid -->
<section class="gallery-grid-section">
    <div class="gallery-container">
        <div class="gallery-grid">
            <?php foreach ($galleryItems as $index => $item): ?>
            <div class="gallery-item" data-category="<?php echo htmlspecialchars($item['category_slug']); ?>">
                <div class="gallery-card">
                    <div class="gallery-image">
                        <?php if (!empty($item['image_path'])): ?>
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="width:100%;height:100%;object-fit:cover;">
                        <?php else: ?>
                        <div class="image-placeholder <?php echo htmlspecialchars($item['bg_class']); ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <?php
                                switch ($item['category_slug']) {
                                    case 'greenfield':
                                        echo '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>';
                                        break;
                                    case 'brownfield':
                                        echo '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>';
                                        break;
                                    case 'layout':
                                        echo '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>';
                                        break;
                                    case 'process':
                                        echo '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>';
                                        break;
                                    default:
                                        echo '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>';
                                }
                                ?>
                            </svg>
                        </div>
                        <?php endif; ?>
                        <div class="gallery-overlay">
                            <button class="view-btn" data-index="<?php echo $index; ?>">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.35-4.35"/>
                                    <line x1="11" y1="8" x2="11" y2="14"/>
                                    <line x1="8" y1="11" x2="14" y2="11"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="gallery-info">
                        <span class="gallery-tag <?php echo htmlspecialchars($item['category_slug']); ?>"><?php echo htmlspecialchars($item['category_name']); ?></span>
                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                        <p><?php echo htmlspecialchars($item['description']); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($galleryItems)): ?>
            <div class="empty-gallery" style="grid-column: 1/-1; text-align: center; padding: 3rem;">
                <p style="color: var(--gallery-text);">No gallery items available yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
/* Gallery Grid Section */
.gallery-grid-section {
    padding: 2rem 0 6rem;
    background: white;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.gallery-item {
    transition: all 0.4s ease;
}

.gallery-item.hidden {
    opacity: 0;
    transform: scale(0.8);
    pointer-events: none;
    position: absolute;
}

.gallery-card {
    background: white;
    border: 1px solid var(--gallery-border);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
}

.gallery-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    transform: translateY(-4px);
}

.gallery-image {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.4s ease;
}

.gallery-card:hover .image-placeholder {
    transform: scale(1.05);
}

.image-placeholder svg {
    width: 48px;
    height: 48px;
    opacity: 0.5;
}

.greenfield-bg {
    background: linear-gradient(135deg, #FEF3E2 0%, #FDE68A 100%);
}
.greenfield-bg svg { color: var(--gallery-orange); }

.brownfield-bg {
    background: linear-gradient(135deg, #DBEAFE 0%, #93C5FD 100%);
}
.brownfield-bg svg { color: var(--gallery-blue); }

.layout-bg {
    background: linear-gradient(135deg, #D1FAE5 0%, #6EE7B7 100%);
}
.layout-bg svg { color: var(--gallery-green); }

.process-bg {
    background: linear-gradient(135deg, #E2E8F0 0%, #CBD5E1 100%);
}
.process-bg svg { color: var(--gallery-gray); }

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(30, 41, 59, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-card:hover .gallery-overlay {
    opacity: 1;
}

.view-btn {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    transform: scale(0.8);
}

.gallery-card:hover .view-btn {
    transform: scale(1);
}

.view-btn:hover {
    background: var(--gallery-orange);
    color: white;
}

.view-btn svg {
    color: var(--gallery-dark);
    transition: color 0.3s ease;
}

.view-btn:hover svg {
    color: white;
}

.gallery-info {
    padding: 1.25rem;
}

.gallery-tag {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
}

.gallery-tag.greenfield {
    background: var(--gallery-orange-light);
    color: var(--gallery-orange);
}

.gallery-tag.brownfield {
    background: var(--gallery-blue-light);
    color: var(--gallery-blue);
}

.gallery-tag.layout {
    background: var(--gallery-green-light);
    color: var(--gallery-green);
}

.gallery-tag.process {
    background: var(--gallery-gray-light);
    color: var(--gallery-gray);
}

.gallery-info h3 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gallery-dark);
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.gallery-info p {
    font-size: 0.875rem;
    color: var(--gallery-text);
    line-height: 1.5;
    margin: 0;
}

@media (max-width: 1024px) {
    .gallery-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .gallery-info {
        padding: 1rem;
    }
    
    .gallery-info h3 {
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .gallery-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Featured Projects Section -->
<section class="featured-section">
    <div class="gallery-container">
        <div class="section-header">
            <h2>Featured Transformations</h2>
            <p>Highlighted case studies showcasing our most impactful lean factory implementations</p>
        </div>
        
        <div class="featured-grid">
            <?php foreach ($featuredProjects as $index => $project): ?>
            <?php 
            $isPrimary = $project['is_primary'];
            $bgClass = $project['bg_class'] ?? 'greenfield-bg';
            $categorySlug = $project['category_slug'] ?? 'greenfield';
            $categoryName = $project['category_name'] ?? 'Project';
            ?>
            <div class="featured-card <?php echo $isPrimary ? 'primary' : ''; ?>">
                <div class="featured-image">
                    <?php if (!empty($project['image_path'])): ?>
                    <img src="<?php echo htmlspecialchars($project['image_path']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" style="width:100%;height:100%;object-fit:cover;">
                    <?php else: ?>
                    <div class="image-placeholder <?php echo htmlspecialchars($bgClass); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <?php
                            switch ($categorySlug) {
                                case 'greenfield':
                                    echo '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>';
                                    break;
                                case 'brownfield':
                                    echo '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>';
                                    break;
                                case 'layout':
                                    echo '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>';
                                    break;
                                default:
                                    echo '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>';
                            }
                            ?>
                        </svg>
                    </div>
                    <?php endif; ?>
                    <?php if ($isPrimary): ?>
                    <div class="featured-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        Featured
                    </div>
                    <?php endif; ?>
                </div>
                <div class="featured-content">
                    <div class="featured-meta">
                        <span class="gallery-tag <?php echo htmlspecialchars($categorySlug); ?>"><?php echo htmlspecialchars($categoryName); ?></span>
                        <?php if ($isPrimary && !empty($project['location'])): ?>
                        <span class="featured-location">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <?php echo htmlspecialchars($project['location']); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                    <p><?php echo htmlspecialchars($project['description']); ?></p>
                    <div class="featured-stats <?php echo $isPrimary ? '' : 'compact'; ?>">
                        <?php if (!empty($project['stat_1_value'])): ?>
                        <div class="featured-stat">
                            <span class="stat-value"><?php echo htmlspecialchars($project['stat_1_value']); ?></span>
                            <span class="stat-label"><?php echo htmlspecialchars($project['stat_1_label']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($project['stat_2_value'])): ?>
                        <div class="featured-stat">
                            <span class="stat-value"><?php echo htmlspecialchars($project['stat_2_value']); ?></span>
                            <span class="stat-label"><?php echo htmlspecialchars($project['stat_2_label']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($isPrimary && !empty($project['stat_3_value'])): ?>
                        <div class="featured-stat">
                            <span class="stat-value"><?php echo htmlspecialchars($project['stat_3_value']); ?></span>
                            <span class="stat-label"><?php echo htmlspecialchars($project['stat_3_label']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($isPrimary): ?>
                    <a href="<?php echo url('portfolio.php'); ?>" class="featured-link">
                        View Case Study
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($featuredProjects)): ?>
            <div class="empty-featured" style="grid-column: 1/-1; text-align: center; padding: 3rem;">
                <p style="color: var(--gallery-text);">No featured projects available yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
/* Featured Section */
.featured-section {
    padding: 6rem 0;
    background: #F8FAFC;
}

.featured-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    grid-template-rows: repeat(2, 1fr);
    gap: 1.5rem;
}

.featured-card {
    background: white;
    border: 1px solid var(--gallery-border);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
}

.featured-card:hover {
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.1);
    transform: translateY(-4px);
}

.featured-card.primary {
    grid-row: span 2;
}

.featured-card.primary .featured-image {
    height: 280px;
}

.featured-card:not(.primary) .featured-image {
    height: 140px;
}

.featured-image {
    position: relative;
    overflow: hidden;
}

.featured-image .image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.featured-image .image-placeholder svg {
    width: 64px;
    height: 64px;
    opacity: 0.4;
}

.featured-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: var(--gallery-orange);
    color: white;
    padding: 0.4rem 0.75rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
}

.featured-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.featured-card.primary .featured-content {
    padding: 2rem;
}

.featured-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}

.featured-location {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    color: var(--gallery-text);
    font-size: 0.85rem;
}

.featured-content h3 {
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--gallery-dark);
    margin-bottom: 0.75rem;
    line-height: 1.3;
}

.featured-card:not(.primary) .featured-content h3 {
    font-size: 1.1rem;
}

.featured-content p {
    color: var(--gallery-text);
    line-height: 1.7;
    margin-bottom: 1.5rem;
    flex: 1;
}

.featured-card:not(.primary) .featured-content p {
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.featured-stats {
    display: flex;
    gap: 2rem;
    margin-bottom: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid var(--gallery-border);
}

.featured-stats.compact {
    gap: 1.5rem;
    margin-bottom: 0;
}

.featured-stat {
    text-align: left;
}

.stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gallery-dark);
    line-height: 1;
}

.featured-stats.compact .stat-value {
    font-size: 1.25rem;
}

.stat-label {
    font-size: 0.8rem;
    color: var(--gallery-text);
}

.featured-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gallery-orange);
    font-weight: 600;
    text-decoration: none;
    transition: gap 0.3s ease;
}

.featured-link:hover {
    gap: 0.75rem;
}

@media (max-width: 1024px) {
    .featured-grid {
        grid-template-columns: 1fr;
        grid-template-rows: auto;
    }
    
    .featured-card.primary {
        grid-row: span 1;
    }
}

@media (max-width: 768px) {
    .featured-section {
        padding: 4rem 0;
    }
    
    .featured-card.primary .featured-content {
        padding: 1.5rem;
    }
    
    .featured-stats {
        flex-wrap: wrap;
        gap: 1.5rem;
    }
}
</style>

<!-- Lightbox Modal -->
<div class="lightbox-modal" id="lightboxModal">
    <div class="lightbox-overlay"></div>
    <div class="lightbox-container">
        <button class="lightbox-close" id="lightboxClose">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
        <button class="lightbox-nav lightbox-prev" id="lightboxPrev">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
        </button>
        <button class="lightbox-nav lightbox-next" id="lightboxNext">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6"/>
            </svg>
        </button>
        <div class="lightbox-content">
            <div class="lightbox-image" id="lightboxImage">
                <!-- Dynamic content will be inserted here -->
            </div>
            <div class="lightbox-info" id="lightboxInfo">
                <span class="lightbox-tag" id="lightboxTag">Category</span>
                <h3 id="lightboxTitle">Project Title</h3>
                <p id="lightboxDesc">Project description</p>
            </div>
        </div>
        <div class="lightbox-counter">
            <span id="lightboxCurrent">1</span> / <span id="lightboxTotal">8</span>
        </div>
    </div>
</div>

<style>
/* Lightbox Modal */
.lightbox-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.lightbox-modal.active {
    opacity: 1;
    visibility: visible;
}

.lightbox-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.95);
}

.lightbox-container {
    position: relative;
    width: 90%;
    max-width: 900px;
    max-height: 90vh;
    z-index: 1;
}

.lightbox-close {
    position: absolute;
    top: -3rem;
    right: 0;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.lightbox-close:hover {
    background: var(--gallery-orange);
}

.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    z-index: 2;
}

.lightbox-nav:hover {
    background: var(--gallery-orange);
}

.lightbox-prev {
    left: -4rem;
}

.lightbox-next {
    right: -4rem;
}

.lightbox-content {
    background: white;
    border-radius: 16px;
    overflow: hidden;
}

.lightbox-image {
    aspect-ratio: 16/10;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-image svg {
    width: 80px;
    height: 80px;
    opacity: 0.4;
}

.lightbox-info {
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--gallery-border);
}

.lightbox-tag {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
    background: var(--gallery-orange-light);
    color: var(--gallery-orange);
}

.lightbox-info h3 {
    font-size: 1.35rem;
    color: var(--gallery-dark);
    margin-bottom: 0.5rem;
}

.lightbox-info p {
    color: var(--gallery-text);
    line-height: 1.6;
    margin: 0;
}

.lightbox-counter {
    text-align: center;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 1rem;
    font-size: 0.9rem;
}

@media (max-width: 1024px) {
    .lightbox-nav {
        top: auto;
        bottom: -4rem;
        transform: none;
    }
    
    .lightbox-prev {
        left: calc(50% - 60px);
    }
    
    .lightbox-next {
        right: calc(50% - 60px);
    }
}

@media (max-width: 768px) {
    .lightbox-container {
        width: 95%;
    }
    
    .lightbox-info {
        padding: 1rem 1.5rem;
    }
    
    .lightbox-info h3 {
        font-size: 1.1rem;
    }
}
</style>

<script>
// Gallery Data - Dynamically generated from database
const galleryData = <?php 
$jsGalleryData = [];
foreach ($galleryItems as $item) {
    $jsGalleryData[] = [
        'category' => $item['category_slug'],
        'tag' => $item['category_name'],
        'title' => $item['title'],
        'description' => $item['description'] ?? '',
        'bgClass' => $item['bg_class'],
        'imagePath' => $item['image_path'] ?? ''
    ];
}
echo json_encode($jsGalleryData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?>;

document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filter items with animation
            galleryItems.forEach(item => {
                const category = item.dataset.category;
                if (filter === 'all' || category === filter) {
                    item.style.display = '';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 50);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
    
    // Lightbox functionality
    const lightboxModal = document.getElementById('lightboxModal');
    const lightboxClose = document.getElementById('lightboxClose');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxTag = document.getElementById('lightboxTag');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxDesc = document.getElementById('lightboxDesc');
    const lightboxCurrent = document.getElementById('lightboxCurrent');
    const lightboxTotal = document.getElementById('lightboxTotal');
    const viewBtns = document.querySelectorAll('.view-btn');
    
    let currentIndex = 0;
    lightboxTotal.textContent = galleryData.length;
    
    function openLightbox(index) {
        currentIndex = index;
        updateLightbox();
        lightboxModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox() {
        lightboxModal.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    function updateLightbox() {
        const data = galleryData[currentIndex];
        lightboxImage.className = 'lightbox-image ' + data.bgClass;
        
        // Check if image exists
        if (data.imagePath && data.imagePath.length > 0) {
            lightboxImage.innerHTML = '<img src="' + data.imagePath + '" alt="' + data.title + '" style="width:100%;height:100%;object-fit:contain;">';
        } else {
            lightboxImage.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>';
        }
        
        lightboxTag.textContent = data.tag;
        lightboxTag.className = 'lightbox-tag';
        lightboxTitle.textContent = data.title;
        lightboxDesc.textContent = data.description;
        lightboxCurrent.textContent = currentIndex + 1;
    }
    
    function nextSlide() {
        currentIndex = (currentIndex + 1) % galleryData.length;
        updateLightbox();
    }
    
    function prevSlide() {
        currentIndex = (currentIndex - 1 + galleryData.length) % galleryData.length;
        updateLightbox();
    }
    
    // Event listeners
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            openLightbox(index);
        });
    });
    
    lightboxClose.addEventListener('click', closeLightbox);
    lightboxNext.addEventListener('click', nextSlide);
    lightboxPrev.addEventListener('click', prevSlide);
    
    document.querySelector('.lightbox-overlay').addEventListener('click', closeLightbox);
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!lightboxModal.classList.contains('active')) return;
        
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') nextSlide();
        if (e.key === 'ArrowLeft') prevSlide();
    });
});
</script>

<!-- Industries Section -->
<section class="industries-section">
    <div class="gallery-container">
        <div class="section-header">
            <h2>Industries We Serve</h2>
            <p>Our lean factory expertise spans across diverse manufacturing sectors</p>
        </div>
        
        <div class="industries-grid">
            <?php foreach ($industries as $industry): ?>
            <div class="industry-card">
                <div class="industry-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <?php
                        switch ($industry['icon']) {
                            case 'car':
                                echo '<path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C1.4 11.3 1 12.1 1 13v3c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/>';
                                break;
                            case 'medical':
                                echo '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>';
                                break;
                            case 'chip':
                                echo '<rect x="4" y="4" width="16" height="16" rx="2" ry="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/>';
                                break;
                            case 'food':
                                echo '<path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>';
                                break;
                            case 'plane':
                                echo '<path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>';
                                break;
                            case 'heart':
                                echo '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>';
                                break;
                            case 'cog':
                                echo '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4"/>';
                                break;
                            default:
                                echo '<path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/>';
                        }
                        ?>
                    </svg>
                </div>
                <h4><?php echo htmlspecialchars($industry['name']); ?></h4>
                <span><?php echo $industry['project_count']; ?>+ Projects</span>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($industries)): ?>
            <div class="empty-industries" style="grid-column: 1/-1; text-align: center; padding: 2rem;">
                <p style="color: var(--gallery-text);">No industries available yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
/* Industries Section */
.industries-section {
    padding: 6rem 0;
    background: white;
}

.industries-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 1.5rem;
}

.industry-card {
    background: #FAFBFC;
    border: 1px solid var(--gallery-border);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.industry-card:hover {
    background: white;
    border-color: var(--gallery-orange);
    box-shadow: 0 8px 25px rgba(233, 148, 49, 0.1);
    transform: translateY(-2px);
}

.industry-icon {
    width: 48px;
    height: 48px;
    background: var(--gallery-orange-light);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.industry-icon svg {
    width: 24px;
    height: 24px;
    color: var(--gallery-orange);
}

.industry-card h4 {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--gallery-dark);
    margin-bottom: 0.25rem;
}

.industry-card span {
    font-size: 0.8rem;
    color: var(--gallery-text);
}

@media (max-width: 1024px) {
    .industries-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .industries-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<!-- Final CTA Section -->
<section class="gallery-cta">
    <div class="gallery-container">
        <div class="cta-card">
            <div class="cta-content">
                <div class="cta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
                <h2>Ready to Transform Your Facility?</h2>
                <p>Let us add your project to our success gallery. Start with a complimentary LFB Pulse Check consultation.</p>
                <div class="cta-buttons">
                    <a href="<?php echo url('pulse-check.php'); ?>" class="btn-modern btn-primary-modern btn-large">
                        Start Your Journey
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
                    <a href="<?php echo url('portfolio.php'); ?>" class="btn-modern btn-outline-light btn-large">
                        View Full Portfolio
                    </a>
                </div>
            </div>
            <div class="cta-visual">
                <div class="cta-stats-row">
                    <div class="cta-stat-item">
                        <div class="cta-stat-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                            </svg>
                        </div>
                        <div>
                            <span class="cta-stat-value">98%</span>
                            <span class="cta-stat-label">Client Satisfaction</span>
                        </div>
                    </div>
                    <div class="cta-stat-item">
                        <div class="cta-stat-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div>
                            <span class="cta-stat-value">15+</span>
                            <span class="cta-stat-label">Years Experience</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Final CTA Section */
.gallery-cta {
    padding: 6rem 0;
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
}

.cta-card {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 4rem;
    align-items: center;
}

.cta-icon {
    width: 64px;
    height: 64px;
    background: rgba(233, 148, 49, 0.15);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.cta-icon svg {
    width: 32px;
    height: 32px;
    color: var(--gallery-orange);
}

.cta-content h2 {
    font-size: 2.5rem;
    color: white;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.cta-content p {
    font-size: 1.15rem;
    color: rgba(255, 255, 255, 0.75);
    margin-bottom: 2rem;
    line-height: 1.7;
    max-width: 500px;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
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
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
}

.cta-visual {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.cta-stats-row {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.cta-stat-item {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.cta-stat-icon {
    width: 48px;
    height: 48px;
    background: rgba(233, 148, 49, 0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.cta-stat-icon svg {
    width: 24px;
    height: 24px;
    color: var(--gallery-orange);
}

.cta-stat-value {
    display: block;
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    line-height: 1;
}

.cta-stat-label {
    display: block;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 0.25rem;
}

@media (max-width: 1024px) {
    .cta-card {
        grid-template-columns: 1fr;
        gap: 2.5rem;
    }
    
    .cta-stats-row {
        flex-direction: row;
    }
    
    .cta-stat-item {
        flex: 1;
    }
}

@media (max-width: 768px) {
    .gallery-cta {
        padding: 4rem 0;
    }
    
    .cta-content h2 {
        font-size: 2rem;
    }
    
    .cta-buttons {
        flex-direction: column;
    }
    
    .cta-stats-row {
        flex-direction: column;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
