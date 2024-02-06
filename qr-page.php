<?php

session_start();

require 'config.php';
require 'Database.php';

if (isset($_SESSION['qrContent'])) {
    $result = $_SESSION['qrContent'];
}

$database = new Database($config);
$currentResult = $database->query('SELECT * FROM qr.qrcode WHERE userid = :userid', ['userid' => $_SESSION['id']]);
if ($currentResult) {
    $body = $currentResult['qrbody'];
    $qrName = $currentResult['qrname'];
}

print_r($_SESSION['id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>QR Code Page</title>
    <link rel="stylesheet" href="styling/qr-page.css">
    <link rel="stylesheet" href="styling/bootstrap.min.css">
</head>

<body>
    <div class="qr-header">
        <h1><?= $qrName ?>
    </h1>
    </div>

    <div class="qr-code-container">
        <?= $result ?>
    </div>

    <div>
        <button data-bs-toggle="modal" data-bs-target="#modal" type="button" class="button">Change</button>
    </div>

    <div>
        <a href="logout.php" class="a button">Logout</a>
    </div>


    <div id="modal" class="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change QR value:</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="qrupdate.php">
                        <label name="qrname">Change QR Name:</label>
                        <input value=<?= $qrName ?> name="qrname">
                        <label name="qrbody">Change QR Content:</label>
                        <input value=<?= $body ?> name="qrbody">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="styling/bootstrap.bundle.min.js"></script>

</body>

</html>