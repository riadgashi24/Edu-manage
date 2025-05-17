<?php
$host = 'localhost';
$db   = 'edumanage';
$user = 'root';
$pass = ''; // shto password nëse ke
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // për gabime të qarta
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // merr të dhënat si array associative
    PDO::ATTR_EMULATE_PREPARES   => false,                  // për siguri më të lartë
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Gabim në lidhje: " . $e->getMessage());
}
?>
