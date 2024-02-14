<?php

session_start();

require 'config.php';
require 'Database.php';
require 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    if (isset($_POST['role']) && $_POST['role'] === 'admin') {
        $qrbody = $_POST['qrbody'];
        $qrName = $_POST['qrName'];
        $database = new Database($config);

        $insert = $database->insert('INSERT INTO qr.qrcode (userid, qrname, qrbody) VALUES (:userid, :qrname, :qrbody)', [':userid' => $_SESSION['id'], ':qrname' => $qrName, ':qrbody' => $qrbody]);

        $qrid = $database->lastInsertId();

        $redirectUrl = "https://qr.executechnology.net/scan.php?qrid=$qrid";

        $qrCode = new QrCode($redirectUrl);

        $writer = new SvgWriter();

        $qrSvgString = $writer->write($qrCode)->getString();

        $path = 'qrcodes/';
        $filename = $path . $qrid . '.svg';
        file_put_contents($filename, $qrSvgString);

        header("Location: qrcodesTable.php");
        exit();

    } else {
        $qrbody = $_POST['qrbody'];
        $qrName = $_POST['qrName'];
        $database = new Database($config);
        $insert = $database->insert('INSERT INTO qr.qrcode (userid, qrname, qrbody) VALUES (:userid, :qrname, :qrbody)', [':userid' => $_SESSION['id'], ':qrname' => $qrName, ':qrbody' => $qrbody]);

        $qrid = $database->lastInsertId();

        $redirectUrl = "https://qr.executechnology.net/scan.php?qrid=$qrid";

        $qrCode = new QrCode($redirectUrl);

        $writer = new SvgWriter();

        $qrSvgString = $writer->write($qrCode)->getString();

        $path = 'qrcodes/';
        $filename = $path . $qrid . '.svg';
        file_put_contents($filename, $qrSvgString);

        header("Location: qr-page.php?userid=" . $_SESSION['id'] . "?qrid=" . $qrid);
        exit();
    }

}

