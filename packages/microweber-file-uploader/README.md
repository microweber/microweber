# Microweber File Uploader

A standalone Laravel package for secure file uploads with comprehensive validation, chunked-upload support, and image processing.

## Features

- **MIME type validation** – validates actual file content, not just the extension
- **Extension ↔ MIME mismatch detection** – catches disguised files
- **File size limits** – configurable per category (`images`, `videos`, `documents`, …)
- **Security checks** – blocks a large list of dangerous extensions (PHP, EXE, HTML, SVG tricks, …)
- **Chunked upload support** – compatible with Plupload and similar JS uploaders
- **Image processing** – EXIF stripping, SVG sanitization, optional auto-resize of large images
- **Storage disk integration** – stores the finished file to any configured Laravel disk

## Installation

```bash
composer require microweber-packages/microweber-file-uploader
```

The service provider is auto-discovered. It merges `config/file-uploader.php`, binds `FileUploaderService` as a singleton, and also binds the standalone `FileValidationService`.

## Usage

### Handle an upload (via the facade)

```php
use MicroweberPackages\FileUploader\Facades\FileUploader;

$result = FileUploader::upload($request, [
    'targetDir'        => storage_path('app/public/uploads'),
    'allowedFileTypes' => ['jpg', 'png', 'pdf'], // empty = all non-dangerous types
    'autoResize'       => true,
    'maxDimension'     => 1980,
    'disk'             => 'public',
    'storeToDisk'      => true,
]);

if ($result['success']) {
    echo 'Uploaded: ' . $result['name'];   // 'src' holds the final path/URL
} else {
    echo 'Error: ' . $result['error_message'];
}
```

`upload()` returns an array. On success: `success`, `file_is_uploaded`, `name`, and
(when `returnPath`) `src`, plus image extras such as `image_size`, `file_size_human`.
On failure it returns the shape produced by `errorResponse()`:
`success=false`, `error=true`, `error_code`, `error_message`, `http_status`.

### Via dependency injection

```php
use MicroweberPackages\FileUploader\FileUploaderService;

public function store(Request $request, FileUploaderService $uploader)
{
    return $uploader->upload($request);
}
```

### Validation only

Resolve the validation service directly, or via `FileUploader::validator()`:

```php
$validator = FileUploader::validator(); // FileValidationService

$validator->validateMimeType('/path/to/file.jpg', ['images']); // ['valid' => bool, 'error' => ?string]
$validator->validateSizeByCategory(filesize($path), 'images');
$validator->isDangerousExtension('file.php');                  // true
$validator->isAllowedExtension('photo.jpg');                   // true
$validator->validateExtensionMatchesMimeType($path);           // catch disguised files
$validator->detectCategoryFromExtension('mp4');                // 'videos'

// Laravel validation rules for a form request
$rules = $validator->getValidationRules(['images'], '10M');
```

The same validator is injectable on its own:

```php
use MicroweberPackages\FileUploader\Validation\FileValidationService;

public function __construct(private FileValidationService $validator) {}
```

### Image processing & filename sanitizing

```php
FileUploader::imageProcessor();     // ImageProcessor (EXIF strip, SVG sanitize, autoResize)
FileUploader::filenameSanitizer();  // FilenameSanitizer
FileUploader::humanFilesize(1536000); // "1.46 MB"
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=file-uploader-config
```

`config/file-uploader.php` keys:

- `disk` – storage disk (env `FILE_UPLOADER_DISK`, default `public`)
- `upload_path` – path within the disk (env `FILE_UPLOADER_PATH`, default `uploads`)
- `size_limits` – max size **in KB** per category (`images`, `videos`, `audios`, `documents`, `archives`, `files`, `default`)
- `auto_resize_images` – bool (env `FILE_UPLOADER_AUTO_RESIZE`)
- `auto_resize_threshold` – bytes above which resize is offered/applied (default `2000000`)
- `auto_resize_max_dimension` – max px (default `1980`)
- `temp_file_max_age` – seconds before `.part` files are cleaned (default `18000`)
- `dangerous_extensions` – blocklist that overrides any allow-list
- `mime_type_mappings` – MIME → extensions, grouped by category
- `allowed_extensions` – allowed extensions grouped by category

## Key classes

- `MicroweberPackages\FileUploader\FileUploaderService` — upload orchestration (singleton)
- `MicroweberPackages\FileUploader\Validation\FileValidationService` — MIME/size/extension validation
- `MicroweberPackages\FileUploader\Support\ImageProcessor` — EXIF/SVG/resize
- `MicroweberPackages\FileUploader\Support\FilenameSanitizer` — safe filenames
- `MicroweberPackages\FileUploader\Facades\FileUploader` — facade

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
