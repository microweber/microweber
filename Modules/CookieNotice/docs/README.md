# `CookieNotice` module

> **Slug:** `cookie-notice`
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

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `POST` | `api/cookie-notice/set-cookie` | `CookieNoticeController::setCookie` |

## Controllers

### `Modules\CookieNotice\Http\Controllers\Api\CookieNoticeController`

Source: `Http/Controllers/Api/CookieNoticeController.php`.

  - `setCookie(Request $request): JsonResponse`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\CookieNotice\Filament\Pages\CookieNoticeModuleSettingsAdmin` | Website Settings | Cookie Notice |

## Tests

Run: `php vendor/bin/phpunit Modules/CookieNotice/Tests`

## Service providers

  - `Modules\CookieNotice\Providers\CookieNoticeServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
