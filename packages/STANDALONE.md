# Standalone package testing

Goal: every `packages/*` should install and test as a **standalone Laravel package**,
decoupled from the Microweber monorepo (Orchestra Testbench boots a real Laravel app
for the package; sibling `microweber-packages/*` deps resolve via a local path repo).

## Harness

```bash
scripts/standalone-test.sh <package>     # e.g. microweber-pagination
scripts/standalone-test.sh --all         # every scaffolded package
```

It copies the package to a throwaway workspace, strips monorepo-relative path
repositories, injects an absolute wildcard `path` repo for all `packages/*` (so
`microweber-packages/*` deps resolve without Packagist), runs `composer install`,
then the package's own `phpunit`. The real package dir is never touched; generated
`vendor/` + `composer.lock` are gitignored.

**Gold-standard template:** `microweber-pagination` — complete `composer.json`
(runtime deps + `require-dev` with `orchestra/testbench` + `phpunit`), own
`phpunit.xml` bootstrapping `vendor/autoload.php`, Testbench `tests/TestCase.php`.
A new standalone package should mirror its shape.

## Status (last full sweep: 2026-10-17)

**33 / 53 scaffolded packages pass fully standalone.**

### ✅ PASS (standalone-ready) — 33
app-bootstrap-cache, blade-cache, class-loader, config, config-merge, content-field,
db-installer, db-migrator, disposable-email-checker, env-writer, event-manager,
filament-registry, filament-theme, filesystem, file-uploader, format, http,
livewire-modal, module-registry, multisite, notification, package, pagination,
repository, searchable, security, svg-icons, taggable-file-cache, thumbnailer,
translation, url, view, zip.

### ❌ FAIL — 20, split by nature

**(a) Monorepo-bound by design (6)** — exercise the full CMS data layer: the custom
Eloquent `CachedBuilder` + `DatabaseManager` facade + (fortify/social-login) the
`MicroweberPackages\Core\tests\TestCase` base needing a User model, auth and DB.
Making these "standalone" would mean recreating the CMS runtime in each package's
Testbench harness (register DatabaseServiceProvider + EventManager + taggable cache +
all migrations + User model) — which defeats the point. Treat as monorepo-only:
**core, database, db-export, system-licenses, social-login, fortify.**

**(b) Fixable — dual-mode Testbench harness, setup gaps (14)** — these already have a
standalone Testbench branch in their TestCase; failures are missing migrations/
bindings/mocks or surfaced test bugs, not fundamental coupling:
ai-tools (test provider must bind `aitools.registry`), cdn-sync, filament-modal-teleport,
image-optimization, mail-sender, media-pixum, media-thumbnail, minifier,
package-manager-client, passport, phpquery, queue, template-custom-css, template-fonts.

Root-cause fix classes for group (b):
- **Dusk tests swept into the suite** — `<exclude>tests/Dusk</exclude>` in the package
  `phpunit.xml` testsuite (done for ai-tools).
- **In-memory sqlite without migrations** — the Testbench `TestCase` must
  `loadMigrationsFrom(...)` the package migrations.
- **Missing container bindings** — the Testbench `getPackageProviders()` must register
  the package's real provider so facades/string keys resolve.
- **Test bugs surfaced standalone** — wrong class/alias in assertions (fixed two in
  event-manager; `queue` `StandaloneAppValidationTest` references `ChunkedDispatcher`
  without importing it — use the Facade alias or `...\Services\ChunkedDispatcherService`).

## Already fixed this pass (28 → 33)
- **16 package `phpunit.xml`** files bootstrapped the **monorepo** vendor
  (`../../vendor/autoload.php`) instead of their own — fixed (monorepo uses the root
  phpunit.xml, so safe).
- **Harness empty-object bug** — stripping `repositories` via `json_decode(...,true)`
  turned empty `{}` keys into `[]`, breaking composer's schema (false install fail on
  filament-theme). Now decodes as objects.
- **Install-layer dep fixes:** app-bootstrap-cache `illuminate/foundation` →
  `laravel/framework`; filesystem + file-uploader `enshrined/svg-sanitize` →
  `^0.22|^1.0` (0.18–0.22 are advisory-blocked); fortify `livewire ^3.0 → ^4.0`.
- **Core `microweber-database` robustness** (no-op in the monorepo, verified; required
  by every database-model package standalone): `CacheableQueryBuilderTrait` guards
  `MultilanguageHelpers` with `class_exists`; `CachedBuilder` widens
  firstOrCreate/createOrFirst/updateOrCreate `$values` to `\Closure|array` (Laravel 12
  sig); `clearModelCache` guards `Cache::tags()` on non-taggable stores.
- **Test/scaffold fixes:** event-manager (2 test bugs), filament-theme (added boot smoke
  test), ai-tools (Dusk excluded).

## Resuming
Pick a FAIL package, run `scripts/standalone-test.sh <pkg>` to see the exact error,
apply the matching fix class above, re-run until PASS. The `--all` sweep regenerates
this matrix.
