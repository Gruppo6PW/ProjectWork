<!-- PHP -->
<?php
    // Avvio la sessione
    session_start();

    // Controllo se l'utente ha la sessione valida, così lo mando subito su index.php
    if($_SESSION["accessoEseguito"]){
        // Ha l'accesso, lo reinderizzo
        header("Location: http://gruppo6.altervista.org/ProjectWork/php/index.php");
    } else{
        function controllaSeCiSonoNumeri($stringaDaControllare){
            // Ciclo che controlla ogni cella se ci sono numeri
            for($i = 0; $i < strlen($stringaDaControllare); $i++){
                if((is_numeric($stringaDaControllare[$i]))){
                    // E' un numero, non valida
                    return false;
                }
            }
    
            // Ciclo terminato, quindi niente numeri
            return true;
        }
    
        // Controllo se l'email è valida
        function controllaRequisitiEmail($stringaDaControllare){
            $emailRegex = "/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/";
            // Controllo se la email rispetta questi parametri
            if (preg_match($emailRegex, $stringaDaControllare) == 1) {
                return true;
            } else{
                return false;
            }
        }
        
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
    
        // Controllo se è stato premuto il button di submit, ossia è presente un elemento inviato in POST con chiave Registrazione nell'array superglobale
        if(isset($_POST["Registrati"]) && isset($_POST["g-recaptcha-response"])){
            // Prendo i valori inviata dalla pagina di registrazione
            $email = $_POST["email"];
            $password = $_POST["password"];
            $nomeTitolare = $_POST["nomeTitolare"];
            $cognomeTitolare = $_POST["cognomeTitolare"];
    
            // Controllo che non siano vuote e chi siano stringhe
            if(!empty($email) && is_string($email) && controllaRequisitiEmail($email)){
                // Non vuota e stringa
    
                if(!empty($password) && is_string($password) && controllaRequisitiPassword($password)){
                    // Non vuota e stringa
    
                    if(!empty($nomeTitolare) != "" && is_string($nomeTitolare) && controllaSeCiSonoNumeri($nomeTitolare)){
                        // Non vuota e stringa senza numeri
    
                        if(!empty($cognomeTitolare) != "" && is_string($cognomeTitolare) && controllaSeCiSonoNumeri($cognomeTitolare)){
                            // Non vuota e stringa senza numeri
    
                            // Verifica del captcha
                            $chiaveServer = "6Lc0L0wmAAAAANdAgFJdpPd7_Sv-M4Mm9zrXT-8R";
                            $rispostaCaptcha = $_POST['g-recaptcha-response'];
    
                            $curl = curl_init();
                            curl_setopt_array($curl, [
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
                            CURLOPT_POST => 1,
                            CURLOPT_POSTFIELDS => [
                                'secret' => $chiaveServer,
                                'response' => $rispostaCaptcha
                            ]
                            ]);
    
                            $risposta = curl_exec($curl);
                            curl_close($curl);
    
                            $datiCaptcha = json_decode($risposta);
                            if (!$datiCaptcha->success) {
                                die('Captcha non valido.');
                            }
                            
                                // Controllo che la mail non esista
                    
                            // Mi connetto al db
                            $conn = mysqli_connect('localhost', "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
                    
                            // Controllo che la connessione sia andata buon fine, altrimenti mostro l'errore
                            if ($conn->connect_error) {
                                die("Connessione fallita: " . $conn->connect_error);
                            }
                    
                            // Creo ed eseguo la query di controllo con il prepared statement per evitare SQL Injection
                            $SQL = "SELECT Email FROM tconticorrenti WHERE Email=? LIMIT 1"; // E' necessario il punto di domanda || Ho messo il limit perchè deve ritornare 1 email solo, quindi così siamo sicuri di evitare dump della tabella
                            if($statement = $conn -> prepare($SQL)){
                                $statement -> bind_param("s", $email);  // Il primo parametro definisce il tipo di dato inserito. i -> integer | d -> double | s -> string
                                $statement -> execute();
    
                                // Prendo il risultato della query
                                $result = $statement->get_result();
    
                                if ($result->num_rows > 0) {
                                    // C'è una tupla. La mail esista già
                                    echo("<h2>Email già esistente, prova con un'altra</h2>");
                                    return;
                                }
    
                                // Chiudo lo statement
                                $statement->close();
                            } else{
                                // C'è stato un errore, lo stampo
                                $errore = $mysqli->errno . ' ' . $mysqli->error;
                                echo $errore;
                                return;
                            }
    
                                // La mail non esiste. Calcolo l'hash della password
                            $salt = "sdfsd89fysd89fhjsr23rfjvsdv";
                            $passwordCriptata = crypt($password, $salt);
    
                            // Genero il token per la conferma della mail
                            $testoRandom = md5($email.$password.$nomeTitolare.$cognomeTitolare);    // Genero un hash MD5 concatenando le informazioni personali dell'utente, per renderlo univoco
                            $token = uniqid() . '_' . $testoRandom;
    
                            // Calcolo la data di apertura
                            $dataApertura = date("Y-m-d") . " " . date("h:i:s"); // Anno-Mese-Giorno Ora-Minuti-Secondi
    
                            $registrazioneConfermata = 0;
                            
                            //Faccio l'inserimento
                            $SQL = "INSERT INTO tconticorrenti(Email, Password, CognomeTitolare, NomeTitolare, DataApertura, RegistrazioneConfermata, Token) VALUES(?, ?, ?, ?, ?, ?, ?)"; // E' necessario il punto di domanda || Escludo l'ID e l'IBAN perchè è vanno fatti dopo
                            if($statement = $conn -> prepare($SQL)){
                                $statement -> bind_param("sssssis", $email, $passwordCriptata, $cognomeTitolare, $nomeTitolare, $dataApertura, $registrazioneConfermata, $token);  // Il primo parametro definisce il tipo di dato inserito. i -> integer | d -> double | s -> string
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
                    
                            // Reinderizzo l'utente alla pagina di invio della mail di conferma
                            header("Location: http://gruppo6.altervista.org/ProjectWork/php/invioMailConferma.php?email=$email&token=$token");
                        } else {
                            echo ("<h2>Cognome titolare non valido</h2>");
                        }
                    } else {
                        echo ("<h2>Nome titolare non valido</h2>");
                    }
                } else{
                    echo("<h2>Password non valida</h2>");
                }
            } else {
                echo ("<h2>L'email non è valida</h2>");
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Registrazione</title>

    <!-- Recaptcha -->
    <!-- <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script> -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
    <!-- JS -->
    <script>
        // Recaptcha
        var onloadCallback = function() {
            alert("grecaptcha is ready!");
        };

        function controllaInput() {
            // Prendo i valori
            email = formRegistrazione.emailID.value;
            password = formRegistrazione.passwordID.value;
            confermaPassword = formRegistrazione.confermaPasswordID.value;
            nomeTitolare = formRegistrazione.nomeTitolareID.value;
            cognomeTitolare = formRegistrazione.cognomeTitolareID.value;

            // Controllo che email non sia vuota e sia string
            if ((email != "" && (typeof email === 'string' || email instanceof String) && controllaRequisitiEmail(email))) {
                // Non vuota e stringa

                // Controllo che password non sia vuota e sia string
                if (password != "" && (typeof password === 'string' || password instanceof String) && controllaRequisitiPassword(password)) {
                    // Non vuota e stringa

                    // Controllo che confermaPassword non sia vuota e sia string
                    if (confermaPassword != "" && (typeof confermaPassword === 'string' || confermaPassword instanceof String) && controllaRequisitiPassword(confermaPassword)) {
                        // Non vuota e stringa

                        // Controllo se le password sono uguali
                        if (password == confermaPassword) {
                            // Uguali

                            // Controllo che nomeTitolare non sia vuota e sia string senza numeri
                            if ((nomeTitolare != "" && (typeof nomeTitolare === 'string' || nomeTitolare instanceof String)) && controllaSeCiSonoNumeri(nomeTitolare)) {
                                // Non vuota e stringa senza numeri

                                // Controllo che cognomeTitolare non sia vuota e sia string senza numeri
                                if ((cognomeTitolare != "" && (typeof cognomeTitolare === 'string' || cognomeTitolare instanceof String)) && controllaSeCiSonoNumeri(cognomeTitolare)) {
                                    // Non vuota e stringa senza numeri
                                    // Tutto ok, invio
                                    formRegistrazione.submit(); // Invio il submit
                                } else {
                                    alert("Cognome titolare non valido. Rimuovi i numeri");

                                    // Cancello gli input
                                    document.getElementById('cognomeTitolare').value = '';
                                    return false;
                                }
                            } else {
                                alert("Nome titolare non valido. Rimuovi i numeri");

                                // Cancello gli input
                                document.getElementById('nomeTitolare').value = '';
                                return false;
                            }
                        } else {
                            alert("Le password non corrispondono");

                            // Cancello gli input
                            document.getElementById('passwordID').value = '';
                            document.getElementById('confermaPasswordID').value = '';
                            return false;
                        }
                    } else {
                        alert("Inserisci una conferma password che sia una stringa");

                        // Cancello l'input
                        document.getElementById('confermaPasswordID').value = '';
                        return false;
                    }
                } else {
                    alert("Inserisci una password valida");

                    // Cancello l'input
                    document.getElementById('passwordID').value = '';
                    return false;
                }
            } else {
                alert("Inserisci una email valida");

                // Cancello l'input
                document.getElementById('emailID').value = '';
                return false;
            }
        }

        function controllaSeCiSonoNumeri(stringaDaControllare) {
            if (/\d/.test(stringaDaControllare)) {
                // Ci sono numeri
                return false; // False, NON può procedere
            } else {
                // Non ci sono numeri
                return true; // True, può procedere
            }
        }

        // Controllo se l'email è valida
        function controllaRequisitiEmail(stringaDaControllare) {
            emailRegex = /^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            // Controllo se la email rispetta questi parametri
            if (emailRegex.test(stringaDaControllare)) {
                return true;
            } else {
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

    <div class="registration-form">
        <form action="" method="POST" name="formRegistrazione">
            <div class="form-icon">
                <span>
                    <!-- Codice per costrure l'svg dell'icona -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#dee9ff" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                </svg>
                </span>
            </div>

            <div class="form-group">
                <input type="text" class="form-control item" name="nomeTitolare" id="nomeTitolareID" placeholder="Nome" required>
            </div>

            <div class="form-group">
                <input type="text" class="form-control item" name="cognomeTitolare" id="cognomeTitolareID" placeholder="Cognome" required>
            </div>

            <div class="form-group">
                <input type="email" class="form-control item" name="email" id="emailID" placeholder="E-Mail" required>
            </div>

            <div class="form-group">
                <input type="password" class="form-control item" name="password" id="passwordID" placeholder="Password" required>
            </div>

            <div class="form-group">
                <input type="password" class="form-control item" name="confermaPassword" id="confermaPasswordID" placeholder="Conferma Password" required>
            </div>

            <br>

            <!-- Recaptcha -->
            <div class="text-center">
                <div class="g-recaptcha" data-sitekey="6Lc0L0wmAAAAAHIusv0dCKOV9a4msMJLD516RB1r"></div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-block create-account" name="Registrati" onclick="controllaInput()">Registrati</button>
            </div>
        </form>

        <div class="social-media">
        	<a href="http://gruppo6.altervista.org/ProjectWork/php/login.php">Già registrato? Accedi al tuo conto</a>
        </div>
    </div>
  
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>