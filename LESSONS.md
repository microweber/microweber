# LESSONS.md — Correction Patterns & Guardrails

> Append-only. Newest at top. Review entries relevant to the current task before similar work begins.

---

## 2026-05-16 — Filament render hooks accumulate in registration order — first-registered renders leftmost

- **Pattern:** task-bcb327 AI-702 added a brand-mark anchor at `PanelsRenderHook::TOPBAR_START`. The natural mental model — "the LAST hook I register renders last (and therefore rightmost in a horizontal flow)" — is correct in some frameworks but wrong for Filament. Filament accumulates hooks at the same name in registration order and renders them in that order, so the **first**-registered hook renders **leftmost**. Initial draft registered the brand hook AFTER the existing `admin-top-navigation-actions` hook, expecting it to anchor the left edge; it actually rendered to the right of the existing actions.
- **Why it happened:** Render-hook ordering semantics aren't documented inline at the call site; the only signal is the rendering result, and that's only visible after a Webpack rebuild + browser refresh. Several rendering frameworks use last-registered-wins / last-registered-on-top conventions, so the bug is reachable from prior experience.
- **Prevention rule:** When adding a new Filament render hook at a name that already has other hooks registered, EXPLICITLY decide where it should sit in the visual order and register it at the correct position in the sequence (first call → leftmost, last call → rightmost). Pin the ordering in a contract test via `strpos($source, 'MARKER_A') < strpos($source, 'MARKER_B')` so future edits can't accidentally reorder. Reference: `tests/Feature/AdminBcb327AI702BrandMarkContractTest.php` `brand_mark_hook_registered_before_existing_topnav_actions`.
- **Applies when:** Adding any Filament render-hook registration to a panel provider that already has hooks at the same hook-name (TOPBAR_START / TOPBAR_END / SIDEBAR_NAV_START / etc.). Especially relevant for chrome-anchor hooks (brand, nav, status indicators).

---

## 2026-05-16 — Vue popover dismiss handlers MUST use capture phase, never default bubble

- **Pattern:** task-66ceca AI-701 PageChip popover initially registered its outside-click dismiss as `document.addEventListener('click', handleOutsideClick)` (default = bubble phase). Clicking outside the popover did not close it — testing with Playwright showed the listener was attached, but `stopPropagation()` calls inside Filament/Livewire/Alpine internals (mostly used for their own modal/dropdown dismissal) cancelled the bubble before it reached `document`.
- **Why it happened:** The default 3rd argument to `addEventListener` is `false` (bubble phase). For elements inside Filament's modal tree or Alpine's `x-on:click.away` regions, multiple downstream handlers call `e.stopPropagation()` for their own dismiss logic — perfectly legitimate from their POV, but it leaves the popover stuck open. Capture phase fires BEFORE the bubble chain, so it runs before any `stopPropagation()` can cancel it.
- **Prevention rule:** ANY Vue popover/dropdown/menu that uses `document.addEventListener('click', ...)` for outside-click dismiss MUST register with capture phase: `document.addEventListener('click', handler, true)` (3rd arg `true`). Same pattern for `keydown` ESC handlers if they need to win over inner handlers. Always remove the listener in `beforeUnmount` / `beforeDestroy` to avoid leaks; otherwise the handler outlives the component instance and fires on stale `this`. Pin in a contract test via `assertMatchesRegularExpression('/addEventListener\(\s*[\'"]click[\'"][\s\S]*?,\s*true\s*\)/', $source)`.
- **Applies when:** Authoring any Vue/Alpine popover, dropdown, dismissable card, or menu in `packages/frontend-assets/resources/assets/ui/`. The same rule applies to JS event listeners registered from a Blade `<script>` block where the surrounding page may contain Filament/Livewire/Alpine modals.

---

## 2026-05-16 — Tokens consumed in Teleport-to-body / Filament-portaled DOM MUST be `:root`-scoped

- **Pattern:** task-7326d6 AI-700 MainDrawer was a `<Teleport to="body">` Vue component that mounted at `document.body` root (outside the `.mw-live-edit-page` wrapper). First draft consumed ESE tokens (`--ese-surface`, `--ese-text`, `--space-md`, etc.) expecting them to resolve. `getComputedStyle(drawerEl).backgroundColor` returned the literal `unset` string — the drawer rendered with the browser default (transparent / black-on-white). Root cause: the project's ESE tokens are defined at multiple scopes; some are `:root`-scoped (resolve everywhere) but several `--ese-*` tokens were originally added inside `.mw-live-edit-page { ... }`, so they only resolve INSIDE that wrapper. Teleport-to-body DOM lives outside.
- **Why it happened:** Vue Teleport's purpose is to escape stacking contexts (z-index, overflow, transform), but as a side effect the teleported DOM also escapes CSS scope. Filament modals (`.fi-modal`), tooltips, popovers, and any portaled overlay component have the same issue. The bug is silent at desktop default theme because some tokens happen to be `:root` and others happen to fall back to inherited values that look approximately right.
- **Prevention rule:** When authoring a `<Teleport to="body">` Vue component, a Filament-portaled overlay, or any DOM that renders outside `.mw-live-edit-page`: (a) confirm every consumed `var(--token)` is `:root`-scoped by grepping the token definition (`grep -rn "--token-name:" packages/`); (b) include a literal fallback on every `var(--token, fallback)` as belt-and-braces (`var(--ese-surface, #fff)`); (c) document the scoping decision in the SHIP report + contract-test docblock (per SOUL #108 designer working contract); (d) browser-verify at dark theme too — `:root` tokens are overridden inside `html.dark`, so dark-theme dropout surfaces a different class of bug.
- **Applies when:** Authoring any new Vue Teleport / Filament modal / popover that consumes ESE or design-system tokens. Confirmed surfaces this session: AI-697 anchored picker, AI-700 MainDrawer, AI-701 PageChip popover.

---

## 2026-05-16 — Vue `:class` + `class` split for state-class triads — never combine static + dynamic in one map

- **Pattern:** task-8b10a3 AI-699 SaveButton first draft used a single `:class="{ 'btn btn-dark live-edit-toolbar-buttons mw-save-button': true, 'mw-save-button--has-changes': _dirty, 'mw-save-button--idle': !_dirty }"` map — bundling the static legacy classes (`btn btn-dark live-edit-toolbar-buttons`) and the always-true new class (`mw-save-button`) into a single combined string. The contract test `assertMatchesRegularExpression("/:class=\"[^\"]*'mw-save-button':\s*true/", $source)` then false-failed because the rendered class-binding string was `'btn btn-dark live-edit-toolbar-buttons mw-save-button': true` — the test was pinning a clean `'mw-save-button': true` and instead matched the longer combined key.
- **Why it happened:** Vue's `:class` API accepts both forms (object map vs concatenated string in the key), and the combined-key form looks more "compact" at edit time. Contract tests, however, expect each class to be pinnable independently — combining bundles legacy noise into the state class assertion.
- **Prevention rule:** SPLIT static legacy classes (back-compat hooks referenced by external code) onto the HTML `class="..."` attribute, and put dynamic state classes on the `:class` binding with one boolean key per class. Static `class="btn btn-dark live-edit-toolbar-buttons"` + dynamic `:class="{ 'mw-save-button': true, 'mw-save-button--has-changes': _dirty, 'mw-save-button--idle': !_dirty }"`. Cleaner separation; cleaner contract pinning; easier to grep for state class names.
- **Applies when:** Authoring any Vue component that mixes always-on legacy classes (Dusk-test-pinned IDs, CSS back-compat hooks) with new state classes (loading / dirty / active / disabled variants). Reference: `SaveButton.vue` final shape.

---

## 2026-05-16 — Sub-slicing pattern: ship `[ticket]a/b/c` when a multi-item ticket has hard dependencies

- **Pattern:** AI-698 ("compress live-edit toolbar to 9 elements / 48px / one row") had 4 sub-items: (1) device-preview → MwSegmented, (2) tools → ⋯ popover, (3) panel triggers → ☰ drawer, (4) lock toolbar height. Item 3 hard-depended on AI-700 (drawer consolidation — hadn't shipped; hamburger would have nothing to open). A single AI-698 ship would have been blocked or shipped with a broken hamburger button. Earlier in the session ESE 1.3 had hit the same shape (~14-component migration in one commit estimated at 600-900 LOC, defeating audit-trail granularity).
- **Why it happened:** Multi-item tickets bundle work that has different readiness states. Some sub-items are immediately shippable, some depend on adjacent tickets, some are scope-creep deferrals. Treating the bundle as atomic forces either "wait for everything" or "ship a broken middle state".
- **Prevention rule:** When recon shows a dispatched ticket bundles items with different dependency states, sub-slice the ticket into `[ticket]a` (immediately shippable, well-bounded), `[ticket]b` (paired with the dependency, ships when both are ready), `[ticket]c` (deferred until a separate dispatch lands). Each sub-slice gets its own commit + contract test + task-id marker. The SHIP report explicitly documents the sub-slicing rationale up-front. Examples shipped this session: ESE 1.3 → 1.3a (primitives + 1 consumer) + 1.3b (remaining 13 consumers, designer-accepted) — SOUL #99; AI-698 → a (height-lock, task-ec0c87) + b (item 3 hamburger, mutually-unblocked with AI-700 / task-7326d6) + c (items 1+2 deferred for a future dispatch). Mutual-unblock (b paired with the dep that needed b) is also a valid resolution when both are ready in the same cycle.
- **Applies when:** Recon of a dispatched ticket reveals 2+ sub-items with non-uniform dependency state. Apply sub-slicing BEFORE coding to keep commits small, contract tests focused, and audit trails meaningful. Document the sub-slice plan in the SHIP report's pacing section so PM/designer can accept or redirect.

---

## 2026-05-16 — Single-`:root`-token cascade is the cheapest global geometry change — pin with negative-lookbehind regex

- **Pattern:** task-ec0c87 AI-698a needed to shrink the Live Edit toolbar from 60px to 48px. Consumers exist in 5+ locations: `#toolbar` height, `#live-edit-frame-holder` `top` + `height: calc(100vh - var(--toolbar-height))`, AI-697 anchored picker `top: calc(var(--toolbar-height, 60px) + var(--space-xs))`, mobile media-query rules. The exhaustive cascade meant one `:root` variable change at `packages/frontend-assets/resources/assets/ui/css/index.css` replaced 5+ per-template patches. Contract-test pinning `assertMatchesRegularExpression('/--toolbar-height:\s*48px/', $source)` initially false-matched unrelated tokens (`--layouts-dialog-toolbar-height`, `--toolbar-static-height`); fixed via negative-lookbehind `(?<![-a-z])--toolbar-height:\s*48px`.
- **Why it happened:** The first instinct on a UX request "make the toolbar shorter" is to grep for `#toolbar { height` rules and patch each — but `--toolbar-height` had been defined as a `:root` token in a prior cycle, so all consumers already read it. Recon-first paid off: a single value change cascaded exhaustively. The regex false-match was a separate gotcha — token-name suffixes (`-toolbar-height` family) all match `--toolbar-height` if anchored loosely.
- **Prevention rule:** Before patching per-consumer rules for a global geometry change (toolbar height, sidebar width, modal max-width, etc.), GREP for a `:root` variable that already drives them: `grep -rn "^\\s*--[a-z-]*<concept>" packages/frontend-assets/resources/assets/ui/css/`. If one exists, change it once and verify the cascade with browser DevTools' computed-styles panel. When pinning the value in a contract test, use negative-lookbehind to avoid false-matching sibling tokens: `'/(?<![-a-z])--toolbar-height:\s*48px/'`. Cite the cascade in the SHIP report so reviewers can verify the impact surface.
- **Applies when:** Any UX change that affects a global geometric value (heights, widths, padding) consumed by multiple components. ESPECIALLY when reading-from-`:root` is already an established pattern in the affected stylesheet.

---

## 2026-05-16 — Single-source-of-truth grep before shipping a new state indicator

- **Pattern:** Before shipping AI-699 (SAVE button "has changes" pulse), I ran `grep -rn 'unsaved\|dirty\|has-changes\|Saved\|Unsaved' packages/frontend-assets/ src/MicroweberPackages/LiveEdit/` to confirm zero redundant indicators existed. Result: clean — no other element in the toolbar / canvas / status bar showed dirty state. Without that check the ship would have added a new dirty-state indicator alongside (potentially) hidden or stale older ones, leaving users with two surfaces communicating the same state — the exact "designer L3.2 fold" the designer asked me to verify.
- **Why it happened:** Status indicators are easy to accumulate over time as agents add affordances without auditing the existing UI. Each addition feels harmless in isolation; the cumulative result is multiple competing signals.
- **Prevention rule:** BEFORE adding a new state indicator (loading, dirty, active, error, success badge / dot / pulse / label), grep the codebase for prior indicators of the same state across `packages/frontend-assets/`, `packages/microweber-filament-theme/`, `src/MicroweberPackages/LiveEdit/`, and `Templates/`. If a redundant indicator exists: either remove or repurpose it as part of the same commit. Document the grep result in the SHIP report (positive "zero redundant found" or negative "found and removed N redundant indicators").
- **Applies when:** Authoring any new visible status indicator — particularly dirty / unsaved / has-changes / loading / saving badges on toolbars, save buttons, form footers, panel headers.

---

## 2026-05-16 — `/* … */` block comments inside an HTML attribute value terminate the attribute at the first embedded `"`

- **Pattern:** task-968a71 first-attempt at the AI-692 two-group Add-Content modal embedded a block comment in the `x-data="..."` Alpine attribute that contained prose like `/* typing "page" hides the "On this page" group header */`. The HTML attribute parser doesn't know the `"` is inside a JS comment — it sees the first `"` and closes the attribute. Result: the entire x-data block was silently truncated, the page paint succeeded, and Alpine then threw a cascade of `q is not defined` / `hasVisibleCardsInGroup is not defined` / `resultAnnouncement is not defined` ReferenceErrors. The bug was invisible until DevTools console was opened.
- **Why it happened:** Block comments inside HTML attribute values look fine in editors with JS syntax highlighting because the editor treats the attribute body as JS — but the browser's HTML parser runs first and only knows about `"`. Any character with parser-level meaning (here `"`, but `</` is the same family) is unsafe in attribute prose, regardless of language wrapping.
- **Prevention rule:** Inside any HTML attribute value (`x-data`, `x-init`, `onclick`, etc.), NEVER write `/* … */` block comments containing literal `"`. Use `//` single-line comments only, and keep them quote-free. The same rule applies to embedded `</script>` strings — never reproduce a parser-meaningful sequence in prose inside that parser's scope. Reference: regression-guard test `comment_block_uses_single_line_comments_only_in_xdata` in `AddContent968a71AI692TwoGroupLayoutContractTest.php` asserts no `/*` appears inside `x-data="..."`.
- **Applies when:** Authoring any Alpine `x-data` / `x-init` / `x-on:*` block longer than ~5 lines, especially when the block contains JS comments describing the helper's behaviour. The PHP docblock variant: literal `*/` inside docblock prose closes the docblock — express as words ("slash-star ... star-slash") rather than characters.

---

## 2026-05-16 — Tailwind v4 uses native CSS `translate` property — independent of `transform`

- **Pattern:** task-02760c (Live Edit sidebar menu invisible after hamburger click) hit a silent regression where the project's `.fi-sidebar { transform: translateX(-100%) !important }` override appeared to do nothing. DevTools confirmed `transform: matrix(1, 0, 0, 1, 0, 0)` (identity!) but `getBoundingClientRect().x === -280`. Root cause: Tailwind v4 compiles utilities like `-translate-x-full` to the **native CSS `translate` property** (`translate: -100% !important`), NOT to `transform: translateX(...)`. CSS `translate` is its own property — independent of `transform` — and the Tailwind utility wins because the project override never touches it.
- **Why it happened:** The `transform: translateX(...)` mental model is the Tailwind v3 / pre-2022 CSS shape; Tailwind v4 adopted the newer CSS individual-transform properties. Project-side overrides written against v3 muscle memory silently miss the v4 generated CSS.
- **Prevention rule:** When defeating any Tailwind v4 translate/rotate/scale utility, reset BOTH the individual property AND the composite `transform`: `translate: none !important; transform: translateX(-100%) !important;` (also `rotate: none !important;` / `scale: none !important;` as applicable). Validate with `getComputedStyle(el).translate` not just `.transform`. Always reproduce at the failing viewport with Playwright + `evaluate(() => getBoundingClientRect())` before declaring the override correct.
- **Applies when:** Any project-side CSS attempting to override a Tailwind v4 utility that animates element position. Common spots: Filament sidebar/topbar visibility toggles, modal slide-in/out, drawer opens.

---

## 2026-05-16 — When refactoring a code section, carry forward the prior task's source-comment marker

- **Pattern:** task-968a71 AI-692 refactored the `@foreach($actions as $action)` card grid into two `<section>` wrappers, but the rewrite dropped the `task-2026-05-16-cdeefd` marker that AI-691 had embedded in the card-section docblock. The audit-grep rule "every UX/UI fix embeds task-YYYY-MM-DD-XXXXXX in (a) commit, (b) test docblock, (c) source-side comment" was broken across blade — the original task became un-greppable from the new source.
- **Why it happened:** The refactor scope was "split into two sections", not "preserve every marker". Mechanical refactors that move/replace blocks tend to inherit only the structural intent of the rewrite, not the metadata of the rewritten code.
- **Prevention rule:** Before deleting or rewriting a code block that carries a `task-YYYY-MM-DD-XXXXXX` marker, grep the block for prior markers and re-embed them in the new block's docblock. If the new task adds its own marker, both markers should appear in the new block (the contract test for the new task can assert both). Pattern: "task-AAAA-MM-DD-NNNNNN / task-BBBB-MM-DD-MMMMMM" comma-separated in the docblock header.
- **Applies when:** Any refactor that replaces a substantial source block (Vue component sections, blade `<section>` wrappers, PHP method bodies, CSS rule groups). Especially relevant when ship reports cite multiple task IDs touching the same surface in close succession.

---

## 2026-05-16 — Contract-test regex `[^)]+` cannot cross parens that appear inside lambda bodies

- **Pattern:** task-968a71 AI-692's `actions_carry_group_key_in_php` test initially used `'/'action'\s*=>\s*'addToCurrentPageAction'[^)]*'group'\s*=>\s*'primary'/'` to assert that an action's `'group' => 'primary'` key appears within the same action array. The regex false-failed on every match because PHP lambda bodies in adjacent actions (e.g. `fn($a) => ($a['group'] ?? 'secondary') === 'primary'`) contained inner parens; the `[^)]*` class refused to cross any `)`, so the match-window terminated before reaching the `'group'` key.
- **Why it happened:** Negative character classes feel safer than `.*?` but they over-constrain when the body contains the excluded character for benign reasons. PHP lambdas, arrow functions, ternaries, and method chains all sprinkle parens inside array literals.
- **Prevention rule:** When asserting that two source patterns co-occur within the same array/block, use multiline-friendly `.*?` (non-greedy) with the `s` modifier — NOT `[^X]*` — unless `X` truly cannot appear in any plausible body. Example: `'/'action'\s*=>\s*'addToCurrentPageAction'.*?'group'\s*=>\s*'primary'/s'`.
- **Applies when:** Writing PHPUnit `assertMatchesRegularExpression` against source code that contains nested syntax (parens, brackets, braces). Reference: `AddContent968a71AI692TwoGroupLayoutContractTest::actions_carry_group_key_in_php` final shape.

---

## 2026-05-16 — `!important` written to defeat a non-loaded stylesheet is dead code (safe to drop)

- **Pattern:** task-1b5604 ESE slice 1.2 inherited a six-rule `!important` fortress on `.v-slider-track__background` / `__fill` / `__surface` from a prior cycle that was guarding against Vuetify's slider styles overriding the project's geometry. Recon found that Vuetify CSS is NOT loaded in the parent Microweber admin window (only inside iframe/canvas surfaces that bundle it explicitly per the `vuetify-slider-in-mw-admin` skill). The `!important` had no real specificity competitor; dropping it and rewriting with token-driven geometry was a pure simplification win.
- **Why it happened:** Defensive `!important` accumulates over time as agents work around symptoms without recon-ing the actual cascade. Each agent inherits the fortress and adds their own `!important` to "make sure" — the original adversary is forgotten.
- **Prevention rule:** Before reproducing or extending an `!important` fortress, run a grep across the rendered HTML's actual stylesheet loads (network panel + `getComputedStyle` showing which rule wins) to confirm the adversary stylesheet is actually loaded. If it isn't, the fortress is dead code — drop the `!important` and rewrite with normal specificity + document the surviving cases inline. Three `!important` rules survived in slice 1.2 with explicit one-line justifications: Vuetify-inline-positioning, hidden a11y input, fight with `live-edit-input.css`.
- **Applies when:** Touching any CSS block with 3+ `!important` declarations on the same component. Recon the actual cascade before assuming the fortress is necessary; the cost of leaving dead `!important` in place is that future agents inherit the wrong mental model of the specificity environment.

---

## 2026-05-16 — "As they were before" feedback usually means delete prior customization, not add more

- **Pattern:** task-cfef17 ("put dark/light back in the submenu") arrived after AI-168 had previously injected `<x-filament-panels::theme-switcher />` into the `TOPBAR_END` render hook to make the theme switcher more visible. The correct fix was to DELETE the 25-line `FilamentView::registerRenderHook(PanelsRenderHook::TOPBAR_END, ...)` block in `MicroweberFilamentTheme.php` and let Filament's stock user-menu dropdown render the switcher per `vendor/filament/.../user-menu.blade.php` lines 112-116. A wrong-but-tempting alternative would have been to add NEW CSS/JS to "make the user-menu version more visible" — layering customization on customization.
- **Why it happened:** Once the user has experienced a prior version of the UI, "as they were before" is a precise specification. The framework's default state is reachable by removing agent-added code rather than by reproducing the default via new code. Adding more code to mimic a default is strictly worse than deleting the override that hides it.
- **Prevention rule:** When user feedback says "back to how it was", "as before", "the way it used to be", or names a prior behaviour positively, FIRST check git history (`git log --oneline -- <file>` and `git blame` on relevant lines) for the customization that overrode the default. Strongly prefer deleting the override over adding compensating code. Commit message should explicitly reference the prior ticket/commit that introduced the override (e.g. "revert AI-168 TOPBAR_END injection").
- **Applies when:** Any task whose description references prior UI state ("before", "old", "previous", "used to"), restores a framework default, or asks the agent to "put X back". Especially relevant for Filament render hooks, CSS overrides, and Vue template injections that override stock behaviour.

---

## 2026-05-16 — Grep `packages/` for a new CSS class name before adopting it

- **Pattern:** task-fd0d1d added a `.mw-empty-state-cta` button to a Filament resource empty state. The element rendered as a dark-gray pill instead of a primary-blue button. `getComputedStyle` revealed `background: rgb(26, 31, 43)` instead of the expected `#0d6efd` — a same-named rule from `DashboardEmptyStateWidget` (in `microweber-theme-v3.scss`) was winning the cascade because it ships in a later-loaded bundle.
- **Why it happened:** Two unrelated bundles can both define rules for the same class name without lint warnings. The runtime cascade picks whichever bundle loads last, which depends on entry order rather than file structure.
- **Prevention rule:** Before naming a new CSS utility/component class, grep the entire `packages/microweber-filament-theme` and `packages/frontend-assets` trees for the proposed name. If any match exists in a different bundle, pick a scope-prefixed alternative (`mw-table-*`, `mw-dashboard-*`, `mw-le-*` etc.). Pin the chosen name in the contract test so regressions surface immediately.
- **Applies when:** Adding any new `mw-*` CSS class for a button, badge, card, callout, or other reusable widget shipped in any package-level bundle.

---

## 2026-05-16 — Mobile UX fixes belong in `live-edit-mobile.css`, not the desktop file

- **Pattern:** task-74c5f5 reported "menus still overlapping" in Live Edit. Initial fix targeted `#toolbar { flex-wrap: nowrap; overflow-x: auto; }` in `live-edit-classes.css` and the user's screenshot still reproduced. Re-measurement at the user's reported viewport (~560×440) showed the rule was being shadowed: `.mw-(admin-)?live-edit-page #toolbar` inside `live-edit-mobile.css`'s `@media (max-width: 768px), (pointer: coarse)` block declares `flex-wrap: wrap !important`, beating the bare `#toolbar` rule on specificity AND at narrow viewports only.
- **Why it happened:** The repo splits Live Edit toolbar CSS across two files — `live-edit-classes.css` (desktop default) and `live-edit-mobile.css` (mobile / pointer-coarse). The mobile file's selector is more specific (`.mw-live-edit-page #toolbar` > `#toolbar`) AND scoped inside a `@media` query, so fixes to the desktop file are invisible on mobile.
- **Prevention rule:** When a UX bug is screenshot-attached and the screenshot shows a mobile/narrow viewport, FIRST `Grep packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css` for the affected selector. The mobile-specific rule almost certainly exists and is the one that needs editing.
- **Applies when:** Any Live Edit toolbar or canvas layout fix where the symptom is reported on a narrow viewport or mobile device.

---

## 2026-05-16 — Grep `tests/Browser/` before deleting Live Edit toolbar DOM elements

- **Pattern:** task-3ae87c merged the user-menu items into the 3-dots `ToolbarToolsDropdown.vue` to eliminate a redundant dropdown. The initial approach removed `<div id="user-menu-wrapper">` and `<button id="toolbar-user-menu-button">` entirely from `Toolbar.vue`. `tests/Browser/AdminLiveEditDropdownAndButtonsTest.php` Dusk test then failed — it asserts the presence of those IDs, and `SettingsCustomize.vue:651` also references them.
- **Why it happened:** Toolbar element IDs are external API surface — Dusk tests, JS event listeners, and CSS rules across multiple files reference them. Removing the element breaks unrelated suites silently until the Dusk run fails much later in CI.
- **Prevention rule:** Before deleting any toolbar DOM element, grep `tests/Browser/`, `packages/frontend-assets/resources/assets/ui/`, and `packages/microweber-filament-theme/resources/assets/css/microweber/` for its `id`, `class`, and any other selectors. If any reference exists, keep the element with `style="display: none;"` instead — preserves the contract while removing the visual rendering. Add a source comment explaining the back-compat reason.
- **Applies when:** Restructuring or consolidating any element in `packages/frontend-assets/resources/assets/ui/components/Toolbar/` — particularly user-menu, hamburger, undo/redo, save, or settings-related elements.

---

## 2026-05-16 — Reproduce UX bugs at the screenshot's actual viewport before declaring "can't reproduce"

- **Pattern:** task-74c5f5's initial Playwright measurement at the default 1440×900 viewport showed no toolbar overlap — the screenshot showed clear overlap at narrow widths but the dev tools said everything fit. Wrong conclusion: "the prior fix worked". Correct: the user took the screenshot on a narrow viewport (estimated ~560×440 based on visible content scale).
- **Why it happened:** Mobile-specific CSS (`@media (max-width: 768px)`) only activates at narrow widths; a desktop measurement gives a different layout entirely.
- **Prevention rule:** When a UX bug arrives with a screenshot, estimate the viewport from visible cues (font scale, breakpoint behaviour, mobile-specific UI) BEFORE measuring. Resize Playwright to that viewport with `browser_resize(width, height)` as the first verification step. Only after reproducing at the correct viewport do measurements have any diagnostic value.
- **Applies when:** Any UX/CSS bug arriving with a screenshot — especially mobile or "the X is overlapping the Y" reports where the layout problem is media-query-gated.

---

## 2026-05-14 — Filament `Action::color('success')` size=lg requires `!important` to enforce min-height

- **Pattern:** Wrote `body.fi-panel-checkout button.fi-btn.fi-color-success { min-height: 44px; }` in `mobile-touch.css` for the AI-517 "Place Order" button. The rule was ignored and the button stayed at ~42px on mobile, 2px short of the 44px floor.
- **Why it happened:** Filament `Action::make(...)->color('success')->size(ActionSize::Large)` (the canonical place-order pattern in `CheckoutResource`) emits inline styles at the size=lg layer that beat normal CSS selectors. The `:hover`/`:focus` rules cascade fine but `min-height` is one of the inline-overridden properties.
- **Prevention rule:** When targeting `.fi-color-success` or any `size=lg` Filament button, add `!important` to the `min-height` declaration: `button.fi-btn.fi-color-success { min-height: 44px !important; }`. Pin the rule in a contract test that asserts the `!important` token is present.
- **Applies when:** Any touch-target / sizing rule against a Filament Action button rendered with a non-default size (`size=lg`, `size=sm`) — particularly success/danger colour variants on checkout/wizard surfaces.

---

## 2026-05-14 — Contract-test source slices must bound to the rule's closing brace, not EOF

- **Pattern:** Wrote `Ai518CustomerTouchTargetContractTest::contactUsBlock()` as `substr($css, strpos($css, 'AI-518'))` — slicing from the marker to end-of-file. The downstream assertion `assertStringNotContainsString('@media', $block)` then false-failed because the slice picked up unrelated `@media` declarations *later* in `public-touch.css`.
- **Why it happened:** The slice's intent was "just the AI-518 rule body" but the implementation grabbed everything from the marker onwards. Whenever the file grows below the marker, the slice grows too, and assertions about what is *absent* from the slice start failing for unrelated reasons.
- **Prevention rule:** Bound the slice to the rule's closing brace, not EOF: `$end = strpos(substr($css, $start), "\n    }\n"); $block = substr($css, $start, $end + 6);`. For multi-rule blocks, bound to the closing brace of the outer `@media` (`"\n}\n"`). Validate with both a present-assertion AND an absent-assertion to catch over-large slices early.
- **Applies when:** Writing PHPUnit contract tests that slice a CSS/JS/PHP source file by a marker comment and assert structural facts (especially absence assertions like "no `@media` inside this rule").

---

## 2026-05-14 — Contract tests that grep source for selectors must avoid quoting the selector in their own comments

- **Pattern:** Drafted an `Ai517CheckoutTouchTargetContractTest` guard that asserted "`.fi-modal-window .fi-form` selector does NOT appear in the AI-517 block" — but included that exact literal selector inside a prose comment of *another* test method. When the second test ran its own self-search guard, it matched the first test's comment and false-failed.
- **Why it happened:** Test files are part of the source tree. A guard that searches the source for a literal token will match wherever that token appears — including inside its own file's prose comments.
- **Prevention rule:** When a contract test asserts the *absence* of a specific selector or token from source, use non-literal phrasing in any nearby prose comment (e.g. write "the modal-window form-scoped selector" instead of `.fi-modal-window .fi-form`). Alternatively, search a subset of files that explicitly excludes the test file via path filter.
- **Applies when:** Authoring guard assertions in contract tests, especially negative assertions (`assertStringNotContainsString`, regex non-matches) over file-system reads.

---

## 2026-05-14 — `{{ }}` in VitePress markdown prose breaks the SSR build

- **Pattern:** Wrote `{{ thumbnail() }}` and `{{ var }}` in inline backticks and prose inside `docs/adr/0001-helper-layer-security.md`, `docs/modules/page/troubleshooting.md`, and `docs/modules/product/troubleshooting.md`. `npm run docs:build` failed with `TypeError: _ctx.thumbnail is not a function` (and friends).
- **Why it happened:** VitePress passes markdown to Vue's SSR pipeline. Vue treats `{{ ... }}` as an interpolation expression *everywhere except inside fenced ```code blocks```. Inline backticks (`` `{{ foo }}` ``) and prose look like raw markdown but Vue still parses them.
- **Prevention rule:** When you need `{{` in docs prose or inline backticks, escape the opening braces as `&#123;&#123; expression }}`. Fenced ```code blocks``` are safe and require no escape. Run `cd docs && npm run docs:build` after editing any docs page to catch new collisions early.
- **Applies when:** Authoring or editing any markdown file under `docs/`, especially documentation about Blade, Vue, or Filament where `{{ }}` is a natural example syntax.

---

## 2026-05-14 — `cd packages/<pkg>` leaves the shell CWD in that subdir for the rest of the dispatch

- **Pattern:** Ran `cd packages/frontend-assets && npm run build` to rebuild a package, then ran `git add docs/foo.md` in the next Bash call. The git command failed with `pathspec 'docs/foo.md' did not match any files` because the working directory was still `packages/frontend-assets/`.
- **Why it happened:** The Bash tool's working directory persists across calls. A bare `cd <subdir>` shifts it for every subsequent invocation in the same session.
- **Prevention rule:** Either (a) use absolute paths in every command, or (b) prepend `cd /home/headless/Documents/GitHub/microweber && ...` to commands that must run from the repo root. Never leave the shell parked in a subdir after a build step.
- **Applies when:** Running per-package builds, VitePress builds, or any tool that lives under a subdirectory followed by repo-root operations (git, file edits, etc.).

---

## 2026-05-14 — `ignoreDeadLinks` regex must accept the `./` prefix from nested pages

- **Pattern:** Added `/^\.\.\/payment\/index$/` to `docs/.vitepress/config.js::ignoreDeadLinks`. The VitePress build still flagged the link as broken because nested pages emit `./../payment/index` (with `./` prefix).
- **Why it happened:** VitePress normalises relative links differently for nested pages — anchoring with `^` rejects the prefixed form.
- **Prevention rule:** Don't anchor `ignoreDeadLinks` regex patterns with `^`. Whitelist by sub-path (e.g. `/\.\.\/payment\/index$/` or `/payment\/index$/`). Verify with a full `npm run docs:build` run, not just spot checks.
- **Applies when:** Tuning the `ignoreDeadLinks` array in `docs/.vitepress/config.js`.

---

## 2026-05-14 — Contract-test regexes need non-greedy `.*?` for JS function bodies containing `)`

- **Pattern:** Wrote a contract test asserting an addEventListener call existed in JS source: `'/document\.addEventListener\(\s*\'click\',\s*function\(e\)\s*\{[^)]+\},\s*true\s*\)/s'`. The test failed even though the JS was present.
- **Why it happened:** `[^)]+` excludes any `)` character, but the function body legitimately contains parentheses (method calls like `e.target.closest('.fi-form')`). The character-class was the wrong tool.
- **Prevention rule:** When matching a multi-line JS function body, use the non-greedy `.*?` with the `s` flag: e.g. `'/document\.addEventListener\(\s*\'click\',.*?\},\s*true\s*\)/s'`. Reserve character-class exclusions for genuinely-rare characters.
- **Applies when:** Writing PHPUnit contract tests that grep JS or PHP source for structural patterns (assertions about presence of a particular call shape).

---

## 2026-05-14 — Cache-staleness methods must not short-circuit with unconditional returns

- **Pattern:** `Modules/Sitemap/Http/Controllers/SitemapHelpersTrait.php::needToUpdateSitemap()` had `return true;` at the top of the method, bypassing the 3-hour TTL check that lived in the rest of the function body. Every sitemap request triggered a full rebuild.
- **Why it happened:** Likely a debug-aid (`return true;`) that was committed and forgotten. The dead code below it was still maintained, masking the regression.
- **Prevention rule:** Cache-staleness gates must always evaluate their real conditions (file existence → readability → age). Pin the behaviour with a contract test that fails if any unconditional early-return is reintroduced. Document the TTL in the method's PHPDoc with a cross-link to the usage docs.
- **Applies when:** Working with any `needToUpdate*`, `isStale*`, `shouldRebuild*` method or other cache-gate logic.

---

## 2026-05-13 — Append to CSS files with the `Edit` tool, not Bash heredoc

- **Pattern:** Used `cat >> file <<'EOF' … EOF` to append a CSS block to `packages/.../mobile-touch.css`. The result was a mid-stream duplication: an unrelated rule near line 781 was partially repeated and the entire new block landed twice. The Webpack build failed on the duplicated brace.
- **Why it happened:** Bash heredoc appends through the shell tooling have produced this corruption in this repo more than once. The exact mechanism is not pinned down, but the failure mode is reproducible enough to ban the technique.
- **Prevention rule:** Always append to existing CSS (and ideally any other source) using the `Edit` tool: read the file, set `old_string` = the existing file tail, set `new_string` = the existing tail + new block. The `Edit` tool gives exact-match guarantees that heredoc does not.
- **Applies when:** Adding rules to any `.css`/`.scss` file under `packages/`, `src/MicroweberPackages/`, or `Modules/`.

---

## 2026-05-13 — Do not rely on a global `[x-cloak] { display: none }` rule

- **Pattern:** Added `x-cloak` to an Alpine-controlled empty-state element expecting the standard "hide until Alpine boots" behaviour. The element still flashed its `x-text` literal (`""`) on first paint.
- **Why it happened:** The project does not ship a global `[x-cloak] { display: none }` rule. `x-cloak` is a CSS-driven feature; without the rule, the attribute is inert.
- **Prevention rule:** For elements that must be hidden until Alpine has bound their state, use inline `style="display: none;"` as the default and let Alpine `x-show` toggle them. Belt-and-braces — works whether or not `[x-cloak]` is defined.
- **Applies when:** Any Blade view using Alpine `x-data` / `x-show` / `x-text` that has a visibly-different "uninitialized" state.

---

## 2026-05-13 — Empty-state copy must be static, not echo the user's query

- **Pattern:** Wrote `"No results for "<query>""` in an Alpine-bound empty state. Pre-Alpine paint rendered the literal `""` and looked broken.
- **Why it happened:** Alpine renders once before binding; user-typed values are unavailable on first paint. Combined with the missing `x-cloak` global rule, the unbound expression briefly leaks.
- **Prevention rule:** Keep empty-state strings static (e.g. "No content types found."). If the query value is genuinely needed, gate the entire empty-state block behind `x-show="..."` and inline-hide the wrapper.
- **Applies when:** Authoring Alpine-driven empty states for search/filter UIs (pickers, list filters, dropdown selectors).

---

## 2026-05-13 — Refactors without test content stay deferred

- **Pattern:** Considered sweeping 38 `text-align: left/right` and 21 `margin-left/right` rules to logical-property equivalents (`start`/`end` / `margin-inline-*`) as part of AI-293 RTL work. Did not ship.
- **Why it happened:** No RTL test content exists in the repo. Without a way to verify visually, the sweep risks LTR regressions on intentional left-aligned elements (data columns, label rows, mixed-mode contexts).
- **Prevention rule:** Bounded slice + contract test is the dominant winning pattern. Large mechanical refactors require either fixture data to verify against or an explicit dispatch from the PM. Flag the concern in the ship report and stop.
- **Applies when:** Any "rename across N files", "swap property across M rules", or other mechanical refactor where the only verification is "tests still pass" (which proves nothing about intent-bearing values).

---

## 2026-05-13 — Filament modal cancel API is `modalCancelActionLabel`, not `modalCancelAction(false)`

- **Pattern:** Wrote `->modalCancelAction(false)` intending to relabel the cancel button. Filament v5 rejected the call.
- **Why it happened:** Misremembered the API. `modalCancelAction(false)` *hides* the cancel button entirely (and may not be the v5 spelling).
- **Prevention rule:** To customize the label use `->modalCancelActionLabel('Cancel')`. To suppress the button use `->modalCancelAction(false)` only if v5 actually supports that signature on the action type — verify in `vendor/filament/` first.
- **Applies when:** Configuring Filament `Action` modals in any admin or checkout panel.

---

## 2026-05-13 — Window-event bridge handler names must match on both Live Edit surfaces

- **Pattern:** Added `liveEditInsertLayoutRequest` dispatch in `AdminLiveEditPage.php` without registering a matching listener in `iframe-page.blade.php`. The event fired and went nowhere.
- **Why it happened:** The two-surface architecture means each verb requires one dispatcher + one listener. Easy to add only the half that the current edit touches.
- **Prevention rule:** When adding a new admin↔iframe verb, edit BOTH `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php` and `src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php` in the same change. Grep for the verb name in both files to confirm both sides exist.
- **Applies when:** Any change that adds, renames, or removes a `CustomEvent` verb in the Live Edit communication bridge.

---

## 2026-05-13 — Built bundles win — always rebuild after touching `packages/<pkg>/resources/`

- **Pattern:** Edited a Vue SFC in `packages/frontend-assets/resources/assets/ui/components/Toolbar/` and verified visually before running the build. The dev server kept serving the prior bundle and the "fix" wasn't actually applied during the verification round.
- **Why it happened:** The Microweber runtime loads built JS/CSS bundles, not raw source. Source edits are invisible until rebuilt.
- **Prevention rule:** After any edit under `packages/<pkg>/resources/**`, run that package's build (`cd packages/<pkg> && npm run build`) BEFORE verifying or reporting. Pin a Memory rule: `feedback_always_build`.
- **Applies when:** Touching any `.vue`, `.scss`, `.css`, `.js`, or `.ts` file inside any subdirectory of `packages/`.
