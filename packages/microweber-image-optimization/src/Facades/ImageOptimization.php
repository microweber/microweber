<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;

/**
 * @method static array<string, mixed>|null convertToWebp(string $sourcePath, array<string, mixed> $options = [])
 * @method static string getWebpOrOriginal(string $sourcePath, array<string, mixed> $options = [])
 * @method static string generateLazyImage(string $src, ?string $alt = null, array<string, mixed> $attributes = [])
 * @method static string generateResponsiveImage(string $src, array<int|string, int|string> $sizes, ?string $alt = null, array<string, mixed> $attributes = [])
 * @method static string getOptimizedUrl(string $src, ?int $width = null, ?int $height = null, bool $allowWebp = true)
 * @method static bool isWebpSupported()
 * @method static bool isWebpEnabled()
 * @method static bool isLazyLoadingEnabled()
 * @method static bool clientSupportsWebp()
 * @method static int clearWebpCache()
 * @method static array{total_files: int, total_size: int, total_size_human: string, enabled: bool, supported: bool, cache_path: string} getStatistics()
 * @method static array<string, mixed> getConfig()
 * @method static string getCacheFullPath()
 * @method static string resolveFullPath(string $path)
 *
 * @see ImageOptimizationService
 */
class ImageOptimization extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ImageOptimizationService::class;
    }
}
