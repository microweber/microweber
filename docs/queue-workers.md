# Queue Workers Configuration Guide

This document describes how to configure and manage queue workers in Microweber.

## Overview

Microweber uses Laravel's queue system for background job processing. Jobs include:
- Email notifications (newsletter campaigns, form submissions)
- Webhook processing (billing webhooks)
- Batch operations (import/export tasks)
- Media processing

## Queue Drivers

### Database Queue (Recommended for most installations)

The database driver stores jobs in your database and is ideal for:
- Shared hosting environments
- Small to medium traffic sites
- Simple deployment without additional services

Configuration in `.env`:
```env
QUEUE_CONNECTION=database
DB_QUEUE_TABLE=jobs
DB_QUEUE_QUEUE=default
DB_QUEUE_RETRY_AFTER=90
```

### Redis Queue (Recommended for high-traffic sites)

Redis provides better performance for high-volume queues:

```env
QUEUE_CONNECTION=redis
REDIS_QUEUE_CONNECTION=default
REDIS_QUEUE_QUEUE=default
REDIS_QUEUE_RETRY_AFTER=90
```

### Sync Queue (Development only)

Executes jobs immediately (no background processing):

```env
QUEUE_CONNECTION=sync
```

## Setting Up Queue Workers

### Option 1: Supervisor (Recommended for Production)

Supervisor ensures queue workers run continuously and restart on failure.

#### Installation

**Ubuntu/Debian:**
```bash
sudo apt-get update
sudo apt-get install supervisor
```

**CentOS/RHEL:**
```bash
sudo yum install supervisor
sudo systemctl enable supervisord
```

#### Configuration

Copy the appropriate configuration file:

```bash
# For production
sudo cp scripts/supervisor/microweber-worker.conf /etc/supervisor/conf.d/

# For staging
sudo cp scripts/supervisor/microweber-worker-staging.conf /etc/supervisor/conf.d/
```

Update paths in the configuration:
- Replace `/var/www/microweber` with your actual project path
- Replace `/usr/bin/php` with your PHP binary path if different

#### Start Workers

```bash
# Read new configuration
sudo supervisorctl reread

# Update with new configuration
sudo supervisorctl update

# Start all workers
sudo supervisorctl start microweber-workers:*

# Check status
sudo supervisorctl status
```

#### Managing Workers

```bash
# Restart all workers
sudo supervisorctl restart microweber-workers:*

# Stop all workers
sudo supervisorctl stop microweber-workers:*

# Restart specific worker
sudo supervisorctl restart microweber-worker_00

# View logs
sudo tail -f /var/www/microweber/storage/logs/worker.log
```

### Option 2: Systemd Service

Create a systemd service file:

```bash
sudo tee /etc/systemd/system/microweber-worker.service > /dev/null <<EOF
[Unit]
Description=Microweber Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/microweber/artisan queue:work --sleep=3 --tries=3

[Install]
WantedBy=multi-user.target
EOF
```

Enable and start:

```bash
sudo systemctl enable microweber-worker
sudo systemctl start microweber-worker
sudo systemctl status microweber-worker
```

### Option 3: Cron (Simple, no daemon)

For shared hosting or simple setups, use cron:

```bash
# Edit crontab
crontab -e

# Add this line to process jobs every minute
* * * * * cd /var/www/microweber && /usr/bin/php artisan queue:work --max-jobs=10 --stop-when-empty >> /dev/null 2>&1
```

## Queue Worker Commands

### Manual Worker

```bash
# Basic worker
php artisan queue:work

# Process specific queue
php artisan queue:work --queue=high,default

# Process one job and exit
php artisan queue:work --once

# Process max jobs then exit
php artisan queue:work --max-jobs=100

# Run for max time then exit
php artisan queue:work --max-time=3600

# Sleep between checks (seconds)
php artisan queue:work --sleep=3

# Number of tries before failing
php artisan queue:work --tries=3

# Timeout per job (seconds)
php artisan queue:work --timeout=60
```

### Queue Management

```bash
# List all queues
php artisan queue:monitor database

# Retry failed jobs
php artisan queue:retry all

# Retry specific failed job
php artisan queue:retry 5

# Delete failed jobs
php artisan queue:forget 5

# Flush all failed jobs
php artisan queue:flush

# Clear a queue
php artisan queue:clear database --queue=default
```

## Queue Priorities

The following queues are configured by priority:

1. **high** - Critical operations (order processing, payments)
2. **billing** - Billing-related jobs (webhooks, invoices)
3. **newsletter** - Email campaign jobs
4. **default** - General background tasks

Workers should be configured to process higher priority queues first:

```bash
php artisan queue:work --queue=high,billing,newsletter,default
```

## Failed Jobs

Failed jobs are stored in the `failed_jobs` table and can be:

- **Retried**: `php artisan queue:retry all`
- **Viewed**: Check the `failed_jobs` table directly
- **Pruned**: `php artisan queue:prune-failed --hours=48`

## Monitoring

### Health Check

```bash
php artisan queue:monitor database
```

### Log Files

Worker logs are stored in:
- `storage/logs/worker.log` - Default queue worker
- `storage/logs/worker-high.log` - High priority queue
- `storage/logs/worker-newsletter.log` - Newsletter queue
- `storage/logs/worker-billing.log` - Billing queue

### Supervisor Status

```bash
sudo supervisorctl status
```

## Troubleshooting

### Workers Not Processing Jobs

1. Check queue connection:
   ```bash
   php artisan tinker --execute="dump(config('queue.default'))"
   ```

2. Verify jobs table exists:
   ```bash
   php artisan migrate:status | grep jobs
   ```

3. Check worker logs:
   ```bash
   tail -f storage/logs/worker.log
   ```

### Jobs Failing

1. Check failed jobs:
   ```bash
   php artisan queue:failed
   ```

2. View exception details in database:
   ```sql
   SELECT * FROM failed_jobs ORDER BY failed_at DESC LIMIT 10;
   ```

3. Retry with debugging:
   ```bash
   php artisan queue:retry --queue=default
   ```

### Memory Issues

Add `--max-jobs` and `--max-time` to restart workers periodically:

```bash
php artisan queue:work --max-jobs=500 --max-time=3600
```

### Database Connection Limits

If using database queue, ensure your DB has enough connections:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=microweber
DB_USERNAME=microweber
DB_PASSWORD=secret

# Increase connection pool
DB_POOL_SIZE=20
```

## Security Considerations

1. **Run workers as non-root user** (e.g., `www-data`)
2. **Secure log files** - Ensure log files aren't publicly accessible
3. **Rate limiting** - Configure job rate limiting for external APIs
4. **Secrets** - Never commit worker configuration with credentials

## Production Deployment Checklist

- [ ] Set `QUEUE_CONNECTION=database` or `redis`
- [ ] Run migrations: `php artisan migrate`
- [ ] Configure supervisor or systemd
- [ ] Set up log rotation
- [ ] Configure monitoring/alerting
- [ ] Test failed job handling
- [ ] Document custom queue names

## Additional Resources

- [Laravel Queue Documentation](https://laravel.com/docs/queues)
- [Supervisor Documentation](http://supervisord.org/)
- [Redis Queue Configuration](https://laravel.com/docs/queues#redis)
