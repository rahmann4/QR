<?php

session_start();

require 'config.php';
require 'Database.php';

$database = new Database($config);
$qrCodes = $database->queryAll('SELECT * FROM qr.qrcode WHERE userid = :userid AND deletetime IS NULL', ['userid' => $_SESSION['id']]);

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

    <?php foreach ($qrCodes as $qrCode): ?>
        <div class="qr-header">
            <h1>
                <?= $qrCode['qrname'] ?>
            </h1>
        </div>

        <div class="qr-code-container">
            <?php
            $idqrcode = $qrCode['idqrcode'];
            $filename = "qrcodes/{$idqrcode}.svg";
            $svgContent = file_get_contents($filename);
            echo $svgContent;
            ?>
        </div>

        <div>
            <button data-bs-toggle="modal" data-bs-target="#modal<?= $qrCode['idqrcode'] ?>" type="button"
                class="button">Change</button>
            <form action="qrdelete.php" method="POST">
            <input type="hidden" value="<?= $qrCode['idqrcode'] ?>" name="deleteid">
                <button name="delete" type="submit" class="button">Delete</button>
            </form>
        </div>

        <div class="modal fade" id="modal<?= $qrCode['idqrcode'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change QR value:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="qrupdate.php">
                            <label name="qrname">Change QR Name:</label>
                            <input value="<?= $qrCode['qrname'] ?>" name="qrname">
                            <label name="qrbody">Change QR Content:</label>
                            <input value="<?= $qrCode['qrbody'] ?>" name="qrbody">
                            <input type="hidden" value="<?= $qrCode['idqrcode'] ?>" name="idqrcode">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div>
        <a href="qr-generator.view.php" class="button">Create new</a>
    </div>


    <div>
        <a href="logout.php" class="a button">Logout</a>
    </div>

    <script src="styling/bootstrap.bundle.min.js"></script>

</body>

</html>