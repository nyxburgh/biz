USE `bizguide`;

INSERT INTO `categories` (`name`, `slug`, `description`, `sort_order`, `status`)
VALUES
('Cafe', 'cafe', 'Coffee shops, tea rooms, and quick refreshment points.', 10, 'active'),
('Chocolate shops', 'chocolate-shops', 'Homemade chocolate, sweets, and local treats.', 20, 'active'),
('Stationery', 'stationery', 'Stationery, school supplies, and office essentials.', 30, 'active'),
('Bakery', 'bakery', 'Fresh bread, cakes, and baked snacks.', 40, 'active'),
('Hotels', 'hotels', 'Hotels, resorts, and guest stays.', 50, 'active'),
('Restaurant', 'restaurant', 'Restaurants, dining, and family food spots.', 60, 'active'),
('Photography', 'photography', 'Photo studios, event photography, and video services.', 70, 'active')
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `description` = VALUES(`description`),
  `sort_order` = VALUES(`sort_order`),
  `status` = VALUES(`status`);

INSERT INTO `users` (`city_id`, `plan_id`, `name`, `email`, `email_verified`, `phone`, `phone_verified`, `profession`, `password`, `user_type`, `status`, `plan_expires_at`)
SELECT c.id, p.id, x.name, x.email, 1, x.phone, 1, x.profession, '', 'owner', 'active', DATE_ADD(CURDATE(), INTERVAL 365 DAY)
FROM `cities` c
JOIN `plans` p
JOIN (
  SELECT 'basic' plan_name, 'Arun Travels' name, 'sample.basic1@bizguide.local' email, '9100001001' phone, 'Travel Operator' profession UNION ALL
  SELECT 'basic', 'Kodai Tent Service', 'sample.basic2@bizguide.local', '9100001002', 'Tent House Owner' UNION ALL
  SELECT 'basic', 'Hill View Stationery', 'sample.basic3@bizguide.local', '9100001003', 'Stationery Shop Owner' UNION ALL
  SELECT 'basic', 'Fresh Oven Bakery', 'sample.basic4@bizguide.local', '9100001004', 'Bakery Owner' UNION ALL
  SELECT 'basic', 'Lake Road Cafe', 'sample.basic5@bizguide.local', '9100001005', 'Cafe Owner' UNION ALL
  SELECT 'premium', 'Misty Meadows Homestay', 'sample.premium1@bizguide.local', '9100002001', 'Homestay Owner' UNION ALL
  SELECT 'premium', 'Kodai Chocolate Corner', 'sample.premium2@bizguide.local', '9100002002', 'Chocolate Shop Owner' UNION ALL
  SELECT 'premium', 'Silver Spoon Restaurant', 'sample.premium3@bizguide.local', '9100002003', 'Restaurant Owner' UNION ALL
  SELECT 'premium', 'Cloud Valley Resort', 'sample.premium4@bizguide.local', '9100002004', 'Hotel Owner' UNION ALL
  SELECT 'premium', 'Misty Lens Studio', 'sample.premium5@bizguide.local', '9100002005', 'Photographer' UNION ALL
  SELECT 'pro', 'GreenHeal Ayurveda Clinic', 'sample.pro1@bizguide.local', '9100003001', 'Wellness Clinic Owner' UNION ALL
  SELECT 'pro', 'Mountain View Tours', 'sample.pro2@bizguide.local', '9100003002', 'Tour Operator' UNION ALL
  SELECT 'pro', 'Kodaikanal Grand Hotel', 'sample.pro3@bizguide.local', '9100003003', 'Hotel Manager' UNION ALL
  SELECT 'pro', 'Elite Event Tent House', 'sample.pro4@bizguide.local', '9100003004', 'Event Service Owner' UNION ALL
  SELECT 'pro', 'Kodai Artisan Cafe', 'sample.pro5@bizguide.local', '9100003005', 'Cafe Owner'
) x
WHERE c.slug = 'kodaikanal' AND p.name = x.plan_name
ON DUPLICATE KEY UPDATE
  `city_id` = VALUES(`city_id`),
  `plan_id` = VALUES(`plan_id`),
  `name` = VALUES(`name`),
  `phone` = VALUES(`phone`),
  `phone_verified` = VALUES(`phone_verified`),
  `profession` = VALUES(`profession`),
  `user_type` = VALUES(`user_type`),
  `status` = VALUES(`status`),
  `plan_expires_at` = VALUES(`plan_expires_at`);

INSERT INTO `business_listings` (`user_id`, `city_id`, `category_id`, `plan_level`, `business_name`, `profession`, `address`, `phone`, `whatsapp`, `email`, `short_description`, `website`, `slug`, `status`, `approved_by`, `approved_at`, `published_at`, `views`, `is_featured`)
SELECT u.id, c.id, cat.id, x.plan_level, x.business_name, x.profession, x.address, x.phone, x.phone, u.email, x.short_description, x.website, x.slug, 'approved', 1, NOW(), NOW(), x.views, x.is_featured
FROM `cities` c
JOIN `users` u
JOIN `categories` cat
JOIN (
  SELECT 'sample.basic1@bizguide.local' email, 'travels' cat_slug, 'basic' plan_level, 'Arun Travels' business_name, 'Travel Operator' profession, 'Anna Salai, Kodaikanal' address, '9100001001' phone, 'Local cab bookings, sightseeing rides, pickup drops, and family trip support around Kodaikanal.' short_description, NULL website, 'sample-basic-arun-travels' slug, 21 views, 0 is_featured UNION ALL
  SELECT 'sample.basic2@bizguide.local', 'tentHouse', 'basic', 'Kodai Tent Service', 'Tent House Owner', 'Naidupuram, Kodaikanal', '9100001002', 'Tent, chair, stage, and event utility rentals for family functions and small programs.', NULL, 'sample-basic-kodai-tent-service', 18, 0 UNION ALL
  SELECT 'sample.basic3@bizguide.local', 'stationery', 'basic', 'Hill View Stationery', 'Stationery Shop Owner', 'Moonjikkal, Kodaikanal', '9100001003', 'School notebooks, office files, art material, printing support, and everyday stationery.', NULL, 'sample-basic-hill-view-stationery', 14, 0 UNION ALL
  SELECT 'sample.basic4@bizguide.local', 'bakery', 'basic', 'Fresh Oven Bakery', 'Bakery Owner', 'Seven Roads Junction, Kodaikanal', '9100001004', 'Fresh bread, tea cakes, biscuits, birthday cake orders, and quick bakery snacks.', NULL, 'sample-basic-fresh-oven-bakery', 16, 0 UNION ALL
  SELECT 'sample.basic5@bizguide.local', 'cafe', 'basic', 'Lake Road Cafe', 'Cafe Owner', 'Lake Road, Kodaikanal', '9100001005', 'Tea, coffee, sandwiches, and relaxed cafe seating near the lake area.', NULL, 'sample-basic-lake-road-cafe', 20, 0 UNION ALL
  SELECT 'sample.premium1@bizguide.local', 'hotels', 'premium', 'Misty Meadows Homestay', 'Homestay Owner', 'Bryant Park Road, Kodaikanal', '9100002001', 'Cozy hillside rooms, valley views, home-cooked meals, bonfire evenings, and family stay packages.', 'https://example.com/misty-meadows', 'sample-premium-misty-meadows-homestay', 72, 1 UNION ALL
  SELECT 'sample.premium2@bizguide.local', 'chocolate-shops', 'premium', 'Kodai Chocolate Corner', 'Chocolate Shop Owner', 'PT Road, Kodaikanal', '9100002002', 'Homemade chocolates, gift boxes, fudge, dry fruit chocolates, and local sweet treats.', 'https://example.com/kodai-chocolate-corner', 'sample-premium-kodai-chocolate-corner', 65, 1 UNION ALL
  SELECT 'sample.premium3@bizguide.local', 'restaurant', 'premium', 'Silver Spoon Restaurant', 'Restaurant Owner', 'Lake Road, Kodaikanal', '9100002003', 'Multi-cuisine dining, family seating, group lunch options, and lake-side meal plans.', 'https://example.com/silver-spoon', 'sample-premium-silver-spoon-restaurant', 88, 1 UNION ALL
  SELECT 'sample.premium4@bizguide.local', 'hotels', 'premium', 'Cloud Valley Resort', 'Hotel Owner', 'Coakers Walk Area, Kodaikanal', '9100002004', 'Premium rooms, mountain views, travel desk, restaurant, and curated holiday stays.', 'https://example.com/cloud-valley-resort', 'sample-premium-cloud-valley-resort', 91, 1 UNION ALL
  SELECT 'sample.premium5@bizguide.local', 'photography', 'premium', 'Misty Lens Studio', 'Photographer', 'Observatory Road, Kodaikanal', '9100002005', 'Outdoor portraits, wedding photography, product shoots, and event video coverage.', 'https://example.com/misty-lens-studio', 'sample-premium-misty-lens-studio', 54, 1 UNION ALL
  SELECT 'sample.pro1@bizguide.local', 'restaurant', 'pro', 'GreenHeal Ayurveda Clinic', 'Wellness Clinic Owner', 'Club Road, Kodaikanal', '9100003001', 'Ayurvedic consultation, wellness therapies, herbal care, and customized rejuvenation programs.', 'https://example.com/greenheal-ayurveda', 'sample-pro-greenheal-ayurveda-clinic', 140, 1 UNION ALL
  SELECT 'sample.pro2@bizguide.local', 'travels', 'pro', 'Mountain View Tours', 'Tour Operator', 'Bus Stand Road, Kodaikanal', '9100003002', 'Guided treks, sightseeing packages, local transport, group tours, and custom travel plans.', 'https://example.com/mountain-view-tours', 'sample-pro-mountain-view-tours', 156, 1 UNION ALL
  SELECT 'sample.pro3@bizguide.local', 'hotels', 'pro', 'Kodaikanal Grand Hotel', 'Hotel Manager', 'Convent Road, Kodaikanal', '9100003003', 'Luxury rooms, restaurant, conference support, travel desk, and family vacation packages.', 'https://example.com/kodaikanal-grand-hotel', 'sample-pro-kodaikanal-grand-hotel', 132, 1 UNION ALL
  SELECT 'sample.pro4@bizguide.local', 'tentHouse', 'pro', 'Elite Event Tent House', 'Event Service Owner', 'Vilpatti Road, Kodaikanal', '9100003004', 'Large event tents, decoration support, chairs, tables, lighting, and complete function setup.', 'https://example.com/elite-event-tent-house', 'sample-pro-elite-event-tent-house', 118, 1 UNION ALL
  SELECT 'sample.pro5@bizguide.local', 'cafe', 'pro', 'Kodai Artisan Cafe', 'Cafe Owner', 'Coakers Walk Road, Kodaikanal', '9100003005', 'Specialty coffee, handmade desserts, brunch plates, work-friendly seating, and scenic ambience.', 'https://example.com/kodai-artisan-cafe', 'sample-pro-kodai-artisan-cafe', 126, 1
) x
WHERE c.slug = 'kodaikanal' AND u.email = x.email AND cat.slug = x.cat_slug
ON DUPLICATE KEY UPDATE
  `city_id` = VALUES(`city_id`),
  `category_id` = VALUES(`category_id`),
  `plan_level` = VALUES(`plan_level`),
  `business_name` = VALUES(`business_name`),
  `profession` = VALUES(`profession`),
  `address` = VALUES(`address`),
  `phone` = VALUES(`phone`),
  `whatsapp` = VALUES(`whatsapp`),
  `email` = VALUES(`email`),
  `short_description` = VALUES(`short_description`),
  `website` = VALUES(`website`),
  `status` = VALUES(`status`),
  `approved_by` = VALUES(`approved_by`),
  `approved_at` = VALUES(`approved_at`),
  `published_at` = VALUES(`published_at`),
  `views` = VALUES(`views`),
  `is_featured` = VALUES(`is_featured`);

SELECT bl.plan_level, COUNT(*) AS sample_count
FROM `business_listings` bl
JOIN `users` u ON u.id = bl.user_id
WHERE bl.city_id = (SELECT id FROM `cities` WHERE slug = 'kodaikanal')
  AND u.email LIKE 'sample.%@bizguide.local'
GROUP BY bl.plan_level
ORDER BY FIELD(bl.plan_level, 'basic', 'premium', 'pro');
