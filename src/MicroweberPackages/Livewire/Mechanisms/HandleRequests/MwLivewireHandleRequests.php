<?php

namespace MicroweberPackages\Livewire\Mechanisms\HandleRequests;

use Livewire\Mechanisms\HandleRequests\HandleRequests;

class MwLivewireHandleRequests extends HandleRequests
{
    public function getUpdateUri()
    {
        $original = parent::getUpdateUri();
        if ($original == '/livewire/update') {
            $original = ltrim($original, '/');
            $url = site_url($original);
            return $url;
        }
        return $original;
    }
}
