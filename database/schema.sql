DROP SCHEMA IF EXISTS `hw2` ;
CREATE SCHEMA IF NOT EXISTS `hw2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `hw2` ;

DROP TABLE IF EXISTS `aeroporti`;
CREATE TABLE `aeroporti` (
  `iata_code` char(3) NOT NULL,
  `country` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  PRIMARY KEY (`iata_code`)
);


DROP TABLE IF EXISTS `utenti`;
CREATE TABLE `utenti` (
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` char(72) NOT NULL,
  `birthdate` date NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  PRIMARY KEY (`username`)
);

DROP TABLE IF EXISTS `destinazioni`;
CREATE TABLE `destinazioni` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titolo` varchar(255) NOT NULL,
  `utente` varchar(255) NOT NULL,
  `descrizione` varchar(1023) DEFAULT NULL,
  `immagine` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX (`utente`),
  CONSTRAINT `destinazioni_ibfk_1` FOREIGN KEY (`utente`) REFERENCES `utenti` (`username`) ON DELETE CASCADE
);

DROP TABLE IF EXISTS `prenotazioni`;
CREATE TABLE `prenotazioni` (
  `id` int NOT NULL AUTO_INCREMENT,
  `prezzo` float DEFAULT NULL,
  `valuta` varchar(15) DEFAULT NULL,
  `origine` char(3) DEFAULT NULL,
  `destinazione` char(3) DEFAULT NULL,
  `compagnia` varchar(255) DEFAULT NULL,
  `data_partenza` datetime DEFAULT NULL,
  `data_arrivo` datetime DEFAULT NULL,
  `utente` varchar(255) DEFAULT NULL,
  `bagaglio` int(11) DEFAULT NULL,
  `data_prenotazione` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX (`origine`),
  INDEX (`destinazione`),
  INDEX (`utente`),
  CONSTRAINT `prenotazioni_ibfk_1` FOREIGN KEY (`destinazione`) REFERENCES `aeroporti` (`iata_code`) ON DELETE SET NULL,
  CONSTRAINT `prenotazioni_ibfk_2` FOREIGN KEY (`origine`) REFERENCES `aeroporti` (`iata_code`) ON DELETE SET NULL,
  CONSTRAINT `prenotazioni_ibfk_3` FOREIGN KEY (`utente`) REFERENCES `utenti` (`username`) ON DELETE CASCADE
);

DROP TABLE IF EXISTS `tratte`;
CREATE TABLE `tratte` (
  `id` int NOT NULL AUTO_INCREMENT,
  `origine` char(3) DEFAULT NULL,
  `destinazione` char(3) DEFAULT NULL,
  `volo` int DEFAULT NULL,
  `data_partenza` datetime DEFAULT NULL,
  `data_arrivo` datetime DEFAULT NULL,
  `progressivo` int(11) DEFAULT NULL,
  `direzione` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX (`volo`),
  INDEX (`origine`),
  INDEX (`destinazione`),
  CONSTRAINT `tratte_ibfk_1` FOREIGN KEY (`volo`) REFERENCES `prenotazioni` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tratte_ibfk_2` FOREIGN KEY (`destinazione`) REFERENCES `aeroporti` (`iata_code`) ON DELETE SET NULL,
  CONSTRAINT `tratte_ibfk_3` FOREIGN KEY (`origine`) REFERENCES `aeroporti` (`iata_code`) ON DELETE SET NULL
);
