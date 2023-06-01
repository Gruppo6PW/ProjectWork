<!-- PHP -->
<?php
$email = $_GET["email"];
$token = $_GET["token"];

// Mando la mail di conferma
$msg = "Clicca <a href='http://gruppo6.altervista.org/ProjectWork/php/attivaAccount.php?token=$token'>qui</a> per confermare la mail";   // Sostutuire con il proprio dominio di altervista
$msg = wordwrap($msg, 70);   // Necessario sopra i 50 caratteri
$specificheHtml = "MIME-Version: 1.0" . "\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n";
mail("$email", "Conferma Registrazione - Project Work", $msg, $specificheHtml);

// Mi connetto al db
$conn = mysqli_connect('localhost', "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");

// Controllo che la connessione sia andata buon fine, altrimenti mostro l'errore
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Faccio una SELECT per leggere i dati da inserire nella tabella tmovimenticontocorrente
$SQL = "SELECT ContoCorrenteID, NomeTitolare, CognomeTitolare, DataApertura FROM tconticorrenti WHERE Email = ? LIMIT 1";   // Mi basta la mail perchè c'è il vincolo UNIQUE sulla tabella
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
            $contoCorrenteID = $row["ContoCorrenteID"];
            $nome = $row["NomeTitolare"];
            $cognome = $row["CognomeTitolare"];
            $data = $row["DataApertura"];
        }
    }
} else {
    // C'è stato un errore, lo stampo
    $errore = $mysqli->errno . ' ' . $mysqli->error;
}

// Inserisco un movimento di apertura con tutti gli importi a zero
$importo = 0;
$saldo = 0;
$categoriaMovimentoID = 0; // Apertura conto id
$descrizioneEstesa = "Apertura del conto di $nome $cognome";
$SQL = "INSERT INTO tmovimenticontocorrente(ContoCorrenteID, Data, Importo, Saldo, CategoriaMovimentoID, DescrizioneEstesa) VALUES(?, ?, ?, ?, ?, ?)";
if ($statement = $conn->prepare($SQL)) {
    $statement->bind_param("isiiis", $contoCorrenteID, $data, $importo, $saldo, $categoriaMovimentoID, $descrizioneEstesa);  // Il primo parametro definisce il tipo di dato inserito. i -> integer | d -> double | s -> string
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvenuto</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styleNoSocial.css">
    <style> 
    #centrata{
      text-align:center;
    }
</style>
</head>
<!-- HTML -->

<body>
    <div class="registration-form">
        <form name="loginForm" method="POST">
            <div class="form-icon">
                <span>
                    <svg xmlns='http://www.w3.org/2000/svg' width='50' height='50' fill='#dee9ff' class='bi bi-envelope-fill' viewBox='0 0 16 12'>
                        <path d='M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z' />
                    </svg>
                </span>
            </div>
            <div class="form-group">
                <h2 id="centrata">Benvenuto!</h2>
            </div>
            <div class="form-group">
                <p>Ti è stata inviata una mail contenente un link di conferma. Cliccalo e accedi al servizio. <br>
                    Puoi chiudere questa pagina.
                </p>
            </div>

            <div class="form-group">
            </div>
        </form>

    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>