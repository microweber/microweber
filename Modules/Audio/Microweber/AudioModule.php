<?php

namespace Modules\Audio\Microweber;

use MicroweberPackages\Module\Abstract\BaseModule;

class AudioModule
{

    public string $settingsModule = \Modules\Audio\Microweber\AudioModuleSettings::class;
    public string $indexModule = \Modules\Audio\Microweber\AudioModuleIndex::class;

    public function getModuleDetails()
    {
        return [
            'name' => 'Audio',
            'position' => 2,
            'description' => 'Audio module',
            'icon' => 'mdi mdi-cube-outline',
            'categories' => 'media, music',
        ];
    }


    public string $title = 'Audio module';

    public string $type = 'audio';

    public function getIcon()
    {
        return '<i class="mdi mdi-cube-outline"></i>';
    }





//    public function getModuleDefinition()
//    {
//        return [
//            'index' => AudioModuleIndex::class,
//            'edit' => AudioModuleSettings::class,
//            'admin' => AudioModuleAdminResource::class,
//        ];
//    }

}
