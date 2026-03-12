# Module-Level Pest Testing Guide

This guide covers the Pest testing workflow for Microweber, including writing tests, running them locally, and how they integrate with CI.

## Prerequisites

Pest is included in `composer.json` (`require-dev`). Install with:

```bash
composer install --dev
```

Required packages:
- `pestphp/pest` ^3.0
- `pestphp/pest-plugin-laravel` ^3.0

## Project Test Architecture

Microweber uses **two configuration files** that serve different purposes:

| Config | Runner | What it discovers |
|---|---|---|
| `phpunit.xml` | `vendor/bin/pest` | `tests/Unit`, `src/MicroweberPackages/*/`, `Modules/*/Tests`, `Templates/*/Tests` |
| `pest.xml` | `vendor/bin/pest` | `tests/Unit`, `tests/Feature`, `Modules/*/Tests/Unit`, `Modules/*/Tests/Feature`, `src/MicroweberPackages/*/tests/Unit`, `src/MicroweberPackages/*/tests/Feature` |

Both PHPUnit class-based tests and Pest closure-based tests are discovered by Pest. You can mix both styles in the same test suite.

### Key configuration files

| File | Purpose |
|---|---|
| `Pest.php` (root) | Root Pest config: binds `TestCase`, discovers modules/packages, defines global helpers |
| `pest.xml` | XML config for Pest-specific suites (Unit/Feature/Modules/Packages) |
| `phpunit.xml` | PHPUnit config (also used by Pest for full suite runs) |
| `Modules/*/Tests/Pest.php` | Per-module Pest config for module-specific setup/teardown |

## Directory Structure

```
Modules/
  YourModule/
    Tests/
      Pest.php              # Module-level Pest config
      Unit/
        YourModelTest.php    # Unit tests (PHPUnit or Pest style)
      Feature/
        YourFeatureTest.php  # Feature/integration tests
    Filament/                # (if module has Filament resources)
      ...

tests/
  Pest.php                   # Root Pest config (auto-loaded)
  Unit/
    ExamplePestTest.php
  Feature/
    Filament/
      FilamentResourceTestCase.php
      Concerns/
        InteractsWithFilamentPanel.php
```

## Running Tests

### Common commands

```bash
# Run ALL tests (PHPUnit + Pest, full suite)
vendor/bin/pest

# Run only Unit + Feature suites (pest.xml)
vendor/bin/pest --configuration pest.xml

# Run a single module's tests
vendor/bin/pest Modules/Backup/Tests

# Run a single test file
vendor/bin/pest Modules/Backup/Tests/Unit/Filament/BackupResourceTest.php

# Run tests matching a name pattern
vendor/bin/pest --filter="backup"

# Run a specific test suite from pest.xml
vendor/bin/pest --configuration pest.xml --testsuite=Unit
vendor/bin/pest --configuration pest.xml --testsuite=Modules

# Run with coverage
vendor/bin/pest --coverage

# Run in parallel (faster on multi-core machines)
vendor/bin/pest --parallel
```

### Composer scripts

```bash
# Full test suite via PHPUnit config
composer test

# Pest-specific suites (Unit + Feature from pest.xml)
composer test-pest
```

## Writing Tests

### Pest closure style (recommended for new tests)

```php
<?php

// Modules/Billing/Tests/Unit/SubscriptionTest.php

use Modules\Billing\Models\Subscription;

test('subscription can be created with factory', function () {
    $subscription = Subscription::factory()->make();

    expect($subscription)->toBeInstanceOf(Subscription::class)
        ->and($subscription->status)->toBeIn(['active', 'inactive', 'cancelled']);
});

test('can validate subscription data', function (string $status) {
    expect(in_array($status, ['active', 'inactive', 'cancelled', 'trialing']))->toBeTrue();
})->with([
    'active',
    'inactive',
    'cancelled',
    'trialing',
]);
```

### PHPUnit class style (existing tests)

Existing PHPUnit tests work as-is. The project convention is:

```php
<?php

namespace Modules\Backup\Tests\Unit\Filament;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BackupResourceTest extends TestCase
{
    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        // ...
    }
}
```

Conventions for PHPUnit-style tests:
- Use `#[Test]` attribute (not `@test` docblock or `test_` prefix)
- Name methods `it_describes_behavior(): void`
- Extend `Tests\TestCase`

### Lifecycle hooks (Pest style)

```php
<?php

beforeEach(function () {
    $this->user = \MicroweberPackages\User\Models\User::factory()->create(['is_admin' => 1]);
});

afterEach(function () {
    // cleanup
});

test('admin can access dashboard', function () {
    $this->actingAs($this->user)->get('/admin')->assertOk();
});
```

## Testing Filament Resources

Filament resource tests require the admin panel to be set up. Use the `InteractsWithFilamentPanel` trait.

### Using the trait directly

```php
<?php

namespace Modules\YourModule\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\YourModule\Filament\Resources\YourResource;
use Modules\YourModule\Filament\Resources\YourResource\Pages\ListRecords;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class YourResourceTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_index_page_loads(): void
    {
        Livewire::test(ListRecords::class)->assertSuccessful();
    }

    #[Test]
    public function it_table_has_columns(): void
    {
        Livewire::test(ListRecords::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('created_at');
    }
}
```

### Using FilamentResourceTestCase (abstract base)

For standard CRUD resource tests, extend `FilamentResourceTestCase`:

```php
<?php

namespace Modules\YourModule\Tests\Unit\Filament;

use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class YourResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return \Modules\YourModule\Filament\Resources\YourResource::class;
    }

    #[Test]
    public function it_can_list_records(): void
    {
        $this->assertCanAccessIndex();
    }
}
```

`FilamentResourceTestCase` provides:
- `assertCanAccessIndex()` - verify the list page renders
- `assertGuestCannotAccess($route)` - verify auth is required
- `createModel($attributes)` - create a model via the resource's factory
- `actingAsAdmin()` / `actingAsUser()` - authenticate with proper panel setup

### InteractsWithFilamentPanel helpers

| Method | Purpose |
|---|---|
| `setUpFilamentPanel($panelId)` | Authenticate admin + set current panel |
| `actingAsAdmin($attributes)` | Create admin user and authenticate |
| `actingAsUser($attributes)` | Create non-admin user and authenticate |
| `getFilamentResourceUrl($class, $action, $params)` | Generate resource URL |

## Setting Up a New Module's Tests

### 1. Create directories

```bash
mkdir -p Modules/YourModule/Tests/Unit
mkdir -p Modules/YourModule/Tests/Feature
```

### 2. Create module Pest.php

Create `Modules/YourModule/Tests/Pest.php`:

```php
<?php

use Tests\TestCase;

uses(Tests\TestCase::class)->in(__DIR__);

// Uncomment for Feature tests that need database resets
// use Illuminate\Foundation\Testing\RefreshDatabase;
// uses(RefreshDatabase::class)->in('Feature');

beforeEach(function () {
    // Module-specific setup
})->in(__DIR__);

afterEach(function () {
    // Module-specific cleanup
})->in(__DIR__);
```

### 3. Write your first test

Create `Modules/YourModule/Tests/Unit/ExampleTest.php`:

```php
<?php

test('module is registered', function () {
    expect(app()->bound('modules.yourmodule'))->toBeTrue();
});
```

### 4. Verify

```bash
vendor/bin/pest Modules/YourModule/Tests
```

## CI Integration

Tests run automatically in GitHub Actions via two workflows:

### ci.yml (primary)

```yaml
- name: Run Tests (Pest + PHPUnit)
  run: vendor/bin/pest --configuration phpunit.xml

- name: Run Pest Module Suites
  run: vendor/bin/pest --configuration pest.xml --testsuite=Unit,Feature
```

### matrix-tests.yml (multi-version)

Runs the same test steps across PHP 8.3/8.4 + Laravel 11, with coverage reporting on the `pest.xml` suites.

## Migration from PHPUnit to Pest

Existing PHPUnit tests run unchanged under Pest. To gradually migrate:

1. **New tests**: Write in Pest closure style
2. **Existing tests**: Leave as PHPUnit classes (they work with both runners)
3. **Incremental conversion**: When modifying an existing test file, optionally convert it

To convert a PHPUnit test class to Pest:

```php
// Before (PHPUnit)
class UserTest extends TestCase
{
    #[Test]
    public function it_creates_user(): void
    {
        $user = User::factory()->create();
        $this->assertNotNull($user->id);
    }
}

// After (Pest)
test('it creates user', function () {
    $user = User::factory()->create();
    expect($user->id)->not->toBeNull();
});
```

Key differences:
- No class wrapper needed
- `$this` still refers to the `TestCase` instance inside closures
- Use `expect()` for fluent assertions (or `$this->assert*()` still works)
- Use `todo('description')` for placeholder tests

## Pest Expectations Quick Reference

```php
expect($value)->toBe(4);                    // strict equality
expect($value)->toEqual('foo');             // loose equality
expect($value)->toBeTrue();
expect($value)->toBeFalse();
expect($value)->toBeNull();
expect($value)->not->toBeNull();
expect($value)->toBeInstanceOf(User::class);
expect($value)->toContain('substring');
expect($value)->toBeIn(['a', 'b', 'c']);
expect($value)->toBeGreaterThan(0);
expect($value)->toHaveCount(3);
expect($value)->toHaveKey('name');
expect($value)->toMatchArray(['key' => 'val']);
expect(fn() => riskyCall())->toThrow(Exception::class);
```

## Troubleshooting

### Tests not discovered

- Ensure the module has `Modules/YourModule/Tests/Pest.php`
- Check that test files end with `Test.php` or are plain `.php` files in the Tests directory
- Verify the directory casing matches (`Tests/Unit` vs `tests/Unit`) - both are supported

### Route not found in Filament tests

Use `InteractsWithFilamentPanel` trait and call `$this->setUpFilamentPanel()` in `setUp()`. This ensures the admin panel is registered as the current panel and all module routes are available.

### Database state issues

Use `RefreshDatabase` trait for tests that modify the database:

```php
// In module Pest.php
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class)->in('Feature');

// Or in a PHPUnit class
use Illuminate\Foundation\Testing\RefreshDatabase;

class YourTest extends TestCase
{
    use RefreshDatabase;
}
```

### Memory issues on large test runs

The `pest.xml` and `phpunit.xml` both set `memory_limit=-1`. If you still hit limits:

```bash
php -d memory_limit=2G vendor/bin/pest Modules/YourModule/Tests
```
