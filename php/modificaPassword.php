<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Password</title>
</head>
<body>
    <!-- JS -->
    <script>
        function controllaInput() {
            // Prendo i valori
            passwordCorrente = formModificaPassword.passwordCorrenteID.value;
            passwordNuova = formModificaPassword.passwordNuovaID.value;
            confermaPasswordNuova = formModificaPassword.confermaPasswordNuovaID.value;

            // Controllo che la passwordCorrente non sia vuota e sia string
            if ((passwordCorrente.isEmpty() && (typeof passwordCorrente === 'string' || passwordCorrente instanceof String) && controllaRequisitiPassword(passwordCorrente))) {
                // Non vuota e stringa

                // Controllo che password non sia vuota e sia string
                if ((passwordNuova.isEmpty() && (typeof passwordNuova === 'string' || passwordNuova instanceof String) && controllaRequisitiPassword(passwordNuova))) {
                    // Non vuota e stringa

                    // Controllo che confermaPassword non sia vuota e sia string
                    if ((confermaPasswordNuova.isEmpty() && (typeof confermaPasswordNuova === 'string' || confermaPasswordNuova instanceof String) && controllaRequisitiPassword(confermaPasswordNuova))) {
                        // Non vuota e stringa

                        // Controllo se le password sono uguali
                        if (passwordNuova == confermaPasswordNuova) {
                            // Uguali

                            // Tutto ok, invio
                            formModificaPassword.submit(); // Invio il submit
                        } else {
                            alert("Le password non corrispondono");

                            // Cancello gli input
                            document.getElementById('passwordNuovaID').value = '';
                            document.getElementById('confermaPasswordNuovaID').value = '';
                            return false;
                        }
                    } else {
                        alert("La  conferma password deve essere valida");

                        // Cancello l'input
                        document.getElementById('confermaPasswordNuovaID').value = '';
                        return false;
                    }
                } else {
                    alert("La password nuova deve valida");

                    // Cancello l'input
                    document.getElementById('passwordNuovaID').value = '';
                    return false;
                }
            } else {
                alert("La password corrente deve essere valida");

                // Cancello l'input
                document.getElementById('passwordCorrenteID').value = '';
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

    <!-- HTML -->
    <form action="" method="post" name="formModificaPassword">
        <input type="password" name="passwordCorrente" id="passwordCorrenteID">
        <input type="password" name="passwordNuova" id="passwordNuovaID">
        <input type="password" name="confermaPasswordNuova" id="confermaPasswordNuovaID">

        <input type="submit" name="Modifica">
    </form>
</body>
</html>