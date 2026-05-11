<?php
abstract class Controller
{
    protected string $viewBase = '';

    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $path = str_replace('.', '/', $view) . '.php';
        $file = $this->viewBase
            ? $this->viewBase . '/' . $path
            : BASE_PATH . '/admin/views/' . $path;
        if (!file_exists($file)) die("View not found: $file");
        require $file;
    }

    protected function redirect(string $url): never
    {
        header("Location: $url");
        exit;
    }

    protected function json(array $data, int $code = 200): never
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['admin_id'])) {
            $this->redirect(BASE_URL . '/admin/login');
        }
    }

    protected function requireSuperAdmin(): void
    {
        $this->requireAuth();
        if (!Auth::isSuperAdmin()) {
            Helper::flash('error', 'Access denied. Super admin only.');
            $this->redirect(BASE_URL . '/admin/dashboard');
        }
    }

    /**
     * Returns city WHERE clause fragment and params for scoped queries.
     * Super admin: no scope (sees all).
     * City admin: scoped to their assigned city.
     *
     * Usage: [$where, $params] = $this->cityScope('bl.city_id');
     *        $sql = "SELECT ... FROM ... WHERE 1=1 $where";
     */
    protected function cityScope(string $column = 'city_id'): array
    {
        if (Auth::isCityAdmin() && Auth::cityId()) {
            return ["AND $column = ?", [Auth::cityId()]];
        }
        return ['', []];
    }

    /**
     * For WHERE-less queries (no existing WHERE).
     */
    protected function cityScopeWhere(string $column = 'city_id'): array
    {
        if (Auth::isCityAdmin() && Auth::cityId()) {
            return ["WHERE $column = ?", [Auth::cityId()]];
        }
        return ['WHERE 1=1', []];
    }

    protected function logActivity(string $action, string $description = '', string $targetType = '', int $targetId = 0): void
    {
        Database::execute(
            "INSERT INTO activity_logs (actor_type, actor_id, action, description, target_type, target_id, city_id, ip_address)
             VALUES ('admin', ?, ?, ?, ?, ?, ?, ?)",
            [
                Auth::id(),
                $action,
                $description,
                $targetType ?: null,
                $targetId ?: null,
                Auth::cityId(),
                $_SERVER['REMOTE_ADDR'] ?? null,
            ]
        );
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function sanitize(string $val): string
    {
        return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
    }

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('CSRF token mismatch.');
        }
    }
}
