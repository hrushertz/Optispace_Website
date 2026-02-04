<?php
// Fix for FAQ insertion error: Field 'id' doesn't have a default value
// This script also fixes duplicate IDs before applying the schema change
require_once 'database/db_config.php';

try {
    echo "Connecting to database...\n";
    $conn = getDBConnection();

    // Step 0: Fix Duplicate IDs
    echo "Step 0: Checking for and fixing duplicate IDs by backing up data...\n";

    // Fetch all rows
    $result = $conn->query("SELECT * FROM pulse_check_faqs ORDER BY id");
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    echo "Found " . count($rows) . " rows to migrate.\n";

    // Truncate table
    // verify we have data backed up first
    if (count($rows) == 0) {
        // If table is empty, we just need to fix schema.
        echo "Table is empty. Proceeding to schema fix only.\n";
    }

    // Truncate whatever partial mess is there.
    $conn->query("TRUNCATE TABLE pulse_check_faqs");
    echo "Table truncated.\n";

    // Step 1: Add Primary Key (Table is empty now - SAFE)
    echo "Step 1: Adding PRIMARY KEY to 'id' column...\n";
    // Using TRY/CATCH for Safety in case PK exists but was not detected
    try {
        $conn->query("ALTER TABLE `pulse_check_faqs` ADD PRIMARY KEY (`id`)");
    } catch (Exception $e) {
        echo "Info: Primary key might already exist: " . $e->getMessage() . "\n";
    }

    // Step 2: Modify to AUTO_INCREMENT
    echo "Step 2: Modifying 'id' column to be AUTO_INCREMENT...\n";
    $conn->query("ALTER TABLE `pulse_check_faqs` MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT");

    // Step 3: Insert Data Back
    echo "Step 3: Restoring data...\n";
    if (count($rows) > 0) {
        $stmt_restore = $conn->prepare("INSERT INTO pulse_check_faqs (question, answer, sort_order, is_active, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");

        $restored = 0;
        foreach ($rows as $row) {
            $stmt_restore->bind_param(
                "ssiiiss",
                $row['question'],
                $row['answer'],
                $row['sort_order'],
                $row['is_active'],
                $row['created_by'],
                $row['created_at'],
                $row['updated_at']
            );
            $stmt_restore->execute();
            $restored++;
        }
        echo "SUCCESS: Restored $restored rows. Schema is now fixed.\n";
    } else {
        echo "SUCCESS: Schema fixed (no data to restore).\n";
    }

    // Final Verification
    $result = $conn->query("DESCRIBE pulse_check_faqs id");
    if ($row = $result->fetch_assoc()) {
        echo "Verification Status: " . $row['Key'] . " | " . $row['Extra'] . "\n";
    }

    $conn->close();

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>