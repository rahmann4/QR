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
    <title>Login</title>
    <link rel="stylesheet" href="/styling/login.css">
</head>

<body>

    <section>
        <div class="signin">
            <div class="content">
                <h2>Sign In</h2>
                <form action="login-handler.php" method="POST" class="form">
                    <div class="inputBox">
                        <input name="username" id="username" type="text" required> <i>Username</i>
                    </div>

                    <div class="inputBox">
                        <input name="password" id="password" type="password" required> <i>Password</i>
                    </div>

                    <div class="inputBox">
                        <input type="submit" value="Login">
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="error-message">
                            <?= $error; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($message)): ?>
                        <div class="success-message">
                            <?= $message; ?>
                        </div>
                    <?php endif; ?>

                </form>

                <div>
                    <a class="button" href="register.php">Register</a>
                </div>
            </div>
        </div>
    </section>

</body>

</html>