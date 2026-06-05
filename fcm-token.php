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
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
if (!is_array($input)) {
    $input = $_POST;
}

$token = isset($input['token']) ? trim((string)$input['token']) : '';
$citySlug = isset($input['city_slug']) ? trim((string)$input['city_slug']) : null;
$deviceType = isset($input['device_type']) ? trim((string)$input['device_type']) : 'web';

if ($citySlug === '') {
    $citySlug = null;
}

if (!preg_match('/^[a-z0-9_-]{1,64}$/i', (string)$citySlug)) {
    $citySlug = null;
}

if (!preg_match('/^[a-z0-9_-]{1,32}$/i', $deviceType)) {
    $deviceType = 'web';
}

if (empty($token)) {
    http_response_code(422);
    echo json_encode(['error' => 'Token required']);
    exit;
}

if (strlen($token) > 4096) {
    http_response_code(422);
    echo json_encode(['error' => 'Token too long']);
    exit;
}

$userId = !empty($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

try {
    // Upsert: insert or update existing token
    Database::execute(
        "INSERT INTO fcm_tokens (user_id, token, device_type, city_slug, created_at, updated_at)
         VALUES (?, ?, ?, ?, NOW(), NOW())
         ON DUPLICATE KEY UPDATE user_id = VALUES(user_id), device_type = VALUES(device_type), city_slug = VALUES(city_slug), updated_at = NOW()",
        [$userId, $token, $deviceType, $citySlug]
    );

    echo json_encode([
        'success' => true,
        'city_slug' => $citySlug,
        'device_type' => $deviceType,
    ]);
} catch (Exception $e) {
    error_log('FCM token save error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save token']);
}
