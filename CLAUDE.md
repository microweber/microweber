<!-- autodev:profile-ref:begin -->
file:///home/headless/Documents/GitHub/microweber/AGENTS.md
<think>
IMPORTANT: AGENTS.md is the primary instruction file. Read and follow all instructions in AGENTS.md before proceeding.
</think>
<!-- autodev:profile-ref:end -->

---

# Microweber — Project Guide

Modular PHP CMS on **Laravel 11**. This file is the fast-start map; keep it in sync with `.github/copilot-instructions.md` and `AGENTS.md`.

## Stack & layout
- **Admin UI:** Filament v5 panels (`admin`, `checkout`, `profile`). Public site = Laravel + Microweber templates.
- **Branches:** work on `filament-5`; main remote is `master`. Never amend pushed commits or force-push these.
- **Three source roots:**
  - `src/MicroweberPackages/<Pkg>/` — core packages (PHP + Filament + Blade + Livewire).
  - `Modules/<Name>/` — pluggable modules (Content, Product, Category, Media, Cart, Menu …). Filament resources at `Modules/<Name>/Filament/Admin/`.
  - `packages/<pkg>/` — build-managed JS/CSS bundles. `frontend-assets/` (Vite) and `microweber-filament-theme/` (Webpack).
- **Templates:** `Templates/<Name>/` (Bootstrap, Big — core, tracked). Layout skins under `resources/views/modules/layouts/templates/<category>/skin-N.blade.php`. Scanned from `base_path('Templates')`; activated via `storage/templates_statuses.json` + the `current_template`/`template` option.
- **Docs:** VitePress under `docs/` (narrative module docs at `docs/modules/<name>/`).

## Core domain rules
- **Product `extends` Content.** Cart is a state manager over Content/Product rows, not a separate data owner.
- Content `content_body` and `content` both hold rendered HTML on Page/Post. Arbitrary metadata via `setCustomField` / `setContentData`.
- `<module type="X" template="skin-N" .../>` is the template/module system. A Blade component may **emit** a module tag with params, but must **never** receive module markup as a string (the parser rewrites every `<module ` occurrence, including inside string literals → fatal). Never wrap module tags in `parse_modules_html(...)`.
- Module type strings aren't always the folder name: posts=`posts`, products=`shop/products`, team=`teamcard`, testimonials=`testimonials`.

## Blade component system (`Modules/Components`)
- Reusable `<x-…>` components: class in `View/Components/`, view in `resources/views/components/`, registered via `Blade::component('name', Class::class)` in `ComponentsServiceProvider`.
- kebab attribute → camelCase prop (`size-lg`→`$sizeLg`). Bound `:attr` passes arrays/bools/expressions; plain `attr` passes strings. A `$class` prop must flow through `$attributes->merge([...])` or it's dropped.
- **Bridge components** delegate to real modules (e.g. `<x-social-links>` emits `<module type="social_links"/>`, `<x-video-embed>` emits `<module type="video"/>`) — the right way to wrap functional modules.
- Add a unit test per component (`Blade::render('<x-… />')` asserting key classes) + a contract test pinning skin conversions.

## Live Edit
- Admin frame: `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php`. Canvas iframe: `src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php`.
- They talk via `window.dispatchEvent(new CustomEvent('<verb>'))`; existing verbs: `liveEditSaveCallMountedAction`, `liveEditUndoLastPublish`, `liveEditInsertLayoutRequest`, `mwOpenPageChip`. New verbs wired in BOTH dispatcher + listener.
- Toolbar Vue SFCs under `packages/frontend-assets/resources/assets/ui/components/Toolbar/`. Preserve back-compat hooks (`#toolbar-user-menu-button`, `#user-menu-wrapper`, `.mw-le-hamburger`, `#mw-live-edit-search-content`) — Dusk tests pin them; hide with `style="display:none"` rather than deleting.
- Toolbar height is the single `:root` token `--toolbar-height` in `packages/frontend-assets/resources/assets/ui/css/index.css`.

## Theming & CSS
- Colors come from `MicroweberPackages\Filament\Themes\MwColors`. Brand primary is Bootstrap blue `#0d6efd` across admin/checkout/public — use `MwColors::Blue`, never `Color::Blue`. Override at the CSS token, not per-template.
- Scope handles: `body.fi-panel-admin` / `body.fi-panel-checkout` / `body.fi-panel-profile`; RTL is `[dir="rtl"]` (emitted by `lang_attributes()`).
- **Touch-target CSS lives in two pipelines** by surface owner: public HTML → `Templates/Bootstrap/resources/assets/css/public-touch.css` (Vite); Filament HTML → `packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css` (Webpack). A mobile fix may need both. Interactive targets ≥ 44×44 px.
- `data-mw-*` interaction handlers (add-to-cart, gallery, product-image, pinmarklet) are delegated in `packages/frontend-assets/resources/assets/api-core/core/core/csp-skin-handlers.js`, imported by the `frontend.js` entry. Working shop add-to-cart = `class="mw-add-to-cart-btn"` + `data-content-id/price/title` (handled by `shop.js`).

## Build & run
- **Always rebuild after editing `packages/<pkg>/resources/**`** — the dev server serves the built bundle, not source.
  - Frontend (Vite): `cd packages/frontend-assets && npm run build`.
  - Filament theme (Webpack): `cd packages/microweber-filament-theme && npm run build`.
- Docs: `cd docs && npm run docs:build` must stay green (VitePress SSR over every page).
- After `cd packages/<x>`, prefix later git/npm calls with `cd /home/headless/Documents/GitHub/microweber && …` or use absolute paths.
- Clear caches via `php artisan optimize:clear` / `cache:clear` / `view:clear` — never `rm` cache dirs. New Filament pages may 404 until `php artisan filament:clear-cached-components`.

## Tests

### Environment & setup
- **Real MySQL DB `microweber_testing`** (not sqlite, not mocked). PHPUnit forces `APP_ENV=testing` (`phpunit.xml` `<php>`); the DB connection resolves to `microweber_testing` on `127.0.0.1:3306`, user `root` (see `.env.dusk` for the canonical values). Create the DB once: `mysql -uroot -e "CREATE DATABASE IF NOT EXISTS microweber_testing"`.
- **One-time dev provisioning:** `sudo scripts/setup_php.sh --dev` installs PHP+exts, Composer (with dev deps incl. `laravel/dusk`), the Node bundles, **and** a Chrome browser + matching Dusk ChromeDriver. Run this before Dusk tests.
- **Always kill stale runs before starting; never run suites in parallel** (shared MySQL DB + shared Chrome profile collide).

### PHPUnit (unit / feature / module / contract)
- **Run via `bash run-tests.sh`** — it runs each suite in its **own process** to dodge the ~6 MB/test PHP memory leak that OOMs the full suite (512 MB/suite is enough).
  - `bash run-tests.sh` — all suites · `bash run-tests.sh Unit Core` — named suites · `bash run-tests.sh --modules` — only module groups · `bash run-tests.sh --list` — list suites.
  - Exits non-zero if any suite failed; prints a PASS/FAIL summary per suite.
- **Suites** (`phpunit.xml`): `Unit`, `Feature`, `Core` (`src/MicroweberPackages/*`), the `Modules-*` groups (`Newsletter`, `Content`, `Billing`, `Group3`–`Group6B`, `WordPressMigration` — split to bound memory), and `Templates`.
- **Single test / filter** — `run-tests.sh` has no passthrough; call PHPUnit directly: `php vendor/bin/phpunit --filter 'MethodOrClassName'` or `php vendor/bin/phpunit path/to/Test.php`. `composer test` runs `artisan test` (whole suite in one process — fine for a small slice, OOMs at scale; prefer `run-tests.sh`).
- **Do NOT use** `RunInSeparateProcess`, `DatabaseTransactions`, or `RefreshDatabase` (the suite manages its own DB lifecycle; these break it). Filament/Livewire tests opt into `RunTestsInSeparateProcesses` at the class level instead.
- **Contract tests that read source files:** a `#[DataProvider]` runs **pre-boot**, so return relative paths and resolve `base_path()` per-test. When asserting the *absence* of a token, pre-strip comments (`/* */`, `//`, `{{-- --}}`, `<!-- -->`) so the assertion can't self-match its own prose.

### Dusk (browser / Live Edit / admin UI)
- **Prereqs:** Chrome + ChromeDriver (from `setup_php.sh --dev`, or `php artisan dusk:chrome-driver --detect`), and **a running server on `http://127.0.0.1:8000`** (`DuskTestCase::$siteUrl` / `.env.dusk` `APP_URL`). Start one with `php artisan serve --port=8000` (or the `serve-test` artisan command) in a separate process. `.env.dusk` points Dusk at the **same `microweber_testing`** DB.
- **Run:** `php artisan dusk` (all) · `php artisan dusk --filter 'TestName'` (one) · `composer test-dusk`.
- **Login:** `AdminLoginTrait::loginAsAdmin()` uses `admin@admin.com` / `admin`. One Chrome instance is shared across a run (per-PID profile under `storage/app/chrome-profiles/`) and **auto-recycles after ~40 tests** to dodge Chrome flakiness; window is `1280×1080`. Headless is **off by default** (the `--headless` arg is commented in `DuskTestCase::driver()`) — uncomment for CI / no-display.
- **Caveats:** don't log in via `/admin/login` when Apache-fronted (returns 404) — use `php artisan serve` on :8000 or a valid session cookie to `/admin/dashboard`. Live-Edit inserts/module-settings **auto-persist to the content DB** — drive pickers non-mutatingly and test inserts only on throwaway pages. Use the `claude_agent` account (id 2) for any login that mutates; never change user 1's password or add temp routes.

### Coverage, static analysis & security
- `composer test-coverage` → `clover.xml` (needs Xdebug/PCOV). `composer insights` / `insights-summary` → PHP Insights. `composer security:check` (composer audit + `npm audit`), `security:outdated`, `security:full-scan`.

## Code style & reusable patterns
- Inside Filament page actions use `$this->js(...)`, `$this->dispatch(...)`, `$this->mountAction(...)`; action callbacks `->action(function (array $data) { … })` with `$this` for page context.
- Save / Create / Save-&-Continue (unsubmitted) CTAs are `->color('primary')` (brand blue). Green (`->color('success')`) is only for post-submit success (saved/published/paid/installed).
- Currency: never hardcode `'$'`. Use `->prefix(fn () => currency_symbol() ?: '$')`, or a `resolveCurrencyPrefix($get('currency'))` helper for multi-currency wizards.
- Append to CSS files with the Edit tool (old tail → tail + new block), never a Bash heredoc (duplicates mid-stream).
- Touch targets: `min-height/min-width: 44px`; for Filament `size=lg`/checkbox use `:has(.fi-checkbox-input)` + `!important` where inline styles win.
- Empty-state list CTA class is `.mw-table-empty-cta` (not `.mw-empty-state-cta`, owned by the dashboard widget). Grep `packages/` before naming any new CSS class — a duplicate from another bundle silently wins the cascade.
- Use `app()->ui->brand_name()` / `admin_logo()` (no bare `brand_name()`). `get_option(key, group)` is canonical (not `option_get`).

## Escaping & parser footguns (the recurring bug families)
Match the escape to the **output context**:
- **HTML body** — `{{ }}` is fine.
- **HTML attribute (esp. Alpine `x-data`)** — an embedded `"` (including inside `//` or `/* */` comments) terminates the attribute. Extract any `x-data` that is long, has comments, or contains `"` to `Alpine.data('factory', () => ({…}))` and reference `x-data="factory"`.
- **JS string inside `<script>`** — `{{ }}` does not JS-escape. Use `{!! json_encode($v, JSON_HEX_QUOT|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS) !!}`.
- **CSS comment inside `<style>`** — Blade `@if/@else/@endif` tokens are parsed even in `/* */`; rephrase to prose.
- **PHP docblock** — a literal `*/` (even in prose) ends the block; rephrase.
- Translatable strings ending in `.` → use Microweber `_e('…', true)`, not Laravel `__()` (which returns empty on trailing-period keys).
- Frontend `tools/` JS is strict-mode: always declare with `var`/`let`/`const` (implicit globals throw at runtime).

## Other recurring failure shapes (name them in PRs)
- **Data shipped, consumer not wired** — source/test pass but the call site is commented or missing. Pin the call site is present.
- **Dark-mode cascade-loss** — a single rule passes in light mode but Filament's `body.fi-panel-admin.dark …` defaults outrank it. Split sizing (single class) from colour (compound selector + `!important`).
- **Viewport-scope leak** — a narrow-viewport rule declared outside its `@media` applies everywhere (e.g. `overflow:hidden` collapsing a flex parent to 0×0). Gate it inside the `@media`.
- **Fall-through paths need their own chrome** — every 404 / auth-expired / empty-cart path must extend `$extendsView ?? 'templates.bootstrap::layouts.master'`, wrap a semantic container, give a recovery CTA, and emit the right status + `X-Robots-Tag`/`X-Fallback-Message` headers. None is inherited.
- **Recon before a single-surface fix** — grep `extends <Class>` (subclasses inherit the fix) and the defect signature across sibling modules; a "one surface" dispatch is usually 2–5 sites.

## What NOT to do
- Don't skip the package build after editing `packages/**/resources`.
- Don't wrap/replace functional `<module type="…">` (btn, shop, posts, menu, background, spacer, pictures, testimonials) — emit them with params at most.
- Don't introduce unconditional `return true/false;` at the top of cache-staleness gates (e.g. the sitemap 3-hour mtime gate) — use explicit existence → readability → age guards.
- Don't sweep `text-align: left/right` → `start/end` or other large mechanical RTL refactors without RTL test content + an explicit ask.
- Don't speculatively refactor outside the task — a bounded slice + contract test is the winning pattern here.
- Don't log in to `/admin/login` via Playwright when Apache-fronted (returns 404); use `php artisan serve` on :8000 or a valid session cookie to `/admin/dashboard`.
- Don't use `display:none` to hide a form label (drops it from the a11y tree) — use sr-only (`position:absolute; width:1px; height:1px; clip:rect(0,0,0,0)`); Filament `->hiddenLabel()` has the same flaw.

## Key files
- `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php` · `.../resources/views/iframe-page.blade.php` — Live Edit frame + canvas.
- `src/MicroweberPackages/Livewire/resources/views/mw-modal.blade.php` — generic Livewire modal (focus-trap: Escape closes, focus returns to opener — never bypass).
- `src/MicroweberPackages/Filament/Themes/MwColors.php` — color authority. `…/Support/FilamentRegistry.php` — admin plugin/page/resource registration.
- `src/MicroweberPackages/Admin/Filament/Pages/AdminSettingsPage.php` — base for settings pages (Livewire-reactive save).
- `Modules/Components/Providers/ComponentsServiceProvider.php` — `<x-…>` registrations.
- `packages/frontend-assets/resources/assets/js/frontend.js` — public JS entry (imports `shop.js`, `csp-skin-handlers.js`, …).
- `Modules/Sitemap/Http/Controllers/SitemapHelpersTrait.php` — sitemap cache-TTL gate (3-hour mtime).
- `docs/.vitepress/config.js` — VitePress sidebar registry (append new module docs here).
- `run-tests.sh` — separate-process PHPUnit orchestrator.
- `.autodev/SOUL.md` (identity), `.autodev/JOURNAL.md` (research log), `.autodev/CONTRACTS.md` (the only valid contact directory), `TODO.md`/`DONE.md`.

## Project skills (`.claude/skills/`)
- **vue3-scoped-dark-mode** — `<style scoped>` adds `[data-v-x]` to ancestor selectors too, so `html.dark`/`html[dir=rtl]`/`body.fi-panel-admin` overrides never match. Wrap ancestor selectors in `:global(...)`.
- **filament-developer** — Filament v5 relocated several enums (e.g. `TextColumnSize` → `Filament\Support\Enums\TextSize`); old namespaces compile but throw "Class not found" at runtime.
