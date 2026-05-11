<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/shared/models/UserModel.php';

class UserController extends Controller
{
    private UserModel $model;

    public function __construct() { $this->model = new UserModel(); }

    public function index(): void
    {
        $this->requireAuth();
        $filters = [
            'search' => $this->sanitize($this->input('search', '')),
            'plan'   => $this->input('plan', ''),
            'status' => $this->input('status', ''),
            'city'   => $this->input('city', ''),
        ];
        $page   = max(1, (int) $this->input('page', 1));
        if (Auth::isCityAdmin()) $filters['_city_scope'] = Auth::cityId();
        $pager  = $this->model->getPaidUsers($filters, $page);
        $cities = Database::fetchAll("SELECT id, name FROM cities WHERE status='active' ORDER BY name");
        $plans  = Database::fetchAll("SELECT * FROM plans WHERE name != 'free' ORDER BY sort_order");
        $this->view('users.index', compact('pager', 'filters', 'cities', 'plans'));
    }

    public function freeUsers(): void
    {
        $this->requireAuth();
        $filters = [
            'search' => $this->sanitize($this->input('search', '')),
            'status' => $this->input('status', ''),
            'city'   => $this->input('city', ''),
        ];
        $page   = max(1, (int) $this->input('page', 1));
        if (Auth::isCityAdmin()) $filters['_city_scope'] = Auth::cityId();
        $pager  = $this->model->getFreeUsers($filters, $page);
        $cities = Database::fetchAll("SELECT id, name FROM cities WHERE status='active' ORDER BY name");
        $plans  = Database::fetchAll("SELECT * FROM plans WHERE name != 'free' ORDER BY sort_order");
        $csrf   = $this->csrfToken();
        $this->view('users.free', compact('pager', 'filters', 'cities', 'plans', 'csrf'));
    }

    public function show(string $id): void
    {
        $this->requireAuth();
        $user = $this->model->getWithPlan((int) $id);
        if (!$user) $this->redirect(BASE_URL . '/admin/users');
        $listing = Database::fetchOne(
            "SELECT bl.*, cat.name AS cat_name,
                    (SELECT filename FROM listing_images WHERE listing_id = bl.id ORDER BY sort_order LIMIT 1) AS first_image
             FROM business_listings bl
             LEFT JOIN categories cat ON bl.category_id = cat.id WHERE bl.user_id = ?", [(int)$id]
        );
        $payments = Database::fetchAll(
            "SELECT pay.*, pl.label FROM payments pay
             LEFT JOIN plans pl ON pay.plan_id = pl.id
             WHERE pay.user_id = ? ORDER BY pay.created_at DESC", [(int)$id]
        );
        $plans = Database::fetchAll("SELECT * FROM plans ORDER BY sort_order");
        $csrf  = $this->csrfToken();
        $this->view('users.show', compact('user', 'listing', 'payments', 'plans', 'csrf'));
    }

    public function edit(string $id): void
    {
        $this->requireAuth();
        $user   = $this->model->getWithPlan((int) $id);
        if (!$user) $this->redirect(BASE_URL . '/admin/users');
        $cities = Database::fetchAll("SELECT id, name FROM cities WHERE status='active' ORDER BY name");
        $csrf   = $this->csrfToken();
        $this->view('users.edit', compact('user', 'cities', 'csrf'));
    }

    public function update(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id = (int) $this->input('id');
        $this->model->update($id, [
            'name'       => $this->sanitize($this->input('name', '')),
            'phone'      => $this->sanitize($this->input('phone', '')),
            'email'      => $this->sanitize($this->input('email', '')) ?: null,
            'profession' => $this->sanitize($this->input('profession', '')),
            'city_id'    => (int) $this->input('city_id') ?: null,
        ]);
        Helper::flash('success', 'User updated.');
        $this->redirect(BASE_URL . '/admin/users/' . $id);
    }

    public function toggle(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id   = (int) $this->input('id');
        $user = $this->model->find($id);
        if (!$user) $this->redirect(BASE_URL . '/admin/users');
        $newStatus = $user['status'] === 'active' ? 'suspended' : 'active';
        $this->model->update($id, ['status' => $newStatus]);
        Helper::flash('success', 'User ' . $newStatus . '.');
        $this->redirect($this->input('back', BASE_URL . '/admin/users'));
    }

    public function upgradePlan(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id     = (int) $this->input('id');
        $planId = (int) $this->input('plan_id');
        $plan   = Database::fetchOne("SELECT * FROM plans WHERE id = ?", [$planId]);
        if (!$plan) {
            Helper::flash('error', 'Invalid plan.');
            $this->redirect(BASE_URL . '/admin/users/' . $id);
        }
        $expires = ($plan['name'] !== 'free')
            ? date('Y-m-d', strtotime('+' . ($plan['duration_days'] ?? 365) . ' days'))
            : null;
        $this->model->update($id, [
            'plan_id'         => $planId,
            'plan_expires_at' => $expires,
            'status'          => 'active',
        ]);
        $listing = Database::fetchOne("SELECT id FROM business_listings WHERE user_id = ?", [$id]);
        if ($listing) {
            if ($plan['name'] === 'free') {
                Database::execute("DELETE FROM business_listings WHERE id = ?", [$listing['id']]);
            } else {
                Database::execute("UPDATE business_listings SET plan_level = ?, updated_at = NOW() WHERE id = ?", [$plan['name'], $listing['id']]);
            }
        }
        $this->logActivity('change_plan', 'Plan changed to ' . $plan['label'], 'user', $id);
        Helper::flash('success', 'Plan changed to ' . $plan['label'] . '.');
        $this->redirect(BASE_URL . '/admin/users/' . $id);
    }

    public function create(): void
    {
        $this->requireAuth();
        $cities = Database::fetchAll("SELECT id, name FROM cities WHERE status='active' ORDER BY name");
        $csrf   = $this->csrfToken();
        $this->view('users.create', compact('cities', 'csrf'));
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $name  = $this->sanitize($this->input('name', ''));
        $phone = $this->sanitize($this->input('phone', ''));
        $email = $this->sanitize($this->input('email', ''));
        if (!$name || !$phone) { Helper::flash('error', 'Name and phone required.'); $this->redirect(BASE_URL . '/admin/users/create'); }
        if ($email && Database::fetchOne("SELECT id FROM users WHERE email = ?", [$email])) {
            Helper::flash('error', 'Email already registered.'); $this->redirect(BASE_URL . '/admin/users/create');
        }
        $password = $this->input('password', '') ?: 'bizguide@123';
        $userId   = $this->model->create([
            'name'       => $name, 'email' => $email ?: null, 'phone' => $phone,
            'profession' => $this->sanitize($this->input('profession', '')),
            'password'   => Auth::hashPassword($password),
            'plan_id'    => 1,
            'city_id'    => (int) $this->input('city_id') ?: null,
            'status'     => $this->input('status', 'active'),
        ]);
        $this->logActivity('create_user', "Created user: $name", 'user', $userId);
        Helper::flash('success', "User created. Password: $password");
        $this->redirect(BASE_URL . '/admin/users/' . $userId);
    }

    public function delete(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->model->delete((int) $this->input('id'));
        Helper::flash('success', 'User deleted.');
        $this->redirect(BASE_URL . '/admin/users');
    }
}
