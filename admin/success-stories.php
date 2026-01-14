<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Success Stories';

// Get database connection
$conn = getDBConnection();

// Handle delete request
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['story_id'])) {
    $storyId = intval($_POST['story_id']);
    $stmt = $conn->prepare("DELETE FROM success_stories WHERE id = ?");
    $stmt->bind_param("i", $storyId);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Success story deleted successfully';
    } else {
        $_SESSION['error_message'] = 'Error deleting success story';
    }
    $stmt->close();
    header('Location: success-stories.php');
    exit;
}

// Handle reorder
if (isset($_POST['action']) && $_POST['action'] === 'reorder' && isset($_POST['order'])) {
    $order = json_decode($_POST['order'], true);
    foreach ($order as $index => $id) {
        $stmt = $conn->prepare("UPDATE success_stories SET sort_order = ? WHERE id = ?");
        $sortOrder = $index + 1;
        $stmt->bind_param("ii", $sortOrder, $id);
        $stmt->execute();
        $stmt->close();
    }
    echo json_encode(['success' => true]);
    exit;
}

// Handle toggle active status
if (isset($_POST['action']) && $_POST['action'] === 'toggle_active' && isset($_POST['story_id'])) {
    $storyId = intval($_POST['story_id']);
    $stmt = $conn->prepare("UPDATE success_stories SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $storyId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $stmt->close();
    exit;
}

// Fetch all success stories
$result = $conn->query("SELECT * FROM success_stories ORDER BY sort_order ASC, id ASC");
$stories = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $stories[] = $row;
    }
}

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 0.5rem;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                Client Success Stories
            </h1>
            <p class="page-subtitle">Manage client success stories displayed on the Portfolio page</p>
        </div>
        <div class="page-actions">
            <a href="success-story-add.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add Success Story
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
            <path d="M15 9l-6 6"/>
            <path d="M9 9l6 6"/>
        </svg>
        <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2>All Success Stories (<?php echo count($stories); ?>)</h2>
            <p style="color: #64748b; font-size: 0.9rem; margin-top: 0.5rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; vertical-align: middle;">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                Drag and drop rows to reorder stories
            </p>
        </div>
        
        <?php if (empty($stories)): ?>
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <h3>No Success Stories Yet</h3>
            <p>Create your first success story to display on the Portfolio page</p>
            <a href="success-story-add.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add First Success Story
            </a>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                                <line x1="8" y1="6" x2="21" y2="6"/>
                                <line x1="8" y1="12" x2="21" y2="12"/>
                                <line x1="8" y1="18" x2="21" y2="18"/>
                                <line x1="3" y1="6" x2="3.01" y2="6"/>
                                <line x1="3" y1="12" x2="3.01" y2="12"/>
                                <line x1="3" y1="18" x2="3.01" y2="18"/>
                            </svg>
                        </th>
                        <th>Title</th>
                        <th>Project Type</th>
                        <th>Industry</th>
                        <th>Results</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sortable-stories">
                    <?php foreach ($stories as $story): 
                        $results = json_decode($story['results'], true);
                        $resultsCount = is_array($results) ? count($results) : 0;
                    ?>
                    <tr data-id="<?php echo $story['id']; ?>">
                        <td class="drag-handle" style="cursor: move;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; color: #94a3b8;">
                                <line x1="9" y1="6" x2="15" y2="6"/>
                                <line x1="9" y1="12" x2="15" y2="12"/>
                                <line x1="9" y1="18" x2="15" y2="18"/>
                            </svg>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($story['title']); ?></strong>
                        </td>
                        <td>
                            <?php if (!empty($story['project_type'])): ?>
                                <span class="badge badge-blue"><?php echo htmlspecialchars($story['project_type']); ?></span>
                            <?php else: ?>
                                <span style="color: #94a3b8;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($story['industry'])): ?>
                                <span class="badge badge-green"><?php echo htmlspecialchars($story['industry']); ?></span>
                            <?php else: ?>
                                <span style="color: #94a3b8;">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $resultsCount; ?> items</td>
                        <td>
                            <label class="toggle-switch" title="Toggle active status">
                                <input type="checkbox" 
                                       <?php echo $story['is_active'] ? 'checked' : ''; ?>
                                       onchange="toggleActive(<?php echo $story['id']; ?>, this)">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="success-story-edit.php?id=<?php echo $story['id']; ?>" 
                                   class="btn-icon btn-icon-primary" 
                                   title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>
                                <button onclick="confirmDelete(<?php echo $story['id']; ?>, '<?php echo htmlspecialchars(addslashes($story['title'])); ?>')" 
                                        class="btn-icon btn-icon-danger" 
                                        title="Delete">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
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

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="story_id" id="deleteStoryId">
</form>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// Initialize sortable
if (document.getElementById('sortable-stories')) {
    new Sortable(document.getElementById('sortable-stories'), {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function(evt) {
            // Get new order
            const rows = document.querySelectorAll('#sortable-stories tr');
            const order = Array.from(rows).map(row => row.dataset.id);
            
            // Send to server
            fetch('success-stories.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=reorder&order=' + encodeURIComponent(JSON.stringify(order))
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Error updating order');
                    location.reload();
                }
            });
        }
    });
}

function toggleActive(storyId, checkbox) {
    fetch('success-stories.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=toggle_active&story_id=' + storyId
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Error updating status');
            checkbox.checked = !checkbox.checked;
        }
    });
}

function confirmDelete(storyId, storyTitle) {
    if (confirm('Are you sure you want to delete "' + storyTitle + '"?\n\nThis action cannot be undone.')) {
        document.getElementById('deleteStoryId').value = storyId;
        document.getElementById('deleteForm').submit();
    }
}
</script>

<?php
$conn->close();
include 'includes/footer.php';
?>
