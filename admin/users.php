<?php
/**
 * Admin Users Management
 * Manage admin users and their roles
 */

$pageTitle = "Users";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();
requireAdminRole('admin'); // Only admin and super_admin can access

// db_config.php is already included in auth.php

$conn = getDBConnection();

$errors = [];
$successMessage = '';
$currentAdmin = getCurrentAdmin();

// Role hierarchy for permission checks
$roleHierarchy = [
    'super_admin' => 3,
    'admin' => 2,
    'editor' => 1
];
$currentUserLevel = $roleHierarchy[$currentAdmin['role']] ?? 0;

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];

    // Can't delete yourself
    if ($deleteId === (int) $currentAdmin['id']) {
        $errors['general'] = "You cannot delete your own account.";
    } else {
        // Check target user's role level
        $checkStmt = $conn->prepare("SELECT role, username FROM admin_users WHERE id = ?");
        $checkStmt->bind_param("i", $deleteId);
        $checkStmt->execute();
        $targetUser = $checkStmt->get_result()->fetch_assoc();
        $checkStmt->close();

        if ($targetUser) {
            $targetLevel = $roleHierarchy[$targetUser['role']] ?? 0;

            // Can only delete users with lower or equal role (super_admin can delete anyone, admin can delete admin/editor)
            if ($currentUserLevel > $targetLevel || ($currentAdmin['role'] === 'super_admin')) {
                // START FIX: Reassign blogs authored by this user to the current admin
                // This prevents deletion failure due to FOREIGN KEY (author_id) REFERENCES admin_users(id) ON DELETE RESTRICT
                $reassignStmt = $conn->prepare("UPDATE blogs SET author_id = ? WHERE author_id = ?");
                $reassignStmt->bind_param("ii", $currentAdmin['id'], $deleteId);
                $reassignStmt->execute();
                $reassignStmt->close();
                // END FIX

                $stmt = $conn->prepare("DELETE FROM admin_users WHERE id = ?");
                $stmt->bind_param("i", $deleteId);

                if ($stmt->execute()) {
                    logAdminActivity($_SESSION['admin_id'], 'delete', 'admin_users', $deleteId, 'Deleted user: ' . $targetUser['username']);
                    $successMessage = "User deleted successfully.";
                } else {
                    $errors['general'] = "Failed to delete user. The user might have associated content that cannot be reassigned automatically.";
                }
                $stmt->close();
            } else {
                $errors['general'] = "You don't have permission to delete this user.";
            }
        } else {
            $errors['general'] = "User not found.";
        }
    }
}

// Handle toggle status
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $toggleId = (int) $_GET['toggle'];

    // Can't toggle yourself
    if ($toggleId === (int) $currentAdmin['id']) {
        $errors['general'] = "You cannot deactivate your own account.";
    } else {
        // Check target user's role level
        $checkStmt = $conn->prepare("SELECT role, username, is_active FROM admin_users WHERE id = ?");
        $checkStmt->bind_param("i", $toggleId);
        $checkStmt->execute();
        $targetUser = $checkStmt->get_result()->fetch_assoc();
        $checkStmt->close();

        if ($targetUser) {
            $targetLevel = $roleHierarchy[$targetUser['role']] ?? 0;

            if ($currentUserLevel > $targetLevel || ($currentAdmin['role'] === 'super_admin')) {
                $stmt = $conn->prepare("UPDATE admin_users SET is_active = NOT is_active WHERE id = ?");
                $stmt->bind_param("i", $toggleId);

                if ($stmt->execute()) {
                    $action = $targetUser['is_active'] ? 'deactivated' : 'activated';
                    logAdminActivity($_SESSION['admin_id'], 'toggle_status', 'admin_users', $toggleId, ucfirst($action) . ' user: ' . $targetUser['username']);
                    $successMessage = "User " . $action . " successfully.";
                }
                $stmt->close();
            } else {
                $errors['general'] = "You don't have permission to modify this user.";
            }
        }
    }
}

// Get all users
$users = $conn->query("
    SELECT id, username, email, full_name, role, avatar, is_active, last_login, created_at
    FROM admin_users 
    ORDER BY 
        CASE role 
            WHEN 'super_admin' THEN 1 
            WHEN 'admin' THEN 2 
            WHEN 'editor' THEN 3 
        END,
        full_name
");

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Users</h1>
        <p class="page-subtitle">Manage admin users and their roles</p>
    </div>
    <div class="page-actions">
        <a href="user-add.php" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Add User
        </a>
    </div>
</div>

<?php if ($successMessage): ?>
    <div class="alert alert-success" data-auto-hide>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        <span><?php echo htmlspecialchars($successMessage); ?></span>
    </div>
<?php endif; ?>

<?php if (isset($errors['general'])): ?>
    <div class="alert alert-danger">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
        </svg>
        <span><?php echo htmlspecialchars($errors['general']); ?></span>
    </div>
<?php endif; ?>

<!-- Users List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Users</h3>
        <span class="badge badge-gray"><?php echo $users->num_rows; ?> users</span>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users->num_rows > 0): ?>
                    <?php while ($user = $users->fetch_assoc()):
                        $userLevel = $roleHierarchy[$user['role']] ?? 0;
                        $canModify = ($currentUserLevel > $userLevel) || ($currentAdmin['role'] === 'super_admin');
                        $isSelf = ((int) $user['id'] === (int) $currentAdmin['id']);
                        ?>
                        <tr>
                            <td>
                                <div class="table-item">
                                    <div class="user-avatar"
                                        style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #E99431, #d4841f); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1rem;">
                                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                    </div>
                                    <div class="table-item-info">
                                        <h4>
                                            <?php echo htmlspecialchars($user['full_name']); ?>
                                            <?php if ($isSelf): ?>
                                                <span class="badge badge-primary"
                                                    style="margin-left: 0.5rem; font-size: 0.65rem;">You</span>
                                            <?php endif; ?>
                                        </h4>
                                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                // Determine badge class based on role (PHP 7.4 compatible)
                                $roleBadgeClasses = [
                                    'super_admin' => 'badge-danger',
                                    'admin' => 'badge-primary',
                                    'editor' => 'badge-gray',
                                    'sales' => 'badge-gray'
                                ];
                                $roleBadgeClass = $roleBadgeClasses[$user['role']] ?? 'badge-gray';
                                $roleLabel = ucwords(str_replace('_', ' ', $user['role']));
                                ?>
                                <span class="badge <?php echo $roleBadgeClass; ?>"><?php echo $roleLabel; ?></span>
                            </td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-gray">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user['last_login']): ?>
                                    <span title="<?php echo date('F j, Y g:i A', strtotime($user['last_login'])); ?>">
                                        <?php echo date('M j, Y', strtotime($user['last_login'])); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--text-muted);">Never</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <?php if ($canModify || $isSelf): ?>
                                        <a href="user-edit.php?id=<?php echo $user['id']; ?>"
                                            class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($canModify && !$isSelf): ?>
                                        <a href="?toggle=<?php echo $user['id']; ?>" class="btn btn-secondary btn-sm btn-icon"
                                            title="<?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                            <?php if ($user['is_active']): ?>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            <?php else: ?>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path
                                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                                                    <line x1="1" y1="1" x2="23" y2="23" />
                                                </svg>
                                            <?php endif; ?>
                                        </a>

                                        <a href="?delete=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm btn-icon"
                                            title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state" style="padding: 2rem;">
                                <p>No users found.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Role Legend -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Role Permissions</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div>
                <h4 style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <span class="badge badge-danger">Super Admin</span>
                </h4>
                <ul style="color: var(--text-secondary); font-size: 0.875rem; padding-left: 1.25rem; margin: 0;">
                    <li>Full system access</li>
                    <li>Manage all users including admins</li>
                    <li>Access all settings</li>
                </ul>
            </div>
            <div>
                <h4 style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <span class="badge badge-primary">Admin</span>
                </h4>
                <ul style="color: var(--text-secondary); font-size: 0.875rem; padding-left: 1.25rem; margin: 0;">
                    <li>Manage content and downloads</li>
                    <li>Manage editors</li>
                    <li>View activity logs</li>
                </ul>
            </div>
            <div>
                <h4 style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <span class="badge badge-gray">Editor</span>
                </h4>
                <ul style="color: var(--text-secondary); font-size: 0.875rem; padding-left: 1.25rem; margin: 0;">
                    <li>Add and edit content</li>
                    <li>Upload files and images</li>
                    <li>View dashboard</li>
                </ul>
            </div>
            <div>
                <h4 style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <span class="badge badge-gray">Sales</span>
                </h4>
                <ul style="color: var(--text-secondary); font-size: 0.875rem; padding-left: 1.25rem; margin: 0;">
                    <li>View and manage Pulse Checks</li>
                    <li>View and manage Inquiries</li>
                    <li>No delete permissions</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>