-- Add theme color support to cities
USE `bizguide`;
ALTER TABLE `cities` ADD COLUMN `theme_color` VARCHAR(20) DEFAULT '#7c3aed' AFTER `logo`;
ALTER TABLE `cities` ADD COLUMN `theme_color_dark` VARCHAR(20) DEFAULT '#2d1b69' AFTER `theme_color`;
