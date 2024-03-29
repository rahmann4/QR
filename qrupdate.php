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
    $idqrcode = $_POST['idqrcode'];

    if (empty($qrbody) || empty($qrname)) {
        $_SESSION['error'] = 'Fields must be filled';
        header('Location: qr-page.php');
        exit();
    }

    $database = new Database($config);
    $insert = $database->update('UPDATE qrcode SET qrname = :qrname, qrbody = :qrbody WHERE userid = :userid AND idqrcode = :idqrcode',
    ['qrname' => $qrname, 'qrbody' => $qrbody, 'userid' => $_SESSION['id'], 'idqrcode' => $idqrcode]);

    $redirectUrl = "scan.php?qrbody=$qrbody&lastInsertId=$idqrcode";

    header("Location: qr-page.php?userid=" . $_SESSION['id']);
    exit();
}
