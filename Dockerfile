# Multi-stage Dockerfile for Microweber CMS
# Supports both development and production builds

# =============================================================================
# Stage 1: Base PHP image with dependencies
# =============================================================================
FROM php:8.4-fpm-alpine AS base

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
    docker-php-ext-install -j$(nproc) gd exif pdo pdo_mysql zip dom curl mbstring intl bcmath sodium opcache pcntl posix

# Install Redis extension
RUN pecl install redis && \
    docker-php-ext-enable redis

# Clean up
RUN apk del --no-cache $PHPIZE_DEPS && \
    rm -rf /tmp/* /var/cache/apk/*

# Create application user, and run the php-fpm pool as it (the default pool runs
# as www-data, but the app files + nginx are 'www' — a mismatch would stop fpm
# from writing storage/.env/userfiles at runtime).
RUN addgroup -g 1000 www && \
    adduser -u 1000 -G www -s /bin/sh -D www && \
    sed -i 's/^user = www-data/user = www/; s/^group = www-data/group = www/' \
        /usr/local/etc/php-fpm.d/www.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =============================================================================
# Stage 2: Asset + vendor builder
# =============================================================================
# Derives from `base` (so it has PHP + Composer) and adds Node, because the two
# runtime bundles we build depend on Composer deps: the Filament admin theme
# imports vendor/filament/filament/dist/theme.css. We deliberately do NOT run
# the repo-root `npm run build` (run-build.js), which walks ~200 sub-packages
# and is far too slow/fragile for an image build — only the public frontend
# (Vite) and the Filament theme (Webpack), both of which publish into
# public/vendor/microweber-packages/…. The production stage copies both the
# built public/vendor bundles and this stage's vendor/ (no second composer run).
FROM base AS asset-build

RUN apk add --no-cache nodejs npm

WORKDIR /var/www/html

# Whole tree so each bundle resolves its own sources + package.json.
COPY . .

# Composer deps first — the Filament theme build reads vendor/filament/…/theme.css.
# --no-scripts: package:discover needs a bootable app (.env), done at container start.
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts && \
    composer clear-cache

# Public frontend bundle (Vite) → public/vendor/microweber-packages/frontend-assets/build
RUN cd packages/frontend-assets \
    && (npm ci || npm install) \
    && npm run build

# Filament admin theme (Webpack) → public/vendor/microweber-packages/microweber-filament-theme/build
RUN cd packages/microweber-filament-theme \
    && (npm ci || npm install) \
    && npm run build

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
    echo 'memory_limit=1024M'; \
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

# Reuse the builder's Composer vendor/ (already --no-dev --optimize-autoloader)
# rather than running composer again here.
COPY --chown=www:www --from=asset-build /var/www/html/vendor ./vendor

# Overlay the built front-end bundles. Both the Vite frontend and the Webpack
# Filament theme publish under public/vendor/…, which is .gitignore'd (absent
# from a fresh checkout) so it MUST come from the builder stage.
COPY --chown=www:www --from=asset-build /var/www/html/public/vendor ./public/vendor

# Permissions. The COPY --chown lines above already own everything as www:www,
# so we do NOT `chown -R` the whole tree — a recursive chmod/chown here rewrites
# every file into a new layer, duplicating the entire codebase (~5 GB). Only the
# group-writable runtime dirs need adjusting.
# Normalize the project root: the base image ships /var/www/html as mode 1777
# (world-writable + sticky) owned www-data. With the kernel's fs.protected_regular
# hardening, that sticky world-writable dir blocks EVEN ROOT from writing files it
# doesn't own (e.g. the www-owned .env the installer must update) — so make it a
# plain www-owned 0755 dir. Non-recursive: recursing would duplicate the tree.
RUN chown www:www /var/www/html && chmod 0755 /var/www/html && \
    mkdir -p storage/app/public storage/framework/cache storage/framework/sessions \
             storage/framework/views bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache && \
    mkdir -p /var/log/supervisor && \
    chown -R www:www /var/log/supervisor storage/app bootstrap/cache

# Storage symlink. Force-recreate it (the committed one is a dev absolute path,
# and it's excluded from the context anyway) pointing at this container's path.
RUN rm -f public/storage && \
    ln -s /var/www/html/storage/app/public /var/www/html/public/storage

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

# Install Xdebug (build via a temporary virtual package: phpize/pecl needs
# autoconf + a C toolchain, which the runtime image doesn't ship — $PHPIZE_DEPS
# provides them. Removed again afterwards to keep the image slim.)
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apk del .build-deps

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
