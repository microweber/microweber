# Settings Module — Installation

The Settings module is a **core module** — ships with Microweber. The underlying data store is the `options` table owned by the Option package (`src/MicroweberPackages/Option/`).

## Prerequisites

- PHP ≥ 8.2
- Laravel 11 base
- Filament v5 — admin pages
- Livewire v4 — form state
- The Option package — the `options` table schema, `Option` model, and the global helpers live here

## Registration

Standard module pipeline:

1. **`Modules/Settings/module.json`** declares the module + provider
2. **`Modules/Settings/Providers/SettingsServiceProvider.php`** auto-discovers + registers the Filament admin pages, API routes, and module config
3. **`composer.json`** PSR-4: `"Modules\\Settings\\": "Modules/Settings/"`

The Option package is registered separately via `src/MicroweberPackages/Option/` autoload — it boots before Settings.

## Database schema

### `options` table (owned by the Option package)

| Column | Type | Notes |
|---|---|---|
| `id` | bigint primary | |
| `option_key` | varchar | The key, e.g. `'website_title'`, `'currency_symbol'` |
| `option_group` | varchar | Namespace, e.g. `'website'`, `'email'`, `'shop'`, `'template'` |
| `option_value` | text | The value (cast to string; complex types JSON-encoded) |
| `module` | varchar | Optional module scope when an option belongs to a specific module's settings |
| `is_system` | tinyint | `1` for options the install scripts manage (shouldn't be hand-edited) |
| `lang` | varchar | Optional locale for per-language overrides (`'en'`, `'es'`, etc.) |
| `created_at`, `updated_at` | timestamp | |

The unique key is roughly `(option_key, option_group, module, lang)` so the same key can exist under different namespaces / locales without collision.

## Filament admin pages

All under `/admin/settings/*` (slugs may differ per panel config):

| Page | URL path | Purpose |
|---|---|---|
| `AdminLanguagePage` | `/admin/settings/language` | Default + available locales, RTL toggle |
| `AdminEmailPage` | `/admin/settings/email` | SMTP host/port/user/password, from address, signature |
| `AdminShopOtherPage` | `/admin/settings/shop` | Currency, tax rules, inventory display |
| `AdminTemplateCustomizerPage` | `/admin/settings/template` | Active template + per-template style editor |
| `AdminMaintenanceModePage` | `/admin/settings/maintenance` | Maintenance toggle, allowed IPs, custom message |
| Main settings landing | `/admin/settings` | Card grid linking to each sub-page |

Each page is a standard Filament `Page` class with a Livewire form. Submitting the form writes via `save_option()`.

## What `microweber:install` does

Seeds the base `options` rows: site title, locale, default template, contact email, etc. Templates that ship `mw_default_content.zip` (Big2, Bootstrap) may overwrite some of these on first restore.

## Configuration

The module itself has no config keys beyond what the Option store manages. Behavior is driven by:

- The user's Filament panel config (which admin URL slug Settings registers under)
- The active template's Settings hooks (some templates expose per-template option groups via `style-settings.json`)

## Caching strategy

`get_option()` reads through `OptionRepository` which caches by `(option_key, option_group, module, lang)` for the request lifecycle. `save_option()` flushes the matching cache entry.

For multi-process invalidation (queue workers, scheduled tasks), the cache is keyed via Laravel's tagged cache:

```php
\Cache::tags(['options', 'settings'])->flush();
```

The `OptionManager` does this automatically on every write. Manual flushes are only needed when bypassing the manager (direct `\DB::table('options')->update(...)`).

## Disabling / replacing

Settings can be disabled (operators lose the Filament admin UI) but the data layer keeps working — programmatic `get_option()` / `save_option()` calls still resolve, and the REST API at `/api/settings` still functions. The site stops being configurable without re-enabling the module or hand-editing the `options` table via SQL.

To customize:

- Add a new section: extend Filament + create a new Page class under `Modules/Settings/Filament/Pages/`
- Override a section: subclass the existing Page and re-register with the panel
- Add a hidden config: write directly to `options` via `save_option('my_internal_flag', '1', 'system')`. It won't appear in any UI but `get_option('my_internal_flag', 'system')` will return the value.
