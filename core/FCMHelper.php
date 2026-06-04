<?php
/**
 * FCMHelper — Server-side Firebase Cloud Messaging (HTTP v1 API)
 * Uses service account credentials from config/service-account.json
 */
class FCMHelper
{
    private static ?string $accessToken = null;
    private static int $tokenExpiry = 0;

    private static function getServiceAccount(): array
    {
        $path = BASE_PATH . '/config/service-account.json';
        if (!file_exists($path)) {
            throw new RuntimeException('FCM service account file not found.');
        }
        $data = json_decode(file_get_contents($path), true);
        if (!$data) {
            throw new RuntimeException('FCM service account JSON is invalid.');
        }
        return $data;
    }

    /**
     * Generate a Google OAuth2 access token using the service account private key.
     */
    private static function getAccessToken(): string
    {
        if (self::$accessToken && time() < self::$tokenExpiry - 60) {
            return self::$accessToken;
        }

        $sa = self::getServiceAccount();
        $now = time();
        $exp = $now + 3600;

        $header = self::base64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $payload = self::base64url(json_encode([
            'iss'   => $sa['client_email'],
            'sub'   => $sa['client_email'],
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $exp,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        ]));

        $sigInput = $header . '.' . $payload;
        $privateKey = openssl_pkey_get_private($sa['private_key']);
        if (!$privateKey) {
            throw new RuntimeException('FCM: Failed to load private key.');
        }
        openssl_sign($sigInput, $sig, $privateKey, 'SHA256');
        $jwt = $sigInput . '.' . self::base64url($sig);

        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT        => 10,
        ]);
        $resp = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new RuntimeException('FCM token request failed: ' . $err);
        }

        $body = json_decode($resp, true);
        if (empty($body['access_token'])) {
            throw new RuntimeException('FCM: No access token in response. ' . $resp);
        }

        self::$accessToken = $body['access_token'];
        self::$tokenExpiry = $now + (int)($body['expires_in'] ?? 3600);
        return self::$accessToken;
    }

    private static function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Send a notification to a single FCM token.
     */
    public static function sendToToken(string $token, string $title, string $body, array $data = []): bool
    {
        return self::sendToTokens([$token], $title, $body, $data);
    }

    /**
     * Send a notification to multiple FCM tokens (batched per FCM limit).
     */
    public static function sendToTokens(array $tokens, string $title, string $body, array $data = []): bool
    {
        $result = self::sendToTokensDetailed($tokens, $title, $body, $data);
        return $result['total'] > 0 && $result['failed'] === 0 && $result['sent'] > 0;
    }

    public static function sendToTokensDetailed(array $tokens, string $title, string $body, array $data = []): array
    {
        $tokens = array_values(array_unique(array_filter(array_map('trim', $tokens))));
        $result = [
            'total'   => count($tokens),
            'sent'    => 0,
            'failed'  => 0,
            'removed' => 0,
            'errors'  => [],
        ];

        if (empty($tokens)) {
            return $result;
        }

        $sa = self::getServiceAccount();
        $projectId = $sa['project_id'];
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        try {
            $accessToken = self::getAccessToken();
        } catch (RuntimeException $e) {
            error_log('FCMHelper::getAccessToken error: ' . $e->getMessage());
            $result['failed'] = $result['total'];
            $result['errors'][] = $e->getMessage();
            return $result;
        }

        foreach ($tokens as $token) {
            $message = [
                'message' => [
                    'token'        => $token,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                    'webpush' => [
                        'notification' => [
                            'title' => $title,
                            'body'  => $body,
                            'icon'  => BASE_URL . '/assets/icons/icon-192.png',
                        ],
                        'fcm_options' => [
                            'link' => isset($data['click_action']) ? $data['click_action'] : BASE_URL,
                        ],
                    ],
                ],
            ];

            if (!empty($data)) {
                // FCM data values must be strings
                $stringData = [];
                foreach ($data as $k => $v) {
                    $stringData[$k] = (string)$v;
                }
                $message['message']['data'] = $stringData;
            }

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json',
                ],
                CURLOPT_POSTFIELDS     => json_encode($message),
                CURLOPT_TIMEOUT        => 10,
            ]);
            $resp   = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err    = curl_error($ch);
            curl_close($ch);

            if ($err || $httpCode >= 400) {
                $errorMessage = $err ?: $resp;
                error_log("FCMHelper send error (token: {$token}): HTTP {$httpCode} — " . $errorMessage);
                $result['failed']++;
                if (count($result['errors']) < 5) {
                    $result['errors'][] = "HTTP {$httpCode}: " . self::summarizeFcmError($errorMessage);
                }
                // Remove invalid tokens from DB
                if ($httpCode === 404 || $httpCode === 400) {
                    self::removeInvalidToken($token);
                    $result['removed']++;
                }
            } else {
                $result['sent']++;
            }
        }

        return $result;
    }

    private static function summarizeFcmError(string $error): string
    {
        $decoded = json_decode($error, true);
        if (isset($decoded['error']['message'])) {
            return $decoded['error']['message'];
        }
        return substr(trim($error), 0, 180);
    }

    /**
     * Send notification to all tokens belonging to a user.
     */
    public static function sendToUser(int $userId, string $title, string $body, array $data = []): bool
    {
        $rows = Database::fetchAll(
            "SELECT token FROM fcm_tokens WHERE user_id = ?",
            [$userId]
        );
        if (empty($rows)) {
            return true;
        }
        $tokens = array_column($rows, 'token');
        return self::sendToTokens($tokens, $title, $body, $data);
    }

    /**
     * Send notification to all stored tokens (broadcast).
     */
    public static function sendToAll(string $title, string $body, array $data = []): bool
    {
        $rows = Database::fetchAll("SELECT token FROM fcm_tokens");
        if (empty($rows)) {
            return true;
        }
        $tokens = array_column($rows, 'token');
        return self::sendToTokens($tokens, $title, $body, $data);
    }

    /**
     * Remove a token that FCM reported as invalid/unregistered.
     */
    private static function removeInvalidToken(string $token): void
    {
        try {
            Database::execute("DELETE FROM fcm_tokens WHERE token = ?", [$token]);
        } catch (Exception $e) {
            error_log('FCMHelper::removeInvalidToken DB error: ' . $e->getMessage());
        }
    }
}
