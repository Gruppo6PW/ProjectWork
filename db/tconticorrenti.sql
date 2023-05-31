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
-- Table structure for table `tconticorrenti`
--

CREATE TABLE `tconticorrenti` (
  `ContoCorrenteID` int(11) NOT NULL,
  `Email` text NOT NULL,
  `Password` text NOT NULL,
  `CognomeTitolare` text NOT NULL,
  `NomeTitolare` text NOT NULL,
  `DataApertura` text NOT NULL,
  `IBAN` text DEFAULT NULL,
  `RegistrazioneConfermata` bit(1) NOT NULL DEFAULT b'0',
  `Token` text NOT NULL,
  `NumeroTentativiLogin` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tconticorrenti`
--

INSERT INTO `tconticorrenti` (`ContoCorrenteID`, `Email`, `Password`, `CognomeTitolare`, `NomeTitolare`, `DataApertura`, `IBAN`, `RegistrazioneConfermata`, `Token`, `NumeroTentativiLogin`) VALUES
(1, 'alberto.basso@gmail.com', 'iFT*du3*26u!whHz', 'Basso', 'Alberto', '2020-02-01 07:30:00', 'IT16B0300203280876544945647', b'0', 'fudh4238', 0),
(2, 'samuele.carnacini@gmail.com', 'Sqs&7@pEhUqEc3!!', 'Carnacini', 'Samuele', '2021-06-10 16:30:00', 'IT27W0300203280356731624263', b'0', 'worf0398', 0),
(3, 'mattia.pozza@gmail.com', 'K2N^4FkML$DGF!#!', 'Pozza', 'Mattia', '2021-12-10 12:30:00', 'IT29J0300203280382868853545', b'0', 'iofd4219', 0),
(4, 'tommaso.zini@gmail.com', 'DESF89#$QSF#fQwt', 'Zini', 'Tommaso', '2022-09-21 14:30:00', 'IT40N0300203280674763574942', b'0', 'nufs4832', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tconticorrenti`
--
ALTER TABLE `tconticorrenti`
  ADD PRIMARY KEY (`ContoCorrenteID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tconticorrenti`
--
ALTER TABLE `tconticorrenti`
  MODIFY `ContoCorrenteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
