<?php
$movimentoID = $_GET["id"];

// Connessione database
//$conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
$conn=new mysqli("localhost", "root", "", "my_gruppo6");


// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Query per recuperare i dati utente
$sql = "SELECT * FROM tmovimenticontocorrente WHERE MovimentoID = $movimentoID";
$result = $conn->query($sql);
// Verifica dei risultati della query
if ($result !== false && $result->num_rows == 1){
    // Recupero dei dati dei movimenti
    $movimenti = array();
    while ($row = $result->fetch_assoc()) {
        $movimento[] = $row;
    }
} else {
    echo "Errore nel caricamento delle informazioni.";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dettagli Operazione</title>
  <link rel="stylesheet" href="/css/stylesIndex.css">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
  <header class="bg-light py-3">
    <div class="container d-flex justify-content-between align-items-center">
    <img src="Media/searchIcon.png" alt="Icona Ricerca" width=200>
    <a href="ProfiloUtente.php"> <img src="Media/profileIcon.png" alt="Icona Profilo Utente" width=200> </a>
    <img src="Media/transactionIcon.png" alt="Icona Operazioni" width=200>
    </div>
  </header>

    <h1>Tabella Dettagli Movimento</h1>
    <table>
        <tr>
            <th>CategoriaMovimentoID</th>
            <td><?php echo $movimento[0]['CategoriaMovimentoID']; ?></td>
        </tr>
        <tr>
            <th>Data</th>
            <td><?php echo $movimento[0]['Data']; ?></td>
        </tr>
        <tr>
            <th>Importo</th>
            <td><?php echo $movimento[0]['Importo']; ?></td>
        </tr>
        <tr>
            <th>DescrizioneEstesa</th>
            <td><?php echo $movimento[0]['DescrizioneEstesa']; ?></td>
        </tr>
    </table>
</body>