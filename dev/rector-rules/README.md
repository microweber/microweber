# Filament v5 Migration Rector Rules

This directory contains custom Rector rules and tools for automating the migration from Filament v3 to Filament v5.

## Overview

The migration tools include:

1. **Custom Rector Rules** - PHP code transformations
2. **Blade Migrator** - Blade template transformations  
3. **Analysis Scripts** - Detection and reporting tools

## Quick Start

### 1. Analyze Your Codebase

Check how many migration issues exist:

```bash
./dev/rector-rules/analyze-filament-migration.sh .
```

### 2. Run PHP Migrations (Dry Run)

See what changes Rector would make:

```bash
vendor/bin/rector process --config=rector-filament.php --dry-run
```

### 3. Apply PHP Migrations

Actually apply the changes:

```bash
vendor/bin/rector process --config=rector-filament.php
```

### 4. Migrate Blade Templates

Run the Blade template migrator:

```bash
php dev/rector-rules/blade-migrator.php Modules/YourModule/resources/views
```

## Custom Rector Rules

### RenameSectionImportRector

Changes:
```php
use Filament\Forms\Components\Section;
```

To:
```php
use Filament\Schemas\Components\Section;
```

### RenameTableActionImportRector

Changes all table action imports from:
```php
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
// etc.
```

To:
```php
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
// etc.
```

### RenameTabsImportRector

Changes:
```php
use Filament\Forms\Components\Tabs;
use Filament\Resources\Components\Tab;
```

To:
```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
```

### RenameFormMethodSignatureRector

Changes form method signatures:
```php
public static function form(Form $form): Form
```

To:
```php
public static function form(Schema $schema): Schema
```

### RenameSchemaMethodCallRector

Changes method calls from:
```php
->schema([...])
```

To:
```php
->components([...])
```

### ConvertTestAnnotationToAttributeRector

Changes:
```php
/** @test */
public function test_something()
```

To:
```php
#[Test]
public function test_something(): void
```

### FixLivewireEventDispatchRector

Changes:
```php
$this->emit('event');
$this->emitUp('event');
```

To:
```php
$this->dispatch('event');
$this->dispatch('event');
```

## Blade Template Patterns

The Blade migrator handles these patterns:

### Blade Components
- `filament-forms::components.*` → `mw-filament::components.*`
- `filament-forms::admin.*` → `mw-filament::admin.*`
- `filament-forms::sections.*` → `mw-filament::sections.*`

### Livewire Events
- `$emit('event')` → `$dispatch('event')`

### Wire Model
- `wire:model.defer` → `wire:model` (v3 deferred by default)

## Configuration

### rector-filament.php

Main Rector configuration file. Modify the paths to target specific modules:

```php
->withPaths([
    __DIR__ . '/Modules/YourModule',
])
```

## Migration Patterns Reference

### PHP Imports

| Old (v3) | New (v5) |
|----------|----------|
| `Filament\Forms\Components\Section` | `Filament\Schemas\Components\Section` |
| `Filament\Forms\Components\Tabs` | `Filament\Schemas\Components\Tabs` |
| `Filament\Resources\Components\Tab` | `Filament\Schemas\Components\Tabs\Tab` |
| `Filament\Tables\Actions\EditAction` | `Filament\Actions\EditAction` |
| `Filament\Tables\Actions\DeleteAction` | `Filament\Actions\DeleteAction` |
| `Filament\Tables\Actions\ViewAction` | `Filament\Actions\ViewAction` |
| `Filament\Tables\Actions\BulkActionGroup` | `Filament\Actions\BulkActionGroup` |
| `Filament\Tables\Actions\DeleteBulkAction` | `Filament\Actions\DeleteBulkAction` |

### Method Signatures

| Old (v3) | New (v5) |
|----------|----------|
| `form(Form $form): Form` | `form(Schema $schema): Schema` |
| `->schema([...])` | `->components([...])` |

### Blade Components

| Old (v3) | New (v5) |
|----------|----------|
| `<x-filament-forms::components.*` | `<x-mw-filament::components.*` |
| `<x-filament-forms::admin.*` | `<x-mw-filament::admin.*` |
| `<x-filament-forms::sections.*` | `<x-mw-filament::sections.*` |

### Livewire

| Old (v3) | New (v5) |
|----------|----------|
| `$emit('event')` | `$dispatch('event')` |
| `wire:model.defer` | `wire:model` |

## Testing

After running migrations, verify the changes:

```bash
# Check for any remaining old patterns
./dev/rector-rules/analyze-filament-migration.sh .

# Run tests
./vendor/bin/phpunit
```

## Contributing

When adding new rules:

1. Create the Rector class in `Filament/Rector/`
2. Add to `rector-filament.php` with require_once and use statements
3. Register in the ->withRules() array
4. Update this README with documentation
5. Test with --dry-run first

## References

- [Filament v5 Upgrade Guide](https://filamentphp.com/docs/5.x/upgrade-guide)
- [Rector Documentation](https://getrector.com/documentation)
- [Microweber Filament Migration Guide](../docs/filament-migration.md)
