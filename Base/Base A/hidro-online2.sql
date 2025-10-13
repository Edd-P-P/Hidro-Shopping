-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-10-2025 a las 21:37:12
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
-- Base de datos: `hidro-online2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
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
-- Volcado de datos para la tabla `categorias`
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
-- Estructura de tabla para la tabla `medidas_categoria`
--

CREATE TABLE `medidas_categoria` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `medida_id` varchar(100) NOT NULL,
  `orden` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `medidas_categoria`
--

INSERT INTO `medidas_categoria` (`id`, `categoria_id`, `medida_id`, `orden`) VALUES
(1, 1, '½\"', 1),
(2, 1, '¾\"', 2),
(3, 1, '1\"', 3),
(4, 1, '1 ¼\"', 4),
(5, 1, '1 ½\"', 5),
(6, 1, '2\"', 6),
(43, 8, '½\"', 43),
(44, 8, '¾\"', 44),
(45, 8, '1\"', 45),
(46, 8, '1 ¼\"', 46),
(47, 8, '1 ½\"', 47),
(48, 8, '2\"', 48),
(49, 8, '2 ½\"', 49),
(50, 8, '3\"', 50),
(51, 8, '4\"', 51);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
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
-- Volcado de datos para la tabla `productos`
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
(58, 'LIMPIADOR ARES 500 ml', NULL, '<table><caption>Rendimiento por bote de 500 ML</caption><thead><tr><th>Diametro mm</th><th>Diametro pulgadas</th><th>Uniones</th></tr></thead><tbody><tr><td>13</td><td>1/2</td><td>200</td></tr><tr><td>19</td><td>3/4</td><td>125</td></tr><tr><td>25</td><td>1</td><td>110</td></tr><tr><td>32</td><td>1¼</td><td>75</td></tr><tr><td>38</td><td>1½</td><td>65</td></tr><tr><td>50</td><td>2</td><td>50</td></tr><tr><td>60</td><td>2½</td><td>40</td></tr><tr><td>75</td><td>3</td><td>35</td></tr><tr><td>100</td><td>4</td><td>20</td></tr><tr><td>150</td><td>6</td><td>10</td></tr></tbody></table>', 'Solución limpiadora para preparar superficies de PVC antes del cementado. Elimina grasa, polvo y residuos, mejorando la adherencia del adhesivo. Ideal para lograr uniones limpias y profesionales.', 0.00, 0, NULL, 4, 1, 1, '2025-10-07 17:59:50', '2025-10-07 19:59:10', NULL, NULL),
(86, 'CODO GALVANIZADO DE 90°', '<strong>Codo galvanizado 90°</strong><br>Cambio de dirección en sistemas de conducción', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Acabado: Zinc por inmersión <br> Radio: Corto <br>  Presión máx: 150 PSI <br> Norma: ASTM A53', 0.00, 50, '', 8, 1, 1, '0000-00-00 00:00:00', '2025-10-09 17:53:48', 0, NULL),
(87, 'CODO GALVANIZADO DE 45°', '<strong>Codo galvanizado 45°</strong><br>Desviación suave en redes hidráulicas', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Angulación: 45° exactos <br> Junta: Metal-metal <br> Aplicación: Reducción de turbulencia', 0.00, 50, '', 8, 1, 1, '0000-00-00 00:00:00', '2025-10-09 17:53:59', 0, NULL),
(88, 'TEE GALVANIZADA ROSCADA', '<strong>Tee roscada galvanizada</strong><br>Derivación en sistemas de distribución', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Configuración: Igual o reducida <br>  Rosca: Cónica NPT <br>  Estanqueidad: Sin soldadura', 0.00, 50, '', 8, 1, 1, '0000-00-00 00:00:00', '2025-10-09 17:54:14', 0, NULL),
(89, 'TAPON MACHO ROSCADO', '<strong>Tapón macho roscado</strong><br>Cierre terminal para inspección', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Tipo: Externo <br>  Sellado: Por contacto metal <br>  Reutilizable: Sí', 0.00, 50, '', 8, 1, 1, '0000-00-00 00:00:00', '2025-10-09 18:02:05', 0, NULL),
(90, 'TAPA ROSCADA', '<strong>Tapa roscada galvanizada</strong><br>Cierre interno para accesos', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Tipo: Interno | Profundidad: Standard <br>  Acabado: Liso galvanizado', 0.00, 50, '', 8, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, NULL),
(91, 'TUERCA UNION ROSCADA', '<strong>Tapa de unión roscada</strong><br>Punto de acceso desmontable', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Sistema: Brida integrada <br>  Mantenimiento: Acceso rápido <br>  Junta: Mecánica', 0.00, 50, '', 8, 1, 1, '0000-00-00 00:00:00', '2025-10-09 18:02:23', 0, NULL),
(92, 'YEE ROSCADA', '<strong>Yee roscada galvanizada</strong><br>Derivación lateral en sistemas', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Geometría: 45° lateral <br>  Flujo: Minimiza pérdida <br>  Aplicación: Drenajes', 0.00, 50, '', 8, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, NULL),
(93, 'CRUZ ROSCADA', '<strong>Cruz roscada galvanizada</strong><br>Intersección completa en redes', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Configuración: 4 vías <br>  Estructura: Refuerzo integrado <br>  Uso: Distribución múltiple', 0.00, 50, '', 8, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, NULL),
(94, 'COPLE HM GALVANIZADO ROSCADO C-40', '<strong>Cople roscado galvanizado</strong><br>Extensión lineal en instalaciones', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Función: Empalme recto <br>  Longitud: Standard <br>  Instalación: Sin preparación', 0.00, 50, '', 8, 1, 1, '0000-00-00 00:00:00', '2025-10-09 17:52:53', 0, NULL),
(95, 'COPLE LISO GALVANIZADO ROSCADO C-40', '<strong>Cople roscado galvanizado</strong><br>Extensión lineal en instalaciones', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Función: Empalme recto <br>  Longitud: Standard <br>  Instalación: Sin preparación', 0.00, 50, '', 8, 1, 1, '0000-00-00 00:00:00', '2025-10-09 17:52:53', 0, NULL),
(96, 'REDUCCIONES BUSHING', 'Fabricada en acero con un acabado brushing galvanizado que ofrece una excelente resistencia a la corrosión y un aspecto profesional. Cumple con el estándar C-40 para garantizar la seguridad y durabilidad de tu instalación.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Norma:C-40<br>\r\nMaterial:Acabado Galvanizado Brushing <br> \r\nUso Interior y Exterior (gracias a su protección galvanizada)', 0.00, 50, '', 8, 1, 1, '2025-10-13 15:46:43', '2025-10-13 18:59:09', 0, NULL),
(97, 'REDUCCIONES CAMPANA', 'Conexión especializada para unir tubos conduit de diferentes diámetros de forma rápida y segura. Fabricada en acero con galvanizado anti-corrosivo.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Material: Acero de Alta Resistencia<br>\r\nUso Recomendado	Interior y Exterior', 0.00, 52, NULL, 8, 1, 1, '2025-10-13 15:50:35', '2025-10-13 17:43:18', 0, NULL),
(98, 'Niple Galvanizado 1/2\"', '<strong>NIPLE GALVANIZADO 1/2\"</strong><br>Conector roscado en ambos extremos, fabricado en acero galvanizado para máxima resistencia a la corrosión. Ideal para uniones en tuberías de agua, gas y sistemas de riego.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Material: Acero Galvanizado <br> Rosca: NPT Estándar <br> Presión Máxima: 150 PSI <br> Temperatura: -20°C a 120°C <br> Norma: ASTM A153', 5.50, 100, '', 8, 1, 1, '2025-10-13 17:37:41', '2025-10-13 18:37:38', 0, NULL),
(99, 'Niple Galvanizado 3/4\"', '<strong>NIPLE GALVANIZADO 3/4\"</strong><br>Conector de acero galvanizado de uso universal, proporciona durabilidad y resistencia en instalaciones hidráulicas y neumáticas. Excelente para conexiones en sistemas de presión.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Material: Acero Galvanizado <br> Rosca: NPT Estándar <br> Presión Máxima: 150 PSI <br> Aplicación: Agua, Aire, Gas <br> Norma: ASTM A153', 7.80, 85, '', 8, 1, 1, '2025-10-13 17:37:41', '2025-10-13 18:37:41', 0, NULL),
(100, 'Niple Galvanizado 1\"', '<strong>NIPLE GALVANIZADO 1\"</strong><br>Conector robusto para tuberías de mayor diámetro, fabricado en acero de alta resistencia con galvanizado por inmersión en caliente. Perfecto para sistemas industriales.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Material: Acero Galvanizado <br> Rosca: NPT Estándar <br> Presión Máxima: 150 PSI <br> Galvanizado: Inmersión en Caliente <br> Norma: ASTM A153', 12.50, 70, '', 8, 1, 1, '2025-10-13 17:37:41', '2025-10-13 18:37:49', 0, NULL),
(101, 'Niple Galvanizado 1 1/4\"', '<strong>NIPLE GALVANIZADO 1 1/4\"</strong><br>Conector para aplicaciones industriales y comerciales, diseñado para soportar altas presiones y condiciones corrosivas. Ideal para plantas de proceso.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Material: Acero Galvanizado <br> Rosca: NPT Estándar <br> Presión Máxima: 150 PSI <br> Uso: Industrial/Comercial <br> Norma: ASTM A153', 18.20, 60, '', 8, 1, 1, '2025-10-13 17:37:41', '2025-10-13 18:37:53', 0, NULL),
(102, 'Niple Galvanizado 1 1/2\"', '<strong>NIPLE GALVANIZADO 1 1/2\"</strong><br>Conector de alta resistencia para sistemas de bombeo y distribución de fluidos. Galvanizado pesado para protección contra corrosión en exteriores.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Material: Acero Galvanizado <br> Rosca: NPT Estándar <br> Presión Máxima: 150 PSI <br> Aplicación: Bombeo y Distribución <br> Norma: ASTM A153', 22.80, 55, '', 8, 1, 1, '2025-10-13 17:37:41', '2025-10-13 18:37:57', 0, NULL),
(103, 'Niple Galvanizado 2\"', '<strong>NIPLE GALVANIZADO 2\"</strong><br>Conector para sistemas de alta capacidad, utilizado en redes principales de agua y sistemas de riego agrícola. Máxima durabilidad garantizada.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Material: Acero Galvanizado <br> Rosca: NPT Estándar <br> Presión Máxima: 150 PSI <br> Uso: Redes Principales <br> Norma: ASTM A153', 35.50, 45, '', 8, 1, 1, '2025-10-13 17:37:41', '2025-10-13 18:38:01', 0, NULL),
(104, 'Niple Galvanizado 2 1/2\"', '<strong>NIPLE GALVANIZADO 2 1/2\"</strong><br>Conector industrial para aplicaciones de gran caudal, fabricado bajo estrictos controles de calidad. Resistente a impactos y vibraciones.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Material: Acero Galvanizado <br> Rosca: NPT Estándar <br> Presión Máxima: 125 PSI <br> Aplicación: Industrial Pesado <br> Norma: ASTM A153', 48.90, 35, '', 8, 1, 1, '2025-10-13 17:37:41', '2025-10-13 18:38:07', 0, NULL),
(105, 'Niple Galvanizado 3\"', '<strong>NIPLE GALVANIZADO 3\"</strong><br>Conector de gran diámetro para sistemas municipales e industriales. Diseñado para soportar condiciones extremas y cargas pesadas continuas.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Material: Acero Galvanizado <br> Rosca: NPT Estándar <br> Presión Máxima: 125 PSI <br> Uso: Municipal/Industrial <br> Norma: ASTM A153', 65.20, 30, '', 8, 1, 1, '2025-10-13 17:37:41', '2025-10-13 18:38:10', 0, NULL),
(106, 'Niple Galvanizado 4\"', '<strong>NIPLE GALVANIZADO 4\"</strong><br>Conector de máxima capacidad para sistemas de distribución principal. Fabricado con los más altos estándares de calidad para aplicaciones críticas.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">TUBERÍA GALVANIZADA</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th></tr></thead><tbody><tr><td>½\"</td><td>13</td></tr><tr><td>¾\"</td><td>19</td></tr><tr><td>1\"</td><td>25</td></tr><tr><td>1 ¼\"</td><td>32</td></tr><tr><td>1 ½\"</td><td>38</td></tr><tr><td>2\"</td><td>50</td></tr><tr><td>2 ½\"</td><td>60</td></tr><tr><td>3\"</td><td>75</td></tr><tr><td>4\"</td><td>100</td></tr><tr><td>6\"</td><td>150</td></tr></tbody></table></div>', 'Material: Acero Galvanizado <br> Rosca: NPT Estándar <br> Presión Máxima: 100 PSI <br> Aplicación: Distribución Principal <br> Norma: ASTM A153', 95.80, 25, '', 8, 1, 1, '2025-10-13 17:37:41', '2025-10-13 18:38:13', 0, NULL),
(107, 'Sifón para Manómetro Cola de Cochino', '<strong>SIFÓN PARA MANÓMETRO COLA DE COCHINO</strong><br>Accesorio esencial para proteger manómetros y instrumentos de medición de presión. Diseñado con forma de \"U\" para crear un sello de líquido que evita daños por golpe de ariete, temperaturas extremas y vibraciones.', '<div class=\"tabla-container\"><div class=\"titulo-tabla\">SIFÓN PARA MANÓMETRO</div><table><thead><tr><th>DIÁMETRO pulgadas</th><th>DIÁMETRO milímetros</th><th>MATERIAL</th></tr></thead><tbody><tr><td>½\"</td><td>13</td><td>Acero Inoxidable</td></tr><tr><td>¾\"</td><td>19</td><td>Acero Inoxidable</td></tr><tr><td>1\"</td><td>25</td><td>Acero Inoxidable</td></tr><tr><td>1 ¼\"</td><td>32</td><td>Acero Inoxidable</td></tr><tr><td>1 ½\"</td><td>38</td><td>Acero Inoxidable</td></tr><tr><td>2\"</td><td>50</td><td>Acero Inoxidable</td></tr></tbody></table><div class=\"info-extra\">Forma: Cola de Cochino (U) - Incluye roscas NPT</div></div>', 'Material: Acero Inoxidable 304 <br> Rosca: NPT Estándar <br> Presión Máxima: 300 PSI <br> Temperatura: -40°C a 400°C <br> Aplicación: Protección de Manómetros <br> Norma: ASTM A269', 45.50, 30, '', 8, 0, 1, '2025-10-13 18:46:07', '2025-10-13 18:46:51', 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_medidas`
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
-- Volcado de datos para la tabla `productos_medidas`
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
(90, 16, 1, '1 ½\"', 0, 0.00, 0.00),
(200, 86, 8, '½\"', 5, 5.00, 0.00),
(201, 86, 8, '¾\"', 8, 9.00, 30.00),
(202, 86, 8, '1\"', 22, 15.00, 0.00),
(203, 86, 8, '1 ¼\"', 10, 17.00, 0.00),
(204, 86, 8, '1 ½\"', 16, 25.00, 10.00),
(205, 86, 8, '2\"', 0, 22.00, 17.00),
(206, 86, 8, '2½\"', 0, 0.00, 0.00),
(207, 86, 8, '3\"', 0, 0.00, 0.00),
(208, 86, 8, '4\"', 0, 0.00, 0.00),
(209, 87, 8, '½\"', 5, 5.00, 0.00),
(210, 87, 8, '¾\"', 8, 9.00, 30.00),
(211, 87, 8, '1\"', 22, 15.00, 0.00),
(212, 87, 8, '1 ¼\"', 10, 17.00, 0.00),
(213, 87, 8, '1 ½\"', 16, 25.00, 10.00),
(214, 87, 8, '2\"', 0, 22.00, 17.00),
(215, 87, 8, '2 ½\"', 0, 0.00, 0.00),
(216, 87, 8, '3\"', 0, 0.00, 0.00),
(217, 87, 8, '4\"', 0, 0.00, 0.00),
(218, 88, 8, '½\"', 5, 5.00, 0.00),
(219, 88, 8, '¾\"', 8, 9.00, 30.00),
(220, 88, 8, '1\"', 22, 15.00, 0.00),
(221, 88, 8, '1 ¼\"', 10, 17.00, 0.00),
(223, 88, 8, '1 ½\"', 16, 25.00, 10.00),
(224, 88, 8, '2\"', 0, 22.00, 17.00),
(225, 88, 8, '2 ½\"', 0, 0.00, 0.00),
(226, 88, 8, '3\"', 0, 0.00, 0.00),
(227, 88, 8, '4\"', 0, 0.00, 0.00),
(228, 89, 8, '½\"', 5, 5.00, 0.00),
(229, 89, 8, '¾\"', 8, 9.00, 30.00),
(230, 89, 8, '1\"', 22, 15.00, 0.00),
(231, 89, 8, '1 ¼\"', 10, 17.00, 0.00),
(232, 89, 8, '1 ½\"', 16, 25.00, 10.00),
(233, 89, 8, '2\"', 0, 22.00, 17.00),
(234, 89, 8, '2 ½\"', 0, 0.00, 0.00),
(235, 89, 8, '3\"', 0, 0.00, 0.00),
(236, 89, 8, '4\"', 0, 0.00, 0.00),
(237, 90, 8, '½\"', 5, 5.00, 0.00),
(238, 90, 8, '¾\"', 8, 9.00, 30.00),
(239, 90, 8, '1\"', 22, 15.00, 0.00),
(240, 90, 8, '1 ¼\"', 10, 17.00, 0.00),
(241, 90, 8, '1 ½\"', 16, 25.00, 10.00),
(242, 90, 8, '2\"', 0, 22.00, 17.00),
(243, 90, 8, '2 ½\"', 0, 0.00, 0.00),
(244, 90, 8, '3\"', 0, 0.00, 0.00),
(245, 90, 8, '4\"', 0, 0.00, 0.00),
(246, 91, 8, '½\"', 5, 5.00, 0.00),
(247, 91, 8, '¾\"', 8, 9.00, 30.00),
(248, 91, 8, '1\"', 22, 15.00, 0.00),
(249, 91, 8, '1 ¼\"', 10, 17.00, 0.00),
(250, 91, 8, '1 ½\"', 16, 25.00, 10.00),
(251, 91, 8, '2\"', 0, 22.00, 17.00),
(252, 91, 8, '2 ½\"', 0, 0.00, 0.00),
(253, 91, 8, '3\"', 0, 0.00, 0.00),
(254, 91, 8, '4\"', 0, 0.00, 0.00),
(255, 92, 8, '½\"', 5, 5.00, 0.00),
(256, 92, 8, '¾\"', 8, 9.00, 30.00),
(257, 92, 8, '1\"', 22, 15.00, 0.00),
(258, 92, 8, '1 ¼\"', 10, 17.00, 0.00),
(259, 92, 8, '1 ½\"', 16, 25.00, 10.00),
(260, 92, 8, '2\"', 0, 22.00, 17.00),
(261, 92, 8, '2 ½\"', 0, 0.00, 0.00),
(262, 92, 8, '3\"', 0, 0.00, 0.00),
(263, 92, 8, '4\"', 0, 0.00, 0.00),
(264, 93, 8, '½\"', 5, 5.00, 0.00),
(265, 93, 8, '¾\"', 8, 9.00, 30.00),
(266, 93, 8, '1\"', 22, 15.00, 0.00),
(267, 93, 8, '1 ¼\"', 10, 17.00, 0.00),
(268, 93, 8, '1 ½\"', 16, 25.00, 10.00),
(269, 93, 8, '2\"', 0, 22.00, 17.00),
(270, 93, 8, '2 ½\"', 0, 0.00, 0.00),
(271, 93, 8, '3\"', 0, 0.00, 0.00),
(272, 93, 8, '4\"', 0, 0.00, 0.00),
(273, 94, 8, '½\"', 5, 5.00, 0.00),
(274, 94, 8, '¾\"', 8, 9.00, 30.00),
(275, 94, 8, '1\"', 22, 15.00, 0.00),
(276, 94, 8, '1 ¼\"', 10, 17.00, 0.00),
(277, 94, 8, '1 ½\"', 16, 25.00, 10.00),
(278, 94, 8, '2\"', 0, 22.00, 17.00),
(279, 94, 8, '2 ½\"', 0, 0.00, 0.00),
(280, 94, 8, '3\"', 0, 0.00, 0.00),
(281, 94, 8, '4\"', 0, 0.00, 0.00),
(282, 95, 8, '½\"', 5, 5.00, 0.00),
(283, 95, 8, '¾\"', 8, 9.00, 30.00),
(284, 95, 8, '1\"', 22, 15.00, 0.00),
(285, 95, 8, '1 ¼\"', 10, 17.00, 0.00),
(286, 95, 8, '1 ½\"', 16, 25.00, 10.00),
(288, 95, 8, '2\"', 0, 22.00, 17.00),
(289, 95, 8, '2 ½\"', 0, 0.00, 0.00),
(290, 95, 8, '3\"', 0, 0.00, 0.00),
(291, 95, 8, '4\"', 0, 0.00, 0.00),
(292, 96, 8, '¾\"x½\"', 10, 0.00, 0.00),
(293, 96, 8, '1\"x½\"', 10, 0.00, 0.00),
(294, 96, 8, '1\"x ¾\"\"', 8, 0.00, 0.00),
(295, 96, 8, '1.¼\"x ½\"', 5, 0.00, 0.00),
(327, 96, 8, '1.¼\"x¾\"', 5, 0.00, 0.00),
(328, 96, 8, '1.¼\"x1\"', 5, 0.00, 0.00),
(329, 96, 8, '1.½\"x½\"', 5, 0.00, 0.00),
(330, 96, 8, '1.½\"x ¾\"', 5, 0.00, 0.00),
(331, 96, 8, '1.½\"x 1\"', 5, 0.00, 0.00),
(332, 96, 8, '1.½\"x 1.¼\"', 5, 0.00, 0.00),
(333, 96, 8, '2\"x½\"', 5, 0.00, 0.00),
(334, 96, 8, '2\"x¾\"', 5, 0.00, 0.00),
(335, 96, 8, '2\"x 1\"', 5, 0.00, 0.00),
(336, 96, 8, '2\"x 1.¼\"', 5, 0.00, 0.00),
(337, 96, 8, '2\"x1.½\"', 5, 0.00, 0.00),
(338, 96, 8, '2.½\"x½\"', 5, 0.00, 0.00),
(339, 96, 8, '2.½\"x ¾', 5, 0.00, 0.00),
(340, 96, 8, '2.½\"x 1\"', 5, 0.00, 0.00),
(341, 96, 8, '2.½\"x 1.¼\"', 5, 0.00, 0.00),
(342, 96, 8, '2.½\"x 1.½\"', 5, 0.00, 0.00),
(343, 96, 8, '2.½\"\"x 2\"', 5, 0.00, 0.00),
(344, 96, 8, '3\"x½\"', 5, 0.00, 0.00),
(345, 96, 8, '3\"x¾\"', 5, 0.00, 0.00),
(346, 96, 8, '3\"x1\"', 5, 0.00, 0.00),
(347, 96, 8, '3\"x1.¼\"', 5, 0.00, 0.00),
(348, 96, 8, '3\"x1.½\"', 5, 0.00, 0.00),
(349, 96, 8, '3\"x2\"', 5, 0.00, 0.00),
(350, 96, 8, '3\"x2.½\"', 5, 0.00, 0.00),
(351, 96, 8, '4\"x1\"', 5, 0.00, 0.00),
(352, 96, 8, '4\"x1.¼\"', 5, 0.00, 0.00),
(353, 96, 8, '4\"x1.½\"', 5, 0.00, 0.00),
(354, 96, 8, '4\"x2\"', 5, 0.00, 0.00),
(355, 96, 8, '4\"x2.½\"', 5, 0.00, 0.00),
(356, 96, 8, '4\"x3\"', 5, 0.00, 0.00),
(357, 97, 8, '¾\"x ½\"', 5, 0.00, 0.00),
(358, 97, 8, '1\"x ½\"', 5, 0.00, 0.00),
(359, 97, 8, '1\"x ¼\"', 5, 0.00, 0.00),
(360, 97, 8, '1.¼\"x ½\"', 5, 0.00, 0.00),
(361, 97, 8, '1.¼\"x¾\"', 5, 0.00, 0.00),
(362, 97, 8, '1.¼\"x1\"', 5, 0.00, 0.00),
(363, 97, 8, '1.½\"x½\"', 5, 0.00, 0.00),
(364, 97, 8, '1.½\"x ¾\"', 5, 0.00, 0.00),
(365, 97, 8, '1.½\"x 1\"', 5, 0.00, 0.00),
(366, 97, 8, '1.½\"x 1.¼\"', 5, 0.00, 0.00),
(367, 97, 8, '2\"x½\"', 5, 0.00, 0.00),
(368, 97, 8, '2\"x¾\"', 5, 0.00, 0.00),
(369, 97, 8, '2\"x 1\"', 5, 0.00, 0.00),
(370, 97, 8, '2\"x 1.¼\"', 5, 0.00, 0.00),
(371, 97, 8, '2\"x1.½\"', 5, 0.00, 0.00),
(372, 97, 8, '2.½\"x½\"', 5, 0.00, 0.00),
(373, 97, 8, '2.½\"\"x ¾\"', 5, 0.00, 0.00),
(374, 97, 8, '2.½\"\"x 1\"', 5, 0.00, 0.00),
(375, 97, 8, '2.½\"\"x 1.¼\"', 5, 0.00, 0.00),
(376, 97, 8, '2.½\"\"x 1.½\"', 5, 0.00, 0.00),
(377, 97, 8, '2.½\"\"x 2\"', 5, 0.00, 0.00),
(378, 97, 8, '3\"x½\"', 5, 0.00, 0.00),
(379, 97, 8, '3\"x¾\"', 5, 0.00, 0.00),
(380, 97, 8, '3\"x1\"', 5, 0.00, 0.00),
(381, 97, 8, '3\"x1.¼\"', 5, 0.00, 0.00),
(382, 97, 8, '3\"x1.½\"', 5, 0.00, 0.00),
(383, 97, 8, '3\"x2\"', 5, 0.00, 0.00),
(384, 97, 8, '3\"x2.½\"', 5, 0.00, 0.00),
(385, 97, 8, '4\"x1\"', 5, 0.00, 0.00),
(386, 97, 8, '4\"x1.¼\"', 5, 0.00, 0.00),
(387, 97, 8, '4\"x1.½\"', 5, 0.00, 0.00),
(388, 97, 8, '4\"x2\"', 5, 0.00, 0.00),
(389, 97, 8, '4\"x2.½\"', 5, 0.00, 0.00),
(390, 97, 8, '4\"x3\"', 5, 0.00, 0.00),
(391, 98, 16, 'Corrido', 10, 0.00, 0.00),
(392, 98, 16, '5 cm', 10, 0.00, 0.00),
(393, 98, 16, '10 cm', 10, 0.00, 0.00),
(394, 98, 16, '15 cm', 10, 0.00, 0.00),
(395, 98, 16, '20 cm', 10, 0.00, 0.00),
(396, 98, 16, '25 cm', 10, 0.00, 0.00),
(397, 98, 16, '30 cm', 10, 0.00, 0.00),
(398, 98, 16, '40 cm', 10, 0.00, 0.00),
(399, 98, 16, '50 cm', 10, 0.00, 0.00),
(400, 98, 16, '60 cm', 10, 0.00, 0.00),
(401, 98, 16, '70 cm', 10, 0.00, 0.00),
(402, 98, 16, '80 cm', 10, 0.00, 0.00),
(403, 98, 16, '90 cm', 10, 0.00, 0.00),
(404, 98, 16, '100cm', 10, 0.00, 0.00),
(405, 99, 16, 'Corrido', 10, 0.00, 0.00),
(406, 99, 16, '5 cm', 10, 0.00, 0.00),
(407, 99, 16, '10 cm', 10, 0.00, 0.00),
(408, 99, 16, '15 cm', 10, 0.00, 0.00),
(409, 99, 16, '20 cm', 10, 0.00, 0.00),
(410, 99, 16, '25 cm', 10, 0.00, 0.00),
(411, 99, 16, '30 cm', 10, 0.00, 0.00),
(412, 99, 16, '40 cm', 10, 0.00, 0.00),
(413, 99, 16, '50 cm', 10, 0.00, 0.00),
(414, 99, 16, '60 cm', 10, 0.00, 0.00),
(415, 99, 16, '70 cm', 10, 0.00, 0.00),
(416, 99, 16, '80 cm', 10, 0.00, 0.00),
(417, 99, 16, '90 cm', 10, 0.00, 0.00),
(418, 99, 16, '100cm', 10, 0.00, 0.00),
(419, 100, 16, 'Corrido', 10, 0.00, 0.00),
(420, 100, 16, '5 cm', 10, 0.00, 0.00),
(421, 100, 16, '10 cm', 10, 0.00, 0.00),
(422, 100, 16, '15 cm', 10, 0.00, 0.00),
(423, 100, 16, '20 cm', 10, 0.00, 0.00),
(424, 100, 16, '25 cm', 10, 0.00, 0.00),
(425, 100, 16, '30 cm', 10, 0.00, 0.00),
(426, 100, 16, '40 cm', 10, 0.00, 0.00),
(427, 100, 16, '50 cm', 10, 0.00, 0.00),
(428, 100, 16, '60 cm', 10, 0.00, 0.00),
(429, 100, 16, '70 cm', 10, 0.00, 0.00),
(430, 100, 16, '80 cm', 10, 0.00, 0.00),
(431, 100, 16, '90 cm', 10, 0.00, 0.00),
(432, 100, 16, '100cm', 10, 0.00, 0.00),
(433, 101, 16, 'Corrido', 10, 0.00, 0.00),
(434, 101, 16, '5 cm', 10, 0.00, 0.00),
(435, 101, 16, '10 cm', 10, 0.00, 0.00),
(436, 101, 16, '15 cm', 10, 0.00, 0.00),
(437, 101, 16, '20 cm', 10, 0.00, 0.00),
(438, 101, 16, '25 cm', 10, 0.00, 0.00),
(439, 101, 16, '30 cm', 10, 0.00, 0.00),
(440, 101, 16, '40 cm', 10, 0.00, 0.00),
(441, 101, 16, '50 cm', 10, 0.00, 0.00),
(442, 101, 16, '60 cm', 10, 0.00, 0.00),
(443, 101, 16, '70 cm', 10, 0.00, 0.00),
(444, 101, 16, '80 cm', 10, 0.00, 0.00),
(445, 101, 16, '90 cm', 10, 0.00, 0.00),
(446, 101, 16, '100cm', 10, 0.00, 0.00),
(447, 102, 16, 'Corrido', 10, 0.00, 0.00),
(448, 102, 16, '5 cm', 10, 0.00, 0.00),
(449, 102, 16, '10 cm', 10, 0.00, 0.00),
(450, 102, 16, '15 cm', 10, 0.00, 0.00),
(451, 102, 16, '20 cm', 10, 0.00, 0.00),
(452, 102, 16, '25 cm', 10, 0.00, 0.00),
(453, 102, 16, '30 cm', 10, 0.00, 0.00),
(454, 102, 16, '40 cm', 10, 0.00, 0.00),
(455, 102, 16, '50 cm', 10, 0.00, 0.00),
(456, 102, 16, '60 cm', 10, 0.00, 0.00),
(457, 102, 16, '70 cm', 10, 0.00, 0.00),
(458, 102, 16, '80 cm', 10, 0.00, 0.00),
(459, 102, 16, '90 cm', 10, 0.00, 0.00),
(460, 102, 16, '100cm', 10, 0.00, 0.00),
(461, 103, 16, 'Corrido', 10, 0.00, 0.00),
(462, 103, 16, '10 cm', 10, 0.00, 0.00),
(463, 103, 16, '15 cm', 10, 0.00, 0.00),
(464, 103, 16, '20 cm', 10, 0.00, 0.00),
(465, 103, 16, '25 cm', 10, 0.00, 0.00),
(466, 103, 16, '30 cm', 10, 0.00, 0.00),
(467, 103, 16, '40 cm', 10, 0.00, 0.00),
(468, 103, 16, '50 cm', 10, 0.00, 0.00),
(469, 103, 16, '60 cm', 10, 0.00, 0.00),
(470, 103, 16, '70 cm', 10, 0.00, 0.00),
(471, 103, 16, '80 cm', 10, 0.00, 0.00),
(472, 103, 16, '90 cm', 10, 0.00, 0.00),
(473, 103, 16, '100cm', 10, 0.00, 0.00),
(474, 104, 16, 'Corrido', 10, 0.00, 0.00),
(475, 104, 16, '10 cm', 10, 0.00, 0.00),
(476, 104, 16, '15 cm', 10, 0.00, 0.00),
(477, 104, 16, '20 cm', 10, 0.00, 0.00),
(478, 104, 16, '25 cm', 10, 0.00, 0.00),
(479, 104, 16, '30 cm', 10, 0.00, 0.00),
(480, 104, 16, '40 cm', 10, 0.00, 0.00),
(481, 104, 16, '50 cm', 10, 0.00, 0.00),
(482, 104, 16, '60 cm', 10, 0.00, 0.00),
(483, 104, 16, '70 cm', 10, 0.00, 0.00),
(484, 104, 16, '80 cm', 10, 0.00, 0.00),
(485, 104, 16, '90 cm', 10, 0.00, 0.00),
(486, 104, 16, '100cm', 10, 0.00, 0.00),
(487, 105, 16, 'Corrido', 10, 0.00, 0.00),
(488, 105, 16, '10 cm', 10, 0.00, 0.00),
(489, 105, 16, '15 cm', 10, 0.00, 0.00),
(490, 105, 16, '20 cm', 10, 0.00, 0.00),
(491, 105, 16, '25 cm', 10, 0.00, 0.00),
(492, 105, 16, '30 cm', 10, 0.00, 0.00),
(493, 105, 16, '40 cm', 10, 0.00, 0.00),
(494, 105, 16, '50 cm', 10, 0.00, 0.00),
(495, 105, 16, '60 cm', 10, 0.00, 0.00),
(496, 105, 16, '70 cm', 10, 0.00, 0.00),
(497, 105, 16, '80 cm', 10, 0.00, 0.00),
(498, 105, 16, '90 cm', 10, 0.00, 0.00),
(499, 105, 16, '100cm', 10, 0.00, 0.00),
(500, 106, 16, 'Corrido', 10, 0.00, 0.00),
(501, 106, 16, '10 cm', 10, 0.00, 0.00),
(502, 106, 16, '15 cm', 10, 0.00, 0.00),
(503, 106, 16, '20 cm', 10, 0.00, 0.00),
(504, 106, 16, '25 cm', 10, 0.00, 0.00),
(505, 106, 16, '30 cm', 10, 0.00, 0.00),
(506, 106, 16, '40 cm', 10, 0.00, 0.00),
(507, 106, 16, '50 cm', 10, 0.00, 0.00),
(508, 106, 16, '60 cm', 10, 0.00, 0.00),
(509, 106, 16, '70 cm', 10, 0.00, 0.00),
(510, 106, 16, '80 cm', 10, 0.00, 0.00),
(511, 106, 16, '90 cm', 10, 0.00, 0.00),
(512, 106, 16, '100cm', 10, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recomendaciones_categoria`
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
-- Volcado de datos para la tabla `recomendaciones_categoria`
--

INSERT INTO `recomendaciones_categoria` (`id`, `categoria_id`, `producto_id`, `orden`, `activo`, `created_at`, `rec_id`) VALUES
(1, 1, 8, 1, 1, '2025-10-07 20:43:06', 1),
(2, 1, 13, 2, 1, '2025-10-07 20:43:06', 1),
(3, 1, 54, 3, 1, '2025-10-07 20:43:06', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `medidas_categoria`
--
ALTER TABLE `medidas_categoria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_categoria_medida` (`categoria_id`,`medida_id`),
  ADD KEY `idx_categoria` (`categoria_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `productos_medidas`
--
ALTER TABLE `productos_medidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_medida_por_producto` (`producto_id`,`medida_id`),
  ADD KEY `idx_producto_id` (`producto_id`);

--
-- Indices de la tabla `recomendaciones_categoria`
--
ALTER TABLE `recomendaciones_categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `medidas_categoria`
--
ALTER TABLE `medidas_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT de la tabla `productos_medidas`
--
ALTER TABLE `productos_medidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=513;

--
-- AUTO_INCREMENT de la tabla `recomendaciones_categoria`
--
ALTER TABLE `recomendaciones_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_medidas`
--
ALTER TABLE `productos_medidas`
  ADD CONSTRAINT `productos_medidas_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `recomendaciones_categoria`
--
ALTER TABLE `recomendaciones_categoria`
  ADD CONSTRAINT `recomendaciones_categoria_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
