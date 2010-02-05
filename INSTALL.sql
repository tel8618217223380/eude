-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Sam 21 Novembre 2009 à 11:34
-- Version du serveur: 5.0.51
-- Version de PHP: 5.2.4-2ubuntu5.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `eu2`
--

-- --------------------------------------------------------

--
-- Structure de la table `Coordonnee`
--

DROP TABLE IF EXISTS `Coordonnee`;
CREATE TABLE `Coordonnee` (
  `ID` bigint(20) NOT NULL auto_increment,
  `TYPE` int(11) NOT NULL default '3',
  `POSIN` varchar(5) NOT NULL,
  `POSOUT` varchar(5) NOT NULL,
  `COORDET` varchar(10) NOT NULL,
  `COORDETOUT` varchar(10) NOT NULL,
  `USER` varchar(30) NOT NULL,
  `EMPIRE` varchar(100) NOT NULL,
  `INFOS` varchar(100) NOT NULL,
  `DATE` datetime NOT NULL,
  `NOTE` varchar(100) NOT NULL,
  `INACTIF` tinyint(1) NOT NULL default '0',
  `UTILISATEUR` varchar(30) NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `POSIN` (`POSIN`),
  KEY `POSOUT` (`POSOUT`),
  KEY `COORDET` (`COORDET`),
  KEY `COORDETOUT` (`COORDETOUT`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Coordonnee_Planetes`
--

DROP TABLE IF EXISTS `Coordonnee_Planetes`;
CREATE TABLE `Coordonnee_Planetes` (
  `pID` int(10) unsigned NOT NULL,
  `Titane` varchar(50) NOT NULL,
  `Cuivre` varchar(50) NOT NULL,
  `Fer` varchar(50) NOT NULL,
  `Aluminium` varchar(50) NOT NULL,
  `Mercure` varchar(50) NOT NULL,
  `Silicium` varchar(50) NOT NULL,
  `Uranium` varchar(50) NOT NULL,
  `Krypton` varchar(50) NOT NULL,
  `Azote` varchar(50) NOT NULL,
  `Hydrogene` varchar(50) NOT NULL,
  PRIMARY KEY  (`pID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Grade`
--

DROP TABLE IF EXISTS `Grade`;
CREATE TABLE `Grade` (
  `GradeId` int(11) NOT NULL auto_increment,
  `Grade` varchar(50) NOT NULL,
  `Niveau` int(11) NOT NULL,
  `Rattachement` int(11) NOT NULL,
  PRIMARY KEY  (`GradeId`),
  KEY `Niveau` (`Niveau`,`Rattachement`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `itineraire`
--

DROP TABLE IF EXISTS `itineraire`;
CREATE TABLE `itineraire` (
  `ID` int(11) NOT NULL auto_increment,
  `Joueur` varchar(30) NOT NULL,
  `Flotte` varchar(50) NOT NULL,
  `Start` varchar(5) NOT NULL,
  `End` varchar(5) NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `Joueur` (`Joueur`,`Flotte`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Log`
--

DROP TABLE IF EXISTS `Log`;
CREATE TABLE `Log` (
  `ID` int(11) NOT NULL auto_increment,
  `DATE` datetime NOT NULL,
  `LOGIN` varchar(30) NOT NULL,
  `IP` varchar(30) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Membres`
--

DROP TABLE IF EXISTS `Membres`;
CREATE TABLE `Membres` (
  `Joueur` varchar(30) NOT NULL,
  `carte_prefs` varchar(30) NOT NULL,
  `Points` int(11) NOT NULL,
  `Don` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `Economie` smallint(8) NOT NULL,
  `Commerce` smallint(8) NOT NULL,
  `Recherche` smallint(8) NOT NULL,
  `Combat` smallint(8) NOT NULL,
  `Construction` smallint(8) NOT NULL,
  `Navigation` smallint(8) NOT NULL,
  `Grade` smallint(8) NOT NULL,
  `Race` varchar(50) NOT NULL,
  `ship` varchar(30) NOT NULL,
  `Titre` varchar(30) NOT NULL,
  `GameGrade` varchar(50) NOT NULL,
  `pts_architecte` int(10) NOT NULL,
  `pts_mineur` int(10) NOT NULL,
  `pts_science` int(10) NOT NULL,
  `pts_commercant` int(10) NOT NULL,
  `pts_amiral` int(10) NOT NULL,
  `pts_guerrier` int(10) NOT NULL,
  PRIMARY KEY  (`Joueur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Membres_log`
--

DROP TABLE IF EXISTS `Membres_log`;
CREATE TABLE `Membres_log` (
  `ID` int(11) NOT NULL auto_increment,
  `Joueur` varchar(30) NOT NULL,
  `Date` datetime NOT NULL,
  `Points` int(11) NOT NULL,
  `Dons` int(11) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ownuniverse`
--

DROP TABLE IF EXISTS `ownuniverse`;
CREATE TABLE `ownuniverse` (
  `UTILISATEUR` varchar(30) NOT NULL default '',
  `planet0` varchar(50) NOT NULL,
  `coord0` varchar(13) NOT NULL,
  `data0` blob NOT NULL,
  `ress0` blob NOT NULL,
  `planet1` varchar(50) NOT NULL,
  `coord1` varchar(13) NOT NULL,
  `data1` blob NOT NULL,
  `ress1` blob NOT NULL,
  `planet2` varchar(50) NOT NULL,
  `coord2` varchar(13) NOT NULL,
  `data2` blob NOT NULL,
  `ress2` blob NOT NULL,
  `planet3` varchar(50) NOT NULL,
  `coord3` varchar(13) NOT NULL,
  `data3` blob NOT NULL,
  `ress3` blob NOT NULL,
  `planet4` varchar(50) NOT NULL,
  `coord4` varchar(13) NOT NULL,
  `data4` blob NOT NULL,
  `ress4` blob NOT NULL,
  PRIMARY KEY  (`UTILISATEUR`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `Login` varchar(30) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Permission` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`Login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- ------------------------
-- Valeurs par défaut ---
-- ------------------------

INSERT INTO `Grade` (`GradeId`, `Grade`, `Niveau`, `Rattachement`) VALUES (1, 'Leader'   , 1, 0);
INSERT INTO `Grade` (`GradeId`, `Grade`, `Niveau`, `Rattachement`) VALUES (2, 'Co-leader', 2, 1);
INSERT INTO `Grade` (`GradeId`, `Grade`, `Niveau`, `Rattachement`) VALUES (3, 'Membres'  , 3, 2);

-- Ne pas toucher, le tuto du forum n'est pas a jour, instructions dans le lisezmoi.txt
INSERT INTO `Membres` (`Joueur`, `Grade`) VALUES ('admin', 1);
INSERT INTO `Users` (`Login`, `Password`, `Permission`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 600);


-- Ne pas décommenter la ligne suivante ;)
-- DROP TABLE `Coordonnee`, `Coordonnee_Planetes`, `Grade`, `itineraire`, `Log`, `Membres`, `Membres_log`, `ownuniverse`, `Users`;