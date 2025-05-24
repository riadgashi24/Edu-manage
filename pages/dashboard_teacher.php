<?php
// register.php - Backend logic for user registration
session_start();
require 'includes/db.php';

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $name     = trim($_POST['name']);
        $surname  = trim($_POST['surname']);
        $username = trim($_POST['username']);
        $email    = trim($_POST['email']);
        $password = $_POST['password'];
        $role     = $_POST['role'];

        if (empty($name) || empty($surname) || empty($username) || empty($email) || empty($password) || empty($role)) {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $error = "Username or email already exists.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (name, surname, username, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
                try {
                    $stmt->execute([$name, $surname, $username, $email, $passwordHash, $role]);
                    $success = "Registration successful. You can now <a href='login.php'>login</a>.";
                } catch (PDOException $e) {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
    }
}
?>

<?php
// login.php - Backend logic for user login
session_start();
require 'includes/db.php';

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
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}
$name = htmlspecialchars($_SESSION['name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Welcome, Prof. <?= $name ?></h2>
        <p>This is your teacher dashboard.</p>

        <ul class="list-group">
            <li class="list-group-item"><a href="create_project.php">Create New Project</a></li>
            <li class="list-group-item"><a href="view_students.php">View All Students</a></li>
            <li class="list-group-item"><a href="logout.php" class="text-danger">Logout</a></li>
        </ul>
    </div>
</body>
</html>
