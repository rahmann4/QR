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
    <div class="container">
        <div class="qr-container">
            <div class="row justify-content-center">
                <?php foreach ($qrCodes as $qrCode): ?>
                    <div class="col-lg-4 col-md-12 mb-3">
                        <div class="qr-item">
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

                            <div class="qr-footer">
                                <button data-bs-toggle="modal" data-bs-target="#modal<?= $qrCode['idqrcode'] ?>"
                                    type="button" class="button">Change</button>

                                <form action="qrdelete.php" method="POST">
                                    <input type="hidden" value="<?= $qrCode['idqrcode'] ?>" name="deleteid">
                                    <button data-bs-toggle="modal" data-bs-target="#delete<?= $qrCode['idqrcode'] ?>"
                                        name="delete" type="button" class="delete">Delete</button>
                                </form>

                                <div class="dropdown mt-2">
                                    <button class="btn btn-success dropdown-toggle w-50 rounded-0" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Save as
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form action="saveqr.php" method="post">
                                                <input type="hidden" value="<?= $qrCode['idqrcode'] ?>" name="idqrcode">
                                                <input type="hidden" value="<?= $_SESSION['id'] ?>" name="userid">
                                                <input type="hidden" value="<?= $qrCode['qrbody'] ?>" name="qrbody">
                                                <button class="dropdown-item" name="formatPNG" type="submit"
                                                    value="png">PNG</button>
                                            </form>

                                        </li>
                                        <li>
                                            <form action="saveqr.php" method="post">
                                                <input type="hidden" value="<?= $qrCode['idqrcode'] ?>" name="idqrcode">
                                                <input type="hidden" value="<?= $_SESSION['id'] ?>" name="userid">
                                                <input type="hidden" value="<?= $qrCode['qrbody'] ?>" name="qrbody">
                                                <button class="dropdown-item" name="formatSVG" type="submit"
                                                    value="svg">SVG</button>
                                            </form>

                                        </li>

                                    </ul>
                                </div>

                            </div>
                        </div>

                        <div class="modal fade" id="modal<?= $qrCode['idqrcode'] ?>" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Change QR:</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="qrupdate.php">
                                            <label class="mb-2 w-100" name="qrname">QR Name:</label>
                                            <input class="mb-2 w-100" value="<?= $qrCode['qrname'] ?>" name="qrname">
                                            <label class="mb-2 w-100" name="qrbody">QR Link:</label>
                                            <input class="mb-2 w-100" value="<?= $qrCode['qrbody'] ?>" name="qrbody">
                                            <input type="hidden" value="<?= $qrCode['idqrcode'] ?>" name="idqrcode">
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary w-100">Save
                                                </button>
                                                <button type="button" class="btn btn-secondary w-100"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="delete<?= $qrCode['idqrcode'] ?>" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Delete</h5>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this QR?</p>
                                </div>
                                <div class="modal-footer">
                                    <form method="POST" action="qrdelete.php">
                                        <button type="submit" class="btn btn-primary">Confirm</button>
                                        <input type="hidden" value="<?= $qrCode['idqrcode'] ?>" name="deleteid">
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <div>
        <a href="qr-generator.view.php" class="create">Create new</a>
    </div>

    <div>
        <a href="logout.php" class="a button">Logout</a>
    </div>

    <script src="styling/bootstrap.bundle.min.js"></script>

</body>

</html>