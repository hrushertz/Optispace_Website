<?php
/**
 * Admin Panel Header
 * Professional sidebar layout with modern design
 */

require_once __DIR__ . '/auth.php';

// Prevent editors from accessing admin panel
if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'editor') {
    logoutAdmin();
    header('Location: ../../blogger/login.php?msg=admin_redirect');
    exit;
}

// Get current page for active state
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$admin = getCurrentAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?>OptiSpace Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="logo-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </div>
                    <div class="logo-text">
                        <span class="logo-title">OptiSpace</span>
                        <span class="logo-subtitle">Admin Panel</span>
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
                        <li class="nav-item">
                            <a href="pulse-checks.php" class="nav-link <?php echo $currentPage === 'pulse-checks' || $currentPage === 'pulse-check-view' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Pulse Checks</span>
                                <?php
                                // Show new submissions count badge
                                $pulseConn = getDBConnection();
                                $newPulseCount = $pulseConn->query("SELECT COUNT(*) as cnt FROM pulse_check_submissions WHERE status = 'new'")->fetch_assoc()['cnt'];
                                $pulseConn->close();
                                if ($newPulseCount > 0): ?>
                                    <span class="nav-badge"><?php echo $newPulseCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pulse-check-faqs.php" class="nav-link <?php echo $currentPage === 'pulse-check-faqs' || $currentPage === 'pulse-check-faq-add' || $currentPage === 'pulse-check-faq-edit' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Pulse Check FAQs</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="inquiries.php" class="nav-link <?php echo $currentPage === 'inquiries' || $currentPage === 'inquiry-view' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Inquiries</span>
                                <?php
                                // Show new inquiries count badge
                                $inquiryConn = getDBConnection();
                                $newInquiryCount = $inquiryConn->query("SELECT COUNT(*) as cnt FROM inquiry_submissions WHERE status = 'new'")->fetch_assoc()['cnt'];
                                $inquiryConn->close();
                                if ($newInquiryCount > 0): ?>
                                    <span class="nav-badge"><?php echo $newInquiryCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <span class="nav-section-title">Content</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="downloads.php" class="nav-link <?php echo $currentPage === 'downloads' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Downloads</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="categories.php" class="nav-link <?php echo $currentPage === 'categories' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Categories</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="waste-items.php" class="nav-link <?php echo $currentPage === 'waste-items' || $currentPage === 'waste-item-add' || $currentPage === 'waste-item-edit' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Waste Items (Mudas)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="team-members.php" class="nav-link <?php echo $currentPage === 'team-members' || $currentPage === 'team-member-add' || $currentPage === 'team-member-edit' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Team Members</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <span class="nav-section-title">Blog</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="blogs.php" class="nav-link <?php echo $currentPage === 'blogs' || $currentPage === 'blog-view' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 19l7-7 3 3-7 7-3-3z"/>
                                        <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/>
                                        <path d="M2 2l7.586 7.586"/>
                                        <circle cx="11" cy="11" r="2"/>
                                    </svg>
                                </span>
                                <span class="nav-text">All Blogs</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="blog-delete-requests.php" class="nav-link <?php echo $currentPage === 'blog-delete-requests' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Delete Requests</span>
                                <?php
                                // Show pending count badge
                                $dbConn = getDBConnection();
                                $pendingCount = $dbConn->query("SELECT COUNT(*) as cnt FROM blog_delete_requests WHERE status = 'pending'")->fetch_assoc()['cnt'];
                                $dbConn->close();
                                if ($pendingCount > 0): ?>
                                    <span class="nav-badge"><?php echo $pendingCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="blog-categories.php" class="nav-link <?php echo $currentPage === 'blog-categories' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="8" y1="6" x2="21" y2="6"/>
                                        <line x1="8" y1="12" x2="21" y2="12"/>
                                        <line x1="8" y1="18" x2="21" y2="18"/>
                                        <line x1="3" y1="6" x2="3.01" y2="6"/>
                                        <line x1="3" y1="12" x2="3.01" y2="12"/>
                                        <line x1="3" y1="18" x2="3.01" y2="18"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Blog Categories</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <span class="nav-section-title">Gallery</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="gallery.php" class="nav-link <?php echo $currentPage === 'gallery' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Gallery Items</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="gallery-categories.php" class="nav-link <?php echo $currentPage === 'gallery-categories' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="8" y1="6" x2="21" y2="6"/>
                                        <line x1="8" y1="12" x2="21" y2="12"/>
                                        <line x1="8" y1="18" x2="21" y2="18"/>
                                        <line x1="3" y1="6" x2="3.01" y2="6"/>
                                        <line x1="3" y1="12" x2="3.01" y2="12"/>
                                        <line x1="3" y1="18" x2="3.01" y2="18"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Gallery Categories</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="featured-projects.php" class="nav-link <?php echo $currentPage === 'featured-projects' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Featured Projects</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="gallery-industries.php" class="nav-link <?php echo $currentPage === 'gallery-industries' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 21h18"/>
                                        <path d="M5 21V7l8-4v18"/>
                                        <path d="M19 21V11l-6-4"/>
                                        <path d="M9 9v.01"/>
                                        <path d="M9 12v.01"/>
                                        <path d="M9 15v.01"/>
                                        <path d="M9 18v.01"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Industries</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <span class="nav-section-title">Portfolio</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="client-videos.php" class="nav-link <?php echo $currentPage === 'client-videos' || $currentPage === 'client-video-add' || $currentPage === 'client-video-edit' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="23 7 16 12 23 17 23 7"/>
                                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Client Videos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="success-stories.php" class="nav-link <?php echo $currentPage === 'success-stories' || $currentPage === 'success-story-add' || $currentPage === 'success-story-edit' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Success Stories</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <?php if (hasAdminRole('admin')): ?>
                <div class="nav-section">
                    <span class="nav-section-title">Administration</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="users.php" class="nav-link <?php echo $currentPage === 'users' || $currentPage === 'user-add' || $currentPage === 'user-edit' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Users</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="settings.php" class="nav-link <?php echo $currentPage === 'settings' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="3"/>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Site Settings</span>
                                <?php
                                // Show maintenance badge if enabled
                                require_once __DIR__ . '/../../includes/config.php';
                                if (isMaintenanceMode()): ?>
                                    <span class="nav-badge" style="background: #EF4444;">!</span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" onclick="alert('Activity log coming soon!'); return false;">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                                    </svg>
                                </span>
                                <span class="nav-text">Activity Log</span>
                                <span style="margin-left: auto; font-size: 0.7rem; opacity: 0.5;">(Soon)</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="nav-section">
                    <span class="nav-section-title">Quick Links</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="../downloads.php" target="_blank" class="nav-link">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                        <polyline points="15 3 21 3 21 9"/>
                                        <line x1="10" y1="14" x2="21" y2="3"/>
                                    </svg>
                                </span>
                                <span class="nav-text">View Website</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($admin['full_name'] ?? 'A', 0, 1)); ?>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?php echo htmlspecialchars($admin['full_name'] ?? 'Admin'); ?></span>
                        <span class="user-role"><?php echo ucfirst(str_replace('_', ' ', $admin['role'] ?? 'Admin')); ?></span>
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
                    <input type="text" placeholder="Search..." class="search-input">
                </div>
                <div class="header-actions">
                    <button class="header-action-btn" title="Notifications">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                    </button>
                    <div class="header-user">
                        <div class="user-avatar small">
                            <?php echo strtoupper(substr($admin['full_name'] ?? 'A', 0, 1)); ?>
                        </div>
                    </div>
                </div>
            </header>
            <div class="admin-content">
