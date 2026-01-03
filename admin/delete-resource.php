<?php
require_once 'auth_check.php';
require_once '../database/db_config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn = getDBConnection();
    
    // Get the file path before deleting
    $stmt = $conn->prepare("SELECT file_path FROM resources WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Delete from database
        $delete_stmt = $conn->prepare("DELETE FROM resources WHERE id = ?");
        $delete_stmt->bind_param("i", $id);
        
        if ($delete_stmt->execute()) {
            // Delete file
            if (file_exists('../' . $row['file_path'])) {
                unlink('../' . $row['file_path']);
            }
        }
        
        $delete_stmt->close();
    }
    
    $stmt->close();
    $conn->close();
}

header('Location: add-resources.php');
exit;
?>
