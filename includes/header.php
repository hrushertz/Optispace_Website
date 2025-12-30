<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $pageDescription ?? 'Solutions OptiSpace - Lean Factory Building (LFB) Architecture. We design factories from the inside out.'; ?>">
    <title><?php echo $pageTitle ?? 'Solutions OptiSpace'; ?></title>
    <link rel="stylesheet" href="/project/assets/css/style.css">
</head>
<body class="<?php echo isset($currentPage) && $currentPage === 'home' ? 'home-page' : ''; ?>">
    <header class="site-header transparent">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/project/index.php">
                        <img src="/project/assets/img/optispace.png" alt="OptiSpace Logo" class="logo-img">
                    </a>
                </div>
                <button class="mobile-menu-toggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <nav class="main-nav">
                    <ul>
                        <li><a href="/project/index.php" class="<?php echo ($currentPage ?? '') == 'home' ? 'active' : ''; ?>">Home</a></li>
                        <li class="has-submenu">
                            <a href="/project/philosophy.php" class="<?php echo ($currentPage ?? '') == 'philosophy' ? 'active' : ''; ?>">Philosophy</a>
                        </li>
                        <li class="has-submenu">
                            <a href="#" class="<?php echo in_array(($currentPage ?? ''), ['greenfield', 'brownfield', 'post-commissioning']) ? 'active' : ''; ?>">Services</a>
                            <ul class="submenu">
                                <li><a href="/project/services/greenfield.php">Greenfield Projects</a></li>
                                <li><a href="/project/services/brownfield.php">Brownfield Projects</a></li>
                                <li><a href="/project/services/post-commissioning.php">Post-Commissioning</a></li>
                            </ul>
                        </li>
                        <li><a href="/project/process.php" class="<?php echo ($currentPage ?? '') == 'process' ? 'active' : ''; ?>">Our Process</a></li>
                        <li><a href="/project/portfolio.php" class="<?php echo ($currentPage ?? '') == 'portfolio' ? 'active' : ''; ?>">Portfolio</a></li>
                        <li class="has-submenu">
                            <a href="/project/about.php" class="<?php echo ($currentPage ?? '') == 'about' ? 'active' : ''; ?>">About</a>
                            <ul class="submenu">
                                <li><a href="/project/about.php">About OptiSpace</a></li>
                                <li><a href="/project/leadership.php" class="<?php echo ($currentPage ?? '') == 'leadership' ? 'active' : ''; ?>">Leadership</a></li>
                            </ul>
                        </li>
                        <li><a href="/project/contact.php" class="<?php echo ($currentPage ?? '') == 'contact' ? 'active' : ''; ?>">Contact</a></li>
                    </ul>
                </nav>
                <div class="header-cta">
                    <a href="/project/contact.php#pulse-check" class="btn btn-primary">Request Pulse Check</a>
                </div>
            </div>
        </div>
    </header>
    <main class="site-main">
