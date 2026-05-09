# Troubleshooting

## 2026-05-04 — `/admin/login` returns 404 in local runtime

### Symptoms
- `curl -I http://127.0.0.1` returns `200 OK`.
- `curl -I http://127.0.0.1/admin/login` returns `404 Not Found`.
- Playwright navigation to `http://127.0.0.1/admin/login` shows Apache’s 404 page instead of the Laravel admin login.

### Likely Cause
- The local Apache-served runtime is not routing requests into the Laravel app for `/admin/*`, even though the host responds on the root URL.

### Impact
- Browser-based verification for admin and live-edit tasks is blocked or misleading in this environment.

### Current Workaround
- Use static/code verification and CLI-level checks when browser validation depends on `/admin/*`.
- Record the environment issue in task notes so UI failures are not misattributed to application regressions.

## 2026-05-04 — Full PHP suite needs split-process runner

### Symptoms
- Broad single-process test runs can fail or become unstable because PHP memory usage grows across many suites.

### Cause
- `run-tests.sh` documents PHP memory fragmentation/leak behavior during long suite execution.

### Fix / Preferred Command
- Use `./run-tests.sh` for broad repo validation.
- Use direct `php vendor/bin/phpunit <path>` or targeted suite runs for small changes.

## 2026-05-04 — Pest helper files are not active root entrypoints

### Symptoms
- Historical helper scripts exist under `docs/testing/` for a possible future Pest migration.
- The root repository uses `phpunit.xml`, `composer test`, and `./run-tests.sh` instead.

### Impact
- Contributors may assume the helper scripts are active root config unless the docs are explicit.

### Current Guidance
- Use `composer test`, targeted `php vendor/bin/phpunit ...`, or `./run-tests.sh`.
- Treat the `docs/testing/*pest*` helpers as scaffolding only unless Pest is intentionally reintroduced as a real root dependency.

## 2026-05-05 — Live-edit CSS leaked into unrelated Filament pages

### Symptoms
- Filament tabs, compact live-edit inputs, and transparent modal overlays kept needing one-off fixes outside live-edit.
- The admin theme bundle imported live-edit helper CSS that styled raw `.fi-tabs`, `.fi-modal-*`, and `.fi-section*` selectors globally.

### Root Cause
- Live-edit-specific CSS lived in the shared Filament theme bundle without a wrapper selector, so generic Filament pages could inherit live-edit presentation rules.

### Fix
- Scope the leaking selectors to `.mw-admin-live-edit-page` in `live-edit-action-links.css`, `live-edit-input.css`, `general-styles.css`, and `live-edit-classes.css`.
- Keep a focused regression in `tests/Feature/Filament/Theme/LiveEditCssScopeTest.php`.

### Recurrence Signal
- If a non-live-edit Filament page suddenly picks up live-edit tab underlines, compact 11px inputs, or transparent modal overlays, search the theme CSS for unscoped `.fi-*` selectors in `packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-*.css`.

## 2026-05-09 — Live-edit Origin guard evaluation (AI-127 / TICKET-BB-EVAL)

### Brief
The brief asked us to evaluate adding an Origin-header check to the
live-edit iframe postMessage handler — and to implement if safe, OR
document in TROUBLESHOOTING.md if not.

### Finding
The first-party live-edit JS does NOT use `window.postMessage` for
iframe ↔ parent communication. Verified via:

```bash
grep -rln "postMessage" packages/ --include="*.js"
# Only matches: bundled tinymce vendor + bundled codemirror vendor.
# No first-party microweber JS uses postMessage.
```

Live-edit's iframe-to-parent protocol is shared via:
- `window.parent.mw.app.dispatch(...)` calls (cycle-79's mw.app
  shim) — direct parent-window function calls, not postMessage.
- DOM mutation observers in the parent window watching the iframe
  body for `data-mw-*` attribute changes.

Both paths require the iframe + parent to share an origin (per the
SETUP.md "Same-origin Live-Edit note"). Adding an Origin check to a
postMessage handler is therefore **N/A** — there is no handler to
guard.

### Decision (2026-05-09 / cycle-111)
- Defer the Origin-guard implementation until live-edit migrates
  to a postMessage-based protocol (no current plan).
- Pin the SAME-origin requirement in SETUP.md (already documented
  in cycle-104) so cross-origin reverse-proxy setups know to set
  APP_URL correctly.
- If a future cycle introduces postMessage in live-edit:
  1. The handler MUST validate `event.origin === window.location.origin`
     (or against an explicit per-deploy allowlist).
  2. Update this TROUBLESHOOTING entry to reference the handler's
     location.
  3. Add a regression test pinning the origin check.

### Recurrence Signal
- If a future PR adds `window.addEventListener('message', ...)` in
  any first-party live-edit JS file, this entry becomes
  ACTIONABLE — implement the origin check before the PR lands.

## 2026-05-09 — Recurring developer gotchas (AI-122 / TICKET-CC-EVAL backfill)

A small set of foot-guns recur across cycles. Each entry below
captures what to look for when reviewing a PR + the canonical fix.

### Backtick template literal collision with Blade `{{ }}`

**Symptom:** A `<script>` block in a Blade template uses backtick
template literals (`\`Hello ${name}\``) and the page renders blank
or the JS console reports `Uncaught SyntaxError: Unexpected
token '{'`.

**Root cause:** Blade's `{{ ... }}` interpolation runs FIRST; if
the JS contains `${...}` Blade may mangle it. Symmetrically, if
the JS contains `{{ ... }}` (e.g. inside a JSDoc comment) Blade
will try to evaluate it.

**Fix:** Wrap the script body in `@verbatim ... @endverbatim` OR
use single-quoted concatenation (`'Hello ' + name`) instead of
template literals. A delegated `data-mw-*` attribute pattern (per
ADR-0003) sidesteps the problem entirely by keeping JS in `.js`
files, not Blade `<script>` blocks.

### `wire:click` on a `<select>` `<option>`

**Symptom:** Clicking a dropdown option triggers two Livewire
round-trips OR the option doesn't change the bound value.

**Root cause:** `<option>` doesn't fire `click` events
consistently across browsers (Safari + Firefox quirks). Livewire's
`wire:click` binds at the option level get swallowed by the
parent `<select>`'s native change handler.

**Fix:** Use `wire:model.live.debounce.500ms` on the parent
`<select>` instead. The cycle-112 sweep enforces the debounce;
the cycle-N grep-gate catches `wire:click` on `<option>` tags.

### Hardcoded element IDs in module skins

**Symptom:** Two instances of the same module on one page —
clicks on one toggle the other; only the first is interactive.

**Root cause:** A skin uses a literal id (`<div id="my-thing">`)
instead of a per-instance id (`<div id="my-thing-{{ $params['id'] }}">`).
Two modules emit the same id; jQuery selectors hit only the
first match in DOM order.

**Fix:** Every skin id MUST include `{{ $params['id'] }}`. The
cycle-89 Post-list bundle + cycle-91 FAQ + cycle-92 Accordion
sweeps closed the known sites; the AI-113 grep-gate catches
new occurrences (pattern: `id="<word>-{{ \$params\['id'\] }}"`
expected in any module skin >50 lines).

### `str_contains()` argument order

**Symptom:** Code `str_contains($needle, $haystack)` (wrong
order) silently returns false — opposite of the intended
behaviour. Often misses input validation entirely (e.g.
`str_contains('https', $url)` returns true for ANY `$url`
containing the substring 'https' — but the dev meant
`str_contains($url, 'https')`).

**Root cause:** PHP's `str_contains($haystack, $needle)` and
JS's `string.includes(substr)` have opposite parameter shapes.
Devs writing PHP after JS get it backwards.

**Fix:** Use the named-parameter form: `str_contains(haystack:
$url, needle: 'https')`. Static analysis (PHPStan level 5+)
catches type mismatches when the strings carry distinct PHPDoc
types.

### AEAD-ciphertext column too narrow after encrypt-at-rest

**Symptom:** `ModelNotFoundException` after a
re-encrypt sweep: `users.api_token` (varchar(80)) silently
truncates the AEAD ciphertext output (~140 bytes for a
40-byte plaintext + nonce + tag); decrypt fails with
"Invalid MAC".

**Root cause:** AEAD ciphertext is base64-encoded
`nonce + ciphertext + auth-tag`. The encoded length is
roughly `4/3 * (12 + plaintext_len + 16) + padding`, which
exceeds the original `varchar` width for any column under
`varchar(255)` once the plaintext is anything but trivial.

**Fix:** Widen the column BEFORE running the re-encrypt
sweep — `$table->text('api_token')->change()`. Cycle-43's
encrypt sweep widened `users.password_history`; the
DEEP_AUDIT_TODO.md TICKET-BJ scope-doc tracks the remaining
columns (`payment_methods.token`, `cms_settings` secret rows).
