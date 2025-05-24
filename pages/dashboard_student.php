<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}
$name = htmlspecialchars($_SESSION['name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Welcome, <?= $name ?></h2>
        <p>This is your student dashboard.</p>

        <ul class="list-group">
            <li class="list-group-item"><a href="view_projects.php">View Projects</a></li>
            <li class="list-group-item"><a href="my_submissions.php">My Submissions</a></li>
            <li class="list-group-item"><a href="logout.php" class="text-danger">Logout</a></li>
        </ul>
    </div>
</body>
</html>
