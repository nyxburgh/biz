<?php
require_once BASE_PATH . '/core/Controller.php';

class CityBaseController extends Controller
{
    public function __construct()
    {
        $this->viewBase = CITY_DIR . '/views';
    }

    protected function requireUserAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Please login to continue.'], 401);
            }
            Helper::flash('info', 'Please login to continue.');
            $this->redirect(CITY_URL . '/login');
        }
    }

    protected function currentUser(): array
    {
        return Database::fetchOne(
            "SELECT u.*, pl.name AS plan_name, pl.label AS plan_label, c.name AS city_name
             FROM users u
             LEFT JOIN plans pl ON u.plan_id = pl.id
             LEFT JOIN cities c ON u.city_id = c.id
             WHERE u.id = ?",
            [$_SESSION['user_id']]
        ) ?? [];
    }

    protected function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            || str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')
            || str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/x-www-form-urlencoded');
    }
}
