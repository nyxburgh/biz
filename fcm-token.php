<?php
/**
 * FCM Token Registration Endpoint
 * POST /biz/fcm-token.php
 * Body: { token: "...", city_slug: "..." }
 */
if (!defined('BASE_PATH')) define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/core/Database.php';

if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$token = isset($input['token']) ? trim($input['token']) : '';
$citySlug = isset($input['city_slug']) ? trim($input['city_slug']) : null;

if (empty($token)) {
    http_response_code(422);
    echo json_encode(['error' => 'Token required']);
    exit;
}

$userId = !empty($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

try {
    // Upsert: insert or update existing token
    Database::execute(
        "INSERT INTO fcm_tokens (user_id, token, device_type, city_slug, created_at, updated_at)
         VALUES (?, ?, 'web', ?, NOW(), NOW())
         ON DUPLICATE KEY UPDATE user_id = VALUES(user_id), city_slug = VALUES(city_slug), updated_at = NOW()",
        [$userId, $token, $citySlug]
    );

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log('FCM token save error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save token']);
}
