#!/bin/sh
set -e

# Production Entrypoint Script for Microweber

echo "=========================================="
echo "Microweber Docker Entrypoint"
echo "Environment: ${APP_ENV:-production}"
echo "=========================================="

# Docker secrets support: any VAR_FILE pointing at a readable file is loaded
# into VAR (the compose production file passes DB_PASSWORD_FILE, APP_KEY_FILE,
# etc. via /run/secrets/*). An already-set VAR is not overwritten.
load_secret_files() {
    for var in APP_KEY DB_PASSWORD DB_ROOT_PASSWORD DB_USERNAME REDIS_PASSWORD; do
        eval "file=\${${var}_FILE:-}"
        eval "cur=\${${var}:-}"
        if [ -n "$file" ] && [ -f "$file" ] && [ -z "$cur" ]; then
            val="$(cat "$file")"
            export "$var=$val"
            echo "Loaded $var from \$${var}_FILE"
        fi
    done
}
load_secret_files

# Create required directories if they don't exist
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/testing
mkdir -p bootstrap/cache
# NOTE: do not `mkdir public/storage` — it is a symlink to storage/app/public
# (created in the image / by `storage:link`). mkdir -p would follow it and fail.

# Set proper permissions
chown -R www:www storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

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
    return 1
}

# Check if .env file exists, create from example if not
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env file from example..."
    if [ -f /var/www/html/.env.example ]; then
        cp /var/www/html/.env.example /var/www/html/.env
    fi
fi

# If an APP_KEY was provided (e.g. via the app_key Docker secret) persist it so
# config:cache below sees it; otherwise generate one when none is set.
if [ -n "${APP_KEY:-}" ]; then
    if grep -q "^APP_KEY=" /var/www/html/.env 2>/dev/null; then
        sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" /var/www/html/.env
    else
        echo "APP_KEY=${APP_KEY}" >> /var/www/html/.env
    fi
elif [ -z "$(grep "^APP_KEY=" /var/www/html/.env | cut -d '=' -f2)" ]; then
    echo "Generating application key..."
    php artisan key:generate --no-interaction --force 2>/dev/null || true
fi

# .env is created/written as root above; the fpm pool runs as www, so hand it
# over (and keep it writable) or runtime env writes (admin settings) would fail.
chown www:www /var/www/html/.env 2>/dev/null || true
chmod 664 /var/www/html/.env 2>/dev/null || true

# The production image ran `composer install --no-scripts`, so Laravel's package
# manifest hasn't been built yet. Do it now that .env/APP_KEY exist.
echo "Discovering packages..."
php artisan package:discover --ansi 2>/dev/null || true

# Wait for database
if [ -n "$DB_HOST" ]; then
    wait_for_db
fi

# Install Microweber on first boot, otherwise just migrate. `migrate` alone does
# NOT produce a working CMS (no admin user, no seeded module/template statuses,
# no default content) — the installer does. The marker lives on the persisted
# storage volume so a restart doesn't reinstall.
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
        echo "Microweber installed."
    else
        echo "WARNING: microweber:install failed — it will be retried on next start."
    fi
elif [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force --no-interaction 2>/dev/null || echo "Migration may have already run or database not ready"
fi

# Production optimization. Microweber is NOT compatible with Laravel's config/
# route/event caches, so we run only the safe precompiles (icons + views):
#   - config:cache  -> MW config holds non-serializable values (closures) -> throws
#   - route:cache / event:cache -> closure routes/listeners -> serializable-closure OOM
#   - view:cache    -> OK. Precompiles ALL Blade, Filament panel views included.
#                      This used to throw, but the cause was dead Blade views and
#                      a few providers registering non-existent resources/views
#                      dirs (now fixed), not Filament itself. `|| true` keeps a
#                      rare failure from blocking boot — the app renders uncached.
if [ "${APP_ENV:-production}" = "production" ]; then
    echo "Optimizing for production..."
    php artisan icons:cache 2>/dev/null || true
    php artisan view:cache || true
fi

# Create storage symlink
if [ ! -L /var/www/html/public/storage ]; then
    echo "Creating storage symlink..."
    php artisan storage:link --force --no-interaction 2>/dev/null || true
fi

# Warm cache if enabled
if [ "${CACHE_WARM:-false}" = "true" ]; then
    echo "Warming cache..."
    php artisan cache:warm 2>/dev/null || true
fi

# The artisan steps above ran as root and created root-owned files under
# storage/ (laravel.log, compiled views) and bootstrap/cache. The fpm pool runs
# as www, so hand those trees back or runtime logging/caching 500s (the log
# write itself throws). This is the final step before services start.
chown -R www:www storage bootstrap/cache 2>/dev/null || true

echo "=========================================="
echo "Starting services..."
echo "=========================================="

# Execute the command passed to the container
exec "$@"
