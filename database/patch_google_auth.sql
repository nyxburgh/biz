-- BizGuide: Google Auth + User Type patch
USE `bizguide`;

-- Add google_id to users
SET @c1 = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='users' AND COLUMN_NAME='google_id');
SET @s1 = IF(@c1=0,
  'ALTER TABLE `users` ADD COLUMN `google_id` VARCHAR(100) DEFAULT NULL AFTER `password`',
  'SELECT "google_id exists"');
PREPARE st FROM @s1; EXECUTE st; DEALLOCATE PREPARE st;

-- Add user_type
SET @c2 = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='users' AND COLUMN_NAME='user_type');
SET @s2 = IF(@c2=0,
  'ALTER TABLE `users` ADD COLUMN `user_type` ENUM(\'visitor\',\'owner\') NOT NULL DEFAULT \'owner\' AFTER `google_id`',
  'SELECT "user_type exists"');
PREPARE st2 FROM @s2; EXECUTE st2; DEALLOCATE PREPARE st2;

-- Add email_verified
SET @c3 = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='users' AND COLUMN_NAME='email_verified');
SET @s3 = IF(@c3=0,
  'ALTER TABLE `users` ADD COLUMN `email_verified` TINYINT(1) DEFAULT 0 AFTER `email`',
  'SELECT "email_verified exists"');
PREPARE st3 FROM @s3; EXECUTE st3; DEALLOCATE PREPARE st3;

SELECT 'Google auth patch done.' AS status;
