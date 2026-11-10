# Microweber Dusk

Shared Laravel Dusk helpers for the Microweber browser test suite — portable across CMS configurations. Registers reusable `Browser` macros (iframe switching with optional JS-coverage capture) and a single autoloadable home for JS coverage persistence.

Part of the [Microweber](https://microweber.com) CMS.

## Features

- **Browser macros** (registered on boot, only when `laravel/dusk` is present):
  - `switchFrame($frame)` — save coverage, then switch the WebDriver context into the named iframe.
  - `switchFrameDefault()` — save coverage, then switch back to the default (top) content.
  - `saveDuskCoverage()` — capture `window.__coverage__` into the coverage sink (no-op when empty).
- **`DuskCoverage::save()`** — persists collected JS coverage to `tests/coverages/js/` as timestamped JSON. This fixes a long-standing PSR-4 mismatch where the legacy coverage writer was never autoloadable, so coverage was silently never written.

## Installation

```bash
composer require --dev microweber-packages/microweber-dusk
```

`DuskPackageServiceProvider` registers the Browser macros on `packageRegistered()`. The macros are consumed by the legacy browser suites (`tests/BrowserLegacy`) and are available to any Dusk test.

## Usage

The macros are available on any Dusk `Browser` instance once the provider is loaded:

```php
$browser->visit('/admin')
    ->switchFrame('live-edit-iframe')   // enter the canvas iframe (captures coverage first)
    ->assertSee('Edit')
    ->switchFrameDefault();             // back to the top window
```

Capture coverage explicitly at any point:

```php
$browser->saveDuskCoverage();
```

Or persist a raw coverage payload directly:

```php
use MicroweberPackages\Dusk\DuskCoverage;

DuskCoverage::save($driver->executeScript('return window.__coverage__'));
// writes tests/coverages/js/js-coverage-<time>_<uniqid>.json (no-op if empty)
```

## Key classes

- Provider / macro registrar: `MicroweberPackages\Dusk\DuskPackageServiceProvider`
- Coverage sink: `MicroweberPackages\Dusk\DuskCoverage` (`save($coverage): void`)

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
