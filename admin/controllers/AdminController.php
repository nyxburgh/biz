<?php
require_once BASE_PATH . '/core/Controller.php';

class AdminController extends Controller
{
    public function index(): void
    {
        $this->requireSuperAdmin();
        $admins = Database::fetchAll(
            "SELECT a.*, c.name AS city_name
             FROM admins a
             LEFT JOIN cities c ON a.assigned_city_id = c.id
             ORDER BY a.role, a.name"
        );
        $cities = Database::fetchAll("SELECT id, name FROM cities WHERE status='active' ORDER BY name");
        $csrf   = $this->csrfToken();
        $this->view('admins.index', compact('admins', 'cities', 'csrf'));
    }

    public function store(): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();
        $name   = $this->sanitize($this->input('name', ''));
        $email  = $this->sanitize($this->input('email', ''));
        $role   = $this->input('role', 'city_admin');
        $cityId = (int) $this->input('assigned_city_id') ?: null;

        if (!$name || !$email) {
            Helper::flash('error', 'Name and email required.');
            $this->redirect(BASE_URL . '/admin/admins');
        }
        if (Database::fetchOne("SELECT id FROM admins WHERE email=?", [$email])) {
            Helper::flash('error', 'Email already exists.');
            $this->redirect(BASE_URL . '/admin/admins');
        }

        $password = $this->input('password', '') ?: 'admin@123';
        Database::execute(
            "INSERT INTO admins (name, email, password, role, assigned_city_id) VALUES (?,?,?,?,?)",
            [$name, $email, Auth::hashPassword($password), $role, $role === 'super_admin' ? null : $cityId]
        );
        $this->logActivity('create_admin', "Created admin: $name ($role)");
        Helper::flash('success', "Admin created. Password: $password");
        $this->redirect(BASE_URL . '/admin/admins');
    }

    public function update(): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();
        $id     = (int) $this->input('id');
        $role   = $this->input('role', 'city_admin');
        $cityId = (int) $this->input('assigned_city_id') ?: null;

        Database::execute(
            "UPDATE admins SET name=?, role=?, assigned_city_id=?, status=? WHERE id=?",
            [
                $this->sanitize($this->input('name', '')),
                $role,
                $role === 'super_admin' ? null : $cityId,
                $this->input('status', 'active'),
                $id,
            ]
        );
        Helper::flash('success', 'Admin updated.');
        $this->redirect(BASE_URL . '/admin/admins');
    }

    public function resetPassword(): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();
        $id       = (int) $this->input('id');
        $password = $this->input('password', '') ?: 'admin@123';
        Database::execute("UPDATE admins SET password=? WHERE id=?", [Auth::hashPassword($password), $id]);
        Helper::flash('success', "Password reset. New password: $password");
        $this->redirect(BASE_URL . '/admin/admins');
    }

    public function delete(): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();
        $id = (int) $this->input('id');
        if ($id === Auth::id()) {
            Helper::flash('error', 'Cannot delete your own account.');
            $this->redirect(BASE_URL . '/admin/admins');
        }
        Database::execute("DELETE FROM admins WHERE id=?", [$id]);
        Helper::flash('success', 'Admin deleted.');
        $this->redirect(BASE_URL . '/admin/admins');
    }
}
