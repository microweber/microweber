# Staging Deployment Guide

This guide covers the full regression testing and staging deployment process for Microweber.

## Overview

The staging deployment process includes:
1. **Admin CRUD Testing** - Comprehensive testing of all Filament resources
2. **Frontend Checkout Testing** - End-to-end checkout flow validation
3. **AI Chat Testing** - Chat creation, tool execution, and response handling
4. **Billing Testing** - Subscription lifecycle, webhooks, and payments
5. **Staging Deployment** - Automated deployment to staging environment
6. **Log Monitoring** - 24-hour post-deployment monitoring

## Prerequisites

- PHP 8.2+
- Composer
- Node.js and NPM
- MySQL/MariaDB or SQLite
- Git access
- SSH access to staging server

## Regression Test Suite

### Test Files

All regression tests are located in `tests/Feature/Regression/`:

1. **AdminCrudRegressionTest.php** - Tests all admin resource CRUD operations
2. **FrontendCheckoutRegressionTest.php** - Tests complete checkout flow
3. **AiChatRegressionTest.php** - Tests AI chat functionality
4. **BillingRegressionTest.php** - Tests subscription lifecycle

### Running Tests

```bash
# Run all regression tests
php artisan test tests/Feature/Regression/

# Run specific test file
php artisan test tests/Feature/Regression/AdminCrudRegressionTest.php

# Run with coverage
php artisan test --coverage tests/Feature/Regression/
```

## Deployment Scripts

### 1. deploy-staging.sh

Main deployment script located at `scripts/deploy-staging.sh`.

**Features:**
- Pre-deployment checks (PHP version, dependencies)
- Database backup
- Code update from Git
- Composer and NPM dependency installation
- Database migrations
- Cache warming
- Asset compilation
- Permission setting
- Smoke tests
- Log monitoring startup

**Usage:**
```bash
# Deploy from main branch (default)
./scripts/deploy-staging.sh

# Deploy specific branch
BRANCH=feature/my-feature ./scripts/deploy-staging.sh

# Deploy to custom staging host
STAGING_HOST=staging.example.com ./scripts/deploy-staging.sh
```

**Environment Variables:**
- `STAGING_HOST` - Staging server hostname (default: staging.microweber.com)
- `STAGING_USER` - SSH user for deployment (default: deploy)
- `STAGING_PATH` - Deployment path (default: /var/www/staging)
- `BRANCH` - Git branch to deploy (default: main)
- `PHP_VERSION` - Required PHP version (default: 8.2)

### 2. smoke-tests.sh

Quick health checks located at `scripts/smoke-tests.sh`.

**Tests Include:**
- Homepage loads (HTTP 200)
- Admin login page accessible
- Static assets (CSS/JS) available
- API endpoints responding
- Database connection active
- Cache files present
- Storage permissions correct
- Environment variables set

**Usage:**
```bash
# Run with default URL (http://localhost)
./scripts/smoke-tests.sh

# Run against specific URL
BASE_URL=https://staging.example.com ./scripts/smoke-tests.sh
```

### 3. monitor-logs.sh

24-hour log monitoring located at `scripts/monitor-logs.sh`.

**Monitored Errors:**
- Fatal errors
- Uncaught exceptions
- Database errors (SQLSTATE)
- Memory limit errors
- Missing class/function errors
- Connection errors
- Permission denied errors
- 5xx HTTP errors

**Features:**
- Real-time error tracking
- Configurable alert thresholds
- Email notifications (optional)
- Slack notifications (optional)
- Detailed error reports

**Usage:**
```bash
# Start monitoring for 24 hours (default)
./scripts/monitor-logs.sh

# Check status
./scripts/monitor-logs.sh status

# Stop monitoring
./scripts/monitor-logs.sh stop

# Custom duration
MONITOR_DURATION=12h ./scripts/monitor-logs.sh

# Custom alert threshold
ALERT_THRESHOLD=5 ./scripts/monitor-logs.sh

# With email alerts
ALERT_EMAIL=admin@example.com ./scripts/monitor-logs.sh

# With Slack notifications
SLACK_WEBHOOK_URL=https://hooks.slack.com/... ./scripts/monitor-logs.sh
```

## Deployment Checklist

### Pre-Deployment
- [ ] All tests passing locally
- [ ] Code committed and pushed
- [ ] Database migrations reviewed
- [ ] Environment variables configured
- [ ] Backup strategy verified

### Deployment Steps
1. **Run regression tests**
   ```bash
   php artisan test tests/Feature/Regression/
   ```

2. **Execute deployment**
   ```bash
   ./scripts/deploy-staging.sh
   ```

3. **Verify deployment**
   ```bash
   ./scripts/smoke-tests.sh
   ```

4. **Start monitoring**
   ```bash
   ./scripts/monitor-logs.sh
   ```

### Post-Deployment
- [ ] Smoke tests pass
- [ ] Admin panel accessible
- [ ] Frontend checkout works
- [ ] AI chat responds
- [ ] Billing webhooks received
- [ ] No critical errors in logs
- [ ] Performance metrics acceptable

## Troubleshooting

### Common Issues

**Test Failures**
```bash
# Run tests with verbose output
php artisan test --verbose tests/Feature/Regression/

# Run single test
php artisan test --filter=test_content_resource_full_crud
```

**Deployment Failures**
```bash
# Check logs
 tail -f storage/logs/laravel.log

# Verify environment
 php artisan env

# Clear caches manually
 php artisan optimize:clear
```

**Monitoring Issues**
```bash
# Check if monitoring is running
./scripts/monitor-logs.sh status

# View error log
 tail -f storage/logs/monitor-errors-*.log
```

## Security Considerations

- Never commit sensitive credentials to Git
- Use environment variables for secrets
- Enable webhook signature verification in production
- Restrict staging access by IP or VPN
- Regular security scans with `composer audit`

## Performance Monitoring

After deployment, monitor:
- Page load times
- Database query performance
- Memory usage
- Error rates
- User session duration

Use Laravel Debugbar for local debugging and Telescope for production monitoring.
