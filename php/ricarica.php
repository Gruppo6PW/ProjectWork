<?php
// Connessione database
//$conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
$conn=new mysqli("localhost", "root", "", "my_gruppo6");
// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

//IMPLEMENTARE SESSION
$contoCorrenteID = 1;
try{
    //query per ottere l'ultimo saldo disponibile, necessario per la query insert
    $query = $conn->prepare("SELECT Saldo FROM tmovimenticontocorrente WHERE ContoCorrenteID = ? ORDER BY Data DESC LIMIT 1");
    $query->bind_param("i", $contoCorrenteID);
    $query->execute();
    $query->bind_result($saldo);
    $query->fetch();
    $query->close();
} catch(Exception $e){
    echo "Qualcosa è andato storto nella richiesta del saldo al db..";
}

// controllo che vengano passati tutti i valori
$telefono = isset($_POST['numero_telefono']) ? $_POST['numero_telefono'] : 0;
$operatore = isset($_POST['operatore']) ? $_POST['operatore'] : 0;
$importo = isset($_POST['importo']) ? $_POST['importo'] : 0;

$pattern = '/^[0-9]{10}$/'; // Espressione regolare per un numero di telefono composto da 10 cifre
if(isset($_POST['numero_telefono'])){
}

if(isset($_POST['invia'])){
    if (preg_match($pattern, $telefono)) {
        // Il numero di telefono è valido
        //echo 'Ricarica effettuata correttamente.' , $telefono , $operatore;
        try{
            $data = date("Y-m-d-G-i-s");
            $descrizione = "Ricarica telefonica $operatore, $importo € al num $telefono";
            $nuovoSaldo = (float)$saldo - (float)$importo;
            if($nuovoSaldo<0){
                throw new Exception('', 1);
            }
            $queryInsert = $conn->prepare("INSERT INTO tmovimenticontocorrente (ContoCorrenteID, Data, Importo, Saldo, CategoriaMovimentoID, DescrizioneEstesa)
          VALUES (?, ?, ?, ?, 5, ?)");
            $queryInsert->bind_param("isdss", $contoCorrenteID, $data, $importo, $nuovoSaldo, $descrizione);
            $queryInsert->execute();
            $risultato = $queryInsert->get_result();
            $queryInsert->close();

            $html="<h2>Ricarica effettuata correttamente.<br>€ $importo a favore di $telefono</h2>";
        } catch(Exception $e){
            $codErrore = $e->getCode();
            if($codErrore===1){
                echo "<h2>Qualcosa è andato storto. Controllare il saldo e riprovare.<h2>/";
            } else{
            echo "<h2>Qualcosa è andato storto. Rircaricare la pagina e riprovare.</h2>";
            }
        }
        //insert query
    } else {
        // Il numero di telefono non è valido
        echo 'Siamo spiacenti, abbiamo riscontrato un errore. Riprovare ricaricando la pagina e inserendo i dati corretti.';
    }
    } else{
        $html='';
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
                        <a class="nav-link" href="http://gruppo6.altervista.org/ProjectWork/php/index.php">Home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="http://gruppo6.altervista.org/ProjectWork/php/profilo.php">Profilo <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item dropdown active ">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLink" role="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">Operazioni</a>
                        <div class="dropdown-menu rounded bg-light"  aria-labelledby="navbarDropdownLink">
                            <a class="dropdown-item " href="http://gruppo6.altervista.org/ProjectWork/php/bonifico.php">Bonifico</a>
                            <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricarica.php">Ricarica telefonica</a>
                            <!-- <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a> -->
                        </div>
                    </li>
                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownDisabled" role="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">Movimenti</a>
                        <div class="dropdown-menu rounded bg-light " aria-labelledby="navbarDropdownDisabled">
                            <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti1.php">Ultimi movimenti</a>
                            <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti2.php">Cerca per categoria</a>
                            <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti3.php">Cerca per data</a>
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
                                                <input style="width: 250px" type="submit" class="btn btn-primary" value="Ricarica" >
                                            </div>
                                        </div>  
                                    </form>
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>