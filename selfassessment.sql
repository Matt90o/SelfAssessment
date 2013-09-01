-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 17 aug 2013 om 11:56
-- Serverversie: 5.5.24-log
-- PHP-versie: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `selfassessment`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` tinyint(1) unsigned DEFAULT NULL,
  `competenceid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itemlevel` tinyint(3) unsigned DEFAULT NULL,
  `itemproof` tinyint(3) unsigned DEFAULT NULL,
  `validated` tinyint(3) unsigned DEFAULT NULL,
  `timestamp` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Gegevens worden uitgevoerd voor tabel `item`
--

INSERT INTO `item` (`id`, `userid`, `competenceid`, `itemlevel`, `itemproof`, `validated`, `timestamp`) VALUES
(2, 1, 'designing1', 0, 1, 1, '2013-08-16'),
(3, 1, 'designing1', 0, 3, 1, '2013-08-16'),
(4, 1, 'designing1', 0, 5, 1, '2013-08-16'),
(5, 1, 'designing1', 1, 0, 0, '2013-08-16'),
(6, 1, 'designing1', 1, 3, 0, '2013-08-16'),
(7, 1, 'designing1', 1, 7, 0, '2013-08-16');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `program`
--

CREATE TABLE IF NOT EXISTS `program` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(70) NOT NULL,
  `xmlpath` varchar(70) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Gegevens worden uitgevoerd voor tabel `program`
--

INSERT INTO `program` (`id`, `title`, `xmlpath`) VALUES
(1, 'Fusion Science (Master)', 'xml/SelfAssessFS.xml'),
(2, 'Electrical Engineering (Master)', 'xml/SelfAssessEE.xml'),
(3, 'Mechanical Engineering (Master)', 'xml/SelfAssessME.xml'),
(4, 'Chemical Engineering (Master)', 'xml/SelfAssessCE.xml');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `sessionid` varchar(50) NOT NULL,
  `studentid` varchar(7) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `program` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Gegevens worden uitgevoerd voor tabel `user`
--

INSERT INTO `user` (`id`, `email`, `sessionid`, `studentid`, `firstname`, `lastname`, `program`, `password`) VALUES
(1, 'test', '', '0675757', 'Voornaam', 'Achternaam', '1', '098f6bcd4621d373cade4e832627b4f6'),
(2, 'm.fiumara@student.tue.nl', '', '0658642', 'Mattia', 'Fiumara', '1', 'ae2b1fca515949e5d54fb22b8ed95575');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
