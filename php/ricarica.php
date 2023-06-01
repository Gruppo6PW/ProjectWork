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
    <h2>Ricarica telefonica</h2>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <select name="operatore" id="operatore" required>
            <option value="">Seleziona un operatore</option>
            <option value="Vodafone">Vodafone</option>
            <option value="TIM">TIM</option>
            <option value="WindTre">WindTre</option>
            <option value="Illiad">Illiad</option>
            <option value="Poste Mobile">Poste Mobile</option>
            <option value="Coop Voce">Coop Voce</option>
            <option value="Fastweb Mobile">Fastweb Mobile</option>
            <option value="Ho Mobile">Ho Mobile</option>
        </select>
        <select name="importo" id="importo" required>
            <option value="">Seleziona un importo</option>
            <option value="5">5€</option>
            <option value="10">10€</option>
            <option value="15">15€</option>
            <option value="20">20€</option>
            <option value="25">25€</option>
            <option value="30">30€</option>
            <option value="50">50€</option>
            <option value="100">100€</option>
        </select>
        <br><br>
        <label for="numero_telefono">Numero di telefono:</label>
        <input type="tel" name="numero_telefono" id="numero_telefono" placeholder="Numero di telefono" minlength="10" maxlength="10" required>
        <br><br>
        <input type="submit" name="invia" value="Effettua ricarica">
        <input type="reset" name="cancella" value="Cancella"> 
    </form>
    <?php echo $html; ?>

    <a class="button" href="index.php">Torna all'Homepage</a>

  </main>
</body>
</html>
