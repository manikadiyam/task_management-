<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/config.php';

if (!isset($_GET['id'])) {
    header('Location: tasks.php');
    exit();
}
$id = $_GET['id'];
$stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ?');
$stmt->execute([$id]);
header('Location: tasks.php');
exit();
?>