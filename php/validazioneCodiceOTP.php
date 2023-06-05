<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Validazione codice mail</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="styleNoSocial.css">
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

        <!-- HTML -->
        <div class='registration-form'>
            <form action="" name="formControlloOTP" method="post" >
                <div class='form-icon'>
                <!-- Codice per l'icona SVG  -->
                    <span>
                        <svg xmlns='http://www.w3.org/2000/svg' height='70' width='70' fill='#dee9ff' class='bi bi-key-fill' viewBox='-1 0 17 9'>
                        <path d='M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z'/>
                        </svg>
                    </span>
                </div>
                <div class='form-group'>
                    <p> Inserisci il codice a 6 cifre che ti è arrivato nella mail: </p>
                    <p style="font-size: 13px"> (Se non vedi la mail controlla nella sezione spam)</p>
                </div>
                <div class='form-group'>
                    <input type='number' class='form-control item' oninput="controlloNumeroCaratteri(this)" oninput="controlloSoloNumeri(this)" name="codiceOTP" id="codiceOTPID" placeholder="Codice OTP" min="0" required maxlenght="6">
                    <p id="codiceOTPErratoID" style="color: red;">Codice OTP errato</p>
                </div>
                <div class='form-group'>
                    <input type='submit' class='btn btn-block create-account' name="Invia" value="Invia" onclick="controllaInput()"> 
                </div>
            </form>
        </div>

        <!-- Validazione input -->
        <script> 
            function controlloNumeroCaratteri(input) {
                const valoreInput = input.value.trim();
                const numeroCaratteriMassimo = 6;

                if (valoreInput.length > numeroCaratteriMassimo) {
                    input.value = valoreInput.slice(0, numeroCaratteriMassimo);
                }
            }
            
            function controlloSoloNumeri(input) {
                input.value = input.value.replace(/[^0-9]/g, '');
            }
        </script>

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
            $SQL = "SELECT ContoCorrenteID FROM taccessi WHERE CodiceOTP=? LIMIT 1";
            if($statement=$conn->prepare($SQL)){
                $statement -> bind_param("i", $OTP);
                $statement -> execute();

                // Prendo l'output della query e li salvo in result
                $result = $statement -> get_result();

                // C'è una tupla, prendo il NumeroTentativiLogin
                if ($result->num_rows == 1) {
                    // Salvo il contenuto del result
                    while ($row = $result->fetch_assoc()) {
                        // Prendo l'id (è gia int)
                        $contoCorrenteID = $row["ContoCorrenteID"];
                    }
                    // Chiudo la connessione al db
                    $conn->close();
                    
                    // C'è una tupla. OTP valido. Reinderizzo all'index.php
                    header("Location: https://gruppo6.altervista.org/ProjectWork/php/index.php?contoCorrenteID=$contoCorrenteID");
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