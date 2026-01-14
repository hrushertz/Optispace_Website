<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Team Members';

// Get database connection
$conn = getDBConnection();

// Handle delete request
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['member_id'])) {
    $memberId = intval($_POST['member_id']);
    
    // Get photo path to delete file
    $stmt = $conn->prepare("SELECT photo_path FROM team_members WHERE id = ?");
    $stmt->bind_param("i", $memberId);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();
    $stmt->close();
    
    // Delete the member
    $stmt = $conn->prepare("DELETE FROM team_members WHERE id = ?");
    $stmt->bind_param("i", $memberId);
    if ($stmt->execute()) {
        // Delete photo file if exists
        if ($member && $member['photo_path'] && file_exists(__DIR__ . '/../' . $member['photo_path'])) {
            unlink(__DIR__ . '/../' . $member['photo_path']);
        }
        $_SESSION['success_message'] = 'Team member deleted successfully';
    } else {
        $_SESSION['error_message'] = 'Error deleting team member';
    }
    $stmt->close();
    header('Location: team-members.php');
    exit;
}

// Handle reorder
if (isset($_POST['action']) && $_POST['action'] === 'reorder' && isset($_POST['order'])) {
    $order = json_decode($_POST['order'], true);
    foreach ($order as $index => $id) {
        $stmt = $conn->prepare("UPDATE team_members SET sort_order = ? WHERE id = ?");
        $sortOrder = $index + 1;
        $stmt->bind_param("ii", $sortOrder, $id);
        $stmt->execute();
        $stmt->close();
    }
    echo json_encode(['success' => true]);
    exit;
}

// Handle toggle active status
if (isset($_POST['action']) && $_POST['action'] === 'toggle_active' && isset($_POST['member_id'])) {
    $memberId = intval($_POST['member_id']);
    $stmt = $conn->prepare("UPDATE team_members SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $memberId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $stmt->close();
    exit;
}

// Fetch all team members
$result = $conn->query("SELECT * FROM team_members ORDER BY sort_order ASC, id ASC");
$teamMembers = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $teamMembers[] = $row;
    }
}

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 0.5rem;">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Team Members
            </h1>
            <p class="page-subtitle">Manage team members displayed on the Team & Associates page</p>
        </div>
        <div class="page-actions">
            <a href="team-member-add.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add Team Member
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
    </div>
    <?php endif; ?>

    <div class="content-card">
        <?php if (empty($teamMembers)): ?>
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 64px; height: 64px; color: #94A3B8; margin-bottom: 1rem;">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <h3>No Team Members Yet</h3>
            <p>Start by adding your first team member to display on the Team & Associates page.</p>
            <a href="team-member-add.php" class="btn btn-primary" style="margin-top: 1rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add First Member
            </a>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="data-table sortable" id="teamMembersTable">
                <thead>
                    <tr>
                        <th width="40" class="drag-handle-col"></th>
                        <th width="80">Photo</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th width="80" class="text-center">Status</th>
                        <th width="120" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="sortable-members">
                    <?php foreach ($teamMembers as $member): ?>
                    <tr data-id="<?php echo $member['id']; ?>">
                        <td class="drag-handle">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="3" y1="12" x2="21" y2="12"/>
                                <line x1="3" y1="6" x2="21" y2="6"/>
                                <line x1="3" y1="18" x2="21" y2="18"/>
                            </svg>
                        </td>
                        <td>
                            <?php if ($member['photo_path']): ?>
                            <img src="<?php echo htmlspecialchars('../' . $member['photo_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($member['name']); ?>" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            <?php else: ?>
                            <div style="width: 50px; height: 50px; background: #F1F5F9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" style="width: 24px; height: 24px;">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo htmlspecialchars($member['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($member['role']); ?></td>
                        <td><?php echo htmlspecialchars($member['email'] ?: '—'); ?></td>
                        <td class="text-center">
                            <button class="status-badge <?php echo $member['is_active'] ? 'status-active' : 'status-inactive'; ?>" 
                                    onclick="toggleStatus(<?php echo $member['id']; ?>, this)">
                                <?php echo $member['is_active'] ? 'Active' : 'Inactive'; ?>
                            </button>
                        </td>
                        <td class="text-center">
                            <div class="action-buttons">
                                <a href="team-member-edit.php?id=<?php echo $member['id']; ?>" 
                                   class="btn-icon" 
                                   title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>
                                <button class="btn-icon btn-icon-danger" 
                                        onclick="deleteMember(<?php echo $member['id']; ?>, '<?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?>')"
                                        title="Delete">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<form id="deleteForm" method="post" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="member_id" id="deleteMemberId">
</form>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// Initialize sortable
const el = document.getElementById('sortable-members');
if (el) {
    const sortable = new Sortable(el, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function(evt) {
            const order = Array.from(el.querySelectorAll('tr')).map(tr => tr.dataset.id);
            
            fetch('team-members.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=reorder&order=' + encodeURIComponent(JSON.stringify(order))
            });
        }
    });
}

function toggleStatus(memberId, button) {
    fetch('team-members.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=toggle_active&member_id=' + memberId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const isActive = button.classList.contains('status-inactive');
            button.classList.toggle('status-active', isActive);
            button.classList.toggle('status-inactive', !isActive);
            button.textContent = isActive ? 'Active' : 'Inactive';
        }
    });
}

function deleteMember(memberId, memberName) {
    if (confirm(`Are you sure you want to delete "${memberName}"? This action cannot be undone.`)) {
        document.getElementById('deleteMemberId').value = memberId;
        document.getElementById('deleteForm').submit();
    }
}
</script>

<?php include 'includes/footer.php'; ?>
