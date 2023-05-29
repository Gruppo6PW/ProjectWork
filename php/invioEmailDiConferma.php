<!-- PHP -->
<?php
    $email = $_GET["email"];
    $token = $_GET["token"];

    // Mando la mail di conferma
    $msg = "Clicca sul link per confermare la mail: http://gruppo6.altervista.org/ProjectWork/attivaAccount.php?token=$token";   // Sostutuire con il proprio dominio di altervista
    $msg = wordwrap($msg,70);   // Necessario sopra i 50 caratteri
    mail("$email", "Conferma Registrazione - Project Work", $msg);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvenuto</title>
</head>
<!-- HTML -->
<body>
    <h1>Benvenuto</h1>

    <p>Ti è stata inviata una mail contenente un link di conferma. Cliccalo e accedi al servizio. <br>
       Puoi pure chiudere questa pagina.
    </p>
</body>
</html>