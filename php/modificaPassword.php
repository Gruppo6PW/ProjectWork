<?php
    // Controllo se la password contiene almeno 1 maiuscola, 1 minuscola, 1 numero, 1 carattere speciale e se è lunga almeno 8 caratteri
    function controllaRequisitiPassword($stringaDaControllare){
        $passwordRegex = "/^(?=\P{Ll}*\p{Ll})(?=\P{Lu}*\p{Lu})(?=\P{N}*\p{N})(?=[\p{L}\p{N}]*[^\p{L}\p{N}])[\s\S]{8,}$/";
        // Controllo se la password rispetta questi parametri
        if (preg_match($passwordRegex, $stringaDaControllare) == 1) {
            return true;
        } else{
            echo ("<a href='http://gruppo6.altervista.org/ProjectWork/registrazione.php'>Torna alla pagina registrazione</a>");
            return false;
        }
    }

    if(isset($_POST["Modifica"])){
        // Prendo i valori inviati dalla pagina
        $passwordCorrente = $_POST["passwordCorrente"];
        $passwordNuova = $_POST["passwordNuova"];
        $confermaPasswordNuova = $_POST["confermaPasswordNuova"];

        // Prendo la mail dalla sessione
    
        // Controllo che non siano vuote e chi siano stringhe
        if(!empty($passwordCorrente) && is_string($passwordCorrente) && controllaRequisitiPassword($passwordCorrente)){
            // Non vuota e stringa
            if(!empty($passwordNuova) && is_string($passwordNuova) && controllaRequisitiPassword($passwordNuova)){
                // Non vuota e stringa
                if(!empty($confermaPasswordNuova) && is_string($confermaPasswordNuova) && controllaRequisitiPassword($confermaPasswordNuova)){
                    // Non vuota e stringa
                    
                    // Mi connetto al db
                    $conn = mysqli_connect('localhost', "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
                    
                    // Controllo che la connessione sia andata buon fine, altrimenti mostro l'errore
                    if ($conn->connect_error) {
                        die("Connessione fallita: " . $conn->connect_error);
                    }

                        // Controllo che la passwordCorrente coincida con quella salvata nel db
                    $salt = "sdfsd89fysd89fhjsr23rfjvsdv";
                    $passwordCorrenteCriptata = crypt($passwordCorrente, $salt);

                    // Creo la query di confronto
                    $SQL = "SELECT ContoCorrenteID FROM tconticorrenti WHERE Email = ? AND Password = ? LIMIT 1";
                    if($statement = $conn -> prepare($SQL)){
                        $statement -> bind_param("ss", $email, $passwordCorrenteCriptata);
                        $statement -> execute();
                        
                        // Prendo il risultato della query
                        $result = $statement->get_result();

                        if ($result->num_rows == 0) {
                            // C'è una tupla. Password valida
                            echo("<h2>Password attuale errata</h2>");
                            return;
                        }

                        // Salvo il contenuto del result
                        while ($row = $result->fetch_assoc()) {
                            // Prendo l'id (è gia int)
                            $id = $row["ContoCorrenteID"];
                        }

                        // Chiudo lo statement
                        $statement->close();
                    } else{
                        // C'è stato un errore, lo stampo
                        $errore = $mysqli->errno . ' ' . $mysqli->error;
                        echo $errore;
                    }

                    // Hasho la nuova password
                    $passwordNuovaCriptata = crypt($passwordNuova, $salt);

                    // Procedo alla modifica della password
                    $SQL = "UPDATE tconticorrenti SET Password = ? WHERE tconticorrenti.ContoCorrenteID = $id";
                    if($statement = $conn -> prepare($SQL)){
                        $statement -> bind_param("s", $passwordNuovaCriptata);
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

                    // Reinderizzo alla pagina del profilo
                    header("Location: http://gruppo6.altervista.org/ProjectWork/php/profiloUtente.php");
                    
                } else {
                    echo ("<h2>La conferma della nuova password non è valida</h2>");
                    return;
                }
            } else {
                echo ("<h2>La nuova password non è valida</h2>");
                return;
            }
        } else {
            echo ("<h2>La password corrente non è valida</h2>");
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
    <title>Modifica Password</title>
</head>
<body>
    <!-- JS -->
    <script>
        function controllaInput() {
            // Prendo i valori
            passwordCorrente = formModificaPassword.passwordCorrenteID.value;
            passwordNuova = formModificaPassword.passwordNuovaID.value;
            confermaPasswordNuova = formModificaPassword.confermaPasswordNuovaID.value;

            // Controllo che la passwordCorrente non sia vuota e sia string
            if ((!passwordCorrente.isEmpty() && (typeof passwordCorrente === 'string' || passwordCorrente instanceof String) && controllaRequisitiPassword(passwordCorrente))) {
                // Non vuota e stringa

                // Controllo che password non sia vuota e sia string
                if ((!passwordNuova.isEmpty() && (typeof passwordNuova === 'string' || passwordNuova instanceof String) && controllaRequisitiPassword(passwordNuova))) {
                    // Non vuota e stringa

                    // Controllo che confermaPassword non sia vuota e sia string
                    if ((!confermaPasswordNuova.isEmpty() && (typeof confermaPasswordNuova === 'string' || confermaPasswordNuova instanceof String) && controllaRequisitiPassword(confermaPasswordNuova))) {
                        // Non vuota e stringa

                        // Controllo se le password sono uguali
                        if (passwordNuova == confermaPasswordNuova) {
                            // Uguali

                            // Tutto ok, invio
                            formModificaPassword.submit(); // Invio il submit
                        } else {
                            alert("Le password non corrispondono");

                            // Cancello gli input
                            document.getElementById('passwordNuovaID').value = '';
                            document.getElementById('confermaPasswordNuovaID').value = '';
                            return false;
                        }
                    } else {
                        alert("La  conferma password deve essere valida");

                        // Cancello l'input
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
                alert("La password attuale deve essere valida");

                // Cancello l'input
                document.getElementById('passwordCorrenteID').value = '';
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
    <form action="" method="post" name="formModificaPassword">
        <label for="passwordCorrenteID">Password attuale:</label>
        <input type="password" name="passwordCorrente" id="passwordCorrenteID">

        <br>

        <label for="passwordNuovaID">Nuova password:</label>
        <input type="password" name="passwordNuova" id="passwordNuovaID">

        <br>

        <label for="confermaPasswordNuovaID">Conferma nuova password:</label>
        <input type="password" name="confermaPasswordNuova" id="confermaPasswordNuovaID">

        <br>

        <input type="submit" name="Modifica" value="Modifica">
    </form>
</body>
</html>