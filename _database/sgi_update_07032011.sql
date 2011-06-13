--
-- Base de datos: `sgi`
--

USE `sgi`;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `incidencias`
--

ALTER TABLE `incidencias` ADD `servicio_id` INT( 11 ) NOT NULL AFTER `usuario_id`;
ALTER TABLE `incidencias` ADD `tiempo` INT NULL;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modificaciones`
--

CREATE TABLE IF NOT EXISTS `modificaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `incidencia_id` longtext COLLATE utf8_spanish_ci NOT NULL,
  `status_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `comentario` longtext COLLATE utf8_spanish_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE IF NOT EXISTS `servicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;