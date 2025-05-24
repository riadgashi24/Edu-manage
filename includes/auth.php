<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_teacher() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'teacher';
}

function is_student() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'student';
}

function redirect_if_not_logged_in() {
    if (!is_logged_in()) {
        header("Location: ../pages/login.php");
        exit;
    }
}
?>
