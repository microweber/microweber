# microweber-packages/microweber-zip

Standalone Laravel package for creating and extracting ZIP archives with:

- **Zip-bomb protection** (file count, total uncompressed size, per-file size, compression ratio)
- **Path traversal guards** (no `..`, absolute paths, null bytes, forbidden characters)
- **Optional file-allowance checks** (CMS-friendly, or standalone dangerous-extension denylist)
- Clean public API usable in any Laravel 10/11/12 app

## Install

```bash
composer require microweber-packages/microweber-zip
```

Inside the Microweber monorepo the provider is registered by the core
`MicroweberPackages\App\Providers\MicroweberServiceProvider`. In a standalone
Laravel app, register it manually:

```php
MicroweberPackages\Zip\ZipServiceProvider::class,
```

When used as a path repository inside the Microweber monorepo:

```json
{
  "type": "path",
  "url": "packages/microweber-zip",
  "options": { "symlink": true }
}
```

The provider registers `ZipBombGuard` (built from config) as a singleton and
binds `Unzip` / `Zip`, so you can also resolve them from the container:

```php
$unzip = app(MicroweberPackages\Zip\Unzip::class); // already wired with the guard
```

## Usage

### Extract (Unzip)

```php
use MicroweberPackages\Zip\Unzip;

$unzip = new Unzip();
$files = $unzip->extract('/path/to/archive.zip', '/path/to/target');

if (is_array($files) && isset($files['error'])) {
    // handle error
} elseif ($files === false) {
    // empty archive
} else {
    // $files is list of absolute extracted paths
}
```

### Extract with ZipArchiveExtractor

```php
use MicroweberPackages\Zip\ZipArchiveExtractor;

$extractor = new ZipArchiveExtractor('/path/to/archive.zip');
$extractor->setAllowedFilesCheck(true); // optional extension filter
$ok = $extractor->extractTo('/path/to/target/');
```

### Create a zip

```php
use MicroweberPackages\Zip\Zip;

$zip = new Zip();
$zip->addFile('hello world', 'hello.txt');
$zip->addDirectoryContent('/path/to/dir', 'dir');
$zip->saveTo('/tmp/out.zip');
```

### Zip-bomb limits

Publish config:

```bash
php artisan vendor:publish --tag=zip-config
```

The published `config/zip.php` keys (each with an env override; set a numeric
limit to `0` to disable that check):

| Config key | Env | Default |
| --- | --- | --- |
| `max_files` | `ZIP_MAX_FILES` | `100000` |
| `max_total_uncompressed_bytes` | `ZIP_MAX_TOTAL_UNCOMPRESSED` | `1073741824` (1 GiB) |
| `max_single_file_uncompressed_bytes` | `ZIP_MAX_SINGLE_FILE` | `536870912` (512 MiB) |
| `max_compression_ratio` | `ZIP_MAX_COMPRESSION_RATIO` | `100.0` |
| `max_path_length` | `ZIP_MAX_PATH_LENGTH` | `512` |
| `skip_directories` | — | `['__MACOSX', '.DS_Store']` |
| `apply_chmod` | — | `0755` |

## Key classes

- `MicroweberPackages\Zip\Unzip` — high-level extractor (`extract()`, `allow()`, `native_unzip()`, error/debug accessors).
- `MicroweberPackages\Zip\ZipArchiveExtractor` — lower-level `ZipArchive` wrapper (`extractTo()`, `setAllowedFilesCheck()`, `listEntries()`).
- `MicroweberPackages\Zip\Zip` — archive builder (`addFile()`, `addDirectoryContent()`, `saveTo()`, `getZipData()`).
- `MicroweberPackages\Zip\Support\ZipBombGuard` — enforces the limits above.
- `MicroweberPackages\Zip\Support\SafePath` — path-traversal guards.
- `MicroweberPackages\Zip\Contracts\FileAllowanceCheckerInterface`, `Contracts\ZipLoggerInterface` — pluggable extension allow-list and logger.
- Exceptions: `ZipException`, `ZipBombException`, `UnsafePathException`, `InvalidArchiveException`.

## Testing

```bash
cd packages/microweber-zip
composer install
vendor/bin/phpunit
```

From the CMS root:

```bash
php artisan test --filter=Zip
composer analyse -- packages/microweber-zip/src
```

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
