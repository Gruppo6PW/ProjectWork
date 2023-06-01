<!-- HTML -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <!-- BOOTSTRAP -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="assets/js/script.js"></script>

    <!-- ReCaptcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
    <script>

        // ReCaptcha
        var onloadCallback = function() {
            alert("grecaptcha is ready!");
        };

        function controllaInput() {
            // Prendo i valori
            email = loginForm.emailID.value;
            password = loginForm.passwordID.value;

            // Controllo che email non sia vuota e sia string
            if ((email != "" && (typeof email === 'string' || email instanceof String) && controllaRequisitiEmail(email))) {
                // Non vuota e stringa

                // Controllo che password non sia vuota e sia string
                if (password != "" && (typeof password === 'string' || password instanceof String) && controllaRequisitiPassword(password)) {
                    // Non vuota e stringa

                    // Tutto ok, invio
                    loginForm.submit();
                } else {
                    alert("Inserisci una password valida");
                    // Cancello l'input
                    cancellaCredenziali();
                    return false;
                }
            } else {
                alert("Inserisci una email valida");
                // Cancello l'input
                cancellaCredenziali();
                return false;
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
        <form name="loginForm" method="POST">
            <div class="form-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#dee9ff" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                </svg>
            </div>
            <p id="tempoRimanenteID">Hai ancora a disposizione</p>
            <div class="form-group">
                <input type="email" class="form-control item" id="emailID" name="email" placeholder="E-Mail">

            </div>
            <div class="form-group">
                <input type="password" class="form-control item" id="passwordID" name="password" placeholder="Password">
            </div>
            <p id="credenzialiErrateID" style="color: red;">Credenziali errate</p>
            <!-- Recaptcha -->
            <div class="text-center">
                <div class="g-recaptcha" data-sitekey="6Lc0L0wmAAAAAHIusv0dCKOV9a4msMJLD516RB1r"></div>
            </div>
            <p id="numeroTentativiRimanentiID">Hai ancora a disposizione 3 tentativi</p>
            <div class="form-group">
                <input type="submit" class="btn btn-block create-account" name="Login" ID="LoginID" value="Login" onclick=controllaInput()>
            </div>
        </form>
        <div class="social-media">
            <a href="registrazione.php">Non hai ancora un conto? Registrati ora!</a>
            <br>
            <br>
            <a href="passwordDimenticata.php">Hai dimenticato la Password?</a>
        </div>
    </div>

    <!-- JS che nasconde credenziali errate -->
    <script>
        document.getElementById("credenzialiErrateID").style.visibility = 'hidden';
    </script>


    <!-- PHP -->
    <?php
    function controllaRequisitiEmail($stringaDaControllare)
    {
        $emailRegex = "/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/";
        // Controllo se la email rispetta questi parametri
        if (preg_match($emailRegex, $stringaDaControllare) == 1) {
            return true;
        } else {
            return false;
        }
    }

    // Controllo se la password contiene almeno 1 maiuscola, 1 minuscola, 1 numero, 1 carattere speciale e se è lunga almeno 8 caratteri
    function controllaRequisitiPassword($stringaDaControllare)
    {
        $passwordRegex = "/^(?=\P{Ll}*\p{Ll})(?=\P{Lu}*\p{Lu})(?=\P{N}*\p{N})(?=[\p{L}\p{N}]*[^\p{L}\p{N}])[\s\S]{8,}$/";
        // Controllo se la password rispetta questi parametri
        if (preg_match($passwordRegex, $stringaDaControllare) == 1) {
            return true;
        } else {
            return false;
        }
    }

    if (isset($_POST["Login"]) && isset($_POST["g-recaptcha-response"])) {
        // Prendo i campi
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Controllo input utente
        if (!empty($email) && is_string($email) && controllaRequisitiEmail($email)) {
            // Non vuota e stringa valida

            if (!empty($password) && is_string($password) && controllaRequisitiPassword($password)) {
                // Non vuota e stringa valida

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

                // Mi connetto al db
                $conn = mysqli_connect('localhost', "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");

                // Controllo che la connessione sia andata buon fine, altrimenti mostro l'errore
                if ($conn->connect_error) {
                    die("Connessione fallita: " . $conn->connect_error);
                }

                // Calcolo l'hash della password
                $salt = "sdfsd89fysd89fhjsr23rfjvsdv";
                $passwordCriptata = crypt($password, $salt);

                // Controllo quanti tentativi di login ha fatto e li salvo
                $SQL = "SELECT ContoCorrenteID, NumeroTentativiLogin FROM tconticorrenti WHERE Email = ? AND Password = ? LIMIT 1";
                if ($statement = $conn->prepare($SQL)) {
                    $statement->bind_param("ss", $email, $passwordCriptata);
                    $statement->execute();

                    // Prendo il risultato della query
                    $result = $statement->get_result();

                    if ($result->num_rows == 0) {
                        // Nessuna tupla ritornata, rendo visibile il paragrafo delle credenzali errate
                        echo "
                        <script> \n
                            document.getElementById('credenzialiErrateID').style.visibility = 'visible';
                        </script> \n
                        ";

                        $accessoValido = 0;

                        // Variabile per bloccare dopo l'insert
                        $esciDopoInsert = "si";
                    } else {
                        // Una tupla è presente, quindi credenziali corrette
                        $accessoValido = 1;

                        // Salvo il contenuto del result
                        while ($row = $result->fetch_assoc()) {
                            // Prendo l'id (è gia int)
                            $id = $row["ContoCorrenteID"];
                            $numeroTentativiLogin = $row["NumeroTentativiLogin"];
                        }
                    }

                    // Chiudo lo statement
                    $statement->close();

                    // Rifaccio la query per avere il numero di tentativi solo dalla mail
                    $SQL = "SELECT ContoCorrenteID, NumeroTentativiLogin FROM tconticorrenti WHERE Email = ? LIMIT 1";
                    if ($statement = $conn->prepare($SQL)) {
                        $statement->bind_param("s", $email);
                        $statement->execute();

                        // Prendo il risultato della query
                        $result = $statement->get_result();

                        // C'è una tupla, prendo il NumeroTentativiLogin
                        if ($result->num_rows != 0) {
                            // Salvo il contenuto del result
                            while ($row = $result->fetch_assoc()) {
                                // Prendo l'id (è gia int)
                                $numeroTentativiLogin = $row["NumeroTentativiLogin"];
                            }
                        }
                    } else {
                        // C'è stato un errore, lo stampo
                        $errore = $mysqli->errno . ' ' . $mysqli->error;
                        echo $errore;
                    }

                    // Prendo indirizzo ip
                    $indirizzoIP = $_SERVER["REMOTE_ADDR"];

                    $dataAccesso = date("Y-m-d") . " " . date("h:i:s");

                    // Aggiungo una tupla nella taccessi
                    $SQL = "INSERT INTO taccessi(IndirizzoIP, Data, AccessoValido) VALUES(?, ?, ?)";
                    if ($statement = $conn->prepare($SQL)) {
                        $statement->bind_param("ssi", $indirizzoIP, $dataAccesso, $accessoValido);
                        $statement->execute();

                        // Prendo il risultato della query
                        $result = $statement->get_result();

                        // Chiudo lo statement
                        $statement->close();
                    } else {
                        // C'è stato un errore, lo stampo
                        $errore = $mysqli->errno . ' ' . $mysqli->error;
                        echo $errore;
                    }

                    if ($esciDopoInsert == "si") {
                        // Sommo + 1 a tentativi di login
                        $numeroTentativiLogin += 1;
                        $SQL = "UPDATE tconticorrenti SET NumeroTentativiLogin = ? WHERE tconticorrenti.Email = ?";
                        if ($statement = $conn->prepare($SQL)) {
                            $statement->bind_param("ii", $numeroTentativiLogin, $email);
                            $statement->execute();

                            // Mostro quanti tentativi rimangono
                            $numeroTentativiRimanenti = 3 - $numeroTentativiLogin;
                            echo "
                                <script> \n
                                    document.getElementById('numeroTentativiRimanentiID').innerHTML = \"Hai ancora a disposizione \" + $numeroTentativiRimanenti + \" tentativi\";
                                </script>
                            ";

                            // Chiudo lo statement
                            $statement->close();
                        } else {
                            // C'è stato un errore, lo stampo
                            $errore = $mysqli->errno . ' ' . $mysqli->error;
                            echo $errore;
                        }

                        // Controllo se è arrivato a 3 tentativi di accesso
                        if ($numeroTentativiLogin == 3) {
                            // Blocco
                            echo "\n <script> \n";
                            echo "function disabilita(){ \n";
                            echo "document.getElementById('emailID').disabled = true;  \n";
                            echo "document.getElementById('passwordID').disabled = true;  \n";
                            echo "document.getElementById('LoginID').disabled = true;  \n";
                            echo "alert('Attendi un minuto prima di riprovare') \n";
                            echo "} \n";

                            echo "disabilita(); \n ";
                            echo "</script> \n";

                            // Sblocco
                            echo "<script> \n ";
                            echo "setTimeout(function(){ \n";
                            echo "document.getElementById('emailID').disabled = false;  \n";
                            echo "document.getElementById('passwordID').disabled = false;  \n";
                            echo "document.getElementById('LoginID').disabled = false;  \n";
                            echo "alert('Ora puoi riprovare') \n";
                            echo "}, 60000); \n";
                            echo "</script> \n";

                            // Reimposto 0 nel campo del db
                            $numeroTentativiLogin = 0;
                            $SQL = "UPDATE tconticorrenti SET NumeroTentativiLogin = ? WHERE tconticorrenti.Email = ?";
                            if ($statement = $conn->prepare($SQL)) {
                                $statement->bind_param("ii", $numeroTentativiLogin, $email);
                                $statement->execute();

                                // Reimposto i tentativi rimanenti a 3
                                $numeroTentativiRimanenti = 3 - $numeroTentativiLogin;
                                echo "
                                    <script> \n
                                        document.getElementById('numeroTentativiRimanentiID').innerHTML = \"Hai ancora a disposizione \" + $numeroTentativiRimanenti + \" tentativi\";
                                    </script>
                                ";

                                // Chiudo lo statement
                                $statement->close();
                            } else {
                                // C'è stato un errore, lo stampo
                                $errore = $mysqli->errno . ' ' . $mysqli->error;
                                echo $errore;
                            }
                        }
                    } else {
                        // Reimposto a 0 dato che ha eseguito il login correttamente
                        $numeroTentativiLogin = 0;
                        $SQL = "UPDATE tconticorrenti SET NumeroTentativiLogin = ? WHERE tconticorrenti.Email = ?";
                        if ($statement = $conn->prepare($SQL)) {
                            $statement->bind_param("ii", $numeroTentativiLogin, $email);
                            $statement->execute();

                            // Chiudo lo statement
                            $statement->close();
                        } else {
                            // C'è stato un errore, lo stampo
                            $errore = $mysqli->errno . ' ' . $mysqli->error;
                            echo $errore;
                        }

                        // Reinderizzo all'index
                        echo "
                        <script> \n
                            window.location.href = 'http://gruppo6.altervista.org/ProjectWork/php/index.php'; \n
                        </script> \n
                        ";
                    }
                } else {
                    // C'è stato un errore, lo stampo
                    $errore = $mysqli->errno . ' ' . $mysqli->error;
                    echo $errore;
                }
            } else {
                echo ("<h2>Password non valida</h2>");
            }
        } else {
            echo ("<h2>L'email non è valida</h2>");
        }
    }
    ?>

    <!-- Funzione che fa partire il controllo per la cancellazione delle textbox -->
    <script>
        // Genero un timero di 60 secondi
        secondi = 30;

        function timerSecondiRimanenti() {
            secondi--;
            document.getElementById("tempoRimanenteID").innerHTML = "Hai ancora a disposizione " + secondi + " secondi";

            if(secondi == 0){
                secondi = 30;
            }
        }

        setInterval((timerSecondiRimanenti), 1000);

        // JS pulizia textbox
        function cancellaCredenziali() {
            document.getElementById("emailID").value = "";
            document.getElementById("passwordID").value = "";
        }

        setInterval((cancellaCredenziali), 30000); // Scrivo la funzione senza parentesi, perchè così passo il riferimento invece di eseguirla. Se metto le parentesi viene eseguita 1 sola volta | Tempo in millisecondi
    </script>
</body>

</html>