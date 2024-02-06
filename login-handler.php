<?php
session_start();

require 'Database.php';
require 'config.php';

$database = new Database($config);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $database->query("SELECT * FROM qr.users WHERE username = :username AND password = :password", [':username' => $username, ':password' => $password]);

    if ($result) {
        $_SESSION['username'] = $result["username"];
        $_SESSION['id'] = $result["idusers"];
        $userid = $_SESSION['id'];
        $qrcheck = $database->query("SELECT * FROM qr.qrcode WHERE userid = ?", [$userid]);
        if (($qrcheck)) {
            header('Location: qr-page.php');
            exit;
        } else {
            header('Location: qr-generator.view.php');
            exit;
        }
    } else {
        $error = "Incorrect credeintals";
        header('Location: index.php?error=' . $error);
        exit;

    }
}
