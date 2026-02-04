<?php
// Include configuration
require_once __DIR__ . '/config.php';

// Check maintenance mode (allows blog pages by default)
checkMaintenanceMode(true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary Meta Tags -->
    <title><?php echo $pageTitle ?? 'Solutions OptiSpace - Lean Factory Building Architecture'; ?></title>
    <meta name="title"
        content="<?php echo $pageTitle ?? 'Solutions OptiSpace - Lean Factory Building Architecture'; ?>">
    <meta name="description"
        content="<?php echo $pageDescription ?? 'Solutions OptiSpace - Lean Factory Building (LFB) Architecture. We design factories from the inside out, optimizing manufacturing process flow before construction.'; ?>">
    <meta name="keywords"
        content="<?php echo $pageKeywords ?? 'lean factory building, LFB architecture, factory design, lean manufacturing, industrial architecture, greenfield factory, brownfield optimization, manufacturing plant design, factory layout optimization, lean consultants, OptiSpace, Solutions KMS, process flow design, value stream mapping, factory planning, industrial facility design, manufacturing optimization, India factory design'; ?>">
    <meta name="author" content="Solutions OptiSpace">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url"
        content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title"
        content="<?php echo $pageTitle ?? 'Solutions OptiSpace - Lean Factory Building Architecture'; ?>">
    <meta property="og:description"
        content="<?php echo $pageDescription ?? 'Solutions OptiSpace - Lean Factory Building (LFB) Architecture. We design factories from the inside out, optimizing manufacturing process flow before construction.'; ?>">
    <meta property="og:image"
        content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/assets/img/optispace-og-image.jpg'; ?>">
    <meta property="og:site_name" content="Solutions OptiSpace">
    <meta property="og:locale" content="en_US">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url"
        content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title"
        content="<?php echo $pageTitle ?? 'Solutions OptiSpace - Lean Factory Building Architecture'; ?>">
    <meta property="twitter:description"
        content="<?php echo $pageDescription ?? 'Solutions OptiSpace - Lean Factory Building (LFB) Architecture. We design factories from the inside out, optimizing manufacturing process flow before construction.'; ?>">
    <meta property="twitter:image"
        content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/assets/img/optispace-og-image.jpg'; ?>">

    <!-- Additional SEO Tags -->
    <meta name="theme-color" content="#E99431">
    <meta name="msapplication-TileColor" content="#E99431">
    <link rel="canonical"
        href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>?v=<?php echo time(); ?>">
</head>

<body class="<?php echo isset($currentPage) && $currentPage === 'home' ? 'home-page' : ''; ?>">
    <!-- Preloader -->
    <div id="preloader">
        <div class="minimal-loader">
            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>
        </div>
    </div>
    <script>
        // Fallback: Force hide preloader if main.js fails to load or error occurs
        window.addEventListener('load', function () {
            setTimeout(function () {
                var p = document.getElementById('preloader');
                if (p && p.style.display !== 'none') {
                    p.classList.add('fade-out');
                    setTimeout(function () { p.style.display = 'none'; }, 500);
                }
            }, 1000); // 1 second safety buffer
        });
        // Ultimate fallback if load never fires
        setTimeout(function () {
            var p = document.getElementById('preloader');
            if (p && p.style.display !== 'none') {
                p.style.display = 'none';
            }
        }, 8000); 
    </script>
    <header class="site-header <?php echo isset($currentPage) && $currentPage === 'home' ? 'transparent' : ''; ?>">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="<?php echo url('index.php'); ?>">
                        <img src="<?php echo img('optispace.png'); ?>" alt="OptiSpace Logo" class="logo-img">
                    </a>
                </div>
                <button class="mobile-menu-toggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <!-- Mobile Side Panel Overlay -->
                <div class="mobile-nav-overlay"></div>

                <!-- Mobile Side Panel -->
                <nav class="mobile-nav-panel">
                    <div class="mobile-nav-header">
                        <img src="<?php echo img('optispace.png'); ?>" alt="OptiSpace Logo" class="mobile-logo">
                        <button class="mobile-nav-close" aria-label="Close menu">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>
                    <ul class="mobile-nav-list">
                        <li><a href="<?php echo url('index.php'); ?>"
                                class="<?php echo ($currentPage ?? '') == 'home' ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="<?php echo url('philosophy.php'); ?>"
                                class="<?php echo ($currentPage ?? '') == 'philosophy' ? 'active' : ''; ?>">Philosophy</a>
                        </li>
                        <li class="mobile-has-submenu">
                            <a href="#"
                                class="mobile-submenu-toggle <?php echo in_array(($currentPage ?? ''), ['greenfield', 'brownfield', 'post-commissioning']) ? 'active' : ''; ?>">
                                Services
                                <svg class="mobile-dropdown-icon" width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </a>
                            <ul class="mobile-submenu">
                                <li><a href="<?php echo url('services/greenfield.php'); ?>">Greenfield Projects</a></li>
                                <li><a href="<?php echo url('services/brownfield.php'); ?>">Brownfield Projects</a></li>
                                <li><a
                                        href="<?php echo url('services/post-commissioning.php'); ?>">Post-Commissioning</a>
                                </li>
                            </ul>
                        </li>
                        <li><a href="<?php echo url('process.php'); ?>"
                                class="<?php echo ($currentPage ?? '') == 'process' ? 'active' : ''; ?>">Our Process</a>
                        </li>
                        <li><a href="<?php echo url('portfolio.php'); ?>"
                                class="<?php echo ($currentPage ?? '') == 'portfolio' ? 'active' : ''; ?>">Portfolio</a>
                        </li>
                        <li class="mobile-has-submenu">
                            <a href="#"
                                class="mobile-submenu-toggle <?php echo in_array(($currentPage ?? ''), ['about', 'leadership', 'team']) ? 'active' : ''; ?>">
                                About
                                <svg class="mobile-dropdown-icon" width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </a>
                            <ul class="mobile-submenu">
                                <li><a href="<?php echo url('about.php'); ?>">About OptiSpace</a></li>
                                <li><a href="<?php echo url('leadership.php'); ?>">Leadership</a></li>
                                <li><a href="<?php echo url('team.php'); ?>">Team & Associates</a></li>
                            </ul>
                        </li>
                        <li class="mobile-has-submenu">
                            <a href="#"
                                class="mobile-submenu-toggle <?php echo in_array(($currentPage ?? ''), ['downloads', 'blogs', 'gallery', 'live-projects']) ? 'active' : ''; ?>">
                                Resources
                                <svg class="mobile-dropdown-icon" width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </a>
                            <ul class="mobile-submenu">
                                <li><a href="<?php echo url('live-projects.php'); ?>">Live Projects</a></li>
                                <li><a href="<?php echo url('downloads.php'); ?>">Downloads</a></li>
                                <li><a href="<?php echo url('blogs.php'); ?>">Blogs</a></li>
                                <?php if (getSiteSetting('gallery_enabled', true)): ?>
                                    <li><a href="<?php echo url('gallery.php'); ?>">Gallery</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li><a href="<?php echo url('contact.php'); ?>"
                                class="<?php echo ($currentPage ?? '') == 'contact' ? 'active' : ''; ?>">Contact</a>
                        </li>
                    </ul>
                    <div class="mobile-nav-cta">
                        <a href="<?php echo url('pulse-check.php'); ?>" class="btn btn-primary btn-pulse">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                            </svg>
                            Request Pulse Check
                        </a>
                    </div>
                </nav>

                <!-- Desktop Navigation -->
                <nav class="main-nav">
                    <ul>
                        <li><a href="<?php echo url('index.php'); ?>"
                                class="<?php echo ($currentPage ?? '') == 'home' ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="<?php echo url('philosophy.php'); ?>"
                                class="<?php echo ($currentPage ?? '') == 'philosophy' ? 'active' : ''; ?>">Philosophy</a>
                        </li>
                        <li class="has-submenu">
                            <a href="#"
                                class="nav-dropdown-toggle <?php echo in_array(($currentPage ?? ''), ['greenfield', 'brownfield', 'post-commissioning']) ? 'active' : ''; ?>">
                                Services
                                <svg class="dropdown-icon" width="10" height="6" viewBox="0 0 10 6" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                            <ul class="submenu">
                                <li>
                                    <a href="<?php echo url('services/greenfield.php'); ?>">
                                        <span class="submenu-icon-wrap">
                                            <svg class="submenu-icon" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M12 2L2 7l10 5 10-5-10-5z" />
                                                <path d="M2 17l10 5 10-5" />
                                                <path d="M2 12l10 5 10-5" />
                                            </svg>
                                        </span>
                                        <span class="submenu-text">
                                            <span class="submenu-title">Greenfield Projects</span>
                                            <span class="submenu-desc">New facility design from scratch</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo url('services/brownfield.php'); ?>">
                                        <span class="submenu-icon-wrap">
                                            <svg class="submenu-icon" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M3 21h18" />
                                                <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
                                                <path d="M9 8h1" />
                                                <path d="M9 12h1" />
                                                <path d="M9 16h1" />
                                                <path d="M14 8h1" />
                                                <path d="M14 12h1" />
                                                <path d="M14 16h1" />
                                            </svg>
                                        </span>
                                        <span class="submenu-text">
                                            <span class="submenu-title">Brownfield Projects</span>
                                            <span class="submenu-desc">Optimize existing facilities</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo url('services/post-commissioning.php'); ?>">
                                        <span class="submenu-icon-wrap">
                                            <svg class="submenu-icon" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                        </span>
                                        <span class="submenu-text">
                                            <span class="submenu-title">Post-Commissioning</span>
                                            <span class="submenu-desc">Ongoing support & optimization</span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li><a href="<?php echo url('process.php'); ?>"
                                class="<?php echo ($currentPage ?? '') == 'process' ? 'active' : ''; ?>">Our Process</a>
                        </li>
                        <li><a href="<?php echo url('portfolio.php'); ?>"
                                class="<?php echo ($currentPage ?? '') == 'portfolio' ? 'active' : ''; ?>">Portfolio</a>
                        </li>
                        <li class="has-submenu">
                            <a href="#"
                                class="nav-dropdown-toggle <?php echo in_array(($currentPage ?? ''), ['about', 'leadership', 'team']) ? 'active' : ''; ?>">
                                About
                                <svg class="dropdown-icon" width="10" height="6" viewBox="0 0 10 6" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                            <ul class="submenu">
                                <li>
                                    <a href="<?php echo url('about.php'); ?>">
                                        <span class="submenu-icon-wrap">
                                            <svg class="submenu-icon" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.5">
                                                <circle cx="12" cy="12" r="10" />
                                                <line x1="12" y1="16" x2="12" y2="12" />
                                                <line x1="12" y1="8" x2="12.01" y2="8" />
                                            </svg>
                                        </span>
                                        <span class="submenu-text">
                                            <span class="submenu-title">About OptiSpace</span>
                                            <span class="submenu-desc">Our story and mission</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo url('leadership.php'); ?>">
                                        <span class="submenu-icon-wrap">
                                            <svg class="submenu-icon" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                            </svg>
                                        </span>
                                        <span class="submenu-text">
                                            <span class="submenu-title">Leadership</span>
                                            <span class="submenu-desc">Meet our team</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo url('team.php'); ?>">
                                        <span class="submenu-icon-wrap">
                                            <svg class="submenu-icon" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                            </svg>
                                        </span>
                                        <span class="submenu-text">
                                            <span class="submenu-title">Team & Associates</span>
                                            <span class="submenu-desc">Our network model</span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a href="#"
                                class="nav-dropdown-toggle <?php echo in_array(($currentPage ?? ''), ['downloads', 'blogs', 'gallery', 'live-projects']) ? 'active' : ''; ?>">
                                Resources
                                <svg class="dropdown-icon" width="10" height="6" viewBox="0 0 10 6" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                            <ul class="submenu">
                                <li>
                                    <a href="<?php echo url('live-projects.php'); ?>">
                                        <span class="submenu-icon-wrap">
                                            <svg class="submenu-icon" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                            </svg>
                                        </span>
                                        <span class="submenu-text">
                                            <span class="submenu-title">Live Projects</span>
                                            <span class="submenu-desc">Ongoing transformations</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo url('downloads.php'); ?>">
                                        <span class="submenu-icon-wrap">
                                            <svg class="submenu-icon" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                <polyline points="7 10 12 15 17 10" />
                                                <line x1="12" y1="15" x2="12" y2="3" />
                                            </svg>
                                        </span>
                                        <span class="submenu-text">
                                            <span class="submenu-title">Downloads</span>
                                            <span class="submenu-desc">Brochures & resources</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo url('blogs.php'); ?>">
                                        <span class="submenu-icon-wrap">
                                            <svg class="submenu-icon" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                                            </svg>
                                        </span>
                                        <span class="submenu-text">
                                            <span class="submenu-title">Blogs</span>
                                            <span class="submenu-desc">Insights & articles</span>
                                        </span>
                                    </a>
                                </li>
                                <?php if (getSiteSetting('gallery_enabled', true)): ?>
                                    <li>
                                        <a href="<?php echo url('gallery.php'); ?>">
                                            <span class="submenu-icon-wrap">
                                                <svg class="submenu-icon" width="18" height="18" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                                    <polyline points="21 15 16 10 5 21" />
                                                </svg>
                                            </span>
                                            <span class="submenu-text">
                                                <span class="submenu-title">Gallery</span>
                                                <span class="submenu-desc">Project photos & media</span>
                                            </span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li><a href="<?php echo url('contact.php'); ?>"
                                class="<?php echo ($currentPage ?? '') == 'contact' ? 'active' : ''; ?>">Contact</a>
                        </li>
                    </ul>
                </nav>
                <div class="header-cta">
                    <a href="<?php echo url('pulse-check.php'); ?>" class="btn btn-primary btn-pulse">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" style="margin-right: 6px;">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                        </svg>
                        Request Pulse Check
                    </a>
                </div>
            </div>
        </div>
    </header>
    <main class="site-main">