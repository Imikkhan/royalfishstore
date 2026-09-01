-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: royalfishstore
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `name` longtext NOT NULL,
  `type` longtext NOT NULL DEFAULT 'Home',
  `address_line` longtext NOT NULL,
  `city` longtext NOT NULL,
  `zip_code` longtext NOT NULL,
  `phone` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` longtext NOT NULL,
  `value` longtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` longtext NOT NULL,
  `owner` longtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) DEFAULT NULL,
  `name` longtext NOT NULL,
  `slug` longtext NOT NULL,
  `icon` longtext DEFAULT NULL,
  `image` longtext DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `is_active` bigint(20) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,'Fish & Seafood','fish-seafood','­ƒÉƒ','https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80','Fresh Catch',1,'2026-07-19 05:41:58','2026-07-21 11:31:31',NULL),(2,NULL,'Fresh Chicken','chicken','­ƒìù','https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=500&q=80','Tender Cuts',1,'2026-07-19 05:41:58','2026-07-21 11:31:31',NULL),(3,NULL,'Rich Mutton','mutton','­ƒÑ®','https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=500&q=80','Premium Goat',1,'2026-07-19 05:41:58','2026-07-21 11:31:31',NULL),(4,NULL,'Ready to Cook','marinades','­ƒìó','https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=500&q=80','Easy Marinades',1,'2026-07-19 05:41:58','2026-07-21 11:31:31',NULL),(5,NULL,'Cold Cuts','cold-cuts','­ƒÑô','https://images.unsplash.com/photo-1629450646452-278271dbde1d?auto=format&fit=crop&w=500&q=80','Salamis & Sausages',1,'2026-07-19 05:41:58','2026-07-21 11:31:31',NULL),(6,NULL,'Super Combos','combos','­ƒì▒','https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80','Value Packs',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(7,1,'Seawater Fish','seawater-fish','­ƒîè',NULL,'Fresh marine selection',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(8,1,'Freshwater Fish','freshwater-fish','­ƒÅ×´©Å',NULL,'Sweet river catch',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(9,1,'Prawns','prawns','­ƒªÉ',NULL,'Cleaned tiger prawns',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(10,1,'Exotic Catch','exotic-catch','­ƒÉÖ',NULL,'Norwegian Salmon etc',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(11,2,'Curry Cuts','chicken-curry-cuts','­ƒìù',NULL,'Standard curry cuts',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(12,2,'Boneless & Mince','chicken-boneless-mince','­ƒÑ®',NULL,'Breasts and Keema',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(13,3,'Curry Cuts','mutton-curry-cuts','­ƒìû',NULL,'Standard mutton cuts',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(14,3,'Keema & Minced','mutton-keema-minced','­ƒÑ®',NULL,'Minced goat meat',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(15,4,'Chicken Marinades','chicken-marinades','­ƒìó',NULL,'Tikkas and kebabs',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(16,4,'Fish Marinades','fish-marinades','­ƒÉƒ',NULL,'Herb-spiced basa',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(17,5,'Salami & Sausages','salami-sausages','­ƒÑô',NULL,'Ready to eat smoked cuts',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(18,6,'Combo Packs','combo-packs','­ƒì▒',NULL,'Curated value bundles',1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL),(19,NULL,'test','test',NULL,NULL,NULL,1,'2026-07-19 05:43:04','2026-07-21 11:31:31',NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` longtext NOT NULL,
  `connection` longtext NOT NULL,
  `queue` longtext NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `total_jobs` bigint(20) NOT NULL,
  `pending_jobs` bigint(20) NOT NULL,
  `failed_jobs` bigint(20) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` longtext DEFAULT NULL,
  `cancelled_at` bigint(20) DEFAULT NULL,
  `created_at` bigint(20) NOT NULL,
  `finished_at` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` longtext NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` bigint(20) NOT NULL,
  `reserved_at` bigint(20) DEFAULT NULL,
  `available_at` bigint(20) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` longtext NOT NULL,
  `file_path` longtext NOT NULL,
  `file_type` longtext DEFAULT NULL,
  `file_size` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_chats`
--

DROP TABLE IF EXISTS `order_chats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_chats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` longtext NOT NULL,
  `sender_type` longtext NOT NULL,
  `sender_id` bigint(20) DEFAULT NULL,
  `sender_name` longtext DEFAULT NULL,
  `message` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_chats`
--

LOCK TABLES `order_chats` WRITE;
/*!40000 ALTER TABLE `order_chats` DISABLE KEYS */;
INSERT INTO `order_chats` VALUES (1,'ROYAL-447135','rider',1,'Ramesh Shinde','helo','2026-07-22 11:29:21','2026-07-22 11:29:21'),(2,'ROYAL-447135','customer',NULL,'Customer','hi','2026-07-22 11:29:36','2026-07-22 11:29:36'),(3,'ROYAL-447135','rider',1,'Ramesh Shinde','ok','2026-07-22 12:00:35','2026-07-22 12:00:35'),(4,'ROYAL-447135','customer',NULL,'Customer','done','2026-07-22 12:01:07','2026-07-22 12:01:07'),(5,'ROYAL-447135','customer',NULL,'Customer','good job','2026-07-22 12:01:51','2026-07-22 12:01:51'),(6,'ROYAL-447135','rider',1,'Ramesh Shinde','thank you','2026-07-22 12:02:28','2026-07-22 12:02:28'),(7,'ROYAL-447135','rider',1,'Ramesh Shinde','I have arrived ­ƒÜ¬','2026-07-22 12:02:37','2026-07-22 12:02:37'),(8,'ROYAL-447135','rider',1,'Ramesh Shinde','I have arrived ­ƒÜ¬','2026-07-22 12:02:37','2026-07-22 12:02:37');
/*!40000 ALTER TABLE `order_chats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` longtext NOT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `product_name` longtext NOT NULL,
  `product_image` longtext DEFAULT NULL,
  `price` bigint(20) NOT NULL,
  `quantity` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,'ROYAL-361198',NULL,'King Fish (Surmai) Curry Cut',NULL,499,1,'2026-07-21 12:34:49','2026-07-21 12:34:49'),(2,'ROYAL-112143',1,'Hilsa Fresh Diamond Harbour (1 Pc)* (.980kg-1kg)','https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80',249,1,'2026-07-22 10:12:46','2026-07-22 10:12:46'),(3,'ROYAL-447135',3,'White Tiger Prawns - Cleaned & De-veined','https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=500&q=80',399,1,'2026-07-22 11:03:51','2026-07-22 11:03:51');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `total_price` bigint(20) NOT NULL,
  `payment_method` longtext NOT NULL,
  `address_data` longtext NOT NULL,
  `status` longtext NOT NULL DEFAULT 'Placed',
  `estimated_delivery` longtext NOT NULL DEFAULT '30-45 mins',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rider_id` bigint(20) DEFAULT NULL,
  `shipment_status` longtext NOT NULL DEFAULT 'Pending Assignment',
  `tracking_number` longtext DEFAULT NULL,
  `dispatched_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `delivery_notes` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,499,'COD','{\"addressLine\":\"123 Main St\",\"name\":\"Test User\",\"phone\":\"9999988888\",\"type\":\"home\",\"city\":\"Mumbai\",\"zipCode\":\"400001\"}','Dispatched','30-45 mins','2026-07-21 12:34:49','2026-07-22 09:53:48',1,'Out for Delivery','RF-TRK-991911','2026-07-22 09:53:04',NULL,NULL),(2,2,298,'COD','{\"id\":\"addr-1\",\"name\":\"Home (Default)\",\"type\":\"Home\",\"addressLine\":\"Flat 402, Royal Residency, Marine Drive\",\"city\":\"Mumbai\",\"zipCode\":\"400002\",\"phone\":\"+91 98765 43210\"}','Delivered','30-45 mins','2026-07-22 10:12:46','2026-07-22 10:29:03',1,'Delivered','RF-TRK-723864','2026-07-22 10:20:33','2026-07-22 10:29:03',NULL),(3,2,448,'COD','{\"id\":\"addr-1\",\"name\":\"Home (Default)\",\"type\":\"Home\",\"addressLine\":\"Flat 402, Royal Residency, Marine Drive\",\"city\":\"Mumbai\",\"zipCode\":\"400002\",\"phone\":\"+91 98765 43210\"}','Delivered','30-45 mins','2026-07-22 11:03:51','2026-07-22 12:04:33',1,'Delivered','RF-TRK-478351','2026-07-22 11:04:14','2026-07-22 12:04:33',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` longtext NOT NULL,
  `token` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` longtext NOT NULL,
  `tokenable_id` bigint(20) NOT NULL,
  `name` longtext NOT NULL,
  `token` longtext NOT NULL,
  `abilities` longtext DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'AppModelsUser',2,'auth_token','ab4dba715ecc5711e5b7becd1aa4d2a25a2960b34cce9e87aaf033ffd553e91f','[\"*\"]','2026-07-21 12:28:54',NULL,'2026-07-21 12:28:51','2026-07-21 12:28:54'),(2,'AppModelsUser',1,'test','73977994148596bc0cf8524522e16fa897b95e1edd92946fd2544b4b655c63d0','[\"*\"]','2026-07-21 12:34:49',NULL,'2026-07-21 12:34:32','2026-07-21 12:34:49'),(3,'AppModelsUser',3,'auth_token','dddf1dd0630298af92268732bad127fa0258bea7fe170082e4cf732b98d130b8','[\"*\"]','2026-07-22 10:03:06',NULL,'2026-07-22 10:03:04','2026-07-22 10:03:06'),(4,'AppModelsUser',2,'auth_token','bf9123b208985992b3c51084d78c23d5416eb2c037a4da3eede7b95a83a1e027','[\"*\"]','2026-07-22 10:04:26',NULL,'2026-07-22 10:04:25','2026-07-22 10:04:26'),(5,'AppModelsUser',3,'auth_token','1b3a1d0f91c39a64175013bdaaedd483a0a3e7878689fb6aebead9e404e1d930','[\"*\"]','2026-07-22 10:05:25',NULL,'2026-07-22 10:05:08','2026-07-22 10:05:25'),(6,'AppModelsUser',2,'auth_token','fd3a8e6437642f708bd0372b3623faffa09e5ff698ffdd1fecda900334cd3a04','[\"*\"]','2026-07-22 12:04:48',NULL,'2026-07-22 10:11:57','2026-07-22 12:04:48');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `image_path` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_code` longtext NOT NULL,
  `name` longtext NOT NULL,
  `slug` longtext NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `sub_category` longtext DEFAULT NULL,
  `image` longtext DEFAULT NULL,
  `price` bigint(20) NOT NULL,
  `original_price` bigint(20) DEFAULT NULL,
  `weight` longtext DEFAULT NULL,
  `pieces` longtext DEFAULT NULL,
  `servings` longtext DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `tags` longtext DEFAULT NULL,
  `rating` double NOT NULL DEFAULT 5,
  `reviews_count` bigint(20) NOT NULL DEFAULT 0,
  `is_best_seller` bigint(20) NOT NULL DEFAULT 0,
  `is_today_special` bigint(20) NOT NULL DEFAULT 0,
  `is_active` bigint(20) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `serviced_pincodes` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'fs-hilsa','Hilsa Fresh Diamond Harbour (1 Pc)* (.980kg-1kg)','hilsa-fresh-diamond-harbour-1-pc-980kg-1kg',1,'Seawater Fish','https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80',249,265,'1 Pc (.980kg-1kg)','10-11 Pieces','Serves 3-4','Sought-after delicious freshwater/seawater Hilsa sourced directly from Diamond Harbour. Perfectly processed and sliced for curry or frying.','\"[\"Royal Catch\",\"Special Price\"]\"',4.9,312,1,0,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL),(2,'fs-1','Surmai / Seer King Fish Steaks','surmai-seer-king-fish-steaks',1,'Seawater Fish','https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=500&q=80',649,799,'500g','5-7 Steaks','Serves 2-3','Also known as King Fish or Surmai, these meaty steaks are freshly sliced, scales removed, and perfectly ready to be shallow fried or cooked in a tangy coastal gravy. Highly rich in Omega-3 fatty acids and protein.','\"[\"Best Seller\",\"Fresh Catch\",\"High Omega 3\"]\"',4.9,142,1,0,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL),(3,'fs-2','White Tiger Prawns - Cleaned & De-veined','white-tiger-prawns-cleaned-de-veined',1,'Prawns','https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=500&q=80',399,499,'250g','15-20 Pieces','Serves 2','Juicy, sweet White Tiger Prawns, thoroughly cleaned, peeled, and de-veined with tail-on. Perfect for Butter Garlic prawns, tandoori skewers, or coastal curries.','\"[\"Cleaned & Peeled\",\"No Mess\",\"Sweet Taste\"]\"',4.8,208,1,1,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL),(4,'fs-3','Premium Salmon Fillet (Skin On)','premium-salmon-fillet-skin-on',1,'Exotic Catch','https://images.unsplash.com/photo-1467003909585-2f8a72700288?auto=format&fit=crop&w=500&q=80',1199,1499,'250g','1 Fillet','Serves 1','Sourced from clean Norwegian waters, this premium pink salmon fillet comes with the skin intact for a crispy cook. Extremely rich in heart-healthy Omega-3 fats.','\"[\"Imported\",\"Sashimi Grade\",\"Super Food\"]\"',4.7,89,0,0,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL),(5,'fs-4','Freshwater Rohu - Bengali Cut (No Head)','freshwater-rohu-bengali-cut-no-head',1,'Freshwater Fish','https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80',249,299,'500g','6-8 Pieces','Serves 2-3','Sweet freshwater Rohu cut in traditional Bengali style. Perfect for Rohu Kalia or Jhol. Sourced daily from bio-secure farms and cleaned perfectly.','\"[\"Freshwater\",\"Bengali Special\"]\"',4.6,312,0,0,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL),(6,'ch-1','Tender Chicken Curry Cut (Small)','tender-chicken-curry-cut-small',2,'Curry Cuts','https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=500&q=80',169,199,'500g','12-16 Pieces','Serves 2-3','Freshly dressed, juicy, pasture-raised spring chicken cuts including breast, wing, and drumsticks. Ideal for aromatic Indian curries or home-style gravies.','\"[\"Antibiotic-free\",\"Juicy Cuts\",\"Daily Fresh\"]\"',4.8,521,1,0,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL),(7,'ch-2','Premium Chicken Breast Fillet','premium-chicken-breast-fillet',2,'Boneless & Mince','https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=500&q=80',259,320,'500g','3-4 Fillets','Serves 2-3','Boneless, skinless breasts trimmed of fat. High in lean protein, low in calorie. Excellent choice for gym-goers, meal prep, pan-searing, or grilling.','\"[\"Lean Protein\",\"Zero Fat\",\"Fitness Choice\"]\"',4.7,410,0,1,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL),(8,'mu-1','Rich Goat Curry Cut (Mix)','rich-goat-curry-cut-mix',3,'Curry Cuts','https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=500&q=80',679,799,'500g','15-18 Pieces','Serves 3','Juicy, tender, fat-marbled pieces of goat meat cut from the leg, shoulder, and ribs. High quality pasture-raised goats from registered farms. Perfect for mutton biryani, korma, or slow-cooked stews.','\"[\"Tender Goat\",\"Marbled Meat\",\"No Added Hormones\"]\"',4.9,295,1,0,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL),(9,'mu-2','Premium Goat Keema (Minced)','premium-goat-keema-minced',3,'Keema & Minced','https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=500&q=80',389,449,'250g','Finely Minced','Serves 2','Finely minced mutton from succulent, boneless goat cuts. Delivers deep, authentic mutton flavor. Crafted for delicious keema matar, keema samosas, or juicy mutton patties.','\"[\"Boneless\",\"Finely Ground\",\"Quick Cook\"]\"',4.8,167,0,0,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL),(10,'ma-1','Tandoori Chicken Tikka Marinade','tandoori-chicken-tikka-marinade',4,'Chicken Marinades','https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=500&q=80',219,269,'350g','10-12 Pieces','Serves 2','Tender boneless chicken thigh cubes marinated in authentic spiced yogurt, ginger-garlic paste, mustard oil, and real Kashmiri red chilies. Ready to bake, grill, or pan fry in 10 minutes!','\"[\"Ready to Cook\",\"Spicy\",\"Chef Special\"]\"',4.8,334,0,1,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL),(11,'ma-2','Hariyali Fish Tikka Marinade','hariyali-fish-tikka-marinade',4,'Fish Marinades','https://images.unsplash.com/photo-1511216113906-8f57bb83e776?auto=format&fit=crop&w=500&q=80',349,429,'300g','8-10 Pieces','Serves 2','Fresh Basa cubes generously coated with an herbaceous, cooling paste of mint, coriander, spinach, green chilies, and aromatic spices. Freshly packed on order.','\"[\"Herbal Spices\",\"Mildly Hot\",\"Exotic Taste\"]\"',4.5,94,0,0,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL),(12,'cc-1','Chicken Salami (Smoked)','chicken-salami-smoked',5,'Salami & Sausages','https://images.unsplash.com/photo-1629450646452-278271dbde1d?auto=format&fit=crop&w=500&q=80',159,199,'200g','12-15 Slices','Serves 2-3','Fully cooked, hickory-smoked premium chicken breast salami slices. Gently flavored with black pepper and mild garlic. Perfect for breakfast sandwiches, wraps, or charcuterie boards.','\"[\"Ready to Eat\",\"Smoked Flavor\",\"Breakfast Essential\"]\"',4.6,178,0,0,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL),(13,'co-1','Super Fish Fry & Curry Combo','super-fish-fry-curry-combo',6,'Combo Packs','https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80',799,999,'1kg Combo','2 Packs','Serves 4-5','Get the best of seawater and freshwater in one go! Includes 500g freshwater Rohu (Bengali Cut) and 500g seawater Basa Fillet at a discounted value pack price.','\"[\"Combo Deal\",\"Seafood Lover\",\"Big Saving\"]\"',4.9,220,1,0,1,'2026-07-19 05:41:59','2026-07-21 11:31:31',NULL,NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `riders`
--

DROP TABLE IF EXISTS `riders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `riders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `phone` longtext NOT NULL,
  `vehicle_type` longtext NOT NULL DEFAULT 'Bike',
  `vehicle_number` longtext NOT NULL,
  `operating_pincodes` longtext DEFAULT NULL,
  `status` longtext NOT NULL DEFAULT 'Available',
  `is_active` bigint(20) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email` longtext DEFAULT NULL,
  `password` longtext DEFAULT NULL,
  `earnings_per_delivery` bigint(20) NOT NULL DEFAULT 50,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `riders`
--

LOCK TABLES `riders` WRITE;
/*!40000 ALTER TABLE `riders` DISABLE KEYS */;
INSERT INTO `riders` VALUES (1,'Ramesh Shinde','9820198201','Motorbike','MH-01-AX-9911','\"[\"400001\",\"400002\"]\"','Available',1,'2026-07-22 08:40:23','2026-07-22 12:04:33','ramesh@royalfish.com','$2y$12$2U3cNesFAXP81O2okrPF7OYmi.N0FsML/DNrNMfWuEXwAi8TnJD.2',50),(2,'Suresh Patil','9820298202','EV Delivery Van','MH-02-EV-4422','\"[\"400003\",\"400004\"]\"','Available',1,'2026-07-22 08:40:23','2026-07-22 09:21:48','suresh@royalfish.com','$2y$12$3mzV/gcvDbgpGGBiltaYSuwOxZ.HZES5rU/cIRt0dfEYhyN9sCvVe',50);
/*!40000 ALTER TABLE `riders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `slug` longtext NOT NULL,
  `permissions` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','super-admin','[\"*\"]','2026-07-19 05:41:57','2026-07-19 05:41:57'),(2,'Admin','admin','[\"dashboard\",\"roles\",\"users\",\"categories\",\"products\",\"orders\",\"media\",\"settings\"]','2026-07-19 05:41:57','2026-07-19 05:41:57'),(3,'Manager','manager','[\"dashboard\",\"categories\",\"products\",\"orders\",\"media\"]','2026-07-19 05:41:57','2026-07-19 05:41:57'),(4,'Staff','staff','[\"dashboard\",\"orders\"]','2026-07-19 05:41:57','2026-07-19 05:41:57');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `ip_address` longtext DEFAULT NULL,
  `user_agent` longtext DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` longtext NOT NULL,
  `value` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'website_name','Royal Fish Store','2026-07-19 05:41:58','2026-07-19 05:41:58'),(2,'website_tagline','Online Fish Delivery','2026-07-19 05:41:58','2026-07-19 05:41:58'),(3,'website_email','care@royalfish.com','2026-07-19 05:41:58','2026-07-19 05:41:58'),(4,'website_phone','+91 98765 43210','2026-07-19 05:41:58','2026-07-19 05:41:58'),(5,'website_address','Flat 402, Royal Residency, Marine Drive, Mumbai','2026-07-19 05:41:58','2026-07-19 05:41:58'),(6,'seo_meta_title','Royal Fish Store - Premium Fresh Fish Online Delivery','2026-07-19 05:41:58','2026-07-19 05:41:58'),(7,'seo_meta_description','Royal Fish Store offers the finest, premium fresh fish and seafood sourced directly and delivered fresh to your doorstep.','2026-07-19 05:41:58','2026-07-19 05:41:58'),(8,'seo_meta_keywords','Royal Fish Store, Royal Fish, fresh fish, seafood delivery','2026-07-19 05:41:58','2026-07-19 05:41:58'),(9,'theme_color','#a80e0e','2026-07-19 05:41:58','2026-07-19 05:41:58'),(10,'smtp_host','smtp.mailtrap.io','2026-07-19 05:41:58','2026-07-19 05:41:58'),(11,'smtp_port','2525','2026-07-19 05:41:58','2026-07-19 05:41:58'),(12,'smtp_user','mock_user','2026-07-19 05:41:58','2026-07-19 05:41:58'),(13,'smtp_password','mock_password','2026-07-19 05:41:58','2026-07-19 05:41:58'),(14,'social_facebook','https://facebook.com/royalfishstore','2026-07-19 05:41:58','2026-07-19 05:41:58'),(15,'social_instagram','https://instagram.com/royalfishstore','2026-07-19 05:41:58','2026-07-19 05:41:58'),(16,'social_twitter','https://twitter.com/royalfishstore','2026-07-19 05:41:58','2026-07-19 05:41:58');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slides`
--

DROP TABLE IF EXISTS `slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slides` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` longtext NOT NULL,
  `subtitle` longtext DEFAULT NULL,
  `code` longtext DEFAULT NULL,
  `bg_gradient` longtext NOT NULL DEFAULT 'from-red-600 to-rose-500',
  `image` longtext DEFAULT NULL,
  `text_color` longtext NOT NULL DEFAULT 'text-white',
  `sort_order` bigint(20) NOT NULL DEFAULT 0,
  `is_active` bigint(20) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slides`
--

LOCK TABLES `slides` WRITE;
/*!40000 ALTER TABLE `slides` DISABLE KEYS */;
INSERT INTO `slides` VALUES (6,'Test','Test','Test150','from-amber-600 to-orange-600','http://127.0.0.1:8000/uploads/slide_1785074816_pngtree-pices-of-fish-with-lemon-png-image_19902676.png','text-white',1,1,'2026-07-21 12:13:07','2026-07-26 08:36:56');
/*!40000 ALTER TABLE `slides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `email` longtext NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` longtext NOT NULL,
  `remember_token` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) DEFAULT NULL,
  `phone` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','admin@royalfish.com',NULL,'$2y$12$PraxBh.12q7Plcdz7SlLMuFZ9xVHKa8OxDyag4xHJaSxMa8zinj5O',NULL,'2026-07-19 05:41:58','2026-07-21 12:01:56',1,'9999999999',NULL),(2,'Guest 1657','user-9875411657@royalfish.com',NULL,'$2y$12$rzce7n8hTjU3G3v0aAaNZuDOw66Ql1a0Tf1WyB5B7n3Ir1C0Bs132',NULL,'2026-07-21 12:28:50','2026-07-21 12:28:50',NULL,'9875411657',NULL),(3,'Guest 8888','user-9999988888@royalfish.com',NULL,'$2y$12$ZmfQmsBjHV.r/UpsVqJFzOaAgS5fxmbQComgKWtIMKaPNnQBqcO82',NULL,'2026-07-22 10:03:03','2026-07-22 10:03:03',NULL,'9999988888',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-28 23:33:29
