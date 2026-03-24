# Browser Tests (Dusk)

This directory contains simple browser tests using Laravel Dusk to verify that pages load without JavaScript errors.

## Overview

These smoke tests are designed to be:
- **Fast**: Simple page loads without complex interactions
- **Reliable**: Check for JavaScript errors in console
- **Focused**: Verify critical pages render properly

## Running Tests

```bash
# Run all browser tests
php artisan dusk

# Run specific test
php artisan dusk --filter SmokeTest

# Run with specific browser (headless)
DUSK_HEADLESS=1 php artisan dusk
```

## Test Coverage

### SmokeTest.php
Basic page loading tests that check for JavaScript errors:

1. **Home Page** - `/` - Verifies homepage loads
2. **Shop Page** - `/shop` - Verifies shop page loads
3. **Login Page** - `/login` - Verifies login form loads
4. **Admin Page** - `/admin` - Verifies admin panel loads
5. **Register Page** - `/register` - Verifies registration form loads
6. **Checkout Page** - `/checkout` - Verifies checkout page loads
7. **Cart Page** - `/cart` - Verifies cart page loads
8. **Search Page** - `/search` - Verifies search page loads
9. **Profile Page** - `/profile` - Verifies profile page loads
10. **Forgot Password** - `/forgot-password` - Verifies forgot password page loads

### AdminPagesTest.php
Admin panel page loading tests with authentication:

**Prerequisites:**
- Creates an admin user before running tests
- Logs in as admin before testing each page

**Test Coverage:**
- **Dashboard** - `/admin` - Main admin dashboard
- **Content Pages** - `/admin/pages` - Content management
- **Products** - `/admin/products` - Product catalog
- **Orders** - `/admin/orders` - Order management
- **Customers** - `/admin/customers` - Customer management
- **Categories** - `/admin/categories` - Category management
- **Comments** - `/admin/comments` - Comment moderation
- **Media** - `/admin/media` - Media library
- **Coupons** - `/admin/coupons` - Coupon management
- **Tax Rates** - `/admin/tax-rates` - Tax configuration
- **Shipping Providers** - `/admin/shipping-providers` - Shipping setup
- **Payment Providers** - `/admin/payment-providers` - Payment gateways
- **Invoices** - `/admin/invoices` - Invoice management
- **Newsletter Campaigns** - `/admin/newsletter/campaigns` - Email campaigns
- **Users** - `/admin/users` - User management
- **Roles** - `/admin/roles` - Role management
- **Marketplace** - `/admin/marketplace` - Module marketplace
- **Settings** - `/admin/settings` - System configuration
- **Modules** - `/admin/modules` - Module management
- **Templates** - `/admin/templates` - Template management
- **Backup History** - `/admin/backup-history` - Backup management
- **Error Tracking** - `/admin/error-tracking` - Error monitoring
- **Currencies** - `/admin/currencies` - Currency management
- **Product Variants** - `/admin/product-variant-attributes` - Product variants
- **Product Inventory** - `/admin/product-inventory` - Inventory management
- **Pricing Rules** - `/admin/product-pricing-rules` - Pricing configuration
- **Tags** - `/admin/tags` - Tag management
- **AI Chat** - `/admin/ai/agent-chats` - AI chat management
- **Mail Templates** - `/admin/mail-templates` - Email templates
- **Billing** - `/admin/subscriptions` - Billing & subscriptions
- **Workflows** - `/admin/newsletter/workflows` - Automation workflows
- **Backup Schedules** - `/admin/backup-schedules` - Automated backups
- **Permissions** - `/admin/permissions` - Permission management

## Legacy Tests

The original complex Dusk tests have been moved to `tests/BrowserLegacy/`. These include:
- CriticalFlowsTest - Complex e-commerce flows
- Cross-browser tests
- Multi-language tests
- Admin panel tests
- Shop/checkout flows
- Live edit tests

See `tests/BrowserLegacy/README.md` for details.

## Test Structure

Each test:
1. Visits the page URL
2. Waits for body to load
3. Captures browser console logs
4. Asserts no JavaScript errors (SEVERE level logs)

## Requirements

- Chrome or Chromium browser installed
- Laravel Dusk configured
- Application running on local server (typically http://127.0.0.1:8000)

## Configuration

Tests use the default `DuskTestCase` configuration in `tests/DuskTestCase.php`.

Environment variables:
- `DUSK_DRIVER_URL` - WebDriver URL (default: http://localhost:9515)
- `DUSK_HEADLESS_DISABLED` - Set to disable headless mode
