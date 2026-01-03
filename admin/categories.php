<?php
/**
 * Admin Categories Management
 */

$pageTitle = "Categories";
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
    
    // Check if category has downloads
    $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM downloads WHERE category_id = ?");
    $checkStmt->bind_param("i", $deleteId);
    $checkStmt->execute();
    $count = $checkStmt->get_result()->fetch_assoc()['count'];
    $checkStmt->close();
    
    if ($count > 0) {
        $errors['general'] = "Cannot delete category. It has {$count} download(s) associated with it.";
    } else {
        $stmt = $conn->prepare("DELETE FROM download_categories WHERE id = ?");
        $stmt->bind_param("i", $deleteId);
        
        if ($stmt->execute()) {
            logAdminActivity($_SESSION['admin_id'], 'delete', 'download_categories', $deleteId, 'Deleted category');
            $successMessage = "Category deleted successfully.";
        } else {
            $errors['general'] = "Failed to delete category.";
        }
        $stmt->close();
    }
}

// Handle toggle status
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $toggleId = (int)$_GET['toggle'];
    $stmt = $conn->prepare("UPDATE download_categories SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $toggleId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'toggle_status', 'download_categories', $toggleId, 'Toggled category status');
        $successMessage = "Category status updated.";
    }
    $stmt->close();
}

// Handle edit - load category data
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM download_categories WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editCategory = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Handle form submission (Add/Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon = trim($_POST['icon'] ?? 'document');
    $color = trim($_POST['color'] ?? '#E99431');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    // Generate slug if empty
    if (empty($slug)) {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
    }
    
    // Validate
    if (empty($name)) {
        $errors['name'] = 'Category name is required.';
    }
    
    // Check for duplicate slug
    if (empty($errors)) {
        $checkQuery = "SELECT id FROM download_categories WHERE slug = ?";
        if ($categoryId > 0) {
            $checkQuery .= " AND id != ?";
        }
        $checkStmt = $conn->prepare($checkQuery);
        if ($categoryId > 0) {
            $checkStmt->bind_param("si", $slug, $categoryId);
        } else {
            $checkStmt->bind_param("s", $slug);
        }
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) {
            $errors['slug'] = 'A category with this slug already exists.';
        }
        $checkStmt->close();
    }
    
    // Save
    if (empty($errors)) {
        if ($categoryId > 0) {
            // Update
            $stmt = $conn->prepare("
                UPDATE download_categories SET
                    name = ?, slug = ?, description = ?, icon = ?, color = ?, sort_order = ?, is_active = ?
                WHERE id = ?
            ");
            $stmt->bind_param("sssssiii", $name, $slug, $description, $icon, $color, $sortOrder, $isActive, $categoryId);
            
            if ($stmt->execute()) {
                logAdminActivity($_SESSION['admin_id'], 'update', 'download_categories', $categoryId, 'Updated category: ' . $name);
                $successMessage = "Category updated successfully.";
                $editCategory = null;
            } else {
                $errors['general'] = 'Failed to update category.';
            }
        } else {
            // Insert
            $stmt = $conn->prepare("
                INSERT INTO download_categories (name, slug, description, icon, color, sort_order, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("sssssi", $name, $slug, $description, $icon, $color, $sortOrder, $isActive);
            
            if ($stmt->execute()) {
                $newId = $conn->insert_id;
                logAdminActivity($_SESSION['admin_id'], 'create', 'download_categories', $newId, 'Created category: ' . $name);
                $successMessage = "Category created successfully.";
            } else {
                $errors['general'] = 'Failed to create category.';
            }
        }
        $stmt->close();
    }
}

// Get all categories
$categories = $conn->query("
    SELECT c.*, 
           (SELECT COUNT(*) FROM downloads d WHERE d.category_id = c.id) as download_count
    FROM download_categories c 
    ORDER BY c.sort_order, c.name
");

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Categories</h1>
        <p class="page-subtitle">Manage download categories</p>
    </div>
</div>

<?php if ($successMessage): ?>
    <div class="alert alert-success" data-auto-hide>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span><?php echo htmlspecialchars($successMessage); ?></span>
    </div>
<?php endif; ?>

<?php if (isset($errors['general'])): ?>
    <div class="alert alert-danger">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span><?php echo htmlspecialchars($errors['general']); ?></span>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <!-- Add/Edit Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?php echo $editCategory ? 'Edit Category' : 'Add New Category'; ?></h3>
            <?php if ($editCategory): ?>
                <a href="categories.php" class="btn btn-secondary btn-sm">Cancel Edit</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <form method="POST">
                <?php if ($editCategory): ?>
                    <input type="hidden" name="category_id" value="<?php echo $editCategory['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name" class="form-label">
                        Category Name <span class="required">*</span>
                    </label>
                    <input type="text" id="name" name="name" class="form-control <?php echo isset($errors['name']) ? 'error' : ''; ?>" 
                           placeholder="e.g., Brochures" required
                           value="<?php echo htmlspecialchars($editCategory['name'] ?? ($_POST['name'] ?? '')); ?>">
                    <?php if (isset($errors['name'])): ?>
                        <span class="form-error"><?php echo $errors['name']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" id="slug" name="slug" class="form-control <?php echo isset($errors['slug']) ? 'error' : ''; ?>" 
                           placeholder="Auto-generated from name"
                           value="<?php echo htmlspecialchars($editCategory['slug'] ?? ($_POST['slug'] ?? '')); ?>">
                    <span class="form-hint">Leave empty to auto-generate from name</span>
                    <?php if (isset($errors['slug'])): ?>
                        <span class="form-error"><?php echo $errors['slug']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="2"
                              placeholder="Brief description of this category"><?php echo htmlspecialchars($editCategory['description'] ?? ($_POST['description'] ?? '')); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="icon" class="form-label">Icon</label>
                        <select id="icon" name="icon" class="form-control form-select">
                            <?php
                            $icons = ['book', 'document', 'cog', 'academic-cap', 'folder', 'chart', 'lightbulb', 'puzzle'];
                            $currentIcon = $editCategory['icon'] ?? ($_POST['icon'] ?? 'document');
                            foreach ($icons as $icon):
                            ?>
                                <option value="<?php echo $icon; ?>" <?php echo $currentIcon == $icon ? 'selected' : ''; ?>>
                                    <?php echo ucfirst(str_replace('-', ' ', $icon)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="color" class="form-label">Color</label>
                        <input type="color" id="color" name="color" class="form-control" style="height: 42px; padding: 4px;"
                               value="<?php echo htmlspecialchars($editCategory['color'] ?? ($_POST['color'] ?? '#E99431')); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-control" min="0"
                               value="<?php echo htmlspecialchars($editCategory['sort_order'] ?? ($_POST['sort_order'] ?? '0')); ?>">
                    </div>
                    
                    <div class="form-group" style="display: flex; align-items: center; padding-top: 28px;">
                        <label class="toggle-switch" style="margin-right: 0.75rem;">
                            <input type="checkbox" name="is_active" <?php echo ($editCategory['is_active'] ?? true) ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                        <span>Active</span>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    <?php echo $editCategory ? 'Update Category' : 'Create Category'; ?>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Categories List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Categories</h3>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Downloads</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($categories->num_rows > 0): ?>
                        <?php while ($category = $categories->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="table-item">
                                        <div class="table-item-icon" style="background: <?php echo htmlspecialchars($category['color']); ?>15;">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="<?php echo htmlspecialchars($category['color']); ?>" stroke-width="2" style="width: 20px; height: 20px;">
                                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="table-item-info">
                                            <h4><?php echo htmlspecialchars($category['name']); ?></h4>
                                            <p><?php echo htmlspecialchars($category['slug']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-gray"><?php echo $category['download_count']; ?> files</span>
                                </td>
                                <td>
                                    <?php if ($category['is_active']): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-gray">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="?edit=<?php echo $category['id']; ?>" 
                                           class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>
                                        <a href="?toggle=<?php echo $category['id']; ?>" 
                                           class="btn btn-secondary btn-sm btn-icon" 
                                           title="<?php echo $category['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                            <?php if ($category['is_active']): ?>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            <?php else: ?>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                                </svg>
                                            <?php endif; ?>
                                        </a>
                                        <?php if ($category['download_count'] == 0): ?>
                                            <a href="?delete=<?php echo $category['id']; ?>" 
                                               class="btn btn-danger btn-sm btn-icon" 
                                               title="Delete"
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
                    <?php else: ?>
                        <tr>
                            <td colspan="4">
                                <div class="empty-state" style="padding: 2rem;">
                                    <p>No categories found. Create your first category.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
