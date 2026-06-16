# Parser Changes 2 — Review & Eval Cycle

## TL;DR

The most important aspects to take into account are:

- Requirement 1 — Split the `ParserProcessor` monolith into small, single-responsibility,
  unit-testable helper classes under `ParserHelpers/`.
- Requirement 2 — Keep behaviour identical (no regression in the live render path: module
  ids, edit-field scoping, script/textarea/input protection).
- Requirement 3 — Fix the documented bug catalogue (digit-in-attr-name, `>`/`<` inside a
  quoted value, escaped quotes, unquoted-value trim, empty-module placeholder leak,
  `select`/`input`/`pre`/`optgroup`, HTML comments, Blade `{{-- --}}` comments).
- Requirement 4 — Preserve the exact module-id generation rules (base `module-<type>`,
  per-scope `--N` counters, `rel=content/inherit` content-id suffix, `rel=global` no suffix,
  custom `id` passthrough, parent-field prefixing).
- Requirement 5 — Test-first: a separate test per bug, plus a thin orchestrator
  (`LayoutProcessor`) that wires the helpers and can replace the monolith.

The diff satisfied the requirements because…
- R1 ✅ — 7 classes added (`TagLexer`, `AttributeParser`, `ModuleIdAllocator`,
  `ContentProtector`, `ModuleRenderer`, `EditFieldExtractor`, `LayoutProcessor`).
- R2 ✅ — the 4 existing live-parser integration tests (`ModuleParseTest`) still pass, and
  a Playwright pass over Home/`/shop`/`/blog`/`/my-blog-post`/`/contact-us` shows 0 placeholder
  leaks, 0 console errors, 73 modules rendered on Home; `/shop`'s 2 raw layout tags are
  identical with the diff stashed (pre-existing, not caused here).
- R3 ✅ — verified by 152 helper unit tests and through the real
  `app()->parser->process()` path (pre/optgroup/Blade/multi-line-comment modules stay verbatim).
- R4 ✅ — `ModuleIdAllocator` reproduces every documented case; live ids unchanged
  (`module-btn--1`, `shop/cart`→`module-shop-cart`).

Issues found during review — now FIXED (this pass):
- Unquoted slash values ✅ — `type=shop/products` *without quotes* previously truncated to
  `shop` (a trade-off taken to fix `type=layouts/>`). Fixed at the root in both `AttributeParser`
  (state machine) and the legacy regex (`Parser.php` + `ParserUtils.php`): internal `/` is kept,
  only the tag's self-closing `/` is dropped. Verified through the live
  `ParserUtils::parseAttributes` and pinned by 8 new unit tests.
- `LayoutProcessor` scope nesting ✅ — scope was keyed off edit-field *open* offsets only, so a
  module after a closed sibling `.edit` mis-scoped (the second `btn` in
  `<div class="edit" rel="content" …><module type="btn"/></div><module type="btn"/>` became
  content-scoped `module-btn-3--1` instead of global `module-btn`). Fixed: `EditFieldExtractor`
  now computes each field's matching close (depth-aware, ignoring nested same-name tags) and
  `LayoutProcessor` scopes a module only when `open < pos < close`. Pinned by 6 new tests.

R5 — LayoutProcessor is now WIRED (opt-in):
- `LayoutProcessor` is wired into the live `ParserProcessor::process()` behind the config flag
  `microweber.parser_use_layout_processor` (env `MW_PARSER_USE_LAYOUT_PROCESSOR`), **default OFF**.
  When on, a top-level context-free layout is routed through the new pipeline, bridging module
  rendering to the real module system via `ParserProcessor::load()`. Only the top-level call is
  routed; every recursive / parent / per-module sub-call stays on the legacy flow.
- **Per-request test toggle** — `FrontendController::index()` honours `?use_layout_processor=1`
  (and `=0`) to flip the pipeline for a single request, gated on `app.debug` OR a logged-in admin
  so anonymous visitors can't switch the parser in production. Pinned by 3 feature tests
  (`tests/Feature/ParserLayoutProcessorGetParamTest.php`). Example:
  `…/parser-test-modules?use_layout_processor=1` renders the authored content + `module-btn-32`.
- The pipeline now also does (added this pass):
  - **DB edit-field loading** — `LayoutProcessor.process()` takes an `editFieldLoader`; each
    `.edit` region's inline default is replaced with its stored value (bridged to
    `content_manager->edit_field()`), recursively for nested fields. This is what lets a real
    page render its *authored* content through the new pipeline.
  - **Recursive module output** — a rendered module whose output itself contains `<module>` /
    `.edit` (e.g. a layout module's inner regions) is re-processed, depth-bounded (`MAX_DEPTH=8`,
    no infinite loop). `process()` resets/restores shared state once; `processInner()` recurses
    without corrupting it.
- Bugs found and fixed while testing the wiring:
  - `content_id()` of `0` produced a bogus `module-btn-0` → now "no scope" (`module-btn`).
  - Renderer echoed a redundant raw `type=""` onto the wrapper → dropped (`data-type` carries it).
  - **Loaded edit-field content arrived after the initial `protect()` pass**, so a `<module>`
    inside a comment in loaded content was wrongly rendered → loaded content is now protected
    before splicing.
- Pinned by 3 integration tests + 12 new unit tests (edit-field load/replace, null-keeps-default,
  nested-field resolution, resolved content-id passing, comment-protection in loaded content,
  recursive module output, depth-bound termination).

Parity — now ACHIEVED (see `PARSER_LAYOUT_BUGS.md`):
- **All 406 Big layout skins render OK** through the new pipeline (was 0/406 — see the 5 fixes
  below), **0 empty / 0 leak / 0 fatal**.
- **Homepage is at visual parity** with legacy (16 sections / 24 images both; 113K vs 116K bytes),
  0 console errors — browser-verified.
- **12 diverse test pages** (`/lp-test-*`) all at parity vs legacy.
- The 5 fixes that closed the gap: (1) coerce module output with `(string)` — it's a
  Stringable/HtmlString, and the old `is_string` check dropped ALL module content (the 406-empty
  cause); (2) a **re-entrancy guard** so `load()`'s nested `process()` doesn't reset the id
  allocator mid-render (which caused duplicate ids → load-cache collisions → every layout showing
  the first one's content); (3) read **native content-table fields** (`content`/`content_body`/…)
  from the content row column, not just `content_fields` (the page's real edited content);
  (4) keep the inline default when `edit_field()` returns `false`; (5) bracket with
  `disableModuleProcessing()`.
- The flag still stays **default OFF** pending a committed golden-master parity test in the suite;
  with it off the wiring guard is a no-op and legacy is unchanged.

---

Review of `mw_parser_changes_2.diff` (the second, cleaner parser refactor set; the first
attempt `mw_parser_refcator.diff` was removed). This is the diff-eval review file
requested alongside applying + fixing + browser-testing the change.

- Verdict: ✅ Apply. The diff is additive, low-risk, and well-tested. Applied clean,
  all 152 helper unit tests green, the 4 existing live-parser integration tests still green,
  and the new protections verified through the real `app()->parser->process()` path.
- One latent robustness issue found and fixed (`LayoutProcessor` scope offset).
- Two behavioural nuances confirmed safe (documented below) — no action needed, but pinned
  here so a future reader doesn't re-discover them.

---

## 1. What the diff does

Architecture: extracts the monolithic regex/`str_replace` logic from `ParserProcessor` /
`ParserEditFieldsTrait` into seven small, single-responsibility, unit-testable helper classes
under `src/MicroweberPackages/App/Utils/ParserHelpers/`. This matches `PARSER_REFACTOR_PLAN_2.md`.

| New class | Responsibility |
|-----------|----------------|
| `TagLexer` | Quote-aware tokenizer for `<module …>` tags (state machine, not regex). |
| `AttributeParser` | Attribute-string → key→value map (digit names, escaped quotes, unquoted trim, first-wins dupes). |
| `ModuleIdAllocator` | Stable per-scope module id allocation (`module-btn`, `module-btn-3`, `--N` dupes, custom-id passthrough, DB-collision avoidance). |
| `ContentProtector` | Shields `script/textarea/code/pre/style/select/optgroup` + HTML + Blade comments behind placeholders; restores byte-for-byte. |
| `ModuleRenderer` | Renders one resolved tag into its wrapper; empty type → empty output (no placeholder leak). |
| `EditFieldExtractor` | Finds `.edit` regions, extracts `field/rel/rel_id`, resolves content id + scope key. |
| `LayoutProcessor` | Thin orchestrator wiring the above into one `process()` pipeline. |

Wiring into the live path (the parts that actually change production behaviour):

1. Attribute regex (`Parser.php:874`, `ParserUtils.php:14`)
   `[a-z-_A-Z]+ … [^\s"\']+?(?:\s+|$)` → name `[a-zA-Z_][a-zA-Z0-9_-]*` (digits allowed) and
   unquoted value `[^\s"\'>]+?(?=\s*/?>|\s|/$|$)` (keeps internal `/`, drops the self-closing
   `/` — the slash fix added this pass).
2. `ParserEditFieldsTrait` — `utils->parseAttributes`/`getEditFieldAttributesFromParsedAttributes`
   → `attributeParser->parse`/`getEditFieldAttributes`; protected-tag list gains `pre` + `optgroup`;
   adds Blade-comment protection and broadens the HTML-comment regex to be multi-line aware.
3. `ParserProcessor` — constructs the seven helpers; `_do_we_have_more_edit_fields_for_parse`
   now uses `TagLexer::hasModuleTags()` + a `strpos` placeholder check instead of two brittle
   `preg_match_all('/<module.*[^>]*>/')` calls; one `parseAttributes` call swapped to the new parser.

`LayoutProcessor` is now wired into the live `process()` behind the config flag
`microweber.parser_use_layout_processor` (default OFF) — see the TL;DR "R5" note. With the flag
off (default) the legacy phpQuery flow runs unchanged; the live wins this diff ships by default
are items 1–3 above.

---

## 2. Bugs fixed by the change (verified)

Empirically compared old vs new attribute regex (`/tmp/regex_cmp.php`) and ran the new + old
test suites. Confirmed fixes:

- Digit-in-attribute-name — `data-col-2="value"`: old dropped it; new keeps it.
- Unquoted trailing `/` — `type=layouts/>`: old captured `layouts/`; new captures `layouts`.
- `>` / `<` inside a quoted value — `title="a > b"`: `TagLexer` keeps the tag intact (old
  `/<module[^>]*>/` truncated at the first `>`).
- Escaped quotes — `title="say \"hi\""`: parsed without corrupting following attributes.
- Empty / type-less `<module>` — renders nothing (no `mw-unprocessed-module-tag` leak).
- `<pre>` / `<optgroup>` — module tags inside are now protected (were not before).
- Blade comments `{{-- … --}}` — module tags inside are now protected.
- Multi-line HTML comments — old `/<!--(?!<!)[^\[>].*?-->/` (no `s` flag) missed comments
  spanning newlines; new `/<!--[\s\S]*?-->/` catches them.

Live verification through `app()->parser->process()` (`/tmp/parse_smoke.php`):
`pre`, `optgroup`, `{{-- … --}}`, and multi-line `<!-- … -->` each leave the inner `<module>`
verbatim and unrendered, while a real `<div class="edit" rel="content"><module type="btn"/></div>`
renders to `class="module module-btn"`. The 4 existing `ModuleParseTest` integration tests
(`module-btn--N` numbering, `shop/cart`→`module-shop-cart`, script/textarea/input protection)
all still pass.

---

## 3. Issue found & fixed during review

`LayoutProcessor::process()` scope offset (robustness). Scope was derived from
`strpos($layout, $tag)` on the layout that is being mutated in place, while the `.edit`-field
offsets come from the original (pre-mutation) layout — two different coordinate systems. In
practice it self-corrects because identical tag strings are consumed in forward order, so no
current test distinguished it, but it is fragile (breaks if a rendered module ever re-introduces
a matching tag string). Fixed to use the lexer's stable original-layout offset
`$tagInfo['offset']`, which shares the coordinate system of the edit-field offsets. Added
`test_cross_edit_field_scope_survives_offset_shift` to pin cross-scope id resolution. 138/138 green.

---

## 4. Behavioural nuances — reviewed, safe, no action

1. `getEditFieldAttributes` returns `null` vs the old `false`. In
   `_do_we_have_more_edit_fields_for_parse`, the guard is `isset($ed['field']) && isset($ed['rel'])`.
   Old helper returned `false` for missing keys (so `isset()` was *always* true for any loosely
   `.edit`-matching element); the new helper returns `null` (so `isset()` is true *only* when a
   real `field`+`rel` exist). The new behaviour is stricter and more correct (loose-regex false
   positives no longer force an extra parse pass). Verified safe: the live `.edit rel=content`
   render path and all 4 integration tests pass.

2. Unquoted value containing `/` — **now fixed** (was a trade-off in the original diff). Both
   `AttributeParser` and the legacy regex keep an internal `/` (`type=shop/products` →
   `shop/products`) while still dropping the tag's self-closing `/` (`type=layouts/>` →
   `layouts`). Verified live and pinned by 8 unit tests.

---

## 5. Suggestions for the next iteration (not blocking)

- Wire `LayoutProcessor` in behind a feature flag and golden-master diff its output against
  the legacy flow over the Big + Bootstrap default content (the plan's parity test) before
  swapping it on. Until then it is well-tested but unexercised in production.
- `LayoutProcessor::determineScope()` is now range-based (scopes a module only when it falls
  between an edit field's open tag and its depth-matched close), so a module after a closed
  sibling `.edit` correctly falls back to global scope. Remaining edge to watch once live:
  malformed/unbalanced `.edit` markup (the close-finder then treats the rest of the document as
  inside — a conservative default).
- `AttributeParser::parse()` does not stop at the first `>` (mirrors the legacy whole-string
  behaviour), so a paired `<module>…</module>` with attribute-like text in its body could pick
  up stray keys. Not a regression (old parser did the same; phpQuery emits self-closed tags), but
  `TagLexer` already isolates the tag — feed `parse()` only the opening tag once integrated.

---

## 6. How this was validated

- `git apply --check` clean → applied clean.
- `php -l` on all 7 new helpers + 4 modified files: no syntax errors.
- `vendor/bin/phpunit tests/Unit/Utils/ParserHelpers/`: 159 tests / 322 assertions green
  (incl. 12 new for edit-field loading, loaded-content protection, and bounded recursion).
- `vendor/bin/phpunit src/MicroweberPackages/Module/slow_tests/ModuleParseTest.php`:
  7 tests / 35 assertions green (real MySQL, live `app()->parser`) — 4 legacy + 3 new for the
  LayoutProcessor wiring (flag-off default = legacy; flag-on new pipeline; flag-on slash type).
- `vendor/bin/phpunit tests/Feature/ParserLayoutProcessorGetParamTest.php`: 3 tests green —
  `?use_layout_processor=1/0` flips the pipeline under app.debug; an anonymous prod visitor cannot.
- GET-param toggle browser-verified: `…/parser-test-modules?use_layout_processor=1` →
  `Parser test — modules` + `module-btn-32`/`--1`, comment raw, 0 console errors; no param → legacy.
- LayoutProcessor wiring (flag ON) verified through `app()->parser->process()`: modules tokenize,
  get correct ids, comment/Blade/pre stay verbatim, no `-0` suffix, no raw `type=` on the wrapper.
- New pages created (live, HTTP 200): `/parser-test-modules`, `/parser-test-slash`,
  `/parser-test-comments`. With the flag ON (then reverted) they render their *authored* content
  through the new pipeline — `/parser-test-modules` shows `<h2>Parser test — modules</h2>` and ids
  `module-btn-32` / `module-btn-32--1` (correct content-scoped legacy pattern), the comment module
  stays raw, 0 console errors. (`content_id()`→`32` via the edit-field bridge.)
- Flag-ON parity boundary observed in the browser: simple authored pages render correctly; the
  homepage degrades (content present in source but not renderable structure) — the documented
  reason the flag stays default OFF. Reverted to OFF; homepage then renders 73 modules / 24 images.
- Live `app()->parser->process()` smoke test of pre/optgroup/Blade/multi-line-comment protection.
- Live `ParserUtils::parseAttributes` slash check: `type=shop/products` → `shop/products`,
  `type=layouts/>` → `layouts`, quoted values unchanged.
- Browser test (Playwright, `php artisan serve` :8000, real `microweber` DB):
  - Home `/`: 73 rendered `module-*` elements, 0 raw `<module>`, 0 unprocessed /
    protected / `mw_replace_back_this` placeholder leaks, 0 console errors.
  - `/shop`: 9 products rendered, 0 placeholder leaks, 0 console errors.
  - `/shop`, `/blog`, `/my-blog-post`, `/contact-us`: all 200, no fatals, no placeholder leaks.
  - `/shop` shows 2 raw `<module type="layouts" …>` (header/footer layout modules). Verified
    identical with the diff stashed → pre-existing template behaviour, not a regression.

