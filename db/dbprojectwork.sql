-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 26, 2023 alle 17:32
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbprojectwork`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `taccessi`
--

CREATE TABLE `taccessi` (
  `AccessoID` int(11) NOT NULL,
  `Indirizzo IP` text NOT NULL,
  `Data` datetime NOT NULL,
  `AccessoValido` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `tcategoriemovimenti`
--

CREATE TABLE `tcategoriemovimenti` (
  `CategoriaMovimentoID` int(11) NOT NULL,
  `NomeCategoria` text NOT NULL,
  `Tipologia` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tcategoriemovimenti`
--

INSERT INTO `tcategoriemovimenti` (`CategoriaMovimentoID`, `NomeCategoria`, `Tipologia`) VALUES
(0, 'Apertura conto', 'Apertura'),
(1, 'Bonifico in entrata', 'Entrata'),
(2, 'Bonifico in uscita', 'Uscita'),
(3, 'Prelievo in contanti-Bancomat', 'Uscita'),
(4, 'Pagamento utenze', 'Uscita'),
(5, 'Ricarica', 'Uscita'),
(6, 'Versamento in contanti-Bancomat', 'Entrata'),
(7, 'Pagamento con carta di credito collegata', 'Uscita');

-- --------------------------------------------------------

--
-- Struttura della tabella `tconticorrenti`
--

CREATE TABLE `tconticorrenti` (
  `ContoCorrenteID` int(11) NOT NULL,
  `Email` text NOT NULL,
  `Password` text NOT NULL,
  `CognomeTitolare` text NOT NULL,
  `NomeTitolare` text NOT NULL,
  `DataApertura` datetime NOT NULL,
  `IBAN` text DEFAULT NULL,
  `RegistrazioneConfermata` bit(1) NOT NULL,
  `Token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tconticorrenti`
--

INSERT INTO `tconticorrenti` (`ContoCorrenteID`, `Email`, `Password`, `CognomeTitolare`, `NomeTitolare`, `DataApertura`, `IBAN`, `RegistrazioneConfermata`, `Token`) VALUES
(1, 'claudiaschiavone2@gmail.com', 'wHS8l8VyZe8tO5J', 'Schiavone', 'Claudia', '2023-05-26 15:11:39', 'IT59O0300203280481447977572', b'0', 'jkbdf65'),
(2, 'riccardopanicucci29@gmail.com', 'Fxap5ElyBkOq3RT', 'Panicucci', 'Riccardo', '2023-05-26 15:15:59', 'IT06R0300203280965384535441', b'0', 'febrt832'),
(3, 'noemiloggia153@gmail.com', 'OB3vqs0kyMTDi3b', 'Loggia', 'noemi', '2023-05-26 15:18:00', 'IT15W0300203280788344212161', b'0', 'dfabvtr5433');

-- --------------------------------------------------------

--
-- Struttura della tabella `tmovimenticontocorrente`
--

CREATE TABLE `tmovimenticontocorrente` (
  `MovimentoID` int(11) NOT NULL,
  `ContoCorrenteID` int(11) NOT NULL,
  `Data` datetime NOT NULL,
  `Importo` float NOT NULL,
  `Saldo` float NOT NULL,
  `CategoriaMovimentoID` int(11) NOT NULL,
  `DescrizioneEstesa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tmovimenticontocorrente`
--

INSERT INTO `tmovimenticontocorrente` (`MovimentoID`, `ContoCorrenteID`, `Data`, `Importo`, `Saldo`, `CategoriaMovimentoID`, `DescrizioneEstesa`) VALUES
(1, 1, '2023-05-26 15:21:01', 3500, 3500, 1, 'Emolumenti per il mese di aprile 2023'),
(2, 1, '2023-05-26 15:26:54', 350, 3850, 1, 'Premio di produttività/ Primo trimestre 2023'),
(3, 1, '2023-05-26 15:29:10', 250, 3600, 7, 'Acquisto online tramite carta di credito di una console di gioco'),
(4, 1, '2023-05-26 15:30:28', 25, 3575, 7, 'Acquisto online tramite carta di credito di gioielli '),
(5, 1, '2023-05-26 15:31:45', 75, 3500, 2, 'Bonifico in uscita per tasse scolastiche'),
(6, 1, '2023-05-26 15:33:29', 500, 3000, 2, 'Bonifico in uscita per il pagamento dell\'assicurazione della macchina'),
(7, 1, '2023-05-26 15:34:39', 300, 2700, 3, 'Prelievo in contanti presso sportello'),
(8, 1, '2023-05-26 15:35:35', 200, 2500, 4, 'Pagamento utenze mese di aprile'),
(9, 1, '2023-05-26 15:37:08', 150, 2650, 6, 'Deposito in contanti presso bancomat'),
(10, 1, '2023-05-26 15:37:59', 15, 2635, 5, 'Ricarica telefonica mensile'),
(11, 2, '2023-05-26 15:40:01', 1500, 1500, 1, 'Emolumenti per il mese di marzo 2023'),
(12, 2, '2023-05-26 15:41:12', 100, 1400, 7, 'Acquisto online tramite carta di credito'),
(13, 2, '2023-05-26 15:41:52', 150, 1250, 2, 'Bonifico in uscita per l\'acquisto di libri scolastici'),
(14, 2, '2023-05-26 15:44:09', 20, 1230, 5, 'Ricarica telefonica mensile'),
(15, 2, '2023-05-26 15:45:18', 200, 1030, 2, 'Bonifico in  uscita per il pagamento della rata mensile della macchina'),
(16, 2, '2023-05-26 15:46:24', 70, 1100, 1, 'Rimborso spese trasferta di lavoro'),
(17, 2, '2023-05-26 15:47:11', 300, 800, 3, 'Prelievo in contanti al bancomat'),
(18, 2, '2023-05-26 15:47:58', 1200, 2000, 1, 'Emolumenti mese di aprile 2023'),
(19, 2, '2023-05-26 15:48:57', 250, 1750, 7, 'Acquisto online tramite carta di credito'),
(20, 2, '2023-05-26 15:49:20', 250, 1500, 2, 'Pagamento bollette mese di maggio 2023');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `taccessi`
--
ALTER TABLE `taccessi`
  ADD PRIMARY KEY (`AccessoID`);

--
-- Indici per le tabelle `tcategoriemovimenti`
--
ALTER TABLE `tcategoriemovimenti`
  ADD PRIMARY KEY (`CategoriaMovimentoID`);

--
-- Indici per le tabelle `tconticorrenti`
--
ALTER TABLE `tconticorrenti`
  ADD PRIMARY KEY (`ContoCorrenteID`),
  ADD UNIQUE KEY `Email` (`Email`) USING HASH,
  ADD UNIQUE KEY `IBAN` (`IBAN`) USING HASH,
  ADD UNIQUE KEY `Token` (`Token`) USING HASH;

--
-- Indici per le tabelle `tmovimenticontocorrente`
--
ALTER TABLE `tmovimenticontocorrente`
  ADD PRIMARY KEY (`MovimentoID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `taccessi`
--
ALTER TABLE `taccessi`
  MODIFY `AccessoID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `tconticorrenti`
--
ALTER TABLE `tconticorrenti`
  MODIFY `ContoCorrenteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `tmovimenticontocorrente`
--
ALTER TABLE `tmovimenticontocorrente`
  MODIFY `MovimentoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
