<?php
/**
 * Admin Dashboard
 */

$pageTitle = "Dashboard";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

// Prevent editors from accessing admin panel
if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'editor') {
    logoutAdmin();
    header('Location: ../blogger/login.php?msg=admin_redirect');
    exit;
}

require_once __DIR__ . '/../database/db_config.php';
require_once __DIR__ . '/../includes/config.php';

// Handle quick maintenance toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quick_maintenance_toggle'])) {
    if (verifyCSRFToken($_POST['csrf_token'] ?? '') && hasAdminRole('admin')) {
        $currentMode = isMaintenanceMode();
        updateSiteSetting('maintenance_mode', !$currentMode, $_SESSION['admin_id']);
        logAdminActivity(
            $_SESSION['admin_id'], 
            !$currentMode ? 'maintenance_enabled' : 'maintenance_disabled', 
            'site_settings', 
            null, 
            !$currentMode ? 'Maintenance mode enabled from dashboard' : 'Maintenance mode disabled from dashboard'
        );
        header('Location: dashboard.php');
        exit;
    }
}

// Check maintenance status
$isMaintenanceActive = isMaintenanceMode();

// Get statistics
$conn = getDBConnection();

// Total downloads
$result = $conn->query("SELECT COUNT(*) as total FROM downloads WHERE is_active = 1");
$totalDownloads = $result->fetch_assoc()['total'];

// Total categories
$result = $conn->query("SELECT COUNT(*) as total FROM download_categories WHERE is_active = 1");
$totalCategories = $result->fetch_assoc()['total'];

// Total download counts
$result = $conn->query("SELECT SUM(download_count) as total FROM downloads");
$totalDownloadCount = $result->fetch_assoc()['total'] ?? 0;

// Recent downloads
$recentDownloads = $conn->query("
    SELECT d.*, c.name as category_name 
    FROM downloads d 
    LEFT JOIN download_categories c ON d.category_id = c.id 
    ORDER BY d.created_at DESC 
    LIMIT 5
");

// Recent activity
$recentActivity = $conn->query("
    SELECT a.*, u.full_name 
    FROM admin_activity_log a 
    LEFT JOIN admin_users u ON a.user_id = u.id 
    ORDER BY a.created_at DESC 
    LIMIT 10
");

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?>!</p>
    </div>
    <div class="page-actions">
        <a href="download-add.php" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add New Download
        </a>
    </div>
</div>

<?php if ($isMaintenanceActive && hasAdminRole('admin')): ?>
<!-- Maintenance Mode Alert -->
<div class="maintenance-alert" style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); border: 1px solid #F59E0B; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 40px; height: 40px; background: #F59E0B; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" style="width: 22px; height: 22px;">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
        </div>
        <div>
            <strong style="color: #92400E; font-size: 1rem;">Maintenance Mode Active</strong>
            <p style="color: #A16207; font-size: 0.875rem; margin: 0.25rem 0 0;">Public pages are restricted. Admin, blogger panels and blog pages remain accessible.</p>
        </div>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: center;">
        <a href="settings.php" style="color: #92400E; font-size: 0.875rem; text-decoration: underline;">Manage Settings</a>
        <form method="post" style="margin: 0;" onsubmit="return confirm('Are you sure you want to disable maintenance mode?');">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
            <input type="hidden" name="quick_maintenance_toggle" value="1">
            <button type="submit" style="background: #10B981; color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <path d="M18.36 6.64a9 9 0 1 1-12.73 0"/>
                    <line x1="12" y1="2" x2="12" y2="12"/>
                </svg>
                Disable
            </button>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon orange">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo $totalDownloads; ?></div>
            <div class="stat-label">Total Resources</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo $totalCategories; ?></div>
            <div class="stat-label">Categories</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo number_format($totalDownloadCount); ?></div>
            <div class="stat-label">Total Downloads</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value">1</div>
            <div class="stat-label">Admin Users</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Recent Downloads -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Downloads</h3>
            <a href="downloads.php" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Resource</th>
                        <th>Category</th>
                        <th>Downloads</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recentDownloads->num_rows > 0): ?>
                        <?php while ($download = $recentDownloads->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="table-item">
                                        <div class="table-item-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                <polyline points="14 2 14 8 20 8"/>
                                            </svg>
                                        </div>
                                        <div class="table-item-info">
                                            <h4><?php echo htmlspecialchars($download['title']); ?></h4>
                                            <p><?php echo htmlspecialchars($download['file_type']); ?> • <?php echo htmlspecialchars($download['file_size']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info"><?php echo htmlspecialchars($download['category_name']); ?></span>
                                </td>
                                <td><?php echo number_format($download['download_count']); ?></td>
                                <td>
                                    <?php if ($download['is_active']): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-gray">Inactive</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">
                                <div class="empty-state" style="padding: 2rem;">
                                    <p>No downloads found.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Activity</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="activity-list">
                <?php if ($recentActivity->num_rows > 0): ?>
                    <?php while ($activity = $recentActivity->fetch_assoc()): ?>
                        <div class="activity-item" style="display: flex; gap: 0.75rem; padding: 1rem 1.5rem; border-bottom: 1px solid var(--admin-gray-200);">
                            <div class="activity-icon" style="width: 32px; height: 32px; background: var(--admin-gray-100); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; color: var(--admin-gray-500);">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                                </svg>
                            </div>
                            <div class="activity-content">
                                <p style="font-size: 0.875rem; color: var(--admin-gray-700); margin-bottom: 0.25rem;">
                                    <strong><?php echo htmlspecialchars($activity['full_name'] ?? 'System'); ?></strong>
                                    <?php echo htmlspecialchars($activity['action']); ?>
                                </p>
                                <span style="font-size: 0.75rem; color: var(--admin-gray-500);">
                                    <?php echo date('M j, Y g:i A', strtotime($activity['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state" style="padding: 2rem;">
                        <p>No recent activity.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
