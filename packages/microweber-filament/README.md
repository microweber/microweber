# Microweber Filament

The core **Filament v5** integration for Microweber's admin panels. It boots the
admin panel theme + panel provider, registers Microweber's custom form components,
table columns, actions and plugins, and injects the admin chrome (meta tags,
Livewire scripts, AI chat trigger, table/row fixes) via Filament render hooks.

This is panel glue, not a standalone library — it depends on the rest of the
Microweber admin stack (`microweber-admin`, `microweber-filament-theme`,
`microweber-user`, `microweber-multilanguage`, `microweber-social-login`).

## What the service provider does

`MicroweberPackages\Filament\Providers\MicroweberFilamentServiceProvider`:

- Registers `MicroweberFilamentThemeServiceProvider` and `FilamentAdminPanelProvider`.
- Registers the `->mwDialog()`, `->slideRight()`, `->slideLeft()` Action macros (`RegistersMwDialogMacro`).
- Adds render hooks: Livewire scripts (`SCRIPTS_BEFORE`), admin meta tags (`HEAD_START` / `BODY_END`), the admin AI-chat sidebar + topbar trigger (admin panel, admins only), roomier/responsive table rows and empty-table cleanup (`STYLES_AFTER`), and a script that hides the stray `0` filter-count badge.
- Loads custom view namespaces: `filament-panels`, `mw-filament`, `filament-tables`, `filament-actions`, `filament-infolists`.

## Installation

```bash
composer require microweber-packages/microweber-filament
```

Part of a full Microweber install; register
`MicroweberPackages\Filament\Providers\MicroweberFilamentServiceProvider` (done for
you in a standard Microweber app).

## Usage

### Custom form components

All live under `MicroweberPackages\Filament\Forms\Components` and are used like any
Filament field inside a form/schema:

```php
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwColorPicker;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\Filament\Forms\Components\MwTree;

MwFileUpload::make('options.white_label.logo_admin')
    ->label('Logo for Admin Panel')
    ->image()
    ->live();

MwColorPicker::make('options.text_color');
MwIconPicker::make('icon');
MwLinkPicker::make('options.url');      // Microweber link/content picker
MwTree::make('mw_parent_page_and_category_state');
```

Also available: `MwMediaBrowser`, `MwInputSlider`, `MwRichEditor`,
`MwTitleWithSlugInput::make(...)`, `MwSelectTemplateForPage::make(...)`, `MwSlugInput`.

### Table columns

Under `MicroweberPackages\Filament\Tables\Columns`:

```php
use MicroweberPackages\Filament\Tables\Columns\BadgesColumn;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Filament\Tables\Columns\ClickableColumn;
use MicroweberPackages\Filament\Tables\Columns\SVGColumn;

BadgesColumn::make('badges')->badges(fn ($record) => $record->badges());
ImageUrlColumn::make('image');
```

### Actions & the mwDialog macro

```php
use MicroweberPackages\Filament\Actions\DeleteActionOnlyIcon;

// Icon-only delete action on a record page
DeleteActionOnlyIcon::make();
```

The provider registers three fluent Action macros (`RegistersMwDialogMacro`):

```php
// Decorate an action modal with mw.dialog (drag, autosize, close) WITHOUT
// tearing off Livewire bindings — used instead of slide-over.
EditAction::make()->mwDialog();
EditAction::make()->mwDialog(['width' => 900]);   // pass mw.dialog options
EditAction::make()->mwDialog(false);               // no-op (leave as a normal modal)

// Directional slide-overs
CreateAction::make()->slideRight();                // SlideOverPosition::End
CreateAction::make()->slideLeft();                 // SlideOverPosition::Start
```

### Plugins

```php
use MicroweberPackages\Filament\Plugins\FilamentTranslatableFieldsPlugin;
use MicroweberPackages\Filament\Plugins\MicroweberFilamentSocialitePlugin;

$panel
    ->plugin(FilamentTranslatableFieldsPlugin::make())
    ->plugin(MicroweberFilamentSocialitePlugin::make());
```

### Width enum (v5 compat)

`MicroweberPackages\Filament\Support\Enums\MaxWidth` is a back-compat enum for code
written against Filament's pre-v5 `MaxWidth` (renamed to `Width` in v5):
`ExtraSmall`, `Small`, `Medium`, `Large`, `ExtraLarge`, `TwoExtraLarge`, …

### Admin fixture guard

`MicroweberPackages\Filament\Support\AdminFixtureGuard` filters test/Faker/lorem
fixture rows out of admin listings and pickers:

```php
use MicroweberPackages\Filament\Support\AdminFixtureGuard;

AdminFixtureGuard::shouldRenderItem($title);        // false for obvious fixtures
AdminFixtureGuard::looksLikeFakerLorem($title);
$clean = AdminFixtureGuard::filterByTitle($rows, 'title');
```

### Middleware

`MicroweberPackages\Filament\Http\Middleware\AuthenticateAdmin` and
`AuthenticateUser` guard the admin / user panels.

## Key classes

- `src/Providers/MicroweberFilamentServiceProvider.php` — boots theme + panel, render hooks, view namespaces
- `src/Support/RegistersMwDialogMacro.php` — `mwDialog` / `slideRight` / `slideLeft` Action macros
- `src/Forms/Components/Mw*.php` — custom form fields (FileUpload, ColorPicker, IconPicker, LinkPicker, Tree, MediaBrowser, InputSlider, RichEditor, TitleWithSlugInput, SelectTemplateForPage)
- `src/Tables/Columns/*.php` — BadgesColumn, ImageUrlColumn, ClickableColumn, SVGColumn
- `src/Actions/*.php`, `src/Tables/Actions/ImportAction.php`, `src/MwFilamentImport.php` — actions + CSV/import helper
- `src/Plugins/*.php` — FilamentTranslatableFieldsPlugin, MicroweberFilamentSocialitePlugin
- `src/Support/Enums/MaxWidth.php` — Filament v5 width compat enum
- `src/Support/AdminFixtureGuard.php` — fixture/Faker row filtering

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Nikolay Rachkov
- Alexander Raikov
