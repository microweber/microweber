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
- [x] 2026-04-01  fix(js): `mwTreeFormComponent is not defined` — Alpine.js component missing on Create/Edit Page, Post, Product forms
  - Affects: Parent page tree selector, category tree selector
  - Console error: `ReferenceError: mwTreeFormComponent is not defined` in Livewire/Alpine init
  - Pages affected: `/admin/pages/create`, `/admin/posts/create`, `/admin/products/create`, and their edit equivalents
  - Root cause: `mw-tree-component.js` and `mw-media-browser.js` registered as Filament `AlpineComponent` (lazy-loaded via `x-load-src`) but use `Alpine.data()` pattern requiring eager `<script>` loading
  - Fix: changed from `AlpineComponent::make()` to `Js::make()` in `MicroweberFilamentTheme.php`
- [x] 2026-04-01  fix(ui): Settings hub page (`/admin/settings`) missing page title prefix — shows "Microweber" instead of "Settings - Microweber"
  - Root cause: `getTitle()` and `getBreadcrumb()` returned empty strings in `Settings.php`
  - Fix: removed empty overrides, set `getTitle()` to return "Settings"
- [x] 2026-04-01  fix(ui): Settings hub page and General Settings page breadcrumb — General Settings breadcrumb is Filament default (no parent-child nav configured); Settings hub title fix resolves the primary issue
- [x] 2026-04-01  fix(ui): Dashboard stat cards use `<h5>` tags for numeric values — semantic misuse
  - Fix: changed `<h5>` to `<p>` in `dashboard-quick-stats-widget.blade.php`; CSS uses class selector so no style breakage
- [x] 2026-04-01  fix(ui): Mobile (375px) — Orders table columns overflow off-screen
  - Table was already scrollable (`overflow-x: auto`) but lacked visual indicator
  - Fix: added right-edge fade gradient on `.fi-ta-ctn::after` at `max-width: 768px` to hint scrollable content
- [x] 2026-04-01  fix(ui): Mobile (375px) — Topbar user avatar clipped on right edge
  - Root cause: topbar flex children + gap exceeded 375px viewport with `overflow: visible`
  - Fix: added `overflow: hidden` on `.fi-topbar` and `min-width: 0` on `.fi-topbar-end` at `max-width: 640px`
- [x] 2026-04-01  fix(a11y): No skip-navigation link present on any admin page
  - Fix: added "Skip to main content" link via `FilamentView::registerRenderHook(BODY_START)` and `#main-content` anchor via `CONTENT_START` in `MicroweberFilamentTheme.php`
  - Note: existing `$panel->renderHook()` calls also migrated to `FilamentView::registerRenderHook()` — panel hooks registered in plugin `boot()` run after `registerRenderHooks()`, so they were silently lost
- [x] 2026-04-01  fix(a11y): Heading hierarchy issue — H2 "Add new" dropdown appears in DOM before H1 page title
  - Investigated: both H2 elements ("Add new", "No notifications") are inside Filament modals with `display: none` — invisible to screen readers and users
  - Not a real issue; standard Filament behavior (modals pre-rendered but hidden)

- [x] 2026-04-01  03 Code Review — Analyse code quality, security, performance, and best practices
  - https://agents.tools.ooyes.net/workflows/dev-cycle/03-code-review.yml
  - Scope: all files changed on filament-5 branch in current dev cycle (13 production files, 4 test files)
  - **Security**: 1 medium finding — CDN script without SRI hash → **fixed**: pinned echarts@5.5.1 with integrity hash
  - **Performance**: 2 high findings → **fixed**:
    - DashboardQuickStatsWidget: 4 uncached queries (2 duplicated on orders table) → combined into single query + 120s cache
    - SiteStatsEchartsWidget: views/visitors footer both showed `totalVisitors` → fixed: now shows visitors + bounce rate
  - **Code quality**: hardcoded `$` currency symbol → **fixed**: now uses `get_option('currency_symbol')` with `$` fallback
  - **Semantic HTML**: `<h5>` misuse in echarts widget → **fixed**: changed to `<span>`

### Code Review — Remaining Items (not fixed, pre-existing) _(ref: workflows/dev-cycle/03-code-review.yml)_
- [x] 2026-04-01  refactor: Settings.php `buildNavFromPanelNavGroup` — extract duplicated item-extraction code
  - Extracted `extractItemData($item, $defaultIcon)` private method shared by parent and child nav items
  - Removed ~70 lines of duplicated try/catch blocks; exception swallowing now logs via `Log::debug()`
  - Fixed `getNavgationLabel` typo → `getNavigationLabel`; removed commented-out debug code
  - Added `!is_array($items)` guard in sort loop to prevent crash from `array_flip` producing integers
  - 94 Settings-related tests pass
- [x] fix(bug): SiteStatsRepository `getSessionsForPeriod('views')` — ambiguous `updated_at` column in JOIN query causes SQL error
  - Qualified all `updated_at` references in `applyDateRangeToQueryBuilder()` with `$query->getModel()->getTable()` prefix
  - Fixed incorrect JOIN condition: `stats_visits_log.id = session_id_key` → `stats_visits_log.session_id_key = stats_sessions.id`
  - Verified all query modes (daily/weekly/monthly, sessions/views/bounced) work correctly
- [x] perf: SiteStatsEchartsWidget — `getChartData()` memoized with `$cachedChartData` instance property
  - Added `protected ?array $cachedChartData = null` to avoid re-executing 3 DB queries on repeated calls within same render cycle
  - Cache resets naturally on Livewire re-render (new component instance)
- [x] perf: SiteStatsEchartsWidget — `getOnlineCount()` cached with 60s TTL via `Cache::remember()`
- [x] fix(ui): echarts-widget period switching now works via Alpine.js + `$wire.updatePeriod()`
  - Root cause: `wire:ignore` blocked Livewire DOM updates; radio buttons dispatched `updateFilter` event that nothing handled
  - Fix: added `public string $period` property and `updatePeriod()` Livewire method; overrode `getPeriodsDataFromFilter()` to use widget-level period
  - Blade rewritten with Alpine.js `x-data` component: radios call `changePeriod()` → `$wire.updatePeriod()` → chart updates via `setOption()`, footer via `x-text` bindings
  - Added loading spinner overlay, input validation (whitelist of allowed periods)
  - Verified Daily→Weekly→Monthly switching in browser: correct label counts (31/13/13) and titles
- [x] refactor: mw-tree.blade.php — removed redundant `$suffix`/`$id` assignments
  - Removed unused `$id = $getId()` (line 7) and `$id = $this->getId()` (line 30) — `$id` was never referenced
  - Removed duplicate `$suffix` initialization (lines 16-18) — already set on line 31
  - Cleaned up excessive blank lines; verified Create Page still loads without errors
- [x] cleanup: mw-media-browser.js — removed redundant condition and commented-out debug code
  - Simplified `$watch` callback: `value.length > 0 && this.selectedImages && this.selectedImages.length > 0` → `value.length > 0` (value IS selectedImages)
  - Removed 4 lines of commented-out `console.log`, `return`, and alternative `statePath` code

### Scope Cycle
- [x] 2026-04-01  01 Define Product Scope — Analyse the codebase and write a comprehensive SCOPE.md
  - Comprehensive product scope covering: vision, target users, 92 modules, e-commerce, 6 panels, tech stack, data models, integrations, requirements, constraints, risks
  - https://agents.tools.ooyes.net/workflows/scope-cycle/01-define-product-scope.yml
- [x] 2026-04-01  02 Actionable Plan → TODO — Break the scope into developer-ready tasks and populate TODO.md
  - Created PLAN.md with 4 phases, 50 tasks, complexity estimates, and dependency map
  - Added Phase 1 tasks (19 items) to TODO.md below
  - https://agents.tools.ooyes.net/workflows/scope-cycle/02-make-actionable-plan.yml

---

## Phase 1: Core Content & Commerce _(ref: PLAN.md)_

### Pages Resource (currently 80%)
- [x] 2026-04-01  feat: Pages — parent-page tree selector already exists in create/edit form _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Verified: ContentResource lines 404-460 renders `mw-tree` view with singleSelect, skipCategories, contentTypeFilter
  - Tree renders on both Create Page and Edit Page; selection updates hidden `parent` field via Alpine.js `$entangle()`
  - No code changes needed — feature was already complete
- [x] 2026-04-01  feat: Pages — template/layout chooser with preview already exists _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Verified: Template tab in ContentResource (lines 505-518) renders MwSelectTemplateForPage component
  - Component provides: template dropdown, layout dropdown, and live preview iframe
  - Browser confirmed: Template="Bootstrap", Layout="Clean page", live preview renders correctly
  - No code changes needed — feature was already complete
- [x] 2026-04-01  feat: Pages — add bulk publish/unpublish action to list _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Added Publish and Unpublish bulk actions to ContentResource table (applies to Pages, Posts, Products)
  - Publish: sets `is_active = 1` with confirmation dialog, green check-circle icon
  - Unpublish: sets `is_active = 0` with confirmation dialog, orange x-circle icon
  - Both deselect records after completion; 155 Content tests pass

### Posts Resource (currently 60%)
- [x] 2026-04-01  feat: Posts — add excerpt field and featured-image showcase to form _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Added `description` Textarea as "Excerpt" field (3 rows, 500 char max) to ContentResource form
  - Visible only for posts (`content_type === 'post'`), positioned after content_body
  - Includes translate hint action and helper text for discoverability
  - Featured image already handled by MwMediaBrowser (first media = featured image)
  - Verified: renders on Create Post, hidden on Create Page; 39 Post tests pass
- [x] 2026-04-01  feat: Posts — add publish/schedule date picker with draft vs published states _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Added `posted_at` to Content model's `$fillable` and cast as `datetime`
  - Added DateTimePicker "Publish Date" in Published section, visible only for posts
  - Helper text dynamically shows "scheduled for future publication" when date is in the future
  - Toggle auto-sets `posted_at` to now when publishing without a date
  - Verified: renders on Create Post, hidden on Create Page
- [x] 2026-04-01  feat: Posts — add author display column and filter to list _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Added Author TextColumn to list table using `created_by` with `user_name()` formatter
  - Column is toggleable, visible by default in list layout
  - Added Author SelectFilter with searchable dropdown, populated from users who have authored content
  - Verified: Author column and filter render in Posts list view; 39 Post tests pass
- [x] 2026-04-01  feat: Posts — add bulk publish/unpublish action _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Already implemented in commit 3d5430922a as part of "Pages — add bulk publish/unpublish action to list"
  - Bulk actions are defined in ContentResource which PostResource inherits
  - No additional code changes needed

### Products Resource (currently 60%)
- [x] 2026-04-02  feat: Products — add variant management UI (size/color/custom attributes) _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Created `ProductVariantManager` Livewire component with attribute selection checkboxes and combination table
  - Added "Variants" tab to ContentResource form (visible only for products, with swatch icon)
  - Attribute selection: checkbox cards for each active attribute, with "Manage Attributes" link
  - Generate Combinations: button creates cartesian product of selected attribute values via `ProductVariantService`
  - Combination table: columns for Variant label, SKU, Price, Stock, Default, Active with Edit/Delete actions
  - Edit modal: pricing (price/compare/cost), inventory (SKU/barcode/quantity/backorders), shipping (weight), status (default/active)
  - Create page shows "Save the product first" empty state (variants require a product ID)
  - Registered Livewire component in `ProductServiceProvider`
  - 171 product tests pass, no regressions
- [x] feat: Products — add inventory tracking fields (stock qty, low-stock threshold) _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Product Details tab already had: SKU, barcode, track_quantity toggle, quantity, sell_oos, max_qty_per_order
  - Added `low_stock_threshold` field to Inventory section (visible when Track Quantity is on)
  - Added `low_stock_threshold` to Content model's `$fillable` array
  - Added `low_stock_threshold` to variant edit form in ProductVariantManager
  - Backend already supported threshold via `InventoryService::getLowStockThreshold()` and `checkStockLevels()`
  - 169 product tests pass, no regressions
- [x] feat: Products — add weight/dimensions fields for shipping calculation _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Shipping section already existed in Product Details tab with weight, width, length, depth fields
  - Fixed field name mismatch: form saved `weight`/`width`/`length`/`depth` but shipping drivers read `shipping_weight`/`shipping_width`/`shipping_height`/`shipping_depth`
  - Renamed form fields to `content_data.shipping_weight`, `shipping_width`, `shipping_height`, `shipping_depth`
  - Renamed "Length" label to "Height" to match backend field name (`shipping_height`)
  - 169 product tests + 31 shipping tests pass, no regressions
- [x] feat: Products — add SKU/barcode field to main form _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Already existed: `content_data.sku` and `content_data.barcode` in Product Details → Inventory section
  - Also has QueryBuilder filter constraints for SKU and barcode in list table
  - No code changes needed — feature was already complete
- [x] feat: Products — add stock status badge in list view _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Added stock badge to grid view via `content-view-column.blade.php` (product-only, with `@if content_type === 'product'`)
  - Added stock badge TextColumn to list view in `getListTableColumns()` (visible only on ListProducts page)
  - Badge states: "In Stock" (green), "Low Stock" (warning), "Out of Stock" (danger)
  - Logic: if track_quantity disabled → "In Stock"; if qty <= 0 → "Out of Stock"; if qty <= threshold → "Low Stock"
  - Badge hidden on Pages/Posts lists; 169 product tests pass, no regressions

### Orders Resource (currently 85%)
- [x] feat: Orders — add status-change timeline/activity log _(ref: workflows/scope-cycle/02-make-actionable-plan)_
- [x] feat: Orders — add shipping tracking number field _(ref: workflows/scope-cycle/02-make-actionable-plan)_
- [x] feat: Orders — add bulk status update action _(ref: workflows/scope-cycle/02-make-actionable-plan)_
- [x] feat: Orders — add refund processing UI (partial/full) _(ref: workflows/scope-cycle/02-make-actionable-plan)_

### Users Resource (currently 60%)
- [x] feat: Users — enable create/edit routes and verify form _(ref: workflows/scope-cycle/02-make-actionable-plan)_
- [x] feat: Users — add role assignment to create/edit form _(ref: workflows/scope-cycle/02-make-actionable-plan)_

### Login Page
- [x] 2026-04-02  style: Login page — match MW v2 visual design (branding, layout, colors) _(ref: workflows/scope-cycle/02-make-actionable-plan)_
  - Added login-specific CSS to microweber-theme-v3.scss: cool blue-gray background, card shadow/border, 420px max-width, centered logo, dark navy submit button with MW v2 box-shadow
  - Fixed brandLogo fallback in FilamentAdminPanelProvider to use admin_logo_login() SVG when admin_logo() is empty
  - Added dark mode login styles (dark background, dark card, accent button)
  - Logo centered with proper sizing in login header
  - Visual QA verified: login page and dashboard both display correctly

---

## Phase 2: Media & Navigation _(ref: PLAN.md, docs/features/media-library.md)_

### Media Library
- [x] feat: Media Library — create Livewire full-page component with 3-panel layout (folder sidebar, grid/list, detail panel) _(ref: docs/features/media-library.md)_ ✅ 2026-04-02 — Livewire page with folder sidebar, grid/list views, detail panel, drag-and-drop upload, bulk actions, search/filter, and SCSS styles
- [x] feat: Media Library — build thumbnail grid view with responsive columns and lazy loading _(ref: docs/features/media-library.md)_ ✅ 2026-04-02 — Cached thumbnails via thumbnail(), IntersectionObserver lazy loading with skeleton placeholders, 4:3 aspect ratio, responsive 3-6 columns, media_type fallback for image detection
- [x] feat: Media Library — add list/table view toggle with session-persisted preference _(ref: docs/features/media-library.md)_ ✅ 2026-04-02 — Already implemented: toggle buttons switch grid/list, table has preview/title/folder/type/size/date columns, session persists preference across navigation
- [x] feat: Media Library — build folder tree sidebar with create/rename/delete actions _(ref: docs/features/media-library.md)_ ✅ 2026-04-02 — Already implemented: recursive folder tree with expand/collapse, create form, inline rename, delete with confirmation, context menu, folder selection filters media, subfolder inclusion toggle
- [x] 2026-04-02  feat: Media Library — add drag-and-drop upload zone with progress indicators _(ref: docs/features/media-library.md)_
- [x] 2026-04-02  feat: Media Library — add bulk select with delete, move-to-folder, and CDN sync actions _(ref: docs/features/media-library.md)_
  - Bulk select: checkbox per item in grid/list, Select All / Deselect All buttons
  - Bulk delete: confirmation dialog, permanently removes selected items
  - Bulk move: folder selector dropdown to relocate selected items (including Root)
  - CDN sync: button visible only when CDN provider is configured, calls CdnIntegrationService::bulkSync(), reports success/failure counts
- [x] 2026-04-02  feat: Media Library — build metadata/detail panel with title, description, alt text, dimensions, usage info _(ref: docs/features/media-library.md)_
  - Detail panel shows: preview image/icon, editable title/description/alt text, save button
  - File info: filename, type, size, folder, upload date, CDN status
  - Dimensions: width × height for images, auto-detected via getimagesize() and cached in metadata JSON
  - Usage info: shows content items referencing this media (type + title)
  - Delete button with confirmation
- [x] 2026-04-02  feat: Media Library — add search bar and filters (type, date range, folder) _(ref: docs/features/media-library.md)_
  - Search bar: debounced live search on title, filename, description
  - Type filter: dropdown for Images/Videos/Audio/Documents
  - Date range: from/to date inputs with clear button
  - Folder filter: sidebar selection with subfolder inclusion toggle
  - Clear all filters: toolbar button + empty state reset (fixed to include date filters)
- [x] 2026-04-02  feat: Media Library — integrate Unsplash stock photo search as tab/panel _(ref: docs/features/media-library.md)_
  - Unsplash tab: toolbar toggle switches between library and Unsplash search views
  - Search: form with input + submit calls existing Unsplash::search() API via microweberapi.com proxy
  - Results grid: reuses media grid layout with photo thumbnails, photographer credit overlay
  - Download: imports photo to local media library with Unsplash metadata (source, photo ID, photographer)
  - Download tracking: per-photo loading state prevents duplicate downloads
  - Load more: pagination support with page tracking and total_pages limit
  - Empty states: initial prompt and no-results feedback
  - CSS: Unsplash button, search bar, grid overlay, download button, credit, loading states
- [x] 2026-04-02  test: Media Library — add Livewire component tests for upload, folder CRUD, bulk actions, and search _(ref: docs/features/media-library.md)_
  - 26 tests, 73 assertions, all passing
  - Page rendering: renders page, defaults to grid view, toggles to list view
  - Search & filters: search by title, filter by type, filter by date range, clear all filters
  - Folder CRUD: create folder, reject empty name, rename, delete empty, prevent non-empty delete, select folder
  - Media detail: select/toggle/close panel, save title/description/alt text edits
  - Bulk actions: toggle select, deselect all, bulk delete, bulk move to folder
  - Delete: single media deletion
  - Unsplash: tab switching, reject invalid tab names
  - Upload: valid image upload via UploadedFile::fake()
  - Helpers: formatFileSize correctness

### Menu Management
- [x] 2026-04-02  feat: Menu management — build drag-and-drop menu editor with nested items _(ref: PLAN.md 2.5)_
  - Fixed critical Filament v5 bug: `Form $schema` → `Schema $schema` with `$schema->fill()` in addMenuItemAction and editAction
  - Redesigned menu editor UI: toolbar with menu title + add button, styled tree area with border/radius, empty state with icon
  - Added item type icons: blue page icon (content_id), amber folder icon (categories_id), gray link icon (custom URL)
  - Added URL preview below title, drag handle, hover-to-reveal edit/delete actions
  - Added rename menu action to menu actions dropdown
  - Improved delete action to cascade-delete child items when deleting a menu
  - Added menu editor CSS to theme SCSS: `.mw-menu-editor__*` classes with nested indentation, sortable placeholder styling, dark mode
  - Verified: create/edit/delete items, menu selector, drag-and-drop reordering all work; 13 menu tests pass
- [x] 2026-04-02  feat: Menu management — add item types (page link, custom URL, category) _(ref: PLAN.md 2.6)_
  - Already implemented: MwLinkPicker component in MenusList handles all three item types
  - Content links: select pages/posts with auto-title sync via content_id
  - Category links: select categories via categories_id
  - Custom URLs: freeform URL input with target window toggle
  - Additional: image uploads (default_image, rollover_image), mega menu settings
- [x] 2026-04-02  feat: Menu management — add menu location assignment (header, footer, sidebar) _(ref: PLAN.md 2.7)_
  - Already implemented: menus are placed via live-edit module system with data-name parameter
  - Each template position (header_menu, footer_menu, etc.) creates its own menu instance
  - MenuModuleSettings Filament component configures per-location menu selection
  - Menu creation auto-populates on first use (make_on_not_found=1)

---

### Feature Cycle
- [x] 2026-04-02  01 Define Feature — Write a complete spec with acceptance criteria before any code is written
  - https://agents.tools.ooyes.net/workflows/feature-cycle/01-define-feature.yml
  - Feature: Media Library — Full Admin UI (Phase 2 top priority)
  - Spec written at `docs/features/media-library.md` with: problem statement, 7 user stories, 19 acceptance criteria, UI layout wireframe, data requirements, API design, security considerations, open questions
  - Implementation tasks added below (Phase 2 section)
- [x] 2026-04-02  02 Design and Review — Technical design covering data, services, API, security, and performance
  - https://agents.tools.ooyes.net/workflows/feature-cycle/02-design-and-review.yml
  - Technical design appended to `docs/features/media-library.md`: architecture overview, data layer (3 tables, 7 query patterns), service layer (4 computed properties, 3 external services), full API surface (20 Livewire methods with validation), security assessment (8 OWASP concerns — all covered), performance evaluation (8 concerns — search LIKE and recursive subfolder expansion flagged as watch items), test coverage plan (44 tests / 11 categories), sequence diagrams (upload + Unsplash), error handling approach
- [x] 2026-04-02  03 Implement — Execute the design task-by-task with verification after each change
  - https://agents.tools.ooyes.net/workflows/feature-cycle/03-implement.yml
  - All 10 Media Library implementation tasks completed: 3-panel layout, thumbnail grid, list/table view, folder tree CRUD, drag-drop upload, bulk actions, CDN sync, detail panel, search/filters, Unsplash integration
- [x] 2026-04-02  04 Test — Verify acceptance criteria, edges, security, and performance
  - https://agents.tools.ooyes.net/workflows/feature-cycle/04-test.yml
  - 26 Livewire component tests covering: rendering, search, filters, folder CRUD, media selection, detail panel, bulk actions, delete, Unsplash tabs, upload, helpers — all passing
- [x] 2026-04-02  05 Release — Pre-release checklist, deployment, smoke test, rollback plan
  - https://agents.tools.ooyes.net/workflows/feature-cycle/05-release.yml
  - Pre-release checklist: 44/44 tests pass, 0 lint errors, 0 debug statements, 0 hardcoded secrets
  - Added `Modules/MediaLibrary/Tests` to phpunit.xml (Modules-Group6A suite)
  - Feature spec marked as implemented with status header
  - No database migrations needed (uses existing tables + `metadata` JSON)
  - Deployment: merge `filament-5` → `master` when ready; no migration steps required

### Bug Cycle
- [x] 2026-04-02  01 Reproduce — Establish an on-demand reproduction and write a failing regression test
  - https://agents.tools.ooyes.net/workflows/bug-cycle/01-reproduce.yml
  - Ran full test suite (2,486 tests): 1 error, 0 failures, 44 skipped
  - Bug #1: `ContentTest::it_save_content_update_time` — `DateMalformedStringException` when saving content with invalid date strings (PHP 8.4 strict date parsing)
  - Bug #2: JS console error "Identifier 'Ziggy' has already been declared" on every admin page — duplicate HEAD_START render hook
  - Regression tests: existing `it_save_content_update_time` covers Bug #1; new `MetaTagsRenderHookTest` (2 tests) covers Bug #2
- [x] 2026-04-02  02 Diagnose — Find the exact root cause using hypothesis-driven investigation
  - https://agents.tools.ooyes.net/workflows/bug-cycle/02-diagnose.yml
  - Bug #1 root cause: `ContentManagerCrud.php:772-777` passes user-supplied date strings directly to Eloquent save without validation; Carbon throws `DateMalformedStringException` in PHP 8.3+
  - Bug #2 root cause: `MicroweberFilamentServiceProvider.php:78-84` and `MicroweberFilamentTheme.php:54-60` both registered `HEAD_START` hook calling `AdminFilamentMetaTagsRenderer::getHeadMetaTags()`, doubling the `mw-api-settings` script (which contains Ziggy routes)
- [x] 2026-04-02  03 Fix and Verify — Apply a minimal targeted fix, verify tests pass, commit with full context
  - https://agents.tools.ooyes.net/workflows/bug-cycle/03-fix-and-verify.yml
  - Bug #1 fix: added `sanitizeDateValue()` method to `ContentManagerCrud.php` — uses `strtotime()` with fallback to current time for unparseable strings
  - Bug #2 fix: removed duplicate `HEAD_START` and `BODY_END` render hooks from `MicroweberFilamentTheme.php` (canonical registration remains in `MicroweberFilamentServiceProvider.php`)
  - Verification: 70 Content module tests pass, 2 new regression tests pass, 0 JS console errors on admin pages

### Release Cycle
- [x] 2026-04-03  01 Pre-Release Check — Tests, security scan, changelog, docs, migration safety — go/no-go gate
  - https://agents.tools.ooyes.net/workflows/release-cycle/01-pre-release-check.yml
  - **Go/No-Go: GO** — all checks pass
  - **Version bump:** MINOR (significant new features: Media Library, Menu editor, Orders enhancements, Products variants, Users CRUD)
  - **Test suite:** 12/12 suites PASS, 0 failures, 0 errors
  - **PHP syntax:** 0 lint errors across all changed files (committed + uncommitted)
  - **Security:** 0 PHP CVEs (composer audit clean), 0 hardcoded secrets, ECharts CDN has SRI hash
  - **npm:** 10 vulnerabilities (all in dev-only deep deps of laravel-mix — no production impact, no fix available)
  - **Debug artifacts:** 0 active dd()/dump()/console.log in production code (1 dd() inside comment block — harmless)
  - **Migrations:** 3 new migrations (order_status_history, shipping_tracking, order_refunds) — all have down() methods, all already ran
  - **Documentation:** SCOPE.md, PLAN.md, docs/features/media-library.md all current; feature spec marked as implemented
  - **Uncommitted work:** 9 modified files + 3 untracked test files need to be committed before release (Media Library, Menu, CategoryResource, ContentManagerCrud bug fix, theme CSS, MetaTagsRenderHook fix)
  - **APP_DEBUG:** set to `true` in .env — must be set to `false` for production deployment
  - **Abandoned packages:** 3 (doctrine/annotations, graham-campbell/security-core, inspector-apm/neuron-ai) — no security risk, cosmetic only
- [x] 2026-04-03  02 Release — Version tag, changelog, deploy, migrations, health check
  - https://agents.tools.ooyes.net/workflows/release-cycle/02-release.yml
  - Version: `4.0-dev17` (bumped from `4.0-dev16`)
  - CHANGELOG.md updated with Added/Changed/Fixed/Security/Performance sections
  - 6 commits: gitignore, bug fixes, Media Library, Menu editor, CategoryResource, theme CSS, release artifacts
  - Annotated tag `v4.0-dev17` created
  - Smoke test: admin login (200), homepage (200), version constant verified
  - All 3 migrations already ran (order_status_history, shipping_tracking, order_refunds)
  - Note: not pushed to remote — merge `filament-5` → `master` when ready
- [x] 2026-04-03  03 Post-Release — 30-minute monitoring, cleanup, stakeholder communication, follow-up tasks
  - https://agents.tools.ooyes.net/workflows/release-cycle/03-post-release.yml
  - **Monitoring:** All endpoints stable (/, /admin/login → 200), 0 errors in logs, DB healthy, all migrations ran
  - **Release summary (v4.0-dev17):**
    - Media Library: full admin UI with 3-panel layout, Unsplash integration, bulk actions, 26 tests
    - Menu editor: redesigned with drag-and-drop, item type icons, rename/cascade-delete
    - Products: variant management UI, low-stock threshold, stock status badges
    - Orders: status timeline, shipping tracking, bulk status update, refund processing
    - Users: create/edit with role assignment
    - Content: bulk publish/unpublish, post scheduling, author filters
    - Bug fixes: PHP 8.4 date parsing, duplicate Ziggy script, ECharts period switching
    - Security: ECharts SRI hash, lodash.set CVE resolved
  - **Follow-up work identified (added below):**
    - npm dev-dependency vulnerabilities (laravel-mix deep deps — no fix available, monitor for updates)
    - APP_DEBUG must be set to false before production deployment
    - 3 abandoned composer packages to evaluate for replacement
    - Remaining Phase 3-4 admin migration tasks (see mapping table below)
    - Compile theme CSS for production (`npm run production`) before deployment

### Refactor Cycle
- [x] 2026-04-03  01 Assess — Identify code quality issues, establish test safety net, risk assessment
  - https://agents.tools.ooyes.net/workflows/refactor-cycle/01-assess.yml
  - Audited 9 production files: ContentResource (D+), OrderResource (C), MediaLibrary (B), MenusList (C+), Settings (C), ProductVariantManager (B+), SiteStatsEchartsWidget (A), DashboardQuickStatsWidget (B+), theme SCSS (B)
  - Critical: ContentResource.php has 555-line `formArray()` method (36% of file), OrderResource has 240-line `form()` with 13-level nesting
  - 45+ duplication patterns: payment hydration (5x), record loaders (5x), error handlers (7x)
  - Test safety net: Content (70), Order (80), MediaLibrary (44), Menu (7), Settings (23) — all passing
  - Refactoring plan written at `docs/refactoring/REFACTOR-ASSESSMENT.md` with 5 priorities, atomic steps, and risk mitigation
  - **Go/No-Go: GO** — all 4 criteria met
- [x] 2026-04-03  02 Plan — Map current state, define target state, sequence atomic steps
  - https://agents.tools.ooyes.net/workflows/refactor-cycle/02-plan.yml
  - Mapped all callers: `formArray()` has 2 external callers (ContentTableList, AdminLiveEditPage), 3 subclasses (Page/Post/Product — none override form methods)
  - Target state defined: ContentResource 1529→<900 lines, OrderResource 818→<650, MenusList 491→<400
  - 13 atomic steps sequenced in additive order across 4 phases (A: Content 7 steps, B: Order 3, C: Menu 2, D: MediaLibrary 1)
  - Success criteria: formArray() 555→<100 lines, form() 240→<40, editAction() 169→<90, 5x payment duplication→1x
  - Full plan at `docs/refactoring/REFACTOR-PLAN.md`
- [x] 2026-04-03  03 Execute — One step at a time, test after every change, commit each step
  - https://agents.tools.ooyes.net/workflows/refactor-cycle/03-execute.yml
  - Phase A: ContentResource formArray() reduced from 555 to 45 lines — extracted 9 section builders + 3 setup helpers
  - Phase B: OrderResource form() reduced from 240 to 25 lines — extracted 8 section/tab methods, deduplicated 5x→1x payment query
  - Phase C: MenusList — added findMenuOrFail() helper replacing 5x Menu::find() calls
  - Phase D: MediaLibrary — added isImageMedia() helper for media type checking
  - Backward-compatible aliases preserved for productDetailsFormArray(), seoFormArray(), advancedSettingsFormArray()
  - Test suite: 12/12 suites PASS (2,489 tests, 16,868 assertions)

### Incident Cycle
- [x] 2026-04-03  01 Detect and Triage — Confirm the incident, assess severity, assemble the response team
  - https://agents.tools.ooyes.net/workflows/incident-cycle/01-detect-and-triage.yml
  - **Severity:** SEV-4 (low impact, no urgent user effect)
  - **Detection:** Full health check — HTTP 200 on all endpoints, 12/12 test suites PASS, 0 PHP lint errors
  - **Issues found:** 1 missing Blade component (`user::primary-button`), 1 pre-existing version.txt newline, 1 pre-existing dispatchFormEvent deprecation
  - **Impact:** Profile photo upload button broken on user profile page; other issues are non-user-facing
  - Incident report: `docs/incidents/2026-04-03-post-refactor-health-check.md`
- [x] 2026-04-03  02 Investigate and Resolve — Form hypotheses, gather evidence, apply fix, confirm resolution
  - https://agents.tools.ooyes.net/workflows/incident-cycle/02-investigate-and-resolve.yml
  - **Fix #1:** Replaced `<x-user::primary-button>` with `<x-user::button>` in profile photo form (component exists, uses btn-primary)
  - **Fix #2:** Removed trailing newline from version.txt (ConfigFileTest assertion)
  - **Deferred:** `dispatchFormEvent` JS deprecation — works currently, scheduled for future cleanup
  - **Verification:** All tests pass, application healthy, error rate at baseline
- [x] 2026-04-03  03 Post-Mortem — Blameless review, timeline reconstruction, action items to prevent recurrence
  - https://agents.tools.ooyes.net/workflows/incident-cycle/03-post-mortem.yml
  - SEV-4 — brief post-mortem per policy (SEV-3/4: document root cause + action items, close)
  - Added 3 action items to incident report: CI Blade validation, `$wire.call()` migration, version.txt script fix
  - Documented lessons learned: Blade component refs not validated at compile time, Write tool newline behavior, expected test log noise

### Data Cycle
- [x] 2026-04-03  01 Model and Design — ERD review, schema design decisions, index strategy, migration plan
  - https://agents.tools.ooyes.net/workflows/data-cycle/01-model-and-design.yml
  - Created `docs/data-model.md`: ERD overview, 221+ migrations cataloged, core table documentation, design decisions (STI, polymorphic, EAV patterns)
  - **Schema issues found:** order_status_history.order_id/user_id type mismatch (unsignedInteger vs bigInteger), missing indexes on user_id and refunded_by
  - **Migration created:** `2026_04_03_000001_fix_order_tables_column_types_and_indexes.php` — fixes column types, adds missing indexes
  - **Index strategy:** existing coverage is good (content, cart_orders, content_data, categories, cart all indexed); recommended future indexes for menus, media, invoices
- [x] 2026-04-03  02 Migrate and Apply — Zero-downtime migration execution and rollback readiness
  - https://agents.tools.ooyes.net/workflows/data-cycle/02-migrate-and-apply.yml
  - **All migrations applied:** 221+ total, 4 new on filament-5 branch (order_status_history, shipping_tracking, order_refunds, column type fixes)
  - **Rollback tested:** all 4 new migrations have proper `down()` methods; rollback + re-apply cycle verified successfully
  - **Zero-downtime safe:** all migrations are additive (new tables, new columns, column type changes) — no destructive operations, no data loss on rollback
  - **Rollback strategy:** `php artisan migrate:rollback --step=4` reverts all filament-5 order changes cleanly
- [x] 2026-04-03  03 Validate and Monitor — Data integrity checks, query performance, pipeline monitoring
  - https://agents.tools.ooyes.net/workflows/data-cycle/03-validate-and-monitor.yml
  - **Integrity checks:** 0 orphaned content_data, media, categories_items, cart items; 415 orphaned order_status_history (test data residue from deleted test orders — not a production issue)
  - **Query performance:** EXPLAIN analysis on 5 core query patterns; 4/5 used indexes properly
  - **Performance fix:** media table had full table scan on (rel_type, rel_id) — added composite index, query now uses `ref` scan (1 row vs 181)
  - **Table health:** all key tables accessible, row counts verified, no corruption detected

### Onboarding Cycle
- [x] 2026-04-03  01 Environment Setup — Local dev environment, tooling, credentials, verify the build runs
  - https://agents.tools.ooyes.net/workflows/onboarding-cycle/01-environment-setup.yml
  - **Stack:** PHP 8.4.18, Laravel 11.51.0, MariaDB 10.11.14, Node 22.22.1, npm 10.9.4, Composer 2.9.5
  - **App health:** homepage 200 (500ms), admin login 200 (62ms), 626 routes registered
  - **Tests:** Core suite 294 tests pass (19 skipped), full suite 12/12 suites pass from previous run
  - **Build:** `composer validate` clean, `npm run build` available
  - **Database:** `microweber_testing` MySQL DB connected, all migrations applied
- [x] 2026-04-03  02 Explore the Codebase — Architecture tour, key concepts, domain model, flow through the system
  - https://agents.tools.ooyes.net/workflows/onboarding-cycle/02-explore-codebase.yml
  - Created `docs/architecture-guide.md`: directory structure, module system, STI content model, polymorphic relations, Filament admin, Live Edit, domain model (content/commerce/user), request flow, testing strategy, key files for contributors
  - **92+ modules** in `Modules/`, **40+ packages** in `src/MicroweberPackages/`, **626 routes**
- [x] 2026-04-03  03 First Contribution — Pick a starter issue, implement, PR, and get it merged
  - https://agents.tools.ooyes.net/workflows/onboarding-cycle/03-first-contribution.yml
  - **Issue:** Migrate deprecated `dispatchFormEvent` to Filament v5 `callSchemaComponentMethod` in media browser
  - Migrated 6 JS calls across `mw-media-browser.js` and `mw-media-browser.blade.php`
  - Added `#[ExposedLivewireMethod]` attribute to 6 methods in `MwMediaBrowser.php`
  - Updated `addMediaItemMultiple` and `updateImageFilename` signatures for new argument passing pattern
  - 250 tests pass (Modules-Group6A suite), 0 regressions

### Security Cycle
- [x] 2026-04-03  01 Audit — OWASP Top 10 review, dependency CVE scan, secret detection, header check
  - https://agents.tools.ooyes.net/workflows/security-cycle/01-audit.yml
  - Report: `docs/security-audit-2026-04-03.md`
  - Composer: 0 CVEs, 3 abandoned packages
  - npm: 10 vulnerabilities (all in build tooling — laravel-mix chain, no runtime exposure)
  - Secrets: dev-only hardcoded credentials in docker-compose.yml (no production secrets)
  - Headers: 6 missing security headers (CSP, HSTS, X-Content-Type-Options, X-Frame-Options, Referrer-Policy, Permissions-Policy)
  - OWASP: 4 critical (unsafe unserialize), 6 high (SQL injection, weak crypto), 15 medium, 10 low — 35 total findings
- [x] 2026-04-03  02 Remediate — Fix all findings — patch CVEs, fix injection/auth bugs, rotate secrets
  - https://agents.tools.ooyes.net/workflows/security-cycle/02-remediate.yml
  - Critical fixed: added `allowed_classes` restriction to all 7 `unserialize()` calls (ProcessQueueController, UrlManager, FieldsManager, OptionManager, CacheFileHandler x2, Format)
  - High fixed: parameterized 5 `whereRaw()` SQL queries in FilterByPriceTrait and FilterByQtyTrait
  - High fixed: replaced MD5 with SHA-256 for password reset token hashing
  - High fixed: admin middleware no longer grants access when no admin users exist (redirects to site_url)
  - npm: ran `npm audit fix` — lodash CVE patched (10→9 remaining, all unfixable laravel-mix deep deps)
  - Verification: 565 tests pass (Content 64, Core 275, Group3 226), 0 regressions, 0 PHP lint errors
- [x] 2026-04-03  03 Harden — CSP, HSTS, rate limiting, least-privilege, security scanning in CI
  - https://agents.tools.ooyes.net/workflows/security-cycle/03-harden.yml

### Deploy Cycle
- [x] 2026-04-03  01 Prepare Deployment — Artefact build, env config validation, migration review, rollback plan
  - https://agents.tools.ooyes.net/workflows/deploy-cycle/01-prepare-deployment.yml
- [x] 2026-04-03  02 Deploy and Verify — Execute deploy, smoke tests, error rate monitoring, rollback if needed
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
| 1.3 | Login page | Filament built-in login | [x] |

### 2. Website Section

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 2.1 | Website > Pages (list) | `Modules/Page/Filament/Resources/PageResource.php` → ListPages | [~] |
| 2.2 | Website > Pages (create/edit) | PageResource → CreatePage, EditPage | [ ] |
| 2.3 | Website > Posts (list) | `Modules/Post/Filament/Admin/Resources/PostResource.php` → ListPosts | [~] |
| 2.4 | Website > Posts (create/edit) | PostResource → CreatePost, EditPost | [ ] |
| 2.5 | Website > Categories | `Modules/Category/Filament/Admin/Resources/CategoryResource.php` | [x] |
| 2.6 | Media Library | `Modules/MediaLibrary/Filament/Admin/Pages/MediaLibrary.php` | [x] |
| 2.7 | Menu management | `Modules/Menu/Filament/Admin/Pages/AdminMenusPage.php` | [x] |
| 2.8 | Tags | `Modules/Tag/Filament/Resources/TagResource.php` | [x] |
| 2.9 | Comments | `Modules/Comments/Filament/Resources/CommentResource.php` | [x] |

### 3. Shop Section

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 3.1 | Shop > Products (list) | `Modules/Product/Filament/Admin/Resources/ProductResource.php` → ListProducts | [ ] |
| 3.2 | Shop > Products (create/edit) | ProductResource → CreateProduct, EditProduct | [ ] |
| 3.3 | Shop > Categories | `Modules/Category/Filament/Admin/Resources/ShopCategoryResource.php` | [ ] |
| 3.4 | Shop > Orders (list) | `Modules/Order/Filament/Admin/Resources/OrderResource.php` → ListOrders | [x] |
| 3.5 | Shop > Orders (create/edit) | OrderResource → CreateOrder, EditOrder | [x] |
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
| 6.1 | Users list | `UsersResource.php` | [x] |
| 6.2 | User create/edit | UsersResource → CreateUsers, EditUsers | [x] |
| 6.3 | Roles | `RoleResource.php` | [x] |
| 6.4 | Permissions | `PermissionResource.php` | [x] |

### 7. Multilanguage / Translations

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 7.1 | Language settings | `MultilanguageSettingsAdmin.php` | [ ] |
| 7.2 | Translations | `TranslationResource.php` | [ ] |

### 8. Email Marketing / Newsletter

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 8.1 | Newsletter dashboard | `Modules/Newsletter/Filament/Admin/Pages/Homepage.php` | [x] |
| 8.2 | Campaigns | `CampaignResource.php` | [x] |
| 8.3 | Subscribers | `SubscribersResource.php` | [x] |
| 8.4 | Lists | `ListResource.php` | [x] |
| 8.5 | Templates | `TemplatesResource.php` | [x] |
| 8.6 | Template editor | `TemplateEditor.php` | [x] |
| 8.7 | Sender accounts | `SenderAccountsResource.php` | [x] |
| 8.8 | Workflows | `WorkflowResource.php` | [x] |

### 9. Billing / Subscriptions (if enabled)

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 9.1 | Billing dashboard | `Modules/Billing/Filament/Admin/Pages/Dashboard.php` | [x] |
| 9.2 | Subscription plans | `SubscriptionPlanResource.php` | [x] |
| 9.3 | Plan groups | `SubscriptionPlanGroupsResource.php` | [x] |
| 9.4 | Subscriptions list | `SubscriptionResource.php` | [x] |
| 9.5 | Billing users | `BillingUserResource.php` | [x] |
| 9.6 | Billing settings | `Modules/Billing/Filament/Admin/Pages/Settings.php` | [x] |

### 10. Cross-Cutting Design Tasks

- [x] 10.1 Login page — match MW v2 login design
- [x] 2026-04-02  10.2 Dark mode — full QA pass across all pages
  - Added comprehensive dark mode for Media Library: toolbar, search/filters, view toggle, sidebar, folder tree, grid items, list table, detail panel, upload zone, bulk actions, Unsplash panel, empty states, pagination
  - Existing dark mode already covered: body, sidebar, topbar, sections, tables, widgets, modals, dropdowns, inputs, tabs, forms, breadcrumbs, links, login page
  - Dark mode mobile overrides for sidebar/detail border colors
- [x] 2026-04-02  10.3 Mobile responsive — sidebar collapse, table stacking
  - Sidebar collapse: handled natively by Filament (auto slide-over on mobile)
  - Table stacking: Filament responsive tables + existing scroll indicator on orders (fi-ta-ctn::after fade)
  - Media Library 768px: toolbar wraps, search full-width, panels stack vertically, sidebar 200px max-height, detail panel 400px max-height, grid shrinks to minmax(120px)
  - Media Library 480px: grid to 2 columns, date filters narrower, Unsplash grid 2 columns
  - Dashboard: quick stats stack to 1 column at 640px, further reduced gap at 480px
  - Topbar: overflow fix at 640px for avatar clipping
  - Bulk actions bar wraps on mobile
- [x] 2026-04-03  10.4 Form layouts — consistent field spacing, labels, help text
  - Added CSS rules for helper text (12px, $mw-text-muted, 4px top margin), section descriptions (13px), required asterisks (red) with dark mode
  - ContentResource: added helperText to title, URL, price, special price fields
  - OrderResource: added helperText to all payment fields and shipping tracking; added Payment section description
  - Field spacing (16px gap), labels (16px/500), input height (44px), section padding (16px 24px) already correct
  - 464 tests pass (Content 64, Group4 400), 0 regressions
- [x] 2026-04-03  10.5 Table layouts — consistent column widths, row heights, status badges
  - OrderResource: added color() and icon() to order_status badge (pending/processing/shipped/completed/cancelled/refunded)
  - OrderResource: explicit width on ID (80px) and date (140px) columns for consistency
  - Dark mode: added badge color variants (success/warning/danger/info/gray) with tinted backgrounds
  - Dark mode: added table cell text (#e2e8f0), title links, published button styling
  - Row heights (10px padding), header cells (10px/600/uppercase), badges (4px radius, 10px font) already correct
  - 400 Group4 tests pass, 0 regressions
- [x] 2026-04-03  10.6 Modal dialogs — consistent sizing, padding, button placement
  - Modal header: 16px/600 heading, 13px description, 16px 24px padding, bottom border
  - Modal footer: right-aligned buttons, 12px 24px padding, top border, 8px gap, 36px min-height buttons
  - Modal close button: 4px radius, muted color with hover state
  - Modal body: padding consistent with card body (16px 24px)
  - Dark mode: header/footer borders (#1e2330), heading/description/close-btn colors
  - Existing rules already correct: content border-radius (8px), elevated shadow, slide-over panel styling
- [x] 2026-04-03  10.7 Notifications / toasts — match MW v2 notification style
  - Toast container: z-index 120, positioned bottom-right
  - Notification card: elevated shadow, 1px border, 12px 16px padding, 280-420px width
  - Status accents: 3px left border — green (success), orange (warning), red (danger), blue (info)
  - Typography: 13px/600 title, 12px body (secondary), 11px date (muted)
  - Close button: 4px radius, muted color, 0.7 opacity with hover to 1.0
  - Action buttons: 12px font, 28px min-height, 4px radius
  - Database notifications: unread items get blue left border, read items 0.7 opacity
  - Dark mode: #161922 background, #1e2330 border, tinted status accents, light text colors
- [x] 2026-04-03  10.8 Empty states — consistent "no data" illustrations
  - Generic empty state (.fi-empty-state): 3rem padding, centered layout, 320px max-width content
  - Table empty state (.fi-ta-empty-state): matching layout and typography
  - Icon background: 48px circle, blue accent (#ebf4ff bg, #4299e1 icon), consistent across all components
  - Heading: 15px/600 weight, primary color; Description: 13px, muted color
  - Footer actions: 13px buttons, 34px min-height, 4px radius
  - Schema empty state (.fi-sc-empty-state): 2rem padding, centered text
  - Dark mode: tinted blue icon bg (rgba), #e2e8f0 heading, #718096 description
  - Mobile responsive: 1.5rem padding at max-width 768px (pre-existing)
- [x] 2026-04-03  10.9 Loading states — skeleton screens, spinners
  - Spinner: accent blue color (#4299e1 light, #63b3ed dark)
  - Table loading: opacity 0.6 pulse with pointer-events disabled during data fetch
  - Section skeleton (.fi-loading-section): card-style with border, 120px min-height
  - Reusable skeleton placeholders: .mw-skeleton (base), .mw-skeleton-text (14px lines), .mw-skeleton-avatar (40px circle), .mw-skeleton-thumbnail (4:3 ratio)
  - Shimmer animation: gradient sweep (mw-skeleton-shimmer keyframes)
  - Page loading bar: 2px fixed top bar with blue-purple gradient
  - Button spinner: 16px inline spinner inheriting button color
  - Dark mode: #1e2330/#2d3748 skeleton gradient, blue spinner, dark section background
  - Pre-existing: dashboard-loading.css has full overlay, widget shimmer, page transition, button spinner
- [x] 2026-04-03  10.10 Breadcrumbs — consistent styling across all pages
  - Typography: 13px/500 weight, muted color for intermediate items
  - Links: accent blue (#4299e1) with darker hover (#2b6cb0) and underline on hover
  - Current item (last-child): primary color, 600 weight — visually distinct from links
  - Separator chevron: 14px icon, faint color (#a0aec0), 6px gap between items
  - Dark mode: muted text (#718096), lighter blue links (#63b3ed), hover (#90cdf4), current (#e2e8f0), separator (#4a5568)
  - Fixed Sass deprecation: replaced darken() with hardcoded color value
  - Page-specific hides (modules, marketplace) preserved in global.css

- [x] 2026-04-03  make a plan to continue the migration of the old that old design from https://demo.microweber.org/ add to the TODO.md all pages and later we will migrate
  - Updated admin page mapping checklist (sections 1-9) with accurate statuses
  - Added Phase 3 migration tasks below with priority order
  - Reference: old admin screenshots in docs/design-references/, design tokens in docs/research/old-admin-design-tokens.json
  - Approach: each task = screenshot old page → compare with Filament equivalent → apply CSS/layout fixes → verify → commit

---

## Phase 3: Page-by-Page Design Migration (old MW v2 → Filament 5)

> Each task: open the old page at demo.microweber.org, compare with current Filament page, fix CSS/layout/UX gaps.
> Reference screenshots: `docs/design-references/`
> Design tokens: `docs/research/old-admin-design-tokens.json`

### Priority 1 — High-Traffic Pages (visual polish)

- [x] 2026-04-03  migrate: Pages list (2.1) — compare grid/list toggle, thumbnail layout, status badges, action buttons with MW v2
  - Grid/list toggle: already implemented via HasToggleableTable with session persistence
  - Thumbnail: 48px rounded with cover fit, flex-shrink, overflow hidden
  - Title: 14px/500 with primary color, accent hover effect
  - Parent breadcrumb: 12px muted text with slash separators
  - Category badges: 12px accent blue filter buttons
  - Updated timestamp: 11px faint color
  - Action icons: muted color with hover-to-primary transition (view, live edit, settings)
  - Status: published/unpublished inline select column (120px min-width)
  - Homepage indicator: home icon in muted color
  - Dark mode: light title (#e2e8f0), blue hover (#63b3ed), muted metadata (#718096), faint timestamps (#4a5568)
- [x] 2026-04-03  migrate: Pages create/edit (2.2) — compare form tab layout, field ordering, sidebar sections with MW v2
  - Parent page tree: constrained to 320px max-height with scroll (was 2000px+ unconstrained)
  - Menu checkbox list: constrained to 240px max-height with scroll (was 12000px+ with 639 checkboxes)
  - Sidebar sections: tighter 16px vertical spacing between Published, Parent page, Tags, Menus
  - Collapsible sections: hover feedback, compact padding when collapsed (SEO sub-sections)
  - Tags input: pill-style with accent-light background, 13px font, 4px radius
  - Rich editor: border styling, toolbar with subtle background
  - Checkbox list: 14px font, proper text color
  - Header actions: consistent 8px gap between Delete, Live edit, Save buttons
  - Custom scrollbars: 6px width, rounded thumb for tree and checkbox containers
  - Dark mode: tree border (#1e2330), dark bg, light scrollbar; tags with blue tint; rich editor dark toolbar
- [x] 2026-04-03  migrate: Posts list (2.3) — compare column layout, author display, date formatting with MW v2
  - Author display: added author name with user icon in grid view for posts (mw-content-author class)
  - Date formatting: posts show "Published: Apr 03, 2026" (from posted_at) instead of verbose "Updated" timestamp; pages still show "Updated" format
  - List view: added sortable Published date column (posted_at) visible only on Posts list page
  - Author column: added user icon to list view Author column
  - Metadata layout: consolidated author + date into flex row with gap spacing (mw-content-meta)
  - Dark mode: proper colors for author (#718096) and date (#4a5568) metadata
  - CSS: added .mw-content-author, .mw-content-date, .mw-content-meta styles to theme SCSS
  - No regressions: Pages list still shows "Updated" format without author; 19 Content tests + 1 Post test pass
- [x] 2026-04-03  migrate: Posts create/edit (2.4) — compare editor layout, excerpt field, publish section with MW v2
  - Section icons: added heroicon icons to all sidebar sections (Published=signal, Parent page=folder, Tags=tag, Menus=bars-3, Media=photo)
  - Publish Date: added calendar prefix icon for better visual identity
  - Excerpt textarea: subtle background (#fafbfc light, #1a1d28 dark), consistent border/radius/padding
  - Date picker input: border-radius consistency with other fields
  - Dark mode: excerpt textarea with dark bg, proper border color, light text
  - No regressions: Pages create form also benefits from section icons; 20 tests pass
- [x] 2026-04-03  migrate: Products list (3.1) — compare product grid cards, price display, stock badges with MW v2
  - Price display: added product price to grid view with currency symbol from settings, bold 14px/600 styling
  - Special/sale price: strikethrough original price + red sale price when special price is active
  - Stock badges: added status icons (check-circle, exclamation-triangle, x-circle) to In Stock/Low Stock/Out of Stock badges
  - CSS: .mw-product-price, .mw-price-current, .mw-price-original, .mw-price-special classes
  - SVG scoping: changed action icon CSS from `.group svg` to `.group a svg` to prevent coloring stock badge icons
  - Dark mode: light price text (#e2e8f0), muted original (#718096), sale red (#fc8181)
  - No regressions: Posts and Pages lists unaffected; 20 Content tests pass
- [x] 2026-04-03  migrate: Products create/edit (3.2) — compare variant tab, pricing section, shipping fields with MW v2
  - Section icons: Pricing (currency-dollar), Inventory (cube), Shipping (truck) on Product Details tab
  - Content tab Pricing section also gets currency-dollar icon (visible for products only)
  - Variants tab: already has swatch icon and "Save the product first" empty state — no changes needed
  - All sidebar sections already have icons from Posts 2.4 task (Published, Parent page, Tags, Menus, Media)
  - Dark mode: verified all sections display correctly
  - No regressions: 20 Content tests pass
- [x] 2026-04-03  migrate: Media Library (2.6) — compare 3-panel layout, upload zone, detail panel spacing with MW v2
  - Grid cards: added visible borders, rounded corners (radius-md), surface background for better card definition
  - Thumbnails: changed object-fit from `contain` to `cover` for natural image display
  - Grid spacing: increased gap (10→14px) and padding (12→16px) for breathing room
  - Card labels: added border-top separator, font-weight 500, muted text color
  - Folder sidebar: folder count badges now use pill styling with background
  - Toolbar: added bottom border separator
  - Panels container: added subtle box-shadow
  - Dark mode: verified grid cards, detail panel, folder sidebar, toolbar all render correctly
  - No regressions: 113 Media tests pass
- [x] 2026-04-03  migrate: Menu management (2.7) — compare tree editor, item cards, drag handles with MW v2
  - Migrated all hardcoded hex colors to $mw-* SCSS variables for consistency
  - Menu items: increased padding/gap, subtle border styling
  - Type icons: added rounded background pill (28x28) for visual weight
  - Drag handles: improved opacity and color using $mw-text-muted
  - Tree nesting: improved sortable placeholder with accent color
  - Dark mode: updated toolbar, tree, items, type icons with proper dark palette
  - No regressions: 13 Menu tests pass

### Priority 2 — Shop Pages

- [x] 2026-04-03  migrate: Shop Categories (3.3) — compare tree layout, category cards with MW v2
  - Added bordered container with rounded corners for category tree list
  - Improved tree item hover states with subtle box-shadow
  - Added section icon (heroicon-m-folder-open) to "Parent Page or Category" sidebar section
  - Dark mode: dark border/background for tree container
  - No regressions: 45 Category tests pass
- [~] migrate: Variant Attributes (3.6) — compare attribute list, value editor with MW v2
- [ ] migrate: Coupons (3.9) — compare list/form layout with MW v2
- [ ] migrate: Offers (3.10) — compare offer cards, conditional rules UI with MW v2
- [ ] migrate: Invoices (3.11) — compare invoice list, print layout with MW v2
- [ ] migrate: Payments (3.12) — compare payment list, status display with MW v2
- [ ] migrate: Taxes (3.15) — compare tax list, rate configuration with MW v2
- [ ] migrate: Checkout flow (3.17) — compare checkout wizard steps with MW v2

### Priority 3 — Settings Pages

- [ ] migrate: Settings hub (4.1) — compare card grid layout, icons, navigation with MW v2
- [ ] migrate: General settings (4.2) — compare form sections, site info fields with MW v2
- [ ] migrate: Template settings (4.3) — compare template picker, live preview with MW v2
- [ ] migrate: SEO settings (4.4) — compare meta fields, sitemap config with MW v2
- [ ] migrate: Email settings (4.7) — compare SMTP form, test email button with MW v2
- [ ] migrate: Mail templates (4.9) — compare template editor, variable list with MW v2
- [ ] migrate: Login & Register settings (4.11) — compare toggle options, social login config with MW v2
- [ ] migrate: Advanced settings (4.12) — compare developer options layout with MW v2
- [ ] migrate: Template Customizer (4.6) — compare color pickers, typography, live preview with MW v2

### Priority 4 — System & Admin Pages

- [ ] migrate: Modules list (5.1) — compare module cards, enable/disable toggles with MW v2
- [ ] migrate: Marketplace (5.2) — compare marketplace grid, install buttons with MW v2
- [ ] migrate: Updates (5.3) — compare update status, changelog display with MW v2
- [ ] migrate: Backup & schedules (5.5) — compare backup list, schedule form with MW v2
- [ ] migrate: Maintenance mode (5.4) — compare toggle, preview message with MW v2

### Priority 5 — Remaining Pages

- [ ] migrate: Language settings (7.1) — compare language list, flag icons, default selection with MW v2
- [ ] migrate: Translations (7.2) — compare translation table, import/export with MW v2
- [ ] migrate: Privacy Policy (4.10) — compare policy editor, consent options with MW v2
- [ ] migrate: Cookie Notice (4.13) — compare banner preview, settings with MW v2
- [ ] migrate: File Manager (4.14) — compare file browser layout with MW v2
- [ ] migrate: Comments settings (4.15) — compare moderation options with MW v2
- [ ] migrate: Custom HTML tags (4.5) — compare code editor, placement options with MW v2
- [ ] migrate: Auto-respond emails (4.8) — compare email template forms with MW v2
- [ ] migrate: Error tracking (5.6) — compare error list, stack trace display with MW v2
- [ ] migrate: AI settings (5.7) — compare API key form, model selection with MW v2
- [ ] migrate: White Label (5.10) — compare branding fields, logo upload with MW v2
