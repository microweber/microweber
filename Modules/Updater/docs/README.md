# `Updater` module

> **Slug:** `updater`
> **Tier:** 2
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

This module owns no migrations of its own.

## API endpoints

Route files exist but no parseable `Route::method` calls were
found:

  - `routes/api.php`
  - `routes/web.php`

## Controllers

### `Modules\Updater\Http\Controllers\UpdaterController`

Source: `Http/Controllers/UpdaterController.php`.

  - `updateNow(Request $request)`
  - `updateFromCli($branch = 'master')`

## Service classes

### `Modules\Updater\Services\UpdaterHelper`

Source: `Services/UpdaterHelper.php`.

  - `getLatestVersion($selectedBranch = 'master')`
  - `getCanUpdateMessages(): array`
  - `copyStandaloneUpdater($updateCacheDir,$skipUi=false)`
  - `generateStandaloneUpdaterFile($stubsPath, $skipUi = false)`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Updater\Filament\Pages\UpdaterPage` | System Settings | Updater |

## Service providers

  - `Modules\Updater\Providers\UpdaterServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
