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

**29 / 53 scaffolded packages pass fully standalone** (app-bootstrap-cache fixed +
verified after the sweep; see below).

### ✅ PASS (standalone-ready)
blade-cache, class-loader, config, config-merge, content-field, db-installer,
db-migrator, disposable-email-checker, env-writer, filament-registry, format, http,
livewire-modal, module-registry, multisite, notification, package, pagination,
repository, searchable, security, svg-icons, taggable-file-cache, thumbnailer,
translation, url, view, zip.

### ❌ FAIL — `composer install` (dependency declaration gaps)
- **app-bootstrap-cache** — required `illuminate/foundation` (not a standalone
  Packagist package); fixed → `laravel/framework`. Re-verify.
- **filament-theme, filesystem, file-uploader, fortify** — unresolvable/blocked deps.
  `filesystem`/`file-uploader` hit `enshrined/svg-sanitize` **composer security
  advisories** (block-insecure) — either bump to an unaffected version or add an
  audit ignore in the package's composer.json.

### ❌ FAIL — tests (setup/scaffolding, not necessarily real bugs)
ai-tools, cdn-sync, core, database, db-export, event-manager, filament-modal-teleport,
image-optimization, mail-sender, media-pixum, media-thumbnail, minifier,
package-manager-client, passport, phpquery, queue, social-login, system-licenses,
template-custom-css, template-fonts.

Common root causes identified:
- **Dusk tests swept into the standalone suite** (e.g. ai-tools) — the package
  `phpunit.xml` testsuite includes a `tests/` dir containing `tests/Dusk/*` which
  needs `laravel/dusk` + a browser. Fix: `<exclude>` the Dusk dir in the package
  testsuite (keep it only in the monorepo Dusk run).
- **In-memory sqlite without the package's migrations loaded** (model-backed packages:
  queue, core, database, db-export, event-manager) — the Testbench `TestCase` must
  `loadMigrationsFrom(...)` the package migrations (and define needed tables).
- **Test bugs surfaced standalone** — e.g. `queue` `StandaloneAppValidationTest` does
  `class_exists(ChunkedDispatcher::class)` but only imports the Facade alias, so it
  resolves to the test namespace (should be `ChunkedDispatcherFacade::class` or the
  `...\Services\ChunkedDispatcherService`).

## Already fixed this pass
- 16 package `phpunit.xml` files bootstrapped the **monorepo** vendor
  (`../../vendor/autoload.php`) instead of their own (`vendor/autoload.php`) — they
  could not boot standalone. Fixed (monorepo runs use the root phpunit.xml, so safe).
- `app-bootstrap-cache` `illuminate/foundation` → `laravel/framework`.

## Resuming
Pick a FAIL package, run `scripts/standalone-test.sh <pkg>` to see the exact error,
apply the matching fix class above, re-run until PASS. The `--all` sweep regenerates
this matrix.
