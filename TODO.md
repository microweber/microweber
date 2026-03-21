## Todo - Project Test Results (2026-03-21)

### Critical Issues

- [x] 2026-03-21 fix: PHP Fatal error in Modules/Ai/Events/ProgressEvent.php:9 - Class cannot extend interface NeuronAI\Workflow\Event (PHPStan failure)
- [x] 2026-03-21 fix: Core test suite error - MwFileUploadTest::it_upload_to_s3_disk_works - UNIQUE constraint failed on users.email (database integrity issue)
- [ ] fix: Modules/Billing test failure - SubscriptionPlanTest expects 2 features but finds 16 (test assertion mismatch at line 45)

### High Priority Issues

- [ ] fix: Modules/Group3 test failures - 4 failures in CouponResourceTest (table sorting and record visibility issues)
- [ ] fix: Modules/Group3 errors - 5 errors in Cart and Coupons tests (Livewire rendering issues)
- [ ] fix: Modules/Group6A test failures - MailTemplateResourceTest filter assertion failure (line 145)
- [ ] fix: Modules/Group6A errors - 7 errors in Marketplace and MailTemplate tests
- [ ] fix: Modules/Group6B failures - ExportTest::it_full_export expected 59 but got 64 (line 117)
- [ ] fix: Modules/Group6B errors - 1 error in SliderSettingsFilamentTest

### Medium Priority Issues

- [ ] fix: Feature test warnings - PHPUnit warning about abstract AuthorizationTest class
- [ ] fix: Feature test deprecations - 2 PHPUnit deprecation warnings
- [ ] fix: Feature test skips - 13 tests are being skipped (investigate why)
- [ ] security: npm audit vulnerabilities - ajv (ReDoS), elliptic (crypto), esbuild (CORS), mdast-util-to-hast (XSS)

### Low Priority Issues

- [ ] update: Composer dependency constraints - Replace exact version constraints with semantic versioning
- [ ] update: Unbound version constraints (*) in composer.json should use specific versions
- [ ] verify: Templates test suite - No tests executed (check if tests exist)

### Summary

**Test Results:**
- Unit: PASS (4 tests, 4 assertions)
- Feature: PARTIAL (273 tests, 848 assertions, 1 warning, 2 deprecations, 13 skipped)
- Core: FAIL (243 tests, 1 error)
- Modules-Newsletter: PASS (128 tests, 517 assertions)
- Modules-Content: Timeout (requires separate run)
- Modules-Billing: FAIL (85 tests, 1 failure)
- Modules-Group3: FAIL (160 tests, 5 errors, 4 failures)
- Modules-Group4: Timeout (requires separate run)
- Modules-Group5: Timeout (requires separate run)
- Modules-Group6A: FAIL (114 tests, 7 errors, 1 failure)
- Modules-Group6B: FAIL (75 tests, 1 error, 1 failure)
- Templates: SKIP (0 tests executed)

**Build Status:**
- PHPStan: FAIL (Fatal error in AI module)
- Composer: VALID (with warnings)
- NPM Audit: VULNERABILITIES FOUND (security issues)

**PHP Version:** 8.5.3
**PHPUnit Version:** 11.5.50
**Laravel Version:** 11.x
