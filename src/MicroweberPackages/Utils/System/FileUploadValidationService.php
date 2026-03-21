<?php

namespace MicroweberPackages\Utils\System;

/**
 * File Upload Validation Service
 *
 * Provides comprehensive validation for file uploads including:
 * - MIME type validation
 * - File size limits
 * - Extension validation
 * - Security checks
 *
 * @package MicroweberPackages\Utils\System
 */
class FileUploadValidationService
{
    /**
     * Default maximum file sizes (in KB)
     */
    protected array $defaultSizeLimits = [
        'images' => 10240,       // 10 MB
        'videos' => 102400,      // 100 MB
        'audios' => 51200,       // 50 MB
        'documents' => 20480,    // 20 MB
        'archives' => 102400,    // 100 MB
        'files' => 10240,        // 10 MB
        'default' => 10240,      // 10 MB
    ];

    /**
     * MIME type mappings by file category
     */
    protected array $mimeTypeMappings = [
        'images' => [
            'image/jpeg' => ['jpg', 'jpeg', 'jpe'],
            'image/png' => ['png'],
            'image/gif' => ['gif'],
            'image/webp' => ['webp'],
            'image/svg+xml' => ['svg'],
            'image/tiff' => ['tiff', 'tif'],
            'image/bmp' => ['bmp'],
            'image/x-icon' => ['ico'],
        ],
        'videos' => [
            'video/mp4' => ['mp4', 'm4v'],
            'video/avi' => ['avi'],
            'video/x-msvideo' => ['avi'],
            'video/mpeg' => ['mpg', 'mpeg'],
            'video/webm' => ['webm'],
            'video/ogg' => ['ogv', 'ogg'],
            'video/quicktime' => ['mov'],
            'video/x-ms-wmv' => ['wmv'],
            'video/3gpp' => ['3gp'],
            'video/3gpp2' => ['3g2'],
        ],
        'audios' => [
            'audio/mpeg' => ['mp3'],
            'audio/ogg' => ['ogg'],
            'audio/wav' => ['wav'],
            'audio/flac' => ['flac'],
            'audio/mp4' => ['m4a'],
            'audio/aac' => ['aac'],
        ],
        'documents' => [
            'application/pdf' => ['pdf'],
            'application/msword' => ['doc'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
            'application/vnd.ms-excel' => ['xls'],
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['xlsx'],
            'application/vnd.ms-powerpoint' => ['ppt'],
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => ['pptx'],
            'application/rtf' => ['rtf'],
            'text/plain' => ['txt'],
            'text/xml' => ['xml'],
            'application/vnd.oasis.opendocument.text' => ['odt'],
        ],
        'archives' => [
            'application/zip' => ['zip'],
            'application/x-zip-compressed' => ['zip'],
            'application/x-rar-compressed' => ['rar'],
            'application/x-7z-compressed' => ['7z'],
            'application/gzip' => ['gz', 'gzip'],
            'application/x-tar' => ['tar'],
            'application/x-compressed-tar' => ['tar.gz', 'tgz'],
        ],
        'files' => [
            'text/css' => ['css'],
            'application/json' => ['json'],
            'application/font-woff' => ['woff'],
            'application/font-woff2' => ['woff2'],
            'font/ttf' => ['ttf'],
            'font/otf' => ['otf'],
            'image/vnd.microsoft.icon' => ['ico'],
        ],
    ];

    /**
     * Get MIME type mappings for a category
     *
     * @param string $category
     * @return array
     */
    public function getMimeTypeMappings(string $category = 'all'): array
    {
        if ($category === 'all') {
            return array_merge_recursive(
                $this->mimeTypeMappings['images'],
                $this->mimeTypeMappings['videos'],
                $this->mimeTypeMappings['audios'],
                $this->mimeTypeMappings['documents'],
                $this->mimeTypeMappings['archives'],
                $this->mimeTypeMappings['files']
            );
        }

        return $this->mimeTypeMappings[$category] ?? [];
    }

    /**
     * Get file size limit for a category (in KB)
     *
     * @param string $category
     * @return int
     */
    public function getSizeLimit(string $category = 'default'): int
    {
        // Check for configuration override
        $configKey = "media.upload_limits.{$category}";
        $configuredLimit = \config($configKey);

        if ($configuredLimit !== null && is_numeric($configuredLimit)) {
            return (int) $configuredLimit;
        }

        return $this->defaultSizeLimits[$category] ?? $this->defaultSizeLimits['default'];
    }

    /**
     * Set default size limits (can be overridden via config)
     *
     * @param array $limits
     * @return self
     */
    public function setSizeLimits(array $limits): self
    {
        $this->defaultSizeLimits = array_merge($this->defaultSizeLimits, $limits);
        return $this;
    }

    /**
     * Validate file MIME type against allowed types
     *
     * @param string $filePath Path to uploaded file
     * @param string|array $allowedCategories Allowed categories (e.g., 'images', 'documents')
     * @return array{valid: bool, mime_type: string|null, extension: string|null, error: string|null}
     */
    public function validateMimeType(string $filePath, string|array $allowedCategories = ['images']): array
    {
        if (!file_exists($filePath)) {
            return [
                'valid' => false,
                'mime_type' => null,
                'extension' => null,
                'error' => 'File does not exist',
            ];
        }

        // Get MIME type using finfo
        $mimeType = $this->getMimeType($filePath);
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($mimeType === null) {
            return [
                'valid' => false,
                'mime_type' => null,
                'extension' => $extension,
                'error' => 'Unable to determine MIME type',
            ];
        }

        // If specific MIME type is provided directly
        if (is_string($allowedCategories) && str_contains($allowedCategories, '/')) {
            if ($mimeType === $allowedCategories) {
                return [
                    'valid' => true,
                    'mime_type' => $mimeType,
                    'extension' => $extension,
                    'error' => null,
                ];
            }
        }

        // Normalize categories to array
        $categories = is_array($allowedCategories) ? $allowedCategories : [$allowedCategories];

        // Check if MIME type is in allowed categories
        foreach ($categories as $category) {
            $categoryMimes = $this->getMimeTypeMappings($category);

            if (isset($categoryMimes[$mimeType])) {
                // Also validate extension matches MIME type
                if (in_array($extension, $categoryMimes[$mimeType], true)) {
                    return [
                        'valid' => true,
                        'mime_type' => $mimeType,
                        'extension' => $extension,
                        'error' => null,
                    ];
                }
            }
        }

        // Get allowed MIME types for error message
        $allowedMimes = [];
        foreach ($categories as $category) {
            $categoryMimes = $this->getMimeTypeMappings($category);
            $allowedMimes = array_merge($allowedMimes, array_keys($categoryMimes));
        }

        return [
            'valid' => false,
            'mime_type' => $mimeType,
            'extension' => $extension,
            'error' => sprintf(
                'File type "%s" (detected as %s) is not allowed. Allowed types: %s',
                $extension,
                $mimeType,
                implode(', ', array_unique($allowedMimes))
            ),
        ];
    }

    /**
     * Validate file size
     *
     * @param int $fileSizeInBytes
     * @param string|int $maxSize Maximum size (e.g., '10M', '1024K', or int in KB)
     * @return array{valid: bool, size_kb: float, limit_kb: int, error: string|null}
     */
    public function validateSize(int $fileSizeInBytes, string|int $maxSize): array
    {
        $sizeInKb = $fileSizeInBytes / 1024;
        $limitInKb = $this->parseSizeToKb($maxSize);

        if ($sizeInKb <= $limitInKb) {
            return [
                'valid' => true,
                'size_kb' => round($sizeInKb, 2),
                'limit_kb' => $limitInKb,
                'error' => null,
            ];
        }

        return [
            'valid' => false,
            'size_kb' => round($sizeInKb, 2),
            'limit_kb' => $limitInKb,
            'error' => sprintf(
                'File size (%.2f KB) exceeds maximum allowed size (%d KB or %.2f MB)',
                $sizeInKb,
                $limitInKb,
                $limitInKb / 1024
            ),
        ];
    }

    /**
     * Validate file size by category
     *
     * @param int $fileSizeInBytes
     * @param string $category
     * @return array{valid: bool, size_kb: float, limit_kb: int, error: string|null}
     */
    public function validateSizeByCategory(int $fileSizeInBytes, string $category = 'default'): array
    {
        $maxSize = $this->getSizeLimit($category);
        return $this->validateSize($fileSizeInBytes, $maxSize);
    }

    /**
     * Comprehensive file upload validation
     *
     * @param array $file File array from $_FILES or similar
     * @param array $options Validation options:
     *                       - allowed_categories: array|string (default: ['images'])
     *                       - max_size: int|string in KB or with suffix (default: from category)
     *                       - check_dangerous: bool (default: true)
     *                       - check_mime: bool (default: true)
     * @return array{valid: bool, errors: array, mime_type: string|null, size_kb: float}
     */
    public function validateUpload(array $file, array $options = []): array
    {
        $defaults = [
            'allowed_categories' => ['images'],
            'max_size' => null,
            'check_dangerous' => true,
            'check_mime' => true,
        ];
        $options = array_merge($defaults, $options);

        $errors = [];
        $mimeType = null;
        $sizeInKb = 0;

        // Check if file was uploaded successfully
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            if (isset($file['error'])) {
                $errors[] = $this->getUploadErrorMessage($file['error']);
            } else {
                $errors[] = 'File was not uploaded properly';
            }

            return [
                'valid' => false,
                'errors' => $errors,
                'mime_type' => null,
                'size_kb' => 0,
            ];
        }

        $tmpPath = $file['tmp_name'];
        $fileName = $file['name'] ?? basename($tmpPath);
        $fileSize = $file['size'] ?? 0;
        $sizeInKb = $fileSize / 1024;

        // Check for dangerous files
        if ($options['check_dangerous']) {
            $filesUtils = new Files();
            if ($filesUtils->is_dangerous_file($fileName)) {
                $errors[] = 'This file type is not allowed for security reasons';
                \Log::warning('Attempted upload of dangerous file', [
                    'filename' => $fileName,
                    'ip' => \request()->ip(),
                ]);
            }
        }

        // Validate MIME type
        if ($options['check_mime'] && empty($errors)) {
            $mimeResult = $this->validateMimeType($tmpPath, $options['allowed_categories']);
            $mimeType = $mimeResult['mime_type'];

            if (!$mimeResult['valid']) {
                $errors[] = $mimeResult['error'];
                \Log::warning('MIME type validation failed', [
                    'filename' => $fileName,
                    'mime_type' => $mimeType,
                    'error' => $mimeResult['error'],
                ]);
            }
        }

        // Validate size
        if (empty($errors)) {
            $maxSize = $options['max_size'];

            // If max_size is not specified, use category limit
            if ($maxSize === null) {
                $categories = is_array($options['allowed_categories'])
                    ? $options['allowed_categories']
                    : [$options['allowed_categories']];
                $category = $categories[0] ?? 'default';
                $maxSize = $this->getSizeLimit($category);
            }

            $sizeResult = $this->validateSize($fileSize, $maxSize);

            if (!$sizeResult['valid']) {
                $errors[] = $sizeResult['error'];
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'mime_type' => $mimeType,
            'size_kb' => round($sizeInKb, 2),
        ];
    }

    /**
     * Get MIME type of file
     *
     * @param string $filePath
     * @return string|null
     */
    public function getMimeType(string $filePath): ?string
    {
        if (!file_exists($filePath)) {
            return null;
        }

        // Try finfo first (most reliable)
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $mimeType = finfo_file($finfo, $filePath);
                finfo_close($finfo);

                if ($mimeType && $mimeType !== 'application/octet-stream') {
                    return $mimeType;
                }
            }
        }

        // Fallback to mime_content_type
        if (function_exists('mime_content_type')) {
            $mimeType = mime_content_type($filePath);
            if ($mimeType) {
                return $mimeType;
            }
        }

        // Fallback to extension-based detection
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mappings = $this->getMimeTypeMappings('all');

        foreach ($mappings as $mime => $extensions) {
            if (in_array($extension, $extensions, true)) {
                return $mime;
            }
        }

        return null;
    }

    /**
     * Parse human-readable size to KB
     *
     * @param string|int $size
     * @return int
     */
    protected function parseSizeToKb(string|int $size): int
    {
        if (is_numeric($size)) {
            return (int) $size;
        }

        $size = trim($size);
        $lastChar = strtolower(substr($size, -1));
        $value = (int) substr($size, 0, -1);

        return match ($lastChar) {
            'g' => $value * 1024 * 1024,
            'm' => $value * 1024,
            'k' => $value,
            default => (int) $size,
        };
    }

    /**
     * Get human-readable upload error message
     *
     * @param int $errorCode
     * @return string
     */
    protected function getUploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive specified in the HTML form',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension',
            default => 'Unknown upload error',
        };
    }

    /**
     * Get validation rules for Laravel Validator
     *
     * @param string|array $allowedCategories
     * @param string|int|null $maxSize
     * @return array
     */
    public function getValidationRules(string|array $allowedCategories = ['images'], string|int|null $maxSize = null): array
    {
        $rules = [];

        // Determine max size
        if ($maxSize === null) {
            $categories = is_array($allowedCategories) ? $allowedCategories : [$allowedCategories];
            $category = $categories[0] ?? 'default';
            $maxSizeKb = $this->getSizeLimit($category);
        } else {
            $maxSizeKb = $this->parseSizeToKb($maxSize);
        }

        $rules['max'] = $maxSizeKb;

        // Build MIME types list
        $categories = is_array($allowedCategories) ? $allowedCategories : [$allowedCategories];
        $mimes = [];
        foreach ($categories as $category) {
            $categoryMimes = $this->getMimeTypeMappings($category);
            $mimes = array_merge($mimes, array_keys($categoryMimes));
        }

        if (!empty($mimes)) {
            $rules['mimetypes'] = implode(',', array_unique($mimes));
        }

        return $rules;
    }

    /**
     * Validate file extension matches MIME type
     *
     * @param string $filePath
     * @return array{valid: bool, expected_extensions: array, actual_extension: string, error: string|null}
     */
    public function validateExtensionMatchesMimeType(string $filePath): array
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeType = $this->getMimeType($filePath);

        if (!$mimeType) {
            return [
                'valid' => false,
                'expected_extensions' => [],
                'actual_extension' => $extension,
                'error' => 'Could not determine MIME type',
            ];
        }

        $mappings = $this->getMimeTypeMappings('all');

        if (isset($mappings[$mimeType])) {
            $expectedExtensions = $mappings[$mimeType];

            if (in_array($extension, $expectedExtensions, true)) {
                return [
                    'valid' => true,
                    'expected_extensions' => $expectedExtensions,
                    'actual_extension' => $extension,
                    'error' => null,
                ];
            }

            return [
                'valid' => false,
                'expected_extensions' => $expectedExtensions,
                'actual_extension' => $extension,
                'error' => sprintf(
                    'Extension ".%s" does not match detected MIME type "%s". Expected extensions: %s',
                    $extension,
                    $mimeType,
                    implode(', ', $expectedExtensions)
                ),
            ];
        }

        return [
            'valid' => true, // Unknown MIME type, let it pass with warning
            'expected_extensions' => [],
            'actual_extension' => $extension,
            'error' => null,
        ];
    }

    /**
     * Get all allowed MIME types as flat array
     *
     * @param string|array $categories
     * @return array
     */
    public function getAllowedMimeTypes(string|array $categories = ['images']): array
    {
        $categories = is_array($categories) ? $categories : [$categories];
        $mimes = [];

        foreach ($categories as $category) {
            $categoryMimes = $this->getMimeTypeMappings($category);
            $mimes = array_merge($mimes, array_keys($categoryMimes));
        }

        return array_unique($mimes);
    }

    /**
     * Check if file is an image based on MIME type
     *
     * @param string $filePath
     * @return bool
     */
    public function isImage(string $filePath): bool
    {
        $mimeType = $this->getMimeType($filePath);
        return $mimeType !== null && str_starts_with($mimeType, 'image/');
    }

    /**
     * Check if file is a video based on MIME type
     *
     * @param string $filePath
     * @return bool
     */
    public function isVideo(string $filePath): bool
    {
        $mimeType = $this->getMimeType($filePath);
        return $mimeType !== null && str_starts_with($mimeType, 'video/');
    }

    /**
     * Check if file is an audio based on MIME type
     *
     * @param string $filePath
     * @return bool
     */
    public function isAudio(string $filePath): bool
    {
        $mimeType = $this->getMimeType($filePath);
        return $mimeType !== null && str_starts_with($mimeType, 'audio/');
    }
}
