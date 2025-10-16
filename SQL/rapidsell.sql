-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-10-2025 a las 19:26:51
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
-- Base de datos: `rapidsell`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id` int(11) NOT NULL,
  `id_compra` varchar(50) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `fecha_compra` text NOT NULL,
  `nombre` text NOT NULL,
  `cantidad_producto` int(11) NOT NULL,
  `ganancia_unidad_vendida` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id`, `id_compra`, `id_producto`, `fecha_compra`, `nombre`, `cantidad_producto`, `ganancia_unidad_vendida`) VALUES
(5, '487093626700144635', 12, '19/09/2025', 'Oliver', 1, 1.14),
(6, '892936016565155546', 12, '23/09/2025', '', 2, 1.14),
(7, '413380718262040411', 12, '23/09/2025', 'Samuel', 1, 1.14),
(8, '835462522829262098', 12, '23/09/2025', '', 3, 1.14),
(9, '835462522829262098', 14, '23/09/2025', '', 1, 0),
(10, '25158389821462913', 14, '23/09/2025', '', 1, 0),
(11, '25158389821462913', 14, '23/09/2025', '', 1, 0),
(12, '107582712345192798', 14, '26/09/2025', '', 1, 0),
(13, '463036678342856527', 14, '26/09/2025', '', 3, 0),
(14, '463036678342856527', 16, '26/09/2025', '', 1, 0.37),
(15, '819451552586346692', 16, '26/09/2025', 'Oliver', 5, 0.37),
(16, '689362967178316995', 12, '29/09/2025', '', 3, 1.14),
(17, '689362967178316995', 15, '29/09/2025', '', 3, 0.11),
(18, '258771708841773486', 15, '14/10/2025', 'Oliver', 1, 0.11),
(19, '715517031336038952', 16, '14/10/2025', 'Oliver', 4, 0.37),
(20, '990984200348600035', 16, '14/10/2025', '', 1, 0.37),
(21, '990984200348600035', 16, '14/10/2025', '', 8, 0.37),
(22, '583177972612393239', 21, '14/10/2025', '', 10, 0.23),
(23, '583177972612393239', 21, '14/10/2025', '', 300, 0.23),
(25, 'AVANCE-68ef9cd8dae45', 28, '15/10/2025', 'Avance', 0, 0),
(26, 'AVANCE-68ef9d7652a1b', 28, '15/10/2025', 'Avance', 0, 0),
(27, 'AVANCE-68ef9d9b64fe9', 28, '15/10/2025', 'Avance', 0, 0),
(28, 'AVANCE-68efa042d14ec', 28, '15/10/2025', 'Avance', 0, 0),
(29, 'AJUSTE-68efa042d1b32', 28, '15/10/2025', '0', 0, 0),
(30, '370140515352801010', 12, '15/10/2025', '', 1, 1.14),
(31, 'AVANCE-68efb5dd08d1d', 28, '15/10/2025', 'Avance', 0, 0),
(32, 'AJUSTE-68efb5dd0bfb3', 28, '15/10/2025', '0', 0, 0),
(33, '303745054716524556', 22, '15/10/2025', '', 5, 0.01),
(34, '303745054716524556', 12, '15/10/2025', '', 2, 1.14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credito`
--

CREATE TABLE `credito` (
  `id_credito` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `estatu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `credito`
--

INSERT INTO `credito` (`id_credito`, `id_venta`, `estatu`) VALUES
(5, 5, '0'),
(6, 7, '0'),
(7, 12, '0'),
(8, 14, '0'),
(9, 15, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `cerial` varchar(20) NOT NULL,
  `nombre_producto` text NOT NULL,
  `precio` varchar(10) NOT NULL,
  `precio_bs` varchar(10) NOT NULL,
  `ganancia` float NOT NULL,
  `stock` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `cerial`, `nombre_producto`, `precio`, `precio_bs`, `ganancia`, `stock`, `estatus`) VALUES
(10, '012546011495', 'Trident', '2.8125', '703.13', 26.25, 36, 0),
(11, '012546011495', 'Trident', '2.81', '702.50', 43.6, 60, 0),
(12, '012546011495', 'Trident', '2.85', '712.50', 26.16, 23, 1),
(13, '5555', 'prueba', '1.04', '260.00', 7.2, 30, 1),
(14, '5555', 'Doritos', '0.69', '172.50', 0.9, 0, 1),
(15, '0', 'Cafe', '0.39', '97.50', 1.8, 16, 1),
(16, '00', 'Cafe Amanecer', '0.47', '117.50', 4.1, 11, 1),
(21, '11111', 'chupetas', '0.31', '77.50', 66, 290, 1),
(22, '001', 'Caramelos Tamarindo', '0.03', '7.50', 0.8, 95, 1),
(23, '001', 'Caramelos Tamarindo', '0.55', '137.50', 1.8, 100, 1),
(24, '1', 'jhgh', '1.01', '252.50', 0.01, 1, 1),
(25, '2', '22', '0.09', '22.50', -0.02, 22, 1),
(26, '3', '33', '1.03', '257.50', 0.27, 9, 1),
(27, '4', '44', '1.04', '260.00', 0.64, 16, 1),
(28, 'AVANCE', 'AVANCE', '0', '0', 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_ventas` int(11) NOT NULL,
  `id_compra` varchar(50) NOT NULL,
  `total_pagar` text NOT NULL,
  `total_pagar_bs` text NOT NULL,
  `metodo_pago` text NOT NULL,
  `fecha` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_ventas`, `id_compra`, `total_pagar`, `total_pagar_bs`, `metodo_pago`, `fecha`) VALUES
(5, '487093626700144635', '2.85', '473.1', 'Credito', '19/09/2025'),
(6, '892936016565155546', '5.7', '946.2', 'Punto', '23/09/2025'),
(7, '413380718262040411', '2.85', '473.1', 'Credito', '23/09/2025'),
(8, '835462522829262098', '9.24', '1848', 'Dolares', '23/09/2025'),
(9, '25158389821462913', '1.38', '276', 'BS', '23/09/2025'),
(10, '107582712345192798', '0.69', '138', 'Punto', '26/09/2025'),
(11, '463036678342856527', '2.54', '508', 'Punto', '26/09/2025'),
(12, '819451552586346692', '2.35', '470', 'Credito', '26/09/2025'),
(13, '689362967178316995', '9.72', '2138.4', 'Punto', '29/09/2025'),
(14, '258771708841773486', '0.39', '70.2', 'Credito', '14/10/2025'),
(15, '715517031336038952', '1.88', '338.4', 'Credito', '14/10/2025'),
(16, '990984200348600035', '4.23', '761.4', 'BS', '14/10/2025'),
(17, '583177972612393239', '96.1', '17298', 'Pago Movil', '14/10/2025'),
(35, 'AVANCE-68ef9cd8dae45', '0.48', '120', 'BS', '15/10/2025'),
(36, 'AVANCE-68ef9d7652a1b', '0.96', '240', 'Pago Movil', '15/10/2025'),
(37, 'AVANCE-68ef9d9b64fe9', '0.96', '240', 'Pago Movil', '15/10/2025'),
(38, 'AVANCE-68efa042d14ec', '0.42', '105', 'Pago Movil', '15/10/2025'),
(39, 'AJUSTE-68efa042d1b32', '-0.42', '-105', 'BS', '15/10/2025'),
(40, '370140515352801010', '2.85', '712.5', 'Punto', '15/10/2025'),
(41, 'AVANCE-68efb5dd08d1d', '0.48', '120', 'Pago Movil', '15/10/2025'),
(42, 'AJUSTE-68efb5dd0bfb3', '-0.48', '-120', 'BS', '15/10/2025'),
(43, '303745054716524556', '5.85', '1462.5', 'Punto', '15/10/2025');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_compra` (`id_compra`);

--
-- Indices de la tabla `credito`
--
ALTER TABLE `credito`
  ADD PRIMARY KEY (`id_credito`),
  ADD KEY `id_venta` (`id_venta`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_ventas`),
  ADD KEY `id_compra` (`id_compra`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `credito`
--
ALTER TABLE `credito`
  MODIFY `id_credito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_ventas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `credito`
--
ALTER TABLE `credito`
  ADD CONSTRAINT `credito_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_ventas`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
