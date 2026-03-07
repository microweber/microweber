# Static Analysis Summary

**Date:** 2026-03-07
**Project:** Microweber Filament v5 Migration

## Tools Run

### 1. PHPStan (Level 5)
- **Configuration:** `phpstan.neon.dist`
- **Paths Analyzed:** app/, src/, Modules/, tests/
- **Result:** 11,473 errors found
- **Report:** `build/phpstan-report.txt`

#### Top Issue Categories:
1. **Undefined properties** - Model properties not properly recognized (most common)
2. **Undefined static methods** - Eloquent methods like `where()`, `create()`, `find()`
3. **Class not found** - Missing imports or autoloading issues
4. **Type mismatches** - Parameter and return type issues

#### Key Findings:
- Many errors are due to PHPStan not recognizing Laravel's magic methods (Eloquent)
- Would benefit from Larastan extension
- Most errors are in the `Modules/` directory

### 2. Rector
- **Configuration:** `rector.php`
- **Status:** Configuration created, dry-run attempted
- **Issue:** Process timed out due to large codebase
- **Note:** Parallel processing disabled to avoid memory issues

### 3. PHP Insights
- **Status:** Not installed
- **Recommendation:** Can be installed via Composer if needed

## Configuration Files Created

1. **`phpstan.neon.dist`** - Root PHPStan configuration
2. **`rector.php`** - Root Rector configuration

## Recommendations

1. **Install Larastan** for better Laravel support:
   ```bash
   composer require --dev larastan/larastan
   ```

2. **Run Rector in smaller batches:**
   ```bash
   vendor/bin/rector process app/ --dry-run
   vendor/bin/rector process src/ --dry-run
   vendor/bin/rector process Modules/ --dry-run
   ```

3. **Fix high-priority issues first:**
   - Actual class not found errors
   - Undefined methods on non-magic classes
   - Type mismatches in function signatures

4. **Consider PHP Insights for code quality metrics**

## Next Steps

- [ ] Address critical PHPStan errors (class not found, undefined methods on non-Eloquent classes)
- [ ] Install Larastan to reduce false positives from Eloquent magic methods
- [ ] Run Rector on specific directories to apply automated fixes
- [ ] Add static analysis to CI/CD pipeline
