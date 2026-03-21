# File Upload Validation Documentation

## Overview

Microweber now includes enhanced file upload validation with comprehensive MIME type checking, size limits, and security features. This document describes the new validation system and how to configure it.

## Features

- **MIME Type Validation**: Validates that uploaded files have proper MIME types matching their extensions
- **Category-Based Size Limits**: Configurable file size limits per file category (images, videos, documents, etc.)
- **Security Checks**: Blocks dangerous file types (executables, scripts, etc.)
- **Extension-to-MIME Matching**: Ensures file extensions match their detected MIME types
- **Configurable**: All settings can be customized via environment variables or config files

## Configuration

### Environment Variables

Add these variables to your `.env` file:

```env
# Upload size limits (in KB)
MW_UPLOAD_LIMIT_IMAGES=10240        # 10 MB
MW_UPLOAD_LIMIT_VIDEOS=102400       # 100 MB
MW_UPLOAD_LIMIT_AUDIOS=51200       # 50 MB
MW_UPLOAD_LIMIT_DOCUMENTS=20480     # 20 MB
MW_UPLOAD_LIMIT_ARCHIVES=102400    # 100 MB
MW_UPLOAD_LIMIT_FILES=10240        # 10 MB
MW_UPLOAD_LIMIT_DEFAULT=10240      # 10 MB

# Upload validation settings
MW_UPLOAD_VALIDATE_MIME=true       # Validate MIME types
MW_UPLOAD_VALIDATE_EXTENSION=true    # Validate extensions match MIME
MW_UPLOAD_BLOCK_DANGEROUS=true     # Block dangerous file types

# Allowed categories
MW_UPLOAD_ALLOW_IMAGES=true
MW_UPLOAD_ALLOW_VIDEOS=true
MW_UPLOAD_ALLOW_AUDIOS=true
MW_UPLOAD_ALLOW_DOCUMENTS=true
MW_UPLOAD_ALLOW_ARCHIVES=true
MW_UPLOAD_ALLOW_FILES=true

# Security settings
MW_UPLOAD_SCAN_SVG=true            # Scan SVG files for malicious content
MW_UPLOAD_STRIP_EXIF=true          # Strip EXIF data from images
MW_UPLOAD_MAX_PER_HOUR=100         # Max uploads per IP per hour
MW_UPLOAD_ALLOW_PHP=false          # Allow PHP uploads (DANGEROUS!)
```

### Config File

The full configuration is available in `config/media.php`:

```php
// config/media.php
return [
    'upload_limits' => [
        'images' => env('MW_UPLOAD_LIMIT_IMAGES', 10240),
        'videos' => env('MW_UPLOAD_LIMIT_VIDEOS', 102400),
        'audios' => env('MW_UPLOAD_LIMIT_AUDIOS', 51200),
        'documents' => env('MW_UPLOAD_LIMIT_DOCUMENTS', 20480),
        'archives' => env('MW_UPLOAD_LIMIT_ARCHIVES', 102400),
        'files' => env('MW_UPLOAD_LIMIT_FILES', 10240),
        'default' => env('MW_UPLOAD_LIMIT_DEFAULT', 10240),
    ],
    // ... additional settings
];
```

## FileUploadValidationService

The `FileUploadValidationService` class provides the core validation functionality.

### Usage

```php
use MicroweberPackages\Utils\System\FileUploadValidationService;

$service = new FileUploadValidationService();

// Validate file MIME type
$result = $service->validateMimeType('/path/to/file.jpg', ['images']);
if (!$result['valid']) {
    echo $result['error']; // "File type \"jpg\" (detected as image/jpeg) is not allowed..."
}

// Validate file size
$result = $service->validateSize($fileSizeInBytes, '10M');
if (!$result['valid']) {
    echo $result['error']; // "File size (20.50 KB) exceeds maximum..."
}

// Validate by category
$result = $service->validateSizeByCategory($fileSizeInBytes, 'images');

// Get MIME type
$mimeType = $service->getMimeType('/path/to/file.jpg'); // "image/jpeg"

// Check file type
$isImage = $service->isImage('/path/to/file.jpg'); // true
$isVideo = $service->isVideo('/path/to/file.mp4'); // true
$isAudio = $service->isAudio('/path/to/file.mp3'); // true

// Comprehensive validation
$result = $service->validateUpload($_FILES['upload'], [
    'allowed_categories' => ['images'],
    'max_size' => '10M',
    'check_dangerous' => true,
    'check_mime' => true,
]);

if (!$result['valid']) {
    foreach ($result['errors'] as $error) {
        echo $error;
    }
}
```

### Supported File Categories

| Category | Extensions | Default Size Limit |
|----------|------------|-------------------|
| images | jpg, jpeg, png, gif, webp, svg, tiff, bmp, ico | 10 MB |
| videos | mp4, m4v, avi, mpg, mpeg, webm, ogv, ogg, mov, wmv, 3gp, 3g2 | 100 MB |
| audios | mp3, ogg, wav, flac, m4a, aac | 50 MB |
| documents | pdf, doc, docx, xls, xlsx, ppt, pptx, rtf, txt, xml, odt | 20 MB |
| archives | zip, rar, 7z, gz, gzip, tar, tgz, tar.gz | 100 MB |
| files | css, json, woff, woff2, ttf, otf, ico | 10 MB |

### MIME Type Mappings

The service maintains comprehensive MIME type mappings for all supported file types:

```php
// Get MIME types for a category
$mappings = $service->getMimeTypeMappings('images');
// Returns: ['image/jpeg' => ['jpg', 'jpeg', 'jpe'], 'image/png' => ['png'], ...]

// Get all allowed MIME types
$mimes = $service->getAllowedMimeTypes(['images', 'videos']);
// Returns: ['image/jpeg', 'image/png', 'video/mp4', ...]
```

## Validation Rules for Laravel

Generate validation rules for Laravel's Validator:

```php
$service = new FileUploadValidationService();

// Get rules for images
$rules = $service->getValidationRules(['images'], '10M');
// Returns: ['max' => 10240, 'mimetypes' => 'image/jpeg,image/png,...']

// Use with Laravel Validator
$validator = Validator::make($request->all(), [
    'image' => ['required', 'file', ...$rules],
]);
```

## Security Features

### Dangerous File Blocking

The service automatically blocks uploads with dangerous extensions:

- PHP files: `.php`, `.phtml`, `.php5`, `.php7`, `.php8`, etc.
- Scripts: `.js`, `.sh`, `.bat`, `.cmd`, `.vbs`, etc.
- Executables: `.exe`, `.msi`, `.com`, `.bin`, etc.
- Web files: `.html`, `.htm`, `.shtml`, `.pl`, `.cgi`, etc.
- System files: `.htaccess`, `.htpasswd`, etc.

### SVG Sanitization

SVG files are sanitized to remove potentially malicious content:

```php
$filesUtils = new \MicroweberPackages\Utils\System\Files();
$cleanSvg = $filesUtils->sanitize_svg($dirtySvg);
```

### EXIF Data Removal

Images can have EXIF data automatically stripped for privacy:

Set `MW_UPLOAD_STRIP_EXIF=true` in your `.env` file.

## Testing

Run the validation tests:

```bash
# Unit tests for the validation service
./vendor/bin/phpunit tests/Unit/Utils/System/FileUploadValidationServiceTest.php

# Feature tests for security validation
./vendor/bin/phpunit tests/Feature/Security/FileUploadValidationTest.php

# Run both test suites
./vendor/bin/phpunit tests/Unit/Utils/System/FileUploadValidationServiceTest.php tests/Feature/Security/FileUploadValidationTest.php
```

## Error Handling

The validation service provides detailed error messages:

- **MIME Type Mismatch**: "File type 'exe' (detected as application/x-msdownload) is not allowed"
- **Size Limit Exceeded**: "File size (20.50 KB) exceeds maximum allowed size (10240 KB or 10.00 MB)"
- **Extension Mismatch**: "Extension '.jpg' does not match detected MIME type 'image/png'"
- **Dangerous File**: "This file type is not allowed for security reasons"
- **Upload Error**: "The uploaded file exceeds the upload_max_filesize directive in php.ini"

## API Endpoints

The Plupload controller (`Modules/FileManager/Http/Controllers/PluploadController`) now uses the validation service for all uploads. Files are validated automatically:

1. MIME type is checked against allowed categories
2. File size is validated against category limits
3. Extensions are validated against detected MIME types
4. Dangerous file types are blocked

Failed validations return appropriate HTTP status codes:
- `415 Unsupported Media Type`: MIME type validation failed
- `413 Payload Too Large`: File size exceeded limit
- `401 Unauthorized`: Security check failed (dangerous file)

## Migration from Legacy System

The new validation service is backward compatible. Existing uploads will continue to work, but with enhanced validation. To disable the new validation, set:

```env
MW_UPLOAD_VALIDATE_MIME=false
MW_UPLOAD_VALIDATE_EXTENSION=false
```

However, this is **not recommended** as it reduces security.

## Best Practices

1. **Keep size limits reasonable**: Set appropriate limits based on your server capacity
2. **Enable MIME validation**: Always keep `MW_UPLOAD_VALIDATE_MIME=true`
3. **Block dangerous files**: Never set `MW_UPLOAD_ALLOW_PHP=true` in production
4. **Monitor uploads**: Check logs for failed upload attempts
5. **Use HTTPS**: Always use HTTPS for file uploads to prevent man-in-the-middle attacks
6. **Regular updates**: Keep the MIME type mappings updated as new formats emerge

## Troubleshooting

### Issue: "Unable to determine MIME type"

**Solution**: Ensure `finfo` extension is installed and enabled in PHP:
```bash
php -m | grep fileinfo
```

### Issue: "File size exceeds maximum" for small files

**Solution**: Check both PHP limits and Microweber limits:
```bash
php -r "echo ini_get('upload_max_filesize');"
php -r "echo ini_get('post_max_size');"
```

Ensure these are larger than your Microweber upload limits.

### Issue: Valid files being rejected

**Solution**: Check if the file's actual MIME type matches its extension:
```php
$finfo = finfo_open(FILEINFO_MIME_TYPE);
echo finfo_file($finfo, '/path/to/file');
finfo_close($finfo);
```

## Changelog

### 2026-03-21
- Initial implementation of FileUploadValidationService
- Added comprehensive MIME type validation
- Added category-based size limits
- Added extension-to-MIME matching
- Integrated validation into PluploadController
- Added extensive test coverage
