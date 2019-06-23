-- MySQL dump 10.16  Distrib 10.3.9-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: serprent_cms
-- ------------------------------------------------------
-- Server version	10.3.9-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cards` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) unsigned NOT NULL,
  `code` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_deleted` int(11) unsigned NOT NULL DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `cards_un` (`code`),
  KEY `cards_clients_fk` (`client_id`),
  CONSTRAINT `cards_clients_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` enum('gym') NOT NULL DEFAULT 'gym',
  `city` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `zip` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_un` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(2) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=248 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `customer_addresses`
--

DROP TABLE IF EXISTS `customer_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_addresses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(11) unsigned NOT NULL,
  `name` varchar(128) NOT NULL DEFAULT '',
  `surname` varchar(128) DEFAULT NULL,
  `street` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL DEFAULT '',
  `zip_code` varchar(16) NOT NULL DEFAULT '',
  `business_name` varchar(128) DEFAULT NULL,
  `business_brn` varchar(10) DEFAULT NULL,
  `business_tax_id` varchar(15) DEFAULT NULL,
  `business_vat_id` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `entity` enum('person','business') NOT NULL DEFAULT 'person',
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`) USING BTREE,
  CONSTRAINT `customers_addresses_fk` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `delivery_types`
--

DROP TABLE IF EXISTS `delivery_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `delivery_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `email_description` text NOT NULL,
  `handle` varchar(32) NOT NULL DEFAULT '',
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order_products`
--

DROP TABLE IF EXISTS `order_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `product_variant_id` int(11) unsigned DEFAULT NULL,
  `quantity` int(11) unsigned NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `tax` decimal(4,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`) USING BTREE,
  KEY `product_id` (`product_id`) USING BTREE,
  KEY `product_variant_id` (`product_variant_id`) USING BTREE,
  CONSTRAINT `order_products_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_products_fk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `order_products_fk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_type_id` int(11) unsigned NOT NULL,
  `payment_type_id` int(11) unsigned DEFAULT NULL,
  `shipping_address_id` int(11) unsigned NOT NULL,
  `billing_address_id` int(11) unsigned NOT NULL,
  `status` enum('new','waiting_for_payment','completed','cancelled') NOT NULL DEFAULT 'new',
  `name` varchar(128) NOT NULL DEFAULT '''''',
  `surname` varchar(128) NOT NULL DEFAULT '''''',
  `email` varchar(320) NOT NULL DEFAULT '''''',
  `phone` varchar(20) NOT NULL DEFAULT '''''',
  `discount_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `delivery_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `product_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(8,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `variable_symbol` char(10) NOT NULL DEFAULT '',
  `is_thank_you_email_sent` tinyint(1) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `processed_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `billing_address_id` (`billing_address_id`) USING BTREE,
  KEY `delivery_type_id` (`delivery_type_id`) USING BTREE,
  KEY `payment_type_id` (`payment_type_id`) USING BTREE,
  KEY `shipping_address_id` (`shipping_address_id`) USING BTREE,
  CONSTRAINT `orders_fk` FOREIGN KEY (`delivery_type_id`) REFERENCES `delivery_types` (`id`),
  CONSTRAINT `orders_fk_1` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`),
  CONSTRAINT `orders_fk_2` FOREIGN KEY (`shipping_address_id`) REFERENCES `customer_addresses` (`id`),
  CONSTRAINT `orders_fk_3` FOREIGN KEY (`billing_address_id`) REFERENCES `customer_addresses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_types`
--

DROP TABLE IF EXISTS `payment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `handle` varchar(64) NOT NULL DEFAULT '',
  `service` varchar(32) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `handle` varchar(64) NOT NULL DEFAULT '',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_variants` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) unsigned NOT NULL,
  `storage_no` varchar(10) DEFAULT NULL,
  `title` varchar(128) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `main_photo_path` varchar(255) DEFAULT NULL,
  `preview_path` varchar(255) DEFAULT NULL,
  `tax` decimal(4,2) NOT NULL DEFAULT 20.00,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `code` varchar(16) NOT NULL DEFAULT '',
  `weight_in_grams` int(11) NOT NULL DEFAULT 0,
  `in_store_quantity` int(11) NOT NULL DEFAULT 0,
  `position` int(11) NOT NULL DEFAULT 1,
  `ean_code` varchar(128) DEFAULT NULL,
  `is_sold_out` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`) USING BTREE,
  CONSTRAINT `product_variants_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_category_id` int(11) unsigned NOT NULL,
  `title` varchar(128) NOT NULL DEFAULT '''''',
  `handle` varchar(128) NOT NULL,
  `position` int(11) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_hidden` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_category_id` (`product_category_id`) USING BTREE,
  CONSTRAINT `products_fk` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_activities`
--

DROP TABLE IF EXISTS `system_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_activities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_languages`
--

DROP TABLE IF EXISTS `system_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_languages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `code` char(5) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_logs`
--

DROP TABLE IF EXISTS `system_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_logs` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `activity_id` int(11) unsigned NOT NULL,
  `level` tinyint(4) unsigned NOT NULL DEFAULT 1,
  `additional_info` text DEFAULT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `user_session` varchar(32) DEFAULT NULL,
  `ip_address` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_activity` (`activity_id`) USING BTREE,
  KEY `id_activity_2` (`activity_id`,`additional_info`(255),`created_at`) USING BTREE,
  KEY `id_user` (`user_id`) USING BTREE,
  CONSTRAINT `system_logs_system_activities_fk` FOREIGN KEY (`activity_id`) REFERENCES `system_activities` (`id`),
  CONSTRAINT `system_logs_system_users_fk` FOREIGN KEY (`user_id`) REFERENCES `system_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=255 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_permissions`
--

DROP TABLE IF EXISTS `system_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `code` varchar(128) NOT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `code` (`code`) USING BTREE,
  KEY `code_is_visible` (`code`,`is_visible`) USING BTREE,
  KEY `id_code` (`id`,`code`) USING BTREE,
  KEY `id_code_is_visible` (`id`,`code`,`is_visible`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_roles`
--

DROP TABLE IF EXISTS `system_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_users`
--

DROP TABLE IF EXISTS `system_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `email` varchar(320) DEFAULT NULL,
  `username` varchar(320) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(200) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`) USING BTREE,
  KEY `language_id_2` (`language_id`) USING BTREE,
  KEY `system_users_system_roles_fk` (`role_id`),
  KEY `system_users_clients_fk` (`client_id`),
  CONSTRAINT `system_users_clients_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  CONSTRAINT `system_users_system_languages_fk` FOREIGN KEY (`language_id`) REFERENCES `system_languages` (`id`),
  CONSTRAINT `system_users_system_roles_fk` FOREIGN KEY (`role_id`) REFERENCES `system_roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_users_info`
--

DROP TABLE IF EXISTS `system_users_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_users_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `role_id` int(11) unsigned DEFAULT NULL,
  `permission_id` int(11) unsigned DEFAULT NULL,
  `value` varchar(75) NOT NULL DEFAULT '',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_permission` (`permission_id`) USING BTREE,
  KEY `id_role` (`role_id`) USING BTREE,
  KEY `id_role_permission` (`role_id`,`permission_id`) USING BTREE,
  KEY `id_role_permission_value` (`role_id`,`permission_id`,`value`) USING BTREE,
  KEY `id_user` (`user_id`) USING BTREE,
  KEY `id_user_permission_role` (`user_id`,`permission_id`,`role_id`) USING BTREE,
  KEY `id_user_role` (`user_id`,`role_id`) USING BTREE,
  KEY `id_user_role_permission_value` (`user_id`,`role_id`,`permission_id`,`value`) USING BTREE,
  KEY `id_user_role_value` (`user_id`,`role_id`,`value`) USING BTREE,
  CONSTRAINT `system_users_info_system_permissions_fk` FOREIGN KEY (`permission_id`) REFERENCES `system_permissions` (`id`),
  CONSTRAINT `system_users_info_system_roles_fk` FOREIGN KEY (`role_id`) REFERENCES `system_roles` (`id`),
  CONSTRAINT `system_users_info_system_users_fk` FOREIGN KEY (`user_id`) REFERENCES `system_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_users_tokens`
--

DROP TABLE IF EXISTS `system_users_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_users_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `token` char(64) DEFAULT NULL,
  `user_agent` varchar(256) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `ibfk_userd_idx` (`user_id`) USING BTREE,
  CONSTRAINT `system_users_tokens_system_users_fk` FOREIGN KEY (`user_id`) REFERENCES `system_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_cards`
--

DROP TABLE IF EXISTS `user_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_cards` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `card_id` int(11) unsigned NOT NULL,
  `client_id` int(11) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_cards_system_users_fk` (`user_id`),
  KEY `user_cards_cards_fk` (`card_id`),
  KEY `user_cards_clients_fk` (`client_id`),
  CONSTRAINT `user_cards_cards_fk` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`),
  CONSTRAINT `user_cards_clients_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  CONSTRAINT `user_cards_system_users_fk` FOREIGN KEY (`user_id`) REFERENCES `system_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'serprent_cms'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-23 19:49:30
