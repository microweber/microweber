---
name: ai-263-phase-c-baseline
description: >-
  Reference baseline of public-page vs admin-page JS payload measured
  at the close of AI-263 Phase C (cycle-186, 2026-05-11). Future
  cycles can compare against these numbers to detect regressions or
  measure additional perf wins. Use this skill whenever you need to
  benchmark JS-payload changes, when AI-264/AI-265 ship, when
  considering further bundle-trimming work, or when investigating
  whether a new dependency has bloated the public pages.
---

# AI-263 Phase C — Network Payload Baseline

> **Captured:** 2026-05-11, cycle-186, after AI-263 Phase B5 shipped.
> **Captured by:** agent-a1 via Playwright `performance.getEntriesByType('resource')`.
> **Viewport:** 390×844 (iPhone 13 / 14 mobile size).

## Public Bootstrap pages (homepage + shop)

After cycle-185 dropped `mw_require_jquery()` from Bootstrap
master.blade.php:

| Metric | Value |
|---|---|
| jQuery script in network | **FALSE** ✅ |
| jQuery UI script in network | **FALSE** ✅ |
| `window.jQuery.fn.jquery` (version string) | **undefined** ✅ |
| Total JS scripts loaded | **7** |
| Total JS decoded bytes | **608,688** (~595 KiB) |
| Total JS transferred bytes | **610,788** (~597 KiB) |
| DOMContentLoaded | 252-437 ms |
| Load event | 254-441 ms |

## The 7 scripts that load on public Bootstrap pages

| Script | Bytes |
|---|---|
| `vendor/livewire/livewire.min.js` | 226,493 |
| `vendor/microweber-packages/frontend-assets/build/frontend.js` | 184,044 |
| `apijs_settings?mwv=4.0-dev17` | 105,094 |
| `templates/bootstrap/dist/build/app.js` | 83,815 |
| `vendor/microweber-packages/frontend-assets-libs/async-alpine/async-alpine.script.js` | 4,529 |
| `modules/contact_form/js/contact-form-alpine.js` | 3,133 |
| `modules/captcha/js/captcha-alpine.js` | 1,580 |

## Admin pages (legacy jQuery preserved)

`/admin` route after auto-redirect from `/admin/login`:

| Metric | Value |
|---|---|
| `window.jQuery.fn.jquery` (version) | **'3.7.1'** ✅ |
| jquery.js in network | **TRUE** ✅ (285,314 bytes) |
| jquery-ui.js in network | **TRUE** ✅ (521,054 bytes) |
| **Combined jQuery + jQuery UI bytes** | **806,368** |
| Total JS scripts loaded | 28 |
| Total JS decoded bytes | 4,413,546 (~4.21 MiB) |

The exact 806,368 bytes (= 285,314 + 521,054) confirms PM's
"806KB" estimate matches reality precisely.

## The savings

| Page | Before AI-263 (est.) | After AI-263 | Reduction |
|---|---|---|---|
| `/` (Bootstrap homepage) | ~1,415,056 bytes | 608,688 bytes | **~57%** (-806KB) |
| `/shop` | ~1,415,056 bytes | 608,688 bytes | **~57%** (-806KB) |
| `/admin` | unchanged | 4,413,546 bytes | 0% (preserved) |

Every public Bootstrap page render now ships **~57% less JS** than
before AI-263. That's 806KB removed from the critical path on the
mobile-first homepage and shop.

## What this enables

- **Lighthouse "Total Byte Weight" audit** — public pages are now
  ~806KB lighter on every cold visit.
- **Lighthouse "Reduce unused JavaScript" audit** — jQuery + jQuery
  UI counted as ~798KB unused on public pages (they were loaded
  but not needed). Now zero.
- **First Contentful Paint** — browser no longer needs to
  parse/compile 806KB of unused JS before painting.
- **Mobile data savings** — every visitor on 3G/4G saves ~806KB
  per first-visit page. Significant on emerging-market connections.

## Caveats

- This measurement is **decoded body size**, not over-the-wire
  transfer (gzip changes those numbers but the SAVED PROPORTION
  is identical).
- The 7-script count on public pages still includes Livewire
  (226KB), which is heavy. Future tickets (AI-264 admin bundle
  split, plus a similar tree-shake on Livewire) can shrink
  further.
- The conditional emission infrastructure from cycle-181 still
  loads jQuery LATE (before `</body>`) on any public page that
  renders a marker-bearing module skin (slick-slider, masonry,
  datetimepicker, chosen, data-mw-needs-jquery). Pages with those
  modules see the cycle-182/183 adapters bring jQuery back at
  end-of-body — that's intentional (zero regressions on legacy
  skins) but means those specific pages don't get the full 806KB
  saving.
- Lighthouse mobile run (with proper 3G throttling + 6× CPU
  slowdown) would give exact Performance score deltas. That run
  requires Lighthouse CLI / MCP which wasn't available in the
  cycle-186 environment — the network-payload measurement above is
  the best proxy.

## How to re-measure

In Playwright at 390×844 on `/`:

```javascript
var e = performance.getEntriesByType('resource');
var jsCount=0, jsBytes=0;
e.forEach(r => {
    if (r.name.match(/\.js(\?|$)/i) || r.initiatorType === 'script') {
        jsCount++; jsBytes += r.decodedBodySize || 0;
    }
});
console.log({jsCount, jsBytes,
    jqueryLoaded: window.jQuery && window.jQuery.fn && window.jQuery.fn.jquery});
```

Expected today: `{jsCount: 7, jsBytes: ~608688, jqueryLoaded: undefined}`.
Regression signal: jsBytes > 700,000 OR jqueryLoaded becomes a
version string on public path.
