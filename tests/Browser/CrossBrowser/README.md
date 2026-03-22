# Cross-Browser Testing Suite

## Overview

This directory contains cross-browser compatibility tests for the Microweber application using Laravel Dusk.

## Structure

```
tests/Browser/CrossBrowser/
├── CrossBrowserTestCase.php          # Base class for cross-browser tests
├── CriticalPathCrossBrowserTest.php  # Critical path tests across browsers
├── BrowserCapabilityTest.php         # Browser capability detection tests
└── README.md                         # This file
```

## Test Files

### 1. CrossBrowserTestCase.php
Base test class that provides:
- Multi-browser driver support (Chrome, Firefox, Edge)
- Browser detection and configuration
- Driver creation for different browsers
- Helper methods for cross-browser testing

### 2. CriticalPathCrossBrowserTest.php
Tests critical user flows across all supported browsers:
- User authentication (login/logout)
- Homepage loading
- Form validation
- Responsive design
- Navigation functionality
- Cookie handling
- Asset loading
- Modal/dialog functionality
- Keyboard navigation
- localStorage support
- CSS Grid/Flexbox support
- Page scrolling
- Image loading
- Link functionality
- Console error detection

### 3. BrowserCapabilityTest.php
Tests browser capabilities and feature support:
- User agent detection
- JavaScript ES6+ features
- CSS features (Grid, Flexbox, Variables)
- HTML5 features (Canvas, Video, Audio, SVG)
- Web API availability
- Viewport and screen properties
- Performance API
- Event handling
- Fetch API
- Console functionality

## Running Tests

### Prerequisites

1. Install browser drivers:
   ```bash
   # ChromeDriver
   npm install -g chromedriver

   # GeckoDriver (Firefox)
   npm install -g geckodriver

   # EdgeDriver (usually included with Edge)
   ```

2. Start browser driver:
   ```bash
   # Chrome
   chromedriver --port=9515

   # Firefox
   geckodriver --port=4444
   ```

3. Start Laravel server:
   ```bash
   php artisan serve
   ```

### Run Tests

```bash
# Run all cross-browser tests
php artisan dusk --filter="CrossBrowser"

# Run critical path tests
php artisan dusk --filter="CriticalPathCrossBrowserTest"

# Run capability tests
php artisan dusk --filter="BrowserCapabilityTest"

# Run specific test
php artisan dusk --filter="cross_browser_user_can_login"
```

### Test Groups

Tests are organized into groups for selective running:

```bash
# Run all cross-browser tests
php artisan dusk --group="cross-browser"

# Run only capability tests
php artisan dusk --group="capabilities"

# Run only critical path tests
php artisan dusk --group="critical-path"

# Run responsive tests
php artisan dusk --group="responsive"

# Run accessibility tests
php artisan dusk --group="accessibility"

# Run CSS tests
php artisan dusk --group="css"
```

## Configuration

### Environment Variables

Add to your `.env.dusk.local` or test environment:

```env
# Browser selection (chrome, firefox, edge)
DUSK_BROWSER=chrome

# Driver URL (optional)
DUSK_DRIVER_URL=http://localhost:9515

# Disable headless mode
DUSK_HEADLESS_DISABLED=true
```

### Supported Browsers

| Browser | Driver | Port | Status |
|---------|--------|------|--------|
| Chrome | ChromeDriver | 9515 | ✅ Available |
| Firefox | GeckoDriver | 4444 | ✅ Available |
| Edge | EdgeDriver | 9515 | ✅ Available |
| Safari | SafariDriver | N/A | macOS only |

## Test Coverage

### Authentication Tests
- ✅ User login
- ✅ Form validation
- ✅ Session management
- ✅ Cookie handling

### UI Tests
- ✅ Homepage loading
- ✅ Responsive design
- ✅ Navigation
- ✅ Modal/dialogs
- ✅ Page scrolling
- ✅ Image loading

### JavaScript Tests
- ✅ ES6+ features
- ✅ Event handling
- ✅ localStorage
- ✅ Fetch API
- ✅ Console functionality

### CSS Tests
- ✅ Grid support
- ✅ Flexbox support
- ✅ CSS variables
- ✅ Transforms

### Accessibility Tests
- ✅ Keyboard navigation
- ✅ ARIA attributes
- ✅ Focus management

## Continuous Integration

### GitHub Actions Example

```yaml
name: Cross-Browser Tests

on: [push, pull_request]

jobs:
  test-chrome:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install Dependencies
        run: composer install
      - name: Download ChromeDriver
        run: |
          wget https://chromedriver.storage.googleapis.com/LATEST_RELEASE/chromedriver_linux64.zip
          unzip chromedriver_linux64.zip -d /usr/local/bin/
      - name: Run Tests
        run: php artisan dusk --filter="CrossBrowser"
```

## Troubleshooting

### Chrome Issues

**Error: ChromeDriver executable not found**
```bash
# Install ChromeDriver globally
npm install -g chromedriver

# Or download manually
wget https://chromedriver.storage.googleapis.com/LATEST_RELEASE/chromedriver_linux64.zip
unzip chromedriver_linux64.zip -d /usr/local/bin/
chmod +x /usr/local/bin/chromedriver
```

**Error: Session not created**
- Ensure Chrome and ChromeDriver versions match
- Update Chrome to latest version
- Download matching ChromeDriver version

### Firefox Issues

**Error: GeckoDriver executable not found**
```bash
# Install GeckoDriver
npm install -g geckodriver

# Or download manually
wget https://github.com/mozilla/geckodriver/releases/download/v0.33.0/geckodriver-v0.33.0-linux64.tar.gz
tar -xzf geckodriver-v0.33.0-linux64.tar.gz -d /usr/local/bin/
chmod +x /usr/local/bin/geckodriver
```

### Edge Issues

**Error: EdgeDriver not found**
- EdgeDriver is usually included with Microsoft Edge
- Check if `msedgedriver` is in PATH
- Download from: https://developer.microsoft.com/en-us/microsoft-edge/tools/webdriver/

## Results

Test results are generated in:
- `tests/Browser/screenshots/` - Screenshots from failed tests
- `tests/Browser/console/` - Browser console logs
- Console output - Test results and assertions

## Maintenance

### Adding New Tests

1. Extend `CrossBrowserTestCase` instead of `DuskTestCase`
2. Use `#[Group('cross-browser')]` attribute
3. Include browser capability checks when needed
4. Add test to appropriate section in documentation

### Updating Browser Versions

When updating supported browser versions:

1. Update minimum version in `docs/CROSS_BROWSER_COMPATIBILITY.md`
2. Update driver versions in CI configuration
3. Run full test suite
4. Update feature support matrix

## References

- [Laravel Dusk Documentation](https://laravel.com/docs/dusk)
- [WebDriver Protocol](https://w3c.github.io/webdriver/)
- [ChromeDriver Documentation](https://chromedriver.chromium.org/)
- [GeckoDriver Documentation](https://github.com/mozilla/geckodriver)
- [MDN Browser Compatibility](https://developer.mozilla.org/en-US/docs/Web/Compatibility)

## License

These tests are part of the Microweber application and follow the same license terms.
