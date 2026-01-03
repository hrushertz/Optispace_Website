<?php
/**
 * Blogger Panel Header
 * Clean sidebar layout for blog writers
 */

require_once __DIR__ . '/auth.php';

// Get current page for active state
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$blogger = getCurrentBlogger();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?>OptiSpace Blogger</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admin/assets/css/admin.css">
    <style>
        /* Blogger-specific overrides */
        .admin-sidebar { background: linear-gradient(180deg, #1a365d 0%, #2c5282 100%); }
        .sidebar-header { border-bottom-color: rgba(255,255,255,0.1); }
        .nav-link.active { background: rgba(255,255,255,0.15); }
        .nav-link:hover { background: rgba(255,255,255,0.1); }
        .logo-icon { background: rgba(59, 130, 246, 0.2); color: #90cdf4; }
    </style>
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="logo-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 19l7-7 3 3-7 7-3-3z"/>
                            <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/>
                            <path d="M2 2l7.586 7.586"/>
                            <circle cx="11" cy="11" r="2"/>
                        </svg>
                    </div>
                    <div class="logo-text">
                        <span class="logo-title">OptiSpace</span>
                        <span class="logo-subtitle">Blogger Panel</span>
                    </div>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <span class="nav-section-title">Main</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="7" height="7"/>
                                        <rect x="14" y="3" width="7" height="7"/>
                                        <rect x="14" y="14" width="7" height="7"/>
                                        <rect x="3" y="14" width="7" height="7"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <span class="nav-section-title">Content</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="blogs.php" class="nav-link <?php echo $currentPage === 'blogs' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                        <polyline points="10 9 9 9 8 9"/>
                                    </svg>
                                </span>
                                <span class="nav-text">My Blogs</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="blog-add.php" class="nav-link <?php echo $currentPage === 'blog-add' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9"/>
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Write New Blog</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="delete-requests.php" class="nav-link <?php echo $currentPage === 'delete-requests' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Delete Requests</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <span class="nav-section-title">Quick Links</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="../blogs.php" target="_blank" class="nav-link">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                        <polyline points="15 3 21 3 21 9"/>
                                        <line x1="10" y1="14" x2="21" y2="3"/>
                                    </svg>
                                </span>
                                <span class="nav-text">View Blog Page</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($blogger['full_name'] ?? 'B', 0, 1)); ?>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?php echo htmlspecialchars($blogger['full_name'] ?? 'Blogger'); ?></span>
                        <span class="user-role">Blog Writer</span>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn" title="Logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <div class="header-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" placeholder="Search blogs..." class="search-input">
                </div>
                <div class="header-actions">
                    <div class="header-user">
                        <div class="user-avatar small">
                            <?php echo strtoupper(substr($blogger['full_name'] ?? 'B', 0, 1)); ?>
                        </div>
                    </div>
                </div>
            </header>
            <div class="admin-content">
