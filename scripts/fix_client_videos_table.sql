-- =================================================================
-- Fix for: Field 'id' doesn't have a default value error
-- =================================================================
-- 
-- This SQL fixes the client_videos table to ensure the id column
-- has AUTO_INCREMENT properly set on production servers.
--
-- Run this in phpMyAdmin if the PHP fix script doesn't work.
-- =================================================================

-- Option 1: If table has data you want to keep
-- Step 1: Create a temporary backup table with proper AUTO_INCREMENT
CREATE TABLE client_videos_temp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL COMMENT 'Client name or video title',
    description TEXT DEFAULT NULL COMMENT 'Brief description of the video',
    youtube_video_url VARCHAR(255) NOT NULL COMMENT 'YouTube video URL or video ID',
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL,
    INDEX idx_active (is_active),
    INDEX idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 2: Copy all data from old table to temp table
INSERT INTO client_videos_temp (id, title, description, youtube_video_url, sort_order, is_active, created_by, created_at, updated_at)
SELECT id, title, description, youtube_video_url, sort_order, is_active, created_by, created_at, updated_at
FROM client_videos;

-- Step 3: Drop the old table
DROP TABLE client_videos;

-- Step 4: Rename temp table to original name
ALTER TABLE client_videos_temp RENAME TO client_videos;

-- Verify the fix worked
SHOW CREATE TABLE client_videos;

-- =================================================================
-- Option 2: If table is empty or you don't need to keep data
-- (Run this if Option 1 doesn't work)
-- =================================================================

-- Simply drop and recreate:
-- DROP TABLE IF EXISTS client_videos;
-- 
-- CREATE TABLE IF NOT EXISTS client_videos (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     title VARCHAR(255) NOT NULL COMMENT 'Client name or video title',
--     description TEXT DEFAULT NULL COMMENT 'Brief description of the video',
--     youtube_video_url VARCHAR(255) NOT NULL COMMENT 'YouTube video URL or video ID',
--     sort_order INT DEFAULT 0,
--     is_active TINYINT(1) DEFAULT 1,
--     created_by INT DEFAULT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--     FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL,
--     INDEX idx_active (is_active),
--     INDEX idx_sort (sort_order)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
