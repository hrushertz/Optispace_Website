<?php
$pageTitle = "Downloads | Solutions OptiSpace";
$pageDescription = "Download brochures, case studies, and resources from Solutions OptiSpace.";
$currentPage = "downloads";
include 'includes/header.php';

// Include database
require_once 'database/db_config.php';

// Get database connection
$conn = getDBConnection();

// Get active categories with download counts
$categoriesQuery = "
    SELECT c.*, 
           (SELECT COUNT(*) FROM downloads d WHERE d.category_id = c.id AND d.is_active = 1) as download_count
    FROM download_categories c 
    WHERE c.is_active = 1 
    ORDER BY c.sort_order, c.name
";
$categoriesResult = $conn->query($categoriesQuery);
$categories = [];
$totalResources = 0;
while ($cat = $categoriesResult->fetch_assoc()) {
    $categories[] = $cat;
    $totalResources += $cat['download_count'];
}

// Get active downloads grouped by category
$downloadsQuery = "
    SELECT d.*, c.name as category_name, c.slug as category_slug, c.color as category_color
    FROM downloads d 
    LEFT JOIN download_categories c ON d.category_id = c.id 
    WHERE d.is_active = 1 AND c.is_active = 1
    ORDER BY c.sort_order, d.is_featured DESC, d.published_date DESC
";
$downloadsResult = $conn->query($downloadsQuery);
$downloads = [];
while ($download = $downloadsResult->fetch_assoc()) {
    $downloads[] = $download;
}

$conn->close();

// Icon SVGs for categories
$categoryIcons = [
    'book' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
    'document' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
    'cog' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>',
    'academic-cap' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
    'folder' => '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>',
    'chart' => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
    'lightbulb' => '<path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/>',
    'puzzle' => '<path d="M19.439 7.85c-.049.322.059.648.289.878l1.568 1.568c.47.47.706 1.087.706 1.704s-.235 1.233-.706 1.704l-1.611 1.611a.98.98 0 0 1-.837.276c-.47-.07-.802-.48-.968-.925a2.501 2.501 0 1 0-3.214 3.214c.446.166.855.497.925.968a.979.979 0 0 1-.276.837l-1.61 1.61a2.404 2.404 0 0 1-1.705.707 2.402 2.402 0 0 1-1.704-.706l-1.568-1.568a1.026 1.026 0 0 0-.877-.29c-.493.074-.84.504-1.02.968a2.5 2.5 0 1 1-3.237-3.237c.464-.18.894-.527.967-1.02a1.026 1.026 0 0 0-.289-.877l-1.568-1.568A2.402 2.402 0 0 1 1.998 12c0-.617.236-1.234.706-1.704L4.23 8.77c.24-.24.581-.353.917-.303.515.077.877.528 1.073 1.01a2.5 2.5 0 1 0 3.259-3.259c-.482-.196-.933-.558-1.01-1.073-.05-.336.062-.676.303-.917l1.525-1.525A2.402 2.402 0 0 1 12 1.998c.617 0 1.234.236 1.704.706l1.568 1.568c.23.23.556.338.878.29.493-.074.84-.504 1.02-.968a2.5 2.5 0 1 1 3.237 3.237c-.464.18-.894.527-.967 1.02Z"/>'
];

function getIconSvg($icon, $icons) {
    return $icons[$icon] ?? $icons['document'];
}
?>

<style>
/* ========================================
   DOWNLOADS PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --download-orange: #E99431;
    --download-orange-light: rgba(233, 148, 49, 0.08);
    --download-blue: #3B82F6;
    --download-blue-light: rgba(59, 130, 246, 0.08);
    --download-green: #10B981;
    --download-green-light: rgba(16, 185, 129, 0.08);
    --download-purple: #8B5CF6;
    --download-purple-light: rgba(139, 92, 246, 0.08);
    --download-gray: #64748B;
    --download-gray-light: rgba(100, 116, 139, 0.08);
    --download-dark: #1E293B;
    --download-text: #475569;
    --download-border: #E2E8F0;
}

/* Hero Section */
.downloads-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 6rem;
    position: relative;
    overflow: hidden;
}

.downloads-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
}

.downloads-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.downloads-hero-content {
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

.downloads-hero h1 {
    font-size: 3.25rem;
    font-weight: 700;
    color: white;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

.downloads-hero h1 span {
    color: #E99431;
}

.downloads-hero-text {
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

.hero-resource-preview {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    max-width: 400px;
}

.preview-resource {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.25rem;
    transition: all 0.3s ease;
}

.preview-resource:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(233, 148, 49, 0.3);
    transform: translateY(-2px);
}

.preview-resource-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #E99431 0%, #f5a854 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
}

.preview-resource-icon svg {
    width: 18px;
    height: 18px;
    color: white;
}

.preview-resource:nth-child(2) .preview-resource-icon {
    background: linear-gradient(135deg, #3B82F6 0%, #60a5fa 100%);
}

.preview-resource:nth-child(3) .preview-resource-icon {
    background: linear-gradient(135deg, #10B981 0%, #34d399 100%);
}

.preview-resource:nth-child(4) .preview-resource-icon {
    background: linear-gradient(135deg, #8B5CF6 0%, #a78bfa 100%);
}

.preview-resource-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
}

.preview-resource-count {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.5);
}

/* Breadcrumb */
.downloads-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--download-border);
}

.downloads-breadcrumb ul {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.downloads-breadcrumb a {
    color: var(--download-gray);
    text-decoration: none;
}

.downloads-breadcrumb a:hover {
    color: var(--download-orange);
}

.downloads-breadcrumb li:last-child {
    color: var(--download-dark);
    font-weight: 500;
}

.downloads-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--download-border);
}

/* Resource Categories Section */
.resources-section {
    padding: 6rem 0;
    background: white;
}

.resources-container {
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
    color: var(--download-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.section-header p {
    font-size: 1.15rem;
    color: var(--download-text);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.7;
}

/* Category Cards */
.category-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 4rem;
}

.category-card {
    background: white;
    border: 1px solid var(--download-border);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.category-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    border-color: transparent;
    transform: translateY(-4px);
}

.category-card.active {
    border-color: var(--download-orange);
    box-shadow: 0 8px 30px rgba(233, 148, 49, 0.15);
}

.category-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.category-card:nth-child(1) .category-icon {
    background: var(--download-orange-light);
}

.category-card:nth-child(2) .category-icon {
    background: var(--download-blue-light);
}

.category-card:nth-child(3) .category-icon {
    background: var(--download-green-light);
}

.category-card:nth-child(4) .category-icon {
    background: var(--download-purple-light);
}

.category-icon svg {
    width: 28px;
    height: 28px;
}

.category-card:nth-child(1) .category-icon svg { color: var(--download-orange); }
.category-card:nth-child(2) .category-icon svg { color: var(--download-blue); }
.category-card:nth-child(3) .category-icon svg { color: var(--download-green); }
.category-card:nth-child(4) .category-icon svg { color: var(--download-purple); }

.category-card h3 {
    font-size: 1.1rem;
    color: var(--download-dark);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.category-card p {
    font-size: 0.9rem;
    color: var(--download-text);
    margin-bottom: 1rem;
    line-height: 1.5;
}

.category-count {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #F8FAFC;
    padding: 0.35rem 0.75rem;
    border-radius: 100px;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--download-gray);
}

/* Downloads Grid */
.downloads-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.download-card {
    background: white;
    border: 1px solid var(--download-border);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    gap: 1.25rem;
    transition: all 0.3s ease;
}

.download-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    border-color: transparent;
}

.download-thumbnail {
    width: 80px;
    height: 100px;
    background: linear-gradient(135deg, #F8FAFC 0%, #E2E8F0 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}

.download-thumbnail svg {
    width: 32px;
    height: 32px;
    color: var(--download-gray);
}

.file-type-badge {
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--download-orange);
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
}

.download-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.download-category {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--download-orange);
    margin-bottom: 0.5rem;
}

.download-card:nth-child(even) .download-category {
    color: var(--download-blue);
}

.download-content h4 {
    font-size: 1.1rem;
    color: var(--download-dark);
    margin-bottom: 0.5rem;
    font-weight: 600;
    line-height: 1.4;
}

.download-content p {
    font-size: 0.9rem;
    color: var(--download-text);
    line-height: 1.5;
    margin-bottom: auto;
}

.download-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--download-border);
}

.download-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.85rem;
    color: var(--download-gray);
}

.download-info span {
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.download-info svg {
    width: 14px;
    height: 14px;
}

.btn-download {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1rem;
    background: var(--download-orange);
    color: white;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-download:hover {
    background: #d4851c;
    transform: translateY(-1px);
}

.btn-download svg {
    width: 16px;
    height: 16px;
}

/* CTA Section */
.download-cta-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
}

.download-cta-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
}

.download-cta-icon {
    width: 80px;
    height: 80px;
    background: var(--download-orange-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
}

.download-cta-icon svg {
    width: 36px;
    height: 36px;
    color: var(--download-orange);
}

.download-cta-container h2 {
    font-size: 2rem;
    color: var(--download-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.download-cta-container > p {
    font-size: 1.1rem;
    color: var(--download-text);
    margin-bottom: 2rem;
    line-height: 1.7;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
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
}

.btn-primary-modern {
    background: var(--download-orange);
    color: white;
}

.btn-primary-modern:hover {
    background: #d4851c;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(233, 148, 49, 0.3);
}

.btn-secondary-modern {
    background: white;
    color: var(--download-dark);
    border: 1px solid var(--download-border);
}

.btn-secondary-modern:hover {
    border-color: var(--download-orange);
    color: var(--download-orange);
}

.btn-modern svg {
    width: 18px;
    height: 18px;
}

/* Responsive */
@media (max-width: 1024px) {
    .downloads-hero-inner {
        grid-template-columns: 1fr;
        gap: 3rem;
        text-align: center;
    }
    
    .downloads-hero-text {
        margin: 0 auto 2rem;
    }
    
    .hero-stats {
        justify-content: center;
    }
    
    .category-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .downloads-hero {
        padding: 5rem 0 4rem;
    }
    
    .downloads-hero h1 {
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
    
    .hero-resource-preview {
        grid-template-columns: 1fr;
        max-width: 280px;
    }
    
    .category-grid {
        grid-template-columns: 1fr;
    }
    
    .downloads-grid {
        grid-template-columns: 1fr;
    }
    
    .download-card {
        flex-direction: column;
        text-align: center;
    }
    
    .download-thumbnail {
        margin: 0 auto;
    }
    
    .download-meta {
        flex-direction: column;
        gap: 1rem;
    }
    
    .cta-buttons {
        flex-direction: column;
    }
}
</style>

<!-- Hero Section -->
<section class="downloads-hero">
    <div class="downloads-hero-inner">
        <div class="downloads-hero-content">
            <div class="hero-eyebrow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Resource Library
            </div>
            <h1>Knowledge <span>Downloads</span></h1>
            <p class="downloads-hero-text">Access our comprehensive library of brochures, case studies, technical resources, and white papers to help you make informed decisions about your facility optimization.</p>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-value"><?php echo $totalResources; ?>+</div>
                    <div class="hero-stat-label">Resources</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value"><?php echo count($categories); ?></div>
                    <div class="hero-stat-label">Categories</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">Free</div>
                    <div class="hero-stat-label">Access</div>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-resource-preview">
            <?php 
                $previewColors = ['#E99431', '#3B82F6', '#10B981', '#8B5CF6'];
                $colorIndex = 0;
                foreach (array_slice($categories, 0, 4) as $category): 
                    $bgColor = $previewColors[$colorIndex % 4];
                    $colorIndex++;
                ?>
                <div class="preview-resource">
                    <div class="preview-resource-icon" style="background: linear-gradient(135deg, <?php echo $bgColor; ?> 0%, <?php echo $bgColor; ?>cc 100%);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <?php echo getIconSvg($category['icon'], $categoryIcons); ?>
                        </svg>
                    </div>
                    <div class="preview-resource-title"><?php echo htmlspecialchars($category['name']); ?></div>
                    <div class="preview-resource-count"><?php echo $category['download_count']; ?> files</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="downloads-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li>Downloads</li>
    </ul>
</nav>

<!-- Resources Section -->
<section class="resources-section">
    <div class="resources-container">
        <div class="section-header">
            <h2>Explore Our Resources</h2>
            <p>Download free resources to learn more about lean factory building, space optimization, and our proven methodologies</p>
        </div>

        <?php if (!empty($categories)): ?>
        <!-- Category Filter Cards -->
        <div class="category-grid">
            <?php 
            $categoryIndex = 0;
            foreach ($categories as $category): 
                $bgColor = $category['color'] ?? $previewColors[$categoryIndex % 4];
            ?>
            <div class="category-card<?php echo $categoryIndex === 0 ? ' active' : ''; ?>" data-category="<?php echo htmlspecialchars($category['slug']); ?>">
                <div class="category-icon" style="background: <?php echo $bgColor; ?>15;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="<?php echo $bgColor; ?>" stroke-width="1.5">
                        <?php echo getIconSvg($category['icon'], $categoryIcons); ?>
                    </svg>
                </div>
                <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                <p><?php echo htmlspecialchars($category['description'] ?: 'Browse ' . strtolower($category['name'])); ?></p>
                <span class="category-count">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    </svg>
                    <?php echo $category['download_count']; ?> files
                </span>
            </div>
            <?php 
                $categoryIndex++;
            endforeach; 
            endif;
            ?>
        </div>

        <!-- Downloads Grid -->
        <div class="downloads-grid">
            <?php if (!empty($downloads)): ?>
            <?php foreach ($downloads as $download): 
                $categorySlug = strtolower(str_replace(' ', '-', $download['category_name']));
                $fileTypeUpper = strtoupper($download['file_type'] ?: 'PDF');
            ?>
            <div class="download-card" data-category="<?php echo htmlspecialchars($categorySlug); ?>">
                <div class="download-thumbnail">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span class="file-type-badge"><?php echo htmlspecialchars($fileTypeUpper); ?></span>
                </div>
                <div class="download-content">
                    <span class="download-category"><?php echo htmlspecialchars($download['category_name']); ?></span>
                    <h4><?php echo htmlspecialchars($download['title']); ?></h4>
                    <p><?php echo htmlspecialchars($download['description']); ?></p>
                    <div class="download-meta">
                        <div class="download-info">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                <?php echo htmlspecialchars($download['file_size'] ?: 'N/A'); ?>
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <?php echo date('M Y', strtotime($download['published_date'])); ?>
                            </span>
                        </div>
                        <a href="<?php echo htmlspecialchars($download['file_path']); ?>" class="btn-download" target="_blank">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="no-downloads-message" style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 64px; height: 64px; margin: 0 auto 1rem; opacity: 0.5;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                <h3 style="color: var(--neutral-800); margin-bottom: 0.5rem;">No Downloads Available</h3>
                <p style="color: var(--neutral-600);">Check back soon for new resources and materials.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="download-cta-section">
    <div class="download-cta-container">
        <div class="download-cta-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <h2>Need Custom Resources?</h2>
        <p>Can't find what you're looking for? Our team can prepare customized materials tailored to your specific industry or project requirements.</p>
        <div class="cta-buttons">
            <a href="<?php echo url('contact.php'); ?>" class="btn-modern btn-primary-modern">
                Contact Our Team
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
            <a href="<?php echo url('pulse-check.php'); ?>" class="btn-modern btn-secondary-modern">
                Request Pulse Check
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Category Filter JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryCards = document.querySelectorAll('.category-card');
    const downloadCards = document.querySelectorAll('.download-card');
    
    // Add "All" category card click handler
    categoryCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove active class from all cards
            categoryCards.forEach(c => c.classList.remove('active'));
            // Add active class to clicked card
            this.classList.add('active');
            
            const selectedCategory = this.dataset.category;
            
            // Filter download cards
            downloadCards.forEach(downloadCard => {
                const cardCategory = downloadCard.dataset.category;
                
                if (selectedCategory === 'all' || cardCategory === selectedCategory) {
                    downloadCard.style.display = 'block';
                    downloadCard.style.animation = 'fadeIn 0.3s ease forwards';
                } else {
                    downloadCard.style.display = 'none';
                }
            });
        });
    });
});

// Add fadeIn animation
const styleSheet = document.createElement('style');
styleSheet.textContent = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(styleSheet);
</script>

<?php include 'includes/footer.php'; ?>
