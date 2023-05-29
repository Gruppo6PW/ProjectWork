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
    function controllaRequisitiPassword($stringaDaControllare){
        $passwordRegex = "/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/";
        // Controllo se la password rispetta questi parametri
        if (preg_match($passwordRegex, $stringaDaControllare) == 1) {
            return true;
        } else{
            echo("<h2>Password non valida</h2>");
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
            echo("<h2>Password non valida</h2>");
            return false;
        }
    }


    // Controllo se è stato premuto il button di submit, ossia è presente un elemento inviato in POST con chiave Registrazione nell'array superglobale
    if(isset($_POST["Registrati"])){
        // Prendo i valori inviata dalla pagina di registrazione
        $email = $_POST["email"];
        echo $password = $_POST["password"];
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
                        
                            // Controllo che la mail non esista
                
                        // Mi connetto al db
                        $conn = mysqli_connect('localhost', "*nomeUtenteAltervista*", "*passwordAccessoAltervista*", "dbprojectwork");
                
                        // Controllo che la connessione sia andata buon fine, altrimenti mostro l'errore
                        if ($conn->connect_error) {
                            die("Connessione fallita: " . $conn->connect_error);
                        }
                
                        // Creo ed eseguo la query di controllo con il prepared statement per evitare SQL Injection
                        $statement = $conn -> prepare("SELECT email FROM tconticorrenti WHERE 'Email'=? LIMIT 1");  // E' necessario il punto di domanda || Ho messo il limit perchè deve ritornare 1 email solo, quindi così siamo sicuri di evitare dump della tabella
                        $statement -> bind_param("s", $email);  // Il primo parametro definisce il tipo di dato inserito. i -> integer | d -> double | s -> string
                        $statement -> execute();
                        
                        // Chiudo lo statement
                        $statement->close();

                        if ($result->num_rows > 0) {
                            // C'è una tupla. La mail esista già
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
                        
                        //Faccio l'inserimento
                        $statement = $conn -> prepare("INSERT INTO tconticorrenti('Email', 'Password', 'CognomeTitolare', 'NomeTitolare', 'DataApertura', 'RegistrazioneConfermata', 'Token') VALUES(?, ?, ?, ?, ?, ?, ?");  // E' necessario il punto di domanda || Escludo l'ID e l'IBAN perchè è vanno fatti dopo
                        $statement -> bind_param("ssssssss", $email, $passwordCriptata, $cognomeTitolare, $nomeTitolare, $dataApertura, 0, $token);  // Il primo parametro definisce il tipo di dato inserito. i -> integer | d -> double | s -> string
                        $statement -> execute();
                        
                        // Chiudo lo statement
                        $statement->close();
                
                        // Chiudo la connessione al db
                        $conn->close();
                
                        // Reinderizzo l'utente alla pagina di invio della mail di conferma
                        header("locate: invioMailConferma.php?Email=$email&token=$token");

                    } else{
                        echo("<h2>Cognome titolare non valido</h2>");
                    return;
                    }
                } else{
                    echo("<h2>Nome titolare non valido</h2>");
                    return;
                }

            } else{
                echo("<h2>Password non valida</h2>");
                return;
            }
        } else{
            echo("<h2>L'email non è valida</h2>");
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
    <title>Registrazione</title>
</head>
<body>
    <!-- JS -->
    <script>
        function controllaInput(){
            // Prendo i valori
            email = formRegistrazione.emailID.value;
            password = formRegistrazione.passwordID.value;
            confermaPassword = formRegistrazione.confermaPasswordID.value;
            nomeTitolare = formRegistrazione.nomeTitolareID.value;
            cognomeTitolare = formRegistrazione.cognomeTitolareID.value;

            // Controllo che email non sia vuota e sia string
            if((email != "" && (typeof email === 'string' || email instanceof String))){
                // Non vuota e stringa

                // Controllo che password non sia vuota e sia string
                if((password != "" && (typeof password === 'string' || password instanceof String))){
                    // Non vuota e stringa

                    // Controllo che confermaPassword non sia vuota e sia string
                    if((confermaPassword != "" && (typeof confermaPassword === 'string' || confermaPassword instanceof String))){
                        // Non vuota e stringa
                        
                        // Controllo se le password sono uguali
                        if(password == confermaPassword){
                            // Uguali

                            // Controllo che nomeTitolare non sia vuota e sia string senza numeri
                            if((nomeTitolare != "" && (typeof nomeTitolare === 'string' || nomeTitolare instanceof String)) && controllaSeCiSonoNumeri(nomeTitolare)){
                                // Non vuota e stringa senza numeri

                                // Controllo che cognomeTitolare non sia vuota e sia string senza numeri
                                if((cognomeTitolare != "" && (typeof cognomeTitolare === 'string' || cognomeTitolare instanceof String)) && controllaSeCiSonoNumeri(cognomeTitolare)){
                                    // Non vuota e stringa senza numeri
                                    // Tutto ok, invio
                                    formRegistrazione.submit();       // Invio il submit
                                } else{
                                    alert("Cognome titolare non valido. Rimuovi i numeri");

                                    // Cancello gli input
                                    document.getElementById('cognomeTitolare').value = '';
                                }
                            } else {
                                alert("Nome titolare non valido. Rimuovi i numeri");

                                // Cancello gli input
                                document.getElementById('nomeTitolare').value = '';
                            }
                        } else{
                            alert("Le password non corrispondono");

                            // Cancello gli input
                            document.getElementById('passwordID').value = '';
                            document.getElementById('confermaPasswordID').value = '';
                        }
                    } else{
                        alert("Inserisci una conferma password che sia una stringa");

                        // Cancello l'input
                        document.getElementById('confermaPasswordID').value = '';
                    }
                } else{
                    alert("Inserisci una password che sia stringa");

                    // Cancello l'input
                    document.getElementById('passwordID').value = '';
                }
            } else{
                alert("Inserisci una email valida");

                // Cancello l'input
                document.getElementById('emailID').value = '';
            }
        }

        function controllaSeCiSonoNumeri(stringaDaControllare){
            if(/\d/.test(stringaDaControllare)){
                // Ci sono numeri
                return false;    // False, NON può procedere
            } else{
                // Non ci sono numeri
                return true;    // True, può procedere
            }
        }
    </script>

    <!-- HTML -->
    <h1>Registrazione</h1>

    <!-- Action vuota, punta a se stessa -->
    <form action="" method="POST" name="formRegistrazione">
        <label for="emailID">Email:</label>
        <input type="email" name="email" id="emailID" required>

        <br>

        <label for="passwordID">Password:</label>
        <input type="password" name="password" id="passwordID" required>

        <br>

        <label for="confermaPasswordID">Conferma password:</label>
        <input type="password" name="confermaPassword" id="confermaPasswordID" required>

        <br>

        <label for="nomeTitolareID">Nome titolare:</label>
        <input type="text" name="nomeTitolare" id="nomeTitolareID" required>

        <br>

        <label for="cognomeTitolareID">Cognome titolare:</label>
        <input type="text" name="cognomeTitolare" id="cognomeTitolareID" required>

        <br>

        <input type="submit" value="Registrati" name="Registrati" onclick="controllaInput()">

    </form>
</body>
</html>