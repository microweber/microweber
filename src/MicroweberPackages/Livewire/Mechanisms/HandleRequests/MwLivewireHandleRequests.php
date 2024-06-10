<?php

namespace MicroweberPackages\Livewire\Mechanisms\HandleRequests;

use Livewire\Mechanisms\HandleRequests\HandleRequests;

class MwLivewireHandleRequests extends HandleRequests
{
    public function getUpdateUri()
    {
        return site_url('livewire/update');
    }
}
