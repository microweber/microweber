## Done

- [x] 2026-04-01  feat: migrate old MW v2 admin design to Filament 5
  - Reconnaissance: captured screenshots and extracted CSS design tokens from demo.microweber.org
  - Created WelcomeWidget with "Welcome back, [username]" greeting matching MW v2 dashboard
  - Created DashboardQuickStatsWidget with colored icon cards (Emails, Comments, Sales, Orders)
  - Added dashboard widget CSS (welcome heading, 2x2 stat card grid with colored icons)
  - Updated Dashboard page to display welcome + stats widgets before analytics
  - Removed redundant "Dashboard" heading (replaced by welcome message)
  - Theme CSS (microweber-theme-v3.scss) already covers: sidebar, topbar, tables, forms, buttons, badges, tabs, breadcrumbs, pagination, modals, dark mode
  - Built and compiled theme CSS
  - Visual QA verified across: dashboard, pages list, orders, settings, create page

- [x] 2026-04-01  feat: migrate dashboard chart widget from Chart.js to ECharts
  - Created SiteStatsEchartsWidget replacing SiteStatsDashboardChart (Chart.js)
  - Built ECharts area chart with smooth line, gradient fill, matching MW v2 style
  - Added Statistics card UI: icon + title, online count, Daily/Weekly/Monthly period tabs
  - Footer with views/visitors counters and "Show more" link
  - Updated SiteStatsServiceProvider to register new ECharts widget
  - Added .mw-stats-card CSS with dark mode support to theme SCSS
  - Built and compiled theme CSS

- [x] 2026-04-01  fix: sidebar inconsistencies between MW v2 and Filament 5
  - Fixed truncated sidebar text ("Variant Attri..." now shows full "Variant Attributes")
  - Removed white-space: nowrap from sidebar labels, allowing text to wrap naturally
  - Improved group header labels: darker color (#4a5568), slightly larger (0.7rem), better letter-spacing
  - Added subtle spacing (4px margin/padding) between navigation groups
  - Softened group separator border opacity (0.14 → 0.10)
  - Widened sidebar from 15rem to 16rem to accommodate longer labels
  - Fixed dark mode group separator border color (rgba white 6%)
  - Visual QA verified across: dashboard, pages list, settings

- [x] 2026-04-01  plan: full admin page mapping (old MW v2 → Filament 5)
  - Enumerated all old admin pages/routes and all Filament resources/pages
  - Created migration checklist below

- [x] 2026-04-01  plan: add workflows from agents.tools.ooyes.net to TODO
  - Fetched 33 workflows across 11 cycles from https://agents.tools.ooyes.net/workflows.yml
  - Added all workflows as actionable TODO items below

---

## Workflows — agents.tools.ooyes.net

### Dev Cycle
- [x] 2026-04-01  01 Test the Project — Run tests, verify build, check dependencies, populate TODO.md with issues
  - https://agents.tools.ooyes.net/workflows/dev-cycle/01-test-the-project.yml
  - Results: 2,416 tests total, 1 failure, 6 risky, 43 skipped, 0 errors
  - Build: theme CSS compiles, webpack OK, composer valid
  - Dependencies: 0 PHP CVEs, 2 high npm CVEs (lodash.set)
  - Issues found and added below

### Test & Build Issues Found
- [x] 2026-04-01  fix: AutomatedBackupTest date-dependent assertion uses hardcoded month _(ref: workflows/dev-cycle/01-test-the-project.yml)_
  - File: `Modules/Backup/Tests/AutomatedBackupTest.php:123`
  - Fix: replaced hardcoded `assertEquals(4, ...)` with dynamic `Carbon::now()->startOfMonth()->addMonth()->month`
- [x] fix: CheckoutClientTest::it_checkout_client_names flaky — missing 'success' key in response _(ref: workflows/dev-cycle/01-test-the-project.yml)_
  - File: `Modules/Shop/Tests/Unit/CheckoutClientTest.php:74`
  - Root cause: `shop_require_terms` option set by other tests (CheckoutWizardTest) leaks via shared DB, causing `validateCheckoutData()` to reject checkout
  - Fix: reset `shop_require_terms` option at test start, verify cart is populated, assert no error before asserting success
- [x] fix: 6 risky tests — output buffers not closed (Filament auth/authorization tests) _(ref: workflows/dev-cycle/01-test-the-project.yml)_
  - `tests/Feature/Filament/Pages/TemplateCustomizerPageTest.php:228`
  - `tests/Feature/Filament/PanelAccessControlTest.php:51, :231`
  - `tests/Feature/Filament/AuthorizationTest.php:112`
  - `tests/Feature/Filament/UsersResourceAuthorizationTest.php:35`
  - `Modules/Billing/Tests/Unit/AuthorizationTest.php:15`
  - Fix: added `#[After]` output buffer cleanup to `InteractsWithFilamentPanel` trait, and `tearDown` to `BillingTestCase`
- [x] fix: npm high-severity CVE in lodash.set (prototype pollution) — run `npm audit fix` _(ref: workflows/dev-cycle/01-test-the-project.yml)_
  - lodash.set CVE already resolved; ran `npm audit fix` reducing vulnerabilities from 13→9
  - Remaining 9 are deep deps of laravel-mix (elliptic, webpack-dev-server) with no fix available
- [x] chore: Sass deprecation warnings — `unquote()` global built-in will be removed in Dart Sass 3.0 _(ref: workflows/dev-cycle/01-test-the-project.yml)_
  - File: `packages/frontend-assets/resources/assets/css/scss/tree.scss:396-402`
  - Fix: removed `unquote()` and `-webkit-calc` entirely, using plain `calc()` which modern Sass handles natively
  - Remaining `unquote()` calls are in third-party font libraries (tabler-icons, materialdesignicons)
- [x] 2026-04-01  02 Test the UI — Test interface components, check browser compatibility and accessibility
  - https://agents.tools.ooyes.net/workflows/dev-cycle/02-test-the-project-ui.yml
  - Tested 12 admin pages via Playwright browser automation
  - Results: 10 pages load without errors, 1 JS error on create/edit forms, 1 settings page title issue

### UI Issues Found _(ref: workflows/dev-cycle/02-test-the-project-ui.yml)_
- [ ] fix(js): `mwTreeFormComponent is not defined` — Alpine.js component missing on Create/Edit Page, Post, Product forms
  - Affects: Parent page tree selector, category tree selector
  - Console error: `ReferenceError: mwTreeFormComponent is not defined` in Livewire/Alpine init
  - Pages affected: `/admin/pages/create`, `/admin/posts/create`, `/admin/products/create`, and their edit equivalents
- [ ] fix(ui): Settings hub page (`/admin/settings`) missing page title prefix — shows "Microweber" instead of "Settings - Microweber"
- [ ] fix(ui): Settings hub page and General Settings page (`/admin/settings/general`) missing breadcrumb navigation
- [ ] fix(ui): Dashboard stat cards use `<h5>` tags for numeric values (417, 95, etc.) — semantic misuse, should be `<span>` or `<p>`
- [ ] fix(ui): Mobile (375px) — Orders table columns truncated, Status/Amount/Paid/Actions overflow off-screen with no scroll indicator
- [ ] fix(ui): Mobile (375px) — Topbar user avatar slightly clipped on right edge
- [ ] fix(a11y): No skip-navigation link present on any admin page
- [ ] fix(a11y): Heading hierarchy issue — H2 "Add new" dropdown appears in DOM before H1 page title on all pages

- [ ] 03 Code Review — Analyse code quality, security, performance, and best practices
  - https://agents.tools.ooyes.net/workflows/dev-cycle/03-code-review.yml

### Scope Cycle
- [ ] 01 Define Product Scope — Analyse the codebase and write a comprehensive SCOPE.md
  - https://agents.tools.ooyes.net/workflows/scope-cycle/01-define-product-scope.yml
- [ ] 02 Actionable Plan → TODO — Break the scope into developer-ready tasks and populate TODO.md
  - https://agents.tools.ooyes.net/workflows/scope-cycle/02-make-actionable-plan.yml

### Feature Cycle
- [ ] 01 Define Feature — Write a complete spec with acceptance criteria before any code is written
  - https://agents.tools.ooyes.net/workflows/feature-cycle/01-define-feature.yml
- [ ] 02 Design and Review — Technical design covering data, services, API, security, and performance
  - https://agents.tools.ooyes.net/workflows/feature-cycle/02-design-and-review.yml
- [ ] 03 Implement — Execute the design task-by-task with verification after each change
  - https://agents.tools.ooyes.net/workflows/feature-cycle/03-implement.yml
- [ ] 04 Test — Verify acceptance criteria, edges, security, and performance
  - https://agents.tools.ooyes.net/workflows/feature-cycle/04-test.yml
- [ ] 05 Release — Pre-release checklist, deployment, smoke test, rollback plan
  - https://agents.tools.ooyes.net/workflows/feature-cycle/05-release.yml

### Bug Cycle
- [ ] 01 Reproduce — Establish an on-demand reproduction and write a failing regression test
  - https://agents.tools.ooyes.net/workflows/bug-cycle/01-reproduce.yml
- [ ] 02 Diagnose — Find the exact root cause using hypothesis-driven investigation
  - https://agents.tools.ooyes.net/workflows/bug-cycle/02-diagnose.yml
- [ ] 03 Fix and Verify — Apply a minimal targeted fix, verify tests pass, commit with full context
  - https://agents.tools.ooyes.net/workflows/bug-cycle/03-fix-and-verify.yml

### Release Cycle
- [ ] 01 Pre-Release Check — Tests, security scan, changelog, docs, migration safety — go/no-go gate
  - https://agents.tools.ooyes.net/workflows/release-cycle/01-pre-release-check.yml
- [ ] 02 Release — Version tag, changelog, deploy, migrations, health check
  - https://agents.tools.ooyes.net/workflows/release-cycle/02-release.yml
- [ ] 03 Post-Release — 30-minute monitoring, cleanup, stakeholder communication, follow-up tasks
  - https://agents.tools.ooyes.net/workflows/release-cycle/03-post-release.yml

### Refactor Cycle
- [ ] 01 Assess — Identify code quality issues, establish test safety net, risk assessment
  - https://agents.tools.ooyes.net/workflows/refactor-cycle/01-assess.yml
- [ ] 02 Plan — Map current state, define target state, sequence atomic steps
  - https://agents.tools.ooyes.net/workflows/refactor-cycle/02-plan.yml
- [ ] 03 Execute — One step at a time, test after every change, commit each step
  - https://agents.tools.ooyes.net/workflows/refactor-cycle/03-execute.yml

### Incident Cycle
- [ ] 01 Detect and Triage — Confirm the incident, assess severity, assemble the response team
  - https://agents.tools.ooyes.net/workflows/incident-cycle/01-detect-and-triage.yml
- [ ] 02 Investigate and Resolve — Form hypotheses, gather evidence, apply fix, confirm resolution
  - https://agents.tools.ooyes.net/workflows/incident-cycle/02-investigate-and-resolve.yml
- [ ] 03 Post-Mortem — Blameless review, timeline reconstruction, action items to prevent recurrence
  - https://agents.tools.ooyes.net/workflows/incident-cycle/03-post-mortem.yml

### Data Cycle
- [ ] 01 Model and Design — ERD review, schema design decisions, index strategy, migration plan
  - https://agents.tools.ooyes.net/workflows/data-cycle/01-model-and-design.yml
- [ ] 02 Migrate and Apply — Zero-downtime migration execution and rollback readiness
  - https://agents.tools.ooyes.net/workflows/data-cycle/02-migrate-and-apply.yml
- [ ] 03 Validate and Monitor — Data integrity checks, query performance, pipeline monitoring
  - https://agents.tools.ooyes.net/workflows/data-cycle/03-validate-and-monitor.yml

### Onboarding Cycle
- [ ] 01 Environment Setup — Local dev environment, tooling, credentials, verify the build runs
  - https://agents.tools.ooyes.net/workflows/onboarding-cycle/01-environment-setup.yml
- [ ] 02 Explore the Codebase — Architecture tour, key concepts, domain model, flow through the system
  - https://agents.tools.ooyes.net/workflows/onboarding-cycle/02-explore-codebase.yml
- [ ] 03 First Contribution — Pick a starter issue, implement, PR, and get it merged
  - https://agents.tools.ooyes.net/workflows/onboarding-cycle/03-first-contribution.yml

### Security Cycle
- [ ] 01 Audit — OWASP Top 10 review, dependency CVE scan, secret detection, header check
  - https://agents.tools.ooyes.net/workflows/security-cycle/01-audit.yml
- [ ] 02 Remediate — Fix all findings — patch CVEs, fix injection/auth bugs, rotate secrets
  - https://agents.tools.ooyes.net/workflows/security-cycle/02-remediate.yml
- [ ] 03 Harden — CSP, HSTS, rate limiting, least-privilege, security scanning in CI
  - https://agents.tools.ooyes.net/workflows/security-cycle/03-harden.yml

### Deploy Cycle
- [ ] 01 Prepare Deployment — Artefact build, env config validation, migration review, rollback plan
  - https://agents.tools.ooyes.net/workflows/deploy-cycle/01-prepare-deployment.yml
- [ ] 02 Deploy and Verify — Execute deploy, smoke tests, error rate monitoring, rollback if needed
  - https://agents.tools.ooyes.net/workflows/deploy-cycle/02-deploy-and-verify.yml

---

## Full Admin Migration Plan — Old MW v2 → Filament 5

### Legend
- **Old** = MW v2 admin page/section
- **New** = Filament 5 equivalent
- Status: `[x]` done, `[ ]` needs design work, `[~]` partially done

---

### 1. Core Pages

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 1.1 | Dashboard (Welcome, Stats, Chart) | `app/Filament/Admin/Pages/Dashboard.php` + WelcomeWidget, DashboardQuickStatsWidget, SiteStatsEchartsWidget | [x] |
| 1.2 | Live Edit (EDIT button in topbar) | `AdminLiveEditPage` (sidebar item) | [x] |
| 1.3 | Login page | Filament built-in login | [ ] |

### 2. Website Section

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 2.1 | Website > Pages (list) | `Modules/Page/Filament/Resources/PageResource.php` → ListPages | [~] |
| 2.2 | Website > Pages (create/edit) | PageResource → CreatePage, EditPage | [ ] |
| 2.3 | Website > Posts (list) | `Modules/Post/Filament/Admin/Resources/PostResource.php` → ListPosts | [~] |
| 2.4 | Website > Posts (create/edit) | PostResource → CreatePost, EditPost | [ ] |
| 2.5 | Website > Categories | `Modules/Category/Filament/Admin/Resources/CategoryResource.php` | [ ] |
| 2.6 | Media Library | `Modules/MediaLibrary/Filament/Admin/Pages/MediaLibrary.php` | [ ] |
| 2.7 | Menu management | `Modules/Menu/Filament/Admin/Pages/AdminMenusPage.php` | [ ] |
| 2.8 | Tags | `Modules/Tag/Filament/Resources/TagResource.php` | [ ] |
| 2.9 | Comments | `Modules/Comments/Filament/Resources/CommentResource.php` | [ ] |

### 3. Shop Section

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 3.1 | Shop > Products (list) | `Modules/Product/Filament/Admin/Resources/ProductResource.php` → ListProducts | [ ] |
| 3.2 | Shop > Products (create/edit) | ProductResource → CreateProduct, EditProduct | [ ] |
| 3.3 | Shop > Categories | `Modules/Category/Filament/Admin/Resources/ShopCategoryResource.php` | [ ] |
| 3.4 | Shop > Orders (list) | `Modules/Order/Filament/Admin/Resources/OrderResource.php` → ListOrders | [~] |
| 3.5 | Shop > Orders (create/edit) | OrderResource → CreateOrder, EditOrder | [ ] |
| 3.6 | Shop > Variant Attributes | `ProductVariantAttributeResource.php` | [ ] |
| 3.7 | Shop > Inventory | `ProductInventoryResource.php` | [ ] |
| 3.8 | Shop > Pricing Rules | `ProductPricingRuleResource.php` | [ ] |
| 3.9 | Shop > Coupons | `Modules/Coupons/Filament/Resources/CouponResource.php` | [ ] |
| 3.10 | Shop > Offers | `Modules/Offer/Filament/Admin/Resources/OfferResource.php` | [ ] |
| 3.11 | Shop > Invoices | `Modules/Invoice/Filament/Resources/InvoiceResource.php` | [ ] |
| 3.12 | Shop > Payments | `Modules/Payment/Filament/Admin/Resources/PaymentResource.php` | [ ] |
| 3.13 | Shop > Payment Providers | `PaymentProviderResource.php` | [ ] |
| 3.14 | Shop > Shipping Providers | `ShippingProviderResource.php` | [ ] |
| 3.15 | Shop > Taxes | `Modules/Tax/Filament/Admin/Resources/TaxResource.php` | [ ] |
| 3.16 | Shop > Tax Rates | `TaxRateResource.php` | [ ] |
| 3.17 | Checkout flow | `Modules/Checkout/Filament/Resources/CheckoutResource.php` | [ ] |

### 4. Settings Section

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 4.1 | Settings hub (card grid) | `Modules/Settings/Filament/Pages/Settings.php` | [~] |
| 4.2 | General settings | `AdminGeneralPage.php` | [ ] |
| 4.3 | Template settings | `AdminTemplatePage.php` | [ ] |
| 4.4 | SEO settings | `AdminSeoPage.php` | [ ] |
| 4.5 | Custom HTML tags | `AdminCustomTagsPage.php` | [ ] |
| 4.6 | Template Customizer | `AdminTemplateCustomizerPage.php` | [ ] |
| 4.7 | Email settings | `AdminEmailPage.php` | [ ] |
| 4.8 | Auto-respond emails | `AdminShopAutoRespondEmailPage.php` | [ ] |
| 4.9 | Mail templates | `MailTemplateResource.php` | [ ] |
| 4.10 | Privacy Policy | `AdminPrivacyPolicyPage.php` | [ ] |
| 4.11 | Login & Register | `AdminLoginRegisterPage.php` | [ ] |
| 4.12 | Advanced settings | `AdminAdvancedPage.php` | [ ] |
| 4.13 | Cookie Notice | `CookieNoticeModuleSettingsAdmin.php` | [ ] |
| 4.14 | File Manager | `FileManagerPageAdmin.php` | [ ] |
| 4.15 | Comments settings | `CommentsModuleSettingsAdmin.php` | [ ] |

### 5. System / Admin Section

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 5.1 | Modules list | `ModuleResource.php` (Customization Settings) | [ ] |
| 5.2 | Marketplace | `Modules/Marketplace/Filament/Admin/MarketplaceResource.php` | [ ] |
| 5.3 | Updates | `Modules/Updater/Filament/Pages/UpdaterPage.php` | [ ] |
| 5.4 | Maintenance mode | `AdminMaintenanceModePage.php` | [ ] |
| 5.5 | Backup & schedules | `BackupResource.php`, `BackupScheduleResource.php`, `BackupHistoryResource.php` | [ ] |
| 5.6 | Error tracking | `ErrorTrackingResource.php` | [ ] |
| 5.7 | AI settings | `AiSettingsPage.php` | [ ] |
| 5.8 | AI Wizard | `AiWizardResource.php` | [ ] |
| 5.9 | Experimental | `AdminExperimentalPage.php` | [ ] |
| 5.10 | White Label | `WhiteLabelSettingsAdminSettingsPage.php` | [ ] |

### 6. Users Section

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 6.1 | Users list | `UsersResource.php` | [ ] |
| 6.2 | User create/edit | UsersResource → CreateUsers, EditUsers | [ ] |
| 6.3 | Roles | `RoleResource.php` | [ ] |
| 6.4 | Permissions | `PermissionResource.php` | [ ] |

### 7. Multilanguage / Translations

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 7.1 | Language settings | `MultilanguageSettingsAdmin.php` | [ ] |
| 7.2 | Translations | `TranslationResource.php` | [ ] |

### 8. Email Marketing / Newsletter

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 8.1 | Newsletter dashboard | `Modules/Newsletter/Filament/Admin/Pages/Homepage.php` | [ ] |
| 8.2 | Campaigns | `CampaignResource.php` | [ ] |
| 8.3 | Subscribers | `SubscribersResource.php` | [ ] |
| 8.4 | Lists | `ListResource.php` | [ ] |
| 8.5 | Templates | `TemplatesResource.php` | [ ] |
| 8.6 | Template editor | `TemplateEditor.php` | [ ] |
| 8.7 | Sender accounts | `SenderAccountsResource.php` | [ ] |
| 8.8 | Workflows | `WorkflowResource.php` | [ ] |

### 9. Billing / Subscriptions (if enabled)

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 9.1 | Billing dashboard | `Modules/Billing/Filament/Admin/Pages/Dashboard.php` | [ ] |
| 9.2 | Subscription plans | `SubscriptionPlanResource.php` | [ ] |
| 9.3 | Plan groups | `SubscriptionPlanGroupsResource.php` | [ ] |
| 9.4 | Subscriptions list | `SubscriptionResource.php` | [ ] |
| 9.5 | Billing users | `BillingUserResource.php` | [ ] |
| 9.6 | Billing settings | `Modules/Billing/Filament/Admin/Pages/Settings.php` | [ ] |

### 10. Cross-Cutting Design Tasks

- [ ] 10.1 Login page — match MW v2 login design
- [ ] 10.2 Dark mode — full QA pass across all pages
- [ ] 10.3 Mobile responsive — sidebar collapse, table stacking
- [ ] 10.4 Form layouts — consistent field spacing, labels, help text
- [ ] 10.5 Table layouts — consistent column widths, row heights, status badges
- [ ] 10.6 Modal dialogs — consistent sizing, padding, button placement
- [ ] 10.7 Notifications / toasts — match MW v2 notification style
- [ ] 10.8 Empty states — consistent "no data" illustrations
- [ ] 10.9 Loading states — skeleton screens, spinners
- [ ] 10.10 Breadcrumbs — consistent styling across all pages
