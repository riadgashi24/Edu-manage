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
