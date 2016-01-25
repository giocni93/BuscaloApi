-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 25-01-2016 a las 13:48:10
-- Versión del servidor: 5.6.28
-- Versión de PHP: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `faltaelp_buscalo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificacionempresa`
--

CREATE TABLE IF NOT EXISTS `calificacionempresa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calificacion` tinyint(3) unsigned NOT NULL,
  `idEmpresa` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idEmpresa` (`idEmpresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoriascliente`
--

CREATE TABLE IF NOT EXISTS `categoriascliente` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idCategoria` int(10) unsigned NOT NULL,
  `idCliente` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idCliente` (`idCliente`),
  KEY `idCategoria` (`idCategoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoriaspromocion`
--

CREATE TABLE IF NOT EXISTS `categoriaspromocion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idCategoria` int(10) unsigned NOT NULL,
  `idPromocion` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idCategoria` (`idCategoria`),
  KEY `idPromocion` (`idPromocion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoriassucursal`
--

CREATE TABLE IF NOT EXISTS `categoriassucursal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idCategoria` int(10) unsigned NOT NULL,
  `idSucursal` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idCategoria` (`idCategoria`),
  KEY `idSucursal` (`idSucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `email` varchar(40) NOT NULL,
  `nombres` varchar(20) NOT NULL,
  `apellidos` varchar(20) DEFAULT NULL,
  `pass` varchar(30) NOT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `idPush` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE IF NOT EXISTS `empresa` (
  `email` varchar(40) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `foto` varchar(30) DEFAULT NULL,
  `pass` varchar(30) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotosoferta`
--

CREATE TABLE IF NOT EXISTS `fotosoferta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foto` varchar(50) DEFAULT NULL,
  `idOferta` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idOferta` (`idOferta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotospeticion`
--

CREATE TABLE IF NOT EXISTS `fotospeticion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foto` varchar(50) DEFAULT NULL,
  `idPeticion` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idPeticion` (`idPeticion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oferta`
--

CREATE TABLE IF NOT EXISTS `oferta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `precio` int(10) unsigned NOT NULL,
  `estado` enum('NUEVO','USADO') NOT NULL,
  `domicilio` enum('SI','NO') NOT NULL,
  `precioDomicilio` int(10) unsigned NOT NULL,
  `cantidadDisponible` smallint(5) unsigned NOT NULL DEFAULT '0',
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idUsuario` varchar(12) NOT NULL,
  `idPeticion` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idSucursal` (`idUsuario`),
  KEY `idPeticion` (`idPeticion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peticion`
--

CREATE TABLE IF NOT EXISTS `peticion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `idCategoria` int(10) unsigned NOT NULL,
  `idCliente` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idCategoria` (`idCategoria`),
  KEY `idCliente` (`idCliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE IF NOT EXISTS `promocion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `foto` varchar(30) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaFinal` date NOT NULL,
  `idUsuario` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idSucursal` (`idUsuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE IF NOT EXISTS `sucursal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `direccion` varchar(30) DEFAULT NULL,
  `latitud` varchar(10) DEFAULT NULL,
  `longitud` varchar(10) DEFAULT NULL,
  `idEmpresa` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idEmpresa` (`idEmpresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` varchar(12) NOT NULL,
  `nombres` varchar(30) NOT NULL,
  `apellidos` varchar(30) DEFAULT NULL,
  `pass` varchar(15) NOT NULL,
  `idPush` varchar(100) DEFAULT NULL,
  `idSucursal` int(10) unsigned NOT NULL,
  `idCategoria` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idSucursal` (`idSucursal`,`idCategoria`),
  KEY `idCategoria` (`idCategoria`),
  KEY `idSucursal_2` (`idSucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calificacionempresa`
--
ALTER TABLE `calificacionempresa`
  ADD CONSTRAINT `calificacionempresa_ibfk_1` FOREIGN KEY (`idEmpresa`) REFERENCES `empresa` (`email`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `categoriascliente`
--
ALTER TABLE `categoriascliente`
  ADD CONSTRAINT `categoriascliente_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`email`) ON UPDATE CASCADE,
  ADD CONSTRAINT `categoriascliente_ibfk_2` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `categoriaspromocion`
--
ALTER TABLE `categoriaspromocion`
  ADD CONSTRAINT `categoriaspromocion_ibfk_1` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `categoriaspromocion_ibfk_2` FOREIGN KEY (`idPromocion`) REFERENCES `promocion` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `categoriassucursal`
--
ALTER TABLE `categoriassucursal`
  ADD CONSTRAINT `categoriassucursal_ibfk_1` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `categoriassucursal_ibfk_2` FOREIGN KEY (`idSucursal`) REFERENCES `sucursal` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `fotosoferta`
--
ALTER TABLE `fotosoferta`
  ADD CONSTRAINT `fotosoferta_ibfk_1` FOREIGN KEY (`idOferta`) REFERENCES `oferta` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `fotospeticion`
--
ALTER TABLE `fotospeticion`
  ADD CONSTRAINT `fotospeticion_ibfk_1` FOREIGN KEY (`idPeticion`) REFERENCES `peticion` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `oferta`
--
ALTER TABLE `oferta`
  ADD CONSTRAINT `oferta_ibfk_2` FOREIGN KEY (`idPeticion`) REFERENCES `peticion` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `oferta_ibfk_3` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `peticion`
--
ALTER TABLE `peticion`
  ADD CONSTRAINT `peticion_ibfk_1` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `peticion_ibfk_2` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`email`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD CONSTRAINT `promocion_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD CONSTRAINT `sucursal_ibfk_1` FOREIGN KEY (`idEmpresa`) REFERENCES `empresa` (`email`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`idSucursal`) REFERENCES `sucursal` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
