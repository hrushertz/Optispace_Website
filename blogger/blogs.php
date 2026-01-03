<?php
/**
 * Blogger - My Blogs List
 */

$pageTitle = "My Blogs";
require_once __DIR__ . '/includes/auth.php';
requireBloggerLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$blogger = getCurrentBlogger();

$successMessage = '';
$errors = [];

// Handle toggle publish status
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $toggleId = (int)$_GET['toggle'];
    
    // Check ownership
    $checkStmt = $conn->prepare("SELECT id, is_published, title FROM blogs WHERE id = ? AND author_id = ?");
    $checkStmt->bind_param("ii", $toggleId, $blogger['id']);
    $checkStmt->execute();
    $blog = $checkStmt->get_result()->fetch_assoc();
    $checkStmt->close();
    
    if ($blog) {
        $newStatus = $blog['is_published'] ? 0 : 1;
        $updateStmt = $conn->prepare("UPDATE blogs SET is_published = ?, published_at = IF(? = 1 AND published_at IS NULL, NOW(), published_at) WHERE id = ?");
        $updateStmt->bind_param("iii", $newStatus, $newStatus, $toggleId);
        
        if ($updateStmt->execute()) {
            $action = $newStatus ? 'published' : 'unpublished';
            logBloggerActivity($blogger['id'], $action, 'blogs', $toggleId, ucfirst($action) . ' blog: ' . $blog['title']);
            $successMessage = "Blog " . $action . " successfully.";
        }
        $updateStmt->close();
    } else {
        $errors['general'] = "Blog not found or you don't have permission.";
    }
}

// Get filter
$filter = $_GET['filter'] ?? 'all';
$whereClause = "WHERE b.author_id = ?";
if ($filter === 'published') {
    $whereClause .= " AND b.is_published = 1";
} elseif ($filter === 'drafts') {
    $whereClause .= " AND b.is_published = 0";
}

// Get blogs
$blogsQuery = "
    SELECT b.*, bc.name as category_name, bc.slug as category_slug
    FROM blogs b
    LEFT JOIN blog_categories bc ON b.category_id = bc.id
    $whereClause
    ORDER BY b.updated_at DESC
";
$blogsStmt = $conn->prepare($blogsQuery);
$blogsStmt->bind_param("i", $blogger['id']);
$blogsStmt->execute();
$blogs = $blogsStmt->get_result();
$blogsStmt->close();

// Get categories for filter
$categories = $conn->query("SELECT * FROM blog_categories WHERE is_active = 1 ORDER BY sort_order, name");

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">My Blogs</h1>
        <p class="page-subtitle">Manage your blog articles</p>
    </div>
    <div class="page-actions">
        <a href="blog-add.php" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Write New Blog
        </a>
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

<!-- Filter Tabs -->
<div class="filter-tabs" style="margin-bottom: 1.5rem;">
    <a href="?filter=all" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">All</a>
    <a href="?filter=published" class="filter-tab <?php echo $filter === 'published' ? 'active' : ''; ?>">Published</a>
    <a href="?filter=drafts" class="filter-tab <?php echo $filter === 'drafts' ? 'active' : ''; ?>">Drafts</a>
</div>

<!-- Blogs List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Blogs</h3>
        <span class="badge badge-gray"><?php echo $blogs->num_rows; ?> blogs</span>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($blogs->num_rows > 0): ?>
                    <?php while ($blog = $blogs->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="table-item-info">
                                    <h4><?php echo htmlspecialchars($blog['title']); ?></h4>
                                    <p style="font-size: 0.8rem; color: #64748B;">
                                        <?php echo htmlspecialchars(substr($blog['excerpt'] ?? '', 0, 60)); ?>...
                                    </p>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-gray"><?php echo htmlspecialchars($blog['category_name'] ?? 'Uncategorized'); ?></span>
                            </td>
                            <td>
                                <?php if ($blog['is_published']): ?>
                                    <span class="badge badge-success">Published</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($blog['view_count']); ?></td>
                            <td>
                                <?php if ($blog['published_at']): ?>
                                    <?php echo date('M j, Y', strtotime($blog['published_at'])); ?>
                                <?php else: ?>
                                    <span style="color: #94a3b8;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="blog-edit.php?id=<?php echo $blog['id']; ?>" 
                                       class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>
                                    
                                    <a href="?toggle=<?php echo $blog['id']; ?>" 
                                       class="btn btn-secondary btn-sm btn-icon" 
                                       title="<?php echo $blog['is_published'] ? 'Unpublish' : 'Publish'; ?>">
                                        <?php if ($blog['is_published']): ?>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                                <line x1="1" y1="1" x2="23" y2="23"/>
                                            </svg>
                                        <?php else: ?>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        <?php endif; ?>
                                    </a>
                                    
                                    <?php if ($blog['is_published']): ?>
                                        <a href="../blog/article.php?slug=<?php echo $blog['slug']; ?>" 
                                           target="_blank" class="btn btn-secondary btn-sm btn-icon" title="View">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                                <polyline points="15 3 21 3 21 9"/>
                                                <line x1="10" y1="14" x2="21" y2="3"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="request-delete.php?id=<?php echo $blog['id']; ?>" 
                                       class="btn btn-danger btn-sm btn-icon" title="Request Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state" style="padding: 3rem;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="width: 64px; height: 64px; color: #cbd5e1; margin-bottom: 1rem;">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                                <p style="color: #64748b; margin-bottom: 1rem;">No blogs found.</p>
                                <a href="blog-add.php" class="btn btn-primary btn-sm">Write Your First Blog</a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.filter-tabs {
    display: flex;
    gap: 0.5rem;
}

.filter-tab {
    padding: 0.5rem 1rem;
    border: 1px solid #E2E8F0;
    background: white;
    border-radius: 100px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #64748B;
    text-decoration: none;
    transition: all 0.2s ease;
}

.filter-tab:hover {
    border-color: #3B82F6;
    color: #3B82F6;
}

.filter-tab.active {
    background: #3B82F6;
    border-color: #3B82F6;
    color: white;
}

.badge-warning {
    background: rgba(245, 158, 11, 0.1);
    color: #B45309;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
