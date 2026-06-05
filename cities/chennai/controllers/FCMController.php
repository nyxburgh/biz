<?php
/**
 * FCMController — Serves the Firebase Messaging service worker and saves FCM tokens.
 * Routes added in city index.php:
 *   GET  .../firebase-messaging-sw.js  -> FCMController::sw
 *   GET  .../fcm-sw.js                 -> FCMController::sw  (alias)
 *   POST .../fcm-token                 -> FCMController::saveToken
 */
class FCMController extends Controller
{
    /**
     * Serve the Firebase Messaging service worker JS with correct headers.
     * The SW must be served from the same origin as the page.
     */
    public function sw(): void
    {
        $swFile = BASE_PATH . '/firebase-messaging-sw.js';
        if (!file_exists($swFile)) {
            http_response_code(404);
            header('Content-Type: text/plain');
            echo 'Service worker not found.';
            exit;
        }

        // SW must be served as JS with no-cache so updates are picked up
        header('Content-Type: application/javascript; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Service-Worker-Allowed: /');
        readfile($swFile);
        exit;
    }

    /**
     * Save/update an FCM registration token for the current visitor.
     * Accepts JSON body: { token, city_slug, device_type }
     */
    public function saveToken(): void
    {
        if (!defined('BASE_PATH')) {
            http_response_code(500);
            echo json_encode(['error' => 'Server misconfiguration']);
            exit;
        }

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

        $token      = isset($input['token'])       ? trim((string)$input['token'])       : '';
        $citySlug   = isset($input['city_slug'])   ? trim((string)$input['city_slug'])   : null;
        $deviceType = isset($input['device_type']) ? trim((string)$input['device_type']) : 'mobile-web';

        // Default city_slug to the current city
        if (empty($citySlug) && defined('CITY_SLUG')) {
            $citySlug = CITY_SLUG;
        }

        if ($citySlug === '') {
            $citySlug = null;
        }

        if (!preg_match('/^[a-z0-9_-]{1,64}$/i', (string)$citySlug)) {
            $citySlug = defined('CITY_SLUG') ? CITY_SLUG : null;
        }

        if (!preg_match('/^[a-z0-9_-]{1,32}$/i', $deviceType)) {
            $deviceType = 'mobile-web';
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

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = !empty($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

        try {
            Database::execute(
                "INSERT INTO fcm_tokens (user_id, token, device_type, city_slug, created_at, updated_at)
                 VALUES (?, ?, ?, ?, NOW(), NOW())
                 ON DUPLICATE KEY UPDATE
                   user_id     = VALUES(user_id),
                   device_type = VALUES(device_type),
                   city_slug   = VALUES(city_slug),
                   updated_at  = NOW()",
                [$userId, $token, $deviceType, $citySlug]
            );

            echo json_encode([
                'success'     => true,
                'city_slug'   => $citySlug,
                'device_type' => $deviceType,
            ]);
        } catch (Exception $e) {
            error_log('FCMController::saveToken error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Failed to save token']);
        }
        exit;
    }
}
