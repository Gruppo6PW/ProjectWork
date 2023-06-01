<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validazione codice mail</title>
</head>
<body>
    <!-- JS -->
    <script>
        function controllaInput() {
            // Prendo i valori
            OTP = formControlloOTP.codiceOTPID.value;

            // Controllo che email non sia vuota e sia string
            if (OTP != "" && (typeof OTP === 'number' || email instanceof Integer)) {
                // Non vuota e stringa

                // Tutto ok, invio
                formControlloOTP.submit();
            } else {
                alert("Inserisci un codice numerico");
                return false;
            }
        }
    </script>

    <h1>Controlla nella mail, ti è arrivato un codice a 6 cifre</h1>

    <form action="" name="formControlloOTP" method="post">
        <input type="number" name="codiceOTP" id="codiceOTPID" placeholder="Codice OTP">

        <p id="codiceOTPErratoID" style="color: red;">Codice OTP errato</p>

        <input type="submit" name="Invia" value="Invia" onclick="controllaInput()">
    </form>

    <!-- JS che nasconde codice TOP errato -->
    <script>
        document.getElementById("codiceOTPErratoID").style.visibility = 'hidden';
    </script>
</body>
</html>

<!-- PHP (Lo metto dopo perchè altrimenti il parser non trova il paragrafo da rendere visibile) -->
<?php
    if(isset($_POST["Invia"])){
        // Prendo il codice OTP e lo controllo
        $OTP = $_POST["codiceOTP"];

        // Controllo che non sia vuoto e che sia stringhe
        if(!empty($OTP) && is_numeric($OTP)){
            // Mi connetto al db
            $conn = mysqli_connect('localhost', "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");

            // Controllo che la connessione sia andata buon fine, altrimenti mostro l'errore
            if ($conn->connect_error) {
                die("Connessione fallita: " . $conn->connect_error);
            }

            // Controllo che sia lo stesso OTP che ho sul server
            $SQL = "SELECT AccessoValido FROM taccessi WHERE CodiceOTP=? LIMIT 1"; // Ritorno AccessoValido così l'attaccante non ottiene nessuna informazione rilevante
            if($statement=$conn->prepare($SQL)){
                $statement -> bind_param("i", $OTP);
                $statement -> execute();

                // Prendo l'output della query e li salvo in result
                $result = $statement -> get_result();

                if ($result->num_rows == 1) {
                    // C'è una tupla. OTP valido. Reinderizzo all'index.php
                    header("Location: http://gruppo6.altervista.org/ProjectWork/php/index.php");
                } else{
                    echo "
                    <script>
                        document.getElementById('codiceOTPErratoID').style.visibility = 'visible';
                    </script>
                    ";
                }
                
                // Chiudo lo statement
                $statement->close();
            } else{
                // C'è stato un errore, lo stampo
                $errore = $conn->errno . ' ' . $conn->error;
                echo $errore;
            }
        } else {
            echo "
            <script>
                document.getElementById('codiceOTPErratoID').style.visibility = 'visible';
            </script>
            ";
        }
    }
?>