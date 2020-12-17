-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1:3306
-- Vytvořeno: Čtv 10. pro 2020, 15:45
-- Verze serveru: 5.7.31
-- Verze PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `websp`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `orionlogin_clanky`
--

DROP TABLE IF EXISTS `orionlogin_clanky`;
CREATE TABLE IF NOT EXISTS `orionlogin_clanky` (
  `id_clanky` int(11) NOT NULL AUTO_INCREMENT,
  `id_uzivatel` int(11) NOT NULL,
  `nazev` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  `abstrakt` mediumtext COLLATE utf8_czech_ci NOT NULL,
  `nazev_souboru` tinytext COLLATE utf8_czech_ci,
  `hodnoceni_1` smallint(6) DEFAULT NULL,
  `hodnoceni_2` smallint(6) DEFAULT NULL,
  `hodnoceni_3` smallint(6) DEFAULT NULL,
  `recenzent_1` int(11) DEFAULT NULL,
  `recenzent_2` int(11) DEFAULT NULL,
  `recenzent_3` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_clanky`),
  KEY `fk_uzivatel_clanky_id_uzivatel_idx` (`id_uzivatel`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `orionlogin_clanky`
--

INSERT INTO `orionlogin_clanky` (`id_clanky`, `id_uzivatel`, `nazev`, `abstrakt`, `nazev_souboru`, `hodnoceni_1`, `hodnoceni_2`, `hodnoceni_3`, `recenzent_1`, `recenzent_2`, `recenzent_3`) VALUES
(54, 32, 'Tenis', 'Nemohl jsem hrát.', NULL, 4, 2, NULL, 29, 30, 31),
(55, 32, 'Hospoda', 'Zavřeli mi všechny hospody nemůžu chlastat.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 28, 'Kino', 'Nemůžu chodit do kina, kvůli coroně.', NULL, 5, 2, 1, 31, 29, 30),
(57, 28, 'tenis', 'nemohl jsem hrat', '1607607274PRO úlohy 11.pdf', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `orionlogin_pravo`
--

DROP TABLE IF EXISTS `orionlogin_pravo`;
CREATE TABLE IF NOT EXISTS `orionlogin_pravo` (
  `id_pravo` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `vaha` int(11) NOT NULL,
  PRIMARY KEY (`id_pravo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `orionlogin_pravo`
--

INSERT INTO `orionlogin_pravo` (`id_pravo`, `nazev`, `vaha`) VALUES
(1, 'Admin', 20),
(2, 'Autor', 10),
(3, 'Recenzent', 5);

-- --------------------------------------------------------

--
-- Struktura tabulky `orionlogin_uzivatel`
--

DROP TABLE IF EXISTS `orionlogin_uzivatel`;
CREATE TABLE IF NOT EXISTS `orionlogin_uzivatel` (
  `id_uzivatel` int(11) NOT NULL AUTO_INCREMENT,
  `id_pravo` int(11) NOT NULL,
  `jmeno` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  `login` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `heslo` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(35) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id_uzivatel`),
  KEY `fk_uzivatel_pravo_id_pravo_idx` (`id_pravo`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `orionlogin_uzivatel`
--

INSERT INTO `orionlogin_uzivatel` (`id_uzivatel`, `id_pravo`, `jmeno`, `login`, `heslo`, `email`) VALUES
(27, 1, 'admin', 'admin', 'admin', 'admin@admin.cz'),
(28, 2, 'autor', 'autor', 'autor', 'autor@autor.cz'),
(29, 3, 'rec1', 'rec1', 'rec', 'rec1@rec1.cz'),
(30, 3, 'rec2', 'rec2', 'rec', 'rec2@rec2.cz'),
(31, 3, 'rec3', 'rec3', 'rec', 'rec3@rec3.cz'),
(32, 2, 'autor2', 'autor2', 'autor', 'autor2@autor2.cz');

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `orionlogin_clanky`
--
ALTER TABLE `orionlogin_clanky`
  ADD CONSTRAINT `fk_uzivatel_clanky_id_uzivatel` FOREIGN KEY (`id_uzivatel`) REFERENCES `orionlogin_uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `orionlogin_uzivatel`
--
ALTER TABLE `orionlogin_uzivatel`
  ADD CONSTRAINT `fk_uzivatel_pravo_id_pravo` FOREIGN KEY (`id_pravo`) REFERENCES `orionlogin_pravo` (`id_pravo`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
