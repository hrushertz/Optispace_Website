<?php
require_once 'auth_check.php';
require_once '../database/db_config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn = getDBConnection();
    
    // Get the image path before deleting
    $stmt = $conn->prepare("SELECT image_path, thumbnail_path FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Delete from database
        $delete_stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
        $delete_stmt->bind_param("i", $id);
        
        if ($delete_stmt->execute()) {
            // Delete files
            if (file_exists('../' . $row['image_path'])) {
                unlink('../' . $row['image_path']);
            }
            if (file_exists('../' . $row['thumbnail_path'])) {
                unlink('../' . $row['thumbnail_path']);
            }
        }
        
        $delete_stmt->close();
    }
    
    $stmt->close();
    $conn->close();
}

header('Location: add-gallery.php');
exit;
?>
