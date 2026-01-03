<?php
require_once 'auth_check.php';
require_once '../database/db_config.php';

$conn = getDBConnection();

// Get statistics
$stats = [];

// Count blogs
$result = $conn->query("SELECT COUNT(*) as count FROM blogs");
$stats['blogs'] = $result ? $result->fetch_assoc()['count'] : 0;

// Count resources
$result = $conn->query("SELECT COUNT(*) as count FROM resources");
$stats['resources'] = $result ? $result->fetch_assoc()['count'] : 0;

// Count gallery images
$result = $conn->query("SELECT COUNT(*) as count FROM gallery");
$stats['gallery'] = $result ? $result->fetch_assoc()['count'] : 0;

$conn->close();

$page_title = 'Dashboard';
$current_page = 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin Panel</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <?php include 'includes/topbar.php'; ?>
            
            <!-- Page Content -->
            <div class="page-content">
                <div class="page-header">
                    <h1>Dashboard</h1>
                    <p class="page-subtitle">Overview of your website content and activity</p>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1);">
                            <svg style="color: #3b82f6;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo $stats['blogs']; ?></div>
                            <div class="stat-label">Blog Posts</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(233, 148, 49, 0.1);">
                            <svg style="color: #e99431;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                                <polyline points="13 2 13 9 20 9"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo $stats['resources']; ?></div>
                            <div class="stat-label">Resources</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1);">
                            <svg style="color: #10b981;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo $stats['gallery']; ?></div>
                            <div class="stat-label">Gallery Images</div>
                        </div>
                    </div>
                </div>
                
                <div class="quick-actions">
                    <h2>Quick Actions</h2>
                    <div class="actions-grid">
                        <a href="add-blog.php" class="action-card">
                            <div class="action-icon" style="background: #3b82f6;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                            </div>
                            <h3>Add New Blog Post</h3>
                            <p>Create and publish new blog content</p>
                        </a>
                        
                        <a href="add-resources.php" class="action-card">
                            <div class="action-icon" style="background: #e99431;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                            </div>
                            <h3>Add New Resource</h3>
                            <p>Upload downloadable resources</p>
                        </a>
                        
                        <a href="add-gallery.php" class="action-card">
                            <div class="action-icon" style="background: #10b981;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                            </div>
                            <h3>Add Gallery Image</h3>
                            <p>Upload new images to gallery</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
