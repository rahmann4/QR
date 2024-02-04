<!doctype html>
<html lang="en"> 

<head> 
<meta charset="UTF-8"> 
<title>Login</title> 
</head> 

<body>
    <form method="POST" action="login-handler.php">
    <label for="username">Username:</label>
    <div>
        <input id="username" name="username">
    </div>

    <label for="password">Password:</label>
    <div>
        <input id="password" name="password">
    </div>

    <button type="submit">Login</button>

    </form>
</body>

</html>