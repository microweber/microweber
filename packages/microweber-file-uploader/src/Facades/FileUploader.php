<?php

namespace MicroweberPackages\FileUploader\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\FileUploader\FileUploaderService;

/**
 * FileUploader facade — greppable public API for file uploads.
 *
 * @method static \MicroweberPackages\FileUploader\Validation\FileValidationService validator()
 * @method static \MicroweberPackages\FileUploader\Support\ImageProcessor imageProcessor()
 * @method static \MicroweberPackages\FileUploader\Support\FilenameSanitizer filenameSanitizer()
 * @method static array upload(\Illuminate\Http\Request $request, array $options = [])
 * @method static string getDefaultUploadPath()
 * @method static void cleanupTempFiles(string $targetDir, string $currentFilePath)
 * @method static string humanFilesize(int $bytes, int $decimals = 2)
 * @method static array errorResponse(int $code, string $message, int $httpStatus = 401)
 *
 * @see \MicroweberPackages\FileUploader\FileUploaderService
 * @mixin \MicroweberPackages\FileUploader\FileUploaderService
 */
class FileUploader extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FileUploaderService::class;
    }
}
