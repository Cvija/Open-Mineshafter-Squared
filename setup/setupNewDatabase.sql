-- phpMyAdmin SQL Dump
-- version 3.4.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 18, 2011 at 05:13 PM
-- Server version: 5.1.59
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kayoticl_MineshafterSquaredBeta`
--

-- --------------------------------------------------------

--
-- Table structure for table `ActiveCape`
--

DROP TABLE IF EXISTS `ActiveCape`;
CREATE TABLE IF NOT EXISTS `ActiveCape` (
  `userId` int(11) NOT NULL,
  `capeId` int(11) NOT NULL,
  UNIQUE KEY `userId` (`userId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ActiveSkin`
--

DROP TABLE IF EXISTS `ActiveSkin`;
CREATE TABLE IF NOT EXISTS `ActiveSkin` (
  `userId` int(11) DEFAULT NULL,
  `skinId` int(11) DEFAULT NULL,
  UNIQUE KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Capes`
--

DROP TABLE IF EXISTS `Capes`;
CREATE TABLE IF NOT EXISTS `Capes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Data`
--

DROP TABLE IF EXISTS `Data`;
CREATE TABLE IF NOT EXISTS `Data` (
  `property` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  UNIQUE KEY `property` (`property`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Data`
--

INSERT INTO `Data` (`property`, `value`) VALUES
('client-version', '2.9'),
('server-version', '2.2'),
('latest-game-version', '1.8.1'),
('latest-game-build', '1316075312000');

-- --------------------------------------------------------

--
-- Table structure for table `Downloads`
--

DROP TABLE IF EXISTS `Downloads`;
CREATE TABLE IF NOT EXISTS `Downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `version` float NOT NULL,
  `external` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `Downloads`
--

INSERT INTO `Downloads` (`id`, `name`, `description`, `link`, `type`, `version`, `external`) VALUES
(7, 'Windows Launcher', 'The Windows proxy client.', 'downloads/MineshafterSquared-Client.exe', 'client', 2.9, ''),
(2, 'Server Proxy', 'The program that makes a Minecraft Server to use this Authentication Server ', 'downloads/MineshafterSquared-Server.jar', 'main', 2.2, ''),
(3, 'Authentication Server', 'PHP and MySQL based Minecraft Authentication Server', 'https://github.com/KayoticSully/Open-Mineshafter-Squared/zipball/master', 'main', 2, 'GitHub*'),
(4, 'Source Code', 'Because this is open source', 'downloads/Source.zip', 'main', 1.2, ''),
(5, 'Mac OSX Launcher', 'The Mac OS X proxy client', 'downloads/MineshafterSquared-Client.dmg', 'client', 2.9, ''),
(6, 'Client Proxy Jar', 'The Mineshafter Squared Java app. (Not needed if you are using a launcher)', 'downloads/MineshafterSquared-Client.jar', 'client', 2.9, '');

-- --------------------------------------------------------

--
-- Table structure for table `Skins`
--

DROP TABLE IF EXISTS `Skins`;
CREATE TABLE IF NOT EXISTS `Skins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `Skins`
--

INSERT INTO `Skins` (`id`, `userId`, `name`, `description`, `link`) VALUES
(1, 0, 'Default', 'Default Minecraft Skin', 'Default.png');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
CREATE TABLE IF NOT EXISTS `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `session` varchar(255) DEFAULT NULL,
  `server` varchar(255) DEFAULT NULL,
  `createDate` int(11) NOT NULL,
  `lastGameLogin` int(11) NOT NULL,
  `lastWebLogin` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
