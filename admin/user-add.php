<?php
/**
 * Admin Add User Page
 * Create new admin users
 */

$pageTitle = "Add User";
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

// Available roles based on current user's role
$availableRoles = [];
if ($currentAdmin['role'] === 'super_admin') {
    $availableRoles = ['super_admin', 'admin', 'editor', 'sales'];
} elseif ($currentAdmin['role'] === 'admin') {
    $availableRoles = ['admin', 'editor', 'sales'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors['general'] = 'Invalid security token. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $fullName = trim($_POST['full_name'] ?? '');
        $role = $_POST['role'] ?? 'editor';
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        // Validate username
        if (empty($username)) {
            $errors['username'] = 'Username is required.';
        } elseif (strlen($username) < 3) {
            $errors['username'] = 'Username must be at least 3 characters.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors['username'] = 'Username can only contain letters, numbers, and underscores.';
        } else {
            // Check for duplicate username
            $checkStmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
            $checkStmt->bind_param("s", $username);
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
            $checkStmt = $conn->prepare("SELECT id FROM admin_users WHERE email = ?");
            $checkStmt->bind_param("s", $email);
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

        // Validate password
        if (empty($password)) {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        // Validate confirm password
        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        // Validate role
        if (!in_array($role, $availableRoles)) {
            $errors['role'] = 'Invalid role selected.';
        }

        // Save if no errors
        if (empty($errors)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                INSERT INTO admin_users (username, email, password, full_name, role, is_active)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("sssssi", $username, $email, $hashedPassword, $fullName, $role, $isActive);

            if ($stmt->execute()) {
                $newId = $conn->insert_id;
                logAdminActivity($_SESSION['admin_id'], 'create', 'admin_users', $newId, 'Created user: ' . $username);

                $conn->close();
                header('Location: users.php?success=created');
                exit;
            } else {
                $errors['general'] = 'Failed to create user. Please try again.';
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
            <span>Add User</span>
        </nav>
        <h1 class="page-title">Add New User</h1>
        <p class="page-subtitle">Create a new admin panel user</p>
    </div>
</div>

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

<div class="card" style="max-width: 600px;">
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
                    value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
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
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
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
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    <?php if (isset($errors['email'])): ?>
                        <span class="form-error"><?php echo $errors['email']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">
                        Password <span class="required">*</span>
                    </label>
                    <input type="password" id="password" name="password"
                        class="form-control <?php echo isset($errors['password']) ? 'error' : ''; ?>"
                        placeholder="Minimum 8 characters" required autocomplete="new-password">
                    <span class="form-hint">At least 8 characters</span>
                    <?php if (isset($errors['password'])): ?>
                        <span class="form-error"><?php echo $errors['password']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">
                        Confirm Password <span class="required">*</span>
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password"
                        class="form-control <?php echo isset($errors['confirm_password']) ? 'error' : ''; ?>"
                        placeholder="Repeat password" required autocomplete="new-password">
                    <?php if (isset($errors['confirm_password'])): ?>
                        <span class="form-error"><?php echo $errors['confirm_password']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="role" class="form-label">
                        Role <span class="required">*</span>
                    </label>
                    <select id="role" name="role"
                        class="form-control form-select <?php echo isset($errors['role']) ? 'error' : ''; ?>" required>
                        <?php foreach ($availableRoles as $roleOption): ?>
                            <option value="<?php echo $roleOption; ?>" <?php echo ($_POST['role'] ?? 'editor') === $roleOption ? 'selected' : ''; ?>>
                                <?php echo ucwords(str_replace('_', ' ', $roleOption)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['role'])): ?>
                        <span class="form-error"><?php echo $errors['role']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group" style="display: flex; align-items: center; padding-top: 28px;">
                    <label class="toggle-switch" style="margin-right: 0.75rem;">
                        <input type="checkbox" name="is_active" <?php echo ($_POST['is_active'] ?? true) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span>Active</span>
                </div>
            </div>

            <div class="form-actions" style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="8.5" cy="7" r="4" />
                        <line x1="20" y1="8" x2="20" y2="14" />
                        <line x1="23" y1="11" x2="17" y2="11" />
                    </svg>
                    Create User
                </button>
                <a href="users.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>