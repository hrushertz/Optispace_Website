<?php
/**
 * Admin Live Projects
 */

$pageTitle = "Live Projects";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];

    // Get the project to delete its image
    $stmt = $conn->prepare("SELECT image_path, thumbnail_path FROM live_projects WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $project = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($project) {
        // Delete images if they exist
        if ($project['image_path'] && file_exists(__DIR__ . '/../' . $project['image_path'])) {
            unlink(__DIR__ . '/../' . $project['image_path']);
        }
        if ($project['thumbnail_path'] && file_exists(__DIR__ . '/../' . $project['thumbnail_path'])) {
            unlink(__DIR__ . '/../' . $project['thumbnail_path']);
        }
    }

    $stmt = $conn->prepare("DELETE FROM live_projects WHERE id = ?");
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'delete', 'live_projects', $deleteId, 'Deleted live project');
        $successMessage = "Live project deleted successfully.";
    } else {
        $errorMessage = "Failed to delete live project.";
    }
    $stmt->close();
}

// Handle toggle status
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $toggleId = (int) $_GET['toggle'];
    $stmt = $conn->prepare("UPDATE live_projects SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $toggleId);

    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'toggle_status', 'live_projects', $toggleId, 'Toggled live project status');
        $successMessage = "Live project status updated.";
    }
    $stmt->close();
}

// Handle featured toggle
if (isset($_GET['featured']) && is_numeric($_GET['featured'])) {
    $featuredId = (int) $_GET['featured'];
    $stmt = $conn->prepare("UPDATE live_projects SET is_featured = NOT is_featured WHERE id = ?");
    $stmt->bind_param("i", $featuredId);

    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'toggle_featured', 'live_projects', $featuredId, 'Toggled live project featured status');
        $successMessage = "Featured status updated.";
    }
    $stmt->close();
}

// Get all live projects
$projects = $conn->query("
    SELECT * FROM live_projects 
    ORDER BY is_featured DESC, sort_order ASC, created_at DESC
");

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Live Projects</h1>
        <p class="page-subtitle">Manage ongoing projects displayed on the website</p>
    </div>
    <div class="page-actions">
        <a href="live-project-add.php" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Add Live Project
        </a>
    </div>
</div>

<?php if (isset($successMessage)): ?>
    <div class="alert alert-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        <?php echo htmlspecialchars($successMessage); ?>
    </div>
<?php endif; ?>

<?php if (isset($errorMessage)): ?>
    <div class="alert alert-danger">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="15" y1="9" x2="9" y2="15" />
            <line x1="9" y1="9" x2="15" y2="15" />
        </svg>
        <?php echo htmlspecialchars($errorMessage); ?>
    </div>
<?php endif; ?>

<div class="content-card">
    <?php if ($projects && $projects->num_rows > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Image</th>
                        <th>Project</th>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Progress</th>
                        <th>Phase</th>
                        <th style="width: 100px;">Featured</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($project = $projects->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="table-image"
                                    style="width: 50px; height: 50px; border-radius: 6px; overflow: hidden; background: #f1f5f9;">
                                    <?php if ($project['image_path'] || $project['thumbnail_path']): ?>
                                        <img src="../<?php echo htmlspecialchars($project['thumbnail_path'] ?: $project['image_path']); ?>"
                                            alt="<?php echo htmlspecialchars($project['title']); ?>"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <div
                                            style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#94a3b8"
                                                stroke-width="2">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                                <circle cx="8.5" cy="8.5" r="1.5" />
                                                <polyline points="21 15 16 10 5 21" />
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="table-primary"><?php echo htmlspecialchars($project['title']); ?></div>
                                <div class="table-secondary"><?php echo htmlspecialchars($project['location'] ?? ''); ?></div>
                            </td>
                            <td>
                                <span
                                    class="table-text"><?php echo htmlspecialchars($project['client_name'] ?? 'N/A'); ?></span>
                            </td>
                            <td>
                                <?php if ($project['project_type']): ?>
                                    <span class="badge badge-info"><?php echo htmlspecialchars($project['project_type']); ?></span>
                                <?php else: ?>
                                    <span class="table-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="progress-wrapper" style="width: 100px;">
                                    <div class="progress-bar"
                                        style="height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden;">
                                        <div
                                            style="width: <?php echo (int) $project['progress_percentage']; ?>%; height: 100%; background: linear-gradient(90deg, #E99431 0%, #f5a854 100%); border-radius: 3px;">
                                        </div>
                                    </div>
                                    <span class="table-secondary"
                                        style="font-size: 0.75rem; margin-top: 2px; display: block;"><?php echo (int) $project['progress_percentage']; ?>%</span>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="table-text"><?php echo htmlspecialchars($project['current_phase'] ?? '-'); ?></span>
                            </td>
                            <td>
                                <a href="?featured=<?php echo $project['id']; ?>"
                                    class="status-toggle <?php echo $project['is_featured'] ? 'active' : ''; ?>"
                                    title="Toggle Featured">
                                    <svg viewBox="0 0 24 24"
                                        fill="<?php echo $project['is_featured'] ? 'currentColor' : 'none'; ?>"
                                        stroke="currentColor" stroke-width="2"
                                        style="width: 18px; height: 18px; color: <?php echo $project['is_featured'] ? '#E99431' : '#94a3b8'; ?>;">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                </a>
                            </td>
                            <td>
                                <a href="?toggle=<?php echo $project['id']; ?>"
                                    class="status-badge <?php echo $project['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo $project['is_active'] ? 'Active' : 'Inactive'; ?>
                                </a>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="live-project-edit.php?id=<?php echo $project['id']; ?>" class="btn-icon"
                                        title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </a>
                                    <a href="?delete=<?php echo $project['id']; ?>" class="btn-icon btn-icon-danger"
                                        title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this project?');">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon" style="width: 64px; height: 64px; margin: 0 auto;">
                <svg width="64" height="64" style="width: 64px !important; height: 64px !important;" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                </svg>
            </div>
            <h3>No Live Projects</h3>
            <p>Get started by adding your first live project.</p>
            <a href="live-project-add.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Add Live Project
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>