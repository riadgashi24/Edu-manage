<?php
session_start();

// Kontrollo nëse është i loguar dhe është student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

require 'includes/db.php';

// Merr të dhënat e studentit
$name = $_SESSION['name'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .dashboard {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="dashboard">
    <h3>Welcome, <?= htmlspecialchars($name) ?> 👋</h3>
    <p class="text-muted">You're logged in as <strong><?= htmlspecialchars($username) ?></strong>.</p>

    <hr>

    <h5>Your Project</h5>
    <p>Check your assigned project or submit a new one.</p>
    <a href="project_submit.php" class="btn btn-success">Submit Project</a>
    <a href="project_view.php" class="btn btn-info">View Project</a>

    <hr>

    <a href="logout.php" class="btn btn-outline-danger mt-3">Logout</a>
</div>
</body>
</html>
