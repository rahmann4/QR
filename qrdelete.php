<?php

session_start();

require 'config.php';
require 'Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database($config);

    if (isset($_POST['deleteid'])) {
        $idqrcode = $_POST['deleteid'];

        $insert = $database->update(
            'UPDATE qr.qrcode SET deletetime = NOW() WHERE idqrcode = :idqrcode',
            ['idqrcode' => $idqrcode]
        );

        $svgFilePath = 'qrcodes/' . $idqrcode . '.svg';
        $pngFilePath = 'qrcodes/' . $idqrcode . '.png';

        if (file_exists($svgFilePath)) {
            unlink($svgFilePath);
        }
        if (file_exists($pngFilePath)) {
            unlink($pngFilePath);
        }

        header("Location: qr-page.php?userid=" . $_SESSION['id']);
        exit();
    } elseif (isset($_POST['deleteidadmin'])) {
        $idqrcode = $_POST['deleteidadmin'];

        $insert = $database->update(
            'UPDATE qr.qrcode SET deletetime = NOW() WHERE idqrcode = :idqrcode',
            ['idqrcode' => $idqrcode]
        );

        $svgFilePath = 'qrcodes/' . $idqrcode . '.svg';
        $pngFilePath = 'qrcodes/' . $idqrcode . '.png';

        if (file_exists($svgFilePath)) {
            unlink($svgFilePath);
        }
        if (file_exists($pngFilePath)) {
            unlink($pngFilePath);
        }

        header("Location: qrcodesTable.php");
        exit();
    }
}
