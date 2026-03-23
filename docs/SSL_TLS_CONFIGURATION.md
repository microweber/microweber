# SSL/TLS Configuration Guide

> **Complete guide for securing Microweber with HTTPS, covering certificate setup, server configuration, and best practices**

---

## Table of Contents

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [Certificate Options](#certificate-options)
4. [Let's Encrypt Setup](#lets-encrypt-setup)
5. [Manual Certificate Installation](#manual-certificate-installation)
6. [Apache Configuration](#apache-configuration)
7. [Nginx Configuration](#nginx-configuration)
8. [Laravel/PHP Configuration](#laravelphp-configuration)
9. [Certificate Renewal](#certificate-renewal)
10. [Troubleshooting](#troubleshooting)
11. [Security Best Practices](#security-best-practices)
12. [Advanced Topics](#advanced-topics)

---

## Overview

SSL/TLS (Secure Sockets Layer / Transport Layer Security) encrypts data transmitted between your Microweber site and its visitors. This guide covers all aspects of HTTPS setup for production Microweber deployments.

### Why HTTPS is Required

- **Security**: Encrypts sensitive data (login credentials, payment information, personal data)
- **SEO**: Google ranks HTTPS sites higher in search results
- **Trust**: Browser padlock icon builds user confidence
- **Compliance**: Required for PCI DSS, GDPR, and other regulations
- **Features**: Required for Service Workers, geolocation, camera access, and modern web APIs

### Supported Protocols

- **TLS 1.3**: Recommended (latest, most secure)
- **TLS 1.2**: Supported for legacy compatibility
- **TLS 1.0/1.1**: Disabled (deprecated, insecure)

---

## Prerequisites

### System Requirements

- Server with root/sudo access
- Domain name pointed to your server (A/AAAA records)
- Ports 80 and 443 accessible
- Web server (Apache or Nginx) installed and running

### Verify Domain Configuration

```bash
# Check domain resolves to your server
dig +short yourdomain.com
# Should return your server's IP address

# Verify port accessibility
nc -zv yourdomain.com 80
nc -zv yourdomain.com 443
```

---

## Certificate Options

### Free Certificates

| Provider | Type | Validity | Auto-Renewal | Best For |
|----------|------|----------|--------------|----------|
| Let's Encrypt | Domain Validated (DV) | 90 days | Yes | Most sites |
| ZeroSSL | Domain Validated (DV) | 90 days | Yes | Alternative to Let's Encrypt |
| Cloudflare Origin | Origin CA | 15 years | No | Behind Cloudflare CDN |

### Paid Certificates

| Type | Validation | Best For |
|------|------------|----------|
| Domain Validated (DV) | Domain ownership | Standard websites |
| Organization Validated (OV) | Organization identity | Business sites |
| Extended Validation (EV) | Extended verification | Banks, high-security |
| Wildcard | Domain + subdomains | Multi-subdomain sites |
| SAN (Subject Alternative Name) | Multiple domains | Multiple domain sites |

---

## Let's Encrypt Setup

Let's Encrypt is the recommended choice for most Microweber deployments - free, automated, and widely trusted.

### Install Certbot

#### Ubuntu/Debian

```bash
# Update package index
sudo apt update

# Install Certbot with web server plugin
# For Apache:
sudo apt install certbot python3-certbot-apache

# For Nginx:
sudo apt install certbot python3-certbot-nginx
```

#### CentOS/RHEL

```bash
# Enable EPEL repository
sudo dnf install epel-release

# Install Certbot
sudo dnf install certbot

# Install web server plugin
# For Apache:
sudo dnf install python3-certbot-apache

# For Nginx:
sudo dnf install python3-certbot-nginx
```

### Obtain Certificate

#### Automatic Configuration (Recommended)

```bash
# For Apache - automatically configures virtual host
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# For Nginx - automatically configures server block
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

#### Manual Configuration

```bash
# Obtain certificate without web server configuration
sudo certbot certonly --standalone -d yourdomain.com -d www.yourdomain.com

# Or using webroot (if server is already running)
sudo certbot certonly --webroot -w /var/www/microweber/public -d yourdomain.com
```

### Certificate Location

Let's Encrypt stores certificates in:

```
/etc/letsencrypt/live/yourdomain.com/
├── cert.pem       # Server certificate
├── chain.pem      # Intermediate certificates
├── fullchain.pem  # Server cert + intermediates
└── privkey.pem    # Private key
```

### Auto-Renewal

Certbot sets up automatic renewal via systemd timer:

```bash
# Test renewal process
sudo certbot renew --dry-run

# Check renewal timer status
sudo systemctl status certbot.timer

# View renewal logs
sudo tail -f /var/log/letsencrypt/letsencrypt.log
```

**Important**: The renewal hook should restart your web server:

```bash
# Create renewal hook
sudo mkdir -p /etc/letsencrypt/renewal-hooks/deploy
sudo tee /etc/letsencrypt/renewal-hooks/deploy/reload-server.sh << 'EOF'
#!/bin/bash
# Reload web server after certificate renewal
systemctl reload apache2 || systemctl reload nginx
EOF

sudo chmod +x /etc/letsencrypt/renewal-hooks/deploy/reload-server.sh
```

---

## Manual Certificate Installation

### Using Cloudflare Origin CA

If using Cloudflare CDN:

1. Go to Cloudflare Dashboard → SSL/TLS → Origin Server
2. Click "Create Certificate"
3. Choose RSA or ECC key type
4. Copy certificate and private key
5. Save to server:

```bash
# Create directory for certificates
sudo mkdir -p /etc/ssl/microweber

# Save certificate
sudo nano /etc/ssl/microweber/origin-cert.pem
# Paste certificate content

# Save private key
sudo nano /etc/ssl/microweber/origin-key.pem
# Paste private key content

# Set permissions
sudo chmod 600 /etc/ssl/microweber/*.pem
sudo chown root:root /etc/ssl/microweber/*.pem
```

### Using Commercial Certificate

```bash
# Upload certificate files to server
# Typically you receive:
# - certificate.crt (your certificate)
# - ca_bundle.crt (intermediate certificates)
# - private.key (private key)

# Create certificate directory
sudo mkdir -p /etc/ssl/microweber

# Copy files
sudo cp certificate.crt /etc/ssl/microweber/
sudo cp ca_bundle.crt /etc/ssl/microweber/
sudo cp private.key /etc/ssl/microweber/

# Combine certificate and chain
sudo cat /etc/ssl/microweber/certificate.crt \
          /etc/ssl/microweber/ca_bundle.crt > \
          /etc/ssl/microweber/fullchain.crt

# Set secure permissions
sudo chmod 600 /etc/ssl/microweber/private.key
sudo chmod 644 /etc/ssl/microweber/*.crt
```

---

## Apache Configuration

### Complete SSL Virtual Host

```apache
# /etc/apache2/sites-available/microweber-ssl.conf

# HTTP to HTTPS redirect
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    
    # Redirect all HTTP to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}$1 [R=301,L]
</VirtualHost>

# HTTPS Virtual Host
<VirtualHost *:443>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/microweber/public
    
    # Enable SSL
    SSLEngine on
    
    # Certificate paths (Let's Encrypt)
    SSLCertificateFile /etc/letsencrypt/live/yourdomain.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/yourdomain.com/privkey.pem
    
    # For manual/commercial certificates:
    # SSLCertificateFile /etc/ssl/microweber/fullchain.crt
    # SSLCertificateKeyFile /etc/ssl/microweber/private.key
    
    # Modern SSL configuration
    SSLProtocol all -SSLv3 -TLSv1 -TLSv1.1
    SSLCipherSuite ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384
    SSLHonorCipherOrder off
    SSLSessionTickets off
    
    # Enable HTTP/2
    Protocols h2 http/1.1
    
    # Security headers
    Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload" always
    Header always set X-Frame-Options "SAMEORIGIN" always
    Header always set X-Content-Type-Options "nosniff" always
    Header always set X-XSS-Protection "1; mode=block" always
    Header always set Referrer-Policy "strict-origin-when-cross-origin" always
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()" always
    
    # Directory configuration
    <Directory /var/www/microweber/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
        
        # Deny access to sensitive files
        <FilesMatch "^\.">
            Require all denied
        </FilesMatch>
        
        <FilesMatch "\.(env|ini|log|git)">
            Require all denied
        </FilesMatch>
    </Directory>
    
    # PHP-FPM configuration
    <FilesMatch \.php$>
        SetHandler "proxy:unix:/var/run/php/php8.3-fpm.sock|fcgi://localhost"
    </FilesMatch>
    
    # Logging
    ErrorLog ${APACHE_LOG_DIR}/microweber-error.log
    CustomLog ${APACHE_LOG_DIR}/microweber-access.log combined
</VirtualHost>
```

### Enable Required Modules

```bash
# Enable SSL module
sudo a2enmod ssl

# Enable HTTP/2 module
sudo a2enmod http2

# Enable headers module (for security headers)
sudo a2enmod headers

# Enable rewrite module
sudo a2enmod rewrite

# Enable proxy for PHP-FPM
sudo a2enmod proxy proxy_fcgi

# Disable SSLv3 (insecure)
sudo a2dismod ssl3

# Restart Apache
sudo systemctl restart apache2
```

### Enable Site Configuration

```bash
# Disable default site
sudo a2dissite 000-default

# Enable Microweber SSL site
sudo a2ensite microweber-ssl

# Test configuration
sudo apache2ctl configtest

# Reload Apache
sudo systemctl reload apache2
```

---

## Nginx Configuration

### Complete SSL Server Block

```nginx
# /etc/nginx/sites-available/microweber

# HTTP to HTTPS redirect
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    
    # ACME challenge for Let's Encrypt
    location /.well-known/acme-challenge/ {
        root /var/www/certbot;
    }
    
    # Redirect all HTTP to HTTPS
    location / {
        return 301 https://$server_name$request_uri;
    }
}

# HTTPS server
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/microweber/public;
    index index.php index.html;
    
    # SSL certificates (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    
    # For manual/commercial certificates:
    # ssl_certificate /etc/ssl/microweber/fullchain.crt;
    # ssl_certificate_key /etc/ssl/microweber/private.key;
    
    # SSL configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    
    # SSL session caching
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 1d;
    ssl_session_tickets off;
    
    # OCSP Stapling
    ssl_stapling on;
    ssl_stapling_verify on;
    ssl_trusted_certificate /etc/letsencrypt/live/yourdomain.com/chain.pem;
    resolver 8.8.8.8 8.8.4.4 valid=300s;
    resolver_timeout 5s;
    
    # Security headers
    add_header Strict-Transport-Security "max-age=63072000; includeSubDomains; preload" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;
    
    # Logging
    access_log /var/log/nginx/microweber-access.log;
    error_log /var/log/nginx/microweber-error.log;
    
    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml application/json application/javascript application/xml+rss application/atom+rss image/svg+xml;
    
    # Client limits
    client_max_body_size 50M;
    client_body_timeout 300s;
    
    # Laravel/Microweber location blocks
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # PHP-FPM configuration
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Increase timeouts for long-running requests
        fastcgi_read_timeout 300s;
        fastcgi_send_timeout 300s;
    }
    
    # Deny access to hidden files
    location ~ /\. {
        deny all;
    }
    
    # Deny access to sensitive files
    location ~* \.(env|ini|log|git)$ {
        deny all;
    }
    
    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### Enable Site Configuration

```bash
# Create symbolic link to sites-enabled
sudo ln -s /etc/nginx/sites-available/microweber /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

---

## Laravel/PHP Configuration

### Environment Configuration

Update your `.env` file:

```env
# Force HTTPS in production
APP_URL=https://yourdomain.com

# Session cookie secure flag
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict

# CSRF token security
CSRF_TOKEN_SAMESITE=strict
```

### Trust Proxies

If using Cloudflare or a load balancer:

```php
// app/Http/Middleware/TrustProxies.php

<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = [
        '173.245.48.0/20',
        '103.21.244.0/22',
        '103.22.200.0/22',
        '103.31.4.0/22',
        '141.101.64.0/18',
        '108.162.192.0/18',
        '190.93.240.0/20',
        '188.114.96.0/20',
        '197.234.240.0/22',
        '198.41.128.0/17',
        '162.158.0.0/15',
        '104.16.0.0/13',
        '104.24.0.0/14',
        '172.64.0.0/13',
        '131.0.72.0/22',
    ];

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR |
                           Request::HEADER_X_FORWARDED_HOST |
                           Request::HEADER_X_FORWARDED_PORT |
                           Request::HEADER_X_FORWARDED_PROTO |
                           Request::HEADER_X_FORWARDED_AWS_ELB;
}
```

### Force HTTPS in Laravel

Add to `app/Providers/AppServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
```

### Middleware for HTTPS Enforcement

Create a middleware:

```php
// app/Http/Middleware/ForceHttps.php

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->secure() && app()->environment('production')) {
            return redirect()->secure($request->getRequestUri());
        }
        
        return $next($request);
    }
}
```

Register in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(App\Http\Middleware\ForceHttps::class);
})
```

---

## Certificate Renewal

### Automatic Renewal (Certbot)

Certbot sets up systemd timers automatically. Verify:

```bash
# Check timer status
sudo systemctl list-timers --all | grep certbot

# View renewal configuration
sudo cat /etc/letsencrypt/renewal/yourdomain.conf

# Test renewal
sudo certbot renew --dry-run
```

### Manual Renewal

```bash
# Renew all certificates
sudo certbot renew

# Force renewal (even if not expired)
sudo certbot renew --force-renewal

# Renew specific certificate
sudo certbot certonly --force-renewal -d yourdomain.com
```

### Renewal Hooks

Create post-renewal hooks to restart services:

```bash
# Create reload hook
sudo tee /etc/letsencrypt/renewal-hooks/deploy/reload-services.sh << 'EOF'
#!/bin/bash

# Reload web server
if systemctl is-active --quiet apache2; then
    systemctl reload apache2
    echo "Apache reloaded"
fi

if systemctl is-active --quiet nginx; then
    systemctl reload nginx
    echo "Nginx reloaded"
fi

# Clear Laravel cache
if [ -f /var/www/microweber/artisan ]; then
    cd /var/www/microweber
    php artisan optimize:clear
    echo "Laravel cache cleared"
fi
EOF

sudo chmod +x /etc/letsencrypt/renewal-hooks/deploy/reload-services.sh
```

---

## Troubleshooting

### Certificate Verification Failed

```bash
# Check certificate details
openssl s_client -connect yourdomain.com:443 -servername yourdomain.com

# Verify certificate chain
openssl s_client -connect yourdomain.com:443 -showcerts

# Check certificate expiration
echo | openssl s_client -servername yourdomain.com -connect yourdomain.com:443 2>/dev/null | openssl x509 -noout -dates
```

### Mixed Content Errors

Browser console shows "Mixed content" warnings:

**Solution**: Update Microweber to use HTTPS URLs:

```env
# Update .env
APP_URL=https://yourdomain.com

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Certificate Not Trusted

```bash
# Check intermediate certificates
openssl s_client -connect yourdomain.com:443

# Verify fullchain is being served
openssl crl2pkcs7 -nocrl -certfile /etc/letsencrypt/live/yourdomain.com/fullchain.pem | openssl pkcs7 -print_certs -noout
```

### Let's Encrypt Rate Limits

Let's Encrypt has rate limits:
- **20 certificates per domain per week**
- **5 duplicate certificates per week**
- **300 new registrations per IP per 3 hours**

**Solution**: Use staging environment for testing:

```bash
# Use staging server (no rate limits)
sudo certbot --staging --apache -d yourdomain.com
```

### Port 443 Not Accessible

```bash
# Check firewall status
sudo ufw status

# Open port 443
sudo ufw allow 443/tcp
sudo ufw allow 'Nginx Full'

# Check if port is listening
sudo netstat -tlnp | grep 443
sudo ss -tlnp | grep 443
```

---

## Security Best Practices

### SSL/TLS Security Checklist

- [ ] Use TLS 1.2 or higher only
- [ ] Disable SSLv2, SSLv3, TLS 1.0, and TLS 1.1
- [ ] Use strong cipher suites (ECDHE preferred)
- [ ] Enable HSTS with preload
- [ ] Enable OCSP stapling (Nginx)
- [ ] Use secure session tickets
- [ ] Implement certificate pinning (optional)
- [ ] Monitor certificate expiration
- [ ] Enable HTTP/2 for performance
- [ ] Configure security headers

### SSL Labs Rating

Test your SSL configuration:

1. Visit: https://www.ssllabs.com/ssltest/
2. Enter your domain
3. Aim for **A+** rating

### Common Security Headers

| Header | Purpose | Example Value |
|--------|---------|---------------|
| Strict-Transport-Security | Force HTTPS | max-age=63072000; includeSubDomains; preload |
| X-Frame-Options | Prevent clickjacking | SAMEORIGIN |
| X-Content-Type-Options | Prevent MIME sniffing | nosniff |
| X-XSS-Protection | XSS protection | 1; mode=block |
| Referrer-Policy | Control referrer info | strict-origin-when-cross-origin |
| Content-Security-Policy | XSS mitigation | default-src 'self' |

---

## Advanced Topics

### Wildcard Certificates

For subdomains:

```bash
# Obtain wildcard certificate
sudo certbot certonly --manual --preferred-challenges dns \
    -d "*.yourdomain.com" -d "yourdomain.com" \
    --server https://acme-v02.api.letsencrypt.org/directory

# Requires DNS TXT record verification
```

### Multi-Domain Certificates (SAN)

```bash
# Single certificate for multiple domains
sudo certbot --apache \
    -d yourdomain.com \
    -d www.yourdomain.com \
    -d api.yourdomain.com \
    -d admin.yourdomain.com
```

### Client Certificate Authentication

For admin areas:

```nginx
# Nginx configuration for client certificates
ssl_verify_client optional;
ssl_client_certificate /etc/nginx/client-ca.crt;

location /admin {
    if ($ssl_client_verify != SUCCESS) {
        return 403;
    }
    # ... rest of configuration
}
```

### Certificate Transparency Monitoring

Monitor certificate issuance:

```bash
# Install certstream
pip3 install certstream

# Monitor for your domain
certstream --url wss://certstream.calidog.io --full_html | grep yourdomain.com
```

### Backup Certificates

```bash
# Create backup script
sudo tee /usr/local/bin/backup-ssl.sh << 'EOF'
#!/bin/bash
BACKUP_DIR="/var/backups/ssl/$(date +%Y%m%d)"
mkdir -p "$BACKUP_DIR"

# Backup Let's Encrypt
if [ -d /etc/letsencrypt ]; then
    tar -czf "$BACKUP_DIR/letsencrypt.tar.gz" /etc/letsencrypt
fi

# Backup manual certificates
if [ -d /etc/ssl/microweber ]; then
    tar -czf "$BACKUP_DIR/custom-ssl.tar.gz" /etc/ssl/microweber
fi

# Keep only last 30 days
find /var/backups/ssl -type d -mtime +30 -exec rm -rf {} +
EOF

sudo chmod +x /usr/local/bin/backup-ssl.sh

# Schedule daily backups
sudo crontab -e
# Add: 0 3 * * * /usr/local/bin/backup-ssl.sh
```

---

## Verification Commands

### Test HTTPS Configuration

```bash
# Basic HTTPS test
curl -I https://yourdomain.com

# Follow redirects
curl -L -I https://yourdomain.com

# Test with specific TLS version
curl --tlsv1.2 -I https://yourdomain.com
curl --tlsv1.3 -I https://yourdomain.com

# Test HSTS
curl -I -H "Accept: */*" https://yourdomain.com | grep Strict-Transport-Security

# Check certificate expiry
echo | openssl s_client -connect yourdomain.com:443 2>/dev/null | openssl x509 -noout -enddate
```

### Monitor Certificate Expiration

```bash
# Create monitoring script
sudo tee /usr/local/bin/check-ssl-expiry.sh << 'EOF'
#!/bin/bash
DOMAIN="yourdomain.com"
DAYS_THRESHOLD=30

EXPIRY_DATE=$(echo | openssl s_client -servername $DOMAIN -connect $DOMAIN:443 2>/dev/null | openssl x509 -noout -enddate | cut -d= -f2)
EXPIRY_EPOCH=$(date -d "$EXPIRY_DATE" +%s)
CURRENT_EPOCH=$(date +%s)
DAYS_UNTIL_EXPIRY=$(( ($EXPIRY_EPOCH - $CURRENT_EPOCH) / 86400 ))

if [ $DAYS_UNTIL_EXPIRY -lt $DAYS_THRESHOLD ]; then
    echo "WARNING: SSL certificate for $DOMAIN expires in $DAYS_UNTIL_EXPIRY days"
    # Send notification (configure as needed)
    # mail -s "SSL Certificate Expiring Soon" admin@yourdomain.com
fi
EOF

sudo chmod +x /usr/local/bin/check-ssl-expiry.sh

# Schedule daily check
sudo crontab -e
# Add: 0 9 * * * /usr/local/bin/check-ssl-expiry.sh
```

---

## References

- [Let's Encrypt Documentation](https://letsencrypt.org/docs/)
- [Certbot User Guide](https://eff-certbot.readthedocs.io/en/stable/)
- [Mozilla SSL Configuration Generator](https://ssl-config.mozilla.org/)
- [SSL Labs Best Practices](https://github.com/ssllabs/research/wiki/SSL-and-TLS-Deployment-Best-Practices)
- [Laravel HTTPS Configuration](https://laravel.com/docs/11.x/configuration)
- [Apache SSL Documentation](https://httpd.apache.org/docs/current/ssl/)
- [Nginx SSL Module](https://nginx.org/en/docs/http/ngx_http_ssl_module.html)

---

## Support

For SSL/TLS configuration issues:

1. Check web server error logs:
   - Apache: `/var/log/apache2/error.log`
   - Nginx: `/var/log/nginx/error.log`

2. Verify Let's Encrypt logs:
   - `/var/log/letsencrypt/letsencrypt.log`

3. Test configuration:
   - Apache: `sudo apache2ctl configtest`
   - Nginx: `sudo nginx -t`

4. Consult documentation:
   - [Microweber Documentation](https://microweber.com/docs)
   - [GitHub Issues](https://github.com/microweber/microweber/issues)
