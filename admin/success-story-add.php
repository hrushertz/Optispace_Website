<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Add Success Story';

// Get database connection
$conn = getDBConnection();

$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $projectType = trim($_POST['project_type'] ?? '');
    $industry = trim($_POST['industry'] ?? '');
    $challenge = trim($_POST['challenge'] ?? '');
    $solution = trim($_POST['solution'] ?? '');
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    // Process results array
    $results = [];
    if (isset($_POST['results']) && is_array($_POST['results'])) {
        foreach ($_POST['results'] as $result) {
            $result = trim($result);
            if (!empty($result)) {
                $results[] = $result;
            }
        }
    }
    
    // Validation
    if (empty($title)) {
        $formError = 'Title is required';
    } elseif (empty($challenge)) {
        $formError = 'Challenge description is required';
    } elseif (empty($solution)) {
        $formError = 'Solution description is required';
    } elseif (empty($results)) {
        $formError = 'At least one result is required';
    } else {
        // Get max sort order
        $result = $conn->query("SELECT MAX(sort_order) as max_order FROM success_stories");
        $row = $result->fetch_assoc();
        $sortOrder = ($row['max_order'] ?? 0) + 1;
        
        $resultsJson = json_encode($results);
        
        // Insert success story
        $stmt = $conn->prepare("INSERT INTO success_stories (title, project_type, industry, challenge, solution, results, sort_order, is_active, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $userId = $_SESSION['admin_user']['id'];
        $stmt->bind_param("ssssssiii", $title, $projectType, $industry, $challenge, $solution, $resultsJson, $sortOrder, $isActive, $userId);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Success story added successfully';
            header('Location: success-stories.php');
            exit;
        } else {
            $formError = 'Error adding success story: ' . $conn->error;
        }
        $stmt->close();
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
                Add New Success Story
            </h1>
            <p class="page-subtitle">Create a client success story for the Portfolio page</p>
        </div>
        <div class="page-actions">
            <a href="success-stories.php" class="btn btn-secondary">
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
        <form method="post" class="admin-form">
            <div class="form-grid">
                <div class="form-group span-full">
                    <label for="title">Story Title <span class="required">*</span></label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           class="form-control" 
                           placeholder="e.g., Automotive Component Manufacturer"
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                           required>
                    <small class="form-text">The client/project title (anonymized if needed)</small>
                </div>

                <div class="form-group">
                    <label for="project_type">Project Type</label>
                    <select id="project_type" name="project_type" class="form-control">
                        <option value="">Select Type</option>
                        <option value="Brownfield" <?php echo (($_POST['project_type'] ?? '') === 'Brownfield') ? 'selected' : ''; ?>>Brownfield</option>
                        <option value="Greenfield" <?php echo (($_POST['project_type'] ?? '') === 'Greenfield') ? 'selected' : ''; ?>>Greenfield</option>
                        <option value="Post-Commissioning" <?php echo (($_POST['project_type'] ?? '') === 'Post-Commissioning') ? 'selected' : ''; ?>>Post-Commissioning</option>
                    </select>
                    <small class="form-text">Type of project engagement</small>
                </div>

                <div class="form-group">
                    <label for="industry">Industry</label>
                    <input type="text" 
                           id="industry" 
                           name="industry" 
                           class="form-control" 
                           placeholder="e.g., Automotive, Pharma, Electronics"
                           value="<?php echo htmlspecialchars($_POST['industry'] ?? ''); ?>">
                    <small class="form-text">Industry sector</small>
                </div>

                <div class="form-group span-full">
                    <label for="challenge">Challenge <span class="required">*</span></label>
                    <textarea id="challenge" 
                              name="challenge" 
                              class="form-control" 
                              rows="3"
                              placeholder="Describe the main challenges the client was facing"
                              required><?php echo htmlspecialchars($_POST['challenge'] ?? ''); ?></textarea>
                    <small class="form-text">Brief description of the problem statement</small>
                </div>

                <div class="form-group span-full">
                    <label for="solution">Solution <span class="required">*</span></label>
                    <textarea id="solution" 
                              name="solution" 
                              class="form-control" 
                              rows="3"
                              placeholder="Describe the solution implemented"
                              required><?php echo htmlspecialchars($_POST['solution'] ?? ''); ?></textarea>
                    <small class="form-text">Brief description of the OptiSpace solution</small>
                </div>

                <div class="form-group span-full">
                    <label>Results Achieved <span class="required">*</span></label>
                    <div id="results-container">
                        <?php
                        $savedResults = $_POST['results'] ?? [''];
                        foreach ($savedResults as $index => $result):
                        ?>
                        <div class="result-item" style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <input type="text" 
                                   name="results[]" 
                                   class="form-control" 
                                   placeholder="e.g., 47% reduction in material travel"
                                   value="<?php echo htmlspecialchars($result); ?>">
                            <button type="button" class="btn btn-icon-danger remove-result" onclick="removeResult(this)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="addResult()" style="margin-top: 0.5rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Add Result
                    </button>
                    <small class="form-text" style="display: block; margin-top: 0.5rem;">Add multiple measurable results achieved in this project</small>
                </div>

                <div class="form-group span-full">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?php echo isset($_POST['is_active']) || !isset($_POST['title']) ? 'checked' : ''; ?>>
                        <span>Active (visible on website)</span>
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
                    Add Success Story
                </button>
                <a href="success-stories.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function addResult() {
    const container = document.getElementById('results-container');
    const resultItem = document.createElement('div');
    resultItem.className = 'result-item';
    resultItem.style.cssText = 'display: flex; gap: 0.5rem; margin-bottom: 0.5rem;';
    resultItem.innerHTML = `
        <input type="text" 
               name="results[]" 
               class="form-control" 
               placeholder="e.g., 47% reduction in material travel">
        <button type="button" class="btn btn-icon-danger remove-result" onclick="removeResult(this)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    `;
    container.appendChild(resultItem);
}

function removeResult(button) {
    const container = document.getElementById('results-container');
    if (container.children.length > 1) {
        button.closest('.result-item').remove();
    } else {
        alert('At least one result is required');
    }
}
</script>

<?php
$conn->close();
include 'includes/footer.php';
?>
