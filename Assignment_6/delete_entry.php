<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: text/plain");

require 'includes/db.php';

$id = intval($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM entries WHERE id = ?");
    $stmt->execute([$id]);
    echo 'Deleted';
} else {
    http_response_code(400);
    echo 'Invalid ID.';
}
