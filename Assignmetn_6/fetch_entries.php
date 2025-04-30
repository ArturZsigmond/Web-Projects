<?php
require 'includes/db.php';

$page = intval($_GET['page'] ?? 1);
$groupBy = $_GET['groupBy'] ?? 'none';
$limit = 4;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM entries";
if ($groupBy === 'author') {
    $query .= " ORDER BY author_email";
} elseif ($groupBy === 'title') {
    $query .= " ORDER BY title";
} else {
    $query .= " ORDER BY date_created DESC";
}
$query .= " LIMIT $limit OFFSET $offset";

$stmt = $pdo->query($query);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($entries);
?>
