<?php

require 'Database.php';
require 'config.php';

$database = new Database($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $database->query("SELECT * FROM users WHERE username = :username AND password = :password", [':username' => $username, ':password' => $password]);

    if ($result) {
        header('Location: qr-generator.view.php');
        exit;
    } else {
        echo 'Username or password are incorrect.';
    }
}