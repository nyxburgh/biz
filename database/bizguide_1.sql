-- ============================================================
-- BizGuide Database Schema
-- Import: mysql -u root -p bizguide < database/bizguide.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS `bizguide`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bizguide`;

-- ------------------------------------------------------------
CREATE TABLE `cities` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NOT NULL,
  `slug`        VARCHAR(100) NOT NULL UNIQUE,
  `domain`      VARCHAR(255) DEFAULT NULL,
  `folder_path` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `logo`        VARCHAR(255) DEFAULT NULL,
  `theme_color`      VARCHAR(20) DEFAULT '#7c3aed',
  `theme_color_dark` VARCHAR(20) DEFAULT '#2d1b69',
  `status`      ENUM('active','inactive') DEFAULT 'active',
  `sort_order`  INT DEFAULT 0,
  `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
CREATE TABLE `plans` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`          ENUM('free','basic','premium','pro') NOT NULL UNIQUE,
  `label`         VARCHAR(50) NOT NULL,
  `price`         DECIMAL(10,2) DEFAULT 0.00,
  `duration_days` INT DEFAULT 365,
  `sort_order`    INT DEFAULT 0,
  `status`        ENUM('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB;

INSERT INTO `plans` (`name`,`label`,`price`,`sort_order`) VALUES
  ('free',    'Free User',    0.00,   1),
  ('basic',   'Basic User',   299.00, 2),
  ('premium', 'Premium User', 599.00, 3),
  ('pro',     'Pro User',     999.00, 4);

-- ------------------------------------------------------------
CREATE TABLE `admins` (
  `id`                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`              VARCHAR(150) NOT NULL,
  `email`             VARCHAR(200) NOT NULL UNIQUE,
  `password`          VARCHAR(255) NOT NULL,
  `role`              ENUM('super_admin','city_admin') NOT NULL DEFAULT 'city_admin',
  `assigned_city_id`  INT UNSIGNED DEFAULT NULL,
  `status`            ENUM('active','inactive') DEFAULT 'active',
  `last_login_at`     DATETIME DEFAULT NULL,
  `created_at`        DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`assigned_city_id`) REFERENCES `cities`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
CREATE TABLE `users` (
  `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `city_id`         INT UNSIGNED DEFAULT NULL,
  `plan_id`         INT UNSIGNED DEFAULT 1,
  `name`            VARCHAR(150) NOT NULL,
  `email`           VARCHAR(200) DEFAULT NULL,
  `phone`           VARCHAR(20) NOT NULL,
  `profession`      VARCHAR(150) DEFAULT NULL,
  `password`        VARCHAR(255) NOT NULL,
  `status`          ENUM('pending','active','suspended','banned') DEFAULT 'pending',
  `plan_expires_at` DATE DEFAULT NULL,
  `last_login_at`   DATETIME DEFAULT NULL,
  `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_email` (`email`),
  FOREIGN KEY (`city_id`) REFERENCES `cities`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`plan_id`) REFERENCES `plans`(`id`)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
CREATE TABLE `categories` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(150) NOT NULL,
  `slug`        VARCHAR(150) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `sort_order`  INT DEFAULT 0,
  `status`      ENUM('active','inactive') DEFAULT 'active',
  `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
CREATE TABLE `subcategories` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED NOT NULL,
  `name`        VARCHAR(150) NOT NULL,
  `slug`        VARCHAR(150) NOT NULL,
  `sort_order`  INT DEFAULT 0,
  `status`      ENUM('active','inactive') DEFAULT 'active',
  `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_cat_slug` (`category_id`, `slug`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
CREATE TABLE `keywords` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id`    INT UNSIGNED DEFAULT NULL,
  `subcategory_id` INT UNSIGNED DEFAULT NULL,
  `name`           VARCHAR(150) NOT NULL,
  `slug`           VARCHAR(150) NOT NULL UNIQUE,
  `status`         ENUM('active','inactive') DEFAULT 'active',
  `created_at`     DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`)    REFERENCES `categories`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
CREATE TABLE `keyword_suggestions` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`        INT UNSIGNED NOT NULL,
  `category_id`    INT UNSIGNED DEFAULT NULL,
  `subcategory_id` INT UNSIGNED DEFAULT NULL,
  `keyword`        VARCHAR(150) NOT NULL,
  `note`           TEXT DEFAULT NULL,
  `status`         ENUM('pending','approved','rejected','converted') DEFAULT 'pending',
  `admin_note`     TEXT DEFAULT NULL,
  `reviewed_by`    INT UNSIGNED DEFAULT NULL,
  `reviewed_at`    DATETIME DEFAULT NULL,
  `created_at`     DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`reviewed_by`) REFERENCES `admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
CREATE TABLE `business_listings` (
  `id`                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`           INT UNSIGNED NOT NULL UNIQUE,  -- one listing per user
  `city_id`           INT UNSIGNED DEFAULT NULL,
  `category_id`       INT UNSIGNED DEFAULT NULL,
  `plan_level`        ENUM('free','basic','premium','pro') DEFAULT 'free',
  `business_name`     VARCHAR(200) DEFAULT NULL,
  `profession`        VARCHAR(150) DEFAULT NULL,
  `address`           TEXT DEFAULT NULL,
  `phone`             VARCHAR(20) DEFAULT NULL,
  `whatsapp`          VARCHAR(20) DEFAULT NULL,
  `email`             VARCHAR(200) DEFAULT NULL,
  `short_description` TEXT DEFAULT NULL,
  `website`           VARCHAR(255) DEFAULT NULL,
  `facebook`          VARCHAR(255) DEFAULT NULL,
  `instagram`         VARCHAR(255) DEFAULT NULL,
  `twitter`           VARCHAR(255) DEFAULT NULL,
  `linkedin`          VARCHAR(255) DEFAULT NULL,
  `youtube_url`       VARCHAR(255) DEFAULT NULL,
  `top_banner`        VARCHAR(255) DEFAULT NULL,
  `slug`              VARCHAR(255) DEFAULT NULL UNIQUE,
  `status`            ENUM('draft','pending','approved','rejected','suspended') DEFAULT 'draft',
  `rejection_note`    TEXT DEFAULT NULL,
  `approved_by`       INT UNSIGNED DEFAULT NULL,
  `approved_at`       DATETIME DEFAULT NULL,
  `published_at`      DATETIME DEFAULT NULL,
  `views`             INT DEFAULT 0,
  `is_featured`       TINYINT(1) DEFAULT 0,
  `created_at`        DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`city_id`)     REFERENCES `cities`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`approved_by`) REFERENCES `admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
CREATE TABLE `listing_subcategories` (
  `listing_id`     INT UNSIGNED NOT NULL,
  `subcategory_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`listing_id`, `subcategory_id`),
  FOREIGN KEY (`listing_id`)     REFERENCES `business_listings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `listing_keywords` (
  `listing_id` INT UNSIGNED NOT NULL,
  `keyword_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`listing_id`, `keyword_id`),
  FOREIGN KEY (`listing_id`) REFERENCES `business_listings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`keyword_id`) REFERENCES `keywords`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `listing_images` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `listing_id` INT UNSIGNED NOT NULL,
  `filename`   VARCHAR(255) NOT NULL,
  `alt_text`   VARCHAR(255) DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`listing_id`) REFERENCES `business_listings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `listing_services` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `listing_id`  INT UNSIGNED NOT NULL,
  `title`       VARCHAR(200) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `price`       VARCHAR(100) DEFAULT NULL,
  `sort_order`  INT DEFAULT 0,
  FOREIGN KEY (`listing_id`) REFERENCES `business_listings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `listing_edit_requests` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `listing_id`  INT UNSIGNED NOT NULL,
  `user_id`     INT UNSIGNED NOT NULL,
  `changes`     JSON NOT NULL,
  `status`      ENUM('pending','approved','rejected') DEFAULT 'pending',
  `admin_note`  TEXT DEFAULT NULL,
  `reviewed_by` INT UNSIGNED DEFAULT NULL,
  `reviewed_at` DATETIME DEFAULT NULL,
  `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`listing_id`)  REFERENCES `business_listings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`reviewed_by`) REFERENCES `admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
CREATE TABLE `payments` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`       INT UNSIGNED NOT NULL,
  `plan_id`       INT UNSIGNED NOT NULL,
  `amount`        DECIMAL(10,2) NOT NULL,
  `reference`     VARCHAR(100) DEFAULT NULL,
  `payment_mode`  VARCHAR(50) DEFAULT NULL,
  `payment_proof` VARCHAR(255) DEFAULT NULL,
  `status`        ENUM('pending','confirmed','rejected') DEFAULT 'pending',
  `note`          TEXT DEFAULT NULL,
  `confirmed_by`  INT UNSIGNED DEFAULT NULL,
  `confirmed_at`  DATETIME DEFAULT NULL,
  `created_at`    DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`plan_id`)     REFERENCES `plans`(`id`),
  FOREIGN KEY (`confirmed_by`) REFERENCES `admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
CREATE TABLE `free_users_sidebar` (
  `id`        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`   INT UNSIGNED NOT NULL UNIQUE,
  `city_id`   INT UNSIGNED DEFAULT NULL,
  `queue_pos` INT DEFAULT 0,
  `visible`   TINYINT(1) DEFAULT 1,
  `added_at`  DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`city_id`) REFERENCES `cities`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
CREATE TABLE `settings` (
  `id`    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key`   VARCHAR(100) NOT NULL UNIQUE,
  `value` TEXT DEFAULT NULL,
  `group` VARCHAR(50) DEFAULT 'general'
) ENGINE=InnoDB;

INSERT INTO `settings` (`key`, `value`, `group`) VALUES
  ('site_name',             'BizGuide',                                    'general'),
  ('site_tagline',          'Your City Business Directory',                'general'),
  ('admin_email',           'admin@bizguide.com',                          'general'),
  ('free_sidebar_limit',    '50',                                          'general'),
  ('currency_symbol',       '₹',                                           'payment'),
  ('payment_instructions',  'Transfer to our UPI/bank and upload proof.',  'payment');


-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `listing_reviews` (
  `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `listing_id`      INT UNSIGNED NOT NULL,
  `user_id`         INT UNSIGNED DEFAULT NULL,
  `reviewer_name`   VARCHAR(150) NOT NULL,
  `reviewer_phone`  VARCHAR(20) DEFAULT NULL,
  `reviewer_email`  VARCHAR(200) DEFAULT NULL,
  `rating`          TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `comment`         TEXT DEFAULT NULL,
  `status`          ENUM('pending','approved','rejected') DEFAULT 'pending',
  `rejection_note`  TEXT DEFAULT NULL,
  `approved_by`     INT UNSIGNED DEFAULT NULL,
  `approved_at`     DATETIME DEFAULT NULL,
  `owner_approved`  TINYINT(1) DEFAULT 0,
  `ip_address`      VARCHAR(45) DEFAULT NULL,
  `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`listing_id`)  REFERENCES `business_listings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`approved_by`) REFERENCES `admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
CREATE TABLE `activity_logs` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `actor_type`  ENUM('admin','user') NOT NULL,
  `actor_id`    INT UNSIGNED NOT NULL,
  `action`      VARCHAR(200) NOT NULL,
  `description` VARCHAR(500) DEFAULT NULL,
  `target_type` VARCHAR(50) DEFAULT NULL,
  `target_id`   INT UNSIGNED DEFAULT NULL,
  `city_id`     INT UNSIGNED DEFAULT NULL,
  `ip_address`  VARCHAR(45) DEFAULT NULL,
  `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
