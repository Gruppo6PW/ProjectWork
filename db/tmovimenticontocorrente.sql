-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2023 at 04:32 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `tmovimenticontocorrente`
--

CREATE TABLE `tmovimenticontocorrente` (
  `MovimentoID` int(11) NOT NULL,
  `ContoCorrenteID` int(11) NOT NULL,
  `Data` text NOT NULL,
  `Importo` float NOT NULL,
  `Saldo` float NOT NULL,
  `CategoriaMovimentoID` int(11) NOT NULL,
  `DescrizioneEstesa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tmovimenticontocorrente`
--

INSERT INTO `tmovimenticontocorrente` (`MovimentoID`, `ContoCorrenteID`, `Data`, `Importo`, `Saldo`, `CategoriaMovimentoID`, `DescrizioneEstesa`) VALUES
(1, 1, '2020-02-01 07:30:00', 0, 0, 0, 'Apertura del conto di Alberto Basso.'),
(2, 1, '2020-02-10 09:00:00', 1000, 1000, 1, 'Bonifico in entrata.'),
(3, 1, '2020-02-12 21:00:00', 10, 990, 5, 'Ricarica telefonica.'),
(4, 3, '2021-12-10 12:30:00', 0, 0, 0, 'Apertura del conto di Mattia Pozza.'),
(5, 3, '2021-12-10 14:30:00', 650, 650, 1, 'Bonifico in entrata.'),
(6, 3, '2021-12-17 11:30:00', 250, 400, 7, 'Pagamento con Carta di Credito.'),
(7, 3, '2021-12-19 11:30:00', 1500, 1900, 1, 'Bonifico in entrata.'),
(8, 2, '2021-06-10 16:30:00', 0, 0, 0, 'Apertura del conto di Samuele Carnacini.'),
(9, 2, '2021-06-10 18:30:00', 15000, 15000, 1, 'Bonifico in entrata.'),
(10, 2, '2021-06-14 08:00:00', 7000, 8000, 2, 'Bonifico in uscita.'),
(11, 2, '2021-06-17 08:00:00', 25, 7975, 5, 'Ricarica telefonica.'),
(12, 2, '2021-06-17 08:00:00', 375, 7600, 2, 'Bonifico in uscita.'),
(13, 4, '2022-09-21 14:30:00', 0, 0, 0, 'Apertura del conto per Tommaso Zini.'),
(14, 4, '2022-09-21 19:30:00', 360, 360, 1, 'Bonifico in entrata.'),
(15, 4, '2022-09-22 10:00:00', 25, 335, 5, 'Ricarica telefonica.'),
(16, 4, '2022-09-22 11:00:00', 25, 310, 5, 'Ricarica telefonica.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tmovimenticontocorrente`
--
ALTER TABLE `tmovimenticontocorrente`
  ADD PRIMARY KEY (`MovimentoID`),
  ADD KEY `ContoCorrenteID` (`ContoCorrenteID`),
  ADD KEY `CategoriaMovimentoID` (`CategoriaMovimentoID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tmovimenticontocorrente`
--
ALTER TABLE `tmovimenticontocorrente`
  MODIFY `MovimentoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tmovimenticontocorrente`
--
ALTER TABLE `tmovimenticontocorrente`
  ADD CONSTRAINT `tmovimenticontocorrente_ibfk_1` FOREIGN KEY (`ContoCorrenteID`) REFERENCES `tconticorrenti` (`ContoCorrenteID`),
  ADD CONSTRAINT `tmovimenticontocorrente_ibfk_2` FOREIGN KEY (`CategoriaMovimentoID`) REFERENCES `tcategoriemovimenti` (`CategoriaMovimentoID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
