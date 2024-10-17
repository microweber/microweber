<?php

namespace Modules\Audio\Microweber;

use MicroweberPackages\Module\Abstract\BaseModule;

class AudioModule extends BaseModule
{

    public string $title = 'Audio module';
    public string $type = 'audio';

    public function getIcon()
    {
        return '<i class="mdi mdi-cube-outline"></i>';
    }
}
