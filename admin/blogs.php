<?php
/**
 * Admin - All Blogs Management
 * Super admins can view and manage all blogs
 */

$pageTitle = "All Blogs";
require_once __DIR__ . '/includes/auth.php';
requireLogin();

// Only admins and super_admins can access
if (!hasAdminRole('admin')) {
    header('Location: dashboard.php?error=unauthorized');
    exit;
}

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$admin = getCurrentAdmin();

// Handle status toggle
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    if (verifyCsrfToken($_GET['token'] ?? '')) {
        $blogId = (int)$_GET['id'];
        $action = $_GET['toggle'];
        
        if ($action === 'publish') {
            $stmt = $conn->prepare("UPDATE blogs SET is_published = 1, published_at = NOW() WHERE id = ?");
        } else {
            $stmt = $conn->prepare("UPDATE blogs SET is_published = 0 WHERE id = ?");
        }
        $stmt->bind_param("i", $blogId);
        $stmt->execute();
        $stmt->close();
        
        logAdminActivity($admin['id'], $action, 'blogs', $blogId, ucfirst($action) . 'd blog');
    }
    
    header('Location: blogs.php?success=' . $action);
    exit;
}

// Handle feature toggle
if (isset($_GET['feature']) && isset($_GET['id'])) {
    if (verifyCsrfToken($_GET['token'] ?? '')) {
        $blogId = (int)$_GET['id'];
        $featured = $_GET['feature'] === 'yes' ? 1 : 0;
        
        $stmt = $conn->prepare("UPDATE blogs SET is_featured = ? WHERE id = ?");
        $stmt->bind_param("ii", $featured, $blogId);
        $stmt->execute();
        $stmt->close();
        
        logAdminActivity($admin['id'], 'update', 'blogs', $blogId, ($featured ? 'Featured' : 'Unfeatured') . ' blog');
    }
    
    header('Location: blogs.php?success=updated');
    exit;
}

// Handle delete (admin only)
if (isset($_GET['delete']) && hasAdminRole('super_admin')) {
    if (verifyCsrfToken($_GET['token'] ?? '')) {
        $blogId = (int)$_GET['delete'];
        
        // Get blog title for log
        $stmt = $conn->prepare("SELECT title FROM blogs WHERE id = ?");
        $stmt->bind_param("i", $blogId);
        $stmt->execute();
        $blog = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if ($blog) {
            // Delete associated delete requests first
            $conn->query("DELETE FROM blog_delete_requests WHERE blog_id = " . $blogId);
            
            // Delete the blog
            $stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
            $stmt->bind_param("i", $blogId);
            $stmt->execute();
            $stmt->close();
            
            logAdminActivity($admin['id'], 'delete', 'blogs', $blogId, 'Deleted blog: ' . $blog['title']);
        }
    }
    
    header('Location: blogs.php?success=deleted');
    exit;
}

// Filters
$filter = $_GET['filter'] ?? 'all';
$authorFilter = isset($_GET['author']) ? (int)$_GET['author'] : 0;
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

$whereClause = "WHERE 1=1";
if ($filter === 'published') {
    $whereClause .= " AND b.is_published = 1";
} elseif ($filter === 'drafts') {
    $whereClause .= " AND b.is_published = 0";
} elseif ($filter === 'featured') {
    $whereClause .= " AND b.is_featured = 1";
}

if ($authorFilter > 0) {
    $whereClause .= " AND b.author_id = " . $authorFilter;
}

if ($categoryFilter > 0) {
    $whereClause .= " AND b.category_id = " . $categoryFilter;
}

// Fetch blogs
$blogs = $conn->query("
    SELECT b.*, c.name as category_name, c.color as category_color,
           a.full_name as author_name, a.role as author_role
    FROM blogs b
    LEFT JOIN blog_categories c ON b.category_id = c.id
    LEFT JOIN admin_users a ON b.author_id = a.id
    $whereClause
    ORDER BY b.created_at DESC
");

// Get counts
$counts = [
    'all' => $conn->query("SELECT COUNT(*) as cnt FROM blogs")->fetch_assoc()['cnt'],
    'published' => $conn->query("SELECT COUNT(*) as cnt FROM blogs WHERE is_published = 1")->fetch_assoc()['cnt'],
    'drafts' => $conn->query("SELECT COUNT(*) as cnt FROM blogs WHERE is_published = 0")->fetch_assoc()['cnt'],
    'featured' => $conn->query("SELECT COUNT(*) as cnt FROM blogs WHERE is_featured = 1")->fetch_assoc()['cnt']
];

// Get authors for filter
$authors = $conn->query("SELECT DISTINCT a.id, a.full_name FROM admin_users a INNER JOIN blogs b ON a.id = b.author_id ORDER BY a.full_name");

// Get categories for filter
$categories = $conn->query("SELECT * FROM blog_categories ORDER BY name");

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">All Blogs</h1>
        <p class="page-subtitle">Manage blog articles across all authors</p>
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
                case 'publish': echo 'Blog published successfully.'; break;
                case 'unpublish': echo 'Blog unpublished.'; break;
                case 'deleted': echo 'Blog deleted successfully.'; break;
                default: echo 'Action completed successfully.';
            }
            ?>
        </span>
    </div>
<?php endif; ?>

<!-- Filters -->
<div class="filters-bar">
    <div class="filter-tabs">
        <a href="?filter=all" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">
            All <span class="count"><?php echo $counts['all']; ?></span>
        </a>
        <a href="?filter=published" class="filter-tab <?php echo $filter === 'published' ? 'active' : ''; ?>">
            Published <span class="count"><?php echo $counts['published']; ?></span>
        </a>
        <a href="?filter=drafts" class="filter-tab <?php echo $filter === 'drafts' ? 'active' : ''; ?>">
            Drafts <span class="count"><?php echo $counts['drafts']; ?></span>
        </a>
        <a href="?filter=featured" class="filter-tab <?php echo $filter === 'featured' ? 'active' : ''; ?>">
            Featured <span class="count"><?php echo $counts['featured']; ?></span>
        </a>
    </div>
    
    <div class="filter-dropdowns">
        <select class="form-control form-select" onchange="applyFilter('author', this.value)">
            <option value="">All Authors</option>
            <?php while ($author = $authors->fetch_assoc()): ?>
                <option value="<?php echo $author['id']; ?>" <?php echo $authorFilter == $author['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($author['full_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <select class="form-control form-select" onchange="applyFilter('category', this.value)">
            <option value="">All Categories</option>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo $categoryFilter == $cat['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
</div>

<?php if ($blogs->num_rows > 0): ?>
<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Blog</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($blog = $blogs->fetch_assoc()): ?>
                <tr>
                    <td>
                        <div class="blog-title-cell">
                            <strong><?php echo htmlspecialchars($blog['title']); ?></strong>
                            <?php if ($blog['is_featured']): ?>
                                <span class="featured-badge" title="Featured">★</span>
                            <?php endif; ?>
                            <?php if ($blog['excerpt']): ?>
                                <p class="blog-excerpt"><?php echo htmlspecialchars(substr($blog['excerpt'], 0, 80)); ?>...</p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="author-cell">
                            <span class="author-name"><?php echo htmlspecialchars($blog['author_name']); ?></span>
                            <span class="author-role"><?php echo ucfirst(str_replace('_', ' ', $blog['author_role'])); ?></span>
                        </div>
                    </td>
                    <td>
                        <?php if ($blog['category_name']): ?>
                            <span class="category-badge" style="background: <?php echo $blog['category_color'] ?? '#64748B'; ?>20; color: <?php echo $blog['category_color'] ?? '#64748B'; ?>">
                                <?php echo htmlspecialchars($blog['category_name']); ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">Uncategorized</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($blog['is_published']): ?>
                            <span class="status-badge status-active">Published</span>
                        <?php else: ?>
                            <span class="status-badge status-inactive">Draft</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo number_format($blog['view_count']); ?></td>
                    <td>
                        <?php if ($blog['published_at']): ?>
                            <?php echo date('M j, Y', strtotime($blog['published_at'])); ?>
                        <?php else: ?>
                            <span class="text-muted"><?php echo date('M j, Y', strtotime($blog['created_at'])); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="blog-view.php?id=<?php echo $blog['id']; ?>" class="btn-icon" title="View Details">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                            
                            <?php if ($blog['is_published']): ?>
                                <a href="?toggle=unpublish&id=<?php echo $blog['id']; ?>&token=<?php echo generateCsrfToken(); ?>" 
                                   class="btn-icon" title="Unpublish"
                                   onclick="return confirm('Unpublish this blog?');">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                        <line x1="1" y1="1" x2="23" y2="23"/>
                                    </svg>
                                </a>
                            <?php else: ?>
                                <a href="?toggle=publish&id=<?php echo $blog['id']; ?>&token=<?php echo generateCsrfToken(); ?>" 
                                   class="btn-icon" title="Publish">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($blog['is_featured']): ?>
                                <a href="?feature=no&id=<?php echo $blog['id']; ?>&token=<?php echo generateCsrfToken(); ?>" 
                                   class="btn-icon" title="Remove from Featured" style="color: #F59E0B;">
                                    <svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                </a>
                            <?php else: ?>
                                <a href="?feature=yes&id=<?php echo $blog['id']; ?>&token=<?php echo generateCsrfToken(); ?>" 
                                   class="btn-icon" title="Add to Featured">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($blog['is_published']): ?>
                                <a href="/blog/article.php?slug=<?php echo urlencode($blog['slug']); ?>" 
                                   class="btn-icon" title="View Live" target="_blank">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                        <polyline points="15 3 21 3 21 9"/>
                                        <line x1="10" y1="14" x2="21" y2="3"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (hasAdminRole('super_admin')): ?>
                                <a href="?delete=<?php echo $blog['id']; ?>&token=<?php echo generateCsrfToken(); ?>" 
                                   class="btn-icon btn-icon-danger" title="Delete"
                                   onclick="return confirm('Are you sure you want to permanently delete this blog?');">
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
    </div>
</div>
<?php else: ?>
<div class="empty-state">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 19l7-7 3 3-7 7-3-3z"/>
        <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/>
    </svg>
    <h3>No Blogs Found</h3>
    <p>No blogs match your current filters.</p>
</div>
<?php endif; ?>

<style>
.filters-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.filter-tabs {
    display: flex;
    gap: 0.5rem;
}

.filter-tab {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    text-decoration: none;
    color: #64748B;
    font-weight: 500;
    transition: all 0.2s;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
}

.filter-tab:hover {
    background: #F1F5F9;
    color: #1E293B;
}

.filter-tab.active {
    background: #FFF7ED;
    color: #EA580C;
    border-color: #FDBA74;
}

.filter-tab .count {
    background: #E2E8F0;
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
}

.filter-tab.active .count {
    background: #FED7AA;
}

.filter-dropdowns {
    display: flex;
    gap: 0.75rem;
}

.filter-dropdowns .form-select {
    min-width: 150px;
}

.blog-title-cell strong {
    display: block;
    margin-bottom: 0.25rem;
}

.blog-excerpt {
    font-size: 0.875rem;
    color: #64748B;
    margin: 0;
}

.featured-badge {
    color: #F59E0B;
    margin-left: 0.25rem;
}

.author-cell {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-weight: 500;
}

.author-role {
    font-size: 0.75rem;
    color: #94A3B8;
}

.category-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.text-muted {
    color: #94A3B8;
}

.btn-icon-danger {
    color: #EF4444 !important;
}

.btn-icon-danger:hover {
    background: #FEE2E2 !important;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 0.75rem;
}

.empty-state svg {
    width: 64px;
    height: 64px;
    color: #CBD5E1;
    margin-bottom: 1rem;
}

.empty-state h3 {
    margin: 0 0 0.5rem 0;
    color: #1E293B;
}

.empty-state p {
    color: #64748B;
    margin: 0;
}
</style>

<script>
function applyFilter(type, value) {
    const url = new URL(window.location.href);
    if (value) {
        url.searchParams.set(type, value);
    } else {
        url.searchParams.delete(type);
    }
    window.location.href = url.toString();
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
