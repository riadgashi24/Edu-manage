<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY due_date ASC");
$stmt->execute([$_SESSION['user_id']]);
$projects = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/styles.css">

</head>
<body>

<h2>Paneli Kryesor</h2>
<a href="add_project.php">➕ Shto Projekt të Ri</a> | <a href="logout.php">🔓 Dil</a>
<hr>

<?php if (count($projects) > 0): ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>Titulli</th>
            <th>Lënda</th>
            <th>Afati</th>
            <th>Statusi</th>
        </tr>
        <?php foreach ($projects as $project): ?>
        <tr>
            <td><?= htmlspecialchars($project['title']) ?></td>
            <td><?= htmlspecialchars($project['subject']) ?></td>
            <td><?= $project['due_date'] ?></td>
            <td><?= $project['status'] ?></td>
            <td>
    <?php if ($project['file_name']): ?>
        <a href="uploads/<?= htmlspecialchars($project['file_name']) ?>" target="_blank">📎 Shkarko</a>
    <?php else: ?>
        -
    <?php endif; ?>
</td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>📭 Nuk ke asnjë projekt të regjistruar.</p>
<?php endif; ?>

</body>
</html>