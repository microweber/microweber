<?php

namespace MicroweberPackages\FileUploader;

use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use MicroweberPackages\FileUploader\Support\FilenameSanitizer;
use MicroweberPackages\FileUploader\Support\ImageProcessor;
use MicroweberPackages\FileUploader\Validation\FileValidationService;

/**
 * File Uploader Service
 *
 * Standalone Laravel service for handling file uploads with:
 * - Chunked upload support (plupload-compatible)
 * - MIME type and extension validation
 * - File size limits
 * - Security checks (dangerous extensions, EXIF stripping, SVG sanitization)
 * - Automatic image resizing
 * - Configurable via config/file-uploader.php
 *
 * Usage:
 *   FileUploader::upload($request);
 *   FileUploader::validator()->validateMimeType($path, ['images']);
 */
class FileUploaderService
{
    protected FileValidationService $validator;
    protected ImageProcessor $imageProcessor;
    protected FilenameSanitizer $filenameSanitizer;

    protected string $disk;
    protected string $uploadPath;

    public function __construct(
        ?FileValidationService $validator = null,
        ?ImageProcessor $imageProcessor = null,
        ?FilenameSanitizer $filenameSanitizer = null
    ) {
        $this->validator = $validator ?? new FileValidationService();
        $this->imageProcessor = $imageProcessor ?? new ImageProcessor();
        $this->filenameSanitizer = $filenameSanitizer ?? new FilenameSanitizer();
        $this->disk = config('file-uploader.disk', 'public');
        $this->uploadPath = config('file-uploader.upload_path', 'uploads');
    }

    /**
     * Get the validation service.
     */
    public function validator(): FileValidationService
    {
        return $this->validator;
    }

    /**
     * Get the image processor.
     */
    public function imageProcessor(): ImageProcessor
    {
        return $this->imageProcessor;
    }

    /**
     * Get the filename sanitizer.
     */
    public function filenameSanitizer(): FilenameSanitizer
    {
        return $this->filenameSanitizer;
    }

    /**
     * Handle a file upload from a request.
     *
     * @param Request $request
     * @param array $options {
     *   @type string $targetDir        Target directory for upload
     *   @type array  $allowedFileTypes  Allowed file extensions (empty = all safe types)
     *   @type bool   $autoResize       Whether to auto-resize images
     *   @type int    $maxDimension     Max dimension for auto-resize
     *   @type string $disk             Storage disk to use
     *   @type bool   $returnPath       Whether to return path info in response
     * }
     * @return array Upload result with keys: success, file_path, file_url, file_name, error, etc.
     */
    public function upload(Request $request, array $options = []): array
    {

        $defaults = [
            'targetDir' => null,
            'allowedFileTypes' => [],
            'autoResize' => config('file-uploader.auto_resize_images', false),
            'maxDimension' => config('file-uploader.auto_resize_max_dimension', 1980),
            'autoResizeThreshold' => config('file-uploader.auto_resize_threshold', 2000000),
            'disk' => $this->disk,
            'returnPath' => true,
            'storeToDisk' => true,
        ];
        $options = array_merge($defaults, $options);

        // Get the file name from the request
        $fileName = $request->input('name', '');
        if (empty($fileName) && $request->hasFile('file')) {
            $fileName = $request->file('file')->getClientOriginalName();
        }

        if (empty($fileName)) {
            return $this->errorResponse(100, 'No file name provided');
        }

        // Validate extension
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Always block dangerous extensions
        if ($this->validator->isDangerousExtension($fileName)) {
            return $this->errorResponse(100, 'You cannot upload scripts or executable files');
        }

        // Check against allowed file types
        if (!empty($options['allowedFileTypes'])) {
            if (!in_array($extension, $options['allowedFileTypes'])) {
                return $this->errorResponse(100, 'You cannot upload scripts or executable files');
            }
        } else {
            if (!$this->validator->isAllowedExtension($fileName)) {
                return $this->errorResponse(100, 'You cannot upload scripts or executable files');
            }
        }

        // Sanitize filename
        $fileName = $this->filenameSanitizer->sanitize($fileName);

        // Determine target directory
        $targetDir = $options['targetDir'];
        if (empty($targetDir)) {
            $targetDir = $this->getDefaultUploadPath();
        }

        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        // Create index.html for directory listing protection
        $indexFile = $targetDir . DIRECTORY_SEPARATOR . 'index.html';
        if (!is_file($indexFile)) {
            @touch($indexFile);
        }

        // Handle chunked upload
        $chunk = (int) $request->input('chunk', 0);
        $chunks = (int) $request->input('chunks', 0);

        $fileNameUniq = date('ymdhis') . uniqid() . $fileName;

        // If not chunked and file exists, use unique name
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        if ($chunks < 2 && file_exists($filePath)) {
            $fileName = $fileNameUniq;
            $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        }

        $filePathUniq = $targetDir . DIRECTORY_SEPARATOR . $fileNameUniq;

        // Cleanup old temp files
        $this->cleanupTempFiles($targetDir, $filePath);

        // Track upload size in cache
        $this->trackUploadSize($request, $fileName);

        // Write file data (chunked or full)
        $writeResult = $this->writeUploadedFile($request, $filePath, $chunk);
        if ($writeResult !== true) {
            return $writeResult; // error response
        }

        // If not all chunks received yet, return partial success
        if ($chunks > 0 && $chunk < $chunks - 1) {
            return [
                'success' => true,
                'file_is_uploaded' => false,
                'name' => $fileName,
                'chunk' => $chunk,
                'chunks' => $chunks,
            ];
        }

        // All chunks received (or single upload) - finalize
        $newFile = $filePath;
        $newFile = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $newFile);
        if (is_file($newFile) && $chunks > 0) {
            $newFile = $filePathUniq;
        }

        // Rename from .part to final
        $partFile = "{$filePath}.part";
        if (is_file($partFile)) {
            rename($partFile, $newFile);
            $filePath = $newFile;
        }

        // Post-upload validation and processing
        $result = $this->postProcessUpload($filePath, $extension, $options);
        if (isset($result['error'])) {
            return $result;
        }

        // Store to disk if requested
        $fileUrl = null;
        $isFullyUploaded = true;

        if ($options['storeToDisk'] && $options['disk']) {
            $storeResult = $this->storeToDisk($filePath, $options);
            if (isset($storeResult['error'])) {
                return $storeResult;
            }
            $fileUrl = $storeResult['url'] ?? null;
        }

        // Build response
        $response = [
            'success' => true,
            'file_is_uploaded' => $isFullyUploaded,
            'name' => basename($filePath),
        ];

        if ($options['returnPath']) {
            $response['src'] = $fileUrl ?? $filePath;
        }

        if (isset($result['extra'])) {
            $response = array_merge($response, $result['extra']);
        }

        return $response;
    }

    /**
     * Get the default upload path.
     */
    public function getDefaultUploadPath(): string
    {
        if (function_exists('media_uploads_path')) {
            $path = media_uploads_path();
            return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        }
        return storage_path('app/public/' . $this->uploadPath);
    }

    /**
     * Clean up old temporary .part files.
     */
    public function cleanupTempFiles(string $targetDir, string $currentFilePath): void
    {
        $maxAge = config('file-uploader.temp_file_max_age', 18000);

        if (!is_dir($targetDir)) {
            return;
        }

        $dir = opendir($targetDir);
        if (!$dir) {
            return;
        }

        while (($file = readdir($dir)) !== false) {
            $tmpPath = $targetDir . DIRECTORY_SEPARATOR . $file;
            if (preg_match('/\.part$/', $file)
                && (filemtime($tmpPath) < time() - $maxAge)
                && ($tmpPath !== "{$currentFilePath}.part")
            ) {
                @unlink($tmpPath);
            }
        }
        closedir($dir);
    }

    /**
     * Track upload size in cache for rate limiting.
     */
    protected function trackUploadSize(Request $request, string $fileName): void
    {
        if (!isset($_SERVER['CONTENT_LENGTH']) || !isset($_FILES['file'])) {
            return;
        }

        $slug = preg_replace('/[^a-z0-9\-_]/', '', strtolower($fileName));
        $cacheKey = 'upload_size_' . $slug . '_' . $request->ip();
        $uplSize = (int) $_SERVER['CONTENT_LENGTH'];

        if (app()->bound('cache')) {
            $cached = Cache::get($cacheKey, 0);
            $uplSize += (int) $cached;
            Cache::put($cacheKey, $uplSize, 30 * 60);
        }
    }

    /**
     * Write the uploaded file (supports chunked and full uploads).
     *
     * @return true|array True on success, error array on failure.
     */
    protected function writeUploadedFile(Request $request, string $filePath, int $chunk)
    {
        $contentType = $_SERVER['HTTP_CONTENT_TYPE'] ?? $_SERVER['CONTENT_TYPE'] ?? null;

        if ($contentType !== null) {
            if (strpos($contentType, 'multipart') !== false) {
                if (isset($_FILES['file']['error']) && $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                    return $this->errorResponse(101, $this->validator->getUploadErrorMessage($_FILES['file']['error']));
                }
            }

            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                $out = fopen("{$filePath}.part", $chunk == 0 ? 'wb' : 'ab');
                if (!$out) {
                    return $this->errorResponse(102, 'Failed to open output stream.');
                }

                $in = fopen($_FILES['file']['tmp_name'], 'rb');
                if (!$in) {
                    fclose($out);
                    return $this->errorResponse(101, 'Failed to open input stream.');
                }

                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
                fclose($in);
                fclose($out);
                @unlink($_FILES['file']['tmp_name']);
            } else {
                // Try to handle from request file
                if ($request->hasFile('file')) {
                    $uploadedFile = $request->file('file');
                    $out = fopen("{$filePath}.part", $chunk == 0 ? 'wb' : 'ab');
                    if (!$out) {
                        return $this->errorResponse(102, 'Failed to open output stream.');
                    }
                    $in = fopen($uploadedFile->getRealPath(), 'rb');
                    if (!$in) {
                        fclose($out);
                        return $this->errorResponse(101, 'Failed to open input stream.');
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    fclose($in);
                    fclose($out);
                } else {
                    return $this->errorResponse(103, 'Failed to move uploaded file.');
                }
            }
        } else {
            // Read from php://input
            $out = fopen("{$filePath}.part", $chunk == 0 ? 'wb' : 'ab');
            if (!$out) {
                return $this->errorResponse(102, 'Failed to open output stream.');
            }
            $in = fopen('php://input', 'rb');
            if (!$in) {
                fclose($out);
                return $this->errorResponse(101, 'Failed to open input stream.');
            }
            while ($buff = fread($in, 4096)) {
                fwrite($out, $buff);
            }
            fclose($in);
            fclose($out);
        }

        return true;
    }

    /**
     * Post-process the uploaded file: MIME validation, size check, image processing.
     *
     * @return array Result with optional 'error' or 'extra' keys.
     */
    protected function postProcessUpload(string $filePath, string $extension, array $options): array
    {
        if (!is_file($filePath)) {
            return $this->errorResponse(107, 'Upload failed - file not found');
        }

        $ext = strtolower($extension);
        $extra = [];

        // Determine file category
        $fileCategory = $this->validator->detectCategoryFromExtension($ext);

        // Validate MIME type
        $mimeResult = $this->validator->validateMimeType($filePath, [$fileCategory]);
        if (!$mimeResult['valid']) {
            @unlink($filePath);
            return $this->errorResponse(108, $mimeResult['error']);
        }

        // Validate file size
        $fileSize = filesize($filePath);
        $sizeResult = $this->validator->validateSizeByCategory($fileSize, $fileCategory);
        if (!$sizeResult['valid']) {
            @unlink($filePath);
            return $this->errorResponse(109, $sizeResult['error']);
        }

        // Legacy dangerous MIME check
        $dangerousExts = $this->validator->getDangerousExtensions();
        if (function_exists('finfo_open') && function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = @finfo_file($finfo, $filePath);
            if ($mime) {
                $uplMimeExt = explode('/', $mime);
                $uplMimeExt = end($uplMimeExt);
                $uplMimeExt = explode('-', $uplMimeExt);
                $uplMimeExt = end($uplMimeExt);
                $uplMimeExt = strtolower($uplMimeExt);
                if (in_array($uplMimeExt, $dangerousExts)) {
                    @unlink($filePath);
                    finfo_close($finfo);
                    return $this->errorResponse(101, 'Cannot upload mime type ' . $uplMimeExt);
                }
            }
            finfo_close($finfo);
        }

        // Process images (strip EXIF, sanitize SVG, etc.)
        $imageExts = ['gif', 'jpg', 'jpeg', 'jpe', 'png', 'svg'];
        if (in_array($ext, $imageExts)) {
            $valid = $this->imageProcessor->processImage($filePath, $ext);
            if (!$valid) {
                @unlink($filePath);
                return $this->errorResponse(107, 'File is not a valid image', 422);
            }
        }

        // Handle image-specific metadata and auto-resize
        $resizableExts = ['gif', 'jpg', 'jpeg', 'png'];
        if (in_array($ext, $resizableExts)) {
            try {
                $size = getimagesize($filePath);
                $extra['file_size'] = $fileSize;
                $extra['file_size_human'] = $this->humanFilesize($fileSize);
                $extra['image_size'] = $size;

                $autoResizeThreshold = $options['autoResizeThreshold'] ?? 2000000;

                if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $extra['automatic_image_resize_is_enabled'] = $options['autoResize'];

                    if (!$options['autoResize'] && $fileSize > $autoResizeThreshold) {
                        $extra['ask_user_to_enable_auto_resizing'] = 1;
                        $extra['ask_user_to_enable_auto_resizing_filesize'] = $fileSize;
                    }

                    if ($options['autoResize'] && $fileSize > $autoResizeThreshold) {
                        $resizeResult = $this->imageProcessor->autoResize(
                            $filePath,
                            $ext,
                            $options['maxDimension'] ?? 1980
                        );
                        if ($resizeResult['resized']) {
                            $extra['image_was_auto_resized'] = 1;
                            $extra['image_was_auto_resized_msg'] = $resizeResult['message'];
                        }
                    }
                }
            } catch (\Exception $e) {
                @unlink($filePath);
                return $this->errorResponse(107, 'File is not a valid image', 422);
            }
        }

        return ['extra' => $extra];
    }

    /**
     * Store the uploaded file to the configured storage disk.
     */
    protected function storeToDisk(string $filePath, array $options): array
    {
        $diskName = $options['disk'] ?? $this->disk;
        $storage = Storage::disk($diskName);

        $relativePath = $options['storagePath'] ?? $this->uploadPath;
        $fileName = basename($filePath);

        // Validate the directory exists on storage
        if (!$storage->directoryExists($relativePath)) {
            // Try to create it
            $storage->makeDirectory($relativePath);
        }

        $storedPath = $storage->putFileAs($relativePath, new File($filePath), $fileName);

        // Remove local temp file
        if (is_file($filePath)) {
            @unlink($filePath);
        }

        $url = $storage->url($storedPath);

        return [
            'path' => $storedPath,
            'url' => $url,
        ];
    }

    /**
     * Format a file size in bytes to human-readable format.
     */
    public function humanFilesize(int $bytes, int $decimals = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen((string) $bytes) - 1) / 3);
        $factor = min($factor, count($units) - 1);
        return sprintf("%.{$decimals}f %s", $bytes / pow(1024, $factor), $units[$factor]);
    }

    /**
     * Create a standardized error response.
     */
    public function errorResponse(int $code, string $message, int $httpStatus = 401): array
    {
        return [
            'success' => false,
            'error' => true,
            'error_code' => $code,
            'error_message' => $message,
            'http_status' => $httpStatus,
            'jsonrpc' => '2.0',
        ];
    }
}
