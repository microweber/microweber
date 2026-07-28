<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Support;

use MicroweberPackages\View\Contracts\ModuleProcessorInterface;

/**
 * Default module processor for standalone Laravel apps (no-op pass-through).
 */
class NullModuleProcessor implements ModuleProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(string $html, array $options = []): string
    {
        return $html;
    }
}
