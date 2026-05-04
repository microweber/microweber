# Project Summary

## Architecture
- Microweber is a Laravel 11 modular monolith bootstrapped through `bootstrap/app.php`, which uses `MicroweberPackages\App\LaravelApplication` instead of the stock `Application`.
- Core framework code lives under `src/MicroweberPackages/`, while product features live under `Modules/` and frontend/theme assets live under `packages/`.
- The largest domain models currently include `Modules/Content/Models/Content.php` and `src/MicroweberPackages/User/Models/User.php`.

## Naming & Conventions
- PHP tests use PHPUnit 11 with `#[Test]` attributes; existing class-style tests prefer descriptive `it_*` method names.
- The repository’s broad PHP suites are defined in `phpunit.xml`, and the memory-safe full-suite runner is `./run-tests.sh`.
- Conventional Commits are already documented in `CONTRIBUTING.md` and used for task-by-task commits.

## Key Files
- `bootstrap/app.php` — Laravel bootstrap, route wiring, and rate limiters.
- `phpunit.xml` — canonical PHPUnit suite layout for Unit, Feature, Core, module groups, and Templates.
- `run-tests.sh` — split-process runner used to avoid PHP memory fragmentation during large suite runs.
- `package.json` — root frontend/docs/security npm scripts.
- `.github/workflows/cicd-pipeline.yml` — CI stages for quality, security, and automated tests.

## Build & Run
- Install PHP dependencies with `composer install`.
- Install frontend dependencies with `npm install`.
- Build frontend assets with `npm run build` from the repo root.
- Run the main PHP suite with `composer test` or targeted grouped suites with `./run-tests.sh`.

## Gotchas & Known Issues
- The local Apache-served runtime in this environment returns `404` for `/admin/login` even though `http://127.0.0.1` responds with `200`, so browser verification can fail for environment reasons rather than app regressions.
- The full PHP test surface is intentionally split by `run-tests.sh` because long single-process runs hit PHP memory fragmentation/OOM issues.
- Testing docs currently mention `Pest.php`, `pest.xml`, and `composer test-pest`, but those root entrypoints do not currently exist in the repo.

## Decisions
- Cache deserialization in `TaggableFileStore` is hardened with `unserialize(..., ['allowed_classes' => false])` to avoid object-injection risk from poisoned cache files.
- Treat `phpunit.xml` + `run-tests.sh` as the current authoritative test entrypoints until the Pest-specific root config is restored or the docs are corrected.

## Dependencies (non-obvious)
- `nwidart/laravel-modules` powers the feature-module layout under `Modules/`.
- Filament 5 + Livewire 4 drive the admin UI and many live-edit flows.
- Root frontend asset builds are orchestrated through `run-build.js` and package-level build scripts.

## Credentials
- None recorded in this repository summary.
