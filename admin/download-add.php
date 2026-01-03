<?php
/**
 * Admin Add Download
 */

$pageTitle = "Add Download";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

// Helper function to format file size
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

// Get categories
$categories = $conn->query("SELECT * FROM download_categories WHERE is_active = 1 ORDER BY sort_order");

$errors = [];
$formData = [
    'title' => '',
    'description' => '',
    'category_id' => '',
    'file_path' => '',
    'file_name' => '',
    'file_type' => 'PDF',
    'file_size' => '',
    'published_date' => date('Y-m-d'),
    'is_active' => 1,
    'is_featured' => 0
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload
    $uploadedFilePath = '';
    $uploadedFileName = '';
    $uploadedFileType = '';
    $uploadedFileSize = '';
    
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file_upload'];
        $fileName = basename($file['name']);
        $fileExt = strtoupper(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileSize = $file['size'];
        
        // Create upload directory if it doesn't exist
        $uploadDir = __DIR__ . '/../downloads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $uniqueName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $fileName);
        $uploadPath = $uploadDir . $uniqueName;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $uploadedFilePath = 'downloads/' . $uniqueName;
            $uploadedFileName = $fileName;
            $uploadedFileType = $fileExt;
            $uploadedFileSize = formatFileSize($fileSize);
        } else {
            $errors['file_upload'] = 'Failed to save uploaded file. Check directory permissions.';
        }
    } elseif (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Handle upload errors
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds maximum upload size.',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds maximum form size.',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'File upload blocked by extension.'
        ];
        $errorCode = $_FILES['file_upload']['error'];
        $errors['file_upload'] = $uploadErrors[$errorCode] ?? 'Unknown upload error occurred.';
    }
    
    // Get form data
    $formData = [
        'title' => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'category_id' => (int)($_POST['category_id'] ?? 0),
        'file_path' => $uploadedFilePath ?: trim($_POST['file_path'] ?? ''),
        'file_name' => $uploadedFileName ?: trim($_POST['file_name'] ?? ''),
        'file_type' => $uploadedFileType ?: trim($_POST['file_type'] ?? 'PDF'),
        'file_size' => $uploadedFileSize ?: trim($_POST['file_size'] ?? ''),
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
    // Only require file path/name if no file was uploaded
    if (empty($uploadedFilePath)) {
        if (empty($formData['file_path'])) {
            $errors['file_path'] = 'Please upload a file or enter file path manually.';
        }
        if (empty($formData['file_name'])) {
            $errors['file_name'] = 'Please upload a file or enter file name manually.';
        }
    }
    
    // If no errors, insert
    if (empty($errors)) {
        $stmt = $conn->prepare("
            INSERT INTO downloads (category_id, title, description, file_path, file_name, file_type, file_size, published_date, is_active, is_featured, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param(
            "isssssssiis",
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
            $_SESSION['admin_id']
        );
        
        if ($stmt->execute()) {
            $newId = $conn->insert_id;
            logAdminActivity($_SESSION['admin_id'], 'create', 'downloads', $newId, 'Created download: ' . $formData['title']);
            
            header('Location: downloads.php?success=created');
            exit;
        } else {
            $errors['general'] = 'Failed to create download. Please try again.';
        }
        $stmt->close();
    }
}

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Add New Download</h1>
        <p class="page-subtitle">Create a new downloadable resource</p>
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

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul style="margin: 0.5rem 0 0 0;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" data-validate>
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
                
                <div class="form-group">
                    <label class="form-label">
                        Upload File <span class="required">*</span>
                    </label>
                    <div class="file-upload-area" id="fileUploadArea">
                        <input type="file" id="fileInput" name="file_upload" class="file-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip">
                        <div class="file-upload-content">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <p class="upload-text"><strong>Click to upload</strong> or drag and drop</p>
                            <p class="upload-hint">PDF, DOC, XLS, PPT, ZIP (MAX. 10MB)</p>
                        </div>
                        <div class="file-upload-preview" id="filePreview" style="display: none;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                            <div class="file-info">
                                <p class="file-name" id="fileName"></p>
                                <p class="file-size" id="fileSize"></p>
                            </div>
                            <button type="button" class="btn-remove-file" id="removeFile">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <?php if (isset($errors['file_path'])): ?>
                        <span class="form-error"><?php echo $errors['file_path']; ?></span>
                    <?php endif; ?>
                    <span class="form-hint">Or enter file details manually below</span>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="file_path" class="form-label">File Path</label>
                        <input type="text" id="file_path" name="file_path" class="form-control" 
                               placeholder="/downloads/brochures/file.pdf"
                               value="<?php echo htmlspecialchars($formData['file_path']); ?>">
                        <span class="form-hint">Leave empty if uploading a file</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="file_name" class="form-label">File Name</label>
                        <input type="text" id="file_name" name="file_name" class="form-control" 
                               placeholder="document.pdf"
                               value="<?php echo htmlspecialchars($formData['file_name']); ?>">
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
                        Save Download
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
