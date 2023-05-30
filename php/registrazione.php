<!-- PHP -->
<?php
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
            echo ("<a href='http://gruppo6.altervista.org/ProjectWork/registrazione.php'>Torna alla pagina registrazione</a>");
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
            echo ("<a href='http://gruppo6.altervista.org/ProjectWork/registrazione.php'>Torna alla pagina registrazione</a>");
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
                        return;
                    }
                } else {
                    echo ("<h2>Nome titolare non valido</h2>");
                    return;
                }
            } else{
                echo("<h2>Password non valida</h2>");
                return;
            }
        } else {
            echo ("<h2>L'email non è valida</h2>");
            return;
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
            <div class="social-media">
                <div class="form-group">
                    <button type="submit" class="btn btn-block create-account" name="Registrati" onclick="controllaInput()">Registrati</button>
                </div>
            </div>
        </form>
    </div>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>