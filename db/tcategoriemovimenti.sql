-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 26, 2023 alle 17:34
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

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tcategoriemovimenti`
--
ALTER TABLE `tcategoriemovimenti`
  ADD PRIMARY KEY (`CategoriaMovimentoID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
