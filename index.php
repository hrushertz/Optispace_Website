<?php
$currentPage = 'home';
$pageTitle = 'Lean Factory Building (LFB) Architecture for Future-Ready Plants | Solutions OptiSpace';
$pageDescription = 'Cut internal travel, energy costs, and WIP before construction begins. Design the process first, then the building—with OptiSpace LFB Architecture.';
include 'includes/header.php';
?>


<!-- Hero Section with Banner Image -->
<section class="hero hero-enhanced" style="background: url('./assets/img/opti_banner_1920x600.png') center center/cover no-repeat, linear-gradient(135deg, #4A5568 0%, #2D3748 100%); min-height: 500px; display: flex; align-items: center;">
    <div class="container">
        <div class="hero-content" style="max-width: 900px; margin: 0 auto; text-align: center;">
            <h1 style="font-size: 2.75rem; font-weight: 600; margin-bottom: 1.5rem; line-height: 1.3;">Lean Factory Building Architecture for Future‑Ready Plants</h1>
            <p class="hero-subheadline" style="font-size: 1.25rem; margin-bottom: 3rem; opacity: 0.95; line-height: 1.6;">Design the process first, then the building—cut internal travel, energy costs, and WIP before construction begins.</p>
            
            <div class="hero-cta" style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="./contact.php#pulse-check" class="btn btn-primary btn-large" style="padding: 0.875rem 2rem; font-size: 1rem;">Request Pulse Check</a>
                <a href="#how-lfb-works" class="btn btn-outline btn-large" style="padding: 0.875rem 2rem; font-size: 1rem;">Learn More</a>
            </div>
        </div>
    </div>
</section>

<!-- Key Benefits - Minimal -->
<section class="section" style="padding: 90px 0 45px 0; background: #f8f9fa;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2.5rem; max-width: 1100px; margin: 0 auto;">
            <div style="text-align: center;">
                <div style="font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 0.5rem;">Efficiency</div>
                <p style="font-size: 1rem; color: #334155; line-height: 1.6;">Reduce internal movement and material handling costs</p>
            </div>
            <div style="text-align: center; border-left: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">
                <div style="font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 0.5rem;">Sustainability</div>
                <p style="font-size: 1rem; color: #334155; line-height: 1.6;">Lower lifetime energy through passive design alignment</p>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 0.5rem;">Flexibility</div>
                <p style="font-size: 1rem; color: #334155; line-height: 1.6;">Build layouts ready for automation and rapid growth</p>
            </div>
        </div>
    </div>
</section>

<!-- Problem & Stakes Section -->
<section class="section problem-section" style="padding: 45px 0; background: #f8fafb;">
    <style>
        .problem-card {
            padding: 2rem 1.75rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .problem-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            border-color: #E99431;
        }
        .problem-card h3 {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0369a1;
            margin-bottom: 0.75rem;
            transition: color 0.3s ease;
        }
        .problem-card:hover h3 {
            color: #E99431;
        }
        .problem-card p {
            font-size: 0.95rem;
            line-height: 1.75;
            color: #475569;
            margin: 0;
            transition: color 0.3s ease;
        }
        .problem-card:hover p {
            color: #334155;
        }
    </style>
    <div class="container">
        <div class="section-header" style="max-width: 850px; margin: 0 auto 5rem; text-align: center;">
            <h2 style="font-size: 2.25rem; font-weight: 600; margin-bottom: 1.75rem; color: #1e293b; line-height: 1.3;">Why conventional factory design fails operations</h2>
            <p class="problem-intro" style="font-size: 1.15rem; line-height: 1.8; color: #475569; max-width: 750px; margin: 0 auto;">Most manufacturers struggle with hidden inefficiencies that SOPs and management cannot fix. These problems are structural—when a building is treated as a mere shell, it creates permanent bottlenecks that drain profitability.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; max-width: 1200px; margin: 0 auto;">
            <div class="problem-card">
                <h3>Aesthetic Priority</h3>
                <p>Buildings planned as real‑estate boxes, prioritizing facades over flow-optimized factories</p>
            </div>
            <div class="problem-card">
                <h3>Material Movement</h3>
                <p>High forklift and operator travel leading to hidden, recurring OPEX leakages</p>
            </div>
            <div class="problem-card">
                <h3>Rigid Infrastructure</h3>
                <p>Painful expansions and automation retrofits blocked by rigid structural grids</p>
            </div>
            <div class="problem-card">
                <h3>Poor Visibility</h3>
                <p>Layouts with dead corners and poor sightlines that don't support Lean, 5S, and flow</p>
            </div>
            <div class="problem-card">
                <h3>MEP Conflicts</h3>
                <p>Utility and HVAC systems designed without process requirements creating operational constraints</p>
            </div>
            <div class="problem-card">
                <h3>Growth Limitations</h3>
                <p>No flexibility built in for future product mix changes or volume scaling needs</p>
            </div>
        </div>
    </div>
</section>

<!-- What is LFB Section -->
<section class="section section-light" id="how-lfb-works" style="padding: 45px 0; background: white;">
    <div class="container">
        <div style="max-width: 1200px; margin: 0 auto;">
            <!-- Section Label -->
            <div style="margin-bottom: 2rem;">
                <span style="display: inline-block; background: #e8f0f5; color: #1e3a5f; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.5rem 1rem; border-radius: 4px;">OUR APPROACH</span>
            </div>

            <!-- Two Column Layout -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: start;">
                <!-- Left Column - Content -->
                <div>
                    <h2 style="font-size: 2.25rem; font-weight: 600; margin-bottom: 1.5rem; color: #1e293b; line-height: 1.3;">What is Lean Factory Building?</h2>
                    
                    <p style="font-size: 1rem; line-height: 1.75; color: #475569; margin-bottom: 2.5rem;">
                        Lean Factory Building (LFB) is a specialized architectural approach that reverses the conventional sequence. Instead of forcing your manufacturing process into a pre-conceived structure, we start from your value stream and material flow, wrapping the building around that optimal process.
                    </p>

                    <!-- Numbered Points -->
                    <div style="display: flex; flex-direction: column; gap: 1.75rem;">
                        <!-- Point 1 -->
                        <div style="display: flex; gap: 1.25rem; align-items: start;">
                            <div style="flex-shrink: 0; width: 48px; height: 48px; background: #E99431; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; font-weight: 600;">1</div>
                            <div>
                                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: #1e293b;">Inside-Out Design</h3>
                                <p style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin: 0;">From process flow to building envelope</p>
                            </div>
                        </div>

                        <!-- Point 2 -->
                        <div style="display: flex; gap: 1.25rem; align-items: start;">
                            <div style="flex-shrink: 0; width: 48px; height: 48px; background: #E99431; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; font-weight: 600;">2</div>
                            <div>
                                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: #1e293b;">Full Integration</h3>
                                <p style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin: 0;">Lean principles, industrial layout, and civil architecture</p>
                            </div>
                        </div>

                        <!-- Point 3 -->
                        <div style="display: flex; gap: 1.25rem; align-items: start;">
                            <div style="flex-shrink: 0; width: 48px; height: 48px; background: #E99431; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; font-weight: 600;">3</div>
                            <div>
                                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: #1e293b;">Strategic Alignment</h3>
                                <p style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin: 0;">Project timelines, operations requirements, and finance goals</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Image Placeholder -->
                <div style="background: #1e3a5f; border-radius: 12px; padding: 3rem; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 400px;">
                    <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </div>
                    <div style="text-align: center; color: rgba(255,255,255,0.6); font-size: 0.875rem; line-height: 1.6;">
                        <div style="margin-bottom: 0.25rem;">Image Placeholder (600×400)</div>
                        <div>Factory Layout Blueprint / 3D Render</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 992px) {
            #how-lfb-works .container > div > div:nth-child(2) {
                grid-template-columns: 1fr !important;
                gap: 3rem !important;
            }
        }
    </style>
</section>

<!-- Inside-Out vs Conventional Comparison -->
<section class="section" style="padding: 45px 0; background: #f8f9fa;">
    <div class="container">
        <div class="section-header" style="max-width: 800px; margin: 0 auto 4rem; text-align: center;">
            <h2 style="font-size: 2rem; font-weight: 600; margin-bottom: 1rem; color: #1e293b;">Inside‑Out vs Conventional Factory Design</h2>
        </div>

        <div style="max-width: 1000px; margin: 0 auto; background: white; border-radius: 4px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: grid; grid-template-columns: 200px 1fr 1fr; border-bottom: 1px solid #0369a1;">
                <div style="padding: 1.5rem; background: #f8f9fa;"></div>
                <div style="padding: 1.5rem; text-align: center; border-left: 1px solid #0369a1;">
                    <h3 style="font-size: 1rem; font-weight: 600; color: #64748b;">Conventional Approach</h3>
                </div>
                <div style="padding: 1.5rem; text-align: center; border-left: 1px solid #0369a1; background: #f0f9ff;">
                    <h3 style="font-size: 1rem; font-weight: 600; color: #0369a1;">LFB Approach</h3>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 200px 1fr 1fr; border-bottom: 1px solid #0369a1;">
                <div style="padding: 1.5rem; background: #f8f9fa; font-weight: 600; font-size: 0.875rem; color: #475569;">Starting Point</div>
                <div style="padding: 1.5rem; border-left: 1px solid #0369a1;">
                    <p style="font-size: 0.9rem; line-height: 1.6; color: #64748b;">Design begins with the building shell, facades, and land limits.</p>
                </div>
                <div style="padding: 1.5rem; border-left: 1px solid #0369a1; background: #f0f9ff;">
                    <p style="font-size: 0.9rem; line-height: 1.6; color: #334155;">Design begins with process flow, value stream mapping, and logistics data.</p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 200px 1fr 1fr; border-bottom: 1px solid #0369a1;">
                <div style="padding: 1.5rem; background: #f8f9fa; font-weight: 600; font-size: 0.875rem; color: #475569;">Layout Impact</div>
                <div style="padding: 1.5rem; border-left: 1px solid #0369a1;">
                    <p style="font-size: 0.9rem; line-height: 1.6; color: #64748b;">Manufacturing processes are squeezed into a fixed structural grid.</p>
                </div>
                <div style="padding: 1.5rem; border-left: 1px solid #0369a1; background: #f0f9ff;">
                    <p style="font-size: 0.9rem; line-height: 1.6; color: #334155;">The structural grid and column spacing adapt to support optimal flow.</p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 200px 1fr 1fr;">
                <div style="padding: 1.5rem; background: #f8f9fa; font-weight: 600; font-size: 0.875rem; color: #475569;">Lifetime Cost</div>
                <div style="padding: 1.5rem; border-left: 1px solid #0369a1;">
                    <p style="font-size: 0.9rem; line-height: 1.6; color: #64748b;">Locks in high recurring energy and material handling costs.</p>
                </div>
                <div style="padding: 1.5rem; border-left: 1px solid #0369a1; background: #f0f9ff;">
                    <p style="font-size: 0.9rem; line-height: 1.6; color: #334155;">Ensures lower lifetime OPEX, passive efficiencies, and easier expansions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services / Use-Cases Section -->
<section class="section section-light" style="padding: 45px 0; background: #f8f9fa;">
    <style>
        .service-card {
            padding: 2.5rem;
            background: white;
            border-radius: 4px;
            border-top: 3px solid #0369a1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .service-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1e293b;
        }
        .service-card p {
            font-size: 0.95rem;
            line-height: 1.7;
            color: #64748b;
            flex-grow: 1;
            margin-bottom: 2rem;
        }
        .service-card .btn {
            align-self: flex-start;
            margin-top: auto;
        }
    </style>
    <div class="container">
        <div class="section-header" style="max-width: 800px; margin: 0 auto 4rem; text-align: center;">
            <h2 style="font-size: 2rem; font-weight: 600; margin-bottom: 1.5rem; color: #1e293b;">Where Lean Factory Building Helps You</h2>
            <p style="font-size: 1.125rem; line-height: 1.7; color: #475569;">Whether you are breaking ground on a new site, optimizing an existing facility, or improving running operations, LFB principles apply.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; max-width: 1100px; margin: 0 auto;">
            <div class="service-card">
                <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #0369a1; margin-bottom: 1rem;">Greenfield Plants</div>
                <h3>New Factory Design</h3>
                <p>Define process, flow, and expansion options before land and structure are frozen. Avoid costly layout compromises.</p>
                <a href="./services/greenfield.php" class="btn btn-primary" style="display: inline-block; padding: 0.75rem 1.5rem; font-size: 0.9rem; text-decoration: none;">Plan My New Plant</a>
            </div>

            <div class="service-card">
                <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #0369a1; margin-bottom: 1rem;">Expansions & Brownfield</div>
                <h3>Existing Plant Optimization</h3>
                <p>Re‑engineer existing layouts to reduce internal travel, integrate new lines, and unlock hidden capacity.</p>
                <a href="./services/brownfield.php" class="btn btn-primary" style="display: inline-block; padding: 0.75rem 1.5rem; font-size: 0.9rem; text-decoration: none;">Optimize My Existing Plant</a>
            </div>

            <div class="service-card">
                <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #0369a1; margin-bottom: 1rem;">Post‑Commissioning</div>
                <h3>Running Operations</h3>
                <p>Eliminate wastes in material handling, storage, and movement using LFB principles to stabilize operations.</p>
                <a href="./services/post-commissioning.php" class="btn btn-primary" style="display: inline-block; padding: 0.75rem 1.5rem; font-size: 0.9rem; text-decoration: none;">Request Plant Study</a>
            </div>
        </div>
    </div>
</section>

<!-- Outcomes & Proof Section -->
<section class="section outcomes-section" style="padding: 45px 0; background: white;">
    <div class="container">
        <div class="section-header" style="max-width: 800px; margin: 0 auto 4rem; text-align: center;">
            <h2 style="font-size: 2rem; font-weight: 600; margin-bottom: 1.5rem; color: #1e293b;">What manufacturers achieve with LFB</h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 2rem; max-width: 1000px; margin: 0 auto 3rem;">
            <div style="padding: 2rem; text-align: center; border: 1px solid #e2e8f0; border-radius: 4px;">
                <div style="font-size: 2.5rem; font-weight: 600; color: #0369a1; margin-bottom: 0.5rem;">250+</div>
                <span style="font-size: 0.9rem; color: #64748b; line-height: 1.5;">Factories transformed with flow‑first layouts</span>
            </div>
            <div style="padding: 2rem; text-align: center; border: 1px solid #e2e8f0; border-radius: 4px;">
                <div style="font-size: 2.5rem; font-weight: 600; color: #0369a1; margin-bottom: 0.5rem;">20+</div>
                <span style="font-size: 0.9rem; color: #64748b; line-height: 1.5;">Years of expertise in factory design</span>
            </div>
            <div style="padding: 2rem; text-align: center; border: 1px solid #e2e8f0; border-radius: 4px;">
                <div style="font-size: 2.5rem; font-weight: 600; color: #0369a1; margin-bottom: 0.5rem;">75+</div>
                <span style="font-size: 0.9rem; color: #64748b; line-height: 1.5;">Industrial segments served</span>
            </div>
        </div>

        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <p style="font-size: 1.05rem; line-height: 1.8; color: #475569;">Beyond the numbers, the operational impact is felt on the shop floor every day. Clients report significant reduction in internal material travel and handling effort, improved manufacturing throughput and space utilization, and better structural readiness for future automation.</p>
        </div>
    </div>
</section>

<!-- SOLUTIONS Legacy Section -->
<section class="section solutions-legacy-section" style="padding: 45px 0; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); position: relative; overflow: hidden;">
    <!-- Background decoration -->
    <div style="position: absolute; top: -50%; right: -10%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(233, 148, 49, 0.12) 0%, transparent 70%); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30%; left: -5%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(233, 148, 49, 0.08) 0%, transparent 70%); border-radius: 50%;"></div>
    
    <div class="container" style="position: relative; z-index: 2;">
        <div class="section-header" style="max-width: 900px; margin: 0 auto 3rem; text-align: center;">
            <div style="display: inline-block; padding: 0.5rem 1.25rem; background: rgba(233, 148, 49, 0.15); border: 1px solid rgba(233, 148, 49, 0.3); border-radius: 30px; margin-bottom: 1.5rem;">
                <span style="font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #E99431;">Our Foundation</span>
            </div>
            <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #ffffff; line-height: 1.2;">
                The <span style="color: #E99431;">"SOLUTIONS"</span> Legacy
            </h2>
            <p style="font-size: 1.15rem; line-height: 1.8; color: #cbd5e1; max-width: 800px; margin: 0 auto;">
                Two decades of operational excellence and proven transformation across India's manufacturing landscape
            </p>
        </div>

        <div style="max-width: 1100px; margin: 0 auto;">
            <!-- Main content card -->
            <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 3rem; margin-bottom: 2.5rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);">
                <p style="font-size: 1.125rem; line-height: 1.9; color: #e2e8f0; margin-bottom: 1.5rem;">
                    Since last <strong style="color: #E99431;">20 years</strong>, we, <strong style="color: #ffffff;">Solutions Kaizen Management Systems</strong>, have successfully undertaken numerous improvement projects, enhancing both the top and bottom lines for over <strong style="color: #E99431;">250 businesses</strong> across India. Varied industrial segments served for, are over <strong style="color: #E99431;">75</strong>.
                </p>
                <p style="font-size: 1.125rem; line-height: 1.9; color: #e2e8f0;">
                    We use the world-wide known techniques viz. <strong style="color: #ffffff;">Lean Manufacturing, Six Sigma, Theory of Constraint</strong>. Our expertise includes designing <strong style="color: #ffffff;">Green as well as Brown field factory layouts</strong>. And of course, addressing challenges in productivity, Production Planning, inventory management, Visual Count-free stores, quality control, process optimization, Visual Workplace, etc.
                </p>
            </div>

            <!-- Key highlights grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; padding: 2rem; text-align: center; transition: all 0.3s;">
                    <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 0.75rem;">Methodologies</div>
                    <div style="font-size: 1rem; line-height: 1.7; color: #e2e8f0; font-weight: 500;">
                        Lean Manufacturing<br>Six Sigma<br>Theory of Constraint
                    </div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; padding: 2rem; text-align: center; transition: all 0.3s;">
                    <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 0.75rem;">Project Types</div>
                    <div style="font-size: 1rem; line-height: 1.7; color: #e2e8f0; font-weight: 500;">
                        Greenfield Layouts<br>Brownfield Optimization<br>Process Redesign
                    </div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; padding: 2rem; text-align: center; transition: all 0.3s;">
                    <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 0.75rem;">Core Expertise</div>
                    <div style="font-size: 1rem; line-height: 1.7; color: #e2e8f0; font-weight: 500;">
                        Inventory Management<br>Visual Workplace<br>Process Optimization
                    </div>
                </div>
            </div>

            <!-- Trusted Clients Section -->
            <div style="margin-top: 4rem; text-align: center;">
                <div style="font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 2rem;">Trusted by Leading Manufacturers</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; max-width: 1000px; margin: 0 auto;">
                    <style>
                        .client-card {
                            background: rgba(255, 255, 255, 0.05);
                            border: 1px solid rgba(255, 255, 255, 0.08);
                            border-radius: 6px;
                            padding: 1.25rem 1.5rem;
                            transition: all 0.3s ease;
                            cursor: pointer;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            min-height: 80px;
                            position: relative;
                            overflow: hidden;
                        }
                        .client-card::before {
                            content: '';
                            position: absolute;
                            top: 0;
                            left: -100%;
                            width: 100%;
                            height: 100%;
                            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
                            transition: left 0.6s ease;
                        }
                        .client-card:hover {
                            border-color: rgba(255, 255, 255, 0.15);
                            transform: translateY(-2px);
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                        }
                        .client-card:hover::before {
                            left: 100%;
                        }
                        .client-card span {
                            font-size: 0.95rem;
                            color: #e2e8f0;
                            font-weight: 500;
                            text-align: center;
                            position: relative;
                            z-index: 1;
                            transition: color 0.3s ease;
                        }
                        .client-card:hover span {
                            color: #f0f4f8;
                        }
                    </style>
                    <div class="client-card">
                        <span>OM Auto Components</span>
                    </div>
                    <div class="client-card">
                        <span>Aalap Industries</span>
                    </div>
                    <div class="client-card">
                        <span>Shree Samarth Industries</span>
                    </div>
                    <div class="client-card">
                        <span>Rahul Industries</span>
                    </div>
                    <div class="client-card">
                        <span>Rudra Industries</span>
                    </div>
                    <div class="client-card">
                        <span>Sakshi Industries</span>
                    </div>
                    <div class="client-card">
                        <span>Manasi Auto Parts</span>
                    </div>
                    <div class="client-card">
                        <span>Baba Engineers</span>
                    </div>
                    <div class="client-card">
                        <span>Katdare Food Products</span>
                    </div>
                    <div class="client-card">
                        <span>Ambrosia Bakers</span>
                    </div>
                    <div class="client-card">
                        <span>Palekar Food Products</span>
                    </div>
                    <div class="client-card">
                        <span>Asuwara Masales</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Methodology Snapshot Section -->
<section class="section section-light" style="padding: 45px 0; background: white;">
    <style>
        .methodology-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .methodology-steps {
            display: flex;
            align-items: stretch;
            gap: 1.5rem;
            margin-top: 3rem;
        }
        .methodology-card {
            flex: 1;
            background: #f8f9fa;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 2.5rem 2rem;
            position: relative;
            transition: all 0.3s ease;
        }
        .methodology-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            border-color: #E99431;
        }
        .methodology-number {
            width: 56px;
            height: 56px;
            background: #1e3a5f;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .methodology-step-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #E99431;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.75rem;
        }
        .methodology-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 40px;
        }
        .methodology-arrow svg {
            width: 24px;
            height: 24px;
            color: #E99431;
        }
        @media (max-width: 992px) {
            .methodology-steps {
                flex-direction: column;
                gap: 2rem;
            }
            .methodology-arrow {
                width: 100%;
                height: 40px;
            }
            .methodology-arrow svg {
                transform: rotate(90deg);
            }
        }
    </style>
    <div class="container">
        <div class="methodology-container">
            <!-- Section Label -->
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <span style="display: inline-block; background: #fff3e0; color: #E99431; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.5rem 1rem; border-radius: 4px;">OUR PROCESS</span>
            </div>

            <!-- Section Header -->
            <div style="text-align: center; max-width: 800px; margin: 0 auto 3rem;">
                <h2 style="font-size: 2.25rem; font-weight: 600; margin-bottom: 1.25rem; color: #1e293b;">How OptiSpace delivers LFB</h2>
                <p style="font-size: 1.05rem; line-height: 1.7; color: #475569;">We don't guess; we calculate. Our methodology is a rigorous translation of your production needs into architectural reality.</p>
            </div>

            <!-- Steps Container -->
            <div class="methodology-steps">
                <!-- Step 1 -->
                <div class="methodology-card">
                    <div class="methodology-number">1</div>
                    <div class="methodology-step-label">STEP 1</div>
                    <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; color: #1e293b;">Understand value stream</h3>
                    <p style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin: 0;">Map products, volumes, and material flows to establish baseline metrics</p>
                </div>

                <!-- Arrow 1 -->
                <div class="methodology-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>

                <!-- Step 2 -->
                <div class="methodology-card">
                    <div class="methodology-number">2</div>
                    <div class="methodology-step-label">STEP 2</div>
                    <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; color: #1e293b;">Design flow-first layouts</h3>
                    <p style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin: 0;">Translate flows into layout and structural options that minimize waste</p>
                </div>

                <!-- Arrow 2 -->
                <div class="methodology-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>

                <!-- Step 3 -->
                <div class="methodology-card">
                    <div class="methodology-number">3</div>
                    <div class="methodology-step-label">STEP 3</div>
                    <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; color: #1e293b;">Detail architecture & MEP</h3>
                    <p style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin: 0;">Freeze building concepts for Lean operations and future growth</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lean Wastes Section -->
<section class="section" style="padding: 45px 0; background: #f8f9fa;">
    <div class="container">
        <div class="section-header" style="max-width: 800px; margin: 0 auto 4rem; text-align: center;">
            <h2 style="font-size: 2rem; font-weight: 600; margin-bottom: 1.5rem; color: #1e293b;">Designed to eliminate Lean wastes</h2>
            <p style="font-size: 1.125rem; line-height: 1.7; color: #475569;">LFB is fundamentally aligned with Lean principles. While traditional Lean focuses on process and people, we focus on the environment they work in—structurally eliminating key wastes through design decisions.</p>
        </div>

        <style>
            .waste-card {
                padding: 1.75rem;
                background: white;
                border-radius: 4px;
                border: 1px solid #e2e8f0;
                transition: all 0.3s ease;
            }
            .waste-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 20px rgba(0,0,0,0.08);
                border-color: #E99431;
            }
            .waste-card h3 {
                font-size: 1rem;
                font-weight: 600;
                margin-bottom: 0.75rem;
                color: #1e293b;
                transition: color 0.3s ease;
            }
            .waste-card:hover h3 {
                color: #E99431;
            }
            .waste-card p {
                font-size: 0.9rem;
                line-height: 1.6;
                color: #64748b;
                margin: 0;
                transition: color 0.3s ease;
            }
            .waste-card:hover p {
                color: #475569;
            }
        </style>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; max-width: 1100px; margin: 0 auto;">
            <div class="waste-card">
                <h3>Transportation</h3>
                <p>Moving material unnecessarily due to poor dock location or equipment placement</p>
            </div>
            <div class="waste-card">
                <h3>Motion</h3>
                <p>Operators walking excessive distances between workstations and storage</p>
            </div>
            <div class="waste-card">
                <h3>Waiting</h3>
                <p>Materials sitting idle due to unbalanced line layouts and bottlenecks</p>
            </div>
            <div class="waste-card">
                <h3>Inventory</h3>
                <p>Excess WIP accumulating between operations from poor flow design</p>
            </div>
            <div class="waste-card">
                <h3>Over-Processing</h3>
                <p>Redundant handling or rework caused by inefficient workspace organization</p>
            </div>
            <div class="waste-card">
                <h3>Energy Waste</h3>
                <p>Inefficient HVAC, lighting, and utilities from suboptimal building orientation</p>
            </div>
        </div>
    </div>
</section>

<!-- Primary Lead Capture Section -->
<section class="section pulse-check-section" style="padding: 45px 0; background: linear-gradient(180deg, #f8fafb 0%, #ffffff 100%);">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto; padding: 4rem 3.5rem; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <div style="text-align: center; margin-bottom: 3.5rem;">
                <h2 style="font-size: 2.25rem; font-weight: 600; margin-bottom: 1.25rem; color: #1e293b; line-height: 1.3;">Get your LFB Pulse Check</h2>
                <p style="font-size: 1.1rem; line-height: 1.75; color: #64748b; max-width: 650px; margin: 0 auto;">Share basic details about your plant or upcoming project, and get a structured view of improvement potential from an LFB perspective.</p>
            </div>

            <form class="pulse-check-form" action="./contact.php#pulse-check" method="get">
                <style>
                    .pulse-check-form input:focus,
                    .pulse-check-form select:focus,
                    .pulse-check-form textarea:focus {
                        outline: none;
                        border-color: #0369a1;
                        box-shadow: 0 0 0 3px rgba(3, 105, 161, 0.1);
                    }
                    .pulse-check-form input::placeholder,
                    .pulse-check-form textarea::placeholder {
                        color: #94a3b8;
                    }
                    .pulse-check-form .submit-btn {
                        background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
                        color: white;
                        border: none;
                        transition: all 0.3s ease;
                    }
                    .pulse-check-form .submit-btn:hover {
                        background: linear-gradient(135deg, #075985 0%, #0369a1 100%);
                        transform: translateY(-1px);
                        box-shadow: 0 4px 12px rgba(3, 105, 161, 0.3);
                    }
                </style>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                    <div>
                        <label for="name" style="display: block; font-size: 0.9rem; font-weight: 600; color: #1e293b; margin-bottom: 0.625rem;">Name *</label>
                        <input type="text" id="name" name="name" required style="width: 100%; padding: 0.875rem 1rem; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 0.95rem; transition: all 0.2s;">
                    </div>
                    <div>
                        <label for="company" style="display: block; font-size: 0.9rem; font-weight: 600; color: #1e293b; margin-bottom: 0.625rem;">Company *</label>
                        <input type="text" id="company" name="company" required style="width: 100%; padding: 0.875rem 1rem; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 0.95rem; transition: all 0.2s;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                    <div>
                        <label for="designation" style="display: block; font-size: 0.9rem; font-weight: 600; color: #1e293b; margin-bottom: 0.625rem;">Designation</label>
                        <input type="text" id="designation" name="designation" style="width: 100%; padding: 0.875rem 1rem; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 0.95rem; transition: all 0.2s;">
                    </div>
                    <div>
                        <label for="location" style="display: block; font-size: 0.9rem; font-weight: 600; color: #1e293b; margin-bottom: 0.625rem;">Location *</label>
                        <input type="text" id="location" name="location" required style="width: 100%; padding: 0.875rem 1rem; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 0.95rem; transition: all 0.2s;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                    <div>
                        <label for="industry" style="display: block; font-size: 0.9rem; font-weight: 600; color: #1e293b; margin-bottom: 0.625rem;">Industry *</label>
                        <input type="text" id="industry" name="industry" required style="width: 100%; padding: 0.875rem 1rem; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 0.95rem; transition: all 0.2s;">
                    </div>
                    <div>
                        <label for="plant_stage" style="display: block; font-size: 0.9rem; font-weight: 600; color: #1e293b; margin-bottom: 0.625rem;">Plant Stage *</label>
                        <select id="plant_stage" name="plant_stage" required style="width: 100%; padding: 0.875rem 1rem; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 0.95rem; transition: all 0.2s; background: white; cursor: pointer;">
                            <option value="">Select stage...</option>
                            <option value="greenfield">Greenfield - New construction</option>
                            <option value="brownfield">Brownfield - Existing facility</option>
                            <option value="expansion">Expansion planning</option>
                            <option value="operational">Already operational</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 2.5rem;">
                    <label for="challenge" style="display: block; font-size: 0.9rem; font-weight: 600; color: #1e293b; margin-bottom: 0.625rem;">Brief description of your challenge</label>
                    <textarea id="challenge" name="challenge" rows="5" placeholder="Tell us about your plant, challenges, or goals..." style="width: 100%; padding: 0.875rem 1rem; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 0.95rem; resize: vertical; transition: all 0.2s; line-height: 1.6;"></textarea>
                </div>

                <button type="submit" class="submit-btn" style="width: 100%; padding: 1.125rem; font-size: 1.05rem; font-weight: 600; cursor: pointer; border-radius: 6px; letter-spacing: 0.3px;">Request Pulse Check</button>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
