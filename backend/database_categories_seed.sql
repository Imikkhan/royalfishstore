-- Seed Categories & Sub-Categories into MySQL Database `royalfishstore`
-- Run this SQL in phpMyAdmin or MySQL CLI

SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE `categories`;
SET FOREIGN_KEY_CHECKS=1;

-- 1. Main Categories (parent_id = NULL)
INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `icon`, `image`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Wholesale Fish', 'wholesale-fish', '📦', 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=500&q=80', 'Bulk & Wholesale Fresh Fish', 1, NOW(), NOW()),
(2, NULL, 'Fresh Fish', 'fresh-fish', '🐟', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80', 'Daily Fresh Catch', 1, NOW(), NOW()),
(3, NULL, 'Fresh Hilsa', 'fresh-hilsa', '👑', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80', 'Diamond Harbour Fresh Hilsa (Ilish)', 1, NOW(), NOW()),
(4, NULL, 'Seafood', 'seafood', '🦀', 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=500&q=80', 'Crabs, Lobsters & Marine Catch', 1, NOW(), NOW()),
(5, NULL, 'Chicken', 'chicken', '🍗', 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=500&q=80', 'Farm Fresh Tender Chicken', 1, NOW(), NOW()),
(6, NULL, 'Mutton', 'mutton', '🥩', 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=500&q=80', 'Rich Pasture-Raised Goat Meat', 1, NOW(), NOW());

-- 2. Sub Categories for Fresh Fish (parent_id = 2)
INSERT INTO `categories` (`parent_id`, `name`, `slug`, `icon`, `image`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Rohu & Catla', 'rohu-catla', '🏞️', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=200&q=80', 'Freshwater Rohu & Catla cuts', 1, NOW(), NOW()),
(2, 'Live Fish', 'live-fish', '🌊', 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=200&q=80', 'Live swimming fresh fish', 1, NOW(), NOW()),
(2, 'Prawn & Shell Fish', 'prawn-shell-fish', '🦐', 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=200&q=80', 'Cleaned prawns & shellfish', 1, NOW(), NOW()),
(2, 'Fillet', 'fillet', '🔪', 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?auto=format&fit=crop&w=200&q=80', 'Boneless fish fillets', 1, NOW(), NOW()),
(2, 'Premium Fish', 'premium-fish', '✨', 'https://images.unsplash.com/photo-1599084993091-1cb5c0721cc6?auto=format&fit=crop&w=200&q=80', 'Surmai, Pomfret & Salmon', 1, NOW(), NOW()),
(2, 'Other Fish', 'other-fish', '🐠', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=200&q=80', 'Assorted regional catch', 1, NOW(), NOW());

-- 3. Sub Categories for Chicken (parent_id = 5)
INSERT INTO `categories` (`parent_id`, `name`, `slug`, `icon`, `image`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(5, 'Chicken Curry Cuts', 'chicken-curry-cuts', '🍗', 'https://images.unsplash.com/photo-1587593810167-a84920ea0781?auto=format&fit=crop&w=200&q=80', 'Bone-in curry cuts', 1, NOW(), NOW()),
(5, 'Chicken Boneless', 'chicken-boneless', '🥩', 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=200&q=80', 'Boneless breast & thigh fillets', 1, NOW(), NOW()),
(5, 'Chicken Special Cuts', 'chicken-special-cuts', '🍢', 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=200&q=80', 'Drumsticks, wings & lollipop', 1, NOW(), NOW()),
(5, 'Chicken Liver', 'chicken-liver', '🫀', 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=200&q=80', 'Fresh chicken liver & gizzard', 1, NOW(), NOW());

-- 4. Sub Categories for Mutton (parent_id = 6)
INSERT INTO `categories` (`parent_id`, `name`, `slug`, `icon`, `image`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(6, 'Mutton Curry Cuts', 'mutton-curry-cuts', '🍖', 'https://images.unsplash.com/photo-1603048588665-791ca8aea617?auto=format&fit=crop&w=200&q=80', 'Standard goat curry cuts', 1, NOW(), NOW()),
(6, 'Mutton Boneless', 'mutton-boneless', '🥩', 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=200&q=80', 'Boneless mutton & keema', 1, NOW(), NOW()),
(6, 'Mutton Special Cuts', 'mutton-special-cuts', '🍢', 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=200&q=80', 'Goat nalli, chops & ribs', 1, NOW(), NOW()),
(6, 'Mutton Liver', 'mutton-liver', '🫀', 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=200&q=80', 'Fresh goat liver (Kaleji)', 1, NOW(), NOW());
