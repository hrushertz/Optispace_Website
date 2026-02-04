<?php
/**
 * Download Handler
 * Serves files from assets/downloads/ directory
 * Usage: /download?file=filename.pdf
 */

// Get the file parameter
$file = isset($_GET['file']) ? basename($_GET['file']) : null;

if (!$file) {
    http_response_code(400);
    die('No file specified');
}

// Security: prevent directory traversal
if (strpos($file, '..') !== false || strpos($file, '/') !== false) {
    http_response_code(403);
    die('Invalid file path');
}

// Full path to file
$filepath = __DIR__ . '/assets/downloads/' . $file;

// Check if file exists
if (!file_exists($filepath) || !is_file($filepath)) {
    http_response_code(404);
    die('File not found');
}

// Check if readable
if (!is_readable($filepath)) {
    http_response_code(403);
    die('File not readable');
}

// Get file info
$filesize = filesize($filepath);
$filename = basename($filepath);
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimetype = finfo_file($finfo, $filepath);
finfo_close($finfo);

// If mimetype detection failed, default to application/octet-stream
if ($mimetype === false) {
    $mimetype = 'application/octet-stream';
}

// Send headers
header('Content-Type: ' . $mimetype);
header('Content-Length: ' . $filesize);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Send file
readfile($filepath);
exit;
?>
