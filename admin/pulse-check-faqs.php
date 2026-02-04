<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Pulse Check FAQs';

// Get database connection
$conn = getDBConnection();

// Handle delete request
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['faq_id'])) {
    $faqId = intval($_POST['faq_id']);
    $stmt = $conn->prepare("DELETE FROM pulse_check_faqs WHERE id = ?");
    $stmt->bind_param("i", $faqId);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'FAQ deleted successfully';
    } else {
        $_SESSION['error_message'] = 'Error deleting FAQ';
    }
    $stmt->close();
    header('Location: pulse-check-faqs.php');
    exit;
}

// Handle reorder
if (isset($_POST['action']) && $_POST['action'] === 'reorder' && isset($_POST['order'])) {
    $order = json_decode($_POST['order'], true);
    foreach ($order as $index => $id) {
        $stmt = $conn->prepare("UPDATE pulse_check_faqs SET sort_order = ? WHERE id = ?");
        $sortOrder = $index + 1;
        $stmt->bind_param("ii", $sortOrder, $id);
        $stmt->execute();
        $stmt->close();
    }
    echo json_encode(['success' => true]);
    exit;
}

// Handle toggle active status
if (isset($_POST['action']) && $_POST['action'] === 'toggle_active' && isset($_POST['faq_id'])) {
    $faqId = intval($_POST['faq_id']);
    $stmt = $conn->prepare("UPDATE pulse_check_faqs SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $faqId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $stmt->close();
    exit;
}

// Fetch all FAQs
$result = $conn->query("SELECT * FROM pulse_check_faqs ORDER BY sort_order ASC, id ASC");
$faqs = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $faqs[] = $row;
    }
}

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 0.5rem;">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                Pulse Check FAQs
            </h1>
            <p class="page-subtitle">Manage frequently asked questions for the Pulse Check page</p>
        </div>
        <div class="page-actions">
            <a href="pulse-check-faq-add.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add FAQ
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
        <div class="card-header">
            <h2>All FAQs (<?php echo count($faqs); ?>)</h2>
            <div class="card-actions">
                <span class="text-muted">Drag to reorder</span>
            </div>
        </div>

        <?php if (empty($faqs)): ?>
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 64px; height: 64px; color: #CBD5E1; margin-bottom: 1rem;">
                <circle cx="12" cy="12" r="10"/>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <h3>No FAQs Found</h3>
            <p>Start by adding your first FAQ</p>
            <a href="pulse-check-faq-add.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add First FAQ
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
                        <th>Question</th>
                        <th width="80">Status</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody id="faqList">
                    <?php foreach ($faqs as $faq): ?>
                    <tr data-id="<?php echo $faq['id']; ?>" class="sortable-row">
                        <td class="drag-handle">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="9" y1="6" x2="9" y2="18"/>
                                <line x1="15" y1="6" x2="15" y2="18"/>
                            </svg>
                        </td>
                        <td><?php echo $faq['sort_order']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($faq['question']); ?></strong>
                            <div class="text-muted text-sm">
                                <?php 
                                $answer = strip_tags($faq['answer']);
                                echo htmlspecialchars(strlen($answer) > 100 ? substr($answer, 0, 100) . '...' : $answer); 
                                ?>
                            </div>
                        </td>
                        <td>
                            <button class="status-badge <?php echo $faq['is_active'] ? 'status-active' : 'status-inactive'; ?>" 
                                    onclick="toggleActive(<?php echo $faq['id']; ?>)">
                                <?php echo $faq['is_active'] ? 'Active' : 'Inactive'; ?>
                            </button>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="pulse-check-faq-edit.php?id=<?php echo $faq['id']; ?>" 
                                   class="btn btn-sm btn-secondary" 
                                   title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>
                                <button class="btn btn-sm btn-danger" 
                                        onclick="deleteFaq(<?php echo $faq['id']; ?>, '<?php echo htmlspecialchars(addslashes($faq['question'])); ?>')"
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
</div>

<!-- Delete Confirmation Modal -->
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
            <p>Are you sure you want to delete this FAQ?</p>
            <p id="deleteFaqName" style="font-weight: bold; color: #E94931; margin-top: 0.5rem;"></p>
        </div>
        <div class="modal-footer">
            <form id="deleteForm" method="post" style="display: contents;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="faq_id" id="deleteFaqId">
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
                    Delete FAQ
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

.sortable-row.dragging {
    opacity: 0.5;
}

.status-badge {
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 12px;
    font-weight: 600;
}

.status-badge:hover {
    opacity: 0.8;
    transform: scale(1.05);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-start;
}

.action-buttons .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    padding: 0;
    border-radius: 6px;
    transition: all 0.2s;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.action-buttons .btn i {
    font-size: 0.9rem;
}
</style>

<script>
// Sortable functionality
let draggedElement = null;

document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.sortable-row');
    
    rows.forEach(row => {
        row.setAttribute('draggable', true);
        
        row.addEventListener('dragstart', function(e) {
            draggedElement = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        });
        
        row.addEventListener('dragend', function(e) {
            this.classList.remove('dragging');
            saveOrder();
        });
        
        row.addEventListener('dragover', function(e) {
            e.preventDefault();
            const afterElement = getDragAfterElement(this.parentElement, e.clientY);
            if (afterElement == null) {
                this.parentElement.appendChild(draggedElement);
            } else {
                this.parentElement.insertBefore(draggedElement, afterElement);
            }
        });
    });
});

function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.sortable-row:not(.dragging)')];
    
    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child };
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}

function saveOrder() {
    const rows = document.querySelectorAll('.sortable-row');
    const order = Array.from(rows).map(row => row.dataset.id);
    
    fetch('pulse-check-faqs.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=reorder&order=' + encodeURIComponent(JSON.stringify(order))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update sort order numbers in the table
            rows.forEach((row, index) => {
                row.querySelector('td:nth-child(2)').textContent = index + 1;
            });
        }
    });
}

function toggleActive(id) {
    fetch('pulse-check-faqs.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=toggle_active&faq_id=' + id
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Delete FAQ
function deleteFaq(id, question) {
    document.getElementById('deleteFaqId').value = id;
    document.getElementById('deleteFaqName').textContent = question;
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
