<?php

declare(strict_types=1);

namespace MicroweberPackages\View;

/**
 * Attribute used to declare a named view component and its package.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class ViewComponentName
{
    public function __construct(
        public string $name,
        public string $package,
    ) {
    }
}
