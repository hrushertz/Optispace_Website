<?php
/**
 * Blogger - My Delete Requests
 */

$pageTitle = "Delete Requests";
require_once __DIR__ . '/includes/auth.php';
requireBloggerLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$blogger = getCurrentBlogger();

// Filter
$filter = $_GET['filter'] ?? 'all';
$whereClause = "WHERE dr.requested_by = ?";

if ($filter === 'pending') {
    $whereClause .= " AND dr.status = 'pending'";
} elseif ($filter === 'approved') {
    $whereClause .= " AND dr.status = 'approved'";
} elseif ($filter === 'declined') {
    $whereClause .= " AND dr.status = 'declined'";
}

// Fetch delete requests
$stmt = $conn->prepare("
    SELECT dr.*, b.title as blog_title, b.slug as blog_slug, 
           r.full_name as reviewer_name
    FROM blog_delete_requests dr
    LEFT JOIN blogs b ON dr.blog_id = b.id
    LEFT JOIN admin_users r ON dr.reviewed_by = r.id
    $whereClause
    ORDER BY dr.created_at DESC
");
$stmt->bind_param("i", $blogger['id']);
$stmt->execute();
$requests = $stmt->get_result();
$stmt->close();

// Count by status
$stmt = $conn->prepare("SELECT status, COUNT(*) as count FROM blog_delete_requests WHERE requested_by = ? GROUP BY status");
$stmt->bind_param("i", $blogger['id']);
$stmt->execute();
$countsResult = $stmt->get_result();
$counts = ['pending' => 0, 'approved' => 0, 'declined' => 0];
while ($row = $countsResult->fetch_assoc()) {
    $counts[$row['status']] = $row['count'];
}
$totalCount = array_sum($counts);
$stmt->close();

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Delete Requests</h1>
        <p class="page-subtitle">Track the status of your blog deletion requests</p>
    </div>
</div>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="?filter=all" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">
        All <span class="count"><?php echo $totalCount; ?></span>
    </a>
    <a href="?filter=pending" class="filter-tab <?php echo $filter === 'pending' ? 'active' : ''; ?>">
        Pending <span class="count"><?php echo $counts['pending']; ?></span>
    </a>
    <a href="?filter=approved" class="filter-tab <?php echo $filter === 'approved' ? 'active' : ''; ?>">
        Approved <span class="count"><?php echo $counts['approved']; ?></span>
    </a>
    <a href="?filter=declined" class="filter-tab <?php echo $filter === 'declined' ? 'active' : ''; ?>">
        Declined <span class="count"><?php echo $counts['declined']; ?></span>
    </a>
</div>

<?php if ($requests->num_rows > 0): ?>
    <div class="requests-list">
        <?php while ($request = $requests->fetch_assoc()): ?>
            <div class="request-card">
                <div class="request-header">
                    <div class="request-blog">
                        <h3><?php echo htmlspecialchars($request['blog_title'] ?? 'Blog Deleted'); ?></h3>
                        <span class="request-date">
                            Requested on <?php echo date('M j, Y \a\t g:i A', strtotime($request['created_at'])); ?>
                        </span>
                    </div>
                    <span class="status-badge status-<?php echo $request['status']; ?>">
                        <?php echo ucfirst($request['status']); ?>
                    </span>
                </div>
                
                <div class="request-body">
                    <div class="request-reason">
                        <span class="label">Your Reason:</span>
                        <p><?php echo nl2br(htmlspecialchars($request['reason'])); ?></p>
                    </div>
                    
                    <?php if ($request['status'] !== 'pending'): ?>
                        <div class="request-review">
                            <span class="label">Admin Response:</span>
                            <?php if ($request['review_notes']): ?>
                                <p><?php echo nl2br(htmlspecialchars($request['review_notes'])); ?></p>
                            <?php else: ?>
                                <p class="no-notes">No additional notes provided.</p>
                            <?php endif; ?>
                            <span class="reviewer">
                                Reviewed by <?php echo htmlspecialchars($request['reviewer_name'] ?? 'Admin'); ?> 
                                on <?php echo date('M j, Y', strtotime($request['reviewed_at'])); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($request['status'] === 'pending' && $request['blog_slug']): ?>
                    <div class="request-actions">
                        <a href="blog-edit.php?id=<?php echo $request['blog_id']; ?>" class="btn btn-secondary btn-sm">
                            Edit Blog Instead
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="9" y1="15" x2="15" y2="15"/>
        </svg>
        <h3>No Delete Requests</h3>
        <p>
            <?php if ($filter === 'pending'): ?>
                You don't have any pending delete requests.
            <?php elseif ($filter === 'approved'): ?>
                No approved delete requests yet.
            <?php elseif ($filter === 'declined'): ?>
                No declined delete requests.
            <?php else: ?>
                You haven't submitted any delete requests yet.
            <?php endif; ?>
        </p>
        <a href="blogs.php" class="btn btn-primary">View My Blogs</a>
    </div>
<?php endif; ?>

<style>
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid #E2E8F0;
    padding-bottom: 1rem;
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
}

.filter-tab:hover {
    background: #F1F5F9;
    color: #1E293B;
}

.filter-tab.active {
    background: #EFF6FF;
    color: #2563EB;
}

.filter-tab .count {
    background: #E2E8F0;
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
}

.filter-tab.active .count {
    background: #BFDBFE;
}

.requests-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.request-card {
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 0.75rem;
    overflow: hidden;
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1.25rem;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
}

.request-blog h3 {
    margin: 0 0 0.25rem 0;
    font-size: 1rem;
    font-weight: 600;
}

.request-date {
    font-size: 0.875rem;
    color: #64748B;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-pending {
    background: #FEF3C7;
    color: #92400E;
}

.status-approved {
    background: #D1FAE5;
    color: #065F46;
}

.status-declined {
    background: #FEE2E2;
    color: #991B1B;
}

.request-body {
    padding: 1.25rem;
}

.request-reason,
.request-review {
    margin-bottom: 1rem;
}

.request-review {
    background: #F8FAFC;
    padding: 1rem;
    border-radius: 0.5rem;
    border-left: 3px solid #3B82F6;
}

.request-body .label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.request-body p {
    margin: 0;
    color: #1E293B;
    line-height: 1.6;
}

.request-body .no-notes {
    color: #94A3B8;
    font-style: italic;
}

.reviewer {
    display: block;
    margin-top: 0.75rem;
    font-size: 0.875rem;
    color: #64748B;
}

.request-actions {
    padding: 1rem 1.25rem;
    border-top: 1px solid #E2E8F0;
    background: #F8FAFC;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
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
    margin-bottom: 1.5rem;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
