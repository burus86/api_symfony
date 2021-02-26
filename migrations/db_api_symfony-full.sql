-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.14-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Volcando estructura de base de datos para db_api_symfony
CREATE DATABASE IF NOT EXISTS `db_api_symfony` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `db_api_symfony`;

-- Volcando estructura para tabla db_api_symfony.category
DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_64C19C15E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_api_symfony.category: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` (`id`, `name`, `description`, `createdAt`) VALUES
	(1, 'Ordenadores portátiles', 'Categoría correspondiente a ordenadores portátiles', '2021-02-07 17:00:00'),
	(2, 'Ordenadores de sobremesa', NULL, '2021-02-07 17:00:00'),
	(3, 'Monitores', 'Categoría correspondiente a monitores', '2021-02-07 17:00:00');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

-- Volcando estructura para tabla db_api_symfony.product
DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EUR',
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `serial_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D34A04ADD948EE2` (`serial_number`),
  UNIQUE KEY `UNIQ_D34A04AD5E237E0612469DE2` (`name`,`category_id`),
  KEY `IDX_D34A04AD12469DE2` (`category_id`),
  CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_api_symfony.product: ~12 rows (aproximadamente)
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` (`id`, `name`, `category_id`, `price`, `currency`, `featured`, `serial_number`, `brand`, `createdAt`) VALUES
	(1, 'Portátil de 700€', 1, 700.00, 'EUR', 1, 'PF1LNYD595', 'Lenovo', '2021-02-07 17:00:00'),
	(2, 'Portátil de 650€', 1, 650.00, 'EUR', 0, 'PF1LNYE15', 'Lenovo', '2021-02-07 17:00:00'),
	(3, 'Portátil de 795$', 1, 795.00, 'USD', 1, NULL, 'Asus', '2021-02-07 17:00:00'),
	(4, 'Portátil de 690$', 1, 690.00, 'USD', 0, NULL, 'Toshiba', '2021-02-07 17:00:00'),
	(5, 'Ordenador de 449€', 2, 449.00, 'EUR', 1, '76534004E91705', 'Acer', '2021-02-07 17:00:00'),
	(6, 'Ordenador de 830€', 2, 830.00, 'EUR', 0, NULL, 'HP', '2021-02-07 17:00:00'),
	(7, 'IMAC DE 2575$', 2, 2575.00, 'USD', 1, 'C02Z37KVJV2R', 'Apple', '2021-02-07 17:00:00'),
	(8, 'IMAC DE 1800$', 2, 1800.00, 'USD', 0, NULL, 'Apple', '2021-02-07 17:00:00'),
	(9, 'Monitor de 89€', 3, 89.00, 'EUR', 1, NULL, 'Benq', '2021-02-07 17:00:00'),
	(10, 'Monitor de 117€', 3, 117.00, 'EUR', 0, NULL, 'Lenovo', '2021-02-07 17:00:00'),
	(11, 'Monitor de 140$', 3, 140.00, 'USD', 1, NULL, 'AOC', '2021-02-07 17:00:00'),
	(12, 'Monitor de 129$', 3, 129.00, 'USD', 0, NULL, 'HP', '2021-02-07 17:00:00');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
