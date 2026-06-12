# Color Schemes Migration Plan — globalize into `components.color-schemes`

**Date:** 2026-06-11
**Goal:** Lift color schemes (palettes) out of the per-template silo and make them a **global, shared resource** owned by the `Modules/Components` module under a `color-schemes` namespace. Every template (Bootstrap, Big, future) loads the **same catalog** from one place, with an optional per-template extension. **This document is the plan only — no code changes.**

---

## 1. How it works today (evidence)

A "color scheme" / palette is a named map of CSS custom properties. Today everything is **template-scoped to the active template folder**:

| Piece | Location (today) | Role |
|---|---|---|
| **Scheme catalog** | `Templates/Big/resources/assets/color-palettes.json` (**134 palettes**) + `Templates/Big/resources/assets/design-styles/style-packs/colors/*.json` (named packs) | Each palette = `{ name, mainColors[], properties{} }`; `properties` is ~50 CSS custom properties (`--primaryColor`, `--links`, `--background`, `--btnBackground`, `--headerBg`, `--footerBg`, …). |
| **Field declaration** | the template's `style-settings.json` / `template_settings`, key `colorPaletteFromTemplateFolderLibrary` → a template-relative folder (`assets/design-styles/colors`) | Declares the palette picker in the Live Edit right sidebar. |
| **Server-side loader** | `src/MicroweberPackages/Template/resources/views/livewire/live-edit/template-setting-render-color-palette-item.blade.php` | Globs JSON from **`templates_dir() . template_name() . DS . $folder`** — i.e. the ACTIVE TEMPLATE's folder. ⬅ *this is the root of the Big-only limitation.* |
| **Picker UI + apply** | `packages/frontend-assets/resources/assets/ui/components/RightSidebar/TemplateSettings/TemplateSettingsFields/FieldColorPalette.vue` | `applyColorPalette()` unsets the previous palette's properties, then writes the new `properties` as live CSS custom properties via `mw.top().app.cssEditor.setPropertyForSelectorBulk(':root', …)` and emits `batch-update` to persist. |
| **JS service** | `packages/frontend-assets/resources/assets/api-core/services/services/color-palette-manager.js` | Client cache via `mw.storage.get/set('colorPalette')`. |
| **Persistence** | `src/MicroweberPackages/Template/Http/Livewire/Admin/LiveEditTemplateSettingsSidebar.php` (`template_settings`) | The selected palette is saved as a template setting and re-emitted on render. |
| **Var bridge** | `Templates/Big/resources/assets/css/scss/_color-scheme-fix.scss` (scoped **`body.Big`**) | Palettes set `--primaryColor`/`--links`; the template's CSS consumes `--mw-primary-color`/`--mw-link-color`/`--mw-btn-*`. This SCSS bridges palette→MW vars. **Big-specific.** |

**Net:** the catalog, the loader path, and the var-bridge are all bound to the Big template. Bootstrap ships only `design-styles/design-vars.*` and **no palette catalog**, so it has no schemes.

### Two distinct naming layers (important)
- **Palette property names** (in the JSON): `--primaryColor`, `--links`, `--btnBackground`, `--headerBg`, … (camelCase-ish, palette-authoring vocabulary).
- **Consumed CSS vars** (in template CSS): `--mw-primary-color`, `--mw-link-color`, `--mw-btn-background-color`, … (the `--mw-*` runtime tokens).

The `body.Big` bridge is what maps one to the other today. Globalizing **requires making this mapping template-agnostic and canonical.**

---

## 2. Why globalize

- **Schemes are universal.** A "color scheme" is just a set of brand colors — nothing about it is Big-specific. Duplicating 134 palettes per template is wasteful and drifts.
- **Bootstrap (and every future template) gets zero schemes today** purely because the catalog lives in `Templates/Big/`.
- **Single source of truth.** One catalog, one var-mapping, maintained once — consistent with the component-system direction (the Components module is already the home for cross-template primitives).
- **Switching templates shouldn't lose your palette.** A global scheme + global persistence lets the chosen palette survive a template switch.

---

## 3. Target architecture — `components.color-schemes`

Make `Modules/Components` the owner. Proposed layout:

```
Modules/Components/
  resources/
    color-schemes/
      palettes/                ← the 134 palettes (was Big/color-palettes.json, split per-file)
        afternoon-sinkers.json
        always-look-up.json
        …
      packs/                   ← named style packs (was style-packs/colors/*.json)
        amber-glow.json
        …
      schema.json              ← canonical property set + metadata (single source of truth)
    css/
      color-schemes.scss       ← GLOBAL var-bridge (palette props → --mw-* tokens), template-agnostic
  View/Components/
    ColorScheme.php            ← (optional) <x-color-scheme> emitter for the active scheme :root block
  Support/
    ColorSchemeRegistry.php    ← PHP service: discover/load/merge schemes (global + per-template override)
  config/
    components.php             ← config('components.color-schemes') paths/overrides
```

**Namespace exposure:** register a resource/asset namespace `components::color-schemes` (Blade view namespace + a published asset path `public/vendor/microweber-packages/components/color-schemes/`) so the loader, the picker, and the bridge all resolve from ONE place regardless of active template.

**Canonical token layer (`schema.json` + `color-schemes.scss`):** define the authoritative list of scheme properties ONCE and the single mapping to `--mw-*` runtime tokens. The bridge SCSS is scoped to a **template-agnostic** selector (`:root` / `body[class]` rather than `body.Big`) so any template picks up the palette. Templates keep consuming `--mw-*` exactly as they do now — no per-template CSS rewrite required.

---

## 4. The pieces to move / build

1. **Catalog → Components.** Move `color-palettes.json` (split into `palettes/*.json`) and `style-packs/colors/*.json` into `Modules/Components/resources/color-schemes/`. Keep the JSON shape (`name`, `mainColors`, `properties`) so the picker UI is unchanged.
2. **`ColorSchemeRegistry` service.** A PHP service that returns the merged scheme list from: (a) the global `components::color-schemes` store, plus (b) any template-local `design-styles/colors` folder (back-compat / per-template extras). Global is the base; template adds/overrides by `name`.
3. **Global var-bridge.** Move the `body.Big` mapping in `_color-scheme-fix.scss` into `Modules/Components/resources/css/color-schemes.scss`, re-scoped template-agnostically, and ship it in a globally-loaded bundle (so Bootstrap gets it too). Establish the canonical palette-property → `--mw-*` map here (the single place the two naming layers meet).
4. **`<x-color-scheme>` (optional but tidy).** A component that emits the active scheme's `:root { … }` block on the public page, reading the persisted selection — so the runtime application isn't bound to template Blade.

---

## 5. Loading mechanism changes

- **Server-side (`template-setting-render-color-palette-item.blade.php`):** replace the `templates_dir() . template_name() . DS . $folder` glob with a call into `ColorSchemeRegistry` that returns the **global** catalog first, then merges any template-folder extras. The `colorPaletteFromTemplateFolderLibrary` field setting becomes optional (template-extras only); the default source is the global namespace.
- **Field declaration:** introduce a `colorPaletteFromGlobalLibrary: true` (default) on the color-palette field so templates opt into the shared catalog without each pointing at its own folder. Keep `colorPaletteFromTemplateFolderLibrary` as an additive override.
- **JS (`FieldColorPalette.vue` / `color-palette-manager.js`):** point the fetch at the global `components::color-schemes` asset URL instead of the template asset path. The apply mechanism (`setPropertyForSelectorBulk(':root', …)`) is unchanged — it already writes to `:root`, which is template-agnostic.

---

## 6. Persistence — make the active scheme global

Today the selection rides in `template_settings` (template-scoped). Decide:
- **Option A (recommended):** persist the active scheme as a **global option** (e.g. `option('current_color_scheme', 'template')` or a dedicated `components` group) so it survives template switches. The applied `:root` block is emitted globally.
- **Option B (minimal):** keep it in `template_settings` but source the catalog globally. Simpler, but switching templates loses the palette.

Recommend A for a true "global color schemes" experience; it's a small option-key + emit change.

---

## 7. Backward compatibility & migration phases

The Big template currently works — the migration must keep it working and not regress Bootstrap.

- **Phase 1 — establish the namespace (no behavior change):** create `Modules/Components/resources/color-schemes/` + `ColorSchemeRegistry` + `config('components.color-schemes')`; copy (don't move yet) the Big catalog in. Register the asset/view namespace. Ship `color-schemes.scss` bridge but don't yet load it globally. *Verifiable: the registry returns 134 schemes; nothing on the site changes.*
- **Phase 2 — flip the loader to global-first:** update the server-render + `FieldColorPalette.vue` fetch to read from the registry (global + template extras). Make the global bridge SCSS load on all templates. Big keeps its `body.Big` bridge during transition (harmless duplicate). *Verifiable: Bootstrap now shows the palette picker and recolors; Big unchanged.*
- **Phase 3 — globalize persistence (Option A)** + emit the active scheme via `<x-color-scheme>`. *Verifiable: switch template → palette persists.*
- **Phase 4 — retire the Big-local copy + `_color-scheme-fix.scss`:** delete the duplicated catalog from `Templates/Big/`, remove the `body.Big`-scoped bridge (now global). Keep a thin template hook only for genuinely Big-only overrides. *Verifiable: Big still recolors using only the global path.*

Each phase is independently shippable and reversible.

---

## 8. Risks & mitigations

| Risk | Mitigation |
|---|---|
| **Var-mapping divergence** — Bootstrap consumes a different `--mw-*` set than Big, so a global bridge mis-colors one. | Audit both templates' consumed `--mw-*` tokens first; the canonical map in `color-schemes.scss` must cover the union. Verify per-template in the browser. |
| **Specificity / cascade** — the old `body.Big` bridge won over `public-touch.css` by specificity; a `:root`-level global bridge may lose to those high-specificity public-touch rules (the documented Stage-2 cascade-loss family). | Match or beat public-touch specificity in the global bridge (scope to `body` with the same `:not(.btn)…` selectors), and load order after public-touch. Pin with a contract test. |
| **Build pipelines** — the bridge must ship in a globally-loaded bundle across BOTH Vite (frontend-assets) and the template builds. | Put the global bridge in a frontend-assets bundle loaded on every public page; rebuild + verify served bytes (the stale-bundle footgun). |
| **Persistence migration** — existing sites have the palette in `template_settings`. | Phase-3 migration reads the old key as a fallback and writes the new global option; idempotent. |
| **134-file split** — splitting one JSON into per-file palettes risks transcription errors. | Script the split + assert count/parity (134 in, 134 out, byte-equal `properties`). |

---

## 9. File-by-file change list (when implemented)

**New (Components):** `resources/color-schemes/palettes/*.json`, `resources/color-schemes/packs/*.json`, `resources/color-schemes/schema.json`, `resources/css/color-schemes.scss`, `Support/ColorSchemeRegistry.php`, `config/components.php` (color-schemes paths), optional `View/Components/ColorScheme.php` + `resources/views/components/color-scheme.blade.php`; register the `components::color-schemes` namespace + asset publish in `ComponentsServiceProvider`.
**Changed:** `…/live-edit/template-setting-render-color-palette-item.blade.php` (load via registry), `…/FieldColorPalette.vue` + `color-palette-manager.js` (global fetch URL), `LiveEditTemplateSettingsSidebar.php` (global persistence, Phase 3), template `style-settings.json` (`colorPaletteFromGlobalLibrary: true`).
**Retired (Phase 4):** `Templates/Big/resources/assets/color-palettes.json`, `Templates/Big/resources/assets/design-styles/style-packs/colors/*`, the `body.Big` block in `Templates/Big/resources/assets/css/scss/_color-scheme-fix.scss`.

---

## 10. Verification protocol (per phase)
1. **Registry** — `ColorSchemeRegistry` returns 134 global schemes (+ template extras); a contract test pins the count + that each scheme has `name`/`mainColors`/`properties`.
2. **Picker** — the Live Edit color-palette picker shows the global catalog on **both** Bootstrap and Big.
3. **Apply** — selecting a palette recolors primary/links/buttons/header/footer on **both** templates (browser, light + the documented `body.fi-panel-*`/dark surfaces where relevant).
4. **Persist** — reload + template-switch keeps the palette (Phase 3+).
5. **No regression** — Big looks identical to today; Bootstrap gains schemes; the cascade-specificity contract test (vs `public-touch.css`) stays green.

---

## 11. TL;DR
- **Today:** 134 color schemes live in `Templates/Big/` and are loaded **per active template** (`templates_dir().template_name().design-styles/colors`), with a `body.Big`-scoped var-bridge — so only Big has them.
- **Target:** move the catalog + a canonical property→`--mw-*` bridge into **`Modules/Components/resources/color-schemes/`** behind a `components::color-schemes` namespace + `ColorSchemeRegistry`, loaded **globally** for every template, with the active scheme persisted globally.
- **How:** 4 reversible phases — establish namespace → flip loader global-first → globalize persistence → retire the Big-local copy. The picker UI and the `:root`-based apply mechanism stay; the work is the loader source, the canonical var-map, and where the bridge CSS ships.
- **Watch:** the cascade-specificity fight with `public-touch.css` (re-create Big's winning selectors globally) and the two build pipelines (the global bridge must ship on every public page).
