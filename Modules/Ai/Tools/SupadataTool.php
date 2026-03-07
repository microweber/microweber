<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use MicroweberPackages\AiTools\Tools\External\SupadataTool as BaseSupadataTool;

/**
 * Supadata Tool - Backward Compatibility Layer
 *
 * This class extends the new microweber-packages/ai-tools SupadataTool
 * to maintain backward compatibility with existing code.
 *
 * @deprecated Use MicroweberPackages\AiTools\Tools\External\SupadataTool instead
 */
class SupadataTool extends BaseSupadataTool
{
    // All functionality is inherited from the base class
    // This stub exists only for backward compatibility
}
