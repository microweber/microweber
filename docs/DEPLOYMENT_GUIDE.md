# Microweber Deployment Guide

> **Comprehensive guide for deploying Microweber to production, staging, and development environments**

---

## Table of Contents

1. [Overview](#overview)
2. [System Requirements](#system-requirements)
3. [Server Requirements](#server-requirements)
4. [Pre-Installation](#pre-installation)
5. [Installation Methods](#installation-methods)
6. [Production Configuration](#production-configuration)
7. [Web Server Configuration](#web-server-configuration)
8. [SSL/TLS Configuration](#ssltls-configuration)
9. [Database Configuration](#database-configuration)
10. [Queue Workers Setup](#queue-workers-setup)
11. [Caching Configuration](#caching-configuration)
12. [Monitoring and Logging](#monitoring-and-logging)
13. [Security Best Practices](#security-best-practices)
14. [Troubleshooting](#troubleshooting)
15. [Docker Deployment](#docker-deployment)

---

## Overview

This guide covers deploying Microweber in various environments, from shared hosting to enterprise-scale production servers. Microweber is a Laravel-based CMS requiring PHP 8.3+, a web server (Apache/Nginx), and a database (MySQL/MariaDB/PostgreSQL/SQLite).

### Deployment Environments

- **Development**: Local development with SQLite, minimal configuration
- **Staging**: Pre-production testing with production-like configuration
- **Production**: Live environment with full optimization and security

---

## System Requirements

### Minimum Requirements

| Component | Minimum | Recommended |
|-----------|---------|-------------|
| PHP | 8.3 | 8.3+ |
| Memory | 512MB | 1GB+ |
| Disk Space | 1GB | 5GB+ |
| Database | MySQL 5.7+ / MariaDB 10.3+ / PostgreSQL 12+ / SQLite 3 | MySQL 8.0+ / MariaDB 10.6+ |
| Web Server | Apache 2.4+ / Nginx 1.18+ | Apache 2.4+ with mod_rewrite / Nginx 1.20+ |

### PHP Extensions Required

```bash
# Core extensions
php8.3-bcmath      # For financial calculations
php8.3-bz2         # Compression support
php8.3-curl        # HTTP requests
php8.3-dom         # XML/DOM processing
php8.3-fileinfo    # File type detection
php8.3-gd          # Image processing
php8.3-intl        # Internationalization
php8.3-mbstring    # Multibyte string handling
php8.3-mysql       # MySQL database driver
php8.3-opcache     # Opcode caching
php8.3-sqlite3     # SQLite database driver
php8.3-xmlrpc      # XML-RPC support
php8.3-zip         # ZIP archive handling
php8.3-sodium      # Encryption
php8.3-openssl     # SSL/TLS support
```

### Ubuntu/Debian Installation

```bash
# Install PHP 8.3 and all required extensions
sudo apt update
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update

sudo apt install php8.3-{bcmath,bz2,curl,dom,fileinfo,gd,intl,mbstring,mysql,opcache,sqlite3,xmlrpc,zip,sodium,openssl}
```

### CentOS/RHEL Installation

```bash
# Install Remi repository
sudo dnf install https://dl.fedoraproject.org/pub/epel/epel-release-latest-8.noarch.rpm
sudo dnf install https://rpms.remirepo.net/enterprise/remi-release-8.rpm

# Enable PHP 8.3
sudo dnf module reset php
sudo dnf module enable php:remi-8.3

# Install PHP and extensions
sudo dnf install php php-{bcmath,bz2,curl,dom,fileinfo,gd,intl,mbstring,mysqlnd,opcache,sqlite3,xmlrpc,zip,sodium,openssl}
```

---

## Server Requirements

### Hardware Specifications

#### Small Sites (Under 10,000 visits/month)

- **CPU**: 1 core
- **RAM**: 1GB
- **Storage**: 10GB SSD
- **Bandwidth**: 100GB/month

#### Medium Sites (10,000 - 100,000 visits/month)

- **CPU**: 2 cores
- **RAM**: 2-4GB
- **Storage**: 50GB SSD
- **Bandwidth**: 500GB/month
- **Database**: Dedicated MySQL/MariaDB instance

#### Large Sites (100,000+ visits/month)

- **CPU**: 4+ cores
- **RAM**: 8GB+
- **Storage**: 100GB+ SSD
- **Bandwidth**: 2TB+/month
- **Database**: Separate database server or managed service
- **Cache**: Redis server
- **CDN**: CloudFlare or similar

### Network Requirements

- **Port 80**: HTTP (redirects to HTTPS in production)
- **Port 443**: HTTPS (required for production)
- **Port 22**: SSH (for server management)
- **Port 3306**: MySQL (if external database)
- **Port 6379**: Redis (if external cache)

---

## Pre-Installation

### 1. Create System User

```bash
# Create dedicated user for Microweber
sudo useradd -m -s /bin/bash microweber
sudo usermod -aG www-data microweber

# Set password
sudo passwd microweber
```

### 2. Configure File Permissions

```bash
# Create application directory
sudo mkdir -p /var/www/microweber
sudo chown -R microweber:www-data /var/www/microweber
sudo chmod -R 755 /var/www/microweber

# Set ACL for proper web server access (optional but recommended)
sudo apt install acl
sudo setfacl -R -m u:www-data:rwx /var/www/microweber
sudo setfacl -dR -m u:www-data:rwx /var/www/microweber
```

### 3. Install Composer

```bash
# Download and install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Verify installation
composer --version
```

### 4. Install Node.js and NPM

```bash
# Using NodeSource repository (recommended)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Verify installation
node --version
npm --version
```

### 5. Database Setup

#### MySQL/MariaDB

```bash
# Install MySQL/MariaDB
sudo apt install mysql-server

# Secure installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p

CREATE DATABASE microweber CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'microweber'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON microweber.* TO 'microweber'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### PostgreSQL

```bash
# Install PostgreSQL
sudo apt install postgresql postgresql-contrib

# Create database and user
sudo -u postgres psql

CREATE DATABASE microweber;
CREATE USER microweber WITH PASSWORD 'your_secure_password';
GRANT ALL PRIVILEGES ON DATABASE microweber TO microweber;
\q
```

---

## Installation Methods

### Method 1: Composer Create-Project (Recommended)

```bash
# Navigate to web root
cd /var/www

# Create project
sudo -u microweber composer create-project microweber/microweber:dev-master microweber

# Navigate to project
cd microweber

# Install NPM dependencies
npm install

# Build assets
npm run build

# Set permissions
sudo chown -R microweber:www-data /var/www/microweber
sudo chmod -R 755 /var/www/microweber
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/
```

### Method 2: Git Clone

```bash
# Clone repository
cd /var/www
sudo -u microweber git clone https://github.com/microweber/microweber.git

# Navigate to project
cd microweber

# Install PHP dependencies
sudo -u microweber composer install --no-dev --optimize-autoloader

# Install NPM dependencies
npm install

# Build assets
npm run build

# Set permissions
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/
```

### Method 3: Download Archive

```bash
# Download latest release
cd /var/www
wget https://github.com/microweber/microweber/archive/refs/heads/dev.zip
unzip dev.zip
mv microweber-dev microweber
rm dev.zip

# Navigate to project
cd microweber

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### Method 4: Shared Hosting (FTP/SFTP)

1. Download the latest release from GitHub
2. Extract locally
3. Run `composer install` locally (or download the pre-built release)
4. Upload files to your hosting account via FTP/SFTP
5. Create database using hosting control panel
6. Navigate to your site and run the web installer

---

## Production Configuration

### Environment Configuration

Copy the example environment file and configure for production:

```bash
cp .env.example .env
```

Edit `.env` with production values:

```env
# Application
APP_NAME="Your Site Name"
APP_ENV=production
APP_KEY=base64:your_generated_key_here
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Localization
APP_LOCALE=en
APP_FALLBACK_LOCALE=en

# Security
BCRYPT_ROUNDS=12

# Logging
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning

# Database (MySQL/MariaDB)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=microweber
DB_USERNAME=microweber
DB_PASSWORD=your_secure_password

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=.yourdomain.com

# Broadcasting
BROADCAST_CONNECTION=log

# Filesystem
FILESYSTEM_DISK=local

# Queue (Production)
QUEUE_CONNECTION=database
DB_QUEUE_TABLE=jobs
DB_QUEUE_QUEUE=default
DB_QUEUE_RETRY_AFTER=90

# Cache (Production)
CACHE_STORE=redis
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1
REDIS_SESSION_DB=2
REDIS_QUEUE_DB=3

# Mail
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# Media Upload
MW_UPLOAD_LIMIT_IMAGES=10240
MW_UPLOAD_LIMIT_VIDEOS=102400
MW_UPLOAD_VALIDATE_MIME=true
MW_UPLOAD_BLOCK_DANGEROUS=true

# Advanced Page Cache
PAGE_CACHE_ENABLED=true
PAGE_CACHE_TTL=3600
PAGE_CACHE_DRIVER=redis
PAGE_CACHE_LOGGED_IN=false

# Fragment Cache
FRAGMENT_CACHE_ENABLED=true
FRAGMENT_CACHE_TTL=3600
FRAGMENT_CACHE_DRIVER=redis
```

### Generate Application Key

```bash
php artisan key:generate
```

### Run Migrations

```bash
php artisan migrate --force
```

### Optimize for Production

```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Cache icons (Filament)
php artisan icons:cache

# Clear old caches
php artisan optimize:clear
php artisan optimize
```

---

## Web Server Configuration

### Apache Configuration

Create a virtual host configuration:

```apache
# /etc/apache2/sites-available/microweber.conf
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/microweber/public

    <Directory /var/www/microweber/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Security headers
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"

    # Logging
    ErrorLog ${APACHE_LOG_DIR}/microweber-error.log
    CustomLog ${APACHE_LOG_DIR}/microweber-access.log combined
</VirtualHost>
```

Enable required modules and site:

```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod ssl
sudo a2ensite microweber.conf
sudo systemctl restart apache2
```

### Nginx Configuration

Create a server block:

```nginx
# /etc/nginx/sites-available/microweber
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/microweber/public;
    index index.php index.html;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Client body size for uploads
    client_max_body_size 100M;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript image/svg+xml;

    # Deny access to sensitive directories
    location ~ /(vendor|src|config|database|bootstrap|storage|app|routes|.git|.env) {
        deny all;
        return 404;
    }

    # Process PHP files
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Laravel's rewrite rules
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Logging
    access_log /var/log/nginx/microweber-access.log;
    error_log /var/log/nginx/microweber-error.log;
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/microweber /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## SSL/TLS Configuration

### Using Let's Encrypt (Certbot)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache  # For Apache
# OR
sudo apt install certbot python3-certbot-nginx   # For Nginx

# Obtain and install certificate (Apache)
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Obtain and install certificate (Nginx)
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal test
sudo certbot renew --dry-run
```

### Manual SSL Configuration (Apache)

```apache
<VirtualHost *:443>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/microweber/public

    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    SSLCertificateChainFile /path/to/ca_bundle.crt

    <Directory /var/www/microweber/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Security headers
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains" always
</VirtualHost>

# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    Redirect permanent / https://yourdomain.com/
</VirtualHost>
```

### Manual SSL Configuration (Nginx)

```nginx
# HTTP to HTTPS redirect
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

# HTTPS server
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/microweber/public;
    index index.php;

    # SSL certificates
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    ssl_trusted_certificate /path/to/ca_bundle.crt;

    # SSL configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 1d;
    ssl_session_tickets off;

    # HSTS
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Rest of configuration...
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## Database Configuration

### MySQL/MariaDB Optimization

Edit `/etc/mysql/mysql.conf.d/mysqld.cnf`:

```ini
[mysqld]
# Performance settings
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
query_cache_type = 1
query_cache_size = 64M
max_connections = 200

# Character set
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

# Slow query log
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2
```

Restart MySQL:

```bash
sudo systemctl restart mysql
```

### Database Backup

Create a backup script:

```bash
# /usr/local/bin/backup-microweber-db.sh
#!/bin/bash

BACKUP_DIR="/var/backups/microweber"
DB_NAME="microweber"
DB_USER="microweber"
DB_PASS="your_password"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Create backup
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/${DB_NAME}_${DATE}.sql.gz

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql.gz" -mtime +7 -delete
```

Schedule with cron:

```bash
# Edit crontab
sudo crontab -e

# Add daily backup at 2 AM
0 2 * * * /usr/local/bin/backup-microweber-db.sh >> /var/log/microweber-backup.log 2>&1
```

---

## Queue Workers Setup

### Using Supervisor (Recommended)

Install and configure Supervisor:

```bash
sudo apt install supervisor
```

Create configuration file:

```ini
# /etc/supervisor/conf.d/microweber-workers.conf
[program:microweber-worker]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php /var/www/microweber/artisan queue:work --sleep=3 --tries=3 --max-jobs=100 --max-time=3600
directory=/var/www/microweber
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/microweber/storage/logs/worker.log
stopwaitsecs=3600
```

Start workers:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start microweber-worker:*
```

### Using Systemd

Create service file:

```ini
# /etc/systemd/system/microweber-worker.service
[Unit]
Description=Microweber Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/microweber/artisan queue:work --sleep=3 --tries=3
WorkingDirectory=/var/www/microweber

[Install]
WantedBy=multi-user.target
```

Enable and start:

```bash
sudo systemctl enable microweber-worker
sudo systemctl start microweber-worker
sudo systemctl status microweber-worker
```

---

## Caching Configuration

### Redis Installation

```bash
# Install Redis
sudo apt install redis-server

# Configure Redis
sudo nano /etc/redis/redis.conf

# Set password (optional but recommended)
requirepass your_redis_password

# Bind to localhost only
bind 127.0.0.1

# Restart
sudo systemctl restart redis-server
```

### Configure Microweber for Redis

Update `.env`:

```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=your_redis_password
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1
REDIS_SESSION_DB=2
REDIS_QUEUE_DB=3
```

### OPcache Configuration

Edit `/etc/php/8.3/fpm/conf.d/10-opcache.ini`:

```ini
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
opcache.validate_timestamps=0
```

---

## Monitoring and Logging

### Log Rotation

Create logrotate configuration:

```bash
# /etc/logrotate.d/microweber
/var/www/microweber/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    sharedscripts
    postrotate
        /usr/bin/php /var/www/microweber/artisan queue:restart > /dev/null 2>&1 || true
    endscript
}
```

### Application Monitoring

Use Laravel Telescope for debugging:

```bash
# Install Telescope (already included in Microweber)
php artisan telescope:publish

# Access at /telescope (protected by admin auth)
```

### Health Checks

Add to monitoring system:

```bash
# Check application is responding
curl -f https://yourdomain.com/health-check || echo "Site down"

# Check queue status
php artisan queue:monitor database

# Check disk space
df -h /var/www/microweber

# Check memory usage
free -m
```

---

## Security Best Practices

### File Permissions

```bash
# Set correct ownership
sudo chown -R microweber:www-data /var/www/microweber

# Set directory permissions
sudo find /var/www/microweber -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/microweber -type f -exec chmod 644 {} \;

# Writable directories
sudo chmod -R 775 /var/www/microweber/storage
sudo chmod -R 775 /var/www/microweber/bootstrap/cache
sudo chmod -R 775 /var/www/microweber/userfiles

# Protect sensitive files
sudo chmod 600 /var/www/microweber/.env
```

### Security Headers

Ensure these headers are configured in your web server:

```
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
Strict-Transport-Security: max-age=31536000; includeSubDomains
Content-Security-Policy: default-src 'self'
```

### Regular Updates

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Update PHP packages
sudo apt install --only-upgrade php8.3-*

# Update Microweber dependencies
sudo -u microweber composer update --no-dev
npm update
npm run build

# Security audit
composer audit
npm audit
```

### Firewall Configuration

```bash
# Allow necessary ports
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

---

## Troubleshooting

### Common Issues

#### 500 Internal Server Error

```bash
# Check Laravel logs
tail -f /var/www/microweber/storage/logs/laravel.log

# Check permissions
sudo chown -R www-data:www-data /var/www/microweber/storage
sudo chmod -R 775 /var/www/microweber/storage

# Clear caches
php artisan optimize:clear
```

#### Database Connection Issues

```bash
# Test database connection
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Connected successfully';"

# Check MySQL status
sudo systemctl status mysql

# Verify credentials in .env
grep DB_ .env
```

#### File Upload Issues

```bash
# Check PHP upload limits
php -r "echo 'Upload max: ' . ini_get('upload_max_filesize') . PHP_EOL; echo 'Post max: ' . ini_get('post_max_size');"

# Update PHP configuration in /etc/php/8.3/fpm/php.ini
upload_max_filesize = 100M
post_max_size = 100M
memory_limit = 256M

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm
```

#### Permission Denied Errors

```bash
# Reset permissions
sudo chown -R microweber:www-data /var/www/microweber
sudo find /var/www/microweber -type f -exec chmod 644 {} \;
sudo find /var/www/microweber -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/microweber/storage
sudo chmod -R 775 /var/www/microweber/bootstrap/cache
```

#### Queue Workers Not Processing

```bash
# Check queue status
php artisan queue:monitor database

# View failed jobs
php artisan queue:failed

# Restart workers
sudo supervisorctl restart microweber-worker:*
# OR
sudo systemctl restart microweber-worker
```

### Performance Optimization

```bash
# Enable OPcache
php -r "var_dump(ini_get('opcache.enable'));"

# Configure Redis for caching
# Edit .env and set CACHE_STORE=redis

# Optimize autoloader
composer dump-autoload --optimize

# Cache routes and views
php artisan optimize
```

---

## Docker Deployment

### Using Docker Compose

Microweber includes a `docker-compose.yml` for easy local development:

```bash
# Start containers
docker-compose up -d

# View logs
docker-compose logs -f

# Run artisan commands
docker-compose exec php-apache php artisan migrate

# Stop containers
docker-compose down
```

### Production Docker Setup

Create a production-ready Docker Compose configuration:

```yaml
# docker-compose.prod.yml
version: '3.8'

services:
  app:
    image: microweber/microweber:latest
    container_name: microweber-app
    restart: unless-stopped
    volumes:
      - ./storage:/var/www/html/storage
      - ./userfiles:/var/www/html/userfiles
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - DB_CONNECTION=mysql
      - DB_HOST=db
      - DB_DATABASE=microweber
      - DB_USERNAME=microweber
      - DB_PASSWORD=${DB_PASSWORD}
      - REDIS_HOST=redis
      - QUEUE_CONNECTION=redis
      - CACHE_STORE=redis
    depends_on:
      - db
      - redis
    networks:
      - microweber

  web:
    image: nginx:alpine
    container_name: microweber-web
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf:ro
      - ./ssl:/etc/nginx/ssl:ro
    depends_on:
      - app
    networks:
      - microweber

  db:
    image: mysql:8.0
    container_name: microweber-db
    restart: unless-stopped
    environment:
      - MYSQL_DATABASE=microweber
      - MYSQL_USER=microweber
      - MYSQL_PASSWORD=${DB_PASSWORD}
      - MYSQL_ROOT_PASSWORD=${DB_ROOT_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - microweber

  redis:
    image: redis:alpine
    container_name: microweber-redis
    restart: unless-stopped
    command: redis-server --appendonly yes
    volumes:
      - redis_data:/data
    networks:
      - microweber

  queue:
    image: microweber/microweber:latest
    container_name: microweber-queue
    restart: unless-stopped
    command: php artisan queue:work --sleep=3 --tries=3
    environment:
      - APP_ENV=production
      - DB_CONNECTION=mysql
      - DB_HOST=db
      - REDIS_HOST=redis
    depends_on:
      - db
      - redis
    networks:
      - microweber

volumes:
  db_data:
  redis_data:

networks:
  microweber:
    driver: bridge
```

Deploy:

```bash
# Build and start
docker-compose -f docker-compose.prod.yml up -d

# Run migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Scale queue workers
docker-compose -f docker-compose.prod.yml up -d --scale queue=3
```

---

## Post-Deployment Checklist

- [ ] Application accessible via HTTPS
- [ ] Admin panel login working
- [ ] Database migrations complete
- [ ] Queue workers running
- [ ] Redis/cache configured
- [ ] SSL certificate valid
- [ ] File permissions correct
- [ ] Environment variables set
- [ ] Backups scheduled
- [ ] Monitoring configured
- [ ] Security headers present
- [ ] Log rotation active
- [ ] Firewall configured
- [ ] Health checks passing

---

## Support

For additional help:

- [Microweber Documentation](https://microweber.com/docs)
- [GitHub Issues](https://github.com/microweber/microweber/issues)
- [Discord Community](https://discord.gg/Bsue9ey)

---

**Last Updated**: 2026-03-23

**Version**: Microweber 2.0 (Laravel 11)
