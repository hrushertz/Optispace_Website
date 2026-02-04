<?php
/**
 * Shared Form for Live Projects (Add/Edit)
 * Expects $project array to be populated (can be empty values for add)
 * Expects $errors array (optional)
 * Expects $isEdit boolean (optional)
 */

if (!isset($project)) {
    $project = [];
}
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="15" y1="9" x2="9" y2="15" />
            <line x1="9" y1="9" x2="15" y2="15" />
        </svg>
        <ul style="margin: 0; padding-left: 1.25rem;">
            <?php foreach ($errors as $error): ?>
                <li>
                    <?php echo htmlspecialchars($error); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="admin-form">
    <?php if (isset($isEdit) && $isEdit): ?>
        <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($projectId); ?>">
    <?php endif; ?>

    <div class="form-layout">
        <div class="form-main">
            <div class="form-card">
                <div class="form-card-header">
                    <h2>Project Information</h2>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="title" class="form-label required">Project Title</label>
                        <input type="text" id="title" name="title" class="form-input"
                            value="<?php echo htmlspecialchars($project['title'] ?? ''); ?>"
                            placeholder="e.g., Automotive Assembly Plant Expansion" required>
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">slug (Optional)</label>
                        <input type="text" id="slug" name="slug" class="form-input"
                            value="<?php echo htmlspecialchars($project['slug'] ?? ''); ?>"
                            placeholder="Leave empty to auto-generate from title">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="client_name" class="form-label">Client Name</label>
                            <input type="text" id="client_name" name="client_name" class="form-input"
                                value="<?php echo htmlspecialchars($project['client_name'] ?? ''); ?>"
                                placeholder="e.g., Leading Automotive OEM">
                            <small class="form-help" style="color: var(--admin-gray-500); font-size: 0.8rem;">Can be
                                kept confidential if needed</small>
                        </div>

                        <div class="form-group">
                            <label for="project_type" class="form-label">Project Type</label>
                            <select id="project_type" name="project_type" class="form-select">
                                <option value="">Select Type</option>
                                <?php
                                $types = ['Greenfield', 'Brownfield', 'Layout Design', 'Process Optimization', 'Expansion'];
                                foreach ($types as $type) {
                                    $selected = (isset($project['project_type']) && $project['project_type'] === $type) ? 'selected' : '';
                                    echo "<option value=\"$type\" $selected>$type</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Short Description (Excerpt)</label>
                        <textarea id="description" name="description" class="form-textarea" rows="3"
                            placeholder="Brief description for the card..."><?php echo htmlspecialchars($project['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="content" class="form-label">Full Content</label>
                        <textarea id="content" name="content" class="form-textarea" rows="10"
                            placeholder="Detailed project information..."><?php echo htmlspecialchars($project['content'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="industry" class="form-label">Industry</label>
                            <input type="text" id="industry" name="industry" class="form-input"
                                value="<?php echo htmlspecialchars($project['industry'] ?? ''); ?>"
                                placeholder="e.g., Automotive, Pharmaceutical">
                        </div>

                        <div class="form-group">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" id="location" name="location" class="form-input"
                                value="<?php echo htmlspecialchars($project['location'] ?? ''); ?>"
                                placeholder="e.g., Pune, Maharashtra">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <h2>Project Status</h2>
                </div>
                <div class="form-card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="form-input"
                                value="<?php echo htmlspecialchars($project['start_date'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="expected_completion" class="form-label">Expected Completion</label>
                            <input type="date" id="expected_completion" name="expected_completion" class="form-input"
                                value="<?php echo htmlspecialchars($project['expected_completion'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="progress_percentage" class="form-label">Progress Percentage</label>
                            <div class="input-with-addon">
                                <input type="number" id="progress_percentage" name="progress_percentage"
                                    class="form-input"
                                    value="<?php echo (int) ($project['progress_percentage'] ?? 0); ?>" min="0"
                                    max="100">
                                <span class="input-addon">%</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="current_phase" class="form-label">Current Phase</label>
                            <select id="current_phase" name="current_phase" class="form-select">
                                <option value="">Select Phase</option>
                                <?php
                                $phases = ['Discovery', 'Value Stream Mapping', 'Layout Design', 'Detailed Design', 'Implementation', 'Review & Handover'];
                                foreach ($phases as $phase) {
                                    $selected = (isset($project['current_phase']) && $project['current_phase'] === $phase) ? 'selected' : '';
                                    echo "<option value=\"$phase\" $selected>$phase</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <h2>Project Highlights</h2>
                    <p class="form-card-subtitle"
                        style="font-size: 0.85rem; color: var(--admin-gray-500); margin-top: 0.25rem;">Add key
                        highlights or achievements to showcase</p>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="highlight_1" class="form-label">Highlight 1</label>
                        <input type="text" id="highlight_1" name="highlight_1" class="form-input"
                            value="<?php echo htmlspecialchars($project['highlight_1'] ?? ''); ?>"
                            placeholder="e.g., 40% reduction in material travel planned">
                    </div>

                    <div class="form-group">
                        <label for="highlight_2" class="form-label">Highlight 2</label>
                        <input type="text" id="highlight_2" name="highlight_2" class="form-input"
                            value="<?php echo htmlspecialchars($project['highlight_2'] ?? ''); ?>"
                            placeholder="e.g., Integrated AGV pathways">
                    </div>

                    <div class="form-group">
                        <label for="highlight_3" class="form-label">Highlight 3</label>
                        <input type="text" id="highlight_3" name="highlight_3" class="form-input"
                            value="<?php echo htmlspecialchars($project['highlight_3'] ?? ''); ?>"
                            placeholder="e.g., Future expansion ready design">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-sidebar">
            <div class="form-card">
                <div class="form-card-header">
                    <h2>Project Image</h2>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label class="form-label">Upload Image</label>
                        <div class="file-upload-area" id="imageUploadArea">
                            <input type="file" id="image" name="image" accept="image/*" class="file-input">
                            <div class="upload-placeholder"
                                style="<?php echo !empty($project['image_path']) ? 'display: none;' : ''; ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg>
                                <span>Click to upload or drag and drop</span>
                                <small>JPG, PNG, WebP or GIF (max. 5MB)</small>
                                <small
                                    style="display: block; margin-top: 5px; color: var(--admin-gray-500);">Recommended:
                                    400x400px image</small>
                            </div>
                            <div class="upload-preview <?php echo !empty($project['image_path']) ? 'active' : ''; ?>"
                                id="imagePreview">
                                <?php if (!empty($project['image_path'])): ?>
                                    <img src="../<?php echo htmlspecialchars($project['image_path']); ?>" alt="Preview">
                                    <button type="button" class="btn-remove-image" id="removeImage"
                                        style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.5); color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            style="width: 14px; height: 14px;">
                                            <line x1="18" y1="6" x2="6" y2="18" />
                                            <line x1="6" y1="6" x2="18" y2="18" />
                                        </svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <h2>Publish</h2>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_active" value="1" <?php echo (!isset($project['is_active']) || $project['is_active']) ? 'checked' : ''; ?>>
                            <span class="checkbox-text">Active</span>
                            <span class="checkbox-description">Visible on website</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_featured" value="1" <?php echo (isset($project['is_featured']) && $project['is_featured']) ? 'checked' : ''; ?>>
                            <span class="checkbox-text">Featured</span>
                            <span class="checkbox-description">Show on home page</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-input"
                            value="<?php echo (int) ($project['sort_order'] ?? 0); ?>" min="0">
                        <small style="color: var(--admin-gray-500); font-size: 0.8rem;">Lower numbers appear
                            first</small>
                    </div>
                </div>
                <div class="form-card-footer">
                    <a href="live-projects.php" class="btn btn-secondary"
                        style="background: transparent; border: 1px solid var(--admin-gray-300);">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        <?php echo isset($isEdit) && $isEdit ? 'Save Changes' : 'Save Project'; ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize CKEditor for Content
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
                    ]
                }
            })
            .catch(error => {
                console.error(error);
            });

        // Initialize CKEditor for Description (Simplified)
        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: ['bold', 'italic', '|', 'undo', 'redo'],
            })
            .catch(error => {
                console.error(error);
            });

        // Image preview functionality
        const imageInput = document.getElementById('image');
        const uploadArea = document.getElementById('imageUploadArea');
        const uploadPlaceholder = document.querySelector('.upload-placeholder');
        const imagePreview = document.getElementById('imagePreview');
        const removeFlag = document.getElementById('removeImageFlag');

        imageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Keep remove button if exists, or create new structure
                    imagePreview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="btn-remove-image" id="removeImage" style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.5); color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                `;
                    imagePreview.classList.add('active');
                    if (uploadPlaceholder) uploadPlaceholder.style.display = 'none';
                    if (removeFlag) removeFlag.value = '0';

                    // Re-bind remove event to new button
                    const remBtn = document.getElementById('removeImage');
                    if (remBtn) remBtn.addEventListener('click', removeImageHandler);
                };
                reader.readAsDataURL(file);
            }
        });

        // Remove image handler
        function removeImageHandler(e) {
            e.stopPropagation();
            e.preventDefault();
            imageInput.value = '';
            imagePreview.innerHTML = '';
            imagePreview.classList.remove('active');
            if (uploadPlaceholder) uploadPlaceholder.style.display = 'flex'; // Reset to column/flex
            if (removeFlag) removeFlag.value = '1';
        }

        const initialRemoveBtn = document.getElementById('removeImage');
        if (initialRemoveBtn) {
            initialRemoveBtn.addEventListener('click', removeImageHandler);
        }

        // Drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, function () {
                uploadArea.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, function () {
                uploadArea.classList.remove('dragover');
            });
        });

        uploadArea.addEventListener('drop', function (e) {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                imageInput.files = files;
                imageInput.dispatchEvent(new Event('change'));
            }
        });

        // Click on area to upload (but check if click is on remove button)
        uploadArea.addEventListener('click', function (e) {
            const removeBtn = document.getElementById('removeImage');
            if (!removeBtn || !removeBtn.contains(e.target)) {
                imageInput.click();
            }
        });
    });
</script>

<style>
    /* CKEditor overrides */
    .ck-editor__editable {
        min-height: 200px;
    }

    /* Form Styles */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .breadcrumb a {
        color: var(--admin-primary);
    }

    .breadcrumb .separator {
        color: var(--admin-gray-400);
    }

    .form-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.5rem;
        align-items: start;
    }

    .form-main {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-card {
        background: var(--admin-white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--admin-gray-200);
        overflow: hidden;
    }

    .form-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--admin-gray-100);
    }

    .form-card-header h2 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--admin-dark);
        margin: 0;
    }

    .form-card-body {
        padding: 1.5rem;
    }

    .form-card-footer {
        padding: 1rem 1.5rem;
        background: var(--admin-gray-50);
        border-top: 1px solid var(--admin-gray-100);
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--admin-gray-700);
        margin-bottom: 0.5rem;
    }

    .form-label.required::after {
        content: '*';
        color: var(--admin-danger);
        margin-left: 0.25rem;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--admin-gray-200);
        border-radius: var(--radius-md);
        font-size: 0.9rem;
        font-family: inherit;
        background: var(--admin-white);
        color: var(--admin-gray-700);
        transition: all var(--transition-fast);
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--admin-primary);
        box-shadow: 0 0 0 3px var(--admin-primary-light);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }

    /* Input with addon */
    .input-with-addon {
        display: flex;
        align-items: center;
    }

    .input-with-addon .form-input {
        border-radius: 8px 0 0 8px;
        border-right: none;
    }

    .input-addon {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-left: none;
        padding: 0.75rem 1rem;
        border-radius: 0 8px 8px 0;
        color: #64748b;
        font-weight: 500;
    }

    /* File Upload */
    .file-upload-area {
        position: relative;
        border: 2px dashed var(--admin-gray-200);
        border-radius: var(--radius-lg);
        padding: 2rem;
        text-align: center;
        transition: all var(--transition-fast);
        cursor: pointer;
    }

    .file-upload-area:hover {
        border-color: var(--admin-primary);
        background: var(--admin-primary-light);
    }

    .file-upload-area.dragover {
        border-color: var(--admin-primary);
        background: var(--admin-primary-light);
    }

    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .upload-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }

    .upload-placeholder svg {
        width: 48px;
        height: 48px;
        color: var(--admin-gray-400);
    }

    .upload-placeholder span {
        font-size: 0.95rem;
        color: var(--admin-gray-600);
    }

    .upload-placeholder small {
        font-size: 0.8rem;
        color: var(--admin-gray-400);
    }

    .upload-preview {
        display: none;
    }

    .upload-preview.active {
        display: block;
    }

    .upload-preview img {
        max-width: 100%;
        max-height: 200px;
        border-radius: var(--radius-md);
    }

    /* Checkbox */
    .checkbox-label {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        gap: 0.75rem;
        cursor: pointer;
        padding: 0.75rem;
        border-radius: var(--radius-md);
        transition: background var(--transition-fast);
    }

    .checkbox-label:hover {
        background: var(--admin-gray-50);
    }

    .checkbox-label input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-top: 2px;
        accent-color: var(--admin-primary);
    }

    .checkbox-text {
        font-weight: 500;
        color: var(--admin-dark);
        flex: 1;
    }

    .checkbox-description {
        font-size: 0.8rem;
        color: var(--admin-gray-500);
        width: 100%;
        padding-left: 1.75rem;
        margin-top: -0.25rem;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .form-layout {
            grid-template-columns: 1fr;
        }

        .form-sidebar {
            order: -1;
        }
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>