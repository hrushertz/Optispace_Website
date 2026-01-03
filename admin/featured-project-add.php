<?php
/**
 * Admin Add Featured Project
 */

$pageTitle = "Add Featured Project";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

// Get gallery items for linking
$galleryItems = $conn->query("SELECT id, title FROM gallery_items WHERE is_active = 1 ORDER BY title ASC");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $galleryItemId = !empty($_POST['gallery_item_id']) ? (int)$_POST['gallery_item_id'] : null;
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $stat1Value = trim($_POST['stat_1_value']);
    $stat1Label = trim($_POST['stat_1_label']);
    $stat2Value = trim($_POST['stat_2_value']);
    $stat2Label = trim($_POST['stat_2_label']);
    $stat3Value = trim($_POST['stat_3_value']);
    $stat3Label = trim($_POST['stat_3_label']);
    $isPrimary = isset($_POST['is_primary']) ? 1 : 0;
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    $sortOrder = (int)$_POST['sort_order'];
    
    $errors = [];
    
    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    
    // Handle image upload
    $imagePath = null;
    
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../assets/img/featured/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            $errors[] = "Invalid image type. Allowed: JPG, PNG, WebP, GIF";
        } elseif ($_FILES['image']['size'] > $maxSize) {
            $errors[] = "Image size must be less than 5MB";
        } else {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'featured_' . time() . '_' . uniqid() . '.' . $ext;
            $uploadPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $imagePath = 'assets/img/featured/' . $filename;
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }
    
    if (empty($errors)) {
        // If setting as primary, unset other primaries first
        if ($isPrimary) {
            $conn->query("UPDATE featured_projects SET is_primary = 0");
        }
        
        $stmt = $conn->prepare("
            INSERT INTO featured_projects 
            (gallery_item_id, title, description, image_path, location, 
             stat_1_value, stat_1_label, stat_2_value, stat_2_label, stat_3_value, stat_3_label,
             is_primary, is_active, sort_order, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $adminId = $_SESSION['admin_id'];
        $stmt->bind_param("issssssssssiiis", 
            $galleryItemId, $title, $description, $imagePath, $location,
            $stat1Value, $stat1Label, $stat2Value, $stat2Label, $stat3Value, $stat3Label,
            $isPrimary, $isActive, $sortOrder, $adminId
        );
        
        if ($stmt->execute()) {
            $newId = $conn->insert_id;
            logAdminActivity($_SESSION['admin_id'], 'create', 'featured_projects', $newId, 'Created featured project: ' . $title);
            $stmt->close();
            $conn->close();
            header('Location: featured-projects.php?success=created');
            exit;
        } else {
            $errors[] = "Failed to create featured project. Please try again.";
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
            <a href="featured-projects.php">Featured Projects</a>
            <span class="separator">/</span>
            <span>Add New</span>
        </nav>
        <h1 class="page-title">Add Featured Project</h1>
    </div>
</div>

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
                    <h2>Project Information</h2>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="title" class="form-label required">Title</label>
                        <input type="text" id="title" name="title" class="form-input" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" placeholder="e.g., Automotive OEM Greenfield Plant" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-textarea" rows="4" placeholder="Detailed description of the project transformation..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" id="location" name="location" class="form-input" value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>" placeholder="e.g., Detroit, Michigan">
                        </div>
                        
                        <div class="form-group">
                            <label for="gallery_item_id" class="form-label">Link to Gallery Item</label>
                            <select id="gallery_item_id" name="gallery_item_id" class="form-select">
                                <option value="">None (Standalone)</option>
                                <?php while ($item = $galleryItems->fetch_assoc()): ?>
                                <option value="<?php echo $item['id']; ?>" <?php echo (isset($_POST['gallery_item_id']) && $_POST['gallery_item_id'] == $item['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($item['title']); ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-card">
                <div class="form-card-header">
                    <h2>Project Statistics</h2>
                    <p class="form-card-subtitle">Add up to 3 key metrics to showcase project results</p>
                </div>
                <div class="form-card-body">
                    <div class="stats-grid">
                        <div class="stat-input-group">
                            <span class="stat-number">1</span>
                            <div class="stat-inputs">
                                <input type="text" name="stat_1_value" class="form-input" value="<?php echo htmlspecialchars($_POST['stat_1_value'] ?? ''); ?>" placeholder="Value (e.g., 40%)">
                                <input type="text" name="stat_1_label" class="form-input" value="<?php echo htmlspecialchars($_POST['stat_1_label'] ?? ''); ?>" placeholder="Label (e.g., Space Optimized)">
                            </div>
                        </div>
                        
                        <div class="stat-input-group">
                            <span class="stat-number">2</span>
                            <div class="stat-inputs">
                                <input type="text" name="stat_2_value" class="form-input" value="<?php echo htmlspecialchars($_POST['stat_2_value'] ?? ''); ?>" placeholder="Value (e.g., 6)">
                                <input type="text" name="stat_2_label" class="form-input" value="<?php echo htmlspecialchars($_POST['stat_2_label'] ?? ''); ?>" placeholder="Label (e.g., Months to Launch)">
                            </div>
                        </div>
                        
                        <div class="stat-input-group">
                            <span class="stat-number">3</span>
                            <div class="stat-inputs">
                                <input type="text" name="stat_3_value" class="form-input" value="<?php echo htmlspecialchars($_POST['stat_3_value'] ?? ''); ?>" placeholder="Value (e.g., 98%)">
                                <input type="text" name="stat_3_label" class="form-input" value="<?php echo htmlspecialchars($_POST['stat_3_label'] ?? ''); ?>" placeholder="Label (e.g., On-time Delivery)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-card">
                <div class="form-card-header">
                    <h2>Project Image</h2>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label class="form-label">Upload Image</label>
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
                            <input type="checkbox" name="is_active" value="1" <?php echo (!isset($_POST['is_active']) || isset($_POST['is_active'])) ? 'checked' : ''; ?>>
                            <span class="checkbox-text">Active</span>
                            <span class="checkbox-description">Project will be visible on the website</span>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_primary" value="1" <?php echo isset($_POST['is_primary']) ? 'checked' : ''; ?>>
                            <span class="checkbox-text">Primary Featured</span>
                            <span class="checkbox-description">Display as the main featured project (larger card)</span>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-input" value="<?php echo htmlspecialchars($_POST['sort_order'] ?? '0'); ?>" min="0">
                    </div>
                </div>
                <div class="form-card-footer">
                    <a href="featured-projects.php" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Save Project
                    </button>
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

.form-card {
    background: var(--admin-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--admin-gray-200);
    overflow: hidden;
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

.form-card-subtitle {
    font-size: 0.85rem;
    color: var(--admin-gray-500);
    margin-top: 0.25rem;
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

/* Stats Grid */
.stats-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-input-group {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: var(--admin-gray-50);
    border-radius: var(--radius-md);
}

.stat-number {
    width: 28px;
    height: 28px;
    background: var(--admin-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    font-weight: 600;
    flex-shrink: 0;
}

.stat-inputs {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 0.75rem;
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
    
    .stat-inputs {
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
