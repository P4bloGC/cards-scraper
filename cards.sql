-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 21-10-2015 a las 03:40:22
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `vanguard`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cards`
--

CREATE TABLE IF NOT EXISTS `cards` (
  `cardID` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `name` varchar(60) CHARACTER SET latin1 DEFAULT NULL,
  `uclass` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `triger` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `power` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `critical` varchar(10) COLLATE utf8_spanish_ci DEFAULT '1',
  `shield` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `clan` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `race` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `effect` varchar(1000) CHARACTER SET latin1 DEFAULT NULL,
  `text` varchar(1000) CHARACTER SET latin1 DEFAULT '0',
  `illustrator` varchar(30) COLLATE utf8_spanish_ci DEFAULT '0',
  `nation` varchar(30) CHARACTER SET latin1 DEFAULT '0',
  `grade_skill` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cards`
--
ALTER TABLE `cards`
 ADD PRIMARY KEY (`cardID`), ADD KEY `cardID` (`cardID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
