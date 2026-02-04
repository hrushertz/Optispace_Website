<?php
/**
 * Final Test - Exact copy of users.php start
 */

$pageTitle = "Users Test";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();
requireAdminRole('admin');

$conn = getDBConnection();
$currentAdmin = getCurrentAdmin();

// If we get here, everything works!
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Success</title>
</head>
<body>
    <h1>✅ SUCCESS!</h1>
    <p>All includes are working correctly.</p>
    <p>Logged in as: <?php echo htmlspecialchars($currentAdmin['full_name']); ?></p>
    <p>Role: <?php echo htmlspecialchars($currentAdmin['role']); ?></p>
    <hr>
    <p><a href="users.php">Now try the actual users.php page</a></p>
</body>
</html>
<?php
$conn->close();
?>
