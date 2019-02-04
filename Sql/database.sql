-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2019 at 10:42 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tecweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `artisti`
--

CREATE TABLE `artisti` (
  `Username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Password` varchar(256) NOT NULL,
  `Nome` varchar(100) CHARACTER SET utf8 NOT NULL,
  `Cognome` varchar(100) CHARACTER SET utf8 NOT NULL,
  `Email` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT 'test@test.com'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `artisti`
--

INSERT INTO `artisti` (`Username`, `Password`, `Nome`, `Cognome`, `Email`) VALUES
('admin', '$2y$10$XocNeVGpSkoY7wVjtEw11.TCx6fUpF439lCt.EZyrMzU2U3p1RxyC', 'admin', 'admin', 'davide.liu@studenti.unipd.it');

-- --------------------------------------------------------

--
-- Table structure for table `commenti`
--

CREATE TABLE `commenti` (
  `ID` int(8) NOT NULL,
  `Opera` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Utente` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Creatore` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Commento` varchar(1000) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commenti`
--

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `Opera` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Utente` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Creatore` varchar(30) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `likes`
--

-- --------------------------------------------------------

--
-- Table structure for table `opere`
--

CREATE TABLE `opere` (
  `Nome` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Descrizione` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `Data_upload` datetime NOT NULL,
  `Artista` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Categoria` enum('Landscape','Fantasy','Abstract','Cartoon','Portrait','Nature','Others') CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `opere`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artisti`
--
ALTER TABLE `artisti`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `commenti`
--
ALTER TABLE `commenti`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Opera` (`Opera`,`Creatore`),
  ADD KEY `Utente` (`Utente`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`Opera`,`Creatore`,`Utente`),
  ADD KEY `Utente` (`Utente`);

--
-- Indexes for table `opere`
--
ALTER TABLE `opere`
  ADD PRIMARY KEY (`Nome`,`Artista`),
  ADD KEY `Artista` (`Artista`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commenti`
--
ALTER TABLE `commenti`
  MODIFY `ID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commenti`
--
ALTER TABLE `commenti`
  ADD CONSTRAINT `commenti_ibfk_1` FOREIGN KEY (`Opera`,`Creatore`) REFERENCES `opere` (`Nome`, `Artista`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commenti_ibfk_2` FOREIGN KEY (`Utente`) REFERENCES `artisti` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`Opera`,`Creatore`) REFERENCES `opere` (`Nome`, `Artista`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`Utente`) REFERENCES `artisti` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `opere`
--
ALTER TABLE `opere`
  ADD CONSTRAINT `opere_ibfk_1` FOREIGN KEY (`Artista`) REFERENCES `artisti` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
