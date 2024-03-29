<?php
session_start();

require 'Database.php';
require 'config.php';

$database = new Database($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $database->query("SELECT * FROM users WHERE username = :username", [':username' => $username]);

    if ($result) {
        $hashedPassword = $result['password'];

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $result["username"];
            $_SESSION['id'] = $result["idusers"];
            $qrcheck = $database->query("SELECT * FROM qrcode WHERE userid = ?", [$userid]);

            header("Location: qr-page.php?userid=" . $_SESSION['id']);
            exit;
        }
    }

    $error = "Incorrect credentials";
    header('Location: index.php?error=' . $error);
    exit;
}
