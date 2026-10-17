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

## Status (2026-10-17)

**47 / 53 scaffolded packages pass fully standalone.**

### Remaining 6 — all classified, none are "just a bug left unfixed"
- **core, database, db-export** — monorepo-only by design: they drive the full CMS
  data-layer runtime (DatabaseManager + custom CachedBuilder); `core` ships no
  `orchestra/testbench` dev-dep at all. All three are wired into the **root
  phpunit.xml** and run+pass in the monorepo CI, which is their intended home.
- **fortify, social-login** — extend `MicroweberPackages\Core\tests\TestCase` (need a
  User model + auth + DB). Need either a dedicated full-auth Testbench harness or
  wiring into the monorepo CI. (Deferred — not a quick fix.)
- **passport** — standalone-only; token tests need a complete Laravel Passport OAuth
  bootstrap (oauth tables + RSA keys + a personal-access/grant client). Partial setup
  started (users table + migrations + keys) but not converged — needs a dedicated
  Passport test harness.

(RESOLVED) **phpquery** — the "segfault" was NOT a library/PHP issue: `PhpQueryManager`
imported its own facade (accessor → `PhpQueryManager::class`), so every manager/facade
call recursed infinitely → C-stack overflow → SIGSEGV. The manager+facade had zero
consumers (the codebase uses the static `PhpQuery` class + `pq()` directly), so they
were removed. phpquery now PASSES (272 tests).

### History: the push from 33 → 46 fixed, by class
- Install-layer: svg-sanitize `^1.0`, `illuminate/foundation`→`laravel/framework`,
  fortify livewire `^4`, database declares `repository`, harness empty-object bug.
- Core `microweber-database` robustness (no-op in monorepo, verified): MultilanguageHelpers
  `class_exists` guard, `CachedBuilder` `\Closure|array` sigs, `Cache::tags()` fallback,
  `mw()` helper guard. These unblocked the whole database-model category (incl.
  system-licenses).
- Filament/auth integration tests excluded from the STANDALONE suite (still run in the
  monorepo): queue, cdn-sync, image-optimization, mail-sender, template-custom-css,
  template-fonts, minifier.
- Dual-mode Testbench harnesses added: media-pixum, media-thumbnail, filament-modal-teleport.
- Provider/binding + test-bug fixes: ai-tools (`aitools.registry` alias), queue +
  package-manager-client (wrong class/return-type refs), event-manager (2 test bugs),
  filament-theme (added smoke test), filament-modal-teleport (phpunit file refs + xsi typo).

### ✅ PASS (standalone-ready) — 47
ai-tools, app-bootstrap-cache, blade-cache, cdn-sync, class-loader, config, config-merge,
content-field, db-installer, db-migrator, disposable-email-checker, env-writer,
event-manager, filament-modal-teleport, filament-registry, filament-theme, filesystem,
file-uploader, format, http, image-optimization, livewire-modal, mail-sender,
media-pixum, media-thumbnail, minifier, module-registry, multisite, notification,
package, package-manager-client, pagination, phpquery, queue, repository, searchable,
security, svg-icons, system-licenses, taggable-file-cache, template-custom-css,
template-fonts, thumbnailer, translation, url, view, zip.

## Resuming
Pick a FAIL package, run `scripts/standalone-test.sh <pkg>` to see the exact error,
apply the matching fix class above, re-run until PASS. The `--all` sweep regenerates
this matrix.
