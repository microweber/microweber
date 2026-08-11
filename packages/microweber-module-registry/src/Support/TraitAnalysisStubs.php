<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Support;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
use MicroweberPackages\ModuleRegistry\Traits\HasMicroweberModuleLiveEditHandleAction;
use MicroweberPackages\ModuleRegistry\Traits\HasMicroweberModuleSettings;

/**
 * Internal stubs so optional traits are analysed by PHPStan (level max requires
 * traits to be used). Not for application use.
 *
 * @internal
 */
final class TraitAnalysisStubs extends BaseModule
{
    use HasMicroweberModuleSettings;
    use HasMicroweberModuleLiveEditHandleAction;
}
