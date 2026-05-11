<?php
require_once BASE_PATH . '/core/Controller.php';

class PlanController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $plans = Database::fetchAll(
            "SELECT p.*, COUNT(u.id) AS user_count
             FROM plans p
             LEFT JOIN users u ON u.plan_id = p.id
             GROUP BY p.id ORDER BY p.sort_order"
        );
        $csrf = $this->csrfToken();
        $this->view('plans.index', compact('plans', 'csrf'));
    }

    public function update(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id = (int) $this->input('id');
        Database::execute(
            "UPDATE plans SET label=?, price=?, duration_days=?, status=? WHERE id=?",
            [
                $this->sanitize($this->input('label', '')),
                (float) $this->input('price', 0),
                (int)   $this->input('duration_days', 365),
                $this->input('status', 'active'),
                $id,
            ]
        );
        Helper::flash('success', 'Plan updated.');
        $this->redirect(BASE_URL . '/admin/plans');
    }
}
