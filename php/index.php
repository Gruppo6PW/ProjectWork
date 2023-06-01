<?php
  // Avvio la sessione
  session_start();

  $_SESSION["accessoEseguito"] = "true";
  $_SESSION["contoCorrenteID"] = $_GET["contoCorrenteID"];
  $contoCorrenteID = $_GET["contoCorrenteID"];

  // Connessione database
  //$conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
  $conn=new mysqli("localhost", "root", "", "my_gruppo6");
  // Verifica della connessione
  if ($conn->connect_error) {
      die("Connessione al database fallita: " . $conn->connect_error);
  }

  // Prepared statement per ricavare i dati utente (nome, data apertura conto)
  try{
      $query1 = $conn->prepare("SELECT NomeTitolare, DataApertura FROM tconticorrenti WHERE ContoCorrenteID = ?");
      $query1->bind_param("i", $contoCorrenteID);
      $query1->execute();
      $risultato1 = $query1->get_result();

      $datiUtente = $risultato1->fetch_assoc();
      $nomeUtente = $datiUtente['NomeTitolare'];
      $dataApertura = $datiUtente['DataApertura'];
      $query1->close();
  } catch(Exception $e){
      echo "Qualcosa è andato storto nella richiesta dei dati dell'utente al db.";
  }

  // Prepared statement per ricavare le ultime 5 operazioni
  try{
      $query2 = $conn->prepare("SELECT movimenti.MovimentoID, movimenti.Data, movimenti.Importo, movimenti.Saldo, categorie.NomeCategoria 
      FROM tmovimenticontocorrente AS movimenti JOIN tcategoriemovimenti AS categorie ON 
      movimenti.CategoriaMovimentoID = categorie.CategoriaMovimentoID WHERE movimenti.ContoCorrenteID = ? ORDER BY movimenti.Data 
      DESC LIMIT 5");
      $query2->bind_param("i", $contoCorrenteID);
      $query2->execute();
      $risultato2 = $query2->get_result();
      $ultimeOperazioni = array();
      while($row = $risultato2->fetch_assoc()){
          $ultimeOperazioni[] = $row;
      }
  $saldo = $ultimeOperazioni[0]['Saldo'];
  $query2->close();
  } catch(Exception $e){
      echo "Qualcosa è andato storto nella richiesta delle operazioni al db.";
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
    <h1>Salve, <?php echo $nomeUtente; ?>!</h1>
    <p>Conto creato in data: <?php echo $dataApertura; ?></p>
    <p>Saldo totale: <?php echo $saldo; ?></p>

    <table>
      <caption>Ultimi movimenti</caption>
      <thead>
        <tr>
          <th>Tipo operazione</th>
          <th>Importo</th>
          <th>Data</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <!-- Creazione tabella ultimi movimenti -->
        <?php foreach ($ultimeOperazioni as $operazione): ?>
          <tr>
            <td><?php echo $operazione['NomeCategoria']; ?></td>
            <td><?php echo $operazione['Importo']; ?>€</td>
            <td><?php echo $operazione['Data']; ?></td>
            <td><a href="DettaglioMovimento.php?id=<?php echo $operazione['MovimentoID']; ?>">
                <img src="Media/details.png" alt="Icona Dettagli" width="25"></a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>
</body>
</html>
