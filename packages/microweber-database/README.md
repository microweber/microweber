# Microweber Database Package

A reusable Laravel package providing database management utilities, CRUD helpers, query caching, model traits, and more.

## Installation

```bash
composer require microweber-packages/database
```

## Features

- **DatabaseManager** — Generic get/save/delete/query operations with caching
- **BaseModel** — Extended Eloquent model with validation support
- **Crud** — Table-based CRUD helper class
- **CachedBuilder** — Eloquent and Query builders with automatic cache tagging
- **Model Traits** — CacheableQueryBuilderTrait, HasSlugTrait, HasCreatedByFieldsTrait, HasTimestampsTrait, MaxPositionTrait, ExtendedSave, QueryFilter, ParentCannotBeSelfTrait
- **Casts** — Markdown, ReplaceSiteUrl, StripTags, StrToLower, StrToLowerTrim
- **Observers** — BaseModelObserver, CreatedByObserver
- **Utils** — Table builder, schema utilities, SQL import, field introspection
- **Helper functions** — `db_get()`, `db_save()`, `db_delete()`, `get_table_prefix()`

## Usage

### Basic CRUD

```php
// Save data
$id = db_save('my_table', ['name' => 'John', 'email' => 'john@example.com']);

// Get data
$results = db_get('my_table', 'limit=10&order_by=id desc');

// Get single record
$record = db_get('my_table', 'single=1&id=' . $id);

// Delete
db_delete('my_table', $id);
```

### Building Tables

```php
app()->database_manager->build_table('my_table', [
    'name' => 'string',
    'email' => 'string',
    'bio' => 'text',
    'updated_at' => 'dateTime',
    'created_at' => 'dateTime',
]);
```

### Using Traits in Models

```php
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;

class MyModel extends \Illuminate\Database\Eloquent\Model
{
    use CacheableQueryBuilderTrait;
    use HasCreatedByFieldsTrait;
}
```

## License

MIT