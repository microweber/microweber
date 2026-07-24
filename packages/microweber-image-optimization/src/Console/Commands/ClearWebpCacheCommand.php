<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;

class ClearWebpCacheCommand extends Command
{
    protected $signature = 'image-optimization:clear-cache';

    protected $description = 'Clear all generated WebP images from the image optimization cache';

    public function handle(ImageOptimizationService $service): int
    {
        $count = $service->clearWebpCache();
        $this->info("Deleted {$count} WebP cache file(s).");

        return self::SUCCESS;
    }
}
