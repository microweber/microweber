<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry;

use MicroweberPackages\ModuleRegistry\Traits\ManagesContent;
use MicroweberPackages\ModuleRegistry\Traits\ManagesModules;
use MicroweberPackages\ModuleRegistry\Traits\ManagesUrl;

/**
 * Central registry for Microweber Live Edit modules.
 *
 * Bound as `microweber` and as {@see ModuleRegistryManager} in the container.
 * Use the {@see Facades\ModuleRegistry} facade (or legacy {@see Facades\Microweber}).
 */
class ModuleRegistryManager
{
    use ManagesModules;
    use ManagesContent;
    use ManagesUrl;
}
