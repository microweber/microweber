# Db Export

Database export, import, and cross-connection migration for Laravel. Supports SQLite, MySQL, and PostgreSQL with chunked transfers, auto-increment preservation, index detection, and streaming JSON (via `halaxa/json-machine`).

Part of the [Microweber](https://microweber.com) CMS.

## Features

- **Copy between connections** — stream every (or selected) table from one connection to another, recreating the table, copying rows in chunked transactions, fixing the auto-increment sequence, and rebuilding indexes.
- **Streaming JSON export/import** — write to / read from a JSON file without loading the whole dataset into memory.
- **Fine-grained filters** — skip tables, skip columns (`table.column`), restrict to specific row IDs, or add arbitrary `WHERE` conditions per table via `ExportFilter`.
- **Artisan commands** — `microweber:db-export` and `microweber:db-import`.

## Installation

```bash
composer require microweber-packages/microweber-db-export
```

The `DbExportServiceProvider` singleton-binds `DbExportManager` and registers the two console commands.

## Usage

### Via the DbExport facade

```php
use MicroweberPackages\DbExport\Facades\DbExport;

// Copy all tables from one connection to another (returns ['table' => rowCount, ...])
$result = DbExport::copy('mysql', 'sqlite');

// Copy only selected tables, with a progress callback
$result = DbExport::copy('mysql', 'pgsql', ['content', 'media'], function (string $table, int $rows) {
    echo "Copied {$table}: {$rows} rows\n";
});

// Export to a streaming JSON file (null connection = default)
DbExport::exportToJson(storage_path('backup.json'), 'mysql', ['content', 'media']);

// Import from a streaming JSON file into a target connection
DbExport::importFromJson(storage_path('backup.json'), 'sqlite');

// Chunk size (rows per transaction)
DbExport::setChunkSize(1000);

// Read filtered rows from a table (used by Backup / Export modules)
$rows = DbExport::getTableContent('content', 'mysql', [1, 2, 3]);
```

### Filtering

```php
use MicroweberPackages\DbExport\Facades\DbExport;

DbExport::filter()
    ->addSkipTable('sessions')
    ->addSkipField('content', 'content_body')     // skip a column
    ->addOnlyIds('content', [1, 2, 3])            // restrict to these IDs
    ->addWhere('orders', 'is_paid', '=', 1);      // arbitrary WHERE

DbExport::exportToJson(storage_path('filtered.json'));
```

### Via dependency injection

```php
use MicroweberPackages\DbExport\DbExportManager;

public function __construct(private DbExportManager $exporter) {}

$this->exporter->setChunkSize(500)->copy('mysql', 'sqlite');
```

### Artisan commands

```bash
# Copy between connections
php artisan microweber:db-export mysql sqlite \
    --tables=content,media \
    --skip-tables=sessions \
    --skip-fields=content.title,cart.session_id \
    --only-ids="content.1,2,3 users.4,5" \
    --chunk-size=500

# Export a connection to a JSON file
php artisan microweber:db-export --connection=mysql --path=storage/backup.json --tables=content

# Import a JSON file into a connection
php artisan microweber:db-import --path=storage/backup.json --connection=sqlite --chunk-size=500
```

## Key classes

- Facade: `MicroweberPackages\DbExport\Facades\DbExport`
- Manager: `MicroweberPackages\DbExport\DbExportManager`
- Filter: `MicroweberPackages\DbExport\ExportFilter`
- Provider: `MicroweberPackages\DbExport\DbExportServiceProvider`
- Commands: `MicroweberPackages\DbExport\Commands\DbExportCommand`, `DbImportCommand`
- Internals: `SchemaInspector`, `TableCopier`, `JsonExporter`, `JsonImporter`

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
