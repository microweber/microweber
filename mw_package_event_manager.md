# Event Manager extraction — `mw_package_event_manager.diff`

Jira: **AI-1299** · Program: `PLAN/PACKAGES_EXTRACTION_PROGRAM.md` (Tier-1, same-namespace relocation)

Extracts the event manager out of the application tree into a standalone,
reusable Composer package.

| | |
|---|---|
| **From** | `src/MicroweberPackages/Event/` |
| **To** | `packages/microweber-event-manager/` |
| **Package name** | `microweber-packages/event-manager` |
| **Namespace** | `MicroweberPackages\Event` *(unchanged — same-namespace relocation)* |
| **Version** | `1.0.0` |
| **Provider** | `MicroweberPackages\Event\EventManagerServiceProvider` |
| **Facade alias** | `EventManager` → `MicroweberPackages\Event\EventManagerFacade` |

Because the namespace is preserved, **no consumer code changes** — every
`MicroweberPackages\Event\*` reference and the manual provider registration keep
working unchanged.

## What moved

Classes relocated to `packages/microweber-event-manager/src/`:

- `Event.php` — the event manager (`on`/`trigger`/`response`), bound as `event_manager`
- `LaravelEvent.php` — Laravel bridge (`$hooks` registry)
- `EventManagerServiceProvider.php` — registers the `event_manager` singleton (`DeferrableProvider`)
- `EventManagerFacade.php` — the `EventManager` facade
- `helpers.php` — the `event_trigger()` / `event_bind()` global helpers

Removed from the old location: the in-tree `composer.json`, `phpunit.xml`,
`.travis.yml`, `README.md`, and `tests/`.

## How the helper functions load

`event_trigger()` / `event_bind()` are global functions, loaded via Composer's
**`files` autoload** — i.e. at `require vendor/autoload.php`, *before* the
application boots. The generated `vendor/composer/autoload_files.php` registers
the package's `helpers.php` under two hash keys:

| hash | path | source |
|---|---|---|
| `29208f77…` | `vendor/microweber-packages/event-manager/src/helpers.php` | the **package** `composer.json` → `"files": ["src/helpers.php"]` |
| `fea70be5…` | `packages/microweber-event-manager/src/helpers.php` | the **root** `composer.json` `files` entry (same file via the path symlink) |

Composer includes each hash once, so the file is included twice — but it is
guarded:

```php
if (!function_exists('event_trigger')) { function event_trigger(...) { ... } }
if (!function_exists('event_bind'))    { function event_bind(...)    { ... } }
```

…so the second include is a no-op. A third definition lives in
`src/MicroweberPackages/App/functions/events.php` (included at
`bootstrap.php:62`), now wrapped in the same `function_exists` guards — also a
no-op since the functions are already defined by the time bootstrap runs.

**Net effect:** the package's `helpers.php` defines the functions first; the
root `files` entry and `App/functions/events.php` are guarded fallbacks.

> Note: the root `composer.json` `files` entry for the package helper is
> redundant with the package's own `files` autoload (both load the same file).
> Harmless (guarded), and removable if desired.

## Composer wiring

Root `composer.json`:

```jsonc
// autoload.psr-4
"MicroweberPackages\\Event\\": "packages/microweber-event-manager/src/",

// autoload.files   (was: src/MicroweberPackages/Event/helpers.php)
"packages/microweber-event-manager/src/helpers.php",

// require
"microweber-packages/event-manager": "^1.0",

// repositories[] — path repo
{ "type": "path", "url": "packages/microweber-event-manager", "options": { "reference": "config", "symlink": true } },

// extra.laravel.dont-discover
"microweber-packages/event-manager"
```

Root `phpunit.xml` adds a `MicroweberEventManager` test suite pointing at
`packages/microweber-event-manager/tests/Cms`.

## Provider registration

`EventManagerServiceProvider` is **registered manually** in
`MicroweberServiceProvider` (`$this->app->register(EventManagerServiceProvider::class)`),
unchanged by the move. The package's `composer.json` also declares the provider
under `extra.laravel.providers`, so the package is added to
**`extra.laravel.dont-discover`** to avoid registering it twice (manual +
auto-discovery).

## Review fixes applied on top of the diff

1. **Missing `require` entry** — the diff added the path repo + autoload but not
   the `require`. Added `microweber-packages/event-manager: ^1.0` (so the path
   package is installed + locked).
2. **`dont-discover`** — added the package, because the provider is already
   registered manually (the diff's package composer.json newly declares the
   provider; the old in-tree composer.json did not).
3. **Package standalone tests** — the package `phpunit.xml` included `tests/Cms`,
   whose tests use the application's `Tests\TestCase` (meant for the root
   `MicroweberEventManager` suite, not the package's testbench run). Excluded
   `tests/Cms` from the package suite.

## Verification

- Class resolution: `Event`, `EventManagerServiceProvider`, `EventManagerFacade`,
  `LaravelEvent` all resolve from the new path.
- `app('event_manager')` → `MicroweberPackages\Event\Event` (single binding — no
  double-registration).
- `event_bind()` + `event_trigger()` round-trip fires the listener; no function
  redeclaration error.
- Package standalone suite: **6 tests** (testbench).
- Root `MicroweberEventManager` suite (`tests/Cms`): **5 tests**.
- Core regression suite: **358 OK** (3 fewer than before only because the old
  in-tree Event tests now live in the package / Cms suites — not a regression).
- No stale `src/MicroweberPackages/Event/` path references remain.
