<?php
/**
 * Blogger - Add New Blog
 */

$pageTitle = "Write New Blog";
require_once __DIR__ . '/includes/auth.php';
requireBloggerLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$blogger = getCurrentBlogger();

$errors = [];

// Get categories
$categories = $conn->query("SELECT * FROM blog_categories WHERE is_active = 1 ORDER BY sort_order, name");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyBloggerCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors['general'] = 'Invalid security token. Please try again.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = $_POST['content'] ?? '';
        $metaTitle = trim($_POST['meta_title'] ?? '');
        $metaDescription = trim($_POST['meta_description'] ?? '');
        $readTime = (int)($_POST['read_time'] ?? 5);
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        
        // Generate slug if empty
        if (empty($slug)) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
            $slug = trim($slug, '-');
        }
        
        // Validate
        if (empty($title)) {
            $errors['title'] = 'Title is required.';
        }
        
        if (empty($content)) {
            $errors['content'] = 'Content is required.';
        }
        
        if ($categoryId <= 0) {
            $errors['category_id'] = 'Please select a category.';
        }
        
        // Check for duplicate slug
        if (empty($errors)) {
            $checkStmt = $conn->prepare("SELECT id FROM blogs WHERE slug = ?");
            $checkStmt->bind_param("s", $slug);
            $checkStmt->execute();
            if ($checkStmt->get_result()->num_rows > 0) {
                $slug = $slug . '-' . time();
            }
            $checkStmt->close();
        }
        
        // Save
        if (empty($errors)) {
            $publishedAt = $isPublished ? date('Y-m-d H:i:s') : null;
            
            $stmt = $conn->prepare("
                INSERT INTO blogs (category_id, author_id, title, slug, excerpt, content, meta_title, meta_description, read_time, is_published, published_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("iisssssssis", $categoryId, $blogger['id'], $title, $slug, $excerpt, $content, $metaTitle, $metaDescription, $readTime, $isPublished, $publishedAt);
            
            if ($stmt->execute()) {
                $newId = $conn->insert_id;
                logBloggerActivity($blogger['id'], 'create', 'blogs', $newId, 'Created blog: ' . $title);
                
                $conn->close();
                header('Location: blogs.php?success=created');
                exit;
            } else {
                $errors['general'] = 'Failed to create blog. Please try again.';
            }
            $stmt->close();
        }
    }
}

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<style>
/* Summernote Toolbar Customization */
.note-editor {
    border: 2px solid #E2E8F0 !important;
    border-radius: 12px !important;
    overflow: hidden;
}

.note-toolbar {
    background: #F8FAFC !important;
    border-bottom: 2px solid #E2E8F0 !important;
    padding: 12px 16px !important;
}

.note-btn-group {
    margin-right: 8px !important;
}

.note-btn {
    background: white !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 6px !important;
    color: #475569 !important;
    padding: 6px 10px !important;
    margin: 0 2px !important;
    transition: all 0.2s ease !important;
    font-size: 14px !important;
}

.note-btn:hover {
    background: #E99431 !important;
    border-color: #E99431 !important;
    color: white !important;
    transform: translateY(-1px);
}

.note-btn.active,
.note-btn:active {
    background: #E99431 !important;
    border-color: #E99431 !important;
    color: white !important;
}

.note-dropdown-menu {
    border: 1px solid #E2E8F0 !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    padding: 8px !important;
}

.note-dropdown-item {
    padding: 8px 12px !important;
    border-radius: 6px !important;
    transition: all 0.2s ease !important;
}

.note-dropdown-item:hover {
    background: #F1F5F9 !important;
    color: #E99431 !important;
}

.note-editable {
    background: white !important;
    padding: 20px !important;
    min-height: 400px !important;
    font-size: 15px !important;
    line-height: 1.7 !important;
    color: #334155 !important;
}

.note-editable:focus {
    background: white !important;
}

.note-statusbar {
    background: #F8FAFC !important;
    border-top: 1px solid #E2E8F0 !important;
    padding: 8px 16px !important;
}

.note-resizebar {
    background: #E2E8F0 !important;
    height: 3px !important;
}

/* Color palette customization */
.note-color .dropdown-toggle {
    width: 20px !important;
    height: 20px !important;
    border-radius: 4px !important;
}

/* Tooltip styling */
.note-tooltip {
    background: #1E293B !important;
    color: white !important;
    border-radius: 6px !important;
    padding: 6px 12px !important;
    font-size: 12px !important;
}
</style>

<div class="page-header">
    <div class="page-title-section">
        <nav class="breadcrumb">
            <a href="blogs.php">My Blogs</a>
            <span class="separator">/</span>
            <span>Write New Blog</span>
        </nav>
        <h1 class="page-title">Write New Blog</h1>
        <p class="page-subtitle">Create a new blog article</p>
    </div>
</div>

<?php if (isset($errors['general'])): ?>
    <div class="alert alert-danger">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span><?php echo htmlspecialchars($errors['general']); ?></span>
    </div>
<?php endif; ?>

<form method="POST" class="blog-form">
    <?php echo bloggerCsrfField(); ?>
    
    <div class="form-layout">
        <!-- Main Content -->
        <div class="form-main">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Blog Content</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="title" class="form-label">
                            Title <span class="required">*</span>
                        </label>
                        <input type="text" id="title" name="title" class="form-control <?php echo isset($errors['title']) ? 'error' : ''; ?>" 
                               placeholder="Enter blog title" required
                               value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                        <?php if (isset($errors['title'])): ?>
                            <span class="form-error"><?php echo $errors['title']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="slug" class="form-label">URL Slug</label>
                        <input type="text" id="slug" name="slug" class="form-control" 
                               placeholder="Auto-generated from title"
                               value="<?php echo htmlspecialchars($_POST['slug'] ?? ''); ?>">
                        <span class="form-hint">Leave empty to auto-generate from title</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="excerpt" class="form-label">Excerpt</label>
                        <textarea id="excerpt" name="excerpt" class="form-control" rows="3"
                                  placeholder="Brief summary of the article (displayed in blog listings)"><?php echo htmlspecialchars($_POST['excerpt'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="content" class="form-label">
                            Content <span class="required">*</span>
                        </label>
                        <textarea id="content" name="content" class="form-control <?php echo isset($errors['content']) ? 'error' : ''; ?>"><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
                        <?php if (isset($errors['content'])): ?>
                            <span class="form-error"><?php echo $errors['content']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- SEO Settings -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">SEO Settings</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" class="form-control" 
                               placeholder="SEO title (leave empty to use blog title)"
                               value="<?php echo htmlspecialchars($_POST['meta_title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" class="form-control" rows="2"
                                  placeholder="SEO description (leave empty to use excerpt)"><?php echo htmlspecialchars($_POST['meta_description'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="form-sidebar">
            <!-- Publish Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Publish</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="category_id" class="form-label">
                            Category <span class="required">*</span>
                        </label>
                        <select id="category_id" name="category_id" class="form-control form-select <?php echo isset($errors['category_id']) ? 'error' : ''; ?>" required>
                            <option value="">Select Category</option>
                            <?php while ($cat = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($_POST['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <?php if (isset($errors['category_id'])): ?>
                            <span class="form-error"><?php echo $errors['category_id']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="read_time" class="form-label">Read Time (minutes)</label>
                        <input type="number" id="read_time" name="read_time" class="form-control" 
                               min="1" max="60" value="<?php echo htmlspecialchars($_POST['read_time'] ?? '5'); ?>">
                    </div>
                    
                    <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem;">
                        <label class="toggle-switch">
                            <input type="checkbox" name="is_published" <?php echo ($_POST['is_published'] ?? false) ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                        <span>Publish immediately</span>
                    </div>
                    
                    <div class="form-actions" style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Save Blog
                        </button>
                        <a href="blogs.php" class="btn btn-secondary" style="width: 100%; text-align: center;">Cancel</a>
                    </div>
                </div>
            </div>
            
            <!-- Writing Tips -->
            <div class="card" style="margin-top: 1rem;">
                <div class="card-header">
                    <h3 class="card-title">Writing Tips</h3>
                </div>
                <div class="card-body" style="font-size: 0.875rem; color: #64748B;">
                    <ul style="padding-left: 1.25rem; margin: 0;">
                        <li style="margin-bottom: 0.5rem;">Use clear, descriptive titles</li>
                        <li style="margin-bottom: 0.5rem;">Break content into sections with headings</li>
                        <li style="margin-bottom: 0.5rem;">Include practical examples</li>
                        <li style="margin-bottom: 0.5rem;">Keep paragraphs short</li>
                        <li>End with a call to action</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.form-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.5rem;
    align-items: start;
}

.content-editor {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.9rem;
    line-height: 1.6;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.breadcrumb a {
    color: #64748B;
    text-decoration: none;
}

.breadcrumb a:hover {
    color: #3B82F6;
}

.breadcrumb .separator {
    color: #CBD5E1;
}

@media (max-width: 1024px) {
    .form-layout {
        grid-template-columns: 1fr;
    }
    
    .form-sidebar {
        order: -1;
    }
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    $('#content').summernote({
        placeholder: 'Write your blog content here...',
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        styleTags: [
            'p',
            { title: 'Heading 1', tag: 'h2', className: '', value: 'h2' },
            { title: 'Heading 2', tag: 'h3', className: '', value: 'h3' },
            { title: 'Heading 3', tag: 'h4', className: '', value: 'h4' },
            { title: 'Blockquote', tag: 'blockquote', className: '', value: 'blockquote' }
        ]
    });
});
</script>

<!-- Load admin.js last -->
<script src="../admin/assets/js/admin.js"></script>
</body>
</html>
