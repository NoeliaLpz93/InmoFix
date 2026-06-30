-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: localhost    Database: inmofix
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `contratos`
--

DROP TABLE IF EXISTS `contratos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contratos` (
  `IdContrato` int NOT NULL AUTO_INCREMENT,
  `IdInmueble` int NOT NULL,
  `IdInquilino` int NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaFin` date NOT NULL,
  `ValorInicial` decimal(10,2) NOT NULL,
  `TipoAjuste` varchar(20) NOT NULL,
  `PorcentajeAjuste` decimal(5,2) NOT NULL,
  `Estado` varchar(20) DEFAULT 'Activo',
  PRIMARY KEY (`IdContrato`),
  KEY `IdInmueble` (`IdInmueble`),
  KEY `IdInquilino` (`IdInquilino`),
  CONSTRAINT `contratos_ibfk_1` FOREIGN KEY (`IdInmueble`) REFERENCES `inmuebles` (`IdInmueble`),
  CONSTRAINT `contratos_ibfk_2` FOREIGN KEY (`IdInquilino`) REFERENCES `usuarios` (`IdUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contratos`
--

LOCK TABLES `contratos` WRITE;
/*!40000 ALTER TABLE `contratos` DISABLE KEYS */;
/*!40000 ALTER TABLE `contratos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inmuebles`
--

DROP TABLE IF EXISTS `inmuebles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inmuebles` (
  `IdInmueble` int NOT NULL AUTO_INCREMENT,
  `Direccion` varchar(120) NOT NULL,
  `Tipo` varchar(50) NOT NULL,
  `Estado` varchar(30) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdInmueble`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inmuebles`
--

LOCK TABLES `inmuebles` WRITE;
/*!40000 ALTER TABLE `inmuebles` DISABLE KEYS */;
/*!40000 ALTER TABLE `inmuebles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inquilinos`
--

DROP TABLE IF EXISTS `inquilinos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inquilinos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `apellido` varchar(50) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inquilinos`
--

LOCK TABLES `inquilinos` WRITE;
/*!40000 ALTER TABLE `inquilinos` DISABLE KEYS */;
/*!40000 ALTER TABLE `inquilinos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `IdNotificacion` int NOT NULL AUTO_INCREMENT,
  `IdUsuario` int NOT NULL,
  `Mensaje` varchar(255) NOT NULL,
  `Fecha` datetime NOT NULL,
  `Leido` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`IdNotificacion`),
  KEY `IdUsuario` (`IdUsuario`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos` (
  `IdPago` int NOT NULL AUTO_INCREMENT,
  `IdContrato` int NOT NULL,
  `Estado` varchar(20) NOT NULL,
  `Monto` decimal(10,2) NOT NULL,
  `FechaPago` date NOT NULL,
  PRIMARY KEY (`IdPago`),
  KEY `IdContrato` (`IdContrato`),
  CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`IdContrato`) REFERENCES `contratos` (`IdContrato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--

LOCK TABLES `pagos` WRITE;
/*!40000 ALTER TABLE `pagos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recibos`
--

DROP TABLE IF EXISTS `recibos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recibos` (
  `IdRecibo` int NOT NULL AUTO_INCREMENT,
  `IdPago` int NOT NULL,
  `ArchivoPDF` varchar(255) NOT NULL,
  `FechaPago` datetime NOT NULL,
  PRIMARY KEY (`IdRecibo`),
  KEY `IdPago` (`IdPago`),
  CONSTRAINT `recibos_ibfk_1` FOREIGN KEY (`IdPago`) REFERENCES `pagos` (`IdPago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recibos`
--

LOCK TABLES `recibos` WRITE;
/*!40000 ALTER TABLE `recibos` DISABLE KEYS */;
/*!40000 ALTER TABLE `recibos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reclamos`
--

DROP TABLE IF EXISTS `reclamos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reclamos` (
  `IdReclamo` int NOT NULL AUTO_INCREMENT,
  `IdInquilino` int NOT NULL,
  `IdInmueble` int NOT NULL,
  `Categoria` varchar(50) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Estado` varchar(20) NOT NULL,
  `FechaCreacion` datetime NOT NULL,
  `FechaResolucion` datetime DEFAULT NULL,
  `Prioridad` varchar(20) NOT NULL DEFAULT 'Media',
  PRIMARY KEY (`IdReclamo`),
  KEY `IdInquilino` (`IdInquilino`),
  KEY `IdInmueble` (`IdInmueble`),
  CONSTRAINT `reclamos_ibfk_1` FOREIGN KEY (`IdInquilino`) REFERENCES `usuarios` (`IdUsuario`),
  CONSTRAINT `reclamos_ibfk_2` FOREIGN KEY (`IdInmueble`) REFERENCES `inmuebles` (`IdInmueble`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reclamos`
--

LOCK TABLES `reclamos` WRITE;
/*!40000 ALTER TABLE `reclamos` DISABLE KEYS */;
/*!40000 ALTER TABLE `reclamos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `IdUsuario` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Email` varchar(80) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Rol` varchar(20) NOT NULL,
  PRIMARY KEY (`IdUsuario`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-29 22:06:43
