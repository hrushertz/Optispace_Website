<?php
/**
 * Admin Gallery Industries
 */

$pageTitle = "Industries";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

$errors = [];
$successMessage = '';
$editIndustry = null;

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM gallery_industries WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'delete', 'gallery_industries', $deleteId, 'Deleted industry');
        $successMessage = "Industry deleted successfully.";
    } else {
        $errors[] = "Failed to delete industry.";
    }
    $stmt->close();
}

// Handle toggle status
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $toggleId = (int)$_GET['toggle'];
    $stmt = $conn->prepare("UPDATE gallery_industries SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $toggleId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'toggle_status', 'gallery_industries', $toggleId, 'Toggled industry status');
        $successMessage = "Industry status updated.";
    }
    $stmt->close();
}

// Handle edit request
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM gallery_industries WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editIndustry = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    $icon = trim($_POST['icon']);
    $projectCount = (int)$_POST['project_count'];
    $sortOrder = (int)$_POST['sort_order'];
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    
    if (empty($slug)) {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
    }
    
    if (empty($errors)) {
        if ($action === 'edit' && isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            $stmt = $conn->prepare("
                UPDATE gallery_industries SET 
                name = ?, slug = ?, icon = ?, project_count = ?, sort_order = ?, is_active = ?
                WHERE id = ?
            ");
            $stmt->bind_param("sssiiis", $name, $slug, $icon, $projectCount, $sortOrder, $isActive, $id);
            
            if ($stmt->execute()) {
                logAdminActivity($_SESSION['admin_id'], 'update', 'gallery_industries', $id, 'Updated industry: ' . $name);
                $successMessage = "Industry updated successfully.";
                $editIndustry = null;
            } else {
                $errors[] = "Failed to update industry.";
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("
                INSERT INTO gallery_industries (name, slug, icon, project_count, sort_order, is_active)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("sssiii", $name, $slug, $icon, $projectCount, $sortOrder, $isActive);
            
            if ($stmt->execute()) {
                logAdminActivity($_SESSION['admin_id'], 'create', 'gallery_industries', $conn->insert_id, 'Created industry: ' . $name);
                $successMessage = "Industry created successfully.";
            } else {
                $errors[] = "Failed to create industry. Slug may already exist.";
            }
            $stmt->close();
        }
    }
}

// Get all industries
$industries = $conn->query("SELECT * FROM gallery_industries ORDER BY sort_order ASC");

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Industries</h1>
        <p class="page-subtitle">Manage industries displayed on the gallery page</p>
    </div>
</div>

<?php if ($successMessage): ?>
<div class="alert alert-success">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
        <polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <?php echo htmlspecialchars($successMessage); ?>
</div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <line x1="15" y1="9" x2="9" y2="15"/>
        <line x1="9" y1="9" x2="15" y2="15"/>
    </svg>
    <ul style="margin: 0; padding-left: 1.25rem;">
        <?php foreach ($errors as $error): ?>
        <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="industries-layout">
    <!-- Industry Form -->
    <div class="form-card industry-form">
        <div class="form-card-header">
            <h2><?php echo $editIndustry ? 'Edit Industry' : 'Add New Industry'; ?></h2>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $editIndustry ? 'edit' : 'add'; ?>">
            <?php if ($editIndustry): ?>
            <input type="hidden" name="id" value="<?php echo $editIndustry['id']; ?>">
            <?php endif; ?>
            
            <div class="form-card-body">
                <div class="form-group">
                    <label for="name" class="form-label required">Name</label>
                    <input type="text" id="name" name="name" class="form-input" 
                           value="<?php echo htmlspecialchars($editIndustry['name'] ?? ''); ?>" 
                           placeholder="e.g., Automotive" required>
                </div>
                
                <div class="form-group">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" id="slug" name="slug" class="form-input" 
                           value="<?php echo htmlspecialchars($editIndustry['slug'] ?? ''); ?>" 
                           placeholder="Auto-generated if empty">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="icon" class="form-label">Icon</label>
                        <select id="icon" name="icon" class="form-select">
                            <option value="car" <?php echo ($editIndustry['icon'] ?? '') === 'car' ? 'selected' : ''; ?>>Car (Automotive)</option>
                            <option value="medical" <?php echo ($editIndustry['icon'] ?? '') === 'medical' ? 'selected' : ''; ?>>Medical</option>
                            <option value="chip" <?php echo ($editIndustry['icon'] ?? '') === 'chip' ? 'selected' : ''; ?>>Chip (Electronics)</option>
                            <option value="food" <?php echo ($editIndustry['icon'] ?? '') === 'food' ? 'selected' : ''; ?>>Food</option>
                            <option value="plane" <?php echo ($editIndustry['icon'] ?? '') === 'plane' ? 'selected' : ''; ?>>Plane (Aerospace)</option>
                            <option value="heart" <?php echo ($editIndustry['icon'] ?? '') === 'heart' ? 'selected' : ''; ?>>Heart (Healthcare)</option>
                            <option value="building" <?php echo ($editIndustry['icon'] ?? '') === 'building' ? 'selected' : ''; ?>>Building</option>
                            <option value="cog" <?php echo ($editIndustry['icon'] ?? '') === 'cog' ? 'selected' : ''; ?>>Cog (Manufacturing)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="project_count" class="form-label">Project Count</label>
                        <input type="number" id="project_count" name="project_count" class="form-input" 
                               value="<?php echo htmlspecialchars($editIndustry['project_count'] ?? '0'); ?>" min="0">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-input" 
                               value="<?php echo htmlspecialchars($editIndustry['sort_order'] ?? '0'); ?>" min="0">
                    </div>
                    
                    <div class="form-group" style="display: flex; align-items: flex-end;">
                        <label class="checkbox-label" style="margin-bottom: 0;">
                            <input type="checkbox" name="is_active" value="1" 
                                   <?php echo (!isset($editIndustry) || $editIndustry['is_active']) ? 'checked' : ''; ?>>
                            <span class="checkbox-text">Active</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="form-card-footer">
                <?php if ($editIndustry): ?>
                <a href="gallery-industries.php" class="btn btn-ghost">Cancel</a>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <?php if ($editIndustry): ?>
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                        <?php else: ?>
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <?php endif; ?>
                    </svg>
                    <?php echo $editIndustry ? 'Update Industry' : 'Add Industry'; ?>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Industries List -->
    <div class="industries-list">
        <div class="form-card">
            <div class="form-card-header">
                <h2>All Industries</h2>
            </div>
            <div class="industries-grid-admin">
                <?php if ($industries->num_rows > 0): ?>
                    <?php while ($ind = $industries->fetch_assoc()): ?>
                    <div class="industry-admin-card <?php echo $ind['is_active'] ? '' : 'inactive'; ?>">
                        <div class="industry-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <?php
                                switch ($ind['icon']) {
                                    case 'car':
                                        echo '<path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C1.4 11.3 1 12.1 1 13v3c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/>';
                                        break;
                                    case 'medical':
                                        echo '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>';
                                        break;
                                    case 'chip':
                                        echo '<rect x="4" y="4" width="16" height="16" rx="2" ry="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/>';
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
                                        echo '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>';
                                        break;
                                    default:
                                        echo '<path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/>';
                                }
                                ?>
                            </svg>
                        </div>
                        <div class="industry-info">
                            <h4><?php echo htmlspecialchars($ind['name']); ?></h4>
                            <span><?php echo $ind['project_count']; ?>+ Projects</span>
                        </div>
                        <div class="industry-actions">
                            <a href="?edit=<?php echo $ind['id']; ?>" class="action-btn" title="Edit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <a href="?toggle=<?php echo $ind['id']; ?>" class="action-btn" title="<?php echo $ind['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <?php if ($ind['is_active']): ?>
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                    <?php else: ?>
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                    <?php endif; ?>
                                </svg>
                            </a>
                            <a href="?delete=<?php echo $ind['id']; ?>" class="action-btn danger" title="Delete" onclick="return confirm('Are you sure you want to delete this industry?');">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                            </a>
                        </div>
                        <?php if (!$ind['is_active']): ?>
                        <div class="inactive-badge">Inactive</div>
                        <?php endif; ?>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state-small">
                        <p>No industries yet. Create your first industry using the form.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Industries Layout */
.industries-layout {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 1.5rem;
    align-items: start;
}

.industry-form {
    position: sticky;
    top: calc(var(--header-height) + 1.5rem);
}

.form-card {
    background: var(--admin-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--admin-gray-200);
    overflow: hidden;
}

.form-card-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--admin-gray-100);
}

.form-card-header h2 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--admin-dark);
    margin: 0;
}

.form-card-body {
    padding: 1.5rem;
}

.form-card-footer {
    padding: 1rem 1.5rem;
    background: var(--admin-gray-50);
    border-top: 1px solid var(--admin-gray-100);
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--admin-gray-700);
    margin-bottom: 0.5rem;
}

.form-label.required::after {
    content: '*';
    color: var(--admin-danger);
    margin-left: 0.25rem;
}

.form-input,
.form-select {
    width: 100%;
    padding: 0.65rem 0.875rem;
    border: 1px solid var(--admin-gray-200);
    border-radius: var(--radius-md);
    font-size: 0.9rem;
    font-family: inherit;
    background: var(--admin-white);
    color: var(--admin-gray-700);
    transition: all var(--transition-fast);
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: var(--admin-primary);
    box-shadow: 0 0 0 3px var(--admin-primary-light);
}

.form-select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    padding-right: 2rem;
}

/* Checkbox */
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: var(--admin-primary);
}

.checkbox-text {
    font-size: 0.9rem;
    color: var(--admin-gray-700);
}

/* Industries Grid */
.industries-grid-admin {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
}

.industry-admin-card {
    position: relative;
    background: var(--admin-gray-50);
    border: 1px solid var(--admin-gray-200);
    border-radius: var(--radius-md);
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    transition: all var(--transition-fast);
}

.industry-admin-card:hover {
    background: var(--admin-white);
    border-color: var(--admin-primary);
    box-shadow: var(--shadow-md);
}

.industry-admin-card.inactive {
    opacity: 0.6;
}

.industry-icon {
    width: 48px;
    height: 48px;
    background: var(--admin-primary-light);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
}

.industry-icon svg {
    width: 24px;
    height: 24px;
    color: var(--admin-primary);
}

.industry-info h4 {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--admin-dark);
    margin-bottom: 0.25rem;
}

.industry-info span {
    font-size: 0.8rem;
    color: var(--admin-gray-500);
}

.industry-actions {
    display: flex;
    gap: 0.35rem;
    margin-top: 1rem;
    opacity: 0;
    transition: opacity var(--transition-fast);
}

.industry-admin-card:hover .industry-actions {
    opacity: 1;
}

.action-btn {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-sm);
    color: var(--admin-gray-500);
    transition: all var(--transition-fast);
}

.action-btn:hover {
    background: var(--admin-gray-100);
    color: var(--admin-primary);
}

.action-btn.danger:hover {
    background: var(--admin-danger-light);
    color: var(--admin-danger);
}

.action-btn svg {
    width: 14px;
    height: 14px;
}

.inactive-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    font-size: 0.65rem;
    background: var(--admin-gray-500);
    color: white;
    padding: 0.15rem 0.4rem;
    border-radius: var(--radius-sm);
    font-weight: 600;
}

.empty-state-small {
    grid-column: 1 / -1;
    text-align: center;
    padding: 2rem;
    color: var(--admin-gray-500);
}

/* Responsive */
@media (max-width: 1024px) {
    .industries-layout {
        grid-template-columns: 1fr;
    }
    
    .industry-form {
        position: static;
    }
}
</style>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const slugInput = document.getElementById('slug');
    if (!slugInput.dataset.edited) {
        slugInput.value = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');
    }
});

document.getElementById('slug').addEventListener('input', function() {
    this.dataset.edited = 'true';
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
