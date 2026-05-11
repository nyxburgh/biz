<?php
// ── Database ──────────────────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'bizguide');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

// ── App ───────────────────────────────────────────────────────
define('APP_NAME', 'BizGuide');

// ── URL ───────────────────────────────────────────────────────
// Auto-detect environment
if (isset($_SERVER['HTTP_HOST'])) {
    $host = $_SERVER['HTTP_HOST'];

    // Production subdomain: kodai.bizguide.in
    if (str_ends_with($host, '.bizguide.in') && $host !== 'www.bizguide.in') {
        define('BASE_URL',  'https://bizguide.in');
        define('IS_SUBDOMAIN', true);
        define('SESSION_NAME', 'bizguide_session');

    // Production main domain: bizguide.in
    } elseif ($host === 'bizguide.in' || $host === 'www.bizguide.in') {
        define('BASE_URL',  'https://bizguide.in');
        define('IS_SUBDOMAIN', false);
        define('SESSION_NAME', 'bizguide_session');

    // Local: localhost/biz
    } else {
        define('BASE_URL',  'http://localhost/biz');
        define('IS_SUBDOMAIN', false);
        define('SESSION_NAME', 'bizguide_local');
    }
} else {
    define('BASE_URL',  'http://localhost/biz');
    define('IS_SUBDOMAIN', false);
    define('SESSION_NAME', 'bizguide_local');
}

// ── Settings ─────────────────────────────────────────────────
define('CITY_ROUTING',   'subfolder'); // subfolder | subdomain
define('UPLOAD_MAX_MB',  5);

// ── Google OAuth ──────────────────────────────────────────────
define('GOOGLE_CLIENT_ID',     'your-google-client-id');
define('GOOGLE_CLIENT_SECRET', 'your-google-client-secret');