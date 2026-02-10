<?php
/**
 * Admin Inquiry View
 * Individual inquiry details with print functionality
 */

$pageTitle = "View Inquiry";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$admin = getCurrentAdmin();
$isSuperAdmin = hasAdminRole('super_admin');

// Get submission ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$id) {
    header("Location: inquiries.php");
    exit;
}

// Handle delete action (Super Admin only)
if (isset($_GET['delete']) && $isSuperAdmin) {
    $stmt = $conn->prepare("DELETE FROM inquiry_submissions WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'delete', 'inquiry_submissions', $id, 'Deleted inquiry submission');
        header("Location: inquiries.php?deleted=1");
        exit;
    }
    $stmt->close();
}

// Handle status update
if (isset($_POST['update_status'])) {
    $newStatus = $_POST['status'];
    $allowedStatuses = ['new', 'read', 'replied', 'closed'];

    if (in_array($newStatus, $allowedStatuses)) {
        $stmt = $conn->prepare("UPDATE inquiry_submissions SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $id);

        if ($stmt->execute()) {
            logAdminActivity($_SESSION['admin_id'], 'update_status', 'inquiry_submissions', $id, "Updated status to: $newStatus");
            $successMessage = "Status updated successfully.";
        }
        $stmt->close();
    }
}

// Handle admin notes update
if (isset($_POST['update_notes'])) {
    $notes = trim($_POST['admin_notes']);

    $stmt = $conn->prepare("UPDATE inquiry_submissions SET admin_notes = ? WHERE id = ?");
    $stmt->bind_param("si", $notes, $id);

    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'update_notes', 'inquiry_submissions', $id, 'Updated admin notes');
        $successMessage = "Notes updated successfully.";
    }
    $stmt->close();
}

// Handle scheduling update
if (isset($_POST['update_scheduling'])) {
    $reschedule = $_POST['reschedule_date'] ?: null;
    $reminder = $_POST['reminder_date'] ?: null;

    $stmt = $conn->prepare("UPDATE inquiry_submissions SET reschedule_date = ?, reminder_date = ?, reminder_sent = 0 WHERE id = ?");
    $stmt->bind_param("ssi", $reschedule, $reminder, $id);

    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'update_scheduling', 'inquiry_submissions', $id, 'Updated call schedule and reminders');
        $successMessage = "Scheduling updated successfully.";
    }
    $stmt->close();
}

// Get submission data
$stmt = $conn->prepare("SELECT * FROM inquiry_submissions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$submission = $result->fetch_assoc();
$stmt->close();

if (!$submission) {
    $conn->close();
    header("Location: inquiries.php");
    exit;
}

// Mark as read if new
if ($submission['status'] === 'new') {
    $stmt = $conn->prepare("UPDATE inquiry_submissions SET status = 'read' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $submission['status'] = 'read';
}

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<style>
    @media print {

        .sidebar,
        .topbar,
        .page-header,
        .btn,
        .action-buttons,
        .danger-zone,
        form,
        .no-print {
            display: none !important;
        }

        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }

        .card {
            border: 1px solid #ccc !important;
            box-shadow: none !important;
            page-break-inside: avoid;
        }

        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #333;
        }

        body {
            background: white !important;
        }
    }

    .print-header {
        display: none;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-new {
        background: #DBEAFE;
        color: #1D4ED8;
    }

    .status-read {
        background: #FEF3C7;
        color: #D97706;
    }

    .status-replied {
        background: #D1FAE5;
        color: #059669;
    }

    .status-closed {
        background: #E2E8F0;
        color: #475569;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .detail-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        overflow: hidden;
    }

    .detail-card-header {
        background: #F8FAFC;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #E2E8F0;
        font-weight: 600;
        color: #1E293B;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .detail-card-header svg {
        width: 20px;
        height: 20px;
        color: #E99431;
    }

    .detail-card-body {
        padding: 1.5rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F1F5F9;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 0.875rem;
        color: #64748B;
        font-weight: 500;
    }

    .detail-value {
        font-size: 0.9rem;
        color: #1E293B;
        font-weight: 500;
        text-align: right;
        word-break: break-word;
    }

    .message-card {
        grid-column: 1 / -1;
    }

    .message-content {
        background: #F8FAFC;
        padding: 1.5rem;
        border-radius: 8px;
        line-height: 1.7;
        color: #334155;
        font-size: 0.95rem;
        white-space: pre-wrap;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .quick-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .quick-action svg {
        width: 18px;
        height: 18px;
    }

    .quick-action-email {
        background: #EFF6FF;
        color: #2563EB;
    }

    .quick-action-email:hover {
        background: #DBEAFE;
    }

    .quick-action-phone {
        background: #F0FDF4;
        color: #16A34A;
    }

    .quick-action-phone:hover {
        background: #DCFCE7;
    }

    .quick-action-print {
        background: #F8FAFC;
        color: #475569;
        border: 1px solid #E2E8F0;
    }

    .quick-action-print:hover {
        background: #F1F5F9;
        border-color: #CBD5E1;
    }

    .status-select {
        padding: 0.5rem 1rem;
        border: 2px solid #E2E8F0;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        background: white;
        cursor: pointer;
        min-width: 150px;
        transition: all 0.2s;
    }

    .status-select:hover {
        border-color: #CBD5E1;
    }

    .status-select:focus {
        outline: none;
        border-color: #E99431;
        box-shadow: 0 0 0 3px rgba(233, 148, 49, 0.1);
    }

    .notes-textarea {
        width: 100%;
        min-height: 120px;
        padding: 1rem;
        border: 2px solid #E2E8F0;
        border-radius: 8px;
        font-size: 0.9rem;
        line-height: 1.6;
        resize: vertical;
        transition: all 0.2s;
    }

    .notes-textarea:focus {
        outline: none;
        border-color: #E99431;
        box-shadow: 0 0 0 3px rgba(233, 148, 49, 0.1);
    }

    .danger-zone {
        margin-top: 2rem;
        padding: 1.5rem;
        background: #FEF2F2;
        border: 1px solid #FECACA;
        border-radius: 12px;
    }

    .danger-zone h4 {
        color: #DC2626;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .danger-zone p {
        color: #991B1B;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .btn-danger {
        background: #DC2626;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-danger:hover {
        background: #B91C1C;
    }

    .meta-info {
        background: #F8FAFC;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-size: 0.8rem;
        color: #64748B;
        margin-top: 1rem;
    }

    .meta-info span {
        margin-right: 2rem;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="print-header">
    <h1>Optispace - General Inquiry</h1>
    <p>Submission #<?php echo $submission['id']; ?> |
        <?php echo date('F j, Y g:i A', strtotime($submission['created_at'])); ?></p>
</div>

<div class="page-header">
    <div class="page-title-section">
        <nav style="margin-bottom: 0.5rem;">
            <a href="inquiries.php" style="color: #64748B; text-decoration: none; font-size: 0.875rem;">
                ← Back to Inquiries
            </a>
        </nav>
        <h1 class="page-title">Inquiry #<?php echo $submission['id']; ?></h1>
        <p class="page-subtitle">Submitted on
            <?php echo date('F j, Y \a\t g:i A', strtotime($submission['created_at'])); ?></p>
    </div>
    <div class="action-buttons no-print">
        <span
            class="status-badge status-<?php echo $submission['status']; ?>"><?php echo ucfirst($submission['status']); ?></span>
    </div>
</div>

<?php if (isset($successMessage)): ?>
    <div class="alert alert-success" data-auto-hide>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        <span><?php echo htmlspecialchars($successMessage); ?></span>
    </div>
<?php endif; ?>

<div class="detail-grid">
    <!-- Contact Information -->
    <div class="detail-card">
        <div class="detail-card-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
            Contact Information
        </div>
        <div class="detail-card-body">
            <div class="detail-row">
                <span class="detail-label">Name</span>
                <span class="detail-value"><?php echo htmlspecialchars($submission['name']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value">
                    <a href="mailto:<?php echo htmlspecialchars($submission['email']); ?>" style="color: #3B82F6;">
                        <?php echo htmlspecialchars($submission['email']); ?>
                    </a>
                </span>
            </div>
            <?php if ($submission['phone']): ?>
                <div class="detail-row">
                    <span class="detail-label">Phone</span>
                    <span class="detail-value">
                        <a href="tel:<?php echo htmlspecialchars($submission['phone']); ?>" style="color: #16A34A;">
                            <?php echo htmlspecialchars($submission['phone']); ?>
                        </a>
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Status & Actions -->
    <div class="detail-card no-print">
        <div class="detail-card-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
            </svg>
            Status & Actions
        </div>
        <div class="detail-card-body">
            <form method="POST" style="margin-bottom: 1.5rem;">
                <input type="hidden" name="update_status" value="1">
                <label
                    style="display: block; font-size: 0.875rem; color: #64748B; margin-bottom: 0.5rem; font-weight: 500;">Update
                    Status</label>
                <select name="status" class="status-select" onchange="this.form.submit()">
                    <option value="new" <?php echo $submission['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                    <option value="read" <?php echo $submission['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                    <option value="replied" <?php echo $submission['status'] === 'replied' ? 'selected' : ''; ?>>Replied
                    </option>
                    <option value="closed" <?php echo $submission['status'] === 'closed' ? 'selected' : ''; ?>>Closed
                    </option>
                </select>
            </form>

            <label
                style="display: block; font-size: 0.875rem; color: #64748B; margin-bottom: 0.5rem; font-weight: 500;">Quick
                Actions</label>
            <div class="action-buttons">
                <a href="mailto:<?php echo htmlspecialchars($submission['email']); ?>"
                    class="quick-action quick-action-email">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                    Send Email
                </a>
                <?php if ($submission['phone']): ?>
                    <a href="tel:<?php echo htmlspecialchars($submission['phone']); ?>"
                        class="quick-action quick-action-phone">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        Call
                    </a>
                <?php endif; ?>
                <button onclick="window.print()" class="quick-action quick-action-print">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 6 2 18 2 18 9" />
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                        <rect x="6" y="14" width="12" height="8" />
                    </svg>
                    Print
                </button>
            </div>
        </div>

        <!-- Call Scheduling & Reminders -->
        <div class="detail-card no-print" style="margin-top: 2rem;">
            <div class="detail-card-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                Call Scheduling & Reminders
            </div>
            <div class="detail-card-body">
                <form method="POST">
                    <input type="hidden" name="update_scheduling" value="1">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label
                                style="display: block; font-size: 0.875rem; color: #64748B; margin-bottom: 0.5rem; font-weight: 500;">Reschedule
                                Call To</label>
                            <input type="datetime-local" name="reschedule_date"
                                value="<?php echo $submission['reschedule_date'] ? date('Y-m-d\TH:i', strtotime($submission['reschedule_date'])) : ''; ?>"
                                class="notes-textarea" style="min-height: auto; padding: 0.75rem;">
                        </div>
                        <div>
                            <label
                                style="display: block; font-size: 0.875rem; color: #64748B; margin-bottom: 0.5rem; font-weight: 500;">Reminder
                                Alert Time</label>
                            <input type="datetime-local" name="reminder_date"
                                value="<?php echo $submission['reminder_date'] ? date('Y-m-d\TH:i', strtotime($submission['reminder_date'])) : ''; ?>"
                                class="notes-textarea" style="min-height: auto; padding: 0.75rem;">
                            <?php if ($submission['reminder_date']): ?>
                                <p
                                    style="font-size: 0.75rem; color: <?php echo $submission['reminder_sent'] ? '#16A34A' : '#64748B'; ?>; margin-top: 0.5rem;">
                                    Status: <?php echo $submission['reminder_sent'] ? 'Alert displayed' : 'Scheduled'; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Set Schedule</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Subject (if exists) -->
    <?php if ($submission['subject']): ?>
        <div class="detail-card" style="grid-column: 1 / -1;">
            <div class="detail-card-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                Subject
            </div>
            <div class="detail-card-body">
                <p style="margin: 0; font-weight: 600; color: #1E293B;">
                    <?php echo htmlspecialchars($submission['subject']); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Message -->
    <div class="detail-card message-card">
        <div class="detail-card-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path
                    d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
            </svg>
            Message
        </div>
        <div class="detail-card-body">
            <div class="message-content"><?php echo nl2br(htmlspecialchars($submission['message'])); ?></div>
        </div>
    </div>

    <!-- Admin Notes -->
    <div class="detail-card message-card no-print">
        <div class="detail-card-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="16" y1="13" x2="8" y2="13" />
                <line x1="16" y1="17" x2="8" y2="17" />
                <polyline points="10 9 9 9 8 9" />
            </svg>
            Admin Notes
        </div>
        <div class="detail-card-body">
            <form method="POST">
                <input type="hidden" name="update_notes" value="1">
                <textarea name="admin_notes" class="notes-textarea"
                    placeholder="Add internal notes about this inquiry..."><?php echo htmlspecialchars($submission['admin_notes'] ?? ''); ?></textarea>
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Save Notes</button>
            </form>
        </div>
    </div>
</div>

<!-- Meta Information -->
<div class="meta-info">
    <span><strong>IP Address:</strong> <?php echo htmlspecialchars($submission['ip_address'] ?? 'N/A'); ?></span>
    <span><strong>User Agent:</strong>
        <?php echo htmlspecialchars(substr($submission['user_agent'] ?? 'N/A', 0, 100)); ?>...</span>
</div>

<?php if ($isSuperAdmin): ?>
    <div class="danger-zone no-print">
        <h4>Danger Zone</h4>
        <p>Once you delete this inquiry, there is no going back. Please be certain.</p>
        <a href="?id=<?php echo $submission['id']; ?>&delete=1" class="btn-danger"
            onclick="return confirm('Are you absolutely sure you want to delete this inquiry? This action cannot be undone.');">
            Delete This Inquiry
        </a>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>