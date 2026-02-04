<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Edit Leader';

// Get database connection
$conn = getDBConnection();

if (!isset($_GET['id'])) {
    header('Location: leadership.php');
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM leadership WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$leader = $result->fetch_assoc();
$stmt->close();

if (!$leader) {
    header('Location: leadership.php');
    exit;
}

$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $sub_designation = trim($_POST['sub_designation'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $quote = trim($_POST['quote'] ?? '');
    $philosophy_content = trim($_POST['philosophy_content'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    // Process List Items
    function processListInput($input)
    {
        $lines = explode("\n", $input);
        $items = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $items[] = $line;
            }
        }
        return json_encode($items);
    }

    $education_items = processListInput($_POST['education_items'] ?? '');
    $experience_items = processListInput($_POST['experience_items'] ?? '');
    $recognition_items = processListInput($_POST['recognition_items'] ?? '');

    // Validation
    if (empty($name)) {
        $formError = 'Name is required';
    } elseif (empty($designation)) {
        $formError = 'Designation is required';
    } else {
        // Handle photo upload
        $imagePath = $leader['image_path'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $fileType = $_FILES['image']['type'];

            if (!in_array($fileType, $allowedTypes)) {
                $formError = 'Only JPG, PNG, and WebP images are allowed';
            } else {
                $uploadDir = __DIR__ . '/../assets/images/team/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('leader_') . '.' . $fileExtension;
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    // Delete old image if exists
                    if ($leader['image_path'] && file_exists(__DIR__ . '/../' . $leader['image_path'])) {
                        unlink(__DIR__ . '/../' . $leader['image_path']);
                    }
                    $imagePath = 'assets/images/team/' . $fileName;
                } else {
                    $formError = 'Error uploading photo';
                }
            }
        }

        if (empty($formError)) {
            // Update leader
            $stmt = $conn->prepare("UPDATE leadership SET name = ?, designation = ?, sub_designation = ?, location = ?, image_path = ?, quote = ?, education_items = ?, experience_items = ?, recognition_items = ?, skills = ?, philosophy_content = ?, is_active = ? WHERE id = ?");
            $stmt->bind_param("sssssssssssii", $name, $designation, $sub_designation, $location, $imagePath, $quote, $education_items, $experience_items, $recognition_items, $skills, $philosophy_content, $isActive, $id);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Leader updated successfully';
                header('Location: leadership.php');
                exit;
            } else {
                $formError = 'Error updating leader: ' . $conn->error;
            }
            $stmt->close();
        }
    }
}

// Convert JSON to newline string for form
function jsonTopL($json)
{
    if (empty($json))
        return '';
    $arr = json_decode($json, true);
    if (!is_array($arr))
        return '';
    return implode("\n", $arr);
}

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    style="width: 24px; height: 24px; vertical-align: middle; margin-right: 0.5rem;">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                Edit Leader
            </h1>
            <p class="page-subtitle">Edit leadership profile</p>
        </div>
        <div class="page-actions">
            <a href="leadership.php" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    style="width: 16px; height: 16px;">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <?php if ($formError): ?>
        <div class="alert alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            <?php echo htmlspecialchars($formError); ?>
        </div>
    <?php endif; ?>

    <div class="content-card">
        <form method="post" enctype="multipart/form-data" class="admin-form">
            <div class="form-grid">
                <!-- Basic Info -->
                <div class="form-group">
                    <label for="name">Full Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required
                        value="<?php echo htmlspecialchars($_POST['name'] ?? $leader['name']); ?>">
                </div>

                <div class="form-group">
                    <label for="designation">Designation <span class="required">*</span></label>
                    <input type="text" id="designation" name="designation" class="form-control" required
                        value="<?php echo htmlspecialchars($_POST['designation'] ?? $leader['designation']); ?>">
                </div>

                <div class="form-group">
                    <label for="sub_designation">Sub-Designation</label>
                    <input type="text" id="sub_designation" name="sub_designation" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['sub_designation'] ?? $leader['sub_designation']); ?>">
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['location'] ?? $leader['location']); ?>">
                </div>

                <!-- Quote & Philosophy -->
                <div class="form-group span-full">
                    <label for="quote">Quote</label>
                    <textarea id="quote" name="quote" class="form-control"
                        rows="2"><?php echo htmlspecialchars($_POST['quote'] ?? $leader['quote']); ?></textarea>
                </div>

                <div class="form-group span-full">
                    <label for="philosophy_content">Philosophy & Approach</label>
                    <textarea id="philosophy_content" name="philosophy_content" class="form-control"
                        rows="4"><?php echo htmlspecialchars($_POST['philosophy_content'] ?? $leader['philosophy_content']); ?></textarea>
                    <small class="form-text">You can use &lt;p&gt;, &lt;strong&gt;, etc.</small>
                </div>

                <!-- Lists -->
                <div class="form-group span-full">
                    <label for="skills">Skills (Comma Separated)</label>
                    <input type="text" id="skills" name="skills" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['skills'] ?? $leader['skills']); ?>">
                </div>

                <div class="form-group span-full">
                    <label for="education_items">Education (One item per line)</label>
                    <textarea id="education_items" name="education_items" class="form-control"
                        rows="3"><?php echo htmlspecialchars($_POST['education_items'] ?? jsonTopL($leader['education_items'])); ?></textarea>
                </div>

                <div class="form-group span-full">
                    <label for="experience_items">Experience (One item per line)</label>
                    <textarea id="experience_items" name="experience_items" class="form-control"
                        rows="3"><?php echo htmlspecialchars($_POST['experience_items'] ?? jsonTopL($leader['experience_items'])); ?></textarea>
                </div>

                <div class="form-group span-full">
                    <label for="recognition_items">Recognition & Affiliations (One item per line)</label>
                    <textarea id="recognition_items" name="recognition_items" class="form-control"
                        rows="3"><?php echo htmlspecialchars($_POST['recognition_items'] ?? jsonTopL($leader['recognition_items'])); ?></textarea>
                </div>

                <!-- Image -->
                <div class="form-group span-full">
                    <label for="image">Profile Photo</label>
                    <?php if ($leader['image_path']): ?>
                        <div style="margin-bottom: 0.5rem;">
                            <img src="<?php echo htmlspecialchars('../' . $leader['image_path']); ?>" alt="Current Photo"
                                style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="image" name="image" class="form-control"
                        accept="image/jpeg,image/png,image/jpg,image/webp">
                    <small class="form-text">Leave empty to keep current photo. Recommended: 400x400px Square
                        Image.</small>
                </div>

                <div class="form-group span-full">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?php echo ($leader['is_active']) ? 'checked' : ''; ?>>
                        <span>Active (display on website)</span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Leader</button>
                <a href="leadership.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>