<?php
/**
 * Create client_videos table for YouTube testimonial videos
 * Run this script once to create the table
 */

require_once __DIR__ . '/../database/db_config.php';

try {
    $conn = getDBConnection();
    
    // Create client_videos table
    $sql = "CREATE TABLE IF NOT EXISTS client_videos (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql)) {
        echo "✓ Successfully created client_videos table\n";
    } else {
        throw new Exception("Error creating table: " . $conn->error);
    }
    
    $conn->close();
    echo "\nDatabase setup completed successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
