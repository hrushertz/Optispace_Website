<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Edit FAQ';

// Get database connection
$conn = getDBConnection();

$formError = '';
$faq = null;

// Get FAQ ID
$faqId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($faqId <= 0) {
    $_SESSION['error_message'] = 'Invalid FAQ ID';
    header('Location: pulse-check-faqs.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question'] ?? '');
    $answer = trim($_POST['answer'] ?? '');
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    // Validation
    if (empty($question)) {
        $formError = 'Question is required';
    } elseif (empty($answer)) {
        $formError = 'Answer is required';
    } else {
        // Update FAQ
        $stmt = $conn->prepare("UPDATE pulse_check_faqs SET question = ?, answer = ?, is_active = ? WHERE id = ?");
        $stmt->bind_param("ssii", $question, $answer, $isActive, $faqId);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'FAQ updated successfully';
            header('Location: pulse-check-faqs.php');
            exit;
        } else {
            $formError = 'Error updating FAQ: ' . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch FAQ data
$stmt = $conn->prepare("SELECT * FROM pulse_check_faqs WHERE id = ?");
$stmt->bind_param("i", $faqId);
$stmt->execute();
$result = $stmt->get_result();
$faq = $result->fetch_assoc();
$stmt->close();

if (!$faq) {
    $_SESSION['error_message'] = 'FAQ not found';
    header('Location: pulse-check-faqs.php');
    exit;
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
                Edit FAQ
            </h1>
            <p class="page-subtitle">Update frequently asked question</p>
        </div>
        <div class="page-actions">
            <a href="pulse-check-faqs.php" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Back to FAQs
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
                    <label for="question">Question <span class="required">*</span></label>
                    <input type="text" 
                           id="question" 
                           name="question" 
                           class="form-control" 
                           placeholder="e.g., Is the Pulse Check really free?"
                           value="<?php echo htmlspecialchars($_POST['question'] ?? $faq['question']); ?>"
                           required>
                    <small class="form-text">The FAQ question as it will appear on the page</small>
                </div>

                <div class="form-group span-full">
                    <label for="answer">Answer <span class="required">*</span></label>
                    <textarea id="answer" 
                              name="answer" 
                              class="form-control" 
                              placeholder="Enter the detailed answer to this question. You can use the editor toolbar for formatting."
                              required><?php echo htmlspecialchars($_POST['answer'] ?? $faq['answer']); ?></textarea>
                    <small class="form-text">Use the rich text editor to format your answer with headings, lists, bold, italic, etc.</small>
                </div>

                <div class="form-group span-full">
                    <label class="checkbox-label">
                        <input type="checkbox" 
                               name="is_active" 
                               <?php echo (isset($_POST['is_active']) ? 'checked' : ($faq['is_active'] ? 'checked' : '')); ?>>
                        <span>Active (visible on website)</span>
                    </label>
                    <small class="form-text">Uncheck to hide this FAQ from the public website</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Update FAQ
                </button>
                <a href="pulse-check-faqs.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 0.5rem;">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                FAQ Details
            </h3>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <span class="detail-label">Sort Order:</span>
                <span class="detail-value"><?php echo $faq['sort_order']; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Created:</span>
                <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($faq['created_at'])); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Last Updated:</span>
                <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($faq['updated_at'])); ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<style>
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-group.span-full {
    grid-column: span 2;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #E99431;
    box-shadow: 0 0 0 3px rgba(233, 148, 49, 0.1);
}

textarea.form-control {
    resize: vertical;
    font-family: inherit;
}

.form-text {
    display: block;
    margin-top: 0.5rem;
    color: #64748B;
    font-size: 0.875rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-weight: 500;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #E2E8F0;
}

.required {
    color: #EF4444;
}

code {
    background: #F1F5F9;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.875rem;
    color: #E99431;
}

.card-body ul {
    padding-left: 1.5rem;
    margin: 1rem 0;
}

.card-body ul li {
    margin: 0.5rem 0;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #F1F5F9;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #475569;
}

.detail-value {
    color: #64748B;
}
</style>

<?php include 'includes/footer.php'; ?>

<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    $('#answer').summernote({
        placeholder: 'Write your FAQ answer here...',
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview', 'help']]
        ],
        styleTags: [
            'p',
            { title: 'Heading', tag: 'h4', className: '', value: 'h4' },
            { title: 'Quote', tag: 'blockquote', className: '', value: 'blockquote' }
        ]
    });
});
</script>
