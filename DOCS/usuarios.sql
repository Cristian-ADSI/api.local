-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-01-2025 a las 15:55:48
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
-- Base de datos: `api.local`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_ultimo_acceso` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` enum('activo','inactivo','suspendido') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `contrasena`, `fecha_nacimiento`, `telefono`, `direccion`, `fecha_creacion`, `fecha_ultimo_acceso`, `estado`) VALUES
(1, 'Juan',     'Pérez',    'juan.perez@example.com',       '$2y$10$EZ0zIRsVRYvIdClCV8dKn.8H9V1Xltbg0wvQ9HEk19sLlmYzRYSeO', '1990-05-15', '555123456', 'Calle Ficticia 123, Ciudad, País',        '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(2, 'Ana',      'Gómez',    'ana.gomez@example.com',        '$2y$10$TlffjZC6kd7SKGsf1dehfW5nuzM2x8zszMvsTjVsF/fL.IHF.HTvK', '1985-08-22', '555234567', 'Avenida Siempre Viva 456, Ciudad, País',  '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(3, 'Carlos',   'Lopez',    'carlos.lopez@example.com',     '$2y$10$hHl3KQUwymbJXG2TLejZ9.d9sG4rp0dGRlX7Vw/gOnFYsQfHlLOeO', '1992-02-10', '555345678', 'Calle Sol 789, Ciudad, País',             '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(4, 'Maria',    'Martínez', 'maria.martinez@example.com',   '$2y$10$pxn6Sy16Fr8QwV4kKht5A.Y5YVvHBYE8V5MlHZnJH0pyqDkPRftR.', '1993-11-30', '555456789', 'Calle Luna 101, Ciudad, País',            '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(5, 'José',     'Ramírez',  'jose.ramirez@example.com',     '$2y$10$9gs10OgiIAYeyXtzq6vslGum1ZK9RH9N1D6zV83cJKVVTTwBikJH.', '1988-04-05', '555567890', 'Calle Estrella 202, Ciudad, País',        '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(6, 'Laura',    'Sánchez',  'laura.sanchez@example.com',    '$2y$10$4HLtZylpwDDx5ktHKYLP4u0OykHvYvnDaFU7A0lt.zCAuqUudFNEC', '1991-07-18', '555678901', 'Avenida Mar 303, Ciudad, País',           '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(7, 'Miguel',   'Díaz',     'miguel.diaz@example.com',      '$2y$10$W6PDRJ0oXZ50EnK7knUnUu04A5a5XjFvPyfJ.Mlbgnc4jflHmc5p2', '1995-12-03', '555789012', 'Calle Río 404, Ciudad, País',             '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(8, 'Isabel',   'Torres',   'isabel.torres@example.com',    '$2y$10$Lg7GsdzCr.J5krRrLje7coIaEKsc2bLwNkwfZQks6kD3IFaY1m5Fe', '1994-06-25', '555890123', 'Avenida del Sol 505, Ciudad, País',       '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(9, 'Francisco','García',   'francisco.garcia@example.com', '$2y$10$hC3XkBXu7jEOVQss0HZt6G7.Jy6E8zXjRuBaClBs3Yo7H4nnq7Riq', '1987-01-17', '555901234', 'Calle Palma 606, Ciudad, País',           '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(10, 'Beatriz', 'Hernández','beatriz.hernandez@example.com','$2y$10$MKQwvXz01B1rtV7jpuf8l.EkD5jABZGvLdoDeO1sk4oDq3jwzmB6o', '1996-09-30', '555012345', 'Avenida Libertad 707, Ciudad, País',      '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(11, 'Oscar',   'Mendoza',  'oscar.mendoza@example.com',    '$2y$10$5wR5YKm6ktF7j60g9A6Uq9rq6d1B1nTpOtUMsc2yjsjT53eJbYt6u', '1990-03-12', '555123987', 'Calle Bosque 808, Ciudad, País',          '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(12, 'Paula',   'Romero',   'paula.romero@example.com',     '$2y$10$7HZ9aoyVmnR6tqQfbV0ApHvX1qE8Op7roKH23AeGgn4hoF6QhsUxe', '1989-10-24', '555234876', 'Avenida Esperanza 909, Ciudad, País',     '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(13, 'Antonio', 'Gutiérrez','antonio.gutierrez@example.com','$2y$10$1y/fqmhnoYmLNe0HcLSEK9sRVwdHiD9xzUOVymkjksJbYIRR9g3t.', '1992-01-14', '555345765', 'Calle Cristal 010, Ciudad, País',         '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(14, 'Raquel',  'Ruiz',     'raquel.ruiz@example.com',      '$2y$10$5tGoV2ODwNwB01ls.IhlYSo4j/J48zAzkaElMPBWSgY6IpyQbVhTS', '1997-04-11', '555456654', 'Avenida Árbol 111, Ciudad, País',         '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo'),
(15, 'Luis',    'Vargas',   'luis.vargas@example.com',      '$2y$10$hKnFBlME2xfk7rs4V7MGDWe2t8ICgwtYjoB33tu2h0GQCh.Nzp6Na', '1994-05-28', '555567543', 'Calle Océano 212, Ciudad, País',          '2025-01-25 14:55:32', '2025-01-25 14:55:32', 'activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
