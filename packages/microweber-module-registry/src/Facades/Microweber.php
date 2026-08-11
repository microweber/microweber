<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Facades;

/**
 * Backward-compatible alias for {@see ModuleRegistry}.
 *
 * Prefer ModuleRegistry in new code. Existing CMS modules and call sites
 * that import Microweber::module(...) continue to work unchanged.
 *
 * @see ModuleRegistry
 * @see \MicroweberPackages\ModuleRegistry\ModuleRegistryManager
 */
class Microweber extends ModuleRegistry
{
}
