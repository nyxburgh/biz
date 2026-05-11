-- ============================================================
-- BizGuide — Patch: Role-Based Admin Access
-- Safe to run on existing installs.
-- mysql -u root -p bizguide < database/patch_roles.sql
-- ============================================================

USE `bizguide`;

-- Step 1: Modify role column (old enum → new enum)
ALTER TABLE `admins`
  MODIFY COLUMN `role` ENUM('super_admin','city_admin') NOT NULL DEFAULT 'city_admin';

-- Step 2: Add assigned_city_id if not exists
SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='admins' AND COLUMN_NAME='assigned_city_id');
SET @sql = IF(@col=0,
  'ALTER TABLE `admins` ADD COLUMN `assigned_city_id` INT UNSIGNED DEFAULT NULL AFTER `role`',
  'SELECT "assigned_city_id already exists"');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- Step 3: Add FK (skip if already exists — error is safe to ignore)
ALTER TABLE `admins`
  ADD CONSTRAINT `fk_admins_city`
  FOREIGN KEY (`assigned_city_id`) REFERENCES `cities`(`id`) ON DELETE SET NULL;

-- Step 4: Add description to activity_logs if not exists
SET @col2 = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='activity_logs' AND COLUMN_NAME='description');
SET @sql2 = IF(@col2=0,
  'ALTER TABLE `activity_logs` ADD COLUMN `description` VARCHAR(500) DEFAULT NULL AFTER `action`',
  'SELECT "description already exists"');
PREPARE s2 FROM @sql2; EXECUTE s2; DEALLOCATE PREPARE s2;

-- Step 5: Add city_id to activity_logs if not exists
SET @col3 = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='activity_logs' AND COLUMN_NAME='city_id');
SET @sql3 = IF(@col3=0,
  'ALTER TABLE `activity_logs` ADD COLUMN `city_id` INT UNSIGNED DEFAULT NULL AFTER `target_id`',
  'SELECT "city_id already exists"');
PREPARE s3 FROM @sql3; EXECUTE s3; DEALLOCATE PREPARE s3;

-- Step 6: Set all existing admins as super_admin
UPDATE `admins` SET `role`='super_admin', `assigned_city_id`=NULL;

SELECT 'Patch applied successfully.' AS status;
