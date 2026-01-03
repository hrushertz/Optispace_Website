<?php
require_once 'auth_check.php';
require_once '../database/db_config.php';

$page_title = 'Gallery';
$current_page = 'gallery';
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    
    if (isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] === 0) {
        $upload_dir = '../uploads/gallery/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['gallery_image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = uniqid('gallery_') . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['gallery_image']['tmp_name'], $upload_path)) {
                // Create thumbnail
                $thumb_dir = '../uploads/gallery/thumbs/';
                if (!file_exists($thumb_dir)) {
                    mkdir($thumb_dir, 0777, true);
                }
                $thumb_filename = 'thumb_' . $new_filename;
                $thumb_path = $thumb_dir . $thumb_filename;
                
                // Simple copy for now (you can add image resize logic)
                copy($upload_path, $thumb_path);
                
                $conn = getDBConnection();
                
                $image_path = 'uploads/gallery/' . $new_filename;
                $thumbnail_path = 'uploads/gallery/thumbs/' . $thumb_filename;
                
                $stmt = $conn->prepare("INSERT INTO gallery (title, description, image_path, thumbnail_path, category) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $title, $description, $image_path, $thumbnail_path, $category);
                
                if ($stmt->execute()) {
                    $success = 'Image uploaded successfully!';
                    $_POST = [];
                } else {
                    $error = 'Error saving image: ' . $conn->error;
                }
                
                $stmt->close();
                $conn->close();
            } else {
                $error = 'Failed to upload image';
            }
        } else {
            $error = 'Invalid file type. Please upload an image (JPG, PNG, GIF, WEBP)';
        }
    } else {
        $error = 'Please select an image to upload';
    }
}

// Get all gallery images
$conn = getDBConnection();
$gallery_result = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
$gallery_images = [];
if ($gallery_result) {
    while ($row = $gallery_result->fetch_assoc()) {
        $gallery_images[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin Panel</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .gallery-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .gallery-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .gallery-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f1f5f9;
        }
        
        .gallery-info {
            padding: 1rem;
        }
        
        .gallery-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }
        
        .gallery-category {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.75rem;
        }
        
        .gallery-actions {
            display: flex;
            gap: 0.75rem;
        }
        
        .gallery-actions a {
            flex: 1;
            text-align: center;
            padding: 0.5rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .btn-view {
            background: #eff6ff;
            color: #3b82f6;
        }
        
        .btn-view:hover {
            background: #dbeafe;
        }
        
        .btn-delete {
            background: #fee2e2;
            color: #ef4444;
        }
        
        .btn-delete:hover {
            background: #fecaca;
        }
        
        .image-preview {
            margin-top: 1rem;
            max-width: 300px;
        }
        
        .image-preview img {
            width: 100%;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include 'includes/topbar.php'; ?>
            
            <div class="page-content">
                <div class="page-header">
                    <h1>Gallery Management</h1>
                    <p class="page-subtitle">Upload and manage gallery images</p>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-container">
                    <h2 style="margin-bottom: 1.5rem;">Upload New Image</h2>
                    <form method="POST" enctype="multipart/form-data" id="galleryForm">
                        <div class="form-group">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-input" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-textarea" placeholder="Optional description..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="">Select Category</option>
                                <option value="Projects" <?php echo (($_POST['category'] ?? '') === 'Projects') ? 'selected' : ''; ?>>Projects</option>
                                <option value="Factory Layouts" <?php echo (($_POST['category'] ?? '') === 'Factory Layouts') ? 'selected' : ''; ?>>Factory Layouts</option>
                                <option value="Team" <?php echo (($_POST['category'] ?? '') === 'Team') ? 'selected' : ''; ?>>Team</option>
                                <option value="Events" <?php echo (($_POST['category'] ?? '') === 'Events') ? 'selected' : ''; ?>>Events</option>
                                <option value="Before/After" <?php echo (($_POST['category'] ?? '') === 'Before/After') ? 'selected' : ''; ?>>Before/After</option>
                                <option value="Other" <?php echo (($_POST['category'] ?? '') === 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Image * (JPG, PNG, GIF, WEBP)</label>
                            <input type="file" name="gallery_image" class="form-input" required 
                                   accept="image/jpeg,image/png,image/gif,image/webp"
                                   onchange="previewImage(event)">
                            <div id="imagePreview" class="image-preview" style="display: none;">
                                <img id="preview" src="" alt="Preview">
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" class="btn-primary">Upload Image</button>
                            <a href="dashboard.php" class="btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
                
                <?php if (count($gallery_images) > 0): ?>
                    <div style="margin-top: 3rem;">
                        <h2 style="margin-bottom: 1.5rem;">Gallery Images (<?php echo count($gallery_images); ?>)</h2>
                        <div class="gallery-grid">
                            <?php foreach ($gallery_images as $image): ?>
                                <div class="gallery-item">
                                    <img src="../<?php echo htmlspecialchars($image['thumbnail_path'] ?: $image['image_path']); ?>" 
                                         alt="<?php echo htmlspecialchars($image['title']); ?>"
                                         class="gallery-image">
                                    <div class="gallery-info">
                                        <div class="gallery-title">
                                            <?php echo htmlspecialchars($image['title'] ?: 'Untitled'); ?>
                                        </div>
                                        <div class="gallery-category">
                                            <?php echo htmlspecialchars($image['category'] ?: 'Uncategorized'); ?> • 
                                            <?php echo date('M d, Y', strtotime($image['created_at'])); ?>
                                        </div>
                                        <div class="gallery-actions">
                                            <a href="../<?php echo htmlspecialchars($image['image_path']); ?>" 
                                               target="_blank" class="btn-view">View</a>
                                            <a href="delete-gallery.php?id=<?php echo $image['id']; ?>" 
                                               class="btn-delete"
                                               onclick="return confirm('Are you sure you want to delete this image?')">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
