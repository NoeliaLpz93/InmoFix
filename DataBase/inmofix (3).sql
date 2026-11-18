-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-11-2026 a las 06:27:17
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inmofix`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratos`
--

CREATE TABLE `contratos` (
  `IdContrato` int(11) NOT NULL,
  `IdInmueble` int(11) NOT NULL,
  `IdInquilino` int(11) NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaFin` date NOT NULL,
  `ValorInicial` decimal(10,2) NOT NULL,
  `TipoAjuste` varchar(20) NOT NULL,
  `PorcentajeAjuste` decimal(5,2) NOT NULL,
  `Estado` varchar(20) DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contratos`
--

INSERT INTO `contratos` (`IdContrato`, `IdInmueble`, `IdInquilino`, `FechaInicio`, `FechaFin`, `ValorInicial`, `TipoAjuste`, `PorcentajeAjuste`, `Estado`) VALUES
(16, 2, 1, '2026-01-01', '2026-12-31', 250000.00, 'Trimestral', 15.00, 'Activo'),
(17, 6, 2, '2026-03-01', '2027-02-28', 320000.00, 'Cuatrimestral', 12.00, 'Activo'),
(18, 5, 3, '2025-07-01', '2026-06-30', 400000.00, 'Semestral', 20.00, 'Finalizado'),
(19, 1, 4, '2026-09-01', '2027-08-31', 180000.00, 'Trimestral', 10.00, 'Activo'),
(20, 7, 5, '2026-10-01', '2027-09-30', 280000.00, 'Semestral', 18.00, 'Activo'),
(21, 4, 6, '2026-10-05', '2026-12-05', 450000.00, 'Porcentaje', 10.00, 'Activo'),
(22, 5, 2, '2026-10-23', '2026-11-23', 250000.00, 'Porcentaje', 15.00, 'Activo'),
(23, 7, 9, '2026-10-14', '2026-12-14', 500000.00, 'Trimestral', 0.00, 'Finalizado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inmuebles`
--

CREATE TABLE `inmuebles` (
  `IdInmueble` int(11) NOT NULL,
  `Direccion` varchar(120) NOT NULL,
  `Tipo` varchar(50) NOT NULL,
  `Estado` varchar(30) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inmuebles`
--

INSERT INTO `inmuebles` (`IdInmueble`, `Direccion`, `Tipo`, `Estado`, `Descripcion`, `imagen`) VALUES
(1, 'San Martín 1251', 'Departamento', 'Disponible', 'Departamento de 2 dormitorios, cocina, baño y balcón.', 'DeptoSanMartin.jpg'),
(2, 'Lamadrid 130', 'Casa', 'Alquilado', 'Terreno 456M2, edificado 134M2, compuesta de 3 dormitorios, living, comedor, cocina, baño, lavadero, entrada para auto, y patio', 'CasaLamadrid130.jpg'),
(4, 'Loteo Suarez', 'Casa', 'Mantenimiento', 'Vivienda en Loteo Suarez sobre terreno de 1139M2, con una edificación de 174M2, compuesta de 2 dormitorios, living, cocina-comedor amplia, baño, cochera doble, galería, patio, y pileta.', 'ViviendaLoteoSuarez.jpg'),
(5, 'Mitre 678', 'Local', 'Alquilado', 'Local comercial ubicado en zona céntrica.', 'LocalMitre678.jpg'),
(6, 'Ortiz Herrera 920', 'Departamento', 'Alquilado', 'Departamento en Planta alta con 2 Dormitorios, Cocina, Comedor, Estar, Baño con accesorios y Balcón pequeño', 'DeptoOrtizHerrera.jpeg'),
(7, 'Salta 28', 'Casa', 'Alquilado', 'Terreno 318M2, edificado 192M2, compuesta de 2 dormitorios, living-comedor, cocina, comedor, 2 baños, lavadero, altillo, garaje, quincho, pileta, y patio.', 'Salta28.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inquilinos`
--

CREATE TABLE `inquilinos` (
  `id` int(11) NOT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `Usuario` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inquilinos`
--

INSERT INTO `inquilinos` (`id`, `apellido`, `nombre`, `dni`, `telefono`, `email`, `Usuario`) VALUES
(1, 'Gómez', 'Luciana', '40123456', '3534123456', 'lucia.gomez@email.com', NULL),
(2, 'Fernández', 'Martín', '38987654', '3534987654', 'martin.fernandez@email.com', NULL),
(3, 'Rodríguez', 'Sofía', '42111222', '3534556789', 'sofia.rodriguez@email.com', NULL),
(4, 'Martínez', 'Nicolás', '37444555', '3534678901', 'nicolas.martinez@email.com', NULL),
(5, 'López', 'Camila', '41555666', '3534234567', 'camila.lopez@email.com', NULL),
(6, 'Sánchez', 'Tomás', '39666777', '3534890123', 'tomas.sanchez@email.com', NULL),
(9, 'Ruiz', 'Maria ', '40444555', '3537456896', 'ruizmaria@gmail.com', NULL),
(13, 'Ruiz', 'María', '4012345', '3537456896', 'mruiz@gmail.com', NULL),
(15, 'Legrand', 'Mirtha', '3123456', NULL, 'mirthalegrand@gmail.com', NULL),
(16, 'Legrand', 'Mirtha', '3123456', NULL, 'mirthalegrand@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `IdNotificacion` int(11) NOT NULL,
  `IdUsuario` int(11) NOT NULL,
  `Mensaje` varchar(255) NOT NULL,
  `Fecha` datetime NOT NULL,
  `Leido` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `IdPago` int(11) NOT NULL,
  `IdContrato` int(11) NOT NULL,
  `Estado` varchar(20) NOT NULL,
  `Monto` decimal(10,2) NOT NULL,
  `FechaEmision` date DEFAULT NULL,
  `FechaVencimiento` date DEFAULT NULL,
  `FechaPago` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`IdPago`, `IdContrato`, `Estado`, `Monto`, `FechaEmision`, `FechaVencimiento`, `FechaPago`) VALUES
(1, 23, 'Pagado', 500000.00, '2026-11-17', '2026-12-02', '2026-11-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibos`
--

CREATE TABLE `recibos` (
  `IdRecibo` int(11) NOT NULL,
  `IdPago` int(11) NOT NULL,
  `ArchivoPDF` varchar(255) NOT NULL,
  `FechaPago` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reclamos`
--

CREATE TABLE `reclamos` (
  `IdReclamo` int(11) NOT NULL,
  `IdInquilino` int(11) NOT NULL,
  `IdInmueble` int(11) NOT NULL,
  `Categoria` varchar(50) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Estado` varchar(20) NOT NULL,
  `FechaCreacion` datetime NOT NULL,
  `FechaResolucion` datetime DEFAULT NULL,
  `Prioridad` varchar(20) NOT NULL DEFAULT 'Media'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reclamos`
--

INSERT INTO `reclamos` (`IdReclamo`, `IdInquilino`, `IdInmueble`, `Categoria`, `Descripcion`, `Estado`, `FechaCreacion`, `FechaResolucion`, `Prioridad`) VALUES
(7, 1, 1, 'Plomería', 'Pérdida de agua en la canilla de la cocina.', 'En Proceso', '2026-10-01 09:30:00', NULL, 'Alta'),
(8, 2, 2, 'Electricidad', 'Una de las luces del living no funciona correctamente.', 'En proceso', '2026-10-02 14:15:00', NULL, 'Media'),
(9, 3, 4, 'Humedad', 'Se observan manchas de humedad en una de las paredes del dormitorio.', 'Pendiente', '2026-10-03 11:20:00', NULL, 'Alta'),
(10, 1, 5, 'Mantenimiento', 'La persiana del local quedó trabada y no puede abrirse correctamente.', 'Resuelto', '2026-09-25 10:00:00', '2026-09-27 16:30:00', 'Media'),
(11, 2, 6, 'Gas', 'La cocina presenta una falla en una de las hornallas.', 'En proceso', '2026-10-04 08:45:00', NULL, 'Alta'),
(12, 3, 7, 'Plomería', 'El desagüe del baño se encuentra parcialmente tapado.', 'Pendiente', '2026-10-05 17:10:00', NULL, 'Media');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `IdUsuario` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Email` varchar(80) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Rol` varchar(20) NOT NULL,
  `Usuario` varchar(50) DEFAULT NULL,
  `idInquilino` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IdUsuario`, `Nombre`, `Apellido`, `Email`, `Password`, `Rol`, `Usuario`, `idInquilino`) VALUES
(4, 'Mirtha', 'Legrand', 'mirthalegrand@gmail.com', '$2y$10$3/upCOoIt2U7F1OZbVJ6xOjS.O0XxpL2IHCPnKjWbGITesxT0RH3a', 'Inquilino', '3123456', 16),
(5, 'Pablo', 'Martinez', 'pablom@gmail.com', '$2y$10$jJ3lc0Xc.wyEHbTf.QgeKOw3Z2aEeF8VYrr0dL.0ZVGTuvlsJqKKC', 'Administrador', 'admin', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`IdContrato`),
  ADD KEY `IdInmueble` (`IdInmueble`),
  ADD KEY `IdInquilino` (`IdInquilino`);

--
-- Indices de la tabla `inmuebles`
--
ALTER TABLE `inmuebles`
  ADD PRIMARY KEY (`IdInmueble`);

--
-- Indices de la tabla `inquilinos`
--
ALTER TABLE `inquilinos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`IdNotificacion`),
  ADD KEY `IdUsuario` (`IdUsuario`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`IdPago`),
  ADD KEY `IdContrato` (`IdContrato`);

--
-- Indices de la tabla `recibos`
--
ALTER TABLE `recibos`
  ADD PRIMARY KEY (`IdRecibo`),
  ADD KEY `IdPago` (`IdPago`);

--
-- Indices de la tabla `reclamos`
--
ALTER TABLE `reclamos`
  ADD PRIMARY KEY (`IdReclamo`),
  ADD KEY `IdInquilino` (`IdInquilino`),
  ADD KEY `IdInmueble` (`IdInmueble`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IdUsuario`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `fk_usuario_inquilino` (`idInquilino`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contratos`
--
ALTER TABLE `contratos`
  MODIFY `IdContrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `inmuebles`
--
ALTER TABLE `inmuebles`
  MODIFY `IdInmueble` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `inquilinos`
--
ALTER TABLE `inquilinos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `IdNotificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `IdPago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `recibos`
--
ALTER TABLE `recibos`
  MODIFY `IdRecibo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reclamos`
--
ALTER TABLE `reclamos`
  MODIFY `IdReclamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IdUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `contratos_ibfk_1` FOREIGN KEY (`IdInmueble`) REFERENCES `inmuebles` (`IdInmueble`),
  ADD CONSTRAINT `contratos_ibfk_2` FOREIGN KEY (`IdInquilino`) REFERENCES `inquilinos` (`id`);

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`IdContrato`) REFERENCES `contratos` (`IdContrato`);

--
-- Filtros para la tabla `recibos`
--
ALTER TABLE `recibos`
  ADD CONSTRAINT `recibos_ibfk_1` FOREIGN KEY (`IdPago`) REFERENCES `pagos` (`IdPago`);

--
-- Filtros para la tabla `reclamos`
--
ALTER TABLE `reclamos`
  ADD CONSTRAINT `reclamos_ibfk_1` FOREIGN KEY (`IdInquilino`) REFERENCES `inquilinos` (`id`),
  ADD CONSTRAINT `reclamos_ibfk_2` FOREIGN KEY (`IdInmueble`) REFERENCES `inmuebles` (`IdInmueble`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_inquilino` FOREIGN KEY (`idInquilino`) REFERENCES `inquilinos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
