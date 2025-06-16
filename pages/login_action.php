<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: ' . BASE_URL . '/pages/dashboard.php');
        exit();
    } else {
        header('Location: ' . BASE_URL . '/pages/login.php?error=Invalid credentials');
        exit();
    }
} else {
    header('Location: ' . BASE_URL . '/pages/login.php');
    exit();
}
?>