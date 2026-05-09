# ADR-0003 — Two-pass escape model

> **Cycle-111 / AI-114 / TICKET-CR (2026-05-09)**
> Status: Accepted
> Extends: ADR-0001 Principle 1
> Context: output-context escaping rules across Blade templates,
> JS templates, CSS interpolation, and Filament surfaces.

---

## Context

Browsers parse content in **multiple stages** with **different
escape rules at each stage**:

1. HTML parsing — recognises `<script>`, `<style>`, `<form>`, etc.
2. Attribute parsing — `<div title="...">` honours `&quot;` but
   `<div data-x='...'>` honours `&#39;`.
3. CSS parsing (inside `<style>` or `style=""`) — honours
   `\27` for apostrophe, ignores HTML escapes.
4. JS parsing (inside `<script>` or `onclick=""`) — honours JS
   string escapes, ignores HTML escapes.
5. URL parsing (inside `href`, `src`, `action`, `url(...)`) —
   honours `%hh` URL-encoding.

A string escaped for stage N is generally **not safe** for stage M.
Worse, stages are nested: `<a href="javascript:alert(1)">` parses
the `href` attribute (stage 2), then the URL (stage 5), then the
JS body (stage 4). An attacker can chain these against any helper
that escapes for the wrong stage.

The solution: **escape at the point of output, in the context of
output**. Microweber calls this the "two-pass" model:

- **Pass 1 (storage)**: validate against an allowlist (ADR-0002).
  Reject malformed / disallowed input. Store the validated form
  in the DB.
- **Pass 2 (render)**: escape FOR the specific output context (HTML
  body / HTML attribute / CSS / JS / URL).

Pass 2 happens AT every render, because a single stored string can
be rendered in multiple contexts across the codebase.

---

## Decision

### Blade `{{ }}` is the default

Plain `{{ $value }}` HTML-escapes. Use it for HTML body content +
HTML attribute values. This handles 90% of cases.

### Blade `{!! !!}` is for trusted-rendered content only

`{!! ... !!}` outputs raw, unescaped. Acceptable uses:
- Output of helpers that themselves do their own per-attribute
  escaping internally (e.g. `responsive_thumbnail()`,
  `category_tree()`).
- Output of HTMLPurifier-cleaned strings.
- Static HTML constants from PHP code.

Never acceptable:
- User-supplied raw HTML (use `mw_strip_tags()` first).
- A string that came from the DB without going through a sanitizer.

### `data-*` attributes for JS hand-offs (not `onclick=""`)

Pass 4 (JS context inside `onclick=`) is the most error-prone. The
canonical pattern is:

```html
<button data-mw-product-id="{{ $id }}"
        data-mw-action="add-to-cart">Add</button>
```

with a delegated listener:

```js
document.addEventListener('click', (e) => {
    if (e.target.matches('[data-mw-action="add-to-cart"]')) {
        const id = e.target.dataset.mwProductId;
        addToCart(id);
    }
});
```

Why: `{{ }}` escapes for HTML attribute (pass 2), which is the
correct escape for `data-*` attribute storage. JS reads the value
via `dataset`, which gives an already-decoded string. No
double-escape, no XSS.

The cycle-87 / cycle-89 / cycle-103 sweeps converted every
`onclick="...{{ }}..."` site to this pattern. The AI-113 grep-gate
pins the absence regression.

### CSS interpolation: never inline, always `<img>` or class-based

CSS context is even more error-prone than JS context because the
browser HTML-decodes attribute values BEFORE the CSS parser sees
them. So:

```html
<!-- BAD: apostrophe in $url breaks out of url() -->
<div style="background-image: url('{{ $url }}')">
```

Even with Blade-escape applied, the HTML parser turns `&#039;`
back into `'`, and the CSS parser sees the un-escaped form. An
admin who can control `$url` (or `$post['title']`) can inject
arbitrary CSS.

Canonical fix: lift to `<img>`:

```html
<img src="{{ $url }}" alt="..." loading="lazy">
```

Or use a class that resolves the variable via theme tokens:

```html
<div class="hero hero-{{ $variant }}">…</div>

/* in CSS */
.hero-dark { background-image: var(--hero-dark-bg); }
```

The cycle-89 Post-list bundle + AI-113 grep-gate pin this.

### Translations (`_e()` / `__()`) escape at the call site

Translation lookup helpers can return user-influenced strings
(some translation files contain `{{ $variables }}` placeholders
expanded at runtime). Always `{{ }}`-wrap the call site:

```blade
{{ __('Welcome, :name', ['name' => $user->name]) }}
```

Never `{!! __(...) !!}` unless you control every locale string.

---

## Consequences

- **Positive**: every user-facing string has a clear, predictable
  escape at the render point. Reviewers don't need to chase a
  variable through 10 helpers to verify it's safe.
- **Positive**: refactoring is easier. Moving a variable from a
  text body to a JS context just means changing the wrapper from
  `{{ }}` to `data-*` + dataset read; the variable itself doesn't
  need to be re-sanitized.
- **Positive**: the cost of a missed escape is one render path,
  not one DB row. We can fix forward without DB migrations.
- **Negative**: storage-time validation alone is no longer
  sufficient to prove safety; reviewers must also verify the
  render-time escape.

---

## Related ADRs

- ADR-0001 — Helper-layer security
- ADR-0002 — Allowlists & sanitization

---

## Related cycles

- Cycle-87 (AI-75 / TICKET-BB): inline-onclick → data-mw-* sweep
- Cycle-89 (AI-77 / TICKET-AD): Post-list inline-style extraction
- Cycle-103 (AI-113 / TICKET-CP): grep-gate enforces no-regression
- Cycle-23 (TICKET-VV): form error summary + attribute escaping
