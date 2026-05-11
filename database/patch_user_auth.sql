-- BizGuide — Patch: User Auth (OTP + password for paid users)
-- mysql -u root -p bizguide < database/patch_user_auth.sql

USE `bizguide`;

CREATE TABLE IF NOT EXISTS `user_otps` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `phone`      VARCHAR(20) NOT NULL,
  `otp`        VARCHAR(6) NOT NULL,
  `purpose`    ENUM('login','register','upgrade') DEFAULT 'login',
  `expires_at` DATETIME NOT NULL,
  `used`       TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX(`phone`)
) ENGINE=InnoDB;

-- Add password column to users if not exists
SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='users' AND COLUMN_NAME='password');
SET @sql = IF(@col=0,
  'ALTER TABLE `users` ADD COLUMN `password` VARCHAR(255) DEFAULT NULL AFTER `email`',
  'SELECT "password column already exists"');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- Add verified column to users if not exists
SET @col2 = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='users' AND COLUMN_NAME='phone_verified');
SET @sql2 = IF(@col2=0,
  'ALTER TABLE `users` ADD COLUMN `phone_verified` TINYINT(1) DEFAULT 0 AFTER `phone`',
  'SELECT "phone_verified already exists"');
PREPARE s2 FROM @sql2; EXECUTE s2; DEALLOCATE PREPARE s2;

SELECT 'Auth patch applied.' AS status;
