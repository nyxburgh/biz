<?php
require_once __DIR__ . '/CityBaseController.php';

class AuthController extends CityBaseController
{
    public function login(): void
    {
        if (!empty($_SESSION['user_id'])) $this->redirect(CITY_URL . '/dashboard');
        $csrf     = $this->csrfToken();
        $googleId = defined('GOOGLE_CLIENT_ID') ? GOOGLE_CLIENT_ID : '';
        $cities   = Database::fetchAll("SELECT id, name FROM cities WHERE status='active' ORDER BY name");
        $this->view('auth.login', compact('csrf', 'googleId', 'cities'));
    }

    // ── Google OAuth ──────────────────────────────────────────
    public function googleCallback(): void
    {
        // JSON header MUST be first — before any other output
        header('Content-Type: application/json; charset=utf-8');

        try {
            $this->verifyCsrf();
            $credential = $this->input('credential', '');
            $userType   = $this->input('user_type', 'visitor');

            if (!$credential) {
                echo json_encode(['error' => 'Google login failed. No credential received.']);
                exit;
            }

            $payload = $this->decodeJwt($credential);
            if (!$payload || empty($payload['sub'])) {
                echo json_encode(['error' => 'Invalid Google token.']);
                exit;
            }

            $googleId = $payload['sub'];
            $email    = $payload['email'] ?? '';
            $name     = $payload['name']  ?? 'User';

            // Find existing user
            $user = null;
            if ($googleId) {
                $user = Database::fetchOne("SELECT * FROM users WHERE google_id=? LIMIT 1", [$googleId]);
            }
            if (!$user && $email) {
                $user = Database::fetchOne("SELECT * FROM users WHERE email=? AND email!='' LIMIT 1", [$email]);
            }

            if ($user) {
                // Update google_id if not set
                if (empty($user['google_id'])) {
                    Database::execute("UPDATE users SET google_id=? WHERE id=?", [$googleId, $user['id']]);
                }
                $this->sessionLogin($user);
                echo json_encode(['success' => true, 'redirect' => $this->afterLoginUrl($user)]);
                exit;
            }

            // New user
            if ($userType === 'visitor') {
                Database::execute(
                    "INSERT INTO users (name, email, google_id, email_verified, user_type, plan_id, city_id, status, created_at)
                     VALUES (?,?,?,1,'visitor',1,?,'active',NOW())",
                    [$name, $email ?: null, $googleId, CITY_ID]
                );
                $newId = Database::lastInsertId();
                $user  = Database::fetchOne("SELECT * FROM users WHERE id=?", [$newId]);
                $this->sessionLogin($user);
                $returnTo = $this->input('return_to', '');
                echo json_encode(['success' => true, 'redirect' => $returnTo ?: CITY_URL]);
                exit;
            }

            // Owner via Google — need phone
            $_SESSION['google_signup'] = ['googleId' => $googleId, 'email' => $email, 'name' => $name];
            echo json_encode(['success' => true, 'action' => 'complete_profile', 'name' => $name, 'email' => $email]);
            exit;

        } catch (Throwable $e) {
            echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
            exit;
        }
    }

    // ── Complete Google owner profile ─────────────────────────
    public function completeProfile(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $this->verifyCsrf();
            $d = $_SESSION['google_signup'] ?? null;
            if (!$d) { echo json_encode(['error' => 'Session expired. Please try again.']); exit; }

            $phone = $this->sanitize($this->input('phone', ''));
            $city  = (int) $this->input('city_id', CITY_ID);
            $prof  = $this->sanitize($this->input('profession', ''));

            if (!$phone) { echo json_encode(['error' => 'Phone number required.']); exit; }
            if (Database::fetchOne("SELECT id FROM users WHERE phone=?", [$phone])) {
                echo json_encode(['error' => 'Phone already registered.']); exit;
            }

            Database::execute(
                "INSERT INTO users (name, email, phone, google_id, email_verified, profession, user_type, plan_id, city_id, status, created_at)
                 VALUES (?,?,?,?,1,?,'owner',1,?,'active',NOW())",
                [$d['name'], $d['email'] ?: null, $phone, $d['googleId'], $prof, $city]
            );
            $user = Database::fetchOne("SELECT * FROM users WHERE id=?", [Database::lastInsertId()]);
            unset($_SESSION['google_signup']);
            $this->sessionLogin($user);
            echo json_encode(['success' => true, 'redirect' => CITY_URL . '/post-ad']);
            exit;
        } catch (Throwable $e) {
            echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
            exit;
        }
    }

    // ── Email/password register (owner) ───────────────────────
    public function register(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $this->verifyCsrf();
            $name  = $this->sanitize($this->input('name', ''));
            $email = $this->sanitize($this->input('email', ''));
            $phone = $this->sanitize($this->input('phone', ''));
            $pass  = $this->input('password', '');
            $prof  = $this->sanitize($this->input('profession', ''));
            $city  = (int) $this->input('city_id', CITY_ID);

            if (!$name || !$email || !$phone || !$pass) {
                echo json_encode(['error' => 'Name, email, phone and password are required.']); exit;
            }
            if (strlen($pass) < 6) {
                echo json_encode(['error' => 'Password must be at least 6 characters.']); exit;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['error' => 'Invalid email address.']); exit;
            }
            if (Database::fetchOne("SELECT id FROM users WHERE email=?", [$email])) {
                echo json_encode(['error' => 'Email already registered. Please login.']); exit;
            }
            if (Database::fetchOne("SELECT id FROM users WHERE phone=?", [$phone])) {
                echo json_encode(['error' => 'Phone already registered.']); exit;
            }

            Database::execute(
                "INSERT INTO users (name, email, phone, password, profession, email_verified, user_type, plan_id, city_id, status, created_at)
                 VALUES (?,?,?,?,?,1,'owner',1,?,'active',NOW())",
                [$name, $email, $phone, password_hash($pass, PASSWORD_BCRYPT), $prof, $city]
            );
            $user = Database::fetchOne("SELECT * FROM users WHERE id=?", [Database::lastInsertId()]);
            $this->sessionLogin($user);
            echo json_encode(['success' => true, 'redirect' => CITY_URL . '/post-ad']);
            exit;
        } catch (Throwable $e) {
            echo json_encode(['error' => 'Registration failed: ' . $e->getMessage()]);
            exit;
        }
    }

    // ── Email/password login ──────────────────────────────────
    public function loginPost(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $this->verifyCsrf();
            $email = $this->sanitize($this->input('email', ''));
            $pass  = $this->input('password', '');
            $user  = Database::fetchOne(
                "SELECT * FROM users WHERE (email=? OR phone=?) AND status='active' LIMIT 1",
                [$email, $email]
            );
            if (!$user || !password_verify($pass, $user['password'] ?? '')) {
                echo json_encode(['error' => 'Incorrect email/phone or password.']); exit;
            }
            $this->sessionLogin($user);
            echo json_encode(['success' => true, 'redirect' => $this->afterLoginUrl($user)]);
            exit;
        } catch (Throwable $e) {
            echo json_encode(['error' => 'Login failed: ' . $e->getMessage()]);
            exit;
        }
    }

    public function logout(): void
    {
        unset($_SESSION['user_id'], $_SESSION['user_data']);
        $this->redirect(CITY_URL . '/login');
    }

    // ── Helpers ───────────────────────────────────────────────
    private function afterLoginUrl(array $user): string
    {
        return (($user['user_type'] ?? 'owner') === 'visitor') ? CITY_URL : CITY_URL . '/dashboard';
    }

    private function sessionLogin(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_data'] = $user;
        Database::execute("UPDATE users SET last_login_at=NOW() WHERE id=?", [$user['id']]);
    }

    private function decodeJwt(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;
        $payload = json_decode(
            base64_decode(strtr($parts[1], '-_', '+/')), true
        );
        return (!$payload || empty($payload['sub'])) ? null : $payload;
    }
}
