-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 06 juin 2022 à 00:23
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
-- Structure de la table `attaquepersomob`
--

DROP TABLE IF EXISTS `attaquepersomob`;
CREATE TABLE IF NOT EXISTS `attaquepersomob` (
  `idMob` int NOT NULL,
  `idPersonnage` int NOT NULL,
  `nbCoup` int NOT NULL,
  `coupFatal` tinyint NOT NULL,
  `DegatsDonnes` int NOT NULL,
  `DegatsReçus` int NOT NULL,
  KEY `idMob` (`idMob`),
  KEY `idPersonnage` (`idPersonnage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Attaque` tinyint NOT NULL,
  `Defense` tinyint NOT NULL,
  `Magie` int NOT NULL,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `Attaque`, `Defense`, `Magie`, `nom`) VALUES
(1, 1, 0, 0, 'Arme'),
(2, 0, 1, 0, 'Armure'),
(3, 1, 0, 1, 'Pouvoir'),
(4, 0, 1, 1, 'Bouclier');

-- --------------------------------------------------------

--
-- Structure de la table `competence`
--

DROP TABLE IF EXISTS `competence`;
CREATE TABLE IF NOT EXISTS `competence` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `idCategorie` int NOT NULL,
  `Information` text NOT NULL,
  `idType` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `efficacite`
--

DROP TABLE IF EXISTS `efficacite`;
CREATE TABLE IF NOT EXISTS `efficacite` (
  `id` int NOT NULL AUTO_INCREMENT,
  `adjectif` varchar(50) NOT NULL,
  `coef` float NOT NULL,
  `ordre` int NOT NULL,
  `chance` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ordre` (`ordre`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `efficacite`
--

INSERT INTO `efficacite` (`id`, `adjectif`, `coef`, `ordre`, `chance`) VALUES
(1, 'assé', 0.7, 1, 1),
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
  `id` int NOT NULL AUTO_INCREMENT,
  `idMap` int NOT NULL,
  `idUser` int DEFAULT NULL,
  `vie` int NOT NULL,
  `vieMax` int NOT NULL,
  `lienImage` varchar(500) NOT NULL,
  `nom` varchar(200) NOT NULL,
  `type` int NOT NULL,
  `degat` int NOT NULL,
  `lvl` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idMap` (`idMap`),
  KEY `idUser` (`idUser`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Structure de la table `entitecompetence`
--

DROP TABLE IF EXISTS `entitecompetence`;
CREATE TABLE IF NOT EXISTS `entitecompetence` (
  `idEntite` int NOT NULL,
  `idCompetence` int NOT NULL,
  `equipe` tinyint NOT NULL,
  KEY `idCompetence` (`idCompetence`),
  KEY `idEntite` (`idEntite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `type` int NOT NULL,
  `efficacite` float NOT NULL,
  `valeur` int NOT NULL,
  `lvl` int NOT NULL,
  `coolDownMS` int NOT NULL DEFAULT '100',
  `LastUse` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `faction`
--

DROP TABLE IF EXISTS `faction`;
CREATE TABLE IF NOT EXISTS `faction` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `couleur` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `faction`
--

INSERT INTO `faction` (`id`, `nom`, `couleur`) VALUES
(1, 'Manga', 'rgba(34,159,190,0.555)'),
(2, 'Comics', 'rgba(128,159,23,0.555)'),
(3, 'Science Fiction', 'rgba(134,159,22,0.555)'),
(4, 'Gaming', 'rgba(177,255,0,0.53)');

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` int NOT NULL,
  `nom` varchar(50) NOT NULL,
  `valeur` int NOT NULL,
  `efficacite` float NOT NULL,
  `lvl` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Structure de la table `map`
--

DROP TABLE IF EXISTS `map`;
CREATE TABLE IF NOT EXISTS `map` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `mapNord` int DEFAULT NULL,
  `mapSud` int DEFAULT NULL,
  `mapEst` int DEFAULT NULL,
  `mapOuest` int DEFAULT NULL,
  `idUserDecouverte` int NOT NULL,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `lienImage` text NOT NULL,
  `type` int NOT NULL,
  PRIMARY KEY (`id`),
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

INSERT INTO `map` (`id`, `nom`, `position`, `mapNord`, `mapSud`, `mapEst`, `mapOuest`, `idUserDecouverte`, `x`, `y`, `lienImage`, `type`) VALUES
(1, 'Discord-City - Centre', 'spawn', 4, 8, 2, 6, 0, 0, 0, 'https://img.freepik.com/photos-gratuite/beau-village-medieval-montagne-caccamo-sicile-italie_287743-1168.jpg?w=996', 1),
(2, 'Discord-City - Est', 'spawn-est', 3, 9, NULL, 1, 1, 1, 0, 'https://img.freepik.com/photos-gratuite/beau-village-medieval-montagne-caccamo-sicile-italie_287743-1168.jpg?w=996', 1),
(3, 'Discord-City - Nord-Est', 'spawn-nord-est', NULL, 2, NULL, 4, 1, 1, 1, 'https://images.unsplash.com/photo-1583849215500-75387d55ea81?w=600', 1),
(4, 'Discord-City - Nord', 'spawn-nord', NULL, 1, 3, 5, 1, 0, 1, 'https://images.unsplash.com/photo-1577705482890-4d66c7d557be?w=600', 1),
(5, 'Discord-City - Nord-Ouest', 'spawn-nord-ouest', NULL, 6, 4, NULL, 1, -1, 1, 'https://images.unsplash.com/photo-1452665536397-024866ff6d36?w=600', 1),
(6, 'Discord-City - Ouest', 'spawn-ouest', 5, 7, 1, NULL, 1, -1, 0, 'https://images.unsplash.com/photo-1594477232357-32644df09d0e?w=600', 1),
(7, 'Discord-City - Sud-Ouest', 'spawn-sud-ouest', 6, NULL, 8, NULL, 1, -1, -1, 'https://images.unsplash.com/photo-1595370637810-cd38573b94fb?w=600', 1),
(8, 'Discord-City - Sud', 'spawn-sud', 1, NULL, 9, 7, 1, 0, -1, 'https://images.unsplash.com/photo-1516473174726-95151fe079af?w=600', 1),
(9, 'Discord-City - Sud-Est', 'spawn-sud-est', 2, NULL, NULL, 8, 1, 1, -1, 'https://images.unsplash.com/photo-1604087267014-7f29ba0127d7?w=600', 1);

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
-- Structure de la table `mob`
--

DROP TABLE IF EXISTS `mob`;
CREATE TABLE IF NOT EXISTS `mob` (
  `id` int NOT NULL,
  `type` int NOT NULL,
  `coefXp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnage`
--

DROP TABLE IF EXISTS `personnage`;
CREATE TABLE IF NOT EXISTS `personnage` (
  `id` int NOT NULL,
  `xp` int NOT NULL,
  `idTypePersonnage` int NOT NULL,
  KEY `idTypePersonnage` (`idTypePersonnage`)
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
-- Structure de la table `typeequipement`
--

DROP TABLE IF EXISTS `typeequipement`;
CREATE TABLE IF NOT EXISTS `typeequipement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `information` text NOT NULL,
  `lienImage` varchar(500) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `rarete` int NOT NULL,
  `idCategorie` int NOT NULL,
  `chance` int NOT NULL,
  `coolDown` int NOT NULL DEFAULT '500',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typeequipement`
--

INSERT INTO `typeequipement` (`id`, `information`, `lienImage`, `nom`, `rarete`, `idCategorie`, `chance`, `coolDown`) VALUES
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
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `rarete` int NOT NULL,
  `lienImage` text NOT NULL,
  `information` varchar(200) NOT NULL,
  `chance` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typeitem`
--

INSERT INTO `typeitem` (`id`, `nom`, `rarete`, `lienImage`, `information`, `chance`) VALUES
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
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `nomFr` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typemap`
--

INSERT INTO `typemap` (`id`, `nom`, `nomFr`) VALUES
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
-- Structure de la table `typemob`
--

DROP TABLE IF EXISTS `typemob`;
CREATE TABLE IF NOT EXISTS `typemob` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `rarete` int NOT NULL,
  `chance` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typemob`
--

INSERT INTO `typemob` (`id`, `nom`, `rarete`, `chance`) VALUES
(1, 'Bandit', 1, 1),
(2, 'Loup', 2, 3),
(3, 'Géant', 3, 5),
(4, 'Vampire', 3, 10),
(5, 'Pirate', 5, 20),
(6, 'Viking', 5, 25),
(7, 'Démon', 6, 100),
(8, 'Ange', 6, 100),
(9, 'Dragon', 7, 200),
(9, 'Archidémon', 8, 600),
(9, 'Archange', 8, 600),
(10, 'Idole', 9, 1500),
(11, 'Divinité', 10, 3000);

-- --------------------------------------------------------

--
-- Structure de la table `typepersonnage`
--

DROP TABLE IF EXISTS `typepersonnage`;
CREATE TABLE IF NOT EXISTS `typepersonnage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL DEFAULT 'humain',
  `coefAttaque` float NOT NULL DEFAULT '1',
  `coefDefense` float NOT NULL DEFAULT '1',
  `coefPouvoir` float NOT NULL DEFAULT '1',
  `coefBouclier` float NOT NULL DEFAULT '1',
  `coefCoolDown` int NOT NULL DEFAULT '1',
  `distance` tinyint NOT NULL DEFAULT '0',
  `lienImage` varchar(200) NOT NULL,
  `idFaction` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typepersonnage`
--

INSERT INTO `typepersonnage` (`id`, `nom`, `coefAttaque`, `coefDefense`, `coefPouvoir`, `coefBouclier`, `coefCoolDown`, `distance`, `lienImage`, `idFaction`) VALUES
(1, 'Humain - 3', 1, 1, 0.8, 1, 1, 0, 'https://media.sciencephoto.com/c0/39/51/80/c0395180-800px-wm.jpg', 3),
(2, 'Naruto Humain', 1, 1, 0.8, 1, 1, 0, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcomkY3FAt3ObQPsLCKb828ltFy1BNS-rtFQ&usqp=CAU', 1),
(3, 'Humain - 2', 1, 1, 0.8, 1, 1, 0, 'https://static.wikia.nocookie.net/batman/images/e/e5/Martha_Wayne.jpg/revision/latest/top-crop/width/360/height/450?cb=20100425181617', 2),
(4, 'Humain - 4', 1, 1, 0.8, 1, 1, 0, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSEi9QxhJpudHqzdfOQH-OpDH0o5L-B6uAcjg&usqp=CAU', 4),
(5, 'Dbz - Sayen', 1.5, 1, 1, 0.7, 1, 0, '', 1),
(6, 'Dbz - Humain', 1, 1, 1, 0.7, 1, 0, '', 1),
(7, 'DbZ - Sorcier', 1, 1, 1, 0.7, 1, 0, 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAoGCBMTERcTExMXGBcZFxoaGhoXGhoYGxwZGhcfGhobGhkaHysjHB8oHxodJTYlKCwuMjMyGSE3PDcxOysxMi4BCwsLDw4PHRERHTEoISg7MTExMTMuMS4xMTMxMTEzOTQxMTExMTExOTMxM', 1),
(8, 'Batman - Humain', 1, 1, 1, 1, 1, 0, '', 2),
(9, 'Batman - Magicien', 0.8, 1, 1.2, 1, 1, 0, '', 2),
(10, 'Flash - Magicien', 0.8, 1, 1.2, 1, 1, 0, '', 2),
(11, 'Hulk - Magicen', 1, 0.8, 1.2, 1, 1, 0, '', 2),
(12, 'Alien - Alien', 1, 1, 1, 1, 1, 0, '', 3),
(13, 'Alien - Marines', 1, 1, 1, 1, 1, 0, '', 3),
(14, 'Alien - Humain', 1, 1, 1, 1, 1, 0, '', 3),
(15, 'Starwars - jedi', 1, 1, 1, 1, 1, 0, '', 3),
(16, 'Starwars - sith', 1, 1, 1, 1, 1, 0, '', 3),
(17, 'League Of Legend - Assassin', 1, 1, 1, 1, 1, 0, '', 4),
(18, 'League Of Legend - Tank', 1, 1, 1, 1, 1, 0, '', 4),
(19, 'Humain - π', 10, 10, 10, 10, 1, 10, 'https://i.pinimg.com/originals/d3/b0/6b/d3b06b0479de8fd88e5b1bd8df1068cd.jpg', 5);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mdp` varchar(200) NOT NULL,
  `idPersonnage` int NOT NULL,
  `admin` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `idPersonnage` (`idPersonnage`)
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