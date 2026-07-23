<?php

namespace MicroweberPackages\MediaPixum\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\MediaPixum\PixumGenerator;

/**
 * @method static string generate(int $width, int $height = 0)
 * @method static string url(int $width, int $height = 0)
 * @method static string getCachePath()
 *
 * @see \MicroweberPackages\MediaPixum\PixumGenerator
 */
class Pixum extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PixumGenerator::class;
    }
}