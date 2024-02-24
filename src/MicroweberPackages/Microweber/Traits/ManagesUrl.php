<?php

namespace MicroweberPackages\Microweber\Traits;

trait ManagesUrl
{
    public function siteUrl($path = false)
    {
        return app()->url_manager->site($path);
    }
     public function siteHostname(){
        return app()->url_manager->hostname();
     }



}
