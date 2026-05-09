# ADR-0002 — Allowlists & sanitization model

> **Cycle-111 / AI-114 / TICKET-CR (2026-05-09)**
> Status: Accepted
> Extends: ADR-0001 Principle 2 + 3
> Context: URL / scheme / file-type / HTML-tag allowlists across
> Microweber's content storage and rendering paths.

---

## Context

Microweber accepts user input across many surfaces:

- Page content body (HTML)
- Module settings (key/value strings)
- Image / file uploads (binary)
- Image-picker URL tab (string URL)
- Link-picker URL tab (string URL)
- Newsletter tracked-link URL list (string URLs)
- Coupon redemption codes (string)
- Custom-field values (string + JSON)

Each surface needs an **allowlist** of acceptable values. The default
should be **deny**: anything not on the allowlist is rejected at the
boundary (storage time AND render time, since data flows through
both).

---

## Decision

### Allowlists are explicit + versioned

Every surface that takes a URL declares its allowlist in code, not
config. Examples:

```php
// Modules/Media/Support/...::ALLOWED_FILE_SCHEMES
private const ALLOWED_FILE_SCHEMES = [
    'http', 'https',
    'data:image/png', 'data:image/jpeg', 'data:image/gif',
    'data:image/webp', 'data:image/svg+xml',
    // Notably NOT: javascript, data:text/html, file, vbscript
];
```

```js
// frontend-assets/.../core/url.js::ALLOWED_LINK_SCHEMES
const ALLOWED_LINK_SCHEMES = ['http:', 'https:', 'mailto:', 'tel:'];
```

Versioning: when an allowlist changes, the change is captured in the
migration that ALSO purges existing rows that no longer pass. (One
recent example: cycle-93 newsletter tracked-link allowlist tightened;
the migration purged tracked links matching the now-disallowed
patterns from existing newsletter records.)

### Sanitization runs at BOTH storage and render

The cycle-23 sweep showed that "sanitize at storage only" leaves the
codebase one DB-row migration / restore-from-backup away from
re-introducing XSS. The convention is now:

1. **At storage**: validate the input against the allowlist; reject
   if it fails. Store the validated form.
2. **At render**: escape for the output context (per ADR-0003);
   never trust that storage-time validation prevents render-time
   exploits.

Costs: 2× the CPU work per request. Mitigation: storage-time
sanitization is offline (form submission); render-time is fast
(htmlspecialchars + a few regex checks).

### Sanitizers fail closed

When parsing fails, return a safe-by-construction empty / null /
sentinel value rather than passing the input through. Examples:

- `safe_css_url('javascript:alert(1)')` returns `''`, not the
  unchanged input.
- `mw_strip_tags($html)` returns `''` if HTMLPurifier fails to
  parse, not the unchanged HTML.

Tests pin this: `assertSame('', $helper($maliciousInput))`.

### File uploads use signature checks, not extension checks

Per cycle-30 / TICKET-RR + cycle-43 hardening:
- `MimeType` is detected via `finfo` (libmagic), not the `$_FILES`
  array (which the client controls).
- Allowed file types are checked against the libmagic signature.
- Server-side rename strips client filename to a UUID + safe
  extension derived from the signature.

This protects against:
- `evil.php.jpg` uploads (extension says JPG, content is PHP).
- `evil.html` masquerading as `image/jpeg`.
- Polyglot files (valid GIF + valid PHP in the same bytes).

---

## Consequences

- **Positive**: the codebase has clear "where do allowlists live"
  rules. Reviewers can grep for `ALLOWED_*` constants.
- **Positive**: each allowlist is a single point of change. Adding
  a new allowed scheme is one PR, one tested change.
- **Positive**: sanitizers' fail-closed behaviour means an XSS
  attempt that exploits a parser quirk turns into an empty string,
  not a reflected payload.
- **Negative**: surfaces that legitimately need wider allowlists
  (e.g. embedding YouTube via custom URLs) need explicit per-case
  carveouts; this becomes its own decision audit-trail.

---

## Related ADRs

- ADR-0001 — Helper-layer security (overarching principles)
- ADR-0003 — Two-pass escape (output-context escaping)

---

## Related cycles

- Cycle-30 (AI-58 / TICKET-RR): newsletter HMAC + tracked-link
  allowlist
- Cycle-43: per-request encrypt + file-upload signature checks
- Cycle-59 (AI-77 / TICKET-AT): CSS-injection vector via
  `style="background-image: url(...)"` closed
- Cycle-79 (AI-55 / mw.app.dispatch shim): event-bus allowlist
- Cycle-95 (AI-84 / TICKET-YY): link-picker URL allowlist
