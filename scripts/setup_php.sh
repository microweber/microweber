#!/usr/bin/env bash
#
# scripts/setup_php.sh — install Microweber's dependencies.
#
# Bootstraps a machine to run Microweber (Laravel 11 CMS):
#   1. PHP (>= 8.3) + the required extensions   (apt)
#   2. Composer                                  (apt: apt-get install composer)
#   3. PHP dependencies                          (composer install, allowed to run as root)
#   4. Global Laravel installer                  (composer global require laravel/installer)
#   5. Node package dependencies + built bundles (npm install + npm run build)
#
# With --dev it additionally provisions the browser-testing stack:
#   6. .env bootstrapped from a template if missing (+ key:generate),
#      Xdebug installed (php<ver>-xdebug) and configured for coverage-only
#      mode (xdebug.mode=coverage in /etc/php/…/conf.d/99-xdebug-coverage.ini),
#      PCOV installed (php<ver>-pcov) for faster line-coverage via php-pcov,
#      a Chrome/Chromium browser (apt), rendering fonts (Noto/Liberation, for
#      non-Latin templates), Xvfb virtual framebuffer (for headless servers
#      that have no real X11 display), and the matching Dusk ChromeDriver
#      (laravel/dusk is already a require-dev package; --dev wires up the driver).
#
# Must be run as root (it apt-installs packages and runs Composer as superuser).
# Idempotent: anything already present and new enough is detected and skipped.
#
# Usage:
#   scripts/setup_php.sh [options]
#
# Options:
#   --dev             full dev setup: system + composer (with dev deps) + Node,
#                     plus Xdebug (coverage mode), a Chrome browser and the
#                     Dusk ChromeDriver for browser tests. Mutually exclusive
#                     with --no-dev.
#   --no-dev          composer install without dev dependencies (production)
#   --install         run the Microweber lazy installer after dependencies are
#                     set up (php artisan microweber:install with no options).
#                     Reads DB connection from environment variables (DB_HOST,
#                     DB_USER, DB_PASS, DB_NAME, DB_ENGINE, DB_PREFIX) or the
#                     .env file defaults. Sets up the DB, default content, and
#                     admin account in one step. Skipped if Microweber is
#                     already installed (storage/installed file present).
#   --swoole          install the Swoole PHP extension (php<ver>-swoole via apt,
#                     or pecl install swoole on non-apt systems) for async/octane
#                     support.
#   --testing         wire up the full testing environment: npm install + build,
#                     php artisan dusk:install, copy .env.dusk → .env, and run
#                     php artisan microweber:install to seed a clean test site.
#                     (creates user root@localhost with password 'root').
#   --pgsql           install PostgreSQL server and set the postgres superuser
#                     password to 'postgres' (postgres@localhost).
#   --apache-fpm      install Apache2 + php<ver>-fpm, enable mod_proxy_fcgi and
#                     the matching PHP-FPM conf so Apache forwards .php requests
#                     to the FPM socket. Enables mod_rewrite + mod_headers too.
#                     Mutually exclusive with --apache-fcgi.
#   --apache-fcgi     install Apache2 + php<ver>-cgi + libapache2-mod-fcgid so
#                     Apache executes PHP via FastCGI (mod_fcgid). Enables
#                     mod_rewrite + mod_headers too. Mutually exclusive with
#                     --apache-fpm.
#   --skip-system     do not apt-install PHP/extensions, Xdebug, PCOV, or the Chrome browser
#   --skip-composer   do not run composer install
#   --skip-node       do not install/build the packages/* Node bundles
#   -h, --help        show this help and exit
#
# Environment:
#   PHP_VERSION       PHP version to apt-install when PHP is missing (default: 8.3)
#
set -euo pipefail

# ---------------------------------------------------------------------------
# Config & helpers
# ---------------------------------------------------------------------------
PHP_MIN_MAJOR=8
PHP_MIN_MINOR=3
PHP_VERSION="${PHP_VERSION:-8.4}"

# Required PHP extensions (Laravel 11 + Microweber composer.json: ext-pdo/zip/dom).
# pdo_sqlite/sqlite3 back the default sqlite DB the composer post-create touches
# and sqlite-based tests; exif/iconv are needed for media (image orientation) and
# encoding conversions in templates.
REQUIRED_EXTS=(bcmath ctype curl dom exif fileinfo gd iconv intl mbstring openssl pdo pdo_mysql pdo_sqlite sqlite3 tokenizer xml zip)

# apt package names for those extensions (php${PHP_VERSION}-* metapackages).
APT_PHP_PKGS=(cli common bcmath curl gd intl mbstring mysql xml zip sqlite3)

DEV=0
NO_DEV=0
INSTALL=0
SKIP_SYSTEM=0
SKIP_COMPOSER=0
SKIP_NODE=0
MYSQL=0
PGSQL=0
SWOOLE=0
TESTING=0
APACHE_FPM=0
APACHE_FCGI=0

# Resolve the repo root (this script lives in <root>/scripts/).
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(cd "${SCRIPT_DIR}/.." && pwd)"

if [ -t 1 ]; then
  RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; BLUE='\033[0;34m'; NC='\033[0m'
else
  RED=''; GREEN=''; YELLOW=''; BLUE=''; NC=''
fi

log()  { echo -e "${BLUE}==>${NC} $*"; }
ok()   { echo -e "${GREEN}  ✓${NC} $*"; }
warn() { echo -e "${YELLOW}  !${NC} $*"; }
err()  { echo -e "${RED}  ✗${NC} $*" >&2; }
die()  { err "$*"; exit 1; }

# Print the leading comment block (everything from line 2 up to, but not
# including, the first `set -euo pipefail`), stripping the "# " markers.
usage() {
  sed -n '2,/^set -euo pipefail/{/^set -euo pipefail/d; s/^# \{0,1\}//; p}' "${BASH_SOURCE[0]}"
  exit 0
}

# ---------------------------------------------------------------------------
# Argument parsing (runs before the root check so --help works for anyone)
# ---------------------------------------------------------------------------
while [ $# -gt 0 ]; do
  case "$1" in
    --dev)           DEV=1 ;;
    --no-dev)        NO_DEV=1 ;;
    --install)       INSTALL=1 ;;
    --mysql)         MYSQL=1 ;;
    --pgsql)         PGSQL=1 ;;
    --swoole)        SWOOLE=1 ;;
    --testing)       TESTING=1 ;;
    --apache-fpm)    APACHE_FPM=1 ;;
    --apache-fcgi)   APACHE_FCGI=1 ;;
    --skip-system)   SKIP_SYSTEM=1 ;;
    --skip-composer) SKIP_COMPOSER=1 ;;
    --skip-node)     SKIP_NODE=1 ;;
    -h|--help)       usage ;;
    *) die "Unknown option: $1 (try --help)" ;;
  esac
  shift
done

# --dev (install dev deps + browser-testing stack) and --no-dev (drop dev deps)
# are contradictory.
if [ "$DEV" -eq 1 ] && [ "$NO_DEV" -eq 1 ]; then
  die "--dev and --no-dev are mutually exclusive."
fi

# --apache-fpm (PHP-FPM) and --apache-fcgi (mod_fcgid) are two different PHP
# execution models for Apache and cannot be active simultaneously.
if [ "$APACHE_FPM" -eq 1 ] && [ "$APACHE_FCGI" -eq 1 ]; then
  die "--apache-fpm and --apache-fcgi are mutually exclusive."
fi

# This script must run as root — it apt-installs system packages and runs
# Composer (which refuses superuser unless explicitly allowed).
if [ "$(id -u)" -ne 0 ]; then
  die "This script must be run as root (e.g. 'sudo $0' or as the root user)."
fi
# Allow Composer to run as root/superuser without prompting.
export COMPOSER_ALLOW_SUPERUSER=1
# Running as root, so no sudo prefix is needed for apt.
SUDO=""

# ---------------------------------------------------------------------------
# 1. PHP + extensions
# ---------------------------------------------------------------------------
php_version_ok() {
  command -v php >/dev/null 2>&1 || return 1
  local maj min
  maj="$(php -r 'echo PHP_MAJOR_VERSION;' 2>/dev/null)" || return 1
  min="$(php -r 'echo PHP_MINOR_VERSION;' 2>/dev/null)" || return 1
  [ "$maj" -gt "$PHP_MIN_MAJOR" ] && return 0
  [ "$maj" -eq "$PHP_MIN_MAJOR" ] && [ "$min" -ge "$PHP_MIN_MINOR" ] && return 0
  return 1
}

# Extensions PHP reports as loaded (lower-cased), missing from REQUIRED_EXTS.
missing_exts() {
  local loaded missing=()
  loaded="$(php -m 2>/dev/null | tr '[:upper:]' '[:lower:]')"
  for ext in "${REQUIRED_EXTS[@]}"; do
    echo "$loaded" | grep -qx "$ext" || missing+=("$ext")
  done
  printf '%s\n' "${missing[@]:-}"
}

ensure_php() {
  log "Checking PHP (need >= ${PHP_MIN_MAJOR}.${PHP_MIN_MINOR})"

  if php_version_ok; then
    ok "PHP $(php -r 'echo PHP_VERSION;') present"
  else
    if [ "$SKIP_SYSTEM" -eq 1 ]; then
      die "PHP >= ${PHP_MIN_MAJOR}.${PHP_MIN_MINOR} not found and --skip-system was given."
    fi
    if ! command -v apt-get >/dev/null 2>&1; then
      die "PHP missing and this isn't an apt system. Install PHP ${PHP_VERSION}+ (${REQUIRED_EXTS[*]}) manually, then re-run with --skip-system."
    fi
    warn "Installing PHP ${PHP_VERSION} via apt (needs sudo/root)…"
    # PHP 8.3+ is not in the default Ubuntu repos — add Ondřej's PPA first.
    if ! apt-cache show "php${PHP_VERSION}" >/dev/null 2>&1; then
      log "Adding ppa:ondrej/php (PHP ${PHP_VERSION} not in default repos)…"
      $SUDO apt-get install -y software-properties-common 2>/dev/null || true
      $SUDO add-apt-repository -y ppa:ondrej/php
      $SUDO apt-get update -y
    else
      $SUDO apt-get update -y
    fi
    local pkgs=()
    for m in "${APT_PHP_PKGS[@]}"; do pkgs+=("php${PHP_VERSION}-${m}"); done
    if ! $SUDO apt-get install -y "php${PHP_VERSION}" "${pkgs[@]}"; then
      die "apt could not install php${PHP_VERSION} even after adding Ondřej's PPA. Check the output above."
    fi
    php_version_ok || die "PHP still < ${PHP_MIN_MAJOR}.${PHP_MIN_MINOR} after install."
    ok "PHP $(php -r 'echo PHP_VERSION;') installed"
  fi

  # Verify extensions; apt-install any missing ones when we may.
  local miss; miss="$(missing_exts | sed '/^$/d')"
  if [ -z "$miss" ]; then
    ok "All required PHP extensions loaded"
  else
    warn "Missing PHP extensions: $(echo "$miss" | tr '\n' ' ')"
    if [ "$SKIP_SYSTEM" -eq 1 ] || ! command -v apt-get >/dev/null 2>&1; then
      die "Install the missing extensions and re-run (or drop --skip-system)."
    fi
    # Map ext -> apt module name (most match; pdo_mysql -> mysql).
    local apt_mods=() ext mod
    for ext in $miss; do
      case "$ext" in
        pdo|pdo_mysql)         mod="mysql" ;;
        pdo_sqlite|sqlite3)    mod="sqlite3" ;;
        exif|iconv)            mod="common" ;;   # shipped in php${V}-common
        openssl|tokenizer|ctype|fileinfo|json) mod="" ;;  # built into php-cli
        *) mod="$ext" ;;
      esac
      [ -n "$mod" ] && apt_mods+=("php${PHP_VERSION}-${mod}")
    done
    if [ "${#apt_mods[@]}" -gt 0 ]; then
      $SUDO apt-get update -y
      $SUDO apt-get install -y "${apt_mods[@]}" || warn "Some extension packages failed to install."
    fi
    miss="$(missing_exts | sed '/^$/d')"
    [ -z "$miss" ] && ok "All required PHP extensions now loaded" \
      || warn "Still missing: $(echo "$miss" | tr '\n' ' ') — composer may refuse to install."
  fi
}

# ---------------------------------------------------------------------------
# 2. Composer
# ---------------------------------------------------------------------------
COMPOSER_BIN=""
ensure_composer() {
  log "Checking Composer"
  if command -v composer >/dev/null 2>&1; then
    COMPOSER_BIN="composer"
    ok "$(composer --version 2>/dev/null | head -1)"
    return
  fi
  if [ "$SKIP_SYSTEM" -eq 1 ]; then
    die "Composer not found and --skip-system was given. Install Composer (apt-get install composer) and re-run."
  fi
  if ! command -v apt-get >/dev/null 2>&1; then
    die "Composer not found and this isn't an apt system. Install Composer manually, then re-run."
  fi
  warn "Composer not on PATH — installing via apt…"
  $SUDO apt-get update -y
  $SUDO apt-get install -y composer || die "apt could not install Composer."
  command -v composer >/dev/null 2>&1 || die "Composer still not on PATH after apt install."
  COMPOSER_BIN="composer"
  ok "$(composer --version 2>/dev/null | head -1)"
}

# ---------------------------------------------------------------------------
# 3. PHP dependencies
# ---------------------------------------------------------------------------
install_php_deps() {
  log "Installing PHP dependencies (composer install)"
  cd "${ROOT_DIR}"
  local flags=(install --no-interaction --prefer-dist --no-progress)
  if [ "$NO_DEV" -eq 1 ]; then
    flags+=(--no-dev --optimize-autoloader)
  else
    flags+=(--dev)
  fi
  # shellcheck disable=SC2086
  $COMPOSER_BIN "${flags[@]}"
  ok "composer install complete"
}

# ---------------------------------------------------------------------------
# 4. Global Laravel installer
# ---------------------------------------------------------------------------
install_global_laravel_installer() {
  log "Ensuring global Laravel installer (laravel/installer)"

  local composer_home laravel_bin
  composer_home="$($COMPOSER_BIN global config home --absolute 2>/dev/null || true)"
  [ -n "$composer_home" ] || die "Could not resolve Composer global home."

  laravel_bin="${composer_home}/vendor/bin/laravel"
  if [ -x "${laravel_bin}" ]; then
    ok "Global laravel/installer already present"
  else
    $COMPOSER_BIN global require laravel/installer --no-interaction --prefer-dist --no-progress
    [ -x "${laravel_bin}" ] || die "laravel/installer installed but '${laravel_bin}' was not created."
    ok "Global laravel/installer installed"
  fi

  ln -sf "${laravel_bin}" /usr/local/bin/laravel
  ok "Linked Laravel installer to /usr/local/bin/laravel"
}

# ---------------------------------------------------------------------------
# 5. Node package dependencies + bundles
# ---------------------------------------------------------------------------
install_node_deps() {
  if ! command -v npm >/dev/null 2>&1; then
    warn "npm not found — skipping Node packages. Install Node 18+ and re-run, or use --skip-node."
    return
  fi
  log "Installing Node package dependencies + building bundles ($(node -v) / npm $(npm -v))"
  local pkg name
  for pkg in "${ROOT_DIR}"/packages/*/; do
    [ -f "${pkg}package.json" ] || continue
    name="$(basename "$pkg")"
    log "  packages/${name}: npm install"
    if [ -f "${pkg}package-lock.json" ]; then
      ( cd "$pkg" && npm ci --no-audit --no-fund ) || ( cd "$pkg" && npm install --no-audit --no-fund )
    else
      ( cd "$pkg" && npm install --no-audit --no-fund )
    fi
    # Build only packages that expose a "build" script.
    if node -e "process.exit(require('${pkg}package.json').scripts?.build?0:1)" 2>/dev/null; then
      log "  packages/${name}: npm run build"
      ( cd "$pkg" && npm run build )
      ok "packages/${name} built"
    else
      ok "packages/${name} deps installed (no build script)"
    fi
  done
}

# ---------------------------------------------------------------------------
# 6. Dev / browser-testing stack (--dev): Xdebug (coverage) + Chrome + Dusk
# ---------------------------------------------------------------------------
ensure_xdebug() {
  log "Checking Xdebug (for PHPUnit coverage)"

  # -------------------------------------------------------------------------
  # Helper: write (or update) the coverage-mode drop-in.
  # On Debian/Ubuntu the apt package installs zend_extension via its own ini
  # (e.g. /etc/php/8.x/mods-available/xdebug.ini symlinked into conf.d/).
  # Our drop-in must NOT repeat zend_extension= — that double-loads the .so
  # and breaks PHP startup.  We only need xdebug.mode=coverage.
  # We place it in the CLI conf.d so only the CLI SAPI is affected.
  # -------------------------------------------------------------------------
  write_coverage_ini() {
    local include_zend="${1:-}"   # pass "load" to also add zend_extension=
    # Locate the CLI conf.d: /etc/php/<ver>/cli/conf.d  (Debian layout).
    local cli_conf
    cli_conf="$(php -r 'echo php_ini_scanned_files();' 2>/dev/null \
      | tr ',' '\n' | head -1 | xargs -r dirname 2>/dev/null)"
    if [ -z "$cli_conf" ] || [ ! -d "$cli_conf" ]; then
      cli_conf="/etc/php/${PHP_VERSION}/cli/conf.d"
    fi

    local ini_file="${cli_conf}/99-xdebug-coverage.ini"
    if [ ! -d "$cli_conf" ]; then
      warn "Cannot locate PHP CLI conf.d — set xdebug.mode=coverage in php.ini manually."
      return
    fi
    if [ -f "$ini_file" ]; then
      ok "Xdebug coverage ini already present: ${ini_file}"
      return
    fi

    if [ "$include_zend" = "load" ]; then
      $SUDO tee "$ini_file" > /dev/null <<'INI'
; Auto-generated by setup_php.sh --dev
; Loads Xdebug and sets coverage-only mode (no step-debug overhead).
zend_extension=xdebug.so
xdebug.mode=coverage
INI
    else
      $SUDO tee "$ini_file" > /dev/null <<'INI'
; Auto-generated by setup_php.sh --dev
; Sets Xdebug to coverage-only mode (extension loaded by the package ini).
; Do NOT add zend_extension= here — that would double-load the .so.
xdebug.mode=coverage
INI
    fi
    ok "Xdebug ini written: ${ini_file} (xdebug.mode=coverage)"
  }

  # Already loaded — ensure mode includes coverage.
  if php -m 2>/dev/null | grep -qi '^xdebug$'; then
    local mode
    mode="$(php -r 'echo ini_get("xdebug.mode");' 2>/dev/null || true)"
    if echo "$mode" | grep -q "coverage"; then
      ok "Xdebug loaded, coverage mode active (xdebug.mode=${mode})"
    else
      warn "Xdebug loaded but xdebug.mode='${mode}' — writing coverage drop-in ini…"
      write_coverage_ini
      ok "Re-run or set XDEBUG_MODE=coverage in env for the mode to take effect."
    fi
    return
  fi

  # .so present but not loaded by any existing ini — write a full ini that both
  # loads the extension and sets coverage mode.
  local xdebug_so
  xdebug_so="$(php -r 'echo PHP_EXTENSION_DIR;' 2>/dev/null)/xdebug.so"
  if [ -f "$xdebug_so" ]; then
    warn "Xdebug .so found but not loaded — writing load+coverage ini…"
    write_coverage_ini load
    return
  fi

  # Not installed at all.
  if [ "$SKIP_SYSTEM" -eq 1 ]; then
    warn "Xdebug not found and --skip-system given — install php${PHP_VERSION}-xdebug manually for coverage."
    return
  fi
  if ! command -v apt-get >/dev/null 2>&1; then
    warn "Not an apt system — install Xdebug manually (pecl install xdebug) for coverage."
    return
  fi

  warn "Installing php${PHP_VERSION}-xdebug via apt…"
  $SUDO apt-get update -y
  if $SUDO apt-get install -y "php${PHP_VERSION}-xdebug"; then
    ok "Xdebug installed (php${PHP_VERSION}-xdebug)"
    write_coverage_ini
  else
    warn "apt could not install php${PHP_VERSION}-xdebug — install it manually for coverage."
  fi
}

# ---------------------------------------------------------------------------
# 6b-2. PCOV (fast line-coverage alternative to Xdebug)
# ---------------------------------------------------------------------------
ensure_pcov() {
  log "Checking PCOV (fast PHPUnit line-coverage)"

  # Already loaded — nothing to do.
  if php -m 2>/dev/null | grep -qi '^pcov$'; then
    ok "PCOV already loaded"
    return
  fi

  if [ "$SKIP_SYSTEM" -eq 1 ]; then
    warn "PCOV not found and --skip-system given — install php${PHP_VERSION}-pcov manually for coverage."
    return
  fi
  if ! command -v apt-get >/dev/null 2>&1; then
    warn "Not an apt system — install PCOV manually (pecl install pcov) for coverage."
    return
  fi

  warn "Installing php${PHP_VERSION}-pcov via apt…"
  $SUDO apt-get update -y
  if $SUDO apt-get install -y "php${PHP_VERSION}-pcov"; then
    ok "PCOV installed (php${PHP_VERSION}-pcov)"
  else
    warn "apt could not install php${PHP_VERSION}-pcov — install it manually for coverage."
  fi
}

# ---------------------------------------------------------------------------
# 6c. Chrome browser
# A browser is already present if any of these resolve.
chrome_present() {
  command -v google-chrome >/dev/null 2>&1 || \
  command -v google-chrome-stable >/dev/null 2>&1 || \
  command -v chromium >/dev/null 2>&1 || \
  command -v chromium-browser >/dev/null 2>&1
}

# Best-effort: report the installed Chrome/Chromium version string.
chrome_version() {
  local b
  for b in google-chrome google-chrome-stable chromium chromium-browser; do
    command -v "$b" >/dev/null 2>&1 && { "$b" --version 2>/dev/null; return; }
  done
}

ensure_chrome() {
  if chrome_present; then
    ok "Browser present: $(chrome_version)"
    return 0
  fi
  if [ "$SKIP_SYSTEM" -eq 1 ]; then
    warn "No Chrome/Chromium found and --skip-system given — install one for Dusk and re-run."
    return 1
  fi
  if ! command -v apt-get >/dev/null 2>&1; then
    warn "No Chrome/Chromium and not an apt system — install Google Chrome or Chromium manually for Dusk."
    return 1
  fi
  warn "Installing a Chrome browser via apt (for Dusk browser tests)…"
  # Prerequisites for adding Google's signed apt repo (gpg --dearmor, https
  # fetch). On a bare box these are often missing and the key/repo step would
  # silently fail, so install them up-front.
  $SUDO apt-get update -y || true
  $SUDO apt-get install -y ca-certificates curl gnupg apt-transport-https wget || true
  # Prefer Google Chrome stable from Google's own apt repo; fall back to Chromium.
  if command -v curl >/dev/null 2>&1 || command -v wget >/dev/null 2>&1; then
    local key=/usr/share/keyrings/google-chrome.gpg
    if [ ! -f "$key" ]; then
      if command -v curl >/dev/null 2>&1; then
        curl -fsSL https://dl.google.com/linux/linux_signing_key.pub | gpg --dearmor -o "$key" 2>/dev/null || true
      else
        wget -qO- https://dl.google.com/linux/linux_signing_key.pub | gpg --dearmor -o "$key" 2>/dev/null || true
      fi
    fi
    if [ -f "$key" ]; then
      echo "deb [arch=amd64 signed-by=${key}] http://dl.google.com/linux/chrome/deb/ stable main" \
        > /etc/apt/sources.list.d/google-chrome.list
      $SUDO apt-get update -y || true
      $SUDO apt-get install -y google-chrome-stable || true
    fi
  fi
  if ! chrome_present; then
    warn "Google Chrome install failed or unavailable — trying Chromium…"
    $SUDO apt-get update -y || true
    $SUDO apt-get install -y chromium || $SUDO apt-get install -y chromium-browser || true
  fi
  if chrome_present; then
    ok "Browser installed: $(chrome_version)"
    return 0
  fi
  warn "Could not install a Chrome/Chromium browser automatically — install one manually for Dusk."
  return 1
}

# Fonts for accurate template rendering in the headless browser. Without broad
# Unicode coverage, non-Latin templates (Cyrillic, Greek, CJK, emoji) render as
# tofu boxes and screenshot/visual assertions are meaningless. Noto covers most
# scripts; Liberation/DejaVu provide metric-compatible Latin faces.
ensure_fonts() {
  if [ "$SKIP_SYSTEM" -eq 1 ]; then
    warn "Skipping fonts (--skip-system) — ensure Noto/Liberation fonts are present for template rendering."
    return 0
  fi
  if ! command -v apt-get >/dev/null 2>&1; then
    warn "Not an apt system — install web fonts (Liberation, Noto, DejaVu) manually for accurate rendering."
    return 0
  fi
  log "Installing rendering fonts (Latin + Cyrillic/Greek + emoji) for template tests"
  $SUDO apt-get update -y || true
  # Core: metric-compatible Latin + broad Unicode (Noto covers Cyrillic/Greek).
  $SUDO apt-get install -y fonts-liberation fonts-dejavu-core fonts-noto-core fonts-noto-ui-core \
    || warn "Some core font packages failed to install."
  # Best-effort extras (large / occasionally unavailable): CJK + colour emoji.
  $SUDO apt-get install -y fonts-noto-cjk fonts-noto-color-emoji || true
  command -v fc-cache >/dev/null 2>&1 && fc-cache -f >/dev/null 2>&1 || true
  ok "Rendering fonts installed"
}

# Virtual framebuffer — needed on headless servers that have no real display.
# Chrome (even in --headless mode) may still need DISPLAY set on some systems;
# Dusk's non-headless mode definitely does.  Xvfb provides a lightweight
# in-memory X11 display so both cases work without a physical screen.
ensure_xvfb() {
  if [ "$SKIP_SYSTEM" -eq 1 ]; then
    warn "Skipping Xvfb (--skip-system) — ensure a virtual display is available on headless servers."
    return 0
  fi

  # Already available?
  if command -v Xvfb >/dev/null 2>&1; then
    ok "Xvfb already installed: $(Xvfb -help 2>&1 | head -1 || true)"
    return 0
  fi

  if ! command -v apt-get >/dev/null 2>&1; then
    warn "Not an apt system — install Xvfb manually for headless browser tests."
    return 0
  fi

  warn "Installing Xvfb (virtual framebuffer for headless Chrome/Dusk)…"
  $SUDO apt-get update -y || true
  # xvfb: the virtual framebuffer server.
  # x11-utils: provides xdpyinfo (useful for display diagnostics).
  if $SUDO apt-get install -y xvfb x11-utils; then
    ok "Xvfb installed"
  else
    warn "Could not install Xvfb — headless Chrome may fail without a DISPLAY on this server."
  fi
}

# 5a. Bootstrap .env from a template if it doesn't exist, so artisan (and the
#     Dusk driver install below) can run. Mirrors the closing "Next steps" hint
#     but actually performs the copy under --dev.
ensure_env() {
  local env_file="${ROOT_DIR}/.env"
  local created_env=0

  if [ -f "${env_file}" ]; then
    ok ".env present"
  else
    local cand src=""
    for cand in env_local .env.example .env.testing; do
      [ -f "${ROOT_DIR}/${cand}" ] && { src="$cand"; break; }
    done
    if [ -z "$src" ]; then
      warn "No .env and no template (env_local/.env.example) found — create .env manually."
      return 1
    fi
    cp "${ROOT_DIR}/${src}" "${env_file}"
    created_env=1
    ok "Created .env from ${src}"
  fi

  # Ensure APP_ENV=testing for the dev setup — add if missing, replace if different.
  if ! grep -qE '^APP_ENV=' "${env_file}" 2>/dev/null; then
    printf '\nAPP_ENV=testing\n' >> "${env_file}"
    ok "Added APP_ENV=testing to .env"
  elif ! grep -qE '^APP_ENV=testing$' "${env_file}" 2>/dev/null; then
    sed -i 's/^APP_ENV=.*/APP_ENV=testing/' "${env_file}"
    ok "Updated APP_ENV to testing in .env"
  else
    ok "APP_ENV=testing already set in .env"
  fi

  # A freshly-copied template usually has an empty APP_KEY; generate one so
  # artisan commands don't bail.
  if [ "$created_env" -eq 1 ] && command -v php >/dev/null 2>&1 && grep -qE '^APP_KEY=[[:space:]]*$' "${env_file}" 2>/dev/null; then
    ( cd "${ROOT_DIR}" && php artisan key:generate --ansi ) \
      && ok "APP_KEY generated" \
      || warn "Could not generate APP_KEY — run 'php artisan key:generate' manually."
  fi
}

install_dev_tools() {
  log "Setting up dev / browser-testing stack (--dev)"

  # 6a. Ensure a usable .env before any artisan call.
  ensure_env || true

  # 6b. Xdebug for PHPUnit coverage.
  ensure_xdebug

  # 6b-2. PCOV for fast line-coverage (complement to Xdebug).
  ensure_pcov

  # 6c. Browser + rendering fonts + virtual display (independent of Dusk/composer).
  local have_browser=0
  ensure_chrome && have_browser=1
  ensure_fonts
  ensure_xvfb

  # 6d. ChromeDriver — requires laravel/dusk in vendor/.
  if ! command -v php >/dev/null 2>&1; then
    warn "php not on PATH — cannot install ChromeDriver; skipping."
    return
  fi

  if [ "$SKIP_COMPOSER" -eq 1 ]; then
    warn "Composer was skipped (--skip-composer) — ensure laravel/dusk is installed to set up ChromeDriver."
  fi
  if [ ! -d "${ROOT_DIR}/vendor/laravel/dusk" ]; then
    warn "vendor/laravel/dusk not found — run composer install (with dev deps) first, then re-run --dev to install ChromeDriver."
    return
  fi

  cd "${ROOT_DIR}"

  # Scaffold Dusk's .env.dusk and base DuskTestCase if not already present.
  if ! env APP_ENV=testing php artisan dusk:install 2>/dev/null; then
    warn "artisan dusk:install failed — run: APP_ENV=testing php artisan dusk:install"
  else
    ok "Dusk scaffolding installed (dusk:install)"
  fi

  # Dusk's service provider only registers when APP_ENV=testing.
  # Run artisan with a temporary override so dusk:chrome-driver is available.
  local artisan_env="APP_ENV=testing"

  # Sanity-check that the command is actually registered before calling it.
  if ! env $artisan_env php artisan list --format=txt 2>/dev/null | grep -q 'dusk:chrome-driver'; then
    warn "artisan dusk:chrome-driver not available (APP_KEY missing? DB unreachable? DuskServiceProvider not registered?)."
    warn "Fix artisan boot errors, then run: APP_ENV=testing php artisan dusk:chrome-driver --detect"
    return
  fi

  if [ "$have_browser" -eq 1 ]; then
    log "Installing matching ChromeDriver (artisan dusk:chrome-driver --detect)"
    if env $artisan_env php artisan dusk:chrome-driver --detect; then
      ok "ChromeDriver installed (matched to installed browser)"
    else
      warn "dusk:chrome-driver --detect failed — falling back to latest stable driver"
      env $artisan_env php artisan dusk:chrome-driver \
        && ok "ChromeDriver (latest stable) installed" \
        || warn "Could not install ChromeDriver — run: APP_ENV=testing php artisan dusk:chrome-driver --detect"
    fi
  else
    log "Installing latest stable ChromeDriver (no browser detected)"
    env $artisan_env php artisan dusk:chrome-driver \
      && ok "ChromeDriver (latest stable) installed" \
      || warn "Could not install ChromeDriver — install a browser first, then: APP_ENV=testing php artisan dusk:chrome-driver --detect"
  fi
}

# ---------------------------------------------------------------------------
# 7. Microweber lazy installer (--install)
# ---------------------------------------------------------------------------
install_microweber() {
  log "Running Microweber lazy installer (php artisan microweber:install)"

  cd "${ROOT_DIR}"

  # Skip if already installed.
  if [ -f "${ROOT_DIR}/storage/installed" ]; then
    ok "Microweber already installed (storage/installed present) — skipping."
    return
  fi

  if ! command -v php >/dev/null 2>&1; then
    die "php not on PATH — cannot run the Microweber installer."
  fi
  if [ ! -f "${ROOT_DIR}/artisan" ]; then
    die "'artisan' not found in ${ROOT_DIR} — is this the Microweber project root?"
  fi

  # Ensure a .env exists so artisan can boot.
  ensure_env || true

  php artisan microweber:install \
    && ok "Microweber installed successfully." \
    || die "Microweber installer exited with an error. Check the output above."
}

# ---------------------------------------------------------------------------
# 8. MySQL server (--mysql)
# ---------------------------------------------------------------------------
install_mysql() {
  log "Installing MySQL server"

  if ! command -v apt-get >/dev/null 2>&1; then
    die "Not an apt system — install MySQL manually."
  fi

  $SUDO apt-get update -y
  # Pre-seed the root password so the interactive debconf prompt is skipped.
  if command -v debconf-set-selections >/dev/null 2>&1; then
    echo "mysql-server mysql-server/root_password password root"       | $SUDO debconf-set-selections
    echo "mysql-server mysql-server/root_password_again password root" | $SUDO debconf-set-selections
  fi
  $SUDO apt-get install -y mysql-server || die "Could not install mysql-server."

  # Start the service if not already running.
  if command -v service >/dev/null 2>&1; then
    $SUDO service mysql start 2>/dev/null || true
  fi

  # Set / confirm root@localhost password = 'root' regardless of auth plugin.
  local set_sql="ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root'; FLUSH PRIVILEGES;"
  if $SUDO mysql -u root --connect-expired-password -e "$set_sql" 2>/dev/null; then
    true
  elif $SUDO mysql --defaults-file=/etc/mysql/debian.cnf -e "$set_sql" 2>/dev/null; then
    true
  else
    warn "Could not set MySQL root password automatically — run manually: mysql -u root -e \"$set_sql\""
  fi
  ok "MySQL server ready — root@localhost password: root"

  # PHP driver: php<ver>-mysql (provides pdo_mysql + mysqli).
  log "Installing PHP MySQL driver (php${PHP_VERSION}-mysql)"
  $SUDO apt-get install -y "php${PHP_VERSION}-mysql" \
    && ok "php${PHP_VERSION}-mysql installed" \
    || warn "Could not install php${PHP_VERSION}-mysql — install it manually."
}

# ---------------------------------------------------------------------------
# 9. PostgreSQL server (--pgsql)
# ---------------------------------------------------------------------------
install_pgsql() {
  log "Installing PostgreSQL server"

  if ! command -v apt-get >/dev/null 2>&1; then
    die "Not an apt system — install PostgreSQL manually."
  fi

  $SUDO apt-get update -y
  $SUDO apt-get install -y postgresql postgresql-contrib || die "Could not install postgresql."

  # Start the service if not already running.
  if command -v service >/dev/null 2>&1; then
    $SUDO service postgresql start 2>/dev/null || true
  fi

  # Set postgres superuser password = 'postgres'.
  $SUDO -u postgres psql -c "ALTER USER postgres PASSWORD 'postgres';" 2>/dev/null \
    && ok "PostgreSQL server ready — postgres@localhost password: postgres" \
    || warn "Could not set postgres password — run: sudo -u postgres psql -c \"ALTER USER postgres PASSWORD 'postgres';\""

  # PHP driver: php<ver>-pgsql (provides pdo_pgsql + pgsql).
  log "Installing PHP PostgreSQL driver (php${PHP_VERSION}-pgsql)"
  $SUDO apt-get install -y "php${PHP_VERSION}-pgsql" \
    && ok "php${PHP_VERSION}-pgsql installed" \
    || warn "Could not install php${PHP_VERSION}-pgsql — install it manually."
}

# ---------------------------------------------------------------------------
# 10. Apache2 + PHP-FPM (--apache-fpm)
# ---------------------------------------------------------------------------
install_apache_fpm() {
  log "Installing Apache2 + PHP-FPM (--apache-fpm)"

  if ! command -v apt-get >/dev/null 2>&1; then
    die "Not an apt system — install Apache2 + php${PHP_VERSION}-fpm manually."
  fi

  $SUDO apt-get update -y
  $SUDO apt-get install -y apache2 "php${PHP_VERSION}-fpm" \
    || die "Could not install apache2 / php${PHP_VERSION}-fpm."

  # Enable the modules required for PHP-FPM proxying + Laravel .htaccess.
  $SUDO a2enmod proxy proxy_fcgi setenvif rewrite headers 2>/dev/null \
    || warn "Some Apache modules may already be enabled or failed."

  # Enable the distro-supplied PHP-FPM Apache configuration.
  local fpm_conf="php${PHP_VERSION}-fpm"
  if apache2ctl -M 2>/dev/null | grep -q proxy_fcgi; then
    if $SUDO a2enconf "$fpm_conf" 2>/dev/null; then
      ok "Apache conf ${fpm_conf} enabled"
    else
      warn "a2enconf ${fpm_conf} failed — you may need to configure the FPM socket path manually."
    fi
  fi

  # Start / restart services.
  if command -v service >/dev/null 2>&1; then
    $SUDO service "php${PHP_VERSION}-fpm" start  2>/dev/null || true
    $SUDO service apache2 restart 2>/dev/null    || true
  fi

  ok "Apache2 + PHP-FPM ready"
  warn "Point your VirtualHost DocumentRoot at ${ROOT_DIR}/public and ensure"
  warn "  <Directory> AllowOverride All is set so Laravel's .htaccess is honoured."
}

# ---------------------------------------------------------------------------
# 11. Apache2 + PHP FastCGI via mod_fcgid (--apache-fcgi)
# ---------------------------------------------------------------------------
install_apache_fcgi() {
  log "Installing Apache2 + PHP FastCGI / mod_fcgid (--apache-fcgi)"

  if ! command -v apt-get >/dev/null 2>&1; then
    die "Not an apt system — install Apache2 + libapache2-mod-fcgid + php${PHP_VERSION}-cgi manually."
  fi

  $SUDO apt-get update -y
  $SUDO apt-get install -y apache2 "php${PHP_VERSION}-cgi" libapache2-mod-fcgid \
    || die "Could not install apache2 / php${PHP_VERSION}-cgi / libapache2-mod-fcgid."

  # Enable mod_fcgid, mod_cgid (fallback CGI), plus Laravel's required modules.
  $SUDO a2enmod fcgid cgid rewrite headers 2>/dev/null \
    || warn "Some Apache modules may already be enabled or failed."

  # Disable mod_php if present — it conflicts with FastCGI execution.
  if apache2ctl -M 2>/dev/null | grep -q 'php'; then
    $SUDO a2dismod "php${PHP_VERSION}" 2>/dev/null || true
    ok "Disabled mod_php${PHP_VERSION} (conflicts with mod_fcgid)"
  fi

  # Start / restart Apache.
  if command -v service >/dev/null 2>&1; then
    $SUDO service apache2 restart 2>/dev/null || true
  fi

  ok "Apache2 + PHP FastCGI (mod_fcgid) ready"
  warn "Point your VirtualHost DocumentRoot at ${ROOT_DIR}/public."
  warn "Add to your VirtualHost to route PHP through fcgid:"
  warn "  Options +ExecCGI"
  warn "  AddHandler fcgid-script .php"
  warn "  FcgidWrapper /usr/bin/php-cgi${PHP_VERSION} .php"
  warn "  AllowOverride All"
}

# ---------------------------------------------------------------------------
# 11. Swoole extension (--swoole)
# ---------------------------------------------------------------------------
install_swoole() {
  log "Installing Swoole PHP extension"

  if php -m 2>/dev/null | grep -qi '^swoole$'; then
    ok "Swoole already loaded"
    return
  fi

  if command -v apt-get >/dev/null 2>&1; then
    $SUDO apt-get update -y
    if $SUDO apt-get install -y "php${PHP_VERSION}-swoole"; then
      ok "Swoole installed (php${PHP_VERSION}-swoole)"
      return
    fi
    warn "apt could not find php${PHP_VERSION}-swoole — falling back to pecl."
  fi

  if command -v pecl >/dev/null 2>&1; then
    if $SUDO pecl install swoole; then
      # Write a drop-in ini so the extension loads automatically.
      local cli_conf="/etc/php/${PHP_VERSION}/cli/conf.d"
      if [ -d "$cli_conf" ]; then
        $SUDO tee "${cli_conf}/99-swoole.ini" > /dev/null <<'INI'
; Auto-generated by setup_php.sh --swoole
extension=swoole.so
INI
        ok "Swoole installed via pecl and enabled in ${cli_conf}/99-swoole.ini"
      else
        warn "Swoole installed via pecl — add 'extension=swoole.so' to your php.ini manually."
      fi
    else
      warn "pecl install swoole failed — install it manually."
    fi
  else
    warn "Neither apt nor pecl available — install Swoole manually."
  fi
}

# ---------------------------------------------------------------------------
# 12. Testing environment setup (--testing)
# ---------------------------------------------------------------------------
install_testing_env() {
  log "Setting up testing environment (--testing)"

  cd "${ROOT_DIR}"

  # Copy .env.dusk → .env first so all subsequent commands boot correctly.
  local env_dusk="${ROOT_DIR}/.env.dusk"
  local env_file="${ROOT_DIR}/.env"
  if [ -f "$env_dusk" ]; then
    cp "$env_dusk" "$env_file"
    ok "Copied .env.dusk → .env"
    # Reset installed flag so microweber:install will run fresh.
    if grep -qE '^MW_IS_INSTALLED=' "$env_file" 2>/dev/null; then
      sed -i 's/^MW_IS_INSTALLED=.*/MW_IS_INSTALLED=0/' "$env_file"
      ok "Set MW_IS_INSTALLED=0 in .env"
    else
      printf '\nMW_IS_INSTALLED=0\n' >> "$env_file"
      ok "Added MW_IS_INSTALLED=0 to .env"
    fi
  else
    warn ".env.dusk not found — skipping .env copy. Create .env.dusk with your testing DB credentials."
  fi

  if ! command -v npm >/dev/null 2>&1; then
    warn "npm not found — skipping npm install / build. Install Node 18+ and re-run."
  else
    log "Running npm install in project root"
    npm install --no-audit --no-fund \
      && ok "npm install complete" \
      || warn "npm install failed — check output above."

    log "Running npm run build"
    npm run build \
      && ok "npm run build complete" \
      || warn "npm run build failed — check output above."
  fi

  if ! command -v php >/dev/null 2>&1; then
    warn "php not on PATH — cannot run artisan commands; skipping."
    return
  fi

  # Scaffold Dusk's base test case and .env.dusk stub if missing.
  if env APP_ENV=testing php artisan dusk:install 2>/dev/null; then
    ok "Dusk scaffolding installed (dusk:install)"
  else
    warn "artisan dusk:install failed — run: APP_ENV=testing php artisan dusk:install"
  fi

  # Run the Microweber installer to seed a clean test site.
  if [ -f "${ROOT_DIR}/storage/installed" ]; then
    ok "Microweber already installed (storage/installed present) — skipping microweber:install."
  else
    log "Running php artisan microweber:install"
    env APP_ENV=testing php artisan microweber:install \
      && ok "Microweber installed successfully." \
      || warn "microweber:install failed — check output above."
  fi
}

# ---------------------------------------------------------------------------
# Main
# ---------------------------------------------------------------------------
echo "=========================================="
echo " Microweber dependency setup"
echo " Root: ${ROOT_DIR}"
echo "=========================================="

if [ "$SKIP_SYSTEM" -eq 1 ]; then
  log "Skipping system PHP install (--skip-system); verifying what's present"
  php_version_ok || die "PHP >= ${PHP_MIN_MAJOR}.${PHP_MIN_MINOR} required but not found."
  ok "PHP $(php -r 'echo PHP_VERSION;') present"
  m="$(missing_exts | sed '/^$/d')"; [ -z "$m" ] && ok "Extensions OK" || warn "Missing exts: $(echo "$m" | tr '\n' ' ')"
else
  ensure_php
fi

if [ "$SKIP_COMPOSER" -eq 1 ]; then
  warn "Skipping composer install (--skip-composer)"
else
  ensure_composer
  install_php_deps
  install_global_laravel_installer
fi

if [ "$SKIP_NODE" -eq 1 ]; then
  warn "Skipping Node packages (--skip-node)"
else
  install_node_deps
fi

if [ "$MYSQL" -eq 1 ]; then
  install_mysql
fi

if [ "$PGSQL" -eq 1 ]; then
  install_pgsql
fi

if [ "$SWOOLE" -eq 1 ]; then
  install_swoole
fi

if [ "$APACHE_FPM" -eq 1 ]; then
  install_apache_fpm
fi

if [ "$APACHE_FCGI" -eq 1 ]; then
  install_apache_fcgi
fi

if [ "$DEV" -eq 1 ]; then
  install_dev_tools
fi

if [ "$TESTING" -eq 1 ]; then
  install_testing_env
fi

if [ "$INSTALL" -eq 1 ]; then
  install_microweber
fi

echo ""
echo -e "${GREEN}==========================================${NC}"
echo -e "${GREEN} Dependencies installed.${NC}"
echo -e "${GREEN}==========================================${NC}"
echo "Next steps (not run by this script):"
echo "  cp -n env_local .env 2>/dev/null || cp -n .env.example .env   # configure DB"
echo "  php artisan key:generate"
if [ "$INSTALL" -eq 1 ]; then
  echo "  (Microweber installer was run — site should be ready)"
else
  echo "  php artisan microweber:install   # or use --install flag next time"
fi
if [ "$DEV" -eq 1 ]; then
  echo "  php artisan dusk          # browser tests (Chrome + ChromeDriver were set up)"
  echo "  composer test-coverage    # PHPUnit with Xdebug/PCOV coverage → clover.xml"
fi
if [ "$TESTING" -eq 1 ]; then
  echo "  php artisan dusk          # run browser tests against the test site"
  echo "  bash run-tests.sh         # run PHPUnit suites against microweber_testing DB"
fi
if [ "$SWOOLE" -eq 1 ]; then
  echo "  php artisan octane:start --server=swoole   # start Octane with Swoole"
fi
  echo "  Set your VirtualHost DocumentRoot to ${ROOT_DIR}/public"
  echo "  Ensure <Directory> AllowOverride All so Laravel .htaccess is honoured"
fi
