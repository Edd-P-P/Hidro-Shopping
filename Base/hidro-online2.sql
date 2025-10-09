-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2025 at 05:32 PM
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
(3, 'Hidráulica inglés C-40 PVC', 'ingles-c40-pvc', NULL, NULL, 1, '2025-10-07 16:55:26'),
(4, 'Cementos', 'cementos', NULL, NULL, 1, '2025-10-07 16:26:08'),
(5, 'Hidráulica inglés C-80 PVC', 'ingles-c80-pvc', NULL, NULL, 1, '2025-10-07 16:55:26'),
(6, 'Hidráulica inglés campana CPVC', 'campana-CPVC', NULL, NULL, 1, '2025-10-07 16:58:27'),
(7, 'Tuberías especiales', 'tuberia-especial', NULL, NULL, 1, '2025-10-07 16:58:27'),
(8, 'Tubería galvanizada', 'tuberia-galvanizada', NULL, NULL, 1, '2025-09-23 17:46:58'),
(9, 'Toma domiciliaria', 'toma-domi', NULL, NULL, 1, '2025-09-23 17:46:58'),
(10, 'Medidores y valvulas', 'medidores-y-valvulas', NULL, NULL, 1, '2025-09-23 17:46:58'),
(11, 'Conexiones fierro fundido', 'Conexiones fierro fundido', NULL, NULL, 1, '2025-10-07 17:02:15'),
(12, 'Alacantarillado métrico campana', 'métrico-campana', NULL, NULL, 1, '2025-10-07 17:02:15'),
(13, 'Tubería polietileno corrugado', 'polietileno-corrugado', NULL, NULL, 1, '2025-10-07 17:08:28'),
(14, 'Linea Sanitaria', 'linea-sanitaria', NULL, NULL, 1, '2025-09-23 17:46:58'),
(15, 'Productos destacados', 'Featured', 'Productos que van a aparecer en index', NULL, 1, '2025-09-23 20:23:01');

-- --------------------------------------------------------

--
-- Table structure for table `medidas_categoria`
--

CREATE TABLE `medidas_categoria` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `medida_id` varchar(100) NOT NULL,
  `orden` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medidas_categoria`
--

INSERT INTO `medidas_categoria` (`id`, `categoria_id`, `medida_id`, `orden`) VALUES
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
  `descuento` tinyint(3) DEFAULT NULL,
  `Recomendaciones_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `tabla_med`, `especificaciones`, `precio`, `stock`, `imagen`, `categoria_id`, `requiere_medidas`, `activo`, `created_at`, `updated_at`, `descuento`, `Recomendaciones_id`) VALUES
(1, 'Adaptador Hembra CPVC', '<strong>ADAPTADOR HEMBRA CPVC</strong><br>Conector que permite unir tuberías de CPVC con accesorios de rosca macho. Resistente a altas temperaturas y presión, ideal para instalaciones hidráulicas y de agua caliente.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 8.50, 50, '', 1, 1, 1, '2025-09-23 19:45:31', '2025-09-29 17:59:55', 0, NULL),
(2, 'Adaptador Hembra Inserción Metálica CPVC', '<strong>ADAPTADOR HEMBRA INSERCIÓN METÁLICA CPVC</strong><br>Pieza que combina la resistencia del CPVC con una inserción metálica para conexiones roscadas. Aporta durabilidad y evita desgaste en uniones frecuentes.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Inserción metálica <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 12.00, 50, '', 1, 1, 1, '2025-09-23 20:16:40', '2025-09-29 16:04:36', NULL, NULL),
(3, 'Adaptador Macho Inserción Metálica CPVC', '<strong>ADAPTADOR MACHO INSERCIÓN METÁLICA CPVC</strong><br>Adaptador de CPVC con inserción metálica roscada macho. Diseñado para conexiones firmes y seguras en instalaciones que requieren resistencia adicional.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 12.00, 50, '', 1, 1, 1, '2025-09-23 20:17:29', '2025-09-29 16:04:36', NULL, NULL),
(4, 'Adaptador Macho CPVC', '<strong>ADAPTADOR MACHO CPVC</strong><br>Accesorio que facilita la unión de tuberías CPVC con conexiones de rosca hembra. Ligero, resistente a la corrosión y fácil de instalar.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 7.50, 50, '', 1, 1, 1, '2025-09-23 20:20:19', '2025-09-29 16:04:36', NULL, NULL),
(5, 'Tuerca Unión CPVC', '<strong>TUERCA UNIÓN CPVC</strong><br>Pieza desmontable que permite unir o separar secciones de tubería sin cortar. Ideal para mantenimiento en sistemas de agua y reparaciones rápidas.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 6.00, 50, '', 1, 1, 1, '2025-09-23 20:20:19', '2025-09-29 16:04:36', NULL, NULL),
(6, 'Codo 90° CPVC', '<strong>CODO 90° CPVC</strong><br>Accesorio para cambiar la dirección del flujo a 90 grados en tuberías de CPVC. Se utiliza en instalaciones hidráulicas y de agua caliente.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 9.00, 50, '', 1, 1, 1, '2025-09-23 20:20:19', '2025-09-29 16:04:36', NULL, NULL),
(7, 'Codo 45° CPVC', '<strong>CODO 45° CPVC</strong><br>Codo que permite desviar el flujo a 45 grados. Reduce pérdidas de presión y se usa en trayectos donde se requiere una ligera desviación.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 8.50, 50, '', 1, 1, 1, '2025-09-23 20:20:19', '2025-09-29 16:04:36', NULL, NULL),
(8, 'Cople CPVC', '<strong>COPLE CPVC</strong><br>Conector recto para unir dos tramos de tubería CPVC del mismo diámetro. Sencillo y práctico para alargar o reparar tuberías.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 5.50, 50, '', 1, 1, 1, '2025-09-23 20:20:19', '2025-09-29 16:04:36', NULL, NULL),
(9, 'Reducción Cople CPVC', '<strong>REDUCCIÓN COPLE CPVC</strong><br>Pieza que une tuberías de diferente diámetro, permitiendo la transición entre medidas. Resistente a la presión y a la corrosión.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 7.00, 50, '', 1, 1, 1, '2025-09-23 20:20:19', '2025-09-29 16:04:36', NULL, NULL),
(10, 'TEE CPVC', '<strong>TEE CPVC</strong><br>Accesorio en forma de “T” que permite ramificar el flujo en tres direcciones. Ideal para distribuir agua en diferentes puntos de la instalación.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 15.00, 50, '', 1, 1, 1, '2025-09-23 20:59:54', '2025-09-29 16:04:36', NULL, NULL),
(11, 'TEE REDUCIDA CPVC', '<strong>TEE REDUCIDA CPVC</strong><br>Tee que permite la unión de tres tuberías, con una salida de diámetro menor. Perfecta para derivaciones que requieren reducción de flujo.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', '', 16.50, 50, '', 1, 1, 1, '2025-09-23 20:59:54', '2025-09-29 16:05:15', NULL, NULL),
(12, 'REDUCCIÓN BUSHING CPVC', '<strong>REDUCCIÓN BUSHING CPVC</strong><br>Accesorio reductor que facilita la conexión entre tuberías de diferentes diámetros. Compacto, durable y de fácil instalación.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 9.00, 50, '', 1, 1, 1, '2025-09-23 20:59:54', '2025-09-29 16:05:58', NULL, NULL),
(13, 'PINZAS PARA CORTE', '<strong>PINZAS PARA CORTE</strong><br>Herramienta diseñada para cortar tubería de CPVC de manera precisa y sin rebabas. Facilita el trabajo en instalaciones limpias y seguras.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div>\r\n<table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 35.00, 1, '', 1, 0, 1, '2025-09-23 20:59:54', '2025-09-30 16:00:27', 20, NULL),
(14, 'VÁLVULA BOLA CEMENTAR CPVC', '<strong>VÁLVULA BOLA CEMENTAR CPVC</strong><br>Válvula de bola fabricada en CPVC que se instala con cemento. Permite abrir o cerrar el paso del agua de forma rápida y confiable.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 22.00, 50, '', 1, 1, 1, '2025-09-23 20:59:54', '2025-09-29 16:05:59', NULL, NULL),
(15, 'TAPA CPVC', '<strong>TAPA CPVC</strong><br>Pieza utilizada para cerrar extremos de tubería. Se emplea en pruebas de presión o para dejar derivaciones listas para futuras conexiones.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 6.50, 50, '', 1, 1, 1, '2025-09-23 20:59:54', '2025-09-29 16:05:59', NULL, NULL),
(16, 'CODO PIPA ROSCADO OREJA CPVC', '<strong>CODO PIPA ROSCADO OREJA CPVC</strong><br>Accesorio en codo con base de fijación y salida roscada. Diseñado para instalaciones donde se requiere sujeción firme a muros o superficies.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA CPVC</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr></tbody></table><div class=\"info-extra\">Tubo en tramo de 6.10 m</div></div>', 'Material CPVC <br> Rosca NTP Estándar <br> Soporta hasta 80°C <br> Estandar ASTMD2846 <br> RD11', 18.00, 50, '', 1, 1, 1, '2025-09-23 20:59:54', '2025-09-29 16:05:59', NULL, NULL),
(53, 'ARES NARANJA/AMARILLO CPVC CTS', NULL, '<table><caption>Rendimiento por bote de 500 ML</caption><thead><tr><th>Diametro mm</th><th>Diametro pulgadas</th><th>Uniones</th></tr></thead><tbody><tr><td>13</td><td>1/2</td><td>200</td></tr><tr><td>19</td><td>3/4</td><td>125</td></tr><tr><td>25</td><td>1</td><td>110</td></tr><tr><td>32</td><td>1¼</td><td>75</td></tr><tr><td>38</td><td>1½</td><td>65</td></tr><tr><td>50</td><td>2</td><td>50</td></tr><tr><td>60</td><td>2½</td><td>40</td></tr><tr><td>75</td><td>3</td><td>35</td></tr><tr><td>100</td><td>4</td><td>20</td></tr><tr><td>150</td><td>6</td><td>10</td></tr></tbody></table>', 'Adhesivo especializado para sistemas CPVC CTS. Ofrece una unión resistente y duradera en instalaciones de agua caliente y fría. Ideal para uso doméstico e industrial gracias a su alta resistencia térmica y química.', 0.00, 0, NULL, 4, 1, 1, '2025-10-07 17:59:50', '2025-10-07 19:59:10', NULL, NULL),
(54, 'ARES AZUL TODA PRESIÓN', NULL, '<table><caption>Rendimiento por bote de 500 ML</caption><thead><tr><th>Diametro mm</th><th>Diametro pulgadas</th><th>Uniones</th></tr></thead><tbody><tr><td>13</td><td>1/2</td><td>200</td></tr><tr><td>19</td><td>3/4</td><td>125</td></tr><tr><td>25</td><td>1</td><td>110</td></tr><tr><td>32</td><td>1¼</td><td>75</td></tr><tr><td>38</td><td>1½</td><td>65</td></tr><tr><td>50</td><td>2</td><td>50</td></tr><tr><td>60</td><td>2½</td><td>40</td></tr><tr><td>75</td><td>3</td><td>35</td></tr><tr><td>100</td><td>4</td><td>20</td></tr><tr><td>150</td><td>6</td><td>10</td></tr></tbody></table>', 'Cemento solvente para tuberías de PVC de toda presión. Proporciona un sellado hermético y seguro, evitando fugas. Recomendado para instalaciones hidráulicas y sanitarias de alta exigencia.', 0.00, 0, NULL, 4, 1, 1, '2025-10-07 17:59:50', '2025-10-07 19:59:10', NULL, NULL),
(55, 'ARES DORADO BAJA PRESIÓN', NULL, '<table><caption>Rendimiento por bote de 500 ML</caption><thead><tr><th>Diametro mm</th><th>Diametro pulgadas</th><th>Uniones</th></tr></thead><tbody><tr><td>13</td><td>1/2</td><td>200</td></tr><tr><td>19</td><td>3/4</td><td>125</td></tr><tr><td>25</td><td>1</td><td>110</td></tr><tr><td>32</td><td>1¼</td><td>75</td></tr><tr><td>38</td><td>1½</td><td>65</td></tr><tr><td>50</td><td>2</td><td>50</td></tr><tr><td>60</td><td>2½</td><td>40</td></tr><tr><td>75</td><td>3</td><td>35</td></tr><tr><td>100</td><td>4</td><td>20</td></tr><tr><td>150</td><td>6</td><td>10</td></tr></tbody></table>', 'Adhesivo diseñado para sistemas de PVC de baja presión. Facilita uniones firmes y confiables en instalaciones domésticas o de riego. Su fórmula garantiza una aplicación rápida y uniforme.', 0.00, 0, NULL, 4, 1, 1, '2025-10-07 17:59:50', '2025-10-07 19:59:10', NULL, NULL),
(56, 'ARES VERDE TODA PRESIÓN', NULL, '<table><caption>Rendimiento por bote de 500 ML</caption><thead><tr><th>Diametro mm</th><th>Diametro pulgadas</th><th>Uniones</th></tr></thead><tbody><tr><td>13</td><td>1/2</td><td>200</td></tr><tr><td>19</td><td>3/4</td><td>125</td></tr><tr><td>25</td><td>1</td><td>110</td></tr><tr><td>32</td><td>1¼</td><td>75</td></tr><tr><td>38</td><td>1½</td><td>65</td></tr><tr><td>50</td><td>2</td><td>50</td></tr><tr><td>60</td><td>2½</td><td>40</td></tr><tr><td>75</td><td>3</td><td>35</td></tr><tr><td>100</td><td>4</td><td>20</td></tr><tr><td>150</td><td>6</td><td>10</td></tr></tbody></table>', 'Cemento solvente para tuberías y conexiones de PVC de toda presión. Asegura una unión fuerte y duradera, incluso en condiciones exigentes. Ideal para sistemas hidráulicos y sanitarios.', 0.00, 0, NULL, 4, 1, 1, '2025-10-07 17:59:50', '2025-10-07 19:59:10', NULL, NULL),
(57, 'LUBRICANTE ARES 500 ml', NULL, '<table><caption>Rendimiento por bote de 500 ML</caption><thead><tr><th>Diametro mm</th><th>Diametro pulgadas</th><th>Uniones</th></tr></thead><tbody><tr><td>13</td><td>1/2</td><td>200</td></tr><tr><td>19</td><td>3/4</td><td>125</td></tr><tr><td>25</td><td>1</td><td>110</td></tr><tr><td>32</td><td>1¼</td><td>75</td></tr><tr><td>38</td><td>1½</td><td>65</td></tr><tr><td>50</td><td>2</td><td>50</td></tr><tr><td>60</td><td>2½</td><td>40</td></tr><tr><td>75</td><td>3</td><td>35</td></tr><tr><td>100</td><td>4</td><td>20</td></tr><tr><td>150</td><td>6</td><td>10</td></tr></tbody></table>', 'Lubricante especializado para facilitar el ensamble de tuberías y conexiones hidráulicas. Reduce la fricción y protege las juntas, garantizando un montaje rápido y seguro. Presentación de 500 ml.', 0.00, 0, NULL, 4, 1, 1, '2025-10-07 17:59:50', '2025-10-07 19:59:10', NULL, NULL),
(58, 'LIMPIADOR ARES 500 ml', NULL, '<table><caption>Rendimiento por bote de 500 ML</caption><thead><tr><th>Diametro mm</th><th>Diametro pulgadas</th><th>Uniones</th></tr></thead><tbody><tr><td>13</td><td>1/2</td><td>200</td></tr><tr><td>19</td><td>3/4</td><td>125</td></tr><tr><td>25</td><td>1</td><td>110</td></tr><tr><td>32</td><td>1¼</td><td>75</td></tr><tr><td>38</td><td>1½</td><td>65</td></tr><tr><td>50</td><td>2</td><td>50</td></tr><tr><td>60</td><td>2½</td><td>40</td></tr><tr><td>75</td><td>3</td><td>35</td></tr><tr><td>100</td><td>4</td><td>20</td></tr><tr><td>150</td><td>6</td><td>10</td></tr></tbody></table>', 'Solución limpiadora para preparar superficies de PVC antes del cementado. Elimina grasa, polvo y residuos, mejorando la adherencia del adhesivo. Ideal para lograr uniones limpias y profesionales.', 0.00, 0, NULL, 4, 1, 1, '2025-10-07 17:59:50', '2025-10-07 19:59:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `productos_medidas`
--

CREATE TABLE `productos_medidas` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL DEFAULT 0,
  `medida_id` varchar(100) NOT NULL,
  `stock_m` int(11) DEFAULT 0,
  `precio_m` decimal(10,2) NOT NULL,
  `descuento_m` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productos_medidas`
--

INSERT INTO `productos_medidas` (`id`, `producto_id`, `categoria_id`, `medida_id`, `stock_m`, `precio_m`, `descuento_m`) VALUES
(1, 1, 1, '½\"', 5, 5.00, 0.00),
(2, 1, 1, '¾\"', 8, 9.00, 30.00),
(3, 1, 1, '1\"', 22, 15.00, 0.00),
(4, 1, 1, '2\"', 10, 17.00, 0.00),
(5, 1, 1, '1 ½\"', 16, 25.00, 10.00),
(6, 1, 1, '1 ¼\"', 0, 22.00, 17.00),
(7, 2, 1, '½\"', 0, 0.00, 0.00),
(8, 2, 1, '¾\"', 0, 0.00, 0.00),
(9, 2, 1, '1\"', 0, 0.00, 0.00),
(10, 2, 1, '2\"', 0, 0.00, 0.00),
(11, 2, 1, '1 ¼\"', 0, 0.00, 0.00),
(12, 2, 1, '1 ½\"', 0, 0.00, 0.00),
(13, 3, 1, '½\"', 0, 0.00, 0.00),
(14, 3, 1, '¾\"', 0, 0.00, 0.00),
(15, 3, 1, '1\"', 0, 0.00, 0.00),
(16, 3, 1, '2\"', 0, 0.00, 0.00),
(17, 3, 1, '1 ½\"', 0, 0.00, 0.00),
(18, 3, 1, '1 ¼\"', 0, 0.00, 0.00),
(19, 4, 1, '½\"', 0, 0.00, 0.00),
(20, 4, 1, '¾\"', 0, 0.00, 0.00),
(21, 4, 1, '1\"', 0, 0.00, 0.00),
(22, 4, 1, '2\"', 0, 0.00, 0.00),
(23, 4, 1, '1 ¼\"', 0, 0.00, 0.00),
(24, 4, 1, '1 ½\"', 0, 0.00, 0.00),
(25, 5, 1, '½\"', 0, 0.00, 0.00),
(26, 5, 1, '¾\"', 0, 0.00, 0.00),
(27, 5, 1, '1\"', 0, 0.00, 0.00),
(28, 5, 1, '2\"', 0, 0.00, 0.00),
(29, 5, 1, '1 ½\"', 0, 0.00, 0.00),
(30, 5, 1, '1 ¼\"', 0, 0.00, 0.00),
(31, 6, 1, '½\"', 0, 0.00, 0.00),
(32, 6, 1, '¾\"', 0, 0.00, 0.00),
(33, 6, 1, '1\"', 0, 0.00, 0.00),
(34, 6, 1, '2\"', 0, 0.00, 0.00),
(35, 6, 1, '1 ½\"', 0, 0.00, 0.00),
(36, 6, 1, '1 ¼\"', 0, 0.00, 0.00),
(37, 7, 1, '½\"', 0, 0.00, 0.00),
(38, 7, 1, '¾\"', 0, 0.00, 0.00),
(39, 7, 1, '1\"', 0, 0.00, 0.00),
(40, 7, 1, '2\"', 0, 0.00, 0.00),
(41, 7, 1, '1 ¼\"', 0, 0.00, 0.00),
(42, 7, 1, '1 ½\"', 0, 0.00, 0.00),
(43, 8, 1, '½\"', 0, 0.00, 0.00),
(44, 8, 1, '¾\"', 0, 0.00, 0.00),
(45, 8, 1, '1\"', 0, 0.00, 0.00),
(46, 8, 1, '2\"', 0, 0.00, 0.00),
(47, 8, 1, '1 ½\"', 0, 0.00, 0.00),
(48, 8, 1, '1 ¼\"', 0, 0.00, 0.00),
(49, 9, 1, '½\"', 0, 0.00, 0.00),
(50, 9, 1, '¾\"', 0, 0.00, 0.00),
(51, 9, 1, '1\"', 0, 0.00, 0.00),
(52, 9, 1, '2\"', 0, 0.00, 0.00),
(53, 9, 1, '1 ½\"', 0, 0.00, 0.00),
(54, 9, 1, '1 ¼\"', 0, 0.00, 0.00),
(55, 10, 1, '½\"', 0, 0.00, 0.00),
(56, 10, 1, '¾\"', 0, 0.00, 0.00),
(57, 10, 1, '1\"', 0, 0.00, 0.00),
(58, 10, 1, '2\"', 0, 0.00, 0.00),
(59, 10, 1, '1 ¼\"', 0, 0.00, 0.00),
(60, 10, 1, '1 ½\"', 0, 0.00, 0.00),
(61, 11, 1, '½\"', 0, 0.00, 0.00),
(62, 11, 1, '¾\"', 0, 0.00, 0.00),
(63, 11, 1, '1\"', 0, 0.00, 0.00),
(64, 11, 1, '2\"', 0, 0.00, 0.00),
(65, 11, 1, '1 ½\"', 0, 0.00, 0.00),
(66, 11, 1, '1 ¼\"', 0, 0.00, 0.00),
(67, 12, 1, '½\"', 0, 0.00, 0.00),
(68, 12, 1, '¾\"', 0, 0.00, 0.00),
(69, 12, 1, '1\"', 0, 0.00, 0.00),
(70, 12, 1, '2\"', 0, 0.00, 0.00),
(71, 12, 1, '1 ¼\"', 0, 0.00, 0.00),
(72, 12, 1, '1 ½\"', 0, 0.00, 0.00),
(73, 14, 1, '½\"', 0, 0.00, 0.00),
(74, 14, 1, '¾\"', 0, 0.00, 0.00),
(75, 14, 1, '1\"', 0, 0.00, 0.00),
(76, 14, 1, '2\"', 0, 0.00, 0.00),
(77, 14, 1, '1 ½\"', 0, 0.00, 0.00),
(78, 14, 1, '1 ¼\"', 0, 0.00, 0.00),
(79, 15, 1, '½\"', 0, 0.00, 0.00),
(80, 15, 1, '¾\"', 0, 0.00, 0.00),
(81, 15, 1, '1\"', 0, 0.00, 0.00),
(82, 15, 1, '2\"', 0, 0.00, 0.00),
(83, 15, 1, '1 ½\"', 0, 0.00, 0.00),
(84, 15, 1, '1 ¼\"', 0, 0.00, 0.00),
(85, 16, 1, '½\"', 0, 0.00, 0.00),
(86, 16, 1, '¾\"', 0, 0.00, 0.00),
(87, 16, 1, '1\"', 0, 0.00, 0.00),
(88, 16, 1, '2\"', 0, 0.00, 0.00),
(89, 16, 1, '1 ¼\"', 0, 0.00, 0.00),
(90, 16, 1, '1 ½\"', 0, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `recomendaciones_categoria`
--

CREATE TABLE `recomendaciones_categoria` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `orden` int(11) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rec_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `recomendaciones_categoria`
--

INSERT INTO `recomendaciones_categoria` (`id`, `categoria_id`, `producto_id`, `orden`, `activo`, `created_at`, `rec_id`) VALUES
(1, 1, 8, 1, 1, '2025-10-07 20:43:06', 1),
(2, 1, 13, 2, 1, '2025-10-07 20:43:06', 1),
(3, 1, 54, 3, 1, '2025-10-07 20:43:06', 1);

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
  ADD UNIQUE KEY `uk_categoria_medida` (`categoria_id`,`medida_id`),
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
  ADD UNIQUE KEY `unique_medida_por_producto` (`producto_id`,`medida_id`),
  ADD KEY `idx_producto_id` (`producto_id`);

--
-- Indexes for table `recomendaciones_categoria`
--
ALTER TABLE `recomendaciones_categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `medidas_categoria`
--
ALTER TABLE `medidas_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `productos_medidas`
--
ALTER TABLE `productos_medidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `recomendaciones_categoria`
--
ALTER TABLE `recomendaciones_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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

--
-- Constraints for table `recomendaciones_categoria`
--
ALTER TABLE `recomendaciones_categoria`
  ADD CONSTRAINT `recomendaciones_categoria_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
