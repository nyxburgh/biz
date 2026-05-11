<?php
require_once 'config/config.php';
require_once 'core/Database.php';

$citySlug = 'kodaikanal';
$city = Database::fetchOne("SELECT * FROM cities WHERE slug=?", [$citySlug]);
if (!$city) {
    echo "City not found: $citySlug\n";
    exit;
}

echo "City: {$city['name']} (ID: {$city['id']})\n";

$listings = Database::fetchAll("SELECT id, business_name, plan_level, status, user_id FROM business_listings WHERE city_id=?", [$city['id']]);
echo "Total Listings: " . count($listings) . "\n";
foreach ($listings as $l) {
    echo "ID: {$l['id']} | Name: {$l['business_name']} | Plan: {$l['plan_level']} | Status: {$l['status']}\n";
    $user = Database::fetchOne("SELECT u.*, pl.name as plan_name FROM users u LEFT JOIN plans pl ON u.plan_id = pl.id WHERE u.id=?", [$l['user_id']]);
    echo "  User Plan: " . ($user['plan_name'] ?? 'N/A') . "\n";
}

$proListings = Database::fetchAll("SELECT * FROM business_listings WHERE city_id=? AND plan_level='pro' AND status='approved'", [$city['id']]);
echo "Approved Pro Listings: " . count($proListings) . "\n";
