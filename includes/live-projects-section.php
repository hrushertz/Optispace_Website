<?php
/**
 * Live Projects Section Component
 * 
 * Displays featured live projects on the home page.
 * Requires a database connection ($conn) to be available.
 * 
 * Usage: include 'includes/live-projects-section.php';
 */

// Fetch featured live projects for home page
$liveProjectsQuery = "SELECT * FROM live_projects 
                      WHERE is_active = 1 AND is_featured = 1 
                      ORDER BY sort_order ASC, created_at DESC 
                      LIMIT 3";
$liveProjectsResult = $conn->query($liveProjectsQuery);
$liveProjects = [];
if ($liveProjectsResult) {
    while ($row = $liveProjectsResult->fetch_assoc()) {
        $liveProjects[] = $row;
    }
}

if (!empty($liveProjects)): ?>
    <section class="live-projects-home" style="padding: 5rem 0; background: white;">
        <style>
            .live-projects-home .lp-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
            }

            .live-projects-home .lp-header {
                text-align: center;
                margin-bottom: 3.5rem;
            }

            .live-projects-home .lp-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background: rgba(233, 148, 49, 0.1);
                border-radius: 100px;
                margin-bottom: 1rem;
            }

            .live-projects-home .lp-badge svg {
                width: 16px;
                height: 16px;
                color: var(--home-orange);
            }

            .live-projects-home .lp-badge span {
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: var(--home-orange);
            }

            .live-projects-home .lp-title {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--home-dark);
                margin-bottom: 0.75rem;
            }

            .live-projects-home .lp-subtitle {
                font-size: 1.1rem;
                color: var(--home-gray);
                max-width: 650px;
                margin: 0 auto;
                line-height: 1.6;
            }

            .live-projects-home .lp-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 2rem;
                margin-bottom: 3rem;
            }

            @media (max-width: 992px) {
                .live-projects-home .lp-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 640px) {
                .live-projects-home .lp-grid {
                    grid-template-columns: 1fr;
                }

                .live-projects-home .lp-title {
                    font-size: 2rem;
                }
            }

            .live-projects-home .lp-card {
                background: #f8fafc;
                border-radius: 16px;
                overflow: hidden;
                border: 1px solid var(--home-border);
                transition: all 0.3s ease;
                max-width: 320px;
                margin: 0 auto;
                width: 100%;
            }

            .live-projects-home .lp-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                border-color: var(--home-orange);
            }

            .live-projects-home .lp-card-image {
                position: relative;
                width: 100%;
                height: auto;
                aspect-ratio: 1/1;
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                overflow: hidden;
            }

            .live-projects-home .lp-card-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .live-projects-home .lp-card:hover .lp-card-image img {
                transform: scale(1.05);
            }

            .live-projects-home .lp-type-badge {
                position: absolute;
                top: 1rem;
                left: 1rem;
                background: rgba(255, 255, 255, 0.95);
                padding: 0.35rem 0.7rem;
                border-radius: 6px;
                font-size: 0.7rem;
                font-weight: 600;
                color: var(--home-dark);
            }

            .live-projects-home .lp-type-badge.greenfield {
                background: rgba(16, 185, 129, 0.9);
                color: white;
            }

            .live-projects-home .lp-type-badge.brownfield {
                background: rgba(59, 130, 246, 0.9);
                color: white;
            }

            .live-projects-home .lp-progress-bar {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: rgba(255, 255, 255, 0.2);
            }

            .live-projects-home .lp-progress-fill {
                height: 100%;
                background: linear-gradient(90deg, var(--home-orange) 0%, #f5a854 100%);
            }

            .live-projects-home .lp-card-content {
                padding: 1.5rem;
            }

            .live-projects-home .lp-card-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--home-dark);
                margin-bottom: 0.5rem;
                line-height: 1.3;
            }

            .live-projects-home .lp-card-client {
                font-size: 0.85rem;
                color: var(--home-gray);
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                gap: 0.35rem;
            }

            .live-projects-home .lp-card-client svg {
                width: 14px;
                height: 14px;
                color: var(--home-orange);
            }

            .live-projects-home .lp-card-meta {
                display: flex;
                justify-content: space-between;
                padding-top: 1rem;
                border-top: 1px solid var(--home-border);
            }

            .live-projects-home .lp-meta-item {
                display: flex;
                flex-direction: column;
                gap: 0.2rem;
            }

            .live-projects-home .lp-meta-label {
                font-size: 0.7rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #94a3b8;
                font-weight: 600;
            }

            .live-projects-home .lp-meta-value {
                font-size: 0.85rem;
                color: var(--home-dark);
                font-weight: 500;
            }

            .live-projects-home .lp-card-progress {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid var(--home-border);
            }

            .live-projects-home .lp-progress-circle {
                width: 40px;
                height: 40px;
                position: relative;
                flex-shrink: 0;
            }

            .live-projects-home .lp-progress-circle svg {
                width: 100%;
                height: 100%;
                transform: rotate(-90deg);
            }

            .live-projects-home .lp-progress-circle .bg {
                fill: none;
                stroke: #e5e7eb;
                stroke-width: 4;
            }

            .live-projects-home .lp-progress-circle .progress {
                fill: none;
                stroke: var(--home-orange);
                stroke-width: 4;
                stroke-linecap: round;
            }

            .live-projects-home .lp-progress-text {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 0.65rem;
                font-weight: 700;
                color: var(--home-dark);
            }

            .live-projects-home .lp-progress-info {
                flex: 1;
            }

            .live-projects-home .lp-phase {
                font-size: 0.85rem;
                font-weight: 600;
                color: var(--home-dark);
            }

            .live-projects-home .lp-phase-label {
                font-size: 0.75rem;
                color: var(--home-gray);
            }

            .live-projects-home .lp-view-all {
                text-align: center;
            }

            .live-projects-home .lp-view-all a {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                background: var(--home-orange);
                color: white;
                padding: 1rem 2rem;
                border-radius: 10px;
                font-size: 1rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(233, 148, 49, 0.3);
            }

            .live-projects-home .lp-view-all a:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(233, 148, 49, 0.4);
                color: white;
            }

            .live-projects-home .lp-view-all a svg {
                width: 18px;
                height: 18px;
                transition: transform 0.3s ease;
            }

            .live-projects-home .lp-view-all a:hover svg {
                transform: translateX(4px);
            }
        </style>

        <div class="lp-container">
            <div class="lp-header">
                <div class="lp-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                    <span>Currently In Progress</span>
                </div>
                <h2 class="lp-title">Live Projects</h2>
                <p class="lp-subtitle">Watch our ongoing factory transformations in action. Each project showcases our
                    commitment to delivering lean manufacturing excellence.</p>
            </div>

            <div class="lp-grid">
                <?php foreach ($liveProjects as $liveProject): ?>
                    <div class="lp-card">
                        <div class="lp-card-image">
                            <?php if ($liveProject['image_path']): ?>
                                <img src="<?php echo htmlspecialchars($liveProject['image_path']); ?>"
                                    alt="<?php echo htmlspecialchars($liveProject['title']); ?>">
                            <?php else: ?>
                                <div
                                    style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.3)"
                                        stroke-width="1.5">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                        <polyline points="9 22 9 12 15 12 15 22" />
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <?php if ($liveProject['project_type']): ?>
                                <span
                                    class="lp-type-badge <?php echo strtolower(str_replace(' ', '-', $liveProject['project_type'])); ?>">
                                    <?php echo htmlspecialchars($liveProject['project_type']); ?>
                                </span>
                            <?php endif; ?>

                            <div class="lp-progress-bar">
                                <div class="lp-progress-fill"
                                    style="width: <?php echo (int) $liveProject['progress_percentage']; ?>%;"></div>
                            </div>
                        </div>

                        <div class="lp-card-content">
                            <h3 class="lp-card-title"><?php echo htmlspecialchars($liveProject['title']); ?></h3>

                            <?php if ($liveProject['client_name']): ?>
                                <div class="lp-card-client">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 21h18M5 21V7l8-4v18M13 21V11l6-4v14" />
                                    </svg>
                                    <?php echo htmlspecialchars($liveProject['client_name']); ?>
                                </div>
                            <?php endif; ?>

                            <div class="lp-card-meta">
                                <?php if ($liveProject['industry']): ?>
                                    <div class="lp-meta-item">
                                        <span class="lp-meta-label">Industry</span>
                                        <span class="lp-meta-value"><?php echo htmlspecialchars($liveProject['industry']); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($liveProject['location']): ?>
                                    <div class="lp-meta-item">
                                        <span class="lp-meta-label">Location</span>
                                        <span class="lp-meta-value"><?php echo htmlspecialchars($liveProject['location']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="lp-card-progress">
                                <div class="lp-progress-circle">
                                    <?php
                                    $progress = (int) $liveProject['progress_percentage'];
                                    $circumference = 2 * 3.14159 * 16;
                                    $offset = $circumference - ($progress / 100) * $circumference;
                                    ?>
                                    <svg viewBox="0 0 40 40">
                                        <circle class="bg" cx="20" cy="20" r="16" />
                                        <circle class="progress" cx="20" cy="20" r="16"
                                            stroke-dasharray="<?php echo $circumference; ?>"
                                            stroke-dashoffset="<?php echo $offset; ?>" />
                                    </svg>
                                    <span class="lp-progress-text"><?php echo $progress; ?>%</span>
                                </div>
                                <div class="lp-progress-info">
                                    <div class="lp-phase">
                                        <?php echo htmlspecialchars($liveProject['current_phase'] ?? 'In Progress'); ?>
                                    </div>
                                    <div class="lp-phase-label">Current Phase</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="lp-view-all">
                <a href="<?php echo url('live-projects.php'); ?>">
                    View All Live Projects
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>