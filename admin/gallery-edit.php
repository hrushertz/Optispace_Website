<?php
/**
 * Admin Edit Gallery Item
 */

$pageTitle = "Edit Gallery Item";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

// Get item ID
$itemId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($itemId <= 0) {
    header('Location: gallery.php');
    exit;
}

// Get existing item
$stmt = $conn->prepare("SELECT * FROM gallery_items WHERE id = ?");
$stmt->bind_param("i", $itemId);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$item) {
    header('Location: gallery.php');
    exit;
}

// Get categories
$categories = $conn->query("SELECT * FROM gallery_categories WHERE is_active = 1 ORDER BY sort_order ASC");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = (int)$_POST['category_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $industry = trim($_POST['industry']);
    $projectSize = trim($_POST['project_size']);
    $completionDate = !empty($_POST['completion_date']) ? $_POST['completion_date'] : null;
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    $sortOrder = (int)$_POST['sort_order'];
    
    $errors = [];
    
    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    
    if ($categoryId <= 0) {
        $errors[] = "Please select a category.";
    }
    
    // Handle image upload
    $imagePath = $item['image_path'];
    $thumbnailPath = $item['thumbnail_path'];
    
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/img/gallery/';
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                $errors[] = "Failed to create upload directory. Please check permissions.";
            }
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            $errors[] = "Invalid image type. Allowed: JPG, PNG, WebP, GIF";
        } elseif ($_FILES['image']['size'] > $maxSize) {
            $errors[] = "Image size must be less than 5MB";
        } elseif (!is_writable($uploadDir)) {
            $errors[] = "Upload directory is not writable. Please check permissions.";
        } else {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $filename = 'gallery_' . time() . '_' . uniqid() . '.' . $ext;
            $uploadPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                // Delete old image if exists
                if ($item['image_path'] && file_exists(__DIR__ . '/..' . $item['image_path'])) {
                    @unlink(__DIR__ . '/..' . $item['image_path']);
                }
                $imagePath = '/assets/img/gallery/' . $filename;
                $thumbnailPath = $imagePath;
            } else {
                $errors[] = "Failed to upload image. Error code: " . $_FILES['image']['error'];
            }
        }
    } elseif (!empty($_FILES['image']['name']) && $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        $errors[] = $uploadErrors[$_FILES['image']['error']] ?? 'Unknown upload error';
    }
    
    // Handle image removal
    if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
        if ($item['image_path'] && file_exists(__DIR__ . '/..' . $item['image_path'])) {
            @unlink(__DIR__ . '/..' . $item['image_path']);
        }
        $imagePath = null;
        $thumbnailPath = null;
    }
    
    if (empty($errors)) {
        $stmt = $conn->prepare("
            UPDATE gallery_items SET
            category_id = ?, title = ?, description = ?, image_path = ?, thumbnail_path = ?, 
            location = ?, industry = ?, project_size = ?, completion_date = ?, 
            is_featured = ?, is_active = ?, sort_order = ?
            WHERE id = ?
        ");
        $stmt->bind_param("issssssssiiii", 
            $categoryId, $title, $description, $imagePath, $thumbnailPath, 
            $location, $industry, $projectSize, $completionDate, 
            $isFeatured, $isActive, $sortOrder, $itemId
        );
        
        if ($stmt->execute()) {
            logAdminActivity($_SESSION['admin_id'], 'update', 'gallery_items', $itemId, 'Updated gallery item: ' . $title);
            $successMessage = "Gallery item updated successfully.";
            
            // Refresh item data
            $stmt->close();
            $stmt = $conn->prepare("SELECT * FROM gallery_items WHERE id = ?");
            $stmt->bind_param("i", $itemId);
            $stmt->execute();
            $item = $stmt->get_result()->fetch_assoc();
        } else {
            $errors[] = "Failed to update gallery item. Please try again.";
        }
        $stmt->close();
    }
}

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <nav class="breadcrumb">
            <a href="gallery.php">Gallery Items</a>
            <span class="separator">/</span>
            <span>Edit</span>
        </nav>
        <h1 class="page-title">Edit Gallery Item</h1>
    </div>
</div>

<?php if (isset($successMessage)): ?>
<div class="alert alert-success">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
        <polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <?php echo htmlspecialchars($successMessage); ?>
</div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <line x1="15" y1="9" x2="9" y2="15"/>
        <line x1="9" y1="9" x2="15" y2="15"/>
    </svg>
    <ul style="margin: 0; padding-left: 1.25rem;">
        <?php foreach ($errors as $error): ?>
        <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="admin-form">
    <div class="form-layout">
        <div class="form-main">
            <div class="form-card">
                <div class="form-card-header">
                    <h2>Basic Information</h2>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="title" class="form-label required">Title</label>
                        <input type="text" id="title" name="title" class="form-input" value="<?php echo htmlspecialchars($item['title']); ?>" placeholder="e.g., Automotive Assembly Plant" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-textarea" rows="4" placeholder="Brief description of the project..."><?php echo htmlspecialchars($item['description']); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category_id" class="form-label required">Category</label>
                            <select id="category_id" name="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php 
                                $categories->data_seek(0);
                                while ($cat = $categories->fetch_assoc()): 
                                ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $item['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="industry" class="form-label">Industry</label>
                            <input type="text" id="industry" name="industry" class="form-input" value="<?php echo htmlspecialchars($item['industry']); ?>" placeholder="e.g., Automotive, Pharmaceutical">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" id="location" name="location" class="form-input" value="<?php echo htmlspecialchars($item['location']); ?>" placeholder="e.g., Michigan, USA">
                        </div>
                        
                        <div class="form-group">
                            <label for="project_size" class="form-label">Project Size</label>
                            <input type="text" id="project_size" name="project_size" class="form-input" value="<?php echo htmlspecialchars($item['project_size']); ?>" placeholder="e.g., 150,000 sq ft">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="completion_date" class="form-label">Completion Date</label>
                            <input type="date" id="completion_date" name="completion_date" class="form-input" value="<?php echo htmlspecialchars($item['completion_date'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" class="form-input" value="<?php echo htmlspecialchars($item['sort_order']); ?>" min="0">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-card">
                <div class="form-card-header">
                    <h2>Project Image</h2>
                </div>
                <div class="form-card-body">
                    <?php if ($item['image_path'] && file_exists(__DIR__ . '/../' . $item['image_path'])): ?>
                    <div class="current-image">
                        <label class="form-label">Current Image</label>
                        <div class="image-preview-container">
                            <img src="../<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <div class="image-actions">
                                <label class="btn btn-danger btn-sm">
                                    <input type="checkbox" name="remove_image" value="1" style="display: none;" id="removeImageCheck">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                    Remove
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label class="form-label"><?php echo $item['image_path'] ? 'Replace Image' : 'Upload Image'; ?></label>
                        <div class="file-upload-area" id="imageUploadArea">
                            <input type="file" id="image" name="image" accept="image/*" class="file-input">
                            <div class="upload-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                <span>Click to upload or drag and drop</span>
                                <small>JPG, PNG, WebP or GIF (max. 5MB)</small>
                            </div>
                            <div class="upload-preview" id="imagePreview"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-sidebar">
            <div class="form-card">
                <div class="form-card-header">
                    <h2>Publish</h2>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_active" value="1" <?php echo $item['is_active'] ? 'checked' : ''; ?>>
                            <span class="checkbox-text">Active</span>
                            <span class="checkbox-description">Item will be visible on the website</span>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_featured" value="1" <?php echo $item['is_featured'] ? 'checked' : ''; ?>>
                            <span class="checkbox-text">Featured</span>
                            <span class="checkbox-description">Highlight this item in the gallery</span>
                        </label>
                    </div>
                    
                    <div class="item-meta">
                        <div class="meta-item">
                            <span class="meta-label">Created</span>
                            <span class="meta-value"><?php echo date('M j, Y', strtotime($item['created_at'])); ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Updated</span>
                            <span class="meta-value"><?php echo date('M j, Y', strtotime($item['updated_at'])); ?></span>
                        </div>
                    </div>
                </div>
                <div class="form-card-footer">
                    <a href="gallery.php" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Update Item
                    </button>
                </div>
            </div>
            
            <div class="form-card danger-zone">
                <div class="form-card-header">
                    <h2>Danger Zone</h2>
                </div>
                <div class="form-card-body">
                    <p>Deleting this item is permanent and cannot be undone.</p>
                    <a href="gallery.php?delete=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this gallery item? This action cannot be undone.');">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        Delete Item
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
/* Form Styles */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.breadcrumb a {
    color: var(--admin-primary);
}

.breadcrumb .separator {
    color: var(--admin-gray-400);
}

.form-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.5rem;
    align-items: start;
}

.form-main {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-card {
    background: var(--admin-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--admin-gray-200);
    overflow: hidden;
}

.form-card.danger-zone {
    border-color: var(--admin-danger-light);
}

.form-card.danger-zone .form-card-header {
    background: var(--admin-danger-light);
}

.form-card.danger-zone .form-card-header h2 {
    color: var(--admin-danger);
}

.form-card.danger-zone p {
    font-size: 0.85rem;
    color: var(--admin-gray-600);
    margin-bottom: 1rem;
}

.form-card-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--admin-gray-100);
}

.form-card-header h2 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--admin-dark);
    margin: 0;
}

.form-card-body {
    padding: 1.5rem;
}

.form-card-footer {
    padding: 1rem 1.5rem;
    background: var(--admin-gray-50);
    border-top: 1px solid var(--admin-gray-100);
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--admin-gray-700);
    margin-bottom: 0.5rem;
}

.form-label.required::after {
    content: '*';
    color: var(--admin-danger);
    margin-left: 0.25rem;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--admin-gray-200);
    border-radius: var(--radius-md);
    font-size: 0.9rem;
    font-family: inherit;
    background: var(--admin-white);
    color: var(--admin-gray-700);
    transition: all var(--transition-fast);
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--admin-primary);
    box-shadow: 0 0 0 3px var(--admin-primary-light);
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.form-select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 2.5rem;
}

/* Current Image */
.current-image {
    margin-bottom: 1.5rem;
}

.image-preview-container {
    position: relative;
    display: inline-block;
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 1px solid var(--admin-gray-200);
}

.image-preview-container img {
    display: block;
    max-width: 100%;
    max-height: 200px;
}

.image-actions {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
}

/* File Upload */
.file-upload-area {
    position: relative;
    border: 2px dashed var(--admin-gray-200);
    border-radius: var(--radius-lg);
    padding: 2rem;
    text-align: center;
    transition: all var(--transition-fast);
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: var(--admin-primary);
    background: var(--admin-primary-light);
}

.file-upload-area.dragover {
    border-color: var(--admin-primary);
    background: var(--admin-primary-light);
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.upload-placeholder svg {
    width: 48px;
    height: 48px;
    color: var(--admin-gray-400);
}

.upload-placeholder span {
    font-size: 0.95rem;
    color: var(--admin-gray-600);
}

.upload-placeholder small {
    font-size: 0.8rem;
    color: var(--admin-gray-400);
}

.upload-preview {
    display: none;
}

.upload-preview.active {
    display: block;
}

.upload-preview img {
    max-width: 100%;
    max-height: 200px;
    border-radius: var(--radius-md);
}

/* Checkbox */
.checkbox-label {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 0.75rem;
    cursor: pointer;
    padding: 0.75rem;
    border-radius: var(--radius-md);
    transition: background var(--transition-fast);
}

.checkbox-label:hover {
    background: var(--admin-gray-50);
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-top: 2px;
    accent-color: var(--admin-primary);
}

.checkbox-text {
    font-weight: 500;
    color: var(--admin-dark);
    flex: 1;
}

.checkbox-description {
    font-size: 0.8rem;
    color: var(--admin-gray-500);
    width: 100%;
    padding-left: 1.75rem;
    margin-top: -0.25rem;
}

/* Item Meta */
.item-meta {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--admin-gray-100);
}

.meta-item {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.meta-item:last-child {
    margin-bottom: 0;
}

.meta-label {
    color: var(--admin-gray-500);
}

.meta-value {
    color: var(--admin-gray-700);
    font-weight: 500;
}

/* Responsive */
@media (max-width: 1024px) {
    .form-layout {
        grid-template-columns: 1fr;
    }
    
    .form-sidebar {
        order: -1;
    }
}

@media (max-width: 600px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Image preview functionality
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const placeholder = document.querySelector('.upload-placeholder');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
            preview.classList.add('active');
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});

// Drag and drop
const uploadArea = document.getElementById('imageUploadArea');
const fileInput = document.getElementById('image');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, function(e) {
        e.preventDefault();
        e.stopPropagation();
    });
});

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, function() {
        uploadArea.classList.add('dragover');
    });
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, function() {
        uploadArea.classList.remove('dragover');
    });
});

uploadArea.addEventListener('drop', function(e) {
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        fileInput.dispatchEvent(new Event('change'));
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
