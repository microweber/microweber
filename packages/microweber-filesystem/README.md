# Microweber Filesystem

Standalone Laravel filesystem service: file utilities, path normalization, recursive directory operations, extension/MIME validation, and SVG sanitization. Modernised from Microweber's legacy `Utils\System\Files` class — no static state, no `exit()`/`die()`, no output-buffer hacks (downloads return a Symfony `BinaryFileResponse`).

## Features

- **Directory operations** – recursive copy, remove, create, and directory maps
- **Path utilities** – OS-aware normalization, double-slash reduction, extension helpers
- **Recursive glob** (`rglob`) that skips `.git` / `.svn` / `_notes`
- **Human-readable file sizes**
- **Safe downloads** – returns a `BinaryFileResponse` instead of streaming + `exit()`
- **Security** – dangerous-extension detection, allowed-upload-extension lists, SVG sanitization/validation (delegates the dangerous-extension list to `microweber-file-uploader` when present)
- **Global helpers** – back-compat procedural wrappers (`normalize_path()`, `rglob()`, …)

## Installation

```bash
composer require microweber-packages/microweber-filesystem
```

The service provider is auto-discovered and binds `FilesystemService` as a singleton.

## Usage

### Via the facade

```php
use MicroweberPackages\Filesystem\Facades\MwFilesystem;

// Path utilities
MwFilesystem::normalizePath('/var//www/./site/');        // OS-normalised, trailing slash
MwFilesystem::getFileExtension('photo.final.JPG');        // "JPG"
MwFilesystem::noExt('archive.tar.gz');                    // "archive.tar"
MwFilesystem::fileSizeNice(1536000);                      // "1.46 MB"

// Directory operations
MwFilesystem::copyDirectory($src, $dest);                 // returns list of copied paths
MwFilesystem::mkdirRecursive('/tmp/a/b/c');
MwFilesystem::removeDirectory('/tmp/cache');              // recursive delete
$files = MwFilesystem::rglob('*.blade.php', 0, resource_path('views'));

// Security
MwFilesystem::isDangerousFile('shell.php');               // true
MwFilesystem::isAllowedFile('photo.jpg');                 // true
MwFilesystem::getAllowedExtensionsForUpload('images');    // "png,gif,jpg,jpeg,..."
$clean = MwFilesystem::sanitizeSvg($dirtySvgString);
$ok    = MwFilesystem::checkIfSvgIsValid($svgString);     // false if content was altered
```

### Via dependency injection

```php
use MicroweberPackages\Filesystem\FilesystemService;

public function __construct(private FilesystemService $fs) {}

$map = $this->fs->directoryMap(base_path('Templates'), 2);
```

### Download a file (returns a Response — never calls exit())

```php
Route::get('/download/{id}', function ($id) {
    return MwFilesystem::downloadResponse(storage_path("app/exports/{$id}.zip"));
});
```

### Global helpers

These thin wrappers delegate to the shared `FilesystemService` (the container singleton once booted, otherwise a standalone instance so they work during early boot):

```php
normalize_path($path, $slashIt = true);
reduce_double_slashes($str);
get_file_extension($pathToFile);
no_ext($filename);
file_size_nice($size);
mkdir_recursive($pathname);
rmdir_recursive($directory, $empty = true);
rglob($pattern = '*', $flags = 0, $path = '');
directory_map($sourceDir, $directoryDepth = 0, $hidden = false, $fullPath = false);
mw_filesystem();   // resolve the FilesystemService itself
```

## Key classes

- `MicroweberPackages\Filesystem\FilesystemService` — the service (singleton)
- `MicroweberPackages\Filesystem\Facades\MwFilesystem` — facade
- `MicroweberPackages\Filesystem\FilesystemServiceProvider` — registers the singleton
- `src/helpers.php` — global procedural helpers (auto-loaded via composer `files`)

## Testing

```bash
composer test
```

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
