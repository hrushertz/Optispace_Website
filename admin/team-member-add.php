<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Add Team Member';

// Get database connection
$conn = getDBConnection();

$formError = '';

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
        $photoPath = null;
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
                    $photoPath = 'assets/img/team/' . $fileName;
                } else {
                    $formError = 'Error uploading photo';
                }
            }
        }
        
        if (empty($formError)) {
            // Get max sort order
            $result = $conn->query("SELECT MAX(sort_order) as max_order FROM team_members");
            $row = $result->fetch_assoc();
            $sortOrder = ($row['max_order'] ?? 0) + 1;
            
            // Insert team member
            $stmt = $conn->prepare("INSERT INTO team_members (name, role, title, description, specialties, email, linkedin_url, photo_path, sort_order, is_active, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $userId = $_SESSION['admin_user']['id'];
            $stmt->bind_param("ssssssssiis", $name, $role, $title, $description, $specialties, $email, $linkedinUrl, $photoPath, $sortOrder, $isActive, $userId);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Team member added successfully';
                header('Location: team-members.php');
                exit;
            } else {
                $formError = 'Error adding team member: ' . $conn->error;
            }
            $stmt->close();
        }
    }
}

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 0.5rem;">
                    <line x1="12" y1="8" x2="12" y2="16"/>
                    <line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
                Add New Team Member
            </h1>
            <p class="page-subtitle">Add a team member to display on the Team & Associates page</p>
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
                    <label for="photo">Profile Photo</label>
                    <input type="file" 
                           id="photo" 
                           name="photo" 
                           class="form-control" 
                           accept="image/jpeg,image/png,image/jpg,image/webp">
                    <small class="form-text">Recommended: Square image, at least 400x400px (JPG, PNG, or WebP)</small>
                </div>

                <div class="form-group span-full">
                    <label class="checkbox-label">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               <?php echo (isset($_POST['is_active']) || !isset($_POST['name'])) ? 'checked' : ''; ?>>
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
                    Add Team Member
                </button>
                <a href="team-members.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
