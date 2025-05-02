<?php
require 'includes/db.php';

$email = $_POST['email'] ?? '';
$title = trim($_POST['title'] ?? '');
$comment = trim($_POST['comment'] ?? '');
$rating = intval($_POST['rating'] ?? 3);
if ($rating < 1 || $rating > 5) {
    http_response_code(400);
    echo 'Invalid rating.';
    exit;
}
session_start();
$user_id = $_SESSION['user_id'] ?? null;




if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'Invalid email.';
    exit;
}

if (strlen($title) < 3 || strlen($comment) < 5) {
    http_response_code(400);
    echo 'Title must be at least 3 chars, comment at least 5.';
    exit;
}

if (!$user_id) {
    http_response_code(400);
    echo 'You must be logged in.';
    exit;
}

$stmt = $pdo->prepare("INSERT INTO entries (author_email, title, comment, rating, user_id) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$email, $title, $comment, $rating, $user_id]);

echo 'OK';
?>
