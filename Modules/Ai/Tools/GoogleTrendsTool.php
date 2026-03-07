<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use MicroweberPackages\AiTools\Tools\External\GoogleTrendsTool as BaseGoogleTrendsTool;

/**
 * Google Trends Tool - Backward Compatibility Layer
 *
 * This class extends the new microweber-packages/ai-tools GoogleTrendsTool
 * to maintain backward compatibility with existing code.
 *
 * @deprecated Use MicroweberPackages\AiTools\Tools\External\GoogleTrendsTool instead
 */
class GoogleTrendsTool extends BaseGoogleTrendsTool
{
    // All functionality is inherited from the base class
    // This stub exists only for backward compatibility
}
