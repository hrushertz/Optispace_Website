<?php
/**
 * Admin - Blog Categories Management
 */

$pageTitle = "Blog Categories";
require_once __DIR__ . '/includes/auth.php';
requireLogin();

if (!hasAdminRole('admin')) {
    header('Location: dashboard.php?error=unauthorized');
    exit;
}

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$admin = getCurrentAdmin();
$errors = [];
$editCategory = null;

// Handle form submission (Add/Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors['general'] = 'Invalid security token.';
    } else {
        $action = $_POST['action'] ?? 'add';
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $color = trim($_POST['color'] ?? '#3B82F6');
        $icon = trim($_POST['icon'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        // Generate slug if empty
        if (empty($slug)) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
            $slug = trim($slug, '-');
        }
        
        if (empty($name)) {
            $errors['name'] = 'Category name is required.';
        }
        
        if (empty($errors)) {
            if ($action === 'edit' && $categoryId > 0) {
                // Check for duplicate slug (excluding current)
                $checkStmt = $conn->prepare("SELECT id FROM blog_categories WHERE slug = ? AND id != ?");
                $checkStmt->bind_param("si", $slug, $categoryId);
                $checkStmt->execute();
                if ($checkStmt->get_result()->num_rows > 0) {
                    $slug = $slug . '-' . time();
                }
                $checkStmt->close();
                
                $stmt = $conn->prepare("UPDATE blog_categories SET name = ?, slug = ?, description = ?, color = ?, icon = ?, sort_order = ?, is_active = ? WHERE id = ?");
                $stmt->bind_param("sssssiii", $name, $slug, $description, $color, $icon, $sortOrder, $isActive, $categoryId);
                $stmt->execute();
                $stmt->close();
                
                logAdminActivity($admin['id'], 'update', 'blog_categories', $categoryId, 'Updated category: ' . $name);
                header('Location: blog-categories.php?success=updated');
                exit;
            } else {
                // Check for duplicate slug
                $checkStmt = $conn->prepare("SELECT id FROM blog_categories WHERE slug = ?");
                $checkStmt->bind_param("s", $slug);
                $checkStmt->execute();
                if ($checkStmt->get_result()->num_rows > 0) {
                    $slug = $slug . '-' . time();
                }
                $checkStmt->close();
                
                $stmt = $conn->prepare("INSERT INTO blog_categories (name, slug, description, color, icon, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssii", $name, $slug, $description, $color, $icon, $sortOrder, $isActive);
                $stmt->execute();
                $newId = $conn->insert_id;
                $stmt->close();
                
                logAdminActivity($admin['id'], 'create', 'blog_categories', $newId, 'Created category: ' . $name);
                header('Location: blog-categories.php?success=created');
                exit;
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    if (verifyCsrfToken($_GET['token'] ?? '')) {
        $deleteId = (int)$_GET['delete'];
        
        // Check if category has blogs
        $checkStmt = $conn->prepare("SELECT COUNT(*) as cnt FROM blogs WHERE category_id = ?");
        $checkStmt->bind_param("i", $deleteId);
        $checkStmt->execute();
        $blogCount = $checkStmt->get_result()->fetch_assoc()['cnt'];
        $checkStmt->close();
        
        if ($blogCount > 0) {
            header('Location: blog-categories.php?error=has_blogs');
            exit;
        }
        
        // Get name for log
        $stmt = $conn->prepare("SELECT name FROM blog_categories WHERE id = ?");
        $stmt->bind_param("i", $deleteId);
        $stmt->execute();
        $cat = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if ($cat) {
            $stmt = $conn->prepare("DELETE FROM blog_categories WHERE id = ?");
            $stmt->bind_param("i", $deleteId);
            $stmt->execute();
            $stmt->close();
            
            logAdminActivity($admin['id'], 'delete', 'blog_categories', $deleteId, 'Deleted category: ' . $cat['name']);
        }
    }
    
    header('Location: blog-categories.php?success=deleted');
    exit;
}

// Handle edit mode
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM blog_categories WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editCategory = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Fetch all categories with blog counts
$categories = $conn->query("
    SELECT c.*, COUNT(b.id) as blog_count 
    FROM blog_categories c 
    LEFT JOIN blogs b ON c.id = b.category_id 
    GROUP BY c.id 
    ORDER BY c.sort_order, c.name
");

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Blog Categories</h1>
        <p class="page-subtitle">Manage categories for blog articles</p>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span>
            <?php 
            switch ($_GET['success']) {
                case 'created': echo 'Category created successfully.'; break;
                case 'updated': echo 'Category updated successfully.'; break;
                case 'deleted': echo 'Category deleted successfully.'; break;
                default: echo 'Action completed successfully.';
            }
            ?>
        </span>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'has_blogs'): ?>
    <div class="alert alert-danger">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span>Cannot delete category. It has blogs assigned to it.</span>
    </div>
<?php endif; ?>

<div class="categories-layout">
    <!-- Add/Edit Form -->
    <div class="card form-card">
        <div class="card-header">
            <h3 class="card-title"><?php echo $editCategory ? 'Edit Category' : 'Add New Category'; ?></h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                <input type="hidden" name="action" value="<?php echo $editCategory ? 'edit' : 'add'; ?>">
                <?php if ($editCategory): ?>
                    <input type="hidden" name="category_id" value="<?php echo $editCategory['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name" class="form-label">Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control <?php echo isset($errors['name']) ? 'error' : ''; ?>" 
                           required value="<?php echo htmlspecialchars($_POST['name'] ?? $editCategory['name'] ?? ''); ?>">
                    <?php if (isset($errors['name'])): ?>
                        <span class="form-error"><?php echo $errors['name']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" id="slug" name="slug" class="form-control" 
                           placeholder="Auto-generated"
                           value="<?php echo htmlspecialchars($_POST['slug'] ?? $editCategory['slug'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="2"><?php echo htmlspecialchars($_POST['description'] ?? $editCategory['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="color" class="form-label">Color</label>
                        <input type="color" id="color" name="color" class="form-control form-color" 
                               value="<?php echo htmlspecialchars($_POST['color'] ?? $editCategory['color'] ?? '#3B82F6'); ?>">
                    </div>
                    
                    <div class="form-group" style="flex: 1;">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-control" min="0"
                               value="<?php echo htmlspecialchars($_POST['sort_order'] ?? $editCategory['sort_order'] ?? '0'); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" <?php echo (($_POST['is_active'] ?? $editCategory['is_active'] ?? 1) ? 'checked' : ''); ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span style="margin-left: 0.5rem;">Active</span>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $editCategory ? 'Update Category' : 'Add Category'; ?>
                    </button>
                    <?php if ($editCategory): ?>
                        <a href="blog-categories.php" class="btn btn-secondary">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Categories List -->
    <div class="card list-card">
        <div class="card-header">
            <h3 class="card-title">All Categories</h3>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Slug</th>
                        <th>Blogs</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($categories->num_rows > 0): ?>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="category-info">
                                        <span class="color-dot" style="background: <?php echo $cat['color']; ?>"></span>
                                        <strong><?php echo htmlspecialchars($cat['name']); ?></strong>
                                    </div>
                                </td>
                                <td><code><?php echo htmlspecialchars($cat['slug']); ?></code></td>
                                <td><?php echo $cat['blog_count']; ?></td>
                                <td><?php echo $cat['sort_order']; ?></td>
                                <td>
                                    <?php if ($cat['is_active']): ?>
                                        <span class="status-badge status-active">Active</span>
                                    <?php else: ?>
                                        <span class="status-badge status-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="?edit=<?php echo $cat['id']; ?>" class="btn-icon" title="Edit">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>
                                        <?php if ($cat['blog_count'] == 0): ?>
                                            <a href="?delete=<?php echo $cat['id']; ?>&token=<?php echo generateCsrfToken(); ?>" 
                                               class="btn-icon btn-icon-danger" title="Delete"
                                               onclick="return confirm('Delete this category?');">
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
                            <td colspan="6" class="text-center" style="padding: 2rem;">
                                No categories found. Add your first category!
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.categories-layout {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 1.5rem;
    align-items: start;
}

.form-row {
    display: flex;
    gap: 1rem;
}

.form-color {
    width: 60px;
    height: 40px;
    padding: 4px;
    cursor: pointer;
}

.category-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.color-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
}

code {
    background: #F1F5F9;
    padding: 0.125rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.8rem;
}

.btn-icon-danger {
    color: #EF4444 !important;
}

.btn-icon-danger:hover {
    background: #FEE2E2 !important;
}

.text-center {
    text-align: center;
    color: #64748B;
}

@media (max-width: 1024px) {
    .categories-layout {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
