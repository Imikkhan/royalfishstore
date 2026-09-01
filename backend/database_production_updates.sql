-- =========================================================
-- ROYAL FISH STORE - PRODUCTION DATABASE UPDATE SCRIPT
-- Copy and run these SQL queries in phpMyAdmin / MySQL Console
-- =========================================================

-- 1. Update `products` table (Add short_description, delivery_time, serviced_pincodes)
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `short_description` TEXT NULL AFTER `description`;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `delivery_time` VARCHAR(255) NULL AFTER `short_description`;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `serviced_pincodes` LONGTEXT NULL AFTER `delivery_time`;

-- 2. Update `orders` table (Modify ID column to VARCHAR and add logistics columns)
ALTER TABLE `orders` MODIFY `id` VARCHAR(255) NOT NULL;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `rider_id` BIGINT(20) UNSIGNED NULL AFTER `user_id`;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `shipment_status` VARCHAR(255) NOT NULL DEFAULT 'Pending Assignment' AFTER `status`;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `tracking_number` VARCHAR(255) NULL AFTER `shipment_status`;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `dispatched_at` TIMESTAMP NULL DEFAULT NULL AFTER `tracking_number`;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `delivered_at` TIMESTAMP NULL DEFAULT NULL AFTER `dispatched_at`;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `delivery_notes` TEXT NULL AFTER `delivered_at`;

-- 3. Create `riders` table (Delivery Partners)
CREATE TABLE IF NOT EXISTS `riders` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255) NOT NULL UNIQUE,
    `email` VARCHAR(255) NULL UNIQUE,
    `password` VARCHAR(255) NULL,
    `vehicle_type` VARCHAR(255) NOT NULL DEFAULT 'Bike',
    `vehicle_number` VARCHAR(255) NOT NULL,
    `operating_pincodes` LONGTEXT NULL,
    `status` VARCHAR(255) NOT NULL DEFAULT 'Available',
    `earnings_per_delivery` INT(11) NOT NULL DEFAULT 50,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Create `order_chats` table (Rider-Customer Live Chat)
CREATE TABLE IF NOT EXISTS `order_chats` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_id` VARCHAR(255) NOT NULL,
    `sender_type` VARCHAR(255) NOT NULL,
    `sender_id` BIGINT(20) UNSIGNED NULL,
    `sender_name` VARCHAR(255) NULL,
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Create `videos` table (YouTube Videos Manager)
CREATE TABLE IF NOT EXISTS `videos` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `youtube_url` TEXT NOT NULL,
    `youtube_id` VARCHAR(255) NOT NULL,
    `thumbnail` TEXT NULL,
    `duration` VARCHAR(255) NULL DEFAULT '1:00',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT(11) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Create `slides` table (Hero Banners)
CREATE TABLE IF NOT EXISTS `slides` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `subtitle` VARCHAR(255) NULL,
    `code` VARCHAR(255) NULL,
    `bg_gradient` VARCHAR(255) NULL,
    `image` TEXT NOT NULL,
    `text_color` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT(11) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Fix original_price: set to 20% above price where it's NULL, 0, or incorrectly <= price
UPDATE `products` SET `original_price` = CEIL(`price` * 1.20)
WHERE `original_price` IS NULL OR `original_price` = 0 OR `original_price` <= `price`;
