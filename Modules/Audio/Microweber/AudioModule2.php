<?php

namespace Modules\Audio\Microweber;

use Livewire\Component;
use MicroweberPackages\Microweber\Contracts\MicroweberModule;
use MicroweberPackages\Module\Abstract\BaseModule;

class AudioModule2   implements MicroweberModule
{

    public string $settingsModule =  \Modules\Audio\Microweber\AudioModuleSettings::class;
    public string $name = 'Audio';
    public string $type = 'audio';
    public string $description = 'Audio module';
    public string $icon = 'mdi mdi-cube-outline';
    public string $categories = 'media, music';
    public int $position = 2;



    public function render()
    {
        return 'Audio index';
    }

}
