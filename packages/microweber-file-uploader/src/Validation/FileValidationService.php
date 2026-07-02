<?php

namespace MicroweberPackages\FileUploader\Validation;

/**
 * File Upload Validation Service
 *
 * Provides comprehensive validation for file uploads including:
 * - MIME type validation
 * - File size limits
 * - Extension validation
 * - Dangerous file detection
 * - Extension-MIME mismatch detection
 */
class FileValidationService
{
    protected array $sizeLimits;
    protected array $mimeTypeMappings;
    protected array $dangerousExtensions;
    protected array $allowedExtensions;

    public function __construct()
    {
        $this->sizeLimits = config('file-uploader.size_limits', [
            'images'    => 10240,
            'videos'    => 102400,
            'audios'    => 51200,
            'documents' => 20480,
            'archives'  => 102400,
            'files'     => 10240,
            'default'   => 10240,
        ]);

        $this->mimeTypeMappings = config('file-uploader.mime_type_mappings', []);

        $this->dangerousExtensions = config('file-uploader.dangerous_extensions', [
            'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'php8',
            'exe', 'msi', 'sh', 'bat', 'vbs', 'cmd', 'com', 'htaccess',
            'html', 'htm', 'shtml', 'xhtml', 'js', 'jsp', 'jspx',
            'pl', 'cgi', 'rb', 'py', 'asp', 'aspx', 'lnk',
        ]);

        $this->allowedExtensions = config('file-uploader.allowed_extensions', []);
    }

    /**
     * Get the size limit for a category in KB.
     */
    public function getSizeLimit(string $category = 'default'): int
    {
        // Check for legacy config key (backward compatibility)
        $configKey = "media.upload_limits.{$category}";
        $configuredLimit = config($configKey);
        if ($configuredLimit !== null && is_numeric($configuredLimit)) {
            return (int) $configuredLimit;
        }

        return $this->sizeLimits[$category] ?? $this->sizeLimits['default'] ?? 10240;
    }

    /**
     * Set custom size limits.
     */
    public function setSizeLimits(array $limits): self
    {
        $this->sizeLimits = array_merge($this->sizeLimits, $limits);
        return $this;
    }

    /**
     * Get MIME type mappings for a category.
     */
    public function getMimeTypeMappings(string $category = 'all'): array
    {
        if ($category === 'all') {
            $merged = [];
            foreach ($this->mimeTypeMappings as $cat => $mappings) {
                foreach ($mappings as $mime => $exts) {
                    if (isset($merged[$mime])) {
                        $merged[$mime] = array_unique(array_merge($merged[$mime], $exts));
                    } else {
                        $merged[$mime] = $exts;
                    }
                }
            }
            return $merged;
        }

        return $this->mimeTypeMappings[$category] ?? [];
    }

    /**
     * Get the list of dangerous file extensions.
     */
    public function getDangerousExtensions(): array
    {
        return $this->dangerousExtensions;
    }

    /**
     * Check if a file extension is dangerous.
     */
    public function isDangerousExtension(string $fileName): bool
    {
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        return in_array($ext, $this->dangerousExtensions, true);
    }

    /**
     * Check if a file extension is allowed based on category or all allowed extensions.
     */
    public function isAllowedExtension(string $fileName, ?array $allowedTypes = null): bool
    {
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // First check: never allow dangerous extensions
        if ($this->isDangerousExtension($fileName)) {
            return false;
        }

        // If specific types are provided, check only those
        if ($allowedTypes !== null) {
            return in_array($ext, $allowedTypes, true);
        }

        // Check against all allowed extension categories
        foreach ($this->allowedExtensions as $category => $extensions) {
            if (in_array($ext, $extensions, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get allowed extensions for a file type category.
     */
    public function getAllowedExtensionsForCategory(string $fileTypes = 'images', bool $returnAsArray = false)
    {
        $allowed = '';

        switch (strtolower($fileTypes)) {
            case 'img':
            case 'image':
            case 'images':
            case 'media':
                $allowed = implode(',', $this->allowedExtensions['images'] ?? ['png', 'gif', 'jpg', 'jpeg', 'tiff', 'bmp', 'webp', 'ico', 'svg']);
                break;
            case 'audio':
            case 'audios':
                $allowed = implode(',', $this->allowedExtensions['audios'] ?? ['mp3', 'mp4', 'ogg', 'wav', 'flac']);
                break;
            case 'video':
            case 'videos':
                $allowed = implode(',', $this->allowedExtensions['videos'] ?? ['avi', 'asf', 'mpg', 'mpeg', 'mp4', 'flv', 'mkv', 'webm', 'ogg', 'ogv', '3gp', '3g2', 'wma', 'mov', 'wmv']);
                break;
            case 'file':
            case 'files':
                $allowed = implode(',', $this->allowedExtensions['files'] ?? ['css', 'json', 'zip', 'gzip', 'psd', 'csv', '7z', 'rar', 'gz', 'woff', 'woff2', 'ttf', 'oet', 'otf', 'cur', 'ico']);
                break;
            case 'documents':
            case 'doc':
                $allowed = implode(',', $this->allowedExtensions['documents'] ?? ['doc', 'docx', 'pdf', 'odt', 'rtf', 'txt', 'pps', 'ppt', 'pptx', 'xls', 'xlsx', 'xml']);
                break;
            case 'archives':
            case 'arc':
            case 'arch':
                $allowed = implode(',', $this->allowedExtensions['archives'] ?? ['zip', 'zipx', 'gzip', 'rar', 'gz', '7z', 'cbr', 'tar.gz']);
                break;
            case 'all':
            case '*':
                $allowed = '*';
                break;
            default:
                $allowed = $fileTypes;
        }

        if ($returnAsArray) {
            if ($allowed === '' || $allowed === '*') {
                return $allowed === '*' ? ['*'] : [];
            }
            $arr = array_filter(array_unique(explode(',', $allowed)));
            return array_values($arr);
        }

        return $allowed;
    }

    /**
     * Get MIME type of a file.
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
     * Validate file MIME type against allowed categories.
     *
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

        // If specific MIME type string is provided directly
        if (is_string($allowedCategories) && str_contains($allowedCategories, '/')) {
            if ($mimeType === $allowedCategories) {
                return ['valid' => true, 'mime_type' => $mimeType, 'extension' => $extension, 'error' => null];
            }
        }

        $categories = is_array($allowedCategories) ? $allowedCategories : [$allowedCategories];

        foreach ($categories as $category) {
            $categoryMimes = $this->getMimeTypeMappings($category);
            if (isset($categoryMimes[$mimeType])) {
                if (in_array($extension, $categoryMimes[$mimeType], true)) {
                    return ['valid' => true, 'mime_type' => $mimeType, 'extension' => $extension, 'error' => null];
                }
            }
        }

        // Build error message
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
     * Validate file size.
     *
     * @return array{valid: bool, size_kb: float, limit_kb: int, error: string|null}
     */
    public function validateSize(int $fileSizeInBytes, string|int $maxSize): array
    {
        $sizeInKb = $fileSizeInBytes / 1024;
        $limitInKb = $this->parseSizeToKb($maxSize);

        if ($sizeInKb <= $limitInKb) {
            return ['valid' => true, 'size_kb' => round($sizeInKb, 2), 'limit_kb' => $limitInKb, 'error' => null];
        }

        return [
            'valid' => false,
            'size_kb' => round($sizeInKb, 2),
            'limit_kb' => $limitInKb,
            'error' => sprintf(
                'File size (%.2f KB) exceeds maximum allowed size (%d KB or %.2f MB)',
                $sizeInKb, $limitInKb, $limitInKb / 1024
            ),
        ];
    }

    /**
     * Validate file size by category.
     *
     * @return array{valid: bool, size_kb: float, limit_kb: int, error: string|null}
     */
    public function validateSizeByCategory(int $fileSizeInBytes, string $category = 'default'): array
    {
        $maxSize = $this->getSizeLimit($category);
        return $this->validateSize($fileSizeInBytes, $maxSize);
    }

    /**
     * Determine file category from extension.
     */
    public function detectCategoryFromExtension(string $extension): string
    {
        $ext = strtolower($extension);
        foreach ($this->allowedExtensions as $category => $extensions) {
            if (in_array($ext, $extensions, true)) {
                return $category;
            }
        }
        return 'files';
    }

    /**
     * Comprehensive file upload validation.
     *
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
            return ['valid' => false, 'errors' => $errors, 'mime_type' => null, 'size_kb' => 0];
        }

        $tmpPath = $file['tmp_name'];
        $fileName = $file['name'] ?? basename($tmpPath);
        $fileSize = $file['size'] ?? 0;
        $sizeInKb = $fileSize / 1024;

        // Check for dangerous files
        if ($options['check_dangerous']) {
            if ($this->isDangerousExtension($fileName)) {
                $errors[] = 'This file type is not allowed for security reasons';
            }
        }

        // Validate MIME type
        if ($options['check_mime'] && empty($errors)) {
            $mimeResult = $this->validateMimeType($tmpPath, $options['allowed_categories']);
            $mimeType = $mimeResult['mime_type'];
            if (!$mimeResult['valid']) {
                $errors[] = $mimeResult['error'];
            }
        }

        // Validate size
        if (empty($errors)) {
            $maxSize = $options['max_size'];
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

        return ['valid' => empty($errors), 'errors' => $errors, 'mime_type' => $mimeType, 'size_kb' => round($sizeInKb, 2)];
    }

    /**
     * Validate file extension matches MIME type.
     */
    public function validateExtensionMatchesMimeType(string $filePath): array
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeType = $this->getMimeType($filePath);

        if (!$mimeType) {
            return ['valid' => false, 'expected_extensions' => [], 'actual_extension' => $extension, 'error' => 'Could not determine MIME type'];
        }

        $mappings = $this->getMimeTypeMappings('all');
        if (isset($mappings[$mimeType])) {
            $expectedExtensions = $mappings[$mimeType];
            if (in_array($extension, $expectedExtensions, true)) {
                return ['valid' => true, 'expected_extensions' => $expectedExtensions, 'actual_extension' => $extension, 'error' => null];
            }
            return [
                'valid' => false,
                'expected_extensions' => $expectedExtensions,
                'actual_extension' => $extension,
                'error' => sprintf('Extension ".%s" does not match detected MIME type "%s". Expected extensions: %s', $extension, $mimeType, implode(', ', $expectedExtensions)),
            ];
        }

        return ['valid' => true, 'expected_extensions' => [], 'actual_extension' => $extension, 'error' => null];
    }

    /**
     * Get all allowed MIME types as a flat array.
     */
    public function getAllowedMimeTypes(string|array $categories = ['images']): array
    {
        $categories = is_array($categories) ? $categories : [$categories];
        $mimes = [];
        foreach ($categories as $category) {
            $categoryMimes = $this->getMimeTypeMappings($category);
            $mimes = array_merge($mimes, array_keys($categoryMimes));
        }
        return array_values(array_unique($mimes));
    }

    /**
     * Get Laravel validation rules for a given category/size.
     */
    public function getValidationRules(string|array $allowedCategories = ['images'], string|int|null $maxSize = null): array
    {
        $rules = [];
        if ($maxSize === null) {
            $categories = is_array($allowedCategories) ? $allowedCategories : [$allowedCategories];
            $category = $categories[0] ?? 'default';
            $maxSizeKb = $this->getSizeLimit($category);
        } else {
            $maxSizeKb = $this->parseSizeToKb($maxSize);
        }
        $rules['max'] = $maxSizeKb;

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
     * Check if file is an image based on MIME type.
     */
    public function isImage(string $filePath): bool
    {
        $mimeType = $this->getMimeType($filePath);
        return $mimeType !== null && str_starts_with($mimeType, 'image/');
    }

    /**
     * Check if file is a video based on MIME type.
     */
    public function isVideo(string $filePath): bool
    {
        $mimeType = $this->getMimeType($filePath);
        return $mimeType !== null && str_starts_with($mimeType, 'video/');
    }

    /**
     * Check if file is an audio based on MIME type.
     */
    public function isAudio(string $filePath): bool
    {
        $mimeType = $this->getMimeType($filePath);
        return $mimeType !== null && str_starts_with($mimeType, 'audio/');
    }

    /**
     * Parse human-readable size to KB.
     */
    public function parseSizeToKb(string|int $size): int
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
     * Get human-readable upload error message.
     */
    public function getUploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive specified in the HTML form',
            UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION  => 'File upload stopped by extension',
            default               => 'Unknown upload error',
        };
    }
}