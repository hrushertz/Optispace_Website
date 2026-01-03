<?php
/**
 * Blogger - Request Blog Delete
 */

$pageTitle = "Request Delete";
require_once __DIR__ . '/includes/auth.php';
requireBloggerLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$blogger = getCurrentBlogger();

$blogId = (int)($_GET['id'] ?? 0);
$errors = [];
$success = false;

if ($blogId <= 0) {
    header('Location: blogs.php?error=invalid');
    exit;
}

// Fetch blog with ownership check
$stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ? AND author_id = ?");
$stmt->bind_param("ii", $blogId, $blogger['id']);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$blog) {
    header('Location: blogs.php?error=notfound');
    exit;
}

// Check if there's already a pending request
$stmt = $conn->prepare("SELECT * FROM blog_delete_requests WHERE blog_id = ? AND status = 'pending'");
$stmt->bind_param("i", $blogId);
$stmt->execute();
$existingRequest = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$existingRequest) {
    if (!verifyBloggerCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors['general'] = 'Invalid security token. Please try again.';
    } else {
        $reason = trim($_POST['reason'] ?? '');
        
        if (empty($reason)) {
            $errors['reason'] = 'Please provide a reason for deletion.';
        }
        
        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO blog_delete_requests (blog_id, requested_by, reason) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $blogId, $blogger['id'], $reason);
            
            if ($stmt->execute()) {
                logBloggerActivity($blogger['id'], 'delete_request', 'blogs', $blogId, 'Requested deletion: ' . $blog['title']);
                $success = true;
            } else {
                $errors['general'] = 'Failed to submit request. Please try again.';
            }
            $stmt->close();
        }
    }
}

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <nav class="breadcrumb">
            <a href="blogs.php">My Blogs</a>
            <span class="separator">/</span>
            <span>Request Delete</span>
        </nav>
        <h1 class="page-title">Request Blog Deletion</h1>
        <p class="page-subtitle">Submit a request to delete your blog</p>
    </div>
</div>

<?php if ($success): ?>
    <div class="alert alert-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span>Delete request submitted successfully! An admin will review your request.</span>
    </div>
    
    <div class="card" style="max-width: 600px;">
        <div class="card-body" style="text-align: center; padding: 2rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 64px; height: 64px; margin: 0 auto 1rem; color: #10B981;">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="9 12 12 15 16 10"/>
            </svg>
            <h3 style="margin-bottom: 0.5rem;">Request Submitted</h3>
            <p style="color: #64748B; margin-bottom: 1.5rem;">
                Your delete request for "<strong><?php echo htmlspecialchars($blog['title']); ?></strong>" has been submitted. 
                You'll be notified when an admin reviews your request.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="blogs.php" class="btn btn-primary">Back to My Blogs</a>
                <a href="delete-requests.php" class="btn btn-secondary">View My Requests</a>
            </div>
        </div>
    </div>

<?php elseif ($existingRequest): ?>
    <div class="alert alert-warning">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span>A delete request for this blog is already pending review.</span>
    </div>
    
    <div class="card" style="max-width: 600px;">
        <div class="card-header">
            <h3 class="card-title">Pending Request Details</h3>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <span class="detail-label">Blog:</span>
                <span class="detail-value"><?php echo htmlspecialchars($blog['title']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Requested:</span>
                <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($existingRequest['created_at'])); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Reason:</span>
                <span class="detail-value"><?php echo htmlspecialchars($existingRequest['reason']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="status-badge status-pending">Pending Review</span>
            </div>
            
            <div style="margin-top: 1.5rem;">
                <a href="blogs.php" class="btn btn-secondary">Back to My Blogs</a>
            </div>
        </div>
    </div>

<?php else: ?>
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
    
    <div class="card" style="max-width: 600px;">
        <div class="card-header" style="background: #FEF2F2; border-color: #FEE2E2;">
            <h3 class="card-title" style="color: #DC2626;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                Request Blog Deletion
            </h3>
        </div>
        <div class="card-body">
            <div class="blog-preview">
                <h4 style="margin: 0 0 0.5rem 0;"><?php echo htmlspecialchars($blog['title']); ?></h4>
                <p style="color: #64748B; font-size: 0.875rem; margin: 0;">
                    <?php echo $blog['is_published'] ? 'Published' : 'Draft'; ?>
                    • <?php echo number_format($blog['view_count']); ?> views
                    • Created <?php echo date('M j, Y', strtotime($blog['created_at'])); ?>
                </p>
            </div>
            
            <div class="warning-box">
                <p><strong>Important:</strong></p>
                <ul>
                    <li>You cannot delete blogs directly. This request will be reviewed by an admin.</li>
                    <li>If approved, the blog will be permanently deleted and cannot be recovered.</li>
                    <li>Please provide a clear reason for your delete request.</li>
                </ul>
            </div>
            
            <form method="POST">
                <?php echo bloggerCsrfField(); ?>
                
                <div class="form-group">
                    <label for="reason" class="form-label">
                        Reason for Deletion <span class="required">*</span>
                    </label>
                    <textarea id="reason" name="reason" class="form-control <?php echo isset($errors['reason']) ? 'error' : ''; ?>" 
                              rows="4" placeholder="Please explain why you want to delete this blog..." required><?php echo htmlspecialchars($_POST['reason'] ?? ''); ?></textarea>
                    <?php if (isset($errors['reason'])): ?>
                        <span class="form-error"><?php echo $errors['reason']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-actions" style="display: flex; gap: 1rem;">
                    <a href="blogs.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        Submit Delete Request
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

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
    color: #3B82F6;
}

.breadcrumb .separator {
    color: #CBD5E1;
}

.blog-preview {
    background: #F8FAFC;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #E2E8F0;
    margin-bottom: 1rem;
}

.warning-box {
    background: #FFFBEB;
    border: 1px solid #FED7AA;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
    color: #92400E;
}

.warning-box p {
    margin: 0 0 0.5rem 0;
}

.warning-box ul {
    margin: 0;
    padding-left: 1.25rem;
}

.warning-box li {
    margin-bottom: 0.25rem;
}

.detail-row {
    display: flex;
    padding: 0.75rem 0;
    border-bottom: 1px solid #E2E8F0;
}

.detail-row:last-of-type {
    border-bottom: none;
}

.detail-label {
    font-weight: 500;
    color: #64748B;
    width: 100px;
    flex-shrink: 0;
}

.detail-value {
    color: #1E293B;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-pending {
    background: #FEF3C7;
    color: #92400E;
}

.btn-danger {
    background: #DC2626;
    color: white;
    border: none;
}

.btn-danger:hover {
    background: #B91C1C;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
