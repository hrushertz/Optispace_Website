<?php
require_once 'includes/auth.php';
requireLogin();

// Get database connection
$conn = getDBConnection();

echo "<h2>POST Data Debug</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<h2>Success Stories in Database</h2>";
$result = $conn->query("SELECT id, title FROM success_stories ORDER BY id ASC");
echo "<table border='1'><tr><th>ID</th><th>Title</th><th>Action</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
    echo "<td>
        <form method='POST' style='display:inline;'>
            <input type='hidden' name='action' value='delete'>
            <input type='hidden' name='story_id' value='" . $row['id'] . "'>
            <button type='submit'>Delete</button>
        </form>
    </td>";
    echo "</tr>";
}
echo "</table>";

// Handle delete if posted
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['story_id'])) {
    $storyId = intval($_POST['story_id']);
    echo "<h3>Attempting to delete ID: $storyId</h3>";
    
    $stmt = $conn->prepare("DELETE FROM success_stories WHERE id = ?");
    $stmt->bind_param("i", $storyId);
    
    if ($stmt->execute()) {
        echo "<p style='color:green'>Delete successful! Rows affected: " . $stmt->affected_rows . "</p>";
        echo "<script>setTimeout(function(){ location.reload(); }, 1000);</script>";
    } else {
        echo "<p style='color:red'>Delete failed! Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

$conn->close();
?>
