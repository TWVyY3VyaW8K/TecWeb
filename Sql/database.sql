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
('admin', '$2y$10$XocNeVGpSkoY7wVjtEw11.TCx6fUpF439lCt.EZyrMzU2U3p1RxyC', 'admin', 'admin', 'davide.liu@studenti.unipd.it'),
('Cheryl', '$2y$10$TB8ps4by6WrOMTK3E.uFH.mpAtkNAyZEtZFpIdVZZkzl42o4spj5C', 'Ruiting', 'Liu', 'davide97ls@gmail.com'),
('Cloe', '$2y$10$QWwsFAHZ5OyD32eLZt7GbOpIC2qYhk.RWgi/5ofb16R9fHPuVcqIm', 'Deng', 'Rong', 'davide97ls@gmail.com'),
('Dolores', '$2y$10$YDAtrTNpU9.4uLjbZEoUjuKqXxzIuN1L53nbgWfFUT//jSv2FKuPG', 'Sun', 'Yue', 'davide97ls@gmail.com'),
('Lixue', '$2y$10$N48lubnhys24Bv9XHmmd4eFTMzvG.oYOJCMcMeAMkDzlsNe671Wcu', 'Lixue', 'Liu', 'davide97ls@gmail.com'),
('Shenlong', '$2y$10$LwjQDaWpTBRTXrQ9maOoGeCBCrYEBOfdiAqyV2S0vIZMY2/L3cdlm', 'Davide', 'Liu', 'davide97ls@gmail.com'),
('Tencent', '$2y$10$RSXLSBbiy/MNEb0cybjYPu.5Y7dOlzyarSLJLtGM2z68dTQz4Ka4u', 'Ma', 'Huateng', 'davide97ls@gmail.com');

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

INSERT INTO `commenti` (`ID`, `Opera`, `Utente`, `Creatore`, `Commento`) VALUES
(1, 'Noctis Lucis Caelum', 'Cheryl', 'Shenlong', ' Good job!'),
(2, 'Noctis Lucis Caelum', 'Shenlong', 'Shenlong', ' Thank you!!!');

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

INSERT INTO `likes` (`Opera`, `Utente`, `Creatore`) VALUES
('Flowers', 'Tencent', 'Lixue'),
('Goku UI', 'Cloe', 'Cloe'),
('Instagram Life', 'Cloe', 'Tencent'),
('Kefla', 'Cheryl', 'Shenlong'),
('Kefla', 'Dolores', 'Shenlong'),
('Noctis Lucis Caelum', 'Cheryl', 'Shenlong'),
('Noctis Lucis Caelum', 'Cloe', 'Shenlong'),
('Noctis Lucis Caelum', 'Dolores', 'Shenlong'),
('Stickers', 'Cheryl', 'Shenlong'),
('Universe', 'Cheryl', 'Cheryl'),
('Universe', 'Dolores', 'Cheryl'),
('Universe', 'Tencent', 'Cheryl'),
('Wild-fi', 'Dolores', 'Dolores'),
('Wild-fi', 'Tencent', 'Dolores');

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

INSERT INTO `opere` (`Nome`, `Descrizione`, `Data_upload`, `Artista`, `Categoria`) VALUES
('Flowers', 'Flowers field.', '2019-01-08 07:10:45', 'Lixue', 'Landscape'),
('Goku UI', 'Ultra Instinct', '2019-01-08 07:16:39', 'Cloe', 'Portrait'),
('Instagram Life', 'Instagram Life', '2019-01-08 07:14:16', 'Tencent', 'Others'),
('Kefla', 'Universe 6 warrior', '2019-01-08 07:05:25', 'Shenlong', 'Fantasy'),
('Noctis Lucis Caelum', 'Heir apparent to the Lucian throne, Noctis&#039;s trials begin when he sets forth from the crown city in order to wed Lunafreya Nox Fleuret. In combat, he wields spectral weapons which he forges from thin air, a power possessed by those of his royal line.', '2019-01-08 07:04:12', 'Shenlong', 'Fantasy'),
('Skull', 'The skull', '2019-01-08 07:19:27', 'Cheryl', 'Fantasy'),
('Stickers', 'Lot of stickers.', '2019-01-08 07:04:36', 'Shenlong', 'Cartoon'),
('Universe', 'When the radiant morn of creation broke, And the world in the smile of God awoke, And the empty realms of darkness and death Were moved through their depths by his mighty breath, And orbs of beauty and spheres of flame From the void abyss by myriads came, In the joy of youth as they darted away, Through the widening wastes of space to play, Their silver voices in chorus rung, And this was the song the bright ones sung.', '2019-01-08 07:08:48', 'Cheryl', 'Abstract'),
('Wild-fi', 'Wild-fi', '2019-01-08 07:12:30', 'Dolores', 'Nature');

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
