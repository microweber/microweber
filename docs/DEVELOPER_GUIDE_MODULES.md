# Microweber Module Development Guide

A comprehensive guide for developers building modules for Microweber CMS.

## Table of Contents

1. [Module Architecture](#module-architecture)
2. [Getting Started](#getting-started)
3. [Module Structure](#module-structure)
4. [Service Providers](#service-providers)
5. [Models and Database](#models-and-database)
6. [Filament Resources](#filament-resources)
7. [Frontend Components](#frontend-components)
8. [Testing](#testing)
9. [API Development](#api-development)
10. [Best Practices](#best-practices)

---

## Module Architecture

Microweber uses a modular architecture built on Laravel 11, Filament v5, and Livewire v4. Each module is a self-contained unit with its own:

- Models and database migrations
- Service providers for dependency injection
- Filament resources for admin panels
- Frontend views and components
- Configuration and translations
- Tests

### Core Concepts

**BaseModuleServiceProvider**: All module service providers extend this base class, which provides:
- Automatic view registration
- Translation loading
- Configuration merging
- Migration loading
- Blade component namespace registration

**BaseModule**: Frontend modules extend this abstract class for:
- Template rendering
- Options management
- Parameter handling
- Template namespace resolution

**Filament Resources**: Admin interface components for:
- CRUD operations
- Form schemas
- Table configurations
- Actions and bulk operations

---

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/PostgreSQL/SQLite

### Creating a New Module

Modules are located in the `/Modules` directory. To create a module manually:

```bash
mkdir -p Modules/YourModule/{Providers,Models,Filament/Admin/Resources,database/migrations,resources/views/templates,Tests/Unit}
```

---

## Module Structure

A complete module structure:

```
YourModule/
├── composer.json                 # Composer autoloading
├── module.json                   # Module metadata
├── README.md                     # Module documentation
│
├── Providers/
│   └── YourModuleServiceProvider.php    # Service provider
│
├── Models/
│   └── YourModel.php             # Eloquent models
│
├── Filament/
│   └── Admin/
│       └── Resources/
│           └── YourResource.php    # Admin resources
│
├── database/
│   └── migrations/
│       └── 2024_01_01_000001_create_your_table.php
│
├── resources/
│   └── views/
│       ├── templates/
│       │   └── default.blade.php   # Frontend templates
│       └── components/
│           └── custom-css.blade.php
│
├── config/
│   └── config.php                # Module config
│
└── Tests/
    └── Unit/
        └── YourModuleTest.php    # Unit tests
```

### Module Configuration Files

#### composer.json

```json
{
    "name": "modules/your_module",
    "description": "Your module description",
    "authors": [
        {
            "name": "Your Name",
            "email": "your@email.com"
        }
    ],
    "autoload": {
        "psr-4": {
            "Modules\\YourModule\\": "",
            "Modules\\YourModule\\Database\\Factories\\": "database/factories/",
            "Modules\\YourModule\\Database\\Seeders\\": "database/seeders/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Modules\\YourModule\\Tests\\": "Tests/"
        }
    }
}
```

#### module.json

```json
{
    "name": "YourModule",
    "alias": "your_module",
    "description": "Module description here",
    "keywords": ["feature", "module"],
    "priority": 0,
    "providers": [
        "Modules\\YourModule\\Providers\\YourModuleServiceProvider"
    ],
    "files": ["Support/helpers.php"]
}
```

**Priority**: Lower numbers load first. Use 0 for most modules.

---

## Service Providers

Service providers are the heart of module registration. They bootstraps the module into the application.

### Basic Service Provider

```php
<?php

namespace Modules\YourModule\Providers;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;

class YourModuleServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'YourModule';
    protected string $moduleNameLower = 'your_module';

    public function boot(): void
    {
        // Boot logic - runs after all providers registered
    }

    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        
        // Register Filament resources
        FilamentRegistry::registerResource(YourResource::class);
    }
}
```

### Service Registration

Register services in the container:

```php
public function register(): void
{
    // Singleton - one instance shared
    $this->app->singleton(YourService::class, function ($app) {
        return new YourService($app->make(Dependency::class));
    });
    
    // Bind - new instance each time
    $this->app->bind(YourInterface::class, YourImplementation::class);
    
    // Instance binding
    $this->app->instance('your_key', $value);
}
```

### Facade Registration

Create a facade for easy access:

```php
// In your service provider
$this->app->singleton('your_module', function ($app) {
    return new YourModuleManager($app);
});
```

```php
<?php

namespace Modules\YourModule\Facades;

use Illuminate\Support\Facades\Facade;

class YourModule extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'your_module';
    }
}
```

Usage:
```php
use Modules\YourModule\Facades\YourModule;

YourModule::doSomething();
```

---

## Models and Database

### Creating Models

Models follow Laravel Eloquent conventions:

```php
<?php

namespace Modules\YourModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class YourModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'your_table';
    
    protected $fillable = [
        'name',
        'description',
        'status',
        'user_id',
        'settings',
    ];
    
    protected $casts = [
        'settings' => 'json',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];
    
    protected $dates = [
        'published_at',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function items(): HasMany
    {
        return $this->hasMany(YourModelItem::class);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Accessors
    public function getFormattedNameAttribute(): string
    {
        return ucfirst($this->name);
    }
    
    // Mutators
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = strtolower($value);
    }
}
```

### Migrations

Create migrations in `database/migrations/`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('your_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index(['is_active', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('your_table');
    }
};
```

### Model Factories

Create factories in `database/factories/`:

```php
<?php

namespace Modules\YourModule\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\YourModule\Models\YourModel;

class YourModelFactory extends Factory
{
    protected $model = YourModel::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'status' => 'draft',
            'user_id' => User::factory(),
            'settings' => null,
            'is_active' => true,
        ];
    }
    
    public function published(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}
```

---

## Filament Resources

Filament resources provide the admin interface for managing module data.

### Creating a Resource

```php
<?php

namespace Modules\YourModule\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\YourModule\Models\YourModel;
use Modules\YourModule\Filament\Admin\Resources\YourResource\Pages;

class YourResource extends Resource
{
    protected static ?string $model = YourModel::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Your Modules';
    
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                            
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ])
                            ->required(),
                    ]),
                    
                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                            
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->native(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                        'warning' => 'archived',
                    ]),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListYourModels::route('/'),
            'create' => Pages\CreateYourModel::route('/create'),
            'edit' => Pages\EditYourModel::route('/{record}/edit'),
        ];
    }
}
```

### Resource Pages

Create in `Filament/Admin/Resources/YourResource/Pages/`:

**ListYourModels.php**:
```php
<?php

namespace Modules\YourModule\Filament\Admin\Resources\YourResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\YourModule\Filament\Admin\Resources\YourResource;

class ListYourModels extends ListRecords
{
    protected static string $resource = YourResource::class;
}
```

**CreateYourModel.php**:
```php
<?php

namespace Modules\YourModule\Filament\Admin\Resources\YourResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\YourModule\Filament\Admin\Resources\YourResource;

class CreateYourModel extends CreateRecord
{
    protected static string $resource = YourResource::class;
}
```

**EditYourModel.php**:
```php
<?php

namespace Modules\YourModule\Filament\Admin\Resources\YourResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\YourModule\Filament\Admin\Resources\YourResource;

class EditYourModel extends EditRecord
{
    protected static string $resource = YourResource::class;
}
```

### Advanced Form Components

#### Tabs

```php
Forms\Components\Tabs::make('Settings')
    ->tabs([
        Forms\Components\Tabs\Tab::make('General')
            ->schema([/* ... */]),
        Forms\Components\Tabs\Tab::make('Advanced')
            ->schema([/* ... */]),
    ])
```

#### Repeater

```php
Forms\Components\Repeater::make('items')
    ->schema([
        Forms\Components\TextInput::make('name')->required(),
        Forms\Components\TextInput::make('value')->required(),
    ])
    ->addActionLabel('Add Item')
    ->collapsible()
```

#### Relationship Select

```php
Forms\Components\Select::make('user_id')
    ->relationship('user', 'name')
    ->searchable()
    ->preload()
```

---

## Frontend Components

### Basic Module Template

Create in `resources/views/templates/default.blade.php`:

```php
@php
/*
type: layout
name: Default
description: Default template
*/
@endphp

<div class="your-module-container">
    <h2>{{ $options['title'] ?? 'Default Title' }}</h2>
    <div class="content">
        {!! $options['content'] ?? '' !!}
    </div>
</div>
```

### Module with Settings

Create a settings component in `Filament/YourModuleSettings.php`:

```php
<?php

namespace Modules\YourModule\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class YourModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'your_module';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('options.title')
                    ->label('Title')
                    ->live()
                    ->default('My Module'),
                    
                Textarea::make('options.content')
                    ->label('Content')
                    ->live()
                    ->columnSpanFull(),
                    
                Toggle::make('options.show_header')
                    ->label('Show Header')
                    ->live()
                    ->default(true),
            ]);
    }
}
```

### BaseModule Implementation

```php
<?php

namespace Modules\YourModule\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\YourModule\Filament\YourModuleSettings;

class YourModule extends BaseModule
{
    public static string $name = 'Your Module';
    public static string $module = 'your_module';
    public static string $icon = 'heroicon-o-rectangle-stack';
    public static string $categories = 'content';
    public static int $position = 30;
    public static string $settingsComponent = YourModuleSettings::class;
    public static string $templatesNamespace = 'modules.your-module::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $template = $viewData['template'] ?? 'default';
        
        // Add custom data
        $viewData['customData'] = [
            'processed_at' => now(),
        ];
        
        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
```

### Livewire Component

For dynamic frontend functionality:

```php
<?php

namespace Modules\YourModule\Livewire;

use Livewire\Component;

class YourLivewireComponent extends Component
{
    public $items = [];
    public $selectedItem = null;
    
    protected $listeners = ['itemUpdated' => 'refreshItems'];

    public function mount()
    {
        $this->items = YourModel::active()->get();
    }
    
    public function selectItem($id)
    {
        $this->selectedItem = YourModel::find($id);
    }
    
    public function refreshItems()
    {
        $this->items = YourModel::active()->get();
    }

    public function render()
    {
        return view('modules.your-module::livewire.component');
    }
}
```

Register in service provider:

```php
use Livewire\Livewire;

public function register(): void
{
    Livewire::component('your-module-component', YourLivewireComponent::class);
}
```

---

## Testing

### Unit Tests

Create tests in `Tests/Unit/`:

```php
<?php

namespace Modules\YourModule\Tests\Unit;

use Tests\TestCase;
use Modules\YourModule\Models\YourModel;
use Modules\YourModule\Services\YourService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YourModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_model()
    {
        $model = YourModel::factory()->create([
            'name' => 'Test Name',
        ]);
        
        $this->assertDatabaseHas('your_table', [
            'name' => 'Test Name',
        ]);
    }
    
    public function test_service_performs_action()
    {
        $service = app(YourService::class);
        $result = $service->performAction();
        
        $this->assertTrue($result);
    }
    
    public function test_model_scopes_work()
    {
        YourModel::factory()->count(3)->create(['is_active' => true]);
        YourModel::factory()->count(2)->create(['is_active' => false]);
        
        $activeCount = YourModel::active()->count();
        
        $this->assertEquals(3, $activeCount);
    }
}
```

### Filament Resource Tests

```php
<?php

namespace Modules\YourModule\Tests\Unit\Filament;

use Tests\TestCase;
use Modules\YourModule\Models\YourModel;
use Modules\YourModule\Filament\Admin\Resources\YourResource;
use Filament\Actions\CreateAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YourResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->createAdminUser());
    }

    public function test_resource_configuration()
    {
        $this->assertEquals(YourModel::class, YourResource::$model);
        $this->assertNotNull(YourResource::$navigationIcon);
    }
    
    public function test_can_list_records()
    {
        YourModel::factory()->count(5)->create();
        
        $response = $this->get(YourResource::getUrl('index'));
        
        $response->assertSuccessful();
    }
    
    public function test_can_create_record()
    {
        $data = [
            'name' => 'Test Name',
            'status' => 'published',
        ];
        
        $this->post(YourResource::getUrl('create'), $data)
            ->assertRedirect();
        
        $this->assertDatabaseHas('your_table', $data);
    }
}
```

### Running Tests

```bash
# Run all module tests
php artisan test --filter=YourModule

# Run specific test file
php artisan test Modules/YourModule/Tests/Unit/YourModuleTest.php

# With coverage
php artisan test --filter=YourModule --coverage
```

---

## API Development

### Creating API Controllers

```php
<?php

namespace Modules\YourModule\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\YourModule\Models\YourModel;
use Modules\YourModule\Http\Resources\YourResource;

class YourApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = YourModel::query()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate($request->per_page ?? 15);
            
        return response()->json([
            'data' => YourResource::collection($items),
            'meta' => [
                'total' => $items->total(),
                'current_page' => $items->currentPage(),
            ],
        ]);
    }
    
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
        ]);
        
        $item = YourModel::create($validated);
        
        return response()->json([
            'data' => new YourResource($item),
            'message' => 'Created successfully',
        ], 201);
    }
    
    public function show(YourModel $item): JsonResponse
    {
        return response()->json([
            'data' => new YourResource($item),
        ]);
    }
    
    public function update(Request $request, YourModel $item): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:draft,published,archived',
        ]);
        
        $item->update($validated);
        
        return response()->json([
            'data' => new YourResource($item),
            'message' => 'Updated successfully',
        ]);
    }
    
    public function destroy(YourModel $item): JsonResponse
    {
        $item->delete();
        
        return response()->json([
            'message' => 'Deleted successfully',
        ]);
    }
}
```

### API Resources

```php
<?php

namespace Modules\YourModule\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class YourResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ]),
        ];
    }
}
```

### API Routes

Create in `routes/api.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use Modules\YourModule\Http\Controllers\Api\YourApiController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('your-module', YourApiController::class);
});

// Public routes
Route::get('your-module/public', [YourApiController::class, 'publicIndex']);
```

Register in service provider:

```php
public function register(): void
{
    $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));
}
```

---

## Best Practices

### Code Organization

1. **Single Responsibility**: Each class should have one reason to change
2. **Namespace Conventions**: Use `Modules\YourModule\` prefix
3. **Directory Structure**: Follow Laravel conventions
4. **Service Layer**: Business logic in services, not controllers

### Security

1. **Input Validation**: Always validate user input
2. **Mass Assignment**: Use `$fillable`, never `$guarded = []`
3. **SQL Injection**: Use Eloquent or parameter binding
4. **XSS Protection**: Escape output with `{{ }}` or `e()`
5. **Authorization**: Use policies and gates

### Performance

1. **Eager Loading**: Use `with()` to avoid N+1 queries
2. **Caching**: Cache expensive operations
3. **Indexes**: Add database indexes for frequently queried columns
4. **Pagination**: Always paginate large datasets

### Testing

1. **Test Coverage**: Aim for >80% coverage
2. **Feature Tests**: Test user workflows
3. **Unit Tests**: Test individual classes
4. **Database**: Use `RefreshDatabase` or `DatabaseTransactions`

### Documentation

1. **README.md**: Module purpose and usage
2. **PHPDoc**: Document all public methods
3. **Examples**: Provide usage examples
4. **Changelog**: Track changes

---

## Module Commands

### Available Artisan Commands

```bash
# Run module migrations
php artisan module:migrate YourModule

# Run module migrations (force)
php artisan module:migrate YourModule --force

# Run module seeders
php artisan module:seed YourModule

# Publish module assets
php artisan module:publish YourModule

# Publish module config
php artisan module:publish-config YourModule

# Publish module translations
php artisan module:publish-translation YourModule

# Enable module
php artisan module:enable YourModule

# Disable module
php artisan module:disable YourModule
```

---

## Example: Complete Module

See existing modules in `/Modules` for real-world examples:

- **Content**: Basic content management
- **Product**: E-commerce product management
- **Order**: Order processing
- **Blog**: Blog posts and categories
- **Comments**: User commenting system
- **Media**: File upload and management

Each module demonstrates different aspects of the module system.

---

## Troubleshooting

### Common Issues

**Module not loading**:
- Check `module.json` syntax
- Verify service provider namespace
- Run `composer dump-autoload`

**Views not found**:
- Check view namespace registration
- Verify `resources/views/` directory structure
- Clear view cache: `php artisan view:clear`

**Migrations not running**:
- Check migration file names (must be valid dates)
- Verify `loadMigrationsFrom()` path
- Run `php artisan migrate --path=Modules/YourModule/database/migrations`

**Filament resources not showing**:
- Verify resource registration in service provider
- Check navigation icon and group
- Clear cache: `php artisan filament:cache-components`

---

## Resources

- [Laravel Documentation](https://laravel.com/docs/11.x)
- [Filament Documentation](https://filamentphp.com/docs)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Microweber API Docs](OPENAPI_DOCUMENTATION.md)

---

*Last Updated: 2026-03-23*
