<?php
/**
 * Test if /downloads URL specifically works
 */
header('Content-Type: text/html');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Downloads URL Test</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: white; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Downloads URL Test</h1>
    
    <h2>Step 1: Check if this file works</h2>
    <p class="success">✓ YES - You can see this page</p>
    
    <h2>Step 2: Test downloading downloads.php content</h2>
    <pre><?php
    $downloads_file = __DIR__ . '/downloads.php';
    if (file_exists($downloads_file)) {
        echo "✓ downloads.php exists\n";
        echo "File size: " . filesize($downloads_file) . " bytes\n";
        echo "Readable: " . (is_readable($downloads_file) ? 'YES' : 'NO') . "\n";
        echo "Permissions: " . substr(sprintf('%o', fileperms($downloads_file)), -4) . "\n";
        
        // Try to read first few lines
        $handle = fopen($downloads_file, 'r');
        if ($handle) {
            echo "\nFirst 5 lines of downloads.php:\n";
            echo "---\n";
            for ($i = 0; $i < 5; $i++) {
                $line = fgets($handle);
                if ($line === false) break;
                echo htmlspecialchars($line);
            }
            fclose($handle);
            echo "---\n";
        }
    } else {
        echo "✗ downloads.php NOT FOUND\n";
    }
    ?></pre>
    
    <h2>Step 3: Try to include downloads.php</h2>
    <div style="border: 2px solid #ccc; padding: 10px; background: white; margin: 10px 0;">
    <?php
    try {
        ob_start();
        @include 'downloads.php';
        $output = ob_get_clean();
        
        if (!empty($output)) {
            echo '<p class="success">✓ downloads.php included successfully!</p>';
            echo '<p class="info">Output length: ' . strlen($output) . ' bytes</p>';
            echo '<details><summary>Show output preview (first 500 chars)</summary><pre>';
            echo htmlspecialchars(substr($output, 0, 500));
            echo '</pre></details>';
        } else {
            echo '<p class="error">✗ downloads.php included but produced no output</p>';
        }
    } catch (Exception $e) {
        echo '<p class="error">✗ Error including downloads.php: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    ?>
    </div>
    
    <h2>Step 4: Direct Access Tests</h2>
    <p>Try these URLs and report which ones work:</p>
    <ul>
        <li><a href="/downloads.php" target="_blank">/downloads.php</a> - Direct .php access</li>
        <li><a href="/downloads" target="_blank">/downloads</a> - Without .php extension</li>
        <li><a href="<?php echo $_SERVER['REQUEST_SCHEME'] ?? 'https'; ?>://<?php echo $_SERVER['HTTP_HOST']; ?>/downloads" target="_blank">Full URL /downloads</a></li>
    </ul>
    
    <h2>Step 5: Check for mod_security blocks</h2>
    <pre><?php
    echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
    echo "Script Name: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "\n";
    echo "Query String: " . ($_SERVER['QUERY_STRING'] ?? 'N/A') . "\n";
    
    // Check if mod_security is loaded
    if (function_exists('apache_get_modules')) {
        $modules = apache_get_modules();
        echo "\nmod_security loaded: " . (in_array('mod_security2', $modules) || in_array('mod_security', $modules) ? 'YES (might be blocking)' : 'NO') . "\n";
    }
    ?></pre>
    
    <h2>Recommendation</h2>
    <p>If downloads.php includes successfully above but accessing /downloads directly gives 403:</p>
    <ul>
        <li>Check cPanel Error Log for "downloads" keyword</li>
        <li>Check if "downloads" is in mod_security blacklist</li>
        <li>Try renaming downloads.php to resources.php temporarily</li>
    </ul>
</body>
</html>
