<?php
/**
 * Admin Pulse Check Submission View
 * Individual submission detail view with print functionality
 */

$pageTitle = "View Submission";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$admin = getCurrentAdmin();
$isSuperAdmin = hasAdminRole('super_admin');

// Get submission ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$id) {
    header('Location: pulse-checks.php');
    exit;
}

// Handle notes update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_notes'])) {
    $notes = trim($_POST['admin_notes']);
    $stmt = $conn->prepare("UPDATE pulse_check_submissions SET admin_notes = ? WHERE id = ?");
    $stmt->bind_param("si", $notes, $id);

    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'update_notes', 'pulse_check_submissions', $id, 'Updated admin notes');
        $successMessage = "Notes updated successfully.";
    }
    $stmt->close();
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $newStatus = $_POST['status'];
    $allowedStatuses = ['new', 'contacted', 'scheduled', 'completed', 'declined'];

    if (in_array($newStatus, $allowedStatuses)) {
        $stmt = $conn->prepare("UPDATE pulse_check_submissions SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $id);

        if ($stmt->execute()) {
            logAdminActivity($_SESSION['admin_id'], 'update_status', 'pulse_check_submissions', $id, "Updated status to: $newStatus");
            $successMessage = "Status updated successfully.";
        }
        $stmt->close();
    }
}

// Handle scheduling update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_scheduling'])) {
    $reschedule = $_POST['reschedule_date'] ?: null;
    $reminder = $_POST['reminder_date'] ?: null;
    $stmt = $conn->prepare("UPDATE pulse_check_submissions SET reschedule_date = ?, reminder_date = ?, reminder_sent = 0 WHERE id = ?");
    $stmt->bind_param("ssi", $reschedule, $reminder, $id);

    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'update_scheduling', 'pulse_check_submissions', $id, 'Updated call schedule and reminders');
        $successMessage = "Scheduling updated successfully.";
    }
    $stmt->close();
}

// Fetch submission
$stmt = $conn->prepare("SELECT * FROM pulse_check_submissions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$submission = $result->fetch_assoc();
$stmt->close();

if (!$submission) {
    header('Location: pulse-checks.php');
    exit;
}

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<style>
    /* Print Styles */
    @media print {

        .admin-sidebar,
        .admin-header,
        .no-print,
        .page-actions,
        .admin-notes-section {
            display: none !important;
        }

        .admin-main {
            margin-left: 0 !important;
            padding: 0 !important;
        }

        .admin-content {
            padding: 0 !important;
        }

        .detail-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            break-inside: avoid;
        }

        .print-header {
            display: block !important;
        }

        body {
            background: white !important;
        }
    }

    .print-header {
        display: none;
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #E99431;
    }

    .print-header h1 {
        font-size: 1.5rem;
        color: #1E293B;
        margin-bottom: 0.25rem;
    }

    .print-header p {
        color: #64748B;
        font-size: 0.9rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    .detail-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .detail-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #E2E8F0;
    }

    .detail-card-header svg {
        width: 20px;
        height: 20px;
        color: #E99431;
    }

    .detail-card-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1E293B;
        margin: 0;
    }

    .detail-row {
        display: flex;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F1F5F9;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        width: 40%;
        font-size: 0.9rem;
        color: #64748B;
        font-weight: 500;
    }

    .detail-value {
        width: 60%;
        font-size: 0.9rem;
        color: #1E293B;
    }

    .detail-value.highlight {
        font-weight: 600;
        color: #E99431;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border-radius: 100px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-new {
        background: #DBEAFE;
        color: #1D4ED8;
    }

    .status-contacted {
        background: #FEF3C7;
        color: #D97706;
    }

    .status-scheduled {
        background: #D1FAE5;
        color: #059669;
    }

    .status-completed {
        background: #E0E7FF;
        color: #4338CA;
    }

    .status-declined {
        background: #FEE2E2;
        color: #DC2626;
    }

    .interest-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .interest-tag {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        background: #F1F5F9;
        border-radius: 6px;
        font-size: 0.8rem;
        color: #475569;
    }

    .text-block {
        background: #F8FAFC;
        border-radius: 8px;
        padding: 1rem;
        font-size: 0.9rem;
        line-height: 1.6;
        color: #475569;
        white-space: pre-wrap;
    }

    .sidebar-card {
        position: sticky;
        top: 1rem;
    }

    .status-form select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }

    .notes-textarea {
        width: 100%;
        min-height: 150px;
        padding: 1rem;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        font-size: 0.9rem;
        resize: vertical;
        margin-bottom: 0.75rem;
    }

    .notes-textarea:focus {
        outline: none;
        border-color: #E99431;
    }

    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1rem;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        background: white;
        color: #475569;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .quick-action-btn:hover {
        border-color: #E99431;
        color: #E99431;
    }

    .quick-action-btn svg {
        width: 18px;
        height: 18px;
    }

    .meta-info {
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #E2E8F0;
        font-size: 0.8rem;
        color: #94A3B8;
    }

    .meta-info p {
        margin: 0.25rem 0;
    }

    @media (max-width: 1024px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .sidebar-card {
            position: static;
        }
    }
</style>

<div class="print-header">
    <h1>Pulse Check Submission #<?php echo $submission['id']; ?></h1>
    <p><?php echo htmlspecialchars($submission['company_name']); ?> -
        <?php echo date('F j, Y', strtotime($submission['created_at'])); ?>
    </p>
</div>

<div class="page-header no-print">
    <div class="page-title-section">
        <a href="pulse-checks.php"
            style="display: inline-flex; align-items: center; gap: 0.5rem; color: #64748B; text-decoration: none; font-size: 0.9rem; margin-bottom: 0.5rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                style="width: 16px; height: 16px;">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Back to Submissions
        </a>
        <h1 class="page-title">Submission #<?php echo $submission['id']; ?></h1>
        <p class="page-subtitle">
            <span
                class="status-badge status-<?php echo $submission['status']; ?>"><?php echo ucfirst($submission['status']); ?></span>
            &nbsp;•&nbsp; Submitted on <?php echo date('F j, Y \a\t g:i A', strtotime($submission['created_at'])); ?>
        </p>
    </div>
    <div class="page-actions">
        <a href="pulse-check-edit.php?id=<?php echo $submission['id']; ?>" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
            Edit Submission
        </a>
        <button onclick="window.print();" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 6 2 18 2 18 9" />
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                <rect x="6" y="14" width="12" height="8" />
            </svg>
            Print
        </button>
    </div>
</div>

<?php if (isset($successMessage)): ?>
    <div class="alert alert-success no-print" data-auto-hide>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        <span><?php echo htmlspecialchars($successMessage); ?></span>
    </div>
<?php endif; ?>

<div class="detail-grid">
    <!-- Main Content -->
    <div class="detail-main">
        <!-- Company Information -->
        <div class="detail-card">
            <div class="detail-card-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 21h18" />
                    <path d="M9 8h6" />
                    <path d="M9 12h6" />
                    <path d="M9 16h6" />
                    <path d="M5 21V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v14" />
                </svg>
                <h3>Company Information</h3>
            </div>
            <div class="detail-row">
                <div class="detail-label">Company Name</div>
                <div class="detail-value highlight"><?php echo htmlspecialchars($submission['company_name']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Industry</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars(ucfirst($submission['industry'] ?: 'Not specified')); ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Website</div>
                <div class="detail-value">
                    <?php if ($submission['website']): ?>
                        <a href="<?php echo htmlspecialchars($submission['website']); ?>" target="_blank"
                            style="color: #3B82F6;">
                            <?php echo htmlspecialchars($submission['website']); ?>
                        </a>
                    <?php else: ?>
                        Not provided
                    <?php endif; ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Facility Address</div>
                <div class="detail-value">
                    <?php
                    $address = [];
                    if ($submission['facility_address'])
                        $address[] = $submission['facility_address'];
                    if ($submission['facility_city'])
                        $address[] = $submission['facility_city'];
                    if ($submission['facility_state'])
                        $address[] = $submission['facility_state'];
                    if ($submission['facility_country'])
                        $address[] = $submission['facility_country'];
                    echo htmlspecialchars(implode(', ', $address) ?: 'Not provided');
                    ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Facility Size</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars($submission['facility_size'] ? $submission['facility_size'] . ' sq. ft.' : 'Not specified'); ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Employees</div>
                <div class="detail-value"><?php echo htmlspecialchars($submission['employees'] ?: 'Not specified'); ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Annual Revenue</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars($submission['annual_revenue'] ?: 'Not specified'); ?>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="detail-card">
            <div class="detail-card-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <h3>Contact Information</h3>
            </div>
            <div class="detail-row">
                <div class="detail-label">Name</div>
                <div class="detail-value highlight">
                    <?php echo htmlspecialchars($submission['first_name'] . ' ' . $submission['last_name']); ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Designation</div>
                <div class="detail-value"><?php echo htmlspecialchars($submission['designation'] ?: 'Not provided'); ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div class="detail-value">
                    <a href="mailto:<?php echo htmlspecialchars($submission['email']); ?>" style="color: #3B82F6;">
                        <?php echo htmlspecialchars($submission['email']); ?>
                    </a>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Phone</div>
                <div class="detail-value">
                    <a href="tel:<?php echo htmlspecialchars($submission['phone']); ?>" style="color: #3B82F6;">
                        <?php echo htmlspecialchars($submission['phone']); ?>
                    </a>
                </div>
            </div>
            <?php if ($submission['alt_phone']): ?>
                <div class="detail-row">
                    <div class="detail-label">Alternate Phone</div>
                    <div class="detail-value">
                        <a href="tel:<?php echo htmlspecialchars($submission['alt_phone']); ?>" style="color: #3B82F6;">
                            <?php echo htmlspecialchars($submission['alt_phone']); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="detail-row">
                <div class="detail-label">Preferred Contact</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars(ucfirst($submission['preferred_contact'] ?: 'Not specified')); ?>
                </div>
            </div>
        </div>

        <!-- Project Information -->
        <div class="detail-card">
            <div class="detail-card-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 2 7 12 12 22 7 12 2" />
                    <polyline points="2 17 12 22 22 17" />
                    <polyline points="2 12 12 17 22 12" />
                </svg>
                <h3>Project Information</h3>
            </div>
            <div class="detail-row">
                <div class="detail-label">Project Type</div>
                <div class="detail-value highlight">
                    <?php echo htmlspecialchars(ucfirst(str_replace('-', ' ', $submission['project_type'] ?: 'Not specified'))); ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Timeline</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars(ucfirst(str_replace('-', ' ', $submission['timeline'] ?: 'Not specified'))); ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Referral Source</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars(ucfirst($submission['referral'] ?: 'Not specified')); ?>
                </div>
            </div>

            <?php if ($submission['interests']): ?>
                <div class="detail-row" style="flex-direction: column;">
                    <div class="detail-label" style="width: 100%; margin-bottom: 0.75rem;">Areas of Interest</div>
                    <div class="interest-tags">
                        <?php
                        $interests = explode(', ', $submission['interests']);
                        foreach ($interests as $interest):
                            $interest = trim($interest);
                            if ($interest):
                                ?>
                                <span
                                    class="interest-tag"><?php echo htmlspecialchars(ucfirst(str_replace('-', ' ', $interest))); ?></span>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($submission['current_challenges']): ?>
                <div style="margin-top: 1.5rem;">
                    <div class="detail-label" style="margin-bottom: 0.75rem; width: 100%;">Current Challenges</div>
                    <div class="text-block"><?php echo htmlspecialchars($submission['current_challenges']); ?></div>
                </div>
            <?php endif; ?>

            <?php if ($submission['project_goals']): ?>
                <div style="margin-top: 1.5rem;">
                    <div class="detail-label" style="margin-bottom: 0.75rem; width: 100%;">Project Goals & Expectations
                    </div>
                    <div class="text-block"><?php echo htmlspecialchars($submission['project_goals']); ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="detail-sidebar no-print">
        <div class="detail-card sidebar-card admin-notes-section">
            <div class="detail-card-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                <h3>Status & Notes</h3>
            </div>

            <!-- Status Update -->
            <form method="POST" class="status-form">
                <input type="hidden" name="update_status" value="1">
                <label style="font-size: 0.85rem; color: #64748B; display: block; margin-bottom: 0.5rem;">Update
                    Status</label>
                <select name="status" onchange="this.form.submit()">
                    <option value="new" <?php echo $submission['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                    <option value="contacted" <?php echo $submission['status'] === 'contacted' ? 'selected' : ''; ?>>
                        Contacted</option>
                    <option value="scheduled" <?php echo $submission['status'] === 'scheduled' ? 'selected' : ''; ?>>
                        Scheduled</option>
                    <option value="completed" <?php echo $submission['status'] === 'completed' ? 'selected' : ''; ?>>
                        Completed</option>
                    <option value="declined" <?php echo $submission['status'] === 'declined' ? 'selected' : ''; ?>>
                        Declined</option>
                </select>
            </form>

            <!-- Admin Notes -->
            <form method="POST" style="margin-top: 1.25rem;">
                <label style="font-size: 0.85rem; color: #64748B; display: block; margin-bottom: 0.5rem;">Internal
                    Notes</label>
                <textarea name="admin_notes" class="notes-textarea"
                    placeholder="Add notes about this lead..."><?php echo htmlspecialchars($submission['admin_notes'] ?? ''); ?></textarea>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Save Notes</button>
            </form>

            <!-- Quick Actions -->
            <div style="margin-top: 1.5rem;">
                <label style="font-size: 0.85rem; color: #64748B; display: block; margin-bottom: 0.75rem;">Quick
                    Actions</label>
                <div class="quick-actions">
                    <a href="mailto:<?php echo htmlspecialchars($submission['email']); ?>?subject=Re: Pulse Check Request - <?php echo urlencode($submission['company_name']); ?>"
                        class="quick-action-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        Send Email
                    </a>
                    <a href="tel:<?php echo htmlspecialchars($submission['phone']); ?>" class="quick-action-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        Call Contact
                    </a>
                    <?php if ($submission['website']): ?>
                        <a href="<?php echo htmlspecialchars($submission['website']); ?>" target="_blank"
                            class="quick-action-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="2" y1="12" x2="22" y2="12" />
                                <path
                                    d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                            </svg>
                            Visit Website
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Scheduling & Reminders -->
            <form method="POST" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #E2E8F0;">
                <input type="hidden" name="update_scheduling" value="1">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#E99431" stroke-width="2"
                        style="width: 18px; height: 18px;">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <h3 style="font-size: 1rem; color: #1E293B; margin: 0;">Call Schedule</h3>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.85rem; color: #64748B; display: block; margin-bottom: 0.5rem;">Reschedule
                        Call To</label>
                    <input type="datetime-local" name="reschedule_date"
                        value="<?php echo $submission['reschedule_date'] ? date('Y-m-d\TH:i', strtotime($submission['reschedule_date'])) : ''; ?>"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 0.9rem;">
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label style="font-size: 0.85rem; color: #64748B; display: block; margin-bottom: 0.5rem;">Reminder
                        Alert Time</label>
                    <input type="datetime-local" name="reminder_date"
                        value="<?php echo $submission['reminder_date'] ? date('Y-m-d\TH:i', strtotime($submission['reminder_date'])) : ''; ?>"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 0.9rem;">
                    <?php if ($submission['reminder_date']): ?>
                        <p
                            style="font-size: 0.75rem; color: <?php echo $submission['reminder_sent'] ? '#10B981' : '#64748B'; ?>; margin-top: 0.5rem;">
                            Status: <?php echo $submission['reminder_sent'] ? 'Alert displayed' : 'Scheduled'; ?>
                        </p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-secondary" style="width: 100%;">Set Schedule</button>
            </form>

            <!-- Meta Information -->
            <div class="meta-info">
                <p><strong>Submitted:</strong> <?php echo date('M j, Y g:i A', strtotime($submission['created_at'])); ?>
                </p>
                <?php if ($submission['updated_at'] !== $submission['created_at']): ?>
                    <p><strong>Last Updated:</strong>
                        <?php echo date('M j, Y g:i A', strtotime($submission['updated_at'])); ?></p>
                <?php endif; ?>
                <p><strong>IP Address:</strong>
                    <?php echo htmlspecialchars($submission['ip_address'] ?: 'Not captured'); ?></p>
            </div>
        </div>

        <?php if ($isSuperAdmin): ?>
            <div class="detail-card" style="margin-top: 1rem; border-color: #FEE2E2;">
                <div class="detail-card-header" style="border-bottom-color: #FEE2E2;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                    </svg>
                    <h3 style="color: #DC2626;">Danger Zone</h3>
                </div>
                <p style="font-size: 0.85rem; color: #64748B; margin-bottom: 1rem;">Once deleted, this submission cannot be
                    recovered.</p>
                <a href="pulse-checks.php?delete=<?php echo $submission['id']; ?>" class="btn"
                    style="width: 100%; background: #DC2626; color: white; border: none;"
                    onclick="return confirm('Are you sure you want to delete this submission? This action cannot be undone.');">
                    Delete Submission
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>