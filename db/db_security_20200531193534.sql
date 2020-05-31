/*
SQLyog Community v12.1 (32 bit)
MySQL - 10.4.11-MariaDB-1:10.4.11+maria~bionic : Database - db_security
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_security` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `db_security`;

/*Table structure for table `app_ip` */

DROP TABLE IF EXISTS `app_ip`;

CREATE TABLE `app_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `insert_date` timestamp NULL DEFAULT current_timestamp(),
  `update_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `remote_ip` varchar(100) NOT NULL,
  `country` varchar(50) DEFAULT NULL,
  `whois` varchar(200) DEFAULT NULL COMMENT 'google, fb, etc',
  PRIMARY KEY (`id`),
  KEY `remote_ip` (`remote_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

/*Data for the table `app_ip` */

/*Table structure for table `app_ip_blacklist` */

DROP TABLE IF EXISTS `app_ip_blacklist`;

CREATE TABLE `app_ip_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `insert_date` timestamp NULL DEFAULT current_timestamp(),
  `update_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `remote_ip` varchar(100) NOT NULL,
  `reason` varchar(200) DEFAULT NULL,
  `visits_day` int(11) DEFAULT NULL,
  `is_blocked` tinyint(2) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `remote_ip` (`remote_ip`),
  KEY `remote_ip_2` (`remote_ip`,`is_blocked`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

/*Data for the table `app_ip_blacklist` */

/*Table structure for table `app_ip_request` */

DROP TABLE IF EXISTS `app_ip_request`;

CREATE TABLE `app_ip_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `insert_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `remote_ip` varchar(100) DEFAULT NULL,
  `domain` varchar(150) DEFAULT NULL,
  `request_uri` varchar(250) DEFAULT NULL,
  `get` varchar(2000) DEFAULT NULL,
  `post` varchar(2000) DEFAULT NULL,
  `files` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `remote_ip` (`remote_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

/*Data for the table `app_ip_request` */

/*Table structure for table `app_keywords` */

DROP TABLE IF EXISTS `app_keywords`;

CREATE TABLE `app_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `insert_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `word` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

/*Data for the table `app_keywords` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
