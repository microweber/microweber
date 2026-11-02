#!/bin/sh
set -e

# Development Entrypoint Script for Microweber

echo "=========================================="
echo "Microweber Docker Entrypoint (Development)"
echo "=========================================="

# Create required directories if they don't exist
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/testing
mkdir -p bootstrap/cache
# NOTE: do not `mkdir public/storage` — it is a symlink to storage/app/public
# (created by `storage:link`). mkdir -p would follow it and fail.

# Set proper permissions
chmod -R 777 storage bootstrap/cache 2>/dev/null || true

# Check if .env file exists, create from example if not
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env file from example..."
    if [ -f /var/www/html/.env.example ]; then
        cp /var/www/html/.env.example /var/www/html/.env
        
        # Update database configuration for Docker
        sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' /var/www/html/.env 2>/dev/null || true
        sed -i 's/^DB_DATABASE=.*/DB_DATABASE=microweber/' /var/www/html/.env 2>/dev/null || true
        sed -i 's/^DB_USERNAME=.*/DB_USERNAME=microweber/' /var/www/html/.env 2>/dev/null || true
        sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=secret/' /var/www/html/.env 2>/dev/null || true
        sed -i 's/^DB_HOST=.*/DB_HOST=db/' /var/www/html/.env 2>/dev/null || true
        sed -i 's/^DB_PORT=.*/DB_PORT=3306/' /var/www/html/.env 2>/dev/null || true
        
        # Update Redis configuration
        sed -i 's/^REDIS_HOST=.*/REDIS_HOST=redis/' /var/www/html/.env 2>/dev/null || true
        sed -i 's/^REDIS_PORT=.*/REDIS_PORT=6379/' /var/www/html/.env 2>/dev/null || true
        
        # Update queue and cache configuration
        sed -i 's/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=database/' /var/www/html/.env 2>/dev/null || true
        sed -i 's/^CACHE_STORE=.*/CACHE_STORE=redis/' /var/www/html/.env 2>/dev/null || true
        sed -i 's/^SESSION_DRIVER=.*/SESSION_DRIVER=redis/' /var/www/html/.env 2>/dev/null || true
        
        # Update mail configuration for Mailpit
        sed -i 's/^MAIL_MAILER=.*/MAIL_MAILER=smtp/' /var/www/html/.env 2>/dev/null || true
        sed -i 's/^MAIL_HOST=.*/MAIL_HOST=mailpit/' /var/www/html/.env 2>/dev/null || true
        sed -i 's/^MAIL_PORT=.*/MAIL_PORT=1025/' /var/www/html/.env 2>/dev/null || true
    fi
fi

# Generate application key if not set
if [ -z "$(grep "^APP_KEY=" /var/www/html/.env | cut -d '=' -f2)" ]; then
    echo "Generating application key..."
    php artisan key:generate --no-interaction --force 2>/dev/null || true
fi

# Install PHP dependencies if vendor directory is missing
if [ ! -d /var/www/html/vendor ] || [ -z "$(ls -A /var/www/html/vendor 2>/dev/null)" ]; then
    echo "Installing PHP dependencies..."
    composer install --no-interaction --prefer-dist --no-scripts 2>/dev/null || true
fi

# Build the two runtime asset bundles (public frontend + Filament admin theme)
# only when the published output is missing — the repo is bind-mounted, so a
# host that already built them is reused and `up` stays fast. The full
# per-package build (run-build.js, ~200 folders) is intentionally NOT run here;
# set MW_SKIP_ASSET_BUILD=1 to skip entirely.
FRONTEND_BUILD="/var/www/html/public/vendor/microweber-packages/frontend-assets/build"
if [ "${MW_SKIP_ASSET_BUILD:-0}" != "1" ] && [ ! -d "$FRONTEND_BUILD" ]; then
    echo "Building front-end asset bundles (first run)..."
    ( cd /var/www/html/packages/frontend-assets && (npm ci || npm install) && npm run build ) \
        2>/dev/null || echo "Frontend bundle build skipped/failed"
    ( cd /var/www/html/packages/microweber-filament-theme && (npm ci || npm install) && npm run build ) \
        2>/dev/null || echo "Filament theme build skipped/failed"
fi

# Wait for database to be ready
wait_for_db() {
    echo "Waiting for database..."
    max_attempts=30
    attempt=1
    
    while [ $attempt -le $max_attempts ]; do
        if php -r "
            try {
                \$pdo = new PDO(
                    'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT', '3306'),
                    getenv('DB_USERNAME'),
                    getenv('DB_PASSWORD'),
                    [PDO::ATTR_TIMEOUT => 5]
                );
                exit(0);
            } catch (PDOException \$e) {
                exit(1);
            }
        " 2>/dev/null; then
            echo "Database is ready!"
            return 0
        fi
        
        echo "Database not ready yet... attempt $attempt/$max_attempts"
        sleep 2
        attempt=$((attempt + 1))
    done
    
    echo "Warning: Could not connect to database after $max_attempts attempts"
    echo "You may need to run migrations manually after the database is ready."
    return 1
}

# Wait for database
if [ -n "$DB_HOST" ]; then
    wait_for_db
fi

# Install Microweber on first boot, otherwise migrate. `migrate` alone does NOT
# yield a working CMS (no admin user, no module/template statuses, no content) —
# the installer does. Marker on the storage volume prevents reinstall on restart.
MW_INSTALL_MARKER="/var/www/html/storage/app/.mw-installed"
if [ "${MW_SKIP_INSTALL:-0}" = "1" ]; then
    echo "MW_SKIP_INSTALL=1 — skipping install/migrate (worker/scheduler role)."
elif [ ! -f "$MW_INSTALL_MARKER" ]; then
    echo "Microweber not installed yet — running microweber:install..."
    if php artisan microweber:install \
        --db-driver="${DB_CONNECTION:-mysql}" \
        --db-host="$DB_HOST" \
        --db-port="${DB_PORT:-3306}" \
        --db-name="$DB_DATABASE" \
        --db-username="$DB_USERNAME" \
        --db-password="$DB_PASSWORD" \
        --email="${MW_ADMIN_EMAIL:-admin@admin.com}" \
        --username="${MW_ADMIN_USERNAME:-admin}" \
        --password="${MW_ADMIN_PASSWORD:-admin}" \
        --default-content="${MW_DEFAULT_CONTENT:-default}" \
        --app-url="${APP_URL:-http://localhost}" \
        --no-interaction; then
        touch "$MW_INSTALL_MARKER"
        echo "Microweber installed. Admin: ${MW_ADMIN_EMAIL:-admin@admin.com} / ${MW_ADMIN_PASSWORD:-admin}"
    else
        echo "WARNING: microweber:install failed — it will be retried on next start."
    fi
elif [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force --no-interaction 2>/dev/null || echo "Migration may have already run or database not ready"
fi

# Publish assets
php artisan vendor:publish --tag=public --force --no-interaction 2>/dev/null || true
php artisan storage:link --force --no-interaction 2>/dev/null || true

# Artisan ran as root above; the fpm pool runs as www. Hand storage/ and
# bootstrap/cache back so runtime logging/caching doesn't 500 on a write.
chown -R www:www storage bootstrap/cache 2>/dev/null || true

echo "=========================================="
echo "Development environment ready!"
echo "=========================================="

# Execute the command passed to the container
exec "$@"
