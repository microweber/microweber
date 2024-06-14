<?php

namespace MicroweberPackages\Content\tests;

use MicroweberPackages\Content\Http\Livewire\Admin\ContentBulkOptions;
use MicroweberPackages\Content\Http\Livewire\Admin\ContentList;
use MicroweberPackages\Notification\tests\UserLivewireComponentsAccessTest;

class ContentLivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $componentsList = [
        ContentBulkOptions::class,
        ContentList::class
    ];
}
