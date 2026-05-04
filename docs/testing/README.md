# Microweber Testing Setup

This directory contains testing documentation and helper scripts for Microweber.

## What's Included

- **Canonical Root Configuration**
- `/phpunit.xml` - Source of truth for Unit, Feature, Core, module-group, and template suites
- `/run-tests.sh` - Memory-safe grouped runner for broad local validation

- **Optional/Legacy Helper Scripts**
- `module-pest-template.php` - Template if the team reintroduces module-level Pest config later
- `setup-module-pest.php` - Helper for generating module-level Pest files
- `generate-module-pest-files.php` - Batch generator for module-level Pest files

**Documentation**
- `module-testing-guide.md` - Complete guide for writing module-level Pest tests

## Quick Start

### Installation

```bash
composer install --dev
```

### Running Tests

```bash
# Run all tests
composer test

# Run the grouped suites without hitting long-run memory issues
./run-tests.sh

# Run a specific grouped suite
./run-tests.sh Unit
./run-tests.sh Feature
./run-tests.sh Modules-Content

# Run a specific module test path directly
php vendor/bin/phpunit Modules/Billing/Tests --no-progress --display-errors
```

## About the Pest helper files

The helper scripts in this folder were added for a future/partial Pest migration, but the root repository does **not** currently ship `/Pest.php`, `/pest.xml`, or a `composer test-pest` script. Until that migration is completed, treat `phpunit.xml` and `run-tests.sh` as the authoritative entrypoints.

## Writing Tests

See `module-testing-guide.md` for detailed examples and best practices.

### Basic test

```php
<?php

#[Test]
public function it_creates_a_post(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/posts', [
        'title' => 'Test Post',
    ]);

    $response->assertRedirect();
}
```

## Next Steps

1. Read the full guide: `module-testing-guide.md`
2. Try running the grouped suites: `./run-tests.sh --list`
3. Use `phpunit.xml` suite names when scoping a local test run
4. Refer to the example tests for patterns and best practices
