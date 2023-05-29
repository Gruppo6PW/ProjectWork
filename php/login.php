<!-- PHP -->
<?php
// Controllo se è stato premuto il button di submit, ossia è presente un elemento inviato in POST con chiave Registrazione nell'array superglobale
if (isset($_POST["Login"])) {
    // Prendo i valori inviata dalla pagina di registrazione
    $email = $_POST["email"];
    $password = $_POST["password"];
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/scripts.js"></script>
    <title>Login</title>
    <!-- Bootstrap CDN !-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
    <!-- JS -->
    <script>
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
    <h1>Login</h1>

    <form action="" method="POST" name="formLogin">

        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="emailID" class="col-form-label">E-Mail</label>
            </div>
            <div class="col-auto">
                <input type="email" id="emailID" class="form-control">
            </div>
        </div>
        <br>
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="passwordID" class="col-form-label">Password</label>
            </div>
            <div class="col-auto">
                <input type="password" id="passwordID" class="form-control">
            </div>
        </div>

    </form>
</body>

</html>