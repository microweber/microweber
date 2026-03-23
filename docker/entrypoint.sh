#!/bin/sh
set -e

# Production Entrypoint Script for Microweber

echo "=========================================="
echo "Microweber Docker Entrypoint"
echo "Environment: ${APP_ENV:-production}"
echo "=========================================="

# Create required directories if they don't exist
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/testing
mkdir -p bootstrap/cache
mkdir -p public/storage

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

# Generate application key if not set
if [ -z "$(grep "^APP_KEY=" /var/www/html/.env | cut -d '=' -f2)" ]; then
    echo "Generating application key..."
    php artisan key:generate --no-interaction --force 2>/dev/null || true
fi

# Wait for database
if [ -n "$DB_HOST" ]; then
    wait_for_db
fi

# Run migrations if requested
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force --no-interaction 2>/dev/null || echo "Migration may have already run or database not ready"
fi

# Clear and cache config in production
if [ "${APP_ENV:-production}" = "production" ]; then
    echo "Optimizing for production..."
    php artisan config:cache 2>/dev/null || true
    php artisan route:cache 2>/dev/null || true
    php artisan view:cache 2>/dev/null || true
    php artisan event:cache 2>/dev/null || true
    php artisan icons:cache 2>/dev/null || true
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

echo "=========================================="
echo "Starting services..."
echo "=========================================="

# Execute the command passed to the container
exec "$@"
