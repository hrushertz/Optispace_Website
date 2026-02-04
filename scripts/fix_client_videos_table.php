<?php
/**
 * Fix client_videos table - ensures id has AUTO_INCREMENT
 * This fixes the "Field 'id' doesn't have a default value" error on production
 * 
 * Run this script if you get the error:
 * "Error adding video: Field 'id' doesn't have a default value"
 */

require_once __DIR__ . '/../database/db_config.php';

try {
    $conn = getDBConnection();
    
    // Check if table exists
    $tableCheckResult = $conn->query("SHOW TABLES LIKE 'client_videos'");
    
    if ($tableCheckResult->num_rows === 0) {
        // Table doesn't exist, create it fresh
        echo "Creating client_videos table...\n";
        
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
    } else {
        // Table exists, check if id has AUTO_INCREMENT
        echo "Checking client_videos table structure...\n";
        
        $result = $conn->query("SHOW CREATE TABLE client_videos");
        if ($result) {
            $row = $result->fetch_row();
            $createTable = $row[1];
            
            // Check if AUTO_INCREMENT is set
            if (strpos($createTable, 'AUTO_INCREMENT') === false) {
                echo "Warning: id column may not have AUTO_INCREMENT. Attempting to fix...\n";
                
                // Drop and recreate the table (this preserves data structure)
                // First, get all data
                $dataResult = $conn->query("SELECT * FROM client_videos");
                $rows = [];
                
                if ($dataResult && $dataResult->num_rows > 0) {
                    while ($row = $dataResult->fetch_assoc()) {
                        $rows[] = $row;
                    }
                }
                
                // Drop the old table
                if ($conn->query("DROP TABLE client_videos")) {
                    echo "Dropped old client_videos table\n";
                    
                    // Recreate with AUTO_INCREMENT
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
                        echo "✓ Recreated client_videos table with AUTO_INCREMENT\n";
                        
                        // Restore data if any
                        if (!empty($rows)) {
                            foreach ($rows as $data) {
                                $title = $conn->real_escape_string($data['title']);
                                $description = isset($data['description']) ? $conn->real_escape_string($data['description']) : null;
                                $url = $conn->real_escape_string($data['youtube_video_url']);
                                $sortOrder = $data['sort_order'];
                                $isActive = $data['is_active'];
                                $createdBy = isset($data['created_by']) ? $data['created_by'] : null;
                                
                                $insertSql = "INSERT INTO client_videos (title, description, youtube_video_url, sort_order, is_active, created_by) 
                                            VALUES ('$title', " . ($description ? "'$description'" : "NULL") . ", '$url', $sortOrder, $isActive, " . ($createdBy ? $createdBy : "NULL") . ")";
                                
                                if (!$conn->query($insertSql)) {
                                    echo "Warning: Could not restore row - " . $conn->error . "\n";
                                }
                            }
                            echo "✓ Restored " . count($rows) . " video records\n";
                        }
                    } else {
                        throw new Exception("Error recreating table: " . $conn->error);
                    }
                } else {
                    throw new Exception("Error dropping old table: " . $conn->error);
                }
            } else {
                echo "✓ Table structure is correct - id has AUTO_INCREMENT\n";
            }
        }
    }
    
    $conn->close();
    echo "\n✓ Fix completed successfully!\n";
    echo "You should now be able to add videos without the 'Field id doesn't have a default value' error.\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
