<?php
session_start();

require 'config.php';
require 'Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qrCode = $_POST['idqrcode'];
    $database = new Database($config);
    $delete = $database->delete(
        'DELETE FROM qr.qrcode WHERE idqrcode = :idqrcode',
        ['idqrcode' => $qrCode]
    );

    if ($delete) {
        header("Location: qrcodesTable.php");
        exit();
    } else {
        $_SESSION['error'] = 'An error occurred. Please try again later.';
        header('Location: qrcodesTable.php?error=' . $_SESSION['error']);
        exit;
    }
    
}