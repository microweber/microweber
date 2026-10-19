#!/bin/bash
#
# Run the Dusk browser suites, each in its OWN `php artisan dusk` process.
#
# Why per-suite isolation (same spirit as run-tests.sh):
#  - The suites do not all share one DuskTestCase base. The microweber-dusk
#    "Package Dusk Suite" (MicroweberSmokeTest) extends
#    MicroweberPackages\Dusk\Tests\DuskTestCase, which manages Chrome
#    differently (its own profile/recycle + --headless=new). Laravel's Dusk
#    keeps the Chrome process and the browser list in STATIC properties shared
#    across every test class in a single process, so mixing that base with the
#    main Tests\DuskTestCase base in one `php artisan dusk` run leaves Chrome in
#    a state the next base cannot drive — every later admin test then fails
#    fast on a dead/duplicated session (a false cascade).
#  - Running each testsuite in its own process gives each base a clean Chrome,
#    and a crash (SIGKILL) in one suite can no longer abort the others.
#
# Environment:
#  - Requires a server on http://127.0.0.1:8000 using the SAME env the tests
#    use. Start one with:  APP_ENV=testing php artisan serve --port=8000
#  - APP_ENV=testing is forced so the app resolves .env.testing (the canonical
#    test DB) for both server and tests — keep them on the same DB.
#  - DUSK_CHROME_PORT lets Chromedriver use a non-default port so the suite can
#    run on a shared host where 9515 is already taken (default 9515).
#  - Headless is forced here (GITHUB_RUN_NUMBER) so it runs without a display.
#
# Usage:
#   ./run-dusk.sh                       # Run all Dusk suites (each isolated)
#   ./run-dusk.sh "Browser Test Suite"  # Run specific suite(s) by name
#   ./run-dusk.sh --list                # List available Dusk suites
#
set -uo pipefail

export APP_ENV="${APP_ENV:-testing}"
export DUSK_CHROME_PORT="${DUSK_CHROME_PORT:-9515}"
# Force headless unless a display run is explicitly requested.
if [ -z "${DUSK_HEADLESS_DISABLED:-}" ]; then
    export GITHUB_RUN_NUMBER="${GITHUB_RUN_NUMBER:-1}"
fi

ALL_SUITES=(
    "PackageLoader Dusk Suite"
    "Package Dusk Suite"
    "AiTools Dusk Suite"
    "Fortify Dusk Suite"
    "Notification Dusk Suite"
    "Queue Dusk Suite"
    "Minifier Dusk Suite"
    "ImageOptimization Dusk Suite"
    "ModuleRegistry Dusk Suite"
    "LivewireModal Browser Suite"
    "Browser Test Suite"
)
FAILED=0
RESULTS=()

if [ "${1:-}" = "--list" ]; then
    echo "Available Dusk suites:"
    for suite in "${ALL_SUITES[@]}"; do
        echo "  - $suite"
    done
    exit 0
fi

if [ $# -gt 0 ]; then
    SUITES=("$@")
else
    SUITES=("${ALL_SUITES[@]}")
fi

# Kill only OUR leftover Chrome/Chromedriver (never other users' on a shared
# host). Process-name match avoids matching this script's own command line.
cleanup_chrome() {
    pgrep -u "$(id -u)" chromedriver-li 2>/dev/null | xargs -r kill -9 2>/dev/null
    pgrep -u "$(id -u)" chrome-headless 2>/dev/null | xargs -r kill -9 2>/dev/null
    pgrep -u "$(id -u)" chrome_crashpad 2>/dev/null | xargs -r kill -9 2>/dev/null
    pgrep -u "$(id -u)" -x chrome 2>/dev/null | xargs -r kill -9 2>/dev/null
    sleep 1
}

# Verify the server is reachable before spending time on a browser run.
if ! curl -s -o /dev/null --max-time 10 http://127.0.0.1:8000/ ; then
    echo "ERROR: no server on http://127.0.0.1:8000 — start one first:"
    echo "       APP_ENV=testing php artisan serve --port=8000 --host=127.0.0.1 &"
    exit 2
fi

chmod -R 0755 vendor/laravel/dusk/bin/ 2>/dev/null || true

echo "Running Dusk suites (APP_ENV=$APP_ENV, DUSK_CHROME_PORT=$DUSK_CHROME_PORT): ${SUITES[*]}"
echo "============================================"

for suite in "${SUITES[@]}"; do
    echo ""
    echo "--- Running Dusk suite: $suite ---"
    cleanup_chrome

    if php artisan dusk --testsuite="$suite"; then
        RESULTS+=("PASS: $suite")
    else
        EXIT_CODE=$?
        RESULTS+=("FAIL: $suite (exit code: $EXIT_CODE)")
        FAILED=1
    fi

    echo "--- Dusk suite $suite complete ---"
done

cleanup_chrome

echo ""
echo "============================================"
echo "Dusk summary:"
for result in "${RESULTS[@]}"; do
    echo "  $result"
done
echo "============================================"

exit $FAILED
