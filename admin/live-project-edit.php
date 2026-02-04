<?php
/**
 * Admin Edit Live Project
 */

$pageTitle = "Edit Live Project";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

// Get project ID
$projectId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($projectId <= 0) {
    header('Location: live-projects.php');
    exit;
}

// Get existing project
$stmt = $conn->prepare("SELECT * FROM live_projects WHERE id = ?");
$stmt->bind_param("i", $projectId);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$project) {
    header('Location: live-projects.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $slug = trim($_POST['slug']);
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }
    $clientName = trim($_POST['client_name']);
    $description = trim($_POST['description']);
    $content = $_POST['content'];
    $projectType = trim($_POST['project_type']);
    $industry = trim($_POST['industry']);
    $location = trim($_POST['location']);
    $startDate = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $expectedCompletion = !empty($_POST['expected_completion']) ? $_POST['expected_completion'] : null;
    $progressPercentage = (int) $_POST['progress_percentage'];
    $currentPhase = trim($_POST['current_phase']);
    $highlight1 = trim($_POST['highlight_1']);
    $highlight2 = trim($_POST['highlight_2']);
    $highlight3 = trim($_POST['highlight_3']);
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    $sortOrder = (int) $_POST['sort_order'];

    $errors = [];

    if (empty($title)) {
        $errors[] = "Title is required.";
    }

    // Validate progress percentage
    if ($progressPercentage < 0 || $progressPercentage > 100) {
        $errors[] = "Progress percentage must be between 0 and 100.";
    }

    // Handle image upload
    $imagePath = $project['image_path'];

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../assets/img/live-projects/';
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
            $filename = 'live_project_' . time() . '_' . uniqid() . '.' . $ext;
            $uploadPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                // Delete old image if exists
                if ($project['image_path'] && file_exists(__DIR__ . '/../' . $project['image_path'])) {
                    unlink(__DIR__ . '/../' . $project['image_path']);
                }
                $imagePath = 'assets/img/live-projects/' . $filename;
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }

    // Handle image removal
    if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
        if ($project['image_path'] && file_exists(__DIR__ . '/../' . $project['image_path'])) {
            unlink(__DIR__ . '/../' . $project['image_path']);
        }
        $imagePath = null;
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("
            UPDATE live_projects SET
            title = ?, slug = ?, client_name = ?, description = ?, content = ?, project_type = ?, industry = ?, location = ?,
            start_date = ?, expected_completion = ?, progress_percentage = ?, current_phase = ?,
            image_path = ?, highlight_1 = ?, highlight_2 = ?, highlight_3 = ?,
            is_featured = ?, is_active = ?, sort_order = ?
            WHERE id = ?
        ");
        $stmt->bind_param(
            "ssssssssssisssssiiii",
            $title,
            $slug,
            $clientName,
            $description,
            $content,
            $projectType,
            $industry,
            $location,
            $startDate,
            $expectedCompletion,
            $progressPercentage,
            $currentPhase,
            $imagePath,
            $highlight1,
            $highlight2,
            $highlight3,
            $isFeatured,
            $isActive,
            $sortOrder,
            $projectId
        );

        if ($stmt->execute()) {
            logAdminActivity($_SESSION['admin_id'], 'update', 'live_projects', $projectId, 'Updated live project: ' . $title);
            $successMessage = "Live project updated successfully.";

            // Refresh project data
            $stmt->close();
            $stmt = $conn->prepare("SELECT * FROM live_projects WHERE id = ?");
            $stmt->bind_param("i", $projectId);
            $stmt->execute();
            $project = $stmt->get_result()->fetch_assoc();
        } else {
            $errors[] = "Failed to update live project. Please try again.";
            // If errors, update project array with POST data to preserve user input
            $project = array_merge($project, $_POST);
            // Map keys that might be different or needed
            $project['highlight_1'] = $_POST['highlight_1'];
            $project['highlight_2'] = $_POST['highlight_2'];
            $project['highlight_3'] = $_POST['highlight_3'];
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
            <a href="live-projects.php">Live Projects</a>
            <span class="separator">/</span>
            <span>Edit</span>
        </nav>
        <h1 class="page-title">Edit Live Project</h1>
    </div>
</div>

<?php if (isset($successMessage)): ?>
    <div class="alert alert-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        <?php echo htmlspecialchars($successMessage); ?>
    </div>
<?php endif; ?>

<?php
$isEdit = true;
include __DIR__ . '/includes/forms/live-project-form.php';
?>

<?php include __DIR__ . '/includes/footer.php'; ?>