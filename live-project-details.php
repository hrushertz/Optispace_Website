<?php
/**
 * Live Project Details Page
 * Displays full content of a live project
 */

require_once 'database/db_config.php';
require_once __DIR__ . '/includes/config.php';

// Get the slug from URL
// Get parameters
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (empty($slug) && $id <= 0) {
    header('Location: ' . url('live-projects.php'));
    exit;
}

$conn = getDBConnection();

// Fetch the project
if (!empty($slug)) {
    $stmt = $conn->prepare("SELECT * FROM live_projects WHERE slug = ? AND is_active = 1");
    $stmt->bind_param("s", $slug);
} else {
    $stmt = $conn->prepare("SELECT * FROM live_projects WHERE id = ? AND is_active = 1");
    $stmt->bind_param("i", $id);
}

$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$project) {
    header('HTTP/1.0 404 Not Found');
    $pageTitle = "Project Not Found | Solutions OptiSpace";
    include 'includes/header.php';
    echo '<div style="text-align: center; padding: 8rem 2rem;"><h1>Project Not Found</h1><p>The project you are looking for does not exist or has been removed.</p><a href="' . url('live-projects.php') . '" style="color: #E99431;">Back to Live Projects</a></div>';
    include 'includes/footer.php';
    $conn->close();
    exit;
}

// Fetch related projects
$relatedStmt = $conn->prepare("
    SELECT title, slug, image_path, project_type 
    FROM live_projects 
    WHERE id != ? AND is_active = 1 
    ORDER BY created_at DESC 
    LIMIT 3
");
$relatedStmt->bind_param("i", $project['id']);
$relatedStmt->execute();
$relatedProjects = $relatedStmt->get_result();
$relatedStmt->close();

$conn->close();

// Page Meta
$pageTitle = htmlspecialchars($project['title']) . " | Live Projects";
$pageDescription = htmlspecialchars($project['description']);
$currentPage = "live-projects";

include 'includes/header.php';
?>

<style>
    :root {
        --project-orange: #E99431;
        --project-dark: #1E293B;
        --project-text: #475569;
        --project-border: #E2E8F0;
    }

    /* Hero Section */
    .project-hero {
        background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
        padding: 8rem 0 4rem;
        position: relative;
        overflow: hidden;
        color: white;
    }

    .project-hero-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        position: relative;
        z-index: 2;
    }

    .project-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(233, 148, 49, 0.15);
        color: var(--project-orange);
        padding: 0.5rem 1rem;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .project-hero h1 {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        max-width: 900px;
        color: white;
    }

    .project-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        margin-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 2rem;
    }

    .hero-meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .hero-meta-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.5);
        letter-spacing: 1px;
    }

    .hero-meta-value {
        font-size: 1.1rem;
        font-weight: 500;
    }

    /* Breadcrumb */
    .project-breadcrumb {
        background: #F8FAFC;
        padding: 1rem 0;
        border-bottom: 1px solid var(--project-border);
    }

    .project-breadcrumb ul {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        list-style: none;
        display: flex;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .project-breadcrumb a {
        color: var(--project-text);
        text-decoration: none;
    }

    .project-breadcrumb a:hover {
        color: var(--project-orange);
    }

    .project-breadcrumb li:last-child {
        color: var(--project-dark);
        font-weight: 500;
    }

    .project-breadcrumb li:not(:last-child)::after {
        content: '/';
        margin-left: 0.5rem;
        color: var(--project-border);
    }

    /* Content Layout */
    .project-layout {
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 2rem;
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 4rem;
        align-items: start;
    }

    .project-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--project-text);
        text-align: justify;
    }

    .project-content h2,
    .project-content h3 {
        color: var(--project-dark);
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .project-content h2 {
        font-size: 1.8rem;
    }

    .project-content h3 {
        font-size: 1.4rem;
    }

    .project-content p {
        margin-bottom: 1.5rem;
    }

    .project-content ul,
    .project-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }

    .project-content li {
        margin-bottom: 0.5rem;
    }

    .project-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 2rem 0;
    }

    .project-main-image {
        display: block;
        float: right;
        width: auto;
        max-width: 50%;
        max-height: 500px;
        height: auto;
        border-radius: 16px;
        margin: 0 0 2rem 3rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        clear: right;
    }

    /* Sidebar */
    .project-sidebar {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        position: sticky;
        top: 2rem;
    }

    .sidebar-project-image {
        width: 100%;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid var(--project-border);
        object-fit: cover;
    }

    .sidebar-widget {
        background: white;
        border: 1px solid var(--project-border);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .sidebar-widget h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--project-dark);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--project-border);
    }

    .status-widget {
        background: #F8FAFC;
    }

    .progress-wrapper {
        margin-bottom: 1.5rem;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--project-dark);
    }

    .progress-track {
        height: 8px;
        background: #E2E8F0;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: var(--project-orange);
        border-radius: 4px;
    }

    .phase-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .phase-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        color: var(--project-text);
    }

    .phase-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #CBD5E1;
    }

    .phase-item.active .phase-dot {
        background: var(--project-orange);
        box-shadow: 0 0 0 3px rgba(233, 148, 49, 0.2);
    }

    .phase-item.active {
        color: var(--project-dark);
        font-weight: 500;
    }

    .highlights-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .highlight-item {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        font-size: 0.95rem;
        color: var(--project-dark);
    }

    .highlight-item svg {
        color: var(--project-orange);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .cta-widget {
        background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
        border: none;
        color: white;
        text-align: center;
    }

    .cta-widget h3 {
        color: white;
        border-color: rgba(255, 255, 255, 0.1);
    }

    .cta-widget p {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 1.5rem;
    }

    .btn-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0.75rem 1rem;
        background: var(--project-orange);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-cta:hover {
        background: #d4851c;
    }

    /* Related Projects */
    .related-projects {
        background: #F8FAFC;
        padding: 5rem 0;
    }

    .related-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-top: 2rem;
    }

    .related-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--project-border);
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s;
    }

    .related-card:hover {
        transform: translateY(-5px);
    }

    .related-image {
        height: 180px;
        background: #E2E8F0;
        overflow: hidden;
    }

    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-content {
        padding: 1.5rem;
    }

    .related-type {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: var(--project-orange);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    .related-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--project-dark);
        margin: 0;
    }

    @media (max-width: 900px) {
        .project-layout {
            grid-template-columns: 1fr;
        }

        .project-sidebar {
            position: static;
        }

        .project-main-image {
            float: none;
            max-width: 100%;
            margin: 0 auto 2rem auto;
            width: 100%;
            object-fit: cover;
        }

        .related-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero Section -->
<section class="project-hero">
    <div class="project-hero-inner">
        <div class="project-hero-badge">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
            </svg>
            <?php echo htmlspecialchars($project['project_type']); ?>
        </div>

        <h1><?php echo htmlspecialchars($project['title']); ?></h1>

        <div class="project-hero-meta">
            <?php if ($project['client_name']): ?>
                <div class="hero-meta-item">
                    <span class="hero-meta-label">Client</span>
                    <span class="hero-meta-value"><?php echo htmlspecialchars($project['client_name']); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($project['industry']): ?>
                <div class="hero-meta-item">
                    <span class="hero-meta-label">Industry</span>
                    <span class="hero-meta-value"><?php echo htmlspecialchars($project['industry']); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($project['location']): ?>
                <div class="hero-meta-item">
                    <span class="hero-meta-label">Location</span>
                    <span class="hero-meta-value"><?php echo htmlspecialchars($project['location']); ?></span>
                </div>
            <?php endif; ?>

            <div class="hero-meta-item">
                <span class="hero-meta-label">Started</span>
                <span class="hero-meta-value"><?php echo date('F Y', strtotime($project['start_date'])); ?></span>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="project-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li><a href="<?php echo url('live-projects.php'); ?>">Live Projects</a></li>
        <li><?php echo htmlspecialchars($project['title']); ?></li>
    </ul>
</nav>

<!-- Main Content -->
<div class="project-layout">
    <main class="project-content">
        <?php if ($project['content']): ?>
            <?php echo $project['content']; ?>
        <?php else: ?>
            <p><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
            <p><em>Detailed project update coming soon...</em></p>
        <?php endif; ?>
    </main>

    <aside class="project-sidebar">
        <!-- Project Image -->
        <?php if ($project['image_path']): ?>
            <img src="<?php echo url($project['image_path']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>"
                class="sidebar-project-image">
        <?php endif; ?>

        <!-- Status Widget -->
        <div class="sidebar-widget status-widget">
            <h3>Project Status</h3>

            <div class="progress-wrapper">
                <div class="progress-label">
                    <span>Progress</span>
                    <span><?php echo (int) $project['progress_percentage']; ?>%</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" style="width: <?php echo (int) $project['progress_percentage']; ?>%">
                    </div>
                </div>
            </div>

            <div class="phase-list">
                <?php
                $phases = ['Discovery', 'Value Stream Mapping', 'Layout Design', 'Detailed Design', 'Implementation', 'Review & Handover'];
                $currentPhase = $project['current_phase'] ?? 'Discovery';
                $reachedCurrent = false;

                foreach ($phases as $phase):
                    $isActive = ($phase === $currentPhase);
                    if ($isActive)
                        $reachedCurrent = true;
                    ?>
                    <div class="phase-item <?php echo $isActive ? 'active' : ''; ?>">
                        <div class="phase-dot"
                            style="<?php echo $reachedCurrent && !$isActive ? 'background: #CBD5E1;' : ''; ?>"></div>
                        <?php echo $phase; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Highlights Widget -->
        <?php if ($project['highlight_1'] || $project['highlight_2'] || $project['highlight_3']): ?>
            <div class="sidebar-widget">
                <h3>Key Highlights</h3>
                <ul class="highlights-list">
                    <?php if ($project['highlight_1']): ?>
                        <li class="highlight-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <?php echo htmlspecialchars($project['highlight_1']); ?>
                        </li>
                    <?php endif; ?>

                    <?php if ($project['highlight_2']): ?>
                        <li class="highlight-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <?php echo htmlspecialchars($project['highlight_2']); ?>
                        </li>
                    <?php endif; ?>

                    <?php if ($project['highlight_3']): ?>
                        <li class="highlight-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <?php echo htmlspecialchars($project['highlight_3']); ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- CTA Widget -->
        <div class="sidebar-widget cta-widget">
            <h3>Start Your Transformation</h3>
            <p>Ready to achieve similar results? Schedule your Pulse Check today.</p>
            <a href="<?php echo url('pulse-check.php'); ?>" class="btn-cta">Request Pulse Check</a>
        </div>
    </aside>
</div>

<!-- Related Projects -->
<?php if ($relatedProjects->num_rows > 0): ?>
    <section class="related-projects">
        <div class="related-container">
            <h2>Other Live Projects</h2>
            <div class="related-grid">
                <?php while ($related = $relatedProjects->fetch_assoc()): ?>
                    <a href="<?php echo url('live-project-details.php?slug=' . $related['slug']); ?>" class="related-card">
                        <div class="related-image">
                            <?php if ($related['image_path']): ?>
                                <img src="<?php echo url($related['image_path']); ?>"
                                    alt="<?php echo htmlspecialchars($related['title']); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="related-content">
                            <span class="related-type"><?php echo htmlspecialchars($related['project_type']); ?></span>
                            <h3 class="related-title"><?php echo htmlspecialchars($related['title']); ?></h3>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>