# RTL (Right-to-Left) Support Guide

> Reference: AI-293. Status of RTL across Microweber surfaces and the rules
> to follow when writing new CSS/markup so layouts do not break when the
> text direction switches (Arabic, Hebrew, Persian, Urdu, …).

## How direction is set

Microweber emits the direction on the root element via `lang_attributes()`,
which renders `dir="rtl"` (plus `lang="ar"` etc.) when the active locale is a
right-to-left language. All RTL CSS in the project is gated on the
`html[dir="rtl"]` selector — never on a body class — so it activates
automatically with the locale.

To preview RTL during development without switching locale, set the attribute
from the console or a Playwright probe:

```js
document.documentElement.setAttribute('dir', 'rtl');
document.documentElement.lang = 'ar';
```

## Current state (verified 2026-06-04, Playwright @ 1280px and 390x844)

| Surface | RTL state | Notes |
| --- | --- | --- |
| Filament admin panel | Works | Sidebar auto-mirrors to the right; topbar actions flow left; text right-aligns. Filament v5 uses logical properties internally. |
| Default Bootstrap public template | Works | Header mirrors, content right-aligns, icon/text blocks flip. Backed by Bootstrap 5 RTL + the `html[dir="rtl"]` block in `frontend-assets ui.css`. |
| Live Edit shell | Works | Inherits Filament + Microweber RTL rules. |
| Big2 template | Partial / out of repo | Lives in `Templates/Big2/` — a separate git repository (gitignored here). RTL fixes for Big2 must be committed in that repo. This is the original "layout breaks" source from AI-262. |

## Rules for new code

1. **Use logical properties, not physical ones.**
   - `margin-inline-start` / `margin-inline-end` instead of `margin-left` / `margin-right`
   - `padding-inline-start` / `padding-inline-end` instead of `padding-left` / `padding-right`
   - `inset-inline-start` / `inset-inline-end` instead of `left` / `right`
   - `text-align: start` / `text-align: end` instead of `left` / `right`
   - `border-inline-start` etc. instead of `border-left`

2. **Flip directional icons, never decorative ones.**
   Horizontally-directional glyphs (back/forward arrows, prev/next chevrons,
   carousel controls) must mirror under RTL. The project ships a shared rule:

   ```css
   html[dir="rtl"] .mdi-arrow-left,
   html[dir="rtl"] .mdi-chevron-left,
   /* …other directional MDI classes… */
   html[dir="rtl"] .mw-rtl-flip {
       transform: scaleX(-1);
   }
   ```

   For a bespoke directional glyph (custom SVG / icon font) not covered by the
   named classes, add the opt-in `mw-rtl-flip` class to the element. Do **not**
   flip vertical glyphs (chevron up/down, dropdown carets) — `scaleX` either
   does nothing useful or visually corrupts them.

   The rule is mirrored in both build pipelines:
   - Public: `packages/frontend-assets/resources/assets/css/microweber/css/ui.css`
   - Admin / Live Edit: `packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css`

3. **Scope every RTL override on `html[dir="rtl"]`** so the LTR default is
   never touched. Test both directions before shipping.

4. **Do not run a blanket `left/right` → logical sweep** without RTL test
   content and an explicit ticket — physical properties are sometimes load
   bearing (absolute-positioned decorations). Convert per-component, verify.

## Verification checklist

- [ ] Set `dir="rtl"` and confirm no horizontal overflow / clipped content.
- [ ] Sidebars / drawers appear on the correct (mirrored) side.
- [ ] Directional arrows/chevrons point along the reading direction.
- [ ] Text blocks align to the inline-start edge.
- [ ] LTR is byte-for-byte unaffected (every RTL rule is `html[dir="rtl"]`-gated).
