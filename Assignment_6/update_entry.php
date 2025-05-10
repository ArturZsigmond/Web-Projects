<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: text/plain");

require 'includes/db.php';

$id = intval($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$comment = trim($_POST['comment'] ?? '');

if ($id > 0 && strlen($title) >= 3 && strlen($comment) >= 5) {
    $stmt = $pdo->prepare("UPDATE entries SET title = ?, comment = ? WHERE id = ?");
    $stmt->execute([$title, $comment, $id]);
    echo 'Updated';
} else {
    http_response_code(400);
    echo 'Invalid data.';
}
