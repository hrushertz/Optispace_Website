<?php
require_once 'auth_check.php';
require_once '../database/db_config.php';

$page_title = 'Resources';
$current_page = 'resources';
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    
    if (!empty($title) && isset($_FILES['resource_file'])) {
        if ($_FILES['resource_file']['error'] === 0) {
            $upload_dir = '../uploads/resources/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $original_name = $_FILES['resource_file']['name'];
            $file_size = $_FILES['resource_file']['size'];
            $file_extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
            
            // Generate unique filename
            $new_filename = uniqid('resource_') . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['resource_file']['tmp_name'], $upload_path)) {
                $conn = getDBConnection();
                
                $file_path = 'uploads/resources/' . $new_filename;
                $file_type = $_FILES['resource_file']['type'];
                
                $stmt = $conn->prepare("INSERT INTO resources (title, description, file_name, file_path, file_type, file_size, category) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssis", $title, $description, $original_name, $file_path, $file_type, $file_size, $category);
                
                if ($stmt->execute()) {
                    $success = 'Resource uploaded successfully!';
                    $_POST = [];
                } else {
                    $error = 'Error saving resource: ' . $conn->error;
                }
                
                $stmt->close();
                $conn->close();
            } else {
                $error = 'Failed to upload file';
            }
        } else {
            $error = 'Please select a file to upload';
        }
    } else {
        $error = 'Please fill in all required fields';
    }
}

// Get all resources
$conn = getDBConnection();
$resources_result = $conn->query("SELECT * FROM resources ORDER BY created_at DESC");
$resources = [];
if ($resources_result) {
    while ($row = $resources_result->fetch_assoc()) {
        $resources[] = $row;
    }
}
$conn->close();

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin Panel</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include 'includes/topbar.php'; ?>
            
            <div class="page-content">
                <div class="page-header">
                    <h1>Resources Management</h1>
                    <p class="page-subtitle">Upload and manage downloadable resources</p>
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
                    <h2 style="margin-bottom: 1.5rem;">Upload New Resource</h2>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-input" required value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-textarea" placeholder="Brief description of the resource..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="">Select Category</option>
                                <option value="Brochures" <?php echo (($_POST['category'] ?? '') === 'Brochures') ? 'selected' : ''; ?>>Brochures</option>
                                <option value="Case Studies" <?php echo (($_POST['category'] ?? '') === 'Case Studies') ? 'selected' : ''; ?>>Case Studies</option>
                                <option value="White Papers" <?php echo (($_POST['category'] ?? '') === 'White Papers') ? 'selected' : ''; ?>>White Papers</option>
                                <option value="Guides" <?php echo (($_POST['category'] ?? '') === 'Guides') ? 'selected' : ''; ?>>Guides</option>
                                <option value="Templates" <?php echo (($_POST['category'] ?? '') === 'Templates') ? 'selected' : ''; ?>>Templates</option>
                                <option value="Other" <?php echo (($_POST['category'] ?? '') === 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">File * (PDF, DOC, DOCX, XLS, XLSX, ZIP)</label>
                            <input type="file" name="resource_file" class="form-input" required 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.ppt,.pptx">
                            <small style="color: #64748b; display: block; margin-top: 0.5rem;">
                                Maximum file size: 10MB
                            </small>
                        </div>
                        
                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" class="btn-primary">Upload Resource</button>
                            <a href="dashboard.php" class="btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
                
                <?php if (count($resources) > 0): ?>
                    <div style="margin-top: 3rem;">
                        <h2 style="margin-bottom: 1.5rem;">Uploaded Resources</h2>
                        <div class="data-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>File Name</th>
                                        <th>Size</th>
                                        <th>Downloads</th>
                                        <th>Uploaded</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($resources as $resource): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($resource['title']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($resource['category'] ?: '-'); ?></td>
                                            <td><?php echo htmlspecialchars($resource['file_name']); ?></td>
                                            <td><?php echo formatFileSize($resource['file_size']); ?></td>
                                            <td><?php echo $resource['download_count']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($resource['created_at'])); ?></td>
                                            <td>
                                                <a href="../<?php echo htmlspecialchars($resource['file_path']); ?>" target="_blank" 
                                                   style="color: #3b82f6; text-decoration: none; margin-right: 1rem;">Download</a>
                                                <a href="delete-resource.php?id=<?php echo $resource['id']; ?>" 
                                                   style="color: #ef4444; text-decoration: none;" 
                                                   onclick="return confirm('Are you sure?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
