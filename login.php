<?php
session_start();
require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Email ose fjalëkalim i gabuar.";
    }
}
?>

<!-- Forma -->
<form method="post">
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Fjalëkalimi" required><br>
  <button type="submit">Kyçu</button>
</form>
