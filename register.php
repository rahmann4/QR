<?php

session_start();

if (isset($_GET["error"])):
    $error = $_GET["error"];
endif;

if (isset($_GET["message"])):
    $message = $_GET["message"] ?? null;
endif;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="/styling/login.css">
</head>

<body>
    <section>
        <div class="signin">
            <div class="content">
                <h2>Register</h2>
                <form action="reg-handler.php" method="POST" class="form">
                    <div class="inputBox">
                        <input name="username" id="username" type="email" required> <i>Email</i>
                    </div>

                    <div class="inputBox">
                        <input name="password" id="password" type="password" required> <i>Password</i>
                    </div>

                    <div class="inputBox">
                        <input type="submit" value="Register">
                    </div>

                    <?php if (isset($message)): ?>
                        <div class="success-message">
                            <?= $message; ?>
                        </div>
                    <?php endif; ?>

                </form>

                <div>
                    <a class="button" href="index.php">Login</a>
                </div>
            </div>
        </div>
    </section>

</body>

</html>