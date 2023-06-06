<?php
    // Avvio la sessione
    session_start();

    // Prendo l'id del conto corrente nell'URL
    $contoCorrenteID = $_GET["contoCorrenteID"];

    // Prendo l'id della categoria
    $categoriaID = isset($_GET['categoria']) ? $_GET['categoria'] : 0;

    // Connessione database
    $conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
    
    // Verifica degli errori di connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    if(session_status() === PHP_SESSION_ACTIVE){
        if($_SESSION["accessoEseguito"] && $_SESSION["contoCorrenteID"] == $contoCorrenteID){
            // Ottenimento del numero di righe dalla richiesta GET
            $numeroRighe = isset($_GET['numeroRighe']) ? $_GET['numeroRighe'] : 0;

            // Prepared statement per ricavare le ultime n operazioni
            try{
                $SQL = "SELECT movimenti.MovimentoID, movimenti.Data, movimenti.Importo, movimenti.Saldo, categorie.NomeCategoria 
                FROM tmovimenticontocorrente AS movimenti JOIN tcategoriemovimenti AS categorie ON movimenti.CategoriaMovimentoID = categorie.CategoriaMovimentoID 
                WHERE movimenti.ContoCorrenteID = ? AND movimenti.CategoriaMovimentoID = ? 
                ORDER BY movimenti.Data";
                if($statement = $conn -> prepare($SQL)){
                    $statement -> bind_param("ii", $contoCorrenteID, $categoriaID);
                    $statement -> execute();
                    
                    // Prendo il risultato della query
                    $result = $statement->get_result();

                    $operazioni = array();

                    // C'è una tupla
                    if ($result->num_rows != 0) {
                        $hasTuple = true;
                        // Salvo il contenuto del result
                        while ($row = $result->fetch_assoc()) {
                            $operazioni[] = $row;
                        }
                    } else{
                        // Avviso che non ha dati
                        $esito = "<h2 id='centrata' style='color: #E00000;'>Nessun dato presente</h2>";
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

            // Ottengo le categorie
            try{
                $SQL = "SELECT CategoriaMovimentoID, NomeCategoria FROM tcategoriemovimenti WHERE CategoriaMovimentoID BETWEEN 1 AND 7";
                if($statement = $conn -> prepare($SQL)){
                    $statement -> execute();
                    
                    // Prendo il risultato della query
                    $result = $statement->get_result();

                    $categorie = array();

                    // C'è una tupla
                    if ($result->num_rows != 0) {
                        // Salvo il contenuto del result
                        while ($row = $result->fetch_assoc()) {
                            $categorie[] = $row;
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
                echo "Qualcosa è andato storto nella richiesta delle operazioni al db2.";
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
      
                  // Rimando a ricercaMovimenti2.php con sessione impostata
                  header("Location: https://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti2.php?contoCorrenteID=$contoCorrenteID");
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

    // Chiusura connessione
    $conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Categorie movimenti</title>
        <link rel="stylesheet" href="/css/stylesIndex.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
                        <a class="nav-link" href="http://gruppo6.altervista.org/ProjectWork/php/index.php?contoCorrenteID=<?php echo $contoCorrenteID ?>">Home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="http://gruppo6.altervista.org/ProjectWork/php/profilo.php?contoCorrenteID=<?php echo $contoCorrenteID ?>">Profilo <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item dropdown active ">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLink" role="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">Operazioni</a>
                        <div class="dropdown-menu rounded bg-light"  aria-labelledby="navbarDropdownLink">
                            <a class="dropdown-item " href="http://gruppo6.altervista.org/ProjectWork/php/bonifico.php?contoCorrenteID=<?php echo $contoCorrenteID ?>">Bonifico</a>
                            <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricarica.php?contoCorrenteID=<?php echo $contoCorrenteID ?>">Ricarica telefonica</a>
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

            <h1 class="mb-4" id="centrata">Scegli la categoria da visualizzare:</h1>
            <form action="" method="GET" id="centrata">
                <select name="categoria" onchange="this.form.submit()">
                    <option value="" selected="selected" disabled >Seleziona una categoria</option> <!-- Opzione vuota di default -->
                    <?php foreach ($categorie as $categoria): ?>
                        <option value="<?php echo $categoria['CategoriaMovimentoID']; ?>">
                            <?php echo $categoria['NomeCategoria']; ?>
                        </option>
                    <?php endforeach ?>
                </select>

                <!-- Input hidden per mantenere il contoCorrenteID -->
                <input type="hidden" name="contoCorrenteID" value="<?php echo $contoCorrenteID; ?>">
            </form>
                
            <?php if ($categoriaID > 0 && $hasTuple): ?> <!-- Verifica se è stata selezionata una categoria valida -->
                <br>

                <h2 id="centrata">Storico operazioni:</h2>
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
                            <?php foreach ($operazioni as $operazione): ?>
                                <tr>
                                    <td><?php echo $operazione['NomeCategoria']; ?></td>
                                    <td><?php echo $operazione['Importo']; ?>€</td>
                                    <td><?php echo $operazione['Data']; ?></td>
                                    <td><a href="http://gruppo6.altervista.org/ProjectWork/php/dettaglioMovimento.php?id=<?php echo $operazione['MovimentoID']; ?>" target="_blank">
                                    <img src="http://gruppo6.altervista.org/ProjectWork/css/Immagini/details.png" alt="Icona Dettagli" width="25" height="25"></a></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <?php else: 
                        echo $esito;?>
            <?php endif ?>
        </main>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>