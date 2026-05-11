<?php
// ============================================================
// BizGuide — Admin Entry Point & Route Definitions
// URL: /admin/
// ============================================================

define('ROOT',      dirname(__DIR__));
define('BASE_PATH', ROOT);

require_once ROOT . '/config/config.php';
require_once ROOT . '/core/Database.php';
require_once ROOT . '/core/Model.php';
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/core/Router.php';
require_once ROOT . '/core/Auth.php';
require_once ROOT . '/core/Helper.php';

session_name(SESSION_NAME);
session_start();

$router = new Router();

// ── Authentication ───────────────────────────────────────────
$router->get( '/admin/login',  'AdminAuthController', 'login');
$router->post('/admin/login',  'AdminAuthController', 'login');
$router->get( '/admin/logout', 'AdminAuthController', 'logout');

// ── Dashboard ────────────────────────────────────────────────
$router->get('/admin',           'DashboardController', 'index');
$router->get('/admin/dashboard', 'DashboardController', 'index');

// ── Users ────────────────────────────────────────────────────
$router->get( '/admin/users',                'UserController', 'index');
$router->get( '/admin/users/free',           'UserController', 'freeUsers');
$router->get( '/admin/users/create',         'UserController', 'create');
$router->post('/admin/users/store',          'UserController', 'store');
$router->get( '/admin/users/{id}',           'UserController', 'show');
$router->get( '/admin/users/{id}/edit',      'UserController', 'edit');
$router->post('/admin/users/update',         'UserController', 'update');
$router->post('/admin/users/toggle',         'UserController', 'toggle');
$router->post('/admin/users/upgrade-plan',   'UserController', 'upgradePlan');
$router->post('/admin/users/delete',         'UserController', 'delete');

// ── Listings ─────────────────────────────────────────────────
$router->get( '/admin/listings',             'ListingController', 'index');
$router->get( '/admin/listings/pending',     'ListingController', 'pending');
$router->get( '/admin/listings/expired',     'ListingController', 'expired');
$router->get( '/admin/listings/create',      'ListingController', 'create');
$router->post('/admin/listings/store',       'ListingController', 'store');
$router->get( '/admin/listings/{id}',        'ListingController', 'show');
$router->get( '/admin/listings/{id}/edit',   'ListingController', 'edit');
$router->post('/admin/listings/update',      'ListingController', 'updateListing');
$router->post('/admin/listings/suspend',     'ListingController', 'suspend');
$router->post('/admin/listings/approve',     'ListingController', 'approve');
$router->post('/admin/listings/reject',      'ListingController', 'reject');
$router->post('/admin/listings/delete',      'ListingController', 'delete');

// ── Categories & Subcategories ───────────────────────────────
$router->get( '/admin/categories',                        'CategoryController', 'index');
$router->post('/admin/categories/store',                  'CategoryController', 'store');
$router->post('/admin/categories/update',                 'CategoryController', 'update');
$router->post('/admin/categories/delete',                 'CategoryController', 'delete');
$router->get( '/admin/categories/{catId}/subcategories',  'CategoryController', 'subcategories');
$router->post('/admin/categories/subcategories/store',    'CategoryController', 'storeSubcategory');
$router->post('/admin/categories/subcategories/delete',   'CategoryController', 'deleteSubcategory');

// ── Cities ───────────────────────────────────────────────────
$router->get( '/admin/cities',          'CityController', 'index');
$router->post('/admin/cities/store',    'CityController', 'store');
$router->post('/admin/cities/update',   'CityController', 'update');
$router->post('/admin/cities/delete',   'CityController', 'delete');

// ── Payments ─────────────────────────────────────────────────
$router->get( '/admin/payments',          'PaymentController', 'index');
$router->post('/admin/payments/confirm',  'PaymentController', 'confirm');
$router->post('/admin/payments/reject',   'PaymentController', 'reject');

// ── Keywords & Suggestions ───────────────────────────────────
$router->get( '/admin/keywords',                        'KeywordController', 'index');
$router->post('/admin/keywords/store',                  'KeywordController', 'store');
$router->post('/admin/keywords/delete',                 'KeywordController', 'delete');
$router->get( '/admin/keywords/suggestions',            'KeywordController', 'suggestions');
$router->post('/admin/keywords/suggestions/approve',    'KeywordController', 'approveSuggestion');
$router->post('/admin/keywords/suggestions/reject',     'KeywordController', 'rejectSuggestion');

// ── Reviews ──────────────────────────────────────────────────
$router->get( '/admin/reviews',         'ReviewController', 'index');
$router->post('/admin/reviews/approve', 'ReviewController', 'approve');
$router->post('/admin/reviews/reject',  'ReviewController', 'reject');
$router->post('/admin/reviews/delete',  'ReviewController', 'delete');

// ── Plans ────────────────────────────────────────────────────
$router->get( '/admin/plans',        'PlanController', 'index');
$router->post('/admin/plans/update', 'PlanController', 'update');

// ── Admin Management (super_admin only) ──────────────────────
$router->get( '/admin/admins',                'AdminController', 'index');
$router->post('/admin/admins/store',          'AdminController', 'store');
$router->post('/admin/admins/update',         'AdminController', 'update');
$router->post('/admin/admins/reset-password', 'AdminController', 'resetPassword');
$router->post('/admin/admins/delete',         'AdminController', 'delete');

// ── Reports ──────────────────────────────────────────────────
$router->get('/admin/reports', 'ReportController', 'index');

// ── Dispatch ─────────────────────────────────────────────────
$router->dispatch(ROOT . '/admin/controllers');
