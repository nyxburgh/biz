<?php
class Auth
{
    public static function check(): bool
    {
        return !empty($_SESSION['admin_id']);
    }

    public static function user(): ?array
    {
        return $_SESSION['admin_user'] ?? null;
    }

    public static function id(): ?int
    {
        return $_SESSION['admin_id'] ?? null;
    }

    public static function role(): string
    {
        return $_SESSION['admin_user']['role'] ?? 'city_admin';
    }

    public static function isSuperAdmin(): bool
    {
        return self::role() === 'super_admin';
    }

    public static function isCityAdmin(): bool
    {
        return self::role() === 'city_admin';
    }

    public static function cityId(): ?int
    {
        return $_SESSION['admin_user']['assigned_city_id'] ?? null;
    }

    public static function login(array $admin): void
    {
        session_regenerate_id(true);
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_user'] = $admin;
        Database::execute("UPDATE admins SET last_login_at=NOW() WHERE id=?", [$admin['id']]);
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
