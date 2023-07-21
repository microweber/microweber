<?php

namespace MicroweberPackages\Livewire\Mechanisms\HandleRequests;


use Livewire\Mechanisms\HandleRequests\HandleRequests;

class MwHandleRequests extends HandleRequests
{


    function getUpdateUri()
    {
         $url = (string) str($this->updateRoute->uri)->start('/');
         $url = ltrim($url,'/');
        return site_url($url);
    }


}
