# Settings Module — API Reference

## REST API

Base URL: `/api/settings`

Routes registered in `Modules/Settings/routes/api.php` and `src/MicroweberPackages/Option/routes/api.php`. Write methods require Sanctum bearer with admin scope. Reads against sensitive groups (`email`, `system`) also require admin.

### `GET /api/settings` — list

| Param | Type | Default | Notes |
|---|---|---|---|
| `option_group` | string | — | Restrict to a group (`'website'`, `'email'`, etc.) |
| `module` | string | — | Restrict to a module scope |
| `lang` | string | — | Restrict to a locale (or `null` for non-localized) |
| `is_system` | int | — | `0` for user-editable; `1` for install-managed |

Response:

```json
{
    "data": [
        {
            "id": 12,
            "option_key": "website_title",
            "option_group": "website",
            "option_value": "My Awesome Site",
            "module": null,
            "lang": null,
            "is_system": 0,
            "created_at": "2026-05-13T10:00:00Z",
            "updated_at": "2026-05-13T10:00:00Z"
        }
    ]
}
```

### `GET /api/settings/{key}` — show

Returns a single option by `option_key`. With `?option_group=email&module=null` query params to disambiguate when the same key exists in multiple groups.

### `POST /api/settings` — create

```json
{
    "option_key": "custom_widget_enabled",
    "option_group": "website",
    "option_value": "1"
}
```

Optional fields: `module`, `lang`, `is_system`.

### `PUT /api/settings/{key}` — update

Same payload shape as `store`. Matches by `option_key` (+ optional disambiguator query params).

### `DELETE /api/settings/{key}` — destroy

Hard-deletes the row. Returns `204`. Sensitive `is_system = 1` rows are protected — destroy returns 422 unless `?force=1` is passed.

## Eloquent reference

### `MicroweberPackages\Option\Models\Option`

The data model. Lives in `src/MicroweberPackages/Option/Models/Option.php` (NOT in the Settings module).

#### Attributes

`id`, `option_key`, `option_group`, `option_value`, `module`, `is_system`, `lang`, `created_at`, `updated_at`.

#### Scopes

- `whereKey($key)`, `whereGroup($group)`, `whereModule($module)`, `whereLang($lang)` — query helpers
- `system()` — `WHERE is_system = 1`
- `userEditable()` — `WHERE is_system = 0`

### `MicroweberPackages\Option\Models\ModuleOption`

A wrapper view for module-scoped options. Usage:

```php
ModuleOption::forModule('GoogleAnalytics')->get();
```

## Global helpers

Defined in `src/MicroweberPackages/Option/helpers/options.php`:

```php
get_option(
    string $key,
    string|false $option_group = false,
    bool $return_full = false,
    string|false $orderby = false,
    string|false $module = false
): string|false|array;

save_option(
    string $optionKey,
    string|false $value = false,
    string|false $group = false,
    string|null $lang = false
): bool;

get_options(array $params = []): array;
```

## Repository

`app('option_repository')` returns the singleton `OptionRepository`.

| Method | Purpose |
|---|---|
| `get(string $key, ?string $group = null, ?string $module = null, ?string $lang = null)` | Single read |
| `getAll(array $params = [])` | Filtered list |
| `set(string $key, $value, ?string $group = null, ?string $module = null, ?string $lang = null)` | Upsert |
| `delete(string $key, ?string $group = null, ?string $module = null, ?string $lang = null)` | Hard-delete |
| `flushCache()` | Force request-cache + tagged-cache flush |

The repository caches by `(key, group, module, lang)` for the request lifecycle.

## Manager

`app('option_manager')` returns the singleton `OptionManager`. Wraps the repository with audit-event firing + automatic cache invalidation on writes. Prefer the manager for writes; the repository for reads.

## Filament admin

The Settings module registers these Filament pages (under `Modules\Settings\Filament\Pages\`):

- `AdminLanguagePage`
- `AdminEmailPage`
- `AdminShopOtherPage`
- `AdminTemplateCustomizerPage`
- `AdminMaintenanceModePage`

Each is a Livewire form that calls `save_option()` on submit.

## Events

The Option package fires Eloquent events (`saved`, `updated`, `deleted`) on the `Option` model. Listeners can register the standard way:

```php
\MicroweberPackages\Option\Models\Option::saved(function ($option) {
    \Log::info("Option saved: {$option->option_key} = {$option->option_value}");
});
```

The `OptionManager` listens to these for cache flush; custom listeners can audit-log, push to Slack, etc.

## CLI

```bash
# Read
php artisan option:get website_title --group=website

# Set
php artisan option:set website_title "My Site" --group=website

# List by group
php artisan option:list --group=email
```

The command lives at `src/MicroweberPackages/Option/Console/Commands/OptionCommand.php`.

## Testing

```bash
./vendor/bin/phpunit --filter=SettingsApiControllerTest
```

Coverage lives in `Modules/Settings/Tests/` (and `src/MicroweberPackages/Option/Tests/` for the data layer).
