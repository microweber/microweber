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
ALL_SUITES=("Unit" "Feature" "Core" "${MODULE_SUITES[@]}" "Templates")
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
