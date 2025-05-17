<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $subject = $_POST['subject'];
    $due_date = $_POST['due_date'];
    $user_id = $_SESSION['user_id'];

    $file_name = null;

    if (isset($_FILES['project_file']) && $_FILES['project_file']['error'] == 0) {
        $original_name = $_FILES['project_file']['name'];
        $tmp_path = $_FILES['project_file']['tmp_name'];

        $upload_dir = "uploads/";
        $unique_name = uniqid() . "_" . basename($original_name);
        $target_path = $upload_dir . $unique_name;

        if (move_uploaded_file($tmp_path, $target_path)) {
            $file_name = $unique_name;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO projects (user_id, title, subject, due_date, file_name) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $subject, $due_date, $file_name]);

    header("Location: dashboard.php");
    exit();
}

?>

<h2>Shto Projekt</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" required>
    <input type="text" name="subject" required>
    <input type="date" name="due_date" required>
    <input type="file" name="project_file" accept=".pdf,.docx,.txt"><br>
    <button type="submit">Ruaj</button>
</form>

<a href="dashboard.php">⬅️ Kthehu</a>
