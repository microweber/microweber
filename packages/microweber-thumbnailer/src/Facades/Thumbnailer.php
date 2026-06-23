<?php

namespace MicroweberPackages\Thumbnailer\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Thumbnailer\ThumbnailGenerator;

/**
 * @method static string|null generate(string $srcPath, int $width = 200, ?int $height = null, $crop = null)
 * @method static string pixum(int $width = 150, int $height = 0)
 * @method static void outputImage(string $path)
 * @method static string getThumbnailsDir()
 * @method static bool isWebpSupported()
 *
 * @see \MicroweberPackages\Thumbnailer\ThumbnailGenerator
 */
class Thumbnailer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ThumbnailGenerator::class;
    }
}