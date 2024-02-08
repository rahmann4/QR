<?php

session_start();

require 'config.php';
require 'Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idqrcode = $_POST['deleteid'];

    $database = new Database($config);
    $insert = $database->update(
        'UPDATE qrcode SET deletetime = NOW() WHERE idqrcode = :idqrcode',
        ['idqrcode' => $idqrcode]
    );

    header("Location: qr-page.php?userid=" . $_SESSION['id']);
    exit();
}
