<?php

namespace MicroweberPackages\Filesystem\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array   copyDirectory(string $source, string $destination)
 * @method static void    removeDirectory(string $dirPath)
 * @method static bool    removeDirRecursive(string $directory, bool $empty = true)
 * @method static bool    mkdirRecursive(string $pathname)
 * @method static array   md5Dir(string $path)
 * @method static array|false directoryMap(string $sourceDir, int $directoryDepth = 0, bool $hidden = false, bool $fullPath = false)
 * @method static array|false rglob(string $pattern = '*', int $flags = 0, string $path = '')
 * @method static string  normalizePath(string $path, bool $slashIt = true)
 * @method static string  reduceDoubleSlashes(string $str)
 * @method static string  getFileExtension(string $pathToFile)
 * @method static string  noExt(string $filename)
 * @method static string  fileSizeNice(int|float $size)
 * @method static \Symfony\Component\HttpFoundation\BinaryFileResponse downloadResponse(string $filename)
 * @method static array   getDangerousExtensions()
 * @method static bool    isDangerousFile(string $fileName)
 * @method static bool    isAllowedFile(string $fileName)
 * @method static array|string getAllowedExtensionsForUpload(string $fileTypes = 'images', bool $returnAsArray = false)
 * @method static string|null sanitizeSvg(string $dirtySVG)
 * @method static bool    checkIfSvgIsValid(string $dirtySVG)
 *
 * @see \MicroweberPackages\Filesystem\FilesystemService
 */
class MwFilesystem extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'mw_filesystem';
    }
}