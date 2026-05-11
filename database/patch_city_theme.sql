USE `bizguide`;
SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='cities' AND COLUMN_NAME='theme_color');
SET @sql = IF(@col=0,
  'ALTER TABLE `cities` ADD COLUMN `theme_color` VARCHAR(20) DEFAULT NULL AFTER `slug`',
  'SELECT "theme_color already exists"');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- Add last_login_at to users
SET @col2 = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='users' AND COLUMN_NAME='last_login_at');
SET @sql2 = IF(@col2=0,
  'ALTER TABLE `users` ADD COLUMN `last_login_at` DATETIME DEFAULT NULL AFTER `status`',
  'SELECT "last_login_at already exists"');
PREPARE s2 FROM @sql2; EXECUTE s2; DEALLOCATE PREPARE s2;

SELECT 'City theme patch done.' AS status;
