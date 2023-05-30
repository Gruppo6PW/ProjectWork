<?php
    // Controllo se la password contiene almeno 1 maiuscola, 1 minuscola, 1 numero, 1 carattere speciale e se è lunga almeno 8 caratteri
    function controllaRequisitiPassword($stringaDaControllare){
        $passwordRegex = "/^(?=\P{Ll}*\p{Ll})(?=\P{Lu}*\p{Lu})(?=\P{N}*\p{N})(?=[\p{L}\p{N}]*[^\p{L}\p{N}])[\s\S]{8,}$/";
        // Controllo se la password rispetta questi parametri
        if (preg_match($passwordRegex, $stringaDaControllare) == 1) {
            return true;
        } else{
            echo ("<a href='http://gruppo6.altervista.org/ProjectWork/passwordDimenticata.php'>Torna alla pagina registrazione</a>");
            return false;
        }
    }
    
    if(isset($_POST["Imposta"])){
        // Prendo i valori
        $nuovaPassword = $_POST["passwordNuova"];
        $confermaNuovaPassword = $_POST["confermaPasswordNuova"];

        if(!empty($nuovaPassword) && is_string($nuovaPassword) && controllaRequisitiPassword($nuovaPassword)){
            // Non vuota e stringa
            if(!empty($confermaNuovaPassword) && is_string($confermaNuovaPassword) && controllaRequisitiPassword($confermaNuovaPassword)){
                // Non vuota e stringa

                // Mi connetto al db
                $conn = mysqli_connect('localhost', "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
                    
                // Controllo che la connessione sia andata buon fine, altrimenti mostro l'errore
                if ($conn->connect_error) {
                    die("Connessione fallita: " . $conn->connect_error);
                }

                    // Hasho la nuova password
                $salt = "sdfsd89fysd89fhjsr23rfjvsdv";
                $nuovaPasswordCriptata = crypt($nuovaPassword, $salt);

                // Creo la query di update
                $SQL = "UPDATE tconticorrenti SET Password = ? WHERE tconticorrenti.ContoCorrenteID = ?";
                if($statement = $conn -> prepare($SQL)){
                    $statement -> bind_param("ss", $passwordNuovaCriptata, $email);
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

                // Reinderizzo alla pgina di login
            } else {
                echo ("<h2>La nuova password non è valida</h2>");
                return;
            }
        } else {
            echo ("<h2>La conferma password non è valida</h2>");
            return;
        }
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
    <!-- JS -->
    <script>
        function controllaInput() {
            // Prendo i valori
            passwordNuova = formPasswordDimenticata.passwordNuovaID.value;
            confermaPasswordNuova = formPasswordDimenticata.confermaPasswordNuovaID.value;

            // Controllo che la passwordCorrente non sia vuota e sia string
            if ((!passwordNuova.isEmpty() && (typeof passwordNuova === 'string' || passwordNuova instanceof String) && controllaRequisitiPassword(passwordNuova))) {
                // Non vuota e stringa

                // Controllo che password non sia vuota e sia string
                if ((!confermaPasswordNuova.isEmpty() && (typeof confermaPasswordNuova === 'string' || confermaPasswordNuova instanceof String) && controllaRequisitiPassword(confermaPasswordNuova))) {
                    // Non vuota e stringa

                    // Controllo se le password sono uguali
                    if (passwordNuova == confermaPasswordNuova) {
                        // Uguali

                        // Tutto ok, invio
                        formPasswordDimenticata.submit(); // Invio il submit
                    } else {
                        alert("Le password non corrispondono");

                        // Cancello gli input
                        document.getElementById('passwordNuovaID').value = '';
                        document.getElementById('confermaPasswordNuovaID').value = '';
                        return false;
                    }
                } else {
                    alert("La password nuova deve valida");

                    // Cancello l'input
                    document.getElementById('passwordNuovaID').value = '';
                    return false;
                }
            } else {
                alert("La conferma della nuova password deve valida");

                // Cancello l'input
                document.getElementById('confermaPasswordNuovaID').value = '';
                return false;
            }
        }

        // Controllo se la password contiene almeno 1 maiuscola, 1 minuscola, 1 numero, 1 carattere speciale e se è lunga almeno 8 caratteri
        function controllaRequisitiPassword(stringaDaControllare) {
            passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s:])([^\s]){8,}$/;
            // Controllo se la password rispetta questi parametri
            if (passwordRegex.test(stringaDaControllare)) {
                return true;
            } else {
                return false;
            }
        }
    </script>

    <!-- HTML -->
    <form action="" name="formPasswordDimenticata" method="POST">
        <label for="passwordNuovaID">Nuova password:</label>
        <input type="password" name="passwordNuova" id="passwordNuovaID">

        <br>

        <label for="confermaPasswordNuovaID">Conferma nuova password:</label>
        <input type="password" name="confermaPasswordNuova" id="confermaPasswordNuovaID">

        <br>

        <input type="submit" value="Imposta" name="Imposta" onclick="controllaInput()">
    </form>
</body>
</html>