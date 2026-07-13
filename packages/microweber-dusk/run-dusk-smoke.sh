#!/bin/bash
#
# Run Microweber Dusk smoke tests across SQLite, MySQL, and PostgreSQL.
#
# Usage:
#   ./packages/microweber-dusk/run-dusk-smoke.sh          # all three DBs
#   ./packages/microweber-dusk/run-dusk-smoke.sh sqlite    # SQLite only
#   ./packages/microweber-dusk/run-dusk-smoke.sh mysql     # MySQL only
#   ./packages/microweber-dusk/run-dusk-smoke.sh pgsql     # PostgreSQL only
#

set -euo pipefail
cd "$(dirname "$0")/../.."

PROJECT_ROOT=$(pwd)
ENV_FILE="$PROJECT_ROOT/.env"
ENV_BACKUP="$PROJECT_ROOT/.env.dusk.bak"

# Default: run all three
DRIVERS="${1:-all}"

# reset_env() overwrites .env for each driver run, so preserve the developer's
# real .env and restore it on ANY exit (success, failure, or Ctrl-C). Without
# this the script would leave behind the stripped-down install stub.
restore_env() {
    if [[ -f "$ENV_BACKUP" ]]; then
        mv -f "$ENV_BACKUP" "$ENV_FILE"
        echo "--- Restored original .env ---"
        php artisan config:clear 2>/dev/null || true
    fi
}
if [[ -f "$ENV_FILE" ]]; then
    cp "$ENV_FILE" "$ENV_BACKUP"
fi
trap restore_env EXIT INT TERM

reset_env() {
    echo "--- Resetting .env to uninstalled state ---"
    cat > "$ENV_FILE" << 'EOF'
APP_KEY=base64:Jec7o8z1jnwv2ngk1Wv1S3q2fHmEg3hmYkXdwmFWcoY=
APP_URL=http://127.0.0.1:8000
MW_IS_INSTALLED=0
EOF
    php artisan config:clear 2>/dev/null || true
    php artisan cache:clear 2>/dev/null || true
}

run_dusk() {
    local driver="$1"
    local extra_env="$2"

    echo ""
    echo "============================================"
    echo " Running Dusk smoke tests with: $driver"
    echo "============================================"

    reset_env

    # Append driver-specific env vars
    echo "$extra_env" >> "$ENV_FILE"

    # Remove old SQLite DBs
    rm -f "$PROJECT_ROOT/database/"*.sqlite "$PROJECT_ROOT/database/"*.sqlite-journal 2>/dev/null || true

    php artisan config:clear 2>/dev/null || true

    # Pre-install via CLI (much faster and more reliable than browser-based install)
    local install_args="--db-driver=$driver --db-prefix= --email=admin@admin.com --username=admin --password=admin --default-content=1 --app-url=http://127.0.0.1:8000 -n"
    if [[ "$driver" == "sqlite" ]]; then
        install_args="$install_args --db-name=storage/database.sqlite"
    elif [[ "$driver" == "mysql" ]]; then
        install_args="$install_args --db-host=127.0.0.1 --db-name=microweber_dusk_mysql --db-username=root --db-password=root"
    elif [[ "$driver" == "pgsql" ]]; then
        install_args="$install_args --db-host=127.0.0.1 --db-name=microweber_dusk_pgsql --db-username=postgres --db-password=postgres"
    fi

    echo "--- Running CLI install: php artisan microweber:install $install_args ---"
    eval php artisan microweber:install $install_args 2>&1 || true

    php vendor/bin/phpunit --configuration=phpunit.dusk.xml --testsuite="Package Dusk Suite" --no-coverage 2>&1 || true
}

if [[ "$DRIVERS" == "all" || "$DRIVERS" == "sqlite" ]]; then
    run_dusk "sqlite" "DB_CONNECTION=sqlite"
fi

if [[ "$DRIVERS" == "all" || "$DRIVERS" == "mysql" ]]; then
    run_dusk "mysql" "DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=microweber_dusk_mysql
DB_USERNAME=root
DB_PASSWORD=root"
fi

if [[ "$DRIVERS" == "all" || "$DRIVERS" == "pgsql" ]]; then
    run_dusk "pgsql" "DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=microweber_dusk_pgsql
DB_USERNAME=postgres
DB_PASSWORD=postgres"
fi

echo ""
echo "=== All Dusk smoke test runs completed ==="
