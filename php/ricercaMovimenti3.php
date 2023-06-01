<?php
    //!!!DA FIXARE CON LA SESSION UTENTE!!!
    $contoCorrenteID = 1;
    // controllo che vengano passati dei valori in post
    $data_periodo = isset($_POST['datefilter']) ? $_POST['datefilter'] : '';
    if(isset($_POST['datefilter'])){
        $date = explode(' - ', $data_periodo);
        $data_inizio = $date[0];
        $data_fine = $date[1];
    }


    // Connessione database
    //$conn=new mysqli("localhost", "gruppo6", "ZQ5Z4Dzc6Ddd", "my_gruppo6");
    $conn=new mysqli("localhost", "root", "", "my_gruppo6");
    // Verifica degli errori di connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    // Prepared statement per ricavare le operazione avvenute nel periodo selezionato
    try{
        $query = $conn->prepare("SELECT movimenti.MovimentoID, movimenti.Data, movimenti.Importo, movimenti.Saldo, 
        categorie.NomeCategoria FROM tmovimenticontocorrente AS movimenti JOIN tcategoriemovimenti AS categorie 
        ON movimenti.CategoriaMovimentoID = categorie.CategoriaMovimentoID WHERE movimenti.ContoCorrenteID = ? 
        AND movimenti.Data BETWEEN ? AND ? ORDER BY movimenti.Data");
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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">
        
    </head>
    <body>
        <header>
            <div>
            <img src="Media/searchIcon.png" alt="Icona Ricerca" width=200>
            <a href="profiloUtente.php"> <img src="Media/profileIcon.png" alt="Icona Profilo Utente" width=200> </a>
            <img src="Media/transactionIcon.png" alt="Icona Operazioni" width=200>
        </div>
    </header>
    
    <!-- Selezione del periodo attraverso datepicker (BOOTSTRAP) -->
    <h1>Seleziona un periodo:</h1>
    <form action="ricercaMovimenti3.php" method="POST">
        <div class="form-group">
            <label for="datefilter">Seleziona un periodo:</label>
            <input type="text" name="datefilter" id="datefilter" value=""/>
        </div>

        <button type="submit" class="btn btn-primary">Invia</button>
    </form>

        <?php if ($operazioni != null): ?> <!-- Verifica se è stata selezionata una categoria valida -->
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

            <script>
        $(document).ready(function() {
            $('input[name="datefilter"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });
    
            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });
    
            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
   
    </body>
</html>


