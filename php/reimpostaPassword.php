<?php
    // Controllo se la password contiene almeno 1 maiuscola, 1 minuscola, 1 numero, 1 carattere speciale e se è lunga almeno 8 caratteri
    function controllaRequisitiPassword($stringaDaControllare){
        $passwordRegex = "/^(?=\P{Ll}*\p{Ll})(?=\P{Lu}*\p{Lu})(?=\P{N}*\p{N})(?=[\p{L}\p{N}]*[^\p{L}\p{N}])[\s\S]{8,}$/";
        // Controllo se la password rispetta questi parametri
        if (preg_match($passwordRegex, $stringaDaControllare) == 1) {
            return true;
        } else{
            echo ("<a href='http://gruppo6.altervista.org/ProjectWork/reimpostaPassword.php'>Torna alla pagina registrazione</a>");
            return false;
        }
    }
    
    if(isset($_POST["Imposta"])){
        // Prendo i valori
        $token = $_GET["token"];
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

                    // Controllo che il token sia giusto
                $SQL = "SELECT ContoCorrenteID FROM tconticorrenti WHERE Token=?";
                if($statement=$conn->prepare($SQL)){
                    $statement -> bind_param("s", $token);
                    $statement -> execute();
            
                    // Prendo l'output della query e li salvo in result
                    $result = $statement -> get_result();
            
                    if ($result->num_rows == 0) {
                        // C'è una tupla. Password valida
                        echo("<h2>Token errato</h2>");
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
                    $errore = $conn->errno . ' ' . $conn->error;
                    echo $errore;
                }

                    // Hasho la nuova password
                $nuovaPasswordCriptata = hash("sha512", $password);

                // Creo la query di update
                $SQL = "UPDATE tconticorrenti SET Password = ? WHERE tconticorrenti.ContoCorrenteID = ?";
                if($statement = $conn -> prepare($SQL)){
                    $statement -> bind_param("si", $nuovaPasswordCriptata, $id);
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

                // Reinderizzo alla pagina di login
                header("Location: https://gruppo6.altervista.org/ProjectWork/php/login.php");
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
        <title>Reimposta Password</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="styleNoSocial.css">
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
        <div class="registration-form">
            <form name="loginForm" method="POST">
                <div class="form-icon">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="70" width="70" fill="#dee9ff" class="bi bi-key-fill" viewBox="-1 0 17 9 ">
                            <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                        </svg>
                    </span>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control item" name="passwordNuova" id="passwordNuovaID" placeholder="Nuova password">
                </div>

                <div class="form-group">
                    <input type="password" class="form-control item" name="confermaPasswordNuova" id="confermaPasswordNuovaID"
                        placeholder="Conferma password">
                </div>

                <div class="text-center">
                    <div class="g-recaptcha" data-sitekey="6Lc0L0wmAAAAAHIusv0dCKOV9a4msMJLD516RB1r"></div>
                </div>

                <div class="form-group">
                    <button type="submit" name="Imposta" class="btn btn-block create-account" onclick=tentativiInserimentoCredenziali()>Imposta nuova password</button>
                </div>
            </form>
        </div>
    
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
        <script src="assets/js/script.js"></script>
    </body>
</html>