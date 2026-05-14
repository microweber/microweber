# Module Authoring Guide

> **Cycle-113 / AI-122 / TICKET-CE (2026-05-09)** — how to author a
> new first-party module in `Modules/<X>/`. Pairs with PROJECT.md
> (architecture overview), SETUP.md (install + run), and the ADRs
> in `docs/adr/`.

---

## When to add a module

Add a module when you need:

1. **A new content type** (e.g. "Recipes" — a new `content_type`
   stored in the `content` table with custom fields and a public
   skin pipeline).
2. **A new admin Filament resource** that doesn't fit any existing
   module's responsibility (e.g. a custom inventory dashboard).
3. **A new public-facing UI block** with multiple skins (e.g. a
   "Pricing Table" module the user can drop into a page).
4. **A new domain** that integrates with external services (e.g.
   "Subscriptions" with Stripe webhook handlers).

Don't add a module if the work fits an existing one — extend the
existing module with new resources / skins / services first.

---

## Module skeleton

```
Modules/MyModule/
├── module.json                   # Manifest: name, providers, alias
├── composer.json                 # Optional — for separate composer release
├── Providers/
│   └── MyModuleServiceProvider.php  # Bind services into the container
├── Models/
│   └── MyModel.php               # Eloquent models
├── Http/
│   ├── Controllers/
│   │   ├── Api/                  # API controllers (JSON responses)
│   │   └── Admin/                # Admin-only controllers
│   ├── Middleware/               # Module-scoped middleware
│   └── Requests/                 # FormRequest validation
├── routes/
│   ├── web.php                   # Public routes
│   ├── api.php                   # API routes
│   └── admin.php                 # Admin-only routes
├── database/
│   ├── migrations/               # Tables + indexes
│   └── seeders/                  # Seed data
├── resources/
│   ├── views/
│   │   ├── templates/            # Public-facing skins (Blade)
│   │   ├── partials/             # Shared partials
│   │   └── livewire/             # Livewire / Filament views
│   └── assets/
│       ├── css/                  # Module-specific stylesheet
│       └── js/                   # Module-specific JS
├── Services/
│   └── MyModuleService.php       # Business logic
├── Filament/
│   └── Admin/
│       ├── Resources/            # Filament resources
│       └── Pages/                # Filament pages
├── Tests/
│   ├── Unit/
│   └── Feature/
└── Support/
    └── helpers.php               # Module-scoped global helpers (sparingly)
```

The minimum viable module is just `module.json` +
`Providers/MyModuleServiceProvider.php`. Everything else is added
on demand.

---

## module.json

```json
{
    "name": "MyModule",
    "alias": "my_module",
    "description": "What the module does (one line).",
    "keywords": ["tag1", "tag2"],
    "priority": 0,
    "providers": [
        "Modules\\MyModule\\Providers\\MyModuleServiceProvider"
    ],
    "files": []
}
```

The `alias` is the kebab-case identifier used by the template
engine: `<module type="my_module" />` resolves to the default skin
in `Modules/MyModule/resources/views/templates/default.blade.php`.

---

## Skins

Public-facing UI lives in `resources/views/templates/<skin>.blade.php`.
The default skin is `default.blade.php`. The Live Edit picker
auto-discovers any other `<skin>.blade.php` files and exposes them
in the per-module skin select.

Each skin file declares its metadata in a top-of-file PHP doc-comment:

```blade
@php
/*
type: layout
name: Card grid
description: 4-column card grid with hover effects.
*/
@endphp

<div class="my-module-card-grid">
    @foreach($items as $item)
        <article class="card">…</article>
    @endforeach
</div>
```

### Skin authoring rules (post-cycle-89/103)

- **No inline `<style>` blocks** — use a shared stylesheet under
  `resources/assets/css/<skin>.css` loaded via `@once <link
  rel="stylesheet">`. The AI-113 grep-gate blocks
  `style="...background-image: url('&#123;&#123; thumbnail|asset...`.
- **No inline `onclick=` with Blade interpolation** — use
  `data-mw-action` + a delegated listener. The AI-113 grep-gate
  blocks the pattern.
- **`<img>` not `background-image`** — for content imagery, use
  `responsive_thumbnail()` (AI-115 cycle-105 added eager-first-2
  request-scoped counter; first 2 images per request get
  `loading="eager"`, rest lazy).
- **Schema.org microdata** — wrap content blocks in
  `itemscope itemtype="..."` so search engines + browsers index
  the content. Don't duplicate `itemprop="url"` (cycle-90 / AI-78
  cleanup).

---

## Filament resources

Admin surfaces use Filament v5. Place resources under
`Filament/Admin/Resources/<Name>Resource.php`. The resource is
auto-discovered by the panel provider via the
`FilamentRegistry::getPlugins()` registry.

Per AI-85 / cycle-96: typed `TextInput` fields MUST declare
their validation chain:
- `email` field → `->email()`
- `phone` field → `->tel()`
- `website` / `url` field → `->url()`
- numeric fields → `->numeric()`

The `tests/Feature/FilamentTextInputValidationSweepContractTest.php`
audit script enforces zero gaps in `Modules/**/Filament/`.

---

## Services + Repositories

Business logic lives in `Services/`. Repositories (DB query
layer) live in `Repositories/`. The convention:

- Repository = "how do I get / store this data?"
- Service = "what business rule do I apply to it?"
- Controller = "translate HTTP into service calls".

Services are bound into the container via
`Providers/MyModuleServiceProvider::register()`:

```php
public function register()
{
    $this->app->singleton('my_module_service', function () {
        return new MyModuleService();
    });
}
```

Then accessed via service-locator:

```php
$result = app('my_module_service')->doSomething();
```

The AI-105 / TICKET-AY foundation cycle is moving towards explicit
interfaces (`Modules\Cart\Contracts\CartManagerContract` etc.) so
modules can depend on contracts not concrete classes. New modules
SHOULD declare their service contracts.

---

## Tests

Module tests live in `Modules/<X>/Tests/`. Run via:

```bash
php vendor/bin/phpunit Modules/MyModule/Tests
```

Per `tests/README.md`:
- No `RunInSeparateProcess`.
- No `DatabaseTransactions` / `RefreshDatabase`.
- Module tests run in their own group via the
  `Modules-<Group>` testsuites in `phpunit.xml` (avoids OOM on
  the ~6MB-per-test memory leak).

For shape-only regression tests (no DB / no Filament mount),
prefer the **contract test** pattern under `tests/Feature/`:

```php
namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MyModuleContractTest extends TestCase
{
    #[Test]
    public function module_declares_its_service_contract(): void
    {
        $src = file_get_contents(base_path(
            'Modules/MyModule/Providers/MyModuleServiceProvider.php'
        ));
        $this->assertStringContainsString('->singleton(', $src);
    }
}
```

The cycle-52..N contract test family is the bulk of new tests.
See `tests/Feature/Ai107DbIndexesContractTest.php` for a recent
example.

---

## Asset publishing

When you add files under `Modules/MyModule/resources/assets/`,
they need to be published to `public/modules/my_module/` so the
browser can fetch them. The publish flow:

1. Add the asset file (e.g. `resources/assets/css/skin-1.css`).
2. Run `php artisan module:publish` (locally + as a deploy step).
3. The file copies to `public/modules/my_module/css/skin-1.css`
   (gitignored — deploy artefact).
4. Reference it from a Blade skin via `asset('modules/my_module/css/skin-1.css')`.

The `public/modules/` mirror is gitignored; never edit those
copies directly.

---

## Frontend bundle integration

If the module needs JS that integrates with the global `mw.*`
runtime (Live Edit, cart events, dispatch bus), prefer adding
the JS to `packages/frontend-assets/resources/assets/...` and
re-bundling via `npm run build`, rather than shipping module-
local JS that mounts independently.

The frontend-assets-bundle-rebuild skill covers the full rebuild
+ verify workflow.

---

## Common gotchas

- **Don't subscribe to `mw.app.on(...)` AFTER an event has fired** —
  the bus has no replay buffer (per the
  `mw-app-event-bus-no-replay` skill). AI-106 / TICKET-AZ adds an
  event_log replay buffer for backfill scenarios.
- **Live-edit CSS must scope under `.mw-live-edit-page`** — bare
  `.fi-*` selectors in `live-edit-*.css` leak to every Filament
  admin page (per the `live-edit-css-must-be-scoped` skill +
  cycle-107 / AI-116).
- **Filament panel registry is a singleton** — don't try to
  parallel-test modules that mount Filament resources.
- **`@apply` Tailwind directives need an active build** — module
  CSS that uses `@apply` requires the build pipeline to be running
  during dev (`npm run dev`).
- **Blade &#123;&#123;-- comments are stripped at render time** but ARE
  visible to greps — strip them in regression-test regexes that
  scan for "this code shape".
