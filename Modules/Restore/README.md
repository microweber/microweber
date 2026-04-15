# Restore

Site restore engine. Import and apply backup archives created by the Backup module, with support for multiple file formats and content deduplication.

## Key Features

- Restore from JSON, CSV, XLSX, XML, and ZIP backup files
- Database content writing with duplicate detection
- Encoding normalization during import
- Configurable PHP file upload blocking for security
- Format-specific readers for each backup type
- Progress logging during restore

## Restore Formats

| Format | Class |
|---|---|
| JSON | `Formats\JsonReader` |
| CSV | `Formats\CsvReader` |
| XLSX | `Formats\XlsxReader` |
| XML | `Formats\XmlReader` |
| ZIP | `Formats\ZipReader` |
| Default | `Formats\DefaultReader` |

## Key Classes

| Class | Purpose |
|---|---|
| `Restore` | Main restore orchestrator |
| `DatabaseWriter` | Writes parsed data to database |
| `DatabaseSave` / `DatabaseSaveContent` | Content-specific save logic |
| `DatabaseDuplicateChecker` | Prevents duplicate record creation |
| `EncodingFix` | Normalizes character encoding |

## Configuration

```php
// config/config.php
'allow_php_files_upload' => env('MW_ALLOW_PHP_FILES_UPLOAD', false)
```

Set `MW_ALLOW_PHP_FILES_UPLOAD=true` in `.env` to allow PHP files in backup archives (disabled by default for security).

## Usage

Restore is typically triggered from the admin panel via the Backup module's interface. The restore process reads the backup file, parses it using the appropriate format reader, checks for duplicates, and writes data to the database.
