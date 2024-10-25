-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 21, 2024 at 03:19 PM
-- Server version: 8.0.33
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projet_idaw`
--

-- --------------------------------------------------------

--
-- Table structure for table `aliment`
--

DROP TABLE IF EXISTS `aliment`;
CREATE TABLE IF NOT EXISTS `aliment` (
  `ID_ALIMENT` int NOT NULL AUTO_INCREMENT,
  `NOM` varchar(2555) DEFAULT NULL,
  PRIMARY KEY (`ID_ALIMENT`)
) ENGINE=InnoDB AUTO_INCREMENT=78182 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contient`
--

DROP TABLE IF EXISTS `contient`;
CREATE TABLE IF NOT EXISTS `contient` (
  `ID_ALIMENT` int NOT NULL,
  `ID_TR` int NOT NULL,
  `ID_CONTIENT` int NOT NULL AUTO_INCREMENT,
  `VALEUR_RATIO` float NOT NULL,
  PRIMARY KEY (`ID_CONTIENT`),
  KEY `FK_CONTIENT_CONTIENT_ALIMENT` (`ID_ALIMENT`),
  KEY `FK_CONTIENT_CONTIENT2_TYPE_RAT` (`ID_TR`)
) ENGINE=InnoDB AUTO_INCREMENT=29825 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal`
--

DROP TABLE IF EXISTS `journal`;
CREATE TABLE IF NOT EXISTS `journal` (
  `ID_JOURNAL` int NOT NULL AUTO_INCREMENT,
  `ID_UTILISATEUR` int NOT NULL,
  `DATE` date NOT NULL,
  PRIMARY KEY (`ID_JOURNAL`),
  KEY `FK_JOURNAL_AVOIR_UTILISAT` (`ID_UTILISATEUR`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `niveau_sportif`
--

DROP TABLE IF EXISTS `niveau_sportif`;
CREATE TABLE IF NOT EXISTS `niveau_sportif` (
  `ID_NS` int NOT NULL AUTO_INCREMENT,
  `LAB` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_NS`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `niveau_sportif`
--

INSERT INTO `niveau_sportif` (`ID_NS`, `LAB`) VALUES
(1, 'Beginner'),
(2, 'Intermediate'),
(3, 'Advanced'),
(4, 'Expert'),
(5, 'Elite');

-- --------------------------------------------------------

--
-- Table structure for table `reference`
--

DROP TABLE IF EXISTS `reference`;
CREATE TABLE IF NOT EXISTS `reference` (
  `ID_JOURNAL` int NOT NULL,
  `ID_ALIMENT` int NOT NULL,
  `QUANTITE` int NOT NULL,
  `ID_REFERENCE` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID_REFERENCE`),
  KEY `FK_REFERENC_REFERENCE_JOURNAL` (`ID_JOURNAL`),
  KEY `FK_REFERENC_REFERENCE_ALIMENT` (`ID_ALIMENT`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sexe`
--

DROP TABLE IF EXISTS `sexe`;
CREATE TABLE IF NOT EXISTS `sexe` (
  `ID_SEXE` int NOT NULL AUTO_INCREMENT,
  `LAB_SEXE` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_SEXE`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sexe`
--

INSERT INTO `sexe` (`ID_SEXE`, `LAB_SEXE`) VALUES
(1, 'Male'),
(2, 'Female'),
(3, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `se_compose`
--

DROP TABLE IF EXISTS `se_compose`;
CREATE TABLE IF NOT EXISTS `se_compose` (
  `ID_ALIMENT` int NOT NULL,
  `ALI_ID_ALIMENT` int NOT NULL,
  `ID_COMPOSITION` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID_COMPOSITION`),
  KEY `FK_SE_COMPO_SE_COMPOS_ALIMENT` (`ID_ALIMENT`),
  KEY `FK_SE_COMPO_SE_COMPOS_ALIMENT2` (`ALI_ID_ALIMENT`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tranche_age`
--

DROP TABLE IF EXISTS `tranche_age`;
CREATE TABLE IF NOT EXISTS `tranche_age` (
  `ID_AGE` int NOT NULL AUTO_INCREMENT,
  `LAB` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_AGE`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tranche_age`
--

INSERT INTO `tranche_age` (`ID_AGE`, `LAB`) VALUES
(1, '13-39'),
(2, '40-59'),
(3, '60+');

-- --------------------------------------------------------

--
-- Table structure for table `type_aliment`
--

DROP TABLE IF EXISTS `type_aliment`;
CREATE TABLE IF NOT EXISTS `type_aliment` (
  `ID_TA` int NOT NULL,
  `ID_ALIMENT` int DEFAULT NULL,
  `LAB` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_TA`),
  KEY `FK_TYPE_ALI_TYPE_ALIMENT` (`ID_ALIMENT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type_ratio`
--

DROP TABLE IF EXISTS `type_ratio`;
CREATE TABLE IF NOT EXISTS `type_ratio` (
  `ID_TR` int NOT NULL AUTO_INCREMENT,
  `LAB` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_TR`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `type_ratio`
--

INSERT INTO `type_ratio` (`ID_TR`, `LAB`) VALUES
(6, 'Energie (kj/100g)'),
(7, 'Energie (kcal/100g)'),
(8, 'Eau (g/100g)'),
(9, 'Lipides (g/100g)'),
(10, 'Glucides (g/100g)'),
(11, 'Sucres (g/100g)'),
(12, 'Protéines (g/100g)'),
(13, 'Fibres alimentaires (g/100g)'),
(14, 'Cholestérol (mg/100g)'),
(15, 'Calcium (mg/100g)'),
(16, 'Fer (mg/100g)'),
(17, 'Potassium (mg/100g)'),
(18, 'Zinc (mg/100g)'),
(19, 'Magnésium (mg/100g)'),
(20, 'Vitamine C (mg/100g)'),
(21, 'Vitamine D (µg/100g)'),
(22, 'Vitamine E (mg/100g)');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `ID_UTILISATEUR` int NOT NULL AUTO_INCREMENT,
  `ID_AGE` int NOT NULL,
  `ID_SEXE` int NOT NULL,
  `ID_NS` int NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  PRIMARY KEY (`ID_UTILISATEUR`),
  KEY `FK_UTILISAT_T_AGE_TRANCHE_` (`ID_AGE`),
  KEY `FK_UTILISAT_SEXE_SEXE` (`ID_SEXE`),
  KEY `FK_UTILISAT_NIVEAU_SP_NIVEAU_S` (`ID_NS`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contient`
--
ALTER TABLE `contient`
  ADD CONSTRAINT `FK_CONTIENT_CONTIENT2_TYPE_RAT` FOREIGN KEY (`ID_TR`) REFERENCES `type_ratio` (`ID_TR`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CONTIENT_CONTIENT_ALIMENT` FOREIGN KEY (`ID_ALIMENT`) REFERENCES `aliment` (`ID_ALIMENT`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `journal`
--
ALTER TABLE `journal`
  ADD CONSTRAINT `FK_JOURNAL_AVOIR_UTILISAT` FOREIGN KEY (`ID_UTILISATEUR`) REFERENCES `utilisateur` (`ID_UTILISATEUR`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `reference`
--
ALTER TABLE `reference`
  ADD CONSTRAINT `FK_REFERENC_REFERENCE_ALIMENT` FOREIGN KEY (`ID_ALIMENT`) REFERENCES `aliment` (`ID_ALIMENT`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_REFERENC_REFERENCE_JOURNAL` FOREIGN KEY (`ID_JOURNAL`) REFERENCES `journal` (`ID_JOURNAL`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `se_compose`
--
ALTER TABLE `se_compose`
  ADD CONSTRAINT `FK_SE_COMPO_SE_COMPOS_ALIMENT` FOREIGN KEY (`ID_ALIMENT`) REFERENCES `aliment` (`ID_ALIMENT`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_SE_COMPO_SE_COMPOS_ALIMENT2` FOREIGN KEY (`ALI_ID_ALIMENT`) REFERENCES `aliment` (`ID_ALIMENT`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `type_aliment`
--
ALTER TABLE `type_aliment`
  ADD CONSTRAINT `FK_TYPE_ALI_TYPE_ALIMENT` FOREIGN KEY (`ID_ALIMENT`) REFERENCES `aliment` (`ID_ALIMENT`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `FK_UTILISAT_NIVEAU_SP_NIVEAU_S` FOREIGN KEY (`ID_NS`) REFERENCES `niveau_sportif` (`ID_NS`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_UTILISAT_SEXE_SEXE` FOREIGN KEY (`ID_SEXE`) REFERENCES `sexe` (`ID_SEXE`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_UTILISAT_T_AGE_TRANCHE_` FOREIGN KEY (`ID_AGE`) REFERENCES `tranche_age` (`ID_AGE`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
