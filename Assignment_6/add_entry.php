<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = $_POST['author'] ?? '';
    $title = $_POST['title'] ?? '';
    $comment = $_POST['comment'] ?? '';

    if (filter_var($author, FILTER_VALIDATE_EMAIL) && !empty($title) && !empty($comment)) {
        $stmt = $pdo->prepare("INSERT INTO guest_book (author, title, comment) VALUES (?, ?, ?)");
        $stmt->execute([$author, $title, $comment]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input.']);
    }
}
?>
