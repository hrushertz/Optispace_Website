<?php
require_once 'database/db_config.php';
$conn = getDBConnection();

$data = [];

$res = $conn->query('SELECT id, name FROM blog_categories');
$data['categories'] = [];
while ($row = $res->fetch_assoc()) {
    $data['categories'][] = $row;
}

$res2 = $conn->query('SELECT id, title, category_id, is_published FROM blogs');
$data['blogs'] = [];
while ($row2 = $res2->fetch_assoc()) {
    $data['blogs'][] = $row2;
}

echo json_encode($data, JSON_PRETTY_PRINT);
?>