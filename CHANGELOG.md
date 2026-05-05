# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).


## [Unreleased]

### Reviewed (no code changes)
- **Link Picker modal audit — review only, no changes** (task-2026-05-05-606151) — read 199-line Drunk-Designer report on the Filament rich-text Link tool. Confirmed via DOM evidence the audited surface is Filament's vendor-built Link tool (`button.fi-fo-rich-editor-tool[aria-label="Link"]` opens `.fi-modal-window`), distinct from Microweber's own `mw-dialog`-based Link Settings (which received the dialog ARIA fix in task-54cda2). All 15 audit items target Filament internals — patching requires upstream Filament PRs or fragile vendor overrides, neither suitable for an autonomous Quick-Win batch. All 15 items captured in the deferred list under task-606151 in TODO.md for prioritisation as separate tasks.

### Fixed
- **Image Upload modal — typo + dialog ARIA Quick Wins from external audit** (task-2026-05-05-54cda2) — implemented the 2 HIGH-severity Quick Wins from `tester-agent-1/DESIGN_REPORT_IMAGE_UPLOAD_MODAL.md` (251 lines, Drunk-Designer framework). (Audit-#1) Fixed visible typo "Refference image" → "Reference image" in `packages/frontend-assets/resources/assets/components/filepicker.js` (AI tab reference image picker). Legacy CSS class names (`refference-image-pick-*`) kept unchanged to avoid breaking theme overrides. (Audit-#2) `.mw-dialog` containers had no `role="dialog"`, no `aria-modal`, no a11y label so screen readers didn't announce the modal as a dialog. Fixed at the source — `packages/frontend-assets/resources/assets/components/dialog.js` `build()` now sets `role="dialog"` and `aria-modal="true"` on every `.mw-dialog` wrapper; `header()` assigns the title element a stable id (`<dialog-id>-title`) and wires it as `aria-labelledby` on the dialog. Headless dialogs (no title) fall back to `aria-label="Dialog"`. Single-source fix benefits every Microweber `mw-dialog`-based modal across the codebase (image upload, link editor, modules dialogs, system dialogs). Verified PHP `-l` clean, frontend-assets bundle rebuilt, `AdminLiveEditElementStyleEditorTest` 10 assertions green. Larger bets (tab reorder by use frequency, modal width lock, lazy thumbnails, AI power controls, persist last-used tab, fixture gating) intentionally deferred.

### Fixed
- **Products Module + Create Product modal — Quick Wins from external audit** (task-2026-05-05-1db9bd) — implemented 3 highest-leverage fixes from `tester-agent-1/DESIGN_REPORT_PRODUCT_LIST_MODULE.md` (342 lines, Drunk-Designer framework) covering the full Add-Product journey. (Audit-#1 HIGH) Body editor label `Write your post here` was bleeding into the Create Product modal — the user was creating a product, not a post. Fixed in `Modules/Content/Filament/Admin/ContentResource.php` at both call sites (compact + long-form) by branching the `->label()` on `content_type`: `'Product description'` for products, `'Write your post here'` for posts. (Audit-#9 MEDIUM) Renamed `Special price` → `Sale price` at both pricing surfaces — industry-standard term across Shopify/WooCommerce/Magento. Database column unchanged. (Audit-#4 HIGH) Right-rail `+` quick-action on inserted modules had no reliable label (Vuetify `<v-tooltip>` doesn't render — Vuetify CSS isn't loaded in admin). Added explicit `aria-label`, `role="button"`, `tabindex`, Enter/Space keyboard handlers in `packages/frontend-assets/resources/assets/ui/components/ContextMenu/CurrentLayoutSettingsButtons.vue`. Renamed visible title from "Insert Module" to "Insert content" (the button opens a content-element picker, not a module picker). Same a11y wiring added to per-module `.module-settings-button` icons. Verified at /admin/live-edit → +Add → New Product: body editor label "Product description", pricing shows "Sale price". `AdminLiveEditElementStyleEditorTest` 10 assertions green. Larger bets (kill iframe-in-iframe architecture, paginate module render, items-list columns, bulk select, SKU/Stock/Variants up-front, autosave) intentionally deferred — each warrants its own task.

### Fixed
- **Admin Add Post modal + Create Post form — a11y Quick Wins from external audit** (task-2026-05-05-5e9ffc) — implemented Audit-#8 and Audit-#1 from `tester-agent-1/DESIGN_REPORT_ADD_POST_MODAL.md` (267 lines, Drunk-Designer framework). (Audit-#8) Added `aria-required="true"` to the admin full-form Title input via `extraInputAttributes` in `Modules/Content/Filament/Admin/ContentResource.php` (separate from the live-edit modal Title fixed in task-66e507 — same resource, different schema variant). Server-side `->required()` was already present; this announces the requirement to screen readers. (Audit-#1) Enriched each `<a>` chooser card in the `+Add` toolbar modal (`src/MicroweberPackages/Admin/resources/views/livewire/filament/top-navigation-actions.blade.php`) with a descriptive `aria-label="<title>: <description>"` and Tailwind `focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 rounded-md` so keyboard users land visibly on each card. The modal-window's `role="dialog"` is the Filament `<x-filament::modal>` Blade component's responsibility (Filament vendor concern, deliberately out of scope). Verified at `/admin/posts` → +Add: 4 cards have aria-label + focus ring; `/admin/posts/create` Title has `aria-required="true"`. `AdminLiveEditElementStyleEditorTest` 10 assertions green. Larger bets (split-button, autosave, collapse-by-default sections, live-preview pane) intentionally deferred.

### Fixed
- **Live-edit Create Post modal — a11y Quick Wins from external audit** (task-2026-05-05-66e507) — implemented the 2 highest-leverage a11y fixes from `tester-agent-1/DESIGN_REPORT_LIVE_EDIT_ADD_POST_AND_POSTS_MODULE.md` (Drunk-Designer framework lens, 289 lines). (QW1) Replaced `<div role="button">` chooser cards with real `<button type="button">` elements in `src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php` — keyboard activation, focus rings, and form-control semantics now work natively without manual wiring. Removed the `onkeydown` Enter/Space shim (no longer needed — `<button>`'s default behaviour fires click on Enter and Space-keyup per ARIA APG). (QW2) Added `aria-required="true"` to the Title input via `extraInputAttributes` in `Modules/Content/Filament/Admin/ContentResource.php` — server-side `->required()` was already present; this announces the requirement to screen readers. Verified at `/admin/live-edit` → +ADD: 4 chooser cards render as `<button type="button">` with intact aria-labels; opening Create Post → Title input has `aria-required="true"`. `AdminLiveEditElementStyleEditorTest` 10 assertions green. Posts list polish (filter tabs, breadcrumb rename, status pill case, master checkbox aria-label, filter funnel badge "0" hide, per-row Actions aria-label, post tree virtualization, autosave) intentionally deferred — those touch shared admin Filament Resource surface and warrant their own task.

### Fixed
- **Element Style Editor — 5 Drunk-Designer Quick Wins from external audit report** (task-2026-05-05-854d66) — implemented 5 of the 9 Quick Wins from `tester-agent-1/DESIGN_REPORT_ELEMENT_STYLE_EDITOR.md`: (QW1) Slider numeric inputs no longer render `NaN` for unset/non-finite values — `SliderSmall.vue` coerces non-finite incoming/typed values to `null` in initial data, `modelValue` watcher, and `validateValue()`. (QW3) Italic field replaced from a 2-option Normal/Italic dropdown to a single `<button class="mw-italic-toggle">` (italic "I" glyph that turns blue on active) bound to the same `fontStyle` model. (QW5) Box Shadow presets renamed from numeric indices (Shadow 2/6/10/18/21/25/26-30) to qualitative names (None/Subtle/Soft/Dramatic/Medium/Strong/Floating); dropped the 26-30 duplicates the audit flagged. (QW8) Added native `title` tooltips to all 5 right-rail icons in `SettingsCustomize.vue` (Insert layout / Template settings / Design / Quick AI edit / Advanced) so hover labels appear — the existing `<v-tooltip>` Vuetify components weren't rendering because Vuetify CSS isn't loaded in the parent admin window (per the `vuetify-slider-in-mw-admin` skill). (QW9) Removed duplicate AI input in `ElementStyleEditorAiChat.vue` — duplicate "AI Style Editor" label inside the panel + dead `.d-none` `<input type="text">` + Send button block both deleted; the AIChatForm-mounted textarea is now the only input. Verified end-to-end via Playwright at 1280×800 dark mode: zero `NaN`-valued inputs, italic toggle present, shadow dropdown shows 7 qualitative names, 5 right-rail icons all carry both `aria-label` AND `title`, AI panel has one input + one label. `AdminLiveEditElementStyleEditorTest` 10 assertions green. Larger bets from the audit (3-tab restructure, Border+Radius merge, animation hover preview + grouping, breadcrumb, accordion-state persistence, palette + WCAG badge) intentionally deferred — each warrants its own task with proper architectural review.

### Added
- **Sixth Microweber skill: `live-edit-css-must-be-scoped`** (task-2026-05-05-ead758) — review pass on the five skills created in task-f6c077 confirmed all five remain accurate against the current code (event-bus listener pattern, Vuetify slider positioning, modal drag class match, bundle rebuild, child panel state isolation). The git log review surfaced one additional recurring pattern not yet captured: commit `51279bb239` "fix(theme): scope live edit css" added a wrapper-class scoping convention so that `.fi-*` rules in `live-edit-*.css` files don't leak to non-live-edit Filament admin pages. The `LiveEditCssScopeTest` regression guard already exists, but the underlying convention belonged in skills too. Added `.claude/skills/live-edit-css-must-be-scoped/SKILL.md` capturing the pattern: every `.fi-*` rule in any `live-edit-*.css` must be prefixed with `.mw-admin-live-edit-page` (or a narrower modal class like `.mw-content-form-modal`). All six skills now visible in the Claude Code runtime's available-skills list.

### Added
- **Five Microweber-specific skills under `.claude/skills/`** (task-2026-05-05-f6c077) — captured the genuinely hard problems solved across the 2026-05-05 batch as reusable agent skills following the official Anthropic skill-creator format. Each skill in its own folder under `.claude/skills/<slug>/SKILL.md` with required `name` + `description` frontmatter, plus `## Problem`, `## Root Cause`, `## Solution Pattern`, `## Code Example`, `## Do NOT`, `## Applies To`, `## History` sections. The five skills: (1) `mw-app-event-bus-no-replay` — `mw.app.on(...)` does NOT replay missed events, register listeners eagerly + seed initial state; (2) `vuetify-slider-in-mw-admin` — Vuetify's CSS isn't loaded in the parent admin window, manual positioning rules required for `<v-slider>` and friends; (3) `live-edit-modal-draggable` — extend the existing drag IIFE in `iframe-page.blade.php` by adding a class to the match list rather than reimplementing; (4) `frontend-assets-bundle-rebuild` — after editing any source file under `packages/`, the right `npm run build` must run in the right package or the served JS/CSS stays stale; (5) `ese-child-panel-state` — each ESE child panel owns a local `showXxx` boolean separate from the parent's `isXxxActive` state, requires explicit `$refs`-based reset on every wrapper click. All five SKILL.md files are picked up by the Claude Code runtime and visible in the skill list.

### Fixed
- **Live-edit Element Style Editor — Animations panel now uses a dropdown** (task-2026-05-05-4b1414) — user reported that the disabled-when-no-selection state worked but the Animations panel was unusable: the existing `<div class="animations-selector">` rendered all 48+ animation names as a flat scrolling block of `.animation-item-wrapper` divs that relied on background-image preview thumbnails which weren't loading, so the user saw a wall of text (None / Bounce / Flash / Pulse / …). Replaced the entire `.animations-selector` block in `packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorAnimations.vue` with a single `<DropdownSmall v-model="selectedAnimation" :options="animations" :label="'Animation'"/>` — the component's existing `animations` data is already in the `[{key, value}]` shape DropdownSmall expects, no transform required. The When + Speed controls below the dropdown stay unchanged. Verified via Playwright: selected `.element.main-content` → opened Animations → `<select>` with 48 options (None → Slide In Up), zero legacy `.animation-item-wrapper` divs rendered. The "grey out when no selection" behavior the user also asked about is already wired (`style-editor-disabled` class sets `pointer-events: none; opacity: 0.5`); no additional change needed. `AdminLiveEditElementStyleEditorTest` 10 assertions green.

### Fixed
- **Live-edit Element Style Editor — clicking one panel now closes the others** (task-2026-05-05-96635c) — user reported "you broke the element style editor I can't open all items some are not opening". Reproduced: clicking Rounded Corners opened its content; then clicking Border highlighted the Border row but left Rounded Corners content visible AND didn't show Border's content. Root cause: each child component (Typography / Background / Border / Rounded Corners / etc.) owned a SEPARATE local `showXxx` boolean controlling whether its expanded panel rendered. The wrapper-level `@click="toggleXxx"` in `ElementStyleEditorApp.vue` only set the parent's `isXxxActive` (active-row styling) and didn't touch the child's local `showXxx`. Fix in `packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorApp.vue`: added `ref="<comp>Comp"` to each of the 10 panel components; added a `closeAllChildPanels(except)` helper that walks every child ref and sets their `showXxx` field to `false`; updated each `toggle<Panel>` method to call the helper and then explicitly set the active child's `showXxx = true` via `$refs`. The field probe is name-based (`'showXxx' in c`) so we don't maintain a per-component map. Verified via Playwright: open Rounded Corners → open Border → rcHeight=32 (header only), borderHeight=284 (full panel visible). `AdminLiveEditElementStyleEditorTest` 10 assertions green.

### Fixed
- **Live-edit Module Settings modal is now draggable** (task-2026-05-05-978bdf) — user reported the centered Module Settings modal in live-edit (Menu Module Settings, post module, etc.) wasn't draggable, unlike the Add Page/Post/Product/Category modal which got drag support in task-2026-05-04-c124bc. Both modals are the same Filament `.fi-modal-window` node — they only differ in the class set via `extraModalWindowAttributes`. Extended the existing drag IIFE in `src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php` to also recognise `mw-module-settings-live-edit-modal`: `isContentFormModal()` now matches either `mw-content-form-modal` or `mw-module-settings-live-edit-modal`; `scan()` and the MutationObserver fallback both pick up the new class; the content-modal-specific `attachOpenInAdminTitleSync` + `attachCancelGuard` helpers are gated behind a class check so they only run on the Add Content modal (they rely on the title input + unsaved-work probes that don't exist in Module Settings). Cursor + dragging-shadow CSS rules extended to the new class. Verified via Playwright: opened menu module settings, simulated mousedown + mousemove 100,50 + mouseup → modal moved exactly 100px right + 50px down (`before {left:443, top:226}` → `after {left:543, top:276}`), `position: fixed`, viewport-pinned. `AdminLiveEditElementStyleEditorTest` 10 assertions green.

### Fixed
- **Live-edit Element Style Editor — slider thumbs now sit on the track** (task-2026-05-05-4bd251) — user attached a Typography panel screenshot showing the slider thumb (white circle) rendering visibly ABOVE the track line on every slider — disconnected from the track it's supposed to control. Reproduced in Playwright: thumbCenter=430, trackCenter=444 (14px gap). Root cause: my earlier task-e62d5b fix made `.v-slider-thumb` `position: absolute; inset-block-start: 50%` to ride along the track, but the nearest positioned ancestor was `.mw-live-edit-slider-small` — the FULL slider component including the label + number-input row above the track. 50% of that container placed the thumb in the middle of the combined block, not on the track. Three-step fix in `packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css`: (1) made `.v-slider__container` (the row wrapping just the track + thumb) the positioning context with `position: relative; min-height: 14px; display: flex; align-items: center` — the thumb's `inset-block-start: 50%` now references the slider row only; (2) stripped Vuetify's inert accessibility `<input>` from layout entirely (`position: absolute; width: 0; height: 0; margin/padding: 0`) — previously just `opacity: 0` which still occupied 25px of layout space and pushed the track down; (3) added `flex: 1` to `.v-slider-track` so it stays centered horizontally in the now-flex container. Verified at 1280×800 dark mode: trackCenter=423, thumbCenter=423, delta=0. All four typography sliders show thumbs perfectly on their tracks. Same treatment cascades to every other panel using SliderSmall. `AdminLiveEditElementStyleEditorTest` 10 assertions green.

### Changed
- **Live-edit Add Product modal — Price + Special price now side-by-side** (task-2026-05-05-e75581) — user reported the price fields in the Add Product modal were laid out poorly. They were stacked full-width vertically, wasting horizontal space and making the Pricing section taller than necessary on a 768px-wide modal. Fix in `Modules/Content/Filament/Admin/ContentResource.php` `pricingSection()`: added `->columns(['default' => 1, 'sm' => 2])` so the section renders as a 2-column grid on sm+ (modal is always wider than sm) and stacks only on narrow mobile. Removed the per-field `columnSpan(['lg' => 2, 'sm' => 2])` that was a workaround. Verified in Playwright: Pricing now shows "Price [$ 19.99] | Special price [$ 14.99]" side-by-side with helper text below each, section is shorter, modal scrolls less. `AdminLiveEditElementStyleEditorTest` 10 assertions green.

### Changed
- **Live-edit Add Post modal — Short summary moved into More options** (task-2026-05-05-dac8a6) — user reported the upfront Body + Short summary stack made the Create post modal scroll. Short summary is optional (auto-derives from the first lines of the body when blank), so leaving it upfront forced everyone to scroll past a 100-line textarea every time. Split `compactBodyAndExcerptGroup()` in `Modules/Content/Filament/Admin/ContentResource.php` into two helpers: `compactBodyAndExcerptGroup()` keeps the upfront RichEditor body, and a new `compactShortSummaryGroup()` returns the post-only Short summary textarea. Wired the new group into the `Section::make('More options')` accordion above `publishedSection()` and the parent picker. Verified via Playwright at /admin/live-edit → +ADD → New Post: upfront labels are now Title / Add images / Write your post here only — Short summary now lives inside the collapsed More options accordion. `AdminLiveEditElementStyleEditorTest` 10 assertions green.

### Fixed
- **Live-edit Add Category — parent dropdown auto-selects the current canvas page** (task-2026-05-05-e78299) — user reported that when clicking +ADD → New Category from the live-edit toolbar, the "Parent page or category" dropdown was empty ("Select an option") and forced the user to manually scroll a 200+ item hierarchy to find the page they were already editing. Root cause: `CategoryResource::formArrayCompact()` tried two fallbacks for inferring the current page — `request()->query('parent_page_id')` and the `Referer` header — both empty during the Livewire `/livewire/update` POST that mounts the action. Fix in `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php` + `Modules/Category/Filament/Admin/Resources/CategoryResource.php`: extracted the live-edit canvas page resolution into a public `resolveCurrentLiveEditPageId(): ?int` helper on AdminLiveEditPage (parses `$this->liveEditUrl` via `content_manager->getContentIdFromUrl()`, falls back to homepage when no `?url=`); pass that id through `formArrayCompact(['currentPageId' => ...])`; CategoryResource reads `$params['currentPageId']` BEFORE the request/Referer fallbacks so the explicit pass-through always wins. Verified via Playwright at /admin/live-edit (no `?url=` so canvas defaults to homepage): +ADD → New Category → Parent select rendered with `value="page:1"`, selected option text "Page: Home". `AdminLiveEditElementStyleEditorTest` 10 assertions green.

### Fixed
- **Live-edit Element Style Editor — plain-English pass on Background, Spacing, Border** (task-2026-05-05-fb6315) — user asked to examine and fix all sections of the ESE. In-session walk through the panels captured: (Background) "BACKGROUND COLOR" / "BACKGROUND IMAGE" labels redundantly re-named the panel; CLIP and BLEND MODE are advanced CSS jargon; the ImagePicker component had a hardcoded `<label>Background Image</label>` masking its `label` prop. (Border) "POSITION" label was misleading — actually means "which sides"; "STYLE" was ambiguous — actually CSS line-style. (Spacing) S/M/L/XL/trash/gear buttons had no tooltips so a novice had to guess; "Inner Spacing (Padding)" leaked the CSS term. Fixes in `packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/`: Background.vue ColorPicker label `Background Color` → `Color`, wrapped Clip (renamed to "Crop area") + Blend Mode in a `<details><summary>More options</summary>` disclosure; Border.vue `Position` → `Sides`, `Style` → `Line style`; Spacing.vue added `title="Small inner space"`/etc. tooltips on all S/M/L/XL buttons, "Remove inner space" on trash, "Fine-tune each side" on gear, and renamed headings to plain "Inner space" / "Outer space" without the parenthetical CSS term; components/ImagePicker.vue replaced hardcoded `<label>Background Image</label>` with `<label>{{ label }}</label>` so the prop actually flows through (Background.vue passes `label="Image"`). Verified at 1280×800 dark mode: Background reads COLOR / Image / SIZE / REPEAT / POSITION with "v More options" hiding Crop area + Blend mode. `AdminLiveEditElementStyleEditorTest` 10 assertions green.

### Fixed
- **Live-edit Element Style Editor — novice-customer plain-English pass** (task-2026-05-05-098f1e) — user asked to run the novice-customer agent and fix what they'd flag. In-session audit captured: (F1) "Weight 700", "Transform None", "Style Normal" — typography jargon a regular site owner doesn't understand; (F2) "Letter Spacing" / "Word Spacing" — passable but stiff phrasing; (F3) "Writing Mode" / "Orientation" — completely opaque vertical-text controls used by virtually nobody; (F4) reset (↻) buttons had `data-tip` but no native `title` so hovering didn't show a browser tooltip. Fixes in `packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorTypography.vue` + `components/SliderSmall.vue` + `packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css`: renamed labels to plain English (Weight → Boldness, Transform → Letter case, Style → Italic, Letter Spacing → Space between letters, Word Spacing → Space between words, Writing Mode → Writing direction, Orientation → Text orientation); wrapped Writing direction + Text orientation in a `<details><summary>More options</summary>` disclosure (collapsed by default) so the primary surface only shows controls a typical site owner uses; added `title`/`role="button"`/`aria-label="Restore default value"` to the slider reset span. Theme CSS adds a chevron pseudo-element that rotates 180° on `[open]` for the disclosure summary, plus a hairline divider above it. Verified at 1280×800 dark mode: Typography labels now read BOLDNESS / LETTER CASE / ITALIC / LINE HEIGHT / SPACE BETWEEN LETTERS / SPACE BETWEEN WORDS instead of jargon; "More options" disclosure visible at the bottom with a chevron, collapsed by default. `AdminLiveEditElementStyleEditorTest` 10 assertions green.

### Fixed
- **Live-edit Element Style Editor — drunk-designer pass on dark mode** (task-2026-05-05-e62d5b) — user asked for a drunk-designer audit "also on dark mode". In-session walkthrough at /admin/live-edit (already in dark mode by default) captured five findings: F1 — slider thumb invisible (glued to track left edge despite the `--v-slider-thumb-position` CSS variable being set correctly); F2 — "Add more fonts" link tiny and easy to miss; F3 — ALIGN icons clumped at the start of the row with no separators; F4 — COLOR row had wasted space between label and swatch; F5 — active row's bg tint blended into expanded panel content with no clear divider. F1 root cause: Vuetify's own CSS isn't loaded in the parent admin window — only the `.v-slider-track` / `.v-slider-thumb__surface` selectors were styled, so the `.v-slider-thumb` wrapper element was static-positioned at left=0% regardless of the model value. Fix in `packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css`: added the missing Vuetify positioning manually (`.v-slider-track { position: relative }` + `.v-slider-thumb { position: absolute; inset-block-start: 50%; transform: translateY(-50%); inset-inline-start: var(--v-slider-thumb-position, 0%); margin-inline-start: -7px; width/height: 14px }`); F2 → globally re-styled "Add more fonts" via `#mw-element-style-editor-app .font-picker-wrapper small` to 11px blue text with a `+ ` pseudo-prefix; F3 → `.text-align > * + *` gets a 1px inline-start border so the 4 align icons read as a segmented control; F4 → `.form-control-live-edit-label-wrapper:has(.picker-button) > .d-flex { align-items: center; gap: 12px }` pulls COLOR's label + swatch closer; F5 → expanded panel content gets a hairline `border-top` divider so the boundary between the active heading and its content is clear (with non-`.dark` fallback). Verified in Playwright at 1280×800 dark mode: all 4 sliders show their thumbs at the correct % position; ALIGN row has separator hairlines; COLOR row is tight; "Add more fonts" reads as `+ Add more fonts`; expanded Typography content starts below a thin divider. `AdminLiveEditElementStyleEditorTest` 10 assertions green.

### Fixed
- **Live-edit Element Style Editor — accordion items now read as accordion items, active row is visibly bold** (task-2026-05-05-86825a) — user attached a screenshot of the right-rail with Container expanded and reported "make them look like accordion items, now the items add more spacing on the item headings, make the active one bold". Live walkthrough confirmed the active row's treatment was too subtle: faint `bg-blue-50/30` tint + `font-semibold` label at the same 14px as inactive rows, so at a glance you couldn't tell which accordion was open. Strengthened in `packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css`: bumped left bar from 2px → 3px; active label is now `font-bold text-[15px]` with the more accessible `text-blue-200` on dark / `text-blue-700` on light; bg tint changed to `bg-gray-100 dark:bg-gray-800/70` for stronger contrast against the panel bg; added `rounded-md` + `padding-bottom: 0.5rem` so the active row visually contains its expanded content as one unified accordion item. Verified at 1280×800: clicked Typography → bold 15px label, blue left bar, chevron rotated. Switched to Background → only Background visibly active, all 14 other rows uniform with icons + chevrons; expanded BACKGROUND COLOR/IMAGE/SIZE/REPEAT/POSITION/CLIP/BLEND MODE rows flow as one cohesive accordion block. `AdminLiveEditElementStyleEditorTest` 10 assertions green.

### Fixed
- **Live-edit Element Style Editor — Typography panel polish (sliders, labels, color picker)** (task-2026-05-05-73c751) — user attached a screenshot of the Typography accordion and reported "not looking good… slider and the UX is not ok, color picker etc, see all sections". Live walkthrough revealed: the slider track was visible at only opacity 0.18 (too faint to read on the dark theme); the ColorPicker pill was rendering as a 61×22 black-on-white text rectangle instead of a swatch (the `<style scoped>` `.picker-button { width: 30px; font-size: 0 }` was being bypassed — computed `font-size: 14px`, not `0`); sub-section labels (FONT, ALIGN, COLOR, FONT SIZE, …) at 14px UPPERCASE were too shouty for a dense control panel; the `ps-7` indent applied to expanded panel content in task-5da2e8 pushed labels ~28px right of the icon column. Fixes in `packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css` + `general-styles.css`: slider track bumped to opacity 0.30 background / 0.85 fill with explicit `height: 4px; border-radius: 2px`; thumb explicitly 14×14 with a 2px ring shadow; new `.element-style-editor-toggle-wrapper.active .live-edit-label` rule sets sub-section labels to `font-size: 10.5px; letter-spacing: 0.04em; font-weight: 600; opacity: 0.75` (with `!important` to defeat the upstream 14px rule); `.form-control-live-edit-label-wrapper` tightened to `min-height: 32px; margin-bottom: 8px` so the eight stacked Typography rows fit in ~320px instead of ~480px+; expanded panel content's `ps-7` indent reverted to `ps-1` so labels sit flush with the header; ColorPicker pill globally re-asserted at theme level (`#mw-element-style-editor-app .picker-button`) — `28×28` round swatch with `font-size: 0`, `border: 1px solid rgba(255,255,255,0.35)`, hover state, non-`.dark` fallback. Verified at 1280×800: every sub-section label is now 10.5px uppercase letter-spaced; FONT/ALIGN/COLOR sit on their own rows; COLOR has a proper 28px round swatch on the right showing the active color; every slider (FONT SIZE/LINE HEIGHT/LETTER SPACING/WORD SPACING) renders with a visible 4px track + filled portion + 14px thumb. Same treatment cascades to Background/Spacing/Border/Shadow panels that share the `.live-edit-label` markup. Regression: `AdminLiveEditElementStyleEditorTest` 1 test / 10 assertions green. Rebuilt the theme bundle.

### Fixed
- **Live-edit Element Style Editor — broken Typography fields** (task-2026-05-05-d2ce0f) — user attached a screenshot of the Typography panel showing three broken bits: the Align icons rendering ~50% width with Color's "Pick color" pill bleeding into the empty right side; every slider (Font size, Line height, Letter spacing, Word spacing) appearing as a solid white horizontal bar; and the numeric inputs next to each label invisible (white text on white background). Root causes: (a) `.text-align` used `block ... text-[0px]` with `inline-block w-1/4` children — when nested inside `.s-field-content`'s flex-col layout the row collapsed to its content width instead of filling the panel; (b) Vuetify's `<v-slider>` defaults to `v-theme--light` and renders an inert accessibility `<input>` under the visible track that inherits the browser default white bg; SliderSmall.vue's scoped styles also wrapped each slider in a near-opaque white card; (c) the slider's numeric input had hardcoded light-mode colors (`#2d3748`, `#e2e8f0`) baked in. Fixes in `packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css` + `packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/components/SliderSmall.vue`: (a) `.text-align` switched to `flex w-full ... mb-2` with `flex-1` children so the 4 align buttons distribute evenly across the row; (b) SliderSmall scoped styles dropped the white card and use `currentColor`/`inherit` so the slider picks up the surrounding dark theme; theme CSS forces `.v-slider-track__background`/`.v-slider-track__fill` to `currentColor` with opacity 0.18/0.55, hides the inert Vuetify input via `opacity: 0`, and re-asserts `.form-control-input-range-slider { background: transparent !important; color: inherit !important }` to override the higher-specificity rules in `live-edit-input.css`; (c) `.v-slider-thumb__surface` uses `currentColor` so the thumb is visible. Verified end-to-end via Playwright at 1280×800: Align row now spans full width with 4 icons in one bordered group, Color row sits below it with the "Pick color" pill on the right, FONT SIZE shows "44" in a small input next to a thin slider track instead of a solid white block, same applies to LINE HEIGHT/LETTER SPACING/WORD SPACING. The element-handle Style Editor button (the icon-bar entry on every selected element) was already wired (`element.js:337-354` → `editNodeStyleRequest` → `CSSGUIService.show()`); confirmed during the audit that it works as designed and only hides for `.no-typing` elements. Regression: `AdminLiveEditElementStyleEditorTest` 1 test / 10 assertions green. Rebuilt both `element-style-editor-app.js` (frontend-assets) and `microweber-filament-theme.css` (theme) bundles.

### Changed
- **Live-edit Element Style Editor — UX polish on the right-rail menu** (task-2026-05-05-5da2e8) — user attached a screenshot of the Element Style Editor's right-rail with a flat list of style categories (Typography, Background, Spacing, …, AI Style Editor) and asked for the design to be reworked. UX-engineer audit findings F1-F10: flat hierarchy with no chevrons, 24px (`mb-4`) gaps creating ~550-600px of vertical space for an 11-item list, no hover state, weak 2px-border active state, ambiguous click target, inconsistent icon sizing. Applied as a single coherent CSS pass in `packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css` plus a `mb-4 → mb-1` template sweep in `packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorApp.vue`: each row is now a button-shaped click target with `cursor: pointer`, `pl-3 pr-2 py-1.5` padding, hover bg (`bg-gray-100 dark:bg-gray-800`); active state is bg + bold blue label + blue icon + blue left-bar (was just the bar); every row gets a chevron-right pseudo-element via `.d-flex:has(> svg)::after` (inline SVG data-uri) that rotates 90° down on the active row to signal "open"; icons normalized to a 20×20 box regardless of viewBox; row spacing dropped from 24px to 4px so the list scans in ~370px instead of ~550-600px and the panel no longer feels empty/unfinished. The `:has(> svg)` selector covers the five category components (Container, Grid, Classes, Section settings, AI Style Editor) that wrap their header `.d-flex` in an extra `<div v-if="hasFoo">`. Verified end-to-end in Playwright at 1280×800: panel opens cleanly, every row has its chevron, clicking Typography highlights it (bg + bold blue + rotated chevron), no JS console errors. Regression: `AdminLiveEditElementStyleEditorTest` 1 test / 10 assertions green. Rebuilt both `element-style-editor-app.js` (frontend-assets) and `microweber-filament-theme.css` (theme) bundles.

### Changed
- **Live-edit Add Content modal — smaller, flatter, less scroll** (task-2026-05-05-899bf8) — user reported "the add post/page/category/product modal is not very good in live edit, please rework it … it must look very small and compact and not have scrolls". The modal had been bumped to FiveExtraLarge (1024px) back when the form had tabs + a two-column rich-editor layout, but the form is now single-column and compact, so the wide-shallow shape was wasting horizontal space while still scrolling vertically. Three changes in `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php` and `src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php`: (1) `modalWidth(MaxWidth::FiveExtraLarge)` → `MaxWidth::ThreeExtraLarge` so the modal renders at 768px and the live-edit canvas stays visible on either side; (2) upfront RichEditor `.ProseMirror` capped at `min-height: 5rem; max-height: 9rem; overflow-y: auto` (was Filament's default ~12rem) so the body field is a comfortable 5-line edit area instead of a 200px+ blob; tightened section padding from default to `0.625rem 0.875rem`, dropped `.fi-section-content` padding to 0, and capped Excerpt textarea to 3-5rem; (3) stripped the Title section's framing chrome (background/border/box-shadow/padding) with `!important` since Filament's `.fi-section` rules ship those tokens at higher specificity — the giant Title input now sits flush with the modal padding instead of inside a nested rounded card. Verified in Playwright at 1280×800 and 1920×1080 for both Post and Page flows: modal width 768px confirmed, Title section height 106px (down from ~190px), upfront body ProseMirror 80px tall (down from ~200px+), modal still centered, footer still sticky, no JS errors. The Add Content Dusk tests (`LiveEditAddContentBig2Test`, `LiveEditAddContentValidationFailPathTest`, `LiveEditAddContentModalIsCenteredTest`) all hit the `assertSame('testing', App::environment())` precondition which fails against the running `local` dev server — pre-existing env mismatch unrelated to this change; PHP `-l` lints clean on the changed files.

### Fixed
- **Live-edit Element Style Editor — empty panel after clicking "Design"** (task-2026-05-05-dc910e) — user reported "all styles are gone" with a screenshot of the right-rail Element Style Editor where every section (Typography, Background, Spacing, Grid, Border, Rounded corners, Animations, Shadow, Classes, AI Style Editor) was inactive and the panel showed "Please select an element to edit" no matter what was clicked on the canvas. Walking the flow with Playwright confirmed `parent.mw.app.liveEdit.getSelectedNode()` returned a valid DIV — live-edit's selection state was correct — but the ESE's Vue `selectedElement` stayed `null`, so the bug was in the Vue listener wiring, not in live-edit. Root cause: `packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorActiveNode.vue` `mounted()` wrapped its `mw.elementStyleEditor.selectNode` / `refreshNode` / `selectLayout` / `canvasDocumentClick` / `reloadCustomCssDone` listeners inside `mw.app.on('onLiveEditReady', ...)`. Microweber's event bus does not replay missed events — `onLiveEditReady` fires once during live-edit boot, well before the user clicks the toolbar's "Design" button to mount the ESE Vue app, so the listeners never got attached and `selectedElement` stayed `null` forever. Fix: register listeners eagerly in `mounted()` (no `onLiveEditReady` gate), wrapped in a `setupListeners()` helper invoked immediately when `mw.top().app.canvas` is reachable, with a 200ms `setTimeout` retry as a fallback for the rare cold-boot race. Also seed `this.$root.selectedElement` from `mw.top().app.liveEdit.getSelectedNode()` on mount so a pre-existing canvas selection paints right away. Rebuilt the frontend-assets bundle (`element-style-editor-app.js` 174.59 kB / 44.11 kB gz). Verified end-to-end via Playwright + `AdminLiveEditElementStyleEditorTest` (10 assertions) plus three adjacent live-edit tests (Pricing inline double-click, Titles skin1, public-page console clean — 43 assertions). All 4 tests green, 53 assertions total, no JS console errors, no public-render regressions.

### Fixed
- **Live-edit Add Content failed-save clarity** (task-2026-05-05-02f93f) — a UX-engineer audit found the highest remaining friction in the compact Add Page/Post/Product modal: when the required title was empty, the shared live-edit SAVE pill could feel like it "did nothing", especially on the product path where the mounted action was not surfacing an obvious server-side invalid state through the compact modal. Tightened the live-edit save flow in `src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php` so empty title submits are intercepted before requestSubmit, the compact title field gets an unmistakable inline invalid state + required message + focus/scroll reveal, and the mounted-action validation-failed event is emitted consistently. Updated `packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue` so mounted-action validation now shows a validation-specific error toast instead of a misleading page-saved success toast. Strengthened `tests/Browser/LiveEditAddContentValidationFailPathTest.php` to assert the visible invalid/error UX for page, post, and product flows.

### Fixed
- **Live-edit CSS leak into generic Filament pages** (task-2026-05-05-3df6cc) — a design audit found that live-edit-specific Filament tab, input, and modal-overlay rules were imported globally by the admin theme and could keep causing unrelated admin pages to inherit live-edit chrome. Scoped the leaking selectors in `packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-action-links.css`, `live-edit-input.css`, `general-styles.css`, and `live-edit-classes.css` to `.mw-admin-live-edit-page`, added `tests/Feature/Filament/Theme/LiveEditCssScopeTest.php` to lock the selector fences in place, and rebuilt both the Filament theme and frontend-assets bundles. Impact: future admin-theme polish work no longer has to fight these live-edit overrides on every non-live-edit Filament screen.

### Fixed
- **Live-edit post-save dead-corner — persistent title-aware toast** (task-2026-05-04-6adcfe) — user reported "I still don't think the content adding is ok". Walking the flow showed: title-only Save lands on a near-empty public page (just title + date + footer) and the success toast faded in 5s, leaving the user with no clear next action. Three fixes in `AdminLiveEditPage::generateAction`'s save handler: toast title now quotes the actual saved content title (`"My Saturday Market" created` instead of generic `Post created`) for strong recognition; body rewritten to "Now click anywhere on the page to start writing — or \"Edit details\" for SEO, tags, and more." giving the user the actual mental model of what to do next; `->duration(5000)` replaced with `->persistent()` so the toast stays until the user dismisses it. The "Edit details" action button stays available the whole time.

### Fixed
- **Live-edit Add Content — UX-engineer second-pass audit (3 quick-wins shipped, 8 captured for follow-up)** (task-2026-05-04-39767a) — second-pass UX audit returned 11 fresh findings the first pass missed. Quick wins: (UX #4 P1) drop blanket `text-transform: uppercase !important` from `.fi-btn.fi-color-success` inside the live-edit modal stack so the green Save no longer SHOUTS next to the friendly placeholder copy; (UX #9 P2) drop uppercase letterspacing from compact-modal section headings ("MORE OPTIONS"/"WHERE TO PUT IT" → "More options"/"Where to put it") so all section labels match the friendly voice; (UX #5 P1, source-only — needs frontend-assets rebuild) `SettingsCustomize.vue` right-rail toolbar buttons (Insert layout, Template settings, Design, Quick AI edit, Advanced) gain `role="button"`, `tabindex="0"`, `aria-label`, `:aria-pressed` toggle pattern, and `keydown.enter`/`keydown.space` handlers — were keyboard-unreachable and screen-reader-nameless. Eight BIGGER findings captured: Edit-existing flow polish gap, dead-corner post-save empty state, modal-on-modal stacking on items-list Edit, domtree icon-glyph aria-hidden, mobile toolbar wrap, parent-tree default-collapse, blog skin no-image variant, template thumbnail dropdown.

### Fixed
- **Live-edit Add Content — accessibility-auditor audit (3 quick-wins shipped, 5 captured for follow-up)** (task-2026-05-04-078a50) — round of WCAG 2.2 AA fixes from an accessibility-auditor-persona Playwright keyboard-only walkthrough. Quick wins shipped: (a11y #1 P0) Picker cards now keyboard-activatable — `role=button tabindex=0` cards had no `onkeydown`, so Enter/Space did nothing on a focused card; added inline keydown that calls `this.click()` plus an `aria-label` combining title+description so screen readers announce both (WCAG 2.1.1 Keyboard). (a11y #2 P0) Toolbar device-preview spans `<span role="button">` had no `tabindex` so browsers computed `tabIndex=-1` and Tab skipped them — added `tabindex="0"`, `aria-pressed` toggle-button pattern, `keydown.enter`/`keydown.space` handlers, and `aria-hidden="true"` on decorative SVGs in `ResolutionSwitch.vue` (WCAG 2.1.1 Keyboard, 4.1.2 Name/Role/Value). (a11y #4 P1) Reduced-motion plumbing — added a `@media (prefers-reduced-motion: reduce)` block in `iframe-page.blade.php` that drops `transition-duration` + `animation-duration` to 0.001ms across the picker cards, modal-window, sections, tabs, inputs, and buttons (WCAG 2.3.3 Animation from Interactions). Five BIGGER findings captured as deferred backlog: validation errors not announced (no `role="alert"` / `aria-invalid`), Search input has no accessible name, Title field not marked required to AT, picker card title is `<div>` not heading, no `<h1>` on the live-edit page.

### Changed
- **Live-edit Add Content — novice-customer audit (6 quick-wins shipped, 6 captured for follow-up)** (task-2026-05-04-75b7fe) — round of plain-English copy + forgiveness fixes from a novice-customer-persona Playwright walkthrough (Brenda, 55, antiques-shop owner, never edited a website). Quick wins: (1) picker card descriptions rewritten to plain English in 3 files (`Add new post to your blog page, linked to category of main page` → `A blog article or news story that appears in your Blog list.`); (2) Excerpt label → "Short summary" with rewritten helper; (3) "Content body" RichEditor label → "Write your post here" so the label doubles as a call-to-action; (4) "Parent page" section heading → "Where to put it"; (5) "Open in admin" footer button → "Show all options" so it doesn't read as a synonym for Save; (6) forgiveness guard on Cancel + X close — `attachCancelGuard()` in `iframe-page.blade.php` intercepts the close when title/body/summary has unsaved content and prompts "Discard this draft?" via `window.confirm()`. Six bigger findings captured as deferred backlog under the same task: success toast not visible, Show-all-options drops body, Toolbar labels still jargon, no edit pencil on existing cards, "Select media file" wording, empty-post post-save state.

### Changed
- **Live-edit module-settings modal: centered, not slideOver** (task-2026-05-04-b2dfc1) — `AdminLiveEditPage::openModuleSettingsAction` (the action fired when the user clicks the inline edit pencil on a Posts / Products / Pages / Categories module to open its settings panel + Items list) used to render as a `MaxWidth::Medium` (~448px) right-edge slideOver. Dropped `->slideOver()` and bumped width to `MaxWidth::FiveExtraLarge` (1024px) so it opens as a centered modal — matches the +ADD toolbar's centered modal width. The inner Create / Edit form now has desktop room and the Items-list table doesn't get squeezed into a narrow column.

### Fixed
- **Live-edit Add Content — mobile-designer audit fixes (6 findings)** (task-2026-05-04-0c2964) — round of mobile-only fixes from a mobile-designer-persona Playwright walkthrough at 390×844. (P0-1) Title/Excerpt/body inputs were collapsed to ~189px on a 366px modal because `.fi-modal-content` + `.fi-section` + `.fi-section-content` compounded ~80px of inline padding before the input saw the inner width — forced 0.5rem ctn + 0 inner padding (with !important against the theme's @layer rules) and grid-template-columns: minmax(0, 1fr) on the field/section grids, lifting Title to 257px (+36%). (P0-2) iOS Safari was triggering zoom-on-focus on every input <16px font-size and never auto-restoring — forced all editable controls in the modal to 16px on `<768px`. (P0-3) Tiptap rich-text toolbar wrapped 20 icons to 4 rows of 32px buttons in the narrow column — switched to `flex-wrap: nowrap; overflow-x: auto` with 44pt min-size on each button, so the toolbar is now one scrollable row of comfortable thumb taps. (P1-4) Live-edit toolbar wraps to 2 rows below 480px (~104px tall) but the modal was using a hard-coded 60px offset — bumped to 104px so the modal opens clear of both wrapped and unwrapped toolbar states. (P1-5) Modal close X bumped from 36×36 to 44×44; filepicker close X + footer buttons bumped to 44pt minimum. (P1-6) Footer now `position: sticky` + `padding-bottom: max(0.75rem, env(safe-area-inset-bottom))` so it stays pinned during keyboard appearances and respects the iPhone home-indicator on notched devices.

### Changed
- **Live-edit Add Content — drunk-designer audit implementation (9 of 12 findings)** (task-2026-05-04-a8d5bb) — round of design-debt fixes from the drunk-designer audit captured in task-2026-05-04-fde944. Implemented all CSS-only / PHP-only items: (#2) modal heading promoted to 18px/600 type ladder; (#5) radius tokens unified (modal 12px, inner sections + picker cards 8px); (#6) shadow soup replaced with one elevation token on the modal-window only — inner sections + picker cards now flat; (#7) picker hover swapped from unconditional `group-hover:bg-white` to `bg-blue-500/10 dark:bg-white/10` (was flashing white-on-dark in dark mode), removed the juvenile 5% hover-scale jiggle, removed the inner-tile shadow; (#8) restored focus rings (2px accent ring on input, picker cards, close X) — Filament's defaults were being suppressed by the theme; (#10) stripped the Title section's wrapping chrome (border/padding/shadow) so the input sits flush with no dead frame; (#11) Price + Special price inputs now show a currency prefix from `currency_symbol()`, helpers tightened, "(e.g. 19.99)" moved into the placeholder; (#12) success toast title de-typo'd ("is  created" → "created"), body rewritten to point at the Edit details action, duration bumped to 5s so users actually see the next-step affordance. Three findings deferred (#1 unify primary green token, #4 standardise radio/Tiptap blues, #9 group Tiptap toolbar) — they require frontend-assets bundle rebuilds outside this CSS-only scope.

### Fixed
- **Live-edit Add Content — customer-persona acceptance fixes** (task-2026-05-04-1e4af3) — round of fixes from a customer-persona acceptance test (Sarah-the-bakery-owner). (1) Content body + Excerpt are now visible UPFRONT for posts/products instead of being hidden in the "More options" accordion — saving a title-only post used to produce a publicly visible empty page (just title + date + footer). Pages already had their body upfront in Page setup. (2) Modal drag clamp tightened: 120px grab-strip on each axis AND `minTop = 0` so the modal can no longer be parked off-screen with no recovery path (Escape + backdrop are disabled on create modals so a full reload was the only escape). (3) Open-in-admin button now carries the typed title forward via `&title=<encoded>` query string — small JS hook in `iframe-page.blade.php` rewrites the button's href on every title input event, so jumping to the full admin form preserves typed work. (4) Category compact modal: parent picker is now OPTIONAL (auto-defaults to the canvas page from query/referer), labelled "(optional)", and moved into the "More options" accordion — title-only category save now works. (5) Picker copy: "pre-pared" → "pre-built" (typo); Open-in-admin button switched from `->color('gray')` (near-invisible on dark mode) to `->outlined()` for explicit border in both themes.

### Fixed
- **Live-edit Create Page modal: type-aware placeholder + page fields visible** (task-2026-05-04-4e425c) — Title placeholder is now content-type aware: "What's the page title?" / "What's the product name?" / "What's the post about?" instead of always "post about". Pages get a new upfront `Page setup` section with `MwSelectTemplateForPage` (Template + Layout chooser with thumbnail preview) and a `Page content` RichEditor. Body editor now renders once per modal: upfront for pages, inside More options for posts/products.

### Fixed
- **Live-edit Add Category modal: dropdown clipping** (task-2026-05-04-fc971b) — Filament's Choices.js Select panel was being clipped by the modal-content's `overflow-y: auto` scroll container (CSS clip happens regardless of z-index for absolutely-positioned descendants of `overflow: auto` ancestors). Switched the parent picker to `->native(true)` so the browser's native `<select>` dropdown renders OUTSIDE the modal stacking context and can't be clipped. Free type-ahead search comes via the OS-level keyboard handler.

### Changed
- **Live-edit Add Product modal: Price visible upfront** (task-2026-05-04-26c52a) — moved pricingSection from "More options" accordion back into the upfront stack so creating a product no longer requires expanding an accordion to enter the most essential field. pricingSection's own `->visible()` callback keeps it absent for posts/pages.
- **Live-edit Add Category modal: Filament Select replaces mw.tree parent picker** (task-2026-05-04-26c52a) — the live-edit category compact form now uses a native searchable Filament Select listing pages and categories with prefixed labels ("Page: My Blog", "Category: News"). Encoded `page:{id}` / `category:{id}` option keys are split server-side in afterStateUpdated to write the appropriate hidden field. The full admin form `CategoryResource::form()` keeps the mw.tree picker for power-user scenarios — the lean Select is live-edit-only.

### Changed
- **Live-edit Add Content modal — true Facebook-compact upfront stack + polished More-options accordion** (task-2026-05-04-2cd250 + task-2026-05-04-1f20d2) — moved the Parent-page section from upfront into the "More options" accordion (the 575px parent-tree was the dominant height contributor pushing SAVE below the fold on smaller viewports). Tightened upfront paddings + Media tile dimensions so the upfront stack drops to ~332px of content; combined with header + footer the modal fits in under ~520px, comfortably reaches SAVE at the reported viewport. Polished the expanded accordion: inner sections drop their card chrome (border, shadow, padding) so the children sit flush; section headings re-styled as small uppercase tracking labels in `--gray-500/400` (light/dark) to function as group dividers; parent-tree search + tree rows use 0.8125rem typography + tighter block padding; RichEditor min-height clamped at 6rem + 0.5rem padding so the toolbar doesn't dwarf the Excerpt below it.

### Changed
- **Live-edit Add Content modal — Facebook-style writing surface skin** (task-2026-05-04-bfe418) — based on user's Facebook "Create post" reference. Title field rendered as a 24px borderless big-typography placeholder ("What's the post about?") with no label; Media drop-zone rendered as a dashed-border "Add a picture" tile with the "Add images" label hidden — reads as a tool affordance, not a labelled form section. Modal heading "Create post" + placeholder text do the labelling work that field labels used to do. Section borders / shadows / heading icons stripped from the upfront stack; only Parent page and "More options" keep their section chrome to guide the eye. Skin scoped to `.mw-content-form-modal` so the full admin form `/admin/contents/{id}/edit` is unaffected.

### Changed
- **Live-edit Add Content modal — super-minimalistic schema with "More options" accordion** (task-2026-05-04-2199df) — restructured `ContentResource::formArrayCompact()` so only Title, Media (Picture), and Parent page are visible upfront; Content body + Excerpt + Published toggle + Pricing collapsed into a single "More options" accordion that the user can expand on demand. `CategoryResource::formArrayCompact()` mirrors the pattern: Title + Parent visible, Description in the accordion. The per-module Posts/Pages/Products Items-list "NEW POST" + "Edit" modals route through the same compact form (via `ContentTableList::editFormArray()`) so they pick up the new minimalistic layout automatically — matches the user's screenshot of the post-module right-sidebar entry point.

### Fixed
- **Live-edit Add Content modal — UX polish round** (task-2026-05-04-f575c7) — UX-engineer audit identified four hits: (1) modal heading was rendering behind the 60px live-edit toolbar (the heading center sat at y=52, toolbar bottom = 60), hiding the title and intercepting clicks on the drag-by-header handle. Pinned the modal to `top: calc(var(--toolbar-height, 60px) + 0.75rem)` so the heading is always clear of the toolbar; (2) the 100+-item parent-page tree was dumped under every form despite the parent being auto-resolved 95% of the time — collapsed `parentPageSection` by default in `formArrayCompact()` for both ContentResource and CategoryResource; (3) browser-native "Please fill out this field" tooltip used to anchor itself to the wrong field on submit — swapped Title's `->required()` for `->rules(['required'])->markAsRequired()` so Filament's own inline validation renders below the Title field instead; (4) the success toast offered "View Post" but the canvas had already navigated there — replaced with "Edit details" → full admin edit form in a new tab, useful next step for power users who want to refine SEO / Tags / Menus / Custom Fields.

### Added
- **Live-edit Add Content modal: "Open in admin" escape hatch + mobile-responsive layout** (task-2026-05-04-76275d) — every live-edit content modal (toolbar +ADD create, per-module Items-list Create, per-module Items-list Edit) now ships an "Open in admin" button (with external-link icon, `target=_blank`) in the modal footer alongside Save/Cancel. Power users who need SEO / Custom Fields / Tags / Menus / Variants click it to open the full admin form in a new tab without losing their place in live-edit. The URL carries the current canvas page forward as `?parent_page_id=` so the admin form pre-selects the same parent. Mobile-responsive: below 768px the `.fi-width-5xl` 1024px width is overridden and the modal pins to viewport edges with 12px gutters — usable on phones.

### Changed
- **Live-edit Category modal uses a compact form schema, Permalink dropped, dark-mode polish** (task-2026-05-04-e0fe54) — added `CategoryResource::formArrayCompact()` mirroring the Content compact variant (no tabs, just Title + Description + Parent picker) and wired `AdminLiveEditPage::generateAction()` to use it for `contentType == 'category'`. The Permalink (URL slug) subsection was dropped from `ContentResource::formArrayCompact()`'s general-information block — customers almost never set the slug manually during inline create, the auto-generated value is fine. Dark-mode chrome polish for `.mw-content-form-modal`: tighter header padding with explicit bottom border, section cards lift slightly off the body in dark mode for card-on-dark separation, tighter section padding + form-field gap, brighter heading colors. Full admin form schemas (`ContentResource::formArray`, `CategoryResource::formArray`) untouched — power users still get the full feature set at /admin/content/{id}/edit and /admin/categories/{id}/edit.
- **Live-edit Add Content modal uses a compact form schema** (task-2026-05-04-1d68c7) — added `ContentResource::formArrayCompact()` and wired it to the live-edit toolbar +ADD (`AdminLiveEditPage::generateAction`) and the per-module Items-list Create/Edit (`ContentTableList::editFormArray`). Cuts the form from 6 tabs + 14+ sections (~25+ fields) down to 4 sections + 6 visible labels — Title, Content body, Excerpt, Permalink (collapsed), Published, Parent page. Power users can still open the full form via the regular admin Filament resource at /admin/content/{id}/edit; the live-edit modal is now optimised for "create now, refine later" inline workflow.

### Fixed
- **Live-edit Add Content modal scroll + footer bleed** (task-2026-05-04-b7eee8) — the Add Post / Page / Product / Category form is ~1640px tall on a typical 900px viewport, so the modal used to grow past the fold, hide the SAVE/Cancel footer, and let later sections (Media, parent-page tree) bleed visually behind the would-be footer. Switched `.mw-content-form-modal` to a flex column with `.fi-modal-content` as the lone scrollable child, and pinned the modal to `position: fixed; top: 1.5rem; max-height: calc(100vh - 3rem)`. Header + scrollable body + footer all fit in the viewport with breathing room above and below; users scroll inside the modal, footer always visible.

### Changed
- **Per-module Posts / Pages / Products Create/Edit modals widened** (task-2026-05-04-61e974, task-2026-05-04-6f1549) — `ContentTableList::headerActions().CreateAction` and `actions().EditAction` `modalWidth(MaxWidth::ThreeExtraLarge)` (768px) → `MaxWidth::FiveExtraLarge` (1024px) to match the +ADD toolbar's modal width. Closes the user's "still uses the old modal" complaint by giving the per-module entry points the same wide layout as the toolbar entry point. Same scroll + flex layout as task-b7eee8 mirrored into `live-edit-module-settings.blade.php` so the iframe-rendered per-module modals also fit their viewport.

### Added
- **Live-edit Add Content modal is now draggable** (task-2026-05-04-c124bc) — the user can grab the modal's header and drag the modal anywhere on the screen so the live-edit canvas behind it is visible — same UX Microweber v2's `mw.dialog` shipped, applied directly to Filament's existing `.fi-modal-window.mw-content-form-modal` via a small native pointer handler in `iframe-page.blade.php`. Avoids porting the full Filament modal pipeline, so the form's Livewire wire:click / wire:model bindings stay intact. Cursor flips to `move` on the header and `grabbing` while dragging; modal pins to `position: fixed` at mousedown so subsequent updates work in viewport space. Y-axis containment is asymmetric (header must stay visible at the bottom, but tall forms can be pushed above the viewport so the user can reach the bottom of the form).

### Fixed
- **Live-edit Add Post — JS errors after save** (task-2026-05-04-3337c0) — `element-style-editor-main.blade.php` ran `mw.require()` / `mw.top()` inline during HTML parse, but the bundle that defines them (`admin.js`) is `type="module"` (deferred), so the inline code raced ahead and threw `mw.require is not a function` + `mw.top is not a function` + a cascading `Cannot read properties of undefined (reading 'on')` from the Vue mounted hook. Wrapped the inline scripts in a wait-for-mw IIFE (poll up to 10s, same pattern as `render-css-editor.blade.php`'s `waitForCodeMirror`) and deferred the Vue bundle injection until inside `init()` so the bundle's own `mounted()` hook only runs after `mw.top().app` is ready. Add Post → SAVE now reports zero console errors end-to-end.

### Changed
- **Live-edit Add Page / Post / Product / Category modal width** (task-2026-05-04-3337c0) — bumped from `MaxWidth::ThreeExtraLarge` (768px) to `MaxWidth::FiveExtraLarge` (1024px). At 768px the tabs + two-column form + rich-text editor still felt cramped on standard 1080p+ desktop viewports; 1024px gives the editor proper breathing room while still leaving the live-edit canvas visible behind the backdrop tint. `LiveEditAddContentModalIsCenteredTest` and `LiveEditTopModalAndSelectiveReloadTest` updated to assert `fi-width-5xl` instead of `fi-width-3xl` so the regression guard tracks the new tier.


## [4.0-dev17] - 2026-04-03

### Added
- **Media Library** — Full admin UI with 3-panel layout (folder sidebar, thumbnail grid/list, detail panel), drag-and-drop upload, bulk actions (delete, move, CDN sync), search/filters (type, date range, folder), Unsplash stock photo integration, and 26 Livewire component tests
- **Menu Management** — Redesigned drag-and-drop menu editor with nested items, item type icons (page/category/custom URL), rename/delete actions, and cascade-delete for child items
- **Product Variants** — Variant management UI with attribute selection, combination generation, and per-variant pricing/inventory/shipping fields
- **Order Enhancements** — Status-change timeline, shipping tracking fields, bulk status update, and refund processing UI (partial/full) with 3 new migrations
- **Users Resource** — Enabled create/edit routes with role assignment form
- **Content Management** — Bulk publish/unpublish actions, post excerpt field, publish/schedule date picker, author column and filter
- **Product Inventory** — Low-stock threshold field, stock status badges (In Stock/Low Stock/Out of Stock) in list view
- **Login Page** — Styled to match MW v2 visual design with branding, layout, and dark mode support
- **Dashboard** — MW v2-style welcome widget, quick stats cards, ECharts area chart with period switching (Daily/Weekly/Monthly)
- **Accessibility** — Skip-navigation link on all admin pages
- **Documentation** — SCOPE.md, PLAN.md, and Media Library feature spec

### Changed
- Migrated dashboard chart from Chart.js to ECharts with gradient fill and smooth line
- Sidebar widened to 16rem with improved group headers and text wrapping
- Shipping dimension fields renamed to match backend drivers (shipping_weight, shipping_width, shipping_height, shipping_depth)
- Settings.php refactored: extracted `extractItemData()`, fixed typo, added array guard
- SiteStatsRepository: fixed ambiguous column in JOIN query, corrected JOIN condition

### Fixed
- Alpine.js component loading error (`mwTreeFormComponent is not defined`) on create/edit forms
- Duplicate `HEAD_START` render hook causing "Identifier 'Ziggy' has already been declared" JS error
- `DateMalformedStringException` when saving content with invalid date strings (PHP 8.4)
- Settings hub page missing title prefix
- Mobile topbar avatar clipping and orders table overflow
- ECharts widget period switching (was non-functional due to `wire:ignore` blocking DOM updates)
- Dashboard stats: deduplicated order queries, added 120s cache, fixed currency symbol

### Security
- Pinned ECharts CDN to v5.5.1 with SRI integrity hash
- Resolved lodash.set prototype pollution CVE

### Performance
- Dashboard quick stats: combined 4 queries into 1 with 120s cache
- SiteStatsEchartsWidget: memoized chart data, cached online count with 60s TTL

## [2.0.16] - 2024-06-26
- Maintenance release
- Fixes on image upload, now rotates the image based on the EXIF data
- XSS fixes in save user and on search 
- Fixes on video module
- Fixes on text type module
- Fixes on marquee module
- Fixes on slider module
- Other issues
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.15...2.0.16 "")



## [2.0.15] - 2024-05-15
- Maintenance release
- Fixes on the Undo and Redo in the live edit
- Fixes editing text in tables in the live edit
- Fixes on the button module url picker
- Fixes on migrations from old versions
- Fixes editing order in admin panel
- Other issues
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.14...2.0.15 "")


 
## [2.0.14] - 2024-05-07
- Maintenance release
- Fixes on the Multi language module
- Fixes on the backup module
- Fixes the contact form module
- Added image alt and title to the image module
- More work done on the free draggable elements
- Icons are now resizable
- Other issues
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.13...2.0.14 "")



## [2.0.13] - 2024-04-02
- Maintenance release
- Fixes on the backup module
- Fixes on the contact form module
- Fixes on the menu module in multilanguage mode
- Fixes on button link picker in multilanguage mode
- Fixes on google analytics module
- Fixes on social links module
- Various fixes on the live edit
- Added marque module
- Added text typing effect module
- Other issues
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.12...2.0.13 "")



## [2.0.12] - 2024-02-19
- Maintenance release
- Fix display of text editor on small screen
- Fix problem with the update function not saving the new version number in the database
- Fix custom fields not updating the list of fields after save
- Fix problem with livewire modules needing initialization after module reload 
- Fix modal window not closing if you are focused on color picker field 
- Fix CRTL+S not working in the text editor if you are with caps lock on or on non-latin keyboard
- Other issues
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.11...2.0.12 "")


## [2.0.11] - 2024-02-16
- Maintenance release
- Fix scroll to element if click on link that has inner elements
- Fix multilanguage dropdown menu was going out of the screen
- Fix Use picture from posts setting was not refreshing the image galeery
- Added trust proxy settings in the admin panel
- Added element style editor button in the live edit toolbar
- Link picker modal is now draggable
- Other issues
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.10...2.0.11 "")


## [2.0.10] - 2024-02-06
- Maintenance release
- Fix Cant edit text in some heading tags
- Fix cant save image as background when using svg
- Fix Must be able to delete email messages
- Fix Live edit menu is missing on small screen
- Fix Layout background image is not deleted when 
- Fix Element style editor was not opening 
- Fix text typing in the editor 
- Fix the background image module when you have picture and color as background
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.9...2.0.10 "")


## [2.0.9] - 2024-01-23
- Maintenance release
- Fixed missing database field in the newsletter module
- Fixed issue on Enter key in live edit
- Fixed on testimonials module not showing the image
- Fixed accordion module not showing the icon
- Fixes on multiple columns module not showing the drag handles
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.8...2.0.9 "")


## [2.0.8] - 2024-01-17
- Maintenance release
- Fixes in live edit
- Fixes on wysiwyg editor in admin
- Multilanguage fixes
- Fixes on module presets
- Fixes on custom fields 
- Fixes on the contact form module
- Fixed on modal windows not aways closing
- Fixed on shop module showing deleted products
- Fixed logo module size slider
- Improved the newsletter module
- Other miscellaneous fixes
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.7...2.0.8 "")


## [2.0.7] - 2023-12-15
- Maintenance release
- Text Editor can now be pinned to the top
- Fixed some problems with live editing
- Fixed undo and redo issues when changing CSS properties
- Fixed Testimonials module
- Fixed editing borders in the style editor
- Fixed Accordion module
- Fixed an issue when saving a Multi language page, but changing the language in another tab
- Further improvements to the newsletter module
- XSS fixes
- Other miscellaneous fixes
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.6...2.0.7 "")


## [2.0.6] - 2023-12-08
- Maintenance release
- Added new price modifier custom field setting for products
- Added newsletter module
- New template editing framework only with css variables 
- Fixed problem with resize images
- Fixed problem when editing text in field without html elements
- Fixed element style editor to work any element that have ID
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/2.0.5...2.0.6 "")



## [2.0.5] - 2023-11-17
- Maintenance release
- Fixed issues with cloneable controls
- Fixed issues with elements style editor when you change element 
- Fixed issues with font manager
- Fixed issues with module item editor 
- Fixed categories images module
- Fixed handles for clonable element not being shown
- Fixed some issues with categories module
- Fixed some issues with modal windows for module settings
- Fixed buttons in add content modal window not being sticky
- Fixed confirm dialog to work with escape key and enter key 
- Fixed security issue with svg upload
- Added options to loop and must video for video module
- Added option for site stats module to send server side evens to google analytics
- Added newsletter module
- Added Laravel events for some cart and checkout actions
- Added box shadow and text shadow settings in element style editor
- [see all changes....](https://github.com/microweber/microweber/compare/2.0.4...2.0.5 "")
`

## [2.0.4] - 2023-11-09
- Fixed problem with menu links  
- Fixed problem when you edit module settings and close the modal while saving
- Fixed accordeon module can't be opened in live edit
- Fixed audio module
- Fixed popup module
 - [see all changes....](https://github.com/microweber/microweber/compare/2.0.3...2.0.4 "")


## [2.0.3] - 2023-11-02
- Small maintenance release
- Fixed problem with adding items to menu in admin panel
- Modules are now inserted on bottom of other modules instead of top
- [see all changes....](https://github.com/microweber/microweber/compare/2.0.2...2.0.3 "")


## [2.0.2] - 2023-10-31
- Small maintenance release
- Fixed problem with image resizing
- Fixed problem with editing testimonials module
- Fixed problem with user image upload
- Fixed problem with reordering testimonials
- Fixed problem with removing format from text 
- [see all changes....](https://github.com/microweber/microweber/compare/2.0.1...2.0.2 "")



## [2.0.1] - 2023-10-27
- Small maintenance release
- Fixed drop problems in columns
- Fixed drop problems when you mouse over other modules
- [see all changes....](https://github.com/microweber/microweber/compare/2.0.0...2.0.1 "")



## [2.0.0] - 2023-10-24
- Big milestone release
- Overhaul of the admin panel and live edit
- New live edit experience
- New admin panel design
- New live edit element style editor
- Refactored modules on Livewire components
- [see all changes....](https://github.com/microweber/microweber/compare/1.3.4...2.0.0 "")



## [1.3.4] - 2023-03-24
- Added artisan command for update `php artisan microweber:update`
- Security fixes
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.3.3...1.3.4 "")


## [1.3.3] - 2023-03-13
- Added bindings for Filament admin panel
- Added driver interface for payment modules
- Added support fo 2FA for admin panel
- Fixed loading order of service providers for modules
- Security fixes
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.3.2...1.3.3 "")


## [1.3.2] - 2023-01-27
- Big maintenance release
- Improvements in live edit mode
- Refactored the admin content list
- Refactored the admin orders list
- Refactored file browser
- Fixed various bugs
- Added Import and Export module to import content
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.3.1...1.3.2 "")


## [1.3.1] - 2022-08-11
- Maintenance release 
- Fix to add missing config files on update
- Fixed XSS in the category name
- Menu url field is now translatable
- Added helper to compile blade from string
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.3.0...1.3.1 "")


## [1.3.0] - 2022-07-20
- Upgraded to Laravel 9 and PHP 8.1
- Added Livewire support for the admin panel
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.20...1.3.0 "")


## [1.2.21] - 2022-07-18
- Fixed security related to module attributes
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.20...1.2.21 "")


## [1.2.20] - 2022-07-09
- Fixed security issue when uploading svg files with script tags
- Fixed security issue when using special characters as url parameters
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.19...1.2.20 "")


## [1.2.19] - 2022-07-04
- Fixed uploading, now requires a valid XSRF token.
- Fixed artisan command names and arguments are now with dash instead of underscore
- Fixed XSRF token is now obtained from the server by ajax request.
- Fixed forgot password now works by username not only by email.
- Fixed external URL redirect on logout, while providing username and password in the URL.
- Fixed toggle of notifications accordion was broken due Bootstrap 5 migration
- Fixed bug related to image resizing in live edit.
- Fixed twitter feed was not loading when you reload the module by JS. 
- Added ability for the code editor to pop-out in new window.
- Added icons in the dom tree in live edit for easier identification of elements.
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.18...1.2.19 "")


## [1.2.18] - 2022-06-22
- Fixed unpublished products that were shown on frontend
- Fixed empty CSS properties are removed when you reset the element from live edit
- Fixed mail sending, when using laravel config
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.17...1.2.18 "")


## [1.2.17] - 2022-06-14
 - Fixed Bootstrap 5 dropdowns and tabs 
 - Fixed api_url function to use router if exists
 - Other fixes
 - [see all changes....](https://github.com/microweber/microweber/compare/1.2.16...1.2.17 "")


## [1.2.16] - 2022-06-09
 - Fixed more RTL issues 
 - Fixed translation issues on the slider module
 - Fixed PNG anf GIF image transparency issue
 - Fixed Twitter feed module
 - Fixed contact form not accepting terms and conditions
 - Translations update 
 - Security fixes
 - Other fixes
 - [see all changes....](https://github.com/microweber/microweber/compare/1.2.15...1.2.16 "")


## [1.2.15] - 2022-04-29
- Added new free template based on Bootstrap 5
- Added support for inline svg in live edit
- Added support animations in live edit
- Fixed contact form not sending email, when disable mail saving option is enabled
- Security fixes 
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.14...1.2.15 "")


## [1.2.14] - 2022-04-14
- Updated admin panel to Bootstrap 5
- Improvements on the editor when editing UL and LI elements
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.13...1.2.14 "")


## [1.2.13] - 2022-04-04
- Fixes right to left text alignment in the editor.
- Fixes on Multi language
- Security fixes
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.12...1.2.13 "")


## [1.2.12] - 2022-03-22
- Fixes on backup module
- Security fixes
- Added more validations
- Added more tests
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.11...1.2.12 "")


## [1.2.11] - 2022-02-19
- Updated Laravel to 8.83
- Fixes on live edit
- Fixes on multi language
- Fixes on backup module
- Fixes on shop module
- Fixes the security 
- Added more tests
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.10...1.2.11 "")


## [1.2.10] - 2021-12-08
- Fixes on live edit
- Fixes on multi language
- Fixes on coupon codes module
- Fixes on sitemap and rss feeds
- Fixes on categories module
- Fixes on comments module
- Fixes on paypal module
- Fixes on forgot password
- Added Laravel Dusk tests
- Some code refactoring 
- Many other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.9...1.2.10 "")


## [1.2.9] - 2021-09-16
- Updated Laravel to 8.61
- Fixes on the loading speed
- Fixes on the security
- Fixes on live edit
- Added service provider for templates
- Added new repository classes
- Added multilanguage module
- Added new icons for modules
- Many other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.8...1.2.9 "")


## [1.2.8] - 2021-07-08
- Updated Laravel to 8.4
- Fixes the custom fields
- Fixes the thumbnails
- Added new blog module
- Added new shop module
- Added ability for shop and blog to have filters
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.7...1.2.8 "")


## [1.2.7] - 2021-05-14
- Fixes on checkout module
- Fixes on marketplace module
- Fixes on categories module
- Fixes database connection not being closed sometimes
- Added content revisions module for live edit
- Added ability for menu module to return data
- Added svg support
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.6...1.2.7 "")


## [1.2.6] - 2021-04-29
- Fixes on the checkout module
- Fixes on the tags module
- Fixes on offers module
- Fixes on the package manager
- Fixes on link editor
- Added content revisions module for live edit
- Added webp support
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.5...1.2.6 "")


## [1.2.5] - 2021-04-15
- Fixes on the shipping module
- Fixes on the checkout module
- Fixes bug where needed to delete bootstrap/cache folder
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.4...1.2.5 "")


## [1.2.4] - 2021-04-12
- UI Fixes on mobile
- Live edit fixes  
- Fix CSS editor tools
- Fix Https not work properly with Full page cache
- Fix stats display bug for period more than year
- Added new translatable option fields 
- Added new default checkout page  
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.3...1.2.4 "")


## [1.2.3] - 2021-03-30
- Update to Laravel 8.3
- Fix Tags problem
- Fix Offer price 
- Fix Contact form bugs
- Fix Multilanguage bugs 
- Fix https bug behind proxy 
- Fix Live edit bugs linking to layout
- Fix Live edit bugs related to cloudflare caching
- Added new elements for live edit
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.2...1.2.3 "")


## [1.2.2] - 2021-03-12
- Added cached model queries
- Fix multilanguage module 
- Fix stats module styling
- Fix Currency display in order list
- Fix Search in Client
- Fix backup module to include mutli language 
- Fix Reloading pictures module loop  
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.1...1.2.2 "")


## [1.2.1] - 2021-03-05
- Added cached model queries
- Fix language translations and locales
- Fix design on the checkout popup
- Fix custom fields bugs
- Fix add order from admin
- Fix offers and discounted price
- Fix backup module
- Fix mail templates are now translatable
- Fix email sending on user registration
- Fix shop bugs related to quantity management
- Added client and address management for users
- Added support for more shipping providers
- Many other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.0...1.2.1 "")


## [1.2.0] - 2020-12-17
- Migrated to Laravel 8
- Refactored the internal code to Laravel style 
- New admin template
- Many other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.20...1.2.0 "")


## [1.1.20] - 2020-06-22
- Fix Module Menu Not working properly 
- Fix toolbar align on RTL 
- Fix some php 7.4 errors
- Fix center image does not work
- Fix Move layout up-down does not work
- Fix On paste two times it alerts false
- Fix Inline link editor with custom url must select the right section
- Fix Inserting modules with "small plus" does not record undo
- Fix on pages that require login, now redirects to the login form
- Fix Categories cannot be removed from post
- Fix problem with export/import Language file  
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.19...1.1.20 "")


## [1.1.19] - 2020-04-27
- Fix of Small plus popup 
- Layout spacing option is gone
- Paste in safe mode does not work
- Fix the design of the pick element tree
- Fix some php 7.4 errors
- Fix Paging in admin panel is gone
- Fix JS tree bugs
- Fix some bugs on the checkout 
- Fix Inline link editor with custom url must select the right section
- Added Dynamic text module
- Added Event mw.cart.checkout.order_paid 
- Added Recaptcha v2 and v3
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.18...1.1.19 "")



## [1.1.18] - 2020-03-13
- Update installation screen
- Update for Backup module
- Update for Visual editor module
- Update for Pictures module to be able to use media library
- Update for Acordion and Tabs module
- Update for Link editor
- Update for PDF module
- Update for Newsletter module
- Verious speed optimizations
- Many fixes on the live edit
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.17...1.1.18 "")



## [1.1.17] - 2020-01-24
- Added support for php 7.4
- Link editor is broken
- Fix icon picker
- Update iconsmind to woff2
- Alt text and description on image does not work
- Cant open module settings
- Crop image does not work in background image
- Enter key on last paragraph does not work
- Color picker default color is black
- Fixed Less compiler path resolution
- Fixed Less compiler path resolution
- Added ability to login user by token
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.16...1.1.17 "")


## [1.1.16] - 2019-12-19
- Fixes on multi language strings
- Fixes on mail templates
- Added uploader type field on template settings 
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.15...1.1.16 "")

## [1.1.15] - 2019-12-12
- Upload image does not work
- Custom fields module settings popup
- Modal is always moving to center
- Various RTL text issues
- Presets on module inside module
- Icon picker size was not removed from html element 
- Language entries with quotes was breaking JS messages
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.14...1.1.15 "")


## [1.1.14] - 2019-11-27
- Fix of category tree does not appear after 3 level
- Ability to change url of package manager
- $.ajax.done() was not avaible 
- Sub module settings handle open setting on parent module
- Change background picture does not work on old templates
- Put scroll on deep category tree
- Opening modal should close all handles submenus
- Issue with reset password
- Export orders to excel
- Email on new order is not editable
- Add page to menu is broken
- Tooltip must close on element change
- Added language choose method on install screen
- Added admin url setting on install screen 
- Added module install command from CLI 
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.13...1.1.14 "")


## [1.1.13] - 2019-10-25
- Fixes on mw.dialog 
- Fixes on Custom fields 
- Fixes on Calendar module in Live Edit mode
- Fixes on Modal mw.dialog.get(...) is not a function when in mw.dialog
- Fixes on Testimonials module select image does not work
- Fixes on UI in RTL languages
- Fixes on Import from CSV 
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.12...1.1.13 "")


## [1.1.12] - 2019-10-10
- Various live edit fixes
- cannot save "edit as HTML"
- Handle overlap problem ?
- Custom fields hidden field does not work
- Files browser add thumbnail size
- Extra tags on format of Heading
- Modal window scroll is missing
- Uploader in folder does not work
- Edit link Modal in small window
- Table editor is missing in live edit
- Jumping modal
- Shipping to country module in live edit
- Video module lazyload
- Modal horizontal scroll
- Fix toolbar align to be on left
- You must not to be able to write in icon
- Contact form module fixed
- Added option for user registration to be able to be approved by admin
- Added bank transfer payment method
- Added elements to be able to come from the active template
- Added custom fields module template support
- Added mail templates
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.11...1.1.12 "")


## [1.1.11] - 2019-09-11
- Various live edit fixes
- Fixes on the custom fields module to support templates 
- Fixes on the contact form module that was not saving in lists
- Fixes on the file browser module
- Fixes the text formating while elements has .safe-mode class 
- Fixes of the categories_images module to have more flexible selection 
- Fixes of the twitter feed and instagram feed module to cache the request 
- Fixes of accordeon module 
- Fixes of live edti toolbar  
- Added ability to change background image position 
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.10...1.1.11 "")


## [1.1.10] - 2019-08-02
- Various live edit fixes
- Fixes on the new modal window
- Fixes on categories module
- Fixes cart module 
- Added ability to export and import language entries 
- Added ability upload template 
- Added ability to use template framework classes in CSS grid 
- Added new module for unified template settings generated from config.php (beta) 
- Added background job queue (beta) 
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.9...1.1.10 "")


## [1.1.9] - 2019-08-02
- Overall stability fixes
- Implemented new modal window
- Implemented media library
- Implemented Less parser
- Finished the import export function
- Fix a lot of handles bugs
- Fix edit link dialog window
- Fix presets drop-down for new modal
- Fix some custom fields bugs
- Fix Mail setting in admin
- Ability to edit comment after post
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.8...1.1.9 "")


## [1.1.8] - 2019-07-04
- Accordeon and tabs modules does not work 
- Target doesn't work on menu
- Visual editor bugs
- Cant remove custom field value
- Small WYSWYG editor is not triggering change event
- Live edit handle is disappearing and hard to reach
- Added ability to add custom  link to #section
- Contact form possibility to have more input fields
- Contact form external API's integration
- Contact form possibility to add attachment to reply message
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.7...1.1.8 "")


## [1.1.7] - 2019-06-21
- Icons for "Live Edit" sidebar
- Visual editor bug fixes and impovememnts like padding editor, erc
- Added less parser and template settings from .less files
- dialog.js fixes
- Duplicate posts appear on default content
- Update popup
- $.ajax success callback
- Paging is missing in admin panel
- Insert image doesn't work
- New backup manager beta
- Added ability to set size of custom fields
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.6...1.1.7 "")


## [1.1.6] - 2019-05-21
- Module presets fix
- fix UI alignment of buttons
- Problem with the layouts
- Sub-module settings does not appear
- Update composer installers directory discovery logic
- Clear format is not working
- Add footer tags customization
- Fix version in Composer
- When you change date format can't save 
- Cant upload picture
- Tabs module in Dream template
- Accordeon modules does not work
- Fix categories display on posts module
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.5...1.1.6 "")


## [1.1.5] - 2019-05-02
- Sometimes the Dragging on Layouts doesn't work
- Fix flicking of handles
- Restore content in trash did't work
- Resizeable control outline misplaced on live edit
- There was class .element on all modules
- Format bug when you reformat after format 
- Format looses selection
- CSS editor can't select nodes
- Style visibility: visible was appearing on all elemennts
- Increase the pooling on is_logged function
- Other small fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.4...1.1.5 "")


## [1.1.4] - 2019-04-03
- Various live edit fixes
- Fix encryption key
- Color overlay does not work
- Comment publish/unpublish does not work
- Fix flicking of handles
- Jumping icon
- Fix grid button
- Handle overlaps toolbar
- Fix UI alignment of buttons
- btn-primary is broken
- Multiple pictures upload
- Fix undo issues
- CSS editor beta version
- Other small fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.3...1.1.4 "")


## [1.1.3] - 2019-02-28
- Added package manager 
- Fixes on the live edit 
- Live edit handles improvements 
- Live edit performance improvements 
- Improvements on newsletter and calendar modules
- Better touch screen support
- Better RTL support
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.2...1.1.3 "")


## [1.1.2.1] - 2018-12-14
- Maintenance release 
- Some fixes on the live edit 
- Custom CSS editor now saves properly
- Added compatibility with Gramarly and Naptha browser plugins


## [1.1.2] - 2018-12-06
- Major update 
- Many overall system improvements
- Drag and drop is stable now
- Introduction of new CSS framework for styling 
- New JavaScript components added
- New modules data management mechanism by JSON schema   
- Added "Coupon Code" module
- Added "Offers (Price Discount)" module
- A lot of bug fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.1...1.1.2 "")



## [1.1.1] - 2018-08-05
- Added: presets for modules 
- Added: gulp support
- Added: events calendar module
- Fix: bugs in the live edit
- Fix: some bugs in the modules parser  
- [see all changes....](https://github.com/microweber/microweber/compare/1.0.11...1.1.1 "")

 

## [1.1.0] - 2018-07-05
- Added: Changed the whole UI of admin panel
- Added: You can now drop full layouts
- Added: CSS editor
- Added: HTML editor
- Added: Site statistics
- Added: SEO features 
- Fix: Huge Live Edit impovements
- Fix: Parser bugs
- Changed: to MIT license 
- [see all changes....](https://github.com/microweber/microweber/compare/1.0.8...1.0.10 "")


## [1.0.8] - 2017-04-26

- Added: testimonials module
- Added: twitter feed module
- Added: teamcard module
- Added: tabs module
- Added: favicon support
- Added: google analytics support
- Added: 'before/after' module
- Added: PDF Module
- Added: calendar module
- Added: facebook page module
- Added: category_images module
- Added: parallax module
- Added: rating module
- Added: updated to laravel 5.4
- Added: Some JS libs
- Added: Some PHP libs
- Added: Paste from word button
- Added: setting to allow admin to login only from some IP's
- Added: captcha setting to login form
- Added: more registration and login settings
- Added: payment methods voguepay.com, przelewy24.pl, mollie, etc 
- Added: start depth setting for breadcrumb
- Added: ability to edit link text in the popup
- Added: search by custom fields basic functionality
- Added: translation for Arabic language
- Added: translation for Bahasa Indonesia
- Added: btn module now has skins
- Added: link parameter in `pages_tree` now can be a closure
- Added: JS validation engine
- Added: override method for options
- Added: verify email function for registered users
- Added: htmlpurifier library
- Added: notification_data field to the notification table
- Added: api_expose_user function
- Added: allow keyword search in joined table
- Added: option to require captcha on login
- Added: option to disable registration with disposable email address
- Added: layouts on in the toolbar now have title  shown
- Added: jquery UI version updated
- Added: new function is_cli()
- Added: module for content revisions
- Added: force_https setting to force the site to be on https
- Added: way to change the admin panel url from the config file #277
- Added: option to set max quantity per product order
- Added: drag & drop improvements
- Added: wrapping laravel classes
- Fix: mail sending error when escapeshellcmd() has been disabled for security reasons
- Fix: reflected xss reported by Kacper Szurek
- Fix: check file mime type if finfo function exists
- Fix: live edit fixes
- Fix: table edit  
- Fix: support for postgres 
- Fix: fix when .env file does not exist
- Fix: menu module bugs
- Fix: UI fixes
- Fix of:  #380, #395, #388, #378, #377, #373, #372, #369, #368, #362, #360, #357, #353, #352, #346, #340, #334, #298, #277, #225
- [see all changes....](https://github.com/microweber/microweber/compare/1.0.7...1.0.8 "")




## [1.0.7] - 2016-03-01

- Vendor: added Omnipay library
- Vendor: added coduo/php-humanizer library
- Vendor: added league/csv library
- Added: taxes support
- Added: caching with .htaccess on some folders
- Added: payment providers (Mollie,Stripe and Authorize.net)
- Added: taxes support
- Added: url support for categories
- Added: crud class
- Added: custom field type "property"
- Added: "max depth" setting to the categories module
- Added: Portuguese language
- Added: LinkedIn login
- Added: export orders to SCV
- Refactor: database, content manager, orders and shop classes
- Fix: general UI improvements
- Fix: security fixes
- Fix: bugfixes in custom fields
- Fix: bugfixes drag and drop
- Fix: thumbnail resize fix
- Fix: fixed dropdown bug when you click on the scroll
- Fix: mw.tooltip issues
- Fix: mw.modal issues
- Fix: sitemap and rss feed
- Fix: performance improvements when there are many categories
- Fix: cache files number reduced
- Fix: fix of notifications  
- Fix: fix of import module
- Various other fixes



## [1.0.6] - 2015-11-19

- Vendor: added Twig parser
- Added: taxes support
- Added: tabs module
- Added: twig parser to shop email template
- Added: content revisions (for new installs only)
- Added: data fields support for categories
- Added: country states class
- Added: .allow-drop css class on live-edit field to allow dropping inside modules
- Added: custom reset password email
- Added: custom register user email
- Refactor: cart moved to own class
- Refactor: orders moved to own class
- Refactor: content data fields moved to own class
- Refactor: content attributes moved to own class
- Fix: search function to exclude deleted content #313
- Fix: uploader makes all files lowercase
- Fix: live edit fixes on columns
- Fix: forms module export to excel
- Fix: Unzip was making all files lowercase
- Fix: URLs are now sequential
- Other fixes

## [1.0.5] - 2015-10-10

- Added: Custom content fields now can be added from the template config file
- Added: Session-less routes and Middleware
- Added: Various UI hooks
- Added: Support for custom data fields
- Added: Cache driver support (Files, APC)
- Added: Template class
- Added: Caching option for apijs and frontend assets
- Fix: create post/product now have parent page
- Fix: parser was outputting the same content in different edit fields
- Fix: module id is now generated with md5 instead with crc32
- Fix: is_home bug under sqlite
- Fix: install bug
- Fix: pqsql fix
- Fix: shipping to country bug with the select menu
- Fix: custom field default values were showing 0
- Fix: editor improvements
- Fix: menu reorder bug
- Fix: google fonts loading
- Fix: facebook login bug
- Fix: mw.front event was called before constants are defined
- Fix: bug in image cropping
- Fix: ConfigSave now replaces the storage_path
- Fix: shipping bug
- Fix:  undo is now working properly
- Fix: preg_replace bug
- Fix: icon picker
- Fix: save edit now uses MutationObserver
- Fix: category reordering
- Fix: live edit changes were not saved when editing an image
- Fix: pixum now caches the images
- Fix: drag and drop in row was appending the item on the wrong place
- Fix: upload image shows progress again
- Fix: resizable columns bug when no width is set
- Fix: redirect in views and modules was not working
- Fix: updated jquery-ui to 1.11.4
- Fix: captcha is now working on pages with multiple captchas
- Fix: upload file bug
- Fix: default controller's render logic now moved in the template class
- Many other fixes



## [1.0.4] - 2015-08-04
- Vendor: added Laravel HTML helper class
- Vendor: updated jquery ui
- Added: template manager class
- Added: XSS filter class
- Added: `artisan microwber:update` command
- Fix: security fix in uploader
- Fix: security fix user creation
- Fix: editing menus bug
- Fix: delete abandoned cart
- Fix: query pagination was skipping pages
- Fix: menu title was outputting html tags
- Fix: menu module bug when editing
- Fix: add to cart with quantity parameter
- Fix: cache now uses locale prefix
- Fix: updated raw SQL queries to use Eloquent
- Fix: redirect bug when using redirect from template
- Fix: UI in forms module in admin
- Fix: captcha validation when there are multiple catchas on one page
- Fix: module id was random when using module inside module
- Fix: other small bugs


## [1.0.3] - 2015-06-15

- Vendor: Updated Laravel to v5.0.33 (last version that supports php 5.4)
- Feature: Custom fonts support (only Google fonts for now)
- Feature: Color picker can set custom color from hash
- Feature: CSS Editor
- Feature: Icon picker
- Feature: Custom font size value can be set
- Fix: improvements in the live edit for Boostrap3 templates
- Fix: possible XSS in users
- Fix: fixes in shop orders management
- Fix: many other minor bugs


## [1.0.2] - 2015-06-01


- Added: get_category_items_count function
- Added: Some missing language entries
- Added: `sum` parameter to the `db_get` function
- Added: `connection_name` parameter to the `db_get` function
- Changed: replaced database class with database_manager
- Fix: shop currency function timeout
- Fix: modal initialization
- Fix: update check
- Fix: install problem under mysql 5.6
- Fix: drop in bootstrap columns
- Fix: improvements for bootsrap themes
- Fix: edit link from live edit
- Fix: cart shipping price display
- Fix: shipping cost per weight
- Fix: price bug
- Fix: shipping cost per weight
- Fix: locale now uses App::getLocale()



## [1.0.1] - 2015-05-12

- Vendor: added Omnipay for payments processing
- Vendor: Markdown provider
- Added: added option to change the currency sign position
- Fix: Custom environment name respects the `getenv` value
- Fix: Cache expiration
- Fix: Category delete
- Fix: Position db field is now converted to integer
- Fix: Delete categories bug
- Fix: Reorder categories bug
- Fix: Factored DB class
- Tests added
- Many other fixes

## [1.0.0] - 2015-02-16

- Moved to Laravel 5
