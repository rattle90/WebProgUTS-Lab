<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']); // Accepts email or username
    $password = $_POST['password'];

    // Determine whether login is an email or username
    $stmt = filter_var($login, FILTER_VALIDATE_EMAIL) 
        ? $pdo->prepare("SELECT * FROM users WHERE email = ?")
        : $pdo->prepare("SELECT * FROM users WHERE username = ?");

    $stmt->execute([$login]);
    $user = $stmt->fetch();

    // Verify password and set session if valid
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header('Location: index.php');
        exit();
    } else {
        // Pass error status for clearer handling in the front-end
        $_SESSION['error'] = 'Email/Username or password is incorrect.';
        header('Location: login.php');
        exit();
    }
}
?>
