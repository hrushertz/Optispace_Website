<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Waste Items';

// Get database connection
$conn = getDBConnection();

// Handle delete request
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['waste_id'])) {
    $wasteId = intval($_POST['waste_id']);
    $stmt = $conn->prepare("DELETE FROM waste_items WHERE id = ?");
    $stmt->bind_param("i", $wasteId);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Waste item deleted successfully';
    } else {
        $_SESSION['error_message'] = 'Error deleting waste item';
    }
    $stmt->close();
    header('Location: waste-items.php');
    exit;
}

// Handle reorder
if (isset($_POST['action']) && $_POST['action'] === 'reorder' && isset($_POST['order'])) {
    $order = json_decode($_POST['order'], true);
    foreach ($order as $index => $id) {
        $stmt = $conn->prepare("UPDATE waste_items SET sort_order = ? WHERE id = ?");
        $sortOrder = $index + 1;
        $stmt->bind_param("ii", $sortOrder, $id);
        $stmt->execute();
        $stmt->close();
    }
    echo json_encode(['success' => true]);
    exit;
}

// Handle toggle active status
if (isset($_POST['action']) && $_POST['action'] === 'toggle_active' && isset($_POST['waste_id'])) {
    $wasteId = intval($_POST['waste_id']);
    $stmt = $conn->prepare("UPDATE waste_items SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $wasteId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $stmt->close();
    exit;
}

// Fetch all waste items
$result = $conn->query("SELECT * FROM waste_items ORDER BY sort_order ASC, id ASC");
$wasteItems = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $wasteItems[] = $row;
    }
}

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 0.5rem;">
                    <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                </svg>
                Waste Items (Mudas)
            </h1>
            <p class="page-subtitle">Manage waste items for the Philosophy page - Eliminating Mudas section</p>
        </div>
        <div class="page-actions">
            <a href="waste-item-add.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add Waste Item
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
            <line x1="15" y1="9" x2="9" y2="15"/>
            <line x1="9" y1="9" x2="15" y2="15"/>
        </svg>
        <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
    </div>
    <?php endif; ?>

    <div class="content-card">
        <div class="card-header">
            <h2>All Waste Items (<?php echo count($wasteItems); ?>)</h2>
            <div class="card-actions">
                <span class="text-muted">Drag to reorder</span>
            </div>
        </div>

        <?php if (empty($wasteItems)): ?>
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 64px; height: 64px; color: #CBD5E1; margin-bottom: 1rem;">
                <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            </svg>
            <h3>No Waste Items Found</h3>
            <p>Start by adding your first waste item</p>
            <a href="waste-item-add.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add First Waste Item
            </a>
        </div>
        <?php else: ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="40">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                                <line x1="9" y1="6" x2="9" y2="18"/>
                                <line x1="15" y1="6" x2="15" y2="18"/>
                            </svg>
                        </th>
                        <th width="60">#</th>
                        <th>Title</th>
                        <th>Problem</th>
                        <th>Solution</th>
                        <th width="80">Status</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody id="wasteList">
                    <?php foreach ($wasteItems as $item): ?>
                    <tr data-id="<?php echo $item['id']; ?>" class="sortable-row">
                        <td class="drag-handle">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="9" y1="6" x2="9" y2="18"/>
                                <line x1="15" y1="6" x2="15" y2="18"/>
                            </svg>
                        </td>
                        <td><?php echo $item['sort_order']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                            <div class="text-muted text-sm">
                                <?php 
                                $impact = strip_tags($item['impact_text']);
                                echo htmlspecialchars(strlen($impact) > 60 ? substr($impact, 0, 60) . '...' : $impact); 
                                ?>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm text-muted">
                                <?php 
                                $problem = strip_tags($item['problem_text']);
                                echo htmlspecialchars(strlen($problem) > 60 ? substr($problem, 0, 60) . '...' : $problem); 
                                ?>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm text-muted">
                                <?php 
                                $solution = strip_tags($item['solution_text']);
                                echo htmlspecialchars(strlen($solution) > 60 ? substr($solution, 0, 60) . '...' : $solution); 
                                ?>
                            </div>
                        </td>
                        <td>
                            <button class="status-badge <?php echo $item['is_active'] ? 'status-active' : 'status-inactive'; ?>" 
                                    onclick="toggleActive(<?php echo $item['id']; ?>)">
                                <?php echo $item['is_active'] ? 'Active' : 'Inactive'; ?>
                            </button>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="waste-item-edit.php?id=<?php echo $item['id']; ?>" 
                                   class="btn btn-sm btn-secondary" 
                                   title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>
                                <button class="btn btn-sm btn-danger" 
                                        onclick="deleteWaste(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars(addslashes($item['title'])); ?>')"
                                        title="Delete">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                        <line x1="14" y1="11" x2="14" y2="17"/>
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
</div><!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-backdrop">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Confirm Delete</h3>
            <button type="button" class="modal-close" onclick="closeDeleteModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this waste item?</p>
            <p id="deleteWasteName" style="font-weight: bold; color: #E94931; margin-top: 0.5rem;"></p>
        </div>
        <div class="modal-footer">
            <form id="deleteForm" method="post" style="display: contents;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="waste_id" id="deleteWasteId">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Cancel
                </button>
                <button type="submit" class="btn btn-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        <line x1="10" y1="11" x2="10" y2="17"/>
                        <line x1="14" y1="11" x2="14" y2="17"/>
                    </svg>
                    Delete Waste Item
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.sortable-row {
    cursor: move;
}

.drag-handle {
    cursor: grab;
    color: #94A3B8;
    text-align: center;
}

.drag-handle:active {
    cursor: grabbing;
}
</style>

<!-- Include Sortable JS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
// Initialize sortable
const wasteList = document.getElementById('wasteList');
if (wasteList) {
    new Sortable(wasteList, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function(evt) {
            const order = [];
            document.querySelectorAll('#wasteList tr').forEach(row => {
                order.push(row.dataset.id);
            });
            
            fetch('waste-items.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=reorder&order=' + JSON.stringify(order)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });
}

// Toggle active status
function toggleActive(wasteId) {
    fetch('waste-items.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=toggle_active&waste_id=' + wasteId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Delete waste item
function deleteWaste(id, name) {
    document.getElementById('deleteWasteId').value = id;
    document.getElementById('deleteWasteName').textContent = name;
    const modal = document.getElementById('deleteModal');
    modal.classList.add('active');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('active');
}

// Close modal on outside click
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeDeleteModal();
    }
}
</script>

<?php include 'includes/footer.php'; ?>
