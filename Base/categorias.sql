-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-11-2025 a las 21:43:41
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
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `color_fondo` varchar(7) DEFAULT '#FFFFFF',
  `texto_color` varchar(7) DEFAULT '#000000',
  `boton_primario` varchar(7) DEFAULT '#007bff',
  `boton_secundario` varchar(7) DEFAULT '#6c757d',
  `color_titulo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `activo`, `created_at`, `color_fondo`, `texto_color`, `boton_primario`, `boton_secundario`, `color_titulo`) VALUES
(1, 'CPVC agua caliente', 'cpvc-agua-caliente', 'Tubos y conexiones de Policloruro de vinilo clorado(CPVC), termoplástico producido por coloración de la resina de policloruro de vinilo(PVC).', 1, '2025-09-23 23:46:58', '#ddc565', '#050040', '#1db954', '#6c757d', '#050040'),
(2, 'Tubería PPR', 'tuberia-ppr', 'Tubos y conexiones de Polipropileno es un termoplástico resistente a impactos, para presiones hasta 20 kg ( a 20 °C), bicapa', 1, '2025-09-23 23:46:58', '#336a53', '#000', '#1db954', '#6c757d', '#050040'),
(3, 'Hidráulica C-40 PVC', 'ingles-c40-pvc', 'Tubos y conexiones de Policloruro de vinilo clorado(CPVC), termoplástico producido por coloración de la resina de policloruro de vinilo(PVC).', 1, '2025-10-07 22:55:26', '#398cac', '#000000', '#1db954', '#6c757d', '#ffffff'),
(4, 'Cementos', 'cementos', 'Cementos para la unión de tubería de acuerdo a su material y presión', 1, '2025-10-07 22:26:08', '#398cac', '#000000', '#1db954', '#6c757d', '#fff'),
(5, 'Hidráulica C-80 PVC', 'ingles-c80-pvc', 'Tubos y conexiones de Policloruro de vinilo (PVC) es un termoplástico producido a partir de la resina de policloruro de vinilo (PVC), la tubería C-80 es gris y se une mediante cemento especial y existen piezas para roscar y cementar', 1, '2025-10-07 22:55:26', '#b3afae', '#7879a', '#1db954', '#6c757d', '#130e83'),
(6, 'Hidráulica con campana PVC', 'campana-CPVC', 'Tubos y conexiones de Policloruro de vinilo (PVC) es un termoplástico de la resina de policloruro de vinilo (PVC). Unión Campana-Espiga. Todos bajo la medida inglesa.', 1, '2025-10-07 22:58:27', '#13aeeb', '#000', '#1db954', '#6c757d', '#fff'),
(7, 'Tubería galvanizada', 'tuberia-galvanizada', 'Tubos y conexiones de fierro galvanizado, ideales para conducción exterior y de alta presión', 1, '2025-09-23 23:46:58', '#7b797a', '#ff070e', '#1db954', '#6c757d', '#ffffff'),
(8, 'Toma domiciliaria', 'toma-domi', 'La toma domiciliaria, es un \"estándar\" que puede ser de diferentes materiales e inicia en la conexión de agua a partir de la red general hidráulica, de la ciudad o de la infraestructura autorizada para suministro de agua a viviendas, edificios, centros comerciales, etc.', 1, '2025-09-23 23:46:58', '#5883ca', '#fff', '#1db954', '#6c757d', '#00'),
(9, 'Medidores y valvulas', 'medidores-y-valvulas', 'Los micro-medidores son una parte esencial en todo sistema de agua para cuantificar la cantidad de líquido que pasa en determinado punto para el abastecimiento de líneas y la extracción de los pozos por lo que se divide en micro medidores (½\", ¾\", 1\", 1 ½\", 2\") y macro medidores (2\", 2 ½\", 3\", 4\", 6\", 8\", 10\", 12\" etc.) así como también existen varios tipos de medidores', 1, '2025-09-23 23:46:58', '#0c378b', '#fff', '#1db954', '#6c757d', '#ffffff'),
(10, 'Conexiones fierro fundido', 'Conexiones fierro fundido', 'Conexiones de Fierro fundido para unión bridada son de medida Inglesa, su uso es común en los arreglos de los trenes de conexión de pozos y sistemas de agua dada su resistencia a la intemperie y capacidad de soporte de presiones.\n\n', 1, '2025-10-07 23:02:15', '#26304b', '#ffffff', '#1db954', '#6c757d', '#ffffff'),
(11, 'Alcantarillado ', 'métrico-campana', 'Las tuberías de alcantarillado están diseñadas para la resistencia a aguas negras y desechos comerciales que se llegan a derramar en el drenaje así como la resistencia a la carga para su colocación bajo tierra', 1, '2025-10-07 23:02:15', '#ff7300', '#fff', '#1db954', '#6c757d', '#fff'),
(12, 'Tubería polietileno corrugado', 'polietileno-corrugado', 'Tubos y conexiones de Polietileno de alta densidad (PEAD o PAD) es un termoplástico producido por etilenos con alta densidad para la resistencia a CARGAS.', 1, '2025-10-07 23:08:28', '#4f4f4f', '#fff', '#1db954', '#6c757d', '#fff'),
(13, 'Sanitaria', 'linea-sanitaria', 'Tubos y conexiones de Policloruro de vinilo clorado(CPVC), termoplástico producido por coloración de la resina de policloruro de vinilo(PVC).', 1, '2025-09-23 23:46:58', '#917d70', '#000', '#1db954', '#6c757d', '#ffffff');

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
