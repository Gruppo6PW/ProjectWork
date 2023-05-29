<!DOCTYPE html>
<html>
<head>
  <title>Gestione Conto Corrente</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/css/bootstrap.min.css">
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
    <h1 class="mb-4">Benvenuto, <?php echo $NomeTitolare; ?>!</h1>
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
