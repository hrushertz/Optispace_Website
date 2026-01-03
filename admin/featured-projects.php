<?php
/**
 * Admin Featured Projects
 */

$pageTitle = "Featured Projects";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM featured_projects WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'delete', 'featured_projects', $deleteId, 'Deleted featured project');
        $successMessage = "Featured project deleted successfully.";
    } else {
        $errorMessage = "Failed to delete featured project.";
    }
    $stmt->close();
}

// Handle toggle status
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $toggleId = (int)$_GET['toggle'];
    $stmt = $conn->prepare("UPDATE featured_projects SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $toggleId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'toggle_status', 'featured_projects', $toggleId, 'Toggled featured project status');
        $successMessage = "Featured project status updated.";
    }
    $stmt->close();
}

// Handle primary toggle
if (isset($_GET['primary']) && is_numeric($_GET['primary'])) {
    $primaryId = (int)$_GET['primary'];
    // First remove primary from all
    $conn->query("UPDATE featured_projects SET is_primary = 0");
    // Set new primary
    $stmt = $conn->prepare("UPDATE featured_projects SET is_primary = 1 WHERE id = ?");
    $stmt->bind_param("i", $primaryId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'set_primary', 'featured_projects', $primaryId, 'Set as primary featured project');
        $successMessage = "Primary featured project updated.";
    }
    $stmt->close();
}

// Get all featured projects
$projects = $conn->query("
    SELECT fp.*, gi.title as gallery_title 
    FROM featured_projects fp 
    LEFT JOIN gallery_items gi ON fp.gallery_item_id = gi.id 
    ORDER BY fp.is_primary DESC, fp.sort_order ASC
");

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Featured Projects</h1>
        <p class="page-subtitle">Manage featured transformation projects displayed on the gallery page</p>
    </div>
    <div class="page-actions">
        <a href="featured-project-add.php" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add Featured Project
        </a>
    </div>
</div>

<?php if (isset($successMessage)): ?>
<div class="alert alert-success">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
        <polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <?php echo htmlspecialchars($successMessage); ?>
</div>
<?php endif; ?>

<?php if (isset($errorMessage)): ?>
<div class="alert alert-danger">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <line x1="15" y1="9" x2="9" y2="15"/>
        <line x1="9" y1="9" x2="15" y2="15"/>
    </svg>
    <?php echo htmlspecialchars($errorMessage); ?>
</div>
<?php endif; ?>

<div class="featured-projects-grid">
    <?php if ($projects->num_rows > 0): ?>
        <?php while ($project = $projects->fetch_assoc()): ?>
        <div class="featured-project-card <?php echo $project['is_active'] ? '' : 'inactive'; ?> <?php echo $project['is_primary'] ? 'primary' : ''; ?>">
            <div class="project-image">
                <?php if ($project['image_path'] && file_exists(__DIR__ . '/../' . $project['image_path'])): ?>
                    <img src="../<?php echo htmlspecialchars($project['image_path']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                <?php else: ?>
                    <div class="placeholder-icon greenfield-bg">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                <?php endif; ?>
                
                <?php if ($project['is_primary']): ?>
                <span class="primary-badge">
                    <svg viewBox="0 0 24 24" fill="currentColor" stroke="none" width="12" height="12">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    Primary
                </span>
                <?php endif; ?>
                
                <div class="card-actions-overlay">
                    <a href="featured-project-edit.php?id=<?php echo $project['id']; ?>" class="action-btn" title="Edit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </a>
                    <?php if (!$project['is_primary']): ?>
                    <a href="?primary=<?php echo $project['id']; ?>" class="action-btn" title="Set as Primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <a href="?toggle=<?php echo $project['id']; ?>" class="action-btn" title="<?php echo $project['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <?php if ($project['is_active']): ?>
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                            <?php else: ?>
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                            <?php endif; ?>
                        </svg>
                    </a>
                    <a href="?delete=<?php echo $project['id']; ?>" class="action-btn danger" title="Delete" onclick="return confirm('Are you sure you want to delete this featured project?');">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="project-content">
                <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                <?php if ($project['location']): ?>
                <div class="project-location">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <?php echo htmlspecialchars($project['location']); ?>
                </div>
                <?php endif; ?>
                <p><?php echo htmlspecialchars(substr($project['description'], 0, 120)); ?><?php echo strlen($project['description']) > 120 ? '...' : ''; ?></p>
                
                <div class="project-stats">
                    <?php if ($project['stat_1_value']): ?>
                    <div class="stat">
                        <span class="stat-value"><?php echo htmlspecialchars($project['stat_1_value']); ?></span>
                        <span class="stat-label"><?php echo htmlspecialchars($project['stat_1_label']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($project['stat_2_value']): ?>
                    <div class="stat">
                        <span class="stat-value"><?php echo htmlspecialchars($project['stat_2_value']); ?></span>
                        <span class="stat-label"><?php echo htmlspecialchars($project['stat_2_label']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($project['stat_3_value']): ?>
                    <div class="stat">
                        <span class="stat-value"><?php echo htmlspecialchars($project['stat_3_value']); ?></span>
                        <span class="stat-label"><?php echo htmlspecialchars($project['stat_3_label']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!$project['is_active']): ?>
            <div class="inactive-overlay">
                <span>Inactive</span>
            </div>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
            </div>
            <h3>No featured projects yet</h3>
            <p>Add your first featured transformation project to highlight on the gallery page</p>
            <a href="featured-project-add.php" class="btn btn-primary">Add Featured Project</a>
        </div>
    <?php endif; ?>
</div>

<style>
/* Featured Projects Grid */
.featured-projects-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.featured-project-card {
    background: var(--admin-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--admin-gray-200);
    transition: all var(--transition-normal);
    position: relative;
}

.featured-project-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.featured-project-card.inactive {
    opacity: 0.7;
}

.featured-project-card.primary {
    border-color: var(--admin-primary);
    box-shadow: 0 0 0 2px var(--admin-primary-light);
}

.project-image {
    position: relative;
    height: 180px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #FEF3E2 0%, #FDE68A 100%);
}

.project-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-icon {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.placeholder-icon svg {
    width: 48px;
    height: 48px;
    color: var(--admin-primary);
    opacity: 0.5;
}

.primary-badge {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: var(--admin-primary);
    color: white;
    padding: 0.35rem 0.65rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
}

.card-actions-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.75);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity var(--transition-normal);
}

.featured-project-card:hover .card-actions-overlay {
    opacity: 1;
}

.action-btn {
    width: 40px;
    height: 40px;
    background: var(--admin-white);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--admin-gray-700);
    transition: all var(--transition-fast);
}

.action-btn:hover {
    background: var(--admin-primary);
    color: white;
}

.action-btn.danger:hover {
    background: var(--admin-danger);
}

.action-btn svg {
    width: 18px;
    height: 18px;
}

.project-content {
    padding: 1.25rem;
}

.project-content h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--admin-dark);
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.project-location {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.85rem;
    color: var(--admin-gray-500);
    margin-bottom: 0.75rem;
}

.project-location svg {
    width: 14px;
    height: 14px;
}

.project-content p {
    font-size: 0.85rem;
    color: var(--admin-gray-600);
    line-height: 1.5;
    margin-bottom: 1rem;
}

.project-stats {
    display: flex;
    gap: 1.25rem;
    padding-top: 1rem;
    border-top: 1px solid var(--admin-gray-100);
}

.stat {
    text-align: left;
}

.stat-value {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--admin-primary);
    line-height: 1;
}

.stat-label {
    font-size: 0.7rem;
    color: var(--admin-gray-500);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.inactive-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.inactive-overlay span {
    background: var(--admin-gray-700);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.85rem;
    font-weight: 600;
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    background: var(--admin-white);
    border-radius: var(--radius-lg);
    border: 2px dashed var(--admin-gray-200);
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: var(--admin-primary-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon svg {
    width: 40px;
    height: 40px;
    color: var(--admin-primary);
}

.empty-state h3 {
    font-size: 1.25rem;
    color: var(--admin-dark);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--admin-gray-500);
    margin-bottom: 1.5rem;
}

/* Responsive */
@media (max-width: 1200px) {
    .featured-projects-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .featured-projects-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
