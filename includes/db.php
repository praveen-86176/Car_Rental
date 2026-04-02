<?php
$host     = 'sql102.byetcluster.com';         
$dbname   = 'if0_41561926_carrental';   
$username = 'if0_41561926';             
$password = 'pk86176rj';          

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>
