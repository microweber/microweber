# Microweber Filament Documentation

Core components form the foundation of Filament in Microweber, providing reusable building blocks for forms and tables.

### Form Elements

Form elements allow users to input and manipulate data. Below are common examples with practical implementations.

#### Select Boxes

```php
// Basic Select
Select::make('status')
    ->options(['draft' => 'Draft', 'published' => 'Published'])
    ->searchable()
    ->native(false)
```

- **Purpose**: Creates a dropdown for selecting a status.
- **Features**: `searchable()` enables filtering, `native(false)` uses a custom UI.

#### Input Fields

```php
TextInput::make('email')
    ->email()
    ->required()
    ->maxLength(255)
    ->rules(['email:rfc,dns'])
    ->prefixIcon('heroicon-o-at-symbol')
```

- **Purpose**: Validates and captures an email address.
- **Features**: Enforces email format, adds a prefix icon, and limits length.

#### Dynamic Prefix Input

```php
TextInput::make('amount')
    ->numeric()
    ->prefix(fn($record) => $record?->currency_symbol ?? '$')
```

- **Purpose**: Numeric input with a dynamic currency prefix based on the record.

### Table Components

Tables display data in an organized, interactive manner.

#### Optimized Table Configuration

```php
public function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')
                ->searchable()
                ->sortable(),
            BadgeColumn::make('status')
                ->colors([
                    'success' => 'published',
                    'warning' => 'draft'
                ])
        ])
        ->defaultSort('created_at', 'desc')
        ->paginated([10, 25, 50])
}
```

- **Purpose**: Displays a sortable, searchable table with status badges.
- **Features**: Pagination options and default sorting by creation date.

#### Table Actions

```php
Tables\Actions\ActionGroup::make([
    Tables\Actions\ViewAction::make(),
    Tables\Actions\EditAction::make(),
    Tables\Actions\DeleteAction::make()
])
->icon('heroicon-o-ellipsis-vertical')
->color('gray')
```

- **Purpose**: Adds grouped actions (view, edit, delete) to table rows.

---

## Module Implementations

This section covers how to implement specific Microweber modules using Filament.

### Payment Module

Handles payment-related functionality.

#### Payment Provider Selection

```php
Select::make('provider')
    ->options(PaymentProvider::active()->whereNotNull('name')->pluck('name', 'id'))
    ->searchable()
    ->live()
```

- **Purpose**: Allows selection of active payment providers dynamically.

#### Payment Amount Input

```php
TextInput::make('amount')
    ->numeric()
    ->prefix(fn($record) => $record->currency_symbol ?? '$')
```

- **Purpose**: Captures payment amount with a currency prefix.

#### Payment Status Badge

```php
TextColumn::make('status')
    ->badge()
    ->color(fn($state) => match($state) {
        'completed' => 'success',
        'failed' => 'danger',
        default => 'warning'
    })
```

- **Purpose**: Displays payment status with color-coded badges.

### Content Module

Manages content creation and editing.

#### Content Builder with Blocks

```php
Builder::make('sections')
    ->blocks([
        Builder\Block::make('text')
            ->schema([RichEditor::make('content')]),
        Builder\Block::make('gallery')
            ->schema([FileUpload::make('images')->image()])
    ])
    ->collapsible()
```

- **Purpose**: Creates a flexible content builder with text and image blocks.
- **Features**: Collapsible sections for better organization.

### Newsletter Module

Manages newsletter campaigns.

#### Campaign Actions

```php
Tables\Actions\Action::make('expand_opened')
    ->label(fn($campaign) => "Expand ({$campaign->opened_count})")
    ->action(function($campaign) {
        // Add logic to expand and show opened emails
    })
    ->icon('heroicon-o-envelope-open')
```

- **Purpose**: Custom action to expand campaign details based on open count.

### Shop Module

Handles e-commerce functionality.

#### Product Variants Repeater

```php
Repeater::make('variants')
    ->schema([
        TextInput::make('name')->required(),
        TextInput::make('sku')->required(),
        TextInput::make('price')->numeric()->required()
    ])
    ->columns(3)
```

- **Purpose**: Allows adding multiple product variants with required fields.

---

## Best Practices

Optimize your Filament implementations with these guidelines.

### Performance

#### Large Datasets

```php
$table
    ->deferLoading()
    ->persistFiltersInSession()
    ->paginated([10, 25, 50, 100])
```

- **Purpose**: Improves performance by loading data on demand and retaining filters.

#### Eager Loading Relationships

```php
Select::make('author_id')
    ->relationship('author', 'name')
    ->whereNotNull('name')
    ->preload()
```

- **Purpose**: Reduces database queries by preloading related data.

### Security

#### Restricting File Uploads

```php
FileUpload::make('document')
    ->acceptedFileTypes(['application/pdf'])
    ->maxSize(5120) // 5MB
    ->directory('private/uploads')
```

- **Purpose**: Limits uploads to PDFs and stores them securely.

#### Sensitive Inputs

```php
TextInput::make('api_key')
    ->password()
    ->revealable(false)
```

- **Purpose**: Masks sensitive data like API keys without reveal option.

### Form Organization

```php
$form->schema([
    Section::make('Main')->columns(2)->schema([
        TextInput::make('title'),
        Select::make('category_id')->relationship('category', 'name')
    ]),
    Section::make('Advanced')->collapsible()->schema([
        TextInput::make('meta_description')
    ])
])
```

- **Purpose**: Organizes fields into collapsible sections for better UX.

---

## Troubleshooting Common Issues

### Components Not Rendering

```php
// Ensure correct namespace imports
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

// Verify Livewire view
protected static string $view = 'filament.resources.my-resource.pages.list';
```

- **Solution**: Check imports and Livewire initialization.

### Relationship Loading Issues

```php
Select::make('author_id')
    ->relationship('author', 'name')
    ->getSearchResultsUsing(fn(string $search) => 
        Author::where('name', 'like', "%{$search}%")->whereNotNull('name')->limit(50)->pluck('name', 'id')
    )
```

- **Solution**: Use custom search logic for efficient relationship loading.

---

## Reference Tables

### Component Locations

| **Category**       | **Key Components**                 | **Example Location**     |
|---------------------|------------------------------------|--------------------------|
| Form Inputs        | Select, TextInput, FileUpload     | Payment/Filament/        |
| Table Features     | TextColumn, Filter, BulkAction    | Newsletter/Filament/     |
| Custom Components  | MwColorPicker, MwIconPicker       | Content/Filament/        |
| Layout             | Tabs, Section, Wizard             | All Modules              |

### Module-Specific Paths
- **Payment:** `/Modules/Payment/Filament/`
- **Newsletter:** `/Modules/Newsletter/Filament/`
- **Content:** `/Modules/Content/Filament/`
- **Shop:** `/Modules/Shop/Filament/`

---

## Additional Resources

- **Official Documentation:**
    - [Filament Documentation](https://filamentphp.com/docs)
    - [Livewire Documentation](https://laravel-livewire.com/docs)
- **Community Support:**
    - [Filament Discord](https://filamentphp.com/discord)
    - [Laravel News](https://laravel-news.com/category/filament)



