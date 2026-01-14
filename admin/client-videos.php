<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Client Videos';

// Get database connection
$conn = getDBConnection();

// Handle delete request
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['video_id'])) {
    $videoId = intval($_POST['video_id']);
    $stmt = $conn->prepare("DELETE FROM client_videos WHERE id = ?");
    $stmt->bind_param("i", $videoId);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Video deleted successfully';
    } else {
        $_SESSION['error_message'] = 'Error deleting video';
    }
    $stmt->close();
    header('Location: client-videos.php');
    exit;
}

// Handle reorder
if (isset($_POST['action']) && $_POST['action'] === 'reorder' && isset($_POST['order'])) {
    $order = json_decode($_POST['order'], true);
    foreach ($order as $index => $id) {
        $stmt = $conn->prepare("UPDATE client_videos SET sort_order = ? WHERE id = ?");
        $sortOrder = $index + 1;
        $stmt->bind_param("ii", $sortOrder, $id);
        $stmt->execute();
        $stmt->close();
    }
    echo json_encode(['success' => true]);
    exit;
}

// Handle toggle active status
if (isset($_POST['action']) && $_POST['action'] === 'toggle_active' && isset($_POST['video_id'])) {
    $videoId = intval($_POST['video_id']);
    $stmt = $conn->prepare("UPDATE client_videos SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $videoId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $stmt->close();
    exit;
}

// Fetch all videos
$result = $conn->query("SELECT * FROM client_videos ORDER BY sort_order ASC, id ASC");
$videos = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }
}

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 0.5rem;">
                    <polygon points="23 7 16 12 23 17 23 7"/>
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                </svg>
                Client Testimonial Videos
            </h1>
            <p class="page-subtitle">Manage YouTube videos displayed on the homepage</p>
        </div>
        <div class="page-actions">
            <a href="client-video-add.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add Video
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
            <h2>All Videos (<?php echo count($videos); ?>)</h2>
            <p style="color: #64748b; font-size: 0.9rem; margin-top: 0.5rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; vertical-align: middle;">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                Drag and drop rows to reorder videos. First video appears as featured.
            </p>
        </div>
        
        <?php if (empty($videos)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="23 7 16 12 23 17 23 7"/>
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                </svg>
            </div>
            <h3>No Videos Yet</h3>
            <p>Add your first client testimonial video to display on the homepage</p>
            <a href="client-video-add.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add First Video
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
                        <th>Preview</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sortable-videos">
                    <?php foreach ($videos as $video): 
                        // Extract video ID for thumbnail
                        $videoId = $video['youtube_video_url'];
                        if (preg_match('/[?&]v=([^&]+)/', $videoId, $matches)) {
                            $videoId = $matches[1];
                        } elseif (preg_match('/youtu\.be\/([^?]+)/', $videoId, $matches)) {
                            $videoId = $matches[1];
                        }
                    ?>
                    <tr data-id="<?php echo $video['id']; ?>">
                        <td class="drag-handle" style="cursor: move;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; color: #94a3b8;">
                                <line x1="9" y1="6" x2="15" y2="6"/>
                                <line x1="9" y1="12" x2="15" y2="12"/>
                                <line x1="9" y1="18" x2="15" y2="18"/>
                            </svg>
                        </td>
                        <td>
                            <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($videoId); ?>/mqdefault.jpg" 
                                 alt="<?php echo htmlspecialchars($video['title']); ?>"
                                 style="width: 80px; height: 45px; object-fit: cover; border-radius: 4px;">
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($video['title']); ?></strong>
                        </td>
                        <td style="max-width: 300px;">
                            <?php echo htmlspecialchars(mb_substr($video['description'] ?? '', 0, 60)); ?>
                            <?php if (strlen($video['description'] ?? '') > 60) echo '...'; ?>
                        </td>
                        <td>
                            <label class="toggle-switch" title="Toggle active status">
                                <input type="checkbox" 
                                       <?php echo $video['is_active'] ? 'checked' : ''; ?>
                                       onchange="toggleActive(<?php echo $video['id']; ?>, this)">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="client-video-edit.php?id=<?php echo $video['id']; ?>" class="btn btn-sm btn-icon" title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>
                                <button type="button" class="btn btn-sm btn-icon-danger" onclick="deleteVideo(<?php echo $video['id']; ?>, '<?php echo htmlspecialchars(addslashes($video['title'])); ?>')" title="Delete">
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

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// Initialize drag and drop
if (document.getElementById('sortable-videos')) {
    new Sortable(document.getElementById('sortable-videos'), {
        animation: 150,
        handle: '.drag-handle',
        onEnd: function() {
            const order = Array.from(document.querySelectorAll('#sortable-videos tr')).map(tr => tr.dataset.id);
            
            fetch('client-videos.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=reorder&order=' + encodeURIComponent(JSON.stringify(order))
            });
        }
    });
}

function toggleActive(videoId, checkbox) {
    fetch('client-videos.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=toggle_active&video_id=' + videoId
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            checkbox.checked = !checkbox.checked;
            alert('Error updating status');
        }
    });
}

function deleteVideo(videoId, title) {
    if (confirm('Are you sure you want to delete "' + title + '"?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = '<input type="hidden" name="action" value="delete"><input type="hidden" name="video_id" value="' + videoId + '">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php
$conn->close();
include 'includes/footer.php';
?>
