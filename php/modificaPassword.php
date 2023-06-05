<?php
    // Controllo se la password contiene almeno 1 maiuscola, 1 minuscola, 1 numero, 1 carattere speciale e se è lunga almeno 8 caratteri
    function controllaRequisitiPassword($stringaDaControllare){
        $passwordRegex = "/^(?=\P{Ll}*\p{Ll})(?=\P{Lu}*\p{Lu})(?=\P{N}*\p{N})(?=[\p{L}\p{N}]*[^\p{L}\p{N}])[\s\S]{8,}$/";
        // Controllo se la password rispetta questi parametri
        if (preg_match($passwordRegex, $stringaDaControllare) == 1) {
            return true;
        } else{
            echo ("<a href='http://gruppo6.altervista.org/ProjectWork/modificaPassword.php'>Torna alla pagina di modifica della password</a>");
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
                        $passwordCriptata = hash("sha512", $password);

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
                    $passwordNuovaCriptata = hash("sha512", $password);

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
                    header("Location: https://gruppo6.altervista.org/ProjectWork/php/profiloUtente.php");
                    
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
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ModificaPassword</title>
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
    <div class="registration-form">
            <form>
                <div class="form-icon">
                <!-- Codice per l'icona SVG  -->
                    <span>
                        <svg width="2.5em" height="2.5em" version="1.1" viewBox="0 0 700 600" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g>
                            <path d="m467.04 295.53c10.262-0.070312 20.266 3.2188 28.48 9.3633 3.4062 2.5352 8.2266 1.832 10.762-1.5742 1.2188-1.6367 1.7383-3.6914 1.4414-5.707-0.29297-2.0195-1.3789-3.8359-3.0156-5.0547-16.461-12.156-37.793-15.566-57.223-9.1484-19.43 6.418-34.535 21.867-40.516 41.434h-5.9805c-4.2266 0-7.6562 3.4297-7.6562 7.6562s3.4297 7.6562 7.6562 7.6562h3.293c-0.050782 1.0938-0.078125 1.6875-0.078125 2.7344s0.027343 1.6406 0.078125 2.7344h-3.293c-4.2266 0-7.6562 3.4297-7.6562 7.6562s3.4297 7.6562 7.6562 7.6562h5.9805c5.9648 19.582 21.086 35.035 40.531 41.426 19.445 6.3906 40.789 2.918 57.203-9.3086 3.3672-2.5352 4.0391-7.3203 1.5039-10.688-2.5391-3.3672-7.3242-4.0391-10.691-1.5039-11.77 8.6914-26.879 11.492-40.98 7.6055-14.105-3.8906-25.641-14.039-31.297-27.531h51.328c4.2266 0 7.6562-3.4297 7.6562-7.6562s-3.4297-7.6562-7.6562-7.6562h-54.938c-0.066406-1.0938-0.10938-1.6836-0.10938-2.7344s0.046875-1.6406 0.10938-2.7344h66.086c4.2266 0 7.6562-3.4297 7.6562-7.6562s-3.4297-7.6562-7.6562-7.6562h-62.477c3.5508-8.6914 9.6172-16.125 17.422-21.348s16.988-7.9961 26.379-7.9648z"/>
                            <path d="m268.28 237.77c45.367 0 82.277-37.945 82.277-84.586 0-46.637-36.91-84.582-82.277-84.582-45.367 0-82.277 37.945-82.277 84.586s36.906 84.582 82.277 84.582zm0-153.86c36.926 0 66.965 31.074 66.965 69.273 0 38.195-30.039 69.273-66.965 69.273-36.926 0-66.965-31.074-66.965-69.273 0-38.199 30.039-69.273 66.965-69.273z"/>
                            <path d="m461.93 230.7c-36.395 0.058594-70.566 17.508-91.957 46.953-20.078 27.734-26.566 63.047-17.66 96.105 8.9102 33.059 32.262 60.332 63.559 74.223 31.293 13.891 67.188 12.918 97.68-2.6523 30.492-15.57 52.332-44.07 59.43-77.566 7.1016-33.496-1.2969-68.402-22.852-95.008-21.551-26.602-53.961-42.055-88.199-42.055zm0 211.73c-26.047 0-51.027-10.348-69.445-28.762-18.418-18.418-28.766-43.398-28.766-69.445s10.348-51.027 28.766-69.441c18.418-18.418 43.398-28.766 69.445-28.766s51.023 10.348 69.441 28.766c18.418 18.414 28.766 43.395 28.766 69.441 0.09375 26.074-10.219 51.109-28.66 69.551-18.438 18.438-43.473 28.754-69.547 28.656z"/>
                            <path d="m461.93 197.04c-47.188 0.078125-91.492 22.703-119.23 60.875-0.44922 0.62109-0.875 1.5312-1.3125 2.1523v0.003907c-11.707-2.8359-23.719-4.2266-35.766-4.1367h-63.633c-83.324 0-151.11 69.66-151.11 155.43 0.003906 2.0195 0.81641 3.9531 2.2539 5.3711 1.4375 1.4141 3.3828 2.1992 5.4023 2.1719h236.38c3.7344 6.418 7.9922 12.516 12.73 18.23 27.906 34.473 69.922 54.453 114.27 54.344 81.156 0 147.18-66.062 147.18-147.22s-66.027-147.22-147.18-147.22zm-355.53 206.55c3.8594-74.375 63.199-132.34 135.59-132.34h63.633c9.125-0.089844 18.238 0.76953 27.191 2.5547-11.906 21.664-18.121 45.988-18.066 70.707 0.027344 20.336 4.2422 40.449 12.391 59.082zm355.53 72.496c-34.977 0-68.516-13.891-93.246-38.621s-38.625-58.27-38.625-93.246c-0.003906-34.973 13.891-68.516 38.621-93.246 24.73-24.73 58.27-38.621 93.246-38.621 34.973 0 68.516 13.891 93.246 38.621 24.727 24.73 38.621 58.273 38.621 93.246 0.12891 35.012-13.723 68.629-38.48 93.387-24.758 24.758-58.371 38.609-93.383 38.48z"/>
                            <use x="70" y="576.40625" xlink:href="#s"/>
                            <use x="74.011719" y="576.40625" xlink:href="#d"/>
                            <use x="76.710938" y="576.40625" xlink:href="#a"/>
                            <use x="80.417969" y="576.40625" xlink:href="#j"/>
                            <use x="84.109375" y="576.40625" xlink:href="#c"/>
                            <use x="86.722656" y="576.40625" xlink:href="#a"/>
                            <use x="90.433594" y="576.40625" xlink:href="#i"/>
                            <use x="96.25" y="576.40625" xlink:href="#h"/>
                            <use x="100.167969" y="576.40625" xlink:href="#g"/>
                            <use x="105.636719" y="576.40625" xlink:href="#f"/>
                            <use x="109.574219" y="576.40625" xlink:href="#b"/>
                            <use x="113.332031" y="576.40625" xlink:href="#e"/>
                            <use x="70" y="581.875" xlink:href="#r"/>
                            <use x="72.378906" y="581.875" xlink:href="#d"/>
                            <use x="75.078125" y="581.875" xlink:href="#b"/>
                            <use x="78.832031" y="581.875" xlink:href="#q"/>
                            <use x="86.4375" y="581.875" xlink:href="#c"/>
                            <use x="89.050781" y="581.875" xlink:href="#p"/>
                            <use x="92.941406" y="581.875" xlink:href="#a"/>
                            <use x="98.554688" y="581.875" xlink:href="#o"/>
                            <use x="103.132812" y="581.875" xlink:href="#b"/>
                            <use x="106.890625" y="581.875" xlink:href="#e"/>
                            <use x="110.785156" y="581.875" xlink:href="#n"/>
                            <use x="116.582031" y="581.875" xlink:href="#m"/>
                            <use x="120.589844" y="581.875" xlink:href="#d"/>
                            <use x="123.285156" y="581.875" xlink:href="#b"/>
                            <use x="127.042969" y="581.875" xlink:href="#l"/>
                            <use x="128.917969" y="581.875" xlink:href="#a"/>
                            <use x="132.625" y="581.875" xlink:href="#k"/>
                            <use x="135.867188" y="581.875" xlink:href="#c"/>
                            </g>
                        </svg>
                    </span>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control item" id="passwordCorrenteID" name="passwordCorrente" placeholder="Password corrente" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control item" id="passwordNuovaID" name="passwordNuova" placeholder="Nuova password" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control item" id="confermaPasswordNuovaID" name="confermaPasswordNuova" placeholder="Conferma password" required>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-block create-account" name="Modifica" onclick="controllaInput()">Modifica password</button>
                </div>
            </form>

            <div class="social-media">
                <a href="http://gruppo6.altervista.org/ProjectWork/php/profiloUtente.php">Cliccato per errore? Torna alla pagina del profilo senza fare modifiche</a>
            </div>
        
        </div>
                
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
        <script src="assets/js/script.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <div class="registration-form">
            <form>
                <div class="form-icon">
                    <!-- Codice per l'icona SVG  -->
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="70" width="70" fill="#dee9ff" class="bi bi-key-fill" viewBox="-1 0 17 9 ">
                            <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                        </svg>
                    </span>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control item" id="passwordCorrenteID" name="passwordCorrente" placeholder="Password corrente" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control item" id="passwordNuovaID" name="passwordNuova" placeholder="Nuova password" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control item" id="confermaPasswordNuovaID" name="confermaPasswordNuova" placeholder="Conferma password" required>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-block create-account" name="Modifica" onclick="controllaInput()">Modifica password</button>
                </div>
            </form>

            <div class="social-media">
                <a href="http://gruppo6.altervista.org/ProjectWork/php/profilo.php">Cliccato per errore? Torna alla pagina del profilo senza fare modifiche</a>
            </div>
        </div>
                    
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
        <script src="assets/js/script.js"></script>
    </body>
</html>