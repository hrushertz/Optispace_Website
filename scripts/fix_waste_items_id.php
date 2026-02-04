<?php
require_once __DIR__ . '/../database/db_config.php';

echo "Attempting to fix waste_items table...\n";
mysqli_report(MYSQLI_REPORT_OFF);

try {
    $conn = getDBConnection();

    // Check if table exists
    $check = $conn->query("SHOW TABLES LIKE 'waste_items'");
    if ($check->num_rows === 0) {
        die("Table waste_items does not exist.\n");
    }

    // Try simple ALTER first
    echo "Attempting simple ALTER...\n";
    $sql = "ALTER TABLE waste_items MODIFY id INT AUTO_INCREMENT PRIMARY KEY";
    if ($conn->query($sql) === TRUE) {
        echo "Successfully modified id column via ALTER.\n";
        exit;
    }

    $error = $conn->error;
    echo "Simple ALTER failed: " . $error . "\n";

    if (strpos($error, 'duplicate entry') !== false) {
        echo "Detected duplicate keys. Switching to Table Re-creation strategy...\n";

        // 1. Create temp table
        echo "Creating temp table...\n";
        $conn->query("DROP TABLE IF EXISTS waste_items_temp");

        // Create table with correct schema manually to be sure, or copy and alter
        // Let's copy structure first
        if (!$conn->query("CREATE TABLE waste_items_temp LIKE waste_items")) {
            die("Failed to create temp table: " . $conn->error . "\n");
        }

        // Fix temp table schema
        echo "Fixing temp table schema...\n";
        if (!$conn->query("ALTER TABLE waste_items_temp MODIFY id INT AUTO_INCREMENT PRIMARY KEY")) {
            die("Failed to alter temp table: " . $conn->error . "\n");
        }

        // 2. Copy data
        echo "Copying data...\n";
        // Get columns excluding id
        $columns = [];
        $res = $conn->query("SHOW COLUMNS FROM waste_items");
        while ($row = $res->fetch_assoc()) {
            if ($row['Field'] !== 'id') {
                $columns[] = $row['Field'];
            }
        }

        if (empty($columns)) {
            die("No columns found?\n");
        }

        $colList = implode(", ", $columns);
        $sql = "INSERT INTO waste_items_temp ($colList) SELECT $colList FROM waste_items";

        if ($conn->query($sql)) {
            echo "Data copied successfully. Rows affected: " . $conn->affected_rows . "\n";

            // 3. Swap tables
            echo "Swapping tables...\n";
            $conn->query("RENAME TABLE waste_items TO waste_items_old, waste_items_temp TO waste_items");

            echo "Tables swapped. Dropping old table...\n";
            $conn->query("DROP TABLE waste_items_old");

            echo "Fix completed successfully via re-creation.\n";

        } else {
            echo "Failed to copy data: " . $conn->error . "\n";
        }

    } else {
        echo "Error was not duplicate entry. Aborting.\n";
    }

    $conn->close();

} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
?>
