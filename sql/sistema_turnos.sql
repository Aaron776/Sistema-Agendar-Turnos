-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-09-2025 a las 02:54:17
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_turnos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Consulta General', NULL),
(2, 'Control', NULL),
(3, 'Examen Medico', NULL),
(4, 'Vacunacion', NULL),
(5, 'Emergencia', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id` int(10) UNSIGNED NOT NULL,
  `servicio_id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `nota_adicional` text DEFAULT NULL,
  `estado` enum('pendiente','realizado') NOT NULL DEFAULT 'pendiente',
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id`, `servicio_id`, `usuario_id`, `fecha_cita`, `hora_cita`, `cedula`, `nota_adicional`, `estado`, `creado_en`, `actualizado_en`) VALUES
(12, 1, 8, '2025-08-24', '15:03:00', '1725159682', '', 'realizado', '2025-08-23 19:03:07', '2025-08-26 19:34:51'),
(14, 1, 8, '2025-08-29', '12:12:00', '1725159682', '', 'realizado', '2025-08-23 19:12:57', '2025-08-29 19:39:25'),
(15, 5, 8, '2025-08-24', '11:23:00', '1725159682', '', 'realizado', '2025-08-23 19:23:42', '2025-08-26 19:34:51'),
(16, 5, 8, '2025-08-29', '16:23:00', '1725159682', '', 'realizado', '2025-08-23 19:24:15', '2025-08-29 19:39:25'),
(17, 4, 8, '2025-09-05', '09:53:00', '1725159682', '', 'pendiente', '2025-08-26 19:53:25', '2025-08-26 19:53:25'),
(18, 2, 8, '2025-08-29', '15:39:00', '1725159682', '', 'realizado', '2025-08-29 19:40:11', '2025-08-30 17:58:36'),
(19, 3, 8, '2025-08-31', '15:50:00', '1725159682', '', 'realizado', '2025-08-29 19:47:38', '2025-09-01 11:32:23'),
(20, 3, 8, '2025-09-12', '15:50:00', '1725159682', '', 'pendiente', '2025-08-29 20:25:30', '2025-08-29 20:25:30'),
(21, 3, 8, '2025-08-31', '15:26:00', '1725159682', '', 'realizado', '2025-08-31 18:26:59', '2025-09-01 11:32:23'),
(22, 3, 8, '2025-09-26', '09:30:00', '1734164544', '', 'pendiente', '2025-09-04 18:27:28', '2025-09-04 18:27:28'),
(23, 4, 14, '2025-10-04', '11:48:00', '1725159600', '', 'pendiente', '2025-09-04 18:55:44', '2025-09-04 18:55:44'),
(24, 3, 14, '2025-09-13', '15:16:00', '1725159600', '', 'pendiente', '2025-09-04 19:27:02', '2025-09-04 19:27:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','cliente') NOT NULL DEFAULT 'cliente',
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `email`, `cedula`, `password`, `rol`, `creado_en`, `actualizado_en`) VALUES
(3, 'Andres Garcia', 'Andres', 'andres@yahoo.com', '1725159683', '$2y$10$H1tNTtYeKiyCWnI/Ds.O4OICc398YiybN8xxjN1QsaazqIUPQFBvS', 'cliente', '2025-08-20 19:01:48', '2025-09-04 18:18:01'),
(6, 'Pablo Marin', 'Pablo', 'pablo@yahoo.com', '1725159682', 'mXv1L05jWs', 'cliente', '2025-08-21 20:11:49', '2025-09-04 18:18:09'),
(8, 'Patricio Ortiz', 'Patricio', 'patricio@yahoo.com', '1734164544', '$2y$10$UkzX6mxli3yrhsMrEy6uYuJUSg8TW8BdaNpgD.k7jP3U9Cn4rLDB6', 'cliente', '2025-08-22 18:20:46', '2025-09-04 19:25:52'),
(10, 'Aaron Ortiz', 'Aaron', 'aronortiz90@yahoo.com', '1725159687', '$2y$10$Wwr4Nvb/xjw7/iTjDswtdO46pL/hQKltw0Y.Lz56BSC8dRkuqpuZm', 'admin', '2025-08-27 20:30:33', '2025-09-04 18:18:34'),
(12, 'Rafael Ortega', 'Rafa56', 'rafael@yahoo.com', '1726169945', '$2y$10$1gcfJFuXun14TvNSUj2B2.4recS/jwMe4zZcOgu7ZCPH6JGvdtxMG', 'cliente', '2025-08-29 20:00:13', '2025-09-04 18:18:49'),
(13, 'Dagmar Moya', 'Dagmar', 'lookdagmar@yahoo.es', '1725159621', '$2y$10$W3fXbrb2lC7SQl2UO0bZ8uWSMakVWT5IwhsIUdd0LOd6cMjzFD.ay', 'cliente', '2025-08-30 20:12:59', '2025-09-04 18:19:04'),
(14, 'Ronaldo Perez', 'Ronaldo', 'ronaldo@yahoo.com', '1725159600', '$2y$10$o3PpZJYbj4GWtWULdkCbhesfonNb.l7ox/ugK5dL5kk85B9FQnsrC', 'cliente', '2025-09-04 18:55:00', '2025-09-04 18:55:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_servicio_nombre` (`nombre`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_servicio_inicio` (`servicio_id`,`fecha_cita`,`hora_cita`),
  ADD KEY `fk_turno_usuario` (`usuario_id`),
  ADD KEY `idx_servicio_estado_fecha` (`servicio_id`,`estado`,`fecha_cita`,`hora_cita`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuario_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `fk_turno_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turno_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
