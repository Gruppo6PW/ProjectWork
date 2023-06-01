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
-- Table structure for table `tcategoriemovimenti`
--

CREATE TABLE `tcategoriemovimenti` (
  `CategoriaMovimentoID` int(11) NOT NULL,
  `NomeCategoria` text NOT NULL,
  `Tipologia` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tcategoriemovimenti`
--

INSERT INTO `tcategoriemovimenti` (`CategoriaMovimentoID`, `NomeCategoria`, `Tipologia`) VALUES
(0, 'Apertura conto', 'Apertura conto'),
(1, 'Bonifico in entrata', 'Entrata'),
(2, 'Bonifico in uscita', 'Uscita'),
(3, 'Prelievo contanti', 'Entrata'),
(4, 'Pagamento delle utenze', 'Uscita'),
(5, 'Ricarica telefonica', 'Uscita'),
(6, 'Versamento al Bancomat', 'Uscita'),
(7, 'Pagamento con Carta Credito', 'Uscita');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tcategoriemovimenti`
--
ALTER TABLE `tcategoriemovimenti`
  ADD PRIMARY KEY (`CategoriaMovimentoID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tcategoriemovimenti`
--
ALTER TABLE `tcategoriemovimenti`
  MODIFY `CategoriaMovimentoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
