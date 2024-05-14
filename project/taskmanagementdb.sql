/*
SQLyog Community v13.1.8 (64 bit)
MySQL - 10.4.28-MariaDB : Database - taskmanagementdb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`taskmanagementdb` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `taskmanagementdb`;

/*Table structure for table `login` */

DROP TABLE IF EXISTS `login`;

CREATE TABLE `login` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `login` */

insert  into `login`(`user_id`,`username`,`password`) values 
(1,'Gina','12345'),
(2,'Taylor Swift','1989'),
(3,'James','1111'),
(13,'Adie','3333');

/*Table structure for table `tasks` */

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('normal','priority','done') NOT NULL DEFAULT 'normal',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tasks` */

insert  into `tasks`(`id`,`user_id`,`description`,`due_date`,`status`) values 
(3,1,'Task 1','2023-09-06','done'),
(4,1,'Task 2','2023-09-07','done'),
(6,1,'Task 3','2023-09-09','priority'),
(7,1,'Task 4','2023-09-10','priority'),
(8,1,'Task 5','2023-09-23','priority'),
(10,1,'Task 6','2023-10-01','normal'),
(23,1,'Task 7','2024-01-01','normal'),
(27,2,'Task 1','2023-09-01','done'),
(28,2,'Task 2','2023-09-02','done'),
(33,2,'Task 3','2023-09-08','priority'),
(41,1,'Task 8','2024-01-02','normal'),
(44,4,'Task 1','2023-09-09','normal'),
(46,7,'Task 1','2023-09-01','normal'),
(47,7,'Task 2','2023-09-03','normal'),
(49,2,'Task 4','2023-09-30','normal'),
(50,2,'Task 5','2023-10-02','normal'),
(52,2,'Task 6','2023-10-31','normal');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
