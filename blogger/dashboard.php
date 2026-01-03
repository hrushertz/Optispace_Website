<?php
/**
 * Blogger Dashboard
 */

$pageTitle = "Dashboard";
require_once __DIR__ . '/includes/auth.php';
requireBloggerLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$blogger = getCurrentBlogger();

// Get stats for this blogger
$statsStmt = $conn->prepare("
    SELECT 
        COUNT(*) as total_blogs,
        SUM(CASE WHEN is_published = 1 THEN 1 ELSE 0 END) as published_blogs,
        SUM(CASE WHEN is_published = 0 THEN 1 ELSE 0 END) as draft_blogs,
        SUM(view_count) as total_views
    FROM blogs 
    WHERE author_id = ?
");
$statsStmt->bind_param("i", $blogger['id']);
$statsStmt->execute();
$stats = $statsStmt->get_result()->fetch_assoc();
$statsStmt->close();

// Get pending delete requests
$deleteReqStmt = $conn->prepare("
    SELECT COUNT(*) as pending_requests 
    FROM blog_delete_requests 
    WHERE requested_by = ? AND status = 'pending'
");
$deleteReqStmt->bind_param("i", $blogger['id']);
$deleteReqStmt->execute();
$deleteReqs = $deleteReqStmt->get_result()->fetch_assoc();
$deleteReqStmt->close();

// Get recent blogs
$recentBlogsStmt = $conn->prepare("
    SELECT b.*, bc.name as category_name
    FROM blogs b
    LEFT JOIN blog_categories bc ON b.category_id = bc.id
    WHERE b.author_id = ?
    ORDER BY b.updated_at DESC
    LIMIT 5
");
$recentBlogsStmt->bind_param("i", $blogger['id']);
$recentBlogsStmt->execute();
$recentBlogs = $recentBlogsStmt->get_result();
$recentBlogsStmt->close();

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Welcome, <?php echo htmlspecialchars($blogger['full_name']); ?>!</h1>
        <p class="page-subtitle">Here's an overview of your blog activity</p>
    </div>
    <div class="page-actions">
        <a href="blog-add.php" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 20h9"/>
                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
            </svg>
            Write New Blog
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo (int)$stats['total_blogs']; ?></div>
            <div class="stat-label">Total Blogs</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo (int)$stats['published_blogs']; ?></div>
            <div class="stat-label">Published</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo (int)$stats['draft_blogs']; ?></div>
            <div class="stat-label">Drafts</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo number_format((int)$stats['total_views']); ?></div>
            <div class="stat-label">Total Views</div>
        </div>
    </div>
</div>

<?php if ($deleteReqs['pending_requests'] > 0): ?>
<div class="alert alert-warning" style="margin-top: 1.5rem;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
    </svg>
    <span>You have <?php echo $deleteReqs['pending_requests']; ?> pending delete request(s). <a href="delete-requests.php">View requests</a></span>
</div>
<?php endif; ?>

<!-- Recent Blogs -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Recent Blogs</h3>
        <a href="blogs.php" class="btn btn-secondary btn-sm">View All</a>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recentBlogs->num_rows > 0): ?>
                    <?php while ($blog = $recentBlogs->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="table-item-info">
                                    <h4><?php echo htmlspecialchars($blog['title']); ?></h4>
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
                            <td><?php echo date('M j, Y', strtotime($blog['updated_at'])); ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="blog-edit.php?id=<?php echo $blog['id']; ?>" 
                                       class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
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
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state" style="padding: 2rem;">
                                <p>You haven't written any blogs yet. <a href="blog-add.php">Write your first blog!</a></p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.stat-card {
    background: white;
    border: 1px solid var(--border-color, #E2E8F0);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon svg {
    width: 28px;
    height: 28px;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1E293B;
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #64748B;
    margin-top: 0.25rem;
}

@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

.alert-warning {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.3);
    color: #92400E;
}

.alert-warning a {
    color: #B45309;
    font-weight: 600;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
