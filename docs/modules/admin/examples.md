# Examples

Four end-to-end recipes for common Admin-package integrations.

---

## 1. Build a custom Admin Settings page for a new package

Suppose your package needs admin-editable settings (`widget_default_color`, `widget_show_thumbnail`, `widget_max_items`). The whole flow:

### Create the page class

```php
namespace YourPackage\Filament\Admin\Pages;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class WidgetSettingsPage extends AdminSettingsPage
{
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Widget Settings';
    protected static ?string $navigationIcon  = 'heroicon-o-puzzle-piece';
    protected static ?string $title           = 'Widget Settings';
    protected static ?int    $navigationSort  = 50;

    protected array $optionGroups = ['widget'];

    protected function getFormSchema(): array
    {
        return [
            Section::make('Appearance')
                ->description('Default visual properties for new widgets.')
                ->schema([
                    ColorPicker::make('widget_default_color')
                        ->label('Default colour')
                        ->default('#0d6efd'),

                    Toggle::make('widget_show_thumbnail')
                        ->label('Show thumbnail by default')
                        ->default(true),
                ]),

            Section::make('Limits')
                ->schema([
                    TextInput::make('widget_max_items')
                        ->label('Maximum items per widget')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(100)
                        ->default(10),
                ]),
        ];
    }
}
```

### Register the page

```php
// YourPackage\Providers\WidgetServiceProvider::boot()

use MicroweberPackages\Filament\FilamentRegistry;

public function boot(): void
{
    FilamentRegistry::registerPage(\YourPackage\Filament\Admin\Pages\WidgetSettingsPage::class);
}
```

### Read the saved values elsewhere

```php
use MicroweberPackages\Option\Models\Option;

$color = Option::getValue('widget_default_color', 'widget');           // '#0d6efd'
$show  = Option::getValue('widget_show_thumbnail', 'widget') === 'y'; // bool
$max   = (int) Option::getValue('widget_max_items', 'widget');         // 10
```

The page itself does NOT need a save handler — `AdminSettingsPage::updated()` writes per-field on every change via Livewire reactivity. Cache invalidation (5-min TTL) is automatic.

---

## 2. Register a custom Filament Resource into the admin panel

Your package owns a `Widget` model and wants a full Filament admin CRUD for it.

```php
// YourPackage\Filament\Admin\Resources\WidgetResource.php

namespace YourPackage\Filament\Admin\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use YourPackage\Models\Widget;

class WidgetResource extends Resource
{
    protected static ?string $model = Widget::class;
    protected static ?string $navigationGroup = 'Modules';
    protected static ?string $navigationLabel = 'Widgets';
    protected static ?string $navigationIcon  = 'heroicon-o-puzzle-piece';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('color')->default('#0d6efd'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('color'),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWidgets::route('/'),
            'create' => Pages\CreateWidget::route('/create'),
            'edit'   => Pages\EditWidget::route('/{record}/edit'),
        ];
    }
}
```

Register in the same provider's `boot()`:

```php
FilamentRegistry::registerResource(\YourPackage\Filament\Admin\Resources\WidgetResource::class);
```

The resource appears under the "Modules" navigation group at `/admin/widgets/*` automatically. No Admin-package edits.

---

## 3. Replace the brand logo and primary color palette

Branding changes for a white-label deployment:

### Override the logo

```php
// In a fresh-install seeder OR a deploy-time artisan command:

use MicroweberPackages\Option\Models\Option;

Option::setValue('admin_logo', asset('img/brand-b-logo.svg'),     'website');
Option::setValue('brand_name', 'Brand B Dashboard',                'website');
```

`app()->ui->admin_logo()` reads the option immediately.

### Override the primary color palette

The cleanest path is via the `microweber-filament-theme` package's CSS variables. Edit (or override-by-publish):

```css
/* packages/microweber-filament-theme/resources/css/theme.css */

:root {
    --primary-50:  255 240 235;
    --primary-100: 255 220 205;
    --primary-200: 255 180 145;
    --primary-300: 255 140 90;
    --primary-400: 255 110 60;
    --primary-500: 255 87 51;    /* hot orange */
    --primary-600: 230 75 40;
    --primary-700: 200 60 30;
    --primary-800: 160 45 22;
    --primary-900: 120 30 15;
    --primary-950: 80 18 8;
}
```

Rebuild the theme:

```bash
cd packages/microweber-filament-theme && npm run build
```

The admin panel now uses your custom orange instead of Bootstrap blue. The `MwColors::$Blue` static is still the array Filament reads, but its RGB triplets are surfaced via the `--primary-*` CSS variables — your CSS override wins at render time.

For a programmatic override (less common, more fragile), assign to the static directly from a service provider that boots AFTER `AdminServiceProvider`:

```php
\MicroweberPackages\Admin\Filament\MwColors::$Blue = [
    50  => '255, 240, 235',
    // ...
    500 => '255, 87, 51',
    // ...
];
```

This works but is harder to maintain — prefer the CSS-variable path.

---

## 4. Scope a render hook to specific resources

Suppose you want a sidebar promo to appear only on Order-related admin pages:

```php
// YourPackage\Providers\YourServiceProvider::boot()

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

FilamentView::registerRenderHook(
    PanelsRenderHook::SIDEBAR_NAV_END,
    fn() => view('yourpackage::admin.partials.order-promo'),
    scopes: [
        \Modules\Order\Filament\Admin\Resources\OrderResource::class,
        \Modules\Order\Filament\Admin\Resources\OrderResource\Pages\ListOrders::class,
        \Modules\Order\Filament\Admin\Resources\OrderResource\Pages\EditOrder::class,
    ],
);
```

The promo Blade renders only when the user is on an Order resource page. On every other admin page, the hook produces nothing.

For a table-toolbar enhancement that only fires for one resource list:

```php
FilamentView::registerRenderHook(
    \Filament\Tables\View\TablesRenderHook::TOOLBAR_END,
    fn() => view('yourpackage::admin.partials.order-bulk-export'),
    scopes: [\Modules\Order\Filament\Admin\Resources\OrderResource\Pages\ListOrders::class],
);
```

This is the pattern Admin's `FilamentAdminPanelProvider` itself uses to render the category tree only on Content/Post/Product list pages — see the `TOOLBAR_SEARCH_BEFORE` registration in the provider's `panel()` method.
