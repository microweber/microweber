<?php

namespace MicroweberPackages\LiveEdit\tests;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;
use MicroweberPackages\LiveEdit\Http\Livewire\ModuleTemplateSelectComponent;
use MicroweberPackages\User\tests\UserLivewireComponentsAccessTest;

class LiveEditLivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $componentsList = [
        ModuleSettingsComponent::class,
        ModuleTemplateSelectComponent::class,
    ];
}

