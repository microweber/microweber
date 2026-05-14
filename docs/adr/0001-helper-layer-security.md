# ADR-0001 — Helper-layer security model

> **Cycle-111 / AI-114 / TICKET-CR (2026-05-09)**
> Status: Accepted
> Context: `src/MicroweberPackages/Helper/functions/` — global helper
> functions used throughout Blade templates + module skins.

---

## Context

Microweber's global helper functions sit at the **boundary** between
admin-supplied data and rendered HTML / CSS / JS. They include:

- `thumbnail($src, $w, $h)` — image URL builder, output goes into
  `<img src="">` and (historically) `style="background-image: url(...)"`.
- `responsive_thumbnail(...)` — wraps `thumbnail()` and emits an
  `<img>` element directly.
- `safe_css_url(...)` — wraps a URL for use inside CSS `url(...)`.
- `content_link($id)` / `module_url(...)` — admin-controlled URL output.
- `_e('...')` / `__('...')` — translation lookup helpers; output goes
  into HTML / attribute / JS contexts.
- `mw_strip_tags($html)` — HTML sanitizer used before display.
- `safe_html(...)` / `clean_html(...)` — sanitizers for stored HTML.

Each of these is a **string-in / HTML-out** path. The risk model:

1. An admin user (with intent or via XSS already on their session)
   stores a malicious string in a content field, page meta, module
   setting, etc.
2. The helper function fetches that string and renders it into a
   page that another user (often a public visitor) loads.
3. Without sanitization, the malicious string executes as HTML / CSS
   / JS in the visitor's browser.

The cycle-23..101 sweeps closed many specific vectors (CSS-injection
via `style="background-image: url('&#123;&#123; thumbnail() }}')"`, inline
`onclick=` interpolations, etc.). This ADR captures the **principles**
those fixes encoded so future helpers don't drift back.

---

## Decision

### Principle 1 — Two-pass escape

Helpers that output HTML must escape **at the point of output**, not
at the point of storage. Escaping at storage seems efficient but
breaks under nested contexts:

- A string escaped for HTML stored as `&lt;script&gt;` is correct
  for `{{ }}` Blade output, but if a helper later strips its
  HTML-escape and feeds it to a JS context, you get an XSS.
- Multi-pass escaping (HTML-escape AT storage, then JS-escape AT
  output) is the only safe model.

The Microweber convention:
- DB stores raw user input (no escaping).
- Blade `{{ }}` HTML-escapes on output.
- Blade `{!! !!}` is **only** used for trusted-rendered content
  (e.g. `responsive_thumbnail()` return value, which itself escapes
  every attribute it interpolates).
- JS-context output uses `json_encode()` to safely serialize.

See `tests/Feature/CspOnclickSweepContractTest.php` for the
regression pin on the inline-onclick + Blade-interpolation pattern.

### Principle 2 — URL allowlists for protocol-sensitive sinks

Any helper that outputs a URL into a context where the protocol can
trigger script execution (`<a href>`, `<img src>`, `<form action>`,
`<script src>`, `<iframe src>`, CSS `url(...)`) must validate the
URL's protocol against an allowlist. The default allowlist:

- `http://`, `https://` — always allowed.
- `mailto:`, `tel:` — allowed for `<a href>` only.
- `data:image/...` — allowed for `<img src>` only.
- `/`-relative paths — always allowed.
- **`javascript:`, `data:text/html`, `vbscript:`, `file://`** — never
  allowed; rejected at the helper boundary.

Implementation: `mw.tools.isAllowedFileUrl(val)` (frontend) +
`Modules/Media/Support/...::is_allowed_url(val)` (backend) as the
canonical allowlist functions. New helpers MUST call them before
emitting a URL.

See `tests/Feature/ImagePickerUrlSchemeContractTest.php` for the
regression pin.

### Principle 3 — Sanitizers fail closed

When a sanitizer encounters input it cannot reason about (malformed
HTML, ambiguous encoding, unrecognized scheme), it **rejects** rather
than passing through. Examples:

- `mw_strip_tags()` returns the input with all tags stripped if
  parsing fails (vs. returning the input unchanged).
- `safe_css_url()` returns an empty string if the URL doesn't
  pass the protocol allowlist.

The "fail open" alternative (return input unchanged on error) has
historically been the source of XSS vectors.

### Principle 4 — Helpers MUST be context-aware

A helper's output context dictates its escaping. The same `$src`
string fed to:
- `<img src="<?= $src ?>">` — needs HTML-attribute escape.
- `<a href="<?= $src ?>">` — needs HTML-attribute escape + URL
  validation.
- `<style>background-image: url('<?= $src ?>');</style>` — needs
  CSS-string escape + URL validation (this combination is so
  hazardous that cycle-87/89/103 banned it via the AI-113
  grep-gate; lift to `<img>` instead).

Helpers must declare their target context via parameter name or
wrapper function. Example:
- `safe_css_url($url)` → CSS context.
- `responsive_thumbnail($src, ...)` → renders the full `<img>`
  including HTML-attribute escapes.

---

## Consequences

- **Positive**: helpers become predictable. A reviewer can read a
  helper signature and know what escape rules apply. New CSP
  policies (script-src 'self', style-src 'self') become enforceable
  because the helpers don't emit inline JS or CSS via interpolation.
- **Positive**: regression tests scale. Each `*ContractTest.php`
  pins one of the principles; a refactor that violates the principle
  fails the contract test.
- **Negative**: helper API surface grows (one helper per context
  instead of one omni-helper). Mitigated by clear naming
  (`safe_css_url`, `safe_html_attr`, etc.).
- **Negative**: existing third-party themes that interpolate raw
  helper output into inline `style=` / `onclick=` will fail the
  AI-113 grep-gate. Mitigated by the waiver list in
  `scripts/grep-gate-waivers.txt` for known-existing legacy
  callsites + a clear path to fixing each (lift to a real `<img>`
  / data-mw-* attribute).

---

## Related ADRs

- ADR-0002 — Allowlists & sanitization (extends Principle 2 + 3)
- ADR-0003 — Two-pass escape (extends Principle 1)

---

## Related cycles

- Cycle-87 (AI-75 / TICKET-BB): inline-onclick sweep
- Cycle-89 (AI-77 / TICKET-AD): Post-list CSS bundle
- Cycle-103 (AI-113 / TICKET-CP): CI grep-gate
- Cycle-95 (AI-84 / TICKET-YY): Link-picker URL validation
