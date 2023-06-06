<?php
    // Avvio la sessione
    session_start();

    // Prendo l'id del conto corrente nell'URL
    $contoCorrenteID = $_GET["contoCorrenteID"];

    // Connessione database
    $conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");

    // Verifica degli errori di connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    // Controllo che vengano passati dei valori in post
    $data_inizio = isset($_POST['data_inizio']) ? $_POST['data_inizio'] : '' ;
    $data_fine = isset($_POST['data_fine']) ? $_POST['data_fine'] : '' ;

    if(session_status() === PHP_SESSION_ACTIVE){
        if($_SESSION["accessoEseguito"] && $_SESSION["contoCorrenteID"] == $contoCorrenteID){
            if(isset($_POST["Invia"])){
                try{
                    $SQL = "SELECT Saldo FROM tmovimenticontocorrente WHERE ContoCorrenteID = ? ORDER BY Data DESC Limit 1";
                    if($statement = $conn -> prepare($SQL)){
                        $statement -> bind_param("i", $contoCorrenteID);
                        $statement -> execute();
                        
                        // Prendo il risultato della query
                        $result = $statement->get_result();
    
                        // C'è una tupla
                        if ($result->num_rows != 0) {
                            // Salvo il contenuto del result
                            while ($row = $result->fetch_assoc()) {
                                $saldo = $row['Saldo'];
                            }
                        } else {
                            $saldo = "Errore nell'acquisizione del saldo";
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
                    echo "Qualcosa è andato storto nella richiesta del saldo al db.";
                }
            
                // Ricavo le operazione avvenute nel periodo selezionato
                try{
                    $SQL = "SELECT movimenti.MovimentoID, movimenti.Data, movimenti.Importo, movimenti.Saldo, categorie.NomeCategoria 
                    FROM tmovimenticontocorrente AS movimenti JOIN tcategoriemovimenti AS categorie ON movimenti.CategoriaMovimentoID = categorie.CategoriaMovimentoID 
                    WHERE movimenti.ContoCorrenteID = ? AND movimenti.Data 
                    BETWEEN ? AND ? 
                    ORDER BY movimenti.Data DESC";
                    if($statement = $conn -> prepare($SQL)){
                        $statement -> bind_param("iss", $contoCorrenteID, $data_inizio, $data_fine);
                        $statement -> execute();
                        
                        // Prendo il risultato della query
                        $result = $statement->get_result();
    
                        $operazioni = array();
    
                        // C'è una tupla
                        if ($result->num_rows != 0) {
                            // Salvo il contenuto del result
                            while ($row = $result->fetch_assoc()) {
                                $operazioni[] = $row;
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
                    echo "Qualcosa è andato storto nella richiesta delle operazioni al db.";
                }
            }
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
  
              // Rimando a ricercaMovimenti3.php con sessione impostata
              header("Location: https://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti3.php?contoCorrenteID=$contoCorrenteID");
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
        
    // Chiudo la connessione
    $conn->close();
    ?>

<!DOCTYPE html>
<html>
    <head>
        <title>Ricerca periodo</title>
        <link rel="stylesheet" href="/css/stylesIndex.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">
        <style>
            body {
                background-color: #f8f9fa;
            }
            #centrata{
                text-align:center;
            }
            .form-control.datepicker {
                width: 200px;
                height: 34px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-primary ">
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ">
                        <li class="nav-item active">
                            <a class="nav-link" href="http://gruppo6.altervista.org/ProjectWork/php/index.php?contoCorrenteID=<?php echo $contoCorrenteID; ?>">Home</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="http://gruppo6.altervista.org/ProjectWork/php/profilo.php?contoCorrenteID=<?php echo $contoCorrenteID; ?>">Profilo <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item dropdown active ">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLink" role="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">Operazioni</a>
                            <div class="dropdown-menu rounded bg-light"  aria-labelledby="navbarDropdownLink">
                                <a class="dropdown-item " href="http://gruppo6.altervista.org/ProjectWork/php/bonifico.php?contoCorrenteID=<?php echo $contoCorrenteID; ?>">Bonifico</a>
                                <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricarica.php?contoCorrenteID=<?php echo $contoCorrenteID; ?>">Ricarica telefonica</a>
                                <!-- <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a> -->
                            </div>
                        </li>
                        <li class="nav-item dropdown active">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownDisabled" role="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">Movimenti</a>
                            <div class="dropdown-menu rounded bg-light " aria-labelledby="navbarDropdownDisabled">
                                <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti1.php?contoCorrenteID=<?php echo $contoCorrenteID; ?>">Ultimi movimenti</a>
                                <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti2.php?contoCorrenteID=<?php echo $contoCorrenteID; ?>">Cerca per categoria</a>
                                <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti3.php?contoCorrenteID=<?php echo $contoCorrenteID; ?>">Cerca per data</a>
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
            <br>

            <h4 id="centrata">Saldo: <?php echo $saldo . "€" ?></h4>
            <!-- Selezione del periodo attraverso datepicker (BOOTSTRAP) -->
            <div> 
                <h1 id="centrata">Seleziona un periodo:</h1>
                <br>
                <form action="" method="POST" id="centrata">
                    <div class="form-group" id="centrata">
                        <label for="data_inizio">Data di inizio:</label>
                        <input type="date" id="data_inizio" name="data_inizio">
                    </div>

                    <div class="form-group" id="centrata">
                        <label for="data_fine">Data di fine:</label>
                        <input type="date" id="data_fine" name="data_fine">
                    </div>

                    <input type="submit" name="Invia" value="Invia">
                </form>
            </div>

            <?php if ($operazioni != null): ?> <!-- Verifica se è stata selezionata una categoria valida -->
                <br>
                
                <h2 class="mb-4" id="centrata">Storico operazioni:</h2>
                <table class="table table-bordered mt-4 table-hover">
                    <thead >
                        <tr class="table-primary">
                            <th scope="col">Tipo Operazione</th>
                            <th scope="col">Importo</th>
                            <th scope="col">Data</th>
                            <th scope="col">Dettagli</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($operazioni as $operazione): ?>
                            <tr>
                                <td><?php echo $operazione['NomeCategoria']; ?></td>
                                <td><?php echo $operazione['Importo']; ?>€</td>
                                <td><?php $data = date("d/m/Y", strtotime($operazione['Data'])); echo $data; ?></td>
                                <td><a href="dettaglioMovimento.php?id=<?php echo $operazione['MovimentoID']; ?>">
                                <img src="http://gruppo6.altervista.org/ProjectWork/css/Immagini/details.png" alt="Icona Dettagli" width="25" height="25"></a></td>
                            </tr>
                        <?php endforeach;
                        ?>
                    </tbody>
                </table>
            <?php endif ?>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>