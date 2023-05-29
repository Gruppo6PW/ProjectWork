-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 26, 2023 alle 17:35
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

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tconticorrenti`
--
ALTER TABLE `tconticorrenti`
  ADD PRIMARY KEY (`ContoCorrenteID`),
  ADD UNIQUE KEY `Email` (`Email`) USING HASH,
  ADD UNIQUE KEY `IBAN` (`IBAN`) USING HASH,
  ADD UNIQUE KEY `Token` (`Token`) USING HASH;

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `tconticorrenti`
--
ALTER TABLE `tconticorrenti`
  MODIFY `ContoCorrenteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
