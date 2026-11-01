#!/bin/bash
#
# Run the full test suite in separate processes to avoid OOM errors.
# PHP leaks ~6MB/test from internal memory fragmentation that cannot be GC'd,
# so each suite group is kept small and runs in its own process.
# Filament/Livewire tests use PHPUnit's RunTestsInSeparateProcesses attribute
# to run each test method in its own PHP process automatically.
#
# Usage:
#   ./run-tests.sh                      # Run all suites
#   ./run-tests.sh Unit Core            # Run specific suites
#   ./run-tests.sh --list               # List available suites
#   ./run-tests.sh --modules            # Run only module groups
#

set -euo pipefail

PHPUNIT="php vendor/bin/phpunit"
MODULE_SUITES=("Modules-Newsletter" "Modules-Content" "Modules-Billing" "Modules-Group3" "Modules-Group4" "Modules-Group5" "Modules-Group6A" "Modules-Group6B")
# Derive the full suite list from phpunit.xml so it stays in sync as suites are
# added/removed (the old hardcoded list referenced a since-removed "Core" suite).
mapfile -t ALL_SUITES < <(grep -oE '<testsuite name="[^"]+"' phpunit.xml | sed -E 's/.*name="([^"]+)"/\1/')
FAILED=0
RESULTS=()

if [ "${1:-}" = "--list" ]; then
    echo "Available test suites:"
    for suite in "${ALL_SUITES[@]}"; do
        echo "  - $suite"
    done
    exit 0
fi

if [ "${1:-}" = "--modules" ]; then
    SUITES=("${MODULE_SUITES[@]}")
elif [ $# -gt 0 ]; then
    SUITES=("$@")
else
    SUITES=("${ALL_SUITES[@]}")
fi

echo "Running test suites: ${SUITES[*]}"
echo "============================================"

for suite in "${SUITES[@]}"; do
    echo ""
    echo "--- Running suite: $suite ---"

    if [ "$suite" = "Feature" ]; then
        # The Feature suite (~4000 tests) overflows the per-process 4GB
        # memory_limit set in microweber-core's TestCase because of PHP's
        # ~6MB/test leak, so a single --testsuite=Feature run OOMs. Run its test
        # files in batches instead — each batch is its own PHP process with a
        # fresh 4GB budget. Same reasoning that split the Modules-* suites, but
        # Feature's files are flat so we batch the file list rather than add
        # <testsuite> entries. Batch count is overridable via FEATURE_BATCHES.
        mapfile -t FEATURE_FILES < <(find tests/Feature -name '*Test.php' -type f \
            ! -path '*/Fixtures/*' ! -path '*/Concerns/*' | sort)
        FEATURE_RUNNABLE=()
        for ff in "${FEATURE_FILES[@]}"; do
            # Skip abstract base cases / traits (e.g. the abstract AuthorizationTest):
            # phpunit aborts the entire run if handed a non-instantiable class file.
            if grep -qE '^[[:space:]]*(abstract class|trait )' "$ff"; then
                continue
            fi
            FEATURE_RUNNABLE+=("$ff")
        done
        FEATURE_COUNT=${#FEATURE_RUNNABLE[@]}
        FEATURE_BATCHES="${FEATURE_BATCHES:-8}"
        FEATURE_PER=$(( (FEATURE_COUNT + FEATURE_BATCHES - 1) / FEATURE_BATCHES ))
        feature_failed=0
        for (( fb=0; fb<FEATURE_BATCHES; fb++ )); do
            chunk=( "${FEATURE_RUNNABLE[@]:fb*FEATURE_PER:FEATURE_PER}" )
            [ ${#chunk[@]} -eq 0 ] && continue
            echo "  Feature batch $(( fb + 1 ))/$FEATURE_BATCHES (${#chunk[@]} files)"
            if $PHPUNIT "${chunk[@]}" --no-progress --display-errors; then
                :
            else
                feature_failed=1
            fi
        done
        if [ "$feature_failed" -eq 0 ]; then
            RESULTS+=("PASS: $suite")
        else
            RESULTS+=("FAIL: $suite (one or more batches failed)")
            FAILED=1
        fi
        echo "--- Suite $suite complete ---"
        continue
    fi

    if $PHPUNIT --testsuite="$suite" --no-progress --display-errors 2>&1; then
        RESULTS+=("PASS: $suite")
    else
        EXIT_CODE=$?
        RESULTS+=("FAIL: $suite (exit code: $EXIT_CODE)")
        FAILED=1
    fi

    echo "--- Suite $suite complete ---"
done

echo ""
echo "============================================"
echo "Summary:"
for result in "${RESULTS[@]}"; do
    echo "  $result"
done
echo "============================================"

exit $FAILED
