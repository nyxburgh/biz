<?php
require_once BASE_PATH . '/core/Controller.php';

class AdminAuthController extends Controller
{
    public function login(): void
    {
        if (Auth::check()) {
            $this->redirect(BASE_URL . '/admin/dashboard');
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $email = trim($this->input('email', ''));
            $pass  = trim($this->input('password', ''));
            
            $admin = Database::fetchOne(
                "SELECT * FROM admins WHERE email = ? AND status = 'active'",
                [$email]
            );

            if ($admin && Auth::verifyPassword($pass, $admin['password'])) {
                Auth::login($admin);
                $this->redirect(BASE_URL . '/admin/dashboard');
            }
            
            if (!$admin) {
                $error = 'Email address not found or account inactive.';
            } else {
                $error = 'Incorrect password.';
            }
        }

        $csrf = $this->csrfToken();
        $this->view('auth.login', compact('error', 'csrf'));
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect(BASE_URL . '/admin/login');
    }
}
