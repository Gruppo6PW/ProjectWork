<!-- PHP -->
<?php
$visibilitaSubmit = "";
function disabilitaInserimentoCredenziali()
{
    $visibilitaSubmit = "";

    if (!isset($_COOKIE["tentativiLogin"])) {
        echo "Creo cookie";
        $cookieName = "tentativiLogin";
        $cookieValue = "0";
        setcookie($cookieName, $cookieValue, time() + (60), "/");
    } else {
        $cookieName = "tentativiLogin";
        $numeroTentativi = $_COOKIE["$cookieName"];
        if ($numeroTentativi < 3) {
            echo "Sommo + 1";
            $cookieValue = $_COOKIE["$cookieName"] + 1;
            $visibilitaSubmit = "";
        } else {
            echo "Metto disabled";
            $visibilitaSubmit = "disabled";
        }
    }
    return $visibilitaSubmit;
}

function controllaRequisitiEmail($stringaDaControllare)
{
    $emailRegex = "/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/";
    // Controllo se la email rispetta questi parametri
    if (preg_match($emailRegex, $stringaDaControllare) == 1) {
        return true;
    } else {
        echo ("<a href='http://gruppo6.altervista.org/ProjectWork/login.php'>Torna alla pagina login</a>");
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
        echo ("<a href='http://gruppo6.altervista.org/ProjectWork/login.php'>Torna alla pagina login</a>");
        return false;
    }
}

if (isset($_POST["Login"])) {
    // Prendo i campi
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Controllo input utente
    if (!empty($email) && is_string($email) && controllaRequisitiEmail($email)) {
        // Non vuota e stringa valida

        if (!empty($password) && is_string($password) && controllaRequisitiPassword($password)) {
            // Non vuota e stringa valida

            // Chiamo la funzione per il cookie
            $VisibilitaSubmit = disabilitaInserimentoCredenziali();

            

        } else {
            echo ("<h2>Password non valida</h2>");
            return;
        }
    } else {
        echo ("<h2>L'email non è valida</h2>");
        return;
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <script>
        function cancellaCredenziali() {
            document.getElementById("emailID").value = "";
            document.getElementById("passwordID").value = "";
        }

        function timerCancellazioneCredenziali() {
            setInterval(cancellaCredenziali(), 30000);
        }
    </script>
</head>

<body onload="timerCancellazioneCredenziali()">
    <script>
        // function controllaInput() {
        //     // Prendo i valori
        //     email = loginForm.emailID.value;
        //     password = loginForm.passwordID.value;

        //     // Controllo che email non sia vuota e sia string
        //     if ((email != "" && (typeof email === 'string' || email instanceof String) && controllaRequisitiEmail(email))) {
        //         // Non vuota e stringa

        //         // Controllo che password non sia vuota e sia string
        //         if (password != "" && (typeof password === 'string' || password instanceof String) && controllaRequisitiPassword(password)) {
        //             // Non vuota e stringa

        //             // Chiamo la funzione php per creare o controllare il cookie
        //             returnFunzionePHPPerDisabilitare = "?php echo (disabilitaInserimentoCredenziali()); ?>";
        //             if(returnFunzionePHPPerDisabilitare == ""){
        //                 loginForm.submit();
        //             } else{
        //                 alert("Devi attende lo scadere del timer prima del prossimo tentativo di accesso");
        //                 setInterval(sbloccaSubmit(), 60);
        //                 return false;
        //             }
        //         }
        //         else {
        //             alert("Inserisci una password valida");
        //             // Cancello l'input
        //             cancellaCredenziali();
        //             return false;
        //         }
        //     } else {
        //         alert("Inserisci una email valida");
        //         // Cancello l'input
        //         cancellaCredenziali();
        //         return false;
        //     }
        // }

        function sbloccaSubmit() {
            return <?php $visibilitaSubmit = ""; ?>
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
        <form name="loginForm" method="POST">
            <div class="form-icon">
                <span><i class="icon"></i></span>
            </div>
            <div class="form-group">
                <input type="email" class="form-control item" id="emailID" name="email" placeholder="E-Mail" value=""
                    required>
                <!-- <input type="hidden" name="tentativiLogin" id="tentativiLoginID" -->
                    <!-- value="?php echo ($_COOKIE["tentativiLogin"]); ?>"> -->
            </div>
            <div class="form-group">
                <input type="password" class="form-control item" id="passwordID" name="password" placeholder="Password"
                    value="" required>
            </div>
            <div class="text-center">
                <div class="g-recaptcha" data-sitekey="6Lc0L0wmAAAAAHIusv0dCKOV9a4msMJLD516RB1r"></div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-block create-account" name="Login" value="Login" <?php echo ($visibilitaSubmit); ?></input>
            </div>
        </form>
        <div class="social-media">
            <a href="registrazione.php">Non hai ancora un conto? Registrati ora!</a>
            <br>
            <br>
            <a href="passwordDimenticata.php">Hai dimenticato la Password?</a>
        </div>
    </div>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>