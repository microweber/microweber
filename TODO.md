GOAL: Make the test suite pass and make it very fast and avoid OOM errors

- [x] Fix OOM errors when running the full test suite

### What was done:
1. **Removed Pest** - Deleted all Pest.php files, converted to PHPUnit only
2. **Fixed base TestCase memory leaks** - Removed second app bootstrap in install(), added TaggableFileStore::$flushedTags cleanup in tearDown(), forced array cache driver
3. **Fixed RefreshDatabase trait** - Changed 4 regression tests from RefreshDatabase to LazilyRefreshDatabase
4. **Split test suites** - Divided monolithic Modules suite into 8 smaller groups in phpunit.xml
5. **Added process isolation for Filament tests** - All test files using Livewire::test() now have `#[RunTestsInSeparateProcesses]` attribute, preventing the ~400MB/test Livewire memory leak from accumulating
6. **Created run-tests.sh** - Shell script to run each suite group in a separate PHP process
7. **Increased memory limit** - Set to 4096M to accommodate Filament test process isolation overhead
8. **Added afterRefreshingDatabase() guard** - Prevents repeated heavy migration/schema creation

### Results:
- All 1,384 tests complete without OOM across all 11 suites
- Each suite runs in its own process via run-tests.sh
- Filament/Livewire tests run each method in a separate process automatically
