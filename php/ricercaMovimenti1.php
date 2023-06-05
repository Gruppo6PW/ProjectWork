<?php
    //!!!DA FIXARE CON LA SESSION UTENTE!!!
    $contoCorrenteID = 1;
    // Connessione database
    //$conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
    $conn=new mysqli("localhost", "root", "", "my_gruppo6");
    // Verifica degli errori di connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }
    // Ottenimento del numero di righe dalla richiesta GET
    $numeroRighe = isset($_GET['numeroRighe']) ? $_GET['numeroRighe'] : 0;
    // Prepared statement per ricavare le ultime n operazioni
    try{
        $query = $conn->prepare("SELECT movimenti.MovimentoID, movimenti.Data, movimenti.Importo, movimenti.Saldo, categorie.NomeCategoria 
        FROM tmovimenticontocorrente AS movimenti JOIN tcategoriemovimenti AS categorie ON 
        movimenti.CategoriaMovimentoID = categorie.CategoriaMovimentoID WHERE movimenti.ContoCorrenteID = ? ORDER BY movimenti.Data 
        DESC LIMIT ?");
        $query->bind_param("ii", $contoCorrenteID, $numeroRighe);
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
        <title>Ricerca n movimenti</title>
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

                <!-- Logout -->
                <div class="nav-item dropdown bg-danger ml-auto ">
                    <a class="nav-link active" href="http://gruppo6.altervista.org/ProjectWork/php/logOut.php" style="color: white ">LogOut</a>
                </div>
            </div>
        </nav>

  
        <main class="container my-5">
            <br>
            <br>
            
            <h1 class="mb-4" id="centrata">Inserisci il numero di righe da visualizzare:</h1>
                
            <form action="ricercaMovimenti1.php" method="GET" class="mb-4" id="centrata">
                    <input type="number" name="numeroRighe" min="0">
                    <button type="submit">Mostra</button>
            </form>

            <?php if ($numeroRighe > 0): ?> <!-- Verifica se è stata selezionata una categoria valida -->
                <h2 class="mb-4" id="centrata">Storico operazioni:</h2>
                <table class="table table-bordered mt-4 table-hover">
                    <thead>
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
                                <td><?php echo $operazione['Data']; ?></td>
                                <td><a href="http://gruppo6.altervista.org/ProjectWork/php/dettaglioMovimento.php?id=<?php echo $operazione['MovimentoID']; ?>" target="_blank">
                                <img src="http://gruppo6.altervista.org/ProjectWork/css/Immagini/details.png" alt="Icona Dettagli" width="25" height="25"></a></td>
                            </tr>
                        <?php endforeach;
                        ?>
                    </tbody>
                </table>
            <?php endif ?>
        </main>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>