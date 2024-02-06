<?php

session_start();

require 'config.php';
require 'Database.php';
require 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qrbody = $_POST['qrbody'];
    $qrName = $_POST['qrName'];

    if (empty($qrbody) || empty($qrName)) {
        $_SESSION['error'] = 'Both fields must be filled';
        header('Location: qr-generator.view.php');
        exit();
    }

    $database = new Database($config);
    $insert = $database->insert('INSERT INTO qr.qrcode (userid, qrname, qrbody) VALUES (:userid, :qrname, :qrbody)', [':userid' =>  $_SESSION['id'], ':qrname' => $qrName, ':qrbody' => $qrbody]);

    $qr_code = QrCode::create($qrbody);
    $writer = new SvgWriter;
    $result = $writer->write($qr_code)->getString();

    $_SESSION['qrContent'] = $result;

    header("Location: qr-page.php");
    exit();
}
