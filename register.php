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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <title>Sign up </title>
</head>
<body>
    
  <div class="signup d-flex flex-column justify-content-center align-items-center h-100">
    <form  method="post" class="signin w-25">
      <h1 class="h33 mb-3 font-weight-normal">Please sign up</h1>
      <label for="inputEmail" class="sr-only">Name</label>
      <input type="text" id="inputEmail" class="form-control" placeholder="name" name="name" required autofocus>

      <label for="inputEmail" class="sr-only">Surname</label>
      <input type="text" id="inputEmail" class="form-control" placeholder="surname" name="surname" required autofocus>

      <label for="inputEmail" class="sr-only">Username</label>
      <input type="text" id="inputEmail" class="form-control" placeholder="username" name="username" required autofocus>

      <label for="inputEmail" class="sr-only">Email</label>
      <input type="email" id="inputEmail" class="form-control" placeholder="email" name="email" required autofocus>

      <label for="inputEmail" class="sr-only">Password</label>
      <input type="password" id="inputEmail" class="form-control" placeholder="password" name="password" required autofocus>

      <button type="submit" class="btn btn-lg btn-primary btn-block" name="submit ">Sign Up</button>
    </form>
  </div>

</body>
