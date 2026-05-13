# Settings Module

> **Slug:** `settings`
> **Tier:** 1 (admin Filament pages over the Option store)
> **Source:** `Modules/Settings/`

The Settings module is Microweber's **admin-side configuration UI** — the collection of Filament pages operators use to configure site title, contact email, shop currency, language, template choice, maintenance mode, and dozens of other site-wide options. Where the Option store (`src/MicroweberPackages/Option/`) owns the data layer (`options` table + `get_option()` / `save_option()` helpers), Settings owns the **operator UX** that sits on top.

## What this module does

- Provides the Filament admin pages under `/admin/settings/*`:
  - General settings (site title, description, contact info)
  - Email (SMTP config, from address, signature)
  - Shop (currency, tax rates, inventory display)
  - Language (locale, available languages, RTL toggle)
  - Template customizer (active template, style overrides)
  - Maintenance mode (toggle, message, allowed IPs)
- Exposes a RESTful API at `/api/settings` for CRUD on options
- Renders the public-side settings overview via `<module type="settings" />` (admin preview)
- Provides per-section validation + value coercion (e.g. SMTP port must be numeric)
- Integrates with the Translation module for per-locale option overrides

## Domain

Settings sits at the **configuration layer** of Microweber. Where the Content module owns data rows and Page/Post/Product own typed content, Settings owns the key/value `options` table that everything else reads to find out "how should I behave?":

- "Which template renders the public site?" → `option('current_template', 'template')`
- "What currency does the shop display?" → `option('currency_symbol', 'website')`
- "Is maintenance mode on?" → `option('maintenance_mode', 'website')`
- "What SMTP server sends mail?" → `option('email_smtp_server', 'email')`

The data layer is owned by:

- `src/MicroweberPackages/Option/` — the `Option` Eloquent model + `OptionRepository` + the `OptionManager` singleton + the `options` table migrations + the global helpers `get_option()` / `save_option()` / `get_options()`

The Settings module is the **operator interface** to that data — a thin Filament UI that calls `save_option()` under the hood.

Cross-references:

- **Option package** (`src/MicroweberPackages/Option/`) — the data layer. The global helpers + the `Option` model live there.
- **Template module** — `current_template` option drives which `Templates/<x>/` directory renders public pages.
- **Translation / Multilanguage modules** — per-locale option overrides.
- **Backup module** — exports/imports the entire `options` table for site migration.

## Documentation map

| Page | Purpose |
|---|---|
| [`index.md`](./index.md) | This overview |
| [`installation.md`](./installation.md) | Schema, registration, Filament page auto-discovery |
| [`usage.md`](./usage.md) | Reading + writing options, helper layer, caching, multilanguage |
| [`api.md`](./api.md) | REST + Option Eloquent reference, helpers, manager |
| [`examples.md`](./examples.md) | End-to-end recipes |
| [`troubleshooting.md`](./troubleshooting.md) | Common issues |

## Quick start

```php
// Read
$title = get_option('website_title', 'website');
$currency = get_option('currency_symbol', 'website') ?: '$';

// Write
save_option('website_title', 'My Awesome Site', 'website');
save_option('maintenance_mode', '1', 'website');

// Bulk read by group
$emailConfig = get_options(['option_group' => 'email']);
```

Operators set the same values via `/admin/settings` → choose section → fill the form → save.

## Key files

- `Modules/Settings/Filament/Pages/AdminLanguagePage.php` — language + locale config
- `Modules/Settings/Filament/Pages/AdminEmailPage.php` — SMTP config
- `Modules/Settings/Filament/Pages/AdminShopOtherPage.php` — shop options
- `Modules/Settings/Filament/Pages/AdminTemplateCustomizerPage.php` — template chooser + style editor
- `Modules/Settings/Filament/Pages/AdminMaintenanceModePage.php` — maintenance toggle
- `Modules/Settings/Http/Controllers/Api/SettingsApiController.php` — REST CRUD (5 methods)
- `Modules/Settings/Providers/SettingsServiceProvider.php` — module bootstrap
- `src/MicroweberPackages/Option/Models/Option.php` — the data model (lives in the Option package)
- `src/MicroweberPackages/Option/helpers/options.php` — `get_option` / `save_option` / `get_options` global helpers
- `src/MicroweberPackages/Option/Repositories/OptionRepository.php` — query layer
- `src/MicroweberPackages/Option/OptionManager.php` — write + cache layer

## Status

Production-stable. The Option store has been stable for many releases; Settings is the operator-facing admin shell over it. Most "settings bugs" trace to either the helper-layer cache (option set but read returns the old value — see troubleshooting) or per-locale lookup (option set in `en` not visible to `es` requests).
