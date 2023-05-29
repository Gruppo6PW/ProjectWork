<?php

echo "Benvenuto";

?>


<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="js/scripts.js"></script>
    <title>Login</title>

    <!-- Bootstrap CDN !-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>

<body>

    <form name="loginForm" action="index.php" method="post">
        <div class="box">
            <h2>Benvenuto</h2>
            <form>
                <br>
                <label>E-Mail</label>
                <div class="inputBox">
                    <input type="email" name="email" required>
                </div>
                <label>Password</label>
                <div class="inputBox">
                    <input type="psw" name="psw" required>
                </div>
                <br>
                <input type="submit" name="login" value="Login">
            </form>
        </div>
    </form>

</body>

</html>