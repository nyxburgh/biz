# BizGuide — Project Context for Claude

## What This Is
Multi-city PHP MVC business directory. Each city runs as an independent site (subdomain or subfolder). Single shared codebase and database. Admin panel manages all cities centrally.

## Tech Stack
- **Backend:** Core PHP MVC (no framework), Modular Monolith
- **Database:** MySQL — DB name: `bizguide`
- **Frontend:** Bootstrap 5, Bootstrap Icons
- **Admin theme:** Purple/grey — `--purple:#7c3aed`, `--purple-dark:#2d1b69`
- **Public fonts:** Syne + DM Sans (Phase 2)
- **Dev environment:** XAMPP, Windows (`D:\xammp\htdocs\Bizguide\`)

## Folder Structure
```
bizguide/
├── config/config.php           — DB, BASE_URL, BASE_PATH
├── core/                       — Router, Database, Controller, Model, Auth, Helper
├── shared/models/              — ALL models shared by admin + cities
├── admin/                      — Admin panel (self-contained)
│   ├── index.php               — Entry point + all routes
│   ├── setup.php               — First-run admin creator (delete after use)
│   ├── controllers/
│   └── views/layout/           — header.php + footer.php (sidebar here)
├── cities/
│   ├── _template/              — Cloned when new city is created
│   ├── kodaikanal/
│   ├── dindugal/
│   └── chennai/
├── assets/
├── database/
│   ├── bizguide.sql            — Full fresh install
│   ├── patch_reviews.sql       — Adds listing_reviews table
│   └── patch_roles.sql         — Adds role/assigned_city_id to admins
└── index.php                   — Main landing (city cards)
```

## Database: Key Tables
| Table | Purpose |
|---|---|
| `admins` | role: super_admin / city_admin, assigned_city_id |
| `users` | plan_id FK→plans, city_id, status, plan_expires_at |
| `plans` | free / basic / premium / pro, price, duration_days |
| `business_listings` | plan_level, status, city_id, user_id (one per user) |
| `listing_reviews` | rating 1-5, status pending/approved/rejected |
| `categories` / `subcategories` | listing classification |
| `keywords` / `keyword_suggestions` | premium/pro user keywords |
| `payments` | manual confirmation, status pending/confirmed/rejected |
| `activity_logs` | admin action log, city_id scoped |

## User Plans
| Plan | Features |
|---|---|
| free | Name, profession, phone. Sidebar listing only. No business page. |
| basic | + Business name, address, phone, whatsapp, email, description |
| premium | + Images, website, social links, keywords |
| pro | + Services list, top banner, YouTube embed |

**Rules:**
- One listing per user
- Plan set at ad posting, not at user registration
- Plan can be changed any time from user profile
- All paid listings require admin approval

## Admin Roles
| Role | Access |
|---|---|
| super_admin | Everything — all cities, categories, keywords, plans, admin accounts |
| city_admin | Own city only — users, ads, payments, reviews. No categories/keywords/plans/cities |

City admin sees their city name badge in the topbar.

## Admin Sidebar (Canonical — Never Remove Any Section)
```
Overview    → Dashboard
Manage      → Cities*, Users, Free Users, Active Ads, Pending Approval, Expired Ads
Content*    → Categories, Keywords, Suggestions, Reviews
Finance     → Plans & Pricing*, Payments
System*     → Admin Accounts
Analytics   → Reports
```
`*` = super_admin only (hidden for city_admin)

## Admin Routes (admin/index.php)
- Auth: GET/POST `/admin/login`, GET `/admin/logout`
- Users: `/admin/users`, `/admin/users/free`, `/admin/users/create`, `/admin/users/{id}`, `/admin/users/{id}/edit`
- User actions: toggle, upgrade-plan, delete
- Listings: `/admin/listings`, `/admin/listings/pending`, `/admin/listings/expired`, `/admin/listings/create?user_id=X`, `/admin/listings/{id}`, `/admin/listings/{id}/edit`
- Listing actions: store, update, suspend, approve, reject, delete
- Categories, Subcategories, Cities, Payments, Keywords, Suggestions, Reviews, Plans, Admin Accounts, Reports

## Core Helpers
- `Auth::isSuperAdmin()` / `Auth::isCityAdmin()` / `Auth::cityId()`
- `$this->cityScope('column')` → returns `[$whereClause, $params]` for city scoping
- `$this->logActivity($action, $description, $targetType, $targetId)`
- `Helper::planBadge($plan)` / `Helper::statusBadge($status)` / `Helper::flash()` / `Helper::paginationLinks()`
- `Database::paginate($sql, $params, $page, $perPage)` → `{data, total, per_page, current_page, last_page}`

## Development Rules
1. **Patch files** for existing installs — never full reinstall
2. **Never remove sidebar sections** — always verify all sections after editing header.php
3. **cityScope()** — always use when querying users/listings/payments/reviews in controllers
4. **One listing per user** — enforced in ListingController::store()
5. **Ad posting only from user profile** — `/admin/listings/create?user_id=X`
6. **Plan fields on ad form** — show/hide by JS based on selected plan (basic/premium/pro)
7. **Activity log** — call `$this->logActivity()` on create/update/delete actions
8. **Mobile-first** — admin uses Bootstrap 5 responsive + hamburger sidebar toggle

## Phase Status
- ✅ **Phase 1 (Admin)** — Complete
- ⏳ **Phase 2 (User/City public side)** — NOT started. Begin only when told "START USER SIDE"

## Phase 2 Notes (for when ready)
- Each city has its own color theme (e.g. kodaikanal=green, chennai=sea blue)
- Mobile-first, PWA-ready (will be wrapped as Android app via WebView)
- Free user FIFO sidebar scroll panel on homepage
- Public listing pages — show approved reviews, average rating
- User registration, login, dashboard, listing create/edit, payment upload
- Keyword suggestion form

## Fresh Install
```bash
mysql -u root -p < database/bizguide.sql
# edit config/config.php — set DB_USER, DB_PASS, BASE_URL
# visit /admin/setup.php → create super admin → delete setup.php
```

## Existing Install Patches
```bash
mysql -u root -p bizguide < database/patch_reviews.sql   # adds listing_reviews
mysql -u root -p bizguide < database/patch_roles.sql     # adds role/city scoping
```
