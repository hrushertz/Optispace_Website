<?php
require_once 'includes/auth.php';
requireLogin();

$pageTitle = 'Add Waste Item';

// Get database connection
$conn = getDBConnection();

$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $iconSvg = trim($_POST['icon_svg'] ?? '');
    $problemText = trim($_POST['problem_text'] ?? '');
    $solutionText = trim($_POST['solution_text'] ?? '');
    $impactText = trim($_POST['impact_text'] ?? '');
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    // Validation
    if (empty($title)) {
        $formError = 'Title is required';
    } elseif (empty($problemText)) {
        $formError = 'Problem text is required';
    } elseif (empty($solutionText)) {
        $formError = 'Solution text is required';
    } else {
        // Get max sort order
        $result = $conn->query("SELECT MAX(sort_order) as max_order FROM waste_items");
        $row = $result->fetch_assoc();
        $sortOrder = ($row['max_order'] ?? 0) + 1;
        
        // Insert waste item
        $stmt = $conn->prepare("INSERT INTO waste_items (title, icon_svg, problem_text, solution_text, impact_text, sort_order, is_active, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $userId = $_SESSION['admin_user']['id'];
        $stmt->bind_param("sssssiis", $title, $iconSvg, $problemText, $solutionText, $impactText, $sortOrder, $isActive, $userId);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Waste item added successfully';
            header('Location: waste-items.php');
            exit;
        } else {
            $formError = 'Error adding waste item: ' . $conn->error;
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
                Add New Waste Item
            </h1>
            <p class="page-subtitle">Add a waste item for the Philosophy page - Eliminating Mudas section</p>
        </div>
        <div class="page-actions">
            <a href="waste-items.php" class="btn btn-secondary">
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
                    <label for="title">Title <span class="required">*</span></label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           class="form-control" 
                           placeholder="e.g., Transportation Waste, Motion Waste"
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                           required>
                    <small class="form-text">The waste item title as it will appear on the page</small>
                </div>

                <div class="form-group span-full">
                    <label>Select Icon</label>
                    <div class="icon-selector">
                        <div class="icon-grid">
                            <div class="icon-option" data-icon="truck">
                                <div class="icon-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                </div>
                                <div class="icon-label">Truck</div>
                            </div>
                            <div class="icon-option" data-icon="zap">
                                <div class="icon-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                                </div>
                                <div class="icon-label">Lightning</div>
                            </div>
                            <div class="icon-option" data-icon="clock">
                                <div class="icon-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                </div>
                                <div class="icon-label">Clock</div>
                            </div>
                            <div class="icon-option" data-icon="package">
                                <div class="icon-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                </div>
                                <div class="icon-label">Package</div>
                            </div>
                            <div class="icon-option" data-icon="repeat">
                                <div class="icon-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                                </div>
                                <div class="icon-label">Repeat</div>
                            </div>
                            <div class="icon-option" data-icon="layers">
                                <div class="icon-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                                </div>
                                <div class="icon-label">Layers</div>
                            </div>
                            <div class="icon-option" data-icon="tool">
                                <div class="icon-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                                </div>
                                <div class="icon-label">Tool</div>
                            </div>
                            <div class="icon-option" data-icon="alert">
                                <div class="icon-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                </div>
                                <div class="icon-label">Alert</div>
                            </div>
                            <div class="icon-option" data-icon="trash">
                                <div class="icon-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </div>
                                <div class="icon-label">Trash</div>
                            </div>
                            <div class="icon-option" data-icon="activity">
                                <div class="icon-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                </div>
                                <div class="icon-label">Activity</div>
                            </div>
                            <div class="icon-option" data-icon="shuffle">
                                <div class="icon-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 3 21 3 21 8"></polyline><line x1="4" y1="20" x2="21" y2="3"></line><polyline points="21 16 21 21 16 21"></polyline><line x1="15" y1="15" x2="21" y2="21"></line><line x1="4" y1="4" x2="9" y2="9"></line></svg>
                                </div>
                                <div class="icon-label">Shuffle</div>
                            </div>
                            <div class="icon-option" data-icon="inbox">
                                <div class="icon-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>
                                </div>
                                <div class="icon-label">Inbox</div>
                            </div>
                        </div>
                        <input type="hidden" id="icon_svg" name="icon_svg" value="<?php echo htmlspecialchars($_POST['icon_svg'] ?? ''); ?>">
                        <div class="selected-icon-preview" style="display: none;">
                            <div style="font-size: 0.85rem; color: #64748B; margin-bottom: 0.5rem;">Selected Icon:</div>
                            <div id="selectedIconDisplay" style="width: 48px; height: 48px; background: rgba(233, 148, 49, 0.08); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #E99431;"></div>
                        </div>
                    </div>
                    <small class="form-text">Click on an icon to select it for this waste item</small>
                </div>

                <div class="form-group span-full">
                    <label for="problem_text">Problem Description <span class="required">*</span></label>
                    <textarea id="problem_text" 
                              name="problem_text" 
                              class="form-control" 
                              placeholder="Describe the problem or waste scenario"
                              required><?php echo htmlspecialchars($_POST['problem_text'] ?? ''); ?></textarea>
                    <small class="form-text">Use the rich text editor to format your problem description</small>
                </div>

                <div class="form-group span-full">
                    <label for="solution_text">LFB Solution <span class="required">*</span></label>
                    <textarea id="solution_text" 
                              name="solution_text" 
                              class="form-control" 
                              placeholder="Describe the LFB solution"
                              required><?php echo htmlspecialchars($_POST['solution_text'] ?? ''); ?></textarea>
                    <small class="form-text">Use the rich text editor to format your solution description</small>
                </div>

                <div class="form-group span-full">
                    <label for="impact_text">Impact & Benefits</label>
                    <textarea id="impact_text" 
                              name="impact_text" 
                              class="form-control" 
                              placeholder="Describe the impact or benefits"><?php echo htmlspecialchars($_POST['impact_text'] ?? ''); ?></textarea>
                    <small class="form-text">Use the rich text editor to format the impact description</small>
                </div>

                <div class="form-group span-full">
                    <label class="checkbox-label">
                        <input type="checkbox" 
                               name="is_active" 
                               <?php echo (isset($_POST['is_active']) || !isset($_POST['title'])) ? 'checked' : ''; ?>>
                        <span>Active (visible on website)</span>
                    </label>
                    <small class="form-text">Uncheck to hide this waste item from the public website</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Add Waste Item
                </button>
                <a href="waste-items.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
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

.icon-selector {
    margin-top: 0.5rem;
}

.icon-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.icon-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border: 2px solid #E2E8F0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    background: white;
}

.icon-option:hover {
    border-color: #E99431;
    background: rgba(233, 148, 49, 0.05);
    transform: translateY(-2px);
}

.icon-option.selected {
    border-color: #E99431;
    background: rgba(233, 148, 49, 0.1);
    box-shadow: 0 0 0 3px rgba(233, 148, 49, 0.1);
}

.icon-preview {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748B;
    transition: color 0.2s;
}

.icon-option:hover .icon-preview,
.icon-option.selected .icon-preview {
    color: #E99431;
}

.icon-preview svg {
    width: 28px;
    height: 28px;
}

.icon-label {
    font-size: 0.75rem;
    color: #64748B;
    font-weight: 500;
    text-align: center;
}

.selected-icon-preview {
    margin-top: 1rem;
    padding: 1rem;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
}
</style>

<?php include 'includes/footer.php'; ?>

<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    // Icon SVG mapping
    const iconSVGs = {
        'truck': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>',
        'zap': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>',
        'clock': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
        'package': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>',
        'repeat': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>',
        'layers': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>',
        'tool': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>',
        'alert': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
        'trash': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>',
        'activity': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>',
        'shuffle': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 3 21 3 21 8"></polyline><line x1="4" y1="20" x2="21" y2="3"></line><polyline points="21 16 21 21 16 21"></polyline><line x1="15" y1="15" x2="21" y2="21"></line><line x1="4" y1="4" x2="9" y2="9"></line></svg>',
        'inbox': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>'
    };
    
    // Icon selection
    $('.icon-option').on('click', function() {
        $('.icon-option').removeClass('selected');
        $(this).addClass('selected');
        
        const iconName = $(this).data('icon');
        const iconSvg = iconSVGs[iconName];
        
        $('#icon_svg').val(iconSvg);
        $('#selectedIconDisplay').html(iconSvg);
        $('.selected-icon-preview').slideDown();
    });
    
    // Initialize Summernote for problem text
    $('#problem_text').summernote({
        placeholder: 'Describe the problem or waste scenario...',
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol']],
            ['view', ['codeview']]
        ]
    });
    
    // Initialize Summernote for solution text
    $('#solution_text').summernote({
        placeholder: 'Describe the LFB solution...',
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol']],
            ['view', ['codeview']]
        ]
    });
    
    // Initialize Summernote for impact text
    $('#impact_text').summernote({
        placeholder: 'Describe the impact or benefits...',
        height: 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol']],
            ['view', ['codeview']]
        ]
    });
});
</script>
