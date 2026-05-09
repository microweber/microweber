#!/usr/bin/env bash
#
# AI-113 / TICKET-CP (cycle-103 2026-05-09): grep-gate for CSP-violating
# Blade patterns.
#
# Mirror of the CI grep-gate in .github/workflows/cicd-pipeline.yml.
# Use locally as a pre-commit hook so devs catch the offence locally
# instead of failing CI.
#
# Forbidden patterns:
#   1. style="...background-image: url('{{ thumbnail|asset..."
#      Inline style with a Blade-interpolated URL. Blade escapes ' to
#      &#039; BEFORE the browser HTML-decodes the attribute value, so
#      apostrophes in the thumbnail URL can break out of url(...) and
#      inject arbitrary CSS. Convert to <img> via responsive_thumbnail().
#   2. onclick="...{{..."
#      Inline onclick with a Blade interpolation. Same shape problem
#      AND a CSP `script-src 'self'` violation. Convert to data-mw-*
#      attributes + a delegated addEventListener.
#
# Wiring as a git pre-commit hook (no husky-runtime required):
#
#   ln -sf ../../scripts/grep-gate.sh .git/hooks/pre-commit
#
# Or copy verbatim into `.husky/pre-commit` if husky is installed.
#
# CI calls this same script via the cicd-pipeline.yml step
# "Grep-gate forbidden Blade patterns".

set -e

# Mode 1 (default): scan only files staged for commit (fast).
# Mode 2 (--all):   scan the whole tree (used in CI).
MODE="staged"
if [ "$1" = "--all" ]; then
    MODE="all"
fi

if [ "$MODE" = "staged" ]; then
    FILES=$(git diff --cached --name-only --diff-filter=ACMR | grep '\.blade\.php$' || true)
    if [ -z "$FILES" ]; then
        echo "AI-113 grep-gate: no staged *.blade.php files — skipping"
        exit 0
    fi
fi

# Exclude paths:
#   - vendor/, node_modules/, storage/  (third-party + caches)
#   - Templates/                         (theme packages have their own
#                                          audit pipeline; many are
#                                          separate composer packages
#                                          like Big2 / Bootstrap)
EXCLUDES=(--exclude-dir=vendor --exclude-dir=node_modules --exclude-dir=storage --exclude-dir=Templates)

scan_pattern() {
    local pattern="$1"
    local raw_hits

    if [ "$MODE" = "staged" ]; then
        raw_hits=$(echo "$FILES" | xargs -r grep -nE "$pattern" 2>/dev/null || true)
    else
        raw_hits=$(grep -rEn --include='*.blade.php' "${EXCLUDES[@]}" "$pattern" . 2>/dev/null || true)
    fi

    # Strip lines that occur inside Blade `{{-- ... --}}` doc-comments
    # (audit-trail comments often quote the literal pre-fix shape). We
    # do this per-file by reading each file and only flagging the line
    # if it sits OUTSIDE any `{{-- ... --}}` block.
    if [ -z "$raw_hits" ]; then
        return 0
    fi

    local real_hits=""
    while IFS= read -r line; do
        [ -z "$line" ] && continue
        local fp lineno
        fp=$(echo "$line" | cut -d: -f1)
        lineno=$(echo "$line" | cut -d: -f2)
        # Strip leading "./" from grep -r output so paths match the
        # waiver file's repo-relative format.
        local rel_fp="${fp#./}"

        if [ ! -f "$fp" ] || [ -z "$lineno" ]; then
            real_hits="$real_hits$line"$'\n'
            continue
        fi

        # Skip if this <path>:<lineno> pair is on the AI-113 waivers
        # ratchet list. Each waiver line begins with `<path>:<lineno>`
        # followed by whitespace or end-of-line; comments (#) are
        # ignored by grep-anchored line-start.
        if [ -f "$WAIVERS_FILE" ] && grep -qE "^${rel_fp}:${lineno}([[:space:]]|#|$)" "$WAIVERS_FILE" 2>/dev/null; then
            continue
        fi

        # Skip if the offending line sits inside a Blade `{{-- ... --}}`
        # doc-comment (audit-trail comments quote the literal pre-fix
        # shape).
        if python3 -c "
import re, sys
fp, target = sys.argv[1], int(sys.argv[2])
src = open(fp).read()
stripped = re.sub(r'\{\{--[\s\S]*?--\}\}', lambda m: '\n' * m.group(0).count('\n'), src)
lines = stripped.splitlines()
if 0 <= target - 1 < len(lines):
    line = lines[target - 1]
    sys.exit(0 if line.strip() else 1)
sys.exit(1)
" "$fp" "$lineno"; then
            real_hits="$real_hits$line"$'\n'
        fi
    done <<< "$raw_hits"

    printf "%s" "$real_hits"
}

# Waivers file: lists known existing violations as <path>:<lineno>
# pairs. The gate skips these but enforces zero NEW offences anywhere
# else (ratchet pattern). See scripts/grep-gate-waivers.txt for the
# format.
WAIVERS_FILE="$(dirname "$0")/grep-gate-waivers.txt"

FAIL=0

# Pattern 1: inline style background-image url + Blade interpolation.
PATTERN_1='style="[^"]*background-image:\s*url\(\s*'"'"'?\{\{\s*(thumbnail|asset)'
HITS=$(scan_pattern "$PATTERN_1")
if [ -n "$HITS" ]; then
    echo "$HITS"
    echo
    echo "✗ AI-113: forbidden inline style=\"...background-image: url('{{ thumbnail|asset...\" pattern."
    echo "  CSP-violation + apostrophe-injection sink."
    echo "  Fix: convert to <img> via responsive_thumbnail()."
    FAIL=1
fi

# Pattern 2: inline onclick + Blade interpolation.
PATTERN_2='onclick="[^"]*\{\{'
HITS=$(scan_pattern "$PATTERN_2")
if [ -n "$HITS" ]; then
    echo "$HITS"
    echo
    echo "✗ AI-113: forbidden inline onclick=\"...{{...\" pattern."
    echo "  CSP-violation + apostrophe-breaks-JS."
    echo "  Fix: convert to data-mw-* attributes + delegated addEventListener."
    FAIL=1
fi

if [ "$FAIL" -ne 0 ]; then
    echo
    echo "AI-113 grep-gate FAILED."
    echo "Reference: tests/Feature/Ai113CiGrepGateContractTest.php"
    exit 1
fi

echo "AI-113 grep-gate: no forbidden patterns found ✓"
exit 0
