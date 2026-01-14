<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Edit Success Story';

// Get database connection
$conn = getDBConnection();

$formError = '';
$storyId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch existing story
$stmt = $conn->prepare("SELECT * FROM success_stories WHERE id = ?");
$stmt->bind_param("i", $storyId);
$stmt->execute();
$result = $stmt->get_result();
$story = $result->fetch_assoc();
$stmt->close();

if (!$story) {
    $_SESSION['error_message'] = 'Success story not found';
    header('Location: success-stories.php');
    exit;
}

// Decode results JSON
$currentResults = json_decode($story['results'], true);
if (!is_array($currentResults)) {
    $currentResults = [];
}

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
        $resultsJson = json_encode($results);
        
        // Update success story
        $stmt = $conn->prepare("UPDATE success_stories SET title = ?, project_type = ?, industry = ?, challenge = ?, solution = ?, results = ?, is_active = ? WHERE id = ?");
        $stmt->bind_param("ssssssii", $title, $projectType, $industry, $challenge, $solution, $resultsJson, $isActive, $storyId);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Success story updated successfully';
            header('Location: success-stories.php');
            exit;
        } else {
            $formError = 'Error updating success story: ' . $conn->error;
        }
        $stmt->close();
    }
    
    // Update current values if form was submitted
    $story['title'] = $title;
    $story['project_type'] = $projectType;
    $story['industry'] = $industry;
    $story['challenge'] = $challenge;
    $story['solution'] = $solution;
    $story['is_active'] = $isActive;
    $currentResults = $results;
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
                Edit Success Story
            </h1>
            <p class="page-subtitle">Update client success story details</p>
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
                           value="<?php echo htmlspecialchars($story['title']); ?>"
                           required>
                    <small class="form-text">The client/project title (anonymized if needed)</small>
                </div>

                <div class="form-group">
                    <label for="project_type">Project Type</label>
                    <select id="project_type" name="project_type" class="form-control">
                        <option value="">Select Type</option>
                        <option value="Brownfield" <?php echo ($story['project_type'] === 'Brownfield') ? 'selected' : ''; ?>>Brownfield</option>
                        <option value="Greenfield" <?php echo ($story['project_type'] === 'Greenfield') ? 'selected' : ''; ?>>Greenfield</option>
                        <option value="Post-Commissioning" <?php echo ($story['project_type'] === 'Post-Commissioning') ? 'selected' : ''; ?>>Post-Commissioning</option>
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
                           value="<?php echo htmlspecialchars($story['industry']); ?>">
                    <small class="form-text">Industry sector</small>
                </div>

                <div class="form-group span-full">
                    <label for="challenge">Challenge <span class="required">*</span></label>
                    <textarea id="challenge" 
                              name="challenge" 
                              class="form-control" 
                              rows="3"
                              placeholder="Describe the main challenges the client was facing"
                              required><?php echo htmlspecialchars($story['challenge']); ?></textarea>
                    <small class="form-text">Brief description of the problem statement</small>
                </div>

                <div class="form-group span-full">
                    <label for="solution">Solution <span class="required">*</span></label>
                    <textarea id="solution" 
                              name="solution" 
                              class="form-control" 
                              rows="3"
                              placeholder="Describe the solution implemented"
                              required><?php echo htmlspecialchars($story['solution']); ?></textarea>
                    <small class="form-text">Brief description of the OptiSpace solution</small>
                </div>

                <div class="form-group span-full">
                    <label>Results Achieved <span class="required">*</span></label>
                    <div id="results-container">
                        <?php foreach ($currentResults as $result): ?>
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
                        <input type="checkbox" name="is_active" value="1" <?php echo $story['is_active'] ? 'checked' : ''; ?>>
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
                    Update Success Story
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
