CREATE TABLE IF NOT EXISTS `banner_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(50) NOT NULL,
  `eyebrow_text` varchar(255) DEFAULT NULL,
  `heading_html` text DEFAULT NULL,
  `subheading` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_page_active` (`page_name`,`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
