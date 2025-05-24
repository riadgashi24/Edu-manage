<?php
session_start();
require 'includes/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username    = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (isset($_POST['submit']) && $user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Incorrect username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <title>Login</title>
</head>
<body>
    <div class="d-flex flex-column justify-content-center align-middle align-items-center vh-100">
        <!-- <img src="images/logo.png" alt="Logo" class="mb-4" width="72" height="72">
        <h1 class="h3 mb-3 fw-normal">Edu Manage</h1>
        <p class="text-center">Project management system for students</p> -->
  <form class="form-signin w-30" method="post">
    <h1 class="text-center text-primary">Login</h1>

    <div class="mb-3">
      <label for="inputEmail" class="sr-only">Username</label>
      <input type="text" id="inputEmail" class="form-control" placeholder="Username" name="username" required autofocus>
    </div>
    <div class="mb-3">
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password" required>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Sign in</button>
    <small>Don't have an account? <a href="register.php">Sign Up</a></small><br><br>
    <p class="mt-5 mb-3 text-muted">Edu Manage &copy; 2025</p>
  </form>
  </div>
</body>
</html>