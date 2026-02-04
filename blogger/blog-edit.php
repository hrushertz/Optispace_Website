<?php
/**
 * Blogger - Edit Blog
 */

require_once __DIR__ . '/includes/auth.php';
requireBloggerLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();
$blogger = getCurrentBlogger();

$blogId = (int)($_GET['id'] ?? 0);
$errors = [];

if ($blogId <= 0) {
    header('Location: blogs.php?error=invalid');
    exit;
}

// Fetch blog with ownership check
$stmt = $conn->prepare("
    SELECT b.*, c.name as category_name 
    FROM blogs b 
    LEFT JOIN blog_categories c ON b.category_id = c.id 
    WHERE b.id = ? AND b.author_id = ?
");
$stmt->bind_param("ii", $blogId, $blogger['id']);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$blog) {
    header('Location: blogs.php?error=notfound');
    exit;
}

$pageTitle = "Edit Blog";

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
        
        // Check for duplicate slug (excluding current blog)
        if (empty($errors)) {
            $checkStmt = $conn->prepare("SELECT id FROM blogs WHERE slug = ? AND id != ?");
            $checkStmt->bind_param("si", $slug, $blogId);
            $checkStmt->execute();
            if ($checkStmt->get_result()->num_rows > 0) {
                $slug = $slug . '-' . time();
            }
            $checkStmt->close();
        }
        
        // Save
        if (empty($errors)) {
            // Handle published_at
            $publishedAt = $blog['published_at'];
            if ($isPublished && !$blog['is_published']) {
                // Newly publishing
                $publishedAt = date('Y-m-d H:i:s');
            } elseif (!$isPublished) {
                // Unpublishing - keep the old date for reference
            }
            
            $stmt = $conn->prepare("
                UPDATE blogs SET 
                    category_id = ?, 
                    title = ?, 
                    slug = ?, 
                    excerpt = ?, 
                    content = ?, 
                    meta_title = ?, 
                    meta_description = ?, 
                    read_time = ?, 
                    is_published = ?, 
                    published_at = ?,
                    updated_at = NOW()
                WHERE id = ? AND author_id = ?
            ");
            $stmt->bind_param("issssssissii", $categoryId, $title, $slug, $excerpt, $content, $metaTitle, $metaDescription, $readTime, $isPublished, $publishedAt, $blogId, $blogger['id']);
            
            if ($stmt->execute()) {
                logBloggerActivity($blogger['id'], 'update', 'blogs', $blogId, 'Updated blog: ' . $title);
                
                $conn->close();
                header('Location: blogs.php?success=updated');
                exit;
            } else {
                $errors['general'] = 'Failed to update blog. Please try again.';
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
            <span>Edit Blog</span>
        </nav>
        <h1 class="page-title">Edit Blog</h1>
        <p class="page-subtitle"><?php echo htmlspecialchars($blog['title']); ?></p>
    </div>
    
    <div class="page-actions">
        <?php if ($blog['is_published']): ?>
            <a href="/blog/article.php?slug=<?php echo urlencode($blog['slug']); ?>" class="btn btn-secondary" target="_blank">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                    <polyline points="15 3 21 3 21 9"/>
                    <line x1="10" y1="14" x2="21" y2="3"/>
                </svg>
                View Live
            </a>
        <?php endif; ?>
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

<!-- Blog Stats -->
<div class="blog-stats">
    <div class="stat-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>
        <span><strong><?php echo number_format($blog['view_count']); ?></strong> views</span>
    </div>
    <div class="stat-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
        </svg>
        <span><?php echo $blog['read_time']; ?> min read</span>
    </div>
    <div class="stat-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <span>Created <?php echo date('M j, Y', strtotime($blog['created_at'])); ?></span>
    </div>
    <?php if ($blog['published_at']): ?>
        <div class="stat-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <span>Published <?php echo date('M j, Y', strtotime($blog['published_at'])); ?></span>
        </div>
    <?php endif; ?>
</div>

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
                               value="<?php echo htmlspecialchars($_POST['title'] ?? $blog['title']); ?>">
                        <?php if (isset($errors['title'])): ?>
                            <span class="form-error"><?php echo $errors['title']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="slug" class="form-label">URL Slug</label>
                        <input type="text" id="slug" name="slug" class="form-control" 
                               placeholder="Auto-generated from title"
                               value="<?php echo htmlspecialchars($_POST['slug'] ?? $blog['slug']); ?>">
                        <span class="form-hint">Leave empty to auto-generate from title</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="excerpt" class="form-label">Excerpt</label>
                        <textarea id="excerpt" name="excerpt" class="form-control" rows="3"
                                  placeholder="Brief summary of the article"><?php echo htmlspecialchars($_POST['excerpt'] ?? $blog['excerpt']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="content" class="form-label">
                            Content <span class="required">*</span>
                        </label>
                        <textarea id="content" name="content" class="form-control <?php echo isset($errors['content']) ? 'error' : ''; ?>"><?php echo htmlspecialchars($_POST['content'] ?? $blog['content']); ?></textarea>
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
                               value="<?php echo htmlspecialchars($_POST['meta_title'] ?? $blog['meta_title']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" class="form-control" rows="2"
                                  placeholder="SEO description (leave empty to use excerpt)"><?php echo htmlspecialchars($_POST['meta_description'] ?? $blog['meta_description']); ?></textarea>
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
                                <option value="<?php echo $cat['id']; ?>" <?php echo (($_POST['category_id'] ?? $blog['category_id']) == $cat['id']) ? 'selected' : ''; ?>>
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
                               min="1" max="60" value="<?php echo htmlspecialchars($_POST['read_time'] ?? $blog['read_time']); ?>">
                    </div>
                    
                    <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem;">
                        <label class="toggle-switch">
                            <input type="checkbox" name="is_published" <?php echo (isset($_POST['is_published']) ? $_POST['is_published'] : $blog['is_published']) ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                        <span>Published</span>
                    </div>
                    
                    <div class="form-actions" style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Update Blog
                        </button>
                        <a href="blogs.php" class="btn btn-secondary" style="width: 100%; text-align: center;">Cancel</a>
                    </div>
                </div>
            </div>
            
            <!-- Danger Zone -->
            <div class="card" style="margin-top: 1rem; border-color: #FEE2E2;">
                <div class="card-header" style="background: #FEF2F2; border-color: #FEE2E2;">
                    <h3 class="card-title" style="color: #DC2626;">Danger Zone</h3>
                </div>
                <div class="card-body">
                    <p style="font-size: 0.875rem; color: #64748B; margin-bottom: 1rem;">
                        Need to delete this blog? Submit a delete request for admin approval.
                    </p>
                    <a href="request-delete.php?id=<?php echo $blogId; ?>" class="btn btn-danger" style="width: 100%;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        Request Delete
                    </a>
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

.blog-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    padding: 1rem 1.25rem;
    background: #F8FAFC;
    border-radius: 0.5rem;
    border: 1px solid #E2E8F0;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #64748B;
}

.stat-item svg {
    width: 16px;
    height: 16px;
}

.stat-item strong {
    color: #1E293B;
}

.btn-danger {
    background: #DC2626;
    color: white;
    border: none;
}

.btn-danger:hover {
    background: #B91C1C;
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
