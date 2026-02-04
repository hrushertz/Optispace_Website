<?php
/**
 * Check for BOM and whitespace issues
 */

header('Content-Type: text/plain');

$files = [
    'auth.php' => __DIR__ . '/includes/auth.php',
    'db_config.php' => dirname(__DIR__) . '/database/db_config.php',
    'config.php' => dirname(__DIR__) . '/includes/config.php',
    'mailer.php' => dirname(__DIR__) . '/includes/mailer.php',
    'env_loader.php' => dirname(__DIR__) . '/env_loader.php',
];

echo "=== CHECKING FOR BOM AND WHITESPACE ===\n\n";

foreach ($files as $name => $path) {
    if (!file_exists($path)) {
        echo "$name: FILE NOT FOUND\n";
        continue;
    }
    
    $content = file_get_contents($path);
    
    // Check for BOM
    $bom = substr($content, 0, 3);
    if ($bom === "\xEF\xBB\xBF") {
        echo "❌ $name: HAS UTF-8 BOM (THIS IS THE PROBLEM!)\n";
    } else {
        echo "✓ $name: No BOM\n";
    }
    
    // Check for whitespace before <?php
    if (substr($content, 0, 5) !== '<?php') {
        $first10 = bin2hex(substr($content, 0, 10));
        echo "❌ $name: HAS WHITESPACE BEFORE <?php - First bytes: $first10\n";
    }
    
    // Check for whitespace or output after ?>
    if (substr(rtrim($content), -2) === '?>') {
        $afterClose = substr($content, strrpos($content, '?>') + 2);
        if (trim($afterClose) !== '') {
            echo "⚠️  $name: Has content after closing ?>\n";
        }
    }
}

echo "\n=== END CHECK ===\n";
?>
