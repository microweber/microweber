#!/usr/bin/env bash
#
# scripts/setup_php.sh — install Microweber's dependencies.
#
# Bootstraps a machine to run Microweber (Laravel 11 CMS):
#   1. PHP (>= 8.3) + the required extensions   (apt)
#   2. Composer                                  (apt: apt-get install composer)
#   3. PHP dependencies                          (composer install, allowed to run as root)
#   4. Node package dependencies + built bundles (npm install + npm run build)
#
# Must be run as root (it apt-installs packages and runs Composer as superuser).
# Idempotent: anything already present and new enough is detected and skipped.
#
# Usage:
#   scripts/setup_php.sh [options]
#
# Options:
#   --no-dev          composer install without dev dependencies (production)
#   --skip-system     do not apt-install PHP/extensions (use what's on PATH)
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
PHP_VERSION="${PHP_VERSION:-8.3}"

# Required PHP extensions (Laravel 11 + Microweber composer.json: ext-pdo/zip/dom).
REQUIRED_EXTS=(bcmath ctype curl dom fileinfo gd intl mbstring openssl pdo pdo_mysql tokenizer xml zip)

# apt package names for those extensions (php${PHP_VERSION}-* metapackages).
APT_PHP_PKGS=(cli common bcmath curl gd intl mbstring mysql xml zip sqlite3)

NO_DEV=0
SKIP_SYSTEM=0
SKIP_COMPOSER=0
SKIP_NODE=0

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

usage() { sed -n '2,40p' "${BASH_SOURCE[0]}" | sed 's/^# \{0,1\}//'; exit 0; }

# ---------------------------------------------------------------------------
# Argument parsing (runs before the root check so --help works for anyone)
# ---------------------------------------------------------------------------
while [ $# -gt 0 ]; do
  case "$1" in
    --no-dev)        NO_DEV=1 ;;
    --skip-system)   SKIP_SYSTEM=1 ;;
    --skip-composer) SKIP_COMPOSER=1 ;;
    --skip-node)     SKIP_NODE=1 ;;
    -h|--help)       usage ;;
    *) die "Unknown option: $1 (try --help)" ;;
  esac
  shift
done

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
    local pkgs=()
    for m in "${APT_PHP_PKGS[@]}"; do pkgs+=("php${PHP_VERSION}-${m}"); done
    $SUDO apt-get update -y
    if ! $SUDO apt-get install -y "php${PHP_VERSION}" "${pkgs[@]}"; then
      die "apt could not install php${PHP_VERSION}. On Ubuntu add Ondřej's PPA first: \
'sudo add-apt-repository ppa:ondrej/php && sudo apt-get update', then re-run."
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
        pdo|pdo_mysql) mod="mysql" ;;
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
  [ "$NO_DEV" -eq 1 ] && flags+=(--no-dev --optimize-autoloader)
  # shellcheck disable=SC2086
  $COMPOSER_BIN "${flags[@]}"
  ok "composer install complete"
}

# ---------------------------------------------------------------------------
# 4. Node package dependencies + bundles
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
fi

if [ "$SKIP_NODE" -eq 1 ]; then
  warn "Skipping Node packages (--skip-node)"
else
  install_node_deps
fi

echo ""
echo -e "${GREEN}==========================================${NC}"
echo -e "${GREEN} Dependencies installed.${NC}"
echo -e "${GREEN}==========================================${NC}"
echo "Next steps (not run by this script):"
echo "  cp -n env_local .env 2>/dev/null || cp -n .env.example .env   # configure DB"
echo "  php artisan key:generate"
echo "  php artisan migrate   # or the Microweber installer"
