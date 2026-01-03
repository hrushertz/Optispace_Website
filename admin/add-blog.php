<?php
require_once 'auth_check.php';
require_once '../database/db_config.php';

$page_title = 'Blog Editor';
$current_page = 'blog';
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? '';
    $author = trim($_POST['author'] ?? '');
    $status = $_POST['status'] ?? 'draft';
    
    // Generate slug if empty
    if (empty($slug) && !empty($title)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    }
    
    if (!empty($title) && !empty($content)) {
        $conn = getDBConnection();
        
        // Handle image upload
        $featured_image = '';
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
            $upload_dir = '../uploads/blog/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = uniqid('blog_') . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $upload_path)) {
                    $featured_image = 'uploads/blog/' . $new_filename;
                }
            }
        }
        
        $stmt = $conn->prepare("INSERT INTO blogs (title, slug, excerpt, content, featured_image, author, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $title, $slug, $excerpt, $content, $featured_image, $author, $status);
        
        if ($stmt->execute()) {
            $success = 'Blog post created successfully!';
            // Clear form
            $_POST = [];
        } else {
            $error = 'Error creating blog post: ' . $conn->error;
        }
        
        $stmt->close();
        $conn->close();
    } else {
        $error = 'Please fill in all required fields';
    }
}

// Get all blogs
$conn = getDBConnection();
$blogs_result = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
$blogs = [];
if ($blogs_result) {
    while ($row = $blogs_result->fetch_assoc()) {
        $blogs[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin Panel</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include 'includes/topbar.php'; ?>
            
            <div class="page-content">
                <div class="page-header">
                    <h1>Blog Editor</h1>
                    <p class="page-subtitle">Create and manage blog posts</p>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-container">
                    <h2 style="margin-bottom: 1.5rem;">Add New Blog Post</h2>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-input" required value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Slug (URL-friendly name)</label>
                            <input type="text" name="slug" class="form-input" placeholder="auto-generated-from-title" value="<?php echo htmlspecialchars($_POST['slug'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Excerpt</label>
                            <textarea name="excerpt" class="form-textarea" placeholder="Short description..."><?php echo htmlspecialchars($_POST['excerpt'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Content *</label>
                            <textarea id="blog-content" name="content" class="form-textarea"><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Featured Image</label>
                            <input type="file" name="featured_image" class="form-input" accept="image/*">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Author</label>
                            <input type="text" name="author" class="form-input" value="<?php echo htmlspecialchars($_POST['author'] ?? 'Admin'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft" <?php echo (($_POST['status'] ?? '') === 'draft') ? 'selected' : ''; ?>>Draft</option>
                                <option value="published" <?php echo (($_POST['status'] ?? '') === 'published') ? 'selected' : ''; ?>>Published</option>
                            </select>
                        </div>
                        
                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" class="btn-primary">Save Blog Post</button>
                            <a href="dashboard.php" class="btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
                
                <?php if (count($blogs) > 0): ?>
                    <div style="margin-top: 3rem;">
                        <h2 style="margin-bottom: 1.5rem;">Existing Blog Posts</h2>
                        <div class="data-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($blogs as $blog): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($blog['title']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($blog['author'] ?? 'Unknown'); ?></td>
                                            <td>
                                                <span style="padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600; 
                                                    <?php echo $blog['status'] === 'published' ? 'background: #d1fae5; color: #065f46;' : 'background: #fef3c7; color: #92400e;'; ?>">
                                                    <?php echo ucfirst($blog['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></td>
                                            <td>
                                                <a href="edit-blog.php?id=<?php echo $blog['id']; ?>" style="color: #3b82f6; text-decoration: none; margin-right: 1rem;">Edit</a>
                                                <a href="delete-blog.php?id=<?php echo $blog['id']; ?>" style="color: #ef4444; text-decoration: none;" onclick="return confirm('Are you sure?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        tinymce.init({
            selector: '#blog-content',
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; }'
        });
    </script>
</body>
</html>
