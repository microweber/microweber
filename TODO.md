# MW v2 → Filament 5 Admin Design Migration Plan

> **Source design:** https://demo.microweber.org/
> **Clone-website skill:** https://agents.tools.ooyes.net/skills/clone-website.yml
> **UI test workflow:** https://agents.tools.ooyes.net/workflows/dev-cycle/02-test-the-project-ui.yml

---

## Migration Approach

Each page migration follows this cycle:

1. **Capture reference** — Use the clone-website skill (Phase 1: Reconnaissance) to screenshot the MW v2 page at `https://demo.microweber.org/admin/<path>` and extract design tokens, layout patterns, spacing, colors
2. **Inspect current Filament page** — Open the local Filament admin, compare against the MW v2 reference
3. **Implement** — Update Filament Resource/Page forms, tables, Blade views, and CSS to match the MW v2 design language (section icons, card layouts, typography, colors, dark mode)
4. **Visual QA** — Use the UI test workflow to verify pixel-level match at desktop and mobile viewports, light and dark mode
5. **Commit** — One logical change per page/group

---
 

## Todo

### Filament admin full-page test plan

> **Goal:** systematically visit every admin page in Filament, capture screenshots at desktop + mobile in light + dark mode, and check for HTTP 500s, JS console errors, and visual regressions vs MW v2.
>
> **Tooling:**
> - HTTP smoke (no-500): extend `tests/Feature/Admin/AdminPagesNo500Test.php` to cover every route below.
> - Visual + JS errors: Playwright via `mcp__playwright__browser_navigate` → `browser_console_messages` → `browser_take_screenshot` at 1440x900 and 390x844, toggling dark mode.
> - Per page assert: (a) status 200/302, (b) no console errors, (c) screenshots saved under `screenshots/audit/<area>/<page>-{light,dark}-{desktop,mobile}.png`.

#### Phase 1 — Route inventory
- [ ] enumerate every Filament page: `php artisan route:list --path=admin --json` and extract unique GET routes
- [ ] group routes by area (dashboard, content, shop, users, settings, marketplace, modules) and write into `tests/fixtures/admin-pages.php`
- [ ] for each Resource include: index, create, edit (with seeded record id), and any custom Pages

#### Phase 2 — HTTP smoke (no-500) test
- [ ] extend `AdminPagesNo500Test` to iterate the fixture and assert 200/302 (never 5xx) for every authenticated GET
- [ ] add per-area data providers so failures point at the exact route
- [ ] run suite, fix any 500s, commit per area

#### Phase 3 — Console / JS error audit
- [ ] write `scripts/audit-admin-console.mjs` that logs in, walks the fixture, and prints every `console.error` per page
- [ ] triage and fix unique JS errors

#### Phase 4 — Visual audit per area
For each area: light desktop, dark desktop, light mobile, dark mobile screenshots; diff against MW v2 reference.

##### Dashboard
- [ ] /admin — main dashboard
- [ ] dashboard widgets daily/weekly/monthly chart states
- [ ] notifications panel open

##### Content — Pages
- [ ] /admin/pages (list)
- [ ] /admin/pages/create
- [ ] /admin/pages/{id}/edit — Content tab
- [ ] /admin/pages/{id}/edit — Template tab
- [ ] /admin/pages/{id}/edit — Custom Fields tab
- [ ] /admin/pages/{id}/edit — SEO tab
- [ ] /admin/pages/{id}/edit — Advanced tab

##### Content — Posts
- [ ] /admin/posts (list)
- [ ] /admin/posts/create (all tabs)
- [ ] /admin/posts/{id}/edit (all tabs)

##### Content — Categories
- [ ] /admin/categories (list)
- [ ] /admin/categories/create (all tabs)
- [ ] /admin/categories/{id}/edit (all tabs)

##### Shop — Products
- [ ] /admin/products (list)
- [ ] /admin/products/create (Content, Product Details, Variants, Custom Fields, SEO, Advanced)
- [ ] /admin/products/{id}/edit (all tabs)

##### Shop — Orders
- [ ] /admin/orders (list, filter tabs: all/new/processing/completed/cancelled)
- [ ] /admin/orders/create (all tabs)
- [ ] /admin/orders/{id}/edit
- [ ] /admin/orders/{id}/view (if exists)

##### Shop — Categories
- [ ] /admin/shop/categories (list, tree view)
- [ ] /admin/shop/categories/create
- [ ] /admin/shop/categories/{id}/edit

##### Shop — Customers
- [ ] /admin/customers (list)
- [ ] /admin/customers/create
- [ ] /admin/customers/{id}/edit

##### Users
- [ ] /admin/users (list)
- [ ] /admin/users/create
- [ ] /admin/users/{id}/edit
- [ ] /admin/users/roles (if exists)

##### Settings
- [ ] /admin/settings — General
- [ ] /admin/settings — Website
- [ ] /admin/settings — Email
- [ ] /admin/settings — Shop / Payments
- [ ] /admin/settings — Shipping
- [ ] /admin/settings — Tax
- [ ] /admin/settings — Comments
- [ ] /admin/settings — Language / Multilanguage
- [ ] /admin/settings — Social login
- [ ] /admin/settings — SEO
- [ ] /admin/settings — Cache
- [ ] /admin/settings — Backup / restore

##### Marketplace
- [ ] /admin/marketplace (index)
- [ ] /admin/marketplace — templates
- [ ] /admin/marketplace — modules
- [ ] /admin/marketplace — install flow

##### Modules
- [ ] /admin/modules (list)
- [ ] /admin/modules/{name}/admin (a couple of representative modules)

##### Auth / standalone
- [ ] /admin/login (light + dark)
- [ ] /admin/password/reset
- [ ] 404 admin page
- [ ] forced 500 admin error page

#### Phase 5 — Cross-cutting checks
- [ ] table pagination controls (per-page selector, prev/next) on every list page
- [ ] modal slide-right behaviour on every create/edit modal
- [ ] tab underline + padding alignment on every tabbed form
- [ ] dark mode color contrast pass on every page
- [ ] mobile sidebar drawer open/close on every page
- [ ] keyboard focus ring visible on every interactive element

#### Phase 6 — Triage + fix
- [ ] open a TODO entry for each defect found, grouped by area
- [ ] fix in order: 500s → JS errors → layout breaks → visual polish
- [ ] re-run Phase 2 + Phase 3 to confirm green before closing