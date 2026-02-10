<?php
/**
 * Admin Pulse Check Manual Entry
 * Sales users can manually add leads
 */

$pageTitle = "Add Pulse Check";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$admin = getCurrentAdmin();

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $altPhone = trim($_POST['alt_phone'] ?? '');
    $companyName = trim($_POST['company_name'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $industry = trim($_POST['industry'] ?? '');
    $facilityAddress = trim($_POST['facility_address'] ?? '');
    $facilityCity = trim($_POST['facility_city'] ?? '');
    $facilityState = trim($_POST['facility_state'] ?? '');
    $facilityCountry = trim($_POST['facility_country'] ?? 'India');
    $facilitySize = trim($_POST['facility_size'] ?? '');
    $employees = trim($_POST['employees'] ?? '');
    $annualRevenue = trim($_POST['annual_revenue'] ?? '');
    $projectType = trim($_POST['project_type'] ?? '');
    $interests = isset($_POST['interests']) ? implode(', ', $_POST['interests']) : '';
    $currentChallenges = trim($_POST['current_challenges'] ?? '');
    $projectGoals = trim($_POST['project_goals'] ?? '');
    $timeline = trim($_POST['timeline'] ?? '');
    $referral = trim($_POST['referral'] ?? '');
    $preferredContact = trim($_POST['preferred_contact'] ?? '');
    $status = $_POST['status'] ?? 'new';
    $adminNotes = trim($_POST['admin_notes'] ?? '');

    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($companyName)) {
        $errors['general'] = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("
            INSERT INTO pulse_check_submissions (
                first_name, last_name, designation, email, phone, alt_phone,
                company_name, website, industry, facility_address, facility_city, 
                facility_state, facility_country, facility_size, employees, annual_revenue,
                project_type, interests, current_challenges, project_goals, 
                timeline, referral, preferred_contact, status, admin_notes, 
                ip_address, user_agent
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $ip = 'Manual Entry';
        $ua = 'Admin Panel (' . $admin['username'] . ')';

        $stmt->bind_param(
            "sssssssssssssssssssssssssss",
            $firstName,
            $lastName,
            $designation,
            $email,
            $phone,
            $altPhone,
            $companyName,
            $website,
            $industry,
            $facilityAddress,
            $facilityCity,
            $facilityState,
            $facilityCountry,
            $facilitySize,
            $employees,
            $annualRevenue,
            $projectType,
            $interests,
            $currentChallenges,
            $projectGoals,
            $timeline,
            $referral,
            $preferredContact,
            $status,
            $adminNotes,
            $ip,
            $ua
        );

        if ($stmt->execute()) {
            $newId = $stmt->insert_id;
            logAdminActivity($admin['id'], 'create', 'pulse_check_submissions', $newId, 'Manually added pulse check for ' . $companyName);
            header("Location: pulse-check-view.php?id=$newId&msg=created");
            exit;
        } else {
            $errors['general'] = "Database error: " . $stmt->error;
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <a href="pulse-checks.php" class="back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Back to Submissions
        </a>
        <h1 class="page-title">Add New Pulse Check</h1>
        <p class="page-subtitle">Manually record a new lead or assessment request</p>
    </div>
</div>

<?php if (isset($errors['general'])): ?>
    <div class="alert alert-danger" data-auto-hide>
        <span>
            <?php echo htmlspecialchars($errors['general']); ?>
        </span>
    </div>
<?php endif; ?>

<form method="POST" class="form-container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Contact Information</h3>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>First Name <span class="required">*</span></label>
                    <input type="text" name="first_name" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Last Name <span class="required">*</span></label>
                    <input type="text" name="last_name" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Designation</label>
                    <input type="text" name="designation" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['designation'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone <span class="required">*</span></label>
                    <input type="text" name="phone" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Alternate Phone</label>
                    <input type="text" name="alt_phone" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['alt_phone'] ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Company & Facility Information</h3>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group span-2">
                    <label>Company Name <span class="required">*</span></label>
                    <input type="text" name="company_name" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['company_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Website</label>
                    <input type="url" name="website" class="form-control" placeholder="https://"
                        value="<?php echo htmlspecialchars($_POST['website'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Industry</label>
                    <input type="text" name="industry" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['industry'] ?? ''); ?>">
                </div>
                <div class="form-group span-2">
                    <label>Facility Address</label>
                    <input type="text" name="facility_address" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['facility_address'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="facility_city" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['facility_city'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="facility_state" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['facility_state'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="facility_country" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['facility_country'] ?? 'India'); ?>">
                </div>
                <div class="form-group">
                    <label>Facility Size (sq. ft.)</label>
                    <input type="text" name="facility_size" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['facility_size'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Number of Employees</label>
                    <input type="text" name="employees" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['employees'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Annual Revenue</label>
                    <input type="text" name="annual_revenue" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['annual_revenue'] ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Project Details</h3>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>Project Type</label>
                    <select name="project_type" class="form-control">
                        <option value="">Select... </option>
                        <option value="greenfield" <?php echo ($_POST['project_type'] ?? '') === 'greenfield' ? 'selected' : ''; ?>>Greenfield (New Setup)</option>
                        <option value="brownfield" <?php echo ($_POST['project_type'] ?? '') === 'brownfield' ? 'selected' : ''; ?>>Brownfield (Expansion/Optimization)</option>
                        <option value="post-commissioning" <?php echo ($_POST['project_type'] ?? '') === 'post-commissioning' ? 'selected' : ''; ?>>Post-Commissioning (Operational Audit)
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Expected Timeline</label>
                    <select name="timeline" class="form-control">
                        <option value="">Select...</option>
                        <option value="immediately" <?php echo ($_POST['timeline'] ?? '') === 'immediately' ? 'selected' : ''; ?>>Immediately</option>
                        <option value="1-3-months" <?php echo ($_POST['timeline'] ?? '') === '1-3-months' ? 'selected' : ''; ?>>1-3 Months</option>
                        <option value="3-6-months" <?php echo ($_POST['timeline'] ?? '') === '3-6-months' ? 'selected' : ''; ?>>3-6 Months</option>
                        <option value="6-plus-months" <?php echo ($_POST['timeline'] ?? '') === '6-plus-months' ? 'selected' : ''; ?>>6+ Months</option>
                        <option value="just-researching" <?php echo ($_POST['timeline'] ?? '') === 'just-researching' ? 'selected' : ''; ?>>Just Researching</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Referral Source</label>
                    <input type="text" name="referral" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['referral'] ?? ''); ?>">
                </div>
                <div class="form-group span-3">
                    <label>Areas of Interest</label>
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.5rem; background: #f8fafc; padding: 1rem; border-radius: 8px;">
                        <?php
                        $interestOptions = [
                            'layout-optimization' => 'Layout Optimization',
                            'process-efficiency' => 'Process Efficiency',
                            'material-handling' => 'Material Handling',
                            'warehouse-management' => 'Warehouse Management',
                            'supply-chain' => 'Supply Chain',
                            'quality-control' => 'Quality Control',
                            'automation' => 'Automation',
                            'cost-reduction' => 'Cost Reduction'
                        ];
                        $selectedInterests = $_POST['interests'] ?? [];
                        foreach ($interestOptions as $val => $label): ?>
                            <label
                                style="display: flex; align-items: center; gap: 0.5rem; font-weight: normal; cursor: pointer;">
                                <input type="checkbox" name="interests[]" value="<?php echo $val; ?>" <?php echo in_array($val, $selectedInterests) ? 'checked' : ''; ?>>
                                <?php echo $label; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="form-group span-3">
                    <label>Current Challenges</label>
                    <textarea name="current_challenges" class="form-control"
                        rows="3"><?php echo htmlspecialchars($_POST['current_challenges'] ?? ''); ?></textarea>
                </div>
                <div class="form-group span-3">
                    <label>Project Goals</label>
                    <textarea name="project_goals" class="form-control"
                        rows="3"><?php echo htmlspecialchars($_POST['project_goals'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Lead Management</h3>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="new">New</option>
                        <option value="contacted">Contacted</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                        <option value="declined">Declined</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Preferred Contact Method</label>
                    <select name="preferred_contact" class="form-control">
                        <option value="">Select...</option>
                        <option value="email">Email</option>
                        <option value="phone">Phone</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                </div>
                <div class="form-group span-3">
                    <label>Internal Notes</label>
                    <textarea name="admin_notes" class="form-control" rows="3"
                        placeholder="Additional info for the sales team..."><?php echo htmlspecialchars($_POST['admin_notes'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>
        <div class="card-footer" style="text-align: right; padding: 1.5rem;">
            <button type="submit" class="btn btn-primary">Save Lead</button>
        </div>
    </div>
</form>

<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .form-group.span-2 {
        grid-column: span 2;
    }

    .form-group.span-3 {
        grid-column: span 3;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        text-decoration: none;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .back-link:hover {
        color: #E99431;
    }

    .back-link svg {
        width: 16px;
        height: 16px;
    }

    .required {
        color: #ef4444;
    }
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>