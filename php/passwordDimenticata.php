<?php
    // Controllo se l'email è valida
    function controllaRequisitiEmail($stringaDaControllare){
        $emailRegex = "/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/";
        // Controllo se la email rispetta questi parametri
        if (preg_match($emailRegex, $stringaDaControllare) == 1) {
            return true;
        } else{
            echo ("<a href='http://gruppo6.altervista.org/ProjectWork/passwordDimenticata.php'>Torna alla pagina per la password dimenticata</a>");
            return false;
        }
    }

    if(isset($_POST["Invia"])){
        // Prendi il valore
        $email = $_POST["email"];

        // Controllo che non siano vuote e chi siano stringhe
        if(!empty($email) && is_string($email) && controllaRequisitiEmail($email)){
            // Non vuota e stringa valida

            // Mi connetto al db
            $conn = mysqli_connect('localhost', "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
                    
            // Controllo che la connessione sia andata buon fine, altrimenti mostro l'errore
            if ($conn->connect_error) {
                die("Connessione fallita: " . $conn->connect_error);
            }

            // Genero un token
            $testoRandom = md5($email);    // Genero un hash MD5 per rendere il token univoco
            $token = uniqid() . '_' . $testoRandom;

            // Aggiorno il token del db
            $SQL = "UPDATE tconticorrenti SET Token = ? WHERE tconticorrenti.Email = ?";
            if($statement = $conn -> prepare($SQL)){
                $statement -> bind_param("ss", $token, $email);
                $statement -> execute();
        
                // Chiudo lo statement
                $statement->close();
            } else{
                // C'è stato un errore, lo stampo
                $errore = $mysqli->errno . ' ' . $mysqli->error;
                echo $errore;
            }
            
            // Chiudo la connessione al db
            $conn->close();

            // Invio la mail
            $msg = "Clicca <a href='http://gruppo6.altervista.org/ProjectWork/php/reimpostaPassword.php?token=$token'>qui</a> per reimpostare la tua password";   // Sostutuire con il proprio dominio di altervista
            $msg = wordwrap($msg,70);   // Necessario sopra i 50 caratteri
            $specificheHtml = "MIME-Version: 1.0" . "\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n";
            mail("$email", "Reimposta Password - Project Work", $msg, $specificheHtml);

            // Creo una variabile per modificare l'html visualizzato
            $html = "
            <h2>Ti è stata inviata una email contenente un link per reimpostare la password</h2>
            ";

        } else {
            echo ("<h2>L'email non è valida</h2>");
            return;
        }
    } else{
        // Carica html della form
        $html = "
        <h1>Inserisci la tua mail, in modo da ricevere il link per reimpostare la password</h1>

        <form action='' name='formPasswordDimenticata' method='POST'>
            <label for='email'>Email:</label>
            <input type='email' name='email' id='emailID'>
    
            <br>
    
            <input type='submit' value='Invia' name='Invia' onclick='controllaInput()'>
        </form>
        ";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Dimenticata</title>
</head>
<body>
    <!-- HTML -->
    <?php echo $html; // Si adatta ad ogni caricamento ?>
</body>
</html>