-- ============================================================
-- BizGuide — Patch: Ratings & Reviews
-- Run: mysql -u root -p bizguide < database/patch_reviews.sql
-- ============================================================

USE `bizguide`;

CREATE TABLE IF NOT EXISTS `listing_reviews` (
  `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `listing_id`      INT UNSIGNED NOT NULL,
  `user_id`         INT UNSIGNED DEFAULT NULL,        -- logged-in user, or NULL for guest
  `reviewer_name`   VARCHAR(150) NOT NULL,
  `reviewer_phone`  VARCHAR(20) DEFAULT NULL,
  `reviewer_email`  VARCHAR(200) DEFAULT NULL,
  `rating`          TINYINT UNSIGNED NOT NULL DEFAULT 5, -- 1 to 5
  `comment`         TEXT DEFAULT NULL,
  `status`          ENUM('pending','approved','rejected') DEFAULT 'pending',
  `rejection_note`  TEXT DEFAULT NULL,
  `approved_by`     INT UNSIGNED DEFAULT NULL,        -- admin who approved
  `approved_at`     DATETIME DEFAULT NULL,
  `owner_approved`  TINYINT(1) DEFAULT 0,             -- 1 if business owner pre-approved
  `ip_address`      VARCHAR(45) DEFAULT NULL,
  `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`listing_id`)  REFERENCES `business_listings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`approved_by`) REFERENCES `admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;
