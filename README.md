# Barraza's Construction — Website & Admin Platform

Custom PHP/MariaDB website and CMS for **Barraza's Construction**, a residential
construction and remodeling company serving Tuolumne County, California.

This is **Phase 1**: the secure technical foundation — architecture, database,
authentication, admin shell, and design system — that later phases build on.
It intentionally does not implement every page or CMS module yet.

---

## 1. Project Overview

- **Stack:** PHP 8.1+, PDO, MariaDB/MySQL, Apache, vanilla JS. No framework,
  no ORM, no Node.js in production, no required Composer/`vendor/` upload.
- **Architecture:** a small custom MVC (front controller → router →
  middleware → controller → model/service → view). See `app/Core/` for the
  framework internals and the directory tree below for where application
  code lives.
- **Hosting target:** one.com shared hosting (Apache + `.htaccess`,
  phpMyAdmin, SFTP). Everything here also runs on any standard PHP 8.1+ /
  MariaDB shared host.

### Directory tree

```
/
├── public/                 # Web root (→ public_html on one.com)
│   ├── index.php           # Front controller
│   ├── install.php         # One-time, self-disabling first-admin installer
│   ├── router.php          # LOCAL DEV ONLY (php -S), not used in production
│   ├── .htaccess           # Rewrite rules + security headers
│   ├── robots.txt
│   ├── assets/{css,js,images,icons}/
│   └── uploads/{projects,services,testimonials,general}/
├── app/
│   ├── Core/                # Router, Request, Response, View, Config, Env,
│   │                         # Logger, ErrorHandler, Nonce, base Controller/Model
│   ├── Config/config.php    # Central config array, sourced from .env
│   ├── Controllers/         # HomeController, Admin/*
│   ├── Models/               # AdminUser, LoginAttempt, PasswordResetToken,
│   │                         # ActivityLog, ContentSummary
│   ├── Middleware/           # AuthMiddleware, GuestMiddleware, CsrfMiddleware
│   ├── Services/             # DatabaseService, SessionService, AuthService,
│   │                         # ActivityLogService, MailService
│   ├── Helpers/               # functions.php, Csrf, Str
│   ├── Validation/Validator.php
│   └── Views/{layouts,components,frontend,admin,errors}/
├── routes/{web.php,admin.php}
├── database/{schema.sql,seed.sql}
├── storage/{logs,cache,sessions}/   # Outside the web root
├── bootstrap/{app.php,autoload.php}
├── .env.example
└── README.md (this file)
```

---

## 2. Requirements

- PHP 8.1 or newer, with `pdo_mysql` enabled
- MariaDB 10.4+ / MySQL 8.0+
- Apache with `mod_rewrite` and (ideally) `mod_headers`
- No Composer or Node.js required to run the application

---

## 3. Local Setup

1. Clone the repository.
2. Copy `.env.example` to `.env` and fill in local values (see §5).
3. Create a local database and import the schema (see §4).
4. Start a local PHP server (see §7).
5. Create your first administrator via `public/install.php` (see §6).

---

## 4. Database Creation

Create an empty database, then import the schema and (optionally) the dev
seed data, in that order:

```sql
CREATE DATABASE barrazas_construction
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```bash
mysql -u youruser -p barrazas_construction < database/schema.sql
mysql -u youruser -p barrazas_construction < database/seed.sql   # optional, dev only
```

`schema.sql` creates all 16 Phase 1 tables (InnoDB, utf8mb4, foreign keys,
indexes). `seed.sql` only inserts placeholder site settings/pages/services/
categories — **it never seeds an administrator account.**

---

## 5. Environment Configuration

All configuration lives in `.env` (never committed — see `.gitignore`) and is
read by `app/Core/Env.php` into `app/Config/config.php`. Copy
`.env.example` and set:

| Variable | Notes |
|---|---|
| `APP_ENV` | `local` or `production` |
| `APP_DEBUG` | `true` locally, **must be `false` in production** |
| `APP_URL` | Full base URL, no trailing slash |
| `APP_KEY` | Generate with `php -r "echo bin2hex(random_bytes(32));"` (reserved for future use — token/cookie signing in a later phase) |
| `DB_*` | Database connection |
| `SESSION_LIFETIME` | Inactivity timeout, in seconds |
| `ADMIN_PATH` | Admin URL prefix, default `admin` (e.g. set to something non-obvious for a little extra obscurity — this is not a substitute for the real auth controls, which are always on) |
| `MAIL_*` | SMTP settings for password-reset email. Leave blank to run without mail — the reset flow still works, it just logs instead of sending (see §16) |

---

## 6. First Admin Creation

**Do not** seed a known administrator password. Use one of these two paths:

### Option A — `public/install.php` (recommended)

1. Upload/deploy the app with a working database connection.
2. Visit `https://yourdomain.com/install.php`.
3. Fill in name, email, and a password (12+ characters).
4. On success you're redirected to the login page, and the installer writes
   `storage/installed.lock` and permanently locks itself — visiting
   `install.php` again (even after a page reload) shows "Installation
   Already Completed" instead of a form, both because of that lock file
   (outside the web root, so it can't be removed remotely) and because an
   `admin_users` row now exists.
5. **Delete `public/install.php` from the server immediately after use.**
   The lock makes it inert, but removing the file is still best practice.

### Option B — manual SQL

Generate a hash locally (never in a web-accessible script):

```bash
php -r "echo password_hash('YourStrongPassword123!', PASSWORD_DEFAULT), PHP_EOL;"
```

Then insert it directly:

```sql
INSERT INTO admin_users (name, email, password_hash, role, is_active, created_at, updated_at)
VALUES ('Your Name', 'you@example.com', '<paste hash here>', 'administrator', 1, NOW(), NOW());
```

---

## 7. Running the Project Locally

One.com serves this app through Apache, but for local development PHP's
built-in server works well, using the included dev-only router that mirrors
`public/.htaccess`'s rewrite behavior:

```bash
cd public
php -S localhost:8000 router.php
```

Visit `http://localhost:8000/`. `router.php` is never used in production —
Apache + `.htaccess` handles routing there.

---

## 8. Folder Permissions

On shared hosting, typical safe permissions are:

- Directories: `755`
- Files: `644`
- `storage/logs`, `storage/cache`, `storage/sessions`: writable by the web
  server user (`755` is usually sufficient on one.com; avoid `777`)

`storage/` and `app/`, `bootstrap/`, `database/`, `routes/` should never be
placed inside the web-accessible document root (see §11).

---

## 9. one.com Deployment Instructions

one.com's web root for a domain is `public_html`, and it is not
practical to point it at an arbitrary subfolder. This project is
structured so the fix is a pure file-placement exercise — no code changes:

1. Upload the **contents of `public/`** (not the folder itself) into
   `public_html/`.
2. Upload `app/`, `bootstrap/`, `database/`, `routes/`, `storage/`, and your
   real `.env` into the account root — **one level above** `public_html`,
   i.e. as *siblings* of `public_html`, not inside it.
3. This mirrors the local project layout exactly (everything except
   `public/` sits one level above it), so no paths need to change.
4. Create the database and import `database/schema.sql` via phpMyAdmin
   (§10), fill in `.env` with the one.com database credentials, then visit
   `install.php` to create the first admin (§6), then delete it.
5. Confirm `APP_ENV=production` and `APP_DEBUG=false` in the production
   `.env`.

```
one.com account root/
├── public_html/        ← contents of this repo's public/
├── app/
├── bootstrap/
├── database/
├── routes/
├── storage/
└── .env
```

---

## 10. phpMyAdmin Import Instructions

1. In one.com's control panel, open the database's phpMyAdmin.
2. Select (or create) the target database.
3. Go to the **Import** tab.
4. Choose `database/schema.sql`, then **Go**. Repeat with `database/seed.sql`
   only if you want the placeholder content (recommended for a fresh site;
   skip it if you're re-importing into a database that already has content).

---

## 11. Public-Folder Deployment Strategy

The rule is simple: **only what's inside `public/` is ever web-accessible.**
Everything else (`app/`, `bootstrap/`, `database/`, `routes/`, `storage/`,
`.env`) must live outside the document root. See §9 for exactly how that
maps onto one.com's fixed `public_html` root. As defense in depth in case a
host is ever misconfigured, every one of those directories also ships its
own `Require all denied` `.htaccess`, and `public/uploads/` blocks PHP
execution outright (no script can run there even if uploaded).

---

## 12. Security Checklist

- [x] Passwords hashed with `password_hash()` / verified with `password_verify()`
- [x] Session ID regenerated on login (session fixation protection)
- [x] Secure, `HttpOnly`, `SameSite=Lax` session cookie (and `Secure` when
      served over HTTPS)
- [x] Session file storage under `storage/sessions/`, outside the web root
- [x] Inactivity timeout (`SESSION_LIFETIME`)
- [x] CSRF token required and verified on every POST
- [x] Per-account lockout after repeated failed logins; per-IP rate limiting
- [x] Every login attempt (success/failure) logged to `login_attempts`
- [x] Generic login error message (never reveals whether an email exists)
- [x] Logout is POST-only
- [x] All PDO queries use prepared statements, emulation disabled
- [x] All dynamic output escaped via `e()` (`htmlspecialchars`)
- [x] Security headers: `X-Content-Type-Options`, `X-Frame-Options`,
      `Referrer-Policy`, `Permissions-Policy`, nonce-based `Content-Security-Policy`
- [x] `.env`, `.sql`, `.log`, and dotfiles blocked from direct web access
- [x] Directory listing disabled
- [x] PHP execution blocked inside `public/uploads/`
- [x] Production error pages never leak stack traces, paths, or DB credentials
- [x] Installer self-disables (lock file outside web root + DB check)
- [ ] TLS/HTTPS enforced at the host level — enable "Force HTTPS" in one.com's
      control panel once the domain's certificate is issued (outside this
      app's control, but `Strict-Transport-Security` is sent automatically
      once requests arrive over HTTPS)

---

## 13. Backup Procedure

- **Database:** use phpMyAdmin's **Export** tab (SQL format, "Custom" with
  all tables selected) on a regular schedule, or one.com's built-in database
  backup feature if available on your plan. Store exports off-server.
- **Files:** back up `public/uploads/` (client photos) and `.env`
  separately via SFTP — `.env` is never in version control, so it has no
  other backup.
- Before any risky change (schema migration, bulk content edit), take a
  fresh export first.

---

## 14. Updating Production Safely

1. Test the change locally against a copy of the production schema.
2. Back up the production database (§13) before any schema change.
3. Upload changed files via SFTP to the correct locations (§9) — never
   overwrite `.env`.
4. If the change includes new SQL, run it through phpMyAdmin's SQL tab
   (write it as an `ALTER TABLE` / additive migration, not a re-import of
   `schema.sql`, which would fail on tables that already exist).
5. Verify `APP_DEBUG=false` afterward, then smoke-test login and the
   homepage.

---

## 15. Common Troubleshooting Steps

| Symptom | Likely cause |
|---|---|
| "We'll Be Right Back" page | Database connection failed — check `.env` DB credentials/host; details are in `storage/logs/app-*.log`, never shown to visitors |
| Blank white page | PHP fatal error with `APP_DEBUG=false`; check `storage/logs/app-*.log` |
| 404 on every page except `/` | `.htaccess` not being read — confirm `mod_rewrite` is enabled and `AllowOverride All` (or one.com equivalent) is in effect |
| CSS/JS not loading | Confirm `public/assets/` uploaded, and that `public/` contents (not the folder itself) were placed directly in `public_html/` |
| Locked out of admin login | Wait for `locked_until` to pass, or clear it manually: `UPDATE admin_users SET failed_login_count=0, locked_until=NULL WHERE email='you@example.com';` |
| `install.php` says "Already Completed" but you need a new admin | Use Option B in §6, or manage administrators from within the panel in a later phase |

---

## 16. Future Phase Roadmap

Phase 1 deliberately stops short of full content management. Planned next:

- CRUD modules for Pages, Services, Projects (+ image galleries, before/after),
  Testimonials, Service Areas, Leads, SEO metadata, and Site Settings
- Image upload/processing pipeline (resizing, validation, storage under
  `public/uploads/`)
- Public lead/contact form wired to the `leads` / `lead_attachments` tables
- Real SMTP delivery in `MailService` (password reset currently logs instead
  of sending — see §5)
- Password reset completion screen (token verification + new password form;
  the `password_reset_tokens` table and token-generation code already exist)
- Administrator management UI (invite/deactivate/change role)
- Full XML sitemap generation from published content (currently lists only
  the homepage) and per-page SEO metadata editing
- Additional public pages (About, individual service pages, project detail
  pages, Contact)

---

## What's Complete (Phase 1)

- Full architecture: front controller, router, middleware, controllers,
  models, services, views/layouts/components
- All 16 database tables (`database/schema.sql`) + dev seed data
  (`database/seed.sql`)
- Secure admin authentication: login, logout, lockout, rate limiting,
  CSRF, session hardening, activity logging
- Self-disabling installer for the first administrator
- Admin dashboard shell with real content-count cards and real recent
  activity, plus polished (non-functional) placeholder screens for every
  other nav item
- Original premium design system (CSS variables, typography, components)
- Public homepage shell built on that design system, with clearly marked
  placeholder content
- Custom 404 / 403 / 500 / DB-error pages
- Security headers, nonce-based CSP, `.htaccess` hardening, upload
  directory PHP-execution blocking
- SEO foundation: title/description/canonical/OG meta, `robots.txt`,
  minimal sitemap route, GeneralContractor/LocalBusiness structured data
  (only real fields populated)

## What Remains (Future Phases)

See §16 above.

---

## Manual Test Checklist

Run through this after any deployment (and after following §4–§7 locally):

1. **Homepage** — loads at `/`, no console errors, header/footer render,
   mobile nav opens/closes with keyboard (`Esc` closes it), no horizontal
   scroll at 320px width.
2. **Install** — `install.php` shows the form on a fresh database; after
   creating an admin, it immediately shows "Installation Already Completed"
   on reload.
3. **Login** — wrong password shows a generic error (not "no such user");
   5 consecutive failures locks the account temporarily; correct login
   reaches `/admin/dashboard`.
4. **CSRF** — submitting the login form with a missing/invalid `_csrf`
   value returns a 403 page, not a silent failure.
5. **Session** — after login, session cookie is `HttpOnly` (check dev
   tools → Application → Cookies); waiting past `SESSION_LIFETIME` and
   reloading redirects back to login.
6. **Logout** — only works via the POST form in the user menu; visiting a
   hypothetical GET logout URL does nothing (no such route exists).
7. **Dashboard** — content counts match the database; Recent Activity
   shows the install + login events; empty state renders correctly on a
   brand-new database with no activity yet.
8. **Placeholder pages** — every sidebar link (Pages, Services, Projects,
   Testimonials, Service Areas, Leads, SEO, Site Settings, Activity Log,
   Administrators) loads and clearly states it's coming in a later phase —
   none of them pretend to save data.
9. **Errors** — visiting a nonexistent path shows the branded 404 page;
   setting bad DB credentials temporarily shows the branded "We'll Be Right
   Back" page, not a stack trace (with `APP_DEBUG=false`).
10. **Responsive** — check 320/375/430/768/1024/1280/1440/1920px widths on
    both the homepage and the admin dashboard; sidebar collapses to a
    drawer below 1024px; admin tables scroll horizontally instead of
    overflowing the page.
11. **Accessibility** — tab through the homepage and login form using only
    the keyboard; focus is always visible; skip-to-content link works;
    reduced-motion is respected (enable "Reduce Motion" in OS settings and
    confirm transitions shorten/disable).
