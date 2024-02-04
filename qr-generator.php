<?php

require "vendor/autoload.php";

use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\QrCode;

$body = $_POST['body'];

$qr_code = QrCode::create($body);
$writer = new PngWriter;
$result = $writer->write($qr_code);

header("Content-Type: " . $result->getMimeType());

echo $result->getString();