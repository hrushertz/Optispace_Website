<?php
/**
 * Admin Inquiries List
 * Admins can view, Super Admins can delete
 */

$pageTitle = "General Inquiries";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$admin = getCurrentAdmin();
$isSuperAdmin = hasAdminRole('super_admin');

// Handle delete action (Super Admin only)
if (isset($_GET['delete']) && is_numeric($_GET['delete']) && $isSuperAdmin) {
    $deleteId = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM inquiry_submissions WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'delete', 'inquiry_submissions', $deleteId, 'Deleted inquiry submission');
        $successMessage = "Inquiry deleted successfully.";
    } else {
        $errorMessage = "Failed to delete inquiry.";
    }
    $stmt->close();
}

// Handle status update
if (isset($_POST['update_status']) && is_numeric($_POST['submission_id'])) {
    $submissionId = (int)$_POST['submission_id'];
    $newStatus = $_POST['status'];
    $allowedStatuses = ['new', 'read', 'replied', 'closed'];
    
    if (in_array($newStatus, $allowedStatuses)) {
        $stmt = $conn->prepare("UPDATE inquiry_submissions SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $submissionId);
        
        if ($stmt->execute()) {
            logAdminActivity($_SESSION['admin_id'], 'update_status', 'inquiry_submissions', $submissionId, "Updated status to: $newStatus");
            $successMessage = "Status updated successfully.";
        }
        $stmt->close();
    }
}

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 15;
$offset = ($page - 1) * $perPage;

// Filters
$statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query
$whereClause = "1=1";
$params = [];
$types = "";

if ($statusFilter) {
    $whereClause .= " AND status = ?";
    $params[] = $statusFilter;
    $types .= "s";
}

if ($searchQuery) {
    $whereClause .= " AND (name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
    $searchParam = "%{$searchQuery}%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= "ssss";
}

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM inquiry_submissions WHERE {$whereClause}";
$countStmt = $conn->prepare($countQuery);
if (!empty($params)) {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$totalRecords = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $perPage);
$countStmt->close();

// Get submissions
$query = "SELECT * FROM inquiry_submissions WHERE {$whereClause} ORDER BY created_at DESC LIMIT ?, ?";
$params[] = $offset;
$params[] = $perPage;
$types .= "ii";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$submissions = $stmt->get_result();
$stmt->close();

// Get counts by status
$statusCounts = [];
$countResult = $conn->query("SELECT status, COUNT(*) as cnt FROM inquiry_submissions GROUP BY status");
while ($row = $countResult->fetch_assoc()) {
    $statusCounts[$row['status']] = $row['cnt'];
}

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<style>
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.35rem 0.75rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}
.status-new { background: #DBEAFE; color: #1D4ED8; }
.status-read { background: #FEF3C7; color: #D97706; }
.status-replied { background: #D1FAE5; color: #059669; }
.status-closed { background: #E2E8F0; color: #475569; }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}
.stat-card {
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.2s;
}
.stat-card:hover {
    border-color: #CBD5E1;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
.stat-card.active {
    border-color: #E99431;
    background: rgba(233, 148, 49, 0.05);
    box-shadow: 0 2px 12px rgba(233, 148, 49, 0.15);
}
.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1E293B;
}
.stat-label {
    font-size: 0.85rem;
    color: #64748B;
    margin-top: 0.5rem;
    font-weight: 500;
}

.table {
    width: 100%;
    table-layout: auto;
    border-collapse: separate;
    border-spacing: 0;
}

.table thead th {
    background: #F8FAFC;
    color: #475569;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1.25rem 1.25rem;
    border-bottom: 2px solid #E2E8F0;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table th,
.table td {
    text-align: left;
}

.table th:first-child,
.table td:first-child {
    padding-left: 1.5rem;
}

.table th:last-child,
.table td:last-child {
    padding-right: 1.5rem;
}

.table th:nth-child(1) { width: 5%; }
.table th:nth-child(2) { width: 18%; }
.table th:nth-child(3) { width: 18%; }
.table th:nth-child(4) { width: 25%; }
.table th:nth-child(5) { width: 10%; }
.table th:nth-child(6) { width: 12%; }
.table th:nth-child(7) { width: 12%; }

.submission-row {
    cursor: pointer;
    transition: all 0.2s;
    border-bottom: 1px solid #F1F5F9;
}
.submission-row:hover {
    background: #F8FAFC;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}

.submission-row:last-child {
    border-bottom: none;
}

.submission-row td {
    padding: 1.5rem 1.25rem;
    vertical-align: middle;
}

.submission-row td:nth-child(4) {
    white-space: normal;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}
.contact-name {
    font-weight: 600;
    color: #1E293B;
    font-size: 0.95rem;
    line-height: 1.3;
}
.contact-phone {
    font-size: 0.8rem;
    color: #64748B;
    line-height: 1.3;
}

.email-info {
    font-size: 0.9rem;
    color: #3B82F6;
    text-decoration: none;
}
.email-info:hover {
    text-decoration: underline;
}

.subject-info {
    font-weight: 500;
    color: #1E293B;
    font-size: 0.9rem;
    margin-bottom: 0.35rem;
}

.message-preview {
    font-size: 0.85rem;
    color: #64748B;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.date-info {
    font-size: 0.875rem;
    color: #64748B;
    font-weight: 500;
}

.status-dropdown {
    padding: 0.5rem 0.75rem;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    font-size: 0.85rem;
    background: white;
    cursor: pointer;
    min-width: 100px;
    font-weight: 500;
    transition: all 0.2s;
}
.status-dropdown:hover {
    border-color: #CBD5E1;
    background: #F8FAFC;
}
.status-dropdown:focus {
    outline: none;
    border-color: #E99431;
    box-shadow: 0 0 0 3px rgba(233, 148, 49, 0.1);
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    transition: all 0.2s;
    color: #64748B;
}

.btn-icon:hover {
    background: #E99431;
    border-color: #E99431;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(233, 148, 49, 0.2);
}

.btn-icon.btn-icon-danger:hover {
    background: #EF4444;
    border-color: #EF4444;
    color: white;
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.2);
}

.btn-icon svg {
    width: 18px;
    height: 18px;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">General Inquiries</h1>
        <p class="page-subtitle">Manage contact form submissions</p>
    </div>
</div>

<?php if (isset($successMessage)): ?>
    <div class="alert alert-success" data-auto-hide>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span><?php echo htmlspecialchars($successMessage); ?></span>
    </div>
<?php endif; ?>

<?php if (isset($errorMessage)): ?>
    <div class="alert alert-danger" data-auto-hide>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span><?php echo htmlspecialchars($errorMessage); ?></span>
    </div>
<?php endif; ?>

<!-- Stats Grid -->
<div class="stats-grid">
    <a href="?status=" class="stat-card <?php echo $statusFilter === '' ? 'active' : ''; ?>" style="text-decoration: none;">
        <div class="stat-value"><?php echo $totalRecords; ?></div>
        <div class="stat-label">Total</div>
    </a>
    <a href="?status=new" class="stat-card <?php echo $statusFilter === 'new' ? 'active' : ''; ?>" style="text-decoration: none;">
        <div class="stat-value"><?php echo $statusCounts['new'] ?? 0; ?></div>
        <div class="stat-label">New</div>
    </a>
    <a href="?status=read" class="stat-card <?php echo $statusFilter === 'read' ? 'active' : ''; ?>" style="text-decoration: none;">
        <div class="stat-value"><?php echo $statusCounts['read'] ?? 0; ?></div>
        <div class="stat-label">Read</div>
    </a>
    <a href="?status=replied" class="stat-card <?php echo $statusFilter === 'replied' ? 'active' : ''; ?>" style="text-decoration: none;">
        <div class="stat-value"><?php echo $statusCounts['replied'] ?? 0; ?></div>
        <div class="stat-label">Replied</div>
    </a>
</div>

<!-- Search & Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form method="GET" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, subject, or message..." 
                       value="<?php echo htmlspecialchars($searchQuery); ?>">
            </div>
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($statusFilter); ?>">
            <button type="submit" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                Search
            </button>
            <?php if ($searchQuery || $statusFilter): ?>
                <a href="inquiries.php" class="btn btn-secondary">Clear Filters</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Submissions Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($submissions->num_rows > 0): ?>
                    <?php while ($row = $submissions->fetch_assoc()): ?>
                        <tr class="submission-row" onclick="window.location='inquiry-view.php?id=<?php echo $row['id']; ?>'">
                            <td>#<?php echo $row['id']; ?></td>
                            <td>
                                <div class="contact-info">
                                    <span class="contact-name"><?php echo htmlspecialchars($row['name']); ?></span>
                                    <?php if ($row['phone']): ?>
                                    <span class="contact-phone"><?php echo htmlspecialchars($row['phone']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="email-info" onclick="event.stopPropagation();">
                                    <?php echo htmlspecialchars($row['email']); ?>
                                </a>
                            </td>
                            <td>
                                <?php if ($row['subject']): ?>
                                <div class="subject-info"><?php echo htmlspecialchars($row['subject']); ?></div>
                                <?php endif; ?>
                                <div class="message-preview"><?php echo htmlspecialchars($row['message']); ?></div>
                            </td>
                            <td onclick="event.stopPropagation();">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="submission_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="update_status" value="1">
                                    <select name="status" class="status-dropdown" onchange="this.form.submit()">
                                        <option value="new" <?php echo $row['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                                        <option value="read" <?php echo $row['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                                        <option value="replied" <?php echo $row['status'] === 'replied' ? 'selected' : ''; ?>>Replied</option>
                                        <option value="closed" <?php echo $row['status'] === 'closed' ? 'selected' : ''; ?>>Closed</option>
                                    </select>
                                </form>
                            </td>
                            <td class="date-info"><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                            <td onclick="event.stopPropagation();">
                                <div class="action-buttons">
                                    <a href="inquiry-view.php?id=<?php echo $row['id']; ?>" class="btn-icon" title="View Details">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </a>
                                    <?php if ($isSuperAdmin): ?>
                                        <a href="?delete=<?php echo $row['id']; ?>" class="btn-icon btn-icon-danger" 
                                           onclick="return confirm('Are you sure you want to delete this inquiry? This action cannot be undone.');" title="Delete">
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
                        <td colspan="7" style="text-align: center; padding: 3rem;">
                            <div style="color: #64748B;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.5;">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <p style="margin: 0;">No inquiries found</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($totalPages > 1): ?>
        <div class="card-footer">
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&status=<?php echo urlencode($statusFilter); ?>&search=<?php echo urlencode($searchQuery); ?>" class="pagination-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                            <polyline points="15 18 9 12 15 6"/>
                        </svg>
                        Previous
                    </a>
                <?php endif; ?>
                
                <span class="pagination-info">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&status=<?php echo urlencode($statusFilter); ?>&search=<?php echo urlencode($searchQuery); ?>" class="pagination-btn">
                        Next
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
