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