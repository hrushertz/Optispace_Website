<?php
/**
 * Process Flow Image Management (Single Image)
 */
$pageTitle = "Process Flow Image";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';
require_once __DIR__ . '/../includes/config.php';

$conn = getDBConnection();
$admin = getCurrentAdmin();
$success = '';
$error = '';
$pageName = 'home_process'; // Unique identifier for this single record

// Fetch existing record
$existingData = [];
$stmt = $conn->prepare("SELECT * FROM banner_settings WHERE page_name = ? LIMIT 1");
$stmt->bind_param("s", $pageName);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $existingData = $res->fetch_assoc();
} else {
    // defaults
    $existingData = [
        'id' => '',
        'image_path' => '',
        'eyebrow_text' => 'Our Approach',
        'heading_html' => 'What is Lean Factory Building?',
        'subheading' => 'Lean Factory Building (LFB) is a specialized architectural approach that reverses the conventional sequence.',
        'is_active' => 1
    ];
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $eyebrow = trim($_POST['eyebrow_text'] ?? '');
        $heading = trim($_POST['heading_html'] ?? '');
        $subheading = trim($_POST['subheading'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $imagePath = $existingData['image_path'];
        $uploadOk = true;

        if (isset($_FILES['process_image']) && $_FILES['process_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/images/home/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileInfo = pathinfo($_FILES['process_image']['name']);
            $extension = strtolower($fileInfo['extension']);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extension, $allowedExtensions)) {
                $error = 'Invalid file type. Only JPG, PNG, and WebP are allowed.';
                $uploadOk = false;
            } else {
                $newFilename = 'process-flow-' . uniqid() . '.' . $extension;
                $targetFile = $uploadDir . $newFilename;

                if (move_uploaded_file($_FILES['process_image']['tmp_name'], $targetFile)) {
                    $imagePath = 'assets/images/home/' . $newFilename;

                    // Optional: Delete old image if it exists and isn't used elsewhere
                    // (Skipping for safety in this iteration)
                } else {
                    $error = 'Failed to upload image. Please check directory permissions.';
                    $uploadOk = false;
                }
            }
        }

        if ($uploadOk) {
            if ($existingData['id']) {
                // Update
                $stmt = $conn->prepare("UPDATE banner_settings SET eyebrow_text = ?, heading_html = ?, subheading = ?, image_path = ?, is_active = ?, updated_at = NOW() WHERE id = ?");
                $stmt->bind_param("ssssii", $eyebrow, $heading, $subheading, $imagePath, $isActive, $existingData['id']);
            } else {
                // Insert
                $stmt = $conn->prepare("INSERT INTO banner_settings (page_name, eyebrow_text, heading_html, subheading, image_path, is_active) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $pageName, $eyebrow, $heading, $subheading, $imagePath, $isActive);
            }

            if ($stmt->execute()) {
                $success = 'Settings updated successfully.';
                // Refresh data
                $stmt = $conn->prepare("SELECT * FROM banner_settings WHERE page_name = ? LIMIT 1");
                $stmt->bind_param("s", $pageName);
                $stmt->execute();
                $existingData = $stmt->get_result()->fetch_assoc();
            } else {
                $error = 'Database error: ' . $conn->error;
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Home Process Flow Image</h1>
        <p class="page-subtitle">Manage the "What is Lean Factory Building" section visual</p>
    </div>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"
        style="margin-bottom: 1.5rem; padding: 1rem 1.25rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 8px; color: #059669;">
        <strong>Success!</strong>
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"
        style="margin-bottom: 1.5rem; padding: 1rem 1.25rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; color: #DC2626;">
        <strong>Error!</strong>
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-body">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">

            <div class="form-group row">
                <div class="col-8">
                    <label for="process_image">Process Flow Image (1080x1350px)</label>
                    <input type="file" name="process_image" id="process_image" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Upload a portrait image (~4:5 ratio). Leave empty to keep
                        current image.</small>

                    <?php if (!empty($existingData['image_path'])): ?>
                        <div
                            style="margin-top: 1rem; border: 1px solid #ddd; padding: 5px; border-radius: 4px; display: inline-block;">
                            <img src="<?php echo htmlspecialchars('../' . $existingData['image_path']); ?>"
                                alt="Current Image" style="max-width: 200px; height: auto;">
                        </div>
                    <?php else: ?>
                        <div style="margin-top: 1rem; color: #999;">No image set currently.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="eyebrow_text">Eyebrow Text</label>
                <input type="text" name="eyebrow_text" id="eyebrow_text" class="form-control"
                    value="<?php echo htmlspecialchars($existingData['eyebrow_text']); ?>">
            </div>

            <div class="form-group">
                <label for="heading_html">Main Heading</label>
                <input type="text" name="heading_html" id="heading_html" class="form-control"
                    value="<?php echo htmlspecialchars($existingData['heading_html']); ?>">
            </div>

            <div class="form-group">
                <label for="subheading">Description</label>
                <textarea name="subheading" id="subheading" class="form-control"
                    rows="4"><?php echo htmlspecialchars($existingData['subheading']); ?></textarea>
            </div>

            <div class="form-group">
                <div style="margin-top: 0.5rem;">
                    <label style="font-weight: normal; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" <?php echo $existingData['is_active'] ? 'checked' : ''; ?>>
                        Active (Visible on Page)
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        style="width: 18px; height: 18px; margin-right: 5px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .admin-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .admin-card-body {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.95rem;
    }

    .form-text {
        font-size: 0.85rem;
        color: #777;
        margin-top: 0.25rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        text-decoration: none;
    }

    .btn-primary {
        background: #E99431;
        color: white;
    }

    .btn-primary:hover {
        background: #d88320;
    }
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>