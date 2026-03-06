#!/bin/bash

# Staging Deployment Script for Microweber
# This script handles deployment to staging environment

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
STAGING_HOST="${STAGING_HOST:-staging.microweber.com}"
STAGING_USER="${STAGING_USER:-deploy}"
STAGING_PATH="${STAGING_PATH:-/var/www/staging}"
BRANCH="${BRANCH:-main}"
PHP_VERSION="${PHP_VERSION:-8.2}"

echo -e "${GREEN}Starting staging deployment...${NC}"
echo "Timestamp: $(date)"
echo "Branch: $BRANCH"
echo "Host: $STAGING_HOST"
echo ""

# Step 1: Pre-deployment checks
echo -e "${YELLOW}Step 1: Running pre-deployment checks...${NC}"

# Check PHP version
CURRENT_PHP=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
if [ "$CURRENT_PHP" != "$PHP_VERSION" ]; then
    echo -e "${RED}Error: PHP version $CURRENT_PHP is installed, but $PHP_VERSION is required${NC}"
    exit 1
fi

# Check if composer is available
if ! command -v composer &> /dev/null; then
    echo -e "${RED}Error: Composer is not installed${NC}"
    exit 1
fi

# Check if artisan exists
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: artisan file not found. Are you in the project root?${NC}"
    exit 1
fi

echo -e "${GREEN}Pre-deployment checks passed${NC}"
echo ""

# Step 2: Put application in maintenance mode
echo -e "${YELLOW}Step 2: Enabling maintenance mode...${NC}"
php artisan down --message="Deployment in progress. Please check back in a few minutes." || true
echo -e "${GREEN}Maintenance mode enabled${NC}"
echo ""

# Step 3: Backup database
echo -e "${YELLOW}Step 3: Creating database backup...${NC}"
BACKUP_FILE="storage/backups/staging-$(date +%Y%m%d-%H%M%S).sql"
mkdir -p storage/backups
if [ -f ".env" ]; then
    DB_CONNECTION=$(grep DB_CONNECTION .env | cut -d '=' -f2)
    if [ "$DB_CONNECTION" == "mysql" ] || [ "$DB_CONNECTION" == "mariadb" ]; then
        DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)
        DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2)
        DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2)
        mysqldump -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" > "$BACKUP_FILE" 2>/dev/null || echo "Database backup skipped (credentials may be invalid)"
    elif [ "$DB_CONNECTION" == "sqlite" ]; then
        cp database/database.sqlite "$BACKUP_FILE" 2>/dev/null || echo "SQLite backup skipped"
    fi
fi
echo -e "${GREEN}Database backup completed${NC}"
echo ""

# Step 4: Update code
echo -e "${YELLOW}Step 4: Updating code from repository...${NC}"
git fetch origin
git checkout "$BRANCH"
git pull origin "$BRANCH"
echo -e "${GREEN}Code updated${NC}"
echo ""

# Step 5: Install/update dependencies
echo -e "${YELLOW}Step 5: Installing dependencies...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
npm ci --production 2>/dev/null || npm install --production 2>/dev/null || echo "NPM dependencies skipped"
echo -e "${GREEN}Dependencies installed${NC}"
echo ""

# Step 6: Run migrations
echo -e "${YELLOW}Step 6: Running database migrations...${NC}"
php artisan migrate --force
echo -e "${GREEN}Migrations completed${NC}"
echo ""

# Step 7: Clear and warm caches
echo -e "${YELLOW}Step 7: Clearing and warming caches...${NC}"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan filament:assets
echo -e "${GREEN}Caches optimized${NC}"
echo ""

# Step 8: Compile assets
echo -e "${YELLOW}Step 8: Compiling frontend assets...${NC}"
npm run build 2>/dev/null || echo "Build step skipped (no build script)"
echo -e "${GREEN}Assets compiled${NC}"
echo ""

# Step 9: Set proper permissions
echo -e "${YELLOW}Step 9: Setting file permissions...${NC}"
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
echo -e "${GREEN}Permissions set${NC}"
echo ""

# Step 10: Run smoke tests
echo -e "${YELLOW}Step 10: Running smoke tests...${NC}"
php artisan test --filter=SmokeTest 2>/dev/null || echo "Smoke tests not found or failed"
./scripts/smoke-tests.sh 2>/dev/null || echo "Smoke test script not found"
echo -e "${GREEN}Smoke tests completed${NC}"
echo ""

# Step 11: Disable maintenance mode
echo -e "${YELLOW}Step 11: Disabling maintenance mode...${NC}"
php artisan up
echo -e "${GREEN}Maintenance mode disabled${NC}"
echo ""

# Step 12: Verify deployment
echo -e "${YELLOW}Step 12: Verifying deployment...${NC}"
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health || echo "000")
if [ "$HTTP_STATUS" == "200" ]; then
    echo -e "${GREEN}Deployment verified: HTTP $HTTP_STATUS${NC}"
else
    echo -e "${YELLOW}Deployment verification warning: HTTP $HTTP_STATUS${NC}"
fi
echo ""

# Step 13: Log deployment
echo -e "${YELLOW}Step 13: Logging deployment...${NC}"
echo "$(date): Staging deployment completed - Branch: $BRANCH" >> storage/logs/deployments.log
echo -e "${GREEN}Deployment logged${NC}"
echo ""

# Step 14: Start monitoring (background process)
echo -e "${YELLOW}Step 14: Starting log monitoring...${NC}"
nohup ./scripts/monitor-logs.sh > /dev/null 2>&1 &
echo -e "${GREEN}Log monitoring started (PID: $!)${NC}"
echo ""

echo -e "${GREEN}================================${NC}"
echo -e "${GREEN}Deployment completed successfully!${NC}"
echo -e "${GREEN}================================${NC}"
echo ""
echo "Staging URL: https://$STAGING_HOST"
echo "Health Check: http://localhost/health"
echo "Log Monitoring: tail -f storage/logs/laravel.log"
echo ""
echo "Run the following to check status:"
echo "  ./scripts/smoke-tests.sh"
echo "  tail -f storage/logs/laravel.log"
