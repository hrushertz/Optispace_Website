-- OptiSpace Admin Panel Database Schema
-- Downloads Management System

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('super_admin', 'admin', 'editor') DEFAULT 'editor',
    avatar VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Download Categories Table
CREATE TABLE IF NOT EXISTS download_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    icon VARCHAR(50) DEFAULT 'document',
    color VARCHAR(20) DEFAULT '#E99431',
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Downloads Table
CREATE TABLE IF NOT EXISTS downloads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(20) DEFAULT 'PDF',
    file_size VARCHAR(20) DEFAULT NULL,
    thumbnail VARCHAR(255) DEFAULT NULL,
    download_count INT DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    published_date DATE DEFAULT NULL,
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description TEXT DEFAULT NULL,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES download_categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Activity Log
CREATE TABLE IF NOT EXISTS admin_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) DEFAULT NULL,
    entity_id INT DEFAULT NULL,
    details TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123 - CHANGE THIS IN PRODUCTION!)
INSERT INTO admin_users (username, email, password, full_name, role) VALUES
('admin', 'admin@optispace.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'super_admin')
ON DUPLICATE KEY UPDATE username = username;

-- Insert default categories
INSERT INTO download_categories (name, slug, description, icon, color, sort_order) VALUES
('Brochures', 'brochures', 'Company and service overviews', 'book', '#E99431', 1),
('Case Studies', 'case-studies', 'Real project success stories', 'document', '#3B82F6', 2),
('Technical Resources', 'technical-resources', 'Guidelines and specifications', 'cog', '#10B981', 3),
('White Papers', 'white-papers', 'In-depth research and insights', 'academic-cap', '#8B5CF6', 4)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Insert sample downloads
INSERT INTO downloads (category_id, title, description, file_path, file_name, file_type, file_size, published_date, is_active) VALUES
(1, 'Solutions OptiSpace Company Overview', 'Learn about our mission, expertise, and comprehensive approach to lean factory building and space optimization.', '/downloads/brochures/company-overview.pdf', 'company-overview.pdf', 'PDF', '2.4 MB', '2025-12-01', 1),
(1, 'Lean Factory Building (LFB) Services', 'Detailed overview of our LFB methodology and how it transforms manufacturing facilities.', '/downloads/brochures/lfb-services.pdf', 'lfb-services.pdf', 'PDF', '3.1 MB', '2025-11-01', 1),
(1, 'Brownfield Optimization Services', 'Transform your existing facility with our brownfield optimization expertise and proven methodology.', '/downloads/brochures/brownfield-optimization.pdf', 'brownfield-optimization.pdf', 'PDF', '2.8 MB', '2025-11-01', 1),
(1, 'Greenfield Development Guide', 'Complete guide to planning and executing greenfield manufacturing projects.', '/downloads/brochures/greenfield-guide.pdf', 'greenfield-guide.pdf', 'PDF', '3.5 MB', '2025-10-01', 1),
(2, 'Automotive Manufacturing: 40% Space Optimization', 'How we helped a leading automotive supplier achieve 40% space reduction while increasing throughput by 25%.', '/downloads/case-studies/automotive-optimization.pdf', 'automotive-optimization.pdf', 'PDF', '1.8 MB', '2025-10-01', 1),
(2, 'Electronics Greenfield: Zero to Production in 6 Months', 'Complete greenfield project from site selection to production launch for an electronics manufacturer.', '/downloads/case-studies/electronics-greenfield.pdf', 'electronics-greenfield.pdf', 'PDF', '2.2 MB', '2025-09-01', 1),
(2, 'Pharmaceutical Facility: Compliance & Efficiency', 'Balancing regulatory compliance with lean principles in a pharmaceutical manufacturing environment.', '/downloads/case-studies/pharma-facility.pdf', 'pharma-facility.pdf', 'PDF', '2.0 MB', '2025-08-01', 1),
(2, 'Food Processing Plant Transformation', 'Modernizing a legacy food processing facility with lean principles and automation.', '/downloads/case-studies/food-processing.pdf', 'food-processing.pdf', 'PDF', '2.5 MB', '2025-07-01', 1),
(2, 'Aerospace Assembly Line Optimization', 'Streamlining aerospace component assembly with cellular manufacturing concepts.', '/downloads/case-studies/aerospace-assembly.pdf', 'aerospace-assembly.pdf', 'PDF', '2.3 MB', '2025-06-01', 1),
(3, 'Layout Design Principles & Guidelines', 'Essential principles for designing efficient manufacturing layouts with lean methodology integration.', '/downloads/technical/layout-design-principles.pdf', 'layout-design-principles.pdf', 'PDF', '4.5 MB', '2025-11-01', 1),
(3, 'Material Flow Analysis Handbook', 'Comprehensive guide to analyzing and optimizing material flow in manufacturing environments.', '/downloads/technical/material-flow-handbook.pdf', 'material-flow-handbook.pdf', 'PDF', '3.2 MB', '2025-10-01', 1),
(3, 'Equipment Specification Templates', 'Standard templates for documenting equipment requirements and specifications.', '/downloads/technical/equipment-templates.pdf', 'equipment-templates.pdf', 'PDF', '1.5 MB', '2025-09-01', 1),
(3, 'Safety & Ergonomics Guidelines', 'Best practices for incorporating safety and ergonomics into factory design.', '/downloads/technical/safety-ergonomics.pdf', 'safety-ergonomics.pdf', 'PDF', '2.8 MB', '2025-08-01', 1),
(4, 'The Future of Lean Manufacturing in Industry 4.0', 'Research insights on integrating lean principles with digital transformation and smart factory concepts.', '/downloads/white-papers/lean-industry-4.pdf', 'lean-industry-4.pdf', 'PDF', '3.8 MB', '2025-10-01', 1),
(4, 'Sustainable Factory Design: A Holistic Approach', 'Exploring the intersection of lean manufacturing and environmental sustainability.', '/downloads/white-papers/sustainable-factory.pdf', 'sustainable-factory.pdf', 'PDF', '4.2 MB', '2025-09-01', 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- ========================================
-- GALLERY MANAGEMENT TABLES
-- ========================================

-- Gallery Categories Table
CREATE TABLE IF NOT EXISTS gallery_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    icon VARCHAR(50) DEFAULT 'image',
    color VARCHAR(20) DEFAULT '#E99431',
    bg_class VARCHAR(50) DEFAULT 'greenfield-bg',
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Gallery Items Table
CREATE TABLE IF NOT EXISTS gallery_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    image_path VARCHAR(500) DEFAULT NULL,
    thumbnail_path VARCHAR(500) DEFAULT NULL,
    location VARCHAR(255) DEFAULT NULL,
    industry VARCHAR(100) DEFAULT NULL,
    project_size VARCHAR(100) DEFAULT NULL,
    completion_date DATE DEFAULT NULL,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES gallery_categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Featured Projects Table (for the Featured Transformations section)
CREATE TABLE IF NOT EXISTS featured_projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gallery_item_id INT DEFAULT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    image_path VARCHAR(500) DEFAULT NULL,
    location VARCHAR(255) DEFAULT NULL,
    stat_1_value VARCHAR(50) DEFAULT NULL,
    stat_1_label VARCHAR(100) DEFAULT NULL,
    stat_2_value VARCHAR(50) DEFAULT NULL,
    stat_2_label VARCHAR(100) DEFAULT NULL,
    stat_3_value VARCHAR(50) DEFAULT NULL,
    stat_3_label VARCHAR(100) DEFAULT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gallery_item_id) REFERENCES gallery_items(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Industries Table
CREATE TABLE IF NOT EXISTS gallery_industries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) DEFAULT 'building',
    project_count INT DEFAULT 0,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default gallery categories
INSERT INTO gallery_categories (name, slug, description, icon, color, bg_class, sort_order) VALUES
('Greenfield', 'greenfield', 'New facility developments from ground up', 'building', '#E99431', 'greenfield-bg', 1),
('Brownfield', 'brownfield', 'Existing facility transformations and retrofits', 'refresh', '#3B82F6', 'brownfield-bg', 2),
('Layout Design', 'layout', 'Facility layout and floor plan optimization', 'layout', '#10B981', 'layout-bg', 3),
('Process Flow', 'process', 'Process optimization and value stream mapping', 'workflow', '#64748B', 'process-bg', 4)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Insert default industries
INSERT INTO gallery_industries (name, slug, icon, project_count, sort_order) VALUES
('Automotive', 'automotive', 'car', 25, 1),
('Pharmaceutical', 'pharmaceutical', 'medical', 18, 2),
('Electronics', 'electronics', 'chip', 22, 3),
('Food & Beverage', 'food-beverage', 'food', 15, 4),
('Aerospace', 'aerospace', 'plane', 12, 5),
('Medical Devices', 'medical-devices', 'heart', 20, 6)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Insert sample gallery items
INSERT INTO gallery_items (category_id, title, description, location, industry, project_size, is_featured, is_active) VALUES
(1, 'Automotive Assembly Plant', '150,000 sq ft new facility with integrated lean production lines', 'Michigan, USA', 'Automotive', '150,000 sq ft', 1, 1),
(2, 'Pharmaceutical Facility Retrofit', 'Modernized 80,000 sq ft facility with zero production downtime', 'New Jersey, USA', 'Pharmaceutical', '80,000 sq ft', 0, 1),
(3, 'Electronics Manufacturing Layout', 'Optimized flow design reducing material handling by 40%', 'California, USA', 'Electronics', '65,000 sq ft', 0, 1),
(4, 'Value Stream Mapping', 'End-to-end process optimization for food processing plant', 'Texas, USA', 'Food & Beverage', '45,000 sq ft', 0, 1),
(1, 'Medical Devices Manufacturing', 'Clean room integrated facility with modular design', 'Minnesota, USA', 'Medical Devices', '120,000 sq ft', 1, 1),
(2, 'Textile Mill Transformation', 'Heritage building converted to modern production facility', 'North Carolina, USA', 'Textile', '95,000 sq ft', 0, 1),
(3, 'Warehouse Distribution Center', '3PL facility with automated picking and packing zones', 'Ohio, USA', 'Logistics', '200,000 sq ft', 0, 1),
(4, 'Assembly Line Optimization', 'Cycle time reduction achieving 25% throughput increase', 'Indiana, USA', 'Automotive', '75,000 sq ft', 0, 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- Insert sample featured projects
INSERT INTO featured_projects (title, description, location, stat_1_value, stat_1_label, stat_2_value, stat_2_label, stat_3_value, stat_3_label, is_primary, is_active) VALUES
('Automotive OEM Greenfield Plant', 'Complete greenfield development for a major automotive supplier, featuring state-of-the-art lean manufacturing principles, integrated automation systems, and sustainable design elements.', 'Detroit, Michigan', '40%', 'Space Optimized', '6', 'Months to Launch', '98%', 'On-time Delivery', 1, 1),
('Pharmaceutical Cleanroom Facility', 'FDA-compliant pharmaceutical manufacturing facility with advanced cleanroom integration and lean workflow design.', 'Boston, MA', '35%', 'Efficiency Gain', '100%', 'Compliance', NULL, NULL, 0, 1),
('Electronics Assembly Optimization', 'Complete redesign of electronics assembly operations with cellular manufacturing and automated material handling.', 'San Jose, CA', '45%', 'Throughput Increase', '30%', 'Cost Reduction', NULL, NULL, 0, 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_downloads_category ON downloads(category_id);
CREATE INDEX IF NOT EXISTS idx_downloads_active ON downloads(is_active);
CREATE INDEX IF NOT EXISTS idx_downloads_featured ON downloads(is_featured);
CREATE INDEX IF NOT EXISTS idx_categories_active ON download_categories(is_active);
CREATE INDEX IF NOT EXISTS idx_activity_user ON admin_activity_log(user_id);
CREATE INDEX IF NOT EXISTS idx_activity_created ON admin_activity_log(created_at);
CREATE INDEX IF NOT EXISTS idx_gallery_items_category ON gallery_items(category_id);
CREATE INDEX IF NOT EXISTS idx_gallery_items_active ON gallery_items(is_active);
CREATE INDEX IF NOT EXISTS idx_gallery_items_featured ON gallery_items(is_featured);
CREATE INDEX IF NOT EXISTS idx_gallery_categories_active ON gallery_categories(is_active);
CREATE INDEX IF NOT EXISTS idx_featured_projects_active ON featured_projects(is_active);

-- ========================================
-- BLOG MANAGEMENT TABLES
-- ========================================

-- Blog Categories Table
CREATE TABLE IF NOT EXISTS blog_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    color VARCHAR(20) DEFAULT '#E99431',
    icon VARCHAR(50) DEFAULT 'book',
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blogs Table
CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT DEFAULT NULL,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(500) DEFAULT NULL,
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description TEXT DEFAULT NULL,
    read_time INT DEFAULT 5,
    view_count INT DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    is_published TINYINT(1) DEFAULT 0,
    published_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (author_id) REFERENCES admin_users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog Delete Requests Table
CREATE TABLE IF NOT EXISTS blog_delete_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blog_id INT NOT NULL,
    requested_by INT NOT NULL,
    reason TEXT DEFAULT NULL,
    status ENUM('pending', 'approved', 'declined') DEFAULT 'pending',
    reviewed_by INT DEFAULT NULL,
    reviewed_at DATETIME DEFAULT NULL,
    review_notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
    FOREIGN KEY (requested_by) REFERENCES admin_users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES admin_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default blog categories
INSERT INTO blog_categories (name, slug, description, color, icon, sort_order) VALUES
('Lean Factory', 'lean-factory', 'Principles and practices of lean manufacturing', '#E99431', 'layers', 1),
('Layout Design', 'layout', 'Factory layout optimization and floor planning', '#3B82F6', 'layout', 2),
('Case Studies', 'case-studies', 'Real-world implementation success stories', '#10B981', 'document', 3),
('Industry Trends', 'industry-trends', 'Latest trends in manufacturing and operations', '#8B5CF6', 'trending', 4),
('Operations', 'operations', 'Operational excellence and best practices', '#64748B', 'cog', 5)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Create indexes for blogs
CREATE INDEX IF NOT EXISTS idx_blogs_category ON blogs(category_id);
CREATE INDEX IF NOT EXISTS idx_blogs_author ON blogs(author_id);
CREATE INDEX IF NOT EXISTS idx_blogs_published ON blogs(is_published);
CREATE INDEX IF NOT EXISTS idx_blogs_featured ON blogs(is_featured);
CREATE INDEX IF NOT EXISTS idx_blogs_slug ON blogs(slug);
CREATE INDEX IF NOT EXISTS idx_blog_delete_requests_status ON blog_delete_requests(status);
CREATE INDEX IF NOT EXISTS idx_blog_delete_requests_blog ON blog_delete_requests(blog_id);
