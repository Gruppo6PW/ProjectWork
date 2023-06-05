<?php
    //!!!DA FIXARE CON LA SESSION UTENTE!!!
    $contoCorrenteID = 1;
    // controllo che vengano passati dei valori in post
    $data_inizio = isset($_POST['data_inizio']) ? $_POST['data_inizio'] : '' ;
    $data_fine = isset($_POST['data_fine']) ? $_POST['data_fine'] : '' ;

    // Connessione database
    //$conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
    $conn=new mysqli("localhost", "root", "", "my_gruppo6");
    // Verifica degli errori di connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    try{
        $query = $conn->prepare("SELECT Saldo FROM tmovimenticontocorrente WHERE ContoCorrenteID = ? ORDER BY Data DESC Limit 1");
        $query->bind_param('i', $contoCorrenteID);
        $query->execute();
        $risultato = $query->get_result();    
        if ($risultato->num_rows > 0) {
            $row = $risultato->fetch_assoc();
            $saldo = $row['Saldo'];
        } else {
            $saldo = -1;
        }
    } catch(Exception $e){
        echo "Qualcosa è andato storto nella richiesta del saldo al db.";
    }

    // Prepared statement per ricavare le operazione avvenute nel periodo selezionato
    try{
        $query = $conn->prepare("SELECT movimenti.MovimentoID, movimenti.Data, movimenti.Importo, movimenti.Saldo, 
        categorie.NomeCategoria FROM tmovimenticontocorrente AS movimenti JOIN tcategoriemovimenti AS categorie 
        ON movimenti.CategoriaMovimentoID = categorie.CategoriaMovimentoID WHERE movimenti.ContoCorrenteID = ? 
        AND movimenti.Data BETWEEN ? AND ? ORDER BY movimenti.Data DESC");
        $query->bind_param("iss", $contoCorrenteID, $data_inizio, $data_fine);
        $query->execute();
        $risultato = $query->get_result();
        $operazioni = array();
        while($row = $risultato->fetch_assoc()){
            $operazioni[] = $row;
        }
        $query->close();
    } catch(Exception $e){
        echo "Qualcosa è andato storto nella richiesta delle operazioni al db.";
    }
    $conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Ricerca periodo</title>
        <link rel="stylesheet" href="/css/stylesIndex.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">
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

                <!-- Logout -->
                <div class="nav-item dropdown bg-danger ml-auto ">
                    <a class="nav-link active" href="http://gruppo6.altervista.org/ProjectWork/php/logOut.php" style="color: white ">LogOut</a>
                </div>
            </div>
        </nav>

        <p>Saldo: <?php echo $saldo ?></p>
    <!-- Selezione del periodo attraverso datepicker (BOOTSTRAP) -->
    <h1>Seleziona un periodo:</h1>
    <form action="ricercaMovimenti3.php" method="POST">
        <div class="form-group">
            <label for="data_inizio">Data di inizio:</label>
            <input type="text" class="form-control datepicker" id="data_inizio" name="data_inizio">
        </div>

        <!-- Selezione del periodo attraverso datepicker (BOOTSTRAP) -->
        <h1>Seleziona un periodo:</h1>

        <form action="ricercaMovimenti3.php" method="POST">
            <div class="md-form md-outline input-with-post-icon datepicker">
                <input type="date" placeholder="Select date"   class="form-control datepicker" id="data_inizio" name="data_inizio">
                <label for="data_inizio">Data di inizio:</label>
            </div>

            <div class="form-group">
                <label for="data_fine">Data di fine:</label>
                <input type="text" class="form-control datepicker" id="data_fine" name="data_fine">
            </div>

            <button type="submit" class="btn btn-primary">Invia</button>
        </form>

        <script>
            $(document).ready(function() {
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
            });
        </script>

        <?php if ($operazioni != null): ?> <!-- Verifica se è stata selezionata una categoria valida -->
            <h2>Storico operazioni:</h2>
            <table>
                <thead>
                    <tr>
                        <td><?php echo $operazione['NomeCategoria']; ?></td>
                        <td><?php echo $operazione['Importo']; ?>€</td>
                        <td><?php echo $operazione['Data']; ?></td>
                        <td><a href="DettaglioMovimento.php?id=<?php echo $operazione['MovimentoID']; ?>" target="_blank">
                        <img src="Media/details.png" alt="Icona Dettagli" width="25"></a></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($operazioni as $operazione): ?>
                            <tr>
                                <td><?php echo $operazione['NomeCategoria']; ?></td>
                                <td><?php echo $operazione['Importo']; ?>€</td>
                                <td><?php echo $operazione['Data']; ?></td>
                                <td><a href="http://gruppo6.altervista.org/ProjectWork/php/dettaglioMovimento.php?id=<?php echo $operazione['MovimentoID']; ?>" target="_blank">
                                <img src="Media/details.png" alt="Icona Dettagli" width="25"></a></td>
                            </tr>
                        <?php endforeach;
                    ?>
                </tbody>
            </table>
        <?php endif ?>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>
