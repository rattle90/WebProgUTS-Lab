<?php
$host = 'localhost';  // Host database
$db = 'todo_list_db'; // Nama database
$user = 'root';       // Username database
$pass = '';           // Password database (biarkan kosong jika tidak ada)

try {
    // Create connection using PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
