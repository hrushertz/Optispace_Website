<?php
/**
 * Live Projects Page Banner Management (Multi-Banner)
 */
$pageTitle = "Live Projects Page Banner Settings";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';
require_once __DIR__ . '/../includes/config.php';

$conn = getDBConnection();
$admin = getCurrentAdmin();
$success = '';
$error = '';
$pageName = 'live-projects';

// Action Handler
$action = $_GET['action'] ?? 'list';
$editId = $_GET['id'] ?? null;

// Helper to delete banner
if ($action === 'delete' && $editId) {
    $stmt = $conn->prepare("DELETE FROM banner_settings WHERE id = ? AND page_name = ?");
    $stmt->bind_param("is", $editId, $pageName);
    if ($stmt->execute()) {
        header("Location: banner-live-projects.php?success=" . urlencode("Banner deleted successfully."));
        exit;
    } else {
        $error = "Failed to delete banner.";
    }
}

// Handle POST (Add/Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $eyebrow = trim($_POST['eyebrow_text'] ?? '');
        $heading = trim($_POST['heading_html'] ?? '');
        $subheading = trim($_POST['subheading'] ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $bannerId = $_POST['banner_id'] ?? '';

        $imagePath = '';
        $uploadOk = true;

        if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/images/banners/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileInfo = pathinfo($_FILES['banner_image']['name']);
            $extension = strtolower($fileInfo['extension']);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extension, $allowedExtensions)) {
                $error = 'Invalid file type. Only JPG, PNG, and WebP are allowed.';
                $uploadOk = false;
            } else {
                $newFilename = 'live-banner-' . uniqid() . '.' . $extension;
                $targetFile = $uploadDir . $newFilename;

                if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $targetFile)) {
                    $imagePath = 'assets/images/banners/' . $newFilename;
                } else {
                    $error = 'Failed to upload image. Please check directory permissions.';
                    $uploadOk = false;
                }
            }
        }

        if ($uploadOk) {
            if ($bannerId) {
                if (empty($imagePath)) {
                    $q = $conn->query("SELECT image_path FROM banner_settings WHERE id = " . (int) $bannerId);
                    if ($row = $q->fetch_assoc()) {
                        $imagePath = $row['image_path'];
                    }
                }

                $stmt = $conn->prepare("UPDATE banner_settings SET eyebrow_text = ?, heading_html = ?, subheading = ?, image_path = ?, sort_order = ?, is_active = ?, updated_at = NOW() WHERE id = ?");
                $stmt->bind_param("ssssiii", $eyebrow, $heading, $subheading, $imagePath, $sortOrder, $isActive, $bannerId);

                if ($stmt->execute()) {
                    header("Location: banner-live-projects.php?success=" . urlencode('Banner updated successfully.'));
                    exit;
                } else {
                    $error = 'Database error: ' . $conn->error;
                }
            } else {
                $stmt = $conn->prepare("INSERT INTO banner_settings (page_name, eyebrow_text, heading_html, subheading, image_path, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssii", $pageName, $eyebrow, $heading, $subheading, $imagePath, $sortOrder, $isActive);

                if ($stmt->execute()) {
                    header("Location: banner-live-projects.php?success=" . urlencode('New banner added successfully.'));
                    exit;
                } else {
                    $error = 'Database error: ' . $conn->error;
                }
            }
        }
    }
}

// Pre-fill form if editing
$editData = [
    'id' => '',
    'image_path' => '',
    'eyebrow_text' => '',
    'heading_html' => '',
    'subheading' => '',
    'sort_order' => 0,
    'is_active' => 1
];

if (($action === 'edit' || $action === 'add') && $editId) {
    $stmt = $conn->prepare("SELECT * FROM banner_settings WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $editData = $res->fetch_assoc();
    }
}

// Fetch List
$bannersList = [];
$listQ = $conn->prepare("SELECT * FROM banner_settings WHERE page_name = ? ORDER BY sort_order ASC, created_at DESC");
$listQ->bind_param("s", $pageName);
$listQ->execute();
$res = $listQ->get_result();
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $bannersList[] = $r;
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Live Projects Banners</h1>
        <p class="page-subtitle">Manage hero section sliders</p>
    </div>
    <?php if ($action === 'list'): ?>
        <a href="banner-live-projects.php?action=add" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                style="width: 18px; height: 18px; margin-right: 5px;">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add New Banner
        </a>
    <?php else: ?>
        <a href="banner-live-projects.php" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                style="width: 18px; height: 18px; margin-right: 5px;">
                <path d="M19 12H5"></path>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"
        style="margin-bottom: 1.5rem; padding: 1rem 1.25rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 8px; color: #059669;">
        <strong>Success!</strong>
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"
        style="margin-bottom: 1.5rem; padding: 1rem 1.25rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; color: #DC2626;">
        <strong>Error!</strong>
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
    <div class="admin-card">
        <div class="admin-card-body" style="padding: 0;">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 1rem;">Image</th>
                        <th style="padding: 1rem;">Heading</th>
                        <th style="padding: 1rem;">Order</th>
                        <th style="padding: 1rem;">Status</th>
                        <th style="padding: 1rem; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bannersList)): ?>
                        <tr>
                            <td colspan="5" style="padding: 2rem; text-align: center; color: #666;">No banners found. Create
                                one!</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bannersList as $b): ?>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 1rem;">
                                    <?php if ($b['image_path']): ?>
                                        <img src="../<?php echo htmlspecialchars($b['image_path']); ?>" alt="Banner"
                                            style="width: 80px; height: 45px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <span style="color: #999;">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <div style="font-weight: 500; font-size: 0.95rem;">
                                        <?php echo strip_tags($b['heading_html']); ?>
                                    </div>
                                    <div style="font-size: 0.85rem; color: #777;">
                                        <?php echo $b['eyebrow_text']; ?>
                                    </div>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php echo $b['sort_order']; ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php if ($b['is_active']): ?>
                                        <span
                                            style="background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 100px; font-size: 0.75rem; font-weight: 600;">Active</span>
                                    <?php else: ?>
                                        <span
                                            style="background: #f3f4f6; color: #4b5563; padding: 2px 8px; border-radius: 100px; font-size: 0.75rem; font-weight: 600;">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem; text-align: right;">
                                    <a href="banner-live-projects.php?action=edit&id=<?php echo $b['id']; ?>" class="btn-sm"
                                        style="color: #2563eb; margin-right: 10px; text-decoration: none;">Edit</a>
                                    <a href="banner-live-projects.php?action=delete&id=<?php echo $b['id']; ?>" class="btn-sm"
                                        style="color: #dc2626; text-decoration: none;"
                                        onclick="return confirm('Are you sure?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>
                <?php echo $editData['id'] ? 'Edit Banner' : 'Create New Banner'; ?>
            </h3>
        </div>
        <div class="admin-card-body">
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                <input type="hidden" name="banner_id" value="<?php echo htmlspecialchars($editData['id']); ?>">

                <div class="form-group row">
                    <div class="col-8">
                        <label for="banner_image">Banner Image (1920x850px)</label>
                        <input type="file" name="banner_image" id="banner_image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Leave empty to keep current image. If no image is set, the
                            default gradient will be used.</small>

                        <?php if (!empty($editData['image_path'])): ?>
                            <div
                                style="margin-top: 1rem; border: 1px solid #ddd; padding: 5px; border-radius: 4px; display: inline-block;">
                                <img src="<?php echo htmlspecialchars('../' . $editData['image_path']); ?>" alt="Current Banner"
                                    style="max-width: 100%; height: auto; max-height: 200px;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="eyebrow_text">Eyebrow Text</label>
                    <input type="text" name="eyebrow_text" id="eyebrow_text" class="form-control"
                        value="<?php echo htmlspecialchars($editData['eyebrow_text']); ?>"
                        placeholder="e.g., Currently In Progress">
                </div>

                <div class="form-group">
                    <label for="heading_html">Main Heading</label>
                    <textarea name="heading_html" id="heading_html" class="form-control" rows="2"
                        placeholder="Title text"><?php echo htmlspecialchars($editData['heading_html']); ?></textarea>
                    <small>Use &lt;span&gt; tags to highlight text.</small>
                </div>

                <div class="form-group">
                    <label for="subheading">Subheading / Description</label>
                    <textarea name="subheading" id="subheading" class="form-control"
                        rows="4"><?php echo htmlspecialchars($editData['subheading']); ?></textarea>
                </div>

                <div style="display: flex; gap: 2rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="flex: 1;">
                        <label for="sort_order">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control"
                            value="<?php echo (int) $editData['sort_order']; ?>">
                        <small>Lower numbers appear first.</small>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Status</label>
                        <div style="margin-top: 0.5rem;">
                            <label style="font-weight: normal; cursor: pointer;">
                                <input type="checkbox" name="is_active" value="1" <?php echo $editData['is_active'] ? 'checked' : ''; ?>>
                                Active (Visible on Page)
                            </label>
                        </div>
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
                        Save Banner
                    </button>
                    <a href="banner-live-projects.php" class="btn btn-secondary"
                        style="margin-left: 10px; background: #e2e8f0; color: #475569;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<style>
    .admin-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .admin-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
    }

    .admin-card-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
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
        background: #d4851c;
    }

    .btn-secondary {
        background: #fff;
        border: 1px solid #ddd;
        color: #475569;
    }
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>