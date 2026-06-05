<?php
if (!defined('BASE_PATH')) define('BASE_PATH', dirname(__DIR__, 2));

require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/Auth.php';
require_once BASE_PATH . '/core/Helper.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$citySlug = basename(__DIR__);
$cityRow  = Database::fetchOne("SELECT * FROM cities WHERE slug=? AND status='active'", [$citySlug]);

if (!$cityRow && $citySlug !== '_template') {
    http_response_code(404); die('City not found.');
}

define('CITY_ID',    $cityRow['id']    ?? 0);
define('CITY_SLUG',  $citySlug);
define('CITY_NAME',  $cityRow['name']  ?? 'BizGuide');
define('CITY_URL',   BASE_URL . '/cities/' . $citySlug);
define('CITY_DIR',   __DIR__);
define('CITY_COLOR', $cityRow['theme_color'] ?? '#7c3aed');

$controllerDir = __DIR__ . '/controllers/';
spl_autoload_register(function(string $class) use ($controllerDir): void {
    $f = $controllerDir . $class . '.php';
    if (file_exists($f)) require_once $f;
});

$router = new Router();
$base   = '/cities/' . $citySlug;

// ── Public ────────────────────────────────────────────────────
$router->get("$base",                          'HomeController',    'index');
$router->get("$base/",                         'HomeController',    'index');
$router->get("$base/search",                   'HomeController',    'search');

// ── Auth ──────────────────────────────────────────────────────
$router->get( "$base/login",                   'AuthController',    'login');
$router->post("$base/auth/google",             'AuthController',    'googleCallback');
$router->post("$base/auth/complete-profile",   'AuthController',    'completeProfile');
$router->post("$base/auth/register",           'AuthController',    'register');
$router->post("$base/auth/login",              'AuthController',    'loginPost');
$router->get( "$base/logout",                  'AuthController',    'logout');

// ── User (authenticated) ──────────────────────────────────────
$router->get( "$base/dashboard",               'UserController',    'dashboard');
$router->get( "$base/post-ad",                 'UserController',    'postAd');
$router->post("$base/post-ad",                 'UserController',    'submitAd');
$router->get( "$base/edit-ad",                 'UserController',    'editAd');
$router->post("$base/edit-ad",                 'UserController',    'updateAd');
$router->get( "$base/upgrade",                 'UserController',    'upgradePlan');
$router->post("$base/upgrade",                 'UserController',    'submitUpgrade');
$router->post("$base/review",                  'UserController',    'submitReview');
$router->post("$base/suggest-keyword",         'UserController',    'suggestKeyword');

// ── FCM push notifications ────────────────────────────────────
$router->get( "$base/firebase-messaging-sw.js",  'FCMController', 'sw');
$router->get( "$base/fcm-sw.js",                 'FCMController', 'sw');
$router->post("$base/fcm-token",                 'FCMController', 'saveToken');

// ── Business listing page — must be LAST (catch-all slug) ─────
$router->get("$base/{slug}",                   'ListingController', 'show');

$router->dispatch(CITY_DIR . '/controllers');
