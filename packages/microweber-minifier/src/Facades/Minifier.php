<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Minifier\Services\MinifierService;

/**
 * @method static string minifyJs(string $js, array<string, mixed> $options = [])
 * @method static string minifyCss(string $css, array<string, mixed> $options = [])
 * @method static bool isEnabled()
 * @method static bool isJsEnabled()
 * @method static bool isCssEnabled()
 * @method static array<string, mixed> getStatistics()
 * @method static array{js: array{ok: bool, original_len: int, minified_len: int, ratio: float}, css: array{ok: bool, original_len: int, minified_len: int, ratio: float}} selfTest()
 *
 * @see MinifierService
 */
class Minifier extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MinifierService::class;
    }
}
