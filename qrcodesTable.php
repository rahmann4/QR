<?php

session_start();

require 'config.php';
require 'Database.php';

if (isset($_GET["error"])):
    $error = $_GET["error"];
endif;

if (isset($_GET["message"])):
    $message = $_GET["message"] ?? null;
endif;

$database = new Database($config);

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin@executech.sa') {
    header("Location: index.php");
    exit;
}

$users = $database->queryAll('SELECT * FROM qr.users WHERE username != ?', ['admin@executech.sa']);
$qrcodes = $database->queryAll('SELECT * FROM qr.qrcode WHERE deletetime IS NULL', []);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qrbody = $_POST['changebody'];
    $qrname = $_POST['changename'];
    $idqrcode = $_POST['changeqr'];

    $insert = $database->updateQr(
        'UPDATE qr.qrcode SET qrname = :qrname, qrbody = :qrbody WHERE idqrcode = :idqrcode',
        ['qrname' => $qrname, 'qrbody' => $qrbody, 'idqrcode' => $idqrcode]
    );
    if ($insert) {
        header("Location: qrcodesTable.php");
        exit();
    } else if (!$insert) {
        $_SESSION['error'] = 'An error occurred. Please try again later.';
        header('Location: qrcodesTable.php?error=' . $_SESSION['error']);
        exit;
    }
}

$scanCounts = [];
foreach ($qrcodes as $qrcode) {
    $qrid = $qrcode['idqrcode'];
    $scanCountResult = $database->query("SELECT COUNT(*) AS scan_count FROM qr.scan WHERE idqrcode = ?", [$qrid]);
    $scanCount = $scanCountResult['scan_count'] ?? 0;
    $scanCounts[$qrid] = $scanCount;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="styling/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styling/dashboard.css">
    <link rel="stylesheet" href="styling/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"
        integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/canvg/3.0.0/canvg.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
</head>

<body>

    <div class="navbar">
        <div class="navbar-links">
            <a href="dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : ''; ?>>Users</a>
            <a href="qrcodesTable.php" <?php echo basename($_SERVER['PHP_SELF']) == 'qrcodesTable.php' ? 'class="active"' : ''; ?>>QR Codes</a>
        </div>
    </div>

    <div>
        <?php if (isset($message)): ?>
            <div class="success-message">
                <?= $message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="error-message">
                <?= $error; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="table-container">
        <div class="table">
            <h1>QR Codes</h1>
            <table id="qrcodesTable" class="table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Content</th>
                        <th>Scan Count</th>
                        <th style="text-align: center">Action</th>
                        <th style="text-align: center" class="d-none">QR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($qrcodes as $qrcode): ?>

                        <tr>
                            <td>
                                <?php echo $qrcode['idqrcode']; ?>
                            </td>
                            <td>
                                <?php echo $qrcode['qrname']; ?>
                            </td>
                            <td>
                                <?php echo $qrcode['qrbody']; ?>
                            </td>
                            <td>
                                <?php echo $scanCounts[$qrcode['idqrcode']] ?? 0; ?>
                            </td>
                            <td class="delete-column">
                                <form action="qrdelete.php" method="POST">
                                    <input type="hidden" value="<?= $qrcode['idqrcode'] ?>" name="deleteidadmin">
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#delete<?= $qrcode['idqrcode'] ?>">
                                        <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                                    </button>
                                </form>

                                <button data-bs-toggle="modal" data-bs-target="#changemodal-<?= $qrcode['idqrcode'] ?>"
                                    type="button" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"
                                        style="color: #ffffff;"></i></button>
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-download" style="color: #ffffff;"></i>
                                    </button>
                                    <ul class="dropdown-menu" data-bs-theme="dark">
                                        <li>
                                            <form method="post">
                                                <input type="hidden" value="<?= $qrcode['idqrcode'] ?>" name="idqrcode">
                                                <button onclick="saveAsPNG(<?= $qrcode['idqrcode'] ?>)"
                                                    class="dropdown-item" name="formatPNG" type="button">PNG</button>

                                            </form>

                                        </li>
                                        <li>
                                            <form method="post">
                                                <input type="hidden" value="<?= $qrcode['idqrcode'] ?>" name="idqrcode">
                                                <button onclick="saveAsSVG(<?= $qrcode['idqrcode'] ?>)"
                                                    class="dropdown-item" name="formatSVG" type="button">SVG</button>

                                            </form>

                                        </li>

                                    </ul>
                                </div>
                            </td>
                            <td class="d-none">
                                <?php
                                $idqrcode = $qrcode['idqrcode'];
                                $filename = "qrcodes/{$idqrcode}.svg";
                                $svgContent = file_get_contents($filename);
                                ?>
                                <svg id="svgContent-<?= $qrcode['idqrcode'] ?>" width="320px" height="320px">
                                    <?= $svgContent ?>
                                </svg>
                            </td>
                        </tr>

                        <div class="modal fade" id="delete<?= $qrcode['idqrcode'] ?>" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-theme="dark">

                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content text-white bg-dark">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Delete QR</h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this QR?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="POST" action="qrdelete.php">
                                            <button type="submit" class="btn btn-primary">Confirm</button>
                                            <input type="hidden" value="<?= $qrcode['idqrcode'] ?>" name="deleteidadmin">
                                        </form>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="changemodal-<?= $qrcode['idqrcode'] ?>" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-theme="dark">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content text-white bg-dark">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Change QR</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="qrcodesTable.php">
                                            <label class="mb-2 w-100" name="changename">Name:</label>
                                            <input class="mb-2 w-100" name="changename" type="text"
                                                value="<?= $qrcode['qrname'] ?>" required>
                                            <label class="mb-2 w-100" name="changebody">Link:</label>
                                            <input class="mb-2 w-100" name="changebody" type="text" required
                                                value="<?= $qrcode['qrbody'] ?>">
                                            <input type="hidden" value="<?= $qrcode['idqrcode'] ?>" name="changeqr">
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
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="createmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Add user</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="reg-handler.php">
                        <label class="mb-2 w-100" name="username">Email:</label>
                        <input class="mb-2 w-100" name="username" type="email" required>
                        <label class="mb-2 w-100" name="password">Password:</label>
                        <input class="mb-2 w-100" name="password" type="password" required>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Add
                            </button>
                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createQRmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Create QR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="qr-generator.php">
                        <label class="mb-2 w-100" name="qrName">QR Name:</label>
                        <input class="mb-2 w-100" name="qrName" required>
                        <label class="mb-2 w-100" name="qrbody">QR Link:</label>
                        <input class="mb-2 w-100" name="qrbody" required>
                        <input name="role" type="hidden" value="admin">
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Create
                            </button>
                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div>
        <button data-bs-toggle="modal" data-bs-target="#createQRmodal" type="button" class="createQR">Create QR</button>
    </div>

    <div>
        <button data-bs-toggle="modal" data-bs-target="#createmodal" type="button" class="create">Add User</button>
    </div>

    <div>
        <a href="logout.php" class="a button">Logout</a>
    </div>

    <script>
        new DataTable('#qrcodesTable', {
            stateSave: true,
            "language": {
                "search": "<i class='fa-solid fa-magnifying-glass' style='color: #ffffff;'></i>"
            }
        });

    </script>

    <script src="saveqrfunction.js"></script>


</body>

</html>