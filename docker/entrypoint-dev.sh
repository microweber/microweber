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
mkdir -p public/storage

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

# Install Node.js dependencies and build assets if needed
if [ ! -d /var/www/html/node_modules ] || [ -z "$(ls -A /var/www/html/node_modules 2>/dev/null)" ]; then
    echo "Installing Node.js dependencies..."
    npm ci 2>/dev/null || npm install 2>/dev/null || echo "Skipping npm install (no package.json or npm not available)"
fi

# Run npm build if package.json exists and build script is available
if [ -f /var/www/html/package.json ]; then
    if grep -q '"build"' /var/www/html/package.json 2>/dev/null; then
        echo "Building frontend assets..."
        npm run build 2>/dev/null || echo "Build completed or skipped"
    fi
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

# Run migrations
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force --no-interaction 2>/dev/null || echo "Migration may have already run or database not ready"
fi

# Publish assets
php artisan vendor:publish --tag=public --force --no-interaction 2>/dev/null || true
php artisan storage:link --force --no-interaction 2>/dev/null || true

echo "=========================================="
echo "Development environment ready!"
echo "=========================================="

# Execute the command passed to the container
exec "$@"
