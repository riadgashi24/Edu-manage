<?php
require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $password]);
        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        echo "Gabim: " . $e->getMessage();
    }
}
?>

<!-- Forma -->
<form method="post">
  <input type="text" name="username" placeholder="Emri" required><br>
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Fjalëkalimi" required><br>
  <button type="submit">Regjistrohu</button>
</form>
