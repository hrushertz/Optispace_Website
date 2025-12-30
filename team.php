<style>
    body.team-no-padding {
        padding-top: 0 !important;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('team-no-padding');
    });
</script>
<?php
$currentPage = 'about';
$pageTitle = 'Team & Associates | Solutions OptiSpace';
$pageDescription = 'OptiSpace operates as a unified, multidisciplinary network bringing best-in-class expertise to every project.';
include 'includes/header.php';
?>

<section class="page-hero" style="position: relative; min-height: 500px; display: flex; align-items: center;">
    <img src="assets/img/banner_1920x500.jpg" alt="Banner" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;">
    <div class="container" style="position: relative; z-index: 2; text-align: center; color: #fff; padding: 4.5rem 2rem 2rem 2rem; max-width: 700px; margin: 0 auto;">
        <h1>Team & Associates</h1>
        <p>A unified, multidisciplinary network for comprehensive factory design</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Our Multidisciplinary Network</h2>
            <p>OptiSpace operates as a unified network - not a traditional hierarchy</p>
        </div>

        <div class="grid grid-3">
            <div class="card">
                <h3>Core Team</h3>
                <ul class="feature-list">
                    <li><strong>Lean Manufacturing Consultants</strong> - Process optimization and layout design</li>
                    <li><strong>Registered Architects</strong> - Building design and compliance</li>
                    <li><strong>Materials & Equipment Consultants</strong> - Specification and procurement</li>
                </ul>
            </div>
            <div class="card">
                <h3>Engineering Associates</h3>
                <ul class="feature-list">
                    <li><strong>Structural Engineers</strong> - Foundation and structural design</li>
                    <li><strong>MEP Consultants</strong> - Mechanical, electrical, plumbing systems</li>
                    <li><strong>Electrical Consultants</strong> - Power distribution and lighting</li>
                </ul>
            </div>
            <div class="card">
                <h3>Specialized Partners</h3>
                <ul class="feature-list">
                    <li><strong>Interior Decorators</strong> - Office and amenity spaces</li>
                    <li><strong>Fabricators</strong> - Custom material handling equipment</li>
                    <li><strong>Fire Safety Vendors</strong> - Safety systems and compliance</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="section section-light">
    <div class="container">
        <div class="section-header">
            <h2>Why This Network Model Works</h2>
            <p>The value of our integrated approach</p>
        </div>

        <div class="grid grid-2">
            <div class="card">
                <h3>Single Partner, Single Responsibility</h3>
                <p><strong>Our integrated approach means you have a single partner responsible for the entire outcome.</strong></p>
                <p style="margin-top: 1rem;">You don't have to coordinate between architects, structural engineers, MEP consultants, lean consultants, and fire safety vendors. We handle all discipline coordination to avoid gaps and finger-pointing.</p>
            </div>
            <div class="card">
                <h3>Best-in-Class Expertise</h3>
                <p>Factory design is complex and multidisciplinary. By maintaining a network of specialized associates, we ensure you get best-in-class expertise in every aspect of your project.</p>
                <p style="margin-top: 1rem;">From lean process design to structural engineering to fire safety - all coordinated seamlessly by OptiSpace.</p>
            </div>
            <div class="card">
                <h3>Flexibility & Scalability</h3>
                <p>Our network model allows us to scale resources up or down based on project needs. Small projects get lean teams; large projects get comprehensive support - all without overhead waste.</p>
            </div>
            <div class="card">
                <h3>Continuous Knowledge Exchange</h3>
                <p>Our associates work together regularly, building shared understanding of LFB principles. This creates better collaboration and faster problem-solving than traditional siloed consulting.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>How We Work Together</h2>
            <p>Collaboration that delivers results</p>
        </div>

        <div class="grid grid-2">
            <div class="card">
                <h3>Lean Leads the Process</h3>
                <p>Every OptiSpace project starts with lean manufacturing principles. Process flow, takt time, value stream mapping - these define the layout.</p>
                <p style="margin-top: 1rem;">Only after the lean layout is optimized do we bring in architecture and engineering disciplines to design the building around the process.</p>
            </div>
            <div class="card">
                <h3>Coordinated, Not Fragmented</h3>
                <p>All disciplines work under OptiSpace's coordination. We facilitate workshops, review sessions, and design iterations to ensure everyone is aligned on the LFB vision.</p>
                <p style="margin-top: 1rem;">No one works in isolation. No surprises emerge late in the project because disciplines didn't talk to each other.</p>
            </div>
        </div>

        <div class="card" style="margin-top: 2rem; text-align: center; background-color: var(--bg-light);">
            <h3>Result: Factories That Work</h3>
            <p style="font-size: 1.125rem; margin-top: 1rem;">When architecture, engineering, and lean consulting work together under one vision, you get factories that are not just compliant and functional - but truly optimized for operational excellence.</p>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2>Ready to Experience Our Integrated Approach?</h2>
        <p>See how our multidisciplinary network can transform your factory project</p>
        <a href="/contact.php#pulse-check" class="btn btn-large" style="background-color: white; color: var(--primary-color);">Request a Pulse Check</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
