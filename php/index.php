<?php
  // Avvio la sessione
  session_start();

  // Prendo l'id del conto corrente nell'URL
  $contoCorrenteID = $_GET["contoCorrenteID"];

  // Connessione database
  $conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");

  // Verifica della connessione
  if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
  }

  // Controllo se c'è una sessione attiva
  if(session_status() === PHP_SESSION_ACTIVE){
    if($_SESSION["accessoEseguito"] && $_SESSION["contoCorrenteID"] == $contoCorrenteID){
      //Ricavo i dati dell'utente (nome, data apertura conto)
      try{
        $SQL = "SELECT NomeTitolare, DataApertura FROM tconticorrenti WHERE ContoCorrenteID = ? LIMIT 1";
        if($statement = $conn -> prepare($SQL)){
          $statement -> bind_param("i", $contoCorrenteID);
          $statement -> execute();
          
          // Prendo il risultato della query
          $result = $statement->get_result();

          // C'è una tupla
          if ($result->num_rows != 0) {
            // Salvo il contenuto del result
            while ($row = $result->fetch_assoc()) {
              // Prendo l'AccessoValido
              $nomeUtente = $row["NomeTitolare"];
              $dataAperturaDB = $row["DataApertura"];

              // Converto nel formato che mi serve
              $dataApertura = date("d/m/Y", strtotime($dataAperturaDB));
            }
          }
  
          // Chiudo lo statement
          $statement->close();
        } else{
            // C'è stato un errore, lo stampo
            $errore = $mysqli->errno . ' ' . $mysqli->error;
            echo $errore;
            return;
        }
      } catch(Exception $e){
          echo "Qualcosa è andato storto nella richiesta dei dati dell'utente al db.";
      }
    
      try{
        // Ricavo le ultime 5 operazioni
        $SQL = "SELECT movimenti.MovimentoID, movimenti.Data, movimenti.Importo, movimenti.Saldo, categorie.NomeCategoria 
        FROM tmovimenticontocorrente AS movimenti JOIN tcategoriemovimenti AS categorie ON movimenti.CategoriaMovimentoID = categorie.CategoriaMovimentoID 
        WHERE movimenti.ContoCorrenteID = ? 
        ORDER BY movimenti.Data DESC 
        LIMIT 5";
        if($statement = $conn -> prepare($SQL)){
            $statement -> bind_param("i", $contoCorrenteID);
            $statement -> execute();
    
            // Prendo il risultato della query
            $result = $statement->get_result();
    
            // C'è una tupla
            if ($result->num_rows != 0) {
              // Salvo il contenuto del result
              $ultimeOperazioni = array();
              while ($row = $result->fetch_assoc()) {
                  $ultimeOperazioni[] = $row;
              }
  
              // Prendo il saldo
              $saldo = $ultimeOperazioni[0]['Saldo'];
            }
    
            // Chiudo lo statement
            $statement->close();
        } else{
            // C'è stato un errore, lo stampo
            $errore = $mysqli->errno . ' ' . $mysqli->error;
            echo $errore;
            return;
        }
      } catch(Exception $e){
          echo "Qualcosa è andato storto nella richiesta delle operazioni al db.";
      }  
    } else{
        // Controllo nel db se l'accesso valido è true (1) e la data dell'ultimo accesso. In quel caso gli creo la sessione, altrimenti lo mando al login
        $SQL = "SELECT AccessoValido, Data FROM taccessi WHERE ContoCorrenteID = ? ORDER BY Data DESC LIMIT 1";
        if ($statement = $conn->prepare($SQL)) {
          $statement->bind_param("i", $contoCorrenteID);
          $statement->execute();
  
          // Prendo il risultato della query
          $result = $statement->get_result();
  
          // Imposto inizialmente l'AccessoValido a 0. Se poi l'utente si è effettivamente registrato allora lo imposto a 1
          $accessoValido = 0;
  
          // C'è una tupla
          if ($result->num_rows != 0) {
              // Salvo il contenuto del result
              while ($row = $result->fetch_assoc()) {
                  // Prendo l'AccessoValido
                  $accessoValido = $row["AccessoValido"];
                  $dataUltimoAccesso = $row["Data"];
              }
          }
  
          // Controllo se è variato il valore di accessoValido
          if($accessoValido == 1){
            $dataCorrenteString = date("Y-m-d") . " " . date("h:i:s");
            $dataCorrente = date_create($dataCorrenteString);
  
            // Converto la data letta dal db in oggetto Date di php
            $dataDB = date_create($dataUltimoAccesso);
  
            // Calcolo la differenza di tempo
            $differenza = date_diff($dataCorrente, $dataDB);
  
            // Controllo se son passate meno di 24 dall'ultimo login valido
            if($differenza -> days == 0 && $differenza -> h < 24){
              // Accesso ancora valido, creo la sessione
              $_SESSION["accessoEseguito"] = "true";
              $_SESSION["contoCorrenteID"] = $contoCorrenteID;
  
              // Rimando a index.php con sessione impostata
              header("Location: https://gruppo6.altervista.org/ProjectWork/php/index.php?contoCorrenteID=$contoCorrenteID");
            } else{
              // Troppo tempo dall'ultimo accesso. Lo mando al login
              header("Location: https://gruppo6.altervista.org/ProjectWork/php/login.php");
            }
  
          } else{
            // Chiudo la connessione al db
            $conn->close();  
    
            // Non ha l'accesso, lo reinderizzo al login
            header("Location: https://gruppo6.altervista.org/ProjectWork/php/login.php");
          }
        } else {
            // C'è stato un errore, lo stampo
            $errore = $mysqli->errno . ' ' . $mysqli->error;
            echo $errore;
        }
    }
  }

  // Chiudo la connessione al db
  $conn->close();  
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLink" role="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">Operazioni</a>
                <div class="dropdown-menu rounded bg-light"  aria-labelledby="navbarDropdownLink">
                  <a class="dropdown-item " href="LINK ALLA PAGINA DEL BONIFICO">Bonifico</a>
                  <a class="dropdown-item" href="LINK ALLA PAGINA DELLA RICARICA TELEFONICA">Ricarica telefonica</a>
                  <!-- <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Something else here</a> -->
                </div>
              </li>
              <li class="nav-item dropdown active">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownDisabled" role="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">Movimenti</a>
                <div class="dropdown-menu rounded bg-light " aria-labelledby="navbarDropdownDisabled">
                  <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti1.php?contoCorrenteID=<?php echo $contoCorrenteID ?>">Ultimi movimenti</a>
                  <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti2.php?contoCorrenteID=<?php echo $contoCorrenteID ?>">Cerca per categoria</a>
                  <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti3.php?contoCorrenteID=<?php echo $contoCorrenteID ?>">Cerca per data</a>
                </div>
              </li>
            </ul>

            <!-- Logout -->
            <div class="nav-item dropdown bg-danger ml-auto ">
                <a class="nav-link active" href="http://gruppo6.altervista.org/ProjectWork/php/logOut.php" style="color: white ">LogOut</a>
            </div>
          </div>
        </nav>

    <main class="container my-5">
      <br>
      <br>

      <h1 class="mb-4" id="centrata">Benvenuto, <?php echo $nomeUtente; ?>!</h1>
      <p id="centrata">Conto creato in data: <?php echo $dataApertura; ?></p>
      <p id="centrata">Saldo totale: <?php echo $saldo; ?></p>

      <h4>Ultimi movimenti</h4>
      <table class="table table-bordered mt-4 table-hover">
        <thead>
          <tr class="table-primary">
            <th scope="col">Tipo operazione</th>
            <th scope="col">Importo</th>
            <th scope="col">Data</th>
            <th scope="col">Dettagli</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ultimeOperazioni as $operazione): ?>
            <tr>
              <td><?php echo $operazione['CategoriaMovimentoID']; ?></td>
              <td><?php echo $operazione['Importo']; ?></td>
              <td><?php echo $operazione['Data']; ?></td>
              <td><a href="http://gruppo6.altervista.org/ProjectWork/php/dettaglioMovimento.php?id=<?php echo $operazione['MovimentoID']; ?>" target="_blank"><img src="http://gruppo6.altervista.org/ProjectWork/css/Immagini/details.png" alt="Icona Dettagli" height="25" width="25"></a></td>
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
