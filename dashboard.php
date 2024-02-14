<?php
session_start();

if (isset($_GET["error"])):
    $error = $_GET["error"];
endif;

if (isset($_GET["message"])):
    $message = $_GET["message"] ?? null;
endif;

require 'config.php';
require 'Database.php';

$database = new Database($config);

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin@gmail.com') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $database = new Database($config);
    $delete = $database->delete(
        'UPDATE qr.users SET deletetime = NOW() WHERE username = :username',
        ['username' => $username]
    );

    if ($delete) {
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = 'An error occurred. Please try again later.';
    }
    header('Location: dashboard.php?error=' . $_SESSION['error']);
    exit;
}

$users = $database->queryAll('SELECT * FROM qr.users WHERE username != ? AND deletetime IS NULL', ['admin@gmail.com']);
$qrcodes = $database->queryAll('SELECT * FROM qr.qrcode WHERE deletetime IS NULL', []);

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
    <style>

    </style>
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
            <h1>Users</h1>
            <table id="usersTable" class="table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <?php echo $user['username']; ?>
                            </td>
                            <td class="delete-column">
                                <form action="dashboard.php" method="POST">
                                    <input type="hidden" value="<?= $user['username'] ?>" name="username">
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal-<?= $user['idusers'] ?>">
                                        <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="deleteModal-<?= $user['idusers'] ?>" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-theme="dark">

                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content text-white bg-dark">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Delete User</h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this user?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="POST" action="dashboard.php">
                                            <button type="submit" class="btn btn-primary">Confirm</button>
                                            <input type="hidden" value="<?= $user['username'] ?>" name="username">
                                        </form>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
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

    <div>
        <button data-bs-toggle="modal" data-bs-target="#createmodal" type="button" class="create">Add User</button>
    </div>

    <div>
        <a href="logout.php" class="a button">Logout</a>
    </div>

    <script>
        new DataTable('#usersTable', {
            stateSave: true,
            "language": {
                "search": "<i class='fa-solid fa-magnifying-glass' style='color: #ffffff;'></i>"
            }
        });
    </script>


</body>

</html>