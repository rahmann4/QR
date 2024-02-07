<?php

session_start();

require 'config.php';
require 'Database.php';

function getQueryParam($name, $default = null)
{
    return isset($_GET[$name]) ? $_GET[$name] : $default;
}

$database = new Database($config);

$qrbody = getQueryParam('qrbody');
$idqrcode = getQueryParam('lastInsertId');

if ($qrbody !== null && $idqrcode !== null) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $qrbody)) {
        $redirectlink = "http://$qrbody";
    }


    $insert = $database->insert(
        'INSERT INTO qr.scan (idqrcode, qrbody, scantime) VALUES (:idqrcode, :qrbody, NOW())',
        ['idqrcode' => $idqrcode, 'qrbody' => $qrbody]
    );

    header("Location: $redirectlink");
    exit();
}

echo "Your QR code is not a link. Your QR code: $qrbody";