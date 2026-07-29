# microweber-packages/zip

Standalone Laravel package for creating and extracting ZIP archives with:

- **Zip-bomb protection** (file count, total uncompressed size, per-file size, compression ratio)
- **Path traversal guards** (no `..`, absolute paths, null bytes, forbidden characters)
- **Optional file-allowance checks** (CMS-friendly, or standalone dangerous-extension denylist)
- Clean public API usable in any Laravel 10/11/12 app

## Install

```bash
composer require microweber-packages/zip
```

When used as a path repository inside the Microweber monorepo:

```json
{
  "type": "path",
  "url": "packages/microweber-zip",
  "options": { "symlink": true }
}
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

Or set env vars:

```
ZIP_MAX_FILES=10000
ZIP_MAX_TOTAL_UNCOMPRESSED=1073741824
ZIP_MAX_SINGLE_FILE=536870912
ZIP_MAX_COMPRESSION_RATIO=100
```

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
