<?php
$pageTitle = 'Sitemap - Solutions OptiSpace';
$pageDescription = 'Sitemap for Solutions OptiSpace - Navigate through our website pages including services, portfolio, resources, and company information.';
$pageKeywords = 'OptiSpace sitemap, website map, navigation, site structure, page list';
$currentPage = 'sitemap';
include 'includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                Site Navigation
            </div>
            <h1>Sitemap</h1>
            <p class="lead">Explore the complete structure of our website.</p>
        </div>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="content-wrapper">
            <div class="sitemap-content">
                <div class="row">
                    <!-- Main Pages -->
                    <div class="col-md-4 mb-4">
                        <h3>Main Pages</h3>
                        <ul class="sitemap-list">
                            <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
                            <li><a href="<?php echo url('about.php'); ?>">About Us</a></li>
                            <li><a href="<?php echo url('philosophy.php'); ?>">The LFB Philosophy</a></li>
                            <li><a href="<?php echo url('leadership.php'); ?>">Leadership</a></li>
                            <li><a href="<?php echo url('contact.php'); ?>">Contact Us</a></li>
                        </ul>
                    </div>

                    <!-- Services -->
                    <div class="col-md-4 mb-4">
                        <h3>Services</h3>
                        <ul class="sitemap-list">
                            <li><a href="<?php echo url('services/greenfield.php'); ?>">Greenfield Projects</a></li>
                            <li><a href="<?php echo url('services/brownfield.php'); ?>">Brownfield Optimization</a></li>
                            <li><a href="<?php echo url('services/post-commissioning.php'); ?>">Post-Commissioning
                                    Support</a></li>
                            <li><a href="<?php echo url('process.php'); ?>">Our Process</a></li>
                        </ul>
                    </div>

                    <!-- Work & Portfolio -->
                    <div class="col-md-4 mb-4">
                        <h3>Work & Portfolio</h3>
                        <ul class="sitemap-list">
                            <li><a href="<?php echo url('portfolio.php'); ?>">Portfolio</a></li>
                            <li><a href="<?php echo url('live-projects.php'); ?>">Live Projects</a></li>
                            <li><a href="<?php echo url('gallery.php'); ?>">Project Gallery</a></li>
                        </ul>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- Resources -->
                    <div class="col-md-4 mb-4">
                        <h3>Resources</h3>
                        <ul class="sitemap-list">
                            <li><a href="<?php echo url('downloads.php'); ?>">Downloads</a></li>
                            <li><a href="<?php echo url('blogs.php'); ?>">Blog & Insights</a></li>
                        </ul>
                    </div>

                    <!-- Legal & Support -->
                    <div class="col-md-4 mb-4">
                        <h3>Legal & Support</h3>
                        <ul class="sitemap-list">
                            <li><a href="<?php echo url('privacy-policy.php'); ?>">Privacy Policy</a></li>
                            <li><a href="<?php echo url('terms-of-use.php'); ?>">Terms of Use</a></li>
                            <li><a href="<?php echo url('sitemap.php'); ?>">Sitemap</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .sitemap-content {
        padding: 2rem 0;
    }

    .sitemap-list {
        list-style: none;
        padding-left: 0;
        margin-top: 1rem;
    }

    .sitemap-list li {
        margin-bottom: 0.75rem;
        position: relative;
        padding-left: 1.5rem;
    }

    .sitemap-list li::before {
        content: "→";
        position: absolute;
        left: 0;
        color: var(--primary-color, #0056b3);
        opacity: 0.7;
    }

    .sitemap-list a {
        color: var(--text-color, #333);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .sitemap-list a:hover {
        color: var(--primary-color, #0056b3);
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .col-md-4 {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    @media (min-width: 768px) {
        .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: var(--heading-color, #222);
        border-bottom: 2px solid var(--border-color, #eee);
        padding-bottom: 0.5rem;
        display: inline-block;
    }
</style>

<?php include 'includes/footer.php'; ?>