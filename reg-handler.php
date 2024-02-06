<?php
session_start();

require 'Database.php';
require 'config.php';

$database = new Database($config);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $result = $database->insert("INSERT INTO qr.users (`username`, `password`) VALUES (:username, :password)", [':username' => $username, ':password' => $password]);

        if ($result) {
            $_SESSION['message'] = 'Registration successful. You can now log in.';
        } else {
            $_SESSION['message'] = 'Registration failed. Please try again.';
        }

        header('Location: index.php?message=' . $_SESSION['message']);
        exit;
    }
} catch (PDOException $e) {
    if ($e->getCode() == '23000') {
        $_SESSION['message'] = 'Username exists. Please choose a different username.';
    } else {
        $_SESSION['message'] = 'An error occurred. Please try again later.';
    }
    header('Location: register.php?message=' . $_SESSION['message']);
    exit;
}