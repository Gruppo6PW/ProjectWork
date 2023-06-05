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

if(isset($_POST['invia'])){
    // controllo che vengano passati tutti i valori
    $beneficiario = isset($_POST['nomeBeneficiario']) ? $_POST['nomeBeneficiario'] : '';
    $iban = isset($_POST['ibanBeneficiario']) ? $_POST['ibanBeneficiario'] : '';
    $importo = isset($_POST['importo']) ? $_POST['importo'] : '';
    $causale = isset($_POST['causale']) ? $_POST['causale'] : '';

    if($beneficiario!=''&&$iban!=''&&$importo!=''&&$causale!=''){
        try{
            $data = date("Y-m-d-G-i-s");
            $descrizione = "Bonifico a favore di $beneficiario. $importo € inviati a $iban. Causale: $causale";
            $nuovoSaldo = (float)$saldo - (float)$importo;
            if($nuovoSaldo<0){
                throw new Exception("", 1);
            }
             $queryInsert = $conn->prepare("INSERT INTO tmovimenticontocorrente (ContoCorrenteID, Data, Importo, Saldo, CategoriaMovimentoID, DescrizioneEstesa)
              VALUES (?, ?, ?, ?, 2, ?)");
            $queryInsert->bind_param("isdss", $contoCorrenteID, $data, $importo, $nuovoSaldo, $descrizione);
            $queryInsert->execute();
            $risultato = $queryInsert->get_result();
            $queryInsert->close();

            $html = "<h2>Bonifico effettuato correttamente.<br>€ $importo a favore di $beneficiario</h2>";
        } catch(Exception $e){
            $codErrore = $e->getCode();
            if($codErrore===1){
                echo "<h2>Qualcosa è andato storto. Controllare il saldo e riprovare.</h2>";
            } else{
                echo "<h2>Qualcosa non ha funzionato. Ricarica la pagina e riprova.</h2>";
            }
            $html='';
        }
    } else {
        echo "<h2>Inserisci tutti i dati correttamente e riprova.</h2>";
    }
    } else{
        $html='';
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bonifico</title>
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
    <h2>Bonifico Bancario</h2>

    <form method="post" action="">
        <label for="nomeBeneficiario">Beneficiario:</label>
        <input type="text" name="nomeBeneficiario" id="nomeBeneficiario" placeholder="Nome Cognome" required>
        <label for="ibanBeneficiario">IBAN:</label>
        <input type="text" name="ibanBeneficiario" id="ibanBeneficiario" placeholder="" minlength="27" maxlength="27" required>
        <label for="importo">Importo:</label>
        <input type="number" name="importo" id="importo" placeholder="" min="0" step="0.01" required>
        <label for="causale">Causale:</label>
        <input type="text" name="causale" id="causale" placeholder="" required>

        <input type="submit" name="invia" value="Effettua bonifico">
        <input type="reset" name="cancella" value="Cancella"> 
    </form>
    <?php echo $html; ?>
  </main>
  <a class="button" href="index.php">Torna all'Homepage</a>
</body>
</html>

<!DOCTYPE html>
<html>
    <head>
        <title>Bonifico</title>
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
            </div>
        </nav>

        <main class="container my-5">
                
            <br> 
            
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div class="container py-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <h1 class="text-center">Bonifico</h1> <br>
                            <div class="card mb-4 shadow p-3 bg-body rounded">
                                <div class="card-body">
                                    <form method="post" action="" >
                                        <div class="row mb-3">
                                            <div class="col">
                                                <input type="text" class="form-control" name="nomeBeneficiario" id="nomeBeneficiario" required placeholder="Nome e cognome ">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <input type="text" minlength="27" maxlength="27" class="form-control" name="ibanBeneficiario" id="ibanBeneficiario" placeholder="IBAN" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <input type="number" class="form-control" placeholder="Importo" name="importo" id="importo" required  step="0.01" min="0">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <input type="text" class="form-control" placeholder="Causale"  name="causale" id="causale" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col text-center">
                                                <input type="submit" class="btn btn-primary" value="Effettua bonifico" >
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

        <!-- Bootstrap -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>

