<?php
$currentPage = 'about';
$pageTitle = 'Team & Associates | Solutions OptiSpace';
$pageDescription = 'OptiSpace operates as a unified, multidisciplinary network bringing best-in-class expertise to every project.';
$pageKeywords = 'OptiSpace team, lean manufacturing experts, factory design team, industrial architects, lean consultants, engineering associates, multidisciplinary team, manufacturing consultants, LFB experts, OptiSpace associates, factory design specialists, expert network';
include 'includes/header.php';

// Get database connection and fetch team members
require_once 'database/db_config.php';
$conn = getDBConnection();
$teamQuery = "SELECT * FROM team_members WHERE is_active = 1 ORDER BY sort_order ASC, id ASC";
$teamResult = $conn->query($teamQuery);
$teamMembers = [];
if ($teamResult && $teamResult->num_rows > 0) {
    while ($row = $teamResult->fetch_assoc()) {
        $teamMembers[] = $row;
    }
}
?>

<style>
/* ========================================
   TEAM PAGE - MODERN CLEAN DESIGN
   ======================================== */

:root {
    --team-orange: #E99431;
    --team-orange-light: rgba(233, 148, 49, 0.08);
    --team-blue: #3B82F6;
    --team-blue-light: rgba(59, 130, 246, 0.08);
    --team-green: #10B981;
    --team-green-light: rgba(16, 185, 129, 0.08);
    --team-dark: #1E293B;
    --team-text: #475569;
    --team-border: #E2E8F0;
}

/* Hero Section */
.team-hero {
    background: linear-gradient(165deg, #1E293B 0%, #334155 100%);
    padding: 8rem 0 6rem;
    position: relative;
    overflow: hidden;
}

.team-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(ellipse at 70% 50%, rgba(233, 148, 49, 0.12) 0%, transparent 60%);
}

.team-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
    position: relative;
    z-index: 2;
}

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(233, 148, 49, 0.15);
    color: #E99431;
    padding: 0.5rem 1rem;
    border-radius: 100px;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 1.5rem;
}

.team-hero h1 {
    font-size: 3.25rem;
    font-weight: 700;
    color: white;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

.team-hero h1 span {
    color: #E99431;
}

.team-hero-text {
    font-size: 1.2rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.75);
    margin-bottom: 0;
    max-width: 650px;
    margin-left: auto;
    margin-right: auto;
}

/* Breadcrumb */
.team-breadcrumb {
    background: #F8FAFC;
    padding: 1rem 0;
    border-bottom: 1px solid var(--team-border);
}

.team-breadcrumb ul {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    list-style: none;
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.team-breadcrumb a {
    color: var(--team-text);
    text-decoration: none;
}

.team-breadcrumb a:hover {
    color: var(--team-orange);
}

.team-breadcrumb li:last-child {
    color: var(--team-dark);
    font-weight: 500;
}

.team-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--team-border);
}

/* Section Styles */
.team-section {
    padding: 6rem 0;
}

.team-section.alt-bg {
    background: #FAFBFC;
}

.team-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.section-header-team {
    text-align: center;
    margin-bottom: 4rem;
}

.section-header-team h2 {
    font-size: 2.5rem;
    color: var(--team-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.section-header-team .subtitle {
    font-size: 1.1rem;
    color: var(--team-text);
    max-width: 700px;
    margin: 0 auto;
}

/* Network Grid */
.network-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
    margin-bottom: 4rem;
}

.network-card {
    background: white;
    border-radius: 12px;
    padding: 2.5rem;
    border: 1px solid var(--team-border);
    transition: all 0.3s ease;
}

.network-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    border-color: var(--team-orange);
}

.network-card h3 {
    font-size: 1.5rem;
    color: var(--team-dark);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.network-card h3::before {
    content: '';
    width: 4px;
    height: 24px;
    background: var(--team-orange);
    border-radius: 2px;
}

.network-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.network-card ul li {
    padding: 0.85rem 0;
    border-bottom: 1px solid var(--team-border);
    color: var(--team-text);
    line-height: 1.6;
}

.network-card ul li:last-child {
    border-bottom: none;
}

.network-card ul li strong {
    color: var(--team-dark);
    font-weight: 600;
}

/* Benefits Grid */
.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.benefit-card {
    background: white;
    border-radius: 12px;
    padding: 2.5rem;
    border: 1px solid var(--team-border);
    transition: all 0.3s ease;
}

.benefit-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    border-color: var(--team-blue);
}

.benefit-card h3 {
    font-size: 1.35rem;
    color: var(--team-dark);
    margin-bottom: 1rem;
    font-weight: 600;
}

.benefit-card p {
    color: var(--team-text);
    line-height: 1.7;
    margin-bottom: 0.75rem;
}

.benefit-card p:last-child {
    margin-bottom: 0;
}

/* Collaboration Section */
.collaboration-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.collaboration-card {
    background: white;
    border-radius: 12px;
    padding: 2.5rem;
    border: 1px solid var(--team-border);
    transition: all 0.3s ease;
}

.collaboration-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    border-color: var(--team-green);
}

.collaboration-card h3 {
    font-size: 1.5rem;
    color: var(--team-dark);
    margin-bottom: 1rem;
    font-weight: 600;
}

.collaboration-card p {
    color: var(--team-text);
    line-height: 1.7;
    margin-bottom: 1rem;
}

.collaboration-card p:last-child {
    margin-bottom: 0;
}

/* Result Card */
.result-card {
    background: linear-gradient(135deg, var(--team-orange-light) 0%, var(--team-blue-light) 100%);
    border-radius: 12px;
    padding: 3rem;
    text-align: center;
    border: 1px solid var(--team-border);
}

.result-card h3 {
    font-size: 2rem;
    color: var(--team-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.result-card p {
    font-size: 1.15rem;
    color: var(--team-text);
    line-height: 1.7;
    max-width: 800px;
    margin: 0 auto;
}

/* Team Members Section */
.team-members-grid {
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
    margin-top: 3rem;
}

.member-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--team-border);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: row;
}

.member-card:hover {
    transform: translateX(8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    border-color: var(--team-orange);
}

.member-photo {
    width: 320px;
    min-width: 320px;
    height: auto;
    overflow: hidden;
    background: linear-gradient(135deg, var(--team-orange-light) 0%, var(--team-blue-light) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.member-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.member-photo-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

.member-photo-placeholder svg {
    width: 60px;
    height: 60px;
    color: var(--team-orange);
}

.member-info {
    padding: 2rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.member-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--team-dark);
    margin-bottom: 0.5rem;
}

.member-role {
    font-size: 1rem;
    font-weight: 600;
    color: var(--team-orange);
    margin-bottom: 0.25rem;
}

.member-title {
    font-size: 0.9rem;
    color: var(--team-text);
    margin-bottom: 1rem;
    font-style: italic;
}

.member-description {
    font-size: 0.95rem;
    color: var(--team-text);
    line-height: 1.7;
    margin-bottom: 1rem;
    flex: 1;
}

.member-specialties {
    margin-top: 1rem;
}

.member-specialties-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--team-dark);
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.member-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.member-tag {
    background: var(--team-orange-light);
    color: var(--team-orange);
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.member-contact {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--team-border);
    display: flex;
    gap: 1rem;
    align-items: center;
}

.member-contact a {
    color: var(--team-text);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.member-contact a:hover {
    color: var(--team-orange);
}

.member-contact svg {
    width: 16px;
    height: 16px;
}

/* CTA Section */
.team-cta-section {
    background: linear-gradient(135deg, var(--team-orange) 0%, #D67F1F 100%);
    padding: 5rem 0;
    text-align: center;
    color: white;
}

.team-cta-section h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.team-cta-section p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.95;
}

.team-cta-section .btn {
    background: white;
    color: var(--team-orange);
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.team-cta-section .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    .team-hero {
        padding: 6rem 0 4rem;
    }
    
    .team-hero h1 {
        font-size: 2.25rem;
    }
    
    .team-hero-text {
        font-size: 1.05rem;
    }
    
    .team-section {
        padding: 4rem 0;
    }
    
    .section-header-team h2 {
        font-size: 2rem;
    }
    
    .network-grid,
    .benefits-grid,
    .collaboration-grid {
        grid-template-columns: 1fr;
    }
    
    .network-card,
    .benefit-card,
    .collaboration-card {
        padding: 2rem;
    }
    
    .member-card {
        flex-direction: column;
    }
    
    .member-photo {
        width: 100%;
        min-width: 100%;
        height: 280px;
    }
    
    .member-info {
        padding: 1.5rem;
    }
    
    .result-card {
        padding: 2rem;
    }
    
    .result-card h3 {
        font-size: 1.5rem;
    }
    
    .result-card p {
        font-size: 1rem;
    }
    
    .team-cta-section h2 {
        font-size: 2rem;
    }
    
    .team-cta-section p {
        font-size: 1.05rem;
    }
}
</style>

<!-- Hero Section -->
<section class="team-hero">
    <div class="team-hero-inner">
        <div class="hero-eyebrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            Our Network
        </div>
        <h1>Team & <span>Associates</span></h1>
        <p class="team-hero-text">A unified, multidisciplinary network bringing best-in-class expertise to every factory project</p>
    </div>
</section>

<!-- Breadcrumb -->
<div class="team-breadcrumb">
    <ul>
        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
        <li><a href="<?php echo url('about.php'); ?>">About</a></li>
        <li>Team & Associates</li>
    </ul>
</div>

<!-- Core Team Members Section -->
<section class="team-section">
    <div class="team-container">
        <div class="section-header-team">
            <h2>Meet Our Core Team</h2>
            <p class="subtitle">The experts who lead every OptiSpace project</p>
        </div>

        <?php if (empty($teamMembers)): ?>
        <div style="text-align: center; padding: 4rem 2rem; color: var(--team-text);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 64px; height: 64px; margin: 0 auto 1rem; color: #CBD5E1;">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--team-dark);">Team Members Coming Soon</h3>
            <p>We're currently updating our team profiles. Please check back soon!</p>
        </div>
        <?php else: ?>
        <div class="team-members-grid">
            <?php foreach ($teamMembers as $member): 
                $specialties = $member['specialties'] ? explode(',', $member['specialties']) : [];
            ?>
            <div class="member-card">
                <div class="member-photo">
                    <?php if ($member['photo_path']): ?>
                    <img src="<?php echo htmlspecialchars(url($member['photo_path'])); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                    <?php else: ?>
                    <div class="member-photo-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="member-info">
                    <h3 class="member-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                    <div class="member-role"><?php echo htmlspecialchars($member['role']); ?></div>
                    <?php if ($member['title']): ?>
                    <div class="member-title"><?php echo htmlspecialchars($member['title']); ?></div>
                    <?php endif; ?>
                    <?php if ($member['description']): ?>
                    <p class="member-description">
                        <?php echo nl2br(htmlspecialchars($member['description'])); ?>
                    </p>
                    <?php endif; ?>
                    <?php if (!empty($specialties)): ?>
                    <div class="member-specialties">
                        <div class="member-specialties-title">Specialties</div>
                        <div class="member-tags">
                            <?php foreach ($specialties as $specialty): ?>
                            <span class="member-tag"><?php echo htmlspecialchars(trim($specialty)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($member['email'] || $member['linkedin_url']): ?>
                    <div class="member-contact">
                        <?php if ($member['email']): ?>
                        <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            Email
                        </a>
                        <?php endif; ?>
                        <?php if ($member['linkedin_url']): ?>
                        <a href="<?php echo htmlspecialchars($member['linkedin_url']); ?>" target="_blank" rel="noopener noreferrer">
                            <svg viewBox="0 0 24 24" fill="currentColor" style="width: 16px; height: 16px;">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                            LinkedIn
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- How We Work Together Section -->
<section class="team-section">
    <div class="team-container">
        <div class="section-header-team">
            <h2>How We Work Together</h2>
            <p class="subtitle">Collaboration that delivers results</p>
        </div>

        <div class="collaboration-grid">
            <div class="collaboration-card">
                <h3>Lean Leads the Process</h3>
                <p>Every OptiSpace project starts with lean manufacturing principles. Process flow, takt time, value stream mapping - these define the layout.</p>
                <p>Only after the lean layout is optimized do we bring in architecture and engineering disciplines to design the building around the process.</p>
            </div>
            <div class="collaboration-card">
                <h3>Coordinated, Not Fragmented</h3>
                <p>All disciplines work under OptiSpace's coordination. We facilitate workshops, review sessions, and design iterations to ensure everyone is aligned on the LFB vision.</p>
                <p>No one works in isolation. No surprises emerge late in the project because disciplines didn't talk to each other.</p>
            </div>
        </div>

        <div class="result-card">
            <h3>Result: Factories That Work</h3>
            <p>When architecture, engineering, and lean consulting work together under one vision, you get factories that are not just compliant and functional - but truly optimized for operational excellence.</p>
        </div>
    </div>
</section>

<!-- CTA Section -->

<?php include 'includes/footer.php'; ?>
