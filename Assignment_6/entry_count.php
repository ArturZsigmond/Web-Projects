<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require 'includes/db.php';
session_start();

$email = $_SESSION['email'] ?? '';

if (!$email) {
    echo json_encode(['count' => 0]);
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM entries WHERE author_email = ?");
$stmt->execute([$email]);
$count = $stmt->fetchColumn();

echo json_encode(['count' => $count]);
?>
