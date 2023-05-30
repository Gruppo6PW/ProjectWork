<?php
    //!!!DA FIXARE CON LA SESSION UTENTE!!!
    $contoCorrenteID = 1;
    $categoriaID = isset($_GET['categoria']) ? $_GET['categoria'] : 0;
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
        $query1 = $conn->prepare("SELECT movimenti.MovimentoID, movimenti.Data, movimenti.Importo, movimenti.Saldo, categorie.NomeCategoria 
        FROM tmovimenticontocorrente AS movimenti JOIN tcategoriemovimenti AS categorie ON 
        movimenti.CategoriaMovimentoID = categorie.CategoriaMovimentoID WHERE movimenti.ContoCorrenteID = ? AND movimenti.CategoriaMovimentoID = ? ORDER BY movimenti.Data");
        $query1->bind_param("ii", $contoCorrenteID, $categoriaID);
        $query1->execute();
        $risultato1 = $query1->get_result();
        $operazioni = array();
        while($row = $risultato1->fetch_assoc()){
            $operazioni[] = $row;
        }
        $query1->close();
    } catch(Exception $e){
        echo "Qualcosa è andato storto nella richiesta delle operazioni al db1.";
    }
    //prepared statement per ottenere le categorie
    try{
        $query2 = $conn->prepare("SELECT CategoriaMovimentoID, NomeCategoria FROM tcategoriemovimenti WHERE 
        CategoriaMovimentoID BETWEEN 1 AND 7");
        $query2->execute();
        $risultato2 = $query2->get_result();
        $categorie = array();
        while($row = $risultato2->fetch_assoc()){
            $categorie[] = $row;
        }
        $query2->close();
    } catch(Exception $e){
        echo "Qualcosa è andato storto nella richiesta delle operazioni al db2.";
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

        <h1>Scegli la categoria da visualizzare:</h1>
        <form action="ricercaMovimenti2.php" method="GET">
            <select name="categoria" onchange="this.form.submit()">
                <option value="">Seleziona una categoria</option> <!-- Opzione vuota di default -->
                <?php foreach ($categorie as $categoria): ?>
                    <option value="<?php echo $categoria['CategoriaMovimentoID']; ?>">
                        <?php echo $categoria['NomeCategoria']; ?>
                    </option>
                <?php endforeach ?>
            </select>
        </form>
        <?php if ($categoriaID > 0): ?> <!-- Verifica se è stata selezionata una categoria valida -->
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
            <?php foreach ($operazioni as $operazione): ?>
                    <tr>
                        <td><?php echo $operazione['NomeCategoria']; ?></td>
                        <td><?php echo $operazione['Importo']; ?>€</td>
                        <td><?php echo $operazione['Data']; ?></td>
                        <td><a href="DettaglioMovimento.php?id=<?php echo $operazione['MovimentoID']; ?>">
                        <img src="Media/details.png" alt="Icona Dettagli" width="25"></a></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php endif ?>
    </body>
</html>