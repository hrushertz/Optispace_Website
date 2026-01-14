<?php
/**
 * Add youtube_video_url column to success_stories table
 * Run this script once to update the database schema
 */

require_once __DIR__ . '/../database/db_config.php';

try {
    $conn = getDBConnection();
    
    // Check if column already exists
    $result = $conn->query("SHOW COLUMNS FROM success_stories LIKE 'youtube_video_url'");
    
    if ($result->num_rows === 0) {
        // Add the youtube_video_url column
        $sql = "ALTER TABLE success_stories 
                ADD COLUMN youtube_video_url VARCHAR(255) DEFAULT NULL 
                COMMENT 'YouTube video URL or video ID' 
                AFTER results";
        
        if ($conn->query($sql)) {
            echo "✓ Successfully added youtube_video_url column to success_stories table\n";
        } else {
            throw new Exception("Error adding column: " . $conn->error);
        }
    } else {
        echo "✓ youtube_video_url column already exists\n";
    }
    
    $conn->close();
    echo "\nDatabase update completed successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
