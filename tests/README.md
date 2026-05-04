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
