# Microweber Database Package

A reusable Laravel package providing a generic database manager (get/save/delete/query with caching), a table builder/schema utilities, CRUD helpers, cacheable query builders, model traits, casts and observers.

## Features

- **DatabaseManager** — Generic `get()` / `save()` / `delete_by_id()` / `query()` operations with automatic result caching and cache-tag invalidation on Eloquent `saved`/`created`/`deleted` events.
- **Utils (table builder)** — `build_table()` turns an array field map into a real schema; plus `get_fields()`, `table_exists()`, `import_sql_file()`, `add_table_index()`, prefix helpers.
- **Crud** — A thin table-scoped CRUD helper class.
- **CachedBuilder** — Eloquent + query builders with cache tagging (`Eloquent/Builder/CachedBuilder`, `Query/CachedBuilder`, `MultilanguageCachedBuilder`).
- **Model traits** — `CacheableQueryBuilderTrait`, `HasSlugTrait`, `HasCreatedByFieldsTrait`, `HasTimestampsTrait`, `MaxPositionTrait`, `ExtendedSave`, `QueryFilter`, `ParentCannotBeSelfTrait`.
- **Casts** — `MarkdownCast`, `ReplaceSiteUrlCast`, `StripTagsCast`, `StrToLowerCast`, `StrToLowerTrimCast`.
- **Observers** — `BaseModelObserver`, `CreatedByObserver`.
- **SQLite support** — A prefix-safe `SQLiteConnection` + `SQLiteQueryGrammar` so Laravel's JSON-contains grammar does not prefix the `json_each` pseudo-table.
- **Global helpers** — `db_get()`, `db_save()`, `db_delete()`, `get_table_prefix()`, `db_query_parse_compare_sign_value()`.

## Installation

```bash
composer require microweber-packages/microweber-database
```

The package registers `DatabaseManagerServiceProvider` (extends `MicroweberPackageServiceProvider`), which singleton-binds the service and installs the SQLite connection resolver.

## Usage

### Via the global helpers

```php
// Save data (returns the inserted/updated id)
$id = db_save('my_table', ['name' => 'John', 'email' => 'john@example.com']);

// Get rows (Microweber query-string or array params)
$results = db_get('my_table', 'limit=10&order_by=id desc');

// Get a single record
$record = db_get('my_table', 'single=1&id=' . $id);

// Delete by id
db_delete('my_table', $id);

// Current table prefix
$prefix = get_table_prefix();
```

### Via the DatabaseManager facade

```php
use MicroweberPackages\Database\Facades\DatabaseManager;

$rows   = DatabaseManager::get('content', ['limit' => 10]);
$id     = DatabaseManager::save('content', ['title' => 'Hello']);
$single = DatabaseManager::get_by_id('content', $id);
DatabaseManager::delete_by_id('content', $id);

// Raw query with caching
$data = DatabaseManager::query('SELECT * FROM content LIMIT 10', 'cache_id', 'content');

// Fluent query builder on a prefixed table
DatabaseManager::table('log')->where('created_at', '<', $old)->delete();

// Invalidate the manager's result cache (also fired automatically on model events)
DatabaseManager::clearCache('content');
```

### Via dependency injection

```php
use MicroweberPackages\Database\DatabaseManagerService;

public function __construct(private DatabaseManagerService $db) {}

$rows = $this->db->get('content', ['limit' => 10]);
```

### Building tables

`build_table()` is inherited by the manager from `Utils` — the single source of truth for the array→table engine (also used by the installer).

```php
use MicroweberPackages\Database\Facades\DatabaseManager;

DatabaseManager::build_table('my_table', [
    'name'       => 'string',
    'email'      => 'string',
    'bio'        => 'text',
    'updated_at' => 'dateTime',
    'created_at' => 'dateTime',
]);

$exists = DatabaseManager::table_exists('my_table');
$fields = DatabaseManager::get_fields('my_table');
```

### Using the traits in a model

```php
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;
use MicroweberPackages\Database\Traits\HasSlugTrait;

class MyModel extends Model
{
    use CacheableQueryBuilderTrait;  // cache-tagged query results
    use HasCreatedByFieldsTrait;     // auto-fills created_by / edited_by
    use HasSlugTrait;                // generates a unique url slug on create/update
}
```

## Key classes

- Service: `MicroweberPackages\Database\DatabaseManagerService` (extends `MicroweberPackages\Database\Utils`)
- Facade: `MicroweberPackages\Database\Facades\DatabaseManager`
- Provider: `MicroweberPackages\Database\DatabaseManagerServiceProvider`
- CRUD helper: `MicroweberPackages\Database\Crud`
- Base model: `MicroweberPackages\Database\BaseModel`

## Testing

```bash
cd packages/microweber-database
../../vendor/bin/phpunit
```

> The Postgres schema-introspection path in `Utils` needs `doctrine/dbal` (suggested, guarded — throws a friendly error if missing).

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Petko Yovchevski
