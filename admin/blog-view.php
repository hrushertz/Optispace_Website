<?php
/**
 * Admin - View Blog Details
 */

$pageTitle = "View Blog";
require_once __DIR__ . '/includes/auth.php';
requireLogin();

if (!hasAdminRole('admin')) {
    header('Location: dashboard.php?error=unauthorized');
    exit;
}

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$admin = getCurrentAdmin();

$blogId = (int)($_GET['id'] ?? 0);

if ($blogId <= 0) {
    header('Location: blogs.php?error=invalid');
    exit;
}

// Fetch blog
$stmt = $conn->prepare("
    SELECT b.*, 
           c.name as category_name, c.color as category_color,
           a.full_name as author_name, a.email as author_email, a.role as author_role
    FROM blogs b
    LEFT JOIN blog_categories c ON b.category_id = c.id
    LEFT JOIN admin_users a ON b.author_id = a.id
    WHERE b.id = ?
");
$stmt->bind_param("i", $blogId);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$blog) {
    header('Location: blogs.php?error=notfound');
    exit;
}

// Check for pending delete requests
$stmt = $conn->prepare("SELECT * FROM blog_delete_requests WHERE blog_id = ? AND status = 'pending'");
$stmt->bind_param("i", $blogId);
$stmt->execute();
$deleteRequest = $stmt->get_result()->fetch_assoc();
$stmt->close();

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <nav class="breadcrumb">
            <a href="blogs.php">All Blogs</a>
            <span class="separator">/</span>
            <span>View Blog</span>
        </nav>
        <h1 class="page-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
        <p class="page-subtitle">Blog details and content preview</p>
    </div>
    
    <div class="page-actions">
        <?php if ($blog['is_published']): ?>
            <a href="/blog/article.php?slug=<?php echo urlencode($blog['slug']); ?>" class="btn btn-secondary" target="_blank">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                    <polyline points="15 3 21 3 21 9"/>
                    <line x1="10" y1="14" x2="21" y2="3"/>
                </svg>
                View Live
            </a>
        <?php endif; ?>
        <a href="blogs.php" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Blogs
        </a>
    </div>
</div>

<?php if ($deleteRequest): ?>
    <div class="alert alert-warning">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
        <span>
            This blog has a <strong>pending delete request</strong>. 
            <a href="blog-delete-requests.php">Review delete requests</a>
        </span>
    </div>
<?php endif; ?>

<div class="view-layout">
    <!-- Main Content -->
    <div class="view-main">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Blog Content</h3>
            </div>
            <div class="card-body">
                <?php if ($blog['excerpt']): ?>
                    <div class="content-section">
                        <label>Excerpt</label>
                        <p class="excerpt"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="content-section">
                    <label>Content</label>
                    <div class="content-preview">
                        <?php echo $blog['content']; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- SEO Info -->
        <?php if ($blog['meta_title'] || $blog['meta_description']): ?>
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">SEO Information</h3>
            </div>
            <div class="card-body">
                <?php if ($blog['meta_title']): ?>
                    <div class="content-section">
                        <label>Meta Title</label>
                        <p><?php echo htmlspecialchars($blog['meta_title']); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if ($blog['meta_description']): ?>
                    <div class="content-section">
                        <label>Meta Description</label>
                        <p><?php echo htmlspecialchars($blog['meta_description']); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Sidebar -->
    <div class="view-sidebar">
        <!-- Status Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Status</h3>
            </div>
            <div class="card-body">
                <div class="status-indicator <?php echo $blog['is_published'] ? 'published' : 'draft'; ?>">
                    <span class="status-dot"></span>
                    <?php echo $blog['is_published'] ? 'Published' : 'Draft'; ?>
                </div>
                
                <?php if ($blog['is_featured']): ?>
                    <div class="featured-indicator">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        Featured
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Details Card -->
        <div class="card" style="margin-top: 1rem;">
            <div class="card-header">
                <h3 class="card-title">Details</h3>
            </div>
            <div class="card-body">
                <div class="detail-row">
                    <span class="detail-label">Author</span>
                    <div class="detail-value">
                        <strong><?php echo htmlspecialchars($blog['author_name']); ?></strong>
                        <span class="role-badge"><?php echo ucfirst(str_replace('_', ' ', $blog['author_role'])); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Category</span>
                    <span class="detail-value">
                        <?php if ($blog['category_name']): ?>
                            <span class="category-badge" style="background: <?php echo $blog['category_color'] ?? '#64748B'; ?>20; color: <?php echo $blog['category_color'] ?? '#64748B'; ?>">
                                <?php echo htmlspecialchars($blog['category_name']); ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">Uncategorized</span>
                        <?php endif; ?>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">URL Slug</span>
                    <span class="detail-value code"><?php echo htmlspecialchars($blog['slug']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Read Time</span>
                    <span class="detail-value"><?php echo $blog['read_time']; ?> min</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Views</span>
                    <span class="detail-value"><?php echo number_format($blog['view_count']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Created</span>
                    <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($blog['created_at'])); ?></span>
                </div>
                
                <?php if ($blog['published_at']): ?>
                <div class="detail-row">
                    <span class="detail-label">Published</span>
                    <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($blog['published_at'])); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($blog['updated_at'] && $blog['updated_at'] !== $blog['created_at']): ?>
                <div class="detail-row">
                    <span class="detail-label">Updated</span>
                    <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($blog['updated_at'])); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card" style="margin-top: 1rem;">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <?php if ($blog['is_published']): ?>
                        <a href="blogs.php?toggle=unpublish&id=<?php echo $blog['id']; ?>&token=<?php echo generateCsrfToken(); ?>" 
                           class="btn btn-secondary" style="width: 100%;"
                           onclick="return confirm('Unpublish this blog?');">
                            Unpublish
                        </a>
                    <?php else: ?>
                        <a href="blogs.php?toggle=publish&id=<?php echo $blog['id']; ?>&token=<?php echo generateCsrfToken(); ?>" 
                           class="btn btn-primary" style="width: 100%;">
                            Publish
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($blog['is_featured']): ?>
                        <a href="blogs.php?feature=no&id=<?php echo $blog['id']; ?>&token=<?php echo generateCsrfToken(); ?>" 
                           class="btn btn-secondary" style="width: 100%;">
                            Remove from Featured
                        </a>
                    <?php else: ?>
                        <a href="blogs.php?feature=yes&id=<?php echo $blog['id']; ?>&token=<?php echo generateCsrfToken(); ?>" 
                           class="btn btn-secondary" style="width: 100%;">
                            Mark as Featured
                        </a>
                    <?php endif; ?>
                    
                    <?php if (hasAdminRole('super_admin')): ?>
                        <a href="blogs.php?delete=<?php echo $blog['id']; ?>&token=<?php echo generateCsrfToken(); ?>" 
                           class="btn btn-danger" style="width: 100%;"
                           onclick="return confirm('Are you sure you want to permanently delete this blog?');">
                            Delete Blog
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.breadcrumb a {
    color: #64748B;
    text-decoration: none;
}

.breadcrumb a:hover {
    color: #EA580C;
}

.breadcrumb .separator {
    color: #CBD5E1;
}

.view-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.5rem;
    align-items: start;
}

.content-section {
    margin-bottom: 1.5rem;
}

.content-section:last-child {
    margin-bottom: 0;
}

.content-section label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.excerpt {
    font-size: 1.125rem;
    color: #475569;
    line-height: 1.6;
    margin: 0;
}

.content-preview {
    background: #FAFAFA;
    border: 1px solid #E2E8F0;
    border-radius: 0.5rem;
    padding: 1.5rem;
    max-height: 500px;
    overflow-y: auto;
    line-height: 1.7;
}

.content-preview h1,
.content-preview h2,
.content-preview h3 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.content-preview h1:first-child,
.content-preview h2:first-child,
.content-preview h3:first-child {
    margin-top: 0;
}

.content-preview p {
    margin-bottom: 1rem;
}

.content-preview ul,
.content-preview ol {
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 1rem;
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.status-indicator.published .status-dot {
    background: #10B981;
}

.status-indicator.draft .status-dot {
    background: #94A3B8;
}

.featured-indicator {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    color: #F59E0B;
    font-weight: 500;
    margin-top: 0.75rem;
}

.featured-indicator svg {
    width: 16px;
    height: 16px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.625rem 0;
    border-bottom: 1px solid #E2E8F0;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-size: 0.875rem;
    color: #64748B;
}

.detail-value {
    font-size: 0.875rem;
    color: #1E293B;
    text-align: right;
}

.detail-value.code {
    font-family: 'Monaco', 'Menlo', monospace;
    font-size: 0.8rem;
    background: #F1F5F9;
    padding: 0.125rem 0.5rem;
    border-radius: 0.25rem;
}

.role-badge {
    display: block;
    font-size: 0.75rem;
    color: #64748B;
    font-weight: normal;
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

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.btn-danger {
    background: #DC2626;
    color: white;
    border: none;
}

.btn-danger:hover {
    background: #B91C1C;
}

@media (max-width: 1024px) {
    .view-layout {
        grid-template-columns: 1fr;
    }
    
    .view-sidebar {
        order: -1;
    }
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
