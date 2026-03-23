# Microweber Docker Configuration

This directory contains Docker configuration files for running Microweber CMS in containerized environments.

## Structure

```
docker/
├── README.md                    # This file
├── nginx/                       # Nginx configuration
│   ├── nginx.conf              # Main nginx configuration
│   └── default.conf            # Virtual host configuration
├── supervisor/                  # Supervisor configuration
│   └── supervisord.conf        # Process management
├── mysql/                       # MySQL/MariaDB configuration
│   ├── my.cnf                  # MySQL server configuration
│   └── init/                   # Initialization scripts
│       └── 01-init.sql
├── redis/                       # Redis configuration
│   └── redis.conf              # Redis server configuration
├── entrypoint.sh               # Production entrypoint script
└── entrypoint-dev.sh           # Development entrypoint script
```

## Usage

### Development

Start the development environment:

```bash
docker-compose up -d
```

This will start:
- **App**: PHP-FPM + Nginx on port 80
- **Database**: MySQL 8.0 on port 3306
- **Redis**: Redis 7 on port 6379
- **Mailpit**: Email testing UI on port 8025
- **phpMyAdmin**: Database management on port 8080
- **MinIO**: S3-compatible storage on ports 9000/9001

Access the application at: http://localhost

### Production

Start the production environment:

```bash
# Create secrets first
echo "your-strong-root-password" > secrets/db_root_password.txt
echo "your-strong-db-password" > secrets/db_password.txt
echo "base64:your-app-key" > secrets/app_key.txt

# Start services
docker-compose -f docker-compose.prod.yml up -d
```

## Environment Variables

See `.env.docker` for a complete list of environment variables.

### Required Production Variables

- `APP_KEY`: Application encryption key (generate with `php artisan key:generate`)
- `DB_PASSWORD`: Database password
- `REDIS_PASSWORD`: Redis password (if using password protection)

## Multi-stage Builds

The Dockerfile supports multi-stage builds:

- **base**: Base PHP image with common extensions
- **production**: Optimized production image
- **development**: Development image with debugging tools

Build specific targets:

```bash
# Production
docker build --target production -t microweber:prod .

# Development
docker build --target development -t microweber:dev .
```

## Volumes

### Development

- Project files are mounted as volumes for live editing
- Database data persists in named volumes

### Production

- Application storage is persisted
- Database and Redis data are persisted
- Logs are persisted for monitoring

## Health Checks

All services include health checks:

- **App**: HTTP check on `/up`
- **Database**: MySQL ping check
- **Redis**: Built-in health check

View health status:

```bash
docker-compose ps
```

## Troubleshooting

### Common Issues

1. **Permission denied on storage/**:
   ```bash
   chmod -R 777 storage bootstrap/cache
   ```

2. **Database connection refused**:
   Wait for the database to be ready before running migrations.

3. **Redis connection failed**:
   Ensure Redis service is running: `docker-compose up redis`

### Logs

View logs:

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
```

## Security Notes

- Never commit `.env` or secrets files to version control
- Use strong passwords in production
- Enable Redis password protection in production
- Configure firewall rules for production
- Use TLS/SSL certificates in production

## Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [Laravel Docker Guide](https://laravel.com/docs/sail)
- [Microweber Documentation](https://docs.microweber.com/)
