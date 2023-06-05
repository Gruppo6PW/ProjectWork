<?php
    // Controllo se l'email è valida
    function controllaRequisitiEmail($stringaDaControllare){
        $emailRegex = "/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/";
        // Controllo se la email rispetta questi parametri
        if (preg_match($emailRegex, $stringaDaControllare) == 1) {
            return true;
        } else{
            return false;
        }
    }

    if(isset($_POST["Invia"])){
        // Prendi il valore
        $email = $_POST["email"];

        // Controllo che non siano vuote e chi siano stringhe
        if(!empty($email) && is_string($email) && controllaRequisitiEmail($email)){
            // Non vuota e stringa valida

            // Mi connetto al db
            $conn = mysqli_connect('localhost', "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
                    
            // Controllo che la connessione sia andata buon fine, altrimenti mostro l'errore
            if ($conn->connect_error) {
                die("Connessione fallita: " . $conn->connect_error);
            }

            // Genero un token
            $testoRandom = md5($email);    // Genero un hash MD5 per rendere il token univoco
            $token = uniqid() . '_' . $testoRandom;

            // Aggiorno il token del db
            $SQL = "UPDATE tconticorrenti SET Token = ? WHERE tconticorrenti.Email = ?";
            if($statement = $conn -> prepare($SQL)){
                $statement -> bind_param("ss", $token, $email);
                $statement -> execute();
        
                // Chiudo lo statement
                $statement->close();
            } else{
                // C'è stato un errore, lo stampo
                $errore = $mysqli->errno . ' ' . $mysqli->error;
                echo $errore;
            }
            
            // Chiudo la connessione al db
            $conn->close();

            // Invio la mail
            $msg = "Clicca <a href='https://gruppo6.altervista.org/ProjectWork/php/reimpostaPassword.php?token=$token'>qui</a> per reimpostare la tua password";   // Sostutuire con il proprio dominio di altervista
            $msg = wordwrap($msg,70);   // Necessario sopra i 50 caratteri
            $specificheHtml = "MIME-Version: 1.0" . "\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n";
            mail("$email", "Reimposta Password - Project Work", $msg, $specificheHtml);

            // Form nel caso in cui sia inviata con successo la mail
            $html = "
            <div class='registration-form'>
            	<form action='' name='formPasswordDimenticata' method='POST' >
                	<div class='form-icon'>
               			<!-- Codice per l'icona SVG  -->
                        <span>
                            <svg xmlns='http://www.w3.org/2000/svg' width='50' height='50' fill='#dee9ff' class='bi bi-envelope-fill' viewBox='0 0 16 12'>
                                <path d='M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z' />
                            </svg>
                        </span>
                	</div>
                    <div class='form-group'>
                        <h3>Ti è stata inviata una email contenente un link per reimpostare la password</h3>
                    </div>
                </form>        
            </div>
            ";

        } else {
            // Form mail non valida
            $html = "
            <div class='registration-form'>
                <form action='' name='formPasswordDimenticata' method='POST' >
                    <div class='form-icon'>
                        <!-- Codice per l'icona SVG  -->
                        <span>
                            <svg xmlns='http://www.w3.org/2000/svg' width='50' height='50' fill='#dee9ff' class='bi bi-envelope-fill' viewBox='0 0 16 12'>
                                <path d='M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z' />
                            </svg>
                        </span>
                    </div>
                    <div class='form-group'>
                        <h3>L'email non è valida</h3>
                    </div>
                </form>        
            </div>
            ";
        }
    } else{
        // Form in cui l'utente inserisce la mail
        $html = "
        <div class='registration-form'>
            <form action='' name='formPasswordDimenticata' method='POST' >
                <div class='form-icon'>
                    <!-- Codice per l'icona SVG  -->
                    <span>
                        <svg xmlns='http://www.w3.org/2000/svg' height='70' width='70' fill='#dee9ff' class='bi bi-key-fill' viewBox='-1 0 17 9'>
                            <path d='M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z'/>
                        </svg>
                    </span>
                </div>
                <div class='form-group'>
                	<p> Inserisci la tua mail, in modo da ricevere il link per reimpostare la password: </p>
           		</div>
                <div class='form-group'>
                    <input type='email' class='form-control item' name='email' id='emailID' placeholder='E-mail' required>
                </div>
                <div class='form-group'>
                    <input type='submit' class='btn btn-block create-account' name='Invia'>
                </div>
            </form>
        </div>
        ";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Dimenticata</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styleNoSocial.css">
</head>
<body>
    <!-- HTML -->
    <?php echo $html; // Si adatta ad ogni caricamento ?>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>