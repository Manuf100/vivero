-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 07:21 PM
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
-- Database: `vivero`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerPedidosPorUsuario` ()  DETERMINISTIC BEGIN
    SELECT usuarios.mail, COUNT(*) AS cantidad_pedidos, SUM(pedidos.total) AS total
    FROM usuarios 
    INNER JOIN pedidos 
    ON usuarios.id = pedidos.id_usuario
    GROUP BY usuarios.mail LIMIT 5;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerPedidosPorUsuario2` ()   BEGIN
    SELECT usuarios.mail, 
           COUNT(*) AS cantidad_pedidos, 
           SUM(pedidos.total) AS total
    FROM usuarios 
    INNER JOIN pedidos 
    ON usuarios.id = pedidos.id_usuario
    GROUP BY usuarios.mail LIMIT 5;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `nombre` longtext NOT NULL,
  `apellido` longtext NOT NULL,
  `email` longtext NOT NULL,
  `direccion` longtext NOT NULL,
  `numero` int(11) NOT NULL,
  `ciudad` mediumtext NOT NULL,
  `provincia` mediumtext NOT NULL,
  `pais` longtext NOT NULL,
  `codigoPostal` mediumtext NOT NULL,
  `medioPago` mediumtext NOT NULL,
  `comentarios` longtext DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `total` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id`, `nombre`, `apellido`, `email`, `direccion`, `numero`, `ciudad`, `provincia`, `pais`, `codigoPostal`, `medioPago`, `comentarios`, `fecha`, `total`, `id_usuario`) VALUES
(1, 'a', 'a', 'a@gmail.com', 'a', 2, 'a', 'a', 'a', 'a', 'Efectivo', '', '2024-08-14', 6000, 1),
(2, 'Manuel', 'FIGUEROA', 'a@gmail.com', 'a', 12, 'd', 'd', 'd', '2', 'Debito', 'dwq', '2023-12-01', 1600, 1),
(3, 'tester', '1', 'tester01mf@gmail.com', 'a', 45, 'Rosario', 'Santa Fe', 'Argentina', '450', 'Debito', '', '2023-10-03', 7300, 2),
(4, 'Manuel', 'FIGUEROA', 'a@gmail.com', 'a', 45, 'Rosario', 'Santa Fe', 'Argentina', '450', 'Debito', 'gracias', '2024-10-23', 17200, 1),
(5, 'Manuel', 'FIGUEROA', 'a@gmail.com', 'a', 12, 'Rosario', 'd', 'd', '23', 'Efectivo', '', '2024-10-23', 2600, 1),
(6, 'Tomas', 'Lopez', 'mmp20745@gmail.com', 'Buenos Aires', 1458, 'Salta', 'Salta', 'Argentina', '4400', 'Transferencia', '', '2023-11-23', 2600, 2);

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` longtext NOT NULL,
  `precio` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `descripcion` longtext NOT NULL,
  `img` longtext NOT NULL,
  `Categoria` mediumtext NOT NULL,
  `cant_vendida` int(11) NOT NULL DEFAULT 0,
  `total` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `cantidad`, `descripcion`, `img`, `Categoria`, `cant_vendida`, `total`) VALUES
(1, 'ALEGRÍAS DEL HOGAR', 800, 1, '\"UBICACIÓN: Exterior a media sombra.FLORACIÓN: Todo el año en lugares protegidos, con más intensidad en Primavera-Verano.OBSERVACIONES: Forma matas globosas. Se puede podar a fin del Invierno dejando tallos de 5 cm para que rebrote. Se comporta como perenne en lugares cálidos. No soporta heladas ni lugares ventosos. Cuidar del encharcamiento en el sustrato.\"', 'IMAGENES/Captura%20de%20pantalla%202024-05-17%20103704.jpg', 'Planta', 0, 0),
(2, 'LAVANDA DENTATA', 2400, 9, '\"UBICACIÓN: Exterior a pleno sol.\r\nFLORACIÓN: Todo el año, por ciclos. Flores en espigas perfumadas que atraen abejas.\r\nOBSERVACIONES: Arbusto bajo y muy ramificado con follaje verde grisáceo. Originaria de lugares secos, necesita sol (de 4 hs en adelante), y riego moderado. Tolera el viento. Podar luego de la floración un tercio de los tallos para que rebrote desde la base.\"', 'IMAGENES/Captura%20de%20pantalla%202024-05-19%20141616.png', 'Planta', 1, 2400),
(3, 'VIOLETA DE LOS ALPES', 5300, 9, 'UBICACIÓN: Exterior a media sombra. Cuanto más baja es la temperatura más resisten el sol pleno.', 'IMAGENES\\f3.png', 'Planta', 1, 5300),
(4, 'ERICA', 980, 10, 'UBICACIÓN: Exterior a pleno sol, o media sombra.\r\nFLORACIÓN: odo el año con más intensidad en Primavera-Verano. Pequeñas flores blancas o rosadas que atraen abejas.\r\nOBSERVACIONES: Forma matas bajas de follaje perenne. Tolera viento y riego moderado. Se puede podar a fin del Invierno para dar forma.', 'IMAGENES\\f4.png', 'Planta', 0, 0),
(5, 'CALÉNDULA', 790, 10, 'UBICACIÓN: Exterior a pleno sol.', 'IMAGENES\\f5.png', 'Planta', 0, 0),
(6, 'SALVIAS PARA COLIBRÍES', 2400, 10, 'UBICACIÓN: Exterior a pleno sol, o media sombra.', 'IMAGENES\\f6.png', 'Planta', 0, 0),
(7, 'ROSA MINIATURA', 3900, 10, 'UBICACIÓN: Exterior a pleno sol.', 'IMAGENES\\f7.png', 'Planta', 0, 0),
(101, 'SEMILLAS DE HIERBAS PARA GATOS Y PERROS 100g', 2600, 7, 'Semillas de pasto que aportan nutrientes a la alimentación de perros y gatos, ayudando a la función digestiva, y a la eliminación de pelos del aparato digestivo.', 'IMAGENES\\s1.png', 'Semilla', 1, 2600),
(102, 'Semillas para césped, resiembra otoño - invierno', 5900, 10, 'Presentación por 1 kg (rinde entre 30 y 40 m²)', 'IMAGENES\\s2.png', 'Semilla', 0, 0),
(103, 'SEMILLAS DE CÉSPED EXCEL PICASSO', 11000, 10, 'Presentación por 1 KG. Cubre aproximadamente 30 m².', 'IMAGENES\\s3.png', 'Semilla', 0, 0),
(201, 'COMÚN TERRACOTA (chico)', 2000, 9, 'Medida 27*40*40. (mm)', 'IMAGENES\\m1.png', 'Maceta', 1, 2000),
(202, 'COMÚN TERRACOTA (mediano)', 2000, 10, 'Medida 124*198*203  (mm).', 'IMAGENES\\m1.png', 'Maceta', 0, 0),
(203, 'COMÚN TERRACOTA (grande)', 2000, 10, 'Medida 189*297*300  (mm).', 'IMAGENES\\m1.png', 'Maceta', 0, 0),
(204, 'CONO TERRACOTA (chico)', 5500, 10, 'Medida 150*400*250  (mm)', 'IMAGENES\\M5.png', 'Maceta', 0, 0),
(205, 'CONO TERRACOTA (mediano)', 5500, 10, 'Medida 180*480*280  (mm)', 'IMAGENES\\M5.png', 'Maceta', 0, 0),
(206, 'CONO TERRACOTA (grande)', 5500, 10, 'Medida 210*560*330  (mm)', 'IMAGENES\\M5.png', 'Maceta', 0, 0),
(207, 'PLATOS REDONDOS DE TERRACOTA (chico)', 2500, 10, 'Medida 56*89 (mm)', 'IMAGENES\\m2.png', 'Maceta', 0, 0),
(208, 'PLATOS REDONDOS DE TERRACOTA (mediano)', 2500, 10, 'Medida 206*261  (mm).', 'IMAGENES\\m2.png', 'Maceta', 0, 0),
(209, 'PLATOS REDONDOS DE TERRACOTA (grande)', 2500, 10, 'Medida 235*300  (mm).', 'IMAGENES\\m2.png', 'Maceta', 0, 0),
(210, 'CUBO FIBROCEMENTO (chico)', 4500, 10, 'Medida 35*35*35 (mm). 16kg, 42l.', 'IMAGENES\\m3.png', 'Maceta', 0, 0),
(211, 'CUBO FIBROCEMENTO (mediano)', 4500, 10, 'Medida 20*20*20 (mm). 5kg, 8l.', 'IMAGENES\\m3.png', 'Maceta', 0, 0),
(212, 'CUBO FIBROCEMENTO (grande)', 4500, 10, 'Medida 60*60*60 (mm). 56kg, 216l.', 'IMAGENES\\m3.png', 'Maceta', 0, 0),
(213, 'MACETA PARA BARANDA TERRACOTA', 9900, 10, 'Alto:20 cm. Boca: 24,5 cm. Para barandas con un diámetrop máximo de 7 cm', 'IMAGENES\\m4.png', 'Maceta', 0, 0),
(301, 'PALA DE MANO ANCHA (METAL) TRAMONTINA', 4200, 8, 'Pala ancha con mango de madera, fabricada en acero carbono especial de alta calidad.', 'IMAGENES\\h1.png', 'Herramienta', 2, 8400),
(302, 'TIJERA DE PODA 8´', 15000, 10, 'Tijera de poda hecha en acero forjado.', 'IMAGENES\\h2.png', 'Herramienta', 0, 0),
(303, 'REGADERAS PLÁSTICAS VERDE', 4600, 10, 'Es un producto fuerte y práctico. Posee un diseño exclusivo, con pico rociador e incluye una flor de riego.', 'IMAGENES\\h3.png', 'Herramienta', 0, 0),
(304, 'CONECTOR PARA CANILLA (AQUAFLEX )', 2600, 9, 'Para canilla de 3/4\"', 'IMAGENES\\h4.png', 'Herramienta', 1, 2600),
(305, 'GUANTE CUERINA', 9700, 10, 'Par de Guantes de cuerina ideal para cactus.', 'IMAGENES\\h5.png', 'Herramienta', 0, 0),
(306, 'PALA PEQUEÑA DE PUNTA, 45 CM. TRAMONTINA', 16000, 10, 'Ideal para trabajos de jardinería en canteros y macetas.', 'IMAGENES\\h6.png', 'Herramienta', 0, 0),
(307, 'Rastrillo', 4300, 16, 'Escobilla Barrehojas. Muy bueno', 'IMAGENES\\h7.png', 'Herramienta', 4, 17200);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` mediumtext NOT NULL,
  `apellido` mediumtext NOT NULL,
  `tipo` mediumtext NOT NULL DEFAULT 'cliente',
  `contraseña` mediumtext NOT NULL,
  `mail` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `tipo`, `contraseña`, `mail`) VALUES
(1, 'Juan', 'Tevez', 'Dueño', '123123', 'juantevez@gmail.com'),
(2, 'Tomas', 'Lopez', 'cliente', '123123', 'tomaslopez@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_padre` (`id_usuario`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_id_padre` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
