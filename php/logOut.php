<?php
    // Avvio la sessione
    session_start();

    // Modifico le variabili
    $_SESSION["accessoEseguito"] = "false";
    $_SESSION["contoCorrenteID"] = 0;

    // Rimuovo tutte le variabili di sessione
    session_unset();
    
    // Distruggo la sessione
    session_destroy();

    // Reinderizzo al login
    header("Location: https://gruppo6.altervista.org/ProjectWork/php/homepage.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogOut</title>
</head>
<body>
    <h1>Logout in corso...</h1>
    <h2>Attendere senza fare nulla. Non chiudere questa pagina.</h2>
</body>
</html>