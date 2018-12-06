CREATE DATABASE TecWeb;
USE TecWeb;

CREATE TABLE IF NOT EXISTS `artisti` (
  `Username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Password` varchar(256) NOT NULL,
  `Nome` varchar(100) CHARACTER SET utf8 NOT NULL,
  `Cognome` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `opere` (
  `Nome` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Descrizione` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `Data_upload` datetime NOT NULL,
  `Artista` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Categoria` enum('Landscape','Fantasy','Abstract','Cartoon','Portrait','Nature','Others') CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`Nome`,`Artista`),
  FOREIGN KEY(`Artista`) REFERENCES `artisti` (`Username`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `commenti` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `Opera` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Utente` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Creatore` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Commento` varchar(1000) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY(`Opera`, `Creatore`) REFERENCES `opere` (`Nome`, `Artista`) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY(`Utente`) REFERENCES `artisti` (`Username`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `likes` (
  `Opera` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Utente` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Creatore` varchar(30) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`Opera`,`Creatore`,`Utente`),
  FOREIGN KEY(`Opera`, `Creatore`) REFERENCES `opere` (`Nome`, `Artista`) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY(`Utente`) REFERENCES `artisti` (`Username`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `artisti` (`Username`, `Password`, `Nome`, `Cognome`) VALUES
('daniele.bianchin', '$2y$10$nWohuRAUx2nk.9IUZc2DuOy1/UMgcWobHFXM526K7TokN0UKQxcHe', 'Daniele', 'Bianchin'),
('admin', '$2y$10$u9Wfos7iftnczbMBIUmcr.qUSA4xyRPsX9NFtOu2h08II3pLJs8ta', 'Daniele', 'Bianchin'),
('Singh', '$2y$10$KK6ctR/OLPqN22uNqS8u5ulbx571gmpVeofxrMxiVEvrajZ9mJr0C', 'Harwinder', 'Singh'),
('Noctis', '$2y$10$mJ5.UH.mUn.BzDax1wT3nuemN/HHkf7Xp2gSrfJ5FzFmd5rlDTCce', 'Davide', 'Liu'),
('pard', '$2y$10$yn0loTn1dxP0gGbzQ.ZJu.nZsQ2txPccPH.f1qHbz4axOT70sNyQ6', 'Pardeep', 'Singh');

INSERT INTO `opere` (`Nome`, `Descrizione`, `Data_upload`, `Artista`, `Categoria`) VALUES
('Noctis', 'Noctis Lucis Caelum', '2018-10-29 04:10:04', 'Noctis', 'Portrait'),
('Stickers', 'Lot of stickers', '2018-10-29 04:08:25', 'Noctis', 'Landscape'),
('Kefla', '', '2018-10-29 04:05:30', 'Noctis', 'Portrait'),
('Rose Wolf', '', '2018-10-29 04:07:24', 'Noctis', 'Nature'),
('High Altitude Vegetation', 'Unkown', '2018-07-22 17:34:11', 'daniele.bianchin', 'Fantasy'),
('Carpe Noctem', 'Unkown', '2018-07-22 17:34:11', 'daniele.bianchin', 'Fantasy'),
('Super Orbit', 'Unkown', '2018-01-21 17:21:11', 'daniele.bianchin', 'Fantasy'),
('Water on Planet X', 'Unkown', '2018-07-22 17:34:11', 'daniele.bianchin', 'Fantasy'),
('Wild-fi', 'Unkown', '2018-07-22 17:34:11', 'daniele.bianchin', 'Fantasy'),
('西王母', '', '2018-10-30 11:33:29', 'Noctis', 'Landscape');

INSERT INTO `commenti` (`ID`, `Opera`, `Utente`, `Creatore`, `Commento`) VALUES
(0, 'Carpe Noctem', 'daniele.bianchin', 'daniele.bianchin', 'Auto-commento');

INSERT INTO `likes` (`Opera`, `Utente`, `Creatore`) VALUES
('Carpe Noctem', 'daniele.bianchin', 'daniele.bianchin'),
('High Altitude Vegetation', 'daniele.bianchin', 'daniele.bianchin'),
('Kefla', 'admin', 'Noctis'),
('Kefla', 'Noctis', 'Noctis'),
('Noctis', 'admin', 'Noctis'),
('Noctis', 'Noctis', 'Noctis'),
('Rose Wolf', 'admin', 'Noctis'),
('Stickers', 'Noctis', 'Noctis'),
('Super Orbit', 'daniele.bianchin', 'daniele.bianchin'),
('Water on Planet X', 'daniele.bianchin', 'daniele.bianchin'),
('Wild-fi', 'daniele.bianchin', 'daniele.bianchin'),
('西王母', 'Noctis', 'Noctis');

