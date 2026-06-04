<?php
class NotificationController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $pageTitle  = 'Push Notifications';
        $tokenCount = 0;
        $userCount  = 0;
        $tokens     = [];

        try {
            $tokenCount = (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM fcm_tokens")['c'] ?? 0);
            $userCount  = (int)(Database::fetchOne("SELECT COUNT(DISTINCT user_id) AS c FROM fcm_tokens WHERE user_id IS NOT NULL")['c'] ?? 0);
            $tokens     = Database::fetchAll(
                "SELECT ft.id, ft.user_id, ft.token, ft.city_slug, ft.created_at, ft.updated_at, u.name AS user_name
                 FROM fcm_tokens ft
                 LEFT JOIN users u ON ft.user_id = u.id
                 ORDER BY ft.updated_at DESC, ft.id DESC LIMIT 20"
            );
        } catch (Exception $e) {
            Helper::flash('error', 'fcm_tokens table missing. Run patch_fcm_tokens.sql first.');
        }

        $cities = Database::fetchAll("SELECT slug, name FROM cities WHERE status='active' ORDER BY name");
        $csrf   = $this->csrfToken();

        $this->view('notifications.index', compact('pageTitle', 'tokenCount', 'userCount', 'tokens', 'cities', 'csrf'));
    }

    public function send(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $title    = trim($this->input('title', ''));
        $body     = trim($this->input('body', ''));
        $target   = $this->input('target', 'all');
        $citySlug = $this->input('city_slug', '');
        $userId   = (int) $this->input('user_id', 0);
        $link     = trim($this->input('click_action', ''));

        if ($title === '' || $body === '') {
            Helper::flash('error', 'Title and message are required.');
            $this->redirect(BASE_URL . '/admin/notifications');
        }

        $data = [];
        if ($link !== '') {
            $data['click_action'] = $link;
        }

        try {
            require_once BASE_PATH . '/core/FCMHelper.php';

            $result = ['total' => 0, 'sent' => 0, 'failed' => 0, 'removed' => 0, 'errors' => []];
            if ($target === 'user' && $userId > 0) {
                $rows   = Database::fetchAll("SELECT token FROM fcm_tokens WHERE user_id = ?", [$userId]);
                $tokens = array_column($rows, 'token');
                $result = FCMHelper::sendToTokensDetailed($tokens, $title, $body, $data);
            } elseif ($target === 'city' && $citySlug !== '') {
                $rows   = Database::fetchAll("SELECT token FROM fcm_tokens WHERE city_slug = ?", [$citySlug]);
                $tokens = array_column($rows, 'token');
                $result = FCMHelper::sendToTokensDetailed($tokens, $title, $body, $data);
            } else {
                $rows   = Database::fetchAll("SELECT token FROM fcm_tokens");
                $tokens = array_column($rows, 'token');
                $result = FCMHelper::sendToTokensDetailed($tokens, $title, $body, $data);
            }

            if ($result['total'] === 0) {
                Helper::flash('error', 'No registered device tokens found for this target.');
            } elseif ($result['failed'] === 0 && $result['sent'] > 0) {
                Helper::flash('success', "Notification accepted by FCM for {$result['sent']} device(s).");
            } else {
                $message = "FCM accepted {$result['sent']} of {$result['total']} device(s); {$result['failed']} failed.";
                if ($result['removed'] > 0) {
                    $message .= " Removed {$result['removed']} invalid token(s).";
                }
                if (!empty($result['errors'])) {
                    $message .= ' ' . implode(' | ', $result['errors']);
                }
                Helper::flash('error', $message);
            }
        } catch (Exception $e) {
            error_log('NotificationController::send — ' . $e->getMessage());
            Helper::flash('error', 'Error: ' . $e->getMessage());
        }

        $this->redirect(BASE_URL . '/admin/notifications');
    }
}
