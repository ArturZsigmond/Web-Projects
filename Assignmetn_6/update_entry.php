<?php
require 'includes/db.php';

$id = $_POST['id'] ?? '';
$title = $_POST['title'] ?? '';
$comment = $_POST['comment'] ?? '';

if ($id && $title && $comment) {
    $stmt = $pdo->prepare("UPDATE entries SET title = ?, comment = ? WHERE id = ?");
    $stmt->execute([$title, $comment, $id]);
    echo 'Updated';
} else {
    http_response_code(400);
    echo 'Missing fields';
}
?>
