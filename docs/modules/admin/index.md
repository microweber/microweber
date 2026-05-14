# Admin Package

The Admin package is the **boot harness for Microweber's admin panel**. It lives under `src/MicroweberPackages/Admin/` (not under `Modules/`) because it's a core, framework-level package — not an installable feature module. Its job is to wire up the Filament v5 admin panel: register the panel provider, set the brand colors + logo, mount render hooks for the top-navigation Live Edit button + quick-nav search, gate access via the `admin` middleware, expose the customisable login page, and provide the abstract base for every "Admin Settings" page that other packages extend.

> **TL;DR** — Admin is ~3,500 lines of PHP that boots the Filament panel and gives the rest of the framework a stable target to register Pages, Resources, Widgets, and settings into. It owns the **panel provider** (`FilamentAdminPanelProvider`), the **login page** (with proper WHATWG autocomplete tokens), the **admin middleware** (`is_admin` check + `ServingAdmin` event), the **`AdminSettingsPage` base class** that all settings pages extend, the **`MwColors`-anchored brand palette**, and a handful of Livewire components for the admin's top-nav quick actions. It owns **zero models**, **zero migrations**, and **no per-module business logic** — everything else is delegated.

---

## What this package owns

| Concern | Where |
|---|---|
| Filament v5 panel definition | `Filament\FilamentAdminPanelProvider` (panel id `admin`, default registration) |
| Admin login page with autofill tokens | `Filament\Pages\Login` |
| Admin middleware (`is_admin()` gate) | `Http\Middleware\Admin` |
| `ServingAdmin` event + legacy `mw.admin` / `mw_backend` event triggers | `Events\ServingAdmin` + middleware dispatches |
| `AdminSettingsPage` abstract base for every settings page | `Filament\Pages\Abstract\AdminSettingsPage` |
| Brand color tokens anchored to `MwColors::Blue` (Bootstrap `#0d6efd`) | `Filament\MwColors` |
| Top-nav Live Edit button | `resources/views/livewire/filament/top-navigation-go-live-edit.blade.php` |
| Top-nav quick-nav search | `resources/views/livewire/filament/search-quick-nav.blade.php` |
| Top-nav quick-add menu (New Page / Post / Category / Product) | `Http\Livewire\TopNavigationActions` |
| Admin Livewire base classes | `AdminComponent`, `AdminModalComponent`, `AdminConfirmModalComponent` |
| Admin script + style injection facade | `Services\AdminManager` (deprecated facade) |
| Microweber SVG icon set (40+ icons) | `resources/mw-svg/` (registered via BladeUI's icon factory) |
| Legacy admin URL routing (back-compat) | `routes/admin.php` |
| License-management API endpoints | `routes/api.php` |

What this package does **NOT** own:

- Admin user model — owned by the [User package](/) (`MicroweberPackages\User\Models\User`). Admin only reads `$user->is_admin === 1`.
- Settings storage — owned by the **Option package** (`Option` + `ModuleOption` models). `AdminSettingsPage` is the form template; the storage layer is elsewhere.
- The Filament resource registry — owned by `MicroweberPackages\Filament\FilamentRegistry`. Admin's panel provider just calls `FilamentRegistry::getPages()` / `getResources()` / `getClusters()` so plugins can register without touching this package.
- The Live Edit page itself — owned by [LiveEdit package](/) (`AdminLiveEditPage` is registered there). Admin just renders the top-nav button that links into it.
- Permissions / RBAC — there is no Spatie or Fortify integration. Authorisation is a simple `is_admin` boolean. Fine-grained per-resource permissions belong to the resource's own Filament policy class.
- Translations — global i18n via the Multilanguage package; this package has no `lang/` directory.

---

## Architectural fact: harness-not-feature

Admin is deliberately **a harness, not a feature**. Three consequences shape the codebase:

1. **No models.** The package has zero Eloquent models. Everything it reads (the User, the Option) is borrowed from sibling packages.
2. **No business logic.** All "do something" code lives in the package whose data is being touched (Content edits in Content, product saves in Product, etc.). Admin only wires the routes / Filament shell that hosts those features.
3. **Plugin-driven registration.** Resources and Pages are not hardcoded into the panel provider. They register themselves via `FilamentRegistry::getPages()` / `getResources()`. Admin's panel provider just iterates the registry. This means installing a new feature module automatically surfaces its resources in admin — no changes to Admin needed.

---

## The Filament panel — what gets wired

`FilamentAdminPanelProvider::panel(Panel $panel)`:

- **Panel id**: `admin` — used in `body.fi-panel-admin` CSS scoping across the project.
- **Path**: `mw_admin_prefix_url()` — supports custom admin URLs (e.g. `/admin`, `/backend`, `/manage`). Resolved per-request from options.
- **Default**: yes (`->default()`) — `auth()->guard(...)` and other Filament helpers default to this panel.
- **Auth middleware**: `AuthenticateAdmin::class` — Filament's standard authentication, layered on top of the project's `is_admin` check.
- **Pages**: `FilamentRegistry::getPages()` — populated by plugins; includes `Modules\Content\Filament\Admin\ContentResource\Pages\*`, `LiveEdit\Filament\Admin\Pages\AdminLiveEditPage`, etc.
- **Resources**: `FilamentRegistry::getResources()` — same pattern.
- **Widgets**: `FilamentInfoWidget` (Filament's standard info card). Other widgets (e.g. `DashboardEmptyStateWidget` from earlier session work, or `OrderStats` from the Order module) self-register through their own providers.
- **Render hooks**:
  - `TOPBAR_START` → mounts the `admin-top-navigation-actions` Livewire (quick-add New Page / Post / Category / Product).
  - `GLOBAL_SEARCH_AFTER` → injects the Live Edit button + the quick-nav search component.
  - `TABLES::TOOLBAR_SEARCH_BEFORE` → renders the category tree on Content, Post, Product list pages (scoped to those resources only).
- **Colors**: `primary = MwColors::Blue`, danger/gray/info/success/warning each anchored to a Filament `Color::*` palette. Primary is the project's Bootstrap-aligned `#0d6efd`.
- **Logo + brand name**: from `mw()->ui->admin_logo()` / `->brand_name()` (project-level UI service).
- **Plugins registered**: MicroweberFilamentTheme (custom Tailwind), UsersFilamentPlugin, MultilanguageFilamentPlugin, Socialite, TableLayoutToggle, plus everything in `FilamentRegistry::getPlugins()`.

---

## The login page — what's customised

`Filament\Pages\Login` extends Filament's stock Login. The override exists for one reason: **password-manager autofill tokens**.

| Field | What's added |
|---|---|
| Email | `placeholder="you@example.com"`, `name="email"`, `autocomplete="username"`, `inputmode="email"` |
| Password | `placeholder="Your password"`, `name="password"`, `autocomplete="current-password"` |

Without these, 1Password / Bitwarden / Apple Keychain / Chrome autofill **silently fail** on the admin login because Filament's defaults don't emit the WHATWG-spec attributes mobile autofill relies on. AI-281 documented the fix; this page exists to preserve it across Filament upgrades.

---

## Surfaces

| Surface | Where | Audience |
|---|---|---|
| `/admin/*` (default path; configurable) | `FilamentAdminPanelProvider` | staff |
| `/admin/login` | `Login` page | staff |
| Legacy `{admin_url_legacy}/*` routes | `Http\Controllers\AdminController` | back-compat for older Microweber installs |
| `/api/mw_*_license` endpoints | `routes/api.php` | license management |
| Microweber SVG icon set | `resources/mw-svg/` + `BladeUI\Icons` registration | view authors |

---

## Where to next

- [Installation](./installation.md) — provider registration, admin URL prefix, middleware order, sibling-package dependencies.
- [Usage](./usage.md) — registering a Filament page or resource via `FilamentRegistry`, building an Admin settings page via `AdminSettingsPage`, injecting scripts/styles, adding a render hook.
- [API](./api.md) — `FilamentAdminPanelProvider` (panel config surface), `AdminSettingsPage` abstract (form + persistence pipeline), `AdminManager` facade (deprecated), `Login` page, `Admin` middleware, `MwColors` token authority.
- [Examples](./examples.md) — custom Admin settings page, custom admin Filament resource, replacing the brand logo, scoping a render hook to specific resources.
- [Troubleshooting](./troubleshooting.md) — login autofill not working, can't reach `/admin`, middleware bypass on first install, render-hook order surprises, iframe detection.
