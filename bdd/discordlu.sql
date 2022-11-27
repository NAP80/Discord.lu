-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : Dim 27 nov. 2022 à 10:10
-- Version du serveur :  8.0.21
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `discordlu`
--

-- --------------------------------------------------------

--
-- Structure de la table `attaquepersocreature`
--

DROP TABLE IF EXISTS `attaquepersocreature`;
CREATE TABLE IF NOT EXISTS `attaquepersocreature` (
  `idCreature` int NOT NULL,
  `idPersonnage` int NOT NULL,
  `nbCoup` int NOT NULL,
  `coupFatal` tinyint NOT NULL,
  `DegatsDonnes` int NOT NULL,
  `DegatsReçus` int NOT NULL,
  KEY `idPersonnage` (`idPersonnage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `idCategorie` int NOT NULL AUTO_INCREMENT,
  `Attaque` tinyint NOT NULL,
  `Defense` tinyint NOT NULL,
  `Distance` int NOT NULL,
  `nameCategorie` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`idCategorie`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`idCategorie`, `Attaque`, `Defense`, `Distance`, `nameCategorie`) VALUES
(1, 1, 0, 0, 'Arme de contact'),
(2, 0, 1, 0, 'Armure'),
(3, 1, 0, 1, 'Arc'),
(4, 0, 1, 1, 'Bouclier');

-- --------------------------------------------------------

--
-- Structure de la table `creature`
--

DROP TABLE IF EXISTS `creature`;
CREATE TABLE IF NOT EXISTS `creature` (
  `idEntite` int NOT NULL,
  `idTypeCreature` int NOT NULL,
  `coefXp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `efficacite`
--

DROP TABLE IF EXISTS `efficacite`;
CREATE TABLE IF NOT EXISTS `efficacite` (
  `idEfficacite` int NOT NULL AUTO_INCREMENT,
  `adjectif` varchar(50) NOT NULL,
  `coef` float NOT NULL,
  `ordre` int NOT NULL,
  `chance` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`idEfficacite`),
  UNIQUE KEY `ordre` (`ordre`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `efficacite`
--

INSERT INTO `efficacite` (`idEfficacite`, `adjectif`, `coef`, `ordre`, `chance`) VALUES
(1, 'cassé', 0.8, 1, 16),
(2, 'usagé', 0.9, 2, 8),
(3, 'classique', 1, 3, 128),
(4, 'neuf', 1.1, 4, 256),
(5, 'résistant', 1.2, 5, 512),
(6, 'puissant', 1.3, 6, 1024);

-- --------------------------------------------------------

--
-- Structure de la table `entite`
--

DROP TABLE IF EXISTS `entite`;
CREATE TABLE IF NOT EXISTS `entite` (
  `idEntite` int NOT NULL AUTO_INCREMENT,
  `idMap` int NOT NULL,
  `idUser` int DEFAULT NULL,
  `healthNow` int NOT NULL,
  `healthMax` int NOT NULL,
  `imgEntite` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nameEntite` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `idTypeEntite` int NOT NULL,
  `degat` int NOT NULL,
  `lvlEntite` int NOT NULL,
  PRIMARY KEY (`idEntite`),
  KEY `idMap` (`idMap`),
  KEY `idUser` (`idUser`),
  KEY `type` (`idTypeEntite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Structure de la table `entiteequipement`
--

DROP TABLE IF EXISTS `entiteequipement`;
CREATE TABLE IF NOT EXISTS `entiteequipement` (
  `idEquipement` int NOT NULL,
  `idEntite` int NOT NULL,
  `equipe` tinyint NOT NULL,
  KEY `idEquipement` (`idEquipement`),
  KEY `idEntite` (`idEntite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `equipement`
--

DROP TABLE IF EXISTS `equipement`;
CREATE TABLE IF NOT EXISTS `equipement` (
  `idEquipement` int NOT NULL AUTO_INCREMENT,
  `nameEquipement` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `idTypeEquipement` int NOT NULL,
  `idEfficacite` float NOT NULL,
  `valeur` int NOT NULL,
  `lvlEquipement` int NOT NULL,
  `coolDownMS` int NOT NULL DEFAULT '100',
  `LastUse` bigint NOT NULL,
  PRIMARY KEY (`idEquipement`),
  KEY `type` (`idTypeEquipement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `faction`
--

DROP TABLE IF EXISTS `faction`;
CREATE TABLE IF NOT EXISTS `faction` (
  `idFaction` int NOT NULL AUTO_INCREMENT,
  `nameFaction` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `descFaction` varchar(100) NOT NULL,
  `logoFaction` varchar(100) NOT NULL,
  PRIMARY KEY (`idFaction`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `faction`
--

INSERT INTO `faction` (`idFaction`, `nameFaction`, `descFaction`, `logoFaction`) VALUES
(1, 'Albion', 'La faction Albion.', '1_faction'),
(2, 'Alterna', 'La faction Alterna.', '2_faction'),
(3, 'Menos', 'La faction Menos.', '3_faction'),
(4, 'Initir', 'La faction Initir.', '4_faction');

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `idItem` int NOT NULL AUTO_INCREMENT,
  `idTypeItem` int NOT NULL,
  `nameItem` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `valeur` int NOT NULL,
  `idEfficacite` float NOT NULL,
  `lvlItem` int NOT NULL,
  PRIMARY KEY (`idItem`),
  KEY `type` (`idTypeItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Structure de la table `map`
--

DROP TABLE IF EXISTS `map`;
CREATE TABLE IF NOT EXISTS `map` (
  `idMap` int NOT NULL AUTO_INCREMENT,
  `nameMap` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `position` varchar(50) NOT NULL,
  `mapNord` int DEFAULT NULL,
  `mapSud` int DEFAULT NULL,
  `mapEst` int DEFAULT NULL,
  `mapOuest` int DEFAULT NULL,
  `idUserDecouverte` int NOT NULL,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `imgMap` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `PvP` tinyint NOT NULL DEFAULT '1',
  `idTypeMap` int NOT NULL,
  `idTypeBatiment` int NOT NULL,
  PRIMARY KEY (`idMap`),
  UNIQUE KEY `Position` (`position`),
  KEY `mapNord` (`mapNord`),
  KEY `mapSud` (`mapSud`),
  KEY `mapEst` (`mapEst`),
  KEY `mapOuest` (`mapOuest`),
  KEY `idUserDecouverte` (`idUserDecouverte`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Déchargement des données de la table `map`
--

INSERT INTO `map` (`idMap`, `nameMap`, `position`, `mapNord`, `mapSud`, `mapEst`, `mapOuest`, `idUserDecouverte`, `x`, `y`, `imgMap`, `PvP`, `idTypeMap`, `idTypeBatiment`) VALUES
(1, 'Discord-City - Centre', 'spawn', 4, 8, 2, 6, 0, 0, 0, 'https://img.freepik.com/photos-gratuite/beau-village-medieval-montagne-caccamo-sicile-italie_287743-1168.jpg?w=996', 0, 0, 0),
(2, 'Discord-City - Est', 'spawn-est', 3, 9, NULL, 1, 1, 1, 0, 'https://img.freepik.com/photos-gratuite/beau-village-medieval-montagne-caccamo-sicile-italie_287743-1168.jpg?w=996', 0, 0, 0),
(3, 'Discord-City - Nord-Est', 'spawn-nord-est', NULL, 2, NULL, 4, 1, 1, 1, 'https://images.unsplash.com/photo-1583849215500-75387d55ea81?w=600', 0, 0, 0),
(4, 'Discord-City - Nord', 'spawn-nord', NULL, 1, 3, 5, 1, 0, 1, 'https://images.unsplash.com/photo-1577705482890-4d66c7d557be?w=600', 0, 0, 0),
(5, 'Discord-City - Nord-Ouest', 'spawn-nord-ouest', NULL, 6, 4, NULL, 1, -1, 1, 'https://images.unsplash.com/photo-1452665536397-024866ff6d36?w=600', 0, 0, 0),
(6, 'Discord-City - Ouest', 'spawn-ouest', 5, 7, 1, NULL, 1, -1, 0, 'https://images.unsplash.com/photo-1594477232357-32644df09d0e?w=600', 0, 0, 0),
(7, 'Discord-City - Sud-Ouest', 'spawn-sud-ouest', 6, NULL, 8, NULL, 1, -1, -1, 'https://images.unsplash.com/photo-1595370637810-cd38573b94fb?w=600', 0, 0, 0),
(8, 'Discord-City - Sud', 'spawn-sud', 1, NULL, 9, 7, 1, 0, -1, 'https://images.unsplash.com/photo-1516473174726-95151fe079af?w=600', 0, 0, 0),
(9, 'Discord-City - Sud-Est', 'spawn-sud-est', 2, NULL, NULL, 8, 1, 1, -1, 'https://images.unsplash.com/photo-1604087267014-7f29ba0127d7?w=600', 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `mapequipements`
--

DROP TABLE IF EXISTS `mapequipements`;
CREATE TABLE IF NOT EXISTS `mapequipements` (
  `idEquipement` int NOT NULL,
  `idMap` int NOT NULL,
  KEY `idEquipement` (`idEquipement`),
  KEY `idMap` (`idMap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mapitems`
--

DROP TABLE IF EXISTS `mapitems`;
CREATE TABLE IF NOT EXISTS `mapitems` (
  `idMap` int NOT NULL,
  `idItem` int NOT NULL,
  KEY `idMap` (`idMap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `personnage`
--

DROP TABLE IF EXISTS `personnage`;
CREATE TABLE IF NOT EXISTS `personnage` (
  `idPersonnage` int NOT NULL,
  `idTypePersonnage` int NOT NULL,
  `levelPersonnage` int NOT NULL DEFAULT '1',
  `expPersonnage` int NOT NULL DEFAULT '0',
  `moneyPersonnage` int NOT NULL DEFAULT '0',
  `effectPersonnage` varchar(500) DEFAULT NULL,
  `idMapSpawnPersonnage` int NOT NULL,
  UNIQUE KEY `idPersonnage` (`idPersonnage`),
  KEY `idTypePersonnage` (`expPersonnage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `persosacitems`
--

DROP TABLE IF EXISTS `persosacitems`;
CREATE TABLE IF NOT EXISTS `persosacitems` (
  `idPersonnage` int NOT NULL,
  `idItem` int NOT NULL,
  KEY `idPersonnage` (`idPersonnage`),
  KEY `idItems` (`idItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tooltip`
--

DROP TABLE IF EXISTS `tooltip`;
CREATE TABLE IF NOT EXISTS `tooltip` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tooltip` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `tooltip`
--

INSERT INTO `tooltip` (`id`, `tooltip`) VALUES
(1, 'Lorsque vous terrassez une créature, elle vous appartient.'),
(2, 'Vous pouvez reprendre votre santé en consommant des fruits.'),
(3, 'Vous pouvez trouver des items à chaque arrivée dans un lieu, s\'il n\'a pas été visité récemment.'),
(4, 'Dans les premières zones, il vaut mieux trouver une arme et une armure pour faire des provisions de fruits avant de se battre.');

-- --------------------------------------------------------

--
-- Structure de la table `typeclasscreature`
--

DROP TABLE IF EXISTS `typeclasscreature`;
CREATE TABLE IF NOT EXISTS `typeclasscreature` (
  `idTypeClassCreature` int NOT NULL AUTO_INCREMENT,
  `nameTypeClassCreature` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `posTypeCreature` int NOT NULL,
  `percentAttaque` int NOT NULL,
  `percentDefense` int NOT NULL,
  `percentDistance` int NOT NULL,
  `percentRessDistance` int NOT NULL,
  `spawnTypeCreature` int NOT NULL,
  PRIMARY KEY (`idTypeClassCreature`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `typeclasscreature`
--

INSERT INTO `typeclasscreature` (`idTypeClassCreature`, `nameTypeClassCreature`, `posTypeCreature`, `percentAttaque`, `percentDefense`, `percentDistance`, `percentRessDistance`, `spawnTypeCreature`) VALUES
(0, '', 1, 100, 100, 100, 100, 230),
(1, 'Bébé', 0, 0, 0, 0, 0, 15),
(2, 'Enfant', 0, 0, 0, 0, 0, 25),
(3, 'Jeune', 0, 0, 0, 0, 0, 55),
(4, 'Immature', 1, 0, 0, 0, 0, 20),
(5, 'Mature', 1, 0, 0, 0, 0, 26),
(6, 'Adulte', 1, 0, 0, 0, 0, 60),
(7, 'Vieux', 0, 0, 0, 0, 0, 15),
(8, 'Ancien', 0, 0, 0, 0, 0, 8),
(9, 'Majeur', 1, 0, 0, 0, 0, 5),
(10, 'Légendaire', 1, 0, 0, 0, 0, 3),
(11, 'Mythique', 1, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `typecreature`
--

DROP TABLE IF EXISTS `typecreature`;
CREATE TABLE IF NOT EXISTS `typecreature` (
  `idTypeCreature` int NOT NULL AUTO_INCREMENT,
  `nameTypeCreature` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `defaultAvatar` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `baseAttaque` int NOT NULL,
  `baseDefense` int NOT NULL,
  `baseDistance` int NOT NULL,
  `baseRessDistance` int NOT NULL,
  `baseGainMoney` int NOT NULL,
  `baseGainExp` int NOT NULL,
  `factionTypeCreature` int NOT NULL,
  `spawnTypeCreature` int NOT NULL,
  PRIMARY KEY (`idTypeCreature`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `typecreature`
--

INSERT INTO `typecreature` (`idTypeCreature`, `nameTypeCreature`, `defaultAvatar`, `baseAttaque`, `baseDefense`, `baseDistance`, `baseRessDistance`, `baseGainMoney`, `baseGainExp`, `factionTypeCreature`, `spawnTypeCreature`) VALUES
(1, 'Pigeon', 'assets/avatar/pigeon.jpg', 0, 0, 0, 0, 0, 0, 0, 1),
(2, 'Sanglier', 'assets/avatar/sanglier.jpg', 0, 0, 0, 0, 0, 0, 0, 3),
(3, 'Loup', 'assets/avatar/loup.jpg', 0, 0, 0, 0, 0, 0, 0, 5),
(4, 'Corbeau', 'assets/avatar/corbeau.jpg', 0, 0, 0, 0, 0, 0, 0, 15),
(5, 'Serpent', 'assets/avatar/serpent.jpg', 0, 0, 0, 0, 0, 0, 0, 25),
(6, 'Ours', 'assets/avatar/ours.jpg', 0, 0, 0, 0, 0, 0, 0, 40),
(7, 'Panthère', 'assets/avatar/panthere.jpg', 0, 0, 0, 0, 0, 0, 0, 60),
(8, 'Puma', 'assets/avatar/puma.jpg', 0, 0, 0, 0, 0, 0, 0, 70),
(9, 'Tigre', 'assets/avatar/tigre.jpg', 0, 0, 0, 0, 0, 0, 0, 100),
(10, 'Gorille', 'assets/avatar/gorille.jpg', 0, 0, 0, 0, 0, 0, 0, 150),
(11, 'Vautour', 'assets/avatar/vautour.jpg', 0, 0, 0, 0, 0, 0, 0, 200);

-- --------------------------------------------------------

--
-- Structure de la table `typeequipement`
--

DROP TABLE IF EXISTS `typeequipement`;
CREATE TABLE IF NOT EXISTS `typeequipement` (
  `idTypeEquipement` int NOT NULL AUTO_INCREMENT,
  `information` text NOT NULL,
  `imgEquipement` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nameTypeEquipement` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `rarete` int NOT NULL,
  `idCategorie` int NOT NULL,
  `chance` int NOT NULL,
  `coolDown` int NOT NULL DEFAULT '500',
  PRIMARY KEY (`idTypeEquipement`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `typeequipement`
--

INSERT INTO `typeequipement` (`idTypeEquipement`, `information`, `imgEquipement`, `nameTypeEquipement`, `rarete`, `idCategorie`, `chance`, `coolDown`) VALUES
(1, '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/71/Uncrossed_gladius.jpg/280px-Uncrossed_gladius.jpg', 'Glaive', 1, 1, 1, 500),
(2, '', 'https://th.bing.com/th/id/OIP.5wkIkkD56nrd6jF0gR2kWwHaHa?w=186&h=186&c=7&r=0&o=5&pid=1.7', 'Baguette', 2, 3, 10, 500),
(3, '', 'https://th.bing.com/th/id/OIP.HIpuL__dWylILgUXtwpaTgHaJ1?w=142&h=189&c=7&r=0&o=5&pid=1.7', 'Parapluie', 3, 4, 20000000, 500),
(4, '', 'https://th.bing.com/th/id/OIP.3H6v0c5zYh8PbN8rJQhdxwHaHa?w=207&h=207&c=7&r=0&o=5&pid=1.7', 'Baton', 1, 3, 1, 500),
(5, '', 'https://th.bing.com/th/id/OIP.zJWr_sr5yO00IwEpjOtURAHaFa?w=225&h=180&c=7&r=0&o=5&pid=1.7', 'Cotte de maille', 3, 2, 10, 500),
(6, '', 'https://th.bing.com/th/id/R.9f29bfbc207eb97b455c75af81028097?rik=rvotqpxiglD7Fw&pid=ImgRaw&r=0', 'Pullover', 1, 2, 1, 500),
(7, 'Le fouet traditionnel d\'indy', 'https://th.bing.com/th/id/OIP.Rz_kOstmpuf6D3fhd3CrhQHaJC?w=166&h=203&c=7&r=0&o=5&pid=1.7', 'Fouet', 3, 1, 1, 500),
(8, '', 'https://th.bing.com/th/id/OIP.ahhiuCfXS8WGmKrdbKGyRgHaJn?w=134&h=180&c=7&r=0&o=5&pid=1.7', 'Sabre', 7, 1, 1000, 500),
(9, '', 'https://th.bing.com/th/id/OIP.rzd266etdMKMgrhsbCLTtAHaFk?w=265&h=199&c=7&r=0&o=5&pid=1.7', 'Pistolet', 6, 1, 500, 500),
(10, '', 'https://th.bing.com/th/id/OIP.ccpZEJIVARMvIZ9N3gnTPgHaHa?w=187&h=187&c=7&r=0&o=5&pid=1.7', 'Dague', 4, 1, 30, 500),
(11, '', 'https://th.bing.com/th/id/OIP.SwxkPeT-m8UTalZWqoXH3wHaJQ?w=206&h=258&c=7&r=0&o=5&pid=1.7', 'Crosse', 5, 1, 100, 500),
(12, '', 'https://th.bing.com/th/id/OIP.OWL0des2smnwx-V9YoYdhQAAAA?w=115&h=220&c=7&r=0&o=5&pid=1.7', 'Cape', 8, 2, 500, 500),
(13, '', 'https://th.bing.com/th/id/OIP.eEzkYTtkcEZi2bwxvsYFgQAAAA?w=135&h=187&c=7&r=0&o=5&pid=1.7', 'L\'Amour', 10, 1, 10000, 500),
(14, '', 'https://th.bing.com/th/id/OIP.pvVmkxsiYWxj6CGB4qcnpAHaE6?w=295&h=196&c=7&r=0&o=5&pid=1.7', 'Planche en bois', 2, 2, 10, 500),
(15, '', 'https://th.bing.com/th/id/OIP.MiASF5B3dzIkuA5MLj7ZKQHaHa?w=192&h=190&c=7&r=0&o=5&pid=1.7', 'Kevlar', 7, 2, 100, 500),
(16, '', 'https://th.bing.com/th/id/OIP.90kI_GyywYN7ZaIZEa4cagHaHa?w=211&h=211&c=7&r=0&o=5&pid=1.7', 'Plastron', 4, 4, 10, 500),
(17, '', 'https://th.bing.com/th/id/OIP.D94sDw7_l_cqB6Kt2XlunwHaHa?w=206&h=206&c=7&r=0&o=5&pid=1.7', 'Cuirasse', 6, 2, 50, 500),
(18, '', 'https://th.bing.com/th/id/OIP.zuI_d5qC3MqglDYomufz8wHaOH?w=181&h=345&c=7&r=0&o=5&pid=1.7', 'Broigne', 5, 2, 10, 500),
(19, '', 'https://th.bing.com/th/id/OIP.Zwl2Y9akC5B1dnmd2VR1NwHaHa?w=206&h=206&c=7&r=0&o=5&pid=1.7', 'Brigandine', 0, 0, 0, 500),
(20, '', 'https://th.bing.com/th/id/OIP.eMW2p5IaesLB1PQqN6yEkQHaLr?w=128&h=202&c=7&r=0&o=5&pid=1.7', 'Exosquelettes', 9, 2, 1000, 500);

-- --------------------------------------------------------

--
-- Structure de la table `typeitem`
--

DROP TABLE IF EXISTS `typeitem`;
CREATE TABLE IF NOT EXISTS `typeitem` (
  `idTypeItem` int NOT NULL AUTO_INCREMENT,
  `nameTypeItem` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `rarete` int NOT NULL,
  `imgItem` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `information` varchar(200) NOT NULL,
  `chance` int NOT NULL,
  PRIMARY KEY (`idTypeItem`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `typeitem`
--

INSERT INTO `typeitem` (`idTypeItem`, `nameTypeItem`, `rarete`, `imgItem`, `information`, `chance`) VALUES
(1, 'Pierre', 2, 'https://image.noelshack.com/fichiers/2019/21/5/1558680066-rock.png', 'Permet d\'améliorer ses armures', 3),
(2, 'Fruit', 1, 'https://img.icons8.com/color/452/group-of-fruits.png', 'Permet de reprendre de la vie', 1),
(3, 'Fiole', 13, 'https://www.icone-png.com/png/42/42072.png', 'Permet d\'immuniser une créature', 2000),
(4, 'Mouchoir', 12, 'https://ravel-foundry.s3.eu-west-3.amazonaws.com/images/items/artisanal/mouchoir-filtrant.png', 'Permet de se soigner ', 1000),
(5, 'Morceau de Fer', 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZ_ep_JR4quCLR07ra1O0tTBxfz2c04MQaZ-vjQH2y3OohelwuC5sMiAQ8TiA8_OF-8h8&usqp=CAU', 'Permet d\'améliorer ses armes', 5),
(6, 'Pépite d&#039;Or', 11, 'https://th.bing.com/th?q=Pépite+Dor+Dessin&w=120&h=120&c=1&rs=1&qlt=90&cb=1&pid=InlineBlock&mkt=fr-FR&cc=FR&setlang=fr&adlt=moderate&t=1&mw=247', 'Permet d\'attraper une créature instantanément', 500),
(7, 'Œuf', 5, 'https://static.wikia.nocookie.net/arksurvivalevolved_gamepedia/images/a/a3/Eggs.png/revision/latest/scale-to-width-down/1200?cb=20200807154254', 'Permet de pop une créature aléatoire là ou vous êtes', 15),
(8, 'Bois', 1, 'https://th.bing.com/th/id/OIP.t4fE9mafaTFTU1RFBXFiwQHaE7?w=255&h=180&c=7&r=0&o=5&pid=1.7', 'Permet de réparer son armes', 2),
(9, 'Brique', 4, 'https://th.bing.com/th/id/OIP.5Uh6vvKtk79YjfVE5_LM3AHaFj?w=245&h=184&c=7&r=0&o=5&pid=1.7', 'Permet de réparer son armures', 10),
(10, 'Canard en plastique', 6, 'https://pullfr-4c63.kxcdn.com/pato-mm-x-mm-jkg-b.png', 'Boost les stats du personnage', 20),
(11, 'Piece en toc', 6, 'https://www.ccopera.com/metaux-precieux/ressources/metaux/medium/20FS-A.png', 'Permet d\'acheter des equipements', 30),
(12, 'Ficelle', 7, 'https://vikings.help/users/vikings/imgExtCatalog/big/m073.png', 'Permet de réparer son armures', 40),
(13, 'Gaudasse', 8, 'https://nhim.splf.in/Boots.png', 'Permet d\'attaquer plus vite', 50),
(14, 'Pain', 9, 'https://www.icone-png.com/png/13/13038.png', 'Redonne toute la vie à un perso', 80);

-- --------------------------------------------------------

--
-- Structure de la table `typemap`
--

DROP TABLE IF EXISTS `typemap`;
CREATE TABLE IF NOT EXISTS `typemap` (
  `idTypeMap` int NOT NULL AUTO_INCREMENT,
  `nameTypeMap` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `defaultBackground` varchar(100) NOT NULL,
  PRIMARY KEY (`idTypeMap`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `typemap`
--

INSERT INTO `typemap` (`idTypeMap`, `nameTypeMap`, `defaultBackground`) VALUES
(1, 'Plaine', 'assets/background/plaine.jpg'),
(2, 'Fôret', 'assets/background/foret.jpg'),
(3, 'Montagne', 'assets/background/montagne.jpg'),
(4, 'Savane', 'assets/background/savane.jpg'),
(5, 'Vallée', 'assets/background/vallee.jpg'),
(6, 'Désert', 'assets/background/desert.jpg'),
(7, 'Dune', 'assets/background/dune.jpg'),
(8, 'Mer', 'assets/background/mer.jpg'),
(9, 'Plage', 'assets/background/plage.jpg'),
(10, 'Lac', 'assets/background/lac.jpg'),
(11, 'Rivière', 'assets/background/riviere.jpg'),
(12, 'Marais ', 'assets/background/marais.jpg'),
(13, 'Colline', 'assets/background/colline.jpg'),
(14, 'Ruine', 'assets/background/ruine.jpg'),
(15, 'Route', 'assets/background/route.jpg'),
(16, 'Village', 'assets/background/village.jpg'),
(17, 'Ville', 'assets/background/ville.jpg'),
(18, 'Château', 'assets/background/chateau.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `typepersonnage`
--

DROP TABLE IF EXISTS `typepersonnage`;
CREATE TABLE IF NOT EXISTS `typepersonnage` (
  `idTypePerso` int NOT NULL AUTO_INCREMENT,
  `nameTypePerso` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `defaultAvatar` varchar(100) NOT NULL,
  `statsAttaque` int NOT NULL,
  `statsDefense` int NOT NULL,
  `statsDistance` int NOT NULL,
  `statsRessDistance` int NOT NULL,
  `idFaction` int NOT NULL,
  PRIMARY KEY (`idTypePerso`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `typepersonnage`
--

INSERT INTO `typepersonnage` (`idTypePerso`, `nameTypePerso`, `defaultAvatar`, `statsAttaque`, `statsDefense`, `statsDistance`, `statsRessDistance`, `idFaction`) VALUES
(1, 'Aventurier', 'assets/avatar/aventurier.jpg', 1, 1, 1, 1, 1),
(2, 'Barde', 'assets/avatar/barde.jpg', 1, 1, 1, 1, 1),
(3, 'Voleur', 'assets/avatar/voleur.jpg', 1, 1, 1, 1, 1),
(4, 'Explorateur', 'assets/avatar/explorateur.jpg', 1, 1, 1, 1, 1),
(5, 'Garde', 'assets/avatar/garde.jpg', 1, 1, 1, 1, 2),
(6, 'Soldat', 'assets/avatar/soldat.jpg', 1, 1, 1, 1, 2),
(7, 'Mercenaire', 'assets/avatar/mercenaire.jpg', 1, 1, 1, 1, 2),
(8, 'Dompteur', 'assets/avatar/dompteur.jpg', 1, 1, 1, 1, 2),
(9, 'Archéologue', 'assets/avatar/archeologue.jpg', 1, 1, 1, 1, 3),
(10, 'Marchand', 'assets/avatar/marchand.jpg', 1, 1, 1, 1, 3),
(11, 'Forgeron', 'assets/avatar/forgeron.jpg', 1, 1, 1, 1, 3),
(12, 'Médecin', 'assets/avatar/medecin.jpg', 1, 1, 1, 1, 3),
(13, 'Vétérinaire', 'assets/avatar/veterinaire.jpg', 1, 1, 1, 1, 4),
(14, 'Ingénieur', 'assets/avatar/ingenieur.jpg', 1, 1, 1, 1, 4),
(15, 'Chercheur', 'assets/avatar/chercheur.jpg', 1, 1, 1, 1, 4),
(16, 'Scientifique', 'assets/avatar/scientifique.jpg', 1, 1, 1, 1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `typeuser`
--

DROP TABLE IF EXISTS `typeuser`;
CREATE TABLE IF NOT EXISTS `typeuser` (
  `idTypeUser` int NOT NULL AUTO_INCREMENT,
  `nameTypeUser` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `admin` tinyint NOT NULL,
  `staff` tinyint NOT NULL,
  `bypass` tinyint NOT NULL,
  `view` tinyint NOT NULL,
  `play` tinyint NOT NULL,
  PRIMARY KEY (`idTypeUser`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `typeuser`
--

INSERT INTO `typeuser` (`idTypeUser`, `nameTypeUser`, `admin`, `staff`, `bypass`, `view`, `play`) VALUES
(-1, 'Ban', 0, 0, 0, 0, 0),
(0, 'Sanctionné', 0, 0, 0, 1, 1),
(1, 'Joueur', 0, 0, 0, 1, 1),
(2, 'Joueur Vérifié', 0, 0, 0, 1, 1),
(3, 'Joueur Expérimenté', 0, 0, 0, 1, 1),
(9, 'Testeur', 0, 0, 1, 0, 1),
(10, 'Modérateur', 0, 1, 1, 0, 1),
(11, 'Opérateur', 1, 1, 1, 0, 1),
(12, 'Administrateur', 1, 1, 1, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pseudo` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password_hash` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `token` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `idPersonnage` int DEFAULT NULL,
  `idFaction` int DEFAULT NULL,
  `dateUser` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idTypeUser` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `login` (`email`),
  KEY `idPersonnage` (`idPersonnage`),
  KEY `idFaction` (`idFaction`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `visites`
--

DROP TABLE IF EXISTS `visites`;
CREATE TABLE IF NOT EXISTS `visites` (
  `idPersonnage` int NOT NULL,
  `idMap` int NOT NULL,
  `laDate` datetime NOT NULL,
  KEY `idUser` (`idPersonnage`),
  KEY `idMap` (`idMap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
