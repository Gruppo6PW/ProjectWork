<!-- PHP -->
<?php
// Controllo se è stato premuto il button di submit, ossia è presente un elemento inviato in POST con chiave Registrazione nell'array superglobale
if (isset($_POST["Login"]) && isset($_POST["g-recaptcha-response"])) {
    // Prendo i valori inviata dalla pagina di registrazione
    $email = $_POST["email"];
    $password = $_POST["password"];
}

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

?>

<!-- HTML -->

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <!-- Recaptcha -->
    <!-- <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script> -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
    <script>
        // Recaptcha
        var onloadCallback = function() {
            alert("grecaptcha is ready!");
        };

        function controllaInput() {
            // Prendo i valori
            email = formRegistrazione.emailID.value;
            password = formRegistrazione.passwordID.value;
            // Controllo che email non sia vuota e sia string
            if ((email != "" && (typeof email === 'string' || email instanceof String))) {

            } else {
                alert("Inserisci una email valida");

                // Cancello l'input
                document.getElementById('emailID').value = '';
            }

            if (password != "") {

            } else {
                alert("Inserisci una email valida");

                // Cancello l'input
                document.getElementById('emailID').value = '';
            }
        }
    </script>
    <div class="registration-form">
        <form>
            <div class="form-icon">
                <span><i class="icon icon-user"></i></span>
            </div>
            <div class="form-group">
                <input type="email" class="form-control item" id="email" placeholder="E-Mail">
            </div>
            <div class="form-group">
                <input type="password" class="form-control item" id="password" placeholder="Password">
            </div>
            <div class="text-center">
                <div class="g-recaptcha" data-sitekey="6Lc0L0wmAAAAAHIusv0dCKOV9a4msMJLD516RB1r"></div>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-block create-account">Login</button>
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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>