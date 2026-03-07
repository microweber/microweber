<?php

declare(strict_types=1);

namespace Modules\Ai\Services;

use MicroweberPackages\AiTools\Services\AmazonScraperService as BaseAmazonScraperService;

/**
 * Amazon Scraper Service - Backward Compatibility Layer
 *
 * This class extends the new microweber-packages/ai-tools AmazonScraperService
 * to maintain backward compatibility with existing code.
 *
 * @deprecated Use MicroweberPackages\AiTools\Services\AmazonScraperService instead
 */
class AmazonScraperService extends BaseAmazonScraperService
{
    // All functionality is inherited from the base class
    // This stub exists only for backward compatibility
}
