# Legacy Browser Tests (DEPRECATED)

This directory contains the original comprehensive browser automation tests using Laravel Dusk.

## Status: LEGACY

These tests have been moved from `tests/Browser/` to `tests/BrowserLegacy/` as they are complex and time-consuming. 

For current testing, use the simple smoke tests in `tests/Browser/SmokeTest.php` which verify pages load without JavaScript errors.

## Overview

These legacy tests simulate real user interactions in a browser to ensure critical paths work correctly:
- **Authentication flows**: Login, logout, registration, password reset
- **Content management**: Creating pages, posts, categories
- **E-commerce flows**: Product browsing, cart operations, checkout
- **Form submissions**: Contact forms, newsletter subscriptions
- **Multi-language support**: Testing translations
- **Live edit functionality**: Frontend editing features
- **Cross-browser testing**: Multiple browser support

## Overview

These tests simulate real user interactions in a browser to ensure critical paths work correctly:
- **Authentication flows**: Login, logout, registration, password reset
- **Content management**: Creating pages, posts, categories
- **E-commerce flows**: Product browsing, cart operations, checkout
- **Form submissions**: Contact forms, newsletter subscriptions

## Test Structure

```
tests/Browser/
├── Auth/
│   └── UserAuthenticationFlowsTest.php    # User auth flows
├── Content/
│   └── ContentManagementFlowsTest.php     # Content CRUD operations
├── Shop/
│   └── ECommerceCriticalFlowsTest.php     # E-commerce flows
├── Forms/
│   └── FormSubmissionFlowsTest.php        # Form submissions
├── Components/
│   ├── AdminLogin.php                     # Reusable admin login component
│   ├── ChekForJavascriptErrors.php        # JS error checking
│   └── ...                                # Other components
├── CriticalFlowsTest.php                  # Legacy critical flows
└── README.md                              # This file
```

## Running Tests

### Prerequisites

1. Install Dusk and ChromeDriver:
```bash
php artisan dusk:install
```

2. Start the development server:
```bash
php artisan serve
```

### Run All Browser Tests

```bash
php artisan dusk
```

### Run Specific Test Files

```bash
# Authentication flows
php artisan dusk --filter=UserAuthenticationFlowsTest

# Content management
php artisan dusk --filter=ContentManagementFlowsTest

# E-commerce
php artisan dusk --filter=ECommerceCriticalFlowsTest

# Form submissions
php artisan dusk --filter=FormSubmissionFlowsTest
```

### Run Tests with Screenshots on Failure

Tests automatically capture screenshots on failure in `tests/Browser/screenshots/`

### Run in Headless Mode

By default, tests run headlessly. To see the browser:

```bash
DUSK_HEADLESS_DISABLED=true php artisan dusk
```

## Test Categories

### 1. User Authentication Flows (`UserAuthenticationFlowsTest`)

Tests critical user authentication paths:
- `it_user_can_register_and_login()` - Complete registration and login flow
- `it_user_login_shows_error_for_invalid_credentials()` - Login error handling
- `it_user_can_update_profile()` - Profile editing
- `it_user_can_logout()` - Logout functionality
- `it_user_can_reset_password()` - Password reset flow

### 2. Content Management Flows (`ContentManagementFlowsTest`)

Tests content creation and management:
- `it_admin_can_create_and_edit_page()` - Page creation/editing
- `it_admin_can_create_and_publish_post()` - Post creation
- `it_admin_can_create_category_and_assign_to_content()` - Category management
- `it_published_content_appears_on_frontend()` - Frontend visibility
- `it_unpublished_content_is_hidden()` - Content visibility control

### 3. E-commerce Critical Flows (`ECommerceCriticalFlowsTest`)

Tests shop functionality:
- `it_user_can_browse_products_and_add_to_cart()` - Product browsing
- `it_user_can_update_cart_quantity()` - Cart updates
- `it_user_can_remove_item_from_cart()` - Cart removal
- `it_empty_cart_shows_appropriate_message()` - Empty cart handling
- `it_guest_can_complete_checkout()` - Guest checkout

### 4. Form Submission Flows (`FormSubmissionFlowsTest`)

Tests form handling:
- `it_contact_form_submits_successfully()` - Contact form
- `it_form_shows_validation_errors()` - Form validation
- `it_newsletter_subscription_works()` - Newsletter signup
- `it_custom_form_with_file_upload()` - File uploads
- `it_multi_step_form_completion()` - Multi-step forms

## Components

### Reusable Components

- **AdminLogin**: Handles admin authentication
- **ChekForJavascriptErrors**: Validates no JS errors occur
- **AdminContentImageAdd**: Image upload functionality
- **AdminContentCustomFieldAdd**: Custom field handling

### Creating New Components

Components encapsulate reusable browser interactions:

```php
class MyComponent extends BaseComponent
{
    public function performAction(Browser $browser)
    {
        $browser->click('@selector');
        $browser->pause(1000);
    }
}
```

## Best Practices

1. **Use unique identifiers**: Always use time() or uniqid() for test data
2. **Wait for elements**: Use waitForText() or waitFor() before assertions
3. **Cleanup data**: Delete created records after tests
4. **Check JS errors**: Wrap critical sections with ChekForJavascriptErrors
5. **Handle exceptions**: Use try-catch for optional features
6. **Pause appropriately**: Use pause() to allow animations/transitions

## Troubleshooting

### ChromeDriver Issues

```bash
# Update ChromeDriver
php artisan dusk:chrome-driver

# Or specify version
php artisan dusk:chrome-driver --detect
```

### Test Failures

1. Check screenshots in `tests/Browser/screenshots/`
2. Review console logs for JavaScript errors
3. Ensure development server is running
4. Check database state between tests

### Timeout Issues

Increase timeout values:
```php
$browser->waitForText('Text', 60); // 60 seconds
```

## Configuration

Configure Dusk in `.env.dusk.local`:

```env
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

## CI/CD Integration

For CI environments, use headless mode:

```yaml
- name: Run Dusk Tests
  run: |
    php artisan serve &
    sleep 5
    php artisan dusk
```

## Coverage

These tests cover the most critical user paths. Add new tests when:
- New critical features are added
- User workflows change
- Bug fixes need regression tests
