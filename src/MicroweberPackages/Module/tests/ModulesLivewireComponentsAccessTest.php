<?php

namespace MicroweberPackages\Module\tests;

use MicroweberPackages\Module\Http\Livewire\Admin\AskForModuleUninstallModal;
use MicroweberPackages\Module\Http\Livewire\Admin\ListModules;
use MicroweberPackages\User\tests\UserLivewireComponentsAccessTest;

class ModulesLivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $componentsList = [
        ListModules::class,
        AskForModuleUninstallModal::class,
    ];
}
