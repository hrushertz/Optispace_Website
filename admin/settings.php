<?php
/**
 * Site Settings Management
 * Admin and Super Admin only
 */

$pageTitle = "Site Settings";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();
requireAdminRole('admin');

require_once __DIR__ . '/../database/db_config.php';
require_once __DIR__ . '/../includes/config.php';

$conn = getDBConnection();
$admin = getCurrentAdmin();
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'toggle_maintenance') {
            $currentMode = isMaintenanceMode();
            $newMode = !$currentMode;
            
            if (updateSiteSetting('maintenance_mode', $newMode, $admin['id'])) {
                // Log the activity
                logAdminActivity(
                    $admin['id'], 
                    $newMode ? 'maintenance_enabled' : 'maintenance_disabled', 
                    'site_settings', 
                    null, 
                    $newMode ? 'Maintenance mode enabled' : 'Maintenance mode disabled'
                );
                
                $success = $newMode ? 'Maintenance mode has been enabled. The public site is now restricted.' : 'Maintenance mode has been disabled. The site is now accessible.';
                
                // Force refresh of cached settings
                header('Location: settings.php?success=' . urlencode($success));
                exit;
            } else {
                $error = 'Failed to update maintenance mode. Please try again.';
            }
        }
        
        if ($action === 'update_maintenance_settings') {
            $message = trim($_POST['maintenance_message'] ?? '');
            $endTime = trim($_POST['maintenance_end_time'] ?? '');
            
            $updated = true;
            
            if (!empty($message)) {
                $updated = $updated && updateSiteSetting('maintenance_message', $message, $admin['id']);
            }
            
            $updated = $updated && updateSiteSetting('maintenance_end_time', $endTime ?: null, $admin['id']);
            
            if ($updated) {
                logAdminActivity($admin['id'], 'maintenance_settings_updated', 'site_settings', null, 'Updated maintenance message and end time');
                $success = 'Maintenance settings have been updated.';
                header('Location: settings.php?success=' . urlencode($success));
                exit;
            } else {
                $error = 'Failed to update settings. Please try again.';
            }
        }
    }
}

// Get success message from redirect
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}

// Get current settings (fresh from DB)
$maintenanceMode = false;
$maintenanceMessage = '';
$maintenanceEndTime = '';

$result = $conn->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ('maintenance_mode', 'maintenance_message', 'maintenance_end_time')");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        switch ($row['setting_key']) {
            case 'maintenance_mode':
                $maintenanceMode = (bool) intval($row['setting_value']);
                break;
            case 'maintenance_message':
                $maintenanceMessage = $row['setting_value'];
                break;
            case 'maintenance_end_time':
                $maintenanceEndTime = $row['setting_value'];
                break;
        }
    }
}

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<style>
.settings-section {
    background: #fff;
    border-radius: 12px;
    border: 1px solid var(--admin-gray-200);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.settings-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
    border-bottom: 1px solid var(--admin-gray-200);
    background: var(--admin-gray-50);
}

.settings-header h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--admin-gray-900);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.settings-header h3 svg {
    width: 24px;
    height: 24px;
    color: var(--admin-gray-500);
}

.settings-body {
    padding: 1.5rem;
}

.maintenance-status {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.maintenance-status.active {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.maintenance-status.inactive {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
}

.maintenance-status.active .status-indicator {
    background: #EF4444;
    animation: pulse-red 2s infinite;
}

.maintenance-status.inactive .status-indicator {
    background: #10B981;
}

@keyframes pulse-red {
    0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
    50% { opacity: 0.8; box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
}

.status-text {
    flex: 1;
}

.status-text strong {
    display: block;
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.maintenance-status.active .status-text strong {
    color: #DC2626;
}

.maintenance-status.inactive .status-text strong {
    color: #059669;
}

.status-text span {
    font-size: 0.875rem;
    color: var(--admin-gray-600);
}

.toggle-form {
    display: inline-block;
}

.maintenance-toggle-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 0.9375rem;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
}

.maintenance-toggle-btn.enable {
    background: #EF4444;
    color: #fff;
}

.maintenance-toggle-btn.enable:hover {
    background: #DC2626;
}

.maintenance-toggle-btn.disable {
    background: #10B981;
    color: #fff;
}

.maintenance-toggle-btn.disable:hover {
    background: #059669;
}

.maintenance-toggle-btn svg {
    width: 18px;
    height: 18px;
}

.settings-form-group {
    margin-bottom: 1.5rem;
}

.settings-form-group:last-child {
    margin-bottom: 0;
}

.settings-form-group label {
    display: block;
    font-size: 0.9375rem;
    font-weight: 500;
    color: var(--admin-gray-700);
    margin-bottom: 0.5rem;
}

.settings-form-group label small {
    font-weight: 400;
    color: var(--admin-gray-500);
}

.settings-form-group input,
.settings-form-group textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--admin-gray-300);
    border-radius: 8px;
    font-size: 0.9375rem;
    transition: all 0.2s;
}

.settings-form-group input:focus,
.settings-form-group textarea:focus {
    outline: none;
    border-color: var(--admin-primary);
    box-shadow: 0 0 0 3px rgba(233, 148, 49, 0.1);
}

.settings-form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.info-box {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 8px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}

.info-box h4 {
    font-size: 0.9375rem;
    font-weight: 600;
    color: #1D4ED8;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-box h4 svg {
    width: 18px;
    height: 18px;
}

.info-box ul {
    margin: 0;
    padding-left: 1.5rem;
    color: var(--admin-gray-700);
    font-size: 0.875rem;
    line-height: 1.7;
}

.preview-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
    font-size: 0.875rem;
    color: var(--admin-primary);
    text-decoration: none;
}

.preview-link:hover {
    text-decoration: underline;
}

.preview-link svg {
    width: 16px;
    height: 16px;
}
</style>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Site Settings</h1>
        <p class="page-subtitle">Manage website configuration and maintenance mode</p>
    </div>
</div>

<?php if ($success): ?>
<div class="alert alert-success" style="margin-bottom: 1.5rem; padding: 1rem 1.25rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 8px; color: #059669;">
    <strong>Success!</strong> <?php echo htmlspecialchars($success); ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-error" style="margin-bottom: 1.5rem; padding: 1rem 1.25rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; color: #DC2626;">
    <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>

<!-- Maintenance Mode Section -->
<div class="settings-section">
    <div class="settings-header">
        <h3>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
            Maintenance Mode
        </h3>
    </div>
    <div class="settings-body">
        <div class="maintenance-status <?php echo $maintenanceMode ? 'active' : 'inactive'; ?>">
            <div class="status-indicator"></div>
            <div class="status-text">
                <strong><?php echo $maintenanceMode ? 'Maintenance Mode is Active' : 'Site is Online'; ?></strong>
                <span><?php echo $maintenanceMode ? 'Public pages are currently restricted. Admin, blogger dashboards, and blog pages remain accessible.' : 'All pages are accessible to visitors.'; ?></span>
            </div>
            <form method="post" class="toggle-form" onsubmit="return confirm('<?php echo $maintenanceMode ? 'Are you sure you want to disable maintenance mode? The site will become publicly accessible.' : 'Are you sure you want to enable maintenance mode? Public pages will be restricted.'; ?>');">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                <input type="hidden" name="action" value="toggle_maintenance">
                <button type="submit" class="maintenance-toggle-btn <?php echo $maintenanceMode ? 'disable' : 'enable'; ?>">
                    <?php if ($maintenanceMode): ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18.36 6.64a9 9 0 1 1-12.73 0"/>
                            <line x1="12" y1="2" x2="12" y2="12"/>
                        </svg>
                        Disable Maintenance
                    <?php else: ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        Enable Maintenance
                    <?php endif; ?>
                </button>
            </form>
        </div>
        
        <div class="info-box">
            <h4>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                What remains accessible during maintenance?
            </h4>
            <ul>
                <li><strong>Admin Dashboard</strong> - Full access for admins and super admins</li>
                <li><strong>Blogger Dashboard</strong> - Full access for editors/bloggers</li>
                <li><strong>Blog Pages</strong> - Public blog listing and individual articles</li>
            </ul>
        </div>
        
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
            <input type="hidden" name="action" value="update_maintenance_settings">
            
            <div class="settings-form-group">
                <label for="maintenance_message">
                    Maintenance Message
                    <small>(Displayed to visitors)</small>
                </label>
                <textarea name="maintenance_message" id="maintenance_message" placeholder="We are currently performing scheduled maintenance. Please check back soon."><?php echo htmlspecialchars($maintenanceMessage); ?></textarea>
            </div>
            
            <div class="settings-form-group">
                <label for="maintenance_end_time">
                    Estimated End Time
                    <small>(Optional - e.g., "January 5, 2026 at 10:00 AM EST")</small>
                </label>
                <input type="text" name="maintenance_end_time" id="maintenance_end_time" placeholder="e.g., January 5, 2026 at 10:00 AM" value="<?php echo htmlspecialchars($maintenanceEndTime ?? ''); ?>">
            </div>
            
            <button type="submit" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/>
                    <polyline points="7 3 7 8 15 8"/>
                </svg>
                Save Settings
            </button>
            
            <a href="../maintenance.php" target="_blank" class="preview-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                    <polyline points="15 3 21 3 21 9"/>
                    <line x1="10" y1="14" x2="21" y2="3"/>
                </svg>
                Preview Maintenance Page
            </a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
