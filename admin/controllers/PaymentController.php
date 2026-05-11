<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/shared/models/PaymentModel.php';

class PaymentController extends Controller
{
    private PaymentModel $model;

    public function __construct()
    {
        $this->model = new PaymentModel();
    }

    public function index(): void
    {
        $this->requireAuth();
        $filters = [
            'search' => $this->sanitize($this->input('search', '')),
            'status' => $this->input('status', ''),
            'plan'   => $this->input('plan', ''),
            'from'   => $this->input('from', ''),
            'to'     => $this->input('to', ''),
        ];
        $page  = max(1, (int) $this->input('page', 1));
        if (Auth::isCityAdmin()) $filters['_city_scope'] = Auth::cityId();
        $pager = $this->model->getAllWithRelations($filters, $page);
        $plans = Database::fetchAll("SELECT * FROM plans ORDER BY sort_order");
        $csrf  = $this->csrfToken();
        $this->view('payments.index', compact('pager', 'filters', 'plans', 'csrf'));
    }

    public function confirm(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id  = (int) $this->input('id');
        $pay = $this->model->find($id);
        if (!$pay) {
            $this->redirect(BASE_URL . '/admin/payments');
        }
        $this->model->update($id, [
            'status'       => 'confirmed',
            'confirmed_by' => Auth::id(),
            'confirmed_at' => date('Y-m-d H:i:s'),
        ]);
        $plan = Database::fetchOne("SELECT * FROM plans WHERE id = ?", [$pay['plan_id']]);
        if ($plan) {
            Database::execute(
                "UPDATE users SET plan_id=?, plan_expires_at=DATE_ADD(NOW(), INTERVAL ? DAY), status='active' WHERE id=?",
                [$plan['id'], $plan['duration_days'] ?? 365, $pay['user_id']]
            );
            Database::execute(
                "UPDATE business_listings SET plan_level = ?, updated_at = NOW() WHERE user_id = ?",
                [$plan['name'], $pay['user_id']]
            );
        }
        Helper::flash('success', 'Payment confirmed. User plan upgraded.');
        $this->redirect(BASE_URL . '/admin/payments');
    }

    public function reject(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id = (int) $this->input('id');
        $this->model->update($id, [
            'status' => 'rejected',
            'note'   => $this->sanitize($this->input('note', '')),
        ]);
        Helper::flash('info', 'Payment rejected.');
        $this->redirect(BASE_URL . '/admin/payments');
    }
}
