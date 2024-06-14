<?php

namespace MicroweberPackages\Template\tests;


use MicroweberPackages\Template\Http\Livewire\Admin\AdminTemplateUpdateModal;
use MicroweberPackages\Notification\tests\UserLivewireComponentsAccessTest;

class TemplateLivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $componentsList = [
        AdminTemplateUpdateModal::class,
    ];
}
