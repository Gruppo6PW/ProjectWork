<!-- PHP -->
<?php

// da inserire il captcha
// if (isset($_POST["Login"])) {
//     // Prendo i valori inviata dalla pagina di registrazione
//     $email = $_POST["email"];
//     $password = $_POST["password"];
// }

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

</head>

<body onload="cancellaCredenziali()">
    <script>

        function cancellaCredenziali() {
            document.getElementById("emailID").value = "";
            document.getElementById("passwordID").value = "";
        }
        setInterval(cancellaCredenziali, 30000);

        function tentativiInserimentoCredenziali() {
            document.getElementById("tentativiLoginID").value += 1;
            controllaInput();
            alert("tentativo" + document.getElementById("tentativiLoginID").value);
        }

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

                }
                else {
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
                <input type="email" class="form-control item" id="emailID" name="email" placeholder="E-Mail">
                <input type="hidden" name="tentativiLogin" id="tentativiLoginID">
            </div>
            <div class="form-group">
                <input type="password" class="form-control item" id="passwordID" password="password"
                    placeholder="Password">
            </div>
            <div class="text-center">
                <div class="g-recaptcha" data-sitekey="6Lc0L0wmAAAAAHIusv0dCKOV9a4msMJLD516RB1r"></div>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-block create-account"
                    onclick=tentativiInserimentoCredenziali()>Login</button>
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