# Microweber Filament Documentation
## Comprehensive Guide v2.0

## Core Components

### Form Elements
```php
// Select Boxes
Select::make('status')
  ->options(['draft'=>'Draft','published'=>'Published'])
  ->searchable()
  ->native(false)

// Input Validation  
TextInput::make('email')
  ->email()
  ->required()
  ->maxLength(255)
  ->rules(['email:rfc,dns'])
```

### Table Components
```php
// Optimized Table Configuration
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

## Module Implementations

### Payment Module
```php
// Payment Processor
Select::make('provider')
  ->options(PaymentProvider::active()->pluck('name','id'))
  ->searchable()
  ->live()

// Amount Input  
TextInput::make('amount')
  ->numeric()
  ->prefix(fn($record) => $record->currency_symbol)
```

### Content Module
```php
// Content Builder
Builder::make('sections')
  ->blocks([
      Builder\Block::make('text')
          ->schema([RichEditor::make('content')]),
      Builder\Block::make('gallery')
          ->schema([FileUpload::make('images')->image()])
  ])
  ->collapsible()
```

## Best Practices

### Performance
```php
// For large datasets
$table
  ->deferLoading()
  ->persistFiltersInSession()
  ->paginated([10, 25, 50, 100])

// Eager loading
Select::make('author_id')
  ->relationship('author','name')
  ->preload()
```

### Security
```php
FileUpload::make('document')
  ->acceptedFileTypes(['application/pdf'])
  ->maxSize(5120) // 5MB
  ->directory('private/uploads')

TextInput::make('api_key')
  ->password()
  ->revealable(false)
```

## Reference Tables

| Category          | Key Components                      | Example Location              |
|-------------------|-------------------------------------|-------------------------------|
| Form Inputs       | Select, TextInput, FileUpload      | Payment/Filament/             |
| Table Features    | TextColumn, Filter, BulkAction     | Newsletter/Filament/          |
| Custom Components | MwColorPicker, MwIconPicker        | Content/Filament/             |
| Layout            | Tabs, Section, Wizard              | All Modules                   |

For module-specific implementations:
- Payment: `/Modules/Payment/Filament/`
- Newsletter: `/Modules/Newsletter/Filament/`
- Content: `/Modules/Content/Filament/`