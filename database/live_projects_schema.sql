-- ========================================
-- LIVE PROJECTS TABLE
-- ========================================

-- Live Projects Table (for showing ongoing/current projects)
CREATE TABLE IF NOT EXISTS live_projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) DEFAULT NULL,
    client_name VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    content LONGTEXT DEFAULT NULL,
    project_type VARCHAR(50) DEFAULT NULL COMMENT 'Brownfield, Greenfield, Layout Design, etc.',
    industry VARCHAR(100) DEFAULT NULL,
    location VARCHAR(255) DEFAULT NULL,
    start_date DATE DEFAULT NULL,
    expected_completion DATE DEFAULT NULL,
    progress_percentage INT DEFAULT 0,
    current_phase VARCHAR(100) DEFAULT NULL COMMENT 'Current phase of the project',
    image_path VARCHAR(500) DEFAULT NULL,
    thumbnail_path VARCHAR(500) DEFAULT NULL,
    highlight_1 VARCHAR(255) DEFAULT NULL COMMENT 'Key highlight/achievement',
    highlight_2 VARCHAR(255) DEFAULT NULL,
    highlight_3 VARCHAR(255) DEFAULT NULL,
    is_featured TINYINT(1) DEFAULT 0 COMMENT 'Show on home page',
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create indexes for live projects
CREATE INDEX idx_live_projects_active ON live_projects(is_active);
CREATE INDEX idx_live_projects_featured ON live_projects(is_featured);
CREATE INDEX idx_live_projects_sort ON live_projects(sort_order);

-- Insert sample live projects
INSERT INTO live_projects (title, client_name, description, project_type, industry, location, start_date, expected_completion, progress_percentage, current_phase, highlight_1, highlight_2, highlight_3, is_featured, is_active, sort_order) VALUES
(
    'Automotive Assembly Plant Expansion',
    'Leading Automotive OEM',
    'Complete greenfield expansion of an existing automotive assembly facility, adding 100,000 sq ft of new production space with integrated lean manufacturing principles.',
    'Greenfield',
    'Automotive',
    'Pune, Maharashtra',
    '2025-09-01',
    '2026-06-30',
    45,
    'Layout Design',
    '40% reduction in material travel planned',
    'Integrated AGV pathways',
    'Future expansion ready design',
    1,
    1,
    1
),
(
    'Pharmaceutical Manufacturing Facility',
    'Pharma Major',
    'Brownfield optimization of existing pharmaceutical manufacturing facility with focus on GMP compliance and lean flow design.',
    'Brownfield',
    'Pharmaceutical',
    'Hyderabad, Telangana',
    '2025-10-15',
    '2026-04-30',
    30,
    'Value Stream Mapping',
    'Zero production disruption approach',
    'GMP-compliant layout design',
    '35% efficiency improvement target',
    1,
    1,
    2
),
(
    'Electronics Manufacturing Layout Optimization',
    'Electronics MNC',
    'Complete layout redesign for electronics manufacturing facility focusing on cellular manufacturing and reduced cycle times.',
    'Layout Design',
    'Electronics',
    'Bengaluru, Karnataka',
    '2025-11-01',
    '2026-03-31',
    60,
    'Implementation',
    'Cellular manufacturing implementation',
    '25% cycle time reduction achieved',
    'Modular workstation design',
    1,
    1,
    3
)
ON DUPLICATE KEY UPDATE title = VALUES(title);
