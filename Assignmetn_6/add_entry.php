<?php
require 'includes/db.php';

$email = $_POST['email'] ?? '';
$title = $_POST['title'] ?? '';
$comment = $_POST['comment'] ?? '';

if ($email && $title && $comment) {
    $stmt = $pdo->prepare("INSERT INTO entries (author_email, title, comment) VALUES (?, ?, ?)");
    $stmt->execute([$email, $title, $comment]);
    echo 'OK';
} else {
    http_response_code(400);
    echo 'Missing fields';
}
?>
