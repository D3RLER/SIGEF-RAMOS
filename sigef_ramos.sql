-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-03-2026 a las 08:04:33
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sigef_ramos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `p_nombre` varchar(50) NOT NULL,
  `s_nombre` varchar(50) DEFAULT NULL,
  `a_paterno` varchar(50) NOT NULL,
  `a_materno` varchar(50) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `p_nombre`, `s_nombre`, `a_paterno`, `a_materno`, `dni`, `email`, `password`, `telefono`, `direccion`, `fecha_registro`) VALUES
(1, 'Sebastian', 'Alberto', 'Ramos', 'Moquegua', '88888888', 'sebastian@ramos.com', '\\\\\\/jZ.0OtzX..72B4Z.zOyM/lH5Q8i2Q7s.j8J.5m/0iQ/XJ9Cjm', NULL, NULL, '2026-03-06 05:30:40'),
(3, 'Jaun', '', 'Pareque', 'Gomezz', '12345678', 'a@gmail.com', '$2y$10$TWm1pf7SV69G6TKwiB4DYOBcPE3NpBW6czMH2QW5EO8UknzKQ0KGm', NULL, NULL, '2026-03-06 05:43:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deudos`
--

CREATE TABLE `deudos` (
  `id` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `deudos`
--

INSERT INTO `deudos` (`id`, `dni`, `nombres`, `apellidos`, `telefono`, `direccion`, `email`, `created_at`) VALUES
(1, '87654321', 'Juan', 'Perez', NULL, NULL, NULL, '2026-03-05 23:14:00'),
(2, '76397340', 'Juan', 'Gonzalez Garcia', '917937496', 'Av. Ficticia 348', NULL, '2026-03-06 01:12:21'),
(3, '64996887', 'Julia', 'Sanchez Sanchez', '934813637', 'Av. Ficticia 727', NULL, '2026-03-06 01:12:21'),
(4, '46195493', 'Julia', 'Lopez Sanchez', '923548774', 'Av. Ficticia 897', NULL, '2026-03-06 01:12:21'),
(5, '18460780', 'Pedro', 'Martinez Rodriguez', '914370849', 'Av. Ficticia 802', NULL, '2026-03-06 01:12:21'),
(6, '82709028', 'Ana', 'Hernandez Sanchez', '987984078', 'Av. Ficticia 988', NULL, '2026-03-06 01:12:21'),
(7, '84810677', 'Carlos', 'Perez Martinez', '923000389', 'Av. Ficticia 624', NULL, '2026-03-06 01:12:21'),
(8, '79655043', 'Lucia', 'Martinez Torres', '935229063', 'Av. Ficticia 885', NULL, '2026-03-06 01:12:21'),
(9, '80822479', 'Lucia', 'Ramirez Torres', '929867985', 'Av. Ficticia 214', NULL, '2026-03-06 01:12:21'),
(10, '96298604', 'Carmen', 'Perez Martinez', '979459014', 'Av. Ficticia 976', NULL, '2026-03-06 01:12:21'),
(11, '53998130', 'Julia', 'Sanchez Gonzalez', '973979895', 'Av. Ficticia 778', NULL, '2026-03-06 01:12:21'),
(12, '77995461', 'Luis', 'Gonzalez Hernandez', '938088011', 'Av. Ficticia 221', NULL, '2026-03-06 01:12:21'),
(13, '34752001', 'Jose', 'Perez Perez', '956321806', 'Av. Ficticia 387', NULL, '2026-03-06 01:12:21'),
(14, '74797115', 'Lucia', 'Rodriguez Torres', '956631714', 'Av. Ficticia 715', NULL, '2026-03-06 01:12:21'),
(15, '51588506', 'Carmen', 'Torres Hernandez', '925864451', 'Av. Ficticia 906', NULL, '2026-03-06 01:12:21'),
(16, '74736319', 'Julia', 'Hernandez Sanchez', '947847680', 'Av. Ficticia 187', NULL, '2026-03-06 01:12:21'),
(17, '66006991', 'Miguel', 'Garcia Ramirez', '946769290', 'Av. Ficticia 738', NULL, '2026-03-06 01:12:21'),
(18, '35045151', 'Miguel', 'Hernandez Gonzalez', '940571491', 'Av. Ficticia 473', NULL, '2026-03-06 01:12:21'),
(19, '82070690', 'Ana', 'Rodriguez Lopez', '998072117', 'Av. Ficticia 349', NULL, '2026-03-06 01:12:21'),
(20, '23868353', 'Juan', 'Garcia Sanchez', '952005185', 'Av. Ficticia 702', NULL, '2026-03-06 01:12:21'),
(21, '66917426', 'Pedro', 'Hernandez Hernandez', '910837960', 'Av. Ficticia 377', NULL, '2026-03-06 01:12:21'),
(22, '61202639', 'Maria', 'Martinez Gonzalez', '991846968', 'Av. Ficticia 780', NULL, '2026-03-06 02:21:01'),
(23, '27538254', 'Carlos', 'Martinez Martinez', '982983326', 'Av. Ficticia 910', NULL, '2026-03-06 02:21:01'),
(24, '23296637', 'Juan', 'Rodriguez Rodriguez', '985890628', 'Av. Ficticia 172', NULL, '2026-03-06 02:21:01'),
(25, '10157971', 'Luis', 'Garcia Hernandez', '957786585', 'Av. Ficticia 457', NULL, '2026-03-06 02:21:01'),
(26, '28315073', 'Julia', 'Garcia Rodriguez', '970061696', 'Av. Ficticia 359', NULL, '2026-03-06 02:21:01'),
(27, '18449844', 'Jose', 'Garcia Perez', '992615741', 'Av. Ficticia 861', NULL, '2026-03-06 02:21:01'),
(28, '19978146', 'Maria', 'Hernandez Gonzalez', '934614753', 'Av. Ficticia 715', NULL, '2026-03-06 02:21:01'),
(29, '11613106', 'Miguel', 'Garcia Lopez', '969216324', 'Av. Ficticia 237', NULL, '2026-03-06 02:21:01'),
(30, '52565949', 'Rosa', 'Ramirez Rodriguez', '925910364', 'Av. Ficticia 193', NULL, '2026-03-06 02:21:01'),
(31, '37126382', 'Julia', 'Sanchez Sanchez', '978803120', 'Av. Ficticia 790', NULL, '2026-03-06 02:21:01'),
(32, '24228852', 'Luis', 'Hernandez Gonzalez', '925726057', 'Av. Ficticia 618', NULL, '2026-03-06 02:21:01'),
(33, '37322859', 'Pedro', 'Lopez Perez', '914927816', 'Av. Ficticia 181', NULL, '2026-03-06 02:21:01'),
(34, '37310155', 'Julia', 'Hernandez Gonzalez', '996898071', 'Av. Ficticia 533', NULL, '2026-03-06 02:21:01'),
(35, '64713377', 'Ana', 'Sanchez Garcia', '956999587', 'Av. Ficticia 377', NULL, '2026-03-06 02:21:01'),
(36, '53389065', 'Miguel', 'Hernandez Hernandez', '951895262', 'Av. Ficticia 808', NULL, '2026-03-06 02:21:01'),
(37, '27580376', 'Julia', 'Lopez Martinez', '943586447', 'Av. Ficticia 585', NULL, '2026-03-06 02:21:01'),
(38, '72671039', 'Luis', 'Rodriguez Martinez', '953036646', 'Av. Ficticia 704', NULL, '2026-03-06 02:21:01'),
(39, '71448748', 'Pedro', 'Torres Gonzalez', '994504675', 'Av. Ficticia 653', NULL, '2026-03-06 02:21:01'),
(40, '29531187', 'Julia', 'Torres Torres', '955867064', 'Av. Ficticia 976', NULL, '2026-03-06 02:21:01'),
(41, '90918216', 'Carmen', 'Perez Perez', '967833542', 'Av. Ficticia 524', NULL, '2026-03-06 02:21:01'),
(42, '71709313', 'asd', 'asdad', '427257', 'dasdad', NULL, '2026-03-06 04:02:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `difuntos`
--

CREATE TABLE `difuntos` (
  `id` int(11) NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_fallecimiento` date NOT NULL,
  `causa_muerte` varchar(255) DEFAULT NULL,
  `deudo_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `difuntos`
--

INSERT INTO `difuntos` (`id`, `dni`, `nombres`, `apellidos`, `fecha_nacimiento`, `fecha_fallecimiento`, `causa_muerte`, `deudo_id`, `created_at`) VALUES
(1, NULL, 'Pedro', 'Perez', NULL, '2023-10-10', NULL, 1, '2026-03-05 23:14:00'),
(2, '45219034', 'Luis', 'Lopez', NULL, '2025-11-29', 'Traumatismo', 2, '2026-03-06 01:12:21'),
(3, '73579081', 'Ana', 'Martinez', NULL, '2025-09-08', 'Paro Cardíaco', 3, '2026-03-06 01:12:21'),
(4, '82453862', 'Carlos', 'Gonzalez', NULL, '2025-12-22', 'Muerte Natural', 4, '2026-03-06 01:12:21'),
(5, '21160600', 'Julia', 'Gonzalez', NULL, '2025-11-20', 'Muerte Natural', 5, '2026-03-06 01:12:21'),
(6, '12240193', 'Carlos', 'Ramirez', NULL, '2025-10-26', 'COVID-19', 6, '2026-03-06 01:12:21'),
(7, '81229863', 'Juan', 'Martinez', NULL, '2025-12-01', 'COVID-19', 7, '2026-03-06 01:12:21'),
(8, '71097236', 'Luis', 'Sanchez', NULL, '2025-12-29', 'Insuficiencia Renal', 8, '2026-03-06 01:12:21'),
(9, '68615966', 'Carlos', 'Perez', NULL, '2026-02-21', 'COVID-19', 9, '2026-03-06 01:12:21'),
(10, '10320764', 'Luis', 'Martinez', NULL, '2026-02-28', 'Cáncer', 10, '2026-03-06 01:12:21'),
(11, '41889309', 'Jose', 'Sanchez', NULL, '2025-09-09', 'Accidente de Tránsito', 11, '2026-03-06 01:12:21'),
(12, '48335497', 'Lucia', 'Torres', NULL, '2025-09-25', 'Traumatismo', 12, '2026-03-06 01:12:21'),
(13, '93717339', 'Miguel', 'Lopez', NULL, '2025-10-05', 'Paro Cardíaco', 13, '2026-03-06 01:12:21'),
(14, '42567059', 'Luis', 'Perez', NULL, '2025-12-28', 'Cáncer', 14, '2026-03-06 01:12:21'),
(15, '89405849', 'Ana', 'Martinez', NULL, '2026-01-12', 'Paro Cardíaco', 15, '2026-03-06 01:12:21'),
(16, '34301598', 'Jose', 'Hernandez', NULL, '2026-01-09', 'Accidente de Tránsito', 16, '2026-03-06 01:12:21'),
(17, '57300236', 'Miguel', 'Martinez', NULL, '2025-09-29', 'Paro Cardíaco', 17, '2026-03-06 01:12:21'),
(18, '47517133', 'Carlos', 'Lopez', NULL, '2026-03-06', 'Accidente de Tránsito', 18, '2026-03-06 01:12:21'),
(19, '59925918', 'Luis', 'Hernandez', NULL, '2026-03-04', 'Insuficiencia Renal', 19, '2026-03-06 01:12:21'),
(20, '16856929', 'Maria', 'Sanchez', NULL, '2025-12-26', 'Cáncer', 20, '2026-03-06 01:12:21'),
(21, '33842823', 'Lucia', 'Garcia', NULL, '2025-09-22', 'Cáncer', 21, '2026-03-06 01:12:21'),
(22, '43653237', 'Lucia', 'Hernandez', NULL, '2025-11-16', 'Accidente de Tránsito', 22, '2026-03-06 02:21:01'),
(23, '85003588', 'Maria', 'Garcia', NULL, '2026-02-23', 'Paro Cardíaco', 23, '2026-03-06 02:21:01'),
(24, '87815564', 'Julia', 'Martinez', NULL, '2025-11-01', 'Insuficiencia Renal', 24, '2026-03-06 02:21:01'),
(25, '64341681', 'Pedro', 'Torres', NULL, '2025-09-10', 'Insuficiencia Renal', 25, '2026-03-06 02:21:01'),
(26, '92668722', 'Luis', 'Martinez', NULL, '2025-11-08', 'COVID-19', 26, '2026-03-06 02:21:01'),
(27, '36438152', 'Jose', 'Ramirez', NULL, '2025-10-13', 'COVID-19', 27, '2026-03-06 02:21:01'),
(28, '93414811', 'Miguel', 'Hernandez', NULL, '2026-01-31', 'Insuficiencia Renal', 28, '2026-03-06 02:21:01'),
(29, '62796397', 'Pedro', 'Lopez', NULL, '2025-10-16', 'Traumatismo', 29, '2026-03-06 02:21:01'),
(30, '64973052', 'Rosa', 'Perez', NULL, '2026-01-28', 'Muerte Natural', 30, '2026-03-06 02:21:01'),
(31, '90891768', 'Lucia', 'Torres', NULL, '2025-11-22', 'Traumatismo', 31, '2026-03-06 02:21:01'),
(32, '37733985', 'Jose', 'Sanchez', NULL, '2026-01-03', 'Muerte Natural', 32, '2026-03-06 02:21:01'),
(33, '54023046', 'Jose', 'Gonzalez', NULL, '2025-09-08', 'Insuficiencia Renal', 33, '2026-03-06 02:21:01'),
(34, '53506361', 'Lucia', 'Perez', NULL, '2026-01-13', 'Cáncer', 34, '2026-03-06 02:21:01'),
(35, '86376562', 'Lucia', 'Lopez', NULL, '2026-01-30', 'Insuficiencia Renal', 35, '2026-03-06 02:21:01'),
(36, '34620925', 'Pedro', 'Torres', NULL, '2025-12-01', 'Muerte Natural', 36, '2026-03-06 02:21:01'),
(37, '22626365', 'Pedro', 'Torres', NULL, '2026-03-02', 'Traumatismo', 37, '2026-03-06 02:21:01'),
(38, '58602856', 'Miguel', 'Garcia', NULL, '2025-10-29', 'Accidente de Tránsito', 38, '2026-03-06 02:21:01'),
(39, '66331185', 'Julia', 'Lopez', NULL, '2026-01-18', 'Paro Cardíaco', 39, '2026-03-06 02:21:01'),
(40, '46696652', 'Carlos', 'Perez', NULL, '2026-02-25', 'Accidente de Tránsito', 40, '2026-03-06 02:21:01'),
(41, '99390337', 'Pedro', 'Garcia', NULL, '2026-01-09', 'COVID-19', 41, '2026-03-06 02:21:01'),
(42, '71708931', 'asda', 'asdasd', NULL, '2026-03-06', 'paro repiratorio', 42, '2026-03-06 04:02:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` int(11) NOT NULL,
  `sede_id` int(11) NOT NULL,
  `producto` varchar(100) NOT NULL,
  `categoria` enum('Ataudes','Traslados','Salas de Velacion','Arreglos Florales','Recordatorios Funebres','Gestion de Tramites') NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 5,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `sede_id`, `producto`, `categoria`, `imagen`, `stock`, `stock_minimo`, `precio_compra`, `precio_venta`, `created_at`) VALUES
(4, 1, 'Modelo Estandar', 'Ataudes', NULL, 10, 5, NULL, 1500.00, '2026-03-06 05:54:27'),
(5, 1, 'Madera Fina', 'Ataudes', NULL, 5, 5, NULL, 2500.00, '2026-03-06 05:54:27'),
(6, 1, 'Carroza de Lujo', 'Traslados', NULL, 2, 5, NULL, 300.00, '2026-03-06 05:54:27'),
(7, 1, 'Capilla Ardiente', 'Salas de Velacion', NULL, 3, 5, NULL, 800.00, '2026-03-06 05:54:27'),
(8, 1, 'Cremacion Integral', 'Gestion de Tramites', NULL, 100, 5, NULL, 2500.00, '2026-03-06 05:54:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proformas`
--

CREATE TABLE `proformas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `sede_id` int(11) DEFAULT NULL,
  `tipo_ataud` varchar(50) DEFAULT NULL,
  `movilidad` enum('SÝ','No') DEFAULT NULL,
  `capilla` enum('SÝ','No') DEFAULT NULL,
  `cremacion` enum('SÝ','No') DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proformas`
--

INSERT INTO `proformas` (`id`, `cliente_id`, `sede_id`, `tipo_ataud`, `movilidad`, `capilla`, `cremacion`, `total`, `fecha_registro`) VALUES
(1, 3, 1, 'Estandar', 'No', 'No', 'No', 1500.00, '2026-03-06 05:46:13'),
(2, 3, 1, 'Estandar', 'No', 'No', 'No', 1500.00, '2026-03-06 05:46:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sedes`
--

CREATE TABLE `sedes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sedes`
--

INSERT INTO `sedes` (`id`, `nombre`, `direccion`, `telefono`, `created_at`) VALUES
(1, 'Ilo', 'Calle Principal 123, Ilo', '999888777', '2026-03-05 23:14:00'),
(2, 'Moquegua', 'Av. Central 456, Moquegua', '987654321', '2026-03-05 23:14:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `difunto_id` int(11) NOT NULL,
  `sede_id` int(11) NOT NULL,
  `vendedor_id` int(11) DEFAULT NULL,
  `tipo_servicio` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha_servicio` datetime NOT NULL,
  `estado` enum('pendiente','en_preparacion','en_traslado','finalizado','cancelado') DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_finalizacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `difunto_id`, `sede_id`, `vendedor_id`, `tipo_servicio`, `descripcion`, `precio`, `fecha_servicio`, `estado`, `created_at`, `fecha_finalizacion`) VALUES
(1, 1, 1, 2, 'Servicio Completo', NULL, 3500.00, '2026-03-05 00:00:00', '', '2026-03-05 23:14:00', NULL),
(2, 1, 1, 2, 'Cremacion', NULL, 2000.00, '2026-03-03 00:00:00', 'finalizado', '2026-03-05 23:14:00', '2026-03-05 23:31:43'),
(3, 1, 2, 2, 'Servicio Basico', NULL, 1500.00, '2026-02-28 00:00:00', 'finalizado', '2026-03-05 23:14:00', '2026-03-05 23:31:43'),
(4, 1, 1, 2, 'Traslado', NULL, 500.00, '2026-02-05 00:00:00', 'finalizado', '2026-03-05 23:14:00', '2026-03-05 23:31:43'),
(5, 1, 2, 2, 'Velatorio', NULL, 1000.00, '2026-03-05 00:00:00', 'pendiente', '2026-03-05 23:14:00', NULL),
(6, 2, 1, 2, 'Paquete Estandar', 'Contrato generado automáticamente por Seed. Paquete: Paquete Estándar', 2549.93, '2025-11-29 19:00:00', 'finalizado', '2026-03-06 01:12:21', '2026-03-05 23:31:43'),
(7, 3, 2, 2, 'Paquete Basico', 'Contrato generado automáticamente por Seed. Paquete: Paquete Básico', 1547.46, '2025-09-08 05:00:00', 'finalizado', '2026-03-06 01:12:21', '2026-03-05 23:31:43'),
(8, 4, 2, 2, 'Paquete Estandar', 'Contrato generado automáticamente por Seed. Paquete: Paquete Estándar', 1161.70, '2025-12-22 18:00:00', 'pendiente', '2026-03-06 01:12:21', NULL),
(9, 5, 1, 2, 'Traslado Nacional', 'Contrato generado automáticamente por Seed. Paquete: Traslado Nacional', 1177.28, '2025-11-20 07:00:00', 'finalizado', '2026-03-06 01:12:21', '2026-03-05 23:31:43'),
(10, 6, 1, 2, 'Paquete Basico', 'Contrato generado automáticamente por Seed. Paquete: Paquete Básico', 3211.39, '2025-10-26 14:00:00', 'finalizado', '2026-03-06 01:12:21', '2026-03-05 23:31:43'),
(11, 7, 2, 2, 'Paquete Premium', 'Contrato generado automáticamente por Seed. Paquete: Paquete Premium', 3816.91, '2025-12-01 11:00:00', 'finalizado', '2026-03-06 01:12:21', '2026-03-05 23:31:43'),
(12, 8, 1, 2, 'Traslado Nacional', 'Contrato generado automáticamente por Seed. Paquete: Traslado Nacional', 3246.02, '2025-12-29 21:00:00', 'en_traslado', '2026-03-06 01:12:21', NULL),
(13, 9, 1, 2, 'Paquete Estandar', 'Contrato generado automáticamente por Seed. Paquete: Paquete Estándar', 2247.67, '2026-02-21 07:00:00', 'finalizado', '2026-03-06 01:12:21', '2026-03-05 23:31:43'),
(14, 10, 1, 2, 'Paquete Estandar', 'Contrato generado automáticamente por Seed. Paquete: Paquete Estándar', 1807.94, '2026-02-28 03:00:00', 'en_preparacion', '2026-03-06 01:12:21', NULL),
(15, 11, 2, 2, 'Traslado Nacional', 'Contrato generado automáticamente por Seed. Paquete: Traslado Nacional', 2018.28, '2025-09-09 17:00:00', 'finalizado', '2026-03-06 01:12:21', '2026-03-05 23:31:43'),
(16, 12, 2, 2, 'Servicio de Cremacion', 'Contrato generado automáticamente por Seed. Paquete: Servicio de Cremación', 4304.28, '2025-09-25 19:00:00', 'pendiente', '2026-03-06 01:12:21', NULL),
(17, 13, 2, 2, 'Traslado Nacional', 'Contrato generado automáticamente por Seed. Paquete: Traslado Nacional', 1731.27, '2025-10-05 23:00:00', 'finalizado', '2026-03-06 01:12:21', '2026-03-05 23:31:43'),
(18, 14, 1, 2, 'Paquete Basico', 'Contrato generado automáticamente por Seed. Paquete: Paquete Básico', 951.25, '2025-12-28 20:00:00', 'finalizado', '2026-03-06 01:12:21', '2026-03-05 23:31:43'),
(19, 15, 1, 2, 'Paquete Premium', 'Contrato generado automáticamente por Seed. Paquete: Paquete Premium', 3085.78, '2026-01-13 00:00:00', 'en_preparacion', '2026-03-06 01:12:21', NULL),
(20, 16, 1, 2, 'Paquete Premium', 'Contrato generado automáticamente por Seed. Paquete: Paquete Premium', 1773.87, '2026-01-09 02:00:00', 'en_preparacion', '2026-03-06 01:12:21', NULL),
(21, 17, 1, 2, 'Paquete Premium', 'Contrato generado automáticamente por Seed. Paquete: Paquete Premium', 3483.88, '2025-09-29 08:00:00', 'finalizado', '2026-03-06 01:12:21', '2026-03-05 23:31:43'),
(22, 18, 2, 2, 'Traslado Nacional', 'Contrato generado automáticamente por Seed. Paquete: Traslado Nacional', 3055.62, '2026-03-06 10:00:00', 'pendiente', '2026-03-06 01:12:21', NULL),
(23, 19, 1, 2, 'Paquete Premium', 'Contrato generado automáticamente por Seed. Paquete: Paquete Premium', 4006.50, '2026-03-04 02:00:00', 'finalizado', '2026-03-06 01:12:21', '2026-03-05 23:31:43'),
(24, 20, 2, 2, 'Paquete Premium', 'Contrato generado automáticamente por Seed. Paquete: Paquete Premium', 1970.05, '2025-12-26 02:00:00', 'en_traslado', '2026-03-06 01:12:21', NULL),
(25, 21, 1, 2, 'Servicio de Cremacion', 'Contrato generado automáticamente por Seed. Paquete: Servicio de Cremación', 3589.48, '2025-09-22 12:00:00', 'finalizado', '2026-03-06 01:12:21', '2026-03-05 23:31:43'),
(26, 22, 1, 3, 'Servicio de Cremacion', 'Contrato generado automáticamente por Seed. Paquete: Servicio de Cremación', 1106.20, '2025-11-16 06:00:00', 'finalizado', '2026-03-06 02:21:01', '2026-03-05 23:31:43'),
(27, 23, 2, 3, 'Servicio de Cremacion', 'Contrato generado automáticamente por Seed. Paquete: Servicio de Cremación', 3161.18, '2026-02-23 17:00:00', 'pendiente', '2026-03-06 02:21:01', NULL),
(28, 24, 1, 3, 'Servicio de Cremacion', 'Contrato generado automáticamente por Seed. Paquete: Servicio de Cremación', 2382.28, '2025-11-01 05:00:00', 'finalizado', '2026-03-06 02:21:01', '2026-03-05 23:31:43'),
(29, 25, 2, 3, 'Servicio de Cremacion', 'Contrato generado automáticamente por Seed. Paquete: Servicio de Cremación', 1755.51, '2025-09-10 08:00:00', 'pendiente', '2026-03-06 02:21:01', NULL),
(30, 26, 1, 3, 'Paquete Estandar', 'Contrato generado automáticamente por Seed. Paquete: Paquete Estándar', 2868.16, '2025-11-08 21:00:00', 'finalizado', '2026-03-06 02:21:01', '2026-03-05 23:31:43'),
(31, 27, 2, 3, 'Servicio de Cremacion', 'Contrato generado automáticamente por Seed. Paquete: Servicio de Cremación', 2994.37, '2025-10-13 09:00:00', 'finalizado', '2026-03-06 02:21:01', '2026-03-05 23:31:43'),
(32, 28, 1, 3, 'Paquete Premium', 'Contrato generado automáticamente por Seed. Paquete: Paquete Premium', 1526.65, '2026-01-31 13:00:00', 'finalizado', '2026-03-06 02:21:01', '2026-03-05 23:31:43'),
(33, 29, 2, 3, 'Paquete Estandar', 'Contrato generado automáticamente por Seed. Paquete: Paquete Estándar', 2902.40, '2025-10-16 02:00:00', 'finalizado', '2026-03-06 02:21:01', '2026-03-05 23:31:43'),
(34, 30, 1, 3, 'Paquete Premium', 'Contrato generado automáticamente por Seed. Paquete: Paquete Premium', 4312.84, '2026-01-28 04:00:00', 'en_preparacion', '2026-03-06 02:21:01', NULL),
(35, 31, 2, 3, 'Servicio de Cremacion', 'Contrato generado automáticamente por Seed. Paquete: Servicio de Cremación', 1163.96, '2025-11-22 16:00:00', 'en_traslado', '2026-03-06 02:21:01', NULL),
(36, 32, 1, 3, 'Traslado Nacional', 'Contrato generado automáticamente por Seed. Paquete: Traslado Nacional', 2643.36, '2026-01-03 17:00:00', 'en_preparacion', '2026-03-06 02:21:01', NULL),
(37, 33, 2, 3, 'Servicio de Cremacion', 'Contrato generado automáticamente por Seed. Paquete: Servicio de Cremación', 3587.85, '2025-09-08 21:00:00', 'en_preparacion', '2026-03-06 02:21:01', NULL),
(38, 34, 1, 3, 'Traslado Nacional', 'Contrato generado automáticamente por Seed. Paquete: Traslado Nacional', 2803.45, '2026-01-13 05:00:00', 'finalizado', '2026-03-06 02:21:01', '2026-03-05 23:31:43'),
(39, 35, 2, 3, 'Paquete Basico', 'Contrato generado automáticamente por Seed. Paquete: Paquete Básico', 3145.91, '2026-01-30 17:00:00', 'en_traslado', '2026-03-06 02:21:01', NULL),
(40, 36, 1, 3, 'Paquete Estandar', 'Contrato generado automáticamente por Seed. Paquete: Paquete Estándar', 2643.22, '2025-12-01 12:00:00', 'finalizado', '2026-03-06 02:21:01', '2026-03-05 23:31:43'),
(41, 37, 2, 3, 'Traslado Nacional', 'Contrato generado automáticamente por Seed. Paquete: Traslado Nacional', 1063.31, '2026-03-02 21:00:00', 'finalizado', '2026-03-06 02:21:01', '2026-03-05 23:31:43'),
(42, 38, 1, 3, 'Servicio de Cremacion', 'Contrato generado automáticamente por Seed. Paquete: Servicio de Cremación', 4216.35, '2025-10-29 10:00:00', 'finalizado', '2026-03-06 02:21:01', '2026-03-05 23:31:43'),
(43, 39, 2, 3, 'Servicio de Cremacion', 'Contrato generado automáticamente por Seed. Paquete: Servicio de Cremación', 1970.35, '2026-01-19 00:00:00', 'pendiente', '2026-03-06 02:21:01', NULL),
(44, 40, 1, 3, 'Paquete Premium', 'Contrato generado automáticamente por Seed. Paquete: Paquete Premium', 1536.01, '2026-02-25 23:00:00', 'finalizado', '2026-03-06 02:21:01', '2026-03-05 23:31:43'),
(45, 41, 2, 3, 'Servicio de Cremacion', 'Contrato generado automáticamente por Seed. Paquete: Servicio de Cremación', 4163.58, '2026-01-09 18:00:00', 'en_traslado', '2026-03-06 02:21:01', NULL),
(46, 42, 1, 3, 'Cremacion', 'sede detalle', 180.00, '2026-03-06 05:02:22', 'pendiente', '2026-03-06 04:02:22', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('Gerente','Vendedor','Operario') NOT NULL,
  `sede_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `username`, `password`, `rol`, `sede_id`, `created_at`) VALUES
(1, 'Administrador Principal', 'admin', '$2y$10$EbQYEx6Q3CTFmh1ZhWCgze0Lzrq00MJm.jnXYSYdrgpwj/ILr/jFi', 'Gerente', NULL, '2026-03-06 02:12:53'),
(2, 'Gerente General Ilo', 'gerente_ilo', '$2y$10$XV9w1tMBKaU6o/MYylD7uu7iBmBvQreqvIwUHC8TWGsWGhKnSoMOq', 'Gerente', 1, '2026-03-06 02:12:53'),
(3, 'Vendedor Moquegua', 'vendedor_moq', '$2y$10$1uK4uoz0RIO33VC18DpR0O8dZzv1Ide9y17tUo61W3W5uB6wuvZeK', 'Vendedor', 2, '2026-03-06 02:12:53'),
(4, 'Operario Ilo', 'operario_ilo', '$2y$10$9mRzLFfIQozEXBWpdPb6DeiFmBaRAt9ym31Tmqy0PbIs6izZ3z/VW', 'Operario', 1, '2026-03-06 02:12:54'),
(5, 'Carlos Gerente', 'gerente1', '$2y$10$wNpw.rCjOhA3V7z9HToXjOovkG.0L6I7j/hB7vXm9tK74lU62Htxm', 'Gerente', 1, '2026-03-06 04:52:09'),
(6, 'Ana Vendedora', 'vendedor1', '$2y$10$wNpw.rCjOhA3V7z9HToXjOovkG.0L6I7j/hB7vXm9tK74lU62Htxm', 'Vendedor', 1, '2026-03-06 04:52:09'),
(7, 'Luis Operario', 'operario1', '$2y$10$wNpw.rCjOhA3V7z9HToXjOovkG.0L6I7j/hB7vXm9tK74lU62Htxm', 'Operario', 2, '2026-03-06 04:52:09');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `deudos`
--
ALTER TABLE `deudos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `difuntos`
--
ALTER TABLE `difuntos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deudo_id` (`deudo_id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sede_id` (`sede_id`);

--
-- Indices de la tabla `proformas`
--
ALTER TABLE `proformas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `sede_id` (`sede_id`);

--
-- Indices de la tabla `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `difunto_id` (`difunto_id`),
  ADD KEY `sede_id` (`sede_id`),
  ADD KEY `vendedor_id` (`vendedor_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `sede_id` (`sede_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `deudos`
--
ALTER TABLE `deudos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `difuntos`
--
ALTER TABLE `difuntos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `proformas`
--
ALTER TABLE `proformas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `difuntos`
--
ALTER TABLE `difuntos`
  ADD CONSTRAINT `difuntos_ibfk_1` FOREIGN KEY (`deudo_id`) REFERENCES `deudos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `proformas`
--
ALTER TABLE `proformas`
  ADD CONSTRAINT `proformas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `proformas_ibfk_2` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`difunto_id`) REFERENCES `difuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `servicios_ibfk_2` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`),
  ADD CONSTRAINT `servicios_ibfk_3` FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
