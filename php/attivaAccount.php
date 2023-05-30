<!-- PHP -->
<?php
    // Prendo il token
    $token = $_GET["token"];

     // Mi connetto al db
     $conn = mysqli_connect('localhost', "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
                
     // Controllo che la connessione sia andata buon fine, altrimenti mostro l'errore
     if ($conn->connect_error) {
         die("Connessione fallita: " . $conn->connect_error);
     }

    // Faccio la query per il controllo del token
    $SQL = "SELECT ContoCorrenteID FROM tconticorrente WHERE Token=?";
    if($statement -> $conn -> prepare($SQL)){
        $statement -> bind_param("s", $token);
        $statement -> execute();

        // Prendo il risultato della query
        $result = $statement->get_result();

        // Salvo l'id
        $id = int()$result -> fetch_assoc();

        // Chiudo lo statement
        $statement->close();
    } else{
        // C'è stato un errore, lo stampo
        $errore = $mysqli->errno . ' ' . $mysqli->error;
        echo $errore;
    }

    // Modifico il campo RegistrazioneConfermata
    $registrazioneConfermata = 1;
    $SQL = "UPDATE tconticorrenti SET RegistrazioneConfermata = ? WHERE tconticorrenti.ContoCorrenteID = ?";
    if($statement -> $conn -> prepare($SQL)){
        $statement -> bind_param("ii", $registrazioneConfermata, $id);
        $statement -> execute();

        // Prendo il risultato della query
        $result = $statement->get_result();

        // Chiudo lo statement
        $statement->close();
    } else{
        // C'è stato un errore, lo stampo
        $errore = $mysqli->errno . ' ' . $mysqli->error;
        echo $errore;
    }

    // Chiudo la connessione al db
    $conn->close();

    // Reinderizzo al login
    header("Location: http://gruppo6.altervista.org/ProjectWork/php/login.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attiva Account</title>
</head>
<body>
    <h2>Attivazione account in corso...</h2>
    <h3>Attendi, il processo sarà automatico</h3>
</body>
</html>