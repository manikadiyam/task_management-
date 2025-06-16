<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit();
}
$id = $_GET['id'];
$stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
$stmt->execute([$id]);
header('Location: users.php');
exit();
?>