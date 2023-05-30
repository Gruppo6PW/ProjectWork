<?php
$movimentoID = $_GET["id"];

// Connessione database
//$conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
$conn=new mysqli("localhost", "root", "", "my_gruppo6");

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Prepared statement per ricavare i dati movimento
try{
    $query = $conn->prepare("SELECT movimenti.Data, movimenti.Importo, movimenti.DescrizioneEstesa, categorie.NomeCategoria FROM tmovimenticontocorrente AS movimenti JOIN tcategoriemovimenti AS categorie ON movimenti.CategoriaMovimentoID = categorie.CategoriaMovimentoID WHERE movimenti.MovimentoID = ?");
    $query->bind_param("i", $movimentoID);
    $query->execute();
    $risultato = $query->get_result();
    $datiMovimento = $risultato->fetch_assoc();
    $query->close();
} catch(Exception $e){
    echo "Qualcosa è andato storto nella richiesta dei dati del movimento al db.";
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
  <header>
    <div>
    <img src="Media/searchIcon.png" alt="Icona Ricerca" width=200>
    <a href="ProfiloUtente.php"> <img src="Media/profileIcon.png" alt="Icona Profilo Utente" width=200> </a>
    <img src="Media/transactionIcon.png" alt="Icona Operazioni" width=200>
    </div>
  </header>

    <h1>Tabella Dettagli Movimento</h1>
    <table>
        <tr>
            <th>Categoria Movimento</th>
            <td><?php echo $datiMovimento['NomeCategoria']; ?></td>
        </tr>
        <tr>
            <th>Data</th>
            <td><?php echo $datiMovimento['Data']; ?></td>
        </tr>
        <tr>
            <th>Importo</th>
            <td><?php echo $datiMovimento['Importo']; ?>€</td>
        </tr>
        <tr>
            <th>DescrizioneEstesa</th>
            <td><?php echo $datiMovimento['DescrizioneEstesa']; ?></td>
        </tr>
    </table>
</body>