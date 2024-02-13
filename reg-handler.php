<?php
session_start();

require 'Database.php';
require 'config.php';

$database = new Database($config);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $result = $database->insert(
            'INSERT INTO qr.users (username, password) VALUES (:username, :password)',
            [':username' => $username, ':password' => $hashedPassword]
        );

        if ($result) {
            $_SESSION['message'] = 'Registration successful.';
            header('Location: dashboard.php?message=' . ($_SESSION['message'])); 
        } else {
            $_SESSION['error'] = 'Registration failed. Please try again.';
            header('Location: dashboard.php?error=' . ($_SESSION['error']));
        }
        exit;
    }

} catch (PDOException $e) {
    if ($e->getCode() == '23000') {
        $_SESSION['error'] = 'Email exists. Please choose a different Email.';
    } else {
        $_SESSION['error'] = 'An error occurred. Please try again later.';
    }
    header('Location: dashboard.php?error=' . $_SESSION['error']);
    exit;
}