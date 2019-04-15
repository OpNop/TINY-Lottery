-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.3.14-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for tiny-lottery
CREATE DATABASE IF NOT EXISTS `tiny-lottery` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `tiny-lottery`;

-- Dumping structure for table tiny-lottery.lottery_log
CREATE TABLE IF NOT EXISTS `lottery_log` (
  `id` int(11) NOT NULL DEFAULT 0,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `user` varchar(50) NOT NULL DEFAULT '0',
  `coins` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=336 DEFAULT CHARSET=LATIN1;

-- Dumping data for table tiny-lottery.lottery_log: ~0 rows (approximately)
DELETE FROM `lottery_log`;
/*!40000 ALTER TABLE `lottery_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `lottery_log` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
