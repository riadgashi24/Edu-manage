<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $title = trim($_POST['title']);
        $subject = trim($_POST['subject']);
        $due_date = $_POST['due_date'];
        $user_id = $_SESSION['user_id'];
        $file_name = null;

        if (empty($title) || empty($subject) || empty($due_date)) {
            $error = "All fields except file are required.";
        } else {
            if (isset($_FILES['project_file']) && $_FILES['project_file']['error'] == 0) {
                $original_name = $_FILES['project_file']['name'];
                $tmp_path = $_FILES['project_file']['tmp_name'];

                $upload_dir = "uploads/";
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $unique_name = uniqid() . "_" . basename($original_name);
                $target_path = $upload_dir . $unique_name;

                if (move_uploaded_file($tmp_path, $target_path)) {
                    $file_name = $unique_name;
                } else {
                    $error = "File upload failed.";
                }
            }

            if (!$error) {
                $stmt = $pdo->prepare("INSERT INTO projects (user_id, title, subject, due_date, file_name) VALUES (?, ?, ?, ?, ?)");
                try {
                    $stmt->execute([$user_id, $title, $subject, $due_date, $file_name]);
                    $success = "Project added successfully.";
                } catch (PDOException $e) {
                    $error = "Database error: could not add project.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 400px; }
        input[type="text"], input[type="date"], input[type="file"] { width: 100%; padding: 0.5rem; margin-bottom: 1rem; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { background-color: #5cb85c; color: white; padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; margin-bottom: 1rem; }
        .success { color: green; margin-bottom: 1rem; }
    </style>
</head>
<body>
<div class="container">
    <h2>Add Project</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="text" name="title" placeholder="Project Title" required>
        <input type="text" name="subject" placeholder="Subject" required>
        <input type="date" name="due_date" required>
        <input type="file" name="project_file">
        <input type="submit" value="Add Project">
    </form>
</div>
</body>
</html>
