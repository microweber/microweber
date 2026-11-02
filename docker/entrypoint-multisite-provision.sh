#!/bin/sh
set -e

# =============================================================================
# Multisite sample — one-shot provisioner
# =============================================================================
# Runs ONCE after the `app` container has installed the default site. It creates
# two extra demo sites, each with its OWN database, selected at runtime by the
# Host header:
#
#   localhost        -> microweber   (installed by the app container)
#   site-a.localhost -> mw_site_a
#   site-b.localhost -> mw_site_b
#
# For each extra domain it:
#   1) runs `multisite:install` — writes config/<domain>/microweber.php (the
#      detector marker) and .env.<domain> (its DB creds, cache prefix, session
#      cookie). APP_KEY is NOT written there; Laravel overlays .env.<domain> on
#      top of the base .env, so the shared APP_KEY is inherited.
#   2) runs `microweber:install` against that site's DB to create its schema,
#      default content and admin user.
#
# `microweber:install` always rewrites the base .env, so we back it up first and
# restore it at the end — the default site keeps pointing at the `microweber` DB
# with its original APP_KEY.
# =============================================================================

cd /var/www/html

DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"
DB_USER="${DB_USERNAME:-microweber}"
DB_PASS="${DB_PASSWORD:-secret}"
ADMIN_EMAIL="${MW_ADMIN_EMAIL:-admin@admin.com}"
ADMIN_USER="${MW_ADMIN_USERNAME:-admin}"
ADMIN_PASS="${MW_ADMIN_PASSWORD:-admin}"

# "domain:database" pairs — add more lines to grow the sample.
SITES="site-a.localhost:mw_site_a site-b.localhost:mw_site_b"

echo "[provision] waiting for the default-site install to finish..."
i=0
while [ ! -f storage/app/.mw-installed ]; do
    i=$((i + 1))
    if [ "$i" -gt 180 ]; then
        echo "[provision] timed out waiting for the default site install"
        exit 1
    fi
    sleep 3
done
echo "[provision] default site is installed."

if [ ! -d vendor ] || [ -z "$(ls -A vendor 2>/dev/null)" ]; then
    echo "[provision] vendor/ not ready (shared with the app container) — aborting."
    exit 1
fi

# Skip if the last site is already provisioned (idempotent re-runs).
if [ -f "config/site-b.localhost/microweber.php" ]; then
    echo "[provision] sites already provisioned — nothing to do."
    exit 0
fi

cp -f .env .env.provision-backup

for pair in $SITES; do
    domain="${pair%%:*}"
    db="${pair##*:}"
    echo "[provision] === ${domain} (${db}) ==="

    # 1) marker + .env.<domain>
    php artisan multisite:install "$domain" \
        --db-name="$db" \
        --db-host="$DB_HOST" \
        --db-port="$DB_PORT" \
        --db-username="$DB_USER" \
        --db-password="$DB_PASS" \
        --app-url="http://${domain}" \
        --force || true

    # 2) populate that site's own database (schema + default content + admin)
    php artisan microweber:install \
        --db-driver=mysql \
        --db-host="$DB_HOST" \
        --db-port="$DB_PORT" \
        --db-name="$db" \
        --db-username="$DB_USER" \
        --db-password="$DB_PASS" \
        --email="$ADMIN_EMAIL" \
        --username="$ADMIN_USER" \
        --password="$ADMIN_PASS" \
        --default-content=default \
        --app-url="http://${domain}" \
        --no-interaction
done

# Restore the base .env so the DEFAULT site points back at the `microweber` DB.
mv -f .env.provision-backup .env

php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

echo "[provision] done."
echo "[provision] default -> http://localhost   (DB: microweber)"
for pair in $SITES; do
    echo "[provision]            http://${pair%%:*}   (DB: ${pair##*:})"
done
