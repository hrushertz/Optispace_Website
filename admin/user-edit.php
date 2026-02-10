<?php
/**
 * Admin Edit User Page
 * Edit existing admin users
 */

$pageTitle = "Edit User";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();
requireAdminRole('admin'); // Only admin and super_admin can access

require_once __DIR__ . '/../database/db_config.php';

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

// Get user ID from URL
$userId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($userId <= 0) {
    header('Location: users.php');
    exit;
}

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM admin_users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    header('Location: users.php?error=notfound');
    exit;
}

// Check permissions
$userLevel = $roleHierarchy[$user['role']] ?? 0;
$isSelf = ((int) $userId === (int) $currentAdmin['id']);
$canModify = ($currentUserLevel > $userLevel) || ($currentAdmin['role'] === 'super_admin') || $isSelf;

if (!$canModify) {
    header('Location: users.php?error=unauthorized');
    exit;
}

// Available roles based on current user's role (and can't elevate above own level)
$availableRoles = [];
if ($currentAdmin['role'] === 'super_admin') {
    $availableRoles = ['super_admin', 'admin', 'editor', 'sales'];
} elseif ($currentAdmin['role'] === 'admin') {
    // Admin can only assign admin or lower, but can't change super_admin's role
    if ($user['role'] === 'super_admin') {
        $availableRoles = ['super_admin']; // Can't change super_admin's role
    } else {
        $availableRoles = ['admin', 'editor', 'sales'];
    }
}

// If editing self, can't change own role unless super_admin
if ($isSelf && $currentAdmin['role'] !== 'super_admin') {
    $availableRoles = [$currentAdmin['role']];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors['general'] = 'Invalid security token. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $fullName = trim($_POST['full_name'] ?? '');
        $role = $_POST['role'] ?? $user['role'];
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate username
        if (empty($username)) {
            $errors['username'] = 'Username is required.';
        } elseif (strlen($username) < 3) {
            $errors['username'] = 'Username must be at least 3 characters.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors['username'] = 'Username can only contain letters, numbers, and underscores.';
        } else {
            // Check for duplicate username
            $checkStmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ? AND id != ?");
            $checkStmt->bind_param("si", $username, $userId);
            $checkStmt->execute();
            if ($checkStmt->get_result()->num_rows > 0) {
                $errors['username'] = 'This username is already taken.';
            }
            $checkStmt->close();
        }

        // Validate email
        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } else {
            // Check for duplicate email
            $checkStmt = $conn->prepare("SELECT id FROM admin_users WHERE email = ? AND id != ?");
            $checkStmt->bind_param("si", $email, $userId);
            $checkStmt->execute();
            if ($checkStmt->get_result()->num_rows > 0) {
                $errors['email'] = 'This email is already registered.';
            }
            $checkStmt->close();
        }

        // Validate full name
        if (empty($fullName)) {
            $errors['full_name'] = 'Full name is required.';
        }

        // Validate password (only if provided)
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 8) {
                $errors['new_password'] = 'Password must be at least 8 characters.';
            } elseif ($newPassword !== $confirmPassword) {
                $errors['confirm_password'] = 'Passwords do not match.';
            }
        }

        // Validate role
        if (!in_array($role, $availableRoles)) {
            $errors['role'] = 'Invalid role selected or you do not have permission to assign this role.';
        }

        // Can't deactivate yourself
        if ($isSelf && !$isActive) {
            $errors['general'] = 'You cannot deactivate your own account.';
            $isActive = 1;
        }

        // Save if no errors
        if (empty($errors)) {
            if (!empty($newPassword)) {
                // Update with password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("
                    UPDATE admin_users SET 
                        username = ?, email = ?, password = ?, full_name = ?, role = ?, is_active = ?
                    WHERE id = ?
                ");
                $stmt->bind_param("sssssii", $username, $email, $hashedPassword, $fullName, $role, $isActive, $userId);
            } else {
                // Update without password
                $stmt = $conn->prepare("
                    UPDATE admin_users SET 
                        username = ?, email = ?, full_name = ?, role = ?, is_active = ?
                    WHERE id = ?
                ");
                $stmt->bind_param("ssssii", $username, $email, $fullName, $role, $isActive, $userId);
            }

            if ($stmt->execute()) {
                logAdminActivity($_SESSION['admin_id'], 'update', 'admin_users', $userId, 'Updated user: ' . $username);

                // If editing self, update session data
                if ($isSelf) {
                    $_SESSION['admin_username'] = $username;
                    $_SESSION['admin_email'] = $email;
                    $_SESSION['admin_name'] = $fullName;
                    if (in_array($role, $availableRoles)) {
                        $_SESSION['admin_role'] = $role;
                    }
                }

                $successMessage = "User updated successfully.";

                // Refresh user data
                $stmt2 = $conn->prepare("SELECT * FROM admin_users WHERE id = ?");
                $stmt2->bind_param("i", $userId);
                $stmt2->execute();
                $user = $stmt2->get_result()->fetch_assoc();
                $stmt2->close();
            } else {
                $errors['general'] = 'Failed to update user. Please try again.';
            }
            $stmt->close();
        }
    }
}

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <nav class="breadcrumb">
            <a href="users.php">Users</a>
            <span class="separator">/</span>
            <span>Edit User</span>
        </nav>
        <h1 class="page-title">Edit User</h1>
        <p class="page-subtitle">
            Editing: <?php echo htmlspecialchars($user['full_name']); ?>
            <?php if ($isSelf): ?>
                <span class="badge badge-primary" style="margin-left: 0.5rem;">Your Account</span>
            <?php endif; ?>
        </p>
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

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <!-- User Information -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">User Information</h3>
        </div>
        <div class="card-body">
            <form method="POST" autocomplete="off">
                <?php echo csrfField(); ?>

                <div class="form-group">
                    <label for="full_name" class="form-label">
                        Full Name <span class="required">*</span>
                    </label>
                    <input type="text" id="full_name" name="full_name"
                        class="form-control <?php echo isset($errors['full_name']) ? 'error' : ''; ?>"
                        placeholder="e.g., John Doe" required
                        value="<?php echo htmlspecialchars($_POST['full_name'] ?? $user['full_name']); ?>">
                    <?php if (isset($errors['full_name'])): ?>
                        <span class="form-error"><?php echo $errors['full_name']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="username" class="form-label">
                            Username <span class="required">*</span>
                        </label>
                        <input type="text" id="username" name="username"
                            class="form-control <?php echo isset($errors['username']) ? 'error' : ''; ?>"
                            placeholder="e.g., johndoe" required autocomplete="off"
                            value="<?php echo htmlspecialchars($_POST['username'] ?? $user['username']); ?>">
                        <span class="form-hint">Letters, numbers, and underscores only</span>
                        <?php if (isset($errors['username'])): ?>
                            <span class="form-error"><?php echo $errors['username']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email <span class="required">*</span>
                        </label>
                        <input type="email" id="email" name="email"
                            class="form-control <?php echo isset($errors['email']) ? 'error' : ''; ?>"
                            placeholder="e.g., john@example.com" required autocomplete="off"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? $user['email']); ?>">
                        <?php if (isset($errors['email'])): ?>
                            <span class="form-error"><?php echo $errors['email']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="role" class="form-label">
                            Role <span class="required">*</span>
                        </label>
                        <select id="role" name="role"
                            class="form-control form-select <?php echo isset($errors['role']) ? 'error' : ''; ?>"
                            required <?php echo count($availableRoles) === 1 ? 'disabled' : ''; ?>>
                            <?php foreach ($availableRoles as $roleOption): ?>
                                <option value="<?php echo $roleOption; ?>" <?php echo ($_POST['role'] ?? $user['role']) === $roleOption ? 'selected' : ''; ?>>
                                    <?php echo ucwords(str_replace('_', ' ', $roleOption)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (count($availableRoles) === 1): ?>
                            <input type="hidden" name="role" value="<?php echo $user['role']; ?>">
                            <span class="form-hint">Role cannot be changed</span>
                        <?php endif; ?>
                        <?php if (isset($errors['role'])): ?>
                            <span class="form-error"><?php echo $errors['role']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group" style="display: flex; align-items: center; padding-top: 28px;">
                        <label class="toggle-switch" style="margin-right: 0.75rem;">
                            <input type="checkbox" name="is_active" <?php echo ($_POST['is_active'] ?? $user['is_active']) ? 'checked' : ''; ?> <?php echo $isSelf ? 'disabled' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                        <span>Active</span>
                        <?php if ($isSelf): ?>
                            <input type="hidden" name="is_active" value="1">
                            <span class="form-hint" style="margin-left: 0.5rem; font-size: 0.75rem;">(Cannot deactivate
                                yourself)</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-actions" style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Update User
                    </button>
                    <a href="users.php" class="btn btn-secondary">Back to Users</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password & Account Info -->
    <div>
        <!-- Change Password -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Change Password</h3>
            </div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <?php echo csrfField(); ?>

                    <!-- Hidden fields to preserve other data -->
                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                    <input type="hidden" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
                    <input type="hidden" name="role" value="<?php echo htmlspecialchars($user['role']); ?>">
                    <input type="hidden" name="is_active" value="<?php echo $user['is_active']; ?>">

                    <div class="form-group">
                        <label for="new_password" class="form-label">
                            New Password
                        </label>
                        <input type="password" id="new_password" name="new_password"
                            class="form-control <?php echo isset($errors['new_password']) ? 'error' : ''; ?>"
                            placeholder="Enter new password" autocomplete="new-password">
                        <span class="form-hint">At least 8 characters. Leave blank to keep current password.</span>
                        <?php if (isset($errors['new_password'])): ?>
                            <span class="form-error"><?php echo $errors['new_password']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password" class="form-label">
                            Confirm New Password
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password"
                            class="form-control <?php echo isset($errors['confirm_password']) ? 'error' : ''; ?>"
                            placeholder="Repeat new password" autocomplete="new-password">
                        <?php if (isset($errors['confirm_password'])): ?>
                            <span class="form-error"><?php echo $errors['confirm_password']; ?></span>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-secondary" style="width: 100%;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        Change Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Account Info -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Account Information</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <span style="color: var(--text-muted); font-size: 0.875rem;">Account Created</span>
                        <p style="margin: 0.25rem 0 0; font-weight: 500;">
                            <?php echo date('F j, Y \a\t g:i A', strtotime($user['created_at'])); ?>
                        </p>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); font-size: 0.875rem;">Last Login</span>
                        <p style="margin: 0.25rem 0 0; font-weight: 500;">
                            <?php if ($user['last_login']): ?>
                                <?php echo date('F j, Y \a\t g:i A', strtotime($user['last_login'])); ?>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">Never logged in</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); font-size: 0.875rem;">Last Updated</span>
                        <p style="margin: 0.25rem 0 0; font-weight: 500;">
                            <?php echo date('F j, Y \a\t g:i A', strtotime($user['updated_at'])); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>