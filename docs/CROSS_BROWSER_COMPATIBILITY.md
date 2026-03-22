# Cross-Browser Compatibility Documentation

## Overview

This document outlines the cross-browser compatibility testing infrastructure and support matrix for the Microweber application.

## Supported Browsers

The application is tested and supported on the following browsers:

| Browser | Minimum Version | Status | Notes |
|---------|----------------|--------|-------|
| **Chrome** | 100+ | ✅ Supported | Primary development browser |
| **Firefox** | 100+ | ✅ Supported | Full feature parity |
| **Edge** | 100+ | ✅ Supported | Chromium-based |
| **Safari** | 15+ | ✅ Supported | macOS/iOS only |

## Browser Testing Infrastructure

### Test Files

Located in `tests/Browser/CrossBrowser/`:

1. **CrossBrowserTestCase.php** - Base test class providing:
   - Multi-browser driver support (Chrome, Firefox, Edge)
   - Automatic browser detection
   - Browser-specific configuration
   - Helper methods for cross-browser testing

2. **CriticalPathCrossBrowserTest.php** - Critical path tests:
   - User authentication flows
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

3. **BrowserCapabilityTest.php** - Browser capability tests:
   - User agent detection
   - JavaScript ES6+ feature support
   - CSS feature support
   - HTML5 feature support
   - Web API availability
   - Viewport and screen properties
   - Performance API support
   - Event handling
   - Fetch API
   - Browser console functionality

### Running Cross-Browser Tests

```bash
# Run all cross-browser tests
php artisan dusk --filter="CrossBrowser"

# Run specific browser tests
php artisan dusk --filter="CriticalPathCrossBrowserTest"

# Run with specific browser (set environment variable)
DUSK_BROWSER=chrome php artisan dusk --filter="CrossBrowser"

# Run capability tests
php artisan dusk --filter="BrowserCapabilityTest"
```

### Browser Driver Requirements

#### Chrome
- **Driver**: ChromeDriver
- **Installation**: `npm install -g chromedriver` or download from [chromedriver.chromium.org](https://chromedriver.chromium.org/)
- **Command**: `chromedriver --port=9515`

#### Firefox
- **Driver**: GeckoDriver
- **Installation**: `npm install -g geckodriver` or download from [github.com/mozilla/geckodriver](https://github.com/mozilla/geckodriver/releases)
- **Command**: `geckodriver --port=4444`

#### Edge
- **Driver**: EdgeDriver (included with Edge or download from Microsoft)
- **Installation**: Included with Microsoft Edge or download from [developer.microsoft.com](https://developer.microsoft.com/en-us/microsoft-edge/tools/webdriver/)
- **Command**: `msedgedriver --port=9515`

#### Safari
- **Driver**: SafariDriver (built-in on macOS)
- **Enable**: Safari > Preferences > Advanced > Show Develop menu > Allow Remote Automation

## Browser-Specific Configuration

### Chrome Configuration

```php
'chrome' => [
    'window_size' => [1920, 1080],
    'headless' => true,
    'arguments' => [
        '--disable-web-security',
        '--disable-gpu',
        '--no-sandbox',
        '--ignore-certificate-errors',
        '--window-size=1920,1080',
        '--disable-popup-blocking',
        '--disable-dev-shm-usage',
        // ... additional args
    ],
],
```

### Firefox Configuration

```php
'firefox' => [
    'window_size' => [1920, 1080],
    'headless' => true,
    'preferences' => [
        'browser.download.folderList' => 2,
        'browser.download.manager.showWhenStarting' => false,
        'security.ssl.enable_ocsp_stapling' => false,
        // ... additional prefs
    ],
],
```

### Edge Configuration

```php
'edge' => [
    'window_size' => [1920, 1080],
    'headless' => true,
    'arguments' => [
        '--disable-web-security',
        '--disable-gpu',
        '--no-sandbox',
        // ... additional args
    ],
],
```

## Feature Support Matrix

### JavaScript Features

| Feature | Chrome | Firefox | Edge | Safari |
|---------|--------|---------|------|--------|
| ES6 Arrow Functions | ✅ | ✅ | ✅ | ✅ |
| Classes | ✅ | ✅ | ✅ | ✅ |
| Template Literals | ✅ | ✅ | ✅ | ✅ |
| Destructuring | ✅ | ✅ | ✅ | ✅ |
| Spread Operator | ✅ | ✅ | ✅ | ✅ |
| Promises | ✅ | ✅ | ✅ | ✅ |
| Async/Await | ✅ | ✅ | ✅ | ✅ |
| Fetch API | ✅ | ✅ | ✅ | ✅ |
| Modules | ✅ | ✅ | ✅ | ✅ |

### CSS Features

| Feature | Chrome | Firefox | Edge | Safari |
|---------|--------|---------|------|--------|
| CSS Grid | ✅ | ✅ | ✅ | ✅ |
| CSS Flexbox | ✅ | ✅ | ✅ | ✅ |
| CSS Variables | ✅ | ✅ | ✅ | ✅ |
| CSS Transforms | ✅ | ✅ | ✅ | ✅ |
| CSS Transitions | ✅ | ✅ | ✅ | ✅ |
| CSS Animations | ✅ | ✅ | ✅ | ✅ |
| Media Queries | ✅ | ✅ | ✅ | ✅ |
| Container Queries | ✅ | ✅ | ✅ | ✅ (15.4+) |

### HTML5 Features

| Feature | Chrome | Firefox | Edge | Safari |
|---------|--------|---------|------|--------|
| Canvas | ✅ | ✅ | ✅ | ✅ |
| Video | ✅ | ✅ | ✅ | ✅ |
| Audio | ✅ | ✅ | ✅ | ✅ |
| SVG | ✅ | ✅ | ✅ | ✅ |
| Web Storage | ✅ | ✅ | ✅ | ✅ |
| Geolocation | ✅ | ✅ | ✅ | ✅ |
| Drag & Drop | ✅ | ✅ | ✅ | ✅ |

### Web APIs

| API | Chrome | Firefox | Edge | Safari |
|-----|--------|---------|------|--------|
| Fetch API | ✅ | ✅ | ✅ | ✅ |
| XMLHttpRequest | ✅ | ✅ | ✅ | ✅ |
| WebSocket | ✅ | ✅ | ✅ | ✅ |
| localStorage | ✅ | ✅ | ✅ | ✅ |
| sessionStorage | ✅ | ✅ | ✅ | ✅ |
| File API | ✅ | ✅ | ✅ | ✅ |
| FormData | ✅ | ✅ | ✅ | ✅ |
| History API | ✅ | ✅ | ✅ | ✅ |
| IntersectionObserver | ✅ | ✅ | ✅ | ✅ |
| MutationObserver | ✅ | ✅ | ✅ | ✅ |
| ResizeObserver | ✅ | ✅ | ✅ | ✅ |
| Performance API | ✅ | ✅ | ✅ | ✅ |

## Known Browser-Specific Issues

### Safari
- **Download Handling**: Safari may block automatic downloads. Users may need to allow downloads.
- **Clipboard Access**: Requires user gesture (click) for clipboard API access.
- **File API**: Some advanced File API features may require permission.

### Firefox
- **PDF Viewer**: Uses built-in PDF viewer instead of download dialog.
- **Performance**: May be slightly slower than Chrome for complex operations.

### Edge
- **Compatibility Mode**: Some enterprise features may require compatibility mode settings.

### Internet Explorer
- **Not Supported**: IE is no longer supported. Users should upgrade to Edge.

## Testing Guidelines

### Automated Testing

1. **Critical Paths**: All critical user flows are tested across browsers
2. **Visual Regression**: Screenshots are captured for comparison
3. **Performance**: Page load times are monitored
4. **JavaScript Errors**: Console errors are checked and logged

### Manual Testing

When automated testing is insufficient, manual testing should cover:

1. **Complex Interactions**: Drag-and-drop, file uploads, etc.
2. **Mobile Browsers**: iOS Safari, Chrome Mobile, Firefox Mobile
3. **Print Functionality**: Print styles and page breaks
4. **Accessibility**: Screen readers and keyboard navigation

## CI/CD Integration

Cross-browser tests can be integrated into CI/CD pipelines:

```yaml
# .github/workflows/cross-browser.yml
name: Cross-Browser Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    strategy:
      matrix:
        browser: [chrome, firefox]
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install Dependencies
        run: composer install
      - name: Setup Browser Drivers
        run: |
          if [ "${{ matrix.browser }}" = "chrome" ]; then
            wget -q https://chromedriver.storage.googleapis.com/LATEST_RELEASE/chromedriver_linux64.zip
            unzip chromedriver_linux64.zip -d /usr/local/bin/
          else
            wget -q https://github.com/mozilla/geckodriver/releases/download/v0.33.0/geckodriver-v0.33.0-linux64.tar.gz
            tar -xzf geckodriver-v0.33.0-linux64.tar.gz -C /usr/local/bin/
          fi
      - name: Run Tests
        run: php artisan dusk --filter="CrossBrowser"
        env:
          DUSK_BROWSER: ${{ matrix.browser }}
```

## Reporting Issues

When reporting cross-browser issues, please include:

1. **Browser and Version**: e.g., Chrome 120.0.6099.129
2. **Operating System**: e.g., Windows 11, macOS 14.2
3. **Steps to Reproduce**: Clear steps to reproduce the issue
4. **Expected Behavior**: What should happen
5. **Actual Behavior**: What actually happens
6. **Screenshots**: If applicable
7. **Console Errors**: Any JavaScript errors from the browser console

## Maintenance

- **Browser Updates**: Test suite should be updated when new browser versions are released
- **Feature Detection**: Use feature detection over browser detection
- **Polyfills**: Consider polyfills for unsupported features
- **Progressive Enhancement**: Build for modern browsers, ensure functionality in older ones

## Resources

- [Can I Use](https://caniuse.com/) - Browser feature support tables
- [MDN Web Docs](https://developer.mozilla.org/) - Web technology documentation
- [WebDriver Protocol](https://w3c.github.io/webdriver/) - Standard for browser automation
- [Laravel Dusk Documentation](https://laravel.com/docs/dusk) - Dusk testing framework

---

*Last updated: March 22, 2026*
