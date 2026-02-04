-- Add slug and content columns to live_projects table

ALTER TABLE `live_projects`
ADD COLUMN `slug` VARCHAR(255) DEFAULT NULL AFTER `title`,
ADD COLUMN `content` LONGTEXT DEFAULT NULL AFTER `description`;

-- Add unique index for slug
CREATE INDEX `idx_live_projects_slug` ON `live_projects` (`slug`);
