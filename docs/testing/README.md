# Microweber Pest Testing Setup

This directory contains the Pest testing framework configuration and documentation for Microweber.

## What's Included

- **Root Configuration**
- `/Pest.php` - Root Pest configuration with module auto-discovery
- `/pest.xml` - Pest XML configuration for test suites
- `/tests/Pest.php` - Root tests directory configuration
- `/tests/Unit/ExamplePestTest.php` - Example Pest tests

- **Module-Level Configuration**
- 76 modules now have `Pest.php` files in their Tests/tests directories
- Each module can run its own Pest test suite independently

- **Documentation**
- `module-testing-guide.md` - Complete guide for writing module-level Pest tests
- `module-pest-template.php` - Template for creating module Pest.php files
- `setup-module-pest.php` - Interactive script to set up Pest for a module
- `generate-module-pest-files.php` - Script to generate Pest.php for all modules

## Quick Start

### Installation

```bash
composer install --dev
```

### Running Tests

```bash
# Run all tests
./vendor/bin/pest

# Run specific module tests
./vendor/bin/pest Modules/Billing/Tests

# Run with coverage
./vendor/bin/pest --coverage

# Run specific test suite
./vendor/bin/pest --testsuite=Unit
./vendor/bin/pest --testsuite=Feature
```

## Setting Up a New Module

```bash
# Interactive setup
php docs/testing/setup-module-pest.php YourModuleName
```

Or manually:

1. Create `Modules/YourModule/Tests/Unit` and `Modules/YourModule/Tests/Feature` directories
2. Create `Modules/YourModule/Tests/Pest.php` (see template)
3. Write your first test in `Modules/YourModule/Tests/Unit/ExampleTest.php`

## Writing Tests

See `module-testing-guide.md` for detailed examples and best practices.

### Basic Pest Test

```php
<?php

test('user can create post', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/posts', [
        'title' => 'Test Post',
    ]);

    $response->assertRedirect();
    expect($user->posts)->toHaveCount(1);
});
```

## Backward Compatibility

All existing PHPUnit tests continue to work. You can gradually migrate tests from PHPUnit to Pest at your own pace.

## Modules with Pest Configuration

The following modules have Pest.php files:

- Accordion, Address, AiWizard, Attributes, Audio, Background, Backup, BeforeAfter
- Blog, Btn, Captcha, Cart, Category, Checkout, Cloudflare, Comments
- Components, Content, ContentData, ContentDataVariant, CookieNotice
- Country, Coupons, Currency, Customer, CustomFields
- Embed, Export, FacebookLike, FacebookPage, Faq, FileManager, Form
- GoogleAnalytics, GoogleMaps, HighlightCode, ImageRollover, Invoice
- Log, Logo, MailTemplate, Marketplace, Marquee, Media, Menu, Newsletter
- Offer, OpenApi, Order, Page, Payment, Pdf, Pictures, Post
- Product, Profile, Rating, Restore, RssFeed, Settings, Sharer
- Shipping, Shop, Sitemap, SiteStats, Skills, Slider, SocialLinks
- Tabs, Tag, Tax, Teamcard, Testimonials, TextType, TweetEmbed, Video

Total: 76 modules configured

## Next Steps

1. Read the full guide: `module-testing-guide.md`
2. Try running tests: `./vendor/bin/pest`
3. Create your first module test using the setup script
4. Refer to the example tests for patterns and best practices
