<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// CSRF token setup
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$project = null;
$error = '';
$success = '';

// Load project
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    $project = $stmt->fetch();

    if (!$project) {
        $error = "Project not found or access denied.";
    }
} else {
    $error = "No project specified.";
}

// Handle updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $title = trim($_POST['title']);
        $subject = trim($_POST['subject']);
        $due_date = $_POST['due_date'];
        $status = $_POST['status'];

        if (!$title || !$subject || !$due_date || !$status) {
            $error = "All fields are required.";
        } else {
            $stmt = $pdo->prepare("UPDATE projects SET title = ?, subject = ?, due_date = ?, status = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$title, $subject, $due_date, $status, $project['id'], $_SESSION['user_id']]);
            $success = "Project updated successfully.";
            header("Refresh:2; url=dashboard.php");
        }
    }
}

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
        $stmt->execute([$project['id'], $_SESSION['user_id']]);
        $success = "Project deleted successfully.";
        header("Refresh:2; url=dashboard.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Project</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: white; padding: 2rem; width: 450px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select { width: 100%; padding: 0.5rem; margin-bottom: 1rem; border: 1px solid #ccc; border-radius: 4px; }
        .error { color: red; margin-bottom: 1rem; }
        .success { color: green; margin-bottom: 1rem; }
        .actions { display: flex; justify-content: space-between; }
        button { padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; }
        .update-btn { background-color: #5cb85c; color: white; }
        .delete-btn { background-color: #d9534f; color: white; }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Project</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($project): ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="text" name="title" value="<?= htmlspecialchars($project['title']) ?>" required>
            <input type="text" name="subject" value="<?= htmlspecialchars($project['subject']) ?>" required>
            <input type="date" name="due_date" value="<?= htmlspecialchars($project['due_date']) ?>" required>
            <select name="status" required>
                <option value="pending" <?= $project['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="complete" <?= $project['status'] === 'complete' ? 'selected' : '' ?>>Complete</option>
            </select>
            <div class="actions">
                <button type="submit" name="update" class="update-btn">Update</button>
                <button type="submit" name="delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete this project?');">Delete</button>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
