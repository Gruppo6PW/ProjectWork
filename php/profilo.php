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

    // Prepared statement per ricavare i dati utente (nome, data apertura conto)
    try{
        $SQL = "SELECT NomeTitolare, CognomeTitolare, DataApertura, Email, Iban FROM tconticorrenti WHERE ContoCorrenteID = ? LIMIT 1";
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
                    $nomeUtente = $row['NomeTitolare'];
                    $cognomeUtente = $row['CognomeTitolare'];
                    $dataAperturaDB = $row['DataApertura'];
                    $email = $row['Email'];
                    $iban = $row['Iban'];

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

    // Chiudo la connessione
    $conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Gestione profilo</title>
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
                            <a class="dropdown-item " href="http://gruppo6.altervista.org/ProjectWork/php/bonifico.php?contoCorrenteID?<?php echo $contoCorrenteID ?>">Bonifico</a>
                            <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricarica.php?contoCorrenteID?<?php echo $contoCorrenteID ?>">Ricarica telefonica</a>
                            <!-- <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a> -->
                        </div>
                    </li>   
                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownDisabled" role="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">Movimenti</a>
                        <div class="dropdown-menu rounded bg-light " aria-labelledby="navbarDropdownDisabled">
                            <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti1.php?contoCorrenteID?<?php echo $contoCorrenteID ?>">Ultimi movimenti</a>
                            <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti2.php?contoCorrenteID?<?php echo $contoCorrenteID ?>">Cerca per categoria</a>
                            <a class="dropdown-item" href="http://gruppo6.altervista.org/ProjectWork/php/ricercaMovimenti3.php?contoCorrenteID?<?php echo $contoCorrenteID ?>">Cerca per data</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        
        <main class="container my-5">
            <br> 
            <br>

            <div style="display: flex; align-items: center; justify-content: space-between;">
                <h1 id="centrata" style="text-align: center; flex-grow: 1;">Il mio profilo</h1>
            </div>

            <br>

            <section style="background-color:#f8f9fa;">
                <div class="container py-5">
                    <div class="row">
                        <div class="col"></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card mb-4 shadow p-3 mb-5 bg-body rounded">
                                <div class="card-body text-center">
                                    <img src="http://gruppo6.altervista.org/ProjectWork/css/Immagini/profileIcon.png" alt="avatar" class="rounded-circle img-fluid" style="width: 171px;">
                                    <h5 class="my-3"><?php echo $nomeUtente ?> <?php echo $cognomeUtente ?></h5>
                        
                                    <a href="http://gruppo6.altervista.org/ProjectWork/php/modificaPassword.php">Modifica password</a>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-lg-8">
                            <div class="card mb-4 shadow p-3 mb-5 bg-body rounded">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Nome </p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0"><?php echo $nomeUtente ?> </p>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Cognome</p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0"><?php echo $cognomeUtente ?></p>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Email</p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0"><?php echo $email ?></p>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Data di apertura</p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0"><?php echo $dataApertura ?></p>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">IBAN</p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0"><?php echo $iban ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

            
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>