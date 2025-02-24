# Creating a Microweber Module

This guide explains how to create a new module for Microweber using Laravel 11, Filament v3, and Livewire v3.

## Module Structure

A typical module structure looks like this:

```
ModuleName/
├── composer.json          # Composer configuration and autoloading
├── config/               # Module configuration files
├── Filament/             # Filament admin panel components
├── Microweber/           # Module-specific implementations
├── module.json           # Module metadata and provider registration
├── Providers/            # Service providers
├── README.md            # Module documentation
├── resources/           # Views, assets, and translations
└── Tests/               # Module tests
```

## Module Configuration

### 1. composer.json

The composer.json file configures autoloading and module metadata:

```json
{
    "name": "modules/module_name",
    "description": "Your module description",
    "authors": [
        {
            "name": "Your Name",
            "email": "your@email.com"
        }
    ],
    "autoload": {
        "psr-4": {
            "Modules\\ModuleName\\": "",
            "Modules\\ModuleName\\Database\\Factories\\": "database/factories/",
            "Modules\\ModuleName\\Database\\Seeders\\": "database/seeders/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Modules\\ModuleName\\Tests\\": "Tests/"
        }
    }
}
```

### 2. module.json

The module.json file defines the module's basic information and service providers:

```json
{
    "name": "ModuleName",
    "alias": "module_name",
    "description": "Your module description",
    "keywords": [],
    "priority": 0,
    "providers": [
        "Modules\\ModuleName\\Providers\\ModuleNameServiceProvider"
    ],
    "files": []
}
```

## Service Provider Registration

Create a service provider that extends `BaseModuleServiceProvider`. This provider handles module registration, views, and Filament integration:

```php
<?php

namespace Modules\ModuleName\Providers;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\ModuleName\Filament\ModuleNameSettings;
use Modules\ModuleName\Livewire\ModuleNameComponent;
use Modules\ModuleName\Microweber\ModuleNameModule;

class ModuleNameServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'ModuleName';
    protected string $moduleNameLower = 'module_name';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        // Register Livewire component
        Livewire::component('module-' . $this->moduleNameLower, ModuleNameComponent::class);

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(ModuleNameSettings::class);

        // Register Microweber module
        Microweber::module(ModuleNameModule::class);
    }
}
```

## Filament Integration

### 1. Create Module Settings

Create a settings page in the `Filament` directory by extending `LiveEditModuleSettings`:

```php
<?php

namespace Modules\ModuleName\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class ModuleNameSettings extends LiveEditModuleSettings
{
    public string $module = 'module_name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        // Content Tab
                        Tabs\Tab::make('Content')
                            ->schema([
                                TextInput::make('options.title')
                                    ->label('Title')
                                    ->helperText('Enter the title for your module.')
                                    ->live()
                                    ->default('My Module'),

                                MwLinkPicker::make('options.url')
                                    ->label('Link')
                                    ->helperText('Select or enter a URL.')
                                    ->live()
                                    ->setSimpleMode(true)
                                    ->columnSpanFull(),

                                Toggle::make('options.enabled')
                                    ->label('Enable Module')
                                    ->helperText('Toggle to enable/disable the module.')
                                    ->live()
                                    ->default(true),
                            ]),

                        // Design Tab
                        Tabs\Tab::make('Design')
                            ->schema([
                                Section::make('Style Settings')
                                    ->schema([
                                        ColorPicker::make('options.backgroundColor')
                                            ->label('Background Color')
                                            ->live(),

                                        ToggleButtons::make('options.align')
                                            ->label('Alignment')
                                            ->helperText('Choose the alignment.')
                                            ->live()
                                            ->options([
                                                'left' => 'Left',
                                                'center' => 'Center',
                                                'right' => 'Right',
                                            ])
                                            ->icons([
                                                'left' => 'heroicon-o-bars-3-bottom-left',
                                                'center' => 'heroicon-o-bars-3',
                                                'right' => 'heroicon-o-bars-3-bottom-right',
                                            ]),

                                        MwIconPicker::make('options.icon')
                                            ->label('Icon')
                                            ->helperText('Select an icon.')
                                            ->live(),
                                    ]),

                                // Add template settings
                                Section::make('Design settings')->schema(
                                    $this->getTemplatesFormSchema()
                                ),

                                // Add custom settings
                                $this->getCustomSettingsFormSchema(),
                            ])
                    ])
            ]);
    }

    private function getCustomSettingsFormSchema()
    {
        return Section::make('Advanced settings')
            ->schema([
                Select::make('options.size')
                    ->label('Size')
                    ->live()
                    ->options([
                        'small' => 'Small',
                        'medium' => 'Medium',
                        'large' => 'Large',
                    ])
                    ->default('medium'),

                TextInput::make('options.customClass')
                    ->label('Custom CSS Class')
                    ->live(),
            ])
            ->collapsed();
    }
}
```

### 2. Module Implementation

You can implement your module in one of two ways:

#### Option 1: Using Livewire (Dynamic Interactivity)

If your module needs dynamic frontend interactivity, create a Livewire component:

```php
<?php

namespace Modules\ModuleName\Http\Livewire;

use Livewire\Component;

class ModuleNameViewComponent extends Component
{
    public function render()
    {
        return view('module-name::livewire.index');
    }
}
```

#### Option 2: Using BaseModule (Standard Rendering)

For simpler modules without dynamic frontend needs, extend BaseModule:

```php
<?php

namespace Modules\ModuleName\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\ModuleName\Filament\ModuleNameSettings;

class ModuleNameModule extends BaseModule
{
    public static string $name = 'Your Module Name';
    public static string $module = 'module_name';
    public static string $icon = 'modules.module-name-icon';
    public static string $categories = 'content';
    public static int $position = 30;
    public static string $settingsComponent = ModuleNameSettings::class;
    public static string $templatesNamespace = 'modules.module-name::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        
        // Add your module-specific data
        $viewData['customData'] = [
            'title' => 'Default Title',
            'content' => 'Default Content'
        ];

        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
```

When using BaseModule:
1. Define static properties for module configuration
2. Implement the render() method to handle view logic
3. Use getViewData() to access module parameters
4. Return a view with the appropriate template

## Templates and Views

### Module Templates

Templates should be placed in `resources/views/templates/` directory of your module. You can have multiple templates (default.blade.php, bootstrap.blade.php, etc.).

#### 1. Livewire Template Example

When using Livewire, your template must include the Livewire component:

```php
{{-- resources/views/templates/default.blade.php --}}
@php
    $moduleId = $id ?? null;
@endphp

<div class="module-shop">
    <livewire:module-shop :module-id="$moduleId" />
</div>
```

#### 2. Standard Template Example

For modules without Livewire, templates directly handle the rendering:

```php
{{-- resources/views/templates/bootstrap.blade.php --}}
@php
/*
type: layout
name: Bootstrap
description: Bootstrap template
*/
@endphp

@include('modules.module-name::components.custom-css')

<div class="module-content">
    @if($action == 'default')
        <a href="{{ $url }}" @if ($blank) target="_blank" @endif 
           class="{{ $style . ' ' . $size . ' ' . $class }}" {!! $attributes !!}>
            {{ $text }}
        </a>
    @else
        {{-- Add other conditional rendering --}}
    @endif
</div>
```

### Views and Assets

1. Place your views in:
   - `resources/views/templates/` for module templates
   - `resources/views/components/` for reusable components
   - `resources/views/livewire/` for Livewire component views
2. Use the registered namespace for your views (e.g., `module-name::view-name`)
3. Store assets in `resources/assets/`

## Installation Commands

After creating your module, you can publish its assets and run migrations:

```bash
php artisan module:publish ModuleName
php artisan module:migrate ModuleName
```

## Best Practices

1. Follow Laravel and Filament naming conventions
2. Use Livewire components for dynamic frontend functionality
3. Implement proper service provider registration
4. Organize views and assets in the resources directory
5. Include comprehensive documentation in README.md
6. Write tests in the Tests directory
