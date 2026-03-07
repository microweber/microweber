# Module-Level Pest Testing Guide

This guide explains how to set up and use Pest testing framework for Microweber modules.

## Overview

Microweber now supports Pest alongside PHPUnit for module-level testing. Each module can have its own Pest test suite with `tests/Unit` and `tests/Feature` directories.

## Installation

Pest is included in the project's `composer.json`. To install:

```bash
composer install --dev
```

Or if already installed:

```bash
composer require pestphp/pest pestphp/pest-plugin-laravel --dev
```

## Directory Structure

Each module should follow this structure:

```
Modules/
└── YourModule/
    ├── Tests/
    │   ├── Pest.php          # Module-level Pest configuration
    │   ├── Unit/             # Unit tests
    │   │   └── ExampleTest.php
    │   └── Feature/          # Feature tests
    │       └── ExampleTest.php
    └── ...
```

## Setting Up a New Module

### 1. Create Test Directories

```bash
mkdir -p Modules/YourModule/Tests/Unit
mkdir -p Modules/YourModule/Tests/Feature
```

### 2. Create Module Pest.php

Create `Modules/YourModule/Tests/Pest.php`:

```php
<?php

use Tests\TestCase;

uses(TestCase::class)->in(__DIR__);
```

### 3. Create Your First Pest Test

Create `Modules/YourModule/Tests/Unit/ExampleTest.php`:

```php
<?php

test('basic assertion', function () {
    expect(true)->toBeTrue();
});
```

## Running Tests

### Run all tests
```bash
./vendor/bin/pest
```

### Run module-specific tests
```bash
./vendor/bin/pest Modules/YourModule/Tests
```

### Run specific test suite
```bash
./vendor/bin/pest --testsuite=Unit
./vendor/bin/pest --testsuite=Feature
```

### Run with coverage
```bash
./vendor/bin/pest --coverage
```

## Writing Pest Tests

### Basic Test

```php
<?php

test('user can be created', function () {
    $user = User::factory()->create();

    expect($user)->toBeInstanceOf(User::class);
});
```

### Using Datasets

```php
<?php

test('can validate email', function ($email) {
    expect(filter_var($email, FILTER_VALIDATE_EMAIL))->not->toBeFalse();
})->with([
    'valid@example.com',
    'test@domain.org',
]);
```

### Using beforeEach

```php
<?php

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('authenticated user can access dashboard', function () {
    $response = $this->actingAs($this->user)->get('/admin');

    $response->assertOk();
});
```

## Migration from PHPUnit

Existing PHPUnit tests continue to work. You can gradually migrate:

1. Keep existing `*Test.php` files (they work with both PHPUnit and Pest)
2. Create new `.php` files (without "Test" suffix) for Pest-specific tests
3. Use Pest's `todo()` function for pending tests:

```php
todo('write test for new feature');
```

## Configuration

### Root Configuration

- `Pest.php` - Root Pest configuration
- `pest.xml` - Pest XML configuration (similar to phpunit.xml)

### Module Configuration

Each module can have its own `Pest.php` file in the Tests directory for module-specific configuration.

## Best Practices

1. **Keep tests organized**: Use `Unit/` for isolated tests, `Feature/` for integration tests
2. **Use descriptive names**: `test('user can create a post with valid data')`
3. **Leverage datasets**: For testing multiple scenarios
4. **Use type hints**: Pest supports PHP 8.2+ features
5. **Mock external services**: Use Laravel's mocking facilities

## Troubleshooting

### Tests not discovered

Ensure your module has a `Pest.php` file in the Tests directory, or tests use the `.php` extension (not `.pest.php`).

### Namespace issues

Make sure your module's composer.json has proper PSR-4 autoloading:

```json
"autoload": {
    "psr-4": {
        "Modules\\YourModule\\": ""
    }
}
```

### Database issues

Use `RefreshDatabase` trait when needed:

```php
<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->in('Feature');
```
