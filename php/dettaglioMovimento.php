<?php
$movimentoID = $_GET["id"];

// Connessione database
//$conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
$conn=new mysqli("localhost", "root", "", "my_gruppo6");

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Prepared statement per ricavare i dati movimento
try{
    $query = $conn->prepare("SELECT movimenti.Data, movimenti.Importo, movimenti.DescrizioneEstesa, categorie.NomeCategoria FROM tmovimenticontocorrente AS movimenti JOIN tcategoriemovimenti AS categorie ON movimenti.CategoriaMovimentoID = categorie.CategoriaMovimentoID WHERE movimenti.MovimentoID = ?");
    $query->bind_param("i", $movimentoID);
    $query->execute();
    $risultato = $query->get_result();
    $datiMovimento = $risultato->fetch_assoc();
    $query->close();
} catch(Exception $e){
    echo "Qualcosa è andato storto nella richiesta dei dati del movimento al db.";
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Dettagli Operazione</title>
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

    <h1>Tabella Dettagli Movimento</h1>
    <table>
        <tr>
            <th>Categoria Movimento</th>
            <td><?php echo $datiMovimento['NomeCategoria']; ?></td>
        </tr>
        <tr>
            <th>Data</th>
            <td><?php echo $datiMovimento['Data']; ?></td>
        </tr>
        <tr>
            <th>Importo</th>
            <td><?php echo $datiMovimento['Importo']; ?>€</td>
        </tr>
        <tr>
            <th>DescrizioneEstesa</th>
            <td><?php echo $datiMovimento['DescrizioneEstesa']; ?></td>
        </tr>
    </table>
</body>

<!DOCTYPE html>
<html>
    <head>
        <title>Dettagli Operazione</title>
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

            <h2 class="mb-4" id="centrata">Dettaglio movimento:</h2>

            <table class="table table-bordered mt-4 table-hover">
                <thead>
                    <tr class="table-primary">
                        <th scope="col">Tipo Operazione</th>
                        <th scope="col"> Data</th>
                        <th scope="col">Importo </th>
                        <th scope="col">Descrizione</th>
                    </tr>
                </thead>
                
                <tbody>
                    <tr>	
                        <td><?php echo $datiMovimento['NomeCategoria'];?></td>
                        <td><?php echo $datiMovimento['Data']; ?></td>
                        <td><?php echo $datiMovimento['Importo']; ?>€</td>
                        <td><?php echo $datiMovimento['DescrizioneEstesa']; ?></td>
                    </tr>
                </tbody>
            </table>
                        
            <button style="font-size: 20px; background: #007bff; color:#ffffff;" type='button' class='btn btn-block create-account ' name='Indietro' onclick='functionIndietro()'>Indietro</button>
                        
            <script>
                function functionIndietro()
                {
                            
                }
            </script>
                    
        </main>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>