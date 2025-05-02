<?php
$host = 'localhost';
$db = 'guestbook';
$user = 'root'; // or your MySQL user if you added one
$pass = 'Yourmomghei1@'; // replace with your actual password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
