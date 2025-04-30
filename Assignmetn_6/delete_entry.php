<?php
require 'includes/db.php';

$id = $_POST['id'] ?? '';

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM entries WHERE id = ?");
    $stmt->execute([$id]);
    echo 'Deleted';
} else {
    http_response_code(400);
    echo 'Missing ID';
}
?>
