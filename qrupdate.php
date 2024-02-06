<?php

session_start();

require 'config.php';
require 'Database.php';
require 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qrbody = $_POST['qrbody'];
    $qrname = $_POST['qrname'];
    $userid = $_SESSION['id'];

    if (empty($qrbody) || empty($qrname) ) {
        $_SESSION['error'] = 'Fields must be filled';
        header('Location: qr-page.php');
        exit();
    }

    $database = new Database($config);
    $insert = $database->update('UPDATE qr.qrcode SET qrname = :qrname, qrbody = :qrbody WHERE userid = :userid', ['qrname'=> $qrname, 'qrbody'=> $qrbody, 'userid'=> $_SESSION['id']]);

    $qr_code = QrCode::create($qrbody);
    $writer = new SvgWriter;
    $result = $writer->write($qr_code)->getString();

    $_SESSION['qrContent'] = $result;

    header("Location: qr-page.php");
    exit();
}
