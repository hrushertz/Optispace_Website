<?php
require_once 'auth_check.php';
require_once '../database/db_config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn = getDBConnection();
    
    // Get the image path before deleting
    $stmt = $conn->prepare("SELECT featured_image FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Delete from database
        $delete_stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
        $delete_stmt->bind_param("i", $id);
        
        if ($delete_stmt->execute()) {
            // Delete featured image if exists
            if (!empty($row['featured_image']) && file_exists('../' . $row['featured_image'])) {
                unlink('../' . $row['featured_image']);
            }
        }
        
        $delete_stmt->close();
    }
    
    $stmt->close();
    $conn->close();
}

header('Location: add-blog.php');
exit;
?>
