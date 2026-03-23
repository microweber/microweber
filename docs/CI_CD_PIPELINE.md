# CI/CD Pipeline Documentation

## Overview

This document describes the comprehensive CI/CD pipeline for the Microweber CMS project, implementing automated testing, security scanning, and deployment workflows using GitHub Actions.

## Workflow File

**Location:** `.github/workflows/cicd-pipeline.yml`

## Pipeline Stages

The CI/CD pipeline consists of 9 stages that run sequentially or in parallel:

```
┌─────────────────┐
│ 1. Code Quality │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ 2. Security     │
│    Scanning     │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ 3. Unit Tests   │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ 4. Module Tests │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ 5. Integration  │
│    Tests        │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ 6. Build &      │
│    Package      │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ 7. Deploy to    │
│    Staging      │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ 8. Deploy to    │
│    Production   │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ 9. Notification │
└─────────────────┘
```

## Stage Details

### Stage 1: Code Quality Checks

**Purpose:** Validate code structure, syntax, and style before running tests.

**Checks Performed:**
- `composer validate --strict` - Validates composer.json/composer.lock
- `composer check-platform-reqs` - Ensures PHP extension requirements
- PHP syntax check on all `.php` files
- PHP-CS-Fixer (if configured)
- PHPStan static analysis (if configured)

**Configuration Files:**
- `.php-cs-fixer.php` or `.php-cs-fixer.dist.php` (optional)
- `phpstan.neon.dist` or `phpstan.neon` (optional)

### Stage 2: Security Scanning

**Purpose:** Detect security vulnerabilities and potential secrets in code.

**Checks Performed:**
- `composer audit` - Scans PHP dependencies for known vulnerabilities
- `npm audit` - Scans JavaScript dependencies
- Secret pattern detection in source code
- Sensitive data exposure check

**Artifacts:**
- `composer-audit-results.json`
- `npm-audit-results.json`

### Stage 3: Unit & Feature Tests

**Purpose:** Run unit tests and feature tests across multiple PHP versions.

**Matrix Strategy:**
- PHP Versions: 8.3 (configurable)
- Test Suites: Unit, Feature

**Artifacts:**
- Coverage reports (clover XML)
- JUnit test results

### Stage 4: Module Tests

**Purpose:** Run module-specific test suites to ensure module functionality.

**Test Groups:**
- Modules-Newsletter
- Modules-Content
- Modules-Billing
- Modules-Group3 (Components, Comments, Cart, Category, Coupons)
- Modules-Group4 (Product, Order, Customer, Tag, Payment, Shipping)
- Modules-Group5 (Invoice, Form, Offer, Backup, CustomFields, Shop, Rating, Page, Faq, Profile, Post, Settings)
- Modules-Group6A (Video, MailTemplate, Sitemap, Menu, Media, Teamcard, Restore, ImageRollover, Checkout, Attributes, AiWizard, SiteStats, Marketplace, FileManager)
- Modules-Group6B (Tax, Tabs, GoogleAnalytics, Country, RssFeed, Log, Export, Btn, Accordion, Address, Audio, Background, BeforeAfter, Blog, Captcha, Cloudflare, CookieNotice, Currency, Embed, FacebookLike, FacebookPage, GoogleMaps, HighlightCode, Logo, Marquee, OpenApi, Pdf, Pictures, Sharer, Skills, Slider, SocialLinks, Testimonials, TextType, TweetEmbed)

### Stage 5: Integration Tests

**Purpose:** Run database integration tests using MySQL.

**Services:**
- MySQL 8.0 container

**Requirements:**
- Integration tests only run on push events (not pull requests)
- Requires successful completion of unit and module tests

### Stage 6: Build & Package

**Purpose:** Create production-ready distribution packages.

**Steps:**
1. Install production dependencies (`composer install --no-dev`)
2. Build assets (`npm run build`)
3. Create distribution directory
4. Clean up development files
5. Create ZIP archive
6. Upload as artifact

**Output:**
- `microweber-{version}.zip`

### Stage 7: Deploy to Staging

**Purpose:** Deploy the built package to staging environment.

**Conditions:**
- Push to `develop` or `main` branch
- Manual trigger with `environment=staging`
- All previous stages successful

**Configuration:**
Requires GitHub repository variables:
- `STAGING_URL` - Staging environment URL
- `STAGING_DEPLOY_ENABLED` - Set to `true` to enable

### Stage 8: Deploy to Production

**Purpose:** Deploy the built package to production environment.

**Conditions:**
- Git tag push (release)
- Manual trigger with `environment=production`
- Successful staging deployment

**Features:**
- Creates GitHub Release with artifacts
- Supports alpha, beta, rc pre-releases

**Configuration:**
Requires GitHub repository variables:
- `PRODUCTION_URL` - Production environment URL
- `PRODUCTION_DEPLOY_ENABLED` - Set to `true` to enable

### Stage 9: Notification

**Purpose:** Send pipeline status notifications.

**Outputs:**
- GitHub Step Summary with pipeline status table
- Console notifications on failure

## Trigger Events

The pipeline triggers on:

1. **Push to branches:** `main`, `master`, `develop`
2. **Tags:** `v*` (e.g., v1.0.0)
3. **Pull requests:** to `main`, `master`, `develop`
4. **Manual trigger:** via `workflow_dispatch`

## Environment Configuration

### Staging Environment Variables

Copy `.env.staging` to `.env` and configure:

```bash
APP_ENV=staging
APP_URL=https://staging.your-domain.com
DB_DATABASE=microweber_staging
DB_USERNAME=microweber_staging
DB_PASSWORD=your_secure_password

# Redis for caching
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Production Environment Variables

Copy `.env.production` to `.env` and configure:

```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Production database
DB_DATABASE=microweber
DB_USERNAME=microweber
DB_PASSWORD=your_secure_password

# Production caching
CACHE_STORE=redis
PAGE_CACHE_ENABLED=true
FRAGMENT_CACHE_ENABLED=true

# Security
SESSION_SECURE_COOKIE=true
APP_FORCE_HTTPS=true
```

## Required GitHub Secrets

Configure these secrets in your GitHub repository settings:

### For Code Coverage
- `CODECOV_TOKEN` - Token for codecov.io integration

### For Deployment
- `STAGING_SSH_KEY` - SSH key for staging server (optional)
- `PRODUCTION_SSH_KEY` - SSH key for production server (optional)
- `FTP_USERNAME` - FTP username for build uploads (optional)
- `FTP_PASSWORD` - FTP password for build uploads (optional)
- `FTP_HOST` - FTP server hostname (optional)

## Required GitHub Variables

Configure these variables in your GitHub repository settings:

### For Staging Deployment
- `STAGING_URL` - URL of staging environment
- `STAGING_DEPLOY_ENABLED` - Set to `true` to enable deployment

### For Production Deployment
- `PRODUCTION_URL` - URL of production environment
- `PRODUCTION_DEPLOY_ENABLED` - Set to `true` to enable deployment

## Manual Workflow Execution

You can manually trigger the pipeline from the GitHub Actions tab:

1. Go to **Actions** → **CI/CD Pipeline**
2. Click **Run workflow**
3. Select options:
   - **Environment:** `staging` or `production`
   - **Skip tests:** Check to skip test execution (not recommended)
4. Click **Run workflow**

## Caching Strategy

The pipeline implements multiple caching layers for performance:

1. **Composer cache** - Caches downloaded packages
2. **Vendor directory** - Caches installed dependencies
3. **Node modules** - Cached via `actions/setup-node`
4. **Build artifacts** - Test results and coverage reports

## Test Reports

Test results are available as artifacts for 7 days:

- `test-results-php8.3-Unit`
- `test-results-php8.3-Feature`
- `test-results-{module-group}`
- `security-audit-results`

## Troubleshooting

### Pipeline Failures

1. **Code Quality Failures:**
   - Check PHP syntax errors
   - Run `composer validate` locally
   - Fix PHPStan errors if configured

2. **Security Scan Failures:**
   - Update dependencies: `composer update`, `npm update`
   - Review `composer audit` output
   - Remove any committed secrets

3. **Test Failures:**
   - Check test logs in artifacts
   - Run tests locally: `composer test`
   - Check for environment-specific issues

4. **Build Failures:**
   - Ensure `npm run build` succeeds locally
   - Check for missing files in `.gitignore`
   - Verify `version.txt` exists

### Common Issues

**Out of Memory:**
- Tests are split into groups to prevent OOM errors
- Each group runs in separate processes

**Timeout:**
- Module tests have a 30-minute timeout
- Increase if needed in workflow file

**Cache Issues:**
- Clear GitHub Actions cache from repository settings
- Re-run workflow

## Integration with Existing Workflows

The CI/CD pipeline complements existing workflows:

- **ci.yml** - Quick tests on push
- **matrix-tests.yml** - Multi-version compatibility tests
- **security-scan.yml** - Detailed security scanning
- **codecov.yml** - Code coverage reporting
- **dusk.yml** - Browser/Dusk tests

## Maintenance

### Adding New Test Suites

1. Add new testsuite to `phpunit.xml`
2. Add corresponding matrix entry in `cicd-pipeline.yml`
3. Update this documentation

### Modifying Deployment

1. Edit deployment steps in `.github/workflows/cicd-pipeline.yml`
2. Add required secrets/variables
3. Test on staging first

### Updating PHP/Node Versions

1. Modify `PHP_VERSION` or `NODE_VERSION` in workflow env section
2. Update matrix strategies as needed
3. Test with matrix-tests.yml first

## References

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Composer Audit](https://getcomposer.org/doc/03-cli.md#audit)
- [NPM Audit](https://docs.npmjs.com/cli/v8/commands/npm-audit)
