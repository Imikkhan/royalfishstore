-- ==============================================================================
-- ROYAL FISH STORE - SAFE PRODUCTION DATABASE UPDATE SCRIPT
-- ==============================================================================
-- Yeh script aapke existing production data ko bina kisi loss ke update karega.
-- Isme koi DROP TABLE ya TRUNCATE command nahi hai.
-- phpMyAdmin me "SQL" tab me paste karke execute karein.
-- ==============================================================================

-- 1. PRODUCTS TABLE: Stock Management & Quantity Limits columns add karein
-- (Agar columns pehle se nahi hain tabhi add honge)
ALTER TABLE `products` 
  ADD COLUMN IF NOT EXISTS `stock_quantity` INT NOT NULL DEFAULT 50 AFTER `is_active`,
  ADD COLUMN IF NOT EXISTS `in_stock` TINYINT(1) NOT NULL DEFAULT 1 AFTER `stock_quantity`,
  ADD COLUMN IF NOT EXISTS `low_stock_threshold` INT NOT NULL DEFAULT 5 AFTER `in_stock`,
  ADD COLUMN IF NOT EXISTS `min_order_qty` INT NOT NULL DEFAULT 1 AFTER `low_stock_threshold`,
  ADD COLUMN IF NOT EXISTS `max_order_qty` INT NOT NULL DEFAULT 10 AFTER `min_order_qty`;

-- 2. SETTINGS TABLE: Missing Configuration Keys insert karein
-- (WHERE NOT EXISTS use kiya hai taaki agar key pehle se ho toh duplicate na ho aur purana data overwrite na ho)
INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'min_order_amount', '199', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `settings` WHERE `key` = 'min_order_amount');

INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'free_delivery_threshold', '499', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `settings` WHERE `key` = 'free_delivery_threshold');

INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'delivery_slot', 'Today 4:00pm - 08:30 pm', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `settings` WHERE `key` = 'delivery_slot');

INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'morning_delivery_slot', 'Today 07:00 am - 12:00 pm', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `settings` WHERE `key` = 'morning_delivery_slot');

INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'evening_delivery_slot', 'Today 4:00pm - 08:30 pm', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `settings` WHERE `key` = 'evening_delivery_slot');

-- 3. (OPTIONAL) Fix missing Original Price for discount display:
-- Agar kisi product ka original_price khali ya 0 hai toh use price ka 120% set karein
UPDATE `products` 
SET `original_price` = CEIL(`price` * 1.20)
WHERE `original_price` IS NULL OR `original_price` = 0;

