<?php

declare(strict_types=1);

namespace Modules\Ai\Services;

use MicroweberPackages\AiTools\Services\GoogleTrendsService as BaseGoogleTrendsService;

/**
 * Google Trends Service - Backward Compatibility Layer
 *
 * This class extends the new microweber-packages/ai-tools GoogleTrendsService
 * to maintain backward compatibility with existing code.
 *
 * @deprecated Use MicroweberPackages\AiTools\Services\GoogleTrendsService instead
 */
class GoogleTrendsService extends BaseGoogleTrendsService
{
    // All functionality is inherited from the base class
    // This stub exists only for backward compatibility
}
