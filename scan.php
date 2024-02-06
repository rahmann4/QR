<?php

session_start();

require 'config.php';
require 'Database.php';

function getQueryParam($name, $default = null)
{
    return isset($_GET[$name]) ? $_GET[$name] : $default;
}

$qrbody = getQueryParam('qrbody');

if ($qrbody !== null) {

    $database = new Database($config);

    $currentResult = $database->query('SELECT qrbody FROM qr.qrcode WHERE userid = :userid', ['userid' => $_SESSION['id']]);

    if ($currentResult && $currentResult['qrbody'] === $qrbody) {
        $update = $database->update('UPDATE qr.qrcode SET scan = scan + 1, scantime = NOW() WHERE userid = :userid', ['userid' => $_SESSION['id']]);
        if (!preg_match("~^(?:f|ht)tps?://~i", $qrbody)) {
            $qrbody = "http://$qrbody";
        }
    }

    header("Location: $qrbody");
    exit();
}

echo "Your QR code is not a link. Your QR code: $qrbody";
