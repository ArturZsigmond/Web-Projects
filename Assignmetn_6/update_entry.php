<?php
require 'includes/db.php';

$id = $_POST['id'] ?? '';
$title = $_POST['title'] ?? '';
$comment = $_POST['comment'] ?? '';

if ($id && $title && $comment) {
    $stmt = $pdo->prepare("UPDATE entries SET title = ?, comment = ? WHERE id = ?");
    $stmt->execute([$title, $comment, $id]);
    header("Location: admin/admin_panel.php");
    exit;

} else {
    http_response_code(400);
    echo 'Missing fields';
}
?>
