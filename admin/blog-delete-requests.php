<?php
/**
 * Admin - Blog Delete Requests
 * Review and process deletion requests from bloggers
 */

$pageTitle = "Delete Requests";
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

// Handle approval/decline
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $requestId = (int)($_POST['request_id'] ?? 0);
        $action = $_POST['action'] ?? '';
        $reviewNotes = trim($_POST['review_notes'] ?? '');
        
        if ($requestId > 0 && in_array($action, ['approve', 'decline'])) {
            $status = $action === 'approve' ? 'approved' : 'declined';
            
            // Get request details
            $stmt = $conn->prepare("SELECT dr.*, b.title as blog_title FROM blog_delete_requests dr LEFT JOIN blogs b ON dr.blog_id = b.id WHERE dr.id = ?");
            $stmt->bind_param("i", $requestId);
            $stmt->execute();
            $request = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            
            if ($request) {
                // Update request status
                $stmt = $conn->prepare("UPDATE blog_delete_requests SET status = ?, reviewed_by = ?, reviewed_at = NOW(), review_notes = ? WHERE id = ?");
                $stmt->bind_param("sisi", $status, $admin['id'], $reviewNotes, $requestId);
                $stmt->execute();
                $stmt->close();
                
                // If approved, delete the blog
                if ($action === 'approve') {
                    $conn->query("DELETE FROM blogs WHERE id = " . (int)$request['blog_id']);
                    logAdminActivity($admin['id'], 'delete', 'blogs', $request['blog_id'], 'Approved delete request for: ' . $request['blog_title']);
                } else {
                    logAdminActivity($admin['id'], 'decline', 'blog_delete_requests', $requestId, 'Declined delete request for: ' . $request['blog_title']);
                }
                
                header('Location: blog-delete-requests.php?success=' . $action . 'd');
                exit;
            }
        }
    }
}

// Filter
$filter = $_GET['filter'] ?? 'pending';
$whereClause = "WHERE 1=1";

if ($filter === 'pending') {
    $whereClause .= " AND dr.status = 'pending'";
} elseif ($filter === 'approved') {
    $whereClause .= " AND dr.status = 'approved'";
} elseif ($filter === 'declined') {
    $whereClause .= " AND dr.status = 'declined'";
}

// Fetch requests
$requests = $conn->query("
    SELECT dr.*, 
           b.title as blog_title, b.slug as blog_slug, b.is_published,
           req.full_name as requester_name, req.email as requester_email,
           rev.full_name as reviewer_name
    FROM blog_delete_requests dr
    LEFT JOIN blogs b ON dr.blog_id = b.id
    LEFT JOIN admin_users req ON dr.requested_by = req.id
    LEFT JOIN admin_users rev ON dr.reviewed_by = rev.id
    $whereClause
    ORDER BY dr.created_at DESC
");

// Get counts
$counts = [
    'pending' => $conn->query("SELECT COUNT(*) as cnt FROM blog_delete_requests WHERE status = 'pending'")->fetch_assoc()['cnt'],
    'approved' => $conn->query("SELECT COUNT(*) as cnt FROM blog_delete_requests WHERE status = 'approved'")->fetch_assoc()['cnt'],
    'declined' => $conn->query("SELECT COUNT(*) as cnt FROM blog_delete_requests WHERE status = 'declined'")->fetch_assoc()['cnt']
];
$counts['all'] = array_sum($counts);

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Delete Requests</h1>
        <p class="page-subtitle">Review and process blog deletion requests from editors</p>
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
                case 'approved': echo 'Delete request approved. Blog has been deleted.'; break;
                case 'declined': echo 'Delete request declined.'; break;
                default: echo 'Action completed successfully.';
            }
            ?>
        </span>
    </div>
<?php endif; ?>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="?filter=pending" class="filter-tab <?php echo $filter === 'pending' ? 'active' : ''; ?>">
        Pending <span class="count"><?php echo $counts['pending']; ?></span>
    </a>
    <a href="?filter=all" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">
        All <span class="count"><?php echo $counts['all']; ?></span>
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
            <div class="request-card <?php echo $request['status']; ?>">
                <div class="request-header">
                    <div class="request-info">
                        <h3>
                            <?php if ($request['blog_title']): ?>
                                <?php echo htmlspecialchars($request['blog_title']); ?>
                            <?php else: ?>
                                <span class="deleted-label">[Blog Deleted]</span>
                            <?php endif; ?>
                        </h3>
                        <div class="request-meta">
                            <span class="requester">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <?php echo htmlspecialchars($request['requester_name']); ?>
                            </span>
                            <span class="date">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                <?php echo date('M j, Y \a\t g:i A', strtotime($request['created_at'])); ?>
                            </span>
                        </div>
                    </div>
                    <span class="status-badge status-<?php echo $request['status']; ?>">
                        <?php echo ucfirst($request['status']); ?>
                    </span>
                </div>
                
                <div class="request-body">
                    <div class="reason-section">
                        <h4>Reason for Deletion</h4>
                        <p><?php echo nl2br(htmlspecialchars($request['reason'])); ?></p>
                    </div>
                    
                    <?php if ($request['status'] !== 'pending'): ?>
                        <div class="review-section">
                            <h4>Admin Response</h4>
                            <?php if ($request['review_notes']): ?>
                                <p><?php echo nl2br(htmlspecialchars($request['review_notes'])); ?></p>
                            <?php else: ?>
                                <p class="no-notes">No additional notes.</p>
                            <?php endif; ?>
                            <span class="reviewer-info">
                                <?php echo ucfirst($request['status']); ?> by <?php echo htmlspecialchars($request['reviewer_name'] ?? 'Admin'); ?> 
                                on <?php echo date('M j, Y', strtotime($request['reviewed_at'])); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($request['status'] === 'pending'): ?>
                    <div class="request-actions">
                        <div class="action-buttons-row">
                            <?php if ($request['blog_slug']): ?>
                                <a href="blog-view.php?id=<?php echo $request['blog_id']; ?>" class="btn btn-secondary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Review Blog
                                </a>
                            <?php endif; ?>
                            
                            <button type="button" class="btn btn-success" onclick="showReviewModal(<?php echo $request['id']; ?>, 'approve', '<?php echo htmlspecialchars($request['blog_title'], ENT_QUOTES); ?>')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Approve & Delete
                            </button>
                            
                            <button type="button" class="btn btn-danger" onclick="showReviewModal(<?php echo $request['id']; ?>, 'decline', '<?php echo htmlspecialchars($request['blog_title'], ENT_QUOTES); ?>')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                                Decline
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <h3>No Requests</h3>
        <p>
            <?php if ($filter === 'pending'): ?>
                There are no pending delete requests.
            <?php else: ?>
                No delete requests match your filter.
            <?php endif; ?>
        </p>
    </div>
<?php endif; ?>

<!-- Review Modal -->
<div class="modal-overlay" id="reviewModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Review Request</h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form method="POST" id="reviewForm">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
            <input type="hidden" name="request_id" id="modalRequestId">
            <input type="hidden" name="action" id="modalAction">
            
            <div class="modal-body">
                <p id="modalMessage"></p>
                
                <div class="form-group">
                    <label for="review_notes" class="form-label">Notes (Optional)</label>
                    <textarea id="review_notes" name="review_notes" class="form-control" rows="3"
                              placeholder="Add any notes for the blogger..."></textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn" id="modalSubmitBtn">Confirm</button>
            </div>
        </form>
    </div>
</div>

<style>
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
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

.request-card.pending {
    border-left: 4px solid #F59E0B;
}

.request-card.approved {
    border-left: 4px solid #10B981;
}

.request-card.declined {
    border-left: 4px solid #EF4444;
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1.25rem;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
}

.request-info h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.125rem;
}

.deleted-label {
    color: #94A3B8;
    font-style: italic;
}

.request-meta {
    display: flex;
    gap: 1.5rem;
    font-size: 0.875rem;
    color: #64748B;
}

.request-meta span {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.request-meta svg {
    width: 14px;
    height: 14px;
}

.status-badge {
    display: inline-block;
    padding: 0.375rem 0.875rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-pending { background: #FEF3C7; color: #92400E; }
.status-approved { background: #D1FAE5; color: #065F46; }
.status-declined { background: #FEE2E2; color: #991B1B; }

.request-body {
    padding: 1.25rem;
}

.reason-section,
.review-section {
    margin-bottom: 1rem;
}

.reason-section h4,
.review-section h4 {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0 0 0.5rem 0;
}

.reason-section p,
.review-section p {
    margin: 0;
    line-height: 1.6;
}

.review-section {
    background: #F1F5F9;
    padding: 1rem;
    border-radius: 0.5rem;
    border-left: 3px solid #3B82F6;
}

.no-notes {
    color: #94A3B8;
    font-style: italic;
}

.reviewer-info {
    display: block;
    margin-top: 0.75rem;
    font-size: 0.875rem;
    color: #64748B;
}

.request-actions {
    padding: 1rem 1.25rem;
    background: #F8FAFC;
    border-top: 1px solid #E2E8F0;
}

.action-buttons-row {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-success {
    background: #10B981;
    color: white;
    border: none;
}

.btn-success:hover {
    background: #059669;
}

.btn-danger {
    background: #EF4444;
    color: white;
    border: none;
}

.btn-danger:hover {
    background: #DC2626;
}

/* Modal */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 0.75rem;
    width: 100%;
    max-width: 480px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem;
    border-bottom: 1px solid #E2E8F0;
}

.modal-header h3 {
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #64748B;
    cursor: pointer;
}

.modal-body {
    padding: 1.25rem;
}

.modal-body p {
    margin: 0 0 1rem 0;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1.25rem;
    border-top: 1px solid #E2E8F0;
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
    color: #10B981;
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
function showReviewModal(requestId, action, blogTitle) {
    document.getElementById('modalRequestId').value = requestId;
    document.getElementById('modalAction').value = action;
    
    const modal = document.getElementById('reviewModal');
    const title = document.getElementById('modalTitle');
    const message = document.getElementById('modalMessage');
    const submitBtn = document.getElementById('modalSubmitBtn');
    
    if (action === 'approve') {
        title.textContent = 'Approve Delete Request';
        message.innerHTML = 'Are you sure you want to approve this request? The blog "<strong>' + blogTitle + '</strong>" will be permanently deleted.';
        submitBtn.textContent = 'Approve & Delete';
        submitBtn.className = 'btn btn-success';
    } else {
        title.textContent = 'Decline Delete Request';
        message.innerHTML = 'Decline the delete request for "<strong>' + blogTitle + '</strong>"? The blog will remain published.';
        submitBtn.textContent = 'Decline Request';
        submitBtn.className = 'btn btn-danger';
    }
    
    modal.classList.add('active');
}

function closeModal() {
    document.getElementById('reviewModal').classList.remove('active');
    document.getElementById('review_notes').value = '';
}

// Close modal on outside click
document.getElementById('reviewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
