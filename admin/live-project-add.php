<?php
/**
 * Admin Add Live Project
 */

$pageTitle = "Add Live Project";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

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
    $imagePath = null;
    $thumbnailPath = null;

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
                $imagePath = 'assets/img/live-projects/' . $filename;
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("
            INSERT INTO live_projects 
            (title, slug, client_name, description, content, project_type, industry, location,
             start_date, expected_completion, progress_percentage, current_phase,
             image_path, thumbnail_path, highlight_1, highlight_2, highlight_3,
             is_featured, is_active, sort_order, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $adminId = $_SESSION['admin_id'];
        $stmt->bind_param(
            "ssssssssssissssssiiis",
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
            $thumbnailPath,
            $highlight1,
            $highlight2,
            $highlight3,
            $isFeatured,
            $isActive,
            $sortOrder,
            $adminId
        );

        if ($stmt->execute()) {
            $newId = $conn->insert_id;
            logAdminActivity($_SESSION['admin_id'], 'create', 'live_projects', $newId, 'Created live project: ' . $title);
            $stmt->close();
            $conn->close();
            header('Location: live-projects.php?success=created');
            exit;
        } else {
            $errors[] = "Failed to create live project. Please try again.";
        }
        $stmt->close();
    }
    // If errors, preserve POST data
    $project = $_POST;
    // Map POST data to match keys expected by form
    $project['highlight_1'] = $_POST['highlight_1'];
    $project['highlight_2'] = $_POST['highlight_2'];
    $project['highlight_3'] = $_POST['highlight_3'];
}

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <nav class="breadcrumb">
            <a href="live-projects.php">Live Projects</a>
            <span class="separator">/</span>
            <span>Add New</span>
        </nav>
        <h1 class="page-title">Add Live Project</h1>
    </div>
</div>

<?php
// Initialize empty project if not set (first load)
if (!isset($project)) {
    $project = [
        'is_active' => 1, // Default to active
        'sort_order' => 0
    ];
}

$isEdit = false;
include __DIR__ . '/includes/forms/live-project-form.php';
?>

<?php include __DIR__ . '/includes/footer.php'; ?>