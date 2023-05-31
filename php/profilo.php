<?php
// Connessione database
//$conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
$conn=new mysqli("localhost", "root", "", "my_gruppo6");
// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

//Da sistemare con session utente
$contoCorrenteID = 1;

// Prepared statement per ricavare i dati utente (nome, data apertura conto)
try{
    $query1 = $conn->prepare("SELECT NomeTitolare, CognomeTitolare, DataApertura, Email, Iban FROM tconticorrenti WHERE ContoCorrenteID = ?");
    $query1->bind_param("i", $contoCorrenteID);
    $query1->execute();
    $risultato1 = $query1->get_result();
    $datiUtente = $risultato1->fetch_assoc();
    $nomeUtente = $datiUtente['NomeTitolare'];
    $cognomeUtente = $datiUtente['CognomeTitolare'];
    $dataApertura = $datiUtente['DataApertura'];
    $email = $datiUtente['Email'];
    $iban = $datiUtente['Iban'];
    $query1->close();
} catch(Exception $e){
    echo "Qualcosa è andato storto nella richiesta dei dati dell'utente al db.";
}
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Gestione Conto Corrente</title>
    <link rel="stylesheet" href="/css/stylesIndex.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
  <header>
    <div>
        <img src="Media/searchIcon.png" alt="Icona Ricerca" width=200>
        <a href="profiloUtente.php"> <img src="Media/profileIcon.png" alt="Icona Profilo Utente" width=200> </a>
        <img src="Media/transactionIcon.png" alt="Icona Operazioni" width=200>
    </div>
  </header>

  <main>
    <table>
      <caption>Profilo</caption>
        <tr>
            <td>Nome</td>
            <td><?php echo $nomeUtente ?></td>
        </tr>
        <tr>
            <td>Cognome</td>
            <td><?php echo $cognomeUtente ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?php echo $email ?></td>
        </tr>
        <tr>
            <td>Data apertura</td>
            <td><?php echo $dataApertura ?></td>
        </tr>
        <tr>
            <td>Iban</td>
            <td><?php echo $iban ?></td>
        </tr>
    </table>
    <br></br>
    <a href="modificaPassword.php">Modifica password</a>
  </main>
</body>
</html>
