<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Edit Client Video';

// Get database connection
$conn = getDBConnection();

$formError = '';
$videoId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch existing video
$stmt = $conn->prepare("SELECT * FROM client_videos WHERE id = ?");
$stmt->bind_param("i", $videoId);
$stmt->execute();
$result = $stmt->get_result();
$video = $result->fetch_assoc();
$stmt->close();

if (!$video) {
    $_SESSION['error_message'] = 'Video not found';
    header('Location: client-videos.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $youtubeVideoUrl = trim($_POST['youtube_video_url'] ?? '');
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    // Validation
    if (empty($title)) {
        $formError = 'Title is required';
    } elseif (empty($youtubeVideoUrl)) {
        $formError = 'YouTube video URL is required';
    } else {
        // Update video
        $stmt = $conn->prepare("UPDATE client_videos SET title = ?, description = ?, youtube_video_url = ?, is_active = ? WHERE id = ?");
        $stmt->bind_param("sssii", $title, $description, $youtubeVideoUrl, $isActive, $videoId);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Video updated successfully';
            header('Location: client-videos.php');
            exit;
        } else {
            $formError = 'Error updating video: ' . $conn->error;
        }
        $stmt->close();
    }
    
    // Update current values if form was submitted
    $video['title'] = $title;
    $video['description'] = $description;
    $video['youtube_video_url'] = $youtubeVideoUrl;
    $video['is_active'] = $isActive;
}

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 0.5rem;">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit Client Video
            </h1>
            <p class="page-subtitle">Update video information</p>
        </div>
        <div class="page-actions">
            <a href="client-videos.php" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <?php if ($formError): ?>
    <div class="alert alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <?php echo htmlspecialchars($formError); ?>
    </div>
    <?php endif; ?>

    <div class="content-card">
        <form method="post" class="admin-form">
            <div class="form-grid">
                <div class="form-group span-full">
                    <label for="title">Client/Video Title <span class="required">*</span></label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           class="form-control" 
                           placeholder="e.g., OM Auto Components or Manufacturing Excellence Story"
                           value="<?php echo htmlspecialchars($video['title']); ?>"
                           required>
                    <small class="form-text">The client name or video title to display</small>
                </div>

                <div class="form-group span-full">
                    <label for="description">Description</label>
                    <textarea id="description" 
                              name="description" 
                              class="form-control" 
                              rows="3"
                              placeholder="Brief description of the video content"><?php echo htmlspecialchars($video['description'] ?? ''); ?></textarea>
                    <small class="form-text">Short description that appears with the video (optional)</small>
                </div>

                <div class="form-group span-full">
                    <label for="youtube_video_url">YouTube Video URL <span class="required">*</span></label>
                    <input type="text" 
                           id="youtube_video_url" 
                           name="youtube_video_url" 
                           class="form-control" 
                           placeholder="e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ or just the video ID: dQw4w9WgXcQ"
                           value="<?php echo htmlspecialchars($video['youtube_video_url']); ?>"
                           required>
                    <small class="form-text">Full YouTube URL or just the video ID</small>
                </div>

                <div class="form-group span-full">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?php echo $video['is_active'] ? 'checked' : ''; ?>>
                        <span>Active (visible on website)</span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Update Video
                </button>
                <a href="client-videos.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
