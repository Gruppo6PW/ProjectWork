<?php
  // Avvio la sessione
  session_start();

  if($_SESSION["accessoEseguito"]){
    $_SESSION["accessoEseguito"] = "true";
    $_SESSION["contoCorrenteID"] = $_GET["contoCorrenteID"];
    $contoCorrenteID = $_GET["contoCorrenteID"];
  
    // Connessione database
    $conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
    //$conn=new mysqli("localhost", "root", "", "my_gruppo6");
    // Verifica della connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }
  
    // Prepared statement per ricavare i dati utente (nome, data apertura conto)
    try{
        $query1 = $conn->prepare("SELECT NomeTitolare, DataApertura FROM tconticorrenti WHERE ContoCorrenteID = ?");
        echo "SELECT NomeTitolare, DataApertura FROM tconticorrenti WHERE ContoCorrenteID = $contoCorrenteID";
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
  } else{
    // Non ha l'accesso, lo reinderizzo al login
    header("Location: http://gruppo6.altervista.org/ProjectWork/php/login.php");
  }

?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HomePage</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    #centrata{
      text-align:center;
    }
  </style>
</head>
<body>
  
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-primary ">
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav ">
          <li class="nav-item active">
              <a class="nav-link" href="http://gruppo6.altervista.org/ProjectWork/php/index.php">Home</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="http://gruppo6.altervista.org/ProjectWork/php/profilo.php">Profilo <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item dropdown active ">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLink" role="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
              Operazioni
            </a>
            <div class="dropdown-menu rounded bg-light"  aria-labelledby="navbarDropdownLink">
              <a class="dropdown-item " href="LINK ALLA PAGINA DEL BONIFICO">Bonifico</a>
              <a class="dropdown-item" href="LINK ALLA PAGINA DELLA RICARICA TELEFONICA">Ricarica telefonica</a>
              <!-- <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Something else here</a> -->
            </div>
          </li>
          <li class="nav-item dropdown active">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownDisabled" role="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
              Movimenti
            </a>
            <div class="dropdown-menu rounded bg-light " aria-labelledby="navbarDropdownDisabled">
              <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti1.php">Ultimi movimenti</a>
              <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti2.php">Cerca per categoria</a>
              <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti3.php">Cerca per data</a>
            </div>
          </li>
          <li class="nav-item active">
              <a class="nav-link" href="http://gruppo6.altervista.org/ProjectWork/php/logOut.php">LogOut</a>
          </li>
        </ul>
      </div>
    </nav>

<main class="container my-5">
  <br>
  <br>

  <h1 class="mb-4" id="centrata">Benvenuto, <?php echo $NomeUtente; ?>!</h1>
  <p id="centrata">Conto creato in data: <?php echo $DataApertura; ?></p>
  <p id="centrata">Saldo totale: <?php echo $Saldo; ?></p>
   <h4>Ultimi movimenti</h4>
  <table class="table table-bordered mt-4 table-hover">
    
    <thead  >
      <tr class="table-primary">
        <th scope="col">Tipo operazione</th>
        <th scope="col">Importo</th>
        <th scope="col">Data</th>
        <th scope="col">Dettagli</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($movimenti as $movimento): ?>
        <tr>
          <td><?php echo $movimento['CategoriaMovimentoID']; ?></td>
          <td><?php echo $movimento['Importo']; ?></td>
          <td><?php echo $movimento['Data']; ?></td>
          <td><a href="http://gruppo6.altervista.org/ProjectWork/php/DettaglioMovimento.php?id=<?php echo $movimento['MovimentoID']; ?>"><img src="http://gruppo6.altervista.org/ProjectWork/css/Immagini/details.png" alt="Icona Dettagli" height="25" width="25"></a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
