#!/bin/bash

# Smoke Tests for Microweber Staging Deployment
# Quick sanity checks to verify deployment health

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
BASE_URL="${BASE_URL:-http://localhost}"
ADMIN_URL="${BASE_URL}/admin"
API_URL="${BASE_URL}/api"
TIMEOUT=30

echo -e "${GREEN}================================${NC}"
echo -e "${GREEN}Running Smoke Tests${NC}"
echo -e "${GREEN}================================${NC}"
echo "Timestamp: $(date)"
echo "Base URL: $BASE_URL"
echo ""

# Test counter
TESTS_PASSED=0
TESTS_FAILED=0

# Function to run a test
run_test() {
    local name="$1"
    local command="$2"
    local expected_status="${3:-200}"

    echo -n "Testing: $name ... "

    if eval "$command" > /dev/null 2>&1; then
        echo -e "${GREEN}PASSED${NC}"
        ((TESTS_PASSED++))
    else
        echo -e "${RED}FAILED${NC}"
        ((TESTS_FAILED++))
    fi
}

# Function to check HTTP status
check_http_status() {
    local url="$1"
    local expected="${2:-200}"
    local status=$(curl -s -o /dev/null -w "%{http_code}" --max-time $TIMEOUT "$url" 2>/dev/null || echo "000")
    [ "$status" == "$expected" ]
}

echo -e "${YELLOW}=== Frontend Tests ===${NC}"

# Test 1: Homepage loads
run_test "Homepage loads" "check_http_status '$BASE_URL/' 200"

# Test 2: Admin login page
run_test "Admin login page" "check_http_status '$BASE_URL/admin/login' 200"

# Test 3: Static assets (CSS)
run_test "CSS assets" "check_http_status '$BASE_URL/css/app.css' 200"

# Test 4: Static assets (JS)
run_test "JS assets" "check_http_status '$BASE_URL/js/app.js' 200"

# Test 5: Favicon
run_test "Favicon" "check_http_status '$BASE_URL/favicon.ico' 200"

echo ""
echo -e "${YELLOW}=== API Tests ===${NC}"

# Test 6: API health endpoint
run_test "API health check" "check_http_status '$API_URL/health' 200"

# Test 7: API cart endpoint
run_test "API cart endpoint" "check_http_status '$API_URL/cart' 200"

# Test 8: API products endpoint
run_test "API products endpoint" "check_http_status '$API_URL/products' 200"

echo ""
echo -e "${YELLOW}=== Database Tests ===${NC}"

# Test 9: Database connection
run_test "Database connection" "php artisan tinker --execute='echo DB::connection()->getPdo() ? \"OK\" : \"FAIL\";' 2>/dev/null | grep -q OK"

# Test 10: Migrations are up to date
run_test "Migrations current" "php artisan migrate:status | grep -q 'No pending migrations'"

echo ""
echo -e "${YELLOW}=== Cache Tests ===${NC}"

# Test 11: Config cache exists
run_test "Config cached" "test -f bootstrap/cache/config.php"

# Test 12: Route cache exists
run_test "Routes cached" "test -f bootstrap/cache/routes.php"

# Test 13: View cache exists
run_test "Views cached" "test -d storage/framework/views"

echo ""
echo -e "${YELLOW}=== Storage Tests ===${NC}"

# Test 14: Storage directory writable
run_test "Storage writable" "test -w storage"

# Test 15: Bootstrap cache writable
run_test "Cache writable" "test -w bootstrap/cache"

# Test 16: Uploads directory exists
run_test "Uploads directory" "test -d storage/app/public"

echo ""
echo -e "${YELLOW}=== Environment Tests ===${NC}"

# Test 17: APP_ENV is set
run_test "APP_ENV set" "php artisan tinker --execute='echo env(\"APP_ENV\") ? \"OK\" : \"FAIL\";' 2>/dev/null | grep -v FAIL"

# Test 18: APP_KEY is set (not the default)
run_test "APP_KEY set" "php artisan tinker --execute='echo (env(\"APP_KEY\") && env(\"APP_KEY\") !== \"base64:SomeRandomString\") ? \"OK\" : \"FAIL\";' 2>/dev/null | grep OK"

echo ""
echo -e "${YELLOW}=== Regression Tests (Quick) ===${NC}"

# Test 19: Admin CRUD resources accessible
run_test "Admin CRUD accessible" "check_http_status '$BASE_URL/admin' 302"

# Test 20: Login functionality
run_test "Login form present" "curl -s '$BASE_URL/admin/login' --max-time $TIMEOUT | grep -q 'login'"

echo ""
echo -e "${GREEN}================================${NC}"
echo -e "${GREEN}Smoke Tests Complete${NC}"
echo -e "${GREEN}================================${NC}"
echo "Tests Passed: $TESTS_PASSED"
echo -e "Tests Failed: ${RED}$TESTS_FAILED${NC}"
echo ""

if [ $TESTS_FAILED -eq 0 ]; then
    echo -e "${GREEN}All smoke tests passed! Deployment is healthy.${NC}"
    exit 0
else
    echo -e "${RED}Some smoke tests failed. Please investigate.${NC}"
    exit 1
fi
