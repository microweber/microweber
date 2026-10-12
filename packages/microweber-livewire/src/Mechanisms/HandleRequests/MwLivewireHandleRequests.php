<?php

namespace MicroweberPackages\Livewire\Mechanisms\HandleRequests;

use Livewire\Mechanisms\HandleRequests\HandleRequests;

class MwLivewireHandleRequests extends HandleRequests
{
    public function getUpdateUri(): string
    {
        return parent::getUpdateUri();
    }
}
