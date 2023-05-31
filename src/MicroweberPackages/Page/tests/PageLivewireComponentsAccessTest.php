<?php

namespace MicroweberPackages\Page\tests;


use MicroweberPackages\Page\Http\Livewire\Admin\PagesList;
use MicroweberPackages\User\tests\UserLivewireComponentsAccessTest;

class PageLivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $componentsList = [
        PagesList::class,
    ];
}
