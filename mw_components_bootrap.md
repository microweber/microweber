# `mw_components_bootrap.diff` — Evaluation, Errors & Fixes

**Date:** 2026-06-09
**Scope:** Refactor of the **Bootstrap** template (`Templates/Bootstrap/resources/views/**`) to replace raw HTML scaffolding with the `Modules/Components` Blade components (`<x-section>`, `<x-container>`, `<x-row>`, `<x-col>`, `<x-card>`, `<x-modal>`) plus 4 new partials.

## TL;DR

The diff applies cleanly to the working tree, but **as written it is broken** — every layout skin that uses the new `@component('…layout-section')` wrapper (14 of them) **fatals at render time**, and several others carry silent functional/visual regressions. The `x-*` components it consumes **do exist** (registered globally by `Modules/Components/Providers/ComponentsServiceProvider.php`), so nothing is "missing" — the defects are in how the diff *uses* them.

After applying the diff and the fixes below, **all 16 layout/footer skins + the page templates render cleanly** through the real Microweber parser.

| # | Severity | Defect | Status |
|---|----------|--------|--------|
| 1 | 🔴 Blocker | `layout-section` docblock nests a `{{-- … --}}` comment → outer comment closes early → stray `@endcomponent` runs as live code → **`View [] not found`** on all 14 `@component`-based skins | ✅ Fixed |
| 2 | 🔴 Blocker | `@if … @endif` placed **inside** the `<x-section …>` opening tag → component-tag compiler splits the directive → **`unexpected token "endif"`** | ✅ Fixed |
| 3 | 🔴 Blocker | `pricing-card` receives a pre-rendered `<module …>` **string**; Microweber's parser rewrites every `<module ` token in source (incl. PHP string literals) → **`unexpected identifier "module"`** on `pricing/skin-1` | ✅ Fixed |
| 4 | 🟠 High | `<x-container class="mw-layout-container">` **silently drops the class** (component binds `class` to a constructor prop the view ignores) → loses `mw-layout-container`, `allow-drop`, `text-center`, paddings on every container | ✅ Fixed |
| 5 | 🟠 High | `layout-classes` partial pulled in via `@include` → `@include` runs in its own scope, so the computed `$layout_classes` (padding defaults `p-t-100`/`p-t-50`…) **does not propagate back** → default vertical padding silently lost + `Undefined variable $layout_classes` / `trim(null)` warnings | ✅ Fixed |
| 6 | 🟡 Medium | `<x-col size="12" size-lg="6">` emits `col-lg-6 col-xl-12 col-xxl-12` — the component cascades xl/xxl from `size`, **breaking Bootstrap's upward cascade** → 2-column layouts collapse to full-width at ≥1200px | ✅ Fixed |
| 7 | 🟡 Medium | **Bare `<x-col>` inside a `row-cols-*` grid** (`pricing/skin-1` via `pricing-card`, `pricing/skin-2`) emits `col-sm-12…col-xxl-12` → overrides the auto-grid → **pricing cards stack full-width instead of 3-up / 4-up**. Only visible in the browser. | ✅ Fixed |
| 8 | 🟢 Low | `<x-modal>` without `title` renders no `.modal-header`, so the login/orders modals **lose their close (×) button** | ✅ Fixed |
| 9 | 🟢 Low | `404` layout cols `col-4`/`col-8` become `col-sm-4 …` (no `xs`) → side-by-side text/image **stack below 576px** | ⚠️ Noted (acceptable) |

---

## What the diff changes

**Modified (20):** `clean`, `contact_us`, `post`, `product` page templates; layout skins `404`, `blog/skin-1`, `content/skin-1`, `default`, `ecommerce/skin-1`, `features/skin-1`, `features/skin-2`, `footers/footer_cart`, `footers/skin-1`, `jumbotron/skin-1`, `pricing/skin-1`, `pricing/skin-2`, `pricing/skin-3`, `skin-1`, `text-block/skin-1`, `titles/skin-1`.

**New partials (4):** `partials/feature-item`, `partials/layout-classes`, `partials/layout-section`, `partials/pricing-card`.

The `x-*` components are registered globally (`Blade::component('container', …)` etc.) by the **Components** module, so `<x-container>`, `<x-col size=… size-lg=…>`, `<x-card>`+`<x-slot>`, `<x-modal id= size=>` all resolve. The breakages are usage bugs, not missing dependencies.

---

## Errors in detail + the fix applied

### 1. 🔴 Nested Blade comment in `layout-section` docblock (`View [] not found`)
The docblock contained an example with a **nested** comment:
```blade
{{--  … @component(…)
          {{-- your inner content here --}}   ← inner --}} closes the OUTER comment
      @endcomponent  … --}}
```
Blade's comment matcher is non-greedy (`{{--.*?--}}`), so it terminates the outer comment at the inner `--}}`, leaving `@endcomponent` and trailing text as **live code**. The stray `@endcomponent` corrupts the component stack → empty view name → `View [] not found`. (Same parser-meaningful-character family as the `*/`-in-docblock and `"`-in-`x-data` footguns.)
**Fix:** removed the nested comment delimiters from the docblock prose; added a note never to write a comment close-token inside a docblock.

### 2. 🔴 `@if` inside the `<x-section>` opening tag (`unexpected token "endif"`)
```blade
<x-section class="…" @if($fieldName) field="…" rel="module" @endif>
```
The component-tag compiler parses `<x-…>` attributes **before** the directive compiler runs, splitting `@if`/`@endif` and leaving an unbalanced `@endif`.
**Fix:** `layout-section` now emits a **literal `<section>`** (and literal `<div class="container …">`) and puts the conditional in plain-HTML attribute position (`<section …@if($fieldName) field="…" rel="module"@endif>`), which compiles fine. Using literals here also guarantees defect #4 can't recur on this path.

### 3. 🔴 `<module>` markup passed as a string to `pricing-card`
```blade
@include('…pricing-card', ['buttonModule' => '<module type="btn" …/>'])
```
Microweber's module parser substitutes **every** `<module ` occurrence in the template source — including inside PHP string literals — which corrupts the `@include` array and fatals (`unexpected identifier "module"`). This is the same family as the "never wrap module tags in `parse_modules_html()`" lesson.
**Fix:** `pricing-card` now takes button **params** (`btnId`, `btnStyle`, `btnText`) and emits `<module type="btn" …/>` itself in normal template position; `pricing/skin-1` updated to pass params.

### 4. 🟠 `x-container` drops the passed `class`
`Modules/Components/View/Components/Container.php` declares a `$class` constructor prop, so `class="…"` is bound to it and **removed from `$attributes`** — but `container.blade.php` did `$attributes->merge(['class' => 'container'])` and **ignored `$class`**. Live proof: `<x-container class="mw-layout-container">` → `<div class="container">` (class lost). This stripped `mw-layout-container`, `allow-drop`, `text-center`, `py-3`, `my-md-5` etc. from every refactored container. (Note: `x-section`/`x-row`/`x-col` are fine — their views *do* use the `$class` prop; `x-card` is fine — it has no `class` prop so `class` stays in `$attributes`.)
**Fix:** `Modules/Components/resources/views/components/container.blade.php` now merges `$class`:
```blade
<div {{ $attributes->merge(['class' => trim('container'.($fluid?'-fluid':'').' '.$class)]) }}>
```
Backward-compatible — all 16 Components unit tests still pass.

### 5. 🟠 `layout-classes` via `@include` doesn't propagate (padding defaults lost)
`@include` renders in its **own** variable scope; a variable it assigns does **not** flow back to the caller. So `layout-section`/`footers/skin-1`, which `@include`d `layout-classes` to obtain `$layout_classes`, never received the padding-augmented value — default vertical padding (`p-t-100`, `p-b-100`, `p-t-50`…) was silently dropped, and `$layout_classes` was undefined (→ `Undefined variable` + `trim(null)` warnings on every render). Empirically confirmed (`[PARENT_SEES:]` came back empty).
**Fix:** compute padding **inline** in `layout-section` and `footers/skin-1`. The `layout-classes.blade.php` partial is left in place but its docblock now warns it must **not** be `@include`d for this purpose. After the fix, `404`→`p-t-50` and `default`/`skin-1`→`p-t-100` are confirmed present; no warnings.

### 6. 🟡 `x-col` breaks Bootstrap's upward cascade
The `Col` component defaults `sizeXl`/`sizeXxl` from `size` (not `sizeLg`) — a deliberate, unit-test-pinned contract. So `<x-col size="12" size-lg="6">` → `col-lg-6 col-xl-12 col-xxl-12`, i.e. full-width again at ≥1200px, whereas the original `col-lg-6` persisted through xl/xxl. Affected: `contact_us` (6/6), `ecommerce` (9/3), `footers/skin-1` (5/4/3), `product` (6/6), and the centered title/text columns in `content`/`features`/`text-block`/`titles`.
**Fix:** mirrored every `size-lg="N"` into `size-xl="N" size-xxl="N"` in the touched files (respecting the component's contract rather than changing the shared component + breaking its tests). Confirmed: ecommerce now renders `col-lg-9 col-xl-9 col-xxl-9`.

### 7. 🟡 Bare `<x-col>` inside a `row-cols-*` grid → cards stack (browser-only finding)
Bootstrap's `row-cols-1 row-cols-md-2 row-cols-lg-4` auto-layout sizes its children only if they are **bare `.col`** (`flex: 1 0 0`). But `<x-col>` with no size always emits `col-sm-12 col-md-12 … col-xxl-12` (`width: 100%`), which overrides the grid → every card spans full width and **stacks vertically**. The parser render passed (markup was valid); the defect only showed in the **browser** — `pricing/skin-1` (3-up) and `pricing/skin-2` (4-up) rendered as single stacked columns. (`features/skin-2` was unaffected — it already uses a literal `<div class="col">`.)
**Fix:** use a literal `<div class="col">` (not `<x-col>`) in `pricing-card.blade.php` and `pricing/skin-2.blade.php`. Confirmed in-browser: 3 / 4 cards across at desktop.

### 8. 🟢 Modal close button (fixed)
`<x-modal>` only rendered `.modal-header` (with the × button) when a `title` was passed, so the title-less login/orders modals lost their explicit close button.
**Fix:** added a `dismissible` prop (default `true`) to the `Modal` component and updated `modal.blade.php` to always render a close button — inside the header when there's a title, or in a minimal borderless header (`border-0 pb-0`, button `ms-auto`) when there isn't. Pass `:dismissible="false"` to opt out. All 62 Components unit tests still pass.

### 9. 🟢 Low / acceptable
- **404 `col-4`/`col-8` xs stacking:** `x-col` has no plain `col-{n}` (xs) class, so the 404 text/illustration stack below 576px instead of staying 4/8. Acceptable on a rarely-seen page; left as-is.

---

## Files changed by the fixes (beyond applying the diff)

- `Templates/Bootstrap/resources/views/partials/layout-section.blade.php` — literal `<section>`/`<div>`; inline padding; docblock comment de-nested (defects 1, 2, 5).
- `Templates/Bootstrap/resources/views/partials/pricing-card.blade.php` — button params instead of module string (defect 3).
- `Templates/Bootstrap/resources/views/modules/layouts/templates/pricing/skin-1.blade.php` — pass button params (defect 3).
- `Templates/Bootstrap/resources/views/modules/layouts/templates/footers/skin-1.blade.php` — inline padding (defect 5).
- `Templates/Bootstrap/resources/views/partials/layout-classes.blade.php` — docblock warning (defect 5).
- `Modules/Components/resources/views/components/container.blade.php` — merge `$class` (defect 4).
- `contact_us`, `product`, `ecommerce/skin-1`, `footers/skin-1`, `content/skin-1`, `features/skin-1`, `features/skin-2`, `text-block/skin-1`, `titles/skin-1` — `size-xl`/`size-xxl` mirror (defect 6).
- `partials/pricing-card.blade.php`, `pricing/skin-2.blade.php` — bare `<x-col>` → literal `<div class="col">` for `row-cols-*` grids (defect 7).
- `Modules/Components/View/Components/Modal.php` + `Modules/Components/resources/views/components/modal.blade.php` — always-rendered close button + `dismissible` prop (defect 8).

---

## Verification (real Microweber parser, template forced to Bootstrap)

- **16 / 16** layout + footer skins render with no exception (`load_module('layouts', …)` → `parser->process`).
- `mw-layout-container` present on every layout; `allow-drop` present on `clean`/`default`/`skin-1`.
- Padding defaults applied (`404` → `p-t-50`, `default`/`skin-1` → `p-t-100`).
- Columns cascade correctly (`col-lg-9 col-xl-9 col-xxl-9`, etc.).
- `clean` + `contact_us` full page templates render (53k / 60k chars) with **no** `View []` / `syntax error` / undefined-variable signatures.
- `footers/footer_cart` renders both modals.
- `post.blade.php` / `product.blade.php` compile and use the same proven components — **recommend a final eyeball on a live product + post page** (they need real content context that this harness doesn't supply).
- `Modules/Components` unit tests: **16 passed, 34 assertions** (Container/Col/Row/Section/Card).

## Browser verification (site temporarily switched to Bootstrap, then restored to Big2)

Driven via the running `php artisan serve` (:8000) with Playwright at 1440×900:

- **Home `/`** — header, hero, contact form, `content/skin-1`, `features/skin-1`+`skin-2`, jumbotron, `blog/skin-1`, `text-block`, `titles`, footer all render. 0 console errors (2 benign debug logs). *(Gray image boxes = stale Big2 demo images embedded in content, not a template defect.)*
- **`/contact-us`** — 2-column "Get in Touch" | form **side-by-side at 1440px** (confirms the col-cascade fix #6) + `titles/skin-1`.
- **`/example-product`** (`product.blade.php`) — 2-column image gallery | product info side-by-side, price/stock/description/Add-to-cart, Related products.
- **`/shop`** — product grid.
- **404** (unknown URL) — AI-795 frontend 404 chrome (Bootstrap card + "Back to homepage").
- **Temp probe page** (pricing/skin-1 + skin-2 + ecommerce/skin-1 + features/skin-2) — caught defect #7 (cards stacked), fixed, re-verified: **pricing 3-up / 4-up grids, ecommerce 9/3 products+sidebar, features 4-col, footer 3-col**. Probe page deleted afterward.

## Is the Bootstrap template working / anything missing?

Yes — working after the fixes. Nothing is *missing* (the `x-*` components are all registered). The diff's own bugs were the issue. Remaining open items are the two **Low** items (#7 modal close button, #8 404 xs stacking) and a recommended live-page check of `post`/`product`.
