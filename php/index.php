<?php
// Configurazione del database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_gruppo6";

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Query per recuperare i dati utente
$sql1 = "SELECT * FROM tconticorrenti WHERE ContoCorrenteID = 1";
$result1 = $conn->query($sql1);
// Verifica dei risultati della query
if ($result1 !== false && $result1->num_rows > 0){
    // Recupero dei dati dei movimenti
    $utente = array();
    while ($row = $result1->fetch_assoc()) {
        $utente[] = $row;
    }
    $NomeUtente = $utente[0]['NomeTitolare'];
} else {
    echo "Nessun utente corrispondente trovato.";
}

// Query per recuperare i movimenti
$sql2 = "SELECT * FROM tmovimenticontocorrente ORDER BY data DESC LIMIT 5";
$result2 = $conn->query($sql2);
// Verifica dei risultati della query
if ($result2 !== false && $result2->num_rows > 0){
    // Recupero dei dati dei movimenti
    $movimenti = array();
    while ($row = $result2->fetch_assoc()) {
        $movimenti[] = $row;
    }
} else {
    echo "Nessun movimento trovato.";
}

// Chiusura della connessione al database
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
  <title>Gestione Conto Corrente</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/css/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
  <header class="bg-light py-3">
    <div class="container d-flex justify-content-between align-items-center">
      <img src="icona_ricerca.png" alt="Icona Ricerca">
      <img src="icona_profilo.png" alt="Icona Profilo Utente">
      <img src="icona_operazioni.png" alt="Icona Operazioni">
    </div>
  </header>

  <main class="container my-5">
    <h1 class="mb-4">Benvenuto, <?php echo $NomeUtente; ?>!</h1>
    <p>Data di creazione del conto: <?php echo $DataApertura; ?></p>
    <p>Saldo totale: <?php echo $Saldo; ?></p>

    <table class="table mt-4">
      <caption>Ultimi movimenti</caption>
      <thead>
        <tr>
          <th scope="col">Tipo operazione</th>
          <th scope="col">Importo</th>
          <th scope="col">Destinatario</th>
          <th scope="col">Data</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($movimenti as $movimento): ?>
          <tr>
            <td><?php echo $movimento['CategoriaMovimentoID']; ?></td>
            <td><?php echo $movimento['Importo']; ?></td>
            <td><?php echo $movimento['Data']; ?></td>
            <td><a href="DettaglioMovimento.php?id=<?php echo $movimento['MovimentoID']; ?>"><img src="icona_dettagli.png" alt="Icona Dettagli"></a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
