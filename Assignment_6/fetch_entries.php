<?php
require 'db.php';

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 4;
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("SELECT * FROM guest_book ORDER BY date DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();

$entries = $stmt->fetchAll();

echo json_encode($entries);
?>
