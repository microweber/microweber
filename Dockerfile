# Multi-stage Dockerfile for Microweber CMS
# Supports both development and production builds

# =============================================================================
# Stage 1: Base PHP image with dependencies
# =============================================================================
FROM php:8.3-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    libxml2-dev \
    curl-dev \
    oniguruma-dev \
    libsodium-dev \
    icu-dev \
    linux-headers \
    $PHPIZE_DEPS

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip dom curl mbstring intl bcmath sodium opcache pcntl posix

# Install Redis extension
RUN pecl install redis && \
    docker-php-ext-enable redis

# Clean up
RUN apk del --no-cache $PHPIZE_DEPS && \
    rm -rf /tmp/* /var/cache/apk/*

# Create application user
RUN addgroup -g 1000 www && \
    adduser -u 1000 -G www -s /bin/sh -D www

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =============================================================================
# Stage 2: Build stage for Node assets
# =============================================================================
FROM node:20-alpine AS node-build

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci --production=false 2>/dev/null || npm install

# Copy source files for building
COPY . .

# Build frontend assets
RUN npm run build 2>/dev/null || echo "No build script found"

# =============================================================================
# Stage 3: Production image
# =============================================================================
FROM base AS production

# Production environment
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV PHP_OPCACHE_ENABLE=1
ENV PHP_OPCACHE_MEMORY_CONSUMPTION=256
ENV PHP_OPCACHE_MAX_ACCELERATED_FILES=20000
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0

# Install additional production dependencies
RUN apk add --no-cache \
    mysql-client \
    postgresql-client

# Configure PHP for production
RUN { \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'memory_limit=512M'; \
    echo 'upload_max_filesize=50M'; \
    echo 'post_max_size=50M'; \
    echo 'max_execution_time=300'; \
    echo 'max_input_vars=3000'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Copy Nginx configuration
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy Supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY --chown=www:www . .

# Copy built assets from node stage (if they exist)
# Note: These are optional - assets may be built to different locations
# The node build copies them to /app, we just copy what exists
COPY --chown=www:www --from=node-build /app/public/css ./public/css
COPY --chown=www:www --from=node-build /app/public/js ./public/js
COPY --chown=www:www --from=node-build /app/resources/assets/dist ./resources/assets/dist

# Install PHP dependencies (production only)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader && \
    composer clear-cache

# Set permissions
RUN chown -R www:www /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 storage bootstrap/cache && \
    mkdir -p /var/log/supervisor && \
    chown www:www /var/log/supervisor

# Create storage symlink
RUN if [ ! -L public/storage ]; then \
    ln -s /var/www/html/storage/app/public /var/www/html/public/storage; \
    fi

# Expose port
EXPOSE 80

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:80/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# =============================================================================
# Stage 4: Development image
# =============================================================================
FROM base AS development

# Development environment
ENV APP_ENV=local
ENV APP_DEBUG=true
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install development tools
RUN apk add --no-cache \
    mysql-client \
    postgresql-client \
    bash \
    vim \
    htop

# Install Xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Configure Xdebug
RUN { \
    echo 'xdebug.mode=develop,debug'; \
    echo 'xdebug.start_with_request=yes'; \
    echo 'xdebug.discover_client_host=1'; \
    echo 'xdebug.client_host=host.docker.internal'; \
    echo 'xdebug.client_port=9003'; \
    echo 'xdebug.idekey=PHPSTORM'; \
    } > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Configure PHP for development
RUN { \
    echo 'memory_limit=1G'; \
    echo 'upload_max_filesize=100M'; \
    echo 'post_max_size=100M'; \
    echo 'max_execution_time=600'; \
    echo 'max_input_vars=5000'; \
    echo 'display_errors=On'; \
    echo 'error_reporting=E_ALL'; \
    } > /usr/local/etc/php/conf.d/development.ini

# Copy Nginx configuration
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy Supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Create directories
RUN mkdir -p /var/log/supervisor && \
    mkdir -p storage/logs && \
    mkdir -p bootstrap/cache

# Copy development entrypoint
COPY docker/entrypoint-dev.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose ports
EXPOSE 80 9003

# Use development entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
