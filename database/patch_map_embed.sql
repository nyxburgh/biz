-- Add map_embed column to business_listings
USE `bizguide`;

SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='business_listings' AND COLUMN_NAME='map_embed');
SET @sql = IF(@col=0,
  'ALTER TABLE `business_listings` ADD COLUMN `map_embed` TEXT DEFAULT NULL AFTER `website`',
  'SELECT "map_embed already exists"');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SELECT 'Map embed patch done.' AS status;