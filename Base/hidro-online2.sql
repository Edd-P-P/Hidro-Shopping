-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 08:46 PM
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

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `imagen`, `activo`, `created_at`) VALUES
(1, 'CPVC agua caliente', 'cpvc-agua-caliente', NULL, NULL, 1, '2025-09-23 17:46:58'),
(2, 'Tubería PPR', 'tuberia-ppr', NULL, NULL, 1, '2025-09-23 17:46:58'),
(3, 'Tubería galvanizada', 'tuberia-galvanizada', NULL, NULL, 1, '2025-09-23 17:46:58'),
(4, 'Accesorios domésticos', 'accesorios-domesticos', NULL, NULL, 1, '2025-09-23 17:46:58'),
(5, 'Medidores y valvulas', 'medidores-y-valvulas', NULL, NULL, 1, '2025-09-23 17:46:58'),
(6, 'Linea Sanitaria', 'linea-sanitaria', NULL, NULL, 1, '2025-09-23 17:46:58'),
(7, 'Aspersores', 'aspersores', NULL, NULL, 1, '2025-09-23 17:46:58'),
(8, 'Nebulizadores', 'nebulizadores', NULL, NULL, 1, '2025-09-23 17:46:58'),
(9, 'Productos destacados', 'Featured', 'Productos que van a aparecer en index', NULL, 1, '2025-09-23 20:23:01');

-- --------------------------------------------------------

--
-- Table structure for table `medidas_categoria`
--

CREATE TABLE `medidas_categoria` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `medida` varchar(100) NOT NULL,
  `orden` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medidas_categoria`
--

INSERT INTO `medidas_categoria` (`id`, `categoria_id`, `medida`, `orden`) VALUES
(1, 1, '½\"', 1),
(2, 1, '¾\"', 2),
(3, 1, '1\"', 3),
(4, 1, '1 ¼\"', 4),
(5, 1, '1 ½\"', 5),
(6, 1, '2\"', 6);

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tabla_med` text DEFAULT NULL,
  `especificaciones` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `requiere_medidas` tinyint(1) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `descuento` tinyint(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `tabla_med`, `especificaciones`, `precio`, `stock`, `imagen`, `categoria_id`, `requiere_medidas`, `activo`, `created_at`, `updated_at`, `descuento`) VALUES
(1, 'Adaptador Hembra CPVC', '<strong>ADAPTADOR HEMBRA CPVC</strong><br>Conector que permite unir tuberías de CPVC con accesorios de rosca macho. Resistente a altas temperaturas y presión, ideal para instalaciones hidráulicas y de agua caliente.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 8.50, 50, '', 1, 1, 1, '2025-09-23 19:45:31', '2025-09-29 17:59:55', 0),
(2, 'Adaptador Hembra Inserción Metálica CPVC', '<strong>ADAPTADOR HEMBRA INSERCIÓN METÁLICA CPVC</strong><br>Pieza que combina la resistencia del CPVC con una inserción metálica para conexiones roscadas. Aporta durabilidad y evita desgaste en uniones frecuentes.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Inserción metálica <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 12.00, 50, '', 1, 1, 1, '2025-09-23 20:16:40', '2025-09-29 16:04:36', NULL),
(3, 'Adaptador Macho Inserción Metálica CPVC', '<strong>ADAPTADOR MACHO INSERCIÓN METÁLICA CPVC</strong><br>Adaptador de CPVC con inserción metálica roscada macho. Diseñado para conexiones firmes y seguras en instalaciones que requieren resistencia adicional.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 12.00, 50, '', 1, 1, 1, '2025-09-23 20:17:29', '2025-09-29 16:04:36', NULL),
(4, 'Adaptador Macho CPVC', '<strong>ADAPTADOR MACHO CPVC</strong><br>Accesorio que facilita la unión de tuberías CPVC con conexiones de rosca hembra. Ligero, resistente a la corrosión y fácil de instalar.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 7.50, 50, '', 1, 1, 1, '2025-09-23 20:20:19', '2025-09-29 16:04:36', NULL),
(5, 'Tuerca Unión CPVC', '<strong>TUERCA UNIÓN CPVC</strong><br>Pieza desmontable que permite unir o separar secciones de tubería sin cortar. Ideal para mantenimiento en sistemas de agua y reparaciones rápidas.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 6.00, 50, '', 1, 1, 1, '2025-09-23 20:20:19', '2025-09-29 16:04:36', NULL),
(6, 'Codo 90° CPVC', '<strong>CODO 90° CPVC</strong><br>Accesorio para cambiar la dirección del flujo a 90 grados en tuberías de CPVC. Se utiliza en instalaciones hidráulicas y de agua caliente.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 9.00, 50, '', 1, 1, 1, '2025-09-23 20:20:19', '2025-09-29 16:04:36', NULL),
(7, 'Codo 45° CPVC', '<strong>CODO 45° CPVC</strong><br>Codo que permite desviar el flujo a 45 grados. Reduce pérdidas de presión y se usa en trayectos donde se requiere una ligera desviación.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 8.50, 50, '', 1, 1, 1, '2025-09-23 20:20:19', '2025-09-29 16:04:36', NULL),
(8, 'Cople CPVC', '<strong>COPLE CPVC</strong><br>Conector recto para unir dos tramos de tubería CPVC del mismo diámetro. Sencillo y práctico para alargar o reparar tuberías.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 5.50, 50, '', 1, 1, 1, '2025-09-23 20:20:19', '2025-09-29 16:04:36', NULL),
(9, 'Reducción Cople CPVC', '<strong>REDUCCIÓN COPLE CPVC</strong><br>Pieza que une tuberías de diferente diámetro, permitiendo la transición entre medidas. Resistente a la presión y a la corrosión.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 7.00, 50, '', 1, 1, 1, '2025-09-23 20:20:19', '2025-09-29 16:04:36', NULL),
(10, 'TEE CPVC', '<strong>TEE CPVC</strong><br>Accesorio en forma de “T” que permite ramificar el flujo en tres direcciones. Ideal para distribuir agua en diferentes puntos de la instalación.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 15.00, 50, '', 1, 1, 1, '2025-09-23 20:59:54', '2025-09-29 16:04:36', NULL),
(11, 'TEE REDUCIDA CPVC', '<strong>TEE REDUCIDA CPVC</strong><br>Tee que permite la unión de tres tuberías, con una salida de diámetro menor. Perfecta para derivaciones que requieren reducción de flujo.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', '', 16.50, 50, '', 1, 1, 1, '2025-09-23 20:59:54', '2025-09-29 16:05:15', NULL),
(12, 'REDUCCIÓN BUSHING CPVC', '<strong>REDUCCIÓN BUSHING CPVC</strong><br>Accesorio reductor que facilita la conexión entre tuberías de diferentes diámetros. Compacto, durable y de fácil instalación.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 9.00, 50, '', 1, 1, 1, '2025-09-23 20:59:54', '2025-09-29 16:05:58', NULL),
(13, 'PINZAS PARA CORTE', '<strong>PINZAS PARA CORTE</strong><br>Herramienta diseñada para cortar tubería de CPVC de manera precisa y sin rebabas. Facilita el trabajo en instalaciones limpias y seguras.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div>\r\n<table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 35.00, 1, '', 1, 0, 1, '2025-09-23 20:59:54', '2025-09-30 16:00:27', 20),
(14, 'VÁLVULA BOLA CEMENTAR CPVC', '<strong>VÁLVULA BOLA CEMENTAR CPVC</strong><br>Válvula de bola fabricada en CPVC que se instala con cemento. Permite abrir o cerrar el paso del agua de forma rápida y confiable.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 22.00, 50, '', 1, 1, 1, '2025-09-23 20:59:54', '2025-09-29 16:05:59', NULL),
(15, 'TAPA CPVC', '<strong>TAPA CPVC</strong><br>Pieza utilizada para cerrar extremos de tubería. Se emplea en pruebas de presión o para dejar derivaciones listas para futuras conexiones.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 6.50, 50, '', 1, 1, 1, '2025-09-23 20:59:54', '2025-09-29 16:05:59', NULL),
(16, 'CODO PIPA ROSCADO OREJA CPVC', '<strong>CODO PIPA ROSCADO OREJA CPVC</strong><br>Accesorio en codo con base de fijación y salida roscada. Diseñado para instalaciones donde se requiere sujeción firme a muros o superficies.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 18.00, 50, '', 1, 1, 1, '2025-09-23 20:59:54', '2025-09-29 16:05:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `productos_medidas`
--

CREATE TABLE `productos_medidas` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL DEFAULT 0,
  `medida` varchar(100) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `precio_m` decimal(10,2) NOT NULL,
  `descuento_m` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productos_medidas`
--

INSERT INTO `productos_medidas` (`id`, `producto_id`, `categoria_id`, `medida`, `stock`, `precio_m`, `descuento_m`) VALUES
(1, 1, 1, '1 ½\"', 10, 2.00, 20.00),
(2, 1, 1, '1 ¼\"', 3, 4.00, 0.00),
(3, 1, 1, '1\"', 0, 0.00, 0.00),
(4, 1, 1, '½\"', 5, 6.00, 0.00),
(5, 1, 1, '2\"', 20, 12.00, 0.00),
(6, 1, 1, '¾\"', 3, 25.00, 0.00),
(7, 2, 1, '1 ½\"', 0, 0.00, 0.00),
(8, 2, 1, '1 ¼\"', 0, 0.00, 0.00),
(9, 2, 1, '1\"', 0, 0.00, 0.00),
(10, 2, 1, '½\"', 0, 0.00, 0.00),
(11, 2, 1, '2\"', 0, 0.00, 0.00),
(12, 2, 1, '¾\"', 0, 0.00, 0.00),
(13, 3, 1, '1 ½\"', 0, 0.00, 0.00),
(14, 3, 1, '1 ¼\"', 0, 0.00, 0.00),
(15, 3, 1, '1\"', 0, 0.00, 0.00),
(16, 3, 1, '½\"', 0, 0.00, 0.00),
(17, 3, 1, '2\"', 0, 0.00, 0.00),
(18, 3, 1, '¾\"', 0, 0.00, 0.00),
(19, 4, 1, '1 ½\"', 0, 0.00, 0.00),
(20, 4, 1, '1 ¼\"', 0, 0.00, 0.00),
(21, 4, 1, '1\"', 0, 0.00, 0.00),
(22, 4, 1, '½\"', 0, 0.00, 0.00),
(23, 4, 1, '2\"', 0, 0.00, 0.00),
(24, 4, 1, '¾\"', 0, 0.00, 0.00),
(25, 5, 1, '1 ½\"', 0, 0.00, 0.00),
(26, 5, 1, '1 ¼\"', 0, 0.00, 0.00),
(27, 5, 1, '1\"', 0, 0.00, 0.00),
(28, 5, 1, '½\"', 0, 0.00, 0.00),
(29, 5, 1, '2\"', 0, 0.00, 0.00),
(30, 5, 1, '¾\"', 0, 0.00, 0.00),
(31, 6, 1, '1 ½\"', 0, 0.00, 0.00),
(32, 6, 1, '1 ¼\"', 0, 0.00, 0.00),
(33, 6, 1, '1\"', 0, 0.00, 0.00),
(34, 6, 1, '½\"', 0, 0.00, 0.00),
(35, 6, 1, '2\"', 0, 0.00, 0.00),
(36, 6, 1, '¾\"', 0, 0.00, 0.00),
(37, 7, 1, '1 ½\"', 0, 0.00, 0.00),
(38, 7, 1, '1 ¼\"', 0, 0.00, 0.00),
(39, 7, 1, '1\"', 0, 0.00, 0.00),
(40, 7, 1, '½\"', 0, 0.00, 0.00),
(41, 7, 1, '2\"', 0, 0.00, 0.00),
(42, 7, 1, '¾\"', 0, 0.00, 0.00),
(43, 8, 1, '1 ½\"', 0, 0.00, 0.00),
(44, 8, 1, '1 ¼\"', 0, 0.00, 0.00),
(45, 8, 1, '1\"', 0, 0.00, 0.00),
(46, 8, 1, '½\"', 0, 0.00, 0.00),
(47, 8, 1, '2\"', 0, 0.00, 0.00),
(48, 8, 1, '¾\"', 0, 0.00, 0.00),
(49, 9, 1, '1 ½\"', 0, 0.00, 0.00),
(50, 9, 1, '1 ¼\"', 0, 0.00, 0.00),
(51, 9, 1, '1\"', 0, 0.00, 0.00),
(52, 9, 1, '½\"', 0, 0.00, 0.00),
(53, 9, 1, '2\"', 0, 0.00, 0.00),
(54, 9, 1, '¾\"', 0, 0.00, 0.00),
(55, 10, 1, '1 ½\"', 0, 0.00, 0.00),
(56, 10, 1, '1 ¼\"', 0, 0.00, 0.00),
(57, 10, 1, '1\"', 0, 0.00, 0.00),
(58, 10, 1, '½\"', 0, 0.00, 0.00),
(59, 10, 1, '2\"', 0, 0.00, 0.00),
(60, 10, 1, '¾\"', 0, 0.00, 0.00),
(61, 11, 1, '1 ½\"', 0, 0.00, 0.00),
(62, 11, 1, '1 ¼\"', 0, 0.00, 0.00),
(63, 11, 1, '1\"', 0, 0.00, 0.00),
(64, 11, 1, '½\"', 0, 0.00, 0.00),
(65, 11, 1, '2\"', 0, 0.00, 0.00),
(66, 11, 1, '¾\"', 0, 0.00, 0.00),
(67, 12, 1, '1 ½\"', 0, 0.00, 0.00),
(68, 12, 1, '1 ¼\"', 0, 0.00, 0.00),
(69, 12, 1, '1\"', 0, 0.00, 0.00),
(70, 12, 1, '½\"', 0, 0.00, 0.00),
(71, 12, 1, '2\"', 0, 0.00, 0.00),
(72, 12, 1, '¾\"', 0, 0.00, 0.00),
(73, 14, 1, '1 ½\"', 0, 0.00, 0.00),
(74, 14, 1, '1 ¼\"', 0, 0.00, 0.00),
(75, 14, 1, '1\"', 0, 0.00, 0.00),
(76, 14, 1, '½\"', 0, 0.00, 0.00),
(77, 14, 1, '2\"', 0, 0.00, 0.00),
(78, 14, 1, '¾\"', 0, 0.00, 0.00),
(79, 15, 1, '1 ½\"', 0, 0.00, 0.00),
(80, 15, 1, '1 ¼\"', 0, 0.00, 0.00),
(81, 15, 1, '1\"', 0, 0.00, 0.00),
(82, 15, 1, '½\"', 0, 0.00, 0.00),
(83, 15, 1, '2\"', 0, 0.00, 0.00),
(84, 15, 1, '¾\"', 0, 0.00, 0.00),
(85, 16, 1, '1 ½\"', 0, 0.00, 0.00),
(86, 16, 1, '1 ¼\"', 0, 0.00, 0.00),
(87, 16, 1, '1\"', 0, 0.00, 0.00),
(88, 16, 1, '½\"', 0, 0.00, 0.00),
(89, 16, 1, '2\"', 0, 0.00, 0.00),
(90, 16, 1, '¾\"', 0, 0.00, 0.00);

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
-- Indexes for table `medidas_categoria`
--
ALTER TABLE `medidas_categoria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_categoria_medida` (`categoria_id`,`medida`),
  ADD KEY `idx_categoria` (`categoria_id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indexes for table `productos_medidas`
--
ALTER TABLE `productos_medidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_medida_por_producto` (`producto_id`,`medida`),
  ADD KEY `idx_producto_id` (`producto_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `medidas_categoria`
--
ALTER TABLE `medidas_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `productos_medidas`
--
ALTER TABLE `productos_medidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `productos_medidas`
--
ALTER TABLE `productos_medidas`
  ADD CONSTRAINT `productos_medidas_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
