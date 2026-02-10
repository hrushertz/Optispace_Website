<?php
/**
 * Admin Pulse Check Bulk Upload
 * Upload leads via CSV
 */

$pageTitle = "Bulk Upload Pulse Checks";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$admin = getCurrentAdmin();

$errors = [];
$successMessage = '';
$importResults = null;

// Handle Sample CSV Download
if (isset($_GET['download_sample'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="pulse_check_sample.csv"');

    $output = fopen('php://output', 'w');
    // Header
    fputcsv($output, [
        'first_name',
        'last_name',
        'designation',
        'email',
        'phone',
        'company_name',
        'website',
        'industry',
        'facility_address',
        'facility_city',
        'facility_state',
        'facility_country',
        'facility_size',
        'employees',
        'annual_revenue',
        'project_type',
        'timeline',
        'current_challenges'
    ]);
    // Sample Data
    fputcsv($output, [
        'John',
        'Doe',
        'Manager',
        'john@example.com',
        '1234567890',
        'Example Corp',
        'https://example.com',
        'Manufacturing',
        '123 Factory Rd',
        'Mumbai',
        'Maharashtra',
        'India',
        '5000',
        '50',
        '10 Cr',
        'greenfield',
        '1-3-months',
        'Need layout optimization'
    ]);
    fclose($output);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "File upload failed with error code: " . $file['error'];
    } else {
        $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (strtolower($fileType) !== 'csv') {
            $errors[] = "Please upload a valid CSV file.";
        } else {
            if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
                // Get headers
                $headers = fgetcsv($handle, 1000, ",");
                $headers = array_map('trim', $headers);

                $totalRows = 0;
                $successCount = 0;
                $failCount = 0;
                $rowErrors = [];

                $ip = 'Bulk Upload';
                $ua = 'Admin Panel (' . $admin['username'] . ')';

                $stmt = $conn->prepare("
                    INSERT INTO pulse_check_submissions (
                        first_name, last_name, designation, email, phone,
                        company_name, website, industry, facility_address, 
                        facility_city, facility_state, facility_country, 
                        facility_size, employees, annual_revenue, 
                        project_type, timeline, current_challenges,
                        status, ip_address, user_agent
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'new', ?, ?)
                ");

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $totalRows++;
                    $row = array_combine($headers, $data);

                    // Basic row validation
                    if (empty($row['first_name']) || empty($row['last_name']) || empty($row['email']) || empty($row['phone']) || empty($row['company_name'])) {
                        $failCount++;
                        $rowErrors[] = "Row $totalRows: Missing required fields (first_name, last_name, email, phone, company_name).";
                        continue;
                    }

                    if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                        $failCount++;
                        $rowErrors[] = "Row $totalRows: Invalid email format ({$row['email']}).";
                        continue;
                    }

                    $stmt->bind_param(
                        "ssssssssssssssssssss",
                        $row['first_name'],
                        $row['last_name'],
                        $row['designation'],
                        $row['email'],
                        $row['phone'],
                        $row['company_name'],
                        $row['website'],
                        $row['industry'],
                        $row['facility_address'],
                        $row['facility_city'],
                        $row['facility_state'],
                        $row['facility_country'],
                        $row['facility_size'],
                        $row['employees'],
                        $row['annual_revenue'],
                        $row['project_type'],
                        $row['timeline'],
                        $row['current_challenges'],
                        $ip,
                        $ua
                    );

                    if ($stmt->execute()) {
                        $successCount++;
                    } else {
                        $failCount++;
                        $rowErrors[] = "Row $totalRows: Database error ({$conn->error}).";
                    }
                }
                fclose($handle);
                $stmt->close();

                logAdminActivity($admin['id'], 'bulk_upload', 'pulse_check_submissions', 0, "Bulk uploaded $successCount leads");

                $importResults = [
                    'total' => $totalRows,
                    'success' => $successCount,
                    'fail' => $failCount,
                    'errors' => $rowErrors
                ];

                if ($successCount > 0) {
                    $successMessage = "Successfully imported $successCount leads!";
                }
            }
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
        <h1 class="page-title">Bulk Upload Pulse Checks</h1>
        <p class="page-subtitle">Upload multiple leads at once using a CSV file</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 350px; gap: 1.5rem;">
    <div>
        <?php if ($successMessage): ?>
            <div class="alert alert-success" data-auto-hide>
                <span>
                    <?php echo htmlspecialchars($successMessage); ?>
                </span>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger">
                    <span>
                        <?php echo htmlspecialchars($error); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($importResults): ?>
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Import Results</h3>
                </div>
                <div class="card-body">
                    <div
                        style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; text-align: center; margin-bottom: 1.5rem;">
                        <div style="padding: 1rem; background: #f8fafc; border-radius: 8px;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">
                                <?php echo $importResults['total']; ?>
                            </div>
                            <div style="font-size: 0.8rem; color: #64748b; text-transform: uppercase;">Total Rows</div>
                        </div>
                        <div style="padding: 1rem; background: #f0fdf4; border-radius: 8px;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #166534;">
                                <?php echo $importResults['success']; ?>
                            </div>
                            <div style="font-size: 0.8rem; color: #15803d; text-transform: uppercase;">Success</div>
                        </div>
                        <div style="padding: 1rem; background: #fef2f2; border-radius: 8px;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #991b1b;">
                                <?php echo $importResults['fail']; ?>
                            </div>
                            <div style="font-size: 0.8rem; color: #b91c1c; text-transform: uppercase;">Failed</div>
                        </div>
                    </div>

                    <?php if (!empty($importResults['errors'])): ?>
                        <div
                            style="max-height: 300px; overflow-y: auto; border: 1px solid #fee2e2; border-radius: 8px; padding: 1rem; background: #fffafb;">
                            <h4 style="font-size: 0.9rem; color: #991b1b; margin-top: 0; margin-bottom: 0.5rem;">Errors
                                detected:</h4>
                            <ul style="font-size: 0.85rem; color: #b91c1c; margin: 0; padding-left: 1.25rem;">
                                <?php foreach ($importResults['errors'] as $error): ?>
                                    <li>
                                        <?php echo htmlspecialchars($error); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload File</h3>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" id="bulkUploadForm">
                    <div style="border: 2px dashed #E2E8F0; border-radius: 12px; padding: 3rem; text-align: center; background: #F8FAFC; transition: all 0.2s;"
                        id="dropZone">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2"
                            style="width: 48px; height: 48px; margin-bottom: 1rem;">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                        <p style="color: #1e293b; font-weight: 600; margin-bottom: 0.5rem;">Select a CSV file to upload
                        </p>
                        <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 1.5rem;">Maximum file size: 5MB</p>
                        <input type="file" name="csv_file" id="csvFile" accept=".csv" style="display: none;">
                        <button type="button" class="btn btn-secondary"
                            onclick="document.getElementById('csvFile').click()">Browse Files</button>
                    </div>
                    <div id="fileInfo"
                        style="display: none; margin-top: 1rem; padding: 1rem; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0; align-items: center; gap: 0.75rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"
                            style="width: 20px; height: 20px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                        <div style="flex: 1;">
                            <span id="fileName" style="font-weight: 600; color: #166534; font-size: 0.9rem;"></span>
                            <span id="fileSize" style="color: #15803d; font-size: 0.8rem; margin-left: 0.5rem;"></span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Upload Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Instructions</h3>
            </div>
            <div class="card-body" style="font-size: 0.875rem; color: #475569; line-height: 1.6;">
                <p>Follow these steps to ensure a smooth upload:</p>
                <ol style="padding-left: 1.25rem;">
                    <li>Download the sample CSV template.</li>
                    <li>Add your lead data to the file.</li>
                    <li>Ensure required fields are filled.</li>
                    <li>Save as a standard CSV file.</li>
                    <li>Upload here for processing.</li>
                </ol>
                <div
                    style="margin-top: 1.5rem; padding: 1rem; background: #FFFBEB; border: 1px solid #FCD34D; border-radius: 8px;">
                    <h4
                        style="font-size: 0.85rem; color: #92400E; font-weight: 700; margin-top: 0; margin-bottom: 0.5rem;">
                        Required Fields:</h4>
                    <p style="margin: 0; font-size: 0.8rem; color: #D97706;">first_name, last_name, email, phone,
                        company_name</p>
                </div>
                <a href="?download_sample=1" class="btn btn-secondary"
                    style="width: 100%; margin-top: 1.5rem; justify-content: center;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        style="width: 18px; height: 18px;">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    Download Template
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('csvFile').onchange = function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
            document.getElementById('dropZone').style.display = 'none';
            document.getElementById('fileInfo').style.display = 'flex';
        }
    };
</script>

<style>
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
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>