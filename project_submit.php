<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $student_id = $_SESSION['user_id'];

    if (empty($title) || empty($description)) {
        $error = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO projects (student_id, title, description) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$student_id, $title, $description]);
            $success = "Project submitted successfully!";
        } catch (PDOException $e) {
            $error = "Error submitting project.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4">
        <h4>Submit Your Project</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Project Title</label>
                <input type="text" class="form-control" name="title" id="title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Project Description</label>
                <textarea class="form-control" name="description" id="description" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="dashboard_student.php" class="btn btn-secondary">Back to Dashboard</a>
        </form>
    </div>
</div>
</body>
</html>
