# BizGuide — Claude Project Context

## What This Is
Multi-city PHP MVC business directory. Each city runs as an independent site (subfolder or subdomain). Single shared codebase and database. Admin panel manages all cities centrally. Planned Android WebView wrapper.

---

## Tech Stack
- **Backend:** Core PHP MVC (no framework), Modular Monolith
- **Database:** MySQL — DB name: `bizguide`
- **Frontend:** Bootstrap 5, Bootstrap Icons
- **Fonts:** Syne (headings) + DM Sans (body)
- **Admin theme:** Purple/grey — `--purple:#7c3aed`, `--purple-dark:#2d1b69`
- **City theme:** City-specific color via `theme_color` column in `cities` table
- **Dev environment:** XAMPP Windows — `D:\xammp\htdocs\Bizguide\`
- **Local base URL:** `http://localhost/nyxburgh/Biz`
- **Google OAuth:** Client ID in `config/config.php`

---

## Folder Structure
```
Biz/
├── config/config.php              — DB, BASE_URL (auto-detects local/prod), Google OAuth
├── core/                          — Router, Database, Controller, Model, Auth, Helper
├── shared/models/                 — ALL models shared by admin + cities
├── admin/
│   ├── index.php                  — Entry point + all routes
│   ├── setup.php                  — First-run admin creator (delete after use)
│   ├── controllers/
│   └── views/layout/              — header.php + footer.php (sidebar inside header)
├── cities/
│   ├── _template/                 — Master copy, cloned when admin creates a new city
│   │   ├── index.php              — City entry + routes
│   │   ├── controllers/           — AuthController, HomeController, UserController, ListingController
│   │   └── views/                 — layout/, auth/, home/, user/, listing/, search/, errors/
│   ├── kodaikanal/                — Cloned from _template
│   ├── dindugal/
│   └── chennai/
├── assets/uploads/                — listings/, users/, payments/
├── database/
│   ├── bizguide.sql               — Full fresh install
│   ├── bizguide_1.sql             — Updated full schema
│   ├── patch_reviews.sql          — listing_reviews table
│   ├── patch_roles.sql            — role + assigned_city_id on admins
│   └── patch_all.sql              — All patches combined (run on existing installs)
├── .htaccess                      — Routes /kodaikanal/* → cities/kodaikanal/index.php
└── index.php                      — Main landing (city cards)
```

---

## URL Structure
| Context | Pattern |
|---|---|
| Local dev | `http://localhost/nyxburgh/Biz` |
| City homepage | `/Biz/cities/kodaikanal/` |
| Business listing | `/Biz/cities/kodaikanal/sunrise-travels` |
| Admin panel | `/Biz/admin/` |
| Production (subfolder) | `bizguide.in/cities/kodaikanal/` |
| Production (subdomain) | `kodai.bizguide.in/` — uncomment in `.htaccess` |

**Slug rule:** business name → lowercase, spaces→hyphens, remove special chars. Auto-increment on duplicate (`sunrise-travels` → `sunrise-travels2`).

---

## Database: Key Tables
| Table | Purpose |
|---|---|
| `cities` | slug, domain, theme_color, status |
| `admins` | role: super_admin / city_admin, assigned_city_id |
| `users` | plan_id, city_id, user_type (visitor/owner), google_id, email_verified, phone_verified |
| `plans` | free / basic / premium / pro, price, duration_days |
| `business_listings` | plan_level, status, slug, archive_at, city_id, user_id |
| `listing_images` | Multiple images per listing (premium/pro) |
| `listing_reviews` | rating 1–5, status pending/approved/rejected |
| `listing_keywords` | Junction: listing ↔ keyword |
| `listing_subcategories` | Junction: listing ↔ subcategory |
| `categories` / `subcategories` | Listing classification |
| `keywords` / `keyword_suggestions` | Premium/pro keywords; user suggestions go to admin |
| `payments` | Manual confirmation, status pending/confirmed/rejected |
| `user_otps` | OTP table (phone verification — currently unused, Google OAuth preferred) |
| `activity_logs` | Admin action log, city_id scoped |
| `settings` | Key-value site settings |
| `free_users_sidebar` | FIFO queue for free user sidebar display |

---

## User Plans
| Plan | Price | Features |
|---|---|---|
| `free` | ₹0 | Name, profession, phone only. Sidebar scroll on homepage. No listing page, no QR, no dashboard. 24hr repost. |
| `basic` | ₹299/yr | Business name, address, phone, WhatsApp, email, description. Listing page + QR code. |
| `premium` | ₹599/yr | Basic + photos (up to 5), website, social links (FB/IG), keywords. |
| `pro` | ₹999/yr | Premium + services list, top banner image, YouTube embed. Priority placement. |

**Rules:**
- One listing per user — enforced in `UserController::submitAd()`
- Plan selected at ad post time (not at registration)
- All paid listings require admin approval before going live
- All listings archived after 24 hours if payment not confirmed (`archive_at` column)
- Free users: 24hr then repost or upgrade — no URL, no QR, no dashboard
- Paid users: listing URL, QR code (Google Charts API), full dashboard

---

## User Types
| Type | How They Login | What They Can Do |
|---|---|---|
| `visitor` | Google Sign-In only (one tap) | Browse freely, rate & review listings |
| `owner` | Google Sign-In + complete profile, or Email + Password | Post ads, manage dashboard, upgrade plan |

**Login flow:**
1. Browse site — no login required
2. Click Review → prompted to login
3. Choose: Visitor (Google only) or Business Owner (Google or email/password)
4. Visitor → Google → instantly done → back to page
5. Owner via Google → asked for phone + profession → goes to post-ad
6. Owner via email → name, email, phone, profession, password, city → goes to post-ad

---

## Ad Posting Flow (User Side)
1. **Step 1** — Select plan + category + subcategory + city
2. **Step 2** — Fill business details (fields shown based on plan)
3. **Step 3** — Payment (free plans skip; paid plans show UPI/bank instructions + proof upload)
4. **Success screen** — Shows listing URL, 24hr archive warning, upgrade link
5. **Admin approves** → listing goes live → `archive_at = NOW() + 24hrs`
6. After 24hrs without payment confirmation → status = `archived`
7. Archived URL shows "temporarily unavailable" message

---

## City Frontend — Implemented Features
- ✅ Homepage with category grid, featured ads, free members sidebar
- ✅ Search page with category filter + keyword search
- ✅ Business listing page — details, images gallery, keywords, services, YouTube embed
- ✅ QR code on listing page (Google Charts API — no library needed)
- ✅ Share URL + WhatsApp/Facebook/Instagram share buttons
- ✅ Archived state shown on listing URL
- ✅ Review form (login required) — pending admin approval before display
- ✅ User dashboard — tabs: Overview, My Listing, Reviews, Payments
- ✅ Post Ad — 3-step form with plan-based fields
- ✅ Edit Ad — update all fields + plan upgrade option
- ✅ Upgrade Plan page
- ✅ Post-ad success screen with 24hr warning
- ✅ Google Sign-In (visitor + owner flows)
- ✅ Email + password registration (owner)
- ✅ Header hides "Post Ad" button if user already has a listing
- ✅ Mobile bottom navigation bar

---

## Admin Panel — Implemented Features

### Sidebar (canonical — never remove sections)
```
Overview    → Dashboard
Manage      → Cities, Users, Free Users, Active Ads, Pending Approval, Expired Ads
Content     → Categories, Keywords, Suggestions, Reviews
Finance     → Plans & Pricing, Payments
System      → Admin Accounts
Analytics   → Reports
```
`Cities, Content, Plans & Pricing, System` = super_admin only (hidden for city_admin)

### Admin Roles
| Role | Access |
|---|---|
| `super_admin` | Everything — all cities, categories, keywords, plans, admin accounts |
| `city_admin` | Own city only — users, ads, payments, reviews. No categories/keywords/plans/cities/admins |

### Admin Features
- ✅ Dashboard — stats, recent users/listings, activity log (city-scoped for city_admin)
- ✅ Users — paid users list, free users list, create user, view/edit, toggle status, change plan
- ✅ Listings — create (from user profile only), approve/reject, active/pending/expired lists
- ✅ Categories — add/edit/delete with modal; subcategory management per category (edit fixed)
- ✅ Keywords — add/edit/delete with modal; keyword suggestions from users
- ✅ Reviews — approve/reject with modal
- ✅ Cities — add/edit/delete; clones `_template` folder when creating new city
- ✅ Plans & Pricing — edit label/price/validity
- ✅ Payments — confirm/reject manual payments
- ✅ Admin Accounts — super admin manages city admins (assign city)
- ✅ Reports — registrations, active ads, transactions, plan-wise counts
- ✅ Mobile — hamburger sidebar, Bootstrap 5 responsive

---

## Admin Routes (admin/index.php)
```
Auth:        GET/POST /admin/login, GET /admin/logout
Dashboard:   GET /admin
Users:       GET /admin/users, /admin/users/free, /admin/users/create, /admin/users/{id}
             POST /admin/users/store, /admin/users/toggle, /admin/users/upgrade-plan, /admin/users/delete
Listings:    GET /admin/listings, /admin/listings/pending, /admin/listings/expired
             GET /admin/listings/create?user_id=X, /admin/listings/{id}/edit
             POST /admin/listings/store, /admin/listings/update, /admin/listings/approve,
                  /admin/listings/reject, /admin/listings/suspend, /admin/listings/delete
Categories:  GET /admin/categories, /admin/categories/{id}/subcategories
             POST /admin/categories/store, /admin/categories/update, /admin/categories/delete
             POST /admin/categories/subcategories/store, /admin/categories/subcategories/update,
                  /admin/categories/subcategories/delete
Keywords:    GET /admin/keywords, /admin/keywords/suggestions
             POST /admin/keywords/store, /admin/keywords/update, /admin/keywords/delete
Reviews:     GET /admin/reviews
             POST /admin/reviews/approve, /admin/reviews/reject
Cities:      GET /admin/cities
             POST /admin/cities/store, /admin/cities/update, /admin/cities/delete
Plans:       GET /admin/plans, POST /admin/plans/update
Payments:    GET /admin/payments
             POST /admin/payments/confirm, /admin/payments/reject
Admins:      GET /admin/admins, /admin/admins/create
             POST /admin/admins/store, /admin/admins/update, /admin/admins/delete, /admin/admins/reset-password
Reports:     GET /admin/reports
```

---

## City Routes (cities/kodaikanal/index.php)
```
Public:      GET /kodaikanal/
             GET /kodaikanal/search
Auth:        GET  /kodaikanal/login
             POST /kodaikanal/auth/google
             POST /kodaikanal/auth/complete-profile
             POST /kodaikanal/auth/register
             POST /kodaikanal/auth/login
             GET  /kodaikanal/logout
User:        GET  /kodaikanal/dashboard
             GET  /kodaikanal/post-ad
             POST /kodaikanal/post-ad
             GET  /kodaikanal/post-ad-success
             GET  /kodaikanal/edit-ad
             POST /kodaikanal/edit-ad
             GET  /kodaikanal/upgrade
             POST /kodaikanal/upgrade
             POST /kodaikanal/review
Listing:     GET  /kodaikanal/{slug}     ← LAST route (catch-all)
```

---

## Core Helpers
- `Auth::isSuperAdmin()` / `Auth::isCityAdmin()` / `Auth::cityId()`
- `$this->cityScope('column')` → `[$whereClause, $params]` for city-scoped queries
- `$this->logActivity($action, $desc, $targetType, $targetId)`
- `Helper::planBadge($plan)` / `Helper::statusBadge($status)`
- `Helper::flash($type, $msg)` / `Helper::getFlash($type)`
- `Helper::slug($name)` — generates URL slug
- `Helper::timeAgo($datetime)` — "2 hours ago"
- `Helper::formatDate($datetime)` — formatted date
- `Helper::paginationLinks($pager, $baseUrl)`
- `Helper::uploadFile($file, $folder)` → filename or null
- `Database::paginate($sql, $params, $page, $perPage)` → `{data, total, current_page, last_page}`
- `Database::fetchOne($sql, $params)` / `Database::fetchAll($sql, $params)`
- `Database::execute($sql, $params)` / `Database::lastInsertId()`
- `CityBaseController::makeSlug($name)` / `uniqueSlug($name, $excludeId)`

---

## Development Rules
1. **Always run patches** on existing installs — never full reinstall unless fresh
2. **Never remove admin sidebar sections** — verify all sections after any header.php edit
3. **Always use cityScope()** when querying users/listings/payments/reviews in admin controllers
4. **One listing per user** — enforced in `UserController::submitAd()`
5. **Admin creates listings from user profile only** — `/admin/listings/create?user_id=X`
6. **Plan-based form fields** — show/hide by JS based on selected plan
7. **Always log activity** — call `$this->logActivity()` on create/update/delete
8. **Always return JSON** in city auth endpoints — set `header('Content-Type: application/json')` first
9. **CSS in views** — use `<<<'ENDCSS'` nowdoc (never double-quoted string — breaks on `"Syne"`)
10. **Sync cities** — any template change must be copied to all 3 city folders + `_template`
11. **Mobile-first** — 44px touch targets, bottom nav on mobile, 768px+ sidebar

---

## Fresh Install
```bash
mysql -u root -p < database/bizguide_1.sql
# Edit config/config.php — set BASE_URL for local path
# Visit /Biz/admin/setup.php → create super admin → delete setup.php
```

## Existing Install — Run Patches
```bash
mysql -u root -p bizguide < database/patch_all.sql
```

---

## Known Gotchas
- `"Syne"` font name inside double-quoted PHP strings → use `<<<'ENDCSS'` nowdoc
- `mkdir -p` with `{}` brace expansion creates literal folder names on Windows — use Python instead
- Google OAuth: `http://localhost` must be in Authorised JavaScript Origins in Google Cloud Console
- City `index.php` must load `CityBaseController` before the autoloader runs
- `{slug}` catch-all route must always be registered LAST in city index.php
- Subcategory `updateSubcategory()` and keyword `update()` methods must be manually added to controllers + routes if not present