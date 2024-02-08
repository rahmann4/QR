<?php

/* session_start(); */

require 'config.php';
require 'Database.php';

$database = new Database($config);

$qrid = $_GET['qrid'];

$qrCodes = $database->query('SELECT * FROM qrcode WHERE idqrcode = :idqrcode', ['idqrcode' => $qrid]);
$qrbody = $qrCodes['qrbody'];
header('Location: ' . "https://" . $qrbody);


$insert = $database->insert(
    'INSERT INTO scan (idqrcode, qrbody, scantime) VALUES (:idqrcode, :qrbody, NOW())',
    ['idqrcode' => $qrid, 'qrbody' => $qrbody]
);

/* function getQueryParam($name, $default = null)
{
    return isset($_GET[$name]) ? $_GET[$name] : $default;
}


$qrbody = getQueryParam('qrbody');
$idqrcode = getQueryParam('lastInsertId');

if ($qrbody !== null && $idqrcode !== null) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $qrbody)) {
        $redirectlink = "http://$qrbody";
    }




    header("Location: $redirectlink");
    exit();
} */

/* echo "Your QR code is not a link. Your QR code: $qrbody"; */