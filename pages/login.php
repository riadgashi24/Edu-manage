<?php 
session_start();
require 'includes/db.php';

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $usernameOrEmail = trim($_POST['username']);
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'teacher') {
                header("Location: dashboard_teacher.php");
            } else {
                header("Location: dashboard_student.php");
            }
            exit;
        } else {
            $error = "Invalid login credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-form {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 380px;
        }
    </style>
</head>
<body>
<div class="login-form">
    <h4 class="mb-4 text-center">Welcome Back</h4>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="username" class="form-control" placeholder="Username or Email" required>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>

        <p class="text-center">Don't have an account? <a href="register.php">Register</a></p>
    </form>
</div>
</body>
</html>
