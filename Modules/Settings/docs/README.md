# `Settings` module

> **Slug:** `settings`
> **Tier:** 1 (full data + API)
>
> Operator-facing entry point for the global `options` table.
> Operators set site title / SEO defaults / e-commerce flags
> through Filament settings pages (`/admin/settings/*`); contributors
> read these values via `Option::getValue(...)` from any module that
> needs site-level config.
>
> This module owns no tables of its own — it's a thin wrapper +
> Filament admin page + API controller around the
> `MicroweberPackages\Option` package's `options` table.

## Domain

Site-wide configuration. Sits next to the `Option` package
(`src/MicroweberPackages/Option/`), which owns the underlying
`options` table + `Option` model + `OptionApiController`.
This module's job is the operator-facing wrapping: Filament admin
pages, the `/api/module/settings/*` REST surface, and the
`SettingsReadTool` that the AI module's MCP catalog exposes for
read-only AI client access.

## Data model

Owned by the [`Option` package](../../../src/MicroweberPackages/Option),
not by this module. Schema summary (canonical version in the
Option package's migrations):

### `options` table

  | Column         | Type             | Notes                                              |
  |----------------|------------------|----------------------------------------------------|
  | `id`           | bigint primary   |                                                    |
  | `option_key`   | varchar(255)     | Stable identifier within `option_group`.           |
  | `option_value` | text             | Free-form scalar / JSON.                           |
  | `option_group` | varchar(255)     | e.g. `website`, `template`, `ecommerce`.           |
  | `module`       | varchar(255)     | Owning module slug for live-edit-scoped options.   |
  | `is_active`    | tinyint          |                                                    |
  | `created_at`   | timestamp        |                                                    |
  | `updated_at`   | timestamp        |                                                    |

Lookups: by `(option_group, option_key)` is the canonical access
pattern; `module` is used by the live-edit + module-settings flows.

## Models

  | Eloquent class                                  | Table     | Purpose                                          |
  |-------------------------------------------------|-----------|--------------------------------------------------|
  | `MicroweberPackages\Option\Models\Option`       | `options` | The Eloquent representation. Lives in the Option package, not this module. |

## API endpoints

  | Method | Path                              | Auth     | Scope            | Controller                                                          |
  |--------|-----------------------------------|----------|------------------|---------------------------------------------------------------------|
  | GET    | `/api/module/settings`            | public   | —                | `Modules\Settings\Http\Controllers\Api\SettingsApiController@index` |
  | GET    | `/api/module/settings/{key}`      | public   | —                | `…@show`                                                            |
  | POST   | `/api/module/settings`            | passport | `settings:write` | `…@store`                                                           |
  | PUT    | `/api/module/settings/{key}`      | passport | `settings:write` | `…@update`                                                          |
  | PATCH  | `/api/module/settings/{key}`      | passport | `settings:write` | `…@update`                                                          |
  | DELETE | `/api/module/settings/{key}`      | passport | `settings:write` | `…@destroy`                                                         |

Routes file: `Modules/Settings/routes/api.php`. Loader:
`SettingsServiceProvider` via `loadRoutesFrom()`. The `{key}` route
parameter is the option's `option_key` (a stable string), not a
numeric id — option ids aren't operationally meaningful.

The public read surface is restricted server-side to a whitelist
of safe option keys; sensitive keys (passwords, API tokens) are
filtered out by the controller before serialization.

## Service classes

This module ships no service classes of its own; it delegates to
the `Option` package's `OptionRepository` (resolved as
`app()->option_repository`) and the `Option` facade. Helper
shorthand `\Option::getValue($key, $default = null, $group = null)`
is used widely across the codebase.

## Events

None dispatched by this module. The Option package may dispatch
cache-invalidation events when options change.

## MCP tools

The AI module's MCP catalog exposes one read-only tool against
this surface:

  | Tool name        | Source class                            | Read/Write |
  |------------------|-----------------------------------------|------------|
  | `settings.read`  | `Modules\Ai\Tools\SettingsReadTool`     | read       |

Cross-link: [`docs/mcp/README.md`](../../../docs/mcp/README.md) for
the wire-protocol contract; the tool's input-schema contract is
pinned by `Modules/Settings/Tests/Unit/Mcp/SettingsReadToolUnitTest`.

## Filament admin

  | Class                                                            | Type | Route                            |
  |------------------------------------------------------------------|------|----------------------------------|
  | `Modules\Settings\Filament\Pages\AdminGeneralPage`               | Page | `/admin/settings`                |
  | `Modules\Settings\Filament\Pages\AdminEmailPage`                 | Page | `/admin/settings-email`          |
  | `Modules\Settings\Filament\Pages\AdminSeoPage`                   | Page | `/admin/settings-seo`            |
  | `Modules\Settings\Filament\Pages\AdminTemplatePage`              | Page | `/admin/settings-template`       |

Each admin page reads + writes `options` rows via the Option
repository.

## Tests

  | Suite                                                                | Coverage                                            |
  |----------------------------------------------------------------------|-----------------------------------------------------|
  | `Modules/Settings/Tests/Filament/SettingsPagesTest`                  | Filament admin-page render + form save round-trip.  |
  | `Modules/Settings/Tests/Filament/SettingsResourceTest`               | Filament resource render.                           |
  | `Modules/Settings/Tests/Unit/Filament/ModuleConfigurationResourceTest` | Module-config resource unit shape.                |
  | `Modules/Settings/Tests/Unit/Mcp/SettingsReadToolUnitTest`           | MCP `settings.read` tool metadata + error path.     |

Run: `php vendor/bin/phpunit Modules/Settings/Tests`.

## Configuration

This module exposes no env-var knobs of its own. The underlying
Option repository's caching is configurable through the Option
package; see `src/MicroweberPackages/Option/`.

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/mcp/README.md`](../../../docs/mcp/README.md) — MCP server + `settings.read` tool.
  - [`src/MicroweberPackages/Option/`](../../../src/MicroweberPackages/Option) — the underlying Option package.
