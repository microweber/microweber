# `<ModuleName>` module

> **Slug:** `<slug>`
> **Tier:** 1 (full data + API) | 2 (API only) | 3 (admin widget)
>
> One-paragraph summary of what the module does and who consumes
> it. Lead with the operator-side answer: "operators use this to
> X; contributors touch it to Y."

## Domain

The bigger conceptual area this module participates in. Cross-link
to sibling modules: e.g. for the Cart module, "Cart sits between
Product and Order; the Cart row is short-lived session state, the
Order row is the persistent record."

## Data model

For each database table the module owns, one subsection. Skip if
the module is API/service-only (tier 2/3).

### `<table_name>` table

  | Column     | Type            | Notes                            |
  |------------|-----------------|----------------------------------|
  | `id`       | bigint primary  |                                  |
  | `<col>`    | varchar(255)    | <inline notes / FK references>  |
  | `created_at` | timestamp     |                                  |
  | `updated_at` | timestamp     |                                  |

Relationships:

  - `<col_name>` → `<other_table>.<other_col>` (belongs-to / has-many).

Migrations: `Modules/<ModuleName>/database/migrations/`.

## Models

  | Eloquent class                                        | Table          | Purpose                                                   |
  |-------------------------------------------------------|----------------|-----------------------------------------------------------|
  | `Modules\<ModuleName>\Models\<X>`                     | `<table>`      | One-line role description.                                |

Notable scopes / observers / casts go inline as bullets under each.

## API endpoints

  | Method | Path                            | Auth         | Scope          | Controller                                                          |
  |--------|---------------------------------|--------------|----------------|---------------------------------------------------------------------|
  | GET    | `/api/module/<slug>`            | public       | —              | `Modules\<ModuleName>\Http\Controllers\Api\<X>ApiController@index`  |
  | GET    | `/api/module/<slug>/{id}`       | public       | —              | `…@show`                                                            |
  | POST   | `/api/module/<slug>`            | passport     | `<slug>:write` | `…@store`                                                           |
  | PUT    | `/api/module/<slug>/{id}`       | passport     | `<slug>:write` | `…@update`                                                          |
  | PATCH  | `/api/module/<slug>/{id}`       | passport     | `<slug>:write` | `…@update`                                                          |
  | DELETE | `/api/module/<slug>/{id}`       | passport     | `<slug>:write` | `…@destroy`                                                         |

Routes: `Modules/<ModuleName>/routes/api.php`. Bootstrap loader:
the module's service provider via `loadRoutesFrom(...)`.

If the module also exposes legacy `/api/<slug>/*` routes, list them
in a separate sub-table.

## Service classes

  | Class                                                   | Purpose                                                    |
  |---------------------------------------------------------|------------------------------------------------------------|
  | `Modules\<ModuleName>\Services\<X>`                     | One-line role.                                             |

For each service, list its public methods + their contract
(input → output, side-effects, exceptions). Container bindings
go in this same section.

## Events

  | Event class                                  | Dispatched when…                          | Listeners                              |
  |----------------------------------------------|-------------------------------------------|----------------------------------------|
  | `Modules\<ModuleName>\Events\<X>`            | …                                         | `Modules\<ModuleName>\Listeners\<Y>`   |

## MCP tools (if any)

If the AI module's MCP catalog exposes tools backed by this
module, list them:

  | Tool name              | Source class                                          | Read/Write |
  |------------------------|-------------------------------------------------------|------------|
  | `<slug>.<verb>`        | `Modules\Ai\Tools\<X>Tool`                            | read       |

Cross-link to `docs/mcp/README.md` for the wire-protocol contract.

## Filament admin

Top-level Filament resources / pages the module registers:

  | Class                                                              | Type        | Route                                |
  |--------------------------------------------------------------------|-------------|--------------------------------------|
  | `Modules\<ModuleName>\Filament\Resources\<X>Resource`              | Resource    | `/admin/<slug>`                      |
  | `Modules\<ModuleName>\Filament\Pages\<X>SettingsPage`              | Page        | `/admin/<slug>-settings`             |

## Tests

  | Suite                                                | Coverage                                       |
  |------------------------------------------------------|------------------------------------------------|
  | `Modules/<ModuleName>/Tests/Unit/<X>Test`            | What this suite pins.                          |
  | `Modules/<ModuleName>/Tests/Feature/<X>Test`         | What this suite pins.                          |

Run: `php vendor/bin/phpunit Modules/<ModuleName>/Tests`.

## Configuration

  | Env var / config key                  | Default         | Purpose                              |
  |---------------------------------------|-----------------|--------------------------------------|
  | `MODULE_<NAME>_<KNOB>`                | `<default>`     | One-line role.                       |

Config file: `Modules/<ModuleName>/config/config.php`.

## Further reading

  - Repo-wide:
      - [`docs/mcp/README.md`](../mcp/README.md) — MCP server.
      - [`docs/architecture-guide.md`](../architecture-guide.md) — overall layout.
  - Sibling modules: cross-link to closely-related modules.
