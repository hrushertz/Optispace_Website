<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Edit Team Member';

// Get database connection
$conn = getDBConnection();

// Get team member ID
$memberId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($memberId === 0) {
    header('Location: team-members.php');
    exit;
}

// Fetch team member
$stmt = $conn->prepare("SELECT * FROM team_members WHERE id = ?");
$stmt->bind_param("i", $memberId);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();
$stmt->close();

if (!$member) {
    $_SESSION['error_message'] = 'Team member not found';
    header('Location: team-members.php');
    exit;
}

$formError = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $specialties = trim($_POST['specialties'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $linkedinUrl = trim($_POST['linkedin_url'] ?? '');
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    // Validation
    if (empty($name)) {
        $formError = 'Name is required';
    } elseif (empty($role)) {
        $formError = 'Role/Position is required';
    } else {
        // Handle photo upload
        $photoPath = $member['photo_path'];
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $fileType = $_FILES['photo']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $formError = 'Only JPG, PNG, and WebP images are allowed';
            } else {
                $uploadDir = __DIR__ . '/../assets/img/team/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('team_') . '.' . $fileExtension;
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                    // Delete old photo if exists
                    if ($photoPath && file_exists(__DIR__ . '/../' . $photoPath)) {
                        unlink(__DIR__ . '/../' . $photoPath);
                    }
                    $photoPath = 'assets/img/team/' . $fileName;
                } else {
                    $formError = 'Error uploading photo';
                }
            }
        }
        
        // Handle photo deletion
        if (isset($_POST['delete_photo']) && $_POST['delete_photo'] == '1') {
            if ($photoPath && file_exists(__DIR__ . '/../' . $photoPath)) {
                unlink(__DIR__ . '/../' . $photoPath);
            }
            $photoPath = null;
        }
        
        if (empty($formError)) {
            $stmt = $conn->prepare("UPDATE team_members SET name = ?, role = ?, title = ?, description = ?, specialties = ?, email = ?, linkedin_url = ?, photo_path = ?, is_active = ? WHERE id = ?");
            $stmt->bind_param("ssssssssii", $name, $role, $title, $description, $specialties, $email, $linkedinUrl, $photoPath, $isActive, $memberId);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Team member updated successfully';
                header('Location: team-members.php');
                exit;
            } else {
                $formError = 'Error updating team member: ' . $conn->error;
            }
            $stmt->close();
        }
    }
} else {
    // Pre-fill form with existing data
    $_POST = $member;
}

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 0.5rem;">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit Team Member
            </h1>
            <p class="page-subtitle">Update team member details</p>
        </div>
        <div class="page-actions">
            <a href="team-members.php" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <?php if ($formError): ?>
    <div class="alert alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <?php echo htmlspecialchars($formError); ?>
    </div>
    <?php endif; ?>

    <div class="content-card">
        <form method="post" enctype="multipart/form-data" class="admin-form">
            <div class="form-grid">
                <div class="form-group span-full">
                    <label for="name">Full Name <span class="required">*</span></label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-control" 
                           placeholder="e.g., John Doe"
                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                           required>
                    <small class="form-text">The full name of the team member</small>
                </div>

                <div class="form-group">
                    <label for="role">Role/Position <span class="required">*</span></label>
                    <input type="text" 
                           id="role" 
                           name="role" 
                           class="form-control" 
                           placeholder="e.g., Lead Lean Consultant"
                           value="<?php echo htmlspecialchars($_POST['role'] ?? ''); ?>"
                           required>
                    <small class="form-text">Their primary role or position</small>
                </div>

                <div class="form-group">
                    <label for="title">Professional Title/Qualification</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           class="form-control" 
                           placeholder="e.g., Lean Six Sigma Black Belt, B.Tech"
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    <small class="form-text">Professional credentials or qualifications</small>
                </div>

                <div class="form-group span-full">
                    <label for="description">Description</label>
                    <textarea id="description" 
                              name="description" 
                              class="form-control" 
                              rows="4"
                              placeholder="Brief description of background, expertise, and role at OptiSpace..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    <small class="form-text">Highlight key contributions and areas of specialization</small>
                </div>

                <div class="form-group span-full">
                    <label for="specialties">Specialties</label>
                    <input type="text" 
                           id="specialties" 
                           name="specialties" 
                           class="form-control" 
                           placeholder="e.g., Lean Manufacturing, Factory Design, Process Optimization"
                           value="<?php echo htmlspecialchars($_POST['specialties'] ?? ''); ?>">
                    <small class="form-text">Comma-separated list of specialties (will be displayed as tags)</small>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control" 
                           placeholder="email@example.com"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    <small class="form-text">Contact email (will be displayed publicly)</small>
                </div>

                <div class="form-group">
                    <label for="linkedin_url">LinkedIn URL</label>
                    <input type="url" 
                           id="linkedin_url" 
                           name="linkedin_url" 
                           class="form-control" 
                           placeholder="https://linkedin.com/in/profile"
                           value="<?php echo htmlspecialchars($_POST['linkedin_url'] ?? ''); ?>">
                    <small class="form-text">Optional LinkedIn profile link</small>
                </div>

                <div class="form-group span-full">
                    <label>Profile Photo</label>
                    
                    <?php if ($member['photo_path']): ?>
                    <div style="margin-bottom: 1rem;">
                        <img src="<?php echo htmlspecialchars('../' . $member['photo_path']); ?>" 
                             alt="Current photo" 
                             style="max-width: 200px; height: auto; border-radius: 8px; border: 2px solid #E2E8F0;">
                        <div style="margin-top: 0.5rem;">
                            <label class="checkbox-label">
                                <input type="checkbox" name="delete_photo" value="1" id="deletePhotoCheckbox">
                                <span>Delete current photo</span>
                            </label>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <input type="file" 
                           id="photo" 
                           name="photo" 
                           class="form-control" 
                           accept="image/jpeg,image/png,image/jpg,image/webp">
                    <small class="form-text">Upload a new photo to replace the current one (JPG, PNG, or WebP). Recommended: Square image, at least 400x400px</small>
                </div>

                <div class="form-group span-full">
                    <label class="checkbox-label">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               <?php echo (isset($_POST['is_active']) && $_POST['is_active']) ? 'checked' : ''; ?>>
                        <span>Active (display on website)</span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Update Team Member
                </button>
                <a href="team-members.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
// Disable file input when delete checkbox is checked
document.getElementById('deletePhotoCheckbox')?.addEventListener('change', function() {
    const photoInput = document.getElementById('photo');
    if (this.checked) {
        photoInput.disabled = true;
        photoInput.value = '';
    } else {
        photoInput.disabled = false;
    }
});
</script>

<?php include 'includes/footer.php'; ?>
