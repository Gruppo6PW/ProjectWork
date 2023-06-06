<?php
    // Avvio la sessione
    session_start();

    // Prendo l'id del conto corrente nell'URL
    $contoCorrenteID = $_GET["contoCorrenteID"];

    // Connessione database
    $conn = new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");

    // Verifica della connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Ricarica telefonica</title>
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
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-primary">
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
        
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div class="container py-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <h1 class="text-center">Ricarica</h1> <br>
                            <div class="card mb-4 shadow p-3 bg-body rounded">
                                <div class="card-body">
                                    <form method="post" action="" >
                                        <div class="row mb-3 ">
                                            <div class="col" >
                                                <span class="select-wrapper" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">     
                                                    <select name="operatore" id="operatore" required style="width: 250px" >
                                                        <option value="" disabled selected="selected">Seleziona un operatore</option>
                                                        <option value="Vodafone">Vodafone</option>
                                                        <option value="TIM">TIM</option>
                                                        <option value="WindTre">WindTre</option>
                                                        <option value="Illiad">Illiad</option>
                                                        <option value="Poste Mobile">Poste Mobile</option>
                                                        <option value="Coop Voce">Coop Voce</option>
                                                        <option value="Fastweb Mobile">Fastweb Mobile</option>
                                                        <option value="Ho Mobile">Ho Mobile</option>
                                                    </select>      
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row mb-3 ">
                                            <div class="col">
                                                <span class="select-wrapper" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">          
                                                    <select name="importo" id="importo" required style="width: 250px">
                                                        <option disabled selected="selected" value="">Seleziona un importo</option>
                                                        <option value="5">5€</option>
                                                        <option value="10">10€</option>
                                                        <option value="15">15€</option>
                                                        <option value="20">20€</option>
                                                        <option value="25">25€</option>
                                                        <option value="30">30€</option>
                                                        <option value="50">50€</option>
                                                        <option value="100">100€</option>
                                                    </select>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row mb-3 ">
                                            <div class="col d-flex justify-content-center">
                                                <input type="tel" class="form-control custom-input-width" min="10" max="10" style="width: 250px"placeholder="Numero di telefono" name="numero_telefono" id="numero_telefono" required >
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col text-center">
                                                <input type="submit" style="width: 250px" class="btn btn-primary" name="Ricarica" value="Ricarica" >
                                            </div>
                                        </div>
                                        <div class="row">
                                            <p id="esitoRicaricaID"></p>
                                        </div>
                                    </form>
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </main>

        <!-- Bootstrap -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <!-- PHP -->
        <?php
            if(session_status() === PHP_SESSION_ACTIVE){
                if($_SESSION["accessoEseguito"] && $_SESSION["contoCorrenteID"] == $contoCorrenteID){
                    try{
                        $SQL = "SELECT Saldo FROM tmovimenticontocorrente WHERE ContoCorrenteID = ? ORDER BY Data DESC LIMIT 1";
                        if($statement = $conn -> prepare($SQL)){
                            $statement -> bind_param("i", $contoCorrenteID);
                            $statement -> execute();
                            
                            // Prendo il risultato della query
                            $result = $statement->get_result();
        
                            // C'è una tupla
                            if ($result->num_rows != 0) {
                                // Salvo il contenuto del result
                                while ($row = $result->fetch_assoc()) {
                                    // Prendo i dati
                                    $saldo = $row['Saldo'];
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
                        echo "Qualcosa è andato storto nella richiesta del saldo al db..";
                    }
                
                    // Controllo che vengano passati tutti i valori
                    $telefono = isset($_POST['numero_telefono']) ? $_POST['numero_telefono'] : 0;
                    $operatore = isset($_POST['operatore']) ? $_POST['operatore'] : 0;
                    $importo = isset($_POST['importo']) ? $_POST['importo'] : 0;
                
                    $pattern = '/^[0-9]{10}$/'; // Espressione regolare per un numero di telefono composto da 10 cifre
                
                    if(isset($_POST['Ricarica'])){
                        if (preg_match($pattern, $telefono)) {
                            // Il numero di telefono è valido
                            try{
                                $dataCorrenteString = date("Y-m-d") . " " . date("H:i:s");
                                $dataCorrente = date_create($dataCorrenteString);
                                $descrizione = "Ricarica telefonica $operatore, $importo € al num $telefono";
                                $nuovoSaldo = (float)$saldo - (float)$importo;
                                if($nuovoSaldo<0){
                                    throw new Exception('', 1);
                                }
        
                                $SQL = "INSERT INTO tmovimenticontocorrente (ContoCorrenteID, Data, Importo, Saldo, CategoriaMovimentoID, DescrizioneEstesa) VALUES (?, ?, ?, ?, 5, ?)";
                                if($statement = $conn -> prepare($SQL)){
                                    $statement -> bind_param("isdss", $contoCorrenteID, $dataCorrenteString, $importo, $nuovoSaldo, $descrizione);
                                    $statement -> execute();
                                    
                                    // Prendo il risultato della query
                                    $result = $statement->get_result();
        
                                    // Chiudo lo statement
                                    $statement->close();
                                } else{
                                    // C'è stato un errore, lo stampo
                                    $errore = $mysqli->errno . ' ' . $mysqli->error;
                                    echo $errore;
                                }

                                echo "
                                <script> \n
                                    document.getElementById('esitoBonificoID').style.innerHTML = 'Ricarica effettuata correttamente.<br>€ $importo a favore di $telefono';
                                    document.getElementById('esitoBonificoID').style.color = 'green';
                                    document.getElementById('esitoBonificoID').style.visibility = 'visible';
                                </script> \n
                                ";
                            } catch(Exception $e){
                                $codErrore = $e->getCode();
                                if($codErrore===1){
                                    echo "
                                    <script> \n
                                        document.getElementById('esitoBonificoID').style.innerHTML = 'Qualcosa è andato storto. Controllare il saldo e riprovare.';
                                        document.getElementById('esitoBonificoID').style.color = 'red';
                                        document.getElementById('esitoBonificoID').style.visibility = 'visible';
                                    </script> \n
                                    ";
                                } else{
                                    echo "
                                    <script> \n
                                    document.getElementById('esitoBonificoID').style.innerHTML = 'Qualcosa non ha funzionato. Ricaricare la pagina e riprovare.';
                                    document.getElementById('esitoBonificoID').style.color = 'red';
                                    document.getElementById('esitoBonificoID').style.visibility = 'visible';
                                    </script> \n
                                    ";
                                }
                            }
                            //insert query
                        } else {
                            // Il numero di telefono non è valido
                            echo "
                            <script> \n
                                document.getElementById('esitoBonificoID').style.visibility = 'visible';
                                document.getElementById('esitoBonificoID').style.color = 'red';
                                document.getElementById('esitoBonificoID').style.innerHTML = 'Siamo spiacenti, abbiamo riscontrato un errore. Riprovare ricaricando la pagina e inserendo i dati corretti.';
                            </script> \n
                            ";
                        }
                    } else{
                        $html='';
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
                        $dataCorrenteString = date("Y-m-d") . " " . date("H:i:s");
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
              
                          // Rimando a ricarica.php con sessione impostata
                          header("Location: https://gruppo6.altervista.org/ProjectWork/php/ricarica.php?contoCorrenteID=$contoCorrenteID");
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
        
            // Chiudo la connessione
            $conn->close();
        ?>
    </body>
</html>