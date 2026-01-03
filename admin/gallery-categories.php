<?php
/**
 * Admin Gallery Categories
 */

$pageTitle = "Gallery Categories";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

$errors = [];
$successMessage = '';
$editCategory = null;

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    
    // Check if category has items
    $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM gallery_items WHERE category_id = ?");
    $checkStmt->bind_param("i", $deleteId);
    $checkStmt->execute();
    $count = $checkStmt->get_result()->fetch_assoc()['count'];
    $checkStmt->close();
    
    if ($count > 0) {
        $errors[] = "Cannot delete category: It has {$count} gallery items. Please move or delete them first.";
    } else {
        $stmt = $conn->prepare("DELETE FROM gallery_categories WHERE id = ?");
        $stmt->bind_param("i", $deleteId);
        
        if ($stmt->execute()) {
            logAdminActivity($_SESSION['admin_id'], 'delete', 'gallery_categories', $deleteId, 'Deleted gallery category');
            $successMessage = "Category deleted successfully.";
        } else {
            $errors[] = "Failed to delete category.";
        }
        $stmt->close();
    }
}

// Handle toggle status
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $toggleId = (int)$_GET['toggle'];
    $stmt = $conn->prepare("UPDATE gallery_categories SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $toggleId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'toggle_status', 'gallery_categories', $toggleId, 'Toggled category status');
        $successMessage = "Category status updated.";
    }
    $stmt->close();
}

// Handle edit request
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM gallery_categories WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editCategory = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    $description = trim($_POST['description']);
    $icon = trim($_POST['icon']);
    $color = trim($_POST['color']);
    $bgClass = trim($_POST['bg_class']);
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
                UPDATE gallery_categories SET 
                name = ?, slug = ?, description = ?, icon = ?, color = ?, bg_class = ?, sort_order = ?, is_active = ?
                WHERE id = ?
            ");
            $stmt->bind_param("ssssssiis", $name, $slug, $description, $icon, $color, $bgClass, $sortOrder, $isActive, $id);
            
            if ($stmt->execute()) {
                logAdminActivity($_SESSION['admin_id'], 'update', 'gallery_categories', $id, 'Updated category: ' . $name);
                $successMessage = "Category updated successfully.";
                $editCategory = null;
            } else {
                $errors[] = "Failed to update category.";
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("
                INSERT INTO gallery_categories (name, slug, description, icon, color, bg_class, sort_order, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("ssssssii", $name, $slug, $description, $icon, $color, $bgClass, $sortOrder, $isActive);
            
            if ($stmt->execute()) {
                logAdminActivity($_SESSION['admin_id'], 'create', 'gallery_categories', $conn->insert_id, 'Created category: ' . $name);
                $successMessage = "Category created successfully.";
            } else {
                $errors[] = "Failed to create category. Slug may already exist.";
            }
            $stmt->close();
        }
    }
}

// Get all categories with item counts
$categories = $conn->query("
    SELECT c.*, COUNT(g.id) as item_count 
    FROM gallery_categories c 
    LEFT JOIN gallery_items g ON c.id = g.category_id 
    GROUP BY c.id 
    ORDER BY c.sort_order ASC
");

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Gallery Categories</h1>
        <p class="page-subtitle">Manage categories for organizing gallery items</p>
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

<div class="categories-layout">
    <!-- Category Form -->
    <div class="form-card category-form">
        <div class="form-card-header">
            <h2><?php echo $editCategory ? 'Edit Category' : 'Add New Category'; ?></h2>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $editCategory ? 'edit' : 'add'; ?>">
            <?php if ($editCategory): ?>
            <input type="hidden" name="id" value="<?php echo $editCategory['id']; ?>">
            <?php endif; ?>
            
            <div class="form-card-body">
                <div class="form-group">
                    <label for="name" class="form-label required">Name</label>
                    <input type="text" id="name" name="name" class="form-input" 
                           value="<?php echo htmlspecialchars($editCategory['name'] ?? ''); ?>" 
                           placeholder="e.g., Greenfield" required>
                </div>
                
                <div class="form-group">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" id="slug" name="slug" class="form-input" 
                           value="<?php echo htmlspecialchars($editCategory['slug'] ?? ''); ?>" 
                           placeholder="Auto-generated if empty">
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-textarea" rows="2" 
                              placeholder="Brief description..."><?php echo htmlspecialchars($editCategory['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="icon" class="form-label">Icon</label>
                        <input type="text" id="icon" name="icon" class="form-input" 
                               value="<?php echo htmlspecialchars($editCategory['icon'] ?? 'image'); ?>" 
                               placeholder="e.g., building">
                    </div>
                    
                    <div class="form-group">
                        <label for="color" class="form-label">Color</label>
                        <div class="color-input-wrapper">
                            <input type="color" id="color_picker" class="color-picker" 
                                   value="<?php echo htmlspecialchars($editCategory['color'] ?? '#E99431'); ?>">
                            <input type="text" id="color" name="color" class="form-input" 
                                   value="<?php echo htmlspecialchars($editCategory['color'] ?? '#E99431'); ?>" 
                                   placeholder="#E99431">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="bg_class" class="form-label">Background Class</label>
                        <select id="bg_class" name="bg_class" class="form-select">
                            <option value="greenfield-bg" <?php echo ($editCategory['bg_class'] ?? '') === 'greenfield-bg' ? 'selected' : ''; ?>>Greenfield (Orange)</option>
                            <option value="brownfield-bg" <?php echo ($editCategory['bg_class'] ?? '') === 'brownfield-bg' ? 'selected' : ''; ?>>Brownfield (Blue)</option>
                            <option value="layout-bg" <?php echo ($editCategory['bg_class'] ?? '') === 'layout-bg' ? 'selected' : ''; ?>>Layout (Green)</option>
                            <option value="process-bg" <?php echo ($editCategory['bg_class'] ?? '') === 'process-bg' ? 'selected' : ''; ?>>Process (Gray)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-input" 
                               value="<?php echo htmlspecialchars($editCategory['sort_order'] ?? '0'); ?>" min="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" 
                               <?php echo (!isset($editCategory) || $editCategory['is_active']) ? 'checked' : ''; ?>>
                        <span class="checkbox-text">Active</span>
                    </label>
                </div>
            </div>
            
            <div class="form-card-footer">
                <?php if ($editCategory): ?>
                <a href="gallery-categories.php" class="btn btn-ghost">Cancel</a>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <?php if ($editCategory): ?>
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                        <?php else: ?>
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <?php endif; ?>
                    </svg>
                    <?php echo $editCategory ? 'Update Category' : 'Add Category'; ?>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Categories List -->
    <div class="categories-list">
        <div class="form-card">
            <div class="form-card-header">
                <h2>All Categories</h2>
            </div>
            <div class="categories-table-wrapper">
                <?php if ($categories->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="category-info">
                                    <div class="category-color" style="background: <?php echo htmlspecialchars($cat['color']); ?>"></div>
                                    <div>
                                        <div class="category-name"><?php echo htmlspecialchars($cat['name']); ?></div>
                                        <div class="category-slug"><?php echo htmlspecialchars($cat['slug']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="item-count"><?php echo $cat['item_count']; ?></span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $cat['is_active'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $cat['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td><?php echo $cat['sort_order']; ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="?edit=<?php echo $cat['id']; ?>" class="action-btn" title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>
                                    <a href="?toggle=<?php echo $cat['id']; ?>" class="action-btn" title="<?php echo $cat['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <?php if ($cat['is_active']): ?>
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                            <?php else: ?>
                                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                            <line x1="1" y1="1" x2="23" y2="23"/>
                                            <?php endif; ?>
                                        </svg>
                                    </a>
                                    <?php if ($cat['item_count'] == 0): ?>
                                    <a href="?delete=<?php echo $cat['id']; ?>" class="action-btn danger" title="Delete" 
                                       onclick="return confirm('Are you sure you want to delete this category?');">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="empty-state-small">
                    <p>No categories yet. Create your first category using the form.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Categories Layout */
.categories-layout {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 1.5rem;
    align-items: start;
}

.category-form {
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
.form-select,
.form-textarea {
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
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--admin-primary);
    box-shadow: 0 0 0 3px var(--admin-primary-light);
}

.form-textarea {
    resize: vertical;
    min-height: 60px;
}

.form-select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    padding-right: 2rem;
}

/* Color Input */
.color-input-wrapper {
    display: flex;
    gap: 0.5rem;
}

.color-picker {
    width: 44px;
    height: 38px;
    padding: 2px;
    border: 1px solid var(--admin-gray-200);
    border-radius: var(--radius-md);
    cursor: pointer;
}

.color-input-wrapper .form-input {
    flex: 1;
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

/* Categories Table */
.categories-table-wrapper {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 1rem 1.25rem;
    text-align: left;
    border-bottom: 1px solid var(--admin-gray-100);
}

.data-table th {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--admin-gray-500);
    background: var(--admin-gray-50);
}

.data-table tbody tr:hover {
    background: var(--admin-gray-50);
}

.category-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.category-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
}

.category-name {
    font-weight: 500;
    color: var(--admin-dark);
}

.category-slug {
    font-size: 0.8rem;
    color: var(--admin-gray-500);
}

.item-count {
    display: inline-block;
    min-width: 24px;
    padding: 0.25rem 0.5rem;
    background: var(--admin-gray-100);
    border-radius: var(--radius-full);
    text-align: center;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.active {
    background: var(--admin-success-light);
    color: var(--admin-success);
}

.status-badge.inactive {
    background: var(--admin-gray-100);
    color: var(--admin-gray-500);
}

.table-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-md);
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
    width: 16px;
    height: 16px;
}

.empty-state-small {
    padding: 3rem 2rem;
    text-align: center;
    color: var(--admin-gray-500);
}

/* Responsive */
@media (max-width: 1024px) {
    .categories-layout {
        grid-template-columns: 1fr;
    }
    
    .category-form {
        position: static;
    }
}
</style>

<script>
// Sync color picker with text input
document.getElementById('color_picker').addEventListener('input', function() {
    document.getElementById('color').value = this.value;
});

document.getElementById('color').addEventListener('input', function() {
    const colorPicker = document.getElementById('color_picker');
    if (/^#[0-9A-F]{6}$/i.test(this.value)) {
        colorPicker.value = this.value;
    }
});

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
