<?php
header('Content-Type: text/plain');

$usersFile = __DIR__ . '/users.php';
$content = file_get_contents($usersFile);

echo "=== USERS.PHP VERIFICATION ===\n\n";
echo "File size: " . strlen($content) . " bytes\n";
echo "Last modified: " . date('Y-m-d H:i:s', filemtime($usersFile)) . "\n\n";

// Check lines 7-15
$lines = explode("\n", $content);
echo "Lines 7-15:\n";
echo "---\n";
for ($i = 6; $i < 15 && $i < count($lines); $i++) {
    echo ($i + 1) . ": " . $lines[$i] . "\n";
}
echo "---\n\n";

// Check for the problematic line
if (strpos($content, "require_once __DIR__ . '/../database/db_config.php'") !== false) {
    echo "❌ PROBLEM FOUND: users.php still has the old db_config.php include!\n";
    echo "   This causes db_config.php to be included twice.\n\n";
    echo "   Line 12 should be: // db_config.php is already included in auth.php\n";
    echo "   Instead it has: require_once __DIR__ . '/../database/db_config.php';\n";
} else {
    echo "✓ users.php is correctly updated (no duplicate db_config include)\n";
}
?>
