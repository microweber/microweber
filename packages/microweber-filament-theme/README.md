# Microweber Filament Theme

The CSS/JS theme and color/icon configuration for Microweber's **Filament v5** admin panels (`admin`, `checkout`, `profile`, `vendor`). Built on Tailwind v4 with a bespoke `build.cjs` pipeline (~28k lines of CSS across ~156 source files). It is both a composer package (the PHP `Plugin` + provider) and an asset bundle you must build.

> See [`ARCHITECTURE.md`](ARCHITECTURE.md) for the full "where do I edit X?" map before changing any CSS.

## What it registers

- Registers the panel **font** (`DM Sans`), the Microweber **color palette** (`Palette::colors()` — Bootstrap blue `#0d6efd` primary, gray, secondary), and the `microweber-filament-theme` theme.
- Registers the compiled **CSS/JS assets** via `FilamentAsset` (`Theme::make(...)` + several `Js::make(...)` bundles incl. the media browser and tree components).
- Configures Filament **color shades** (badges, notification icons) and a large **icon** override map (`getIcons()`).
- Adds accessibility **render hooks**: a "Skip to main content" link (`BODY_START`) and a `#main-content` anchor (`CONTENT_START`).

## Installation

```bash
composer require microweber-packages/microweber-filament-theme
```

The service provider is auto-discovered. It publishes the built assets to
`public/vendor/microweber-packages/microweber-filament-theme` and calls
`MicroweberFilamentTheme::configure()` on boot.

Publish the compiled assets:

```bash
php artisan vendor:publish --tag=public
```

## Usage

The theme is applied as a Filament plugin from your panel provider:

```php
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;

public function panel(Panel $panel): Panel
{
    return $panel->plugin(new MicroweberFilamentTheme());
    // plugin id: 'microweber-filament-theme'
}
```

`MicroweberFilamentTheme::register()` sets the font, colors and theme; `boot()`
wires color shades, components, assets and the accessibility render hooks.

### Programmatic helpers

```php
MicroweberFilamentTheme::getColors(); // Palette::colors() — Blue / Gray / Secondary shade maps
MicroweberFilamentTheme::getIcons();  // the Filament icon override map
Palette::Blue;                        // const shade map [50 => '...', ..., 950 => '...']
```

## Building the theme assets

The dev server serves the **built** bundle, not the source — always rebuild after
editing anything under `resources/assets/**`:

```bash
cd packages/microweber-filament-theme
npm install
npm run build        # node build.cjs — webpack (JS) + sass + @tailwindcss/postcss
```

The compiled bundle is `resources/dist/build/microweber-filament-theme.css`
(also copied to `public/vendor/microweber-packages/microweber-filament-theme/build/`).
`build.cjs` also runs `broadenPanelScope()` so the `admin` panel's styling is shared
with the `profile` and `vendor` panels.

Where to edit (see `ARCHITECTURE.md` for the full table):

- Color / spacing / radius / font tokens → `resources/assets/css/tokens/*.css`
- A Filament component (button, table, modal, …) → `resources/assets/css/filament/<area>/…`
- General admin look → `resources/assets/css/microweber/general/NN-*.css` (split parts; do **not** edit the generated `general-styles.css`)
- Mobile / touch → `resources/assets/css/microweber/mobile/NN-*.css` (do **not** edit the generated `mobile-touch.css`)

## Key classes

- `MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme` — the Filament `Plugin` (colors, assets, icons, render hooks)
- `MicroweberPackages\MicroweberFilamentTheme\Palette` — the color palette (`Blue`, `Gray`, `Secondary`, `colors()`)
- `MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentThemeServiceProvider` — publishes assets, calls `configure()`

## Testing

```bash
composer test
```

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
