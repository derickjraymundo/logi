/*
SQLyog Community v13.2.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - logi
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`logi` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `logi`;

/*Table structure for table `clock_in_out` */

DROP TABLE IF EXISTS `clock_in_out`;

CREATE TABLE `clock_in_out` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `clock_in` datetime NOT NULL,
  `clock_out` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `clock_in_out_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `clock_in_out` */

insert  into `clock_in_out`(`id`,`user_id`,`clock_in`,`clock_out`) values 
(1,3,'2025-04-01 07:53:00','2025-04-01 16:23:00'),
(4,3,'2025-04-02 08:00:00','2025-04-02 17:23:00');

/*Table structure for table `schedule` */

DROP TABLE IF EXISTS `schedule`;

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `date_in` date NOT NULL,
  `time_in` time NOT NULL,
  `date_out` date NOT NULL,
  `time_out` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `schedule` */

insert  into `schedule`(`id`,`user_id`,`date_in`,`time_in`,`date_out`,`time_out`) values 
(1,3,'2025-04-01','08:00:00','2025-04-01','17:00:00'),
(2,3,'2025-04-02','08:00:00','2025-04-02','17:00:00'),
(3,3,'2025-04-03','08:00:00','2025-04-03','17:00:00');

/*Table structure for table `tbl_driver_book` */

DROP TABLE IF EXISTS `tbl_driver_book`;

CREATE TABLE `tbl_driver_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `driver_id` int(11) DEFAULT NULL,
  `booking_id` varchar(50) DEFAULT NULL,
  `froms` varchar(30) DEFAULT NULL,
  `froms_lat` varchar(100) DEFAULT NULL,
  `froms_long` varchar(100) DEFAULT NULL,
  `tos` text DEFAULT NULL,
  `tos_lat` varchar(100) DEFAULT NULL,
  `tos_long` varchar(100) DEFAULT NULL,
  `booking_date` datetime DEFAULT NULL,
  `booking_status` int(11) DEFAULT NULL COMMENT '0 =pending, 1= ongoing, 2= cancelled, 3 = done',
  `booking_done` datetime DEFAULT NULL,
  `booking_remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_driver_book` */

insert  into `tbl_driver_book`(`id`,`driver_id`,`booking_id`,`froms`,`froms_lat`,`froms_long`,`tos`,`tos_lat`,`tos_long`,`booking_date`,`booking_status`,`booking_done`,`booking_remarks`) values 
(1,3,'TR-D1DAB26B84','','14.734537','121.003539','Quirino Hwy, Novaliches, Quezon City, Metro Manila','14.674285','121.073338','2025-04-02 08:00:00',2,NULL,'test lang'),
(2,3,'TR-D1DAB26B85','','14.734537','121.003539','SM Marilao','14.674285','121.073338','2025-04-02 08:00:00',2,NULL,'test lang'),
(3,3,'TR-D1DAB26B86','','14.734537','121.003539','Mandaluyong','14.674285','121.073338','2025-04-02 08:00:00',3,NULL,'test lang'),
(4,3,'TR-D1DAB26B86','','14.734537','121.003539','Bayan Glori','14.674285','121.073338','2025-04-02 08:00:00',1,NULL,'test lang'),
(5,3,'TR-D1DAB26B87','','14.734537','121.003539','Mindanao Avenue','14.674285','121.073338','2025-04-02 08:00:00',0,NULL,'test lang');

/*Table structure for table `tbl_driver_location` */

DROP TABLE IF EXISTS `tbl_driver_location`;

CREATE TABLE `tbl_driver_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `driver_id` int(11) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `driver_id` (`driver_id`),
  CONSTRAINT `tbl_driver_location_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_driver_location` */

/*Table structure for table `tbl_fuel_monitoring` */

DROP TABLE IF EXISTS `tbl_fuel_monitoring`;

CREATE TABLE `tbl_fuel_monitoring` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(50) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `before_arrived` varchar(20) DEFAULT NULL,
  `after_arrived` varchar(20) DEFAULT NULL,
  `isDeleted` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_fuel_monitoring` */

insert  into `tbl_fuel_monitoring`(`id`,`transaction_id`,`driver_id`,`vehicle_id`,`transaction_date`,`before_arrived`,`after_arrived`,`isDeleted`) values 
(1,'TXN1743311351',3,2,'2025-03-30 08:00:00','22','23',0),
(2,'TXN1743311352',3,2,'2025-03-31 08:00:00','11','55',0),
(3,'TXN1743311353',3,2,'2025-04-01 08:00:00','12','12',0),
(4,'TXN1743311354',3,2,'2025-04-02 08:00:00','60','70',0),
(5,'TXN1743311355',3,2,'2025-04-03 08:00:00','23','11',0),
(6,'TXN1743311356',3,2,'2025-04-04 08:00:00','10','10',0),
(7,'TXN1743311357',3,2,'2025-04-05 08:00:00','10','10',0);

/*Table structure for table `tbl_helpers` */

DROP TABLE IF EXISTS `tbl_helpers`;

CREATE TABLE `tbl_helpers` (
  `booking_id` varchar(30) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_helpers` */

insert  into `tbl_helpers`(`booking_id`,`lastname`,`firstname`,`middlename`) values 
('1','PANGILINAN','KIKO','KIKOMIDLLE');

/*Table structure for table `tbl_items` */

DROP TABLE IF EXISTS `tbl_items`;

CREATE TABLE `tbl_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(60) DEFAULT NULL,
  `isDeleted` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_items` */

insert  into `tbl_items`(`id`,`item_name`,`isDeleted`) values 
(1,'Gulong',0),
(2,'Preno',0),
(3,'Headlights',0);

/*Table structure for table `tbl_request_items` */

DROP TABLE IF EXISTS `tbl_request_items`;

CREATE TABLE `tbl_request_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `tbl_request_items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `tbl_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tbl_request_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `tbl_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_request_items` */

insert  into `tbl_request_items`(`id`,`request_id`,`item_id`,`quantity`) values 
(1,1,1,5),
(2,1,2,10),
(3,1,3,20),
(4,2,1,55);

/*Table structure for table `tbl_requests` */

DROP TABLE IF EXISTS `tbl_requests`;

CREATE TABLE `tbl_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tbl_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_requests` */

insert  into `tbl_requests`(`id`,`transaction_id`,`user_id`,`request_date`) values 
(1,'REQ-20250401-00001',3,'2025-04-01 00:31:16'),
(2,'REQ-20250401-00002',3,'2025-04-01 00:31:33');

/*Table structure for table `tbl_schedule` */

DROP TABLE IF EXISTS `tbl_schedule`;

CREATE TABLE `tbl_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `driver_id` int(11) DEFAULT NULL,
  `schedule_date_in` date DEFAULT NULL,
  `schedule_time_in` time DEFAULT NULL,
  `schedule_date_out` date DEFAULT NULL,
  `schedule_time_out` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_schedule` */

/*Table structure for table `tbl_setup_gender` */

DROP TABLE IF EXISTS `tbl_setup_gender`;

CREATE TABLE `tbl_setup_gender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gender_name` varchar(6) DEFAULT NULL,
  `isDeleted` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_setup_gender` */

insert  into `tbl_setup_gender`(`id`,`gender_name`,`isDeleted`) values 
(1,'Male',0),
(2,'Female',0);

/*Table structure for table `tbl_setup_user_type` */

DROP TABLE IF EXISTS `tbl_setup_user_type`;

CREATE TABLE `tbl_setup_user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type_name` varchar(50) DEFAULT NULL,
  `isDeleted` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_setup_user_type` */

insert  into `tbl_setup_user_type`(`id`,`user_type_name`,`isDeleted`) values 
(1,'ADMIN',0),
(2,'MECHANIC',0),
(3,'DRIVER',0),
(4,'HELPER',0),
(5,'HR',0);

/*Table structure for table `tbl_setup_vehicle_manufacturers` */

DROP TABLE IF EXISTS `tbl_setup_vehicle_manufacturers`;

CREATE TABLE `tbl_setup_vehicle_manufacturers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturer_name` varchar(80) DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_date` datetime DEFAULT current_timestamp(),
  `isDeleted` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `manufacturer_name` (`manufacturer_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_setup_vehicle_manufacturers` */

insert  into `tbl_setup_vehicle_manufacturers`(`id`,`manufacturer_name`,`photo`,`added_by`,`added_date`,`isDeleted`) values 
(1,'TOYOTA','../images/manufacturer/item_67d134c3dbfbb.jpg',1,'2025-03-12 15:16:19',0),
(2,'VOLKSWAGEN','../images/manufacturer/item_67d13bb6741b2.png',1,'2025-03-12 15:45:58',0);

/*Table structure for table `tbl_setup_vehicle_parts` */

DROP TABLE IF EXISTS `tbl_setup_vehicle_parts`;

CREATE TABLE `tbl_setup_vehicle_parts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_parts_name` varchar(80) DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_date` datetime DEFAULT current_timestamp(),
  `isDeleted` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_setup_vehicle_parts` */

insert  into `tbl_setup_vehicle_parts`(`id`,`vehicle_parts_name`,`photo`,`added_by`,`added_date`,`isDeleted`) values 
(1,'ENGINE BLOCK','../images/vehicle_types/item_67d13b37a2d14.jpg',1,'2025-03-12 14:25:36',0),
(2,'CYLINDER HEADS','../images/vehicle_types/item_67d1290f00f33.jpg',1,'2025-03-12 14:26:23',0),
(3,'PISTONS','../images/vehicle_types/item_67d13b7857c55.png',1,'2025-03-12 15:44:56',0);

/*Table structure for table `tbl_setup_vehicle_types` */

DROP TABLE IF EXISTS `tbl_setup_vehicle_types`;

CREATE TABLE `tbl_setup_vehicle_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_type_name` varchar(50) DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_date` datetime DEFAULT current_timestamp(),
  `isDeleted` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicle_type_name` (`vehicle_type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_setup_vehicle_types` */

insert  into `tbl_setup_vehicle_types`(`id`,`vehicle_type_name`,`photo`,`added_by`,`added_date`,`isDeleted`) values 
(1,'COMMERCIAL VEHICLES','../images/vehicle_types/item_67d13a5e39db7.jpg',1,'2025-03-12 14:19:22',0),
(2,'PASSENGER VEHICLES','../images/vehicle_types/item_67d13a29c8ae4.jpg',1,'2025-03-12 14:19:32',0),
(3,'PUBLIC TRANSPORT VEHICLES','../images/vehicle_types/item_67d13a84cc89e.jpg',1,'2025-03-12 15:40:52',0),
(4,'TWO-WHEELERS & THREE-WHEELERS','../images/vehicle_types/item_67d13abb491a3.jpg',1,'2025-03-12 15:41:47',0),
(5,'OFF-ROAD & SPECIALTY VEHICLES','../images/vehicle_types/item_67d13add93482.jpg',1,'2025-03-12 15:42:21',0),
(6,'EMERGENCY & SERVICE VEHICLES','../images/vehicle_types/item_67d13afb7996e.jpg',1,'2025-03-12 15:42:51',0);

/*Table structure for table `tbl_setup_vendor_type` */

DROP TABLE IF EXISTS `tbl_setup_vendor_type`;

CREATE TABLE `tbl_setup_vendor_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_type_name` varchar(40) DEFAULT NULL,
  `isDeleted` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_setup_vendor_type` */

insert  into `tbl_setup_vendor_type`(`id`,`vendor_type_name`,`isDeleted`) values 
(1,'VENDOR TYPE 1',0),
(2,'VENDOR TYPE 2',0),
(3,'VENDOR TYPE 3',0);

/*Table structure for table `tbl_setup_vendors` */

DROP TABLE IF EXISTS `tbl_setup_vendors`;

CREATE TABLE `tbl_setup_vendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(80) DEFAULT NULL,
  `vendor_type` int(11) DEFAULT NULL,
  `organization` varchar(50) DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_date` datetime DEFAULT current_timestamp(),
  `isDeleted` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendor_name` (`vendor_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_setup_vendors` */

insert  into `tbl_setup_vendors`(`id`,`vendor_name`,`vendor_type`,`organization`,`photo`,`added_by`,`added_date`,`isDeleted`) values 
(1,'MY VENDOR NAME',2,'POGI ORGANIZATIONS','../images/vendor/item_67d53ed332a34.jpg',1,'2025-03-15 16:48:19',0);

/*Table structure for table `tbl_users` */

DROP TABLE IF EXISTS `tbl_users`;

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alternative_id` varchar(20) DEFAULT NULL,
  `drivers_id` varchar(30) DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `license_expire_date` date DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `vehicle_type_id` int(11) DEFAULT NULL,
  `vehicle_plate_number` varchar(30) DEFAULT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `user_photo` text DEFAULT NULL,
  `user_type_id` int(11) NOT NULL,
  `email_address` varchar(120) NOT NULL,
  `gender_id` int(10) NOT NULL,
  `password` char(128) DEFAULT NULL,
  `isDeleted` int(11) NOT NULL DEFAULT 0,
  `added_date` datetime DEFAULT current_timestamp(),
  `added_by` int(11) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `rider_availability` int(11) DEFAULT 1 COMMENT '1 = available, 0 = unavailable',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_address` (`email_address`),
  KEY `user_type_id` (`user_type_id`),
  KEY `gender_id` (`gender_id`),
  KEY `added_by` (`added_by`),
  CONSTRAINT `tbl_users_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `tbl_setup_user_type` (`id`),
  CONSTRAINT `tbl_users_ibfk_2` FOREIGN KEY (`gender_id`) REFERENCES `tbl_setup_gender` (`id`),
  CONSTRAINT `tbl_users_ibfk_4` FOREIGN KEY (`added_by`) REFERENCES `tbl_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_users` */

insert  into `tbl_users`(`id`,`alternative_id`,`drivers_id`,`license_number`,`license_expire_date`,`contact_number`,`address`,`dob`,`vehicle_type_id`,`vehicle_plate_number`,`suffix`,`lastname`,`firstname`,`middlename`,`user_photo`,`user_type_id`,`email_address`,`gender_id`,`password`,`isDeleted`,`added_date`,`added_by`,`reset_token`,`reset_token_expires`,`rider_availability`) values 
(1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','DANTES','DINGDONG','ADMIN','../images/user_photo/1740129780_Jose-Sixto-Raphael-Gonzalez-Dantes.webp',1,'ding@gmail.com',1,'$2y$10$sPbWGFGsQNmuQ/ftcTP4xuilMOQ3TDgkgWRX.ulVjkBeDks7JdYBa',0,'2024-06-27 22:36:04',NULL,NULL,NULL,1),
(2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'MR','ALCASID','OGIE','CASHIER','../images/user_photo/item_67b847188d0ba.jpg',2,'ogie@gmail.com',1,'$2y$10$SkGG1IwmGg2BOdxfJpwmveWoIN5MhEbSDb.iy.EOxInr0QvR5M/mm',0,'2024-09-22 12:37:30',NULL,NULL,NULL,1),
(3,NULL,'3435-34523-3153-3333','4215-3444-4315-2222','1995-03-26','09098374331','711-2880 Nulla St.\r\nMankato Mississippi 96522\r\n(257) 563-7401',NULL,1,'4444-5555-6666-7777','MS','LOCSIN','ANGEL',NULL,'../images/user_photo/item_67d68f0334e80.jpg',3,'angel@gmail.com',2,'$2y$10$v7AWEqOqokq/zX.CtveUd.stxibmJF1W6rrmGWJf8iN2z7c1g77/u',0,'2025-01-29 05:31:22',1,NULL,NULL,0),
(4,'ST-639384','4342-25252-2325-2424',NULL,NULL,'09485756221',NULL,NULL,NULL,NULL,'MR','SOTTO','VIC',NULL,'../images/user_photo/item_67bf21322aed4.jpg',3,'moritech26@gmail.com',2,'$2y$10$v7AWEqOqokq/zX.CtveUd.stxibmJF1W6rrmGWJf8iN2z7c1g77/u',0,'2025-01-29 05:31:22',1,NULL,NULL,1),
(8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'PANGILINAN','KIKO','KIKOMIDLLE',NULL,4,'kiko@gmail.com',1,NULL,0,'2025-03-30 16:17:06',1,NULL,NULL,1),
(9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'BAYOLA','WALLY','WALLYMIDDLE',NULL,4,'wally@gmail.com',1,NULL,0,'2025-03-30 16:17:49',1,NULL,NULL,1),
(10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'VELASQUEZ','REGINE',NULL,NULL,5,'regine@gmail.com',2,'$2y$10$sPbWGFGsQNmuQ/ftcTP4xuilMOQ3TDgkgWRX.ulVjkBeDks7JdYBa',0,'2025-04-03 22:44:38',1,NULL,NULL,0);

/*Table structure for table `tbl_v_vehicles` */

DROP TABLE IF EXISTS `tbl_v_vehicles`;

CREATE TABLE `tbl_v_vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_name` varchar(90) DEFAULT NULL,
  `vehicle_model` varchar(60) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_v_vehicles` */

/*Table structure for table `tbl_v_vehicles_parts` */

DROP TABLE IF EXISTS `tbl_v_vehicles_parts`;

CREATE TABLE `tbl_v_vehicles_parts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(11) DEFAULT NULL,
  `vehicle_parts_id` int(11) DEFAULT NULL,
  `vehicle_parts_lifespan` int(11) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicle_id` (`vehicle_id`,`vehicle_parts_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_v_vehicles_parts` */

insert  into `tbl_v_vehicles_parts`(`id`,`vehicle_id`,`vehicle_parts_id`,`vehicle_parts_lifespan`,`added_by`,`added_date`) values 
(1,2,1,1,1,'2025-03-15 15:50:32'),
(2,2,2,7,1,'2025-03-15 16:05:24'),
(3,1,3,1,1,'2025-03-15 16:22:50');

/*Table structure for table `tbl_vehicle_requests` */

DROP TABLE IF EXISTS `tbl_vehicle_requests`;

CREATE TABLE `tbl_vehicle_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` varchar(50) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `date_requested` date NOT NULL,
  `time_requested` time NOT NULL,
  `remarks` text DEFAULT NULL,
  `requested_by` int(11) NOT NULL,
  `request_status` enum('Pending','Approved','Cancelled','Delievered','Reserved') DEFAULT 'Pending',
  `requested_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `delievered_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_id` (`request_id`),
  KEY `vehicle_id` (`vehicle_id`),
  KEY `tbl_vehicle_requests_ibfk_2` (`requested_by`),
  CONSTRAINT `tbl_vehicle_requests_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  CONSTRAINT `tbl_vehicle_requests_ibfk_2` FOREIGN KEY (`requested_by`) REFERENCES `tbl_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_vehicle_requests` */

insert  into `tbl_vehicle_requests`(`id`,`request_id`,`vehicle_id`,`date_requested`,`time_requested`,`remarks`,`requested_by`,`request_status`,`requested_date`,`delievered_date`) values 
(1,'REQ_9248429',1,'2025-03-15','12:00:00','TEST LANG TO',1,'Delievered','2025-03-15 17:35:12','2025-03-23 11:45:43'),
(2,'REQ_3328382',1,'2025-03-15','12:00:00','TEST LANG TO',1,'Delievered','2025-03-15 17:35:12','2025-03-23 11:45:43'),
(4,'REQ_3352362',1,'2025-03-15','12:00:00','TEST LANG TO',1,'Delievered','2025-03-15 17:35:12','2025-03-22 11:45:43'),
(6,'REQ_3555662',1,'2025-03-15','12:00:00','TEST LANG TO',1,'Delievered','2025-03-15 17:35:12','2025-03-21 11:48:43'),
(7,'REQ_664366',1,'2025-03-15','12:00:00','TEST LANG TO',1,'Approved','2025-03-15 17:35:12','2025-03-21 11:48:43'),
(10,'REQ_754735',2,'2025-03-15','12:00:00','TEST LANG TO',1,'Reserved','2025-03-15 17:35:12','2025-03-21 11:48:43');

/*Table structure for table `tbl_vehicle_rollouts` */

DROP TABLE IF EXISTS `tbl_vehicle_rollouts`;

CREATE TABLE `tbl_vehicle_rollouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `requested_by` varchar(80) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `date_vehicle_needed` date DEFAULT NULL,
  `time_vehicle_needed` time DEFAULT NULL,
  `requested_date` datetime DEFAULT current_timestamp(),
  `admin_vehicle_type` int(11) DEFAULT NULL,
  `admin_vehicle_id` int(11) DEFAULT NULL,
  `admin_remarks` text DEFAULT NULL,
  `admin_replied_date` datetime DEFAULT NULL,
  `status` enum('Cancelled','Pending','Assigned') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_vehicle_rollouts` */

insert  into `tbl_vehicle_rollouts`(`id`,`requested_by`,`purpose`,`date_vehicle_needed`,`time_vehicle_needed`,`requested_date`,`admin_vehicle_type`,`admin_vehicle_id`,`admin_remarks`,`admin_replied_date`,`status`) values 
(1,'Jessica Soho','for testing only',NULL,NULL,'2025-04-03 23:43:09',2,2,'test1','2025-04-03 23:43:42','Assigned'),
(2,'Jessica Soho','test lang po itu','2025-04-04','09:00:00','2025-04-03 23:56:20',NULL,NULL,NULL,NULL,'Cancelled');

/*Table structure for table `tbl_warehouse_inventory` */

DROP TABLE IF EXISTS `tbl_warehouse_inventory`;

CREATE TABLE `tbl_warehouse_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `item_remaining_stock` int(11) DEFAULT NULL,
  `isDeleted` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_warehouse_inventory` */

insert  into `tbl_warehouse_inventory`(`id`,`item_id`,`item_remaining_stock`,`isDeleted`) values 
(1,1,40,0),
(2,2,90,0),
(3,3,80,0),
(4,4,100,0),
(5,5,100,0);

/*Table structure for table `vehicles` */

DROP TABLE IF EXISTS `vehicles`;

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `make` int(11) NOT NULL,
  `model` varchar(100) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `year` int(11) NOT NULL,
  `vehicle_type` varchar(50) DEFAULT NULL,
  `vin` varchar(50) DEFAULT NULL,
  `license_plate` varchar(50) DEFAULT NULL,
  `chassis_number` varchar(50) DEFAULT NULL,
  `engine_number` varchar(50) DEFAULT NULL,
  `fuel_type` enum('Gasoline','Diesel','Electric','Hybrid') DEFAULT NULL,
  `transmission` enum('Manual','Automatic','CVT') DEFAULT NULL,
  `engine_capacity` varchar(20) DEFAULT NULL,
  `horsepower` int(11) DEFAULT NULL,
  `torque` int(11) DEFAULT NULL,
  `drivetrain` enum('FWD','RWD','AWD','4WD') DEFAULT NULL,
  `body_type` varchar(50) DEFAULT NULL,
  `exterior_color` varchar(50) DEFAULT NULL,
  `interior_color` varchar(50) DEFAULT NULL,
  `number_of_doors` int(11) DEFAULT NULL,
  `number_of_seats` int(11) DEFAULT NULL,
  `airbags` int(11) DEFAULT NULL,
  `abs` tinyint(1) DEFAULT NULL,
  `traction_control` tinyint(1) DEFAULT NULL,
  `parking_sensors` tinyint(1) DEFAULT NULL,
  `rearview_camera` tinyint(1) DEFAULT NULL,
  `security_alarm` tinyint(1) DEFAULT NULL,
  `registration_expiry` date DEFAULT NULL,
  `insurance_provider` varchar(100) DEFAULT NULL,
  `insurance_expiry` date DEFAULT NULL,
  `mileage` int(11) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `photos` text DEFAULT NULL,
  `vehicle_lifespan` int(11) DEFAULT NULL,
  `isActive` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vin` (`vin`),
  UNIQUE KEY `license_plate` (`license_plate`),
  UNIQUE KEY `chassis_number` (`chassis_number`),
  UNIQUE KEY `engine_number` (`engine_number`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `vehicles` */

insert  into `vehicles`(`id`,`make`,`model`,`driver_id`,`year`,`vehicle_type`,`vin`,`license_plate`,`chassis_number`,`engine_number`,`fuel_type`,`transmission`,`engine_capacity`,`horsepower`,`torque`,`drivetrain`,`body_type`,`exterior_color`,`interior_color`,`number_of_doors`,`number_of_seats`,`airbags`,`abs`,`traction_control`,`parking_sensors`,`rearview_camera`,`security_alarm`,`registration_expiry`,`insurance_provider`,`insurance_expiry`,`mileage`,`photo`,`added_by`,`created_at`,`photos`,`vehicle_lifespan`,`isActive`) values 
(1,2,'TY-394842',3,1996,'1',NULL,'043948-49928-24924821',NULL,NULL,'Gasoline','Manual','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,1,'2025-03-12 15:47:14','{\"2\":\"vehicle_67d1445984779.jpeg\"}',1,1),
(2,2,'VOKS3215',3,1993,'5',NULL,'999999999999999',NULL,NULL,'Electric','Automatic','38',NULL,NULL,NULL,'TEST',NULL,NULL,4,7,NULL,0,0,NULL,NULL,NULL,NULL,'TEST',NULL,NULL,NULL,1,'2025-03-15 15:11:46','[\"vehicle_67d5283280f79.jpeg\",\"vehicle_67d528328168e.jpeg\"]',5,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
