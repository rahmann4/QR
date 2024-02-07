<?php

session_start();

require 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

$path = 'qrcodes/';
if (isset($_POST['formatPNG'])) {
    $idqrcode = $_POST['idqrcode'];
    $userid = $_POST['userid'];
    $qrbody = $_POST['qrbody'];
    $filename = $path . $idqrcode . '.png';
    $qrCode = new QrCode($qrbody);
    $writer = new PngWriter();
    $qrPngString = $writer->write($qrCode)->getString();
    file_put_contents($filename, $qrPngString);
    header("Location: qr-page.php?userid=" . $_SESSION['id'] . "?qrid=" . $idqrcode);
    exit();
}
if (isset($_POST['formatSVG'])) {
    $idqrcode = $_POST['idqrcode'];
    $userid = $_POST['userid'];
    $qrbody = $_POST['qrbody'];
    $filename = $path . $idqrcode . '.svg';
    $qrCode = new QrCode($qrbody);
    $writer = new SvgWriter();
    $qrSvgString = $writer->write($qrCode)->getString();
    file_put_contents($filename, $qrSvgString);
    header("Location: qr-page.php?userid=" . $_SESSION['id'] . "?qrid=" . $idqrcode);
    exit();
}

/* if (isset($_POST['format'])) {
    $fileFormat = $_POST['format'];
    $idqrcode = $_POST['idqrcode'];
    $userid = $_POST['userid'];
    $qrbody = $_POST['qrbody'];

    $filename = $path . $idqrcode . '.' . strtolower($fileFormat);

    if ($fileFormat === 'PNG') {
        $qrCode = new QrCode($qrbody);
        $writer = new PngWriter();
        $qrPngString = $writer->write($qrCode)->getString();
        file_put_contents($filename, $qrPngString);

    } elseif ($fileFormat === 'JPG') {
       $qrCode = new QrCode($qrbody);
       $writer = new JpgWriter();
       $qrJpgString = $writer->write($qrCode)->getString();
       file_put_contents($filename, $qrJpgString);

   }elseif ($fileFormat === 'SVG') {
        $qrCode = new QrCode($qrbody);
        $writer = new SvgWriter();
        $qrSvgString = $writer->write($qrCode)->getString();
        file_put_contents($filename, $qrSvgString);
    }

    header("Location: qr-page.php?userid=" . $_SESSION['id'] . "?qrid=" . $lastInsertId);
    exit();
} */