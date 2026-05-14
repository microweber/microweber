# Project Summary

## Architecture
- Microweber is a Laravel 11 modular monolith bootstrapped through `bootstrap/app.php`, which uses `MicroweberPackages\App\LaravelApplication` instead of the stock `Application`.
- Core framework code lives under `src/MicroweberPackages/`, while product features live under `Modules/` and frontend/theme assets live under `packages/`.
- The largest domain models currently include `Modules/Content/Models/Content.php` and `src/MicroweberPackages/User/Models/User.php`.
- Two active Filament v5 panels: `admin` (default) and `checkout`. Scope CSS overrides via `body.fi-panel-admin` / `body.fi-panel-checkout`.
- Live Edit is a two-surface system: the Filament admin frame (`AdminLiveEditPage.php`) wraps an iframe canvas (`iframe-page.blade.php`); they communicate via `window.dispatchEvent(new CustomEvent('<verb>'))` (e.g. `liveEditSaveCallMountedAction`, `liveEditUndoLastPublish`, `liveEditInsertLayoutRequest`).
- Vue 3 SFC toolbar lives in `packages/frontend-assets/resources/assets/ui/components/Toolbar/` and is what users see at the top of the Live Edit canvas.
- `Product` extends `Content`; `Cart` is a state manager over Content/Product rows — not a separate data owner. Page/Post bodies live in both `content_body` and `content` columns.
- Color tokens are centralized in `MicroweberPackages\Filament\Themes\MwColors` (primary blue = Bootstrap `#0d6efd` across admin + checkout + public). Use `MwColors::Blue`, never `Color::Blue`.
- mw-modal (Livewire generic modal at `src/MicroweberPackages/Livewire/resources/views/mw-modal.blade.php`) has a focus-trap + Escape + focus-return contract (AI-240); additions like a close-X must filter out of *initial* focus while staying in the Tab cycle.
- Documentation site is VitePress under `docs/`; per-module pages live at `docs/modules/<name>/{index,installation,usage,api,examples,troubleshooting}.md`.
- 95 feature modules under `Modules/` (Accordion, Address, Ai, AiWizard, Attributes, Audio, Background, Backup, BeforeAfter, Billing, Blog, Breadcrumb, Btn, Captcha, Cart, Category, Checkout, Cloudflare, Comments, …) — confirmed total per 2026-05-14 tester audit. Six asset packages under `packages/`: `frontend-assets`, `frontend-assets-libs`, `filament-modules`, `microweber-filament-theme`, `laravel-config-extended`, `laravel-helper-functions`.
- HTTP routes are wired in `bootstrap/app.php` via `withRouting()` and load: `routes/web.php` (public + admin), `routes/api.php` (REST), `routes/ecommerce-api.php` (shop endpoints, applied via the `api` middleware group), `routes/module-api.php` (per-module endpoints), `routes/console.php` (artisan commands).
- DB schema lives in `database/migrations/` (core) plus per-module `Modules/<Name>/database/migrations/` directories where present. SQLite files `database/database.sqlite` and `database/testing.sqlite` exist but the PHPUnit suite targets MySQL `microweber_testing`.
- Admin settings pages extend `MicroweberPackages\Admin\Filament\Pages\AdminSettingsPage` (abstract base); save is Livewire-reactive — subclasses do not implement a submit action.
- `MicroweberPackages\Filament\Support\FilamentRegistry` is the plugin-driven entry point: `registerPage()`, `registerResource()`, `registerWidget()`, `registerPlugin()` register elements onto the admin panel.
- LiveEdit canvas runs 3 concurrent Filament forms; the save-flow specificity ranking (highest priority wins) is `callMountedTableBulkAction` > `TableAction` > `FormComponentAction` > `Action` (mountedAction).
- Sitemap freshness gate (`Modules/Sitemap/Http/Controllers/SitemapHelpersTrait.php::needToUpdateSitemap()`) rebuilds only when the file is missing, unreadable, or older than 3 hours — pinned by `tests/Feature/SitemapCacheTtlContractTest.php`.
- Tier-1 narrative documentation now covers 9 modules at `docs/modules/<name>/` (product, cart, checkout, order, search, seo, sitemap, admin, liveedit). Each module ships 6 pages: index, installation, usage, api, examples, troubleshooting. Landing page at `docs/modules/README.md` groups them by sub-cluster (e-commerce, content-discovery, admin shell); auto-generated 2026-04-25 filesystem index preserved below.
- Multilanguage gate: `MicroweberPackages\Multilanguage\MultilanguageHelpers` controls every locale-aware admin/public surface. Per-locale settings persist as `ModuleOption` rows keyed by locale.
- Skin/template system: public pages render via `userfiles/templates/<template>/` (active template chosen at runtime). The Filament admin panel and the public template renderer are independent rendering pipelines.
- LiveEdit `iframe-page.blade.php` (~2054 lines) is the canonical home of two-surface coordination logic — verb listeners, save bridges, undo/redo snapshots, picker dual-routing. Treat as a single locus; do not split without an architectural dispatch.
- Helper-layer security boundary (ADR-0001): every helper that emits HTML/CSS/JS must apply two-pass escape, URL protocol allowlists (for `href`/`src`/CSS `url()`), and fail-closed sanitization. Context-aware helpers (e.g. `safe_css_url` vs `safe_html_attr`) — one helper per output context.
- A third Filament panel exists: customer Profile (`Modules/Profile/Providers/FilamentProfilePanelProvider.php`, panel id `profile`, mounted at `/profile`). Scope CSS via `body.fi-panel-profile`. Used by AI-518 customer-mobile fixes.
- Touch-target CSS is split across **two build pipelines** based on what owns the rendered HTML — a single mobile-audit fix may need rules in BOTH: public-template HTML → `Templates/Bootstrap/resources/assets/css/public-touch.css` (Vite, served mirror at `public/templates/bootstrap/css/public-touch.css`); Filament-panel HTML → `packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css` (Webpack, served bundle at `public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css`). Reference: AI-518 ships rules in both files.

## Module Map (selected — full list under `Modules/`)
- **Content & taxonomy:** Content, ContentData, ContentDataVariant, ContentField, Category, Comments, ContactForm.
- **E-commerce:** Cart, Checkout, Coupons, Currency, Billing, Address, Country.
- **Media & UI primitives:** Audio, Background, BeforeAfter, Breadcrumb, Btn, Accordion, Captcha, CookieNotice.
- **Operations / infra:** Backup, Cloudflare, Ai, AiWizard.
- **Pattern:** each module owns its Filament resource under `Modules/<Name>/Filament/Admin/<Resource>/Pages/`, its model under `Modules/<Name>/Models/`, and optionally its own migrations directory `Modules/<Name>/database/migrations/`.

## Naming & Conventions
- PHP tests use PHPUnit 11 with `#[Test]` attributes; existing class-style tests prefer descriptive `it_*` method names.
- The repository’s broad PHP suites are defined in `phpunit.xml`, and the memory-safe full-suite runner is `./run-tests.sh`.
- Conventional Commits are already documented in `CONTRIBUTING.md` and used for task-by-task commits.
- Filament action keys are camelCase ending in `Action` (e.g. `addImageAction`, `generateAction`, `saveContentAndGoLiveEditIframe`).
- Filament page classes mirror Filament defaults: `Create<Name>`, `Edit<Name>`, `<Name>Resource`; pages register form via `static::getResource()::form($schema)`.
- CSS class prefix `mw-*` for project-bespoke styles (never inside the Filament `.fi-*` namespace). HTML data attributes use `data-mw-*`. localStorage keys use `mw-*`.
- Toolbar Vue back-compat hooks (e.g. `id="toolbar-user-menu-button"`, `class="mw-le-hamburger"`) are referenced by external code — preserve them when restyling toolbar elements.
- Picker synonym maps (e.g. in `add-content-modal.blade.php`) are keyed by action name with a space-joined synonym string as the value — used by the Alpine search to match user intent.

## Key Files
- `public/index.php` — HTTP entry point served by Apache/nginx; boots Laravel via `bootstrap/app.php`.
- `artisan` — CLI entry point for migrations, queue workers, scheduler, custom commands.
- `bootstrap/app.php` — Laravel bootstrap; instantiates `MicroweberPackages\App\LaravelApplication`; wires routes via `withRouting()`; configures rate limiters.
- `routes/web.php` — public + admin web routes.
- `routes/api.php` — base REST API routes.
- `routes/ecommerce-api.php` — shop/cart/checkout endpoints (loaded inside the `api` middleware group).
- `routes/module-api.php` — per-module API endpoints.
- `routes/console.php` — artisan command bindings.
- `config/app.php` — service providers, aliases, app-level config (companion config files for each subsystem live alongside).
- `.env` / `.env.example` — environment configuration. Contains DB credentials, app key, mail driver, and (per `.gitignore`) is never committed. Tests read from `phpunit.xml` env block or `.env.testing`.
- `database/migrations/` — core schema migrations. Per-module migrations live under each `Modules/<Name>/database/migrations/`.
- `phpunit.xml` — canonical PHPUnit suite layout for Unit, Feature, Core, module groups, and Templates.
- `run-tests.sh` — split-process runner used to avoid PHP memory fragmentation during large suite runs.
- `package.json` — root frontend/docs/security npm scripts. Root `npm run build` shells out to `run-build.js`; `npm run dev` shells out to `run-dev.js`.
- `run-build.js` / `run-dev.js` — orchestrate per-package builds across `packages/<pkg>/`.
- `.github/workflows/cicd-pipeline.yml` — CI stages for quality, security, and automated tests.
- `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php` — Live Edit admin frame (actions, modals, save bridge).
- `src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php` — Live Edit canvas iframe (event listeners, in-canvas glue).
- `src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php` — picker modal with Alpine search + synonym map.
- `src/MicroweberPackages/Livewire/resources/views/mw-modal.blade.php` — generic Livewire modal wrapper (focus trap, backdrop dismiss, close X).
- `src/MicroweberPackages/Filament/Themes/MwColors.php` — canonical color token authority.
- `src/MicroweberPackages/Admin/Filament/Pages/Login.php` — admin login (autocomplete + inputmode tokens).
- `src/MicroweberPackages/Filament/resources/views/filament-forms/components/mw-media-browser.blade.php` — media browser dropzone affordance.
- `Modules/Content/Filament/Admin/ContentResource/Pages/CreateContent.php` — Content create page; uses `getInitialData()` to pre-fill from query string.
- `packages/frontend-assets/resources/assets/ui/components/Toolbar/{Toolbar,SaveButton,AddContentButton}.vue` — Live Edit toolbar Vue SFCs.
- `packages/frontend-assets/resources/assets/css/microweber/css/ui.css` — toolbar styles; mirror new toolbar rules in `packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css` when admin needs them too.
- `docs/.vitepress/config.js` — VitePress sidebar registry; append a sidebar entry whenever new module docs land.
- `.autodev/SOUL.md` / `.autodev/JOURNAL.md` / `.autodev/CONTRACTS.md` — agent identity, research log, and contact directory (read at session start).
- `NOVICE_REPORT.md` — novice-customer persona audit findings (P0..P3).
- `TODO.md` / `DONE.md` — live task queue and append-only archive.
- `CHANGELOG.md` — completed-task technical record (prepended newest-first; written before each commit).
- `LESSONS.md` — correction patterns and guardrails (append-only, newest at top).
- `TROUBLESHOOTING.md` / `SETUP.md` — living failure register and onboarding guide.
- `Modules/Sitemap/Http/Controllers/SitemapHelpersTrait.php` — sitemap cache-TTL gate (3hr file-mtime threshold).
- `tests/Feature/SitemapCacheTtlContractTest.php` — reference pattern for trait-only PHPUnit contract tests (anonymous class consumes the trait, no DB, no Laravel boot).
- `tests/Feature/Ai518CustomerTouchTargetContractTest.php` — reference for two-surface bounded-slice contract test (mobile-touch.css + public-touch.css) with admin/checkout boundary guard. Slice-bounding pattern: scope an assertion block by `strpos` from marker to closing brace, NOT to EOF.
- `tests/Feature/Ai519CouponCoverageContractTest.php` — reference for coverage-analysis regression-guard test (no CSS changes; pins the inheritance chain when recon shows inherited coverage; flags partial-coverage gaps as `AI-NNNa follow-up candidate` in docblock).
- `Modules/Profile/Providers/FilamentProfilePanelProvider.php` — customer Profile Filament panel registration.
- `docs/modules/README.md` — tier-1 narrative-docs landing page with sub-cluster tables.
- `src/MicroweberPackages/Admin/Filament/Pages/AdminSettingsPage.php` — abstract base for admin settings pages.
- `src/MicroweberPackages/Filament/Support/FilamentRegistry.php` — plugin-driven Filament registration entry point.
- `docs/adr/0001-helper-layer-security.md` — helper-layer security model ADR (two-pass escape, URL allowlists, sanitizer fail-closed, context-aware helpers).
- `packages/frontend-assets/resources/assets/css/microweber/css/design-system.css` — canonical CSS token authority (`--color-*`, `--radius-*`, `--space-*`). Override at the token, not per-template.
- `packages/frontend-assets/resources/assets/css/microweber/css/public-touch.css` + `mobile-touch.css` — public-facing UX rules; mobile-only and touch-target adjustments scope here.
- `userfiles/templates/<template>/` — active skin/template renders public pages (filesystem-driven, not in Filament).
- `src/MicroweberPackages/Multilanguage/MultilanguageHelpers.php` — locale gate for admin and public surfaces (`shouldShowOnAdminEditor()` etc.).

## Build & Run

### Install
- `composer install` — PHP dependencies.
- `npm install` — root JS dependencies.

### Development
- `php artisan serve` — local PHP dev server (default `http://127.0.0.1:8000`). Production typically uses Apache/nginx in front of `public/index.php`.
- `npm run dev` (root) — orchestrates per-package dev/watch builds via `run-dev.js`.
- Per-package dev builds (scoped, faster):
  - `cd packages/frontend-assets && npm run dev` (Vite watch).
  - `cd packages/microweber-filament-theme && npm run dev` (Webpack watch).
- VitePress docs preview: `cd docs && npm run docs:dev`.

### Production / one-shot build
- `npm run build` (root) — orchestrates production builds across all packages via `run-build.js`.
- Per-package production builds:
  - `cd packages/frontend-assets && npm run build` (Vite).
  - `cd packages/microweber-filament-theme && npm run build` (Webpack).
- VitePress docs production build: `cd docs && npm run docs:build`. Must stay green; targeted `ignoreDeadLinks` patterns in `docs/.vitepress/config.js` whitelist filesystem-pointer refs (`../../Modules/`, `.php`, etc.) so real dead-link detection still catches new ones.
- After editing anything under `packages/<pkg>/resources/**` (Vue, SCSS, CSS, JS, TS), rebuild that package — the running app serves the built bundle, not source.
- `cd packages/<pkg>` (or any subdir) leaves the shell CWD in that subdir for subsequent commands. For later `git`, `npm`, or tool calls in the same dispatch, prepend `cd /home/headless/Documents/GitHub/microweber && ...` or use absolute paths — otherwise pathspecs fail with `did not match any files`.

### Tests
- `composer test` — main PHPUnit run.
- `./run-tests.sh` — split-process runner that groups suites separately (avoids the ~6 MB/test memory leak).
- `vendor/bin/phpunit --filter=<ClassName>` — run a single test class (fast iteration on contract tests).
- `vendor/bin/phpunit tests/Feature/<File>.php` — run a single file.
- Test DB is MySQL `microweber_testing` (real DB, not sqlite, not mocked); kill any prior PHPUnit run before starting a new one.

### Operational
- `php artisan migrate` — apply core + module migrations.
- `php artisan queue:work` — process background jobs.
- `php artisan schedule:run` — invoked by a system cron each minute.

## Gotchas & Known Issues
- The local Apache-served runtime in this environment returns `404` for `/admin/login` even though `http://127.0.0.1` responds with `200`, so browser verification can fail for environment reasons rather than app regressions.
- The full PHP test surface is intentionally split by `run-tests.sh` because long single-process runs hit PHP memory fragmentation/OOM issues (~6 MB/test leak).
- The root repo now documents PHPUnit + `run-tests.sh` as the active entrypoints; the remaining Pest helper files under `docs/testing/` are optional scaffolding only, not live root configuration.
- Live-edit theme helpers must stay scoped to `.mw-admin-live-edit-page`; unscoped Filament tab/input/modal overrides leak into unrelated admin screens and create recurring visual regressions.
- No global `[x-cloak] { display: none }` rule exists. Alpine paints once before init, so any element with text bindings flashes literal `""`. Use inline `style="display: none;"` defaults for empty states instead of relying on `x-cloak`.
- Bash heredoc appends to CSS files have produced reproducible mid-stream duplications. Append via the `Edit` tool with `old_string` = existing file tail, `new_string` = tail + new block.
- `->modalCancelAction(false)` is not the correct Filament API — use `->modalCancelActionLabel('Cancel')`.
- Filament RichEditor renders into `[contenteditable=true]` inside `.fi-fo-rich-editor` / `.ProseMirror`; it does NOT bubble plain `input` events reliably. Listen on `input` + `keyup` + `blur` together when watching for content changes.
- Do not use PHPUnit `RunInSeparateProcess`, `DatabaseTransactions`, or `RefreshDatabase` traits, and never run tests in parallel — the suite is not safe under those modes.
- Window-event handler names registered between admin frame and iframe must match exactly on both sides (`liveEditSaveCallMountedAction`, `liveEditUndoLastPublish`, `liveEditInsertLayoutRequest`). Adding a new verb means editing both files.
- VitePress + Vue interpolation: `{{ expression }}` in markdown prose or inline backticks (outside fenced code blocks) is parsed by Vue at SSR. If the expression isn't a real method on the rendering context, `npm run docs:build` fails with `TypeError: _ctx.<x> is not a function`. Escape with `&#123;&#123; expression }}`. Fenced ```code blocks``` are safe.
- VitePress `ignoreDeadLinks` regex patterns must accept the `./` prefix that nested pages emit — don't anchor with `^`. Whitelist filesystem-pointer paths so real dead-link detection still catches new ones.
- Sitemap previously had an unconditional `return true;` at the top of `needToUpdateSitemap()` that bypassed the 3hr TTL and forced regeneration on every request. Fixed (commit 7386606cd1) and pinned by a contract test that re-fails if the short-circuit returns.
- Filament `Action::make()->color('success')` rendered with `size=lg` (e.g. "Place Order") applies inline styles that beat normal CSS selectors — enforcing `min-height` requires `!important` on `button.fi-btn.fi-color-success`. Discovered AI-517.
- JIRA queue drift: PM's view can run 1–4 cycles behind on-the-ground state when ship cadence exceeds queue-refresh interval. Observed 3+ times in a single session (AI-322..AI-335 tier-1 docs; AI-326 hardening; AI-510..AI-515 Phase 1 UX). Mitigation: status-correction reply with ship-table + commit hashes + JIRA-refresh recommendation, no defensive tone.

## Decisions
- Cache deserialization in `TaggableFileStore` is hardened with `unserialize(..., ['allowed_classes' => false])` to avoid object-injection risk from poisoned cache files.
- Treat `phpunit.xml` + `run-tests.sh` as the authoritative test entrypoints for the repository root.
- Keep live-edit-specific Filament overrides scoped to the live-edit wrapper instead of styling raw `.fi-*` selectors globally in the admin theme bundle.
- The window-event `CustomEvent` bridge is the canonical communication channel between the Live Edit admin frame and the canvas iframe. New verbs are added by registering matching listeners on both surfaces.
- Primary brand blue is Bootstrap `#0d6efd` across admin + checkout + public, sourced from `MwColors::Blue`. Filament Color enum direct use is avoided so the single token can be re-anchored project-wide.
- Touch-target floor is 44×44 px (WCAG 2.5.5) for all interactive elements. Mobile-only adjustments live in `mobile-touch.css`.
- Length caps for cross-surface query-string carry-forward (Live Edit → Filament Create page): `title` 256 chars, `content_body` 6 KB, `description` 2 KB. Apply caps on both producer (JS) and consumer (`getInitialData()`) sides as defence-in-depth.
- `protected function getInitialData(): array` is the canonical hook for pre-filling Filament Create pages from URL query strings. Do not modify the form schema for this.
- Bounded slice + contract test is the dominant winning pattern. Avoid speculative mechanical refactors without RTL/test content or an explicit dispatch.
- VitePress sidebar entries in `docs/.vitepress/config.js` MUST be appended whenever new module docs land — the docs site does not auto-discover.
- Tier-1 narrative docs (`docs/modules/<name>/`) are the canonical guides; the auto-generated `docs/modules/README.md` filesystem index is a snapshot reference, not the primary entry. Preserve the auto-generated section below the manual landing tables — never delete it.
- Contract tests for helper traits use the anonymous-class pattern: `new class { use TheTrait; };`. Gives a no-DB, no-Laravel-boot unit-test surface — reference `tests/Feature/SitemapCacheTtlContractTest.php`.
- Pure-ack PM emails are never replied to (per `.autodev/CONTRACTS.md` routing). Even celebratory or milestone-marking acks — no ack-of-ack chain.
- Phase 1 UX baseline (AI-510..AI-515) shipped 6 commits, 121 PHPUnit tests, 489 assertions, ~1700 lines of CSS+JS+tests. Deferred follow-ups (AI-510a/b/c, AI-511a/b/c, AI-512a/b/c, AI-513a/b, AI-514a) are documented in CSS comments and await explicit dispatch.
- Helper-layer security principles (ADR-0001): two-pass escape; URL protocol allowlist (`http`/`https`/`mailto:`/`tel:` + `data:image/*` for `<img>` only — never `javascript:` / `data:text/html` / `vbscript:` / `file://`); sanitizers fail closed; one helper per output context.
- Sitemap cron strategy: project ships no default schedule. Three documented patterns — Laravel scheduler (`schedule:run` hourly), direct system cron, event-driven invalidation on content save — see `docs/modules/sitemap/usage.md`.
- CSS-token chain is the canonical unification surface (`--color-product-title` → `--color-text-primary`). Override at the token; avoid per-template patches that drift.
- Filament checkbox 44×44 hit-area uses the cross-browser `:has(.fi-checkbox-input)` pattern on BOTH `.fi-fo-field-wrp` AND `label`. Pattern verified across AI-517 (checkout) and AI-518 (profile) panels.
- Coverage-analysis regression-guard pattern: when recon shows a feature inherits existing CSS coverage, ship a contract test that pins the inheritance chain (no CSS changes) and flag any partial-coverage gap as `AI-NNNa follow-up candidate` in the docblock. Reference: AI-519 Coupon ships `Ai519CouponCoverageContractTest` with no CSS, flagging Surface 4 (admin form min-height) as follow-up.
- Mobile-audit fix surfaces are determined by who owns the rendered HTML — public-template HTML routes to `Templates/Bootstrap/.../public-touch.css` (Vite); Filament-panel HTML routes to `packages/microweber-filament-theme/.../mobile-touch.css` (Webpack). A single audit (e.g. AI-518 Customer) may need rules in BOTH files.
- Commerce-mobile arc (Batch 1 P1+P2 commerce tickets): AI-516 (`3157870616`), AI-517 (`81ffb093fb`), AI-518 (`a9f8dd7a1c`), AI-519 (`a188cdcbfe`) shipped 2026-05-14 — 25 PHPUnit tests / 91 assertions green across `PublicTouchTargetContractTest`, `Ai517CheckoutTouchTargetContractTest`, `Ai518CustomerTouchTargetContractTest`, `Ai519CouponCoverageContractTest`. AI-520 Shipping + AI-521 Payment queued. Deferred: AI-519a (admin form min-height gap) pending tester measurements.
- Live Edit mobile layout: `x-init="..."` attributes must use ONLY single-quoted string literals internally — any `"double quote"` inside terminates the HTML attribute at the first `"`. A misplaced JS comment containing `<form wire:submit...>` became real DOM ~460px tall, pushing the Vue toolbar completely off-screen. Rule: all JS strings inside `x-init="..."` must be single-quoted.
- `padding-top` on `fi-main-ctn` can fight with fixed `top: var(--toolbar-height)` on the canvas `#live-edit-frame-holder` on mobile. On mobile live-edit, there is no Filament fixed topbar — the 56px `padding-top` that was added in cycle-86 (AI-116/TICKET-CI) to guard against topbar overlap instead created a 56px dead zone AND 52px toolbar/canvas overlap. The correct geometry: single-row toolbar (`max-height: 56px`) + canvas at `top: 60px` (4px gap) — no `fi-main-ctn` padding needed.

## Dependencies (non-obvious)
- `nwidart/laravel-modules` powers the feature-module layout under `Modules/`.
- Filament 5 + Livewire 4 drive the admin UI and many live-edit flows.
- Root frontend asset builds are orchestrated through `run-build.js` and package-level build scripts.
- `packages/frontend-assets/` builds with Vite; `packages/microweber-filament-theme/` builds with Webpack. The two are not interchangeable.
- VitePress (under `docs/`) is the documentation site engine.
- Locale-aware UI relies on `MultilanguageHelpers` + per-locale `ModuleOption` rows — no separate i18n library; admin URL locale switch lives at `/admin/lang/{locale}`.
- The `public` storage disk (`storage/app/public` symlinked to `public/storage`) is the canonical home for uploaded media; `Media::create(['filename' => Storage::disk('public')->url($path), ...])` is the canonical insert shape.

## Credentials
- None recorded in this repository summary.
