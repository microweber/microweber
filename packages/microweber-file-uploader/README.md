# Microweber File Uploader

A standalone Laravel package for secure file uploads with comprehensive validation.

## Features

- **MIME type validation** – Validates actual file content, not just extensions
- **File size limits** – Configurable per-category size limits  
- **Security checks** – Blocks dangerous file types (PHP, EXE, etc.)
- **Extension-MIME mismatch detection** – Catches disguised files
- **Chunked upload support** – Compatible with Plupload and similar JS uploaders
- **Image processing** – EXIF stripping, auto-rotation, SVG sanitization
- **Auto-resize** – Automatic image resizing for large uploads
- **Configurable** – Fully configurable via `config/file-uploader.php`

## Installation

```bash
composer require microweber-packages/file-uploader
```

The package auto-discovers its service provider and facade.

## Usage

### Via the Container

```php
$uploader = FileUploader::;

// Upload a file from a request
$result = $uploader->upload($request, [
    'targetDir' => storage_path('app/public/uploads'),
    'allowedFileTypes' => ['jpg', 'png', 'pdf'],
    'autoResize' => true,
]);

if ($result['success']) {
    echo "Uploaded: " . $result['name'];
} else {
    echo "Error: " . $result['error_message'];
}
```

### Via the Facade

```php
use MicroweberPackages\FileUploader\Facades\FileUploader;

$result = FileUploader::upload($request);
```

### Validation Only

```php
$validator = FileUploader::->validator();

// Check MIME type
$result = $validator->validateMimeType('/path/to/file.jpg', ['images']);

// Check file size
$result = $validator->validateSizeByCategory(filesize($path), 'images');

// Check dangerous extension
$isDangerous = $validator->isDangerousExtension('file.php'); // true

// Get Laravel validation rules
$rules = $validator->getValidationRules(['images'], '10M');
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=file-uploader-config
```

## Testing

```bash
composer test
```

## License

MIT