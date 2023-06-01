<?php
// Connessione database
//$conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
$conn=new mysqli("localhost", "root", "", "my_gruppo6");
// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

//IMPLEMENTARE SESSION
$contoCorrenteID = 1;
try{
    //query per ottere l'ultimo saldo disponibile, necessario per la query insert
    $query = $conn->prepare("SELECT Saldo FROM tmovimenticontocorrente WHERE ContoCorrenteID = ? ORDER BY Data DESC LIMIT 1");
    $query->bind_param("i", $contoCorrenteID);
    $query->execute();
    $query->bind_result($saldo);
    $query->fetch();
    $query->close();
} catch(Exception $e){
    echo "Qualcosa è andato storto nella richiesta del saldo al db..";
}

// controllo che vengano passati tutti i valori
$beneficiario = isset($_POST['nomeBeneficiario']) ? $_POST['nomeBeneficiario'] : '';
$iban = isset($_POST['ibanBeneficiario']) ? $_POST['ibanBeneficiario'] : '';
$importo = isset($_POST['importo']) ? $_POST['importo'] : '';
$causale = isset($_POST['causale']) ? $_POST['causale'] : '';

if(isset($_POST['ibanBeneficiario'])){
    try{
        $data = date("Y-m-d-G-i-s");
        $descrizione = "Bonifico a favore di $beneficiario. $importo € inviati a $iban. Causale: $causale";
        $nuovoSaldo = (float)$saldo - (float)$importo;
         $queryInsert = $conn->prepare("INSERT INTO tmovimenticontocorrente (ContoCorrenteID, Data, Importo, Saldo, CategoriaMovimentoID, DescrizioneEstesa)
          VALUES (?, ?, ?, ?, 2, ?)");
        $queryInsert->bind_param("isdss", $contoCorrenteID, $data, $importo, $nuovoSaldo, $descrizione);
        $queryInsert->execute();
        $risultato = $queryInsert->get_result();
        $queryInsert->close();

        echo 'Bonifico effettuata correttamente.' . $beneficiario . $iban . $importo;
    } catch(Exception $e){
        echo "Qualcosa è andato storto nell'esecuzione del bonifico.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Bonifico</title>
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
    <h2>Bonifico Bancario</h2>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="nomeBeneficiario">Beneficiario:</label>
        <input type="text" name="nomeBeneficiario" id="nomeBeneficiario" placeholder="Nome Cognome" required>
        <label for="ibanBeneficiario">IBAN:</label>
        <input type="text" name="ibanBeneficiario" id="ibanBeneficiario" placeholder="" minlength="27" maxlength="27" required>
        <label for="importo">Importo:</label>
        <input type="number" name="importo" id="importo" placeholder="" min="0" step="0.01" required>
        <label for="causale">Causale:</label>
        <input type="text" name="causale" id="causale" placeholder="" required>

        <input type="submit" value="Effettua bonifico">
    </form>
  </main>
</body>
</html>

