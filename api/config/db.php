<?php
$host     = 'sql300.epizy.com';         // ← your actual host
$dbname   = 'epiz_12345678_carrental';   // ← your actual DB name
$username = 'epiz_12345678';             // ← your actual username
$password = '';          // ← your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>
