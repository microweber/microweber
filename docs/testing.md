# Microweber Testing Guide

Microweber includes a comprehensive test suite covering core functionality, modules, and APIs.

## Test Environment Setup
1. Install testing dependencies:
```bash
composer require --dev phpunit/phpunit
```

2. Configure test environment:
```bash
cp .env.testing.example .env.testing
```

## Running Tests

### Basic Test Commands
```bash
# Run all tests
php artisan test

# Run tests with verbose output
php artisan test -v

# Stop on first failure
php artisan test --stop-on-failure
```

### Targeted Testing
```bash
# Run specific test class
php artisan test --filter ContactFormTest

# Run tests by group
php artisan test --group modules

# Run tests in parallel
php artisan test --parallel
```

### Code Coverage
```bash
# Generate HTML coverage report
php artisan test --coverage-html coverage/

# Generate clover XML report (for CI)
php artisan test --coverage-clover coverage.xml

# Minimum coverage enforcement
php artisan test --min-coverage 80
```

## Test Groups

### Core Test Groups
- `modules` - Module functionality tests
- `forms` - Form builder and validation
- `api` - REST API endpoints
- `ui` - Frontend interaction tests
- `integration` - System integration tests

### Writing Tests
Tests are located in `tests/` directory. Example test structure:

```php
class ExampleTest extends TestCase
{
    public function test_basic_example()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
```

## CI/CD Integration

### GitHub Actions Example (PHPUnit)
```yaml
name: Tests
on: [push, pull_request]
jobs:
  phpunit:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - run: composer install
      - run: npm install
      - run: php artisan test

  dusk:
    needs: phpunit
    runs-on: ubuntu-latest
    services:
      selenium:
        image: selenium/standalone-chrome
        ports:
          - 4444:4444
    steps:
      - uses: actions/checkout@v2
      - run: composer install
      - run: npm install && npm run build
      - run: php artisan dusk:chrome-driver
      - run: php artisan dusk
      env:
        APP_URL: "http://127.0.0.1:8000"
        DB_CONNECTION: sqlite
        DB_DATABASE: ":memory:"
```

### Dusk-Specific Setup
1. Install Dusk:
```bash
composer require --dev laravel/dusk
php artisan dusk:install
```

2. Configure GitHub Secrets:
- Add `APP_KEY` from your .env file as a repository secret
- Add any other sensitive environment variables

3. Chrome Driver Management:
```bash
# Update Chrome Driver version
php artisan dusk:chrome-driver --detect
```

### Note on Parallel Testing
We recommend against parallel Dusk execution because:
1. Browser tests require sequential execution
2. Shared state causes flaky results
3. Debugging parallel failures is difficult

For better results:
- Use `APP_ENV=dusk.local` for headless mode
- Optimize test speed individually
- Split large test suites
- Scale CI resources vertically
```



## Troubleshooting

### Common Issues
- Database connection errors: Ensure test database is configured
- Environment variables: Verify .env.testing is properly set up
- Timeouts: Increase timeout with `--process-timeout=60`

### Debugging Tests
```bash
# With Xdebug
php -d xdebug.mode=debug artisan test --filter FailingTest

# With dump statements
php artisan test --filter FailingTest --debug
```