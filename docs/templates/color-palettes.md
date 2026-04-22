# Color palette packs — JSON contract

This document is the authoring contract for Microweber template color
palette packs. Every pack file under

```
Templates/<template>/resources/assets/design-styles/style-packs/colors/<slug>.json
```

is parsed at live-edit time by the Vue picker (`FieldStylePack.vue`) and
applied to the canvas iframe via
`mw.top().app.cssEditor.setPropertyForSelectorBulk(':root', properties, true, true)`.
A pack that drifts from the shape below will silently fail to apply —
the swatch shows up in the sidebar, the click handler runs, but the
previous palette bleeds through on `:root`.

The shape is enforced by
[`tests/Unit/Template/ColorPaletteFilesTest`](../../tests/Unit/Template/ColorPaletteFilesTest.php).
Run it before opening a PR:

```bash
php artisan test --filter=ColorPaletteFilesTest
```

The Dusk palette suite (`php artisan dusk --group=color-palettes`)
exercises every pack end-to-end through the live-edit pipeline; pass
both layers and a palette is shippable.

---

## 1. File layout

- **Path:** `Templates/<template>/resources/assets/design-styles/style-packs/colors/<slug>.json`
- **Filename:** kebab-case slug, e.g. `neon-night.json`. The slug is
  what the picker uses internally to key the apply-click handler.
- **Character encoding:** UTF-8. Trailing newline optional, no BOM.

### Filename ↔ title parity

`settings[0].title` must kebab-case-normalize back to the filename
slug. The picker displays `title` as the swatch label *and* applies the
palette by slug; a mismatch means users see one name while the browser
applies a different palette.

Normalization (from `ColorPaletteFilesTest::toKebabCase`):

1. `trim()`
2. Best-effort ASCII transliteration via `iconv` (strips diacritics)
3. `strtolower`
4. Collapse runs of non-alphanumeric characters to single hyphens
5. Strip leading and trailing hyphens

Examples:

| `settings[0].title`    | Normalized slug    | Required filename      |
|------------------------|--------------------|------------------------|
| `Apple Shine`          | `apple-shine`      | `apple-shine.json`     |
| `Sunset Boulevard`     | `sunset-boulevard` | `sunset-boulevard.json`|
| `Robocop`              | `robocop`          | `robocop.json`         |
| `Coral_Pop`            | `coral-pop`        | `coral-pop.json`       |

---

## 2. Top-level JSON shape

```json
{
    "settings": [
        {
            "title": "Neon Night",
            "description": "A vibrant neon-inspired color palette.",
            "fieldType": "stylePack",
            "selectors": [":root"],
            "fieldSettings": {
                "styleProperties": [
                    {
                        "label": "Neon Night",
                        "properties": {
                            "--mw-background-color": "#f8f9ff",
                            "--mw-primary-color": "#94b3fd"
                        }
                    }
                ]
            }
        }
    ]
}
```

The root must be a JSON object with a single `settings` key whose value
is a non-empty array; `settings[0]` is the payload the picker reads.

### `settings[0].fieldType`

Must be the string literal `"stylePack"`. Anything else and the picker
does not register the pack as a swatch at all.

### `settings[0].selectors`

Array of CSS selectors the pack's custom properties are written to.
Must contain `":root"` exactly so the variables land on the document
root and cascade to every skin. Additional selectors are permitted but
at least `":root"` is required — without it the picker's bulk-apply
call has no target and every `--mw-*` write is a no-op.

### `settings[0].title`

Human-facing label. Rendered verbatim as the swatch label, must satisfy
the kebab-case parity rule above.

### `settings[0].description`

Optional short blurb. No runtime effect today, recommended for
contributor readability.

### `settings[0].fieldSettings.styleProperties[0].properties`

The CSS custom-property map the picker writes to `:root`. Every key
must begin with `--` and every value must parse as a valid CSS paint
token (see §4).

---

## 3. Core variable checklist

Every pack **must** declare these six CSS custom properties with
non-empty string values. They are the minimum set every skin reads.
Omit one and the previous palette's value bleeds through on that
property when a user picks this pack.

- `--mw-background-color`
- `--mw-primary-color`
- `--mw-body-color`
- `--mw-heading-color`
- `--mw-paragraph-color`
- `--mw-link-color`

The list is frozen by
`ColorPaletteFilesTest::core_variable_list_is_stable_and_non_empty` —
changing it is a deliberate breaking change that must land in that
file in the same commit.

### Extended variables

Shipped Bootstrap packs also cover button, form, header, top-header,
and section tokens. These are not gated by a core checklist but keeping
the same set across packs prevents per-skin bleed. See any shipped
pack (e.g. `Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/neon-night.json`)
for the conventional set:

- Button: `--mw-btn-background-color`, `--mw-btn-background-hover-color`,
  `--mw-btn-text-color`, `--mw-btn-text-hover-color`, `--mw-btn-border-color`
- Form controls: `--mw-form-control-background`, `--mw-form-control-border-color`
- Header: `--mw-header-background-color`, `--mw-header-link-color`,
  `--mw-header-link-hover-color`
- Top header: `--mw-top-header-background-color`, `--mw-top-header-link-color`,
  `--mw-top-header-link-hover-color`, `--mw-top-header-button-background-color`,
  `--mw-top-header-button-text-color`, `--mw-top-header-primary-color`
- Section: `--mw-section-background-color`
- Link hover: `--mw-link-hover-color`

---

## 4. Value grammar

Every value is parsed by
`ColorPaletteFilesTest::pack_property_values_parse_as_valid_css_color`.
Accepted forms:

- Hex: `#rgb`, `#rgba`, `#rrggbb`, `#rrggbbaa`
- Legacy RGB: `rgb(255, 255, 255)`, `rgba(0, 0, 0, 0.5)`
- Modern RGB slash syntax: `rgb(255 255 255 / 0.5)`
- HSL (both comma and slash forms): `hsl(120, 100%, 50%)`, `hsl(120 100% 50% / 0.5)`
- CSS named colors (the 148 CSS4 keywords, e.g. `rebeccapurple`, `whitesmoke`)
- Keywords: `transparent`, `currentColor`, `inherit`, `initial`, `unset`, `revert`, `none`
- `var(--another-variable)`, optionally with a fallback: `var(--mw-primary-color, #fff)`

**Leading or trailing whitespace in a value is a hard failure.** The CSS
parser keeps it; JSON diffs won't. The test flags this as its own
offender class.

### Gradients — `*-background-color` only

CSS image expressions — `linear-gradient(...)`, `radial-gradient(...)`,
`conic-gradient(...)` (and their `repeating-*` variants), plus `url(...)`
— are accepted **only on properties whose name ends in
`-background-color` or `-background-hover-color`**. Shipping a gradient
on `--mw-heading-color`, `--mw-link-color`, `--mw-body-color`, etc. is
a semantic error and fails the contract test.

Live examples of the allowed case live in `golden-hour.json` and
`robocop.json`.

---

## 5. Authoring a new pack — step by step

1. **Pick a slug.** Lowercase, hyphen-separated, filesystem-safe. This
   is the filename and the picker key.

2. **Create the file.**

   ```bash
   cp Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/apple-shine.json \
      Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/my-slug.json
   ```

   Starting from an existing pack guarantees the shape is valid — you
   only need to edit the content, not the surrounding JSON.

3. **Update `settings[0].title`** to match the slug under kebab-case
   normalization (title-case is conventional: `My Slug`).

4. **Update `properties`.** Keep all core variables populated (§3).
   Use the conventional extended set where possible (§3 extended list).
   Stick to hex/rgb literals unless there's a specific gradient-background
   design intent (§4).

5. **Run the contract test locally.**

   ```bash
   php artisan test --filter=ColorPaletteFilesTest
   ```

   Every data-provider row prefixes its slug, so a failure like

   ```
   FAIL  pack_declares_every_core_css_variable with data set "my-slug"
   ```

   points straight at the offending pack.

6. **Add the Dusk per-palette test.** Phase 3 has one Dusk test per
   pack asserting the pack applies every `--mw-*` variable to `:root`
   via the live-edit picker. The generator recipe lives in this folder
   alongside this doc (coming in the Phase-10 "How to add a new color
   pack" entry).

7. **Run the palette Dusk group.**

   ```bash
   php artisan dusk --group=color-palettes
   ```

   End-to-end validation. Any regression surfaces as a discrete step in
   CI (see `.github/workflows/dusk.yml`).

---

## 6. Failure modes the contract catches

The structural test file exists because each of these has bitten the
project at least once. If your pack fails one, start by reading the
assertion message — each one is self-describing.

| Symptom                                                     | Test that flags it                              |
|-------------------------------------------------------------|-------------------------------------------------|
| File missing or empty on disk                               | `pack_file_exists_and_is_readable`              |
| Invalid JSON                                                | `pack_file_parses_as_valid_json_object`         |
| Wrong `fieldType` or `fieldType` missing                    | `pack_settings_declare_style_pack_field_type`   |
| `selectors` missing or doesn't contain `":root"`            | `pack_selectors_contain_root`                   |
| `properties` empty, non-assoc, or using numeric keys        | `pack_style_properties_are_non_empty_assoc_array` |
| A property key missing the `--` prefix                      | `pack_property_keys_are_all_css_custom_properties` |
| A core variable missing or empty                            | `pack_declares_every_core_css_variable`         |
| Value not a valid CSS color (typo, stray whitespace, etc.)  | `pack_property_values_parse_as_valid_css_color` |
| Gradient applied to a non-`-background-color` property      | `pack_property_values_parse_as_valid_css_color` |
| Filename doesn't match title after kebab-case normalization | `pack_title_kebab_case_matches_filename_slug`   |

---

## 7. Cross-template packs

The above contract applies identically to any template that ships
color packs under
`Templates/<template>/resources/assets/design-styles/style-packs/colors/`.
The cross-template Dusk harness
(`tests/Browser/LiveEditColorPaletteCrossTemplateTest.php`) enumerates
every such folder automatically, so a sibling template (Big, Big2,
Dream, etc.) that drops a valid pack JSON is picked up with no code
change on the test side.

The parent template's live-edit sidebar resolves packs from the
active template's folder (see
`MicroweberPackages\Template\Http\Controllers\Api\TemplateStyleEditorSettingsController`),
so the pipeline is the same — only the source directory differs.
