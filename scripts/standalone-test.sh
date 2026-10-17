#!/usr/bin/env bash
#
# standalone-test.sh — verify a packages/* package installs and tests as a
# standalone Laravel package, fully decoupled from the Microweber monorepo.
#
# It copies the package to a throwaway workspace, injects a wildcard `path`
# repository for all sibling packages/* (so microweber-packages/* deps resolve
# locally without Packagist), runs `composer install`, then runs the package's
# own phpunit suite (Orchestra Testbench boots a real Laravel app for it).
#
# Usage:
#   scripts/standalone-test.sh <package-dir-name> [--keep]
#   scripts/standalone-test.sh microweber-pagination
#   scripts/standalone-test.sh --all            # run every scaffolded package
#
# Exit non-zero if install or tests fail. Does NOT touch the real package dir.

set -uo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PKGROOT="$ROOT/packages"
KEEP=0

# Packages whose tests exercise the INTEGRATED CMS (full data-layer / core app
# runtime) and are therefore monorepo-only by design. They run + pass via the root
# phpunit.xml in CI; recreating the CMS inside a per-package Testbench harness is a
# non-goal. The harness reports them as monorepo-only rather than failing.
MONOREPO_ONLY=" microweber-core microweber-database microweber-db-export "

run_one() {
  local pkg="$1"
  local dir="$PKGROOT/$pkg"
  if [ ! -f "$dir/composer.json" ]; then
    echo "SKIP  $pkg (no composer.json)"; return 3
  fi
  if [[ "$MONOREPO_ONLY" == *" $pkg "* ]]; then
    echo "SKIP  $pkg (monorepo-only — integrated CMS data/core layer; runs in root phpunit.xml CI)"; return 3
  fi
  if [ ! -f "$dir/phpunit.xml" ] && [ ! -f "$dir/phpunit.xml.dist" ]; then
    echo "SKIP  $pkg (no phpunit.xml — not scaffolded for standalone tests)"; return 3
  fi

  local ws; ws="$(mktemp -d)"
  trap 'rm -rf "$ws"' RETURN
  cp -a "$dir/." "$ws/"
  rm -rf "$ws/vendor" "$ws/composer.lock"

  pushd "$ws" >/dev/null
  # Drop any monorepo-relative path repositories the package declares ("../microweber-x"),
  # which don't resolve from this throwaway workspace; we replace them with an absolute
  # wildcard path repo below so every sibling resolves regardless of layout.
  # decode as OBJECTS (not assoc) so empty "{}" keys (require-dev, scripts, config) are
  # preserved as objects — assoc=true would turn them into [] and break composer's schema.
  php -r '$f="composer.json";$j=json_decode(file_get_contents($f));unset($j->repositories);file_put_contents($f,json_encode($j,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));'
  # wildcard path repo so any sibling microweber-packages/* resolves locally
  composer config repositories.mw-monorepo path "$PKGROOT/*" >/dev/null 2>&1
  # keep stability loose for dev sibling versions
  composer config minimum-stability dev >/dev/null 2>&1
  composer config prefer-stable true >/dev/null 2>&1

  local out
  if ! out="$(COMPOSER_NO_INTERACTION=1 composer install --no-progress 2>&1)"; then
    echo "FAIL  $pkg — composer install failed"
    echo "$out" | tail -8 | sed 's/^/        /'
    popd >/dev/null; return 1
  fi

  local phpunit="./vendor/bin/phpunit"
  [ -x "$phpunit" ] || phpunit="$ROOT/vendor/bin/phpunit"
  if ! out="$("$phpunit" 2>&1)"; then
    echo "FAIL  $pkg — tests failed"
    echo "$out" | tail -12 | sed 's/^/        /'
    popd >/dev/null; return 2
  fi
  local summary; summary="$(echo "$out" | grep -E 'OK \(|Tests: ' | tail -1)"
  echo "PASS  $pkg — $summary"
  popd >/dev/null; return 0
}

if [ "${1:-}" = "--all" ]; then
  rc=0
  for d in "$PKGROOT"/*/; do
    p="$(basename "$d")"
    [ -f "$d/phpunit.xml" ] || [ -f "$d/phpunit.xml.dist" ] || continue
    run_one "$p" || rc=1
  done
  exit $rc
fi

[ "${2:-}" = "--keep" ] && KEEP=1
run_one "${1:?usage: standalone-test.sh <package> | --all}"
