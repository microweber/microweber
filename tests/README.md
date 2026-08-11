# Testing Guide

## Test Layout
- `tests/Unit` contains fast root-level unit tests.
- `tests/Feature` contains root-level application and integration tests.
- `tests/Browser` contains Laravel Dusk browser tests.
- `Modules/*/Tests` contains module-specific PHPUnit suites.
- `src/MicroweberPackages/*/tests` contains package-level PHPUnit suites.
- `Templates/*/Tests` contains template-specific tests.

## Source of Truth
- `phpunit.xml` defines the canonical PHPUnit suite layout.
- `./run-tests.sh` is the preferred broad-run entrypoint because it splits suites into separate PHP processes to avoid long-run memory fragmentation.
- Application, module, and template tests extend `Tests\TestCase`, which is backed by **Orchestra Testbench** (`MicroweberPackages\Core\tests\TestCase`). Standalone package suites under `packages/*` may use Testbench directly.

## Common Commands

```bash
# List the available grouped suites
./run-tests.sh --list

# Run the main grouped validation flow
./run-tests.sh

# Run only selected grouped suites
./run-tests.sh Unit Feature
./run-tests.sh --modules

# Run the default Laravel test command
composer test

# Run a specific PHPUnit suite directly
php vendor/bin/phpunit --testsuite=Unit --no-progress --display-errors
php vendor/bin/phpunit --testsuite=Feature --no-progress --display-errors

# Run a specific module or file
php vendor/bin/phpunit Modules/Newsletter/Tests --no-progress --display-errors
php vendor/bin/phpunit tests/Feature/Security/PenetrationTest.php --no-progress --display-errors
```

## Why `run-tests.sh` exists
- The repository is large enough that broad single-process runs can become unstable from PHP memory fragmentation.
- `run-tests.sh` executes the suite groups one at a time so failures remain attributable while peak memory stays manageable.
- Filament and Livewire-heavy tests may also use per-test separate-process execution inside PHPUnit where needed.

## Browser / Dusk Tests

### Setup

```bash
php artisan dusk:install --env=testing
```

### Run against your own local server

```bash
php artisan dusk --env=testing
```

### Run with Dusk's built-in server

```bash
php artisan dusk:serve --env=testing
```

## Notes
- Prefer targeted PHPUnit runs while iterating on a change, then use `./run-tests.sh` for broader regression coverage.
- In this environment, `/admin/login` currently returns an Apache `404`, so some browser checks can fail for local routing reasons unrelated to the code under test.

---

## Hard constraints (AI-110 / TICKET-BR + AI-122 / TICKET-BZ)

These are **non-negotiable** — adding test infrastructure that violates
any of them has historically broken CI:

1. **No `RunInSeparateProcess` attribute.** The test bootstrap is
   expensive (Filament panel boot, module discovery); spawning a
   fresh process per test scales O(n) and makes the suite multi-hour.
   Use `tests/Feature/*ContractTest.php`-style file-system reads when
   test isolation matters.

2. **No `DatabaseTransactions` / `RefreshDatabase` traits.** The seed
   model uses persistent fixtures (admin user + initial CMS settings).
   Wrapping tests in transactions fights the seeder; refreshing per
   test discards persistent fixtures and breaks the next test.

3. **No parallel runs.** Several test families share state across
   workers:
   - **Cart / Checkout** keys on `session_id`; parallel workers
     reuse session ids and tests cross-contaminate.
   - **Static helper counters** (e.g. cycle-105
     `responsive_thumbnail` request counter) are process-wide.
   - **Filament panel registry** is a singleton — duplicate
     registration errors across parallel workers.

   The brief (AI-110 / TICKET-BR) calls out enabling `--parallel`
   when a suite exceeds 100 tests. The CI pipeline emits a
   `::notice::` when the threshold is crossed; until the isolation
   issues above are fixed, `--parallel` stays off.

4. **Always kill the previous test run before starting a new one.**
   The suite holds DB locks + temp file locks; a stale runner can
   wedge subsequent ones.

5. **MySQL test DB**: `microweber_testing` is the canonical name.
   `run-tests.sh` creates / resets it between runs.

6. **Per-suite memory**: 512MB per phpunit process is sufficient.
   Splitting suites avoids the ~6MB-per-test leak from accumulating
   into an OOM around test #800.

---

## Contract tests (file-system reads only)

The `tests/Feature/*ContractTest.php` family is the bulk of new
tests shipped since cycle-52. They:

- **Never mount Filament resources** — the registry singleton
  detection above is the hard line.
- **Never hit MySQL** — they read source files via
  `file_get_contents` and assert on string / regex shape.
- **Run in <1s each** — the entire family completes in ~2s.
- **Work offline** — no network, no DB, no `composer install`
  required beyond `vendor/`.

When adding a new feature that needs a regression-proof "this code
shape exists" pin, prefer a contract test over a Feature test. See
`tests/Feature/Ai107DbIndexesContractTest.php` for a recent example.
