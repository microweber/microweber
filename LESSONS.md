# LESSONS.md — Correction Patterns & Guardrails

> Append-only. Newest at top. Review entries relevant to the current task before similar work begins.

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
