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
    </head>
    <body>
        <header>
            <div>
            <img src="Media/searchIcon.png" alt="Icona Ricerca" width=200>
            <a href="profiloUtente.php"> <img src="Media/profileIcon.png" alt="Icona Profilo Utente" width=200> </a>
            <img src="Media/transactionIcon.png" alt="Icona Operazioni" width=200>
            </div>
        </header>

        <h1>Inserisci il numero di righe da visualizzare:</h1>
        <form action="ricercaMovimenti1.php" method="GET">
            <input type="number" name="numeroRighe">
            <button type="submit">Mostra</button>
        </form>
        <?php if ($numeroRighe > 0): ?> <!-- Verifica se è stata selezionata una categoria valida -->
        <h2>Storico operazioni:</h2>
        <table>
            <thead>
                <tr>
                    <th>Tipo Operazione</th>
                    <th>Importo</th>
                    <th>Data</th>
                    <th></th>
                </tr>
            </thead>
        <tbody>
           <?php
                foreach ($operazioni as $operazione): ?>
                    <tr>
                        <td><?php echo $operazione['NomeCategoria']; ?></td>
                        <td><?php echo $operazione['Importo']; ?>€</td>
                        <td><?php echo $operazione['Data']; ?></td>
                        <td><a href="DettaglioMovimento.php?id=<?php echo $operazione['MovimentoID']; ?>">
                        <img src="Media/details.png" alt="Icona Dettagli" width="25"></a></td>
                    </tr>
            <?php endforeach;
            ?>
        </tbody>
    </table>
    <?php endif ?>
    </body>
</html>


