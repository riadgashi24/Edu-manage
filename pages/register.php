<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$error = $_SESSION['register_error'] ?? '';
$success = $_SESSION['register_success'] ?? '';
unset($_SESSION['register_error'], $_SESSION['register_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
<div class="card p-4 shadow" style="width: 400px;">
    <h4 class="mb-3 text-center">Create Account</h4>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" action="../logic/register_logic.php">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="row mb-3">
            <div class="col">
                <input type="text" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="col">
                <input type="text" name="surname" class="form-control" placeholder="Surname" required>
            </div>
        </div>

        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>

        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <div class="mb-3">
            <select name="role" class="form-select" required>
                <option value="">Choose Role</option>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Register</button>
        </div>

        <div class="mt-3 text-center">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </form>
</div>
</body>
</html>
