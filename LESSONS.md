# LESSONS.md — Correction Patterns & Guardrails

> Append-only. Newest at top. Review entries relevant to the current task before similar work begins.

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
