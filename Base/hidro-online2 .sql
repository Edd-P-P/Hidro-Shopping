-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2025 at 09:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hidro-online2`
--

-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `hidro-online2`;
USE `hidro-online2`;
--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `hero_color` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `color_fondo` varchar(7) DEFAULT '#FFFFFF',
  `texto_color` varchar(7) DEFAULT '#000000',
  `boton_primario` varchar(7) DEFAULT '#007bff',
  `boton_secundario` varchar(7) DEFAULT '#6c757d',
  `color_titulo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `hero_color`, `activo`, `created_at`, `color_fondo`, `texto_color`, `boton_primario`, `boton_secundario`, `color_titulo`) VALUES
(1, 'CPVC agua caliente', 'cpvc-agua-caliente', 'Tubos y conexiones de Policloruro de vinilo clorado(CPVC), termoplástico producido por coloración de la resina de policloruro de vinilo(PVC).', 'linear-gradient(rgba(255, 249, 196, 0.5), /* amarillo claro con 70% de opacidad */ rgba(219, 248, 196, 0.5)', 1, '2025-09-23 17:46:58', '#FFF9C4', '#000000', '#007bff', '#6c757d', '#1972eb'),
(2, 'Tubería PPR', 'tuberia-ppr', NULL, '#1e3a8a', 1, '2025-09-23 17:46:58', '#ffffff', '#000000', '#007bff', '#6c757d', '#000000'),
(3, 'Hidráulica inglés C-40 PVC', 'ingles-c40-pvc', 'Tubos y conexiones de Policloruro de vinilo clorado(CPVC), termoplástico producido por coloración de la resina de policloruro de vinilo(PVC).', '#1e3a8a', 1, '2025-10-07 16:55:26', '#398cac', '#000000', '#007bff', '#6c757d', '#ffffff'),
(4, 'Cementos', 'cementos', NULL, '#1e3a8a', 1, '2025-10-07 16:26:08', '#ffffff', '#000000', '#007bff', '#6c757d', '#000000'),
(5, 'Hidráulica inglés C-80 PVC', 'ingles-c80-pvc', NULL, '#1e3a8a', 1, '2025-10-07 16:55:26', '#ffffff', '#000000', '#007bff', '#6c757d', '#000000'),
(6, 'Hidráulica inglés campana CPVC', 'campana-CPVC', NULL, '#1e3a8a', 1, '2025-10-07 16:58:27', '#ffffff', '#000000', '#007bff', '#6c757d', '#000000'),
(7, 'Tuberías especiales', 'tuberia-especial', NULL, '#1e3a8a', 1, '2025-10-07 16:58:27', '#ffffff', '#000000', '#007bff', '#6c757d', '#000000'),
(8, 'Tubería galvanizada', 'tuberia-galvanizada', 'Tubos y conexiones de fierro galvanizado, ideales para conducción exterior y de alta presión', '#1e3a8a', 1, '2025-09-23 17:46:58', '#7b797a', '#ff070e', '#007bff', '#6c757d', '#ffffff'),
(9, 'Toma domiciliaria', 'toma-domi', NULL, '#1e3a8a', 1, '2025-09-23 17:46:58', '#ffffff', '#000000', '#007bff', '#6c757d', '#000000'),
(10, 'Medidores y valvulas', 'medidores-y-valvulas', NULL, '#1e3a8a', 1, '2025-09-23 17:46:58', '#ffffff', '#000000', '#007bff', '#6c757d', '#000000'),
(11, 'Conexiones fierro fundido', 'Conexiones fierro fundido', NULL, '#1e3a8a', 1, '2025-10-07 17:02:15', '#ffffff', '#000000', '#007bff', '#6c757d', '#000000'),
(12, 'Alacantarillado métrico campana', 'métrico-campana', NULL, '#1e3a8a', 1, '2025-10-07 17:02:15', '#ffffff', '#000000', '#007bff', '#6c757d', '#000000'),
(13, 'Tubería polietileno corrugado', 'polietileno-corrugado', NULL, '#1e3a8a', 1, '2025-10-07 17:08:28', '#ffffff', '#000000', '#007bff', '#6c757d', '#000000'),
(14, 'Linea Sanitaria', 'linea-sanitaria', 'Tubos y conexiones de Policloruro de vinilo clorado(CPVC), termoplástico producido por coloración de la resina de policloruro de vinilo(PVC).', '#1e3a8a', 1, '2025-09-23 17:46:58', '#917d70', '#000000', '#007bff', '#6c757d', '#ffffff'),
(15, 'Productos destacados', 'Featured', 'Productos que van a aparecer en index', '#1e3a8a', 1, '2025-09-23 20:23:01', '#ffffff', '#000000', '#007bff', '#6c757d', '#000000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
