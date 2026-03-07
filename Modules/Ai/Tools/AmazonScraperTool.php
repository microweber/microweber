<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use MicroweberPackages\AiTools\Tools\External\AmazonScraperTool as BaseAmazonScraperTool;

/**
 * Amazon Scraper Tool - Backward Compatibility Layer
 *
 * This class extends the new microweber-packages/ai-tools AmazonScraperTool
 * to maintain backward compatibility with existing code.
 *
 * @deprecated Use MicroweberPackages\AiTools\Tools\External\AmazonScraperTool instead
 */
class AmazonScraperTool extends BaseAmazonScraperTool
{
    // All functionality is inherited from the base class
    // This stub exists only for backward compatibility
}
