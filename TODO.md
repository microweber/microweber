GOAL: Make the test suite pass and make it very fast and avoid OOM errors

- [x] 2026-03-15  Make more tests for the Filament modules/components and make sure each is loading without error and fix them using the Livewire test helper

## Test Suite Results (2026-03-15)

Summary: Unit (4/4 pass), Feature (219/275 pass), Core (237/243 pass), Modules mixed results.

| Suite              | Tests | Pass | Errors | Failures |
|--------------------|-------|------|--------|----------|
| Unit               |     4 |    4 |      0 |        0 |
| Feature            |   275 |  219 |     30 |       26 |
| Core               |   243 |  237 |      2 |        4 |
| Modules-Newsletter |   134 |   63 |     62 |        9 |
| Modules-Content    |    70 |   59 |      9 |        2 |
| Modules-Billing    |    85 |   83 |      1 |        1 |
| Modules-Group3     |   160 |  151 |      5 |        4 |
| Modules-Group4     |   127 |   71 |     53 |        3 |
| Modules-Group5     |   166 |  117 |     40 |        9 |
| Modules-Group6A    |   114 |  105 |      7 |        2 |
| Modules-Group6B    |    75 |   72 |      3 |        0 |
| Templates          |     0 |    0 |      0 |        0 |

### Critical: AiSettingsPage null user check (affects navigation tests)

- [ ] fix: AiSettingsPage::canAccess() calls `->can()` on null user — add null guard in Modules/Ai/Filament/Pages/AiSettingsPage.php:46

### Critical: AiService unresolvable dependency (12 tests)

- [ ] fix: AiService requires `$defaultDriver` string param but is not bound in container — register proper binding in AiServiceProvider or add default value

### Critical: SubscriptionPlan factory uses non-existent `is_active` column (5+ tests)

- [ ] fix: SubscriptionPlan factory references `is_active` column that doesn't exist in subscription_plans table — add migration or fix factory

### Critical: FrontendCheckoutRegressionTest — all 10 tests error (Cart/Checkout model issues)

- [ ] fix: FrontendCheckoutRegressionTest errors — likely missing table columns or model method issues in Cart/Checkout flow

### Critical: AdminCrudRegressionTest failures (10 tests)

- [ ] fix: Content::categories() method does not exist — add relationship or fix test in AdminCrudRegressionTest
- [ ] fix: AdminCrudRegressionTest CRUD tests (content, user, category, order, product) fail — resource pages return errors

### Critical: AdminAuthenticationTest (4 failures)

- [ ] fix: AdminAuthenticationTest — all 4 auth tests failing (login, dashboard render, invalid credentials)

### High: Newsletter module errors (62 errors, 9 failures)

- [ ] fix: Newsletter module — 62 errors across tests, likely model/migration issues

### High: Modules-Group4 errors (53 errors — mostly Payment module)

- [ ] fix: Payment module Filament tests — PaymentResourceTest form validation and many errors

### High: Modules-Group5 errors (40 errors — Settings/Translation module)

- [ ] fix: TranslationResourceTest and Settings module tests — 40 errors, likely Filament resource issues

### Medium: Filament Panel API mismatches

- [ ] fix: DarkModeTest — Panel::getFont() does not exist (Filament v5 API change)
- [ ] fix: DarkModeTest — safelist and z-index assertion failures
- [ ] fix: ResponsiveDesignTest — grid layout and spacing utility assertions fail
- [ ] fix: MobileNavigationCollapseTest — panel provider configuration test fails

### Medium: UsersResource test failures (Core + Feature)

- [ ] fix: UsersResourceTest — index_page_shows_all_records assertion fails (table record key mismatch)
- [ ] fix: UsersResourceAuthorizationTest — isUnauthorized() method does not exist on Response

### Medium: Core suite issues

- [ ] fix: MultilanguageTest::it_multilanguage_api error
- [ ] fix: TaggableFileCacheServiceProviderTest and TaggableFileStoreTest failures
- [ ] fix: LiveEdit ModuleSettingsItemsEditorTest failure

### Low: BillingRegressionTest (5 failures beyond is_active issue)

- [ ] fix: BillingRegressionTest — subscription trial, webhook signature, plan change, admin management, stats tests

### Low: Modules-Group6A (Restore module)

- [ ] fix: Restore module ReadersTest — 7 errors

### Low: Modules-Group6B (Testimonials module)

- [ ] fix: TestimonialsTableListFilamentTest — 3 errors
