<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");
session_start();

$email = $_SESSION['email'] ?? '';
echo json_encode(['email' => $email]);
