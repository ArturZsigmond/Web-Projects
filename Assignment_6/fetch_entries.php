<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require 'includes/db.php';

$groupBy = $_GET['groupBy'] ?? 'none';

$query = "SELECT * FROM entries";

if ($groupBy === 'author') {
    $query .= " ORDER BY author_email";
} elseif ($groupBy === 'title') {
    $query .= " ORDER BY title";
} else {
    $query .= " ORDER BY date_created DESC";
}

$stmt = $pdo->query($query);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($entries);
?>