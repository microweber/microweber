# Project Knowledge Base

## Overview
- Microweber is a drag-and-drop CMS/e-commerce platform built on Laravel 11, PHP 8.3+, Filament 5, Livewire 4, and a large internal module system.
- The codebase mixes a Laravel application shell with internal framework packages (`src/MicroweberPackages/`) and feature modules (`Modules/`).

## Architecture & Module Map
- `app/` contains the thin Laravel app shell and providers.
- `src/MicroweberPackages/` contains cross-cutting framework code such as auth, cache, database helpers, Filament integrations, and user management.
- `Modules/` contains product features such as Content, Product, Order, Payment, Shipping, Media, AI, and Live Edit.
- `packages/` contains frontend asset and theme packages, including `frontend-assets` and `microweber-filament-theme`.
- `Templates/` contains installable templates with their own tests and frontend assets.

## Domain Logic
- Content creation, pages/posts/products, categories, and related metadata are centered in `Modules/Content`.
- User/authentication concerns are centered in `src/MicroweberPackages/User`.
- Live-edit behavior spans Filament admin pages, Livewire components, and the frontend asset packages.
- AI/MCP integrations are implemented in `Modules/Ai`.

## Data Models
- `Modules/Content/Models/Content.php` is the central content/domain model and includes search, media, category, menu, and multilingual traits.
- `src/MicroweberPackages/User/Models/User.php` is the central user model and mixes API tokens, roles, notifications, email verification, and social/auth integrations.
- Core settings/state are also spread across options and module-specific models.

## Integrations & External Services
- OAuth/API auth uses Laravel Passport and Sanctum.
- Payments use Omnipay drivers plus module-specific integrations.
- Frontend/docs/tooling use Node-based builds from the repo root and package subdirectories.
- CI runs Composer audit and npm audit in `.github/workflows/cicd-pipeline.yml`.

## Deployment & Infrastructure
- Laravel bootstraps from `bootstrap/app.php` and loads `web.php`, `api.php`, `ecommerce-api.php`, and `module-api.php`.
- CI targets PHP 8.3 by default and Node 22 in GitHub Actions.
- The local environment used by the agent currently serves Apache at `127.0.0.1`, but Laravel admin routes are not currently wired through at `/admin/login`.

## Environment Variables
- Application environment setup follows standard Laravel `.env` flow from `.env.example`.
- CI rewrites `.env` to `APP_ENV=testing` before automated suite execution.
- Production-safe defaults should keep `APP_DEBUG=false`; local/test variants may differ.

---

## AI-122 / TICKET-CB extension (cycle-113 2026-05-09)

### Module map (key Modules/)
- `Cart`, `Checkout`, `Coupons`, `Customer`, `Order` — commerce flow.
- `Product`, `Shop`, `Categories`, `Pictures`, `Slider` — catalogue + presentation.
- `Posts`, `Content` (Posts delegates to Content), `Faq`, `Accordion` — editorial.
- `Newsletter`, `Forms`, `Captcha`, `Search` — engagement.
- `Media`, `MediaLibrary` — file/image pipeline; `responsive_thumbnail()` lives here.
- `Layouts`, `Menu`, `Testimonials`, `Teamcard`, `Gallery` — site building blocks.
- `Billing`, `Payment`, `Invoice`, `Profile` — subscriptions / billing.
- `Ai` — AI assistant + image generation.

### Cross-module communication contracts
- HTTP routes live under `Modules/<X>/routes/*.php`.
- Service-locator access (`app('cart_manager')->...`) is the dominant style; AI-105 / TICKET-AY foundation cycle is moving towards explicit interfaces.
- Frontend event bus: legacy `mw.app.dispatch()` (no replay — see `mw-app-event-bus-no-replay` skill); subscribe before the event fires or risk missing it.
- Database: shared MySQL — modules share tables. FK enforcement is partial (AI-119 / TICKET-BI audit pending).

### Key tables (referenced by AI-107 indexes + ADR-0004)
- `content` (root tree of pages/posts/products/categories) + `content_fields` + `content_data`.
- `categories` + `categories_items` (polymorphic pivot).
- `cart` (one row per item; cycle-102 added compound `(session_id, rel_id)` index).
- `orders` + `orders_data`; `customers`; `users`.
- `newsletter_subscribers` (cycle-102 added `(email, is_subscribed)` index).
- `cms_settings` (AI-108 / TICKET-BG Option model migration is a future cycle).

### Deployment topology
- Production: nginx / Apache + PHP-FPM 8.3 + MySQL 8 + Redis (sessions + cache) + queue worker.
- `php artisan module:publish` populates `public/modules/<X>/` (gitignored) on every deploy.
- Staging: `develop` branch auto-deploys via the cicd-pipeline.yml `deploy-staging` stage.
- Production: tag-triggered via `deploy-production` + GitHub Releases.

### Critical ADRs (cycle-111)
- ADR-0001 — Helper-layer security: principles for string-in / HTML-out paths.
- ADR-0002 — Allowlists + sanitization: explicit, versioned, fail-closed.
- ADR-0003 — Two-pass escape: storage-time validate + render-time context-escape.
- ADR-0004 — Cart guest-merging: session wins, qty-sum, MW_CART read-only.

See `docs/adr/` for the full set.

### Where to look next
- `SETUP.md` — install + run + env reference (cycle-104).
- `tests/README.md` — test runner constraints (cycle-110/112).
- `CHANGELOG.md` — completed cycle history.
- `TROUBLESHOOTING.md` — known issues + runbooks (cycle-111 origin-guard eval).
- `docs/MODULE_GUIDE.md` — module authoring guide (AI-122 / TICKET-CE — see file for cycle-113 skeleton).
