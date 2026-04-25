# `Captcha` module

> **Slug:** `captcha`
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

## Service classes

### `Modules\Captcha\Services\CaptchaManager`

Source: `Services/CaptchaManager.php`.

  - `validate($key, $captcha_id = null, $unset_if_found = true)`
  - `render($params = array()`
  - `reset($captcha_id = null)`
  - `setAdapter($adapter)`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Captcha\Filament\CaptchaModuleSettings` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Captcha/Tests`

## Service providers

  - `Modules\Captcha\Providers\CaptchaServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
