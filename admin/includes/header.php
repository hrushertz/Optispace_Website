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
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                    </div>
                    <div class="logo-text">
                        <span class="logo-title">OptiSpace</span>
                        <span class="logo-subtitle">Admin Panel</span>
                    </div>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <span class="nav-section-title">Main</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="dashboard.php"
                                class="nav-link <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="7" height="7" />
                                        <rect x="14" y="3" width="7" height="7" />
                                        <rect x="14" y="14" width="7" height="7" />
                                        <rect x="3" y="14" width="7" height="7" />
                                    </svg>
                                </span>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>

                        <?php
                        // Group: Banner Management
                        $bannerPages = ['banner-home', 'banner-philosophy', 'banner-greenfield', 'banner-brownfield', 'banner-post-commissioning', 'banner-process', 'banner-portfolio', 'banner-about', 'banner-leadership', 'banner-team', 'banner-live-projects', 'banner-downloads', 'banner-blogs', 'banner-gallery', 'banner-contact', 'banner-pulse-check'];
                        $isBannerActive = in_array($currentPage, $bannerPages);
                        ?>
                        <li class="nav-item nav-dropdown <?php echo $isBannerActive ? 'active' : ''; ?>">
                            <a href="#" class="nav-link nav-dropdown-toggle"
                                aria-expanded="<?php echo $isBannerActive ? 'true' : 'false'; ?>">
                                <div class="nav-link-content">
                                    <span class="nav-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" />
                                        </svg>
                                    </span>
                                    <span class="nav-text">Banner</span>
                                </div>
                                <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </a>
                            <ul class="nav-dropdown-menu">
                                <li class="nav-dropdown-item">
                                    <a href="banner-home.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-home' ? 'active' : ''; ?>">
                                        Home
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="process-flow-image.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'process-flow-image' ? 'active' : ''; ?>">
                                        Home Process Flow
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-home-outcomes.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-home-outcomes' ? 'active' : ''; ?>">
                                        Home Outcomes
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-philosophy.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-philosophy' ? 'active' : ''; ?>">
                                        Philosophy
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-greenfield.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-greenfield' ? 'active' : ''; ?>">
                                        Greenfield
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-brownfield.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-brownfield' ? 'active' : ''; ?>">
                                        Brownfield
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-post-commissioning.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-post-commissioning' ? 'active' : ''; ?>">
                                        Post-Commissioning
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-process.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-process' ? 'active' : ''; ?>">
                                        Our Process
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-portfolio.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-portfolio' ? 'active' : ''; ?>">
                                        Portfolio
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-about.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-about' ? 'active' : ''; ?>">
                                        About Us
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-leadership.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-leadership' ? 'active' : ''; ?>">
                                        Leadership
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-team.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-team' ? 'active' : ''; ?>">
                                        Team
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-live-projects.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-live-projects' ? 'active' : ''; ?>">
                                        Live Projects
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-downloads.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-downloads' ? 'active' : ''; ?>">
                                        Downloads
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-blogs.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-blogs' ? 'active' : ''; ?>">
                                        Blogs
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-gallery.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-gallery' ? 'active' : ''; ?>">
                                        Gallery
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-contact.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-contact' ? 'active' : ''; ?>">
                                        Contact
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="banner-pulse-check.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'banner-pulse-check' ? 'active' : ''; ?>">
                                        Pulse Check
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <?php
                        // Group: Pulse Checks
                        $pulsePages = ['pulse-checks', 'pulse-check-view', 'pulse-check-faqs', 'pulse-check-faq-add', 'pulse-check-faq-edit'];
                        $isPulseActive = in_array($currentPage, $pulsePages);

                        // New counts
                        $pulseConn = getDBConnection();
                        $newPulseCount = $pulseConn->query("SELECT COUNT(*) as cnt FROM pulse_check_submissions WHERE status = 'new'")->fetch_assoc()['cnt'];
                        $pulseConn->close();
                        ?>
                        <li class="nav-item nav-dropdown <?php echo $isPulseActive ? 'active' : ''; ?>">
                            <a href="#" class="nav-link nav-dropdown-toggle"
                                aria-expanded="<?php echo $isPulseActive ? 'true' : 'false'; ?>">
                                <div class="nav-link-content">
                                    <span class="nav-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                        </svg>
                                    </span>
                                    <span class="nav-text">Pulse Checks</span>
                                </div>
                                <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                                <?php if ($newPulseCount > 0): ?>
                                    <span class="nav-badge"><?php echo $newPulseCount; ?></span>
                                <?php endif; ?>
                            </a>
                            <ul class="nav-dropdown-menu">
                                <li class="nav-dropdown-item">
                                    <a href="pulse-checks.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'pulse-checks' || $currentPage === 'pulse-check-view' ? 'active' : ''; ?>">
                                        Submissions
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="pulse-check-faqs.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'pulse-check-faqs' || $currentPage === 'pulse-check-faq-add' || $currentPage === 'pulse-check-faq-edit' ? 'active' : ''; ?>">
                                        FAQs
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <?php
                        // Group: Inquiries
                        $inquiryPages = ['inquiries', 'inquiry-view'];
                        $isInquiryActive = in_array($currentPage, $inquiryPages);

                        $inquiryConn = getDBConnection();
                        $newInquiryCount = $inquiryConn->query("SELECT COUNT(*) as cnt FROM inquiry_submissions WHERE status = 'new'")->fetch_assoc()['cnt'];
                        $inquiryConn->close();
                        ?>
                        <li class="nav-item">
                            <a href="inquiries.php" class="nav-link <?php echo $isInquiryActive ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                        <polyline points="22,6 12,13 2,6" />
                                    </svg>
                                </span>
                                <span class="nav-text">Inquiries</span>
                                <?php if ($newInquiryCount > 0): ?>
                                    <span class="nav-badge"><?php echo $newInquiryCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <span class="nav-section-title">Manage</span>
                    <ul class="nav-list">
                        <?php
                        // Group: Content
                        $contentPages = ['downloads', 'categories', 'waste-items', 'waste-item-add', 'waste-item-edit', 'leadership', 'leadership-add', 'leadership-edit', 'team-members', 'team-member-add', 'team-member-edit'];
                        $isContentActive = in_array($currentPage, $contentPages);
                        ?>
                        <li class="nav-item nav-dropdown <?php echo $isContentActive ? 'active' : ''; ?>">
                            <a href="#" class="nav-link nav-dropdown-toggle"
                                aria-expanded="<?php echo $isContentActive ? 'true' : 'false'; ?>">
                                <div class="nav-link-content">
                                    <span class="nav-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </span>
                                    <span class="nav-text">Content</span>
                                </div>
                                <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </a>
                            <ul class="nav-dropdown-menu">
                                <li class="nav-dropdown-item">
                                    <a href="downloads.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'downloads' ? 'active' : ''; ?>">Downloads</a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="categories.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'categories' ? 'active' : ''; ?>">Categories</a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="waste-items.php"
                                        class="nav-dropdown-link <?php echo strpos($currentPage, 'waste-item') !== false ? 'active' : ''; ?>">Waste
                                        Items</a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="leadership.php"
                                        class="nav-dropdown-link <?php echo strpos($currentPage, 'leadership') !== false && strpos($currentPage, 'banner') === false ? 'active' : ''; ?>">
                                        Leadership
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="team-members.php"
                                        class="nav-dropdown-link <?php echo strpos($currentPage, 'team-member') !== false ? 'active' : ''; ?>">Team
                                        Members</a>
                                </li>
                            </ul>
                        </li>

                        <?php
                        // Group: Blog
                        $blogPages = ['blogs', 'blog-view', 'blog-delete-requests', 'blog-categories'];
                        $isBlogActive = in_array($currentPage, $blogPages);

                        $dbConn = getDBConnection();
                        $pendingBlogCount = $dbConn->query("SELECT COUNT(*) as cnt FROM blog_delete_requests WHERE status = 'pending'")->fetch_assoc()['cnt'];
                        $dbConn->close();
                        ?>
                        <li class="nav-item nav-dropdown <?php echo $isBlogActive ? 'active' : ''; ?>">
                            <a href="#" class="nav-link nav-dropdown-toggle"
                                aria-expanded="<?php echo $isBlogActive ? 'true' : 'false'; ?>">
                                <div class="nav-link-content">
                                    <span class="nav-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                        </svg>
                                    </span>
                                    <span class="nav-text">Blog</span>
                                </div>
                                <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                                <?php if ($pendingBlogCount > 0): ?>
                                    <span class="nav-badge"><?php echo $pendingBlogCount; ?></span>
                                <?php endif; ?>
                            </a>
                            <ul class="nav-dropdown-menu">
                                <li class="nav-dropdown-item">
                                    <a href="blogs.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'blogs' || $currentPage === 'blog-view' ? 'active' : ''; ?>">All
                                        Blogs</a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="blog-delete-requests.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'blog-delete-requests' ? 'active' : ''; ?>">
                                        Delete Requests
                                        <?php if ($pendingBlogCount > 0): ?>
                                            <span class="nav-badge"
                                                style="margin-left:5px; font-size:0.6rem;"><?php echo $pendingBlogCount; ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="blog-categories.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'blog-categories' ? 'active' : ''; ?>">Categories</a>
                                </li>
                            </ul>
                        </li>

                        <?php
                        // Group: Gallery
                        $galleryPages = ['gallery', 'gallery-add', 'gallery-edit', 'gallery-categories', 'featured-projects', 'featured-project-add', 'featured-project-edit', 'gallery-industries'];
                        $isGalleryActive = in_array($currentPage, $galleryPages);
                        ?>
                        <li class="nav-item nav-dropdown <?php echo $isGalleryActive ? 'active' : ''; ?>">
                            <a href="#" class="nav-link nav-dropdown-toggle"
                                aria-expanded="<?php echo $isGalleryActive ? 'true' : 'false'; ?>">
                                <div class="nav-link-content">
                                    <span class="nav-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" />
                                        </svg>
                                    </span>
                                    <span class="nav-text">Gallery</span>
                                </div>
                                <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </a>
                            <ul class="nav-dropdown-menu">
                                <li class="nav-dropdown-item">
                                    <a href="gallery.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'gallery' || $currentPage === 'gallery-add' || $currentPage === 'gallery-edit' ? 'active' : ''; ?>">Gallery
                                        Items</a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="gallery-categories.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'gallery-categories' ? 'active' : ''; ?>">Categories</a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="featured-projects.php"
                                        class="nav-dropdown-link <?php echo strpos($currentPage, 'featured-project') !== false ? 'active' : ''; ?>">Featured
                                        Projects</a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="gallery-industries.php"
                                        class="nav-dropdown-link <?php echo $currentPage === 'gallery-industries' ? 'active' : ''; ?>">Industries</a>
                                </li>
                            </ul>
                        </li>

                        <?php
                        // Group: Portfolio
                        $portfolioPages = ['client-videos', 'client-video-add', 'client-video-edit', 'success-stories', 'success-story-add', 'success-story-edit', 'live-projects', 'live-project-add', 'live-project-edit'];
                        $isPortfolioActive = in_array($currentPage, $portfolioPages);
                        ?>
                        <li class="nav-item nav-dropdown <?php echo $isPortfolioActive ? 'active' : ''; ?>">
                            <a href="#" class="nav-link nav-dropdown-toggle"
                                aria-expanded="<?php echo $isPortfolioActive ? 'true' : 'false'; ?>">
                                <div class="nav-link-content">
                                    <span class="nav-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                            <polyline points="2 17 12 22 22 17"></polyline>
                                            <polyline points="2 12 12 17 22 12"></polyline>
                                        </svg>
                                    </span>
                                    <span class="nav-text">Portfolio</span>
                                </div>
                                <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </a>
                            <ul class="nav-dropdown-menu">
                                <li class="nav-dropdown-item">
                                    <a href="client-videos.php"
                                        class="nav-dropdown-link <?php echo strpos($currentPage, 'client-video') !== false ? 'active' : ''; ?>">Client
                                        Videos</a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="success-stories.php"
                                        class="nav-dropdown-link <?php echo strpos($currentPage, 'success-story') !== false ? 'active' : ''; ?>">Success
                                        Stories</a>
                                </li>
                                <li class="nav-dropdown-item">
                                    <a href="live-projects.php"
                                        class="nav-dropdown-link <?php echo strpos($currentPage, 'live-project') !== false ? 'active' : ''; ?>">Live
                                        Projects</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <?php if (hasAdminRole('admin')): ?>
                    <div class="nav-section">
                        <span class="nav-section-title">Administration</span>
                        <ul class="nav-list">
                            <?php
                            // Group: Admin
                            $adminPages = ['users', 'user-add', 'user-edit', 'settings'];
                            $isAdminActive = in_array($currentPage, $adminPages);
                            ?>
                            <li class="nav-item nav-dropdown <?php echo $isAdminActive ? 'active' : ''; ?>">
                                <a href="#" class="nav-link nav-dropdown-toggle"
                                    aria-expanded="<?php echo $isAdminActive ? 'true' : 'false'; ?>">
                                    <div class="nav-link-content">
                                        <span class="nav-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                            </svg>
                                        </span>
                                        <span class="nav-text">System</span>
                                    </div>
                                    <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </a>
                                <ul class="nav-dropdown-menu">
                                    <li class="nav-dropdown-item">
                                        <a href="users.php"
                                            class="nav-dropdown-link <?php echo strpos($currentPage, 'user') !== false ? 'active' : ''; ?>">Users</a>
                                    </li>
                                    <li class="nav-dropdown-item">
                                        <a href="settings.php"
                                            class="nav-dropdown-link <?php echo $currentPage === 'settings' ? 'active' : ''; ?>">
                                            Settings
                                            <?php
                                            require_once __DIR__ . '/../../includes/config.php';
                                            if (isMaintenanceMode()): ?>
                                                <span class="nav-badge"
                                                    style="margin-left:5px; background: #EF4444; width:8px; height:8px; padding:0; display:inline-block; border-radius:50%;"></span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                    <li class="nav-dropdown-item">
                                        <a href="#" class="nav-dropdown-link"
                                            onclick="alert('Activity log coming soon!'); return false;">
                                            Activity Log <span
                                                style="font-size: 0.7rem; opacity: 0.5; margin-left: 5px;">(Soon)</span>
                                        </a>
                                    </li>
                                </ul>
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
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                        <polyline points="15 3 21 3 21 9" />
                                        <line x1="10" y1="14" x2="21" y2="3" />
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
                        <span
                            class="user-role"><?php echo ucfirst(str_replace('_', ' ', $admin['role'] ?? 'Admin')); ?></span>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn" title="Logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>
                <div class="header-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" placeholder="Search..." class="search-input">
                </div>
                <div class="header-actions">
                    <button class="header-action-btn" title="Notifications">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
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