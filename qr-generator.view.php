<?php
session_start();
if (!isset($_SESSION["username"])):
    header("Location: index.php");
endif;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styling/bootstrap.min.css">
    <title>QR</title>
    <link rel="stylesheet" href="styling/qr.css">
</head>

<body>
    <form method="POST" action="qr-generator.php">
        <label for="qrName">QR name</label>

        <div>
            <input id="qrName" name="qrName" required>
        </div>

        <label for="qrbody">QR link</label>

        <div>
            <textarea id="qrbody" name="qrbody" required></textarea>
        </div>

        <div>
            <button type="submit">Generate</button>
        </div>

        <div>
            <a href="qr-page.php" class="cancel">Cancel</a>

        </div>
    </form>

    <div>
        <a href="logout.php" class="a button">Logout</a>
    </div>

</body>

</html>