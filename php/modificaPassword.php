<?php
    // Controllo se la password contiene almeno 1 maiuscola, 1 minuscola, 1 numero, 1 carattere speciale e se è lunga almeno 8 caratteri
    function controllaRequisitiPassword($stringaDaControllare){
        $passwordRegex = "/^(?=\P{Ll}*\p{Ll})(?=\P{Lu}*\p{Lu})(?=\P{N}*\p{N})(?=[\p{L}\p{N}]*[^\p{L}\p{N}])[\s\S]{8,}$/";
        // Controllo se la password rispetta questi parametri
        if (preg_match($passwordRegex, $stringaDaControllare) == 1) {
            return true;
        } else{
            return false;
        }
    }

    if(isset($_POST["Modifica"])){
        // Prendo i valori inviati dalla pagina
        $passwordCorrente = $_POST["passwordCorrente"];
        $passwordNuova = $_POST["passwordNuova"];
        $confermaPasswordNuova = $_POST["confermaPasswordNuova"];

        $contoCorrenteID = $_GET["contoCorrenteID"];

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

                    // Hasho la nuova password
                    $passwordNuovaCriptata = hash("sha512", $password);

                    // Procedo alla modifica della password
                    $SQL = "UPDATE tconticorrenti SET Password = ? WHERE tconticorrenti.ContoCorrenteID = ?";
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
                    header("Location: https://gruppo6.altervista.org/ProjectWork/php/profilo.php?contoCorrenteID=<?php echo $contoCorrenteID ?>");
                    
                } else {
                    echo ("<h2>La conferma della nuova password non è valcontoCorrenteIDa</h2>");
                    return;
                }
            } else {
                echo ("<h2>La nuova password non è valcontoCorrenteIDa</h2>");
                return;
            }
        } else {
            echo ("<h2>La password corrente non è valcontoCorrenteIDa</h2>");
            return;
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="wcontoCorrenteIDth=device-wcontoCorrenteIDth, initial-scale=1">
        <title>Modifica Password</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
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
                            alert("La  conferma password deve essere valcontoCorrenteIDa");

                            // Cancello l'input
                            document.getElementById('confermaPasswordNuovaID').value = '';
                            return false;
                        }
                    } else {
                        alert("La password nuova deve valcontoCorrenteIDa");

                        // Cancello l'input
                        document.getElementById('passwordNuovaID').value = '';
                        return false;
                    }
                } else {
                    alert("La password attuale deve essere valcontoCorrenteIDa");

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
        <div class="registration-form">
            <form>
                <div class="form-icon">
                    <!-- Codice per l'icona SVG  -->
                            <span>
                        <svg xmlns='http://www.w3.org/2000/svg' height='70' width='70' fill='#dee9ff' class='bi bi-key-fill' viewBox='-1 0 17 9'>
                            <path d='M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z'/>
                        </svg>
                    </span>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control item" contoCorrenteID="passwordCorrenteID" name="passwordCorrente" placeholder="Password corrente" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control item" contoCorrenteID="passwordNuovaID" name="passwordNuova" placeholder="Nuova password" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control item" contoCorrenteID="confermaPasswordNuovaID" name="confermaPasswordNuova" placeholder="Conferma password" required>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block create-account" name="Modifica" onclick="controllaInput()" value="Modifica password">
                </div>
            </form>

            <div class="social-media">
                <a href="http://gruppo6.altervista.org/ProjectWork/php/profilo.php?contoCorrenteID=<?php echo $contoCorrenteID ?>">Cliccato per errore? Torna alla pagina del profilo senza fare modifiche</a>
            </div>
        </div>
                    
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
        <script src="assets/js/script.js"></script>
    </body>
</html>