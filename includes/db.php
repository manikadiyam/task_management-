<?php
// Database connection
$host = 'localhost';
$db = 'task_new';
$user = 'task_new';
$pass = 'c20GajSrSekEqR7O';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>