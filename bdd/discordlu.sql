-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 18 sep. 2022 à 11:30
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
-- Structure de la table `attaquepersomonster`
--

DROP TABLE IF EXISTS `attaquepersomonster`;
CREATE TABLE IF NOT EXISTS `attaquepersomonster` (
  `idMonster` int NOT NULL,
  `idPersonnage` int NOT NULL,
  `nbCoup` int NOT NULL,
  `coupFatal` tinyint NOT NULL,
  `DegatsDonnes` int NOT NULL,
  `DegatsReçus` int NOT NULL,
  KEY `idMob` (`idMonster`),
  KEY `idPersonnage` (`idPersonnage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `idCategorie` int NOT NULL AUTO_INCREMENT,
  `Attaque` tinyint NOT NULL,
  `Defense` tinyint NOT NULL,
  `Magie` int NOT NULL,
  `nameCategorie` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`idCategorie`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`idCategorie`, `Attaque`, `Defense`, `Magie`, `nameCategorie`) VALUES
(1, 1, 0, 0, 'Arme'),
(2, 0, 1, 0, 'Armure');

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `efficacite`
--

INSERT INTO `efficacite` (`idEfficacite`, `adjectif`, `coef`, `ordre`, `chance`) VALUES
(1, 'cassé', 0.7, 1, 1),
(2, 'pourri', 0.7, 2, 2),
(3, 'tout mou', 0.8, 3, 4),
(4, 'moisie', 0.8, 4, 8),
(5, 'usagé ', 0.9, 5, 16),
(6, 'moche', 0.9, 6, 32),
(7, 'reconditionné', 0.9, 7, 64),
(8, 'neuf', 1, 8, 128),
(9, 'efficace', 1.1, 9, 256),
(10, 'redoutable', 1.2, 10, 512),
(11, 'puissant', 1.3, 11, 1024),
(12, 'magique', 1.4, 12, 2048),
(13, 'enchanté', 1.5, 13, 4096),
(14, 'en fusion', 1.6, 14, 8186),
(15, 'nucléaire', 1.7, 15, 20001),
(16, 'infini', 1.8, 18, 40002);

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
  `imgEntite` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nameEntite` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `idTypeEntite` int NOT NULL,
  `degat` int NOT NULL,
  `lvlEntite` int NOT NULL,
  PRIMARY KEY (`idEntite`),
  KEY `idMap` (`idMap`),
  KEY `idUser` (`idUser`),
  KEY `type` (`idTypeEntite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=COMPACT;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `equipement`
--

DROP TABLE IF EXISTS `equipement`;
CREATE TABLE IF NOT EXISTS `equipement` (
  `idEquipement` int NOT NULL AUTO_INCREMENT,
  `nameEquipement` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `idTypeEquipement` int NOT NULL,
  `idEfficacite` float NOT NULL,
  `valeur` int NOT NULL,
  `lvlEquipement` int NOT NULL,
  `coolDownMS` int NOT NULL DEFAULT '100',
  `LastUse` bigint NOT NULL,
  PRIMARY KEY (`idEquipement`),
  KEY `type` (`idTypeEquipement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `faction`
--

DROP TABLE IF EXISTS `faction`;
CREATE TABLE IF NOT EXISTS `faction` (
  `idFaction` int NOT NULL AUTO_INCREMENT,
  `nameFaction` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `descFaction` varchar(100) NOT NULL,
  `logoFaction` varchar(100) NOT NULL,
  PRIMARY KEY (`idFaction`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `faction`
--

INSERT INTO `faction` (`idFaction`, `nameFaction`, `descFaction`, `logoFaction`) VALUES
(1, 'Albion', 'La faction Albion.\r\nRaces : ', '1_faction'),
(2, 'Alterna', 'La faction Alterna.\r\nRaces : ', '2_faction'),
(3, 'Menos', 'La faction Menos.\r\nRaces : ', '3_faction'),
(4, 'Initir', 'La faction Initir.\r\nRaces : ', '4_faction');

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `idItem` int NOT NULL AUTO_INCREMENT,
  `idTypeItem` int NOT NULL,
  `nameItem` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `valeur` int NOT NULL,
  `idEfficacite` float NOT NULL,
  `lvlItem` int NOT NULL,
  PRIMARY KEY (`idItem`),
  KEY `type` (`idTypeItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Structure de la table `map`
--

DROP TABLE IF EXISTS `map`;
CREATE TABLE IF NOT EXISTS `map` (
  `idMap` int NOT NULL AUTO_INCREMENT,
  `nameMap` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `position` varchar(50) NOT NULL,
  `mapNord` int DEFAULT NULL,
  `mapSud` int DEFAULT NULL,
  `mapEst` int DEFAULT NULL,
  `mapOuest` int DEFAULT NULL,
  `idUserDecouverte` int NOT NULL,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `imgMap` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` int NOT NULL,
  `idTypeMap` int NOT NULL,
  `idTypeBatiment` int NOT NULL,
  PRIMARY KEY (`idMap`),
  UNIQUE KEY `Position` (`position`),
  KEY `mapNord` (`mapNord`),
  KEY `mapSud` (`mapSud`),
  KEY `mapEst` (`mapEst`),
  KEY `mapOuest` (`mapOuest`),
  KEY `idUserDecouverte` (`idUserDecouverte`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=COMPACT;

--
-- Déchargement des données de la table `map`
--

INSERT INTO `map` (`idMap`, `nameMap`, `position`, `mapNord`, `mapSud`, `mapEst`, `mapOuest`, `idUserDecouverte`, `x`, `y`, `imgMap`, `type`, `idTypeMap`, `idTypeBatiment`) VALUES
(1, 'Discord-City - Centre', 'spawn', 4, 8, 2, 6, 0, 0, 0, 'https://img.freepik.com/photos-gratuite/beau-village-medieval-montagne-caccamo-sicile-italie_287743-1168.jpg?w=996', 1, 0, 0),
(2, 'Discord-City - Est', 'spawn-est', 3, 9, NULL, 1, 1, 1, 0, 'https://img.freepik.com/photos-gratuite/beau-village-medieval-montagne-caccamo-sicile-italie_287743-1168.jpg?w=996', 1, 0, 0),
(3, 'Discord-City - Nord-Est', 'spawn-nord-est', NULL, 2, NULL, 4, 1, 1, 1, 'https://images.unsplash.com/photo-1583849215500-75387d55ea81?w=600', 1, 0, 0),
(4, 'Discord-City - Nord', 'spawn-nord', NULL, 1, 3, 5, 1, 0, 1, 'https://images.unsplash.com/photo-1577705482890-4d66c7d557be?w=600', 1, 0, 0),
(5, 'Discord-City - Nord-Ouest', 'spawn-nord-ouest', NULL, 6, 4, NULL, 1, -1, 1, 'https://images.unsplash.com/photo-1452665536397-024866ff6d36?w=600', 1, 0, 0),
(6, 'Discord-City - Ouest', 'spawn-ouest', 5, 7, 1, NULL, 1, -1, 0, 'https://images.unsplash.com/photo-1594477232357-32644df09d0e?w=600', 1, 0, 0),
(7, 'Discord-City - Sud-Ouest', 'spawn-sud-ouest', 6, NULL, 8, NULL, 1, -1, -1, 'https://images.unsplash.com/photo-1595370637810-cd38573b94fb?w=600', 1, 0, 0),
(8, 'Discord-City - Sud', 'spawn-sud', 1, NULL, 9, 7, 1, 0, -1, 'https://images.unsplash.com/photo-1516473174726-95151fe079af?w=600', 1, 0, 0),
(9, 'Discord-City - Sud-Est', 'spawn-sud-est', 2, NULL, NULL, 8, 1, 1, -1, 'https://images.unsplash.com/photo-1604087267014-7f29ba0127d7?w=600', 1, 0, 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mapitems`
--

DROP TABLE IF EXISTS `mapitems`;
CREATE TABLE IF NOT EXISTS `mapitems` (
  `idMap` int NOT NULL,
  `idItem` int NOT NULL,
  KEY `idMap` (`idMap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `monster`
--

DROP TABLE IF EXISTS `monster`;
CREATE TABLE IF NOT EXISTS `monster` (
  `idEntite` int NOT NULL,
  `idTypeMonster` int NOT NULL,
  `coefXp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tooltip`
--

DROP TABLE IF EXISTS `tooltip`;
CREATE TABLE IF NOT EXISTS `tooltip` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tooltip` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tooltip`
--

INSERT INTO `tooltip` (`id`, `tooltip`) VALUES
(1, 'Lorsque vous terrassez un monstre, il vous appartient.'),
(2, 'Vous pouvez reprendre votre santé en consommant des fruits.'),
(3, 'Vous pouvez trouver des items à chaque arrivée dans un lieu, s\'il n\'a pas été visité récemment.'),
(4, 'Dans les premières zones, il vaut mieux trouver une arme et une armure pour faire des provisions de fruits avant de se battre.');

-- --------------------------------------------------------

--
-- Structure de la table `typeclassmonster`
--

DROP TABLE IF EXISTS `typeclassmonster`;
CREATE TABLE IF NOT EXISTS `typeclassmonster` (
  `idTypeClassMonster` int NOT NULL AUTO_INCREMENT,
  `nameTypeClassMonster` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `posTypeMonster` int NOT NULL,
  `percentAttaque` int NOT NULL,
  `percentDefense` int NOT NULL,
  `percentMagique` int NOT NULL,
  `percentRessMagique` int NOT NULL,
  `spawnTypeMonster` int NOT NULL,
  PRIMARY KEY (`idTypeClassMonster`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typeclassmonster`
--

INSERT INTO `typeclassmonster` (`idTypeClassMonster`, `nameTypeClassMonster`, `posTypeMonster`, `percentAttaque`, `percentDefense`, `percentMagique`, `percentRessMagique`, `spawnTypeMonster`) VALUES
(1, 'Bébé', 0, 0, 0, 0, 0, 15),
(3, 'Jeune', 0, 0, 0, 0, 0, 55),
(5, 'Mature', 1, 0, 0, 0, 0, 26),
(6, 'Adulte', 1, 0, 0, 0, 0, 60),
(12, 'Vénérable', 0, 0, 0, 0, 0, 12),
(13, 'Ancien', 0, 0, 0, 0, 0, 8),
(9, 'Maître', 0, 0, 0, 0, 0, 22),
(11, 'Vieux', 0, 0, 0, 0, 0, 15),
(15, 'Légendaire', 1, 0, 0, 0, 0, 3),
(8, 'Mage', 0, 0, 0, 0, 0, 25),
(7, 'Guerrier', 0, 0, 0, 0, 0, 33),
(16, 'Divin', 1, 0, 0, 0, 0, 1),
(0, '', 1, 100, 100, 100, 100, 230),
(14, 'Majeur', 1, 0, 0, 0, 0, 5),
(4, 'Immature', 1, 0, 0, 0, 0, 20),
(10, 'Sage', 0, 0, 0, 0, 0, 15),
(2, 'Enfant', 0, 0, 0, 0, 0, 25);

-- --------------------------------------------------------

--
-- Structure de la table `typeequipement`
--

DROP TABLE IF EXISTS `typeequipement`;
CREATE TABLE IF NOT EXISTS `typeequipement` (
  `idTypeEquipement` int NOT NULL AUTO_INCREMENT,
  `information` text NOT NULL,
  `imgEquipement` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nameTypeEquipement` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rarete` int NOT NULL,
  `idCategorie` int NOT NULL,
  `chance` int NOT NULL,
  `coolDown` int NOT NULL DEFAULT '500',
  PRIMARY KEY (`idTypeEquipement`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typeequipement`
--

INSERT INTO `typeequipement` (`idTypeEquipement`, `information`, `imgEquipement`, `nameTypeEquipement`, `rarete`, `idCategorie`, `chance`, `coolDown`) VALUES
(1, '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/71/Uncrossed_gladius.jpg/280px-Uncrossed_gladius.jpg', 'Glaive', 1, 1, 1, 500),
(2, '', 'https://th.bing.com/th/id/OIP.5wkIkkD56nrd6jF0gR2kWwHaHa?w=186&h=186&c=7&r=0&o=5&pid=1.7', 'Baguette Magic', 2, 3, 10, 500),
(3, '', 'https://th.bing.com/th/id/OIP.HIpuL__dWylILgUXtwpaTgHaJ1?w=142&h=189&c=7&r=0&o=5&pid=1.7', 'Parapluie', 3, 4, 20000000, 500),
(4, '', 'https://th.bing.com/th/id/OIP.3H6v0c5zYh8PbN8rJQhdxwHaHa?w=207&h=207&c=7&r=0&o=5&pid=1.7', 'Baton', 1, 3, 1, 500),
(5, '', 'https://th.bing.com/th/id/OIP.zJWr_sr5yO00IwEpjOtURAHaFa?w=225&h=180&c=7&r=0&o=5&pid=1.7', 'Cotte de maille', 3, 2, 10, 500),
(6, '', 'https://th.bing.com/th/id/R.9f29bfbc207eb97b455c75af81028097?rik=rvotqpxiglD7Fw&pid=ImgRaw&r=0', 'Pullover', 1, 2, 1, 500),
(7, 'Le fouet traditionnel d\'indy', 'https://th.bing.com/th/id/OIP.Rz_kOstmpuf6D3fhd3CrhQHaJC?w=166&h=203&c=7&r=0&o=5&pid=1.7', 'Fouet', 3, 1, 1, 500),
(8, '', 'https://th.bing.com/th/id/OIP.ahhiuCfXS8WGmKrdbKGyRgHaJn?w=134&h=180&c=7&r=0&o=5&pid=1.7', 'Sabre Laser', 7, 1, 1000, 500),
(9, '', 'https://th.bing.com/th/id/OIP.rzd266etdMKMgrhsbCLTtAHaFk?w=265&h=199&c=7&r=0&o=5&pid=1.7', 'Pistolet', 6, 1, 500, 500),
(10, '', 'https://th.bing.com/th/id/OIP.ccpZEJIVARMvIZ9N3gnTPgHaHa?w=187&h=187&c=7&r=0&o=5&pid=1.7', 'Dague', 4, 1, 30, 500),
(11, '', 'https://th.bing.com/th/id/OIP.SwxkPeT-m8UTalZWqoXH3wHaJQ?w=206&h=258&c=7&r=0&o=5&pid=1.7', 'Crosse', 5, 1, 100, 500),
(12, '', 'https://th.bing.com/th/id/OIP.OWL0des2smnwx-V9YoYdhQAAAA?w=115&h=220&c=7&r=0&o=5&pid=1.7', 'Cape invisible', 8, 2, 500, 500),
(13, '', 'https://th.bing.com/th/id/OIP.eEzkYTtkcEZi2bwxvsYFgQAAAA?w=135&h=187&c=7&r=0&o=5&pid=1.7', 'L\'Amour', 10, 1, 10000, 500),
(14, '', 'https://th.bing.com/th/id/OIP.pvVmkxsiYWxj6CGB4qcnpAHaE6?w=295&h=196&c=7&r=0&o=5&pid=1.7', 'Planche en bois', 2, 2, 10, 500),
(15, '', 'https://th.bing.com/th/id/OIP.MiASF5B3dzIkuA5MLj7ZKQHaHa?w=192&h=190&c=7&r=0&o=5&pid=1.7', 'Kevlar', 7, 2, 100, 500),
(16, '', 'https://th.bing.com/th/id/OIP.90kI_GyywYN7ZaIZEa4cagHaHa?w=211&h=211&c=7&r=0&o=5&pid=1.7', 'Plastron Magic', 4, 4, 10, 500),
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
  `nameTypeItem` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rarete` int NOT NULL,
  `imgItem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `information` varchar(200) NOT NULL,
  `chance` int NOT NULL,
  PRIMARY KEY (`idTypeItem`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typeitem`
--

INSERT INTO `typeitem` (`idTypeItem`, `nameTypeItem`, `rarete`, `imgItem`, `information`, `chance`) VALUES
(1, 'Pierre', 2, 'https://image.noelshack.com/fichiers/2019/21/5/1558680066-rock.png', 'Permet d\'améliorer ses armures', 3),
(2, 'Fruit', 1, 'https://img.icons8.com/color/452/group-of-fruits.png', 'Permet de reprendre de la vie', 1),
(3, 'Fiole', 13, 'https://www.icone-png.com/png/42/42072.png', 'Permet d\'immuniser un montre', 2000),
(4, 'Mouchoir', 12, 'https://ravel-foundry.s3.eu-west-3.amazonaws.com/images/items/artisanal/mouchoir-filtrant.png', 'Permet de se soigner ', 1000),
(5, 'Morceau de Fer', 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZ_ep_JR4quCLR07ra1O0tTBxfz2c04MQaZ-vjQH2y3OohelwuC5sMiAQ8TiA8_OF-8h8&usqp=CAU', 'Permet d\'améliorer ses armes', 5),
(6, 'Pépite d&#039;Or', 11, 'https://th.bing.com/th?q=Pépite+Dor+Dessin&w=120&h=120&c=1&rs=1&qlt=90&cb=1&pid=InlineBlock&mkt=fr-FR&cc=FR&setlang=fr&adlt=moderate&t=1&mw=247', 'Permet d\'attraper un mob instantanément', 500),
(7, 'Œuf', 5, 'https://static.wikia.nocookie.net/arksurvivalevolved_gamepedia/images/a/a3/Eggs.png/revision/latest/scale-to-width-down/1200?cb=20200807154254', 'Permet de pop un mob aléatoire là ou vous êtes', 15),
(8, 'Bois', 1, 'https://th.bing.com/th/id/OIP.t4fE9mafaTFTU1RFBXFiwQHaE7?w=255&h=180&c=7&r=0&o=5&pid=1.7', 'Permet de réparer son armes', 2),
(9, 'Brique', 4, 'https://th.bing.com/th/id/OIP.5Uh6vvKtk79YjfVE5_LM3AHaFj?w=245&h=184&c=7&r=0&o=5&pid=1.7', 'Permet de réparer son armures', 10),
(10, 'Canard en plastique', 6, 'https://pullfr-4c63.kxcdn.com/pato-mm-x-mm-jkg-b.png', 'Boost les stats du personnage', 20),
(11, 'Piece en toc', 6, 'https://www.ccopera.com/metaux-precieux/ressources/metaux/medium/20FS-A.png', 'Permet d\'acheter des equipements', 30),
(12, 'Ficelle', 7, 'https://vikings.help/users/vikings/imgExtCatalog/big/m073.png', 'Permet de réparer son armures', 40),
(13, 'Gaudasse', 8, 'https://nhim.splf.in/Boots.png', 'Permet d\'attaquer plus vite', 50),
(14, 'Pain', 9, 'https://www.icone-png.com/png/13/13038.png', 'Redonne toute la vie à un perso', 80),
(15, 'Haricot Magique', 10, 'https://www.dol-celeb.com/wp-content/uploads/2018/06/haricots-magiques.jpg', 'Redonne de la vie à une perso mort', 100);

-- --------------------------------------------------------

--
-- Structure de la table `typemap`
--

DROP TABLE IF EXISTS `typemap`;
CREATE TABLE IF NOT EXISTS `typemap` (
  `idTypeMap` int NOT NULL AUTO_INCREMENT,
  `nameTypeMapEn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nameTypeMapFr` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`idTypeMap`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typemap`
--

INSERT INTO `typemap` (`idTypeMap`, `nameTypeMapEn`, `nameTypeMapFr`) VALUES
(1, 'Plain', 'Plaine'),
(2, 'Foret', 'Fôret'),
(3, 'Mountain', 'Montagne'),
(4, 'Path', 'Route'),
(5, 'Dungeon', 'Donjon'),
(6, 'Castle', 'Château'),
(7, 'Dune', 'Dune'),
(8, 'Sea', 'Mer'),
(9, 'Ocean', 'Océan'),
(10, 'Lake', 'Lac'),
(11, 'River', 'Rivière'),
(12, 'Swamp', 'Marais '),
(13, 'City', 'Ville'),
(14, 'Town', 'Ville'),
(15, 'Savannah', 'Savane'),
(16, 'Coline', 'Colline'),
(17, 'Valley', 'Vallée'),
(18, 'Desert', 'Désert');

-- --------------------------------------------------------

--
-- Structure de la table `typemonster`
--

DROP TABLE IF EXISTS `typemonster`;
CREATE TABLE IF NOT EXISTS `typemonster` (
  `idTypeMonster` int NOT NULL AUTO_INCREMENT,
  `nameTypeMonster` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `baseAttaque` int NOT NULL,
  `baseDefense` int NOT NULL,
  `baseMagique` int NOT NULL,
  `baseRessMagique` int NOT NULL,
  `baseGainMoney` int NOT NULL,
  `baseGainExp` int NOT NULL,
  `factionTypeMonster` int NOT NULL,
  `spawnTypeMonster` int NOT NULL,
  PRIMARY KEY (`idTypeMonster`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typemonster`
--

INSERT INTO `typemonster` (`idTypeMonster`, `nameTypeMonster`, `baseAttaque`, `baseDefense`, `baseMagique`, `baseRessMagique`, `baseGainMoney`, `baseGainExp`, `factionTypeMonster`, `spawnTypeMonster`) VALUES
(2, 'Loup', 0, 0, 0, 0, 0, 0, 0, 3),
(3, 'Géant', 0, 0, 0, 0, 0, 0, 0, 5),
(4, 'Vampire', 0, 0, 0, 0, 0, 0, 0, 10),
(7, 'Démon', 0, 0, 0, 0, 0, 0, 0, 100),
(8, 'Ange', 0, 0, 0, 0, 0, 0, 0, 100),
(9, 'Dragon', 0, 0, 0, 0, 0, 0, 0, 200),
(10, 'Archidémon', 0, 0, 0, 0, 0, 0, 0, 600),
(11, 'Archange', 0, 0, 0, 0, 0, 0, 0, 600),
(12, 'Idole', 0, 0, 0, 0, 0, 0, 0, 1500),
(13, 'Divinité', 0, 0, 0, 0, 0, 0, 0, 3000),
(14, 'Cyclope', 0, 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `typepersonnage`
--

DROP TABLE IF EXISTS `typepersonnage`;
CREATE TABLE IF NOT EXISTS `typepersonnage` (
  `idTypePerso` int NOT NULL AUTO_INCREMENT,
  `nameTypePerso` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `statsAttaque` int NOT NULL,
  `statsDefense` int NOT NULL,
  `statsMagique` int NOT NULL,
  `statsRessMagique` int NOT NULL,
  `imgTypePerso` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `idFaction` int NOT NULL,
  PRIMARY KEY (`idTypePerso`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typepersonnage`
--

INSERT INTO `typepersonnage` (`idTypePerso`, `nameTypePerso`, `statsAttaque`, `statsDefense`, `statsMagique`, `statsRessMagique`, `imgTypePerso`, `idFaction`) VALUES
(1, 'Haut Elfe', 1, 1, 1, 1, '', 1),
(2, 'Halfelin', 1, 1, 1, 1, '', 1),
(3, 'Mage', 1, 1, 1, 1, '', 1),
(4, 'Héliade', 1, 1, 1, 1, '', 1),
(5, 'Elfe Sylvain', 1, 1, 1, 1, '', 2),
(6, 'Korrigan', 1, 1, 1, 1, '', 2),
(7, 'Nain', 1, 1, 1, 1, '', 2),
(8, 'Hobbit', 1, 1, 1, 1, '', 2),
(9, 'Sirène', 1, 1, 1, 1, '', 3),
(10, 'Tritons', 1, 1, 1, 1, '', 3),
(11, 'Homme-Lézard', 1, 1, 1, 1, '', 3),
(12, 'Hydriade', 1, 1, 1, 1, '', 3),
(13, 'Elfe Noir', 1, 1, 1, 1, '', 4),
(14, 'Orc', 1, 1, 1, 1, '', 4),
(15, 'Duergars', 1, 1, 1, 1, '', 4),
(16, 'Sorcier', 1, 1, 1, 1, '', 4);

-- --------------------------------------------------------

--
-- Structure de la table `typeuser`
--

DROP TABLE IF EXISTS `typeuser`;
CREATE TABLE IF NOT EXISTS `typeuser` (
  `idTypeUser` int NOT NULL AUTO_INCREMENT,
  `nameTypeUser` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `admin` tinyint NOT NULL,
  `staff` tinyint NOT NULL,
  `bypass` tinyint NOT NULL,
  `view` tinyint NOT NULL,
  PRIMARY KEY (`idTypeUser`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typeuser`
--

INSERT INTO `typeuser` (`idTypeUser`, `nameTypeUser`, `admin`, `staff`, `bypass`, `view`) VALUES
(12, 'Administrateur', 1, 1, 1, 0),
(11, 'Opérateur', 1, 1, 1, 0),
(10, 'Modérateur', 0, 1, 1, 0),
(3, 'Joueur Expérimenté', 0, 0, 0, 1),
(2, 'Joueur Vérifié', 0, 0, 0, 1),
(1, 'Joueur', 0, 0, 0, 1),
(-1, 'Ban', 0, 0, 0, 0),
(9, 'Testeur', 0, 0, 1, 0),
(0, 'Sanctionné', 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pseudo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password_hash` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `token` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `idPersonnage` int DEFAULT NULL,
  `idFaction` int DEFAULT NULL,
  `dateUser` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `typeUser` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `login` (`email`),
  KEY `idPersonnage` (`idPersonnage`),
  KEY `idFaction` (`idFaction`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
