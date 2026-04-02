<?php
$host = 'localhost';
$db   = 'car_rental';
$user = 'root';
$pass = 'Kumar Praveen'; // Default XAMPP/MAMP password is often empty or 'root'. Please update if needed.
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
    // Create DB if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`");
    $pdo->exec("USE `$db`");
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
