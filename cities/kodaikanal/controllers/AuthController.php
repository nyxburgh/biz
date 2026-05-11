<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/CityBaseController.php';

class AuthController extends CityBaseController
{
    // ── Login / Register page ─────────────────────────────────
    public function login(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirectAfterLogin();
        }
        $csrf      = $this->csrfToken();
        $returnTo  = $this->input('return', '');
        $googleClientId = defined('GOOGLE_CLIENT_ID') ? GOOGLE_CLIENT_ID : '';
        $this->view('auth.login', compact('csrf', 'returnTo', 'googleClientId'));
    }

    // ── Google OAuth callback ─────────────────────────────────
    public function googleCallback(): void
    {
        $this->verifyCsrf();
        $credential = $this->input('credential', '');
        $userType   = $this->input('user_type', 'visitor'); // visitor | owner

        if (!$credential) {
            $this->json(['error' => 'Google login failed.'], 400);
        }

        // Decode JWT (Google ID token) — verify signature in production
        $payload = $this->decodeGoogleJwt($credential);
        if (!$payload) {
            $this->json(['error' => 'Invalid Google token.'], 400);
        }

        $googleId = $payload['sub'];
        $email    = $payload['email'] ?? '';
        $name     = $payload['name']  ?? '';

        // Check existing user by google_id or email
        $user = Database::fetchOne(
            "SELECT * FROM users WHERE google_id=? OR (email=? AND email IS NOT NULL)",
            [$googleId, $email]
        );

        if ($user) {
            // Update google_id if missing
            if (empty($user['google_id'])) {
                Database::execute("UPDATE users SET google_id=? WHERE id=?", [$googleId, $user['id']]);
            }
            $this->sessionLogin($user);
            $redirect = $this->getRedirectAfterLogin($user);
            $this->json(['success' => true, 'redirect' => $redirect]);
        } else {
            // New user via Google
            if ($userType === 'visitor') {
                // Visitor — create immediately, no extra details needed
                $randomPass = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
                Database::execute(
                    "INSERT INTO users (name, email, password, google_id, email_verified, user_type, plan_id, city_id, status, created_at)
                     VALUES (?, ?, ?, ?, 1, 'visitor', 1, ?, 'active', NOW())",
                    [$name, $email ?: null, $randomPass, $googleId, CITY_ID]
                );
                $user = Database::fetchOne("SELECT * FROM users WHERE id=?", [Database::lastInsertId()]);
                $this->sessionLogin($user);
                $returnTo = $this->input('return_to', '');
                $this->json(['success' => true, 'redirect' => $returnTo ?: CITY_URL]);
            } else {
                // Owner — need more details, store temporarily
                $_SESSION['google_signup'] = [
                    'google_id' => $googleId,
                    'email'     => $email,
                    'name'      => $name,
                ];
                $this->json(['success' => true, 'action' => 'complete_profile',
                             'name' => $name, 'email' => $email]);
            }
        }
    }

    // ── Complete owner profile after Google signup ────────────
    public function completeProfile(): void
    {
        $this->verifyCsrf();
        $d = $_SESSION['google_signup'] ?? null;
        if (!$d) { $this->json(['error' => 'Session expired.'], 400); }

        $phone = $this->sanitize($this->input('phone', ''));
        $city  = (int) $this->input('city_id', CITY_ID);
        $prof  = $this->sanitize($this->input('profession', ''));

        if (!$phone) { $this->json(['error' => 'Phone number required.'], 422); }
        if (Database::fetchOne("SELECT id FROM users WHERE phone=?", [$phone])) {
            $this->json(['error' => 'Phone already registered.'], 409);
        }

        $randomPass = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
        Database::execute(
            "INSERT INTO users (name, email, phone, password, google_id, email_verified, profession, user_type, plan_id, city_id, status, created_at)
             VALUES (?, ?, ?, ?, ?, 1, ?, 'owner', 1, ?, 'active', NOW())",
            [$d['name'], $d['email'] ?: null, $phone, $randomPass, $d['google_id'], $prof, $city]
        );
        $user = Database::fetchOne("SELECT * FROM users WHERE id=?", [Database::lastInsertId()]);
        unset($_SESSION['google_signup']);
        $this->sessionLogin($user);
        $this->json(['success' => true, 'redirect' => CITY_URL . '/post-ad']);
    }

    // ── Email/password signup (owner) ─────────────────────────
    public function register(): void
    {
        $this->verifyCsrf();
        $name  = $this->sanitize($this->input('name', ''));
        $email = $this->sanitize($this->input('email', ''));
        $phone = $this->sanitize($this->input('phone', ''));
        $pass  = $this->input('password', '');
        $prof  = $this->sanitize($this->input('profession', ''));
        $city  = (int) $this->input('city_id', CITY_ID);

        if (!$name || !$email || !$phone || !$pass) {
            $this->json(['error' => 'All fields are required.'], 422);
        }
        if (strlen($pass) < 6) {
            $this->json(['error' => 'Password must be at least 6 characters.'], 422);
        }
        if (Database::fetchOne("SELECT id FROM users WHERE email=?", [$email])) {
            $this->json(['error' => 'Email already registered.'], 409);
        }
        if (Database::fetchOne("SELECT id FROM users WHERE phone=?", [$phone])) {
            $this->json(['error' => 'Phone already registered.'], 409);
        }

        Database::execute(
            "INSERT INTO users (name, email, phone, password, profession, email_verified, user_type, plan_id, city_id, status, created_at)
             VALUES (?, ?, ?, ?, ?, 1, 'owner', 1, ?, 'active', NOW())",
            [$name, $email, $phone, password_hash($pass, PASSWORD_BCRYPT), $prof, $city]
        );
        $user = Database::fetchOne("SELECT * FROM users WHERE id=?", [Database::lastInsertId()]);
        $this->sessionLogin($user);
        $this->json(['success' => true, 'redirect' => CITY_URL . '/post-ad']);
    }

    // ── Email/password login ──────────────────────────────────
    public function loginPost(): void
    {
        $this->verifyCsrf();
        $email = $this->sanitize($this->input('email', ''));
        $pass  = $this->input('password', '');

        $user = Database::fetchOne(
            "SELECT * FROM users WHERE (email=? OR phone=?) AND status='active'",
            [$email, $email]
        );
        if (!$user || !password_verify($pass, $user['password'] ?? '')) {
            $this->json(['error' => 'Incorrect email/phone or password.'], 401);
        }
        $this->sessionLogin($user);
        $this->json(['success' => true, 'redirect' => $this->getRedirectAfterLogin($user)]);
    }

    public function setPassword(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect(CITY_URL . '/login');
        }

        $user = Database::fetchOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
        if (!$user) {
            unset($_SESSION['user_id'], $_SESSION['user_data']);
            $this->redirect(CITY_URL . '/login');
        }

        $csrf = $this->csrfToken();
        $this->view('auth.set-password', compact('user', 'csrf'));
    }

    public function savePassword(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect(CITY_URL . '/login');
        }

        $this->verifyCsrf();

        $userId = (int) $_SESSION['user_id'];
        $password = $this->input('password', '');
        $confirm = $this->input('confirm', '');

        if (strlen($password) < 6) {
            Helper::flash('error', 'Password must be at least 6 characters.');
            $this->redirect(CITY_URL . '/set-password');
        }

        if ($password !== $confirm) {
            Helper::flash('error', 'Passwords do not match.');
            $this->redirect(CITY_URL . '/set-password');
        }

        Database::execute(
            "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?",
            [password_hash($password, PASSWORD_BCRYPT), $userId]
        );

        $freshUser = Database::fetchOne("SELECT * FROM users WHERE id = ?", [$userId]);
        if ($freshUser) {
            $_SESSION['user_data'] = $freshUser;
        }

        Helper::flash('success', 'Password saved successfully.');
        $this->redirect(CITY_URL . '/dashboard');
    }

    public function logout(): void
    {
        unset($_SESSION['user_id'], $_SESSION['user_data']);
        $this->redirect(CITY_URL . '/login');
    }

    // ── Helpers ───────────────────────────────────────────────
    private function getRedirectAfterLogin(array $user): string
    {
        $type = $user['user_type'] ?? 'owner';
        if ($type === 'visitor') return CITY_URL;
        $planName = Database::fetchOne("SELECT name FROM plans WHERE id=?", [$user['plan_id']])['name'] ?? 'free';
        return CITY_URL . '/dashboard';
    }

    private function redirectAfterLogin(): void
    {
        $user = $_SESSION['user_data'];
        $this->redirect($this->getRedirectAfterLogin($user));
    }

    private function sessionLogin(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_data'] = $user;
        Database::execute("UPDATE users SET last_login_at=NOW() WHERE id=?", [$user['id']]);
    }

    // Google JWT decode (without signature verify — add in production)
    private function decodeGoogleJwt(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;
        $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
        if (!$payload || empty($payload['sub'])) return null;
        // In production: verify signature using Google's public keys
        return $payload;
    }
}
