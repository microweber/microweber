# Microweber Architecture Guide

**Version:** 4.0-dev17 | **Branch:** filament-5 | **Date:** 2026-04-03

## Overview

Microweber is a **Laravel-based CMS** with integrated e-commerce, built on:
- **Laravel 11** (framework)
- **Filament 5** (admin panel)
- **Livewire 4** (reactive UI components)
- **Alpine.js** (lightweight JS interactivity)
- **MariaDB/MySQL** (database)

## Directory Structure

```
microweber/
├── Modules/              # 92+ domain modules (Content, Order, Product, Media, etc.)
│   └── {Module}/
│       ├── Filament/     # Admin resources, pages, widgets
│       ├── Models/        # Eloquent models
│       ├── Livewire/      # Livewire components
│       ├── database/      # Migrations, factories
│       ├── resources/     # Views, assets
│       ├── Tests/         # PHPUnit tests
│       └── Providers/     # Service providers
├── src/MicroweberPackages/  # Core framework packages
│   ├── Admin/             # Legacy admin (being replaced by Filament)
│   ├── App/               # Application bootstrap
│   ├── Core/              # Core services (content CRUD, options, cache)
│   ├── Filament/          # Filament theme, forms, components
│   ├── LiveEdit/          # Frontend drag-and-drop editor
│   ├── User/              # Authentication, roles, profiles
│   └── ...                # 40+ packages
├── database/migrations/   # Core migrations
├── packages/              # Theme packages
│   └── microweber-filament-theme/  # Admin CSS theme
├── tests/                 # Feature/integration tests
└── config/                # Laravel config files
```

## Key Architectural Concepts

### 1. Module System
Every feature is a **Module** in `Modules/`. Modules are auto-discovered via `nwidart/laravel-modules`. Each module is self-contained with its own:
- Eloquent models
- Filament resources (admin CRUD)
- Migrations
- Tests
- Service providers

### 2. Content as Single Table Inheritance (STI)
The `content` table stores **pages, posts, and products** using `content_type` to discriminate:
- `content_type = 'page'` → Pages
- `content_type = 'post'` → Posts (via Blog module)
- `content_type = 'product'` → Products (via Product module)

Extended attributes (price, SKU, etc.) are stored in **`content_data`** using an EAV pattern (`rel_type`, `rel_id`, `field_name`, `field_value`).

### 3. Filament Admin Panel
The admin UI uses **Filament 5** with:
- **Resources** (`{Module}/Filament/Admin/Resources/`) — CRUD for models (ContentResource, OrderResource, etc.)
- **Pages** (`{Module}/Filament/Admin/Pages/`) — Custom pages (MediaLibrary, Settings, etc.)
- **Widgets** — Dashboard components (stats, charts)
- **Theme** — Custom SCSS in `packages/microweber-filament-theme/`

Resources follow **inheritance**: `PageResource` and `PostResource` extend `ContentResource`.

### 4. Polymorphic Relations
Several tables use `rel_type` + `rel_id` for polymorphic ownership:
- **media** — any model can own media files
- **payments** — any model can have payment records
- **content_data** — EAV for any model
- **custom_fields** — dynamic fields for any model

### 5. Live Edit (Frontend Editor)
The drag-and-drop page editor (`src/MicroweberPackages/LiveEdit/`) lets users edit pages visually. Modules provide renderable components that can be placed on pages.

## Request Flow

```
HTTP Request
  → Laravel Router (626 routes)
    → Middleware (auth, admin panel)
      → Filament Resource/Page (admin)
        → Livewire Component (reactive UI)
          → Eloquent Model (database)
            → Response (Blade view)
```

### Admin Panel Flow
1. User visits `/admin/*`
2. Filament panel resolves the resource/page
3. Livewire components render forms, tables, actions
4. Alpine.js handles client-side interactivity
5. Custom JS components: `mw-tree-component.js` (tree selectors), `mw-media-browser.js` (media picker)

## Domain Model

### Content Domain
- **Content** — Pages, posts, products (STI)
- **Category** — Hierarchical taxonomy (`parent_id`)
- **Tag** — Flat taxonomy
- **Media** — Files attached to content
- **Menu** — Navigation items linked to content/categories/URLs

### Commerce Domain
- **Order** (`cart_orders`) — Customer orders with status workflow
- **Cart** — Shopping cart line items
- **Payment** — Payment transactions (polymorphic)
- **Customer** — Buyer profiles (may differ from users)
- **Invoice** — Generated invoices
- **Product Variants** — Size/color combinations
- **Coupons** — Discount codes
- **Shipping** — Shipping providers and rates
- **Tax** — Tax rules and rates

### User Domain
- **User** — Authentication via Fortify, roles via Spatie
- **Address** — Shipping/billing addresses

## Testing

- **12 PHPUnit suites** split to avoid OOM (~6MB/test leak)
- Run via `./run-tests.sh` (separate processes per suite)
- **~2,500 tests**, **~17,000 assertions**
- Database: `microweber_testing` (MySQL, shared state — no RefreshDatabase)
- Truncate `notifications` table before full run

## Key Files for New Contributors

| What | Where |
|------|-------|
| Admin panel config | `app/Providers/Filament/FilamentAdminPanelProvider.php` |
| Theme CSS | `packages/microweber-filament-theme/resources/assets/css/microweber-theme-v3.scss` |
| Content CRUD | `Modules/Content/Filament/Admin/ContentResource.php` |
| Order management | `Modules/Order/Filament/Admin/Resources/OrderResource.php` |
| Media Library | `Modules/MediaLibrary/Filament/Admin/Pages/MediaLibrary.php` |
| Menu editor | `Modules/Menu/Livewire/Admin/MenusList.php` |
| Dashboard | `Modules/SiteStats/Filament/Admin/Widgets/SiteStatsEchartsWidget.php` |
| Core content manager | `src/MicroweberPackages/Content/ContentManager.php` |
| Test runner | `./run-tests.sh` |
| PHPUnit config | `phpunit.xml` |
