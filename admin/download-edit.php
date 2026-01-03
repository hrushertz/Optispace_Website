<?php
/**
 * Admin Edit Download
 */

$pageTitle = "Edit Download";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

// Get download ID
$downloadId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$downloadId) {
    header('Location: downloads.php');
    exit;
}

// Get download data
$stmt = $conn->prepare("SELECT * FROM downloads WHERE id = ?");
$stmt->bind_param("i", $downloadId);
$stmt->execute();
$download = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$download) {
    header('Location: downloads.php?error=not_found');
    exit;
}

// Get categories
$categories = $conn->query("SELECT * FROM download_categories WHERE is_active = 1 ORDER BY sort_order");

$errors = [];
$formData = $download;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $formData = [
        'title' => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'category_id' => (int)($_POST['category_id'] ?? 0),
        'file_path' => trim($_POST['file_path'] ?? ''),
        'file_name' => trim($_POST['file_name'] ?? ''),
        'file_type' => trim($_POST['file_type'] ?? 'PDF'),
        'file_size' => trim($_POST['file_size'] ?? ''),
        'published_date' => $_POST['published_date'] ?? date('Y-m-d'),
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0
    ];
    
    // Validate
    if (empty($formData['title'])) {
        $errors['title'] = 'Title is required.';
    }
    if (empty($formData['category_id'])) {
        $errors['category_id'] = 'Please select a category.';
    }
    if (empty($formData['file_path'])) {
        $errors['file_path'] = 'File path is required.';
    }
    if (empty($formData['file_name'])) {
        $errors['file_name'] = 'File name is required.';
    }
    
    // If no errors, update
    if (empty($errors)) {
        $stmt = $conn->prepare("
            UPDATE downloads SET
                category_id = ?,
                title = ?,
                description = ?,
                file_path = ?,
                file_name = ?,
                file_type = ?,
                file_size = ?,
                published_date = ?,
                is_active = ?,
                is_featured = ?
            WHERE id = ?
        ");
        
        $stmt->bind_param(
            "isssssssiii",
            $formData['category_id'],
            $formData['title'],
            $formData['description'],
            $formData['file_path'],
            $formData['file_name'],
            $formData['file_type'],
            $formData['file_size'],
            $formData['published_date'],
            $formData['is_active'],
            $formData['is_featured'],
            $downloadId
        );
        
        if ($stmt->execute()) {
            logAdminActivity($_SESSION['admin_id'], 'update', 'downloads', $downloadId, 'Updated download: ' . $formData['title']);
            
            $successMessage = "Download updated successfully.";
        } else {
            $errors['general'] = 'Failed to update download. Please try again.';
        }
        $stmt->close();
    }
}

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Edit Download</h1>
        <p class="page-subtitle">Update resource details</p>
    </div>
    <div class="page-actions">
        <a href="downloads.php" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Downloads
        </a>
    </div>
</div>

<?php if (isset($successMessage)): ?>
    <div class="alert alert-success" data-auto-hide>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span><?php echo htmlspecialchars($successMessage); ?></span>
    </div>
<?php endif; ?>

<?php if (isset($errors['general'])): ?>
    <div class="alert alert-danger">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span><?php echo htmlspecialchars($errors['general']); ?></span>
    </div>
<?php endif; ?>

<form method="POST" data-validate>
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Main Content -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Resource Details</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="title" class="form-label">
                        Title <span class="required">*</span>
                    </label>
                    <input type="text" id="title" name="title" class="form-control <?php echo isset($errors['title']) ? 'error' : ''; ?>" 
                           placeholder="Enter download title" required
                           value="<?php echo htmlspecialchars($formData['title']); ?>">
                    <?php if (isset($errors['title'])): ?>
                        <span class="form-error"><?php echo $errors['title']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4"
                              placeholder="Enter a description for this resource"><?php echo htmlspecialchars($formData['description']); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="file_path" class="form-label">
                            File Path <span class="required">*</span>
                        </label>
                        <input type="text" id="file_path" name="file_path" class="form-control <?php echo isset($errors['file_path']) ? 'error' : ''; ?>" 
                               placeholder="/downloads/brochures/file.pdf" required
                               value="<?php echo htmlspecialchars($formData['file_path']); ?>">
                        <span class="form-hint">Relative path to the downloadable file</span>
                        <?php if (isset($errors['file_path'])): ?>
                            <span class="form-error"><?php echo $errors['file_path']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="file_name" class="form-label">
                            File Name <span class="required">*</span>
                        </label>
                        <input type="text" id="file_name" name="file_name" class="form-control <?php echo isset($errors['file_name']) ? 'error' : ''; ?>" 
                               placeholder="document.pdf" required
                               value="<?php echo htmlspecialchars($formData['file_name']); ?>">
                        <?php if (isset($errors['file_name'])): ?>
                            <span class="form-error"><?php echo $errors['file_name']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="file_type" class="form-label">File Type</label>
                        <select id="file_type" name="file_type" class="form-control form-select">
                            <option value="PDF" <?php echo $formData['file_type'] == 'PDF' ? 'selected' : ''; ?>>PDF</option>
                            <option value="DOC" <?php echo $formData['file_type'] == 'DOC' ? 'selected' : ''; ?>>DOC</option>
                            <option value="DOCX" <?php echo $formData['file_type'] == 'DOCX' ? 'selected' : ''; ?>>DOCX</option>
                            <option value="XLS" <?php echo $formData['file_type'] == 'XLS' ? 'selected' : ''; ?>>XLS</option>
                            <option value="XLSX" <?php echo $formData['file_type'] == 'XLSX' ? 'selected' : ''; ?>>XLSX</option>
                            <option value="PPT" <?php echo $formData['file_type'] == 'PPT' ? 'selected' : ''; ?>>PPT</option>
                            <option value="PPTX" <?php echo $formData['file_type'] == 'PPTX' ? 'selected' : ''; ?>>PPTX</option>
                            <option value="ZIP" <?php echo $formData['file_type'] == 'ZIP' ? 'selected' : ''; ?>>ZIP</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="file_size" class="form-label">File Size</label>
                        <input type="text" id="file_size" name="file_size" class="form-control" 
                               placeholder="e.g., 2.5 MB"
                               value="<?php echo htmlspecialchars($formData['file_size']); ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div>
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Publish</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="category_id" class="form-label">
                            Category <span class="required">*</span>
                        </label>
                        <select id="category_id" name="category_id" class="form-control form-select <?php echo isset($errors['category_id']) ? 'error' : ''; ?>" required>
                            <option value="">Select Category</option>
                            <?php while ($cat = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $formData['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <?php if (isset($errors['category_id'])): ?>
                            <span class="form-error"><?php echo $errors['category_id']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="published_date" class="form-label">Published Date</label>
                        <input type="date" id="published_date" name="published_date" class="form-control"
                               value="<?php echo htmlspecialchars($formData['published_date']); ?>">
                    </div>
                    
                    <div class="form-group" style="display: flex; align-items: center; justify-content: space-between;">
                        <label class="form-label" style="margin-bottom: 0;">Active</label>
                        <label class="toggle-switch">
                            <input type="checkbox" name="is_active" <?php echo $formData['is_active'] ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="form-group" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0;">
                        <label class="form-label" style="margin-bottom: 0;">Featured</label>
                        <label class="toggle-switch">
                            <input type="checkbox" name="is_featured" <?php echo $formData['is_featured'] ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
                <div class="card-footer" style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Update Download
                    </button>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistics</h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--admin-gray-200);">
                        <span style="color: var(--admin-gray-500);">Downloads</span>
                        <strong><?php echo number_format($download['download_count']); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--admin-gray-200);">
                        <span style="color: var(--admin-gray-500);">Created</span>
                        <strong><?php echo date('M j, Y', strtotime($download['created_at'])); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--admin-gray-500);">Last Updated</span>
                        <strong><?php echo date('M j, Y', strtotime($download['updated_at'])); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
