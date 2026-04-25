# Legacy framework helpers

A handful of plain-PHP helper functions that pre-date the Laravel-centric
era are still alive in the current codebase, still callable from any
module, controller, or template, and still wired into core data paths.
This page documents the four families that survive — **options**,
**API exposure**, **events**, and **multisite config** — verified by
reading the current source rather than the old book that this page
salvages from.

> **Status:** salvaged 2026-04-25 from
> `microweber-docs/guides/modules_options.md`,
> `microweber-docs/guides/rest_api.md`,
> `microweber-docs/guides/framework_events.md`. Old code samples that
> referenced removed paths (`src/Microweber/...`) have been rewritten
> against current locations.

---

## Options API

**Source:** `src/MicroweberPackages/Option/helpers/options.php`

The options helpers are key/value persistence backed by the `options`
table. They're used by the Filament admin pages, the public-frontend
modules, and the live-edit toolbar — so changing a row through any of
them is visible to the others.

### `save_option($key, $value, $group, $lang = false)`

Persists a single row to the `options` table. The classic positional
form takes scalar args; passing an array as the first arg uses the
array verbatim as the row payload (this is what the Filament settings
pages do internally).

```php
// positional form
save_option('text_color', 'red', 'my_module_group_id');

// array form (used by Filament Livewire updates)
save_option([
    'option_key'   => 'text_color',
    'option_value' => 'red',
    'option_group' => 'my_module_group_id',
    'module'       => 'pictures',
]);
```

### `get_option($key, $group = false, $returnFull = false)`

Reads a single row. Pass `$returnFull=true` to get the whole row array
instead of just `option_value`.

```php
$color = get_option('text_color', 'my_module_group_id');
$row   = get_option('text_color', 'my_module_group_id', true);
```

### `save_module_option($key, $value, $group, $module)` / `get_module_option(...)`

Module-scoped variants. They forward to the same backing `options`
table but additionally tag the row with the module name so the same
key can hold per-module values without collisions. The Filament
LiveEdit settings pages use this pair on every reactive field update.

### `get_module_options($group, $module = false)` / `get_options($params)`

Bulk reads. `get_module_options()` returns every option attached to a
module instance; `get_options(['option_group' => 'foo'])` is a generic
filter-by-params query.

### `delete_option($key, $group, $moduleId)`

Removes a row. Used by the WordPressMigration commit job and the
admin "reset to defaults" actions.

---

## REST API exposure

**Source:** `packages/laravel-helper-functions/src/functions/api.php`,
consumed by `src/MicroweberPackages/App/Http/Controllers/ApiController.php`.

Modules can expose plain PHP functions under `/api/{function}` without
declaring a route. The Microweber `ApiController` walks the
`api_expose` registry on every request and dispatches to the matching
function name. Three scopes are available:

| Helper | Scope | Auth required |
| --- | --- | --- |
| `api_expose($name, $cb = null)` | Public | None |
| `api_expose_user($name, $cb = null)` | Logged-in users | Authenticated session |
| `api_expose_admin($name, $cb = null)` | Admin users | `is_admin === 1` |

Two call shapes:

```php
// Bind a callback under that endpoint:
api_expose_admin('mw_purge_image_cache', function () {
    return app()->media_manager->purge_thumbnails();
});

// Or expose an existing global function by name:
function my_module_save() { /* ... */ }
api_expose('my_module_save');
```

Either form makes the function reachable as `POST /api/{name}` via
`ApiController::index()`. The Filament admin pages still rely on this
for legacy save endpoints (`save_option`, `mw_save_module`, etc.).

---

## Events

**Source:** `src/MicroweberPackages/App/functions/events.php`

A small synchronous event bus for code paths that pre-date Laravel's
event dispatcher. The two helpers are `event_trigger($name, $data)`
and `event_bind($name, $callback)`. The bus is widely used by core
data save paths — see `src/MicroweberPackages/Database/Traits/ExtendedSave.php`,
which fires seven `mw.database.extended_save_*` events on every
ContentManager save.

```php
// Subscribe to an event from a service provider:
event_bind('mw.database.extended_save_categories', function ($params) {
    // $params has the saved row + categories payload
});

// Trigger an event from your own code:
event_trigger('my_module.after_save', ['id' => $id]);
```

### Stable events to listen for

These are the events that **still fire** in the current codebase
(verified against `event_trigger(...)` call sites). Listening to one
of these is the supported way to extend a core save without patching
the trait.

| Event | Where it fires | Payload |
| --- | --- | --- |
| `mw.database.extended_save` | `ExtendedSave::extendedSave()` | full save params |
| `mw.database.extended_save_images` | `ExtendedSave` | image rows + parent id |
| `mw.database.extended_save_attributes` | `ExtendedSave` | attribute rows + parent id |
| `mw.database.extended_save_custom_fields` | `ExtendedSave` | custom-field rows + parent id |
| `mw.database.extended_save_data_fields` | `ExtendedSave` | data-field rows + parent id |
| `mw.database.extended_save_categories` | `ExtendedSave` | category rows + parent id |
| `mw.database.extended_save_tags` | `ExtendedSave` | tag rows + parent id |
| `mw.install.complete` | `InstallController::index()` | the install input array |
| `mw.template.before_render` | `TemplateManager` | the layout name |
| `mw.template.print_custom_css_includes` | `TemplateCustomCss` | (none) |
| `mw.template.print_custom_css` | `TemplateCustomCss` | (none) |

Events listed in the old documentation that referenced
`src/Microweber/...` (`mw.admin`, `mw_frontend`, `mw_save_content`,
`on_load`, `mw.live_edit`, etc.) have **not** been audited in the
current codebase yet — treat them as historical. Run
`grep -rn "event_trigger\(['\"]name" src Modules` before writing a
listener for any old name.

---

## Multisite per-domain config

**Source:** `src/MicroweberPackages/App/Providers/AppServiceProvider.php`

The `Application::detectEnvironment()` callback in AppServiceProvider
checks for `config/<domain>/microweber.php` on every request. If that
file exists, the application boots with a domain-scoped environment
named after the domain — meaning each domain gets its own DB
connection, cache, and session keys.

```text
config/
├── domain-a.com/
│   └── microweber.php
├── domain-b.com/
│   └── microweber.php
└── microweber.php          # shared default
```

Both files can be empty initially; visiting `https://domain-a.com/`
the first time triggers the install flow, which writes the per-domain
DB credentials into `config/domain-a.com/microweber.php`. There's no
admin UI to manage this — you create the directory, run the install,
done.

DNS-wise, point each domain's `A` record at the same server IP. The
Laravel application discriminates on `Host:` header.

---

## What was deliberately *not* salvaged

The old `microweber-docs` book also covered:

- `function`/`classes` reference pages auto-generated from doc-blocks —
  superseded by the per-module `Modules/<X>/docs/README.md` files
  generated 2026-04-25 (see `docs/modules/README.md`).
- Component reference (`components/box.md`, `components/form.md`, etc.)
  for the old non-Filament admin UI — replaced by the kitchen-sink
  page at `/admin/kitchen-sink`.
- JavaScript helpers under `js-css/` (`mw.tabs`, `mw.modal`, etc.) —
  replaced by Livewire/Alpine in the Filament admin.
- The book's own static-site infra (`book.json`, `index.php`,
  `search.php`, `vendor/`, `assets/`) — not knowledge, just plumbing.

If you need any of those topics, search the current code first; if the
concept survives at all, it's likely in a renamed location and the old
sample would need a full rewrite anyway.
