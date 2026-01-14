<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Add Client Video';

// Get database connection
$conn = getDBConnection();

$formError = '';

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
        // Get max sort order
        $result = $conn->query("SELECT MAX(sort_order) as max_order FROM client_videos");
        $row = $result->fetch_assoc();
        $sortOrder = ($row['max_order'] ?? 0) + 1;
        
        // Insert video
        $stmt = $conn->prepare("INSERT INTO client_videos (title, description, youtube_video_url, sort_order, is_active, created_by) VALUES (?, ?, ?, ?, ?, ?)");
        $userId = $_SESSION['admin_user']['id'];
        $stmt->bind_param("sssiii", $title, $description, $youtubeVideoUrl, $sortOrder, $isActive, $userId);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Video added successfully';
            header('Location: client-videos.php');
            exit;
        } else {
            $formError = 'Error adding video: ' . $conn->error;
        }
        $stmt->close();
    }
}

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 0.5rem;">
                    <line x1="12" y1="8" x2="12" y2="16"/>
                    <line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
                Add Client Video
            </h1>
            <p class="page-subtitle">Add a new YouTube testimonial video</p>
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
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                           required>
                    <small class="form-text">The client name or video title to display</small>
                </div>

                <div class="form-group span-full">
                    <label for="description">Description</label>
                    <textarea id="description" 
                              name="description" 
                              class="form-control" 
                              rows="3"
                              placeholder="Brief description of the video content"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    <small class="form-text">Short description that appears with the video (optional)</small>
                </div>

                <div class="form-group span-full">
                    <label for="youtube_video_url">YouTube Video URL <span class="required">*</span></label>
                    <input type="text" 
                           id="youtube_video_url" 
                           name="youtube_video_url" 
                           class="form-control" 
                           placeholder="e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ or just the video ID: dQw4w9WgXcQ"
                           value="<?php echo htmlspecialchars($_POST['youtube_video_url'] ?? ''); ?>"
                           required>
                    <small class="form-text">Full YouTube URL or just the video ID</small>
                </div>

                <div class="form-group span-full">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?php echo isset($_POST['is_active']) || !isset($_POST['title']) ? 'checked' : ''; ?>>
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
                    Add Video
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
