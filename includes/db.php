<?php
$host = 'localhost';
$db   = 'edumanage'; // sigurohu që ky emër është i njëjtë në phpMyAdmin
$user = 'root';       // përdoruesi i MySQL
$pass = '';           // fjalëkalimi për root (shpesh bosh në local)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
